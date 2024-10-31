<?php


global $dashboard;

$migration_version = get_option("rtm_mail_migration_version", '');
$settings = get_option('rtm_mail_settings');
$mail_capability = $settings['mail_capability'] ?? 'manage_options';
?>

<div class="wrap" id="rtm-mail">
    <?php \RtmMail\Core::render_page_header('dashboard'); ?>

    <div class="rtm-mail-page-content">
        <div class="rtm-page-info">
            <p class="heading rtm-heading"><?php echo __('Dashboard', 'rtm-mail'); ?></p>
            <p class="rtm-heading-description"><?php echo __('Here you have a general overview about everything related to the plugin. You can also find some statistics about the plugin.', 'rtm-mail'); ?></p>
            <h1 class="notice-header"><?php echo __('Dashboard', 'rtm-mail'); ?></h1>
        </div>
        <hr>
        <div class="rtm-mail-page-dashboard">
            <div class="dashboard-items-group dashboard-block-items">
                <div class="dashboard-block">
                    <h4 class="block-label"><?php echo __('Emails logged today', 'rtm-mail'); ?></h4>
                    <h3 class="block-data">
                        <span class="fa-stack block-icon">
                            <i class="fas fa-circle fa-stack-2x" style="color: #297da8;"></i>
                            <i class="fas fa-inbox fa-stack-1x"></i>
                        </span>
                        <span class="block-amount"><?php echo esc_attr($dashboard['blocks']['emails_logged']['total']); ?></span>
                    </h3>
                    <h5 class="block-updated"><?php echo ($dashboard['blocks']['emails_logged']['latest'] !== null) ? __('Last log on', 'rtm-mail') . ' ' . esc_attr($dashboard['blocks']['emails_logged']['latest']) : __('No emails logged yet', 'rtm-mail') ?></h5>
                </div>
                <div class="dashboard-block">
                    <h4 class="block-label"><?php echo __('Emails sent today', 'rtm-mail'); ?></h4>
                    <h3 class="block-data">
                        <span class="fa-stack block-icon">
                            <i class="fas fa-circle fa-stack-2x" style="color: #05988a;"></i>
                            <i class="fas fa-envelope fa-stack-1x"></i>
                        </span>
                        <span class="block-amount"><?php echo esc_attr($dashboard['blocks']['emails_sent']['total']); ?></span>
                    </h3>
                    <h5 class="block-updated"><?php echo ($dashboard['blocks']['emails_sent']['latest'] !== null) ? __('Last sent on', 'rtm-mail') . ' ' . esc_attr($dashboard['blocks']['emails_sent']['latest']) : __('No emails sent yet', 'rtm-mail') ?></h5>
                </div>
                <div class="dashboard-block">
                    <h4 class="block-label"><?php echo __('Events logged today', 'rtm-mail'); ?></h4>
                    <h3 class="block-data">
                        <span class="fa-stack block-icon">
                            <i class="fas fa-circle fa-stack-2x" style="color: #f6904d;"></i>
                            <i class="fas fa-database fa-stack-1x"></i>
                        </span>
                        <span class="block-amount"><?php echo esc_attr($dashboard['blocks']['events_logged']['total']); ?></span>
                    </h3>
                    <h5 class="block-updated"><?php echo ($dashboard['blocks']['events_logged']['latest'] !== null) ? __('Last event on', 'rtm-mail') . ' ' . esc_attr($dashboard['blocks']['events_logged']['latest']) : __('No events logged yet', 'rtm-mail') ?></h5>
                </div>
                <div class="dashboard-block">
                    <h4 class="block-label"><?php echo __('Errors logged today', 'rtm-mail'); ?></h4>
                    <h3 class="block-data">
                        <span class="fa-stack block-icon">
                            <i class="fas fa-circle fa-stack-2x" style="color: #bf0707;"></i>
                            <i class="fas fa-exclamation fa-stack-1x"></i>
                        </span>
                        <span class="block-amount"><?php echo esc_attr($dashboard['blocks']['errors_logged']['total']); ?></span>
                    </h3>
                    <h5 class="block-updated"><?php echo ($dashboard['blocks']['errors_logged']['latest'] !== null) ? __('Last error on', 'rtm-mail') . ' ' . esc_attr($dashboard['blocks']['errors_logged']['latest']) : __('No errors logged yet', 'rtm-mail') ?></h5>
                </div>
            </div>
            <div class="dashboard-items-group">
                <!-- Column 1 -->
                <div class="dashboard-items-column">
                    <!-- Core information -->
                    <div class="dashboard-item-container">
                        <div class="dashboard-item-header">
                            <div class="dashboard-header-content">
                                <i class="fas fa-toolbox"></i>
                                <h3><?php echo __('Core information', 'rtm-mail') ?></h3>
                            </div>
                        </div>
                        <div class="dashboard-item-content">
                            <div class="dashboard-widget-block">
                                <span class="dashboard-widget-label"><?php echo __('Core Settings', 'rtm-mail') ?></span>
                                <?php if (current_user_can('manage_options')) { ?>
                                    <span class="dashboard-widget-extra"><a
                                                href="<?php echo get_admin_url(); ?>admin.php?page=rtm-mail-settings"
                                                class="settings-box-dashboard"><i class="fas fa-cog"></i></a></span>
                                <?php } ?>
                            </div>
                            <?php if (current_user_can('manage_options')) { ?>
                            <div class="dashboard-content-group">
                                <span class="content-group-label"><?php echo __('Capability access', 'rtm-mail'); ?></span>
                                <span class="content-group-data"><span
                                            class="badge badge-dashboard-data"><?php echo esc_attr($mail_capability); ?></span></span>
                            </div>
                            <?php } ?>
                            <div class="dashboard-content-group pro-opacity">
                                <span class="content-group-label"><?php echo __('Block mails', 'rtm-mail'); ?> <a href="<?php echo RTM_MAIL_PRO_SITE; ?>" target="_blank" class="badge badge-pro">PRO</a></span>
                                <span class="content-group-data"><i class="fas fa-times-circle status-off"></i></span>
                            </div>
                            <div class="dashboard-content-group pro-opacity">
                                <span class="content-group-label"><?php echo __('Edit sent emails', 'rtm-mail'); ?> <a href="<?php echo RTM_MAIL_PRO_SITE; ?>" target="_blank" class="badge badge-pro">PRO</a></span>
                                <span class="content-group-data"><i class="fas fa-times-circle status-off"></i></span>
                            </div>
                            <div class="dashboard-content-group pro-opacity">
                                <span class="content-group-label"><?php echo __('Send caught emails', 'rtm-mail'); ?> <a href="<?php echo RTM_MAIL_PRO_SITE; ?>" target="_blank" class="badge badge-pro">PRO</a></span>
                                <span class="content-group-data"><i class="fas fa-times-circle status-off"></i></span>
                            </div>
                            <div class="dashboard-widget-block" style="border-top: none">
                                <span class="dashboard-widget-label"><?php echo __('Core Statistics', 'rtm-mail') ?></span>
                            </div>
                            <div class="dashboard-content-group">
                                <span class="content-group-label"><?php echo __('Total intercepted emails', 'rtm-mail'); ?></span>
                                <span class="content-group-data"><span
                                            class="badge badge-dashboard-data"><?php echo esc_attr($dashboard['logs_info']['total']); ?></span></span>
                            </div>
                            <div class="dashboard-content-group" style="border-bottom: none">
                                <span class="content-group-label"><?php echo __('Total events logged', 'rtm-mail'); ?></span>
                                <span class="content-group-data"><span
                                            class="badge badge-dashboard-data"><?php echo esc_attr($dashboard['event_info']['total']); ?></span></span>
                            </div>
                            <?php if (current_user_can('manage_options')) { ?>
                                <div class="dashboard-widget-block">
                                    <span class="dashboard-widget-label"><?php echo __('Admin information', 'rtm-mail') ?></span>
                                </div>
                                <div class="dashboard-content-group">
                                    <span class="content-group-label"><?php echo __('Migration version', 'rtm-mail'); ?></span>
                                    <span class="content-group-data"><?php echo ($migration_version === RTM_MAIL_VERSION) ? '<span class="badge badge-dashboard-data">' . esc_attr($migration_version) . '</span>' : '<span class="badge badge-dashboard-data" style="background: #bf0707; color: #fff;margin-right: 5px;"> OUTDATED</span><span class="badge badge-dashboard-data">' . esc_attr($migration_version) . '</span>' ?></span>
                                </div>
                                <div class="dashboard-content-group" style="border-bottom: none;">
                                    <span class="content-group-label"><?php echo __('Logs database table', 'rtm-mail'); ?></span>
                                    <span class="content-group-data"><span
                                                class="badge badge-dashboard-data"><?php echo RTM_MAIL_TABLE_PREFIX; ?>logs</span></span>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <!-- Queue statistics -->
                    <div class="dashboard-item-container">
                        <div class="dashboard-item-header">
                            <div class="dashboard-header-content">
                                <i class="fas fa-calendar-alt"></i>
                                <h3><?php echo __('Queue information', 'rtm-mail') ?></h3>
                            </div>
                            <div class="dashboard-header-content">
                                <a href="<?php echo RTM_MAIL_PRO_SITE; ?>" target="_blank" class="badge badge-pro" style="margin-right: 10px;">PRO</a>
                                <a class="far fa-question-circle icon__info" data-target="modal_queue_info"></a>
                            </div>
                        </div>
                        <div class="dashboard-item-content">
                            <div class="dashboard-widget-block">
                                <span class="dashboard-widget-label"><?php echo __('Queue Statistics', 'rtm-mail') ?></span>
                            </div>
                            <div class="dashboard-content-group" style="border-bottom: none;">
                                <span class="content-group-label"><?php printf(__('This feature is only available in the <a href="%s" target="_blank" style="font-weight: 600">PRO</a> version of this plugin.', 'rtm-mail'), RTM_MAIL_PRO_SITE); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Column 2 -->
                <div class="dashboard-items-column">
                    <!-- Logs statistics -->
                    <div class="dashboard-item-container">
                        <div class="dashboard-item-header">
                            <div class="dashboard-header-content">
                                <i class="fas fa-inbox"></i>
                                <h3><?php echo __('Logs information', 'rtm-mail') ?></h3>
                            </div>
                            <a class="far fa-question-circle icon__info" data-target="modal_logs_info"></a>
                        </div>
                        <div class="dashboard-item-content">
                            <div class="dashboard-widget-block">
                                <span class="dashboard-widget-label"><?php echo __('Logs Statistics', 'rtm-mail') ?></span>
                            </div>
                            <div class="dashboard-content-group" style="padding: 0;">
                                <div class="content-group-item">
                                    <span class="group-item-label"><i class="fas fa-database"></i> <span
                                                class="group-item-amount"><?php echo esc_attr($dashboard['logs_info']['total']); ?></span> <?php echo __('total', 'rtm-mail'); ?></span>
                                </div>
                                <div class="content-group-item">
                                    <span class="group-item-label"><i class="fas fa-inbox status-normal"></i> <span
                                                class="group-item-amount"><?php echo esc_attr($dashboard['logs_info']['caught']); ?></span> <?php echo __('caught', 'rtm-mail'); ?></span>
                                </div>
                                <div class="content-group-item">
                                    <span class="group-item-label"><i class="fas fa-envelope status-on"></i> <span
                                                class="group-item-amount"><?php echo esc_attr($dashboard['logs_info']['sent']); ?></span> <?php echo __('sent', 'rtm-mail'); ?></span>
                                </div>
                                <div class="content-group-item">
                                    <span class="group-item-label"><i class="fas fa-exclamation-circle status-off"></i> <span
                                                class="group-item-amount"><?php echo esc_attr($dashboard['logs_info']['failed']); ?></span> <?php echo __('failed', 'rtm-mail'); ?></span>
                                </div>
                            </div>
                            <div class="dashboard-content-group">
                                <span class="content-group-label"><?php echo __('Deleted email logs', 'rtm-mail'); ?></span>
                                <span class="content-group-data"><span
                                            class="badge badge-dashboard-data"><?php echo esc_attr($dashboard['logs_info']['deleted_logs']); ?></span></span>
                            </div>
                            <div class="dashboard-content-group">
                                <span class="content-group-label"><?php echo __('Most recent added log', 'rtm-mail'); ?></span>
                                <span class="content-group-data"><?php echo ($dashboard['logs_info']['last_added']['id'] !== null) ? '<a href="' . get_admin_url() . 'admin.php?page=rtm-mail-details&log_id=' . $dashboard['logs_info']['last_added']['id'] . '">' . $dashboard['logs_info']['last_added']['subject'] . ' (#' . $dashboard['logs_info']['last_added']['id'] . ')</a>' : __('No recently added log yet...', 'rtm-mail'); ?></span>
                            </div>
                            <div class="dashboard-content-group">
                                <span class="content-group-label"><?php echo __('Most recent edited log', 'rtm-mail'); ?></span>
                                <span class="content-group-data"><?php echo ($dashboard['logs_info']['last_edited']['id'] !== null) ? '<a href="' . get_admin_url() . 'admin.php?page=rtm-mail-details&log_id=' . $dashboard['logs_info']['last_edited']['id'] . '">' . $dashboard['logs_info']['last_edited']['subject'] . ' (#' . $dashboard['logs_info']['last_edited']['id'] . ')</a>' : __('No recently edited log yet...', 'rtm-mail'); ?></span>
                            </div>
                            <div class="dashboard-content-group">
                                <span class="content-group-label"><?php echo __('Most recent sent log', 'rtm-mail'); ?></span>
                                <span class="content-group-data"><?php echo ($dashboard['logs_info']['last_sent']['id'] !== null) ? '<a href="' . get_admin_url() . 'admin.php?page=rtm-mail-details&log_id=' . $dashboard['logs_info']['last_sent']['id'] . '">' . $dashboard['logs_info']['last_sent']['subject'] . ' (#' . $dashboard['logs_info']['last_sent']['id'] . ')</a>' : __('No recently sent log yet...', 'rtm-mail'); ?></span>
                            </div>
                            <div class="dashboard-content-group" style="border-bottom: none;">
                                <span class="content-group-label"><a
                                            href="<?php echo get_admin_url(); ?>admin.php?page=rtm-mail-overview"
                                            class="button button-action"
                                            style="font-size: 14px; margin: 5px 0 5px 0;"><?php echo __('Go to logs overview', 'rtm-mail'); ?></a></span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Column 3 -->
                <div class="dashboard-items-column">
                    <!-- Event statistics -->
                    <div class="dashboard-item-container">
                        <div class="dashboard-item-header">
                            <div class="dashboard-header-content">
                                <i class="fas fa-database"></i>
                                <h3><?php echo __('Debug events information', 'rtm-mail') ?></h3>
                            </div>
                            <div class="dashboard-header-content">
                                <a href="<?php echo RTM_MAIL_PRO_SITE; ?>" target="_blank" class="badge badge-pro" style="margin-right: 10px;">PRO</a>
                                <a class="far fa-question-circle icon__info" data-target="modal_events_info"></a>
                            </div>
                        </div>
                        <div class="dashboard-item-content">
                            <div class="dashboard-widget-block">
                                <span class="dashboard-widget-label"><?php echo __('Event Statistics', 'rtm-mail') ?></span>
                            </div>
                            <div class="dashboard-content-group" style="border-bottom: none;">
                                <span class="content-group-label"><?php printf(__('This feature is only available in the <a href="%s" target="_blank" style="font-weight: 600">PRO</a> version of this plugin.', 'rtm-mail'), RTM_MAIL_PRO_SITE); ?></span>
                            </div>
                            <div class="dashboard-widget-block">
                                <span class="dashboard-widget-label"><?php echo __('Last errors', 'rtm-mail') ?></span>
                            </div>
                            <?php
                            if (isset($dashboard['event_info']['last_errors'][0])) {
                                foreach ($dashboard['event_info']['last_errors'] as $error_data) {
                                    ?>
                                    <div class="dashboard-content-group">
                                        <span class="content-group-label"><i
                                                    class="fas fa-exclamation-circle status-off"></i> <i
                                                    class="error-date"><?php echo $error_data['date']->format('d-m-Y H:i'); ?></i></span>
                                        <span class="content-group-data"><code><?php echo $error_data['message']; ?> </code></span>
                                    </div>
                                    <?php
                                }
                            } else {
                                ?>
                                <div class="dashboard-content-group" >
                                    <span class="content-group-label"><i>No errors were found...</i></span>
                                    <span class="content-group-data"></span>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal log-action-send-modal" id="modal_logs_info">
        <div class="modal-container">
            <div class="modal-header">
                <h3><?php echo __('Logs information', 'rtm-mail'); ?></h3>
                <i class="fas fa-times modal-close" data-id="logs_info"></i>
            </div>
            <hr>
            <div class="modal-content">
                <p><?php echo __('The most important part of the plugin is logging emails that are sent with the wp_mail function. In this plugin you have an overview of all logged emails that are sent from or to your website. You can visit the overview page by clicking the "Go to overview" button. At the overview page you can also filter, edit, send or delete emails that have been logged.', 'rtm-mail'); ?></p>
                <p><?php echo __('On this dashboard page you see a few statistics about the email logs.', 'rtm-mail'); ?></p>
            </div>
        </div>
    </div>
    <div class="modal log-action-send-modal" id="modal_events_info">
        <div class="modal-container">
            <div class="modal-header">
                <h3><?php echo __('Debug events information', 'rtm-mail'); ?></h3>
                <i class="fas fa-times modal-close" data-id="events_info"></i>
            </div>
            <hr>
            <div class="modal-content">
                <p><?php echo __('Debug events are logs that are registering every event within this plugin. These events contain: Logging a mail, sending a mail, deleting a mail, editing a mail and checking migration status. It is also possible within the debug events to debug the caught mail. You can for example backtrace where the mail is called from in code and see the request data of the sent email. Debug events are only accessible for administrators.', 'rtm-mail'); ?></p>
                <p><?php echo __('On this dashboard page you can see some statistics about the debug events and the last 5 errors that are found.', 'rtm-mail'); ?></p>
            </div>
        </div>
    </div>
    <div class="modal log-action-send-modal" id="modal_queue_info">
        <div class="modal-container">
            <div class="modal-header">
                <h3><?php echo __('Queue information', 'rtm-mail'); ?></h3>
                <i class="fas fa-times modal-close" data-id="queue_info"></i>
            </div>
            <hr>
            <div class="modal-content">
                <p><?php echo __('Within this plugin you have the ability to queue emails for sending them out at a specific date and time. You can queue email logs at the overview page or at the details page of a certain log.', 'rtm-mail'); ?></p>
                <p><?php echo __('Here on the dashboard you can find the total amount of queued emails and the first outgoing queued email log with a countdown timer.', 'rtm-mail'); ?></p>
            </div>
        </div>
    </div>
</div>
