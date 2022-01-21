<?php
class Class_Export_Import_Entry {

    // The ID number of the form to be exported.
    private $form_id;

    function __construct() {
        // I use the download feature on the frontend so I use the init action hook.
        add_action('admin_init', array($this, 'export_json'));
    }

    function export_json() {

        // # check the URL in order to perform the downloading
        if (!isset($_GET['export_json'])) {
            return false;
        }

        $this->form_id = $_GET['export_json'];

        $form = Codex_form_DB::get_form_by_id($this->form_id);

        // set name file
        $file_name = $form->name . ".json";

        $form_config = json_decode(stripslashes($form->config), true);

        $data = json_encode($form_config);

        // # prompt download popup
        header('Content-Type: application/json; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . basename($file_name));
        header('Expires: 0'); //No caching allowed
        header('Cache-Control: must-revalidate');
        header('Content-Length: ' . strlen($data));
        file_put_contents('php://output', $data);
    }
}

new Class_Export_Import_Entry();
