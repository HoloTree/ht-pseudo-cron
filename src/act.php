<?php
/**
 * Runs the actual actions and callbacks.
 *
 * @package holotree\pseudo_cron
 * @author    Josh Pollock <Josh@JoshPress.net>
 * @license   GPL-2.0+
 * @link      
 * @copyright 2014 Josh Pollock
 */

namespace holotree\pseudo_cron;

/**
 * Class act
 *
 * @package holotree\pseudo_cron
 */
class act {

	/**
	 * Params for running pseudo-cron.
	 *
	 * @var object
	 */
	public $params;

	/**
	 * Constructor for class
	 *
	 * @param object $params The parameters to use.
	 *
	 */
	public function __construct( $params ) {
		$this->params = $params;

	}

	/**
	 * Runs the actual system.
	 *
	 * @access protected
	 *
	 * If callbacks and actions can't do all the things you need done here, add additional things by extending this class before this method runs and replacing this method there.
	 */
	public function run() {

		$this->do_callbacks();

		$this->do_actions();

	}

	/**
	 * Do all actions stored in $this->params->actions
	 *
	 * @access protected
	 *
	 */
	protected function do_actions() {
		if ( is_array( $this->params->actions ) ) {
			foreach ( $this->params->actions as $action ) {
				do_action( $action );
			}

		}

	}

	/**
	 * Run all valid callbacks in $this->params->callbacks
	 *
	 * @access protected
	 *
	 */
	protected function do_callbacks() {
		if ( is_array( $this->params->callbacks ) ) {
			foreach ( $this->params->callbacks as $callback ) {
				switch ( $callback ) {
					case is_string( $callback ) :
						if ( function_exists( $callback ) ) {
							call_user_func( $callback );
						}

						break;

					case is_array( $callback ) :
						if ( $this->verify_method( $callback ) ) {
							call_user_func( array( $callback[0], $callback[1] ) );
						}

						break;

				} //endswitch

			} //endforeach

		}

	}

	/**
	 * Verify that a class method in $this->params->callbacks is valid.
	 *
	 * Runs whenever a key of $this->params->callbacks is an array. Will return true if keys 0 and 1 of that array are set, key 0 is a class that exists, and key 1 is a method in that class.
	 *
	 * @access protected
	 *
	 * @param array $callback Callback class/method
	 *
	 * @return bool True if valid, false if not
	 */
	protected function verify_method( $callback ) {
		if ( isset( $callback[0] ) && isset ( $callback[1] ) ) {
			if ( class_exists( $callback[0] ) && method_exists( $callback[0], $callback[1] ) ) {
				return true;

			}

		}

	}
}
