# Login Form Processor #

`wp-login.php` is the WordPress login form presenter. See [wp-login.php](http://core.trac.wordpress.org/browser/tags/3.5/wp-login.php)

![http://prevent-multiple-logins.googlecode.com/svn/assets/wiki/wpauth-3.png](http://prevent-multiple-logins.googlecode.com/svn/assets/wiki/wpauth-3.png)

# wp-login.php #

It loads WordPress at line 12.
```
/** Make sure that the WordPress bootstrap has run before continuing. */
require( dirname(__FILE__) . '/wp-load.php' );
```

WordPress handles several cases in this file. Switching starts at [Line 389](http://core.trac.wordpress.org/browser/tags/3.5/wp-login.php#L389)

`login` and `default` cases starts at [Line 572](http://core.trac.wordpress.org/browser/tags/3.5/wp-login.php#L572)

`$user` is obtained at [Line 608](http://core.trac.wordpress.org/browser/tags/3.5/wp-login.php#L608) using `wp_signon()`

# user.php #

`wp_signon()` is the first function in `user.php`. See [user.php](http://core.trac.wordpress.org/browser/tags/3.5/wp-includes/user.php)

This function obtains $user from another function.

```
$user = wp_authenticate($credentials['user_login'], $credentials['user_password']);
```

See this line in `user.php` [Line 53](http://core.trac.wordpress.org/browser/tags/3.5/wp-includes/user.php#L53)

# pluggable.php #

```
function wp_authenticate($username, $password) {
	$username = sanitize_user($username);
	$password = trim($password);

	$user = apply_filters('authenticate', null, $username, $password);

	if ( $user == null ) {
		// TODO what should the error message be? (Or would these even happen?)
		// Only needed if all authentication handlers fail to return anything.
		$user = new WP_Error('authentication_failed', __('<strong>ERROR</strong>: Invalid username or incorrect password.'));
	}

	$ignore_codes = array('empty_username', 'empty_password');

	if (is_wp_error($user) && !in_array($user->get_error_code(), $ignore_codes) ) {
		do_action('wp_login_failed', $username);
	}

	return $user;
}
```

See [Line 470](http://core.trac.wordpress.org/browser/tags/3.5/wp-includes/pluggable.php#L470) on pluggable.php.

Note the `authenticate` filter.
```
$user = apply_filters('authenticate', null, $username, $password);
```
'Prevent Multiple Logins' plugin hook into this filter. uwpml\_authenticate() is hooked into `authenticate` filter.

# uwpml\_authenticate() #

The following are set or updated inside this function.

  * UWPML Cookie
  * UWPML Transient

Prevent Multiple Logins plugin uses the above information to prevent multiple logins.
