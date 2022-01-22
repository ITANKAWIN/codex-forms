<?php
class Codex_Templates {

    public function __construct() {
        $this->init();
    }

    public function init() {
        $templates = self::templates();

        if (!empty($templates)) {
            foreach ($templates as $template) {
                add_filter("codex_template_{$template['name']}", array($this, "Template_{$template['name']}"));
            }
        }
    }

    public static function templates() {

        $templates = array(
            'blank'     => array(
                'name'          => 'Blank',
                'description'   => 'ซักอย่าง'
            ),

            'register'  => array(
                'name'          => 'Register',
                'description'   => 'สมัครสมาชิก'
            ),

            'login'  => array(
                'name'          => 'Login',
                'description'   => 'เข้าสู่ระบบ'
            )
        );

        return $templates;
    }

    public function Template_Blank() {
        $data = array(
            'fields'    => [],
            'panels'    => 12,
            'panel'     => [],
            'setting'   => [
                'template'  => 'blank',
            ],
        );

        return $data;
    }

    function Template_Register() {
    }

    function Template_Login() {
        $data = array(
            'fields' =>
            array(
                865017 =>
                array(
                    'id' => '865017',
                    'type' => 'text',
                    'label' => 'Username',
                    'name' => 'username',
                    'placeholder' => 'Enter username',
                    'value' => '',
                    'require' => 'on',
                ),
                868719 =>
                array(
                    'id' => '868719',
                    'type' => 'password',
                    'label' => 'Password',
                    'name' => 'password',
                    'placeholder' => 'Enter password',
                    'value' => '',
                    'require' => 'on',
                ),
                533540 =>
                array(
                    'id' => '533540',
                    'type' => 'button',
                    'text' => 'Login',
                    'align' => 'right',
                ),
            ),
            'id' => '24',
            'panels' => '12|12',
            'panel' =>
            array(
                865017 => '1:1',
                868719 => '1:1',
                533540 => '2:1',
            ),
            'setting' =>
            array(
                'succ_msg' => '',
                'redirect' => '',
                'template' => 'Normal',
            ),
        );

        return $data;
    }
}

new Codex_Templates();
