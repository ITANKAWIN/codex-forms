<?php
class preview {

    public $form_data;

    public function __construct() {

        add_shortcode('codex_form_preview', array($this, 'preview'));

        if (!$this->is_preview_page()) {
            return;
        }

        $this->hooks();
    }

    function preview($atts = array()) {
        ob_start();
        $this->view($atts['id']);
        return ob_get_clean();
    }

    public function is_preview_page() {

        // Only proceed for the form preview page.
        if (empty($_GET['codex_form_preview'])) { // phpcs:ignore
            return false;
        }

        $form_id = \absint($_GET['codex_form_preview']); // phpcs:ignore WordPress.Security.NonceVerification.Recommended


        // Fetch form details for the entry.
        $this->form_data = get_post(
            $form_id
        );

        // Check valid form was found.
        if (empty($this->form_data)) {
            return false;
        }

        return true;
    }

    public function hooks() {

        \add_filter('the_title', array($this, 'the_title'), 100, 1);

        \add_filter('the_content', array($this, 'the_content'), 999);

        \add_filter('get_the_excerpt', array($this, 'the_content'), 999);

        \add_filter('template_include', array($this, 'template_include'));

        \add_filter('post_thumbnail_html', '__return_empty_string');
    }

    public function the_title($title) {

        if (
            in_the_loop()
        ) {
            $title = $this->form_data->post_title;
        }

        return $title;
    }

    public function the_content() {

        if (
            !isset($this->form_data->ID)
        ) {
            return '';
        }

        $content = do_shortcode('[codex_form_preview id="' . absint($this->form_data->ID) . '"]');

        return $content;
    }

    public function template_include() {

        return locate_template(array('admin.php', 'single.php', 'index.php'));
    }

    public static function view($id) {

        $form_data = get_post($id);

        $form_content = json_decode(stripslashes($form_data->post_content), true);

        echo '<div class="layout-panel">';
        echo '<input type="hidden" id="form_id" name="id" value="' . $form_data->ID . '">';
        if (!empty($form_content['panels'])) {
            $row = 0;
            echo '<input type="hidden" name="panels" value="' . $form_content['panels'] . '">';
            if (!empty($form_content['panels'])) {
                $rows = explode("|", $form_content['panels']);
            } else {
                $rows = array('12');
            }

            if (!empty($rows)) {
                foreach ($rows as $row_in => $columns) {
                    echo '<div class="layout-row">';
                    $row += 1;
                    $columns = explode(':', $columns);
                    foreach ($columns as $column => $span) {
                        $column += 1;
                        echo '<div class="col-' . $span . '">';
                        echo '<div class="layout-column">';
                        if (!empty($form_content['panel'])) {
                            foreach ($form_content['panel'] as $field => $panel) {
                                foreach ($form_content['fields'] as $fields) {
                                    if ($field == $fields['id'] && $panel == $row . ":" . $column) {
                                        echo '<div class="field-row" data-field-type="' . $fields['type'] . '" data-field-id="' . $fields['id'] . '">';
                                        echo apply_filters("codex_form_preview_{$form_content['fields'][$fields['id']]['type']}", $form_content['fields'][$fields['id']]);
                                        echo '<input type="hidden" name="panel[' . $fields['id'] . ']" class="panel" value="' . $row . ":" . $column . '">';
                                        echo '</div>';
                                    }
                                }
                            }
                        }
                        echo '</div>';
                        echo '</div>';
                    }
                    echo '</div>';
                }
            }
        }
        echo '</div>';
    }
}

new preview();