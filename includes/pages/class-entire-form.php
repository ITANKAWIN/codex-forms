<?php
class Codex_Entire_forms {

    private $forms;

    private $form_id;


    function __construct() {

        $this->forms = Codex_form_DB::get_forms('form', 'active');

        // add modal show entry value 
        add_action('codex_entire_form', array($this, 'modal_view'));

        // add modal edit entry value
        add_action('codex_entire_form', array($this, 'modal_edit'));

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
            echo "<input type='hidden' id='form_id' value='{$this->form_id}'>";
        ?>

            <div class="ui two column grid">
                <div class="column">
                    <select class="ui dropdown" name="actions" id="">
                        <option>Bulk actions</option>
                        <option>Move to Trash</option>
                        <option>Export</option>
                    </select>
                    <button class="Medium ui primary basic button">Apply</button>
                    <a href="?excel=<?= $this->form_id ?>" class="ui teal button" data-tooltip="Click here to export all form" data-position="bottom center"><i class="download icon"></i>Export All</a>
                </div>
                <div class="column">
                    <label for="min">From:</label>
                    <input type="text" id="min" name="min" placeholder='From date'>
                    <input type="text" id="max" name="max" placeholder='To date'>
                </div>

            </div>
            <?php $this->display(); ?>
        <?php
        } else {
            echo '<h3 class="ui center aligned header">Select form to see entry.</h3>';
        }
    }

    function display() {
        ?>
        <table class="ui celled table" id="entire_form">
            <thead>
                <th><input type='checkbox' id="selectAll"></th>
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
            echo "<td><input type='checkbox' class='cf-select' value='{$entry->id}'></td>";
            echo "<td>{$entry->id}</td>";
            $date_submitted = strtotime($entry->date);
            echo "<td>" . date("d F Y, H:i:s", $date_submitted) . "</td>";
            echo "<td >";
            echo "<button class='ui green button view-entry' data-entry-id='{$entry->id}'><i class='eye icon'></i>view</button>";
            echo "<button class='ui red button'><i class='trash icon'></i>trash</button>";
            echo "</td>";
            echo "</tr>";
        }
    }

    function modal_view() {
    ?>
        <div class="ui tiny modal modal-view">
            <i class="close icon"></i>
            <div class="header">
            </div>
            <div class="content">
                <table class="ui large celled table">
                </table>
            </div>
            <div class="actions">
                <div class="ui positive basic button entry-edit">
                    Edit
                </div>
                <div class="ui black deny right button">
                    Close
                </div>
            </div>
        </div>
    <?php
    }

    function modal_edit() {
    ?>
        <div class="ui tiny modal modal-edit">
            <i class="close icon"></i>
            <div class="header">
            </div>
            <div class="content">
                <form method="post" class='edit_entry_value'>
                    <table class="ui large celled table">
                    </table>
                </form>
            </div>
            <div class="actions">
                <div class="ui positive button entry-view">
                    View
                </div>
                <div class="ui right primary button entry-save">
                    Save
                </div>
            </div>
        </div>
<?php
    }
}

new Codex_Entire_forms();
