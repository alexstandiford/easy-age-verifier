=== Easy Age Verifier ===
Contributors: tstandiford
Donate link: http://www.easybeerlister.com/recommends/donate
Tags: beer, beers, brewery, untappd, age verification, bar, bars, restaurant, brewer, craft beer, craft bar
Requires at least: 3.0.1
Tested up to: 4.5
Stable tag: 1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Easy Age Verifier makes it easy for taprooms and bars to confirm their website visitors are of legal age.

== Description ==

Easy Age Verifier makes it easy for taprooms and bars to confirm their website visitors are of legal age. The plugin was designed to work out of the box, but can be easily customized in the settings page, as well as a series of hooks and filters.

__Features__

* Ask users to verify their age on page load. If the user is less than the age you specify (default is 21), then they will be removed from the website
* Customize all items on the form, including the question asked, the message stated when they're underage, and the class each form item has.
* Remembers if a visitor has verified their age in the past, and won't ask again until they close their web browser.


== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/plugin-name` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. (Optional) Configure the form, including minimum age, and all messaging for the form on settings>>>Easy Age Verifier

== Frequently Asked Questions ==

= My Form Isn't Showing Up! =

The form will not display if you are logged in, or if you have confirmed your age in the last 24 hours. To force the form to display, open your website in incognito mode (Chrome) or in a new private window (Firefox). That will get around it.

== Screenshots ==

1. Form pops up to confirm age on page load
2. Popup details why the user cannot access the website after the form is filled-in
3. Customize all aspects of the form easily

== Changelog ==

= 1.00 =
* Initial Launch.  Hooray!

= 1.10 =
* Added a fix that caused IE 11 to not load properly
* Added an option to change the button value in the settings page
* Added several hooks to the form
* The form will no longer display for logged-in users.