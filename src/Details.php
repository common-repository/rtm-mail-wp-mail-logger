<?php

namespace RtmMail;

use RtmMail\Helpers\LogHelper;


class Details extends AbstractPage
{
    protected $slug = 'details';

    
    private $mail_log;

    public function __construct()
    {
        parent::__construct();
    }

    
    public function handle_requests()
    {
                if ($this->page_check()) {
                        if (isset($_GET['log_id'])) {
                                $log_id = sanitize_key($_GET['log_id']);
                                $this->mail_log = LogHelper::get([
                    'post_per_page' => null,
                    'where' => [
                        'id' => [
                            'type' => '=',
                            'value' => intval($log_id),
                        ]
                    ]
                ]);
            }

                        if (isset($_GET['sent_successful'])) {
                add_action('admin_notices', function () {
                    echo '<div class="notice notice-success"> ';
                    echo '<p><strong>WP Mail Logger: </strong>';
                    echo __('Email successfully sent', 'rtm-mail');
                    echo '</p>';
                    echo '</div>';
                });
            }

                        if (isset($_GET['sent_failed'])) {
                add_action('admin_notices', function () {
                    echo '<div class="notice notice-error"> ';
                    echo '<p><strong>WP Mail Logger: </strong>';
                    echo esc_attr($_GET['sent_failed']);
                    echo '</p>';
                    echo '</div>';
                });
            }

                        if (isset($_POST['action'])) {
                                if ($_POST['action'] === 'send_log') {
                                        if (wp_verify_nonce($_POST['_wpnonce'], 'rtm_mail_send_log')) {
                        if (isset($_POST['log_id']) && !empty($_POST['log_id'])) {
                            if (isset($_POST['send_log'])) {
                                                                $mail_sent = Catcher::send_mail(intval($_POST['log_id']));
                                wp_clear_scheduled_hook('rtm_mail_send_log', [$_POST['log_id']]);
                                if ($mail_sent === 'success') {
                                    wp_redirect(get_admin_url() . 'admin.php?page=rtm-mail-details&log_id=' . $_POST['log_id'] . '&sent_successful=true');
                                } else {
                                    wp_redirect(get_admin_url() . 'admin.php?page=rtm-mail-details&log_id=' . $_POST['log_id'] . '&sent_failed=' . $mail_sent);
                                }
                                exit();
                            }
                        }
                    }
                } else if ($_POST['action'] === 'delete_log') {
                                        if (wp_verify_nonce($_POST['_wpnonce'], 'rtm_mail_delete_log')) {
                        if (isset($_POST['log_id']) && !empty($_POST['log_id'])) {
                            if (isset($_POST['delete_log'])) {
                                                                LogHelper::delete(['id' => intval(sanitize_text_field($_POST['log_id']))]);
                                do_action('rtmmail_log_deleted', $_POST['log_id'], get_current_user_id());
                                wp_clear_scheduled_hook('rtm_mail_send_log', [$_POST['log_id']]);
                                                                wp_redirect(get_admin_url() . 'admin.php?page=rtm-mail-overview&delete_successful=true');
                                                                exit();
                            }
                        }
                    }
                }
            }
        }
    }

    
    public function display()
    {
                if ($this->page_check()) {
            global $log;
                        $log = $this->mail_log;
                        require __DIR__ . '/Views/' . $this->slug . '.php';
        }
    }
}
