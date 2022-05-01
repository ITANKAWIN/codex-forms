<?php
class Main_action {

    function __construct() {

        add_action('admin_menu', array($this, 'admin_menu'));

        if (isset($_GET['page'])) {
            if ($_GET['page'] == 'codex-forms' && !isset($_GET['view'])) {
                add_action('codex_admin_page', array($this, 'class_show_forms'));
            }

            if ($_GET['page'] == 'codex-forms' && $_GET['view'] = 'edit') {
                add_action('codex_admin_page', array($this, 'class_edit_forms'));
            }

            if ($_GET['page'] == 'entire-codex-forms') {
                add_action('codex_admin_page', array($this, 'class_entire_forms'));
            }

            if ($_GET['page'] == 'about-codex-forms') {
                add_action('codex_admin_page', array($this, 'class_about_forms'));
            }

        }

        require_once(CODEX_PATH . '/includes/pages/class-preview-form.php');

        require_once(CODEX_PATH . '/includes/pages/class-export-import-form.php');

        require_once(CODEX_PATH . '/includes/pages/class-export-entry.php');
    }

    function admin_menu() {
        add_menu_page(
            __('Codex Forms', 'CODEX-FORMS'),
            __('Codex Forms', 'CODEX-FORMS'),
            'manage_options',
            'codex-forms',
            array($this, 'codex_admin_page'),
            'dashicons-feedback',
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

        add_submenu_page(
            'codex-forms',
            __('About me', 'CODEX-FORMS'),
            __('About me', 'CODEX-FORMS'),
            'manage_options',
            'about-codex-forms',
            array($this, 'codex_admin_page'),
        );
    }

    function codex_admin_page() {
        do_action('codex_admin_page');
    }

    function class_show_forms() {
        require_once(CODEX_PATH . '/includes/pages/class-show-forms.php');
    }

    function class_edit_forms() {
        require_once(CODEX_PATH . '/includes/pages/class-edit-form.php');
    }

    function class_entire_forms() {
        require_once(CODEX_PATH . '/includes/pages/class-entire-form.php');
    }

    function class_about_forms() {
        require_once(CODEX_PATH . '/includes/pages/class-about.php');
    }
}
new Main_action();
