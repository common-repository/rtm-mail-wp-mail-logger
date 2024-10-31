<?php

?>

<div class="wrap" id="rtm-mail">
    <?php \RtmMail\Core::render_page_header('events'); ?>

    <div class="rtm-mail-page-content">
        <div class="rtm-page-info">
            <p class="heading rtm-heading"><?php echo __('Events', 'rtm-mail'); ?> <a href="<?php echo RTM_MAIL_PRO_SITE; ?>" target="_blank" class="badge badge-pro" style="margin-left: 10px;">PRO</a></p>
            <p class="rtm-heading-description"><?php echo __('On this page you can check every event that happened in the WP Mail Logger Plugin. Here you can see what logs were sent, edited or deleted. You can also find emails that failed to sent and the error.', 'rtm-mail'); ?></p>
            <h1 class="notice-header"><?php echo __('Events', 'rtm-mail'); ?></h1>
        </div>
        <hr>
        <div class="rtm-mail-page-events">
            <h2 class="feature-title"><?php echo __('Debug Events features', 'rtm-mail'); ?>:</h2>
            <div class="feature-block-item">
                <div class="feature-item">
                    <div class="feature-block">
                        <ul class="feature-list">
                            <li><i class="fas fa-check-circle feature-check"></i> <?php echo __('An overview of everything that happens inside this plugin', 'rtm-mail'); ?></li>
                            <li><i class="fas fa-check-circle feature-check"></i> <?php echo __('See everything that happens to a mail log (edit, send, delete, queue)', 'rtm-mail'); ?></li>
                            <li><i class="fas fa-check-circle feature-check"></i> <?php echo __('Trace back every error that happens inside this plugin', 'rtm-mail'); ?></li>
                            <li><i class="fas fa-check-circle feature-check"></i> <?php echo __('See the source where the logged mail came from', 'rtm-mail'); ?></li>
                            <li><i class="fas fa-check-circle feature-check"></i> <?php echo __('Backtrace the logged mail in code', 'rtm-mail'); ?></li>
                            <li><i class="fas fa-check-circle feature-check"></i> <?php echo __('Look through all request data from a logged mail', 'rtm-mail'); ?></li>
                        </ul>
                        <a href="<?php echo RTM_MAIL_PRO_SITE; ?>" target="_blank" class="button button-pro" style="margin-top: 10px;"><?php echo __('Upgrade to PRO', 'rtm-mail'); ?></a>
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-image-preview" data-target="overview">
                        <span class="fa-stack search-icon">
                            <i class="fas fa-circle fa-stack-2x"></i>
                            <i class="fas fa-search-plus fa-stack-1x"></i>
                        </span>
                        <img src="<?php echo RTM_MAIL_PLUGIN_PATH; ?>assets/images/event-overview.png" alt="event overview preview"
                             class="preview-image"/>
                    </div>
                    <div class="feature-image-preview" data-target="details">
                        <span class="fa-stack search-icon">
                            <i class="fas fa-circle fa-stack-2x"></i>
                            <i class="fas fa-search-plus fa-stack-1x"></i>
                        </span>
                        <img src="<?php echo RTM_MAIL_PLUGIN_PATH; ?>assets/images/event-details.png" alt="event details preview"
                             class="preview-image"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="image-preview-modal" id="image-overview">
        <i class="fas fa-times preview__close" data-close="overview"></i>
        <div class="image-preview-container">
            <img src="<?php echo RTM_MAIL_PLUGIN_PATH; ?>assets/images/event-overview.png" alt="event overview preview" />
        </div>
    </div>

    <div class="image-preview-modal" id="image-details">
        <i class="fas fa-times preview__close" data-close="details"></i>
        <div class="image-preview-container">
            <img src="<?php echo RTM_MAIL_PLUGIN_PATH; ?>assets/images/event-details.png" alt="event details preview" />
        </div>
    </div>
</div>
