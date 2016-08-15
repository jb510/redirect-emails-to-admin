<?php

if ( ! class_exists( 'S9_Redirect_Email_to_Admin', false ) ) {
	/**
	 * This class handles redirecting emaiils when on the WP Engine Staging site.
	 *
	 * It is usually undesirable for the staging site to send out emails to anyone besides the site
	 * administrator. This class hooks into the wp_mail() function in WordPress
	 *
	 * @package RedirectEmailtoAdmim
	 * @author Jon Brown
	 * @license GPL-2.0+
	 * @since 1.0
	 */
	class S9_Redirect_Email_to_Admin extends S9_REA_Singleton {

		/**
		 * Constructor.
		 *
		 * Hook the methods of this class to the appropriate hooks in WordPress
		 *
		 * @since 1.1
		 */
		protected function __construct() {

			// We're using a high priority to give other plugins room to also modify this filter.
			add_filter( 'wp_mail', array( $this, 'maybe_redirect_mail' ), 9999, 1 );
			add_action( 'admin_notices', array( $this, 'do_admin_notice' ) );

		}

		/**
		 * Should email be redirected.
		 *
		 * We only apply this filter if we're on the staging site, where we generally don't want email
		 * accidentally being sent out to end users.
		 *
		 * @since 1.0
		 *
		 * @uses is_wpe_snapshot() Checks to determine if this is a WP Engine Staging site.
		 * @uses REA_DO_REDIRECT Define to force redirect on/off
		 *
		 * @return boolean
		 *
		 * @todo maybe add filter
		 */
		private function is_redirect_forced() {

			if ( defined( 'REA_DO_REDIRECT' ) ) {
				if ( true === REA_DO_REDIRECT ) {
					$do_redirect = true;
				} elseif ( false === REA_DO_REDIRECT ) {
					$do_redirect = false;
				}
				// WPE is only checked if the constant is not set.
			} elseif ( $this->check_for_dev() ) {
				$do_redirect = true;
			}

			/**
		 * Filter to override redirect boolean
		 * yes this means you can set REA_DO_REDIRECT to true then force it to
		 *
		 * @param boolean
		 */
			return apply_filters( 'rea_do_redirect', $do_redirect );

		}

		/**
		 * Check for devlopment install (WPE Staging, .dev, .local, .test)
		 *
		 * @since 1.0
		 *
		 * @uses is_wpe_snapshot() Checks to determine if this is a WP Engine Staging site.
		 *
		 * @param array $mail_args Array of settings for sending the message.
		 * @return array The args to use for the mail message
		 */
		function check_for_dev() {
			$is_dev = false;

			if ( function_exists( 'is_wpe_snapshot' ) && is_wpe_snapshot() ) {
				$is_dev = true;
			} else {
				$url_parts = parse_url( htmlspecialchars( "//$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]", ENT_QUOTES, 'UTF-8' ) );
				$host_parts = explode( '.', $url_parts['host'] );
				$host_parts = array_reverse( $host_parts );
				$dev_endings = array( 'dev', 'test', 'local' );
				if ( in_array( $host_parts[0], $dev_endings, true ) ) {
					$is_dev = true;
				}
			}
				return $is_dev;
		}

		/**
		 * Get the admin email we want to send everything to
		 *
		 * @since 1.0
		 *
		 * @todo add translation files and load textdomain
		 */
		public function get_admin_email() {
			/**
			 * Filter to override admin email
			 * @param string email
			 */
			$site_admin = get_site_option( 'admin_email' );
			if ( defined( 'REA_ADMIN_EMAIL' ) ) {
				$admin_email = REA_ADMIN_EMAIL;
			} else {
				$admin_email = $site_admin;
			}

				$admin_email = apply_filters( 'rea_admin_email', $admin_email );
				return $admin_email;
		}

		/**
		 * Possibly filter all mail. Doesn't affect email already going to the site admin.
		 *
		 * @since 1.0
		 *
		 * @uses is_wpe_snapshot() Checks to determine if this is a WP Engine Staging site.
		 *
		 * @param array $mail_args Array of settings for sending the message.
		 * @return array The args to use for the mail message
		 */
		public function do_mail_redirect( $mail_args ) {

			if ( true === $this->is_redirect_forced() ) {

				$site_admin = get_site_option( 'admin_email' );
				$admin_email = $this->get_admin_email();

				// Only redirect email that is NOT going to the current site admin
				// Note: this isn't comparing with the value passed into the rea_admin_email filter
				if ( $$site_admin !== $mail_args['to'] ) {
					$mail_args['message'] = 'Originally to: ' . $mail_args['to'] . "\n\n" . $mail_args['message'];
					$mail_args['subject'] = 'REDIRECTED MAIL | ' . $mail_args['subject'];
					$mail_args['to'] = $admin_email;
					
				}
			}
			return $mail_args;
		}

		/**
		 * Display a notice if the plugin is  to ensure the plugin is able to run
		 *
		 * We're specifically looking to make sure there is a PHP version of 5.3.2 or greater
		 *
		 * @since 1.0
		 *
		 * @todo add translation files and load textdomain
		 * @todo DRY $site_admin and $admin_email here and in do_mail_redirect()
		 */
		public function do_admin_notice() {
			if ( true === $this->is_redirect_forced() ) {

				$site_admin = get_site_option( 'admin_email' );
				$admin_email = $this->get_admin_email();

				$class = 'notice notice-warning';
				$message = __( 'Redirect All Emails to Admin is active and sending all emails to ', 'rea' );
				printf( '<div class="%1$s"><p>%2$s%3$s</p></div>', $class, $message, $admin_email );
			}
		}
	} // end of S9_Redirect_Email_to_Admin class
} // end of if ( class_exists() ) statement
