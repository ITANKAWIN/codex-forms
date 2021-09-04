<?php

if (!defined('ABSPATH')) {

    die();

}



class Field_Checkbox

{

    private $field_type = 'checkbox';



    public function __construct()

    {

        $this->init();

    }



    function init()

    {

        add_action("wp_ajax_codex_new_field_{$this->field_type}", array($this, 'get_field'));

        add_filter("codex_form_preview_{$this->field_type}", array($this, 'preview'));

        add_filter("codex_form_config_{$this->field_type}", array($this, 'config'));

    }



    public function get_field()

    {

        // Check for form ID.

        if (!isset($_POST['id']) || empty($_POST['id'])) {

            die(esc_html__('No form ID found'));

        }



        //default config for field

        $default_config = array(

            'id' => $_POST['field_id'],

            'type' => $this->field_type,

            'label' => 'CheckBoxes',

            'placeholder' => 'CheckBoxes',

            'value' => '',

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
        $preview .= "<div class='field'>";
        if (isset($config['label'])) {

            $preview .= "<label>{$config['label']}</label>";
        }
        $preview .= "<div>
                        <input type='checkbox' id='Option1' name='Option1' disabled placeholder='" . (isset($config['placeholder']) ? $config['placeholder'] : '') . "'>
                        <label for='Option1'>Option 1</label>
                    </div>
                    <div>
                        <input type='checkbox' id='Option2' name='Option2' disabled placeholder='" . (isset($config['placeholder']) ? $config['placeholder'] : '') . "'>
                        <label for='Option2'>Option 2</label>
                    </div>";
        $preview .= "</div>";
        $preview .= "</form>";
        return $preview;
    }



    public function config($config = [])

    {

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

                    <label>Label</label>
                
                </div>

                <div class='eleven wide column'>

                    <div class='ui fluid input'>
                        
                        <input type='text' class='form-control' name='fields[{$config['id']}][label]' value='{$config['label']}'>

                    </div>

                </div>
        
            </div>


            <div class='ui grid'>

                <div class='five wide column'>

                    <label>Placeholder</label>

                </div>

                <div class='eleven wide column'>

                    <div class='ui fluid input'>

                        <input type='text' class='form-control' name='fields[{$config['id']}][placeholder]' value='{$config['placeholder']}'>

                    </div>
                    
                </div>

            </div>

            <div class='ui grid'>

                <div class='five wide column'>

                    <label'>Option</label>
                
                </div>

                <div class='eleven wide column'>

                    <div class='ui fluid input'>

                        <input type='text' class='form-control' name='fields[{$config['id']}][value]' value='{$config['value']}'>

                        <i class='icon plus circle plus-option'></i>

                    </div>
                    
                </div>

            </div>

        </div>

        ";

        return $config_field;
    }
}



new Field_Checkbox();
