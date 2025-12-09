<?php
error_reporting(E_ALL);
global $wpdb;

switch($_GET['type']){

	case 'update-reg' :

		$data = '';

		if(!empty($_POST)){

			$devicetoken = trim($_POST['dToken']);
			$devicetype = trim($_POST['dType']);
			$uuid = trim($_POST['uuId']);
			$subid = trim($_POST['subId']);
			$email = trim($_POST['email']);

			if(!empty($subid)){

				$sql2 = $wpdb->get_results("
					SELECT user_status
					FROM mobapp_users
					WHERE id = '".$subid."'
					AND user_email = '".$email."'
					AND user_status = 1");

				if(count($sql2) > 0){

					// All device tokens are inserted upon first registration. Then updated based on idUser/Subid values.
					// device Token status is tracked and managed through node service when a BadDevicToken is received.
					// This allows for the ability for user to have multiple good deviceTokens for multiple devices.

					$sql = $wpdb->get_results("
						SELECT uuid, deviceToken
						FROM mobapp_users_tokens
						WHERE uuid = '".$uuid."'
						AND idUser = '".$subid."'");

					if(count($sql) > 0){

						$oldToken = trim($sql[0]->devToken);
						$devicetoken = trim($devicetoken);

						if($oldToken != $devicetoken){

							$wpdb->update( 'mobapp_users_tokens', array( 'deviceToken' => $devicetoken, 'tokenStatus' => 'good'),  array('uuid' => $uuid));

							$data['msg'] = 'UUID was updated. User is active.';
							$data['error'] = false;

						}else{

							$data['msg'] = 'No Changes made. User is active.';
							$data['error'] = false;
						}

					}else{

						$wpdb->insert( 'mobapp_users_tokens', array('idUser' => $subid, 'deviceToken' => $devicetoken, 'uuid' => $uuid, 'deviceType' => $devicetype, 'tokenStatus' => 'good' ));

						$data['msg'] = 'New UUID was inserted. User is active.';
						$data['error'] = false;

					}

				}else{
					$data['msg'] = 'User is not active.';
					$data['error'] = true;
				}


			}else{
				$data['msg'] = 'User is not active. No subid provided.';
				$data['error'] = true;
			}

		}else{

			$data['msg'] = 'No Data was sent/retrieved.';
			$data['error'] = true;

		}

		echo json_encode($data);
		exit;

		break;

	case 'login-mobile' :
	

		$email = $_POST['subscriber_email'];
		$pass = $_POST['subscriber_pass'];

		$active_user = $wpdb->get_results("
			SELECT id, user_status
			FROM mobapp_users
			WHERE user_email = '".$email."'
			AND user_pass = '".$pass."'");
			
		if(count($active_user) > 0){

			$cats = $wpdb->get_results("
				SELECT idCat
				FROM mobapp_users_categories
				WHERE idUser = '".$active_user[0]->id."'");

			if(count($cats) > 0){
				$data['cats'] = json_encode($cats);
			}else{
				$data['cats'] = false;
			}

			$data['subId'] = $active_user[0]->id;
			$data['email'] = $email;
			$data['msg'] = 'User is active.';
			$data['error'] = false;

		}else{

			$data['msg'] = 'User is Inactive.';
			$data['error'] = true;

		}

		echo json_encode($data);
		exit;

		break;

	case 'login-web' :

			$email = $_POST['subscriber_email'];

			$active_user = $wpdb->get_results("
				SELECT id
				FROM mobapp_users
				WHERE user_email = '".$email."'");

			if(count($active_user) > 0){

				$data['subId'] = $active_user[0]->id;
				$data['email'] = $email;
				$data['msg'] = 'User is active.';
				$data['error'] = false;

			}else{

				$activeWP_user = $wpdb->get_results("
					SELECT ID, user_email, user_pass
					FROM wp_users
					WHERE user_email = '".$email."'");

					if(count($activeWP_user) > 0){

						$wpdb->insert( 'mobapp_users', array('user_email' => $activeWP_user[0]->user_email, 'user_status' => '1', 'user_pass' => $activeWP_user[0]->user_pass, 'user_type' => 'web', 'idWPUser' => $activeWP_user[0]->ID ) );

						$newID = $wpdb->get_results("
							SELECT id
							FROM mobapp_users
							WHERE user_email = '".$activeWP_user[0]->user_email."'");

						if(count($newID) > 0){

							$data['subId'] = $newID[0]->id;
							$data['email'] = $activeWP_user[0]->user_email;
							$data['msg'] = 'Web User is active.';
							$data['error'] = false;

						}else{

							$data['msg'] = 'WP User exists but no mobile Id was set.';
							$data['error'] = true;

						}

					}else{

						$data['msg'] = 'WP User does not exist.';
						$data['error'] = true;

					}

			}

			if(!$data['msg']){
				$cats = $wpdb->get_results("
					SELECT idCat
					FROM mobapp_users_categories
					WHERE idUser = '".$data['subId']."'");

				if(count($cats) > 0){
					$data['cats'] = json_encode($cats);
				}else{
					$data['cats'] = false;
				}
			}

		echo json_encode($data);
		exit;
		break;

	case 'post-message' :

		$newposts = $wpdb->get_results("
			SELECT mobapp_newposts.id, mobapp_newposts.idPost, mobapp_newposts.categories, wp_posts.post_title, wp_posts.post_excerpt, wp_posts.post_name
			FROM mobapp_newposts
			LEFT JOIN wp_posts ON wp_posts.ID = mobapp_newposts.idPost
			WHERE status = '0'");

		if(count($newposts)){

			$posts = array();
			$comlist = '';

			foreach($newposts as $key => $val){

				$cats = explode(',', $val->categories);
				//print_r($cats);
				//echo '----';
				foreach($cats as $k => $v){


					$cat = $wpdb->get_results("SELECT id, name FROM mobapp_categories WHERE wpId = '".$v."'");
					$posts[$cat[0]->id][] = array(
						'post_id' => $val->idPost,
						'post_title' => $val->post_title,
						'post_subhead' => $val->post_excerpt,
						'post_slug' => $val->post_name,
						'post_cat' => $cat[0]->id,
						'post_catname' => $cat[0]->name
					);

					if(!array_search($cat[0]->id, $comlist)){
						$comlist[] = $cat[0]->id;
						//print_r($comlist);
					}
				}
			}
		}

		$comlist = implode(',', $comlist);

		$users = $wpdb->get_results("
			SELECT mobapp_users_tokens.uuid, mobapp_users_tokens.deviceToken, mobapp_users_tokens.deviceType, mobapp_users_categories.idCat
			FROM mobapp_users_tokens
			INNER JOIN mobapp_users_categories ON mobapp_users_categories.idUser = mobapp_users_tokens.idUser
			INNER JOIN mobapp_users ON mobapp_users.id = mobapp_users_categories.idUser
			WHERE mobapp_users_categories.idCat IN (".$comlist.")
		");

		$posts_cats = array();

		if(count($users) > 0){
			foreach($users as $kuser => $vuser){

				$posts_cats[strtolower($vuser->deviceType)][$vuser->uuid]['posts'][$vuser->idCat] = $posts[$vuser->idCat];
				$posts_cats[strtolower($vuser->deviceType)][$vuser->uuid]['info'] = $vuser;
			}

			foreach($posts_cats as $kcat => $vcat){

				foreach($vcat as $kpost => $vpost){

					$cnt = 0;
					foreach($vpost['posts'] as $k => $v){
						$cnt = $cnt+count($v);
					}

					// counting. if multiples, send a list of tokens. no need to send full message.
					if($cnt > 1){
						$data['multiple'][$kcat]['tokens'][] = array('token' => $vpost['info']->deviceToken, 'count' => $cnt);
					}else{

						$singPost = '';
						foreach($vpost['posts'] as $kvp => $vvp){
							$singPost = $vvp[0];
						}

						$data['single'][$kcat][] = array('token' => $vpost['info']->deviceToken, 'posts' => $singPost);
					}
				}
			}


			header('Content-Type: application/json');
			echo json_encode($data);
//print_r($data);
			exit;
		}

		break;

	case 'set-device-status' :

		$post = json_decode($_POST);

		if(!empty($post)){
			$wpdb->insert( 'mobapp_users_errors', array( 'errDump' => $post ));
		}else{
			echo json_encode('Empty String');
		}
		header('Content-Type: application/json');
		echo json_encode($post);
		exit;
		break;

	case 'init-check-new-user' :

		$email = $_POST['SU_subscriber_email'];
		if(!empty($email)){
			$active_user = $wpdb->get_results("
				SELECT mobapp_users.id, mobapp_users.user_email, mobapp_users.user_status
				FROM mobapp_users
				WHERE user_email = '".$email."'");

			if(count($active_user) > 0){
				if($active_user[0]->user_status == 1){

					$data['email'] = $active_user[0]->user_email;
					$data['subId'] = $active_user[0]->id;
					$data['msg'] = 'USER_EXIST_ACTIVE';
					$data['error'] = true;

				}else{

					$data['email'] = $active_user[0]->user_email;
					$data['subId'] = $active_user[0]->id;
					$data['msg'] = 'USER_EXIST_INACTIVE';
					$data['error'] = true;

				}
			}else{

				$active_web_user = $wpdb->get_results("
					SELECT wp_users.user_email
					FROM wp_users
					WHERE user_email = '".$email."'");

				if(count($active_web_user) > 0){

					$data['msg'] = 'USER_EXIST_WEB';
					$data['error'] = true;

				}else{

					$data['msg'] = 'NO_USER';
					$data['error'] = false;

				}

			}
		}else{
			$data['msg'] = 'NO_EMAIL';
			$data['error'] = true;
		}
		echo json_encode($data);
		exit;
		break;

	case 'new-subscriber' :

		$tranId = $_POST['transactionId'];
		$receipt = $_POST['receipt'];
		$signature = $_POST['signature'];
		$productId = $_POST['productId'];
		$uuId = $_POST['uuId'];
		$email = $_POST['email'];
		$pass = $_POST['pcode'];
		$type = $_POST['deviceType'];
/*
		$tranId = 'asfd';
		$receipt = 'sadfasdf';
		$signature = 'asdfasfd';
		$productId = 'asdfasfd';
		$uuId = 'asdfasfd';
		$email = 'asdfasfd';
		$pass = 'asdfasfd';
		$type = 'asdfasfd';
*/
		$tempRec = json_decode($receipt);

		$orderId = $tempRec->orderId;
		$purchaseTime = $tempRec->purchaseTime;
		$purchaseToken = $tempRec->purchaseToken;

		$sql = $wpdb->insert( 'mobapp_users', array( 'user_email' => $email, 'user_status' => '1', 'user_pass' => $pass));

		if($sql){
			$new_user = $wpdb->get_results("
				SELECT id
				FROM mobapp_users
				WHERE user_email = '".$email."'");


			if(count($new_user) > 0){
				$sql2 = $wpdb->insert( 'mobapp_subscriptions', array('dateAdded' => date('Y-m-d h:i:s'), 'idUser' => $new_user[0]->id, 'transactionID' => $tranId, 'receipt' => $receipt, 'signature' => $signature, 'state' => 'ACTIVE', 'productID' => $productId, 'orderID' => $orderId, 'purchaseTime' => $purchaseTime, 'purchaseToken' => $purchaseToken ));

				if($sql2){
					$subscriptionID = $wpdb->get_results("
						SELECT id
						FROM mobapp_subscriptions
						WHERE idUser = '".$new_user[0]->id."'");

					if(count($subscriptionID) > 0){
						$wpdb->insert( 'mobapp_users_tokens', array('idUser' => $new_user[0]->id, 'uuid' => $uuId, 'deviceType' => $type ));
					}

					$data['msg'] = 'USER_ADDED';
					$data['error'] = false;
					$data['subId'] = $new_user[0]->id;

				}else{

					$errArr = json_encode($_POST);
					$wpdb->insert( 'mobapp_users_errors', array('arrArr' => $errArr, 'dateAdded' => date('Y-m-d h:i:s')));
				}

			}else{
				$data['msg'] = 'There was an error with your subscription.';
				$data['error'] = false;
				$errArr = json_encode($_POST);
				$wpdb->insert( 'mobapp_users_errors', array('arrArr' => $errArr, 'dateAdded' => date('Y-m-d h:i:s')));
			}
		}else{

			$data['msg'] = 'There was an error with your subscription.';
			$data['error'] = false;
			$errArr = json_encode($_POST);
			$wpdb->insert( 'mobapp_users_errors', array('arrArr' => $errArr, 'dateAdded' => date('Y-m-d h:i:s')));

		}


		echo json_encode($data);
		exit;
		break;

	case 'set-notification' :

		$uuid = $_POST['uuId'];
		$subId = $_POST['subId'];
		$cat = $_POST['cat'];

		if(!empty($subId)){
			if($_GET['addcat'] === 'true'){
				$exists = $wpdb->get_results("
					SELECT idUser
					FROM mobapp_users_categories
					WHERE idUser = '".$subId."'
					AND idCat = '".$cat."'");

				if(count($exists) <= 0){
					$wpdb->insert( 'mobapp_users_categories', array('idCat' => $cat, 'idUser' => $subId, ));

					$data['msg'] = 'Category added';
					$data['error'] = false;
				}else{
					$data['msg'] = 'Category already exists for this user.';
					$data['error'] = true;
				}
			}else{
				$wpdb->delete( 'mobapp_users_categories', array( 'idCat' => $cat, 'idUser' => $subId ));
				$data['msg'] = 'Category removed';
				$data['error'] = false;
			}

		}else{

			$data['msg'] = 'No subId.';
			$data['error'] = true;

		}

		$cats = $wpdb->get_results("
			SELECT idCat
			FROM mobapp_users_categories
			WHERE idUser = '".$subId."'");
		if(count($cats) > 0){
			$data['cats'] = json_encode($cats);

		}else{
			$data['cats'] = false;
		}

		echo json_encode($data);
		exit;
		break;

	case 'restore-purchases' :

		//$receipt = '{"orderId":"GPA.3308-7381-6282-72024","packageName":"com.luxurydaily.luxdaily","productId":"com.luxurydaily.1year","purchaseTime":1508885343123,"purchaseState":0,"purchaseToken":"jebnjgdjeenpbdhpnbliembe.AO-J1OxoqMAmdwBvrrYtjhbn2UF11CUKXC9SLO1RqWW15ppiGPdnA4y24V4RWnDltBJPG3dk9oFH-6pB-7ijWDhxn4IM_JVJVcP_C6r7ZMoDgeRbeE6VafaofEY_ajxVvWFKBbXqVuA_","autoRenewing":false}';
		//print_r($_POST['receipt']);
		$tempRec = $_POST['receipt'];
		//$temp = json_encode($tempRec['receipt']);
		$temp = json_decode('"'.$tempRec['receipt'].'"');
		$tempRec = json_decode($temp);

		$orderId = $tempRec->orderId;
		//$purchaseState = $tempRec->purchaseState;
		$purchaseToken = $tempRec->purchaseToken;

		$subscriptionID = $wpdb->get_results("
			SELECT mobapp_users.id, mobapp_subscriptions.orderID, mobapp_subscriptions.purchaseToken
			FROM mobapp_subscriptions
			LEFT JOIN mobapp_users ON mobapp_users.id = mobapp_subscriptions.idUser
			WHERE mobapp_subscriptions.orderID = '".$orderId."'
			AND mobapp_subscriptions.purchaseToken = '".$purchaseToken."'");

		if(count($subscriptionID) > 0){
			$data['msg'] = 'USER_SUBSCRIBED';
			$data['error'] = false;
			$data['subId'] = $subscriptionID[0]->id;
		}else{
			$data['msg'] = 'USER_NOT_SUBSCRIBED';
			$data['error'] = true;
		}


		echo json_encode($data);
		exit;
		break;

	case 'post-comment' :


	//    if ( 'POST' != $_SERVER['REQUEST_METHOD'] ) {
	// 	   $protocol = $_SERVER['SERVER_PROTOCOL'];
	// 	   if ( ! in_array( $protocol, array( 'HTTP/1.1', 'HTTP/2', 'HTTP/2.0' ) ) ) {
	// 		   $protocol = 'HTTP/1.0';
	// 	   }
	   //
	// 	   header('Allow: POST');
	// 	   if(!$mobileApp){
	// 		   header("$protocol 405 Method Not Allowed");
	// 	   }
	// 	   header('Content-Type: text/plain');
	// 	   exit;
	//    }

	   /** Sets up the WordPress Environment. */
	   require(dirname(__FILE__) . '/../../../wp-load.php' );

	   nocache_headers();

	   $comment = wp_handle_comment_submission( wp_unslash( $_POST ) );
	   if ( is_wp_error( $comment ) ) {

		   $data = intval( $comment->get_error_data() );

		   if ( ! empty( $data ) ) {

			   $data['error'] = true;
			   $data['msg'] = $comment->get_error_message();
			   //wp_die( '<p>' . $comment->get_error_message() . '</p>', __( 'Comment Submission Failure' ), array( 'response' => $data, 'back_link' => true ) );
		   } else {
			   $data['error'] = true;
		   }
	   }else{

		   $user = wp_get_current_user();

		   do_action( 'set_comment_cookies', $comment, $user );

		   $location = empty( $_POST['redirect_to'] ) ? get_comment_link( $comment ) : $_POST['redirect_to'] . '#comment-' . $comment->comment_ID;

		   $location = apply_filters( 'comment_post_redirect', $location, $comment );

		   $data['error'] = false;
		   $data['location'] = $location;
	   }


	   echo json_encode($data);
	   exit;

		break;


}
