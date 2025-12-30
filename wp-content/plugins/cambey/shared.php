<?php

//error_reporting(E_ALL);
//ini_set('display_errors', 1);

date_default_timezone_set('America/New_York');

$cred_salt = "asjdf9792relkjsd2903892988*^*&%%sa;ldkjAKLEALKJFfl";
$cred_name = "_QAS3247adjl";

$day_pass_salt = '02398jlsdfa4893uOIEJ$(#(W*JLFEJadsf';
$day_pass_name = '_oweLKJSDF97apl';

function bake_cred($cwrec_id, $acctno)
{

  global $cred_salt, $cred_name;

  $expires = time() + 60 * 60 * 24 * 14;

  setcookie("luxurymarketer_acctno", $acctno, $expires, "/", ".luxurymarketer.com", 0, false);

  bake_cookie($cwrec_id, $acctno, $cred_salt, $cred_name, $expires);
}

function bake_day_pass($cwrec_id, $acctno)
{

  global $day_pass_salt, $day_pass_name;

  bake_cookie($cwrec_id, $acctno, $day_pass_salt, $day_pass_name, time() + 60 * 60 * 24);
}

function bake_cookie($cwrec_id, $acctno, $salt, $name, $expires)
{

  $time = time();

  //$hash = md5($cwrec_id.$acctno.$time.$salt);
  $hash = md5($cwrec_id . $acctno . $salt);

  $cookie = array(
    0 => $cwrec_id,
    1 => $acctno,
    2 => $time,
    3 => $hash
  );

  $cookie = json_encode($cookie);

  $cookie = base64_encode($cookie);

  setcookie($name, $cookie, $expires, "/", ".luxurymarketer.com", 0, false);
}

function read_cookie($name)
{

  $base64 = $_COOKIE[$name];

  $json = base64_decode($base64);

  $arr = json_decode($json, TRUE);

  return $arr;
}

function trash_cookies()
{

  global $cred_name, $day_pass_name;

  setcookie($cred_name, '', time() - 3600, "/", ".luxurymarketer.com", 0, false);
  setcookie($day_pass_name, '', time() - 3600, "/", ".luxurymarketer.com", 0, false);
  setcookie('luxurymarketer_login', '',  time() - 3600, "/", ".luxurymarketercom", 0, false);
  setcookie('luxurymarketer_acctno', '',  time() - 3600, "/", ".luxurymarketer.com", 0, false);
}

function get_token($id, $yesterday = false)
{

  //md5(post_id, today, salt) + post_id
  $salt = "928374hsdafluEKSHF$*(E";

  $date = date('Ymd');

  if ($yesterday) {

    $date = date("Ymd", strtotime('-1 days'));
  }
  if ($id != 'undefined') {
    $md5 = md5($id . $date . $salt);

    $token = $md5 . $id;
  }

  if (!isset($token)) {
    $token = 'tilt';
  }

  return $token;
}

$debug = false;
function debug($msg)
{
  global $debug;
  if ($debug) {
    file_put_contents("/tmp/output.log", "$debug: $msg" . "\r\n");
  }
}
