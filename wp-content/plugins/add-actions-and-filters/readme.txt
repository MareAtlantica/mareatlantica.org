=== Add Actions And Filters ===
Contributors: msimpson
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=SSABNHHPSVWT6
Tags: add actions and filters,add action,add filter,actions,filters
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Requires at least: 3.2.1
Tested up to: 3.9.1
Stable tag: 1.2

Add PHP Code to create your own Actions and Filters

== Description ==

Add PHP Code to create your own Actions and Filters.

Provides a place to add your code that is more convenient than putting it in your theme's functions.php file.

Add your code in the administration area -> <strong>Tools</strong> -> <strong>Add Actions and Filters</strong>
but this is only available to users with Administrator role.

Why this plugin?
Existing WordPress documentation suggests adding your own functions and filters in the theme's functions.php file. This is not a good idea because

* If you upgrade your theme, this file can be overwritten and
* if you change your theme then you need to add the same code to that theme as well.

Your code additions should not have to be artificially tied to your theme. This plugin frees you from that constraint.

Credit: inspired by a similar plugin: <a href="http://wordpress.org/extend/plugins/shortcode-exec-php/">Shortcode Exec PHP</a>

Credit: includes <a href="http://www.cdolivet.com/editarea/">Edit area</a>

== Installation ==


== Frequently Asked Questions ==

= I get a fatal PHP Error and my site doesn't work. How do I recover? =
If the code you save causes a fatal PHP error, it can cause your site to stop working.
In such a case, connect to you database via PHPMyAdmin.

1. Run the query: <code>SELECT * FROM wp_options WHERE option_name = 'AddActionsAndFilters_Plugin_code'</code>
1. Copy the code so that you have it
1. Delete it from the database: <code>DELETE FROM wp_options WHERE option_name = 'AddActionsAndFilters_Plugin_code'</code>

== Screenshots ==

1. Admin page where code is entered

== Changelog ==

= 1.3 =

* More graceful handling of PHP FATAL Errors introduced by user's code

= 1.2 =

* Fixed debug error message on admin page

= 1.1 =

* Limiting access to only Administrators to avoid possible security exploit

= 1.0 =

* Initial Revision
