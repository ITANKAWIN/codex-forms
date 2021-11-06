<?php

class Codex_Show_Forms {

    private $forms;

    function __construct() {

        $this->forms = Codex_form_DB::get_forms('form', 'active');

        add_action('codex_show_forms', array($this, 'view'));

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
                <button class="ui blue button"><i class="upload icon"></i> Import</button>
            </div>
        </div>
        <div class="ui container">
            <table class="ui inverted olive table" id="show_form">
                <thead>
                    <tr>
                        <th>Form name</th>
                        <th>Short code</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($this->forms as $key) {
                        echo "<tr class='option-show-form'>";
                        echo "
                            <td width='50%'>
                                <h5 class='ui header'>
                                    {$key->name}
                                    <a class='menu-button' href='" . home_url() . '/?codex_form_preview=' . $key->id . "' target='_blank'>View</a> 
                                    <a class='menu-button' href='" . admin_url('admin.php?page=codex-forms&view=edit&form_id=' . $key->id) . "'>Edit</a> 
                                    <a class='menu-button delete-form ' data-id='" . $key->id . "'>Delete</a>
                                </h5>
                                </div>
                            </td>
                        ";
                        echo "<td><a class='ui button short-code-copy' data-tooltip='Click here copy to clipboard' data-position='top left'>[codex_form_preivew id={$key->id}]</a></td>";
                        echo "<td>{$key->date}</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Modal -->
        <div class="ui modal small">
            <i class="close icon"></i>
            <div class="header">
                Form Template
            </div>
            <div class="content">
                <div class="ui special cards">
                    <div class="card">
                        <div class="blurring dimmable image">
                            <div class="ui dimmer">
                                <div class="content">
                                    <div class="center">
                                        <div class="ui negative message transition hidden">
                                            <i class="close icon"></i>
                                            <div class="header">
                                                Something went wrong
                                            </div>
                                            <p>input the form name
                                            </p>
                                        </div>
                                        <div class="ui left icon input">
                                            <input type="text" placeholder="Search..." id="form_name">
                                            <i class="wpforms icon"></i>
                                            <button class="ui button primary create-form">Create</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <img src="<?= CODEX_URL ?>assets/image/template/blank.png">
                        </div>
                        <div class="content">
                            <a class="header">Blank Form</a>
                            <div class="meta">
                                <span class="date">Created in Sep 2014</span>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="blurring dimmable image">
                            <div class="ui dimmer">
                                <div class="content">
                                    <div class="center">
                                        <div class="ui negative message transition hidden">
                                            <i class="close icon"></i>
                                            <div class="header">
                                                Something went wrong
                                            </div>
                                            <p>input the form name
                                            </p>
                                        </div>
                                        <div class="ui left icon input">
                                            <input type="text" placeholder="Search..." id="form_name">
                                            <i class="wpforms icon"></i>
                                            <button class="ui button primary create-form">Create</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <img src="<?= CODEX_URL ?>assets/image/template/register.png">
                        </div>
                        <div class="content">
                            <a class="header">Register Form</a>
                            <div class="meta">
                                <span class="date">Created in Aug 2014</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

<?php

    }
}







new Codex_Show_Forms();
