<?php
class Codex_Templates {

    public function __construct() {
        $this->init();
    }

    public function init() {
        $templates = self::templates();

        if (!empty($templates)) {
            foreach ($templates as $template) {
                add_action("codex_template_{$template['name']}", array($this, "Template_{$template['name']}"));
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
    }
}

new Codex_Templates();
