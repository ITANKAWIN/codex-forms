<?php
class Codex_Show_Preview {

    public $form_data;

    public function __construct() {

        if (!class_exists('Codex_form_DB')) {
            require_once(CODEX_PATH . 'includes/DB/class-db.php');
        }

        add_shortcode('codex_form_preview', array($this, 'preview'));

        // Check preview page and include style
        $this->is_preview_page();
    }

    public function preview($atts = array()) {
        ob_start();
        $this->preview_style();
        $this->view($atts['id']);
        return ob_get_clean();
    }

    public function is_preview_page() {

        // Only proceed for the form preview page.
        if (!empty($_GET['codex_form_preview'])) { // phpcs:ignore

            $form_id = \absint($_GET['codex_form_preview']); // phpcs:ignore WordPress.Security.NonceVerification.Recommended


            // Fetch form details for the entry.
            $this->form_data = Codex_form_DB::get_form_by_id($form_id);

            // Check valid form was found.
            if (empty($this->form_data)) {
                return false;
            }


            add_filter('the_title', array($this, 'the_title'), 100, 1);

            add_filter('the_content', array($this, 'the_content'), 999);

            add_filter('get_the_excerpt', array($this, 'the_content'), 999);

            // add_filter('template_include', array($this, 'template_include'));

            // add_filter('home_template_hierarchy', array($this, 'template_include'));

            // add_filter('frontpage_template_hierarchy', array($this, 'template_include'));

            add_filter('post_thumbnail_html', '__return_empty_string');
        }
    }

    public function the_title($title) {

        $title = $this->form_data->name;

        return $title;
    }

    public function the_content() {

        if (
            !isset($this->form_data->id)
        ) {
            return '';
        }

        $content = do_shortcode('[codex_form_preview id="' . absint($this->form_data->id) . '"]');

        return $content;
    }

    public function template_include($templates) {
        return array('page.php', 'single.php', 'index.php');
    }

    public function preview_style() {

        wp_enqueue_script('jquery-ui-core');

        wp_enqueue_style('codex-preview', CODEX_URL . 'assets/public/css/codex-style.css', __FILE__, codex_css_ver);

        wp_enqueue_script('codex-preview', CODEX_URL . 'assets/public/js/codex-style.js', __FILE__, codex_js_ver);

        wp_enqueue_style('codex-semantic', CODEX_URL . 'assets/admin/semantic-ui/semantic.min.css', __FILE__);

        wp_enqueue_script('codex-semantic', CODEX_URL . 'assets/admin/semantic-ui/semantic.min.js', __FILE__);

        $strings = array(
            'ajax_url' => admin_url('admin-ajax.php'),
        );

        wp_localize_script(
            'codex-preview',
            'codex_admin',
            $strings
        );
    }

    public static function view($id) {

        // Fetch form details for the entry.
        $form_data = Codex_form_DB::get_form_by_id($id, 'wp_codex_forms');
        $form_content = json_decode(stripslashes($form_data->config), true);
        echo "<form method='POST' enctype='multipart/form-data' id='{$form_data->id}' class='codex_forms_form'>";
        echo "<div class='layout-panel' data-template='{$form_content['setting']['template']}'>";
        echo "<div class='msg_errors'></div>";
        echo "<input type='hidden' name='form_id' value='{$form_data->id}'>";
        if (!empty($form_content['panels'])) {
            $row = 0;
            if (!empty($form_content['panels'])) {
                $rows = explode("|", $form_content['panels']);
            } else {
                $rows = array('12');
            }

            if (!empty($rows)) {
                foreach ($rows as $row_in => $columns) {
                    echo "<div class='layout-row'>";
                    $row += 1;
                    $columns = explode(':', $columns);
                    foreach ($columns as $column => $span) {
                        $column += 1;
                        echo "<div class='col-{$span}'>";
                        echo "<div class='layout-column'>";
                        if (!empty($form_content['panel'])) {
                            foreach ($form_content['panel'] as $field => $panel) {
                                foreach ($form_content['fields'] as $fields) {
                                    if ($field == $fields['id'] && $panel == $row . ":" . $column) {
                                        echo "<div class='field-row'>";
                                        echo apply_filters("codex_form_preview_{$form_content['fields'][$fields['id']]['type']}", $form_content['fields'][$fields['id']]);
                                        echo "</div>";
                                    }
                                }
                            }
                        }
                        echo "</div>";
                        echo "</div>";
                    }
                    echo "</div>";
                }
            }
        }
        echo "</div>";
        echo "</form>";
    }
}

new Codex_Show_Preview();
