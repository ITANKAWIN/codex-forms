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
    require_once(CODEX_PATH . '/assets/admin/admin.php');
}

require_once(CODEX_PATH . '/includes/setting.php');
require_once(CODEX_PATH . '/includes/main-action.php');
require_once(CODEX_PATH . '/includes/ajax-action.php');
require_once(CODEX_PATH . '/includes/class-fields.php');

function hook_style() {
    global $post;
    if (
        is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'codex_preview')
    ) {
?>
        <!-- <link rel="stylesheet" href="<?= CODEX_URL . 'assets/admin/css/codex-edit.css' ?>"> -->
        <link rel="stylesheet" href="<?= CODEX_URL . 'assets/admin/css/codex.css' ?>">
        <link rel="stylesheet" href="<?= CODEX_URL . 'assets/admin/semantic-ui/semantic.min.css' ?>">
    <?php
    }
}
add_action('wp_head', 'hook_style');

function hook_javascript() {
    global $post;
    if (
        is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'codex_preview')
    ) {
    ?>
        <script src="<?= CODEX_URL . 'assets/admin/js/codex.js' ?>"></script>
<?php
    }
}
add_action('wp_footer', 'hook_javascript');
