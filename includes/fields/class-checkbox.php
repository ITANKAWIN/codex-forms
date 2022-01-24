<?php
if (!defined('ABSPATH')) {
    die();
}
class Field_Checkbox {

    private $field_type = 'checkbox';
    
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
            'label' => 'CheckBoxes',
            #'orientation' => '',
            'options' => array(
                1 => 'Option 1',
                2 => 'Option 2'
            ),
            'next_option_id' => 3,
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

    public function preview($config = []) { #vertical(แนวตั้ง)

        $preview = "";
        $preview .= "<div class='ui form big'>";
        $preview .= "<div class='grouped fields'>";
        if (isset($config['label'])) {
            $preview .= "<label id='{$config['id']}'>{$config['label']}</label>";
        }
        foreach ($config['options'] as $option) {
            $preview .= "<div class='ui'><input type='checkbox' name='field_id[{$config['id']}]' id='{$config['id']}' value='{$option}' disabled>";
            $preview .= "<label value='{$option}' " . ($option === $config['option_default'] ? 'selected' : '') . ">{$option}</label></div>";
        }
        $preview .= "</div>";
        $preview .= "</div>";
        return $preview;
    }

    // public function preview($config = []) { #horizontal(แนวนอน)

    //     $preview = "";
    //     $preview .= "<div class='ui form big'>";
    //     $preview .= "<div class='inline fields'>";
    //     if (isset($config['label'])) {
    //         $preview .= "<label id='{$config['id']}'>{$config['label']}</label>";
    //     }
    //     foreach ($config['options'] as $option) {
    //         $preview .= "<div class='ui checkbox'><input type='checkbox' name='field[{$config['id']}]' id='{$config['id']}' disabled placeholder='" . (isset($config['placeholder']) ? $config['placeholder'] : '') . "'>";
    //         $preview .= "<label value='$option' " . ($option === $config['option_default'] ? 'selected' : '') . ">{$option}</label></div>";
    //     }
    //     $preview .= "</div>";
    //     $preview .= "</div>";
    //     return $preview;
    // }

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
        $field_types = Codex_Fields::field_types();
            foreach ($field_types as $field) {
                $config_field .= "<option value='{$field['type']}' " . ($field['type'] == $config['type'] ? 'selected' : '') . ">{$field['type']}</option>";
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
                        <label>Name</label>
                    </div>
                    <div class='column'>
                        <div class='ui fluid input'>
                            <input type='text' class='config-form-name' name='fields[{$config['id']}][name]' value='{$config['name']}'>
                        </div>
                    </div>
                </div>
                <div class='row'>  
                    <div class='column'>
                        <label'>Orientation</label>
                    </div>
                    <div class='column'>
                        <select class='ui fluid dropdown' name='fields[{$config['id']}][orientation]'>
                        <option value='left' " . ('left' == $config['orientation'] ? 'selected' : '') . ">Vertical</option>
                        <option value='middle' " . ('middle' == $config['orientation'] ? 'selected' : '') . ">Horizontal</option>
                        </select>
                    </div>
                </div>
            </div>
            <hr>

            <input type='hidden' name='fields[{$config['id']}][next_option_id]' value='{$config['id']['next_option_id']}'>
            <div class='ui grid'>
                <div class='four wide column'>
                    <div class='column'>
                        <label'>Option</label>
                    </div>
                    <div class='four wide column'>
                        ";
            foreach ($config['options'] as $option => $v) {
                $config_field .= "
                        <div class='ui fluid input'>
                            <div class='index-control'><input type='radio' name='fields[{$config['id']}][option_default]' " . ($config['option_default'] == $v ? 'checked' : '') . " value='{$v}'></div>
                            <input type='text' class='form-control' name='fields[{$config['id']}][options][{$option}]' value='{$v}'>
                            <a class='add' href='#'>
                                <i class='icon plus circle green'></i>
                            </a>
                            <a class='remove' href='#'>
                                <i class='icon minus circle red'></i>
                            </a>
                        </div>";
            }
            $config_field .= "
                    </div>
                </div>
            </div>
        </div>
        ";
        return $config_field;
    }
}

new Field_Checkbox();
