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
	 * Params for running pseudo-cron.
	 *
	 * @var object
	 */
	public $params;

	/**
	 * The time to cache settings for.
	 *
	 * @var int
	 */
	protected  $transient_length = DAY_IN_SECONDS;

	protected $lockout_transient_name = 'ht_pseudo_cron_lockout';

	/**
	 * Constructor for class
	 */
	public function __construct( ) {
		add_action( 'init', array( $this, 'add_endpoints' ) );
		add_action( 'template_redirect', array( $this, 'do_pseudo_cron' ) );

		$this->set_params();

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
	 * Set $this->params from the transient that saves the config or using the helper classes.
	 */
	protected function set_params() {
		$key = implode( '_', array( __CLASS__, __FUNCTION__ ) );
		if ( false == ( $params = get_transient( $key ) ) ) {
			$params_class = new set_params();
			$this->params = $params = $params_class->the_params();
			set_transient( $key, $params, $this->transient_length );
		}else{
			$this->params = $params;
		}

	}


	/**
	 * Determines if the pseudo-cron system can run, and if so does so via $this->act();
	 *
	 * @uses 'template_redirect' action
	 */
	public function do_pseudo_cron() {
		global $wp_query;

		$key = $wp_query->get( $this->the_key_name() );

		if ( $key && ! $this->is_locked_out() && $this->params->secret_key === $key ) {
			$act = new act( $this->params );
			$act->run();
			set_transient( $this->lockout_transient_name, true, $this->params->lockout_time );

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


	/**
	 * Check if the lockout transient is set
	 *
	 * @access protected
	 *
	 * @return bool
	 */
	protected function is_locked_out() {
		return get_transient( $this->lockout_transient_name );

	}

}
