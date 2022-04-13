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
                "description"   => "Used to create a single line of text input boxes.",
                "icon"          => "icon font"
            ),
            'select' => array(
                "name"          => "Select",
                "group"         => "Select",
                "type"          => "select",
                "description"   => "Used to create a drop-down list box. There will be a down arrow to click on multiple drop down items.",
                "icon"          => "icon caret square down outline"
            ),
            'password' => array(
                "name"          => "Password",
                "group"         => "Basic",
                "type"          => "password",
                "description"   => "Used to create a password box, the typed item will be hidden in a black circle.",
                "icon"          => "icon font"
            ),
            'number' => array(
                "name"          => "Number",
                "group"         => "Basic",
                "type"          => "number",
                "description"   => "Used to create a number field.",
                "icon"          => "icon hashtag"
            ),
            'email' => array(
                "name"          => "Email",
                "group"         => "Basic",
                "type"          => "email",
                "description"   => "Use to create an email field.",
                "icon"          => "icon envelope outline"
            ),
            'date' => array(
                "name"          => "Date",
                "group"         => "Select",
                "type"          => "date",
                "description"   => "Used to create a date field.",
                "icon"          => "icon calendar alternate outline"
            ),
            'radio' => array(
                "name"          => "Radio",
                "group"         => "Select",
                "type"          => "radio",
                "description"   => "Used to create one of the options.",
                "icon"          => "icon dot circle outline"
            ),
            'checkbox' => array(
                "name"          => "Checkbox",
                "group"         => "Select",
                "type"          => "checkbox",
                "description"   => "Used to create the options more than one option, or not select at all.",
                "icon"          => "icon check square outline"
            ),
            'button' => array(
                "name"          => "Button",
                "group"         => "Basic",
                "type"          => "button",
                "description"   => "Used to create buttons",
                "icon"          => "icon square outline"
            ),
            'reset' => array(
                "name"          => "Reset",
                "group"         => "Basic",
                "type"          => "reset",
                "description"   => "Used to create button reset all value in any fields",
                "icon"          => "icon square outline"
            ),
            'file' => array(
                "name"          => "File",
                "group"         => "File",
                "type"          => "file",
                "description"   => "Used to add attachments with various extensions.",
                "icon"          => "icon upload"
            ),
            'textarea' => array(
                "name"          => "TextArea",
                "group"         => "Basic",
                "type"          => "textarea",
                "description"   => "Used to create a  long of text multi-line., mostly used for comment data, addresses.",
                "icon"          => "icon paragraph"
            ),
            'divider' => array(
                "name"          => "Divider",
                "group"         => "Special",
                "type"          => "divider",
                "description"   => "Used to create separators.",
                "icon"          => "icon arrows alternate horizontal"
            ),
            'thai-provinces' => array(
                "name"          => "Thai Provinces",
                "group"         => "Special",
                "type"          => "thai-provinces",
                "description"   => "Used to create a drop-down list box. Select all provinces in Thailand.",
                "icon"          => "icon map marker alternate"
            ),
            'Pin-GPS' => array(
                "name"          => "Google Maps",
                "group"         => "Special",
                "type"          => "Pin-GPS",
                "description"   => "Used to select a location on the Google Map.",
                "icon"          => "icon map marker alternate"
            ),
            'Star-Rating' => array(
                "name"          => "Star Rating",
                "group"         => "Special",
                "type"          => "Star-Rating",
                "description"   => "Used to create a star rating option.",
                "icon"          => "icon star outline"
            ),
            'Scale-Rating' => array(
                "name"          => "Scale Rating",
                "group"         => "Special",
                "type"          => "Scale-Rating",
                "description"   => "Used to create a scale rating option.",
                "icon"          => "icon ellipsis horizontal"
            ),
            'Image' => array(
                "name"          => "Image",
                "group"         => "Special",
                "type"          => "Image",
                "description"   => "Used to create a image type field.",
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
                "description"   => "Use to create an email field.",
                "icon"          => "icon envelope outline"
            ),
            'Password_User' => array(
                "name"          => "Password",
                "group"         => "Basic",
                "type"          => "password_user",
                "description"   => "Used to create a password box, the typed item will be hidden in a black circle.",
                "icon"          => "icon font"
            ),
            'Con_Password_User' => array(
                "name"          => "Confirm Password",
                "group"         => "Basic",
                "type"          => "con_password_user",
                "description"   => "Used to create a password confirm.",
                "icon"          => "icon font"
            ),
            'Username' => array(
                "name"          => "Username",
                "group"         => "Basic",
                "type"          => "username",
                "description"   => "Used to create a single line of text input boxes.",
                "icon"          => "icon user"
            ),
            'Nickname' => array(
                "name"          => "Nickname",
                "group"         => "Basic",
                "type"          => "nickname",
                "description"   => "Used to create a short name.",
                "icon"          => "icon user"
            ),
            'First_Name' => array(
                "name"          => "First Name",
                "group"         => "Basic",
                "type"          => "first_name",
                "description"   => "Used to create a input string or integer one line",
                "icon"          => "icon font"
            ),
            'Last_Name' => array(
                "name"          => "Last Name",
                "group"         => "Basic",
                "type"          => "last_name",
                "description"   => "Used to create a input string or integer one line",
                "icon"          => "icon font"
            ),
            'Website' => array(
                "name"          => "Website",
                "group"         => "Basic",
                "type"          => "website",
                "description"   => "Used to to build your website.",
                "icon"          => "icon globe"
            ),
            'Display_Name' => array(
                "name"          => "Display Name",
                "group"         => "Basic",
                "type"          => "display_name",
                "description"   => "Used to create a display Name",
                "icon"          => "icon id badge outline"
            ),
            'User_Bio' => array(
                "name"          => "User Bio",
                "group"         => "Basic",
                "type"          => "user_bio",
                "description"   => "Used to create a input string or integer one line",
                "icon"          => "icon user circle"
            ),
            'Submit' => array(
                "name"          => "Submit",
                "group"         => "Basic",
                "type"          => "submit",
                "description"   => "Used to create buttons",
                "icon"          => "icon square outline"
            ),
            'Reset' => array(
                "name"          => "Reset",
                "group"         => "Basic",
                "type"          => "reset",
                "description"   => "Used to create button reset all value in any fields",
                "icon"          => "icon square outline"
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
                "description"   => "Used to create a single line of text input boxes.",
                "icon"          => "icon user"
            ),
            'Email_login' => array(
                "name"          => "Email",
                "group"         => "Basic",
                "type"          => "email_login",
                "description"   => "Use to create an email field.",
                "icon"          => "icon envelope outline"
            ),
            'Password_login' => array(
                "name"          => "Password",
                "group"         => "Basic",
                "type"          => "password_login",
                "description"   => "Used to create a password box, the typed item will be hidden in a black circle.",
                "icon"          => "icon font"
            ),
            'Submit_login' => array(
                "name"          => "Submit",
                "group"         => "Basic",
                "type"          => "submit_login",
                "description"   => "Used to create buttons",
                "icon"          => "icon square outline"
            ),
            'Reset' => array(
                "name"          => "Reset",
                "group"         => "Basic",
                "type"          => "reset",
                "description"   => "Used to create button reset all value in any fields",
                "icon"          => "icon square outline"
            ),
        );

        return $field;
    }
}

new Codex_Fields();
