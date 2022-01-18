<?php

class Codex_Fields {

    public function __construct() {
        $this->init();
    }
    public static function init() {
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
                "group"         => "Basic",
                "type"          => "text",
                "description"   => "input string or integer one line",
                "icon"          => "icon font"
            ),
            'select' => array(
                "group"         => "Select",
                "type"          => "select",
                "description"   => "input string or integer one line",
                "icon"          => "icon caret square down outline"
            ),
            'password' => array(
                "group"         => "Basic",
                "type"          => "password",
                "description"   => "input string or integer one line",
                "icon"          => "icon font"
            ),
            'number' => array(
                "group"         => "Basic",
                "type"          => "number",
                "description"   => "input string or integer one line",
                "icon"          => "icon hashtag"
            ),
            'email' => array(
                "group"         => "Basic",
                "type"          => "email",
                "description"   => "input string or integer one line",
                "icon"          => "icon envelope outline"
            ),
            'date' => array(
                "group"         => "Select",
                "type"          => "date",
                "description"   => "input string or integer one line",
                "icon"          => "icon calendar alternate outline"
            ),
            'radio' => array(
                "group"         => "Select",
                "type"          => "radio",
                "description"   => "input string or integer one line",
                "icon"          => "icon dot circle outline"
            ),
            'checkbox' => array(
                "group"         => "Select",
                "type"          => "checkbox",
                "description"   => "input string or integer one line",
                "icon"          => "icon check square outline"
            ),
            'button' => array(
                "group"         => "Basic",
                "type"          => "button",
                "description"   => "input string or integer one line",
                "icon"          => "icon square outline"
            ),
            'file' => array(
                "group"         => "File",
                "type"          => "file",
                "description"   => "input string or integer one line",
                "icon"          => "icon upload"
            ),
            'textarea' => array(
                "group"         => "Basic",
                "type"          => "textarea",
                "description"   => "input string or integer one line",
                "icon"          => "icon paragraph"
            ),
            'divider' => array(
                "group"         => "Special",
                "type"          => "divider",
                "description"   => "input string or integer one line",
                "icon"          => "icon arrows alternate horizontal"
            ),
            'thai-provinces' => array(
                "group"         => "Special",
                "type"          => "thai-provinces",
                "description"   => "input string or integer one line",
                "icon"          => "icon map marker alternate"
            ),
            'Pin-GPS' => array(
                "group"         => "Special",
                "type"          => "Pin-GPS",
                "description"   => "input string or integer one line",
                "icon"          => "icon map marker alternate"
            ),
            'Star-Rating' => array(
                "group"         => "Special",
                "type"          => "Star-Rating",
                "description"   => "input string or integer one line",
                "icon"          => "icon star outline"
            ),
            'Scale-Rating' => array(
                "group"         => "Special",
                "type"          => "Scale-Rating",
                "description"   => "input string or integer one line",
                "icon"          => "icon ellipsis horizontal"
            ),
            'Image' => array(
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
