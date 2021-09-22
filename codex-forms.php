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
define('CODEX_PATH', trailingslashit(plugin_dir_path(__FILE__)));
define('CODEX_URL', trailingslashit(plugins_url('/', __FILE__)));

// When plugin activate, trigger install method.
register_activation_hook(__FILE__, ['Codex_Install', 'activate']);

if (is_admin()) {
    require_once(CODEX_PATH . '/assets/admin/admin.php');
}

require_once(CODEX_PATH . '/includes/setting.php');
require_once(CODEX_PATH . '/includes/main-action.php');
require_once(CODEX_PATH . '/includes/class-install.php');
require_once(CODEX_PATH . '/includes/class-ajax.php');
require_once(CODEX_PATH . '/includes/class-fields.php');
