=== Easy Age Verifier ===
Contributors: alexstandiford
Donate link: http://paypal.me/alexstandiford
Tags: beer, brewery, age verification, bar, restaurant, brewer, craft beer, craft bar, weed, marijuana, cannabis
Requires at least: 3.0.1
Tested up to: 4.9.5
Stable tag: 2.1.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Easy Age Verifier makes it easy for taprooms and bars to confirm their website visitors are of legal age. The plugin was designed to work out of the box, but can be easily customized in the settings page, as well as a series of hooks and filters.

== Description ==

A quick overview:
https://vimeo.com/202638210
Easy Age Verifier makes it easy for taprooms and bars to confirm their website visitors are of legal age. The plugin was designed to work out of the box, but can be easily customized in the settings page, as well as a series of hooks and filters.

__Features__

* Ask users to verify their age on page load. If the user is less than the age you specify (default is 21), then they will be removed from the website
* Easy customization directly in the WordPress Customizer
* SEO Friendly (Google can still crawl the website, even with the verifier installed)
* Ask users to verify that they're above age by clicking a button, instead of forcing the user to enter their age.
* Customize all items on the form, including the question asked, the message stated when they're underage, and the class each form item has.
* Remembers if a visitor has verified their age in the past, and won't ask again until they close their web browser.


== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/plugin-name` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. (Optional) Configure the form, including minimum age, and all messaging for the form on settings>>>Easy Age Verifier

== Frequently Asked Questions ==

= My Form Isn't Showing Up! =

The form will not display if you are logged in, or if you have confirmed your age in the last 24 hours. This option can be configured in the settings, but you can also force the form to display by opening your website in incognito mode (Chrome) or in a new private window (Firefox). That will get around it.

= I'm in incognito mode and my form is still not showing up! =

This generally is caused by one of these things:

1. Your theme's CSS has somehow prevented the verifier from displaying properly.
2. You're logged in
3. You verified your age in your current session
4. Some kind of custom verifier logic is interfering with the verifier
5. Something prevented the javascript from loading properly
6. Debug Mode is enabled

To troubleshoot, follow these steps:

1. Open your WordPress dashboard, and click Age Verifier>>Debug Verifier. Confirm debug mode is disabled.
2. Close all incognito windows, and open a new incognito window. This will force any active cookies to clear and might fix the issue right away.
3. If the page loads, and you still see no verifier, right-click on your webpage and click "inspect". If you see `<div id="taseav-age-verify" class="taseav-age-verify">` just below the `<body>` tag, then the verifier has loaded properly. If you don't see this, then something stopped the verifier from loading.
4. If you see `<div id="taseav-age-verify" class="taseav-age-verify">`, Take a look at the styles that are applying to the verificaiton form. It's possible something is overriding the CSS in a detrimental way.
5. If you see `<div id="taseav-age-verify" class="taseav-age-verify">`, Check the javascript console for any errors. It's possible that something went wrong with the script.
6. If you don't see `<div id="taseav-age-verify" class="taseav-age-verify">`, Make sure you're not logged in, no custom logic is running, and you're running a new session.

If all else fails, you can always submit a support request. I'll help you out.

= I don't want my form to show up on a specific page =

Check out the readme for details on how to add custom conditionals to your verifier. https://github.com/alexstandiford/easy-age-verifier#custom-logic-filter

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

= 1.20 =
* Implemented an optional form type that allows users to specify that they are of age instead of forcing them to enter their date of birth.

= 1.21 =
* Fixed a bug that caused the verification to occasionally pop up when it shouldn't. This was especially true for sites that use caching plugins.
* Tweaked the CSS for the under/over pop up.

= 1.30 =
* Overhauled the options page for better read-ability
* Tested plugin on newest version of WordPress
* Further improved issues where verification would pop up when it shouldn't.
* Added translation functions to plugin
* Implemented external extendability on options page.
* Implemented a system that allows developers to add custom conditionals to override the default age verification behavior

= 2.00 =
* Overhauled the plugin from the ground up. The code is much cleaner, and more organized
* Moved the settings page in its entirety to the WordPress customizer. This makes tweaking the form a lot nicer.
* Improved overall plugin extend-ability

= 2.03 =
* Improved the speed and stability of the verifier by reducing server calls (yay!).
* Improved readme documentation
* Implemented a debug logger, which generates some debug info to make supporting the plugin easier in the future.

= 2.05 =
* Added a fix that prevented the Age Verifier form from displaying properly in some caching situations
* Implemented support for custom form actions on verification failure and success
* Added a body class based on the verification status. This allows developers to change how the site looks when the verifier is popped up.
* Fixed a bug that caused the verifier to not work on a fresh install
* Removed all WP_DEBUG warnings (sorry about that, fellow devs!)

= 2.10 =
* Added a debug mode, which disables the verification form, but enqueues the script so the verifier can be activated with a console command.

= 2.1.1 =
* Added better debug information, including current browser
* Improved the verifier cookie regex to match Moz standards, thus vastly improving cross-browser support
* Improved script and style version tagging to prevent future caching issues

== Upgrade Notice ==

= 2.1.1 =
* Fixed issue that caused verifier to not display on some browsers