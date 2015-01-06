<?php
/*
Plugin name: Pseudo cron
 */


include_once( dirname( __FILE__ ) . '/pseudo_cron.php' );
include_once( dirname(__FILE__ ) .'/helper/defaults.php' );
include_once( dirname(__FILE__ ) .'/helper/set_params.php' );

new holotree\pseudo_cron\pseudo_cron();

add_action( 'nog', function() {
	$x=1;
});


function nog() {
	$x=1;
}

class nog {
	public function foo() {
		$x=1;
	}
}
