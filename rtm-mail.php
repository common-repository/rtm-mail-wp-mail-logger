<?php
/**
 * Plugin Name: RTM Mail | WP Mail Logger
 * Version: 1.0.4
 * Requires at least: 5.0
 * Requires PHP: 7.2
 * Plugin URI: https://wpmail.nl/
 * Description: Catches every mail sent with the <code>wp_mail()</code> function and saves it to the database. This plugin also allows you to set up an <strong>SMTP connection</strong>
 * Author: RTM Business
 * Text Domain: rtm-mail
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */


// When the file is called directly exit the code
if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('get_plugin_data')) {
    require_once(ABSPATH . 'wp-admin/includes/plugin.php');
}

// Define the plugin path when it isn't     defined yet
if (!defined('RTM_MAIL_PLUGIN_PATH')) {
    define('RTM_MAIL_PLUGIN_PATH', plugin_dir_url(__FILE__));
}

// Define the plugin version (Don't change this, it's needed for the migration check)
if (!defined('RTM_MAIL_VERSION')) {
    $plugin_data = get_plugin_data(__FILE__);
    define('RTM_MAIL_VERSION', $plugin_data['Version']);
}

// Define the plugin database table prefix
if (!defined('RTM_MAIL_TABLE_PREFIX')) {
    define('RTM_MAIL_TABLE_PREFIX', $GLOBALS['wpdb']->prefix . 'rtm_mail_');
}

// Define the plugin file path
if (!defined('RTM_MAIL_PLUGIN_FILE')) {
    define('RTM_MAIL_PLUGIN_FILE', __FILE__);
}

// Define the plugin database table prefix
if (!defined('RTM_MAIL_PRO_SITE')) {
    define('RTM_MAIL_PRO_SITE', 'https://wpmail.nl/');
}

// Require the autoloader
if (!class_exists("RtmMail\\Core")) {
    require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';
}
// Load the core class
$core = new RtmMail\Core();
// register activation and deactivation hook
register_activation_hook(__FILE__, [$core, 'activate']);
register_deactivation_hook(__FILE__, [$core, 'deactivate']);

// Instantiate Wonolog
Inpsyde\Wonolog\bootstrap();