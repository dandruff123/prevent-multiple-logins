# wp\_get\_current\_user() #

Previous Page: [A visit to your site](WordPressAuthentication.md)
```
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

`wp_get_current_user()` sets up the global `$current_user` using `get_currentuserinfo()`.
See [Line 51](http://core.trac.wordpress.org/browser/tags/3.5/wp-includes/pluggable.php#L51) on `pluggable.php`.

# get\_currentuserinfo() #

Populate global variables with information about the currently logged in user.

Will set the current user, if the current user is not set.

The current user will be set to the logged in person.

If no user is logged in, then it will set the current user to 0, which is invalid and won't have any permissions.

This funtion tries to validate auth cookie at [line 100](http://core.trac.wordpress.org/browser/tags/3.5/wp-includes/pluggable.php#L100) by calling `wp_validate_auth_cookie()`.

See [Line 74](http://core.trac.wordpress.org/browser/tags/3.5/wp-includes/pluggable.php#L74) on `pluggable.php`

# wp\_validate\_auth\_cookie() #

Who is the current user? This function will answer the question based on the auth coockie.

If the auth cookie is valid `auth_cookie_valid` action is done.

`do_action('auth_cookie_valid', $cookie_elements, $user)`

See the [action hook](http://core.trac.wordpress.org/browser/tags/3.5/wp-includes/pluggable.php#L559)

You are given **$cookie\_elements** and **$user**

'Prevent Multiple Logins' plugin hook into this action. `uwpml_auth_cookie_valid()` is hooked into `auth_cookie_valid` action.

`add_action('auth_cookie_valid', 'uwpml_auth_cookie_valid');`

# uwpml\_auth\_cookie\_valid() #

The following are set or updated inside this function.

  * UWPML Cookie
  * UWPML Transient

Prevent Multiple Logins plugin uses the above information to prevent multiple logins.

![http://prevent-multiple-logins.googlecode.com/svn/assets/wiki/wpauth-2.png](http://prevent-multiple-logins.googlecode.com/svn/assets/wiki/wpauth-2.png)

