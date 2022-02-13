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
                'description'   => 'The blank form allows you to create any type of form using our drag & drop builder.'
            ),

            'register'  => array(
                'name'          => 'Register',
                'description'   => 'Register a WordPress User'
            ),

            'login'  => array(
                'name'          => 'Login',
                'description'   => 'Signup a WordPress User'
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
                'succ_msg'  => 'Thankyou for submit form.',
                'redirect' => '',
                'err_msg'  => 'Something Went wrong!!',
                'err_redirect' => '',
                'template'  => 'blank',
            ],
        );

        return $data;
    }

    function Template_Register() {
        $data = array(
            'fields' =>
            array(
                445087 =>
                array(
                    'id' => '445087',
                    'type' => 'email_user',
                    'label' => 'Email',
                    'placeholder' => 'name@gmail.com',
                    'value' => '',
                    'require' => 'on',
                ),
                982292 =>
                array(
                    'id' => '982292',
                    'type' => 'submit',
                    'text' => 'Submit',
                    'align' => 'left',
                ),
                613439 =>
                array(
                    'id' => '613439',
                    'type' => 'first_name',
                    'label' => 'First Name',
                    'placeholder' => 'first name',
                    'value' => '',
                    'require' => 'on',
                ),
                102111 =>
                array(
                    'id' => '102111',
                    'type' => 'last_name',
                    'label' => 'Last Name',
                    'placeholder' => 'last name',
                    'value' => '',
                    'require' => 'on',
                ),
                355616 =>
                array(
                    'id' => '355616',
                    'type' => 'username',
                    'label' => 'Username',
                    'placeholder' => 'username',
                    'value' => '',
                    'require' => 'on',
                ),
                996450 =>
                array(
                    'id' => '996450',
                    'type' => 'password_user',
                    'label' => 'Password',
                    'placeholder' => 'password',
                    'value' => '',
                    'require' => 'on',
                ),
                846074 =>
                array(
                    'id' => '846074',
                    'type' => 'con_password_user',
                    'label' => 'Confirm Password',
                    'placeholder' => 'confirm password',
                    'value' => '',
                    'require' => 'on',
                ),
            ),
            'id' => '34',
            'panels' => '6:6|12|12|12|12|12',
            'panel' =>
            array(
                613439 => '1:1',
                102111 => '1:2',
                445087 => '2:1',
                355616 => '3:1',
                996450 => '4:1',
                846074 => '5:1',
                982292 => '6:1',
            ),
            'setting' =>
            array(
                'succ_msg' => 'Thankyou for submit form.',
                'redirect' => '',
                'err_msg' => 'Something Went wrong!!',
                'err_redirect' => '',
                'template' => 'register',
            ),
        );

        return $data;
    }

    function Template_Login() {
        $data = array(
            'fields' =>
            array(
                937293 =>
                array(
                    'id' => '937293',
                    'type' => 'submit_login',
                    'text' => 'Submit',
                    'align' => 'left',
                ),
                855284 =>
                array(
                    'id' => '855284',
                    'type' => 'email_login',
                    'label' => 'Email',
                    'placeholder' => 'name@gmail.com',
                    'value' => '',
                    'require' => 'on',
                ),
                442662 =>
                array(
                    'id' => '442662',
                    'type' => 'password_login',
                    'label' => 'Password',
                    'placeholder' => '**********',
                    'value' => '',
                    'require' => 'on',
                ),
            ),
            'id' => '35',
            'panels' => '12|12|12',
            'panel' =>
            array(
                855284 => '1:1',
                442662 => '2:1',
                937293 => '3:1',
            ),
            'setting' =>
            array(
                'succ_msg' => 'Thankyou for submit form.',
                'redirect' => '',
                'err_msg' => 'Something Went wrong!!',
                'err_redirect' => '',
                'template' => 'login',
            ),
        );

        return $data;
    }
}

new Codex_Templates();
