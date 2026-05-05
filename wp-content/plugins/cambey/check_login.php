<?php
require(dirname(__FILE__) . "/shared.php");

$debug = false;
//$debug = rand(1, 100);

$post_id = 0;
$token = '';
if (array_key_exists('post_id', $_GET)) {
	$post_id = $_GET['post_id'];
	$token = get_token($post_id);
	$yesterday_token = get_token($post_id, true);
}

$acctno = '';
$cwrec_id = '';
$time = '';
$hash = '';
$cred_issued = 0;
$max_session = 60 * 60 * 24 * 14;
$debug_session = isset($_GET['lm_debug_session']) && $_GET['lm_debug_session'] === '1';
$cred_issued = lm_read_cred_issued($cred_name, $cred_salt);

lm_log( 'check_login.start', array(
	'post_id'      => $post_id,
	'cookies'      => lm_cookie_snapshot(),
	'cred_issued'  => $cred_issued,
	'cred_age_d'   => $cred_issued > 0 ? round( ( time() - $cred_issued ) / 86400, 4 ) : null,
	'cred_payload' => lm_decode_cookie_for_log( $cred_name ),
	'dp_payload'   => lm_decode_cookie_for_log( $day_pass_name ),
) );


//check day pass first
if (array_key_exists($day_pass_name, $_COOKIE) && $_COOKIE[$day_pass_name]) {

	debug('day pass exists');

	$arr = read_cookie($day_pass_name);

	$cwrec_id = $arr[0];
	$acctno = $arr[1];
	$time = $arr[2];
	$hash = $arr[3];

	//$check_hash = md5($acctno.$cwrec_id.$time.$day_pass_salt);
	$check_hash = md5($cwrec_id . $acctno . $day_pass_salt);

	if ($check_hash == $hash) {

		debug('hashes match');

		$now = time();

		//day pass
		if ($time + (60 * 60 * 24) > $now) {

			debug('hashes match');

			lm_log( 'day_pass.valid', array(
				'cwrec_id'    => (string) $cwrec_id,
				'acctno'      => (string) $acctno,
				'issued'      => (int) $time,
				'age_sec'     => $now - (int) $time,
				'expires_sec' => ( (int) $time + 86400 ) - $now,
			) );

			// Slide the 14-day credential window forward on each verified visit so users
			// who keep visiting within the day-pass window don't get logged out at day 14
			// from initial login. Also re-bake the day_pass so its HTTP expiry stays fresh.
			if ($acctno || $cwrec_id) {
				bake_cred($cwrec_id, $acctno);
				bake_day_pass($cwrec_id, $acctno);
				$cred_issued = time();
			} else {
				lm_log( 'day_pass.skip_rebake_no_ids' );
			}

			if ($acctno) {
				debug("in by acct $acctno");
				lm_log( 'check_login.end', array( 'outcome' => 'ok_day_pass_acct', 'acctno' => $acctno ) );
				print json_encode(lm_attach_session_debug(array('ac' => $acctno, 'day_pass' => 'true', 'token' => $token, 'token2' => $yesterday_token), $debug_session, $cred_issued, $max_session));
				die();
			} elseif ($cwrec_id) {
				debug("in by recid $cwrec_id");
				lm_log( 'check_login.end', array( 'outcome' => 'ok_day_pass_cw', 'cwrec_id' => $cwrec_id ) );
				print json_encode(lm_attach_session_debug(array('cw' => $cwrec_id, 'day_pass' => 'true', 'token' => $token, 'token2' => $yesterday_token), $debug_session, $cred_issued, $max_session));
				die();
			} else {
				debug("not going in");
				lm_log( 'day_pass.no_ids_in_payload' );
			}
		} else {
			debug("time has passed");
			lm_log( 'day_pass.expired', array(
				'issued'  => (int) $time,
				'age_sec' => $now - (int) $time,
				'over_by' => $now - ( (int) $time + 86400 ),
			) );
		}
	} else {
		debug('hashes do not match ' . "$check_hash == $hash");
		lm_log( 'day_pass.hash_mismatch', array( 'expected' => $check_hash, 'got' => $hash ) );
	}
} else {
	debug('day pass does not exist');
	lm_log( 'day_pass.absent' );
}

