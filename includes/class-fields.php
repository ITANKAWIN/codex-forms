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
                "group"         => "basic",
                "type"          => "text",
                "description"   => "input string or integer one line",
                "icon"          => "icon font"
            ),
            'select' => array(
                "group"         => "select",
                "type"          => "select",
                "description"   => "input string or integer one line",
                "icon"          => "icon caret square down outline"
            ),
            'password' => array(
                "group"         => "basic",
                "type"          => "password",
                "description"   => "input string or integer one line",
                "icon"          => "icon font"
            ),
            'number' => array(
                "group"         => "basic",
                "type"          => "number",
                "description"   => "input string or integer one line",
                "icon"          => "icon hashtag"
            ),
            'email' => array(
                "group"         => "basic",
                "type"          => "email",
                "description"   => "input string or integer one line",
                "icon"          => "icon envelope outline"
            ),
            'date' => array(
                "group"         => "select",
                "type"          => "date",
                "description"   => "input string or integer one line",
                "icon"          => "icon calendar alternate outline"
            ),
            'radio' => array(
                "group"         => "select",
                "type"          => "radio",
                "description"   => "input string or integer one line",
                "icon"          => "icon dot circle outline"
            ),
            'checkbox' => array(
                "group"         => "select",
                "type"          => "checkbox",
                "description"   => "input string or integer one line",
                "icon"          => "icon check square outline"
            ),
            'button' => array(
                "group"         => "basic",
                "type"          => "button",
                "description"   => "input string or integer one line",
                "icon"          => "icon square outline"
            ),
            'file' => array(
                "group"         => "file",
                "type"          => "file",
                "description"   => "input string or integer one line",
                "icon"          => "icon upload"
            ),
            'textarea' => array(
                "group"         => "basic",
                "type"          => "textarea",
                "description"   => "input string or integer one line",
                "icon"          => "icon paragraph"
            ),
            'divider' => array(
                "group"         => "special",
                "type"          => "divider",
                "description"   => "input string or integer one line",
                "icon"          => "icon arrows alternate horizontal"
            ),
            'thai-provinces' => array(
                "group"         => "special",
                "type"          => "thai-provinces",
                "description"   => "input string or integer one line",
                "icon"          => "icon map marker alternate"
            ),
            'Pin-GPS' => array(
                "group"         => "special",
                "type"          => "Pin-GPS",
                "description"   => "input string or integer one line",
                "icon"          => "icon map marker alternate"
            ),
            'Star-Rating' => array(
                "group"         => "special",
                "type"          => "Star-Rating",
                "description"   => "input string or integer one line",
                "icon"          => "icon star outline"
            ),
            'Scale-Rating' => array(
                "group"         => "special",
                "type"          => "Scale-Rating",
                "description"   => "input string or integer one line",
                "icon"          => "icon ellipsis horizontal"
            ),
            'Image' => array(
                "group"         => "special",
                "type"          => "Image",
                "description"   => "input string or integer one line",
                "icon"          => "icon image outline"
            ),
        );

        return $fields;
    }
}

new Codex_Fields();
