<?php

class Codex_Fields {

    public function __construct() {
        $this->init();
    }
    public function init() {
        $field_types = self::field_types();


        if (!empty($field_types)) {
            foreach ($field_types as $field) {

                if (file_exists(CODEX_PATH . '/includes/fields/class-' . $field['type'] . '.php')) {
                    require_once(CODEX_PATH . '/includes/fields/class-' . $field['type'] . '.php');
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
                "name"          => "GPS",
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
}

new Codex_Fields();
