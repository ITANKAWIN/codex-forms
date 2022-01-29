<?php
if (!defined('ABSPATH')) {
    exit();
}

class Codex_AJAX {

    function __construct() {

        add_action('wp_ajax_new_form', array($this, 'new_form'));

        add_action('wp_ajax_duplicate_form', array($this, 'duplicate_form'));

        add_action('wp_ajax_delete_form', array($this, 'delete_form'));

        add_action('wp_ajax_save_form', array($this, 'save_form'));

        add_action('wp_ajax_entry_value', array($this, 'form_submit'));

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

        if (isset($_POST['data'])) {
            $data = $_POST['data'];
        } else {
            // default config form
            $data = array(
                'fields'    => [],
                'panels'    => 12,
                'panel'     => [],
                'setting'   => [
                    'template'  => 'blank',
                ],
            );
        }

        // select template default
        if (isset($_POST['template'])) {
            $data = apply_filters("codex_template_{$_POST['template']}", '');
        }

        // Merge args and create the form.
        $form = array(
            'name'      => $form_title,
            'type'      => 'form',
            'config'    => json_encode($data, JSON_UNESCAPED_UNICODE),
        );

        $form_id = Codex_form_DB::new_form($form);

        $arg = array(
            "ID" => $data,
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

    function duplicate_form() {

        $id = sanitize_text_field($_POST['id']);
        if (empty($id)) {
            return false;
        }

        $data = Codex_form_DB::get_form_by_id($id);

        // Merge args and create the form.
        $form = array(
            'name'      => $data->name . '-copy',
            'type'      => 'form',
            'config'    => $data->config,
        );

        $form_id = Codex_form_DB::new_form($form);

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

        $form_setting = json_decode(stripslashes($_POST['setting']));

        $form_data = json_decode(stripslashes($_POST['data']));

        $form_data = array_merge($form_data, $form_setting);

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

    function form_submit() {

        if (empty($_POST['data'])) {
            wp_send_json_error();
        }

        $form_data = json_decode(stripslashes($_POST['data']));

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

        if (!empty($_FILES['file'])) {
            $arr_img_ext = array('image/png', 'image/jpeg', 'image/jpg', 'image/gif');
            if (in_array($_FILES['file']['type'], $arr_img_ext)) {
                $upload = wp_upload_bits($_FILES["file"]["name"], null, file_get_contents($_FILES["file"]["tmp_name"]));
                $url_upload = $upload['url'];
            }

            $data = array_merge_recursive($data, array('field_id' => array($_POST['file_id'] => $url_upload)));
        }

        if (!empty($data['form_id'])) {
            $entry_id = Codex_form_DB::entry($data['form_id']);
            foreach ($data['field_id'] as $field => $value) {
                Codex_form_DB::entry_value($entry_id, $field, $value);
            }

            // load form setting
            $form = Codex_form_DB::get_form_by_id($data['form_id']);
            $form_setting = json_decode(stripslashes($form->config), true, JSON_UNESCAPED_UNICODE);
        }

        wp_send_json_success($form_setting['setting']);
    }

    function load_entry() {

        // load entry data
        $entry_id = sanitize_text_field($_POST['entry_id']);

        if (empty($entry_id)) {
            wp_send_json_error();
        }

        $entrys = Codex_form_DB::get_entry($entry_id);

        wp_send_json_success($entrys);
    }

    function load_entry_value() {

        // load entry data
        $entry_id = sanitize_text_field($_POST['entry_id']);

        if (empty($entry_id)) {
            wp_send_json_error();
        }

        $entrys = Codex_form_DB::get_entry_meta($entry_id);

        // load form data
        $form = Codex_form_DB::get_form_by_id($_POST['form_id']);

        $form_config = json_decode(stripslashes($form->config));

        $meta_val = [];

        $i = 0;

        // foreach name field to entry meta value
        foreach ($entrys as $key => $value) {
            $meta_val[$i] = $entrys[$key];

            // field name
            $meta_val[$i]->name = $form_config->fields->{$value->field_id}->name;
            $meta_val[$i]->type = $form_config->fields->{$value->field_id}->type;
            $i++;
        }

        wp_send_json_success($meta_val);
    }

    function save_edit_entry() {

        $id_entry = json_decode(stripslashes($_POST['entry_id']));

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
