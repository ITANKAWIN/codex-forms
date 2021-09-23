<?php
if (!defined('ABSPATH')) {
    die();
}
class Field_Provinces{

    private $field_type = 'thai-provinces';
    
    public function __construct(){
        $this->init();
    }

    function init(){
        add_action("wp_ajax_codex_new_field_{$this->field_type}", array($this, 'get_field'));
        add_filter("codex_form_preview_{$this->field_type}", array($this, 'preview'));
        add_filter("codex_form_config_{$this->field_type}", array($this, 'config'));
    }

    public function get_field(){
        // Check for form ID.
        if (!isset($_POST['id']) || empty($_POST['id'])) {
            die(esc_html__('No form ID found'));
        }
        //default config for field
        $default_config = array(
            'id' => $_POST['field_id'],
            'type' => $this->field_type,
            'label' => 'Thai-provinces',
            'placeholder' => 'thai-provinces',
            'option_default' => 1,
            'options' => array(
                1 => 'กรุงเทพมหานคร', 
                2 => 'จังหวัดกระบี่', 
                3 => 'จังหวัดกาญจนบุรี',
                4 => 'จังหวัดกาฬสินธุ์', 
                5 => 'จังหวัดกำแพงเพชร',
                6 => 'จังหวัดขอนแก่น', 
                7 => 'จังหวัดจันทบุรี',
                8 => 'จังหวัดฉะเชิงเทรา', 
                9 => 'จังหวัดชลบุรี', 
                10 => 'จังหวัดชัยนาท', 
                11 => 'จังหวัดชัยภูมิ', 
                12 => 'จังหวัดชุมพร', 
                13 => 'จังหวัดเชียงราย', 
                14 => 'จังหวัดเชียงใหม่',
                15 => 'จังหวัดตรัง', 
                16 => 'จังหวัดตราด', 
                17 => 'จังหวัดตาก', 
                18 => 'จังหวัดนครนายก',
                19 => 'จังหวัดนครปฐม', 
                20 => 'จังหวัดนครพนม', 
                21 => 'จังหวัดนครราชสีมา',
                22 => 'จังหวัดนครศรีธรรมราช',
                23 => 'จังหวัดนครสวรรค์',
                24 => 'จังหวัดนนทบุรี',
                25 => 'จังหวัดนราธิวาส',
                26 => 'จังหวัดน่าน',
                27 => 'จังหวัดบึงกาฬ',
                28 => 'จังหวัดบุรีรัมย์',
                29 => 'จังหวัดปทุมธานี',
                30 => 'จังหวัดประจวบคีรีขันธ์',
                31 => 'จังหวัดปราจีนบุรี',
                32 => 'จังหวัดปัตตานี',
                33 => 'จังหวัดพระนครศรีอยุธยา',
                34 => 'จังหวัดพะเยา',
                35 => 'จังหวัดพังงา',
                36 => 'จังหวัดพัทลุง',
                37 => 'จังหวัดพิจิตร',
                38 => 'จังหวัดพิษณุโลก',
                39 => 'จังหวัดเพชรบุรี',
                40 => 'จังหวัดเพชรบูรณ์',
                41 => 'จังหวัดแพร่',
                42 => 'จังหวัดภูเก็ต',
                43 => 'จังหวัดมหาสารคาม',
                44 => 'จังหวัดมุกดาหาร',
                45 => 'จังหวัดแม่ฮ่องสอน',
                46 => 'จังหวัดยโสธร',
                47 => 'จังหวัดยะลา',
                48 => 'จังหวัดร้อยเอ็ด',
                49 => 'จังหวัดระนอง',
                50 => 'จังหวัดระยอง',
                51 => 'จังหวัดราชบุรี',
                52 => 'จังหวัดลพบุรี',
                53 => 'จังหวัดลำปาง',
                54 => 'จังหวัดลำพูน',
                55 => 'จังหวัดเลย',
                56 => 'จังหวัดศรีสะเกษ',
                57 => 'จังหวัดสกลนคร',
                58 => 'จังหวัดสงขลา',
                59 => 'จังหวัดสตูล',
                60 => 'จังหวัดสมุทรปราการ',
                61 => 'จังหวัดสมุทรสงคราม',
                62 => 'จังหวัดสมุทรสาคร',
                63 => 'จังหวัดสระแก้ว',
                64 => 'จังหวัดสระบุรี',
                65 => 'จังหวัดสิงห์บุรี',
                66 => 'จังหวัดสุโขทัย',
                67 => 'จังหวัดสุพรรณบุรี',
                68 => 'จังหวัดสุราษฎร์ธานี',
                69 => 'จังหวัดสุรินทร์',
                70 => 'จังหวัดหนองคาย',
                71 => 'จังหวัดหนองบัวลำภู',
                72 => 'จังหวัดอ่างทอง',
                73 => 'จังหวัดอำนาจเจริญ',
                74 => 'จังหวัดอุดรธานี',
                75 => 'จังหวัดอุตรดิตถ์',
                76 => 'จังหวัดอุทัยธานี',
                77 => 'จังหวัดอุบลราชธานี'
            ),
            'next_option_id' => 78,
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

    public function preview($config = []){

        if (!isset($config['option_default'])) {
            $config['option_default'] = null;
        }
        
        $preview = "";
        $preview .= "<div class='ui form big'>";
        $preview .= "<div class='field'>";
        if (isset($config['label'])) {

            $preview .= "<label>{$config['label']}</label>";
        }
        $preview .= "<select name='select' class='ui dropdown fluid' name='field[{$config['id']}]' id='{$config['id']}' disabled placeholder='" . (isset($config['placeholder']) ? $config['placeholder'] : '') . "'>";
        foreach ($config['options'] as $option) {
            $preview .= "<option value='$option' " . ($option === $config['option_default'] ? 'selected' : '') . ">{$option}</option>";
        }
        $preview .= "</select>";
        $preview .= "</div>";
        $preview .= "</div>";
        return $preview;
    }

    public function config($config = []){
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
            <hr>

            <input type='hidden' name='fields[{$config['id']}][next_option_id]' value='{$config['id']['next_option_id']}'>
            <div class='ui grid'>
                <div class='four wide column'>
                    <label'>Option</label>
                </div>
                <div class='twelve wide column'>
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
        ";
        return $config_field;
    }
}

new Field_Provinces();

