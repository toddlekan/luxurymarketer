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
				print json_encode(array('ac' => $acctno, 'day_pass' => 'true', 'token' => $token, 'token2' => $yesterday_token));
				die();
			} elseif ($cwrec_id) {
				debug("in by recid $cwrec_id");
				print json_encode(array('cw' => $cwrec_id, 'day_pass' => 'true', 'token' => $token, 'token2' => $yesterday_token));
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
	$hash = $arr[3];

	//$check_hash = md5($acctno.$cwrec_id.$time.$cred_salt);
	$check_hash = md5($cwrec_id . $acctno . $cred_salt);

	if ($check_hash != $hash) {
		debug('hashes do not match ' . "$check_hash != $hash");

		die();
	} else {
		debug('hashes match ' . "$check_hash == $hash");
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

		bake_day_pass($cwrec_id, $acctno);
		print json_encode(array('ac' => $acctno));
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

		print json_encode($result);
	} elseif (array_key_exists('cw', $result) && $result['cw']) {

		debug('cw exists');

		$cwrec_id = $result['cw'];

		bake_day_pass($cwrec_id, $acctno);
		print json_encode($result);
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
