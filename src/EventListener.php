<?php

namespace RtmMail;

use Monolog\Logger;


class EventListener
{
    
    const CHANNEL_NAME = 'RTMMAIL';

    

    
    public function migration_success($migration_class)
    {
        $source = $this->get_source();
        do_action('wonolog.log', [
            'message' => "Migration of class <code>{$migration_class}</code> was successful",
            'channel' => self::CHANNEL_NAME,
            'level' => Logger::INFO,
            'context' => compact('migration_class', 'source'),
        ]);
    }

    
    public function migration_failed($migration_class, $error)
    {
        $source = $this->get_source();
        do_action('wonolog.log', [
            'message' => "Migration of class <code>{$migration_class}</code> failed because of an error: {$error}",
            'channel' => self::CHANNEL_NAME,
            'level' => Logger::ERROR,
            'context' => compact('migration_class', 'source'),
        ]);
    }

    
    public function log_deleted($log_id, $user_id)
    {
        $source = $this->get_source();
        $user_data = get_user_by('id', $user_id);
        do_action('wonolog.log', [
            'message' => "Email Log (#{$log_id}) has been deleted by {$user_data->display_name}",
            'channel' => self::CHANNEL_NAME,
            'level' => Logger::DEBUG,
            'context' => compact('log_id', 'user_id', 'source'),
        ]);
    }

    
    public function send_failed($log_id, $error)
    {
        $source = $this->get_source();
        do_action('wonolog.log', [
            'message' => "Failed to send email (#{$log_id}): {$error}",
            'channel' => self::CHANNEL_NAME,
            'level' => Logger::ERROR,
            'context' => compact('log_id', 'error', 'source'),
        ]);
    }

    
    public function send_success($log_id)
    {
        $source = $this->get_source();
        do_action('wonolog.log', [
            'message' => "Email with log id {$log_id} has been sent",
            'channel' => self::CHANNEL_NAME,
            'level' => Logger::INFO,
            'context' => compact('log_id', 'source'),
        ]);
    }

    
    private function get_source()
    {
        if (is_user_logged_in()) {
            $user = wp_get_current_user();
            return $user->display_name . ' (ID: ' . $user->ID . ')';
        } else {
            $source = $_SERVER['REMOTE_ADDR'];
            if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                $source = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $source = $_SERVER['HTTP_X_FORWARDED_FOR'];
            }
            return $source;
        }
    }
}