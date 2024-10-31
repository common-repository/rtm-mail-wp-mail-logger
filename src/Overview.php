<?php

namespace RtmMail;

use Cassandra\Date;
use DateTime;
use RtmMail\Helpers\LogHelper;


class Overview extends AbstractPage
{
    protected $slug = 'overview';

    public function __construct()
    {
        parent::__construct();
    }

    
    public function handle_requests()
    {
        if ($this->page_check()) {

            if (isset($_POST['action'])) {
                if ($_POST['action'] === 'send_log') {
                    if (wp_verify_nonce($_POST['_wpnonce'], 'rtm_mail_action_send')) {
                        if (isset($_POST['log_id']) && !empty($_POST['log_id'])) {
                            if (isset($_POST['send_log'])) {
                                                                $mail_sent = Catcher::send_mail(intval(sanitize_text_field($_POST['log_id'])));
                                if ($mail_sent === 'success') {
                                                                        add_action('admin_notices', function () {
                                        echo '<div class="notice notice-success"> ';
                                        echo '<p><strong>WP Mail Logger: </strong>';
                                        echo __('Selected row was sent!', 'rtm-mail');
                                        echo '</p>';
                                        echo '</div>';
                                    });
                                } else {
                                                                        echo '<div class="notice notice-error"> ';
                                    echo '<p><strong>WP Mail Logger: </strong>';
                                    printf(__('Error sending email log: #%1$s (%2$s)', 'rtm-mail'), esc_attr($_POST['log_id']), $mail_sent);
                                    echo '</p>';
                                    echo '</div>';
                                }
                            }
                        }
                    }
                } else if ($_POST['action'] === 'delete_log') {
                    if (wp_verify_nonce($_POST['_wpnonce'], 'rtm_mail_action_delete')) {
                        if (isset($_POST['log_id']) && !empty($_POST['log_id'])) {
                            if (isset($_POST['delete_log'])) {
                                                                $log_id = sanitize_text_field($_POST['log_id']);
                                LogHelper::delete(['id' => intval($log_id)]);
                                do_action('rtmmail_log_deleted', $log_id, get_current_user_id());
                                                                wp_redirect(get_admin_url() . 'admin.php?page=rtm-mail-overview&delete_successful=true');
                                exit();
                            }
                        }
                    }
                } else if ($_POST['action'] === 'test_mail') {
                    wp_mail(get_option('admin_email'), 'This is a sample subject', '<h1>This is a test</h1><p>This is just a test mail to see if everything is working OK</p>', 'Content-Type:text/html', []);
                    add_action('admin_notices', function () {
                        echo '<div class="notice notice-success"> ';
                        echo '<p><strong>WP Mail Logger: </strong>';
                        echo __('A test email was sent to the admin email!', 'rtm-mail');
                        echo '</p>';
                        echo '</div>';
                    });
                }
            }

                        if (isset($_GET['action'])) {
                if (isset($_GET['delete_log_rows'])) {
                                        if (wp_verify_nonce($_GET['_wpnonce_delete'], 'rtm_mail_delete_log_rows')) {
                                                if (isset($_GET['id'])) {
                            foreach ($_GET['id'] as $id) {
                                if (!empty($id)) {
                                                                        LogHelper::delete(['id' => intval(sanitize_text_field($id))]);
                                    do_action('rtmmail_log_deleted', $id, get_current_user_id());
                                }
                            }
                                                        add_action('admin_notices', function () {
                                echo '<div class="notice notice-success"> ';
                                echo '<p><strong>WP Mail Logger: </strong>';
                                echo __('Row(s) successfully deleted', 'rtm-mail');
                                echo '</p>';
                                echo '</div>';
                            });
                        }
                    }
                } else if (isset($_GET['send_log_rows'])) {
                                        if (wp_verify_nonce($_GET['_wpnonce_send'], 'rtm_mail_send_log_rows')) {
                        if (isset($_GET['id'])) {
                            foreach ($_GET['id'] as $id) {
                                if (!empty($id)) {
                                    $mail_sent = Catcher::send_mail($id);
                                    if ($mail_sent != 'success') {
                                                                                echo '<div class="notice notice-error"> ';
                                        echo '<p><strong>WP Mail Logger: </strong>';
                                        printf(__('Error sending email log: #%1$s (%2$s)', 'rtm-mail'), $id, $mail_sent);
                                        echo '</p>';
                                        echo '</div>';
                                    }
                                }
                            }
                                                        add_action('admin_notices', function () {
                                echo '<div class="notice notice-success"> ';
                                echo '<p><strong>WP Mail Logger: </strong>';
                                echo __('Selected row(s) were sent', 'rtm-mail');
                                echo '</p>';
                                echo '</div>';
                            });
                        }
                    }
                }
            } else if (isset($_GET['delete_successful'])) {
                                add_action('admin_notices', function () {
                    echo '<div class="notice notice-success"> ';
                    echo '<p><strong>WP Mail Logger: </strong>';
                    echo __('Log successfully deleted', 'rtm-mail');
                    echo '</p>';
                    echo '</div>';
                });
            }
        }
    }
}