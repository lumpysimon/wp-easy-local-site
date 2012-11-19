=== Easy Local Site ===
Contributors: lumpysimon
Tags: developer, development, local, xampp, mamp, wamp, email, override
Requires at least: 3.1
Tested up to: 3.5
Stable tag: trunk
License: GPL v2 or later

Aid the development process on a local WordPress site by adding some handy reminders and overriding outgoing emails.

== Description ==

When working on a local site and a production site at the same time, things can easily get confusing and you forget which site you're looking at. This plugin adds a hard-to-miss, bright orange reminder to the toolbar and prepends [LOCAL] to the title tag on your local site.

If you've imported a database from a production site, you may also want to avoid sending emails out to real users. This plugin overrides the 'to' address of all outgoing emails, sending them instead to an address specified by you. This enables you to test contact forms, notifications or any other outgoing email communications. All emails will have the original recipient's email address prepended to the subject line so you can see at a glance who they were originally intended for.

= The WP_LOCAL_DEV constant method =

You must use the WP_LOCAL_DEV constant method as outlined by Mark Jaquith: [markjaquith.wordpress.com/2011/06/24/wordpress-local-dev-tips](http://markjaquith.wordpress.com/2011/06/24/wordpress-local-dev-tips/)

If the WP_LOCAL_DEV constant is not defined this plugin will do nothing. This means you can safely put it in your mu-plugins folder and include it in your Git repository on a production site.

== Installation ==

To install directly from your WordPress dashboard:

 1. Go to the *Plugins* menu and click *Add New*.
 2. Search for *Easy Local Site*.
 3. Click *Install Now* next to the Easy Local Site plugin.
 4. Activate the plugin.

Alternatively, see the official WordPress Codex guide to [Manually Installing Plugins](http://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation).

= Using the mu-plugins folder is better =

Ideally you should install Easy Local Site in wp-content/mu-plugins (plugins in here are 'Must Use' and are automatically activated). This will mean that it is always activated, even after migrating a database from a production site where the plugin is not installed/activated, so you don't have to remember to manually activate it.

= The WP_LOCAL_DEV constant method =

You must use the WP_LOCAL_DEV constant method as outlined by Mark Jaquith: [markjaquith.wordpress.com/2011/06/24/wordpress-local-dev-tips](http://markjaquith.wordpress.com/2011/06/24/wordpress-local-dev-tips/)

If the WP_LOCAL_DEV constant is not defined this plugin will do nothing. This means you can safely put it in your mu-plugins folder and include it in your Git repository on a production site.

= Overriding outgoing emails =

To override outgoing emails, you must define WP_LOCAL_EMAIL in local-config.php as follows:

define( 'WP_LOCAL_EMAIL', 'me@myemailaddress.com' );

= Download from GitHub =

You can also download this plugin from GitHub at [https://github.com/lumpysimon/wp-easy-local-site](https://github.com/lumpysimon/wp-easy-local-site)

== Changelog ==

= 0.3 =
* Use WP_LOCAL_EMAIL constant for email address (for easier use by teams in a version-controlled environment)
* Prepare for localisation.
* Add PHPDoc comments

= 0.2 =
* Add email overriding

= 0.1 =
* Initial release
