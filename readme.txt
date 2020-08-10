=== Easy Local Site ===
Contributors: lumpysimon
Tags: developer, development, local, xampp, mamp, wamp, email, override
Requires at least: 3.1
Tested up to: 5.5
Stable tag: trunk
License: GPL v2 or later

A set of tools to aid the development process on a local WordPress site.

= Current features: =
* Display a handy reminder on the toolbar
* Override outgoing emails, either by sending them to a different address or by logging them as a 'Local Email' custom post type.

== Description ==

When working on a local site and a production site at the same time, things can easily get confusing and you forget which site you're looking at. This plugin adds a hard-to-miss, bright orange reminder to the toolbar and prepends [LOCAL] to the title tag on your local site.

If you've imported a database from a production site, you may also want to avoid sending emails out to real users (e.g. for testing contact forms or notifications). This plugin provides two options: you can override the 'to' address of all outgoing emails, sending them instead to an address specified by you (the original recipient's email address is prepended to the subject line so you can see at a glance who it was originally intended for); or you can disable the outgoing email completely and instead log it as the 'Local Email' custom post type.

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

To send all outgoing emails to a specified email address, you must define WP_LOCAL_EMAIL in local-config.php as follows:

define( 'WP_LOCAL_EMAIL', 'me@myemailaddress.com' );

= Disabling/logging outgoing emails =

To disable outgoing emails completely and instead log them as a 'Local Email' custom post type, you must define WP_LOCAL_EMAIL in local-config.php as follows:

define( 'WP_LOCAL_EMAIL', 'post' );

You will then be able to view all sent emails at http://example.com/wp-admin/edit.php?post_type=local-email

= Download from GitHub =

You can also download this plugin from GitHub at [https://github.com/lumpysimon/wp-easy-local-site](https://github.com/lumpysimon/wp-easy-local-site)

== Changelog ==

= 0.4 =
* Option to disable outgoing emails and log as a 'Local Email' custom post type instead.

= 0.3 =
* Use WP_LOCAL_EMAIL constant for email address (for easier use by teams in a version-controlled environment)
* Prepare for localisation.
* Add PHPDoc comments

= 0.2 =
* Add email overriding

= 0.1 =
* Initial release
