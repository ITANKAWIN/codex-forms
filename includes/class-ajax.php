<?php
if (!defined('ABSPATH')) {
    exit();
}

class Codex_AJAX {

    function __construct() {

        add_action('wp_ajax_new_form', array($this, 'new_form'));

        add_action('wp_ajax_delete_form', array($this, 'delete_form'));

        add_action('wp_ajax_save_form', array($this, 'save_form'));

        add_action('wp_ajax_entry_value', array($this, 'form_entry'));

        // load all entry id from id form
        add_action('wp_ajax_load_entry', array($this, 'load_entry'));

        // load all value in entry id
        add_action('wp_ajax_load_entry_value', array($this, 'load_entry_value'));

        // save edit entry
        add_action('wp_ajax_save_edit_entry', array($this, 'save_edit_entry'));
    }

    function new_form() {

        $form_title = sanitize_text_field($_POST['title']);
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

    function load_entry() {

        $form_id = sanitize_text_field($_POST['id']);

        if (empty($form_id)) {
            wp_send_json_error();
        }

        $entrys = Codex_form_DB::get_entry($form_id);

        wp_send_json_success($entrys);
    }

    function load_entry_value() {

        $entry_id = sanitize_text_field($_POST['id']);

        if (empty($entry_id)) {
            wp_send_json_error();
        }

        $entrys = Codex_form_DB::get_entry_meta($entry_id);

        wp_send_json_success($entrys);
    }

    function save_edit_entry() {

        $id_entry = json_decode(stripslashes($_POST['id_entry']));

        $entry_data = json_decode(stripslashes($_POST['entry_value']));

        $data      = [];

        if (!is_null($entry_data) && $entry_data) {
            foreach ($entry_data as $post_input_data) {

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

        $test = [];

        foreach ($data as $field => $value) {
            $val = Codex_form_DB::save_entry_value($id_entry, $field, $value);
            array_push($test, $val);
        }

        wp_send_json_success($test);
    }
}

new Codex_AJAX();
