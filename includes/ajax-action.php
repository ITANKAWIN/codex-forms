<?php
function new_form() {

    $form_title = sanitize_text_field($_POST['title']);

    // $form_template = sanitize_text_field($_POST['template']);

    // $title_exists  = get_page_by_title($form_title, 'OBJECT', 'wpforms');

    if (empty($form_title)) {

        return false;
    }


    $data      = [

        'panels' => 12,

        'panel'  => [],

        'fields' => []

    ];



    // Merge args and create the form.

    $form = array(

        'post_title'   => $form_title,

        'post_status'  => 'publish',

        'post_type'    => 'codex-forms',

        'post_content' => wp_json_encode($data),

        'form_desc'  => '',

    );

    $form_id = wp_insert_post($form);

    $arg = array(
        "ID" => $form_id,
        'redirect' => add_query_arg(
            array(
                'view'    => 'edit',

                'form_id' => $form_id,
            ),
            admin_url('admin.php?page=codex-forms')
        ),
    );

    wp_send_json_success($arg);
}

add_action('wp_ajax_new_form', 'new_form');



function delete_form() {



    $form_id = sanitize_text_field($_POST['id']);

    if (empty($form_id)) {

        wp_send_json_error();
    }



    $arg = array(

        'redirect' => add_query_arg(

            array(),

            admin_url('admin.php?page=codex-forms')

        ),

    );



    if (wp_delete_post($form_id)) {

        wp_send_json_success($arg);
    }
}

add_action('wp_ajax_delete_form', 'delete_form');



function save_form() {

    $form_id = sanitize_text_field($_POST['id']);

    if (empty($form_id)) {

        wp_send_json_error();
    }

    $form_title = sanitize_text_field($_POST['title']);

    $form_post = json_decode(stripslashes($_POST['data']));

    $data      = [

        'fields' => [],

    ];

    if (!is_null($form_post) && $form_post) {

        foreach ($form_post as $post_input_data) {

            // For input names that are arrays (e.g. `menu-item-db-id[3][4][5]`),

            // derive the array path keys via regex and set the value in $_POST.

            preg_match('#([^\[]*)(\[(.+)\])?#', $post_input_data->name, $matches);



            $array_bits = array($matches[1]);



            if (isset($matches[3])) {

                $array_bits = array_merge($array_bits, explode('][', $matches[3]));
            }



            $new_post_data = [];



            // Build the new array value from leaf to trunk.

            for ($i = count($array_bits) - 1; $i >= 0; $i--) {

                if ($i === count($array_bits) - 1) {

                    $new_post_data[$array_bits[$i]] = wp_slash($post_input_data->value);
                } else {

                    $new_post_data = array(

                        $array_bits[$i] => $new_post_data,

                    );
                }
            }



            $data = array_replace_recursive($data, $new_post_data);
        }
    }



    $form = array(

        'ID' => $form_id,

        'post_title' => $form_title,

        'post_name' => $form_title,

        'post_content' => wp_json_encode($data, JSON_UNESCAPED_UNICODE),

    );



    $form_id = wp_update_post($form);



    //id form array to response

    $arg = array(

        "ID" => $form_id,

    );

    wp_send_json_success($arg);
}

add_action('wp_ajax_save_form', 'save_form');
