<?php
if (!defined('ABSPATH')) {
    die();
}
class Field_Pin_GPS {
    private $field_type = 'Pin-GPS';

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
            'id'    => $_POST['field_id'],
            'type'  => $this->field_type,
            'label' => 'Google Map',
            'map'   => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7961373.3852991015!2d96.98248701210906!3d13.010859993898343!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x304d8df747424db1%3A0x9ed72c880757e802!2z4Lib4Lij4Liw4LmA4LiX4Lio4LmE4LiX4Lii!5e0!3m2!1sth!2sth!4v1643385011844!5m2!1sth!2sth" style="border:0;" allowfullscreen="" loading="lazy"></iframe>',
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
        $preview .= "<div class='field'>";
        if (isset($config['label'])) {
            $preview .= "<label id='{$config['id']}'>{$config['label']}</label>";
        }
        $preview .= "<div class='codex-google-map'>{$config['map']}</div>";
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
                        <select class='ui fluid dropdown field-type' name='fields[{$config['id']}][type]'>
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
                        <label>Google Map</label>
                    </div>
                    <div class='column'>
                        <div class='ui fluid input'>
                            <textarea class='config-form-map' name='fields[{$config['id']}][map]'>{$config['map']}</textarea>
                        </div>
                    </div> 
                </div>
            </div>
        </div>
        ";
        return $config_field;
    }
}

new Field_Pin_GPS();
