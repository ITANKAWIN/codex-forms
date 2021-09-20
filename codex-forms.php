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

define('CODEX_PATH', trailingslashit(plugin_dir_path(__FILE__)));
define('CODEX_URL', trailingslashit(plugins_url('/', __FILE__)));

if (is_admin()) {
    require_once(CODEX_PATH . '/assets/admin/admin.php');
}

// When plugin activate, trigger install method.
register_activation_hook(__FILE__, ['Codex_Install', 'activate']);

require_once(CODEX_PATH . '/includes/class-install.php');
require_once(CODEX_PATH . '/includes/setting.php');
require_once(CODEX_PATH . '/includes/main-action.php');
require_once(CODEX_PATH . '/includes/ajax-action.php');
require_once(CODEX_PATH . '/includes/class-fields.php');

register_activation_hook(
    __FILE__,
    static function () {
        // global $wpdb;
        // require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        // // Never assume the prefix
        // $table_name = $wpdb->prefix . 'codex_form';

        // $sql = "CREATE TABLE `${table_name}` 
        //         (
        //             `ID` INT(200) NOT NULL AUTO_INCREMENT ,
        //             `post_ID` INT(200) NOT NULL ,
        //             `author_name` VARCHAR(200) NOT NULL ,
        //             `title` VARCHAR(200) NOT NULL ,
        //             `description` VARCHAR(200) NOT NULL ,
        //             PRIMARY KEY (`ID`)) ENGINE = InnoDB;";

        // dbDelta($sql);
    }
);
