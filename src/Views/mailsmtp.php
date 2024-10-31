<?php


use RtmMail\Cryptor;

$encryption_key = get_option('rtm_mail_smtp_key', false);
if (empty($encryption_key)) {
    $encryption_key = wp_salt();
    update_option('rtm_mail_smtp_key', $encryption_key);
}

$settings = get_option('rtm_mail_smtp_settings');
$smtp_enabled = $settings['smtp_enabled'] ?? false;
$host = $settings['host'] ?? '';
$encryption = $settings['encryption'] ?? 'none';
$port = $settings['port'] ?? 0;
$authentication = $settings['authentication'] ?? true;
$username = $settings['username'] ?? '';
$password = isset($settings['password']) && !empty($settings['password']) ? Cryptor::Decrypt($settings['password'], $encryption_key) : '';
?>
<div class="wrap" id="rtm-mail">
    <?php \RtmMail\Core::render_page_header('mailsmtp'); ?>

    <div class="rtm-mail-page-content">
        <div class="rtm-page-info">
            <p class="heading rtm-heading"><?php echo __('Mail SMTP', 'rtm-mail'); ?></p>
            <p class="rtm-heading-description"><?php echo __('Here you can setup the SMTP settings of your WordPress site, you can choose to use the default PHP Mailer of a Custom SMTP Mailer.', 'rtm-mail'); ?></p>
            <h1 class="notice-header"><?php echo __('Mail SMTP', 'rtm-mail'); ?></h1>
        </div>
        <?php if (!$smtp_enabled || empty($host)) { ?>
        <div class="notice notice-warning">
            <div class="rtm-mail-notice-content">
                <h2><?php echo __('SMTP Setup', 'rtm-mail'); ?></h2>
                <p><?php echo __('Setting up the SMTP allows the use of a more consistent way to send mails from this plugin. However to setup the SMTP with your host there are some things that need to be done before you can send mails.'); ?></p>
                <p>
                    <?php echo __('<strong>For Google (Gmail):</strong> To use the Gmail SMTP you need to turn on <strong>Access for less secure apps</strong>, go to <a href="https://www.google.com/settings/security/lesssecureapps" target="_blank">Less secure apps</a> and sign in to turn on access for less secured apps.'); ?><br />
                    <?php echo __('<strong>For Office 365 (Outlook):</strong> Make sure the sender address (or default WordPress mail) is set to the same mail address as your Outlook account.'); ?>
                </p>
                <p><?php echo __('Most SMTP servers require TLS or SSL authentication with the right port where you need to fill in your credentials. These credentials are encrypted and saved for this plugin.'); ?></p>
            </div>
        </div>
        <?php } ?>
        <hr>
        <div class="rtm-mail-page-content">
            <form id="rtm-mail-settings" action="" method="post">
                <div class="smtp-settings-container">
                    <div class="option-container">
                        <div class="settings-block">
                            <div class="settings-block-option">
                                <p class="option-label"><?php echo __('Enable Custom SMTP', 'rtm-mail'); ?></p>
                                <p class="option-description"><?php echo __('When enabled you can add a custom SMTP Mailer for this plugin, if this option is disabled it will use the default PHP mailer.', 'rtm-mail'); ?></p>
                            </div>
                            <div class="settings-block-input checkbox-input">
                                <label class="option-switch">
                                    <input type="checkbox" class="option-checkbox" id="enable__smtp"
                                           name="smtp_enabled" <?php echo checked(1, filter_var($smtp_enabled, FILTER_VALIDATE_BOOLEAN), false); ?> />
                                    <span class="option-slider">
                                        <span class="option-slider-on"><?php echo __('ON', 'rtm-mail'); ?></span>
                                        <span class="option-slider-off"><?php echo __('OFF', 'rtm-mail'); ?></span>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="option-container smtp__option" <?php echo ($smtp_enabled) ? 'style="opacity: 1.0; pointer-events:auto;"' : ''; ?>>
                        <div class="settings-block">
                            <div class="settings-block-option">
                                <p class="option-label"><?php echo __('Host', 'rtm-mail'); ?></p>
                                <p class="option-description"><?php echo __('The host URL of the SMTP Mail server where you can authenticate and send mails from.', 'rtm-mail'); ?></p>
                            </div>
                            <div class="settings-block-input checkbox-input">
                                <input type="text" name="smtp_host" id="smtp-host-input" class="form-input" value="<?php echo esc_attr($host); ?>" placeholder="<?php echo __('Ex. smtp.gmail.com', 'rtm-mail'); ?>" spellcheck="false" />
                            </div>
                        </div>
                        <div class="settings-block">
                            <div class="settings-block-option">
                                <p class="option-label"><?php echo __('Encryption', 'rtm-mail'); ?></p>
                                <p class="option-description"><?php echo __('Choose the different type of encryption, choose what your smtp host recommends. Often this is SSL or TLS', 'rtm-mail'); ?></p>
                            </div>
                            <div class="settings-block-input">
                                <div class="encryption-option">
                                    <input type="radio" name="smtp_encryption"
                                        <?php if ($encryption === 'none') echo "checked";?>
                                           value="none" /><span class="radio-label">None</span>
                                </div>
                                <div class="encryption-option">
                                    <input type="radio" name="smtp_encryption"
                                        <?php if ($encryption === 'ssl') echo "checked";?>
                                           value="ssl" /><span class="radio-label">SSL</span>
                                </div>
                                <div class="encryption-option">
                                    <input type="radio" name="smtp_encryption"
                                        <?php if ($encryption === 'tls') echo "checked";?>
                                           value="tls" /><span class="radio-label">TLS</span>
                                </div>
                            </div>
                        </div>
                        <div class="settings-block">
                            <div class="settings-block-option">
                                <p class="option-label"><?php echo __('Port', 'rtm-mail'); ?></p>
                                <p class="option-description"><?php echo __('The port of your SMTP host.', 'rtm-mail'); ?></p>
                            </div>
                            <div class="settings-block-input checkbox-input">
                                <input type="number" name="smtp_port" class="form-input" value="<?php echo esc_attr($port); ?>" style="width: 100px;"/>
                            </div>
                        </div>
                        <div class="settings-block">
                            <div class="settings-block-option">
                                <p class="option-label"><?php echo __('Authentication', 'rtm-mail'); ?></p>
                                <p class="option-description"><?php echo __('Enable or disable authentication for the smtp host.', 'rtm-mail'); ?></p>
                            </div>
                            <div class="settings-block-input checkbox-input">
                                <label class="option-switch">
                                    <input type="checkbox" class="option-checkbox" id="enable__authentication"
                                           name="smtp_authentication" <?php echo checked(1, filter_var($authentication, FILTER_VALIDATE_BOOLEAN), false); ?> />
                                    <span class="option-slider">
                                        <span class="option-slider-on"><?php echo __('ON', 'rtm-mail'); ?></span>
                                        <span class="option-slider-off"><?php echo __('OFF', 'rtm-mail'); ?></span>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <div class="settings-block smtp__credentials" <?php echo ($authentication) ? 'style="display:flex;"' : ''; ?>>
                            <div class="settings-block-option">
                                <p class="option-label"><?php echo __('Username', 'rtm-mail'); ?></p>
                                <p class="option-description"><?php echo __('Username of your SMTP Host', 'rtm-mail'); ?></p>
                            </div>
                            <div class="settings-block-input checkbox-input">
                                <input type="email" name="smtp_username" id="smtp-username-input" class="form-input" value="<?php echo esc_attr($username); ?>" placeholder="<?php echo __('Ex. example@gmail.com', 'rtm-mail'); ?>" spellcheck="false" />
                            </div>
                        </div>
                        <div class="settings-block smtp__credentials" <?php echo ($authentication) ? 'style="display:flex;"' : ''; ?>>
                            <div class="settings-block-option">
                                <p class="option-label"><?php echo __('Password', 'rtm-mail'); ?></p>
                                <p class="option-description"><?php echo __('Password of your SMTP Host. (This password is encrypted in the database)', 'rtm-mail'); ?></p>
                            </div>
                            <div class="settings-block-input checkbox-input">
                                <input type="password" name="smtp_password" id="smtp-password-input" class="form-input" value="<?php echo esc_attr($password); ?>" spellcheck="false" />
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="_wpnonce"
                           value="<?php echo esc_attr(wp_create_nonce('rtm_mail_save_smtp')); ?>"/>
                    <?php submit_button(__('Save settings', 'rtm-mail'), '', 'save_settings', false, ['id' => 'save-settings']); ?>
                </div>
            </form>
        </div>
    </div>
</div>
