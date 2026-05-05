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
    if ( function_exists( 'lm_log' ) ) {
      lm_log( 'setcookie_skipped_empty_name' );
    }
    return;
  }

  $is_https = ( ! empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] !== 'off' )
    || ( ! empty( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https' );

  $headers_sent_file = '';
  $headers_sent_line = 0;
  $headers_sent     = headers_sent( $headers_sent_file, $headers_sent_line );

  if ( PHP_VERSION_ID >= 70300 ) {
    $ok = setcookie( $name, $value, array(
      'expires'  => (int) $expires,
      'path'     => '/',
      'domain'   => '.luxurymarketer.com',
      'secure'   => (bool) $is_https,
      'httponly' => false,
      'samesite' => 'Lax',
    ) );
  } else {
    $ok = setcookie( $name, $value, (int) $expires, '/; samesite=Lax', '.luxurymarketer.com', (bool) $is_https, false );
  }

  if ( function_exists( 'lm_log' ) ) {
    lm_log( 'setcookie', array(
      'name'              => $name,
      'value_len'         => strlen( (string) $value ),
      'value_empty'       => ( $value === '' ),
      'expires_unix'      => (int) $expires,
      'expires_iso'       => (int) $expires > 0 ? gmdate( 'c', (int) $expires ) : null,
      'expires_in_sec'    => (int) $expires - time(),
      'is_https'          => (bool) $is_https,
      'samesite'          => 'Lax',
      'headers_sent'      => (bool) $headers_sent,
      'headers_sent_file' => $headers_sent ? $headers_sent_file : null,
      'headers_sent_line' => $headers_sent ? (int) $headers_sent_line : null,
      'setcookie_return'  => (bool) $ok,
    ) );
  }
}

function bake_cred($cwrec_id, $acctno)
{

  global $cred_salt, $cred_name;

  $expires = time() + 60 * 60 * 24 * 14;

  if ( function_exists( 'lm_log' ) ) {
    lm_log( 'bake_cred', array(
      'cwrec_id'       => (string) $cwrec_id,
      'acctno'         => (string) $acctno,
      'expires_unix'   => $expires,
      'expires_iso'    => gmdate( 'c', $expires ),
      'days_from_now'  => round( ( $expires - time() ) / 86400, 4 ),
      'caller'         => lm_short_backtrace( 4 ),
    ) );
  }

  cambey_setcookie( "luxurymarketer_acctno", $acctno, $expires );

  bake_cookie($cwrec_id, $acctno, $cred_salt, $cred_name, $expires);
}

function bake_day_pass($cwrec_id, $acctno)
{

  global $day_pass_salt, $day_pass_name;

  $expires = time() + 60 * 60 * 24;

  if ( function_exists( 'lm_log' ) ) {
    lm_log( 'bake_day_pass', array(
      'cwrec_id'       => (string) $cwrec_id,
      'acctno'         => (string) $acctno,
      'expires_unix'   => $expires,
      'expires_iso'    => gmdate( 'c', $expires ),
      'caller'         => lm_short_backtrace( 4 ),
    ) );
  }

  bake_cookie($cwrec_id, $acctno, $day_pass_salt, $day_pass_name, $expires);
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

  if ( function_exists( 'lm_log' ) ) {
    lm_log( 'trash_cookies', array(
      'cookies_before' => lm_cookie_snapshot(),
      'cred_payload'   => isset( $cred_name ) && $cred_name ? lm_decode_cookie_for_log( $cred_name ) : null,
      // 8-frame backtrace tells us exactly which decision branch wiped the user's session.
      'backtrace'      => lm_short_backtrace( 8 ),
    ) );
  }

  $past = time() - 3600;
  cambey_setcookie( $cred_name, '', $past );
  cambey_setcookie( $day_pass_name, '', $past );
  cambey_setcookie( 'luxurymarketer_login', '', $past );
  cambey_setcookie( 'luxurymarketer_acctno', '', $past );
}

/**
 * Compact backtrace ("file:line in function") for log entries.
 */
function lm_short_backtrace( $limit = 6 )
{
  $frames = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS, $limit + 1 );
  array_shift( $frames ); // drop self
  $out = array();
  foreach ( $frames as $f ) {
    $file = isset( $f['file'] ) ? basename( $f['file'] ) : '?';
    $line = isset( $f['line'] ) ? (int) $f['line'] : 0;
    $func = isset( $f['function'] ) ? $f['function'] : '?';
    $out[] = $file . ':' . $line . ' in ' . $func;
  }
  return $out;
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

/**
 * Path to the cambey debug log. Tries wp-content/uploads first (web-writable on
 * shared hosts), falls back to /tmp. Logs are auto-rotated at LM_LOG_MAX_BYTES.
 *
 * To disable: define( 'CAMBEY_LOG_DISABLED', true ) in wp-config.php.
 */
if ( ! defined( 'LM_LOG_MAX_BYTES' ) ) {
  define( 'LM_LOG_MAX_BYTES', 5 * 1024 * 1024 );
}

function lm_log_path()
{
  static $cached = null;
  if ( $cached !== null ) {
    return $cached;
  }
  // wp-content/uploads/cambey-debug.log; cambey is at wp-content/plugins/cambey
  $dir = dirname( dirname( dirname( __FILE__ ) ) ) . '/uploads';
  if ( defined( 'WP_CONTENT_DIR' ) ) {
    $dir = WP_CONTENT_DIR . '/uploads';
  }
  if ( ! is_dir( $dir ) ) {
    @mkdir( $dir, 0755, true );
  }
  if ( is_dir( $dir ) && is_writable( $dir ) ) {
    $cached = $dir . '/cambey-debug.log';
  } else {
    $cached = '/tmp/cambey-debug.log';
  }
  return $cached;
}

/**
 * Stable per-request id so we can group every event from a single page hit.
 */
function lm_request_id()
{
  static $rid = null;
  if ( $rid === null ) {
    $rid = substr( md5( uniqid( '', true ) . mt_rand() ), 0, 12 );
  }
  return $rid;
}

/**
 * Append a single JSON event to the cambey debug log.
 *
 * Each line is a self-contained JSON object so you can grep/jq the file:
 *   grep '"ev":"trash_cookies"' wp-content/uploads/cambey-debug.log | tail
 */
function lm_log( $event, $data = array() )
{
  if ( defined( 'CAMBEY_LOG_DISABLED' ) && CAMBEY_LOG_DISABLED ) {
    return;
  }

  $path = lm_log_path();
  if ( ! $path ) {
    return;
  }

  // Cheap rotation
  if ( @file_exists( $path ) && @filesize( $path ) > LM_LOG_MAX_BYTES ) {
    @rename( $path, $path . '.1' );
  }

  $entry = array(
    'ts'   => gmdate( 'c' ),
    'rid'  => lm_request_id(),
    'ev'   => $event,
    'ip'   => isset( $_SERVER['REMOTE_ADDR'] ) ? $_SERVER['REMOTE_ADDR'] : '',
    'uri'  => isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : '',
    'ua'   => isset( $_SERVER['HTTP_USER_AGENT'] ) ? substr( (string) $_SERVER['HTTP_USER_AGENT'], 0, 200 ) : '',
    'ref'  => isset( $_SERVER['HTTP_REFERER'] ) ? (string) $_SERVER['HTTP_REFERER'] : '',
    'data' => $data,
  );

  $json = @json_encode( $entry, defined( 'JSON_UNESCAPED_SLASHES' ) ? JSON_UNESCAPED_SLASHES : 0 );
  if ( $json === false || $json === null ) {
    $json = '{"ts":"' . gmdate( 'c' ) . '","ev":"' . $event . '","encode_failed":true}';
  }

  @file_put_contents( $path, $json . "\n", FILE_APPEND | LOCK_EX );
}

/**
 * Snapshot of the cambey-relevant cookies on this request (sizes only — no
 * payload — so we don't leak credentials into the log).
 */
function lm_cookie_snapshot()
{
  global $cred_name, $day_pass_name;
  $names = array(
    'cred'        => $cred_name,
    'day_pass'    => $day_pass_name,
    'login'       => 'luxurymarketer_login',
    'acctno'      => 'luxurymarketer_acctno',
  );
  $out = array();
  foreach ( $names as $label => $real_name ) {
    if ( $real_name && isset( $_COOKIE[ $real_name ] ) && $_COOKIE[ $real_name ] !== '' ) {
      $out[ $label ] = array( 'present' => true, 'len' => strlen( (string) $_COOKIE[ $real_name ] ) );
    } else {
      $out[ $label ] = array( 'present' => false );
    }
  }
  return $out;
}

/**
 * Decode a cred/day_pass cookie payload safely for logging (no hash leak).
 */
function lm_decode_cookie_for_log( $cookie_name )
{
  if ( ! isset( $_COOKIE[ $cookie_name ] ) || $_COOKIE[ $cookie_name ] === '' ) {
    return array( 'present' => false );
  }
  $raw = (string) $_COOKIE[ $cookie_name ];
  $json = @base64_decode( $raw );
  if ( $json === false ) {
    return array( 'present' => true, 'parse' => 'b64_fail', 'len' => strlen( $raw ) );
  }
  $arr = @json_decode( $json, true );
  if ( ! is_array( $arr ) || count( $arr ) < 4 ) {
    return array( 'present' => true, 'parse' => 'json_fail', 'len' => strlen( $raw ) );
  }
  $issued = is_numeric( $arr[2] ) ? (int) $arr[2] : 0;
  return array(
    'present'    => true,
    'cwrec_id'   => (string) $arr[0],
    'acctno'     => (string) $arr[1],
    'issued'     => $issued,
    'issued_iso' => $issued > 0 ? gmdate( 'c', $issued ) : null,
    'age_sec'    => $issued > 0 ? ( time() - $issued ) : null,
  );
}
