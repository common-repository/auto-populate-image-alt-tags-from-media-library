=== Auto-populate Image ALT Tags from Media Library ===

Tags: image, img, title, alt, attribute, seo, media library
Requires at least: 4.7.5
Tested up to: 4.9.4
Stable tag: trunk
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Automatically populates Alt and Title tags for images using the values set in the Media Library.

== Description ==


Automatically populates Alt and Title tags for images featured in posts and pages, pulling directly from the values set in the Media Library. This makes it easy to manage your image metadata centrally in one place (the Media Library). Plugin also supports the ability to override the Alt/Title tag Media Library values by placing an Alt/Title tag directly on the attachment in the WordPress WYSIWYG Editor.

Tag population only occurs when viewing the post or page, and does not occur in the WYSIWYG editor.


== Installation ==

This section describes how to install the plugin and get it working.
 
1. Upload the `img-alt-media-library` directory to the plugins directory of your theme (usually `/wp-content/plugins/`).
2. Activate the plugin through the 'Plugins' menu in WordPress.

That's all, you're good to go! Upon viewing your posts and pages, you should now see image Alt and Title tags being pulled automatically from the Media Library.


== Screenshots ==
 
1. [https://taylor.callsen.me/wp-content/uploads/2018/02/set-title-alt-tags-media-li.jpg  Set the Image Alt and Title tags in the Media Library]
2. [https://taylor.callsen.me/wp-content/uploads/2018/02/see-img-alt-title-when-view.jpg  The Alt and Title tags are automatically inserted into the Image HTML when viewing a page or post]

== Frequently Asked Questions ==
 
= How does it work? =
 
This plugin hooks into the the_content filter on page and post render. Essentially right before post_content (in the form of raw HTML) is set to the browser, this plugin will scan the HTML for <img /> tags using a regex matcher. Regex was used instead of parsing the HTML into a Document Object to maximize compatibility  (parsing the HTML into a Document Object, and then back to an HTML string would likely modify the HTML syntax, drop attributes, etc. which could break other components and post functionality).

Once images are identified in the HTML, the image src URL is used to lookup the attachment ID corresponding to the image. Unfortunately I was not able to find a cleaner way of performing this lookup, as the image ID is not always output onto the page when an attachment is included.

Once the image ID is determined, the plugin retrieves the image Alt and Title values for that attachment using the get_post_meta() and get_the_title() WordPress functions. Once these values are returned, they are added to the image unless the image already contains an alt or title tag, in which case this part is skipped.

The modified image HTML is placed back into the post_content HTML string, and returned to the browser.

== Changelog ==

= 1.1 =
* Updated "Tested up to" tag on readme 

= 1.0 =
* Initial release of functional code and readme