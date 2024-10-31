<?php

namespace RtmMail\Helpers;

use DateTime;


class DashboardHelper
{
    public static function get()
    {
                global $wpdb;
                $data = [];
                $latest_log = LogHelper::get(['post_per_page' => 1]);
                $latest_sent_log = LogHelper::get([
            'post_per_page' => 1,
            'orderby' => 'sent',
            'order' => 'DESC',
            'where' => [
                'status' => [
                    'type' => '=',
                    'value' => 'sent',
                ]
            ],
        ]);
                $latest_updated_log = LogHelper::get([
            'post_per_page' => 1,
            'orderby' => 'updated',
            'order' => 'DESC',
        ]);

                $latest_event = EventHelper::get(['post_per_page' => 1]);
                $latest_error_event = EventHelper::get([
            'post_per_page' => 1,
            'filter_level' => 'on',
            'level_type' => 'error',
        ]);

                $data['blocks'] = [
            'emails_logged' => [
                'total' => count(LogHelper::get([
                    'post_per_page' => null,
                    'date' => date("Y-m-d") . ' - ' . date("Y-m-d"),
                ])),
                'latest' => isset($latest_log[0]) ? (new DateTime($latest_log[0]['created']))->format('d-m-Y H:i') : 'no date yet',
            ],
            'emails_sent' => [
                'total' => count(LogHelper::get([
                    'post_per_page' => null,
                    'date' => date("Y-m-d") . ' - ' . date("Y-m-d"),
                    'date_type' => 'sent',
                ])),
                'latest' => isset($latest_sent_log[0]) ? (new DateTime($latest_sent_log[0]['sent']))->format('d-m-Y H:i') : null,
            ],
            'events_logged' => [
                'total' => count(EventHelper::get([
                    'post_per_page' => null,
                    'date' => date("Y-m-d") . ' - ' . date("Y-m-d"),
                ])),
                'latest' => isset($latest_event[0]) ? $latest_event[0]['date']->format('d-m-Y H:i') : null,
            ],
            'errors_logged' => [
                'total' => count(EventHelper::get([
                    'post_per_page' => null,
                    'date' => date("Y-m-d") . ' - ' . date("Y-m-d"),
                    'filter_level' => 'on',
                    'level_type' => 'error',
                ])),
                'latest' => isset($latest_error_event[0]) ? $latest_error_event[0]['date']->format('d-m-Y H:i') : null,
            ],
        ];

                $logs_row_count = $wpdb->get_results("SELECT COUNT(*) AS total, 
                                                    COUNT(CASE WHEN status = 'caught' THEN 1 END) AS caught, 
                                                    COUNT(CASE WHEN status = 'sent' THEN 1 END) AS sent, 
                                                    COUNT(CASE WHEN status = 'failed' THEN 1 END) AS failed 
                                                    FROM " . RTM_MAIL_TABLE_PREFIX . "logs;");
                $data['logs_info'] = [
            'total' => $logs_row_count[0]->total,
            'caught' => $logs_row_count[0]->caught,
            'sent' => $logs_row_count[0]->sent,
            'failed' => $logs_row_count[0]->failed,
            'deleted_logs' => count(EventHelper::get(['post_per_page' => null, 'search_filter' => ['message' => 'on'], 's' => 'deleted'])),
            'last_added' => [
                'id' => isset($latest_log[0]) ? $latest_log[0]['id'] : null,
                'subject' => isset($latest_log[0]) ? $latest_log[0]['subject'] : null,
            ],
            'last_edited' => [
                'id' => isset($latest_updated_log[0]) ? $latest_updated_log[0]['id'] : null,
                'subject' => isset($latest_updated_log[0]) ? $latest_updated_log[0]['subject'] : null,
            ],
            'last_sent' => [
                'id' => isset($latest_sent_log[0]) ? $latest_sent_log[0]['id'] : null,
                'subject' => isset($latest_sent_log[0]) ? $latest_sent_log[0]['subject'] : null,
            ],
        ];

                $data['event_info'] = [
            'total' => count(EventHelper::get(['post_per_page' => null])),
            'last_errors' => EventHelper::get(['post_per_page' => 5, 'filter_level' => 'on', 'level_type' => 'error']),
        ];

        return $data;
    }
}