<?php



class Codex_Edit_form

{

    public $form;

    private $form_content;



    function __construct()

    {

        $this->get_form();

        $this->init();
    }



    function init()

    {

        add_action('codex_edit_form', array($this, 'header'));

        do_action('codex_edit_form');
    }



    function get_form()

    {

        $form_id = $_GET['form_id'];



        $this->form = get_post($form_id);



        if (empty($this->form)) {

            return;
        }



        $this->form_content = json_decode(stripslashes($this->form->post_content), true);



        // echo "<pre>";
        // print_r($this->form_content);
        // echo "</pre>";
    }



    function load_field()

    {

        echo '<div class="layout-panel">';

        echo '<input type="hidden" id="form_id" name="id" value="' . $this->form->ID . '">';

        if (!empty($this->form_content['panels'])) {

            $row = 0;

            echo '<input type="hidden" name="panels" value="' . $this->form_content['panels'] . '">';

            if (!empty($this->form_content['panels'])) {

                $rows = explode("|", $this->form_content['panels']);
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
                        if (!empty($this->form_content['panel'])) {

                            foreach ($this->form_content['panel'] as $field => $panel) {

                                foreach ($this->form_content['fields'] as $fields) {

                                    if ($field == $fields['id'] && $panel == $row . ":" . $column) {

                                        echo '<div class="field-row in-panel" data-field-type="' . $fields['type'] . '" data-field-id="' . $fields['id'] . '">';

                                        echo apply_filters("codex_form_preview_{$this->form_content['fields'][$fields['id']]['type']}", $this->form_content['fields'][$fields['id']]);

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



    function header()

    {

?>

        <div class="ui stackable menu massive">
            <div href="#" class="item">
                <img class="logo" src="<?= CODEX_URL ?>assets/image/codex-plugin_logo.png">
            </div>
            <div class="item header">
                Codex-Forms
            </div>
            <div class="item">
                <input class="form-control me-2" type="text" id="form_name" name="form_name" value="<?= !empty($this->form) ? $this->form->post_title : '' ?>">
            </div>
            <div class="right item">
                <button class="ui orange button new-form" id="save_form"><i class="plus icon"></i> Save</button>
                <button class="ui button" id="sidebarCollapse">

                    <span class="dashicons dashicons-menu-alt3"></span>

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
                            <div class="ui segment">
                                <div class="ui menu two item top">
                                    <a class="active item" data-tab="fields"><i class="icon sign"></i>Fields</a>
                                    <a class="item" data-tab="config"><i class="icon sign"></i>Config</a>
                                </div>

                                <div class="ui tab active " data-tab="fields">
                                    <div class="tool-bar" id="tool-bar">

                                        <?php

                                        $field_types = Codex_Fields::init();

                                        foreach ($field_types as $field) {

                                            echo '<div class="field-item" data-field-type="' . $field['type'] . '">';

                                            echo $field['type'];

                                            echo '</div>';
                                        }

                                        ?>

                                    </div>

                                </div>
                                <div class="ui tab" data-tab="config">
                                    <div class="ui form">
                                        <div class="config-fields bg">
                                            <?php

                                            if (isset($this->form_content['fields'])) {

                                                foreach ($this->form_content['fields'] as $fields) {

                                                    echo apply_filters("codex_form_config_{$this->form_content['fields'][$fields['id']]['type']}", $this->form_content['fields'][$fields['id']]);
                                                }
                                            }

                                            ?>
                                        </div>
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
