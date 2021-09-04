<?php
if (!defined('ABSPATH')) {
    die();
}

class Field_Divider
{
    private $field_type = 'divider';

    public function __construct()
    {
        $this->init();
    }

    function init()
    {
        add_action("wp_ajax_codex_new_field_{$this->field_type}", array($this, 'get_field'));
        add_filter("codex_form_preview_{$this->field_type}", array($this, 'preview'));
    }

    public function get_field()
    {
        // Check for form ID.
        if (!isset($_POST['id']) || empty($_POST['id'])) {
            die(esc_html__('No form ID found'));
        }

        $this->field_id       = $_POST['field_id'];

        //default config for field
        $default_config = array(
            'label' => '',
            'placeholder' => 'divider',
            'value' => ''
        );

        $position = "<input type='hidden' name='panel[{$_POST['field_id']}]' class='panel' value=''>";
        // Prepare to return compiled results.
        wp_send_json_success(
            array(
                'form_id' => (int) $_POST['id'],
                'preview' => $this->preview($default_config),
                'config'  => $this->config($default_config),
                'position' => $position,
            )
        );
    }

    public function preview($config = [])
    {
        $preview = "";
        $preview .= "<form class='ui form huge'>";
        $preview .= "<div class='field hg'>";
        if (isset($config['label'])) {

            $preview .= "<label>{$config['label']}</label>";
        }
        $preview .= "<hr clss='fielddivider' disabled placeholder='" . (isset($config['placeholder']) ? $config['placeholder'] : '') . "'>";
        $preview .= "</div>";
        $preview .= "</form>";
        return $preview;
    }

    public function config($config = [])
    {
        $config_field = "<div class='config-field' data-field-id='{$this->field_id}'>";
        $config_field .= "<input type='hidden' name='fields[{$this->field_id}][id]' value='{$this->field_id}'>";
        $config_field .= "<input type='hidden' name='fields[{$this->field_id}][type]' value='{$this->field_type}'>";
        foreach ($config as $k => $v) {
            $config_field .= "<input type='hidden' name='option[{$this->field_id}][{$k}]' value='{$v}'>";
        }
        $config_field .= "</div>";

        return $config_field;
    }
}

new Field_Divider();
