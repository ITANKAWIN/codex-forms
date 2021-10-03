<?php

class Admin_Script {

    function __construct() {
        $page = isset($_GET['page']) ? $_GET['page'] : '';
        if ($page !== 'codex-forms' || $page !== 'entire-codex-forms') {
            return;
        }
        $this->init();
    }

    function init() {
        add_action('admin_enqueue_scripts', array($this, 'codex_admin_scripts'));

        add_action('admin_enqueue_scripts', array($this, 'codex_semantic_ui_scripts'));

        add_action('init', array($this, 'codex_jquery_ui'));

        add_action('admin_enqueue_scripts', array($this, 'javascript_api'));
    }

    function javascript_api() {
        $strings = array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'config_field' => "<div class='config-field'><i class='icon edit'></i></div>",
            'delete_field' => "<div class='delete-field'><i class='icon trash'></i></div>",
            'confirm_remove_option' => "Are you sure want to delete option this?",
            'confirm_remove_option_alert' => "This item must contain at least one option."
        );

        wp_localize_script(
            'codex-admin',
            'codex_admin',
            $strings
        );
    }

    function codex_admin_scripts() {
        wp_enqueue_style('codex-admin', CODEX_URL . 'assets/admin/css/codex.css', __FILE__, '1.0.0');
        wp_enqueue_style('codex-sidebar', CODEX_URL . 'assets/admin/css/sidebar.css', __FILE__, '1.0.0');
        wp_enqueue_style('codex-edit', CODEX_URL . 'assets/admin/css/codex-edit.css', __FILE__, '1.0.0');

        wp_enqueue_script('codex-admin', CODEX_URL . 'assets/admin/js/codex.js', __FILE__, array('jquery'), '1.0.0', true);
        wp_enqueue_script('codex-sidebar', CODEX_URL . 'assets/admin/js/sidebar.js', __FILE__, array('jquery'), '1.0.0', true);
        wp_enqueue_script('codex-edit', CODEX_URL . 'assets/admin/js/codex-edit.js', __FILE__, array('jquery'), '1.0.0', true);

    }

    function codex_semantic_ui_scripts() {
        wp_enqueue_style('codex-semantic', CODEX_URL . 'assets/admin/semantic-ui/semantic.min.css', __FILE__);

        wp_enqueue_script('codex-semantic', CODEX_URL . 'assets/admin/semantic-ui/semantic.min.js', __FILE__);
    }

    function codex_jquery_ui() {
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-widget');
        wp_enqueue_script('jquery-ui-mouse');
        wp_enqueue_script('jquery-ui-tabs');
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_script('jquery-ui-draggable');
        wp_enqueue_script('jquery-ui-droppable');
        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_script('jquery-ui-resize');
    }
}
new Admin_Script();
