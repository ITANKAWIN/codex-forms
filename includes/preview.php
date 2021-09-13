<?php
class preview {
    public static function view($id) {
        $form = get_post($id);

        if (empty($form)) {
            return;
        }

        $form_content = json_decode(stripslashes($form->post_content), true);

        echo '<div class="layout-panel">';
        echo '<input type="hidden" id="form_id" name="id" value="' . $form->ID . '">';
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
                                        echo '<div class="field-row ui segment" data-field-type="' . $fields['type'] . '" data-field-id="' . $fields['id'] . '">';
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
