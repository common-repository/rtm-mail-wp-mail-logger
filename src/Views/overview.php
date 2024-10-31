<?php


use RtmMail\LogsTable;

$log_table = new LogsTable();
$log_table->prepare_items();
?>


<div class="wrap" id="rtm-mail">
    <?php \RtmMail\Core::render_page_header('overview'); ?>

    <div class="rtm-mail-page-content">
        <div class="rtm-page-info">
            <p class="heading rtm-heading"><?php echo __('Overview', 'rtm-mail'); ?></p>
            <p class="rtm-heading-description"><?php echo __('On this page you will find a list of all sent emails from the website. Here you can choose an email to preview, send, edit or delete. You can also search on subject, receiver and look for emails between certain dates.', 'rtm-mail'); ?></p>
            <h1 class="notice-header"><?php echo __('Overview', 'rtm-mail'); ?></h1>
        </div>
        <hr>
        <div class="rtm-mail-page-overview">
            <form method="get">
                <input type="hidden" name="page" value="rtm-mail-overview"/>
                <div class="table-top-box">
                    <div class="date-box">
                        <div class="overview-actions">
                            <div class="bulk-actions">
                                <select name="bulk-action" class="bulk__action">
                                    <option value="" selected><?php echo __('Bulk actions', 'rtm-mail'); ?></option>
                                    <option value="send" class="status-on"><?php echo __('Send mails', 'rtm-mail'); ?></option>
                                    <option value="delete" class="status-off"><?php echo __('Delete mails', 'rtm-mail'); ?></option>
                                </select>
                                <button type="button" class="button" id="apply__bulk__action" name="apply-bulk-action"
                                        disabled><?php echo __('Apply', 'rtm-mail'); ?></button>
                            </div>
                            <div class="date-actions">
                                <div class="filter-date">
                                    <input type="text" id="rtm-date-range" name="date"
                                           placeholder="<?php echo __('Select a date range', 'rtm-mail'); ?>"/>
                                    <?php submit_button(__('Filter', 'rtm-mail'), '', 'filter-date-range', false, ['id' => 'filter-date-range']); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php $log_table->search_box(__('Search', 'rtm-mail'), 'search_id'); ?>
                </div>
                <!-- Normal desktop view -->
                <?php $log_table->display(); ?>

                <div class="modal delete-log-modal" id="modal_delete_log_rows">
                    <div class="modal-container" style="width: auto;">
                        <div class="modal-header" style="display: block!important;">
                            <i class="fas fa-times modal-close" data-id="delete_log_rows"></i>
                        </div>
                        <div class="modal-content">
                            <input type="hidden" name="_wpnonce_delete"
                                   value="<?php echo esc_attr(wp_create_nonce('rtm_mail_delete_log_rows')); ?>"/>
                            <input type="hidden" name="action" value="delete_log_rows"/>
                            <p>
                                <strong><?php echo __('Are you sure you want to delete the selected mail log(s)?', 'rtm-mail'); ?></strong>
                            </p>
                            <button type="submit" name="delete_log_rows" class="button button-delete"
                                    id="confirm_delete_log_rows"><?php echo __('Delete', 'rtm-mail'); ?></button>
                        </div>
                    </div>
                </div>

                <div class="modal send-log-modal" id="modal_send_log_rows">
                    <div class="modal-container" style="width: auto;">
                        <div class="modal-header" style="display: block!important;">
                            <i class="fas fa-times modal-close" data-id="send_log_rows"></i>
                        </div>
                        <div class="modal-content">
                            <input type="hidden" name="_wpnonce_send"
                                   value="<?php echo esc_attr(wp_create_nonce('rtm_mail_send_log_rows')); ?>"/>
                            <input type="hidden" name="action" value="send_log_rows"/>
                            <p>
                                <strong><?php echo __('Are you sure you want to send the selected row(s)?', 'rtm-mail'); ?></strong>
                            </p>
                            <button type="submit" name="send_log_rows" class="button button-success"
                                    id="confirm_send_log_rows"><?php echo __('Send', 'rtm-mail'); ?></button>
                        </div>
                    </div>
                </div>
            </form>
            <?php if (current_user_can('manage_options')) { ?>
                <form method="post">
                    <input type="hidden" name="action" value="test_mail"/>
                    <button type="submit" name="send_test_mail" class="button"><?php echo __('Test Mail', 'rtm-mail'); ?></button>
                </form>
            <?php } ?>
            <div class="modal log-action-delete-modal" id="modal_log_action_delete">
                <div class="modal-container">
                    <div class="modal-header" style="display: block!important;">
                        <i class="fas fa-times modal-close" data-id="log_action_delete"></i>
                    </div>
                    <div class="modal-content">
                        <form method="post">
                            <input type="hidden" name="_wpnonce"
                                   value="<?php echo esc_attr(wp_create_nonce('rtm_mail_action_delete')); ?>"/>
                            <input type="hidden" id="action_type" name="action" value="delete_log"/>
                            <input type="hidden" id="delete_log_id" name="log_id" value=""/>
                            <p>
                                <strong class="action_message"><?php echo __('Are you sure you want to delete the selected mail log?', 'rtm-mail'); ?></strong>
                            </p>
                            <button type="submit" name="delete_log" class="button button-delete"
                                    id="confirm_action"><?php echo __('Delete', 'rtm-mail'); ?></button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal log-action-send-modal" id="modal_log_action_send">
                <div class="modal-container">
                    <div class="modal-header" style="display: block!important;">
                        <i class="fas fa-times modal-close" data-id="log_action_send"></i>
                    </div>
                    <div class="modal-content">
                        <form method="post">
                            <input type="hidden" name="_wpnonce"
                                   value="<?php echo esc_attr(wp_create_nonce('rtm_mail_action_send')); ?>"/>
                            <input type="hidden" id="action_type" name="action" value="send_log"/>
                            <input type="hidden" id="send_log_id" name="log_id" value=""/>
                            <p>
                                <strong class="action_message"><?php echo __('Are you sure you want to send this email?', 'rtm-mail'); ?></strong>
                            </p>
                            <button type="submit" name="send_log" class="button button-success"
                                    id="confirm_action"><?php echo __('Send', 'rtm-mail'); ?></button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal preview-modal" id="modal_action_preview">
                <div class="modal-container">
                    <div class="modal-header">
                        <h3><?php echo __('Preview', 'rtm-mail'); ?></h3>
                        <i class="fas fa-times modal-close" data-id="action_preview"></i>
                    </div>
                    <hr>
                    <div class="modal-content">
                        <iframe src=""
                                class="mail-preview"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
