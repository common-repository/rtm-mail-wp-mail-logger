<?php
global $log;
$mail_log = $log[0] ?? null;

if ($mail_log == null) {
    echo '<div class="notice notice-error"> ';
    echo '<p><strong>WP Mail Logger: </strong>';
    echo __('No log was found with this log_id', 'rtm-mail');
    echo '</p>';
    echo '</div>';
} else {
    $sender_options = $mail_log['headers']['from'] ?? null;
    $send_address = '';
    $send_title = '';
    if ($sender_options !== null) {
        $send_address = substr(explode('<', $sender_options)[1], 0, -1);
        $send_title = explode('<', $sender_options)[0];
    }

        $settings = get_option('rtm_mail_settings');
    $sender_options = $settings['sender_options'] ?? null;
    $block_mails = isset($settings['block_mails']) && filter_var($settings['block_mails'], FILTER_VALIDATE_BOOLEAN);
    $edit_mails = isset($settings['edit_mails']) && filter_var($settings['edit_mails'], FILTER_VALIDATE_BOOLEAN);

    $sender_address_default = ($sender_options != null && !empty($sender_options['address'])) ? $sender_options['address'] : get_option('admin_email');
    $sender_title_default = ($sender_options != null && !empty($sender_options['title'])) ? $sender_options['title'] : get_bloginfo('name');
    ?>
    <div class="wrap" id="rtm-mail">
        <?php \RtmMail\Core::render_page_header('details'); ?>

        <div class="rtm-mail-page-content">
            <div class="rtm-page-info">
                <p class="heading rtm-heading"><?php echo __('Details', 'rtm-mail'); ?></p>
                <p class="rtm-heading-description"><?php echo __('On this page you will find the details about your selected email. You can also edit certain fields and send the email if it wasn\'t already sent. Always save the mail first before sending.', 'rtm-mail'); ?></p>
                <h1 class="notice-header"><?php echo __('Details', 'rtm-mail'); ?></h1>
            </div>
            <hr>
            <div class="rtm-mail-page-details">
                <div class="details-edit-box">
                    <form method="post">
                        <input type="hidden" name="action" value="edit"/>
                        <input type="hidden" name="log_id" value="<?php echo esc_attr($mail_log['id']); ?>"/>
                        <input type="hidden" name="_wpnonce"
                               value="<?php echo esc_attr(wp_create_nonce('rtm_mail_edit_log')); ?>"/>
                        <div class="details-heading" style="border-top: none">
                            <span class="heading-title"><?php echo __('Sender options', 'rtm-mail'); ?></span>
                        </div>
                        <div class="details-edit-input">
                            <div class="edit-input">
                                <div class="sender-preset-input">
                                    <div class="sender-input-box">
                                        <p class="edit-input-label"><?php echo __('Mail title', 'rtm-mail'); ?></p>
                                        <input type="text" name="sender_options[title]" id="sender_preset_title"
                                               value="<?php echo esc_attr($send_title); ?>"
                                               class="edit-input-text edit-preset-text" disabled />
                                    </div>
                                    <div class="sender-input-box">
                                        <p class="edit-input-label"><?php echo __('Sender address', 'rtm-mail'); ?></p>
                                        <input type="text" name="sender_options[address]" id="sender_preset_address"
                                               value="<?php echo esc_attr($send_address); ?>"
                                               class="edit-input-text edit-preset-text" disabled />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="details-heading">
                            <span class="heading-title"><?php echo __('Mail Details', 'rtm-mail'); ?></span>
                        </div>
                        <div class="details-edit-input">
                            <div class="edit-input">
                                <p class="edit-input-label"><?php echo __('Receiver', 'rtm-mail'); ?></p>
                                <input type="text" name="receiver" id="edit_receiver"
                                       value="<?php echo !empty($mail_log['receiver']) ? implode(';', $mail_log['receiver']) : ''; ?>"
                                       class="edit-input-text" disabled />
                            </div>
                        </div>
                        <div class="details-edit-input">
                            <div class="edit-input">
                                <p class="edit-input-label"><?php echo __('CC', 'rtm-mail'); ?></p>
                                <input type="text" name="cc" id="edit_cc"
                                       value="<?php echo !empty($mail_log['cc']) ? implode(';', $mail_log['cc']) : ''; ?>"
                                       class="edit-input-text" disabled />
                            </div>
                        </div>
                        <div class="details-edit-input">
                            <div class="edit-input">
                                <p class="edit-input-label"><?php echo __('BCC', 'rtm-mail'); ?></p>
                                <input type="text" name="bcc" id="edit_bcc"
                                       value="<?php echo !empty($mail_log['bcc']) ? implode(';', $mail_log['bcc']) : ''; ?>"
                                       class="edit-input-text" disabled />
                            </div>
                        </div>
                        <div class="details-edit-input">
                            <div class="edit-input">
                                <p class="edit-input-label"><?php echo __('Subject', 'rtm-mail'); ?></p>
                                <input type="text" name="subject" id="edit_subject"
                                       value="<?php echo esc_attr($mail_log['subject']); ?>"
                                       class="edit-input-text" disabled />
                            </div>
                        </div>
                        <div class="details-edit-input">
                            <div class="edit-input" style="width: 100%">
                                <p class="edit-input-label"><?php echo __('Body (content)', 'rtm-mail'); ?></p>
                                <textarea name="body" id="edit_body"
                                          class="edit-input-text" disabled><?php echo esc_textarea($mail_log['body']); ?></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="details-detail-box">
                    <div class="details-heading">
                        <span class="heading-title"><?php echo __('Details', 'rtm-mail'); ?></span>
                    </div>
                    <div class="detail-status">
                        <div class="detail-status-box">
                            <p class="edit-input-label"><?php echo __('Status', 'rtm-mail'); ?></p>
                            <?php
                            $label = __('Caught', 'rtm-mail');
                            switch ($mail_log['status']) {
                                case 'caught':
                                    $label = __('Caught', 'rtm-mail');
                                    break;
                                case'sent':
                                    $label = __('Sent', 'rtm-mail');
                                    break;
                                case 'failed':
                                    $label = __('Failed', 'rtm-mail');
                                    break;
                            }
                            echo '<p><span class="badge badge-' . $mail_log['status'] . '">' . $label . '</span></p>';
                            ?>
                        </div>
                        <div class="detail-status-box">
                            <p class="edit-input-label"><?php echo __('Sender', 'rtm-mail'); ?></p>
                            <p><?php echo esc_attr($mail_log['sender']); ?></p>
                        </div>
                        <div class="detail-status-box">
                        </div>
                    </div>
                    <div class="detail-dates">
                        <div class="detail-date-box">
                            <p class="edit-input-label"><?php echo __('Creation date', 'rtm-mail'); ?></p>
                            <p><?php echo date_format(date_create($mail_log['created']), 'd-m-Y H:i'); ?></p>
                        </div>
                        <div class="detail-date-box">
                            <p class="edit-input-label"><?php echo __('Updated on', 'rtm-mail'); ?></p>
                            <p><?php echo !empty($mail_log['updated']) ? date_format(date_create($mail_log['updated']), 'd-m-Y H:i') : __('Not updated yet...', 'rtm-mail'); ?></p>
                        </div>
                        <div class="detail-date-box">
                            <p class="edit-input-label"><?php echo __('Date sent', 'rtm-mail'); ?></p>
                            <p><?php echo !empty($mail_log['sent']) ? date_format(date_create($mail_log['sent']), 'd-m-Y H:i') : __('Not sent yet...', 'rtm-mail'); ?></p>

                        </div>
                    </div>
                    <div class="detail-attachments">
                        <p class="edit-input-label"><?php echo __('Attachments', 'rtm-mail'); ?></p>
                        <div class="attachments-box">
                            <?php
                            if (isset($mail_log['attachments']) && !empty($mail_log['attachments'])) {
                                foreach ($mail_log['attachments'] as $attachment) {
                                    $upload = wp_upload_dir();
                                    $file_path = str_replace($upload['basedir'], '', $attachment);
                                    $file_name = substr($attachment, strrpos($attachment, '/'));
                                    ?>
                                    <a style="text-decoration: none!important;"
                                       href="<?php echo esc_attr($upload['baseurl'] . $file_path); ?>" target="_blank">
                                        <div class="attachment-item" data-file="<?php echo esc_attr($file_name); ?>">
                                            <div class="attachment-item-detail">
                                                <p class="edit-input-label"><?php echo __('File name', 'rtm-mail'); ?></p>
                                                <p class="attachment-name"><?php echo esc_attr($file_name); ?></p>
                                            </div>
                                            <div class="attachment-item-detail">
                                                <p class="edit-input-label"><?php echo __('File type', 'rtm-mail'); ?></p>
                                                <p class="extension-name"><?php echo pathinfo($attachment, PATHINFO_EXTENSION); ?></p>
                                            </div>
                                        </div>
                                    </a>
                                    <?php
                                }
                            } else {
                                echo __('No attachments to this mail...', 'rtm-mail');
                            }
                            ?>
                        </div>
                    </div>
                    <div class="details-heading" style="margin-bottom: 10px;">
                        <span class="heading-title"><?php echo __('Actions', 'rtm-mail'); ?></span>
                    </div>
                    <div class="detail-buttons">
                        <input type="button" name="delete_log" id="delete_log" class="button button-delete"
                               value="<?php echo __('Delete', 'rtm-mail'); ?>"/>
                        <input type="button" name="send_mail" id="send_mail" class="button button-success"
                               value="<?php echo __('Send Mail', 'rtm-mail'); ?>"/>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal delete-log-modal" id="modal_delete_log">
            <div class="modal-container">
                <div class="modal-header" style="display: block!important;">
                    <i class="fas fa-times modal-close" data-id="delete_log"></i>
                </div>
                <div class="modal-content">
                    <form method="post">
                        <input type="hidden" name="_wpnonce"
                               value="<?php echo esc_attr(wp_create_nonce('rtm_mail_delete_log')); ?>"/>
                        <input type="hidden" name="action" value="delete_log"/>
                        <input type="hidden" name="log_id" value="<?php echo esc_attr($mail_log['id']); ?>"/>
                        <p>
                            <strong><?php echo __('Are you sure you want to delete this mail log?', 'rtm-mail'); ?></strong>
                        </p>
                        <button type="submit" name="delete_log" class="button button-delete"
                                id="confirm_delete_log"><?php echo __('Delete', 'rtm-mail'); ?></button>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal delete-log-modal" id="modal_send_log">
            <div class="modal-container">
                <div class="modal-header" style="display: block!important;">
                    <i class="fas fa-times modal-close" data-id="send_log"></i>
                </div>
                <div class="modal-content">
                    <form method="post">
                        <input type="hidden" name="_wpnonce"
                               value="<?php echo esc_attr(wp_create_nonce('rtm_mail_send_log')); ?>"/>
                        <input type="hidden" name="action" value="send_log"/>
                        <input type="hidden" name="log_id" value="<?php echo esc_attr($mail_log['id']); ?>"/>
                        <p><strong><?php echo __('Are you sure you want to send this email?', 'rtm-mail'); ?></strong>
                        </p>
                        <button type="submit" name="send_log" class="button button-success"
                                id="confirm_send_log"><?php echo __('Send', 'rtm-mail'); ?></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php
}