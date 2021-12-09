<?php
class Codex_Entire_forms {

    private $codex_forms = 'wp_codex_forms';

    private $forms;

    private $form_id;

    private $entry_title = array();

    function __construct() {

        $this->forms = Codex_form_DB::get_forms('form', 'active');

        add_action('codex_entire_form', array($this, 'content'));

        do_action('codex_entire_form');
    }

    function content() {
        // print_r($this->forms);
?>
        <div class="ui menu massive">
            <div href="#" class="item">
                <img class="logo" src="<?= CODEX_URL ?>assets/image/codex-plugin_logo.png">
            </div>
            <div class="item header">
                Codex-Forms
            </div>
            <div class="item">
                <form action=""></form>
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
            <?php $this->display(); ?>
        </div>

        <?php
    }

    function display() {
        if (isset($_GET['form'])) {
            $this->form_id = $_GET['form'];
        ?>
            <table class="ui celled table" id="entire_form">
                <thead>
                    <th><input type='checkbox'></th>
                    <th>ID</th>
                    <th>Submitted</th>
                    <th></th>
                </thead>
                <tbody>
                    <?php $this->print_column_content(); ?>
                </tbody>
            </table>
<?php
        }
    }

    function print_column_content() {

        $entry_val = array();

        $entrys = Codex_form_DB::get_entry($this->form_id);

        foreach ($entrys as $entry) {
            echo "<tr>";
            echo "<td><input type='checkbox' id='$entry->id'></td>";
            echo "<td>$entry->id</td>";
            $date_submitted = strtotime($entry->date);
            echo "<td>" . date("d F Y, H:i:s", $date_submitted) . "</td>";
            echo "<td >";
            echo "<button class='ui green button'><i class='eye icon'></i>view</button>";
            echo "<button class='ui red button'><i class='trash icon'></i>trash</button>";
            echo "</td>";
            echo "</tr>";
        }
    }
}

new Codex_Entire_forms();
