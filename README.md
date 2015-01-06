This is a simple system for running "pseudo-cron jobs" triggered by an external cron system, like [Set Cron Job](https://www.setcronjob.com/). It is designed to be used in place of a real cron job, or WordPress' own pseudo-cron system.

### How to use
By default, this library effectively does nothing. You must save some settings in the option "_ht_pseudo_cron" for it to do anything meaningful.

##### Make Things Happen
You can specify callback functions and actions to run when the system runs. You can have any number of actions, functions and methods in class run.

To have an action, add a key to the option called actions, with an array of actions to run. For example, to run the actions "slug_do_hourly" and "slug_updates", you would save like this:

```php
    update_option( '_ht_pseudo_cron', array( 'actions' => array( 'slug_do_hourly', 'slug_updates' );
```

You can also call functions directly. For example, to call the method foo of the class foofighter, and the function bar, save the option like this:

```php
    	update_option( '_ht_pseudo_cron', array(
    			'callbacks' => array(
    				array( 'foofighter', 'foo' ),
    				'bar'
    			)
    		)
    	);
```

##### Setting Up The Endpoint
By default this system will work when a request is made to `yoursite.com/ht-pseudo-cron?ht-pseudo-cron-key=12345`

You can change the name of the endpoint by settings (IE the option "_ht_pseudo_cron") the "endpoint" in the settings array.  You can change the key name "secret_key_name" in the settings. You can also change the key value from something other than 1235, using "secret_key".

For example, to change the endpoint to "ex-cron", the key name to "key" and the key itself to "42", you would do:

```php
    	update_option( '_ht_pseudo_cron', array(
    			'endpoint' => 'ex-cron',
    			'secret_key_name' => 'key',
    			'secret_key' => '42'
    		) 
    	);
```

##### Changing The Lockout Time (or hold on that's not very secure)
Yes, without the lockout time in place this is super-not secure as someone evil-doer could hit your endpoint over and over again, run all those callbacks and actions and DDOS your site. Luckily this system can only be run once per period specified in the "lockout_time" key of the settings. Even if someone/ something other than hits the endpoint, the system can only run once per intended interval.

The default lockout time is one hour.


### License, Copyright etc.
Copyright 2014 [Josh Pollock](http://JoshPress.net).

Licensed under the terms of the [GNU General Public License version 2](http://www.gnu.org/licenses/gpl-2.0.html) or later. Please share with your neighbor.


