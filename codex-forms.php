<?php
/*
Plugin Name: Codex-Forms
Description: This is my Codex-Forms ! It makes a new form!
Author: Kawin & Wattana
Text Domain: codex-forms
Version: 1.0
*/

if (!defined('ABSPATH')) {
    exit();
}

// Path
define('CODEX_PATH', plugin_dir_path(__FILE__));
define('CODEX_URL', plugin_dir_url(__FILE__));

// When plugin activate, trigger install method.
register_activation_hook(__FILE__, ['Codex_Install', 'activate']);

if (is_admin()) {
    require_once(CODEX_PATH . '/assets/admin/admin.php');
}

require_once(CODEX_PATH . '/includes/setting.php');
require_once(CODEX_PATH . '/includes/main-action.php');
require_once(CODEX_PATH . '/includes/class-install.php');
require_once(CODEX_PATH . '/includes/class-ajax.php');
require_once(CODEX_PATH . '/includes/class-templates.php');
require_once(CODEX_PATH . '/includes/class-fields.php');

// # check the class is already loaded or not. If it is not loaded yet, we will load it.
if (!class_exists('Codex_form_DB')) {
    require_once(CODEX_PATH . '/includes/db/class-db.php');
}

if (!class_exists('XLSXWriter')) {
    include_once(CODEX_PATH . '/includes/XLSXWriter/xlsxwriter.class.php');
}