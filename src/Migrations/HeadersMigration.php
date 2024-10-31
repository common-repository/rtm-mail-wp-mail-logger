<?php

namespace RtmMail\Migrations;

use Exception;


class HeadersMigration implements MigrationInterface
{
    
    const PRIORITY = 2;

    public function migrate()
    {
        global $wpdb;

                $sql = "ALTER TABLE " . RTM_MAIL_TABLE_PREFIX . "logs
                ADD headers TEXT NULL AFTER bcc";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        $result = $wpdb->query($sql);
        if (!$result) {
            throw new Exception($wpdb->last_error);
        }
    }

    public function rollback()
    {
        $sql = "ALTER TABLE " . RTM_MAIL_TABLE_PREFIX . "logs DROP COLUMN headers;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        dbDelta($sql);
    }

    public function is_migrated()
    {
        global $wpdb;
                $row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '" . RTM_MAIL_TABLE_PREFIX . "logs' AND column_name = 'headers'");
        return !empty($row);
    }

    public function get_priority()
    {
        return self::PRIORITY;
    }
}