<?php
class Main_action {

    function __construct() {
        add_action('admin_menu', array($this, 'admin_menu'));
        if (isset($_GET['page'])) {
            if ($_GET['page'] == 'codex-forms' && !isset($_GET['view'])) {
                add_action('codex_admin_page', array($this, 'class_show_forms'));
            }

            if ($_GET['page'] == 'codex-forms' && $_GET['view'] = 'edit') {
                add_action('codex_admin_page', array($this, 'page_edit_forms'));
            }

            if ($_GET['page'] == 'entire-codex-forms') {
                add_action('codex_admin_page', array($this, 'class_entire_forms'));
            }

            if ($_GET['page'] == 'entire-codex-forms' && isset($_GET['export'])) {
                add_action('codex_admin_page', array($this, 'class_export_forms'));
            }
        }

        require_once(CODEX_PATH . 'includes/pages/preview.php');
    }

    function admin_menu() {
        add_menu_page(
            __('Codex Forms', 'CODEX-FORMS'),
            __('Codex Forms', 'CODEX-FORMS'),
            'manage_options',
            'codex-forms',
            array($this, 'codex_admin_page'),
            '',
            6
        );

        add_submenu_page(
            'codex-forms',
            __('Entires', 'CODEX-FORMS'),
            __('Entires Value', 'CODEX-FORMS'),
            'manage_options',
            'entire-codex-forms',
            array($this, 'codex_admin_page'),
        );
    }

    function codex_admin_page() {
        do_action('codex_admin_page');
    }

    function class_show_forms() {
        require_once(CODEX_PATH . '/includes/pages/class-show-forms.php');
    }

    function page_edit_forms() {
        require_once(CODEX_PATH . '/includes/pages/class-edit-form.php');
    }

    function class_entire_forms() {
        require_once(CODEX_PATH . '/includes/pages/class-entire-form.php');
    }

    function class_export_forms() {
        require_once(CODEX_PATH . '/includes/pages/class-export-form.php');
    }
}
new Main_action();
