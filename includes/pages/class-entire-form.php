<?php
class Codex_Entire_forms {

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
            <table class="ui table" id="entire_form">
                <thead>
                    <?php $this->print_column_headers(); ?>
                </thead>
                <tbody>
                    <?php $this->print_column_content(); ?>
                </tbody>
            </table>
<?php
        }
    }

    function print_column_headers() {

        $entrys = Codex_form_DB::get_entry($this->form_id);

        $last_entry = $entrys[0];

        $entry_meta = Codex_form_DB::get_entry_meta($last_entry->id);

        echo "<th><input type='checkbox'></th>";
        echo "<th>ID</th>";
        foreach ($entry_meta as $entry_val) {
            array_push($this->entry_title, $entry_val->field_id);
            echo "<th>" . $entry_val->field_id . "</th>";
        }
    }

    function print_column_content() {

        $entry_val = array();

        $entrys = Codex_form_DB::get_entry($this->form_id);



        foreach ($entrys as $entry) {

            $entry_meta = Codex_form_DB::get_entry_meta($entry->id);

            foreach ($entry_meta as $meta_val) {
                $entry_val[$entry->id][$meta_val->field_id] = $meta_val->value;
            }
        }

        foreach ($entrys as $entry) {
            $i = 0;
            echo "<tr>";
            echo "<td><input type='checkbox'></td>";
            echo "<td>" . $entry->id . "</td>";
            $output = "<td></td>";
            foreach ($entry_val as $field_id ) {

                $output = "<td>" . $entry_val[$entry->id][$this->entry_title[$i]] . "</td>";
                echo $output;
                $i++;
            }
            echo "</tr>";
        }
        // echo "<pre>";
        // print_r($entry_val);
        // echo "</pre>";
    }
}
new Codex_Entire_forms();
