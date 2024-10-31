<?php


$settings = get_option('rtm_mail_settings');
$mail_capability = $settings['mail_capability'] ?? 'manage_options';
?>
<div class="wrap" id="rtm-mail">
    <?php \RtmMail\Core::render_page_header('settings'); ?>

    <div class="rtm-mail-page-content">
        <div class="rtm-page-info">
            <p class="heading rtm-heading"><?php echo __('Settings', 'rtm-mail'); ?></p>
            <p class="rtm-heading-description"><?php echo __('On this page you can change the settings of the WP Mail Logger. Be sure to look through all the settings this plugin has to offer.', 'rtm-mail'); ?></p>
            <h1 class="notice-header"><?php echo __('Settings', 'rtm-mail'); ?></h1>
        </div>
        <hr>
        <div class="rtm-mail-page-content">
            <form id="rtm-mail-settings" action="" method="post">
                <div class="settings-block">
                    <div class="settings-block-option">
                        <p class="option-label"><?php echo __('Capability access', 'rtm-mail'); ?></p>
                        <p class="option-description"><?php echo __('Here you can choose which role capabilities can access the mail overview and details page. People with this capability can also edit, send and delete mails.', 'rtm-mail'); ?></p>
                    </div>
                    <div class="settings-block-input">
                        <?php
                        global $wp_roles;
                        $all_capabilities = [];
                        foreach ($wp_roles->roles as $role) {
                            $all_capabilities = array_merge($all_capabilities, $role['capabilities']);
                        }
                        ?>
                        <div class="option-mail-capability">
                            <select class="mail_capability" name="mail_capability">
                                <?php foreach ($all_capabilities as $capability => $has_capability) { ?>
                                    <option value="<?php echo esc_attr($capability); ?>" <?php echo $mail_capability === $capability ? 'selected="selected"' : '' ?>><?php echo esc_attr($capability); ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="pro-settings-block">
                    <div class="settings-block">
                        <div class="settings-block-option">
                            <p class="option-label"><?php echo __('Block mails', 'rtm-mail'); ?> <span class="badge badge-pro" style="margin-left: 5px;">PRO</span></p>
                            <p class="option-description"><?php echo __('When this option is enabled every mail sent inside your site will be blocked and logged. If this option is disabled every mail will be automatically send after it has been logged.', 'rtm-mail'); ?></p>
                        </div>
                        <div class="settings-block-input checkbox-input">
                            <label class="option-switch">
                                <input type="checkbox" class="option-checkbox"
                                       name="send_mails" />
                                <span class="option-slider">
                                <span class="option-slider-on"><?php echo __('ON', 'rtm-mail'); ?></span>
                                <span class="option-slider-off"><?php echo __('OFF', 'rtm-mail'); ?></span>
                            </span>
                            </label>
                        </div>
                    </div>
                    <div class="settings-block">
                        <div class="settings-block-option">
                            <p class="option-label"><?php echo __('Edit sent emails', 'rtm-mail'); ?> <span class="badge badge-pro" style="margin-left: 5px;">PRO</span></p>
                            <p class="option-description"><?php echo __('If you want you can choose if anyone can edit emails that have already been sent, if this is disabled no one can edit the email when it has been sent by someone.', 'rtm-mail'); ?></p>
                        </div>
                        <div class="settings-block-input checkbox-input">
                            <label class="option-switch">
                                <input type="checkbox" class="option-checkbox"
                                       name="edit_mails" />
                                <span class="option-slider">
                                <span class="option-slider-on"><?php echo __('ON', 'rtm-mail'); ?></span>
                                <span class="option-slider-off"><?php echo __('OFF', 'rtm-mail'); ?></span>
                            </span>
                            </label>
                        </div>
                    </div>
                    <div class="settings-block">
                        <div class="settings-block-option">
                            <p class="option-label"><?php echo __('Sender options', 'rtm-mail'); ?> <span class="badge badge-pro" style="margin-left: 5px;">PRO</span></p>
                            <p class="option-description"><?php echo __('If you want you can set default sender options, this includes the mail title and the sender address. If you leave these fields blank the plugin will use the site name as mail title and the admin address as sender address.', 'rtm-mail'); ?></p>
                        </div>
                        <div class="settings-block-input">
                            <div class="sender-preset-input" style="display: block;">
                                <div class="sender-input-box">
                                    <p class="edit-input-label"><?php echo __('Mail title', 'rtm-mail'); ?></p>
                                    <input type="text" name="sender_options[title]" id="sender_preset_title"
                                           value=""
                                           class="edit-input-text edit-preset-text"/>
                                </div>
                                <div class="sender-input-box">
                                    <p class="edit-input-label"><?php echo __('Sender address', 'rtm-mail'); ?></p>
                                    <input type="text" name="sender_options[address]" id="sender_preset_address"
                                           value=""
                                           class="edit-input-text edit-preset-text"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="settings-block">
                        <div class="settings-block-option">
                            <p class="option-label"><?php echo __('Address for outgoing emails', 'rtm-mail'); ?> <span class="badge badge-pro" style="margin-left: 5px;">PRO</span></p>
                            <p class="option-description"><?php echo __('You can add an adress to the CC/BCC/Recipient for every outgoing email, click on the "+" to add more addresses.', 'rtm-mail'); ?></p>
                        </div>
                        <div class="settings-block-input">
                            <div class="option-outgoing outgoing-email">
                                <table class="outgoing-mail-table">
                                    <tbody id="outgoing__list">
                                    <tr class="outgoing-mail-row">
                                        <td class="outgoing-mail-cell email-cell">
                                            <input type="text" name="option_email[]" class="option-email"
                                                   placeholder="<?php echo __('Type an address', 'rtm-mail'); ?>"/>
                                        </td>
                                        <td class="outgoing-mail-cell type-cell">
                                            <select class="outgoing_type" name="outgoing_type[]">
                                                <option value="cc"><?php echo __('CC', 'rtm-mail'); ?></option>
                                                <option value="bcc"><?php echo __('BCC', 'rtm-mail'); ?></option>
                                                <option value="recipient"><?php echo __('Recipient', 'rtm-mail'); ?></option>
                                            </select>
                                        </td>
                                        <td class="outgoing-mail-cell button-cell">
                                            <button type="button" id="add_outgoing_field" name="add_outgoing"
                                                    class="button button-invert">+
                                            </button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="settings-block">
                        <div class="settings-block-option">
                            <p class="option-label"><?php echo __('Only clear deleted logs', 'rtm-mail'); ?> <span class="badge badge-pro" style="margin-left: 5px;">PRO</span></p>
                            <p class="option-description"><?php echo __('When enabled only deleted logs will be cleared from the debug events when the user clears it. If this option is disabled every debug event will be removed.', 'rtm-mail'); ?></p>
                        </div>
                        <div class="settings-block-input checkbox-input">
                            <label class="option-switch">
                                <input type="checkbox" class="option-checkbox"
                                       name="clear_deleted_events" checked />
                                <span class="option-slider">
                                <span class="option-slider-on"><?php echo __('ON', 'rtm-mail'); ?></span>
                                <span class="option-slider-off"><?php echo __('OFF', 'rtm-mail'); ?></span>
                            </span>
                            </label>
                        </div>
                    </div>
                    <div class="settings-block">
                        <div class="settings-block-option">
                            <p class="option-label"><?php echo __('Send caught mails automatically', 'rtm-mail'); ?> <span class="badge badge-pro" style="margin-left: 5px;">PRO</span></p>
                            <p class="option-description"><?php echo __('With this setting you can choose to send all caught mails around a certain time. The time you can do this with is based on WordPress schedules. Keep in mind this can collide with queued emails, once the email is sent it won\'t send again. The first caught emails will be sent at the time now + the scheduled time.', 'rtm-mail'); ?></p>
                        </div>
                        <div class="settings-block-input">
                            <div class="settings-input-cron">
                                <label class="option-switch">
                                    <input type="checkbox" class="option-checkbox"
                                           name="mail_cron_enabled" />
                                    <span class="option-slider">
                                <span class="option-slider-on"><?php echo __('ON', 'rtm-mail'); ?></span>
                                <span class="option-slider-off"><?php echo __('OFF', 'rtm-mail'); ?></span>
                            </span>
                                </label>
                                <div class="option-send-mails-time">
                                    <select class="send-mail-cron-times" name="mail_cron_schedule">
                                        <option value="hourly"><?php echo __('Once an hour', 'rtm-mail'); ?></option>
                                        <?php
                                        foreach (wp_get_schedules() as $schedule_key => $schedule) {
                                            ?>
                                            <option value="<?php echo $schedule_key; ?>"><?php echo $schedule['display']; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="settings-block">
                        <div class="settings-block-option">
                            <p class="option-label"><?php echo __('Send SMS text message on fail', 'rtm-mail'); ?> <span class="badge badge-pro" style="margin-left: 5px;">PRO</span></p>
                            <p class="option-description"><?php echo __('Using this option you can choose to get a text message everytime a mail log fails to be sent. This option requires <a href="https://www.twilio.com/" target="_blank" style="font-weight: 600">Twilio</a> as a SMS gateway API to send out messages to a phone number.', 'rtm-mail'); ?></p>
                        </div>
                        <div class="settings-block-input">
                            <div class="settings-input-cron">
                                <label class="option-switch">
                                    <input type="checkbox" class="option-checkbox"
                                           name="send_sms" />
                                    <span class="option-slider">
                                        <span class="option-slider-on"><?php echo __('ON', 'rtm-mail'); ?></span>
                                        <span class="option-slider-off"><?php echo __('OFF', 'rtm-mail'); ?></span>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="_wpnonce"
                       value="<?php echo esc_attr(wp_create_nonce('rtm_mail_save_settings')); ?>"/>
                <?php submit_button(__('Save settings', 'rtm-mail'), '', 'save_settings', false, ['id' => 'save-settings']); ?>
            </form>
        </div>
    </div>
</div>
