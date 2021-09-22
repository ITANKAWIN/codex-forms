<?php

class Codex_form_DB {

    static public function add($data, $table) {

        global $wpdb;

        $wpdb->insert($table, $data);

        return $wpdb->insert_id;
    }

    static public function delete($row_id, $table) {

        global $wpdb;

        if (empty($row_id)) {
            return false;
        }

        if ($wpdb->query($wpdb->prepare("DELETE FROM $table WHERE `id` = %d", $row_id)) === false) {
            return false;
        }

        return true;
    }

    static public function update($row_id, $data = [], $table, $where = '') {

        global $wpdb;

        // Row ID must be a positive integer.
        $row_id = absint($row_id);

        if (empty($row_id)) {
            return false;
        }

        if (empty($where)) {
            $where = 'id';
        }

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching
        if ($wpdb->update($table, $data, [$where => $row_id]) === false) {
            return false;
        }

        return true;
    }

    static public function get($table) {

        global $wpdb;

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching
        return $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM $table;",
            )
        );
    }

    static public function get_by_id($row_id, $table) {

        global $wpdb;

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching
        return $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM $table WHERE `id` = %d LIMIT 1;", // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
                (int) $row_id
            )
        );
    }

}

new Codex_form_DB();
