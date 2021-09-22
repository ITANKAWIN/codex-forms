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
            'codex_form_value',
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
				`name` varchar(18) NOT NULL DEFAULT '',
				`type` varchar(255) NOT NULL DEFAULT '',
				`config` longtext NOT NULL,
                `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
				PRIMARY KEY (`id`)
				) " . $this->charset_collate . ";";
        dbDelta($forms_table);
    }

    protected function codex_form_value() {

        $value_table = "CREATE TABLE `" . $this->wpdb->prefix .
            "codex_form_value` (
                `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				`form_id` int(11) NOT NULL,
				`field_id` varchar(20) NOT NULL,
				`name` varchar(255) NOT NULL DEFAULT '',
				`value` longtext NOT NULL,
				PRIMARY KEY (`id`),
				KEY `form_id` (`form_id`),
				KEY `field_id` (`field_id`),
				KEY `name` (`name`)
            ) " . $this->charset_collate . ";";

        dbDelta($value_table);
    }
}
