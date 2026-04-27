<?php

//error_reporting(E_ALL);
//ini_set('display_errors', 1);

// When shared.php is loaded from login.php included inside a function (e.g. admin-ajax),
// assignments must target the global scope or bake_cred/trash_cookies see empty $cred_name
// and PHP 8+ fatals: setcookie(): Argument #1 ($name) must not be empty.
global $cred_salt, $cred_name, $day_pass_salt, $day_pass_name, $debug;

date_default_timezone_set('America/New_York');

$cred_salt = "asjdf9792relkjsd2903892988*^*&%%sa;ldkjAKLEALKJFfl";
$cred_name = "_QAS3247adjl";

$day_pass_salt = '02398jlsdfa4893uOIEJ$(#(W*JLFEJadsf';
$day_pass_name = '_oweLKJSDF97apl';

/**
 * Set a Cambey cookie with modern attributes so browsers don't cap its lifetime.
 *
 * Browsers (especially Safari ITP) shorten cookies that are missing SameSite or
 * are set without Secure on HTTPS sites. Using SameSite=Lax + Secure preserves
 * the intended 14-day expiration baked into bake_cred()/bake_cookie().
 */
function cambey_setcookie( $name, $value, $expires )
{
  if ( $name === '' ) {
    return;
  }

  $is_https = ( ! empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] !== 'off' )
    || ( ! empty( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https' );

  if ( PHP_VERSION_ID >= 70300 ) {
    setcookie( $name, $value, array(
      'expires'  => (int) $expires,
      'path'     => '/',
      'domain'   => '.luxurymarketer.com',
      'secure'   => (bool) $is_https,
      'httponly' => false,
      'samesite' => 'Lax',
    ) );
  } else {
    setcookie( $name, $value, (int) $expires, '/; samesite=Lax', '.luxurymarketer.com', (bool) $is_https, false );
  }
}

function bake_cred($cwrec_id, $acctno)
{

  global $cred_salt, $cred_name;

  $expires = time() + 60 * 60 * 24 * 14;

  cambey_setcookie( "luxurymarketer_acctno", $acctno, $expires );

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

  cambey_setcookie( $name, $cookie, $expires );
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

  $past = time() - 3600;
  cambey_setcookie( $cred_name, '', $past );
  cambey_setcookie( $day_pass_name, '', $past );
  cambey_setcookie( 'luxurymarketer_login', '', $past );
  cambey_setcookie( 'luxurymarketer_acctno', '', $past );
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
