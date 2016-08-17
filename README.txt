=== Redirect All Emails to Admin ===
Contributors: Jon Brown
Tags: email, local development, staging, wpengine
Requires at least: 4.0
Tested up to: 4.6
Stable Tag: 0.9
License: GPL2

Redirect all outgoing emails to the administrator of the site from local and staging sites (with auto detection and overrides)

== Description ==

When using a local development install or staging site, for example WP Engine's staging [Staging site](http://support.wpengine.com/staging/) or your local development install, you may want to prevent the site from sending emails to your site users (membership renewals and order updates for example). This plugin redirects those all to specific address, by default the site admin.

This plugin will detect local installs ending in .dev, .local or .test (by url ending) and will detect WP Engine staging installs (using is_wpe_snapshot() ).  You can also use a global define or filter to force the behavior either on or off.

There are great server level tools for this like https://mailcatcher.me/ but often you may not have control over the server. There are options to disable all outgoing emails, but you many want to see what's actually being sent out.  This plugin redirects all emails to the site admin so you can see what's going on.

The plugin will display a permanent admin notice when it's redirecting emails (there is a filter to hide the notice if it is really annoying you).

This plugin is compatible with WordPress Multisite (see FAQ for details)

Note of caution: There are many plugins out there that do interesting things to WP_Mail(). It is possible that one of those will interfere with or bypass this plugin. If you find an instance of that, please report it in the support forums so I can take a look.

== Installation ==

Simple installation:

1. Upload the `redirect-emails-to-admin` folder to your `/wp-content/plugins/` directory
2. Activate the plugin through the "Plugins" menu in WordPress
3. If you're using it on WP Engine Staging or the domain you wish to redirect emails on ends in .dev or .local that's all you should need to do.  If you're using it elsewhere see the notes below.

Advanced instructions:

There are two ways override automatic detection:
A. You can add this line to your wp-config.php file:
`if ( ! defined( 'REA_DO_REDIRECT' ) ) define( 'REA_DO_REDIRECT', true );`
or
B. You can add this line to your theme files (functions.php):
`add_filter( 'rea_force_redirect', __return_true );`

Those two ways can also be used in reverse (if you wanted to leave the plugin active, but overide automatic detection and force email redirection off)
A. You can add this line to your wp-config.php file:
`if ( ! defined( 'REA_DO_REDIRECT' ) ) define( 'REA_DO_REDIRECT', false );
`
or
B. You can add this line to your theme files (functions.php):
`add_filter( 'rea_force_redirect', __return_false );`

== Frequently Asked Questions ==

= Can I leave the plugin active but force redirect on/off? =
Yes, please see the installation instructions

= Can I send emails to a different address? =
Yes, you can use the filter
`
add_filter( 'rea_admin_email', 'my_rea_email' );
function my_rea_email() {
	return 'me@email.com';
}
`
= Does it work on Multisite? =
Yes, but with one caveat. You can either activate this plugin on individual sites or network activate across the entire network, however it will only override emails sent by each individual site, not emails sent to the network admin (ex. new site creation).  For most this probably isn't an issue, but I welcome a pull request!

= Can I leave this active on my production site so that when I clone the site to staging, it's already active? =

Yes, that's kind of the whole point of it! You can even install it in mu-plugins.

== Screenshots ==

(there is really nothing to see on this one)

== Changelog ==

= 0.9 =
* Created the plugin based on Jeremy Pry's Redirect Emails on Staging
* Modified the plugin to work outside of WP Engine's staging enviroment and to be included in WP Local Toolbox https://github.com/joeguilmette/wp-local-toolbox
* 

== Upgrade Notice ==

