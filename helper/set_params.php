<?php
/**
 * Helper class to set $params in main class
 *
 * @package holotree\pseudo_cron\helper
 * @author    Josh Pollock <Josh@JoshPress.net>
 * @license   GPL-2.0+
 * @link      
 * @copyright 2014 Josh Pollock
 */

namespace holotree\pseudo_cron\helper;

/**
 * Class set_params
 *
 * @package holotree\pseudo_cron\helper
 */
class set_params {

	/**
	 * The name of the option that saves the non-default params
	 *
	 * @access protected
	 *
	 * @var string
	 */
	protected $option_name = '_ht_pseudo_cron';

	/**
	 * The default options
	 *
	 * @var defaults
	 */
	private $defaults;

	/**
	 * Constructed params for running pseudo-cron
	 *
	 * @var object
	 */
	public $params;

	/**
	 * Constructor for this class
	 */
	public function __construct()  {
		$this->defaults =  new defaults();
		$this->set();
	}

	/**
	 * Returns the params of this class for use externally.
	 *
	 * @return object
	 */
	public function the_params() {
		return (object) $this->params;

	}

	/**
	 * Sets the params
	 *
	 * @access private
	 *
	 */
	private function set() {
		$save = $this->get_saved();
		if ( is_null( $save ) ) {
			$this->params = $this->defaults;
		}else{
			$params = wp_parse_args( (array) $save, (array) $this->defaults );
			$this->params = (object) $params;
		}

	}


	/**
	 * Get the non-default, saved params
	 *
	 * @access protected
	 *
	 * @return array|null
	 */
	protected function get_saved() {
		return get_option( $this->option_name, null );

	}


}
