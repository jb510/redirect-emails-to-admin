<?php

if ( ! class_exists( 'S9_REA_Singleton', false ) ) {
	/**
	 * An abstract class the sets up the "singleton" mode for other classes
	 *
	 * With classes in WordPress, it is often desirable to instantiate the class only once, to prevent
	 * collisions when hooking into WordPress actions and filters.
	 *
	 * @package RedirectEmailtoAdmim
	 * @author Jon Brown
	 * @since 1.0
	 */
	abstract class S9_REA_Singleton {

		/**
		 * The Singleton instance of this class
		 *
		 * @var object
		 */
		protected static $instance = null;

		/**
		 * Retrieve the single instance of the called class, to allow working with the class object.
		 *
		 * @return mixed The Singleton instance of the called class
		 */
		final public static function get_instance() {
			if ( null === static::$instance ) {
				static::$instance = new static;
			}
			return static::$instance;
		}

		/**
		 * Must be redefined in a child class to do anything
		 */
		abstract protected function __construct();
	} // end of S9_REA_Singleton class
} // end of if ( class_exists() ) statement
