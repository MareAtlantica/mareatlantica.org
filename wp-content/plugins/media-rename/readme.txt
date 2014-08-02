=== Media Rename ===
Contributors: ShadowsDweller
Tags: media, file, image, attachment, rename, retitle, change, name, title
Requires at least: 3.5
Tested up to: 3.8.1
Stable tag: 3.2.3
Donate Link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=8DAVXJ35WQSRE
License: GPL2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The Media Rename plugin allows you to easily rename (and retitle) your media files, once uploaded.

== Description ==

Easily rename (and retitle) your media files with the "Media Rename" plugin. Never lose your media information again!
Media Rename will not only rename your media, but also will check all the posts, metas and options on your site and change the links to the current media file, so they continue to be working!

NOTE: From version 3.1 the plugin will not only loop through your posts' content, but also all your metas and options in order to replace the old links with the new ones. If something goes wrong, it could mess up your database!
Please make sure to backup your database and files before using this plugin. I am not responsible for any damage caused by its usage!

== Installation ==

1. Upload `media-rename` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. It is done! You can go to any media file single page and will notice the new field "Filename". Bulk edit is also available at the "Media" listing page!

== Frequently Asked Questions ==

= How to rename a single media? =

Go to the Media section of the admin panel and open a media of your choice. You will see a new field named "Filename" and your current filename in it. Change it to whatever you want the new name to be and hit the "Update" button.

= How to bulk rename medias? =

Go to the Media section of the admin panel, select the "Rename" or "Rename & Retitle" bulk action (depending on if you want the media get retitled too) from the dropdown, check the medias you would like to rename and change their filenames using the "Filename" fields at the last column. When you are done, hit the "Apply" button and let the plugin do its magic!

= Can I use the plugin to rename medias via code? =

Sure, you can use the "do_rename" static function, located at the Media_Rename class. Prototype: Media_Rename::do_rename($attachment_id, $new_filename, $retitle = 0). On success the function returns 1, and on error - the error message.

== Screenshots ==

1. screenshot-1.jpg
2. screenshot-2.jpg

== Changelog ==

= 3.2.3 =
* Bugfix

= 3.2.2 =
* Speed optimization

= 3.2.1 =
* Bugfix

= 3.2 =
* Memory optimization tweaks
* Old size files are now deleted

= 3.1.2 =
* Bugfix

= 3.1.1 =
* Bugfix

= 3.1 =
* Rename & Retitle bulk action added
* Replaces the old links in metas & options too
* Renaming process totally revised
* Bugfixes

= 3.0 =
* Bulk Rename feature added
* OOP code

= 2.0.4 =
* Added compatibility with Wordpress 3.5

= 2.0.3 =
* Bugfix

= 2.0.2 =
* Fixed multiple dots in filename bug

= 2.0.1 =
* Multisite compatible
* Code cleaning

= 2.0 =
* Errors view added
* Media link correction added
* Bugfixes

= 1.0.1 =
* Filename sanitization bug fixed

= 1.0 =
* Initial version