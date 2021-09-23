<?php
if (!defined('ABSPATH')) {

    die();
}
class Field_button {
    private $field_type = 'button';
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
            'id' => $_POST['field_id'],
            'type' => $this->field_type,
            'text' => 'Submit',
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
        $preview .= "<div class='field'>";
        if (isset($config['label'])) {

            $preview .= "<label>{$config['label']}</label>";
        }
        $preview .= "<button type='submit' class='ui primary large basic button' disabled>" . (!empty($config['text']) ? $config['text'] : '') . "</button>";
        $preview .= "</div>";
        $preview .= "</form>";
        return $preview;
    }

    public function config($config = []) {
        $config_field = "
        <div class='wrapper-instance-pane properties-config config_field_{$config['id']}' data-field-id='{$config['id']}' style='display: none;'>
            <div class='ui grid'>
                <div class='five wide column'>
                    <label>ID</label>
                </div>
                <div class='eleven wide column'>
                    <div class='ui fluid input'>
                        <input type='text' name='fields[{$config['id']}][id]' value='{$config['id']}' readonly>
                    </div>
                </div>
            </div>
            <div class='ui grid'>
                <div class='five wide column'>
                    <label>Type</label>
                </div>
                <div class='eleven wide column'>
                    <select class='ui fluid dropdown' name='fields[{$config['id']}][type]'>
                    ";
        $field_types = Codex_Fields::init();
        foreach ($field_types as $field) {
            $config_field .= "<option value='{$field['type']}' " . ($field['type'] == $config['type'] ? 'selected' : '') . ">{$field['type']}</option>";
        }
        $config_field .= "
                    </select>
                </div>
            </div>
            <div class='ui grid'>
                <div class='five wide column'>
                    <label>Text</label>
                </div>
                <div class='eleven wide column'>
                    <div class='ui fluid input'>
                        <input type='text' name='fields[{$config['id']}][text]' value='{$config['text']}' readonly>
                    </div>
                </div>
            </div>
            <div class='ui grid'>
                <div class='five wide column'>
                    <label'>Button Align</label>
                </div>
                <div class='eleven wide column'>
                    <select class='ui fluid dropdown' name='fields[{$config['id']}][align]'>
                     <option value='left' " . ('left' == $config['align'] ? 'selected' : '') . ">Left</option>
                     <option value='middle' " . ('middle' == $config['align'] ? 'selected' : '') . ">Middle</option>
                     <option value='right' " . ('right' == $config['align'] ? 'selected' : '') . ">Right</option>
                    </select>
                </div>
            </div>
        </div>
        ";
        return $config_field;
    }
}

new Field_button();
