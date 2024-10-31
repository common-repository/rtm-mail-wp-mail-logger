<?php

namespace RtmMail\Migrations;

use Exception;


class FirstMigration implements MigrationInterface
{
    
    const PRIORITY = 1;

    public function migrate()
    {
        global $wpdb;
        
        $sql = "CREATE TABLE IF NOT EXISTS " . RTM_MAIL_TABLE_PREFIX . "logs (
                 id int(11) NOT NULL AUTO_INCREMENT,
                 subject varchar(255) NOT NULL,
                 sender varchar(255) NOT NULL,
                 receiver text NOT NULL,
                 cc text,
                 bcc text,
                 body text,
                 status enum('failed','sent','caught') NOT NULL DEFAULT 'caught',
                 attachments text,
                 created datetime DEFAULT NULL,
                 updated datetime DEFAULT NULL,
                 sent datetime DEFAULT NULL,
                 PRIMARY KEY (id)
                ) " . $wpdb->get_charset_collate() . ";";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        dbDelta($sql);

        if (!empty($wpdb->last_error)) {
            throw new Exception($wpdb->last_error);
        }
    }

    public function rollback()
    {
        $sql = "DROP TABLE " . RTM_MAIL_TABLE_PREFIX . "logs";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        dbDelta($sql);
    }

    public function is_migrated()
    {
        global $wpdb;
                return ($wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", RTM_MAIL_TABLE_PREFIX . "logs")) === RTM_MAIL_TABLE_PREFIX . "logs");
    }

    public function get_priority()
    {
        return self::PRIORITY;
    }
}