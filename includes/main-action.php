<?php

namespace main_action;

class Main_action
{

    function __construct()
    {
        add_action('admin_menu', array($this, 'admin_menu'));
        if (isset($_GET['page'])) {
            if ($_GET['page'] == 'codex-forms' && !isset($_GET['view'])) {
                add_action('codex_admin_page', array($this, 'class_show_forms'));
            }
            if (isset($_GET['view'])) {
                if ($_GET['view'] == 'edit') {
                    add_action('codex_admin_page', array($this, 'page_edit_forms'));
                }
            }
        }
    }

    function admin_menu()
    {
        add_menu_page(
            __('Codex Forms', 'CODEX-FORMS'),
            __('Codex Forms', 'CODEX-FORMS'),
            'manage_options',
            'codex-forms',
            array($this, 'admin_page'),
            '',
            6
        );

        add_submenu_page(
            'codex-forms',
            __('Edit Forms', 'CODEX-FORMS'),
            __('Edit Forms', 'CODEX-FORMS'),
            'manage_options',
            'edit-codex-forms'
        );
    }

    function admin_page()
    {
        do_action('codex_admin_page');
    }

    function class_show_forms()
    {
        require_once(CODEX_PATH . '/includes/page/class-show-forms.php');
    }

    function page_edit_forms()
    {
        require_once(CODEX_PATH . '/includes/page/class-edit-form.php');
    }
}
new Main_action();
