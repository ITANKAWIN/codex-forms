<?php

class Codex_Install {


    public function activate() {
        global $wpdb;

        if (!class_exists('Codex_Forms_DB_Tables')) {
            require_once(CODEX_PATH . 'includes/db/class-tables.php');
        }

        new Codex_Forms_DB_Tables($wpdb);
    }
    
}

new Codex_Install();
