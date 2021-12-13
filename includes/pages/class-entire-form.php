<?php
class Codex_Entire_forms {

    private $forms;

    private $form_id;


    function __construct() {

        $this->forms = Codex_form_DB::get_forms('form', 'active');

        add_action('codex_entire_form', array($this, 'content'));

        do_action('codex_entire_form');
    }

    function content() {
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
                    <option value="-">-- Select Form --</option>
                    <?php foreach ($this->forms as $form) {
                        echo "<option value='{$form->id}' " . ($_GET['form'] == $form->id ? 'selected' : '') . ">{$form->name}</option>";
                    } ?>
                </select>
            </div>
        </div>
        <?php
        if (isset($_GET['form'])) {
            $this->form_id = $_GET['form'];
        ?>
            <div class="ui two column grid">
                <div class="column">
                    <select class="ui dropdown" name="actions" id="">
                        <option>Bulk actions</option>
                        <option>Move to Trash</option>
                        <option>Export</option>
                    </select>
                    <button class="Medium ui primary basic button">Apply</button>
                    <a href="?excel=<?= $this->form_id ?>" class="ui teal button"><i class="download icon"></i>Export All</a>
                </div>
                <div class="column">
                    <label for="min">From:</label>
                    <input type="text" id="min" name="min" placeholder='From date'>
                    <input type="text" id="max" name="max" placeholder='To date'>
                    <button type="button" class="ui  button">Clear</button>
                </div>

            </div>
            <?php $this->display(); ?>
        <?php
        }
    }

    function display() {
        ?>
        <table class="ui celled table" id="entire_form">
            <thead>
                <th><input type='checkbox'></th>
                <th>ID</th>
                <th>Submitted</th>
                <th></th>
            </thead>
            <tbody>
                <?php
                $this->print_column_content();
                ?>
            </tbody>
        </table>
<?php
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
