=== Plugin Name ===
Contributors: AdamDionne
Donate link: 
Tags: itunes, iphone, ipad, mac, appstore, lookup, api
Requires at least: 4.0
Tested up to: 4.7.2
Stable tag: 1.5.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds shortcodes that display data from iOS and Mac AppStore applications.

== Description ==

The AppStore Lookup is a simple WordPress plugin that provides shortcodes for querying Apple’s Lookup API to get app data from iTunes or the Mac AppStore.  You can modify it as you wish, or use the built-in options to make it fit the needs of your site.

Features:

* Lookup and display data from Mac App Store and iPhone/iPad App Store
* Display App icons
* Display App screenshots
* Up-to-date app ratings information
* Add Smart App Banners for iOS mobile Safari
* Use your Linkshare ID for download links and Smart App Banners

[Support](http://adamdionne.com)

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload the plugin folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Start using the shortcodes anywhere in a post or page.  Detailed shortcode info can be found on the Plugin Settings page.  For more information on how to use short codes in your posts or templates, check out the [WordPress Codex](http://codex.wordpress.org/Shortcode).

== Frequently Asked Questions ==

= Where does the data come from? =
The plugin uses the Apple Lookup API, information about which is found here: http://www.apple.com/itunes/affiliates/resources/documentation/itunes-store-web-service-search-api.html#lookup

= I found a bug or I have an idea for a feature that would be great. =

Get in touch via twitter [@AdamDionne](http://www.twitter.com/AdamDionne) or [my website](http://www.adamdionne.com) and I can help. Probably.

== Screenshots ==

1. Options Screen
2. Shortcodes list from Settings Menu

== Changelog ==

= 1.5.1 =
* Fixes to documentation. Updated plugin to reflect that this has been tested on latest Wordpress.

= 1.5 =
* At long last, removed Timthumb. This should not be used by anyone anymore due to some dangerous exploits.

= 1.4.1 =
* Fixes a bug where the default icon size was being determined from the setting for screenshot width.

= 1.4 =
* There is now a default icon that will be shown when the app id is not found in the store (or when one is not passed by you to the plugin).  Screenshots use placeholder kittens so that the package does not have a ton of included art, but I can update that later if that’s a thing people want.
* Beginning of localization/internationalization support, but since I’ve never done this, I’m not confident it’s ready quite yet.

= 1.3.2 =
* Now with silent error handling.  When an appId is not found, it will continue loading the page without the appropriate content instead of using wp_die.

= 1.3.1 =
* Fixed bug with cache folder creation that would throw errors up all over the place

= 1.3 =
* Added local caching of JSON API data.
* Added feature to choose duration of JSON cache.
* Added ability to select default store for lookup data.
* Added optional link parameter to asl_seller to allow user to link to seller's website.
* App Smart Banner support chooses not to show iPad apps to iPhone/iPod users, because that's just mean.
* Fixed a bug where choosing not to link the SellerName displayed the URL and not the name.  Which was in there because I'm stupid.

= 1.2 =
Now that we can establish if posts/pages are about a single app using custom field appId, we will automatically add a smart banner to that post for Safari mobile.  If Linkshare is present in options, it adds that.  No short code required.

= 1.1 =
For posts about a single app, the user can now set a post attribute called appId with the value of the AppStore ID.  This will eliminate the need to place the id parameter in all short codes, which sure is easier than copy+pasting it every time.

= 1.0 =
* Initial version

== Upgrade Notice ==

v1.5 includes a major security update in that it removes the ability to use TimThumb. This is recommended for all users.