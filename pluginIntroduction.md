Notice: [bapml](http://bapml.com) site contains an introductory article about this project.

# Prevent Multiple Logins #

![http://prevent-multiple-logins.googlecode.com/svn/assets/banner.png](http://prevent-multiple-logins.googlecode.com/svn/assets/banner.png)

Prevents login to a user account multiple times.

This project is a plugin for WordPress.

  * Read more about [WordPress](http://wordpress.org/) <sup>1</sup>
  * Read more about [WordPress plugins](http://codex.wordpress.org/Plugins) <sup>2</sup>

<sup>1</sup> _WordPress is web software you can use to create a beautiful website or blog_

<sup>2</sup> _Plugins are tools to extend the functionality of WordPress_

# Default WordPress Login #

Login credentials are validated and auth cookie is generated upon login. Each visit to the site by a logged in user is identified by checking the auth cookie formed upon login.

HTTP is stateless. Session is not generated connecting WordPress and logged in user. WordPress has no idea of who is currently logged in by default.

If another person enters the same credentials as a logged in user, the latter one will also given a auth cookie. This default WordPress behavior makes it possible to have two logged in users with same login credentials to the same user account.

# WordPress Plugin to Prevent Multiple Logins #

Can a WordPress plugin alter the default WordPress login? Yes, a plugin can hook into various actions and filters available during the login process and prevent multiple logins. This plugin will display a error message in the login form with error details upon attempting to login with the same credentials that of an already logged in user.

# Read More #

[Plugin Details](pluginDetails.md)