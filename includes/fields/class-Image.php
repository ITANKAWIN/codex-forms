<?php
if (!defined('ABSPATH')) {
    die();
}

class Field_Image {

    private $field_type = 'Image';

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
            'label'     => '',
            'image'     => 'http://localhost/wordpress/wp-content/plugins/codex-forms/assets/image/fields/image.png',
            'size'      => 'medium',
            'border'    => ''
        );

        $position = "<input type='hidden' name='panel[{$_POST['field_id']}]' class='panel' value=''>";
        // Prepare to return compiled results.
        wp_send_json_success(
            array(
                'form_id'   => (int) $_POST['id'],
                'preview'   => $this->preview($default_config),
                'config'    => $this->config($default_config),
                'position'  => $position,
            )
        );
    }

    public function preview($config = []) {

        $preview = "";
        $preview .= "<div class='ui form big'>";
        $preview .= "<div class='field'>";
        if (isset($config['label'])) {
            $preview .= "<label id='{$config['id']}'>{$config['label']}</label>";
        }
        $preview .= "<img id='{$config['id']}' class='ui image centered {$config['size']} {$config['border']}' src='{$config['image']}'>";
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
                        <label>Image</label>
                    </div>
                    <div class='column'>
                        <div class='ui fluid input'>
                            <input type='hidden' name='fields[{$config['id']}][image]' value='{$config['image']}'>
                            <button class='ui button select_media' data-id='{$config['id']}'><i class='upload icon'></i></button>
                        </div>
                    </div>
                </div>
                <div class='row'>
                    <div class='column'>
                        <label>Border</label>
                    </div>
                    <div class='column'>
                        <div class='ui fluid input'>
                            <select class='ui fluid dropdown' name='fields[{$config['id']}][border]'>
                        ";
        foreach ($this->border() as $border) {
            $config_field .= "<option value='{$border}' " . ($border == $config['border'] ? 'selected' : '') . ">{$border}</option>";
        }
        $config_field .= "
                            </select>
                        </div>
                    </div>
                </div>
                <div class='row'>
                    <div class='column'>
                        <label>Size</label>
                    </div>
                    <div class='column'>
                        <div class='ui fluid input'>
                            <select class='ui fluid dropdown' name='fields[{$config['id']}][size]'>
                        ";
        foreach ($this->size() as $size) {
            $config_field .= "<option value='{$size}' " . ($size == $config['size'] ? 'selected' : '') . ">{$size}</option>";
        }
        $config_field .= "
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        ";

        return $config_field;
    }

    function border() {
        $border = array(
            'Normal' => 'normal',
            'Rounded' => 'rounded',
            'Circular' => 'circular',
        );

        return $border;
    }

    function size() {
        $size = array(
            'mini' => 'mini',
            'tiny' => 'tiny',
            'small' => 'small',
            'medium' => 'medium',
            'large' => 'large',
            'big' => 'big',
            'huge' => 'huge',
            'massive' => 'massive'
        );

        return $size;
    }
}

new Field_Image();
