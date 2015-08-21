# A visit to your site #

When you browse pages of your site requests are sent to the server. The following describes what happens inside WordPress.

## index.php ##

The request reaches index.php. Front to the WordPress application.

This file doesn't do anything, but loads `wp-blog-header.php`

[index.php](http://core.trac.wordpress.org/browser/tags/3.5/index.php)

## wp-blog-header.php ##

Three things happen here.

  1. wp-load.php sets the `ABSPATH` constant.
    * `wp-config.php` file loads with all configuration details. DB,Language,Debugging configs,etc.
    * `wp-settings.php` loads. At the end of `wp-settings.php` WP, all plugins, and the theme are fully loaded and instantiated.
  1. `wp()` sets up the WordPress query. This function live inside `functions.php`.
  1. `template-loader.php` Loads the correct template based on the visitor's url.

[wp-blog-header.php](http://core.trac.wordpress.org/browser/tags/3.5/wp-blog-header.php)

## wp-settings.php ##

Used to set up and fix common variables and include the WordPress procedural and class library. Seven Major WordPress
actions are fired in this file.

  * muplugins\_loaded [Line 171](http://core.trac.wordpress.org/browser/tags/3.5/wp-settings.php#L171)
  * plugins\_loaded [Line 209](http://core.trac.wordpress.org/browser/tags/3.5/wp-settings.php#L209)
  * sanitize\_comment\_cookies [Line 217](http://core.trac.wordpress.org/browser/tags/3.5/wp-settings.php#L217)
  * setup\_theme [Line 262](http://core.trac.wordpress.org/browser/tags/3.5/wp-settings.php#L262)
  * after\_setup\_theme [Line 294](http://core.trac.wordpress.org/browser/tags/3.5/wp-settings.php#L294)
  * init [Line 306](http://core.trac.wordpress.org/browser/tags/3.5/wp-settings.php#L306)
  * wp\_loaded [Last Line](http://core.trac.wordpress.org/browser/tags/3.5/wp-settings.php#L327)

## Who is the current user? ##

This is an important question for `Prevent Multiple Logins` Plugin. For each visit plugin needs to know who is the
current user. WordPress starts to find the answer to **Who is the current user?** in the `wp-settings.php` file.
### Step 1 ###
```
// Set up current user.
$wp->init();
```
See [Line 297](http://core.trac.wordpress.org/browser/tags/3.5/wp-settings.php#L297)

$wp is an instance of WP Class. [class-wp.php](http://core.trac.wordpress.org/browser/tags/3.5/wp-includes/class-wp.php)

### Step 2 ###

The following is the `init()` method of `WP` class.
```
/**
 * Set up the current user.
 *
 * @since 2.0.0
 */
function init() {
        wp_get_current_user();
}
```
See [function init()](http://core.trac.wordpress.org/browser/tags/3.5/wp-includes/class-wp.php#L455)


### Step 3 ###
`wp_get_current_user()` is a pluggable <sup>1</sup> function. It lives in [pluggable.php](http://core.trac.wordpress.org/browser/tags/3.5/wp-includes/pluggable.php). The following is the `wp_get_current_user()`
```
if ( !function_exists('wp_get_current_user') ) :
/**
 * Retrieve the current user object.
 *
 * @since 2.0.3
 *
 * @return WP_User Current user WP_User object
 */
function wp_get_current_user() {
	global $current_user;

	get_currentuserinfo();

	return $current_user;
}
endif; 
```
See [wp\_get\_current\_user()](http://core.trac.wordpress.org/browser/tags/3.5/wp-includes/pluggable.php#L51)

![http://prevent-multiple-logins.googlecode.com/svn/assets/wiki/wpauth-1.png](http://prevent-multiple-logins.googlecode.com/svn/assets/wiki/wpauth-1.png)

# Notes #

<sup>1</sup> These functions can be replaced via plugins. If plugins do not redefine these functions, then these will be used
instead.

At the time of this writing the highest tag is 3.5. Links are pointing to Trac tag 3.5.