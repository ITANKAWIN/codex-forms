<?php
if (!defined('ABSPATH')) {
    die();
}

class Field_Divider {

    private $field_type = 'divider';

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

        $this->field_id       = $_POST['field_id'];

        //default config for field
        $default_config = array(
            'id' => $_POST['field_id'],
            'type' => $this->field_type,
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
        $preview .= "<div class='ui form big'>";
        $preview .= "<div class='field hg'>";
        $preview .= "<hr name='field_id[{$config['id']}]' id='{$config['id']}' clss='fielddivider' disabled placeholder='" . (isset($config['placeholder']) ? $config['placeholder'] : '') . "'>";
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
            </div>
            <div class='ui two column grid'>
                <div class='row'>
                    <div class='column'>
                        <label>Type</label>
                    </div>
                    <div class='column'>
                        <select class='ui fluid dropdown' name='fields[{$config['id']}][type]'>
                        ";
        $field_types = Codex_Fields::field_types();
            foreach ($field_types as $field) {
                $config_field .= "<option value='{$field['type']}' " . ($field['type'] == $config['type'] ? 'selected' : '') . ">{$field['type']}</option>";
            }
            $config_field .= "
                
                        </select>
                    </div>
                </div>
            </div>
        </div>
        ";
        return $config_field;
    }
}

new Field_Divider();
