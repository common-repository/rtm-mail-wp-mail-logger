<?php

namespace RtmMail;


class Settings extends AbstractPage
{
    protected $slug = 'settings';

    public function __construct()
    {
        parent::__construct();
    }

    
    public function handle_requests()
    {
                if ($this->page_check()) {
                        if (isset($_POST['save_settings'])) {
                                if (wp_verify_nonce($_POST['_wpnonce'], 'rtm_mail_save_settings')) {
                                        $mail_capability = sanitize_text_field($_POST['mail_capability']);

                                        update_option('rtm_mail_settings', [
                        'mail_capability' => $mail_capability,
                    ]);

                                        echo '<div class="notice notice-success"> ';
                    echo '<p><strong>WP Mail Logger: </strong>';
                    echo __('Settings successfully saved!', 'rtm-mail');
                    echo '</p>';
                    echo '</div>';
                } else {
                                        echo '<div class="notice notice-error"> ';
                    echo '<p><strong>WP Mail Logger: </strong>';
                    echo __('Invalide nonce!', 'rtm-mail');
                    echo '</p>';
                    echo '</div>';
                }
            }
        }
    }
}
