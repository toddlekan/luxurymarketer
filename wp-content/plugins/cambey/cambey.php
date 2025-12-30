<?php
/**
 * @package Cambey
 * @author Todd Lekan
 * @version 0.1
 */
/*
Plugin Name: Cambey
Plugin URI:
Description: Cambey Subscription Integration
Author: Todd Lekan
Version: 0.1
Author URI: http://toddlekan.com
*/


function cambey_login() {
	//extract data from the post
	//set POST variables
	$url = 'https://www.cambeywest.com/api/service.asmx/GetSubscriberData';
	$fields = array(
		'subscriber_email' => urlencode($_POST['subscriber_email']),
		'subscriber_pass' => urlencode($_POST['subscriber_pass']),
		'pub_acronym' => urlencode('LXM'),
		'auth_user' => urlencode('API231018'),
		'auth_pass' => urlencode('5358Q3R2XQ'),

	);
	//'auth_user' => urlencode('api130425'),
	//'auth_pass' => urlencode('tyXeEQS&'),

	$fields_string ="";

	//url-ify the data for the POST
	foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
	rtrim($fields_string, '&');

	//open connection
	$ch = curl_init();

	//set the url, number of POST vars, POST data
	curl_setopt($ch,CURLOPT_URL, $url);
	curl_setopt($ch,CURLOPT_POST, count($fields));
	curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

	//execute post
	ob_start();

	curl_exec($ch);

	$result = ob_get_contents();

	ob_end_clean();

	//close connection
	curl_close($ch);

	/*
	$result = "
	<SubscriberData>
	    <result>TRUE</result>
	    <frienderrormsg><![CDATA[Your account information was not found. Please try again or click the link below to start a subscription.]]></frienderrormsg>
	    <friendcorrectiveaction><![CDATA[Please Click Here to Subscribe]]></friendcorrectiveaction>
	    <friendhttp><![CDATA[https://www.cambeywest.com/subscribe2/?p=LXR&f=paid]]></friendhttp>
	</SubscriberData>
	";
	*/

	/*
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<string xmlns="https://www.cambeywest.com/">
    <SubscriberDataRoot>
        <SubscriberData>
            <acctno>00755057</acctno>
            <addr2/>
            <bcode/>
            <btitle/>
            <city>LOS ANGELES</city>
            <company/>
            <country>USA</country>
            <curr_source>T46POA01</curr_source>
            <curr_status>C</curr_status>
            <curr_type>PD</curr_type>
            <display_name/>
            <e_mail>email71726@home.com</e_mail>
            <f_name>L</f_name>
            <l_name>CARTER</l_name>
            <nxlen>0</nxlen>
            <nxtype>UA</nxtype>
            <phone>3237359559</phone>
            <qual_src/>
            <qual_sub/>
            <qualdate/>
            <result>True</result>
            <state>CA</state>
            <street>449 S HALSTED ST</street>
            <title/>
            <undel>0</undel>
            <yiexpire>06/28/15</yiexpire>
            <zip>90007-1609</zip>
            <frienderrormsg>Your account is no longer active, please click Re-activate My Account to resume your access.</frienderrormsg>
            <friendcorrectiveaction>Re-activate my Account</friendcorrectiveaction>
            <friendhttp>https://www.cambeywest.com/subscribe2_stage/?p=LXR&amp;amp;f=renew&amp;amp;a=00755057</friendhttp>
        </SubscriberData>
    </SubscriberDataRoot>
</string>
	 */

/*
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<string xmlns="https://www.cambeywest.com/">
    <SubscriberDataRoot>
        <SubscriberData>
            <acctno>00019544</acctno>
            <addr2/>
            <bcode/>
            <btitle/>
            <city>NEW YORK</city>
            <company>COMMONWEALTH FOUNDATION</company>
            <country>USA</country>
            <curr_source>COMPERL</curr_source>
            <curr_status>A</curr_status>
            <curr_type>FT</curr_type>
            <display_name/>
            <e_mail>email1732@home.com</e_mail>
            <f_name>ANN MARIE</f_name>
            <l_name>WALSH</l_name>
            <nxlen>0</nxlen>
            <nxtype>UA</nxtype>
            <phone/>
            <qual_src/>
            <qual_sub>N</qual_sub>
            <qualdate/>
            <result>True</result>
            <state>NY</state>
            <street>15 CHAGALL RD</street>
            <title/>
            <undel>40</undel>
            <yiexpire>06/26/16</yiexpire>
            <zip>10115-0433</zip>
            <friendcorrectiveaction/>
        </SubscriberData>
    </SubscriberDataRoot>
</string>
 */


	$result = substr($result, strpos($result, '?'.'>') + 2);

	$result = str_replace(array('&gt;', '&lt;'), array('>', '<'), $result);

	//print $result;

	$xml = simplexml_load_string($result);

	$data =  $xml->SubscriberData;

	if(!$data){
		 $data = $xml->SubscriberDataRoot->SubscriberData;
	}

	if(!trim($data->friendcorrectiveaction)){

		$value = base64_encode($data->email);

		setcookie("luxurymarketer_login", $value, time()+60*60*24*7, "/", ".luxurymarketer.com", 0, false);

		print "COOKIE SET $value";
	} else {
		print "yy".trim($data->friendcorrectiveaction)."xx";
	}


	print "YO";


	return $data;
}
