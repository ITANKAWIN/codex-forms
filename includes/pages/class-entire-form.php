<?php
class Codex_Entire_forms {

    private $codex_forms = 'wp_codex_forms';

    private $forms;

    function __construct() {

        $this->forms = Codex_form_DB::get_forms('form', 'active');

        add_action('codex_entire_form', array($this, 'content'));

        do_action('codex_entire_form');
    }

    function content() {
        print_r($this->forms);
?>
        <div class="ui menu massive">
            <div href="#" class="item">
                <img class="logo" src="<?= CODEX_URL ?>assets/image/codex-plugin_logo.png">
            </div>
            <div class="item header">
                Codex-Forms
            </div>
            <div class="item">
                <select class="ui dropdown" id="select-form">
                    <option>-- Select Form --</option>
                    <?php foreach ($this->forms as $form) {
                        echo "<option value='{$form->id}'>{$form->name}</option>";
                    } ?>
                </select>
            </div>
        </div>
        <div class="ui form">
            <div class="fields">
                <div class="three wide field">
                    <select class="ui dropdown" name="actions" id="">
                        <option>Bulk actions</option>
                        <option>Move to Trash</option>
                        <option>Export</option>
                    </select>
                </div>
                <div class="one wide field">
                    <button class="Medium ui primary basic button">Apply</button>
                </div>
                <div class="two wide field">
                    <input type="text" placeholder="Begin Date">
                </div>
                <div class="two wide field">
                    <input type="text" placeholder="End Date">
                </div>
                <div class="one wide field">
                    <button class="Medium ui primary basic button">Filter</button>
                </div>
                <div class="two wide field">
                </div>
                <div class="three wide field">
                    <input type="text" name="search" id="">
                </div>
                <div class="one wide field">
                    <button class="Medium ui primary basic button">Search</button>
                </div>
            </div>
        </div>
        <table class="ui table">
            <thead>
                <tr>
                    <th class="one wide"><input type="checkbox"></th>
                    <th class="one wide">ID</th>
                    <th class="six wide">Form Title</th>
                    <th class="one wide">
                        <h5 class="ui center aligned">Entries</h5>
                    </th>
                    <th class="three wide">Shortcodes</th>
                    <th class="two wide">Date</th>
                </tr>
            </thead>
            <tbody id="entire-val">
                <!-- <tr>
                    <td><input type="checkbox"></td>
                    <td>1</td>
                    <td>Customer</td>
                    <td>
                        <a href="#">
                            <h5 class="ui center aligned">0</h5>
                        </a>
                    </td>
                    <td>[codex_form_preivew id=2]</td>
                    <td>30:10:2021</td>
                </tr> -->
            </tbody>
        </table>
        <div class="ui centered grid">
            <div class="ui right floated pagination menu">
                <a class="icon item">
                    <i class="left chevron icon"></i>
                </a>
                <a class="item">1</a>
                <a class="item">2</a>
                <a class="item">3</a>
                <a class="icon item">
                    <i class="right chevron icon"></i>
                </a>
            </div>
        </div>
<?php
    }
}

new Codex_Entire_forms();
