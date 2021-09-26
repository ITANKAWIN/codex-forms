<?php

class Codex_Forms_DB_Tables {

    protected $wpdb;
    protected $charset_collate;

    public function __construct(wpdb $wpdb) {
        $this->wpdb = $wpdb;

        $this->form_table();
    }

    protected function form_table() {
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        $this->charset_collate = '';

        if (!empty($this->wpdb->charset)) {
            $this->charset_collate .= "DEFAULT CHARACTER SET {$this->wpdb->charset}";
        }
        if (!empty($this->wpdb->collate)) {
            $this->charset_collate .= " COLLATE {$this->wpdb->collate}";
        }

        $forms_table_list = array(
            'forms',
            'codex_form_entry',
            'codex_form_entry_meta'
        );

        if ($this->wpdb->get_var("show tables like '$table_name'") != $table_name) {
            foreach ($forms_table_list as $table) {
                call_user_func(array($this, $table));
            }
        }
    }

    protected function forms() {

        $forms_table = "CREATE TABLE `" . $this->wpdb->prefix . "codex_forms` (
				`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				`name` varchar(55) NOT NULL DEFAULT '',
				`type` varchar(55) NOT NULL DEFAULT 'form',
				`config` longtext NOT NULL,
                `status` varchar(20) NOT NULL DEFAULT 'active',
                `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
				PRIMARY KEY (`id`)
				) " . $this->charset_collate . ";";
        dbDelta($forms_table);
    }

    protected function codex_form_entry() {

        $value_table = "CREATE TABLE `" . $this->wpdb->prefix .
            "codex_form_entry` (
                `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				`form_id` int(11) NOT NULL,
				`date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
				PRIMARY KEY (`id`)
            ) " . $this->charset_collate . ";";

        dbDelta($value_table);
    }

    protected function codex_form_entry_meta() {

        $value_table = "CREATE TABLE `" . $this->wpdb->prefix .
            "codex_form_entry_meta` (
                `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				`entry_id` int(11) NOT NULL,
				`field_id` varchar(20) NOT NULL,
				`value` longtext NOT NULL,
				PRIMARY KEY (`id`)
            ) " . $this->charset_collate . ";";

        dbDelta($value_table);
    }
}
