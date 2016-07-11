# Easy Age Verifier #

Easy Age Verifier makes it easy for taprooms and bars to confirm their website visitors are of legal age. The plugin was designed to work out of the box, but can be easily customized in the settings page, as well as a series of hooks and filters.

https://github.com/tstandiford/easy-age-verifier

## Features ##
* Ask users to verify their age on page load. If the user is less than the age you specify (default is 21), then they will be removed from the website
* Customize all items on the form, including the question asked, the message stated when they're underage, and the class each form item has.
* Remembers if a visitor has verified their age in the past, and won't ask again until they close their web browser.

## Installation ##

1. Upload the plugin files to the `/wp-content/plugins/plugin-name` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. (Optional) Configure the form, including minimum age, and all messaging for the form on settings>>>Easy Age Verifier

## Filters ##

Easy Age Verifier has a few filters that allow theme developers to customize defaults for this plugin. Most of these can be overridden by the administrator under settings>>>Easy Age Verifier

* **eav_default_age**
  * Sets the default age to check against
  * Default: 21
* **eav_default_underage_message**
  * Sets the default message to display if the website visitor is underage.
  * Default: Sorry! You must be $age to visit this website.
* **eav_default_form_title**
  * Sets the default content to display above the form.
  * Default: Verify Your Age to Continue
* **eav_wrapper_class**
  * Sets the default wrapper class.
  * **IMPORTANT:** If you override this class and do not update the CSS to display properly, this will prevent your form from displaying properly. This is intended for advanced users only.
  * Default: taseav-age-verify
* **eav_form_class**
  * Sets the default form class.
  * Default: taseav-form-class
* **eav_before_form**
  * Adds html before the form
  * Default: null
* **eav_after_form**
  * Adds html after the form
  * Default: null
* **eav_month_class**
  * Replaces the default month class
  * Default: taseav-month
* **eav_day_class**
  * Replaces the default day class
  * Default: taseav-day
* **eav_year_class**
  * Replaces the default year class
  * Default: taseav-year
* **eav_min_year**
  * Replaces the default minimum year value
  * Default: 1900
* **eav_before_year**
  * Adds html before the year div
  * Default: null
* **eav_before_day**
  * Adds html before the day div
  * Default: null
* **eav_before_month**
  * Adds html before the month div
  * Default: null
* **eav_before_button**
  * Adds html before the submit button
  * Default: null
* **eav_button_value**
  * Replaces the default button text
  * Default: Submit
    