<?php
extract($_POST);


/*require_once('/mnt/www/luxurydaily/wp-content/themes/MobileCommerceDaily/recaptchalib.php');
$privatekey = "6Le4YAsAAAAAAIWjTC4yX9vDAMrFExgLp0nqXnQW";
$resp = recaptcha_check_answer ($privatekey,
			    $_SERVER["REMOTE_ADDR"],
			    $_POST["recaptcha_challenge_field"],
			    $_POST["recaptcha_response_field"]);

if (!$resp->is_valid) {

$redirect = "http://www.luxurydaily.com/newsletter?status=Incorrect%20Captcha";
header("Location: $redirect");

} else {

 *
 */
 //set POST variables
 /*
    $url = 'http://luxurydaily.gravitywmail.com/optin.php';
    $fields = array(
		'gmail_list_key' => urlencode('ovXwPwBITNk@ODY'),
		'FIRST_NAME' => urlencode($FIRST_NAME),
		'LAST_NAME' => urlencode($LAST_NAME),
		'EMAIL' => urlencode($EMAIL),
		'TITLE' => urlencode($TITLE),
		'COMPANY' => urlencode($COMPANY),
		'COUNTRY' => urlencode($COUNTRY),
		'INDUSTRY' => urlencode($INDUSTRY),
		'ZIP' => urlencode($ZIP)
	    );

    //url-ify the data for the POST
    foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
    rtrim($fields_string, '&');

    //open connection
    $ch = curl_init();

    //set the url, number of POST vars, POST data
    curl_setopt($ch,CURLOPT_URL, $url);
    curl_setopt($ch,CURLOPT_POST, count($fields));
    curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

    //execute post
    $result = curl_exec($ch);

    //close connection
    curl_close($ch);
    *.
  *
  *
  *
  *
  */

include(dirname(__FILE__)."/MailChimp.php");
use \DrewM\MailChimp\MailChimp;

$MailChimp = new MailChimp('getenv('MAILCHIMP_API_KEY') ?: 'YOUR_MAILCHIMP_API_KEY_HERE'');

$list_id = 'e40241a98c';

$result = $MailChimp->post("lists/$list_id/members", array(
                'email_address' =>$EMAIL,
                'status'        => 'subscribed',
            ));

//print_r($result);

$subscriber_hash = $MailChimp->subscriberHash($EMAIL);

$result = $MailChimp->patch("lists/$list_id/members/$subscriber_hash", array(
                'merge_fields' => array(
                	'FNAME'=>$FIRST_NAME,
                	'LNAME'=>$LAST_NAME,
                	'TITLE'=>$TITLE,
                	'COMPANY'=>$COMPANY,
                	'COUNTRY'=>$COUNTRY,
                  'ZIPCODE'=>$ZIP,
                  'PHONE'=>$PHONE,
                	'CATEGORY'=>$RESEARCH,
            )));

//print_r($result);

header("Location: http://www.luxuryroundtable.com/subscription-confirmation/");
//}
?>