if (array_key_exists($cred_name, $_COOKIE) && $_COOKIE[$cred_name]) {

	debug("cred exists");

	$arr = read_cookie($cred_name);

	$cwrec_id = $arr[0];
	$acctno = $arr[1];
	$time = $arr[2];
	$cred_issued = is_numeric($time) ? (int) $time : 0;
	$hash = $arr[3];

	//$check_hash = md5($acctno.$cwrec_id.$time.$cred_salt);
	$check_hash = md5($cwrec_id . $acctno . $cred_salt);

	if ($check_hash != $hash) {
		debug('hashes do not match ' . "$check_hash != $hash");
		lm_log( 'cred.hash_mismatch', array(
			'expected' => $check_hash,
			'got'      => $hash,
			'cwrec_id' => (string) $cwrec_id,
			'acctno'   => (string) $acctno,
			'reason'   => 'die without response - client interprets empty body as logged out',
		) );
		lm_log( 'check_login.end', array( 'outcome' => 'die_cred_hash_mismatch' ) );
		die();
	} else {
		debug('hashes match ' . "$check_hash == $hash");
		// Enforce same 14-day limit as bake_cred() in shared.php (subscriber session).
		$issued      = $cred_issued;
		if ( $issued <= 0 || ( $issued + $max_session ) < time() ) {
			debug('subscriber session expired');
			lm_log( 'cred.session_expired', array(
				'issued'      => (int) $issued,
				'age_sec'     => $issued > 0 ? ( time() - (int) $issued ) : null,
				'age_days'    => $issued > 0 ? round( ( time() - (int) $issued ) / 86400, 4 ) : null,
				'max_session' => $max_session,
			) );
			trash_cookies();
			lm_log( 'check_login.end', array( 'outcome' => 'die_cred_expired' ) );
			die();
		}
		lm_log( 'cred.valid', array(
			'cwrec_id'       => (string) $cwrec_id,
			'acctno'         => (string) $acctno,
			'issued'         => (int) $issued,
			'age_days'       => round( ( time() - (int) $issued ) / 86400, 4 ),
			'remaining_days' => round( ( ( $issued + $max_session ) - time() ) / 86400, 4 ),
		) );
	}
} else {
	debug('no cred cookie');
	lm_log( 'cred.absent' );
}



if ($acctno) {

	debug("acctno: $acctno");

	$api_t0 = microtime( true );
	$result = get_result('ByAcctNo', array('subscriber_acctno' => urlencode($acctno),));
	$api_ms = (int) ( ( microtime( true ) - $api_t0 ) * 1000 );

	lm_log( 'api.ByAcctNo', array(
		'acctno' => $acctno,
		'ms'     => $api_ms,
		'result' => $result,
		'has_ac' => array_key_exists( 'ac', $result ) && $result['ac'] !== '',
	) );

	//return the accountno

	if (array_key_exists('ac', $result) && $result['ac']) {

		debug('ac exists');

		// Keep subscriber sessions alive for 14 days from latest verified activity.
		bake_cred($cwrec_id, $acctno);
		bake_day_pass($cwrec_id, $acctno);
		lm_log( 'check_login.end', array( 'outcome' => 'ok_api_acct', 'acctno' => $acctno, 'api_ms' => $api_ms ) );
		print json_encode(lm_attach_session_debug(array('ac' => $acctno), $debug_session, time(), $max_session));
	} else {
		debug('trash from acctno, does not exist');
		lm_log( 'api.ByAcctNo.empty_ac', array(
			'acctno' => $acctno,
			'reason' => 'API returned no acctno - trashing cookies, user will be logged out',
		) );
		trash_cookies();
		lm_log( 'check_login.end', array( 'outcome' => 'trash_api_acct_empty', 'acctno' => $acctno ) );
	}
} elseif ($cwrec_id) {

	debug("cwrec_id: $cwrec_id");

	$api_t0 = microtime( true );
	$result = get_result('ByCWRecId ', array('cwrecid' => urlencode($cwrec_id),));
	$api_ms = (int) ( ( microtime( true ) - $api_t0 ) * 1000 );

	lm_log( 'api.ByCWRecId', array(
		'cwrec_id' => $cwrec_id,
		'ms'       => $api_ms,
		'result'   => $result,
	) );

	//return the accountno

	if (array_key_exists('ac', $result) && $result['ac']) {

		debug('ac exists');

		$acctno = $result['ac'];

		bake_cred($cwrec_id, $acctno);
		bake_day_pass($cwrec_id, $acctno);

		lm_log( 'check_login.end', array( 'outcome' => 'ok_api_cw_to_acct', 'cwrec_id' => $cwrec_id, 'acctno' => $acctno, 'api_ms' => $api_ms ) );
		print json_encode(lm_attach_session_debug($result, $debug_session, time(), $max_session));
	} elseif (array_key_exists('cw', $result) && $result['cw']) {

		debug('cw exists');

		$cwrec_id = $result['cw'];

		bake_day_pass($cwrec_id, $acctno);
		lm_log( 'check_login.end', array( 'outcome' => 'ok_api_cw_only', 'cwrec_id' => $cwrec_id, 'api_ms' => $api_ms ) );
		print json_encode(lm_attach_session_debug($result, $debug_session, $cred_issued, $max_session));
	} else {

		debug('trash from cw');
		lm_log( 'api.ByCWRecId.empty', array(
			'cwrec_id' => $cwrec_id,
			'reason'   => 'API returned no acctno or cwrec_id - trashing cookies',
		) );
		trash_cookies();
		lm_log( 'check_login.end', array( 'outcome' => 'trash_api_cw_empty', 'cwrec_id' => $cwrec_id ) );
	}
} else {
	debug('no cw or ac');
	lm_log( 'check_login.end', array( 'outcome' => 'noop_no_cred', 'note' => 'no day-pass and no cred cookie; nothing to verify' ) );
}

function get_result($action, $arr)
{
	global $token;
	global $yesterday_token;
	
	//recheck acct status
	$url = 'https://www.cambeywest.com/api/service.asmx/GetSubscriberData_' . $action;

	$fields = array(

		'pub_acronym' => urlencode('LXR'),
		'auth_user' => urlencode('API231018'),
		'auth_pass' => urlencode('5358Q3R2XQ'),

	);

	$fields = array_merge($arr, $fields);

	$fields_string = "";

	//url-ify the data for the POST
	foreach ($fields as $key => $value) {
		$fields_string .= $key . '=' . $value . '&';
	}
	rtrim($fields_string, '&');

	//open connection
	$ch = curl_init();

	//set the url, number of POST vars, POST data
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, count($fields));
	curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);

	//execute post
	ob_start();

	$exec_ok    = curl_exec($ch);
	$curl_errno = curl_errno($ch);
	$curl_error = curl_error($ch);
	$http_code  = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
	$total_time = (float) curl_getinfo($ch, CURLINFO_TOTAL_TIME);

	$result = ob_get_contents();

	ob_end_clean();

	//close connection
	curl_close($ch);

	if ( function_exists( 'lm_log' ) ) {
		lm_log( 'cambey_api.http', array(
			'action'      => trim( (string) $action ),
			'http_code'   => $http_code,
			'curl_errno'  => $curl_errno,
			'curl_error'  => $curl_error,
			'curl_sec'    => round( $total_time, 4 ),
			'body_len'    => strlen( (string) $result ),
			'body_empty'  => ( $result === '' || $result === false ),
		) );
	}

	$result = substr($result, strpos($result, '?' . '>') + 2);

	$result = str_replace(array('&gt;', '&lt;'), array('>', '<'), $result);

	$xml = @simplexml_load_string($result);

	if ( ! $xml ) {
		if ( function_exists( 'lm_log' ) ) {
			lm_log( 'cambey_api.parse_fail', array(
				'action'         => trim( (string) $action ),
				'payload_len'    => strlen( (string) $result ),
				'payload_sample' => substr( preg_replace( '/\s+/', ' ', (string) $result ), 0, 300 ),
			) );
		}
		return array( 'ac' => '', 'token' => $token, 'token2' => $yesterday_token, '_parse_fail' => true );
	}

	$data =  $xml->SubscriberData;

	if (!$data) {
		$data = $xml->SubscriberDataRoot->SubscriberData;
	}

	if (trim($action) == 'ByCWRecId') {

		if ((string)$data->acctno) {

			$result =  array('ac' => (string)$data->acctno, 'token' => $token, 'token2' => $yesterday_token);
		} else {

			$result =  array('cw' => (string)$data->cwrec_id, 'token' => $token, 'token2' => $yesterday_token);
		}
	} else {

		$result =  array('ac' => (string)$data->acctno, 'token' => $token, 'token2' => $yesterday_token);
	}

	return $result;
}

/**
 * Temporary opt-in diagnostics for subscriber cookie lifetime.
 * Enable with: /wp-content/plugins/cambey/check_login.php?...&lm_debug_session=1
 */
function lm_attach_session_debug($payload, $enabled, $issued, $max_session)
{
	if (!$enabled) {
		return $payload;
	}

	$now = time();
	$issued_ts = is_numeric($issued) ? (int) $issued : 0;
	$expires_ts = $issued_ts > 0 ? $issued_ts + (int) $max_session : 0;
	$remaining = $expires_ts > 0 ? max(0, $expires_ts - $now) : 0;

	$payload['_session_debug'] = array(
		'server_now_unix' => $now,
		'server_now_iso' => gmdate('c', $now),
		'cred_cookie_present' => !empty($_COOKIE['_QAS3247adjl']),
		'cred_issued_unix' => $issued_ts,
		'cred_issued_iso' => $issued_ts > 0 ? gmdate('c', $issued_ts) : null,
		'cred_expires_unix' => $expires_ts,
		'cred_expires_iso' => $expires_ts > 0 ? gmdate('c', $expires_ts) : null,
		'seconds_remaining' => $remaining,
		'days_remaining' => round($remaining / 86400, 4),
		'max_session_seconds' => (int) $max_session,
	);

	return $payload;
}

/**
 * Read issued timestamp from subscriber credential cookie even when day-pass
 * flow returns early, so debug output reflects the 14-day cookie window.
 */
function lm_read_cred_issued($cred_name, $cred_salt)
{
	if (!array_key_exists($cred_name, $_COOKIE) || !$_COOKIE[$cred_name]) {
		return 0;
	}

	$base64 = $_COOKIE[$cred_name];
	$json = base64_decode($base64);
	$arr = json_decode($json, true);

	if (!is_array($arr) || count($arr) < 4) {
		return 0;
	}

	$cwrec_id = $arr[0];
	$acctno = $arr[1];
	$issued = $arr[2];
	$hash = $arr[3];

	$check_hash = md5($cwrec_id . $acctno . $cred_salt);
	if ($check_hash !== $hash) {
		return 0;
	}

	return is_numeric($issued) ? (int) $issued : 0;
}
