<?php

class Codex_Install {

    public function activate() {
        global $wpdb;
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        // Never assume the prefix
        $table_name = $wpdb->prefix . 'codex_form';

        $charset_collate = '';

        if (!empty($wpdb->charset)) {
            $charset_collate .= "DEFAULT CHARACTER SET {$wpdb->charset}";
        }
        if (!empty($wpdb->collate)) {
            $charset_collate .= " COLLATE {$wpdb->collate}";
        }

        if ($wpdb->get_var("show tables like '$table_name'") != $table_name) {
            $sql = "CREATE TABLE {$table_name} (
                `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				`entry_id` int(11) NOT NULL,
				`field_id` varchar(20) NOT NULL,
				`name` varchar(255) NOT NULL DEFAULT '',
				`value` longtext NOT NULL,
				PRIMARY KEY (`id`),
				KEY `form_id` (`entry_id`),
				KEY `field_id` (`field_id`),
				KEY `name` (`name`)
            ) {$charset_collate};";

            dbDelta($sql);
        }
    }
}

new Codex_Install();
