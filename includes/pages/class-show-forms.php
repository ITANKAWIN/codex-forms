<?php

class Codex_Show_Forms {

    private $forms;

    function __construct() {

        $this->forms = Codex_form_DB::get_forms('form', 'active');

        add_action('codex_show_forms', array($this, 'view'));

        add_action('codex_show_forms', array($this, 'modal_template'));

        add_action('codex_show_forms', array($this, 'modal_create_form'));

        do_action('codex_show_forms');
    }

    function view() {
?>
        <div class="ui stackable container menu massive">
            <div href="#" class="item">
                <img class="logo" src="<?= CODEX_URL ?>assets/image/codex-plugin_logo.png">
            </div>
            <div class="item header">
                Codex-Forms
            </div>
            <div class="item">
                <button class="ui orange button new-form"><i class="plus icon"></i> New Form</button>
            </div>
            <div class="right item">
                <button class="ui blue button btn_import_form"><i class="upload icon"></i> Import</button>
                <input type="file" id="import_form" accept="application/JSON" style="display: none">
            </div>
        </div>
        <div class="ui container">
            <table class="ui inverted olive table" id="show_form">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Form name</th>
                        <th>Short code</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($this->forms as $form) {
                    ?>
                        <tr class="option-show-form">
                            <td><?= $form->id ?></td>
                            <td width="50%">
                                <h5 class="ui header">
                                    <?= $form->name ?>
                                    <a class="menu-button" href="<?= home_url() ?>/?codex_form_preview=<?= $form->id ?>" target="_blank">View</a>
                                    <a class="menu-button" href="<?= admin_url('admin.php?page=codex-forms&view=edit&form_id=' . $form->id) ?>">Edit</a>
                                    <a class="menu-button duplicate-form" data-id="<?= $form->id ?>">Duplicate</a>
                                    <a class="menu-button delete-form" data-id="<?= $form->id ?>">Delete</a>
                                </h5>
                            </td>
                            <td><a class="ui button short-code-copy" data-tooltip="Click here copy to clipboard" data-position="top left">[codex_form_preview id=<?= $form->id ?>]</a></td>
                            <td><?= $form->date ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>

    <?php

    }

    function modal_template() {
    ?>
        <!-- Modal -->
        <div class="ui small modal modal_template">
            <i class="close icon"></i>
            <div class="header">
                Form Template
            </div>
            <div class="content">
                <div class="ui link cards">
                    <?php
                    $templates = Codex_Templates::templates();
                    foreach ($templates as $name => $template) {
                    ?>
                        <div class="card form-template" data-template-name="<?= $template['name'] ?>">
                            <div class="content">
                                <a class="header"><?= $template['name'] ?></a>
                                <div class="meta">
                                    <span class="date"><?= $template['description'] ?></span>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    <?php
    }

    function modal_create_form() {
    ?>
        <div class="ui mini modal modal_create_form">
            <i class="close icon"></i>
            <div class="header">Form name</div>
            <div class="content">
                <div class="ui fluid input">
                    <input type="text" placeholder="Enter name form..." id="form_name">
                </div>
            </div>
            <div class="actions">
                <div class="ui negative button">
                    No
                </div>
                <div class="ui positive right labeled icon button create-form">
                    Create
                    <i class="checkmark icon"></i>
                </div>
            </div>
        </div>
<?php
    }
}


new Codex_Show_Forms();
