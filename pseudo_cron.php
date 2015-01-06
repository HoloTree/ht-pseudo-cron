<?php
/**
 * Sets up a "pseduo-cron" system to run functions or actions when a request is made to the endpoint. Works well with an external cron service.
 *
 * @package   holotree\pseudo_cron
 * @author    Josh Pollock <Josh@JoshPress.net>
 * @license   GPL-2.0+
 * @link      
 * @copyright 2014 Josh Pollock
 */

namespace holotree\pseudo_cron;


use holotree\pseudo_cron\helper\set_params;

/**
 * Class pseudo_cron
 *
 * @package holotree\pseudo_cron
 */
class pseudo_cron {

	/**
	 * Params for running pseudo-cron
	 *
	 * @var object
	 */
	public $params;

	/**
	 * Constructor for class
	 */
	public function __construct( ) {
		add_action( 'init', array( $this, 'add_endpoints' ) );
		add_action( 'template_redirect', array( $this, 'do_pseudo_cron' ) );

		$params_class = new set_params();
		$this->params = $params_class->the_params();


	}

	/**
	 * Add the endpoint and tag
	 *
	 * @uses 'init' action
	 */
	public function add_endpoints() {
		$endpoint = $this->params->endpoint;
		add_rewrite_rule( "{$endpoint}/^[a-z0-9_\-]+$/?", 'index.php?action=$matches[1]', 'top' );
		$key = $this->the_key_name();
		add_rewrite_tag( "%{$key}%", '^[a-z0-9_\-]+$' );

	}

	/**
	 * Determines if the pseudo-cron system can run, and if so does so via $this->act();
	 *
	 * @uses 'template_redirect' action
	 */
	public function do_pseudo_cron() {
		global $wp_query;

		$key = $wp_query->get( $this->the_key_name() );

		if ( $key && $this->params->secret_key === $key ) {
			$this->act();
		}

	}

	/**
	 * Runs the actual system.
	 *
	 * @access protected
	 *
	 * If callbacks and actions can't do all the things you need done here, add additional things by extending this class before this method runs and replacing this method there.
	 */
	protected function act() {

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

	/**
	 * Get the name of the "key" IE the get var to check for the (not so secret) key
	 *
	 * @access private
	 *
	 * @return string
	 */
	private function the_key_name() {
		return $this->params->secret_key_name;

	}

}
