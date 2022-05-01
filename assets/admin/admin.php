<?php

class Admin_Script {

    function __construct() {
        $page = isset($_GET['page']) ? $_GET['page'] : '';
        if ($page !== 'codex-forms' && $page !== 'entire-codex-forms' && $page !== 'about-codex-forms') {
            return;
        }

        $this->init();
    }

    function init() {
        add_action('admin_enqueue_scripts', array($this, 'codex_admin_scripts'));

        add_action('admin_enqueue_scripts', array($this, 'codex_semantic_ui_scripts'));

        add_action('admin_enqueue_scripts', array($this, 'codex_DataTables_scripts'));

        add_action('init', array($this, 'codex_jquery_ui'));

        add_action('admin_enqueue_scripts', array($this, 'javascript_api'));

        // field image select wp media file
        add_action('admin_enqueue_scripts', array($this, 'load_wp_media_files'));
    }

    function javascript_api() {
        $strings = array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'element_addRow' => "<div class='layout-row'><div class='col-12'><div class='layout-column'></div></div></div>",
            'config_field' => "<div class='config-field'><i class='icon edit'></i></div>",
            'delete_field' => "<div class='delete-field'><i class='icon trash'></i></div>",
            'confirm_remove_option' => "Are you sure want to delete option this?",
            'confirm_remove_option_alert' => "This item must contain at least one option.",
            'field_login' => Codex_Fields::field_types_login(),
            'field_register' => Codex_Fields::field_types_register(),
        );

        wp_localize_script(
            'codex-admin',
            'codex_admin',
            $strings
        );
    }

    function codex_admin_scripts() {
        wp_enqueue_style('codex-admin', CODEX_URL . 'assets/admin/css/codex.min.css', __FILE__, '1.0.1');
        wp_enqueue_style('codex-sidebar', CODEX_URL . 'assets/admin/css/sidebar.min.css', __FILE__, '1.0.1');
        wp_enqueue_style('codex-edit', CODEX_URL . 'assets/admin/css/codex-edit.min.css', __FILE__, '1.0.1');

        wp_enqueue_script('codex-admin', CODEX_URL . 'assets/admin/js/codex.min.js', __FILE__, array('jquery'), '1.0.1', true);
        wp_enqueue_script('codex-sidebar', CODEX_URL . 'assets/admin/js/sidebar.min.js', __FILE__, array('jquery'), '1.0.1', true);
        wp_enqueue_script('codex-edit', CODEX_URL . 'assets/admin/js/codex-edit.min.js', __FILE__, array('jquery'), '1.0.1', true);
        wp_enqueue_script('codex-entire', CODEX_URL . 'assets/admin/js/codex-entire.min.js', __FILE__, array('jquery'), '1.0.1', true);
    }

    function codex_semantic_ui_scripts() {
        wp_enqueue_style('codex-semantic', CODEX_URL . 'assets/admin/semantic-ui/semantic.min.css', __FILE__);

        wp_enqueue_script('codex-semantic', CODEX_URL . 'assets/admin/semantic-ui/semantic.min.js', __FILE__);
    }

    function codex_DataTables_scripts() {
        wp_enqueue_style('codex-DataTables', CODEX_URL . 'assets/admin/DataTables/datatables.min.css', __FILE__);

        wp_enqueue_script('codex-DataTables', CODEX_URL . 'assets/admin/DataTables/datatables.min.js', __FILE__);

        wp_enqueue_style('codex-DataTables-dateTime', CODEX_URL . 'assets/admin/DataTables/dataTables.dateTime.min.css', __FILE__);

        wp_enqueue_script('codex-DataTables-dateTime', CODEX_URL . 'assets/admin/DataTables/dataTables.dateTime.min.js', __FILE__);
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

    function load_wp_media_files() {
        wp_enqueue_media();
        
    }
}
new Admin_Script();
