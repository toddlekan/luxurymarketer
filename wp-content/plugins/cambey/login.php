<?php
require dirname( __FILE__ ) . '/shared.php';

if ( ! function_exists( 'cambey_pick_subscriber_data' ) ) {
/**
 * Pick the SubscriberData node that actually contains account identifiers (SOAP may nest multiple nodes).
 *
 * @param SimpleXMLElement $xml Parsed outer or inner XML.
 * @return SimpleXMLElement|null
 */
function cambey_pick_subscriber_data( SimpleXMLElement $xml ) {
	$nodes = $xml->xpath( '//*[local-name()="SubscriberData"]' );
	if ( empty( $nodes ) ) {
		return null;
	}
	foreach ( $nodes as $node ) {
		$acct = trim( (string) $node->acctno );
		$cw   = trim( (string) $node->cwrec_id );
		$res  = strtolower( trim( (string) $node->result ) );
		if ( ( $res === 'true' || $res === '1' ) && ( $acct !== '' || $cw !== '' ) ) {
			return $node;
		}
	}
	foreach ( $nodes as $node ) {
		$acct = trim( (string) $node->acctno );
		$cw   = trim( (string) $node->cwrec_id );
		if ( $acct !== '' || $cw !== '' ) {
			return $node;
		}
	}
	return $nodes[0];
}

/**
 * If the ASMX response is SOAP-wrapped, return inner XML string for the subscriber payload.
 *
 * @param string $raw Raw HTTP body.
 * @return string XML fragment to parse with simplexml_load_string.
 */
function cambey_unwrap_asmx_response( $raw ) {
	$raw = trim( $raw );
	if ( $raw === '' ) {
		return '';
	}
	// UTF-8 BOM
	if ( strncmp( $raw, "\xEF\xBB\xBF", 3 ) === 0 ) {
		$raw = substr( $raw, 3 );
	}
	// Try SOAP: inner payload often lives in GetSubscriberDataResult (may be CDATA + entity-encoded XML).
	if ( preg_match( '/<GetSubscriberDataResult[^>]*>([\s\S]*?)<\/GetSubscriberDataResult>/i', $raw, $m ) ) {
		$inner = trim( $m[1] );
		if ( preg_match( '/^<!\[CDATA\[/', $inner ) ) {
			$inner = preg_replace( '/^<!\[CDATA\[/', '', $inner );
			$inner = preg_replace( '/\]\]>\s*$/', '', $inner );
			$inner = trim( $inner );
		}
		$inner = html_entity_decode( $inner, ENT_QUOTES | ENT_XML1, 'UTF-8' );
		$inner = trim( $inner );
		if ( $inner !== '' ) {
			return $inner;
		}
	}
	// Original path: strip first XML declaration and use remainder (legacy direct string response).
	$xml_start = strpos( $raw, '?>' );
	if ( $xml_start !== false ) {
		$raw = substr( $raw, $xml_start + 2 );
	}
	return str_replace( array( '&gt;', '&lt;' ), array( '>', '<' ), $raw );
}
} // end function_exists guard for cambey XML helpers

//extract data from the post
//set POST variables

$url = 'https://www.cambeywest.com/api/service.asmx/GetSubscriberData';
$fields = array(
	'subscriber_email' => isset( $_POST['subscriber_email'] ) ? stripslashes( (string) $_POST['subscriber_email'] ) : '',
	'subscriber_pass'  => isset( $_POST['subscriber_pass'] ) ? stripslashes( (string) $_POST['subscriber_pass'] ) : '',
	'pub_acronym'      => 'LXM',
	'auth_user'        => 'API231018',
	'auth_pass'        => '5358Q3R2XQ',
);

if ($fields['subscriber_email'] == '' || $fields['subscriber_pass'] == '') {
    $msg = "Either email or password is blank.";
    $url = "https:\/\/luxurymarketer.subsmediahub.com\/LXM\/?f=paid";
    $url_label = "Subscribe";
    $arr = array(
        'msg' => (string)$msg,
        'url' => (string)$url,
        'url_label' => (string)$url_label,
        'acctno' => isset($acctno) ? $acctno : ""
    );

	header( 'Content-Type: application/json; charset=utf-8' );
    print json_encode($arr);
    exit;
}


$fields_string = http_build_query( $fields );

//open connection
$curl = curl_init();

//set the url, number of POST vars, POST data
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_POST, count($fields));
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $fields_string);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

$result = curl_exec($curl);

//close connection
curl_close($curl);

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

if ($result === false || $result === '') {
	$arr = array(
		'msg'         => 'Login service temporarily unavailable. Please try again.',
		'url'         => '',
		'url_label'   => '',
		'acctno'      => '',
	);
	header( 'Content-Type: application/json; charset=utf-8' );
	print json_encode( $arr );
	exit;
}

$payload = cambey_unwrap_asmx_response( $result );
$xml     = @simplexml_load_string( $payload );

if ( ! $xml ) {
	$arr = array(
		'msg'       => 'Login service returned an unexpected response. Please try again.',
		'url'       => '',
		'url_label' => '',
		'acctno'    => '',
	);
	header( 'Content-Type: application/json; charset=utf-8' );
	print json_encode( $arr );
	exit;
}

$data = cambey_pick_subscriber_data( $xml );

if ( ! $data ) {
	$arr = array(
		'msg'       => 'Login service returned incomplete data. Please try again.',
		'url'       => '',
		'url_label' => '',
		'acctno'    => '',
	);
	header( 'Content-Type: application/json; charset=utf-8' );
	print json_encode( $arr );
	exit;
}

$msg       = (string) $data->frienderrormsg;
$url       = (string) $data->friendhttp;
$url_label = (string) $data->friendcorrectiveaction;

$fc        = trim( (string) $data->friendcorrectiveaction );
$acctno    = trim( (string) $data->acctno );
$cwrec_id  = trim( (string) $data->cwrec_id );
$result_ok = in_array( strtolower( trim( (string) $data->result ) ), array( 'true', '1' ), true );
$has_ids   = ( $acctno !== '' || $cwrec_id !== '' );

// Success when we have account identifiers and either <result> is true or there is no "friend" corrective action (legacy).
// Fixes cases where friendcorrectiveaction is still populated but login succeeded (result + acctno present).
if ( $has_ids && ( $result_ok || $fc === '' ) ) {
	bake_cred( $cwrec_id, $acctno );
	bake_day_pass( $cwrec_id, $acctno );
	$msg       = 'You have been logged in!';
	$url       = '';
	$url_label = '';
} elseif ( $fc !== '' ) {
	trash_cookies();
} else {
	$msg       = 'Your account information was not found. Please try again or click the link below to start a subscription.';
	$url       = 'https://join.luxurymarketer.com/LXR/?f=paid';
	$url_label = 'Subscribe';
	trash_cookies();
}

$arr = array(
    'msg' => (string)$msg,
    'url' => (string)$url,
    'url_label' => (string)$url_label,
    'acctno' => isset($acctno) ? $acctno : ""
);

header( 'Content-Type: application/json; charset=utf-8' );
print json_encode($arr);
exit;
