<?
require(dirname(__FILE__)."/shared.php");

$debug = false;
//$debug = rand(1, 100);

$post_id = 0;
$token = '';
if(array_key_exists('post_id', $_GET)){
	$post_id = $_GET['post_id'];
	$token = get_token($post_id);
	$yesterday_token = get_token($post_id, true);

    print json_encode(array('token' => $token, 'token2' => $yesterday_token));
    die();
}
