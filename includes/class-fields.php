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
                "icon"          => "icon font"
            ),
            'select' => array(
                "type"          => "select",
                "description"   => "input string or integer one line",
                "icon"          => "icon caret square down outline"
            ),
            'password' => array(
                "type"          => "password",
                "description"   => "input string or integer one line",
                "icon"          => "icon font"
            ),
            'number' => array(
                "type"          => "number",
                "description"   => "input string or integer one line",
                "icon"          => "icon hashtag"
            ),
            'email' => array(
                "type"          => "email",
                "description"   => "input string or integer one line",
                "icon"          => "icon envelope outline"
            ),
            'date' => array(
                "type"          => "date",
                "description"   => "input string or integer one line",
                "icon"          => "icon calendar alternate outline"
            ),
            'radio' => array(
                "type"          => "radio",
                "description"   => "input string or integer one line",
                "icon"          => "icon dot circle outline"
            ),
            'checkbox' => array(
                "type"          => "checkbox",
                "description"   => "input string or integer one line",
                "icon"          => "icon check square outline"
            ),
            'button' => array(
                "type"          => "button",
                "description"   => "input string or integer one line",
                "icon"          => "icon square outline"
            ),
            'file' => array(
                "type"          => "file",
                "description"   => "input string or integer one line",
                "icon"          => "icon upload"
            ),
            'textarea' => array(
                "type"          => "textarea",
                "description"   => "input string or integer one line",
                "icon"          => "icon paragraph"
            ),
            'divider' => array(
                "type"          => "divider",
                "description"   => "input string or integer one line",
                "icon"          => "icon arrows alternate horizontal"
            ),
            'thai-provinces' => array(
                "type"          => "thai-provinces",
                "description"   => "input string or integer one line",
                "icon"          => "icon map marker alternate"
            ),
            'Pin-GPS' => array(
                "type"          => "Pin-GPS",
                "description"   => "input string or integer one line",
                "icon"          => "icon map marker alternate"
            ),
            'Star-Rating' => array(
                "type"          => "Star-Rating",
                "description"   => "input string or integer one line",
                "icon"          => "icon star outline"
            ),
            'Scale-Rating' => array(
                "type"          => "Scale-Rating",
                "description"   => "input string or integer one line",
                "icon"          => "icon ellipsis horizontal"
            ),
            'reCAPTCHA' => array(
                "type"          => "reCAPTCHA",
                "description"   => "input string or integer one line",
                "icon"          => "icon filter"
            ),
            'Image' => array(
                "type"          => "Image",
                "description"   => "input string or integer one line",
                "icon"          => "icon image outline"
            ),
        );

        return $fields;
    }
}

new Codex_Fields();
