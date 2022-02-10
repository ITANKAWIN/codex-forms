<?php

class Codex_Fields {

    public function __construct() {
        $this->init();
    }
    public function init() {
        $field_types = self::field_types();


        if (!empty($field_types)) {
            foreach ($field_types as $field) {

                if (file_exists(CODEX_PATH . '/includes/fields/Standard/class-' . $field['type'] . '.php')) {
                    require_once(CODEX_PATH . '/includes/fields/Standard/class-' . $field['type'] . '.php');
                }
            }
        }

        // Load class field template register
        $field_types = self::field_types_register();


        if (!empty($field_types)) {
            foreach ($field_types as $field) {

                if (file_exists(CODEX_PATH . '/includes/fields/Register/class-' . $field['type'] . '.php')) {
                    require_once(CODEX_PATH . '/includes/fields/Register/class-' . $field['type'] . '.php');
                }
            }
        }

        // Load class field template login
        $field_types = self::field_types_login();


        if (!empty($field_types)) {
            foreach ($field_types as $field) {

                if (file_exists(CODEX_PATH . '/includes/fields/Login/class-' . $field['type'] . '.php')) {
                    require_once(CODEX_PATH . '/includes/fields/Login/class-' . $field['type'] . '.php');
                }
            }
        }
    }

    public static function groups() {
        $groups = array(
            'Basic',
            'Select',
            'File',
            'Special',
        );

        return $groups;
    }

    public static function groups_register() {
        $groups = array(
            'Basic',
        );

        return $groups;
    }

    public static function groups_login() {
        $groups = array(
            'Basic',
        );

        return $groups;
    }

    public static function field_types() {

        $fields = array(
            'text' => array(
                "name"          => "Text",
                "group"         => "Basic",
                "type"          => "text",
                "description"   => "input string or integer one line",
                "icon"          => "icon font"
            ),
            'select' => array(
                "name"          => "Select",
                "group"         => "Select",
                "type"          => "select",
                "description"   => "input string or integer one line",
                "icon"          => "icon caret square down outline"
            ),
            'password' => array(
                "name"          => "Password",
                "group"         => "Basic",
                "type"          => "password",
                "description"   => "input string or integer one line",
                "icon"          => "icon font"
            ),
            'number' => array(
                "name"          => "Number",
                "group"         => "Basic",
                "type"          => "number",
                "description"   => "input string or integer one line",
                "icon"          => "icon hashtag"
            ),
            'email' => array(
                "name"          => "Email",
                "group"         => "Basic",
                "type"          => "email",
                "description"   => "input string or integer one line",
                "icon"          => "icon envelope outline"
            ),
            'date' => array(
                "name"          => "Date",
                "group"         => "Select",
                "type"          => "date",
                "description"   => "input string or integer one line",
                "icon"          => "icon calendar alternate outline"
            ),
            'radio' => array(
                "name"          => "Radio",
                "group"         => "Select",
                "type"          => "radio",
                "description"   => "input string or integer one line",
                "icon"          => "icon dot circle outline"
            ),
            'checkbox' => array(
                "name"          => "Checkbox",
                "group"         => "Select",
                "type"          => "checkbox",
                "description"   => "input string or integer one line",
                "icon"          => "icon check square outline"
            ),
            'button' => array(
                "name"          => "Button",
                "group"         => "Basic",
                "type"          => "button",
                "description"   => "input string or integer one line",
                "icon"          => "icon square outline"
            ),
            'file' => array(
                "name"          => "File",
                "group"         => "File",
                "type"          => "file",
                "description"   => "input string or integer one line",
                "icon"          => "icon upload"
            ),
            'textarea' => array(
                "name"          => "TextArea",
                "group"         => "Basic",
                "type"          => "textarea",
                "description"   => "input string or integer one line",
                "icon"          => "icon paragraph"
            ),
            'divider' => array(
                "name"          => "Divider",
                "group"         => "Special",
                "type"          => "divider",
                "description"   => "input string or integer one line",
                "icon"          => "icon arrows alternate horizontal"
            ),
            'thai-provinces' => array(
                "name"          => "Thai Provinces",
                "group"         => "Special",
                "type"          => "thai-provinces",
                "description"   => "input string or integer one line",
                "icon"          => "icon map marker alternate"
            ),
            'Pin-GPS' => array(
                "name"          => "Google Maps",
                "group"         => "Special",
                "type"          => "Pin-GPS",
                "description"   => "input string or integer one line",
                "icon"          => "icon map marker alternate"
            ),
            'Star-Rating' => array(
                "name"          => "Star Rating",
                "group"         => "Special",
                "type"          => "Star-Rating",
                "description"   => "input string or integer one line",
                "icon"          => "icon star outline"
            ),
            'Scale-Rating' => array(
                "name"          => "Scale Rating",
                "group"         => "Special",
                "type"          => "Scale-Rating",
                "description"   => "input string or integer one line",
                "icon"          => "icon ellipsis horizontal"
            ),
            'Image' => array(
                "name"          => "Image",
                "group"         => "Special",
                "type"          => "Image",
                "description"   => "input string or integer one line",
                "icon"          => "icon image outline"
            ),
        );

        return $fields;
    }

