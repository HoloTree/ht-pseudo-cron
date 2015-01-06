<?php
/**
 * Helper class to create an object of default options.
 *
 * @package holotree\pseudo_cron\helper
 * @author    Josh Pollock <Josh@JoshPress.net>
 * @license   GPL-2.0+
 * @link      
 * @copyright 2014 Josh Pollock
 */

namespace holotree\pseudo_cron\helper;

/**
 * Class defaults
 *
 * @package holotree\pseudo_cron\helper
 */
class defaults {

	/**
	 * The name of the endpoint.
	 *
	 * @var string
	 */
	public $endpoint = 'ht-pseudo-cron';

	/**
	 * The name of the URL var for the secret key
	 *
	 * @var string
	 */
	public $secret_key_name = 'ht-pseudo-cron-key';

	/**
	 * The value of the secret key.
	 *
	 * The default value is "12345", which is the kind of code an idiot would use on his luggage.
	 *
	 * @var string
	 */
	public $secret_key = '12345';

	/**
	 * The time to lockout the system between requests
	 *
	 * @var int
	 */
	public $lockout_time = HOUR_IN_SECONDS;

	/**
	 * An array of callback functions to run or null to skip.
	 *
	 * Each key should either be the name of a function, or an array in the form of array( class_name, function_name )
	 *
	 * @var null
	 */
	public $callbacks = null;

	/**
	 * An array of actions to run or null to skip.
	 *
	 * @var null
	 */
	public $actions = null;

}


