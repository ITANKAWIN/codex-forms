<?php
/*
Plugin Name: Codex-Forms
Description: This is my Codex-Forms ! It makes a new form!
Author: Kawin & Wattana
Text Domain: codex-forms
Version: 1.0
*/

if (!defined('ABSPATH')) : exit();
endif;

define('CODEX_PATH', trailingslashit(plugin_dir_path(__FILE__)));
define('CODEX_URL', trailingslashit(plugins_url('/', __FILE__)));

if (is_admin()) {
    require_once(CODEX_PATH . '/assets/admin.php');
}

require_once(CODEX_PATH . '/includes/setting.php');
require_once(CODEX_PATH . '/includes/main-action.php');
require_once(CODEX_PATH . '/includes/ajax-action.php');
require_once(CODEX_PATH . '/includes/class-fields.php');