<?php defined('SYSPATH') or die('No direct access allowed.');

return array(
    // Go to your facebook apps (https://developers.facebook.com/apps) to get these

	'app_id'        => 'YOUR-APP-ID',
	'secret'		=> 'YOUR-APP-SECRET',
	'next'			=> 'YOUR-SUCCESS-REDIRECT-URI',
	'cancel_url'	=> 'YOUR-CANCEL-REDIRECT-URI',
	'req_perms'		=> 'email',

	// Full list of permission you can request is available here: https://developers.facebook.com/docs/reference/api/permissions/    
);