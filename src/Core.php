<?php

namespace RtmMail;


class Core
{
    private static $pages;

    private $catcher;
    private $listener;

    
    public function __construct()
    {
        $settings = get_option('rtm_mail_settings');
        $mail_capability = $settings['mail_capability'] ?? 'manage_options';
                $dashboard = new Dashboard();

                self::$pages = [
            'main' => [                 'page' => $dashboard,
                'page_title' => __('WP Mail Logger - Dashboard', 'rtm-mail'),
                'menu_title' => 'WP Mail Logger',
                'capability' => $mail_capability,
                'menu_slug' => 'rtm-mail-dashboard',
                'icon_url' => 'dashicons-email-alt',
            ],
            'dashboard' => [                 'page' => $dashboard,
                'parent_slug' => 'rtm-mail-dashboard',
                'page_title' => __('WP Mail Logger - Dashboard', 'rtm-mail'),
                'menu_title' => __('Dashboard', 'rtm-mail'),
                'capability' => $mail_capability,
                'menu_slug' => 'rtm-mail-dashboard',
            ],
            'overview' => [                 'page' => new Overview(),
                'parent_slug' => 'rtm-mail-dashboard',
                'page_title' => __('WP Mail Logger - Overview', 'rtm-mail'),
                'menu_title' => __('Overview', 'rtm-mail'),
                'capability' => $mail_capability,
                'menu_slug' => 'rtm-mail-overview',
            ],
            'settings' => [                 'page' => new Settings(),
                'parent_slug' => 'rtm-mail-dashboard',
                'page_title' => __('WP Mail Logger - Settings', 'rtm-mail'),
                'menu_title' => __('Settings', 'rtm-mail'),
                'capability' => 'manage_options',
                'menu_slug' => 'rtm-mail-settings',
            ],
            'mailsmtp' => [                 'page' => new MailSMTP(),
                'parent_slug' => 'rtm-mail-dashboard',
                'page_title' => __('WP Mail Logger - Mail SMTP', 'rtm-mail'),
                'menu_title' => __('Mail SMTP', 'rtm-mail'),
                'capability' => 'manage_options',
                'menu_slug' => 'rtm-mail-mailsmtp',
            ],
            'events' => [                 'page' => new Events(),
                'parent_slug' => 'rtm-mail-dashboard',
                'page_title' => __('WP Mail Logger - Debug Events', 'rtm-mail'),
                'menu_title' => __('Debug Events', 'rtm-mail'),
                'capability' => 'manage_options',
                'menu_slug' => 'rtm-mail-events',
                'is_pro' => true,
            ],
            'details' => [                 'page' => new Details(),
                'parent_slug' => '',
                'page_title' => __('WP Mail Logger - Details', 'rtm-mail'),
                'menu_title' => __('Details', 'rtm-mail'),
                'capability' => $mail_capability,
                'menu_slug' => 'rtm-mail-details',
            ],
        ];

                $this->catcher = new Catcher();

                $this->listener = new EventListener();

                add_filter('wonolog.channels', function ($channels) {
            $channels[] = EventListener::CHANNEL_NAME;
            return $channels;
        });
                add_filter('wonolog.default-handler-folder', function ($folder) {
            $upload = wp_upload_dir();
            return $upload['basedir'] . '/rtm-mail/logs';
        });

                $this->add_filters();
                $this->add_actions();
    }

    
    private function add_filters()
    {
                add_filter('wp_mail', [$this->catcher, 'catch_mail'], 9999999);
                add_filter('admin_footer_text', [$this, 'display_footer_text']);

        add_filter( 'plugin_action_links_' . plugin_basename(RTM_MAIL_PLUGIN_FILE), [$this, 'plugin_actions'], 10, 1);
    }

    
    private function add_actions()
    {
        add_action('admin_init', [$this, 'init']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('admin_menu', [$this, 'add_pages']);
        add_action('plugins_loaded', [$this, 'check_migrations']);
        add_action('admin_footer', [$this, 'display_footer']);
        add_action('wp_mail_failed', [$this->catcher, 'mail_error'], 9999999);
                add_action('rtmmail_migration_failed', [$this->listener, 'migration_failed'], 10, 2);
        add_action('rtmmail_migration_success', [$this->listener, 'migration_success']);
        add_action('rtmmail_send_failed', [$this->listener, 'send_failed'], 10, 2);
        add_action('rtmmail_send_success', [$this->listener, 'send_success']);
        add_action('rtmmail_log_deleted', [$this->listener, 'log_deleted'], 10, 2);
    }

    

    
    public function init()
    {
        load_plugin_textdomain('rtm-mail', false, RTM_MAIL_PLUGIN_PATH . '/assets/languages');
    }

    
    public function activate()
    {
        if (class_exists('WPMailSMTP\Core')) {
            add_action('admin_notices', function () {
                echo '<div class="notice notice-warning"> ';
                echo '<p><strong>WP Mail Logger: </strong>';
                echo __('You are currently using <code>WP-Mail-SMTP</code> which may conflict with the sender options in this plugin.', 'rtm-mail');
                echo '</p>';
                echo '</div>';
            });
        }
        update_option('rtm_mail_settings', [
            'mail_capability' => 'manage_options',
        ]);

        update_option('rtm_mail_smtp_settings', [
            'smtp_enabled' => false,
            'host' => '',
            'encryption' => 'none',
            'port' => 0,
            'authentication' => true,
            'username' => '',
            'password' => '',
        ]);
    }

    
    public function deactivate()
    {
        delete_option('rtm_mail_settings');
        wp_clear_scheduled_hook('rtm_mail_send_caught_logs');
        wp_clear_scheduled_hook('rtm_mail_send_log');
    }

    
    public function check_migrations()
    {
        (new Migration(RTM_MAIL_VERSION))->check();
    }

    
    public function add_pages()
    {
        foreach (self::$pages as $page => $page_data) {
            if ($page === 'main') {
                add_menu_page($page_data['page_title'], $page_data['menu_title'], $page_data['capability'], $page_data['menu_slug'], [$page_data['page'], 'display'], $page_data['icon_url']);
            } else {
                add_submenu_page($page_data['parent_slug'], $page_data['page_title'], $page_data['menu_title'], $page_data['capability'], $page_data['menu_slug'], [$page_data['page'], 'display']);
            }
        }
    }

    
    public function display_footer()
    {
                if ($this->page_check()) {
            echo '<div class="rtm-mail-footer">';
            echo '<div class="rtm-mail-logo"><a href="https://www.rtmbusiness.nl/?utm_source=rtm-mail-plugin&utm_medium=footer&utm_campaign=mail-plugin" target="_blank"><img src="' . RTM_MAIL_PLUGIN_PATH . 'assets/images/rtm-logo.png" alt="rtm-logo" /></a></div>';
            echo '</div>';
        }
    }

    
    public function display_footer_text($text)
    {
                if ($this->page_check()) {
            $text = sprintf(__('Thanks for using the <span style="font-weight: 600;">WP Mail Logger Plugin</span>. Consider upgrading to <a href="%s" style="font-weight: 600" target="_blank">PRO</a> to get more features!', 'rtm-mail'), RTM_MAIL_PRO_SITE);
            $text .= sprintf(__(' If you have any issues please <a href="%s" style="font-weight: 600" target="_blank">contact us</a>!', 'rtm-mail'), RTM_MAIL_PRO_SITE . '#contact');
        }
        return $text;
    }

    
    public function plugin_actions($links)
    {
        $links['pro'] = sprintf(__('<a href="%s" style="font-weight: bold">Upgrade to PRO</a> ', 'rtm-mail'), RTM_MAIL_PRO_SITE);
        return $links;
    }

    
    public function enqueue_scripts()
    {
                if ($this->page_check()) {
                        wp_enqueue_style('fontawesome', 'https://use.fontawesome.com/releases/v5.7.0/css/all.css');

                        wp_enqueue_style('rtm-mail', RTM_MAIL_PLUGIN_PATH . 'assets/css/style.css');
            wp_register_script('rtm-mail', RTM_MAIL_PLUGIN_PATH . 'dist/bundle.js');
            wp_localize_script('rtm-mail', 'rtm_mail_translation', [
                'type_address' => __('Type an address', 'rtm-mail'),
                'status_sent' => __('Sent', 'rtm-mail'),
            ]);
            wp_enqueue_script('rtm-mail');
        }
    }

    
    private function page_check()
    {
                foreach (self::$pages as $page_data) {
                        if ($page_data['page']->page_check()) {
                return true;
            }
        }
                return false;
    }

    
    public static function render_page_header($current_page)
    {
?>
        <div class="navbar">
            <div class="navbar-item">
                <div class="nav-logo">
                    <img src="<?php echo RTM_MAIL_PLUGIN_PATH; ?>assets/images/logo-icon.png" alt="wp-mail-logger logo"
                         class="logo-icon"/>
                    <img src="<?php echo RTM_MAIL_PLUGIN_PATH; ?>assets/images/logo.png" alt="wp-mail-logger logo"
                         class="logo"/>
                    <span class="nav-version">v<?php echo RTM_MAIL_VERSION; ?></span>
                </div>
                <a href="#" class="nav-link mobile-link-toggle"><i class="fas fa-bars"></i></a>
                <div class="nav-menu-links">
                    <?php foreach (self::$pages as $page => $page_data) { ?>
                        <?php if (isset($page_data['parent_slug']) && !empty($page_data['parent_slug'])) { ?>
                            <?php if (current_user_can($page_data['capability'])) { ?>
                                <?php if (!isset($page_data['is_pro']) || !$page_data['is_pro']) { ?>
                                    <a href="<?php echo get_admin_url(); ?>admin.php?page=<?php echo $page_data['menu_slug']; ?>" class="nav-link <?php echo ($page === $current_page) ? 'nav-selected' : ''; ?>"><?php echo $page_data['menu_title']; ?></a>
                                <?php } else { ?>
                                    <a href="<?php echo get_admin_url(); ?>admin.php?page=<?php echo $page_data['menu_slug']; ?>" class="nav-link <?php echo ($page === $current_page) ? 'nav-selected' : ''; ?>"><?php echo $page_data['menu_title']; ?> <span class="badge badge-pro" style="margin-left: 5px;">PRO</span></a>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>
            <div class="navbar-item navbar-button" style="margin-right: 20px;">
                <a href="<?php echo RTM_MAIL_PRO_SITE; ?>" target="_blank" class="button button-pro"><?php echo __('Upgrade to PRO'); ?></a>
            </div>
        </div>
        <div class="navbar-mobile-container">
            <div class="navbar-mobile">
                <?php foreach (self::$pages as $page => $page_data) { ?>
                    <?php if (isset($page_data['parent_slug']) && !empty($page_data['parent_slug'])) { ?>
                        <?php if (current_user_can($page_data['capability'])) { ?>
                            <?php if (!isset($page_data['is_pro']) || !$page_data['is_pro']) { ?>
                                <a href="<?php echo get_admin_url(); ?>admin.php?page=<?php echo $page_data['menu_slug']; ?>"
                                   class="nav-link-mobile <?php echo ($page === $current_page) ? 'nav-mobile-selected' : ''; ?>"><?php echo $page_data['menu_title']; ?></a>
                            <?php } else { ?>
                                <a href="<?php echo get_admin_url(); ?>admin.php?page=<?php echo $page_data['menu_slug']; ?>" class="nav-link-mobile <?php echo ($page === $current_page) ? 'nav-mobile-selected' : ''; ?>"><?php echo $page_data['menu_title']; ?>
                                    <span class="badge badge-pro" style="margin-left: 5px;">PRO</span></a>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
<?php
    }
}
