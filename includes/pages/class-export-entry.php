<?php
class Class_Export_Entry {

    // The ID number of the form to be exported.
    private $form_id;

    // ID of selected entry to export
    private $entry_id;

    // Name form to name file
    private $name;

    // collect column head to be drawn out to show correctly
    private $entry_title = array();

    // Store an array of data to be inserted into excel.
    private $entry_content = array();

    function __construct() {
        // I use the download feature on the frontend so I use the init action hook.
        add_action('admin_init', array($this, 'export_excel'));
    }

    function export_excel() {

        // # check the URL in order to perform the downloading
        if (!isset($_GET['export'])) {
            return false;
        }

        $this->form_id = $_GET['export'];

        if (isset($_GET['id'])) {
            $query  = explode(
                '&',
                $_SERVER['QUERY_STRING']
            );
            $params = array();

            foreach ($query as $param) {
                // prevent notice on explode() if $param has no '='
                if (strpos($param, '=') === false) $param += '=';

                list($name, $value) = explode('=', $param, 2);
                $params[urldecode($name)][] = urldecode($value);
            }

            $this->entry_id = $params['id'];
        }


        $this->generate_data_header();

        $this->generate_data_content();

        // set file name
        $file_name = $this->name . '.xlsx';

        // # call the class and generate the excel file from the $data
        $writer = new XLSXWriter();
        $writer->writeSheet($this->entry_content);
        $writer->writeToFile($file_name);

        // # prompt download popup
        header('Content-Description: File Transfer');
        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header("Content-Disposition: attachment; filename=" . basename($file_name));
        header("Content-Transfer-Encoding: binary");
        header("Expires: 0");
        header("Pragma: public");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header('Content-Length: ' . filesize($file_name));

        ob_clean();
        flush();

        readfile($file_name);
        unlink($file_name);
        exit;
    }

    function generate_data_header() {

        $header = array();

        $form = Codex_form_DB::get_form_by_id($this->form_id);

        // set file name
        $this->name = $form->name;

        $form_config = json_decode(stripslashes($form->config));

        if (empty($this->entry_id)) {
            $entrys = Codex_form_DB::get_entry_by_form_id($this->form_id);
        } else {
            $entrys = Codex_form_DB::get_entry_by_id($this->entry_id);
        }

        $last_entry = $entrys[0];

        $entry_meta = Codex_form_DB::get_entry_meta($last_entry->id);

        $meta_val = [];

        $i = 0;

        // foreach name field to entry meta value
        foreach ($entry_meta as $key => $value) {
            $field = $form_config->fields->{$value->field_id};
            $meta_val[$i] = $entry_meta[$key];

            // if field name is null, show field id and type
            $meta_val[$i]->name = ($field->name == '' ? $value->field_id . ' (' . $field->type . ')' : $field->name);
            $i++;
        }

        array_push($header, 'ID');
        foreach ($meta_val as $entry_val) {
            // header id field
            array_push($header, $entry_val->name);

            // header foreach id field
            array_push($this->entry_title, $entry_val->field_id);
        }

        array_push($this->entry_content, $header);
    }

    function generate_data_content() {

        $entry_val = array();

        if (empty($this->entry_id)) {
            $entrys = Codex_form_DB::get_entry_by_form_id($this->form_id);
        } else {
            $entrys = Codex_form_DB::get_entry_by_id($this->entry_id);
        }

        // extract value data
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

            foreach ($this->entry_title as $field_id) {
                $output = $entry_val[$entry->id][$this->entry_title[$i]];
                array_push($entry_row, $output);

                $i++;
            }

            array_push($this->entry_content, $entry_row);
        }
    }
}

new Class_Export_Entry();
