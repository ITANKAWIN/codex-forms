<?php

class Class_Export_Forms {
    /**
     * Constructor
     */
    function __construct() {
        if (isset($_GET['export'])) {
            // $this->generate_table();

            header("Content-Type: text/csv charset=utf-8");
            header("Content-Disposition: attachment; filename=test.csv");
            $output = fopen("php://output", 'w');

            $entry_title = array();
            $delimiter = ',';
            $entrys = Codex_form_DB::get_entry($_GET['export']);

            $last_entry = $entrys[0];

            $entry_meta = Codex_form_DB::get_entry_meta($last_entry->id);

            foreach ($entry_meta as $entry_val) {
                array_push($entry_title, $entry_val->field_id);
            }
            fputcsv($output, $entry_title, $delimiter);
            fclose($output);
        }
    }

    /**
     * Converting data to CSV
     */
    public function generate_table() {
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
            foreach ($entry_val as $field_id) {

                $output = "<td>" . $entry_val[$entry->id][$this->entry_title[$i]] . "</td>";
                echo $output;
                $i++;
            }
            echo "</tr>";
        }
    }
}

new Class_Export_Forms();
