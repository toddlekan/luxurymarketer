<?php
require(dirname(__FILE__) . "/shared.php");

//extract data from the post
//set POST variables

$url = 'https://www.cambeywest.com/api/service.asmx/GetSubscriberData';
$fields = array(
    'subscriber_email' => urlencode($_POST['subscriber_email']),
    'subscriber_pass' => urlencode($_POST['subscriber_pass']),
    'pub_acronym' => urlencode('LXR'),
    'auth_user' => urlencode('API231018'),
    'auth_pass' => urlencode('5358Q3R2XQ'),
);

if ($fields['subscriber_email'] == '' || $fields['subscriber_pass'] == '') {
    $msg = "Either email or password is blank.";
    $url = "https:\/\/join.luxuryroundtable.com\/LXR\/?f=paid";
    $url_label = "Subscribe";
    $arr = array(
        'msg' => (string)$msg,
        'url' => (string)$url,
        'url_label' => (string)$url_label,
        'acctno' => isset($acctno) ? $acctno : ""
    );

    print json_encode($arr);
    return;
}


$fields_string = "";

//url-ify the data for the POST
foreach ($fields as $key => $value) {
    $fields_string .= $key . '=' . $value . '&';
}
rtrim($fields_string, '&');

//open connection
$curl = curl_init();

//set the url, number of POST vars, POST data
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_POST, count($fields));
// curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_POSTFIELDS, $fields_string);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

// $fp = fopen(dirname(__FILE__).'/errorlog.txt', 'w');
// curl_setopt($curl, CURLOPT_VERBOSE, 1);
// curl_setopt($curl, CURLOPT_STDERR, $fp);

//execute post
ob_start();

curl_exec($curl);

$result = ob_get_contents();

ob_end_clean();

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

$result = substr($result, strpos($result, '?' . '>') + 2);

$result = str_replace(array('&gt;', '&lt;'), array('>', '<'), $result);

$xml = simplexml_load_string($result);

$data =  $xml->SubscriberData;

/*
SimpleXMLElement Object
(
    [SubscriberDataRoot] => SimpleXMLElement Object
        (
            [SubscriberData] => SimpleXMLElement Object
                (
                    [acctno] => 00001004
*/

if (!$data) {
    $data = $xml->SubscriberDataRoot->SubscriberData;
}

$msg = (string)$data->frienderrormsg;
$url = (string)$data->friendhttp;
$url_label = (string)$data->friendcorrectiveaction;

if (!trim($data->friendcorrectiveaction)) {

    $cwrec_id = (string)$data->cwrec_id;
    $acctno = (string)$data->acctno;

    if ($acctno !== "" || $cwrec_id !== "") {
        bake_cred($cwrec_id, $acctno);
        bake_day_pass($cwrec_id, $acctno);

        $msg = "You have been logged in!";
        $url = "";
        $url_label = "";
    } else {
        $msg = "Your account information was not found. Please try again or click the link below to start a subscription.";
        $url = "https://join.luxuryroundtable.com/LXR/?f=paid";
        $url_label = "Subscribe";
        trash_cookies();
    }
} else {

    trash_cookies();
}

$arr = array(
    'msg' => (string)$msg,
    'url' => (string)$url,
    'url_label' => (string)$url_label,
    'acctno' => isset($acctno) ? $acctno : ""
);

print json_encode($arr);
