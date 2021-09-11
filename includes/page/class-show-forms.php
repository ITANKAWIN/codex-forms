<?php

class Codex_Show_Forms
{
    public $forms;
    function __construct()
    {

        $this->get_forms();

        $this->init();
    }

    function init()
    {

        add_action('codex_show_forms', array($this, 'view'));

        do_action('codex_show_forms');
    }

    function view()
    {
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
            <table class="ui inverted olive table">
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
                                    {$key->post_title}
                                    <a class='menu-button' href='#'>View</a> 
                                    <a class='menu-button' href='" . admin_url('admin.php?page=codex-forms&view=edit&form_id=' . $key->ID) . "'>Edit</a> 
                                    <a class='menu-button delete-form ' data-id='" . $key->ID . "'>Delete</a>
                                </h5>
                                </div>
                            </td>
                        ";
                        echo "<td>short code</td>";
                        echo "<td>{$key->post_date}</td>";
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


    function get_forms()

    {

        $form = array(

            "post_type" => "codex-forms",

        );

        $this->forms = get_posts($form);
    }
}







new Codex_Show_Forms();
