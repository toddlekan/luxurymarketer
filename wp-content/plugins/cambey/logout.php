<?php
require(dirname(__FILE__)."/shared.php");

if ( function_exists( 'lm_log' ) ) {
	lm_log( 'logout.explicit', array(
		'cookies_before' => lm_cookie_snapshot(),
		'cred_payload'   => lm_decode_cookie_for_log( $cred_name ),
	) );
}

trash_cookies();
