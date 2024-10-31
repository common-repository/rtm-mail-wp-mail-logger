<?php

namespace RtmMail;

use Exception;

class Migration
{
    
    const OPTION_NAME = "rtm_mail_migration_version";
    
    private $version;
    
    private $notice_messages;

    public function __construct($plugin_version)
    {
                $this->version = $plugin_version;
    }

    
    public function check()
    {
        $migration_version = get_option(self::OPTION_NAME, '');
        if (empty($migration_version) || version_compare($this->version, $migration_version, '>')) {
                        $migration_files = array_diff(scandir(__DIR__ . '/Migrations/'), ['..', '.']);
                        $migration_classes = [];

                        foreach ($migration_files as $file) {
                                $file = str_replace('.php', '', $file);
                                if (class_exists("RtmMail\Migrations\\" . $file)) {
                    $migration_classes[] = "RtmMail\Migrations\\" . $file;
                }
            }

                        $this->notice_messages = [];
                        $migrations = [];

                        foreach ($migration_classes as $migration_class) {
                                if (class_implements($migration_class, 'RtmMail\Migrations\MigrationInterface')) {
                                        $migrations[] = new $migration_class();
                }
            }

                        usort($migrations, function ($a, $b) {
                return ($a->get_priority() < $b->get_priority()) ? -1 : 1;
            });

                        $success = [];

                        foreach ($migrations as $migration) {
                                if (!$migration->is_migrated()) {
                    try {
                        $migration->migrate();
                        $success[] = true;
                        do_action('rtmmail_migration_success', get_class($migration));
                        $this->notice_messages[] = ['type' => 'success', 'text' => 'Migration of class <code>' . get_class($migration) . '</code> was successful'];
                    } catch (Exception $e) {
                        $success[] = false;
                        do_action('rtmmail_migration_failed', get_class($migration), $e->getMessage());
                        $this->notice_messages[] = ['type' => 'error', 'text' => 'Migration of class <code>' . get_class($migration) . '</code> failed due to an error - <code>' . $e->getMessage() . '</code>'];
                        break;
                    }
                }
            }
                        if (!empty($this->notice_messages)) {
                add_action('admin_notices', [$this, 'render_notice']);
            }
                        if (!in_array(false, $success, true)) {
                $this->update_version();
            }
        }
    }

    
    private function update_version()
    {
        update_option(self::OPTION_NAME, $this->version);
    }

    
    public function render_notice()
    {
        foreach ($this->notice_messages as $message) {
            echo '<div class="notice notice-' . esc_attr($message['type']) . ' is-dismissible">';
            echo '<p><strong>WP Mail Logger:</strong> ' . esc_attr($message['text']) . '</p>';
            echo '</div>';
        }
    }
}