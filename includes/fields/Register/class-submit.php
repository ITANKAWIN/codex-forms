<?php
if (!defined('ABSPATH')) {

    die();
}
class Field_Submit {

    private $field_type = 'submit';

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
            'id'        => $_POST['field_id'],
            'type'      => $this->field_type,
            'text'      => 'Submit',
            'align'     => 'left'
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

    public function preview($config = []) {

        $preview = "";
        $preview .= "<form class='ui form huge'>";
        $preview .= "<div class='field' style='text-align: {$config['align']}'>";
        if (isset($config['label'])) {
            $preview .= "<label>{$config['label']}</label>";
        }
        $preview .= "<button type='submit' name='field_id[{$config['id']}]' id='{$config['id']}' class='ui primary large button' disabled>" . (!empty($config['text']) ? $config['text'] : '') . "</button>";
        $preview .= "</div>";
        $preview .= "</form>";
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
                        <select class='ui dropdown' name='fields[{$config['id']}][type]'>
                        ";
        $field_types = Codex_Fields::field_types_register();
        foreach ($field_types as $field) {
            $config_field .= "<option value='{$field['type']}' " . ($field['type'] == $config['type'] ? 'selected' : '') . ">{$field['type']}</option>";
        }
        $config_field .= "
                        </select>
                    </div>
                </div>
                <div class='row'>
                    <div class='column'>
                        <label>Text</label>
                    </div>
                    <div class='column'>
                        <div class='ui fluid input'>
                            <input type='text' class='config-form-text-button' name='fields[{$config['id']}][text]' value='{$config['text']}'>
                        </div>
                    </div>
                </div>
                <div class='row'>
                    <div class='column'>
                        <label'>Button Align</label>
                    </div>
                    <div class='column'>
                        <select class='ui fluid dropdown config-form-align-button' name='fields[{$config['id']}][align]'>
                        <option value='left' " . ('left' == $config['align'] ? 'selected' : '') . ">Left</option>
                        <option value='center' " . ('center' == $config['align'] ? 'selected' : '') . ">Center</option>
                        <option value='right' " . ('right' == $config['align'] ? 'selected' : '') . ">Right</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        ";
        return $config_field;
    }
}

new Field_Submit();
