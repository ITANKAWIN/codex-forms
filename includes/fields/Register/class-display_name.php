<?php
if (!defined('ABSPATH')) {
    die();
}
class Field_Display_Name {

    private $field_type = 'display_name';

    public function __construct() {
        $this->init();
    }

    function init() {
        add_action("wp_ajax_codex_new_field_{$this->field_type}", array($this, 'get_field'));
        add_filter("codex_form_preview_{$this->field_type}", array($this, 'preview'));
        add_filter("codex_form_config_{$this->field_type}", array($this, 'config'));
    }

    public function get_field() {
        // Check for form ID.
        if (!isset($_POST['id']) || empty($_POST['id'])) {
            die(esc_html__('No form ID found'));
        }
        //default config for field
        $default_config = array(
            'id'            => $_POST['field_id'],
            'type'          => $this->field_type,
            'label'         => 'Display Name',
            'placeholder'   => 'display name',
            'value'         => '',
            'require'       => 'on',
        );
        $position = "<input type='hidden' name='panel[{$_POST['field_id']}]' class='panel' value=''>";
        // Prepare to return compiled results.
        wp_send_json_success(
            array(
                'field' => (int) $_POST['field_id'],
                'preview' => $this->preview($default_config),
                'config'  => $this->config($default_config),
                'position' => $position,
            )
        );
    }

    public function preview($config = []) {
        $preview = "";
        $preview .= "<div class='ui form big'>";
        $preview .= "<div class='field " . ($config['require'] == 'on' ? 'required' : '') . "'>";
        if (isset($config['label'])) {
            $preview .= "<label id='{$config['id']}'>{$config['label']}</label>";
        }
        $preview .= "<input type='text' name='display_name' id='{$config['id']}' disabled placeholder='" . (isset($config['placeholder']) ? $config['placeholder'] : '') . "' " . ($config['require'] == 'on' ? 'required' : '') . " value='" . (isset($config['value']) ? $config['value'] : '') . "'>";
        $preview .= "</div>";
        $preview .= "</div>";
        return $preview;
    }

    public function config($config = []) {
        $config_field = "
        <div class='wrapper-instance-pane properties-config config_field_{$config['id']}' data-field-id='{$config['id']}' style='display: none;'>
            <div class='ui two column grid'>
                <div class='row'>
                    <div class='column'>
                        <label>ID</label>
                    </div>
                    <div class='column'>
                        <div class='ui fluid input'>
                            <input type='text' name='fields[{$config['id']}][id]' value='{$config['id']}' readonly>
                        </div>
                    </div>
                </div>
                <div class='row'>
                    <div class='column'>
                        <label>Type</label>
                    </div>
                    <div class='column'>
                        <select class='ui fluid dropdown' name='fields[{$config['id']}][type]'>
                        ";
        $field_types = Codex_Fields::field_types_register();
        foreach ($field_types as $field) {
            $config_field .= "<option value='{$field['type']}' " . ($field['type'] == $config['type'] ? 'selected' : '') . ">{$field['name']}</option>";
        }
        $config_field .= "
                        </select>
                    </div>
                </div>
                <div class='row'>
                    <div class='column'>
                        <label>Label</label>
                    </div>
                    <div class='column'>
                        <div class='ui fluid input'>
                            <input type='text' class='config-form-label' name='fields[{$config['id']}][label]' value='{$config['label']}'>
                        </div>
                    </div>
                </div>
                <div class='row'>  
                    <div class='column'>
                        <label>Placeholder</label>
                    </div>
                    <div class='column'>
                        <div class='ui fluid input'>
                            <input type='text' class='config-form-placeholder' name='fields[{$config['id']}][placeholder]' value='{$config['placeholder']}'>
                        </div>
                    </div>
                </div>
                <div class='row'>
                    <div class='column'>
                        <label'>Default Value</label>
                    </div>
                    <div class='column'>
                        <div class='ui fluid input'>
                            <input type='text' name='fields[{$config['id']}][value]' value='{$config['value']}'>
                        </div>
                    </div>
                </div>
                <div class='row'>
                    <div class='column'>
                        <label'>Required</label>
                    </div>
                    <div class='column'>
                        <div class='ui toggle checkbox'>
                            <input type='checkbox' name='fields[{$config['id']}][require]' " . ($config['require'] == 'on' ? 'checked' : '') . ">
                            <label></label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        ";
        return $config_field;
    }
}
new Field_Display_Name();