    public static function field_types_register() {
        $field = array(
            'Email_User' => array(
                "name"          => "Email",
                "group"         => "Basic",
                "type"          => "email_user",
                "description"   => "input string or integer one line",
                "icon"          => "icon image outline"
            ),
            'Password_User' => array(
                "name"          => "Password",
                "group"         => "Basic",
                "type"          => "password_user",
                "description"   => "input string or integer one line",
                "icon"          => "icon image outline"
            ),
            'Con_Password_User' => array(
                "name"          => "Confirm Password",
                "group"         => "Basic",
                "type"          => "con_password_user",
                "description"   => "input string or integer one line",
                "icon"          => "icon image outline"
            ),
            'Username' => array(
                "name"          => "Username",
                "group"         => "Basic",
                "type"          => "username",
                "description"   => "input string or integer one line",
                "icon"          => "icon image outline"
            ),
            'Nickname' => array(
                "name"          => "Nickname",
                "group"         => "Basic",
                "type"          => "nickname",
                "description"   => "input string or integer one line",
                "icon"          => "icon image outline"
            ),
            'First_Name' => array(
                "name"          => "First Name",
                "group"         => "Basic",
                "type"          => "first_name",
                "description"   => "input string or integer one line",
                "icon"          => "icon image outline"
            ),
            'Last_Name' => array(
                "name"          => "Last Name",
                "group"         => "Basic",
                "type"          => "last_name",
                "description"   => "input string or integer one line",
                "icon"          => "icon image outline"
            ),
            'Website' => array(
                "name"          => "Website",
                "group"         => "Basic",
                "type"          => "website",
                "description"   => "input string or integer one line",
                "icon"          => "icon image outline"
            ),
            'Display_Name' => array(
                "name"          => "Display Name",
                "group"         => "Basic",
                "type"          => "display_name",
                "description"   => "input string or integer one line",
                "icon"          => "icon image outline"
            ),
            'User_Bio' => array(
                "name"          => "User Bio",
                "group"         => "Basic",
                "type"          => "user_bio",
                "description"   => "input string or integer one line",
                "icon"          => "icon image outline"
            ),
            'Submit' => array(
                "name"          => "Submit",
                "group"         => "Basic",
                "type"          => "submit",
                "description"   => "input string or integer one line",
                "icon"          => "icon image outline"
            ),
        );

        return $field;
    }

    public static function field_types_login() {
        $field = array(
            'Username_login' => array(
                "name"          => "Username",
                "group"         => "Basic",
                "type"          => "username_login",
                "description"   => "input string or integer one line",
                "icon"          => "icon image outline"
            ),
            'Email_login' => array(
                "name"          => "Email",
                "group"         => "Basic",
                "type"          => "email_login",
                "description"   => "input string or integer one line",
                "icon"          => "icon image outline"
            ),
            'Password_login' => array(
                "name"          => "Password",
                "group"         => "Basic",
                "type"          => "password_login",
                "description"   => "input string or integer one line",
                "icon"          => "icon image outline"
            ),
            'Submit_login' => array(
                "name"          => "Submit",
                "group"         => "Basic",
                "type"          => "submit_login",
                "description"   => "input string or integer one line",
                "icon"          => "icon image outline"
            ),
        );

        return $field;
    }
}

new Codex_Fields();
