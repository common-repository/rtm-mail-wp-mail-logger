<?php

namespace RtmMail\Helpers;


class LogHelper
{
    
    const TABLE_NAME = 'logs';
    
    const DEFAULT_ARGS = [
        'orderby' => 'id',
        'order' => 'DESC',
        'post_per_page' => 10,
        'paged' => 1,
        's' => null,
        'where' => null,
        'date' => null,
    ];

    
    public static function get($args = [])
    {
        global $wpdb;

                $args = array_merge(self::DEFAULT_ARGS, $args);

        $query = "SELECT * FROM " . RTM_MAIL_TABLE_PREFIX . self::TABLE_NAME . " ";
        $where_added = false;

                if ($args['where'] != null) {
            $query .= "WHERE ";
                                    foreach ($args['where'] as $column_name => $column_data) {
                                $column_value = esc_sql($column_data['value']);
                if ($column_data['type'] == 'LIKE') {
                                        $column_value = '%' . esc_sql($column_value) . '%';
                }
                                reset($args['where']);
                if ($column_name === key($args['where'])) {
                    $query .= $column_name . " " . $column_data['type'] . " '" . esc_sql($column_value) . "' ";
                } else {
                                        $which = $column_data['which'] ?? 'AND';
                    $query .= $which . " " . $column_name . " " . $column_data['type'] . " '" . esc_sql($column_value) . "' ";
                }
            }
            $where_added = true;
        }

                if ($args['s'] != null && (isset($args['search_filter']) && !empty($args['search_filter']))) {
                        if ($where_added) {
                $query .= "AND ";
            } else {
                $query .= "WHERE ";
                $where_added = true;
            }
                        $args['s'] = esc_sql($args['s']);
                        foreach ($args['search_filter'] as $filter_column => $is_filter) {
                $filter_column = esc_sql($filter_column);
                                if (filter_var($is_filter, FILTER_VALIDATE_BOOLEAN)) {
                    $query .= "(" . $filter_column . " LIKE '%" . esc_sql($args['s']) . "%') OR ";
                }
                                end($args['search_filter']);
                if ($filter_column === key($args['search_filter'])) {
                    $query .= "(" . $filter_column . " LIKE '%" . esc_sql($args['s']) . "%') ";
                }
            }
        }

        if ($args['date'] != null) {
            $args['date_type'] = $args['date_type'] ?? 'created';
            if ($where_added) {
                $query .= "AND ";
            } else {
                $query .= "WHERE ";
            }
            $dates = explode(' - ', sanitize_text_field(wp_unslash($args['date'])));
            $query .= "(CAST(" . $args['date_type'] . " as DATE) >= '" . date('Y-m-d', strtotime($dates[0])) . "' AND CAST(" . $args['date_type'] . " as DATE) <= '" . date('Y-m-d', strtotime($dates[1])) . "')";
        }

                if (!empty($args['orderby']) && !empty($args['order'])) {
            $query .= "ORDER BY " . esc_sql($args['orderby']) . " " . esc_sql($args['order']) . " ";
        }

                if ($args['post_per_page'] != null && $args['post_per_page'] != -1) {
            $query .= "LIMIT " . esc_sql($args['post_per_page']) . " OFFSET " . (($args['paged'] - 1) * $args['post_per_page']);
        }
                return self::format($wpdb->get_results($query));
    }

    
    private static function format($logs)
    {
        $formatted_data = [];
        if (!empty($logs) && $logs != null) {
            foreach ($logs as $log) {
                $formatted_data[] = [
                    'id' => $log->id,
                    'subject' => $log->subject,
                    'sender' => $log->sender,
                    'receiver' => json_decode($log->receiver, true),
                    'cc' => json_decode($log->cc, true),
                    'bcc' => json_decode($log->bcc, true),
                    'headers' => json_decode($log->headers, true),
                    'body' => $log->body,
                    'status' => $log->status,
                    'attachments' => json_decode($log->attachments, true),
                    'backtrace' => json_decode($log->backtrace, true),
                    'created' => $log->created,
                    'updated' => $log->updated,
                    'sent' => $log->sent,
                ];
            }
        }
        return $formatted_data;
    }

    
    public static function save($args)
    {
        global $wpdb;

        $wpdb->insert(RTM_MAIL_TABLE_PREFIX . self::TABLE_NAME, [
            'subject' => esc_sql($args['subject']),
            'sender' => esc_sql($args['sender']),
            'receiver' => json_encode(esc_sql($args['receiver'])),
            'cc' => json_encode(esc_sql($args['cc'])),
            'bcc' => json_encode(esc_sql($args['bcc'])),
            'headers' => json_encode(esc_sql($args['headers'])),
            'body' => esc_sql($args['body']),
            'attachments' => json_encode(esc_sql($args['attachments'])),
            'backtrace' => json_encode(esc_sql($args['backtrace'])),
            'created' => esc_sql($args['created']),
        ]);

        return $wpdb->insert_id;
    }

    
    public static function update($log_id, $args)
    {
        global $wpdb;

        $wpdb->update(RTM_MAIL_TABLE_PREFIX . self::TABLE_NAME, $args, ['id' => esc_sql($log_id)]);
    }

    public static function delete($args)
    {
        global $wpdb;

        $wpdb->delete(RTM_MAIL_TABLE_PREFIX . self::TABLE_NAME, esc_sql($args));
    }

    
    public static function get_total_rows($args = [])
    {
        $args = array_merge($args, ['post_per_page' => -1]);
        return count(self::get($args));
    }
}