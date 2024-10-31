<?php

namespace RtmMail;


class MailSMTP extends AbstractPage
{
    protected $slug = 'mailsmtp';

    public function __construct()
    {
        parent::__construct();
    }

    
    public function handle_requests()
    {
                if ($this->page_check()) {
                        if (isset($_POST['save_settings'])) {
                                if (wp_verify_nonce($_POST['_wpnonce'], 'rtm_mail_save_smtp')) {
                    $encryption_key = get_option('rtm_mail_smtp_key', false);
                    if (empty($encryption_key)) {
                        $encryption_key = wp_salt();
                        update_option('rtm_mail_smtp_key', $encryption_key);
                    }

                    $smtp_enabled = isset($_POST['smtp_enabled']) ? filter_var($_POST['smtp_enabled'], FILTER_VALIDATE_BOOLEAN) : false;
                    $smtp_host = sanitize_text_field($_POST['smtp_host']);
                    $smtp_encryption = sanitize_text_field($_POST['smtp_encryption']);
                    $smtp_port = intval(sanitize_text_field($_POST['smtp_port']));
                    $smtp_authentication = isset($_POST['smtp_authentication']) ? filter_var($_POST['smtp_authentication'], FILTER_VALIDATE_BOOLEAN) : false;
                    $smtp_username = sanitize_email($_POST['smtp_username']);
                    $smtp_password = Cryptor::Encrypt($_POST['smtp_password'], $encryption_key);

                                        update_option('rtm_mail_smtp_settings', [
                        'smtp_enabled' => $smtp_enabled,
                        'host' => $smtp_host,
                        'encryption' => $smtp_encryption,
                        'port' => $smtp_port,
                        'authentication' => $smtp_authentication,
                        'username' => $smtp_username,
                        'password' => $smtp_password,
                    ]);

                                        echo '<div class="notice notice-success"> ';
                    echo '<p><strong>WP Mail Logger: </strong>';
                    echo __('Settings successfully saved!', 'rtm-mail');
                    echo '</p>';
                    echo '</div>';
                }
            }
        }
    }
}
