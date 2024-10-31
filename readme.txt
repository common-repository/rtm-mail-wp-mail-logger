=== WP Mail Logger ===
Contributors: appsrtmbusiness
Tags: wp mail, logger, smtp, mail logger, mailer, wordpress smtp, wordpress logger
Requires at least: 5.0
Tested up to: 5.9.1
Stable tag: 1.0.4
Requires PHP: 7.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

WP Mail Logger is a WordPress plugin used to catch every mail sent with the `wp_mail()` function and saves it to the database.

== Description ==

The WP Mail Logger is a plugin that allows you to log every mail inside your WordPress website.
You can use this plugin to identify mistakes or failures when an email is sent.
This plugin is ideal when you want to make sure that your clients get the right mail!

= Features =
* Log every mail within your WordPress website
* Overview to filter all mails
* Resend mails (For example if a mail fails to be sent)
* Mail error/debug logging (need to enable WP_DEBUG_LOG)
* Setup a custom SMTP connection

= Usage =
Everytime the `wp_mail` function gets called inside your WordPress website it will trigger a filter where the recently sent email will get caught. This works inside for every outgoing mail from the website and every incoming mail from the website.

An example wp_mail function could look like this:
```php
wp_mail(get_option('admin_email'), 'This is a sample subject', '<h1>This is a test</h1><p>This is just a test mail to see if everything is working OK</p>', 'Content-Type:text/html', []);
```

For questions, feedback or other issues please contact us at [WPMail](https://wpmail.nl/ "WP Mail Logger")

== Screenshots ==

1. Screenshot of the Dashboard page
2. Screenshot of the Overview page
3. Screenshot of the Settings page
4. Screenshot of the SMTP settings page

= Debug =
Every mail that gets caught, sent, failed or edited and every migration that is successful or fails will get logged in the Debug Events.
However you will need to set the constant `WP_DEBUG_LOG` to `true`, otherwise the Debug Events will only log `ERROR` level events inside the rtm-mail plugin.

== Frequently Asked Questions ==

= Will mails automatically be sent? =

Yes, unless there is a failure then you can resend it once the problem is identified.

== Changelog ==

= 1.0.4 =
* First WordPress repository release!
* Some technical and styling fixes
* Removed technical logging of request data (for the time being)

= 1.0.3 =
* Further changes to comply with the WordPress repository
* Fixed some security issues
* Added webpack to the plugin project (faster load time)

= 1.0.2 =
* Changes the site hyperlink in the plugins

= 1.0.1 =
* Changes to comply with the WordPress repository

= 1.0 =
* Initial release
* The ability to log and resend mails.
* Dashboard overview with some details and statistics
* See errors on the Dashboard
* Overview of all logged mails