<?php

class Codex_form_DB {

    static public function new_form($data) {

        global $wpdb;

        $wpdb->insert($wpdb->prefix . 'codex_forms', $data);

        return $wpdb->insert_id;
    }

    static public function entry($id) {

        global $wpdb;

        $wpdb->insert($wpdb->prefix . 'codex_form_entry', array('form_id' => $id, 'date' => wp_date('Y-m-d H:i:s')));

        return $wpdb->insert_id;
    }

    static public function entry_value($entry_id, $field_id, $value) {

        global $wpdb;

        $wpdb->insert($wpdb->prefix . 'codex_form_entry_meta', array('entry_id' => $entry_id, 'field_id' => $field_id, 'value' => $value));

        return true;
    }

    static public function delete_form($row_id) {

        global $wpdb;

        if (empty($row_id)) {
            return false;
        }

        if ($wpdb->query($wpdb->prepare("DELETE FROM {$wpdb->prefix}codex_forms WHERE `id` = %d", $row_id)) === false) {
            return false;
        }

        return true;
    }

    static public function update_form($row_id, $data = []) {

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
        if ($wpdb->update($wpdb->prefix . 'codex_forms', $data, [$where => $row_id]) === false) {
            return false;
        }

        return true;
    }

    static public function get_forms() {

        global $wpdb;

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching
        return $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}codex_forms"
            )
        );
    }

    static public function get_form_by_id($row_id) {

        global $wpdb;

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching
        return $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}codex_forms WHERE `id` = %d LIMIT 1", // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
                (int) $row_id
            )
        );
    }

    static public function get_entry_by_form_id($row_id) {

        global $wpdb;

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching
        return $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}codex_form_entry WHERE `form_id` = %d ORDER BY id DESC",
                (int) $row_id
            )
        );
    }

    static public function get_entry_by_id($value) {

        global $wpdb;

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching
        return $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}codex_form_entry WHERE `id` in (" . implode(',', $value) . ")"
            )
        );
    }

    static public function get_entry_meta($where) {

        global $wpdb;

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching
        return $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM `{$wpdb->prefix}codex_form_entry_meta` 
                WHERE entry_id = {$where}",
            )
        );
    }

    // save edit entry to db
    static public function save_entry_value($id, $field_id, $value) {

        global $wpdb;

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching
        $test = $wpdb->query(
            $wpdb->prepare(
                "UPDATE `{$wpdb->prefix}codex_form_entry_meta`
                SET value = %s
                WHERE entry_id = %d AND field_id = %d",
                $value,
                $id,
                $field_id
            )
        );

        return $test;
    }

    // save edit entry to db
    static public function delete_entry($from, $where, $value) {

        global $wpdb;

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching
        return $wpdb->query(
            $wpdb->prepare(
                "DELETE FROM `{$wpdb->prefix}$from` WHERE {$where} in (" . implode(',', $value) . ")",
            )
        );

    }
}

new Codex_form_DB();
