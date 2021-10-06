<?php
if (!defined('ABSPATH')) {
    exit();
}

class Codex_AJAX {

    function __construct() {

        if (!class_exists('Codex_form_DB')) {
            require_once(CODEX_PATH . 'includes/db/class-db.php');
        }

        add_action('wp_ajax_new_form', array($this, 'new_form'));

        add_action('wp_ajax_delete_form', array($this, 'delete_form'));

        add_action('wp_ajax_save_form', array($this, 'save_form'));

        add_action('wp_ajax_entry_value', array($this, 'form_entry'));

        add_action('wp_ajax_load_entire', array($this, 'load_entire'));
    }

    function new_form() {

        $form_title = sanitize_text_field($_POST['title']);
        // $form_template = sanitize_text_field($_POST['template']);
        // $title_exists  = get_page_by_title($form_title, 'OBJECT', 'wpforms');

        if (empty($form_title)) {
            return false;
        }

        // default config form
        $data = array(
            'panels'    => 12,
            'panel'     => [],
            'fields'    => []
        );

        // Merge args and create the form.
        $form = array(
            'name'      => $form_title,
            'type'      => 'form',
            'config'    => wp_json_encode($data),
        );

        $form_id = Codex_form_DB::add_form($form);

        $arg = array(
            "ID" => $form_id,
            'redirect' => add_query_arg(
                array(
                    'view'    => 'edit',
                    'form_id' => $form_id,
                ),
                admin_url('admin.php?page=codex-forms')
            ),
        );

        wp_send_json_success($arg);
    }

    function delete_form() {

        $form_id = sanitize_text_field($_POST['id']);

        if (empty($form_id)) {
            wp_send_json_error();
        }

        $arg = array(
            'redirect' => add_query_arg(
                array(),
                admin_url('admin.php?page=codex-forms')
            ),
        );

        if (Codex_form_DB::delete_form($form_id)) {
            wp_send_json_success($arg);
        }
    }

    function save_form() {

        $form_id = sanitize_text_field($_POST['id']);

        if (empty($form_id)) {
            wp_send_json_error();
        }

        $form_title = sanitize_text_field($_POST['title']);

        $form_data = json_decode(stripslashes($_POST['data']));

        $data      = [
            'fields' => [],
        ];

        if (!is_null($form_data) && $form_data) {
            foreach ($form_data as $post_input_data) {

                // For input names that are arrays (e.g. `menu-item-db-id[3][4][5]`),

                // derive the array path keys via regex and set the value in $_POST.

                preg_match('#([^\[]*)(\[(.+)\])?#', $post_input_data->name, $matches);

                $array_bits = array($matches[1]);

                if (isset($matches[3])) {
                    $array_bits = array_merge($array_bits, explode('][', $matches[3]));
                }

                $new_post_data = [];

                // Build the new array value from leaf to trunk.
                for ($i = count($array_bits) - 1; $i >= 0; $i--) {
                    if ($i === count($array_bits) - 1) {
                        $new_post_data[$array_bits[$i]] = wp_slash($post_input_data->value);
                    } else {
                        $new_post_data = array(
                            $array_bits[$i] => $new_post_data,
                        );
                    }
                }
                $data = array_replace_recursive($data, $new_post_data);
            }
        }

        $form = array(
            'name' => $form_title,
            'config' => wp_json_encode($data, JSON_UNESCAPED_UNICODE),
        );

        Codex_form_DB::update_form($form_id, $form);

        wp_send_json_success();
    }

    function form_entry() {

        $form_data = json_decode(stripslashes($_POST['entry_value']));

        $data      = [];

        if (!is_null($form_data) && $form_data) {
            foreach ($form_data as $post_input_data) {

                // For input names that are arrays (e.g. `menu-item-db-id[3][4][5]`),

                // derive the array path keys via regex and set the value in $_POST.

                preg_match('#([^\[]*)(\[(.+)\])?#', $post_input_data->name, $matches);

                $array_bits = array($matches[1]);

                if (isset($matches[3])) {
                    $array_bits = array_merge($array_bits, explode('][', $matches[3]));
                }

                $new_post_data = [];

                // Build the new array value from leaf to trunk.
                for ($i = count($array_bits) - 1; $i >= 0; $i--) {
                    if ($i === count($array_bits) - 1) {
                        $new_post_data[$array_bits[$i]] = wp_slash($post_input_data->value);
                    } else {
                        $new_post_data = array(
                            $array_bits[$i] => $new_post_data,
                        );
                    }
                }
                $data = array_replace_recursive($data, $new_post_data);
            }
        }

        if (!empty($data['form_id'])) {
            $entry_id = Codex_form_DB::entry($data['form_id']);

            foreach ($data['field_id'] as $field => $value) {
                Codex_form_DB::entry_value($entry_id, $field, $value);
            }
        }

        wp_send_json_success();
    }

    function load_entire() {

        $form_id = sanitize_text_field($_POST['id']);

        if (empty($form_id)) {
            wp_send_json_error();
        }

        $entry_val = array();

        $entrys = Codex_form_DB::get_entry($form_id);

        // echo "<pre>";
        // print_r($entrys);
        // echo "</pre>";

        foreach ($entrys as $entry => $value) {
            // $entry_val[$entry['id']] = Codex_form_DB::get_entry_meta($entry['id']);
            echo $entry . " " . $value;
        }

        wp_send_json_success($entry_val);
    }
}

new Codex_AJAX();
