<?php
class Class_Export_Forms {

    // The ID number of the form to be exported.
    private $form_id;

    // collect column head to be drawn out to show correctly
    private $entry_title = array();

    // Store an array of data to be inserted into excel.
    private $entry_content = array();

    function __construct() {
        // I use the download feature on the frontend so I use the init action hook.
        add_action('admin_init', array($this, 'print_excel'));
    }

    function print_excel() {

        // # check the URL in order to perform the downloading
        if (!isset($_GET['excel'])) {
            return false;
        }

        $this->form_id = $_GET['excel'];

        // # set the destination file
        $fileLocation = 'output.xlsx';

        $this->generate_data_header();

        $this->generate_data_content();

        // # call the class and generate the excel file from the $data
        $writer = new XLSXWriter();
        $writer->writeSheet($this->entry_content);
        $writer->writeToFile($fileLocation);

        // # prompt download popup
        header('Content-Description: File Transfer');
        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header("Content-Disposition: attachment; filename=" . basename($fileLocation));
        header("Content-Transfer-Encoding: binary");
        header("Expires: 0");
        header("Pragma: public");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header('Content-Length: ' . filesize($fileLocation));

        ob_clean();
        flush();

        readfile($fileLocation);
        unlink($fileLocation);
        exit;
    }

    function generate_data_header() {

        $header = array();

        $entrys = Codex_form_DB::get_entry($this->form_id);

        $last_entry = $entrys[0];

        $entry_meta = Codex_form_DB::get_entry_meta($last_entry->id);
        array_push($header, 'ID');
        foreach ($entry_meta as $entry_val) {
            array_push($header, $entry_val->field_id);
            array_push($this->entry_title, $entry_val->field_id);
        }

        array_push($this->entry_content, $header);
    }

    function generate_data_content() {

        array_push($this->entry_content, $this->entry_title);

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
            $entry_row = array();
            array_push($entry_row, $entry->id);

            foreach ($entry_val as $field_id) {
                $output = $entry_val[$entry->id][$this->entry_title[$i]];
                array_push($entry_row, $output);

                $i++;
            }

            array_push($this->entry_content, $entry_row);
        }
    }
}

new Class_Export_Forms();
