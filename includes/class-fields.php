<?php

class Codex_Fields
{

    public function __construct()
    {
        $this->init();
    }
    public static function init()
    {
        $field_types = self::field_types();


        if (!empty($field_types)) {
            foreach ($field_types as $field) {

                if (file_exists(CODEX_PATH . '/includes/fields/class-' . $field['type'] . '.php')) {
                    require_once(CODEX_PATH . '/includes/fields/class-' . $field['type'] . '.php');
                }
            }
        }

        return $field_types;
    }

    public static function field_types()
    {

        $fields = array(
            'text' => array(
                "type"          => "text",
                "description"   => "input string or integer one line",
                "icon"          => ""
            ),
            'select' => array(
                "type"          => "select",
                "description"   => "input string or integer one line",
                "icon"          => ""
            ),
            'password' => array(
                "type"          => "password",
                "description"   => "input string or integer one line",
                "icon"          => ""
            ),
            'number' => array(
                "type"          => "number",
                "description"   => "input string or integer one line",
                "icon"          => ""
            ),
            'email' => array(
                "type"          => "email",
                "description"   => "input string or integer one line",
                "icon"          => ""
            ),
            'date' => array(
                "type"          => "date",
                "description"   => "input string or integer one line",
                "icon"          => ""
            ),
            'radio' => array(
                "type"          => "radio",
                "description"   => "input string or integer one line",
                "icon"          => ""
            ),
            'checkbox' => array(
                "type"          => "checkbox",
                "description"   => "input string or integer one line",
                "icon"          => ""
            ),
            'button' => array(
                "type"          => "button",
                "description"   => "input string or integer one line",
                "icon"          => ""
            ),
            'file' => array(
                "type"          => "file",
                "description"   => "input string or integer one line",
                "icon"          => ""
            ),
            'textarea' => array(
                "type"          => "textarea",
                "description"   => "input string or integer one line",
                "icon"          => ""
            ),
            'divider' => array(
                "type"          => "divider",
                "description"   => "input string or integer one line",
                "icon"          => ""
            ),
            'thai-provinces' => array(
                "type"          => "thai-provinces",
                "description"   => "input string or integer one line",
                "icon"          => ""
            ),
            'Pin-GPS' => array(
                "type"          => "Pin-GPS",
                "description"   => "input string or integer one line",
                "icon"          => ""
            ),
            'Star-Rating' => array(
                "type"          => "Star-Rating",
                "description"   => "input string or integer one line",
                "icon"          => ""
            ),
            'Scale-Rating' => array(
                "type"          => "Scale-Rating",
                "description"   => "input string or integer one line",
                "icon"          => ""
            ),
            'reCAPTCHA' => array(
                "type"          => "reCAPTCHA",
                "description"   => "input string or integer one line",
                "icon"          => ""
            ),
            'Image' => array(
                "type"          => "Image",
                "description"   => "input string or integer one line",
                "icon"          => ""
            ),
        );

        return $fields;
    }
}

new Codex_Fields();