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

        // Submit template blank form
        add_action('wp_ajax_submit_form', array($this, 'submit_form'));
        // Submit template blank form on the Viewer-Facing Side
        add_action('wp_ajax_nopriv_submit_form', array($this, 'submit_form'));

        // Submit template login form
        add_action('wp_ajax_submit_form_login', array($this, 'submit_form_login'));
        // login form on the Viewer-Facing Side
        add_action('wp_ajax_nopriv_submit_form_login', array($this, 'submit_form_login'));

        // Submit template register form
        add_action('wp_ajax_submit_form_register', array($this, 'submit_form_register'));
        // register form on the Viewer-Facing Side
        add_action('wp_ajax_nopriv_submit_form_register', array($this, 'submit_form_register'));

        // load all entry id from id form
        add_action('wp_ajax_load_entry', array($this, 'load_entry'));

        // load all value in entry id
        add_action('wp_ajax_load_entry_value', array($this, 'load_entry_value'));

        // save edit entry
        add_action('wp_ajax_save_edit_entry', array($this, 'save_edit_entry'));

        // delete entry selected
        add_action('wp_ajax_delete_entry', array($this, 'delete_entry'));

        // export entry selected
        add_action('wp_ajax_export_entry', array($this, 'export_entry'));
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

    function submit_form() {

        if (empty($_POST['data'])) {
            wp_send_json_error();
        }

        $form_data = json_decode(stripslashes($_POST['data']));

        $data      = $this->form_to_array($form_data);


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

    function submit_form_login() {

        if (empty($_POST['data'])) {
            wp_send_json_error();
        }

        $form_data = json_decode(stripslashes($_POST['data']));

        $data      = $this->form_to_array($form_data);

        if (!empty($data['form_id'])) {
            // load form setting
            $form = Codex_form_DB::get_form_by_id($data['form_id']);
            $form_setting = json_decode(stripslashes($form->config), true, JSON_UNESCAPED_UNICODE);
        }

        $error_msg = array();

        if (isset($data["email"])) {
            // empty email
            if ($data["email"] != '') {
                $user = get_user_by('login', $data["email"]);

                if (!$user) {
                    array_push($error_msg, "Invalid email.");
                }
            } else {
                array_push($error_msg, "Please enter a email.");
            }
        }


        if (isset($data["username"])) {
            // empty username
            if ($data["username"] != '') {
                $user = get_user_by('email', $data["username"]);

                if (!$user) {
                    array_push($error_msg, "Invalid email.");
                }
            } else {
                array_push($error_msg, "Please enter a username.");
            }
        }

        // passwords do not match
        // check the user's login with their password
        if (!wp_check_password($data["password"], $user->user_pass, $user->ID)) {
            // if the password is incorrect for the specified user
            array_push($error_msg, "Incorrect password.");
        }

        if (empty($error_msg)) {
            wp_clear_auth_cookie();
            wp_set_current_user($user->ID); // Set the current user detail
            wp_set_auth_cookie($user->ID); // Set auth details in cookie

            wp_send_json_success($form_setting['setting']);
        } else {
            wp_send_json_error($form_setting['setting'] + array('error_msg' => $error_msg));
        }
    }

    function submit_form_register() {

        if (empty($_POST['data'])) {
            wp_send_json_error();
        }

        $form_data = json_decode(stripslashes($_POST['data']));

        $data      = $this->form_to_array($form_data);

        if (!empty($data['form_id'])) {
            // load form setting
            $form = Codex_form_DB::get_form_by_id($data['form_id']);
            $form_setting = json_decode(stripslashes($form->config), true, JSON_UNESCAPED_UNICODE);
        }

        $error_msg = array();

        if (username_exists($data["username"])) {
            array_push($error_msg, "Username already taken.");
        }

        if (!validate_username($data["username"])) {
            array_push($error_msg, "Invalid username.");
        }

        if ($data["username"] == '') {
            // empty username
            array_push($error_msg, "Please enter a username.");
        }

        if (!is_email($data["email"])) {
            //invalid email
            array_push($error_msg, "Invalid email.");
        }

        if (email_exists($data["email"])) {
            //Email address already registered
            array_push($error_msg, "Email already registered.");
        }

        if ($data["password"] == '') {
            // passwords do not match
            array_push($error_msg, "Please enter a password.");
        }

        if ($data["password"] != $data["con_password"]) {
            // passwords do not match
            array_push($error_msg, "Passwords do not match.");
        }

        if (empty($error_msg)) {
            $new_user_id = wp_insert_user(
                array(
                    'user_login'        => $data["username"],
                    'user_pass'         => $data["password"],
                    'user_email'        => $data["email"],
                    'first_name'        => $data["first_name"],
                    'last_name'         => $data["last_name"],
                    'user_registered'   => date('Y-m-d H:i:s'),
                    'role'              => 'subscriber',
                    'user_url'          => $data["website"],
                    'nickname'          => $data["nickname"],
                    'display_name'      => $data["display_name"],
                    'description'       => $data["user_bio"],
                )
            );

            if ($new_user_id) {
                wp_new_user_notification($new_user_id);

                wp_send_json_success($form_setting['setting']);
            }
        } else {
            wp_send_json_error($form_setting['setting'] + array('error_msg' => $error_msg));
        }
    }

    function form_to_array($form_data) {
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

        return $data;
    }

    function load_entry() {

        // load entry data
        $entry_id = sanitize_text_field($_POST['entry_id']);

        if (empty($entry_id)) {
            wp_send_json_error();
        }

        $entrys = Codex_form_DB::get_entry_by_form_id($entry_id);

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


    function delete_entry() {
        $query  = explode('&', $_POST['select']);
        $params = array();

        foreach ($query as $param) {
            // prevent notice on explode() if $param has no '='
            if (strpos($param, '=') === false) $param += '=';

            list($name, $value) = explode('=', $param, 2);
            $params[urldecode($name)][] = urldecode($value);
        }

        $data = Codex_form_DB::delete_entry('codex_form_entry', 'id', $params['id']);

        if ($data > 0) {
            $data = Codex_form_DB::delete_entry('codex_form_entry_meta', 'entry_id', $params['id']);
            if ($data > 0) {
                wp_send_json_success(array('action' => 'delete'));
            }
        }

        wp_send_json_error();
    }

    function export_entry() {
        wp_send_json_success(array('url' => '?export=' . $_POST['form_id'] . '&' . $_POST['select']));
    }
}

new Codex_AJAX();
