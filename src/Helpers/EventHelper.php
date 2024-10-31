<?php

namespace RtmMail\Helpers;

use Dubture\Monolog\Reader\LogReader;


class EventHelper
{
    
    const DEFAULT_ARGS = [
        'orderby' => 'date',
        'order' => 'DESC',
        'post_per_page' => 10,
        'paged' => 1,
        'date' => null,
        's' => null,
    ];

    
    public static function get($args = [])
    {
                $args = array_merge(self::DEFAULT_ARGS, $args);

                $logs = [];
        $upload = wp_upload_dir();
                $log_directories = self::get_directories($upload['basedir'] . '/rtm-mail/logs');
        foreach ($log_directories as $directory) {
                        $directory_files = array_diff(scandir($directory), ['.', '..']);
                        $log_paths = [];
            foreach ($directory_files as $log_file) {
                                $ext = explode('.', $log_file);
                if (!empty($log_file) && isset($ext[1]) && strtolower($ext[1]) === 'log') {
                                        $log_paths[] = $directory . '/' . $log_file;
                }
            }
                        foreach ($log_paths as $path) {
                                $reader = new LogReader($path, 0);
                                foreach ($reader as $log) {
                    if (isset($log['logger']) && $log['logger'] === 'RTMMAIL') {
                        $logs[] = $log;
                    }
                }
            }
        }
                if ($args['s'] != null && (isset($args['search_filter']) && !empty($args['search_filter']))) {
                        $logs = array_filter($logs, function ($log_data) use ($args) {
                if ((isset($args['search_filter']['log_id']) && filter_var($args['search_filter']['log_id'], FILTER_VALIDATE_BOOLEAN) && isset($log_data['context']['log_id']) && $args['s'] === $log_data['context']['log_id']) ||
                    (isset($args['search_filter']['message']) && filter_var($args['search_filter']['message'], FILTER_VALIDATE_BOOLEAN) && stripos(strtolower($log_data['message']), strtolower($args['s'])) !== false) ||
                    (isset($args['search_filter']['level']) && filter_var($args['search_filter']['level'], FILTER_VALIDATE_BOOLEAN) && stripos(strtolower($log_data['level']), strtolower($args['s'])) !== false)) {
                    return true;
                }
                return false;
            });
        }

                if ($args['date'] != null) {
                        $dates = explode(' - ', sanitize_text_field(wp_unslash($args['date'])));
                        $logs = array_filter($logs, function ($log_data) use ($dates) {
                $log_time = strtotime($log_data['date']->format('d-m-Y'));
                return $log_time >= strtotime($dates[0]) && $log_time <= strtotime($dates[1]);
            });
        }

                if (isset($args['filter_level']) && isset($args['level_type']) && !empty($args['level_type'])) {
                        $logs = array_filter($logs, function ($log_data) use ($args) {
                if (stripos(strtolower($log_data['level']), $args['level_type']) !== false) {
                    return true;
                }
                return false;
            });
        }

                if (!empty($args['orderby']) && !empty($args['order'])) {
            if (strtolower($args['orderby']) === 'date') {
                                usort($logs, function ($a, $b) use ($args) {
                    if ($a == $b) {
                        return 0;
                    }
                    if (strtolower($args['order']) === 'asc') {
                        return $a['date'] > $b['date'];
                    }
                    return $a['date'] < $b['date'];
                });
            } else if (strtolower($args['orderby']) === 'level') {
                usort($logs, function ($a, $b) use ($args) {
                    if (strtolower($args['order']) === 'asc') {
                        return strcmp($a['level'], $b['level']);
                    }
                    return strcmp($b['level'], $a['level']);
                });
            }
        }

                if ($args['post_per_page'] != null && $args['post_per_page'] !== -1) {
            $logs = array_slice($logs, (($args['paged'] - 1) * $args['post_per_page']), $args['post_per_page']);
        }
        return $logs;
    }

    
    private static function get_directories($path)
    {
        $all_directories = [];
        $directories = array_filter(glob($path), 'is_dir');
        $all_directories = array_merge($all_directories, $directories);
        foreach ($directories as $directory) {
            $all_directories = array_merge($all_directories, self::get_directories($directory . '/*'));
        }

        return $all_directories;
    }
}