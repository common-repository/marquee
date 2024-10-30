=== Marquee ===
Contributors: Pierre Sudarovich
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=9912497
Tags: Marquee, scrolling text
Requires at least: 2.0
Tested up to: 3.0
Stable tag: trunk

A plugin that will let you embed, in the desired section of your page, a scrolling text fully configurable.

== Description ==

Embed, where you want, a scrolling message on your blog.

Features:
--------
* add a scrolling (or fixed) text in your wanted direction (right to left, left to right, bottom to top, top to bottom or fixed) in a choosen section of your page,
* possible customization : change height, width, font-size, direction, speed, background color (or image), text color, stop on mouse over,
* define precisely where to show it (multiple choice available) : everywhere, on the homepage, on pages, on search page, on single pages, on archive pages, on categories pages,
* internationalization of the plugin,
* XHTML conform,
* tested and working on IE6, IE7, IE8, Firefox, Opera, Chrome, Safari,
* the marque will be shown only where your page will be load ;). 


== Installation ==

1. Upload the folder `marquee` to your `/wp-content/plugins/` directory,
2. Activate the plugin through the "Plugins" menu in WordPress,
3. Go to plugin interface to define the message content and its render ;)
 

== Frequently Asked Questions ==

In some explanations, i ask to edit a php file. Be careful to edit them with a good editor like [Notepad++](http://notepad-plus.sourceforge.net/ "") and open each file with the format "UTF-8 without BOM".

= I've write an url in my marquee and it's appear as text. What's wrong ? =
The plugin, in its actual state do not transform automatically url. You have to "manually" write them like this `<a href="http://www.google.com/" target="_blank">Google</a>`

= I'd like to get the Marquee interface in my native language. How can i do that? =
Download the files of your native language. Adapt it, eventually, to your needs by using a PO file editor such as :
KBabel (Linux) should be available as a package for your Linux distribution, so install the package.
poEdit (Linux/Windows) available from http://www.poedit.net/.
Put the marquee-xx_XX.mo file in the `lang` folder (under `marquee`), the PO file is just here to generate the MO translation file...

= Ok, i've done what you explain above, but the Marquee interface is still in english How to make it works? =
Open your `wp-config.php` file (at the root of your blog) and search for : `define ('WPLANG', 'xx_XX');` where xx_XX is your language. If this line doesn't exist add it in your file. Save your modifications and re-upload the wp-config on your server.

= Does Marquee works with WP-MU? =
Yes


== Screenshots ==
1. Example of marquee in the `content` part of my blog.

2. Example of marquee in the `sidebar` of my blog.

3. Admin interface of the marquee.


== Changelog ==

= 2.76 =
* nothing really important, just the update of my blog url ;)

= 2.75 =
* favor to the use of `define('WP_DEBUG', true);` little menage in the code :)

= 2.7 =
* New option : `$level_for_admin`, select in a combo the user level required for the management of the Marquee, only administrators will be able to see and set this parameter ;),
* the textarea, in the admin interface of the marquee, is a little less high and its width is now set to 100%.

= 2.6 =
* Little correction in the variable `$canstop` (there was a double space),
* replacement of carriage return by space to avoid a javascript error,
* if you don't define precisely the unit in the field `width`, then : if width <= 100 the unit will be `%` otherwise it will be `px`.

= 2.5 =
* (22 Nov 2009) - First Release.
