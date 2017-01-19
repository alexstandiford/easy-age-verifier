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

## Form Types ##
Easy Age Verifier comes with 2 different ways to verify your visitor is of age. The form that you wish to use can be customized on the Easy Age Verifier settings page.

1. Enter Age Form (default)
  * This form requires that the visitor enters their date of birth
2. Confirm Age Form
  * This form does not require that the visitor enter their date of birth. Instead, they simply click on one of two buttons, that says they're either of age, or they're not of age.

## Filters ##

Easy Age Verifier has a few filters that allow theme developers to customize defaults for this plugin. Most of these can be overridden by the administrator under settings>>>Easy Age Verifier


* **eav_custom_modal_logic**
  * Allows developers to create custom functions to override when Easy Age Verifier attempts to verify a visitor's age
  * Default: `Array('default',true);`
	* More information on how to use this [here](#custom-logic-filter).
* **eav_default_age**
  * Sets the default age to check against
  * Default: 21
* **eav_default_underage_message**
  * Sets the default message to display if the website visitor is underage.
  * Default: Sorry! You must be $age to visit this website.
* **eav_default_form_title**
  * Sets the default content to display above the form.
  * Default: Verify Your Age to Continue
* **eav_under_age_value**
  * Sets the default content to display inside of the button that says the visitor is underage.
    * This filter only applies to the confirm age form, not the enter age form.
  * Default: I am under $age
* **eav_over_age_value**
  * Sets the default content to display inside of the button that says the visitor is of age.
    * This filter only applies to the confirm age form, not the enter age form.
  * Default: I am $age or older.
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
    
## Custom Verifier Template ##

To override the default verifier template, follow these steps:

1. Locate the default template file located at `{plugin-directory}/assets/templates/default.php`
1. In your current theme directory, create an `assets` folder, and put a folder called `templates` inside `assets`. You should have an identical folder structure to the plugin.
1. Copy `default.php` into your newly created `assets` directory
1. Edit the file as you see fit
    
## Custom Logic Filter ##

The `eav_custom_modal_logic` filter can be used to specify custom conditions that prevents the modal from popping up. The filter also allows you to specify a custom action when that specific conditional returns false.

The filter is an associative array, which contains **conditional IDs** as the keys, and booleans (true, or false) as the values. if _any_ of the values in this array return `false`, the modal will not pop-up, and an action will trigger for each boolean that returns false.

By default, the array looks like this:

```PHP
$checks = array('default',true);
```

**Important! Do not modify the `default` item in this array. Removing it can cause unexpected behavior!**

### Adding Custom Conditionals ###

To add custom logic to your site, you need to add a **conditional ID** and a **boolean** to this array. Let's say, for example, you wanted to only display your age verifier on the home page. You would add something like this to your `functions.php` file (or plugin file, if you're fancy like that).

```PHP
//The function that adds the check. Be sure to pass the $checks variable

function home_page_disable_verifier($checks){
	//Check if the current page is the home page
	if(is_home()){
		//If it is, add the check ID key, and set it to true.
		$checks["is_home_page"] = true; // is_home_page can be whatever you want it to be. Make sure it's unique!
	}
	else{
		//If not, add the check ID key, and set it to false.
		$checks["is_home_page"] = false;
	}
	//Returns the array with the newly added boolean
	return $checks;
}
add_filter('eav_custom_modal_logic','home_page_disable_verifier');
```
### Doing Actions If Those Conditionals Return false ###

When a custom conditional returns `false`, an [action hook](https://developer.wordpress.org/reference/functions/add_action/) tied to that conditional runs. This allows you to do a specified action, based on which conditional returns false.

The action to tie your function is equal to whatever your check ID is inside of the array, with `_custom_is_false` added to the end. In the case of the example above, that action would be `is_home_page_custom_is_false`.

Let's say you wanted to display a warning instead of the age verifier when the home page is loaded. To do this, you would add the filter shown above, and then after that add something like this:

```PHP
//The function that adds the content if you're on the home page.

function load_home_page_warning(){
	echo "Warning! Content on this site is not suitable for visitors under the age of 18.";
}
//Ties the action to when the home page returns false.
add_action('is_home_page_custom_is_false','load_home_page_warning');
```