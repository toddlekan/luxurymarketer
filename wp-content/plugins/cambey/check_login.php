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

			if ($acctno) {
				debug("in by acct $acctno");
				print json_encode(lm_attach_session_debug(array('ac' => $acctno, 'day_pass' => 'true', 'token' => $token, 'token2' => $yesterday_token), $debug_session, $cred_issued, $max_session));
				die();
			} elseif ($cwrec_id) {
				debug("in by recid $cwrec_id");
				print json_encode(lm_attach_session_debug(array('cw' => $cwrec_id, 'day_pass' => 'true', 'token' => $token, 'token2' => $yesterday_token), $debug_session, $cred_issued, $max_session));
				die();
			} else {
				debug("not going in");
			}
		} else {
			debug("time has passed");
		}
	} else {
		debug('hashes do not match ' . "$check_hash == $hash");
	}
} else {
	debug('day pass does not exist');
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

		die();
	} else {
		debug('hashes match ' . "$check_hash == $hash");
		// Enforce same 14-day limit as bake_cred() in shared.php (subscriber session).
		$issued      = $cred_issued;
		if ( $issued <= 0 || ( $issued + $max_session ) < time() ) {
			debug('subscriber session expired');
			trash_cookies();
			die();
		}
	}
} else {
	debug('no cred cookie');
}



if ($acctno) {

	debug("acctno: $acctno");

	$result = get_result('ByAcctNo', array('subscriber_acctno' => urlencode($acctno),));

	//return the accountno

	if (array_key_exists('ac', $result) && $result['ac']) {

		debug('ac exists');

		// Keep subscriber sessions alive for 14 days from latest verified activity.
		bake_cred($cwrec_id, $acctno);
		bake_day_pass($cwrec_id, $acctno);
		print json_encode(lm_attach_session_debug(array('ac' => $acctno), $debug_session, time(), $max_session));
	} else {
		debug('trash from acctno, does not exist');
		trash_cookies();
	}
} elseif ($cwrec_id) {

	debug("cwrec_id: $cwrec_id");

	$result = get_result('ByCWRecId ', array('cwrecid' => urlencode($cwrec_id),));

	//return the accountno

	if (array_key_exists('ac', $result) && $result['ac']) {

		debug('ac exists');

		$acctno = $result['ac'];

		bake_cred($cwrec_id, $acctno);
		bake_day_pass($cwrec_id, $acctno);

		print json_encode(lm_attach_session_debug($result, $debug_session, time(), $max_session));
	} elseif (array_key_exists('cw', $result) && $result['cw']) {

		debug('cw exists');

		$cwrec_id = $result['cw'];

		bake_day_pass($cwrec_id, $acctno);
		print json_encode(lm_attach_session_debug($result, $debug_session, $cred_issued, $max_session));
	} else {

		debug('trash from cw');
		trash_cookies();
	}
} else {
	debug('no cw or ac');
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

	curl_exec($ch);

	$result = ob_get_contents();

	ob_end_clean();

	//close connection
	curl_close($ch);

	$result = substr($result, strpos($result, '?' . '>') + 2);

	$result = str_replace(array('&gt;', '&lt;'), array('>', '<'), $result);

	$xml = simplexml_load_string($result);

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
