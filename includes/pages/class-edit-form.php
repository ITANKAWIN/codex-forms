<?php
class Codex_Edit_form {

    private $form_id;
    private $form;
    private $form_config;

    function __construct() {

        if (empty($_GET['form_id'])) {
            return;
        }

        $this->form_id = $_GET['form_id'];

        $this->init();

        add_action('codex_edit_form', array($this, 'content'));

        do_action('codex_edit_form');
    }

    function init() {

        $this->form = Codex_form_DB::get_form_by_id($this->form_id);

        if (empty($this->form)) {
            return;
        }

        $this->form_config = json_decode(stripslashes($this->form->config), true);

        // echo "<pre>";
        // print_r($this->form_config);
        // echo "</pre>";
    }

    function load_field() {

        echo '<div class="layout-panel">';
        echo '<input type="hidden" id="form_id" name="id" value="' . $this->form->id . '">';
        if (!empty($this->form_config['panels'])) {
            $row = 0;
            echo '<input type="hidden" name="panels" value="' . $this->form_config['panels'] . '">';
            if (!empty($this->form_config['panels'])) {
                $rows = explode("|", $this->form_config['panels']);
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
                        if (!empty($this->form_config['panel'])) {
                            foreach ($this->form_config['panel'] as $field => $panel) {
                                foreach ($this->form_config['fields'] as $fields) {
                                    if ($field == $fields['id'] && $panel == $row . ":" . $column) {
                                        echo '<div class="field-row ui segment" data-field-type="' . $fields['type'] . '" data-field-id="' . $fields['id'] . '">';
                                        echo apply_filters("codex_form_preview_{$this->form_config['fields'][$fields['id']]['type']}", $this->form_config['fields'][$fields['id']]);
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
        } else {
            echo '<input type="hidden" name="panels" value="12">';
            echo '<div class="layout-row row">';
            echo '<div class="col-12">';
            echo '<div class="layout-column">';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
        echo '</div>';
    }

    function content() {

?>

        <div class="ui stackable menu massive ">
            <div href="#" class="item">
                <img class="logo" src="<?= CODEX_URL ?>assets/image/codex-plugin_logo.png">
            </div>
            <div class="item header">
                Codex-Forms
            </div>
            <div class="item">
                <div class="ui input">
                    <input type="text" id="form_name" name="form_name" value="<?= !empty($this->form) ? $this->form->name : '' ?>" placeholder="Form name">
                </div>
            </div>
            <div class="right item">
                <button class="ui orange button new-form" id="save_form"><i class="plus icon"></i> Save</button>
            </div>
            <div class="item">
                <a class="ui olive button" href="<?= home_url() . '/?codex_form_preview=' . $this->form->id ?>" target="_blank"><i class="eye icon"></i> Preview</a>
            </div>
            <div class="item">
                <button class="circular ui icon button blue" id="sidebarCollapse">
                    <i class="plus circle icon"></i>
                </button>
            </div>
        </div>


        <form id="panel" method="post">
            <div class="wrapper">
                <div id="content">
                    <div class="ui container">
                        <?php

                        $this->load_field();

                        ?>
                        <div class="add-row">
                            <div class="panel-add" id="add-row">
                                <i class="dashicons dashicons-plus-alt"></i>
                                <b>Add row</b>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <nav id="sidebar">
                    <div class="ui grid">
                        <div class="column">
                            <div class="ui menu two item top yellow  inverted">
                                <a class="active yellow item" data-tab="fields"><i class="icon sign"></i>Fields</a>
                                <a class="yellow item" data-tab="config"><i class="icon sign"></i>Config</a>
                            </div>
                            <div class="ui tab active " data-tab="fields">
                                <div class="tool-bar" id="tool-bar">
                                    <div class="ui vertical menu size">
                                        <div class="ui header size">Basic</div>
                                        <div class="item size">
                                            <?php

                                            $field_types = Codex_Fields::init();

                                            foreach ($field_types as $field) {
                                                echo "<div class='field-item' data-field-type='{$field['type']}'>";
                                                echo "<i class='{$field['icon']}'></i>" . $field['type'];
                                                echo "</div>";
                                            }

                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="ui tab" data-tab="config">
                                <div class="ui form">
                                    <div class="config-fields">
                                        <?php

                                        if (isset($this->form_config['fields'])) {

                                            foreach ($this->form_config['fields'] as $fields) {

                                                echo apply_filters("codex_form_config_{$this->form_config['fields'][$fields['id']]['type']}", $this->form_config['fields'][$fields['id']]);
                                            }
                                        }

                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </nav>
            </div>
        </form>

<?php

    }
}



new Codex_Edit_form();
