<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

use PHPMailer\PHPMailer\PHPMailer;

// Override any coming soon redirects - prevents cached redirects in browsers
add_action('template_redirect', 'prevent_coming_soon_redirect', 1);
function prevent_coming_soon_redirect() {
	// Check if we're being redirected to a coming soon page
	$request_uri = $_SERVER['REQUEST_URI'] ?? '';
	$query_string = $_SERVER['QUERY_STRING'] ?? '';
	
	// If someone tries to access coming soon page, redirect them away
	if (strpos($request_uri, 'coming-soon') !== false || strpos($query_string, 'coming-soon') !== false) {
		wp_redirect(home_url('/'), 301);
		exit;
	}
	
	// Prevent any redirects to coming soon pages
	if (!headers_sent()) {
		// Add cache-busting headers to help clear browser cache
		header('Cache-Control: no-cache, no-store, must-revalidate, max-age=0');
		header('Pragma: no-cache');
		header('Expires: 0');
	}
}

// Also hook into wp_redirect to prevent redirects to coming soon
add_filter('wp_redirect', 'prevent_coming_soon_redirect_url', 10, 2);
function prevent_coming_soon_redirect_url($location, $status) {
	if (strpos($location, 'coming-soon') !== false) {
		// Redirect to home instead
		return home_url('/');
	}
	return $location;
}

add_filter('acf/settings/remove_wp_meta_box', '__return_false');
add_filter('wp_get_attachment_url', 'am19_url_change');
update_option('image_default_size', 'full' );

function am19_url_change($url)
{

	return $url; //str_replace('luxurymarketer.com', 'www.luxurymarketer.com', $url);
}

if (array_key_exists('mobile', $_GET) && $_GET['mobile']) {

	add_action('after_setup_theme', 'plugin_override');

	function plugin_override()
	{
		global $wp_filter;

		foreach ($wp_filter['comment_form']->callbacks[10] as $key => $cb) {

			if (strpos($key, 'show_recaptcha_in_comments') !== FALSE) {
				remove_action('comment_form', $key);
				break;
			}
		}
	}
}

add_action('init', 'add_rewrite_tags');
function add_rewrite_tags()
{
	global $wp_rewrite;

	/*add_rewrite_rule(
        '^get-body/(.+)/?$',
        'index.php?p=199033',
        'top'
    );
		add_rewrite_tag('%token%', '(.+)');
		*/

	add_rewrite_endpoint('token', EP_PAGES);

	//$wp_rewrite->flush_rules( true );

}

function feedFilter($query)
{
	if ($query->is_feed) {

		add_filter('the_content', 'feedContentFilter');
	}
	return $query;
}
add_filter('pre_get_posts', 'feedFilter');

function feedContentFilter($content)
{
	$content = '';

	return $content;
}


//flag videos on save
add_action('future_post', 'scrape_media');
add_action('publish_page', 'scrape_media');
add_action('publish_post', 'scrape_media');
function scrape_media($id)
{
	global $wpdb;

	//file_put_contents("/tmp/ld.log", "ID: $id\r\n", FILE_APPEND);

	$post = get_post($id);
	$content = $post->post_content;

	$content = str_replace(chr(194) . chr(160), ' ', $content);

	//byline
	for ($i = 1; $i < 4; $i++) {

		delete_post_meta($id, 'byline_url');
		delete_post_meta($id, 'byline_name');
	}

	preg_match_all('#(By <a href=\"http.*?\/a>)#', $content, $bylines);

	if (count($bylines)) {

		foreach ($bylines[0] as $byline) {

			//By <a href="http://www.forter.com">Bill Zielke</a>

			preg_match('/href="([^"]*)"/i', $byline, $href);

			if (count($href) == 2) {

				$url = $href[1];

				add_post_meta($id, 'byline_url', $url, true);
			}

			preg_match('/\">([^"]*)</i', $byline, $name);

			if (count($name) == 2) {

				$name = $name[1];

				add_post_meta($id, 'byline_name', $name, true);
			}

			break;
		}
	}

	//video
	for ($i = 1; $i < 4; $i++) {

		delete_post_meta($id, 'video_' . $i);
		delete_post_meta($id, 'video_' . $i . '_caption');
		delete_post_meta($id, 'video_' . $i . '_headline');
	}

	preg_match_all('#(<iframe.*?\/iframe>)#', $content, $iframes);

	if (count($iframes)) {

		$i = 1;
		foreach ($iframes[0] as $iframe) {

			if (strpos($iframe, "facebook.com/plugins/post.php?") !== FALSE) {
			} else {

				preg_match('/src="([^"]*)"/i', $iframe, $src);

				if (count($src) == 2) {

					$url = $src[1];

					add_post_meta($id, 'video_' . $i, $url, true);

					$caption = "";

					$start = strpos($content, $iframe) + strlen($iframe) + 2;

					if ($start) {

						$end = strpos($content, '</em>', $start) + 5;

						$caption = substr($content, $start, $end - $start);
					}

					$caption = preg_replace("/<.*?>/", " ", $caption);

					add_post_meta($id, 'video_' . $i . '_caption', "$caption", true);
					add_post_meta($id, 'video_' . $i . '_headline', $post->post_title, true);
					$i++;
				}
			}
		}
	}


	////file_put_contents("/tmp/ld.log", "ID: $id\r\n", FILE_APPEND);

	//images
	////file_put_contents("/tmp/ld.log", "deleting\r\n", FILE_APPEND);
	delete_post_meta($id, 'main_img');
	delete_post_meta($id, 'gallery_img');
	delete_post_meta($id, 'has_gallery');

	$width = 0;
	$main_img = "";
	$gallery_img = array();

	$media = get_attached_media('image', $id);

	////file_put_contents("/tmp/ld.log", "ID: $id\r\n", FILE_APPEND);

	foreach ($media as $media_id => $img) {

		if ($img->ping_status == 'open') {
			$new_guid = $img->guid;
			//$new_guid = str_replace('www.luxurydaily.com', 'cache.luxurydaily.com', $new_guid);

			list($new_width, $height, $type, $attr) = getimagesize($new_guid);

			if ($new_width > $width && $new_width >= $height) {
				$main_img = $new_guid;
				$width = $new_width;
			} else {
				//file_put_contents("/tmp/ld.log", "1 $new_guid wrong dimenstions $new_width > $width && $new_width >= $height \r\n", FILE_APPEND);
			}

			//file_put_contents("/tmp/ld.log", "checking: $new_width $new_guid\r\n", FILE_APPEND);

			if ($new_width > 185) {
				if (!in_array($new_guid, $gallery_img)) {
					$gallery_img[] = $new_guid;
					//file_put_contents("/tmp/ld.log", "attached\r\n", FILE_APPEND);
				}
			} else {
				//file_put_contents("/tmp/ld.log", "gallery: $new_width $new_guid\r\n", FILE_APPEND);
			}
		}
	}

	//file_put_contents("/tmp/ld.log", "ID: $id\r\n", FILE_APPEND);

	$new_guid_arr = get_post_meta($id, 'Image');

	foreach ($new_guid_arr as $new_guid) {

		//file_put_contents("/tmp/ld.log", "checking: $new_width $new_guid\r\n", FILE_APPEND);

		//$new_guid = str_replace('www.luxurydaily.com', 'cache.luxurydaily.com', $new_guid);
		list($new_width, $height, $type, $attr) = getimagesize($new_guid);

		if (!in_array($new_guid, $gallery_img)) {
			$gallery_img[] = $new_guid;
		}

		if ($new_width > $width && $new_width >= $height) {
			$main_img = $new_guid;
			$width = $new_width;

			//file_put_contents("/tmp/ld.log", "main image changed: $new_width $new_guid\r\n", FILE_APPEND);

		} else {
			//file_put_contents("/tmp/ld.log", "2 $new_guid wrong dimenstions $new_width > $width && $new_width >= $height\r\n", FILE_APPEND);
		}
	}

	if ($new_width > 185) {
		if (!in_array($new_guid, $gallery_img)) {
			$gallery_img[] = $new_guid;
			//file_put_contents("/tmp/ld.log", "meta\r\n", FILE_APPEND);
		}
	} else {
		//file_put_contents("/tmp/ld.log", "gallery: $new_width $new_guid\r\n", FILE_APPEND);
	}

	preg_match_all('#(<img.*?>)#', $content, $images);

	if (count($images)) {
		foreach ($images[0] as $img) {

			preg_match('/src="([^"]*)"/i', $img, $src);

			if (count($src) == 2) {

				$new_guid = $src[1];

				//$new_guid = str_replace('www.luxurydaily.com', 'cache.luxurydaily.com', $new_guid);
				list($new_width, $height, $type, $attr) = getimagesize($new_guid);

				if ($new_width > $width && $new_width >= $height) {
					$main_img = $new_guid;
					$width = $new_width;
				} else {
					//file_put_contents("/tmp/ld.log", "3 $new_guid wrong dimenstions\r\n", FILE_APPEND);
				}

				//file_put_contents("/tmp/ld.log", "checking: $new_width $new_guid\r\n", FILE_APPEND);

				if ($new_width > 185) {
					if (!in_array($new_guid, $gallery_img)) {
						$gallery_img[] = $new_guid;
						//file_put_contents("/tmp/ld.log", "inline\r\n", FILE_APPEND);
					}
				} else {
					//file_put_contents("/tmp/ld.log", "gallery: $new_width $new_guid\r\n", FILE_APPEND);
				}
			}
		}
	}

	//error_reporting(E_ALL);
	//ini_set('display_errors', 1);
	//file_put_contents("/tmp/ld.log", "MAIN IMAGE: $main_img ??\r\n", FILE_APPEND);
	if ($main_img) {
		add_post_meta($id, 'main_img', $main_img, true);

		//$main_img = str_replace('www.luxurydaily.com', 'cache.luxurydaily.com', $main_img);
		list($width, $height, $type, $attr) = getimagesize($main_img);

		$new_width = 320;

		if ($width > $new_width) {

			$ratio_orig = $new_width * $height;

			$new_height = $ratio_orig / $width;


			$temp = explode('.', $main_img);
			$ext = array_pop($temp);
			$main_img_newsletter = implode('.', $temp) . "-320." . $ext;

			//ini_set("log_errors", 1);
			//ini_set("error_log", "/tmp/ld.log");

			$image_p = imagecreatetruecolor($new_width, $new_height);

			if ($ext == 'png') {
				$image = imagecreatefrompng($main_img);
			} else {
				$image = imagecreatefromjpeg($main_img);
			}

			

			imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);


			$dir = dirname(__FILE__);

			$pos = strpos($dir, "/wp-content");

			$root = substr($dir, 0, $pos);

			$pos = strpos($main_img_newsletter, "/wp-content");

			$end = substr($main_img_newsletter, $pos);
			
			// Output
			if ($ext == 'png') {
				imagepng($image_p, $root . $end);
			} else {
				imagejpeg($image_p, $root . $end, 100);
			}

			add_post_meta($id, 'main_img_newsletter', $main_img_newsletter, true);
		} else {

			add_post_meta($id, 'main_img_newsletter', $main_img, true);
		}
	}




	//file_put_contents("/tmp/ld.log", "ID: $id IMG: $main_img result: $result\r\n", FILE_APPEND);

	foreach ($gallery_img as $img) {
		//file_put_contents("/tmp/ld.log", "adding: $img\r\n", FILE_APPEND);
		add_post_meta($id, 'gallery_img', $img);
	}

	if (count($gallery_img) > 1) {
		add_post_meta($id, 'has_gallery', 'true', true);
	}
}

add_action('future_post', 'ld16_gen_pdf');
add_action('publish_post', 'ld16_gen_pdf');

function ld16_gen_pdf($id = 0)
{
	if (!$id) {
		$id = get_the_ID();
	}

	$cache_path = dirname(__FILE__) . "/../../uploads/pdf_cache";

	if (!file_exists($cache_path)) {
		//file_put_contents("/tmp/ld.log", "1: $cache_path\r\n", FILE_APPEND);
		mkdir($cache_path);
	}

	$str = (string) $id;

	for ($i = 0; $i < strlen($str); $i++) {

		$chr = $str[$i];

		$cache_path .= "/$chr";

		//file_put_contents("/tmp/ld.log", "$cache_path\r\n", FILE_APPEND);
		if (!file_exists($cache_path)) {
			// file_put_contents("/tmp/ld.log", "2: $cache_path\r\n", FILE_APPEND);
			// mkdir($cache_path);
			//file_put_contents("/tmp/ld.log", "write it\r\n", FILE_APPEND);
			//shell_exec("/bin/mkdir -p $cache_path");
			mkdir($cache_path);
		}

		/*if(!file_exists($cache_path)){
			file_put_contents("/tmp/ld.log", "still nothing\r\n", FILE_APPEND);
		}*/
	}

	$filename = "$cache_path/$id.pdf";

	//if(!file_exists($filename) || array_key_exists('nocache', $_GET)){

	// wget to tmp
	// /usr/bin/wget
	//  /usr/bin/wget -O /tmp/276748.html 'https://www.luxurydaily.com/?p=276748&format=pdf'
	$wget_cmd = "/usr/bin/wget -O /tmp/lr/$id.html 'https://luxurymarketer.com/?p=$id&format=pdf'";
	shell_exec($wget_cmd);
	file_put_contents("/tmp/lr.log", "$wget_cmd\r\n", FILE_APPEND);

	// replace whack chars
	$text = file_get_contents("/tmp/lr/$id.html");

	$find = ['â€œ', 'â€', 'â€˜', 'â€™', 'â€¦', 'â€”', 'â€“', 'â€ś', 'â€ť', 'Â'];

	$replace = ['"', '"', "'", "'", "...", "-", "-", '"', '"', ''];

	$text = str_replace($find, $replace, $text);

	$find = ['“', '”', '’'];

	$replace = ['"', '"', "'"];

	$text = str_replace($find, $replace, $text);

	$text = str_replace(chr(194), " ", $text); // 'Â'

	$text = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $text);

	$text = str_replace('<script', '<!--script ', $text);
	$text = str_replace('</script>', '</script-->', $text);

	$text = str_replace('<!--[if lt IE 9]>', '', $text);
	$text = str_replace('<![endif]-->', '', $text);

	$text = preg_replace('/<!--(.|\s)*?-->/', '', $text);

	$text = str_replace('-->', '', $text);

	file_put_contents("/tmp/lr/$id.html", $text);

	// pdf it
	$cmd = "export LD_LIBRARY_PATH=/usr/glibc-compat/lib:/usr/lib:/lib64:/lib && ";
	$cmd .= "/usr/local/bin/wkhtmltopdf  --encoding UTF-8 --load-error-handling ignore /tmp/lr/$id.html $filename";
	// $cmd = "/usr/local/bin/wkhtmltopdf \"https://www.luxurydaily.com/?p=$id&format=pdf\" $filename";
	// $cmd = "/usr/local/bin/wkhtmltopdf https://beta.luxurydaily.com/?p=204922&format=pdf /tmp/test.pdf";
	file_put_contents("/tmp/lr.log", "$cmd\r\n", FILE_APPEND);

	shell_exec("/bin/mkdir -p $cache_path");
	shell_exec($cmd);
	unlink("/tmp/lr/$id.html");

	/*
		$cmd = "/usr/local/bin/wkhtmltopdf \"https://www.luxurydaily.com/?p=$id&format=pdf\" $filename";
		//$cmd = "/usr/local/bin/wkhtmltopdf https://beta.luxurydaily.com/?p=204922&format=pdf /tmp/test.pdf";
		file_put_contents("/tmp/ld.log", "$cmd\r\n", FILE_APPEND);
		$result = shell_exec($cmd);
		*/

	//}


}

function ld16_get_post_meta($id, $name, $last_arg)
{

	$domain = (isset($_SERVER['HTTPS']) ? ($_SERVER['HTTPS'] == 'on' ? 'https://' : 'http://') : 'http://');

	$GLOBALS['server_name'] = $domain . htmlentities($_SERVER['SERVER_NAME']);

	$val = get_post_meta($id, $name, $last_arg);

	// $val = str_replace('http://www.luxurymarketer.com', $server_name, $val);
	// if($name == 'Image'){
	// 	$val = str_replace('www.luxurymarketer.com', 'cache.luxurymarketer.com', $val);
	// 	$val = str_replace('http://', 'https://', $val);
	// }

	return $val;
}

function ld16_get_video_thumbnail($field, $id = 0)
{

	if (!$id) {
		$id = get_the_ID();
	}

	$url = ld16_get_custom_field($field, true, $id);

	if (strpos($url, 'youtube') !== FALSE) {

		$step1 = explode("?", $url);

		$step2 = explode("/", $step1[0]);

		$id = end($step2);

		$thumb = "https://img.youtube.com/vi/$id/0.jpg";
	} elseif (strpos($url, 'facebook.com') !== FALSE) {

		$step1 = explode("%2F", $url);

		foreach ($step1 as $step) {

			if (is_numeric($step)) {
				$id = $step;

				$thumb = "https://graph.facebook.com/$id/picture";

				break;
			}
		}
	} else {

		$thumb = null;
	}

	return $thumb;
}

function ld16_get_image()
{

	$guid = ld16_get_custom_field('main_img');

	if (!$guid) {
		$id = get_the_ID();

		$media = get_attached_media('image');

		$width = 0;
		$guid = "";
		foreach ($media as $id => $img) {

			$new_guid = $img->guid;

			if ($new_guid) {

				//$new_guid = str_replace('www.luxurydaily.com', 'cache.luxurydaily.com', $new_guid);
				list($new_width, $height, $type, $attr) = getimagesize($new_guid);

				if ($new_width > $width && $new_width >= $height) {
					$guid = $new_guid;
					$width = $new_width;
				}
			}
		}

		if ($width < 186) {

			$new_guid = get_post_meta($id, 'Image', true);

			if ($new_guid) {

				//$new_guid = str_replace('www.luxurydaily.com', 'cache.luxurydaily.com', $new_guid);
				list($new_width, $height, $type, $attr) = getimagesize($new_guid);

				if ($new_width > $width && $new_width >= $height) {
					$guid = $new_guid;
					$width = $new_width;
				}
			}
		}

		if ($width < 186) {

			$content = get_the_content();

			preg_match_all('#(<img.*?>)#', $content, $images);


			if (count($images)) {
				foreach ($images[0] as $img) {

					preg_match('/src="([^"]*)"/i', $img, $src);

					if (count($src) == 2) {

						$new_guid = $src[1];

						if ($new_guid) {

							//$new_guid = str_replace('www.luxurydaily.com', 'cache.luxurydaily.com', $new_guid);
							list($new_width, $height, $type, $attr) = getimagesize($new_guid);
							if ($new_width > $width && $new_width >= $height) {
								$guid = $new_guid;
								$width = $new_width;
							}
						}
					}
				}
			}
		}
	}
	//print "GUID: $guid;";
	//die();

	// $guid = str_replace('www.', 'cache.', $guid);

	return $guid;
}

function ld16_get_author_url($id = 0)
{

	if (!$id) {
		$id = get_the_ID();
	}

	$guest_author_url = get_post_meta($id, 'byline_url', true);

	if (!$guest_author_url) {
		$guest_author_url = get_post_meta($id, 'guest_author_url', true);
	}

	if (!$guest_author_url) {
		return '/author/' . get_the_author_meta('nicename');
	} else {
		return $guest_author_url;
	}
}

function ld16_month()
{

	$month = date('M');
	switch ($month) {
		case "Mar":
			$month = "March";
			break;
		case "Apr":
			$month = "April";
			break;
		case "May":
			$month = "May";
			break;
		case "Jun":
			$month = "June";
			break;
		case "Jul":
			$month = "July";
			break;
		default:
			$month .= ".";
	}

	return $month;
}

function ld16_get_author_name($id = 0)
{

	if (!$id) {
		$id = get_the_ID();
	}

	$guest_author = get_post_meta($id, 'byline_name', true);

	if (!$guest_author) {

		$guest_author = get_post_meta($id, 'guest_author', true);
	}

	if (!$guest_author) {
		return get_the_author_meta('display_name');
	} else {
		return $guest_author;
	}
}

function ld16_logged_in()
{

	$logged_in = $_COOKIE['_QAS3247adjl'] ?? null;

	if ($logged_in) {

		return true;
	}

	return false;
}

function ld16_is_pdf()
{

	$output = false;

	if (array_key_exists('format', $_GET)) {

		if ($_GET['format'] == 'pdf') {

			$output = true;
		}
	}

	return $output;
}

function ld16_showkey($id = 0, $newsletter = false)
{

	if (ld16_is_pdf()) {

		return '';
	}

	if (!$id) {
		$id = get_the_ID();
	}

	$output = '';

	if ($newsletter) {
		$output = '<span style="color:#BCBCBC; font-size: 9px;">COMPLIMENTARY</span>';
	}

	$unlocked = get_post_meta($id, 'unlocked', true);

	if (!$unlocked && !ld16_logged_in()) {
		$url_root = ld16_cdn(get_template_directory_uri());
		$output = ld16_is_locked() == false ? '<img src="' . $url_root . '/img/ic_unlock.png"/>' : '';
	} else if($unlocked && !ld16_logged_in()){
		$url_root = ld16_cdn(get_template_directory_uri());
		$output = ld16_is_locked() == false ? '<img src="' . $url_root . '/img/ic_unlock.png"/>' : '';
	}

	return $output;
}

function ld16_is_locked($id = 0)
{

	if (!$id) {
		$id = get_the_ID();
	}

	$unlocked = (bool)get_post_meta($id, 'unlocked', true);
	$lock = true;

	if ($unlocked) {		
		$lock = false;
	} else {		
		$logged_in = $_COOKIE['_QAS3247adjl'] ?? null;
		// $logged_acctno = $_COOKIE['luxuryroundtable_acctno'];

		if ($logged_in) {
			$lock = false;
		}
	}

	return $lock;
}

function ld16_get_custom_field($key, $single = true, $id = 0)
{

	if (!$id) {
		$id = get_the_ID();
	}

	return get_post_meta($id, $key, $single);
}

function ld16_permalink($id = 0)
{
	if (!$id) {
		$id = get_the_ID();
	}

	$post = get_post($id);
	$slug = wp_unique_post_slug($post->post_name, $id, $post->post_status, $post->post_type, $post->post_parent);

	return "https://www.luxurymarketer.com/" . $slug . "/";
}


function ld16_get_file_root()
{
	return dirname(__FILE__);
}

function ld16_get_token($id)
{

	//md5(post_id, today, salt) + post_id
	$salt = "928374hsdafluEKSHF$*(E";
	$md5 = md5($id . date('Ymd') . $salt);

	$token = $md5 . $id;

	return $token;
}

function ld16_cdn($input)
{
	$output = $input;
	//$output = str_replace('www.luxurydaily.com', 'cache.luxurydaily.com', $input);
	// if (strpos($input, 'localhost') === 0) {
	// 	$output = str_replace('http://', 'https://', $output);
	// 	$output = str_replace('luxurymarketer.com', 'luxurymarketer', $output);
	// 	$output = str_replace('luxurymarketer', 'www.luxurymarketer', $output);
	// }
	return $output;
}

function ld16_encrypt($value, $passphrase)
{
	$salt = openssl_random_pseudo_bytes(8);
	$salted = '';
	$dx = '';
	while (strlen($salted) < 48) {
		$dx = md5($dx . $passphrase . $salt, true);
		$salted .= $dx;
	}
	$key = substr($salted, 0, 32);
	$iv  = substr($salted, 32, 16);
	$encrypted_data = openssl_encrypt(json_encode($value), 'aes-256-cbc', $key, true, $iv);
	$data = array("ct" => base64_encode($encrypted_data), "iv" => bin2hex($iv), "s" => bin2hex($salt));
	return json_encode($data);
}

function ld16_merge_stickies($oldest_post_date_stamp, $post_arr)
{

	global $wp_query, $post;
	rewind_posts();

	$post_arr_2 = array();

	$sticky_args['posts_per_page'] = 100;
	$sticky_args['meta_key'] = 'sticky';

	query_posts($sticky_args);

	$now_date_stamp = time();

	while (have_posts()) : the_post();

		$id = $post->ID;

		$sticky_date = get_post_meta($id, 'sticky', true);

		//Aug 24, 2017 @ 04:46
		//2016-10-25 04:30:13
		$sticky_date_arr = explode(' ', $sticky_date);

		$post_date = '';

		if (count($sticky_date_arr) == 5) {

			$year = $sticky_date_arr[2];

			$month = $sticky_date_arr[0];

			switch ($month) {
				case "Jan":
					$month = "01";
					break;
				case "Feb":
					$month = "02";
					break;
				case "Mar":
					$month = "03";
					break;
				case "Apr":
					$month = "04";
					break;
				case "May":
					$month = "05";
					break;
				case "Jun":
					$month = "06";
					break;
				case "Jul":
					$month = "07";
					break;
				case "Aug":
					$month = "08";
					break;
				case "Sep":
					$month = "09";
					break;
				case "Oct":
					$month = "10";
					break;
				case "Nov":
					$month = "11";
					break;
				case "Dec":
					$month = "12";
					break;
			}

			$day = $sticky_date_arr[1];

			$day = rtrim($day, ',');

			if (strlen($day == 1)) {

				$day = '0' . $day;
			}

			$time = $sticky_date_arr[4];

			$time_arr = explode(':', $time);

			if (count($time_arr) == 2) {

				$hour = $time_arr[0];

				$minute = $time_arr[1];

				$post_date = "$year-$month-$day $hour:$minute:00";

				$post_date_stamp = strtotime($post_date);

				if ($post_date_stamp >= $oldest_post_date_stamp && $post_date_stamp <= $now_date_stamp) {

					$key = $post_date . '_' . $id;

					$post_arr[$key] = $id;
				}
			}

			krsort($post_arr);
		}

	endwhile;

	foreach ($post_arr as $post_id) {

		$post_arr_2[] = $post_id;
	}

	return $post_arr_2;
}

function ld16_merge_posts()
{

	global $wp_query, $post;
	rewind_posts();

	$post_arr = array();

	$args = array('posts_per_page' => 16);

	query_posts($args);

	$oldest_post_date = '';

	while (have_posts()) : the_post();

		$post_date = $post->post_date;

		$id = $post->ID;

		$key = $post_date . '_' . $id;

		$post_arr[$key] = $id;

		$oldest_post_date = $post_date;

	endwhile;

	$oldest_post_date_stamp = strtotime($oldest_post_date);

	$post_arr_2 = ld16_merge_stickies($oldest_post_date_stamp, $post_arr);

	return $post_arr_2;
}

function ld16_pdf($id = 0)
{
	$url = "";

	if (!$id) {
		$id = get_the_ID();
	}

	$str = (string) $id;
	$cache_path = "";
	for ($i = 0; $i < strlen($str); $i++) {

		$chr = $str[$i];

		$cache_path .= "/$chr";

		if (!file_exists($cache_path)) {
			mkdir($cache_path);
		}
	}

	$filename = "$cache_path/$id.pdf";

	$url = 'https://www.luxurymarketer.com/wp-content/uploads/pdf_cache' . $filename;


	return $url;
}

function ld16_cat_id($id = 0)
{

	if (!$id) {
		$id = get_the_ID();
	}

	$category_id = ld16_get_post_meta($id, 'cat', true);

	if (!$category_id) {
		$category = get_the_category($id);
		$category_id = $category[0]->cat_ID;
	}

	return str_replace('https://www.luxurymarketer.com', 'https://luxurymarketer.com', $category_id);
}

function ld16_cat_name($id)
{

	if (!$id) {
		$id = get_the_ID();
	}

	$category_name = ld16_get_post_meta($id, 'catname', true);

	if (!$category_name) {
		$category = get_the_category($id);
		$category_name = $category[0]->cat_name;
	}

	return $category_name;
}

function ld16_get_the_excerpt()
{

	$output = get_the_excerpt();

	$start = substr($output, 0, 9);

	$start = str_replace(' ', '&nbsp;', $start);

	return $start . substr($output, 9);
}

function get_most_popular_post()
{
	$filecontent = file_get_contents('/tmp/most_popular_json.lr.cache');
	if (empty($filecontent)) {
		return null;
	}
	return json_decode($filecontent);
}

register_rest_field('post', 'cat_name', array(
	'get_callback' => function ($data) {
		return get_post_meta($data['id'], 'catname', true);
	},
));

register_rest_field('post', 'next', array(
	'get_callback' => function ($data) {
		$nextPost = get_adjacent_post(false, '', false);
		return !empty($nextPost) ? $nextPost : null;
	},
));


register_rest_field('post', 'previous', array(
	'get_callback' => function ($data) {
		$prevPost = get_adjacent_post(false, '', true);
		return !empty($prevPost) ? $prevPost : null;
	},
));

function rest_cat_id($id = 0)
{

	$category_url = ld16_cat_id($id);
	$category = explode('/', $category_url);
	if (end($category) == "")
		$category_slug = $category[count($category) - 2];
	else
		$category_slug = $category[count($category) - 1];
	$category_id = get_category_by_slug($category_slug)->cat_ID;
	return $category_id;
}
register_rest_field('post', 'cat_id', array(
	'get_callback' => function ($data) {
		return rest_cat_id($data['id'], 'cat_ID', true);
	},
));


register_rest_field('post', 'meta', array(
	'get_callback' => function ($data) {
		return get_post_meta($data['id']);
	},
));

register_rest_field('post', 'image', array(
	'get_callback' => function ($data) {
		return ld16_get_image($data['id']);
	},
));

register_rest_field('post', 'pdf', array(
	'get_callback' => function ($data) {
		return ld16_pdf($data['id']);
	},
));

function send_email($data)
{
	try {
		global $wpdb, $text_direction;

		$id =  (!empty($data['id'])	? intval($data['id']) : 0);
		$yourname = (!empty($data['your_name'])	? strip_tags(stripslashes(trim($data['your_name']))) : '');
		$youremail = (!empty($data['your_email'])	? strip_tags(stripslashes(trim($data['your_email']))) : '');
		$yourremarks = (!empty($data['your_remarks'])	? strip_tags(stripslashes(trim($data['your_remarks']))) : '');
		$friendname = (!empty($data['friend_name'])	? strip_tags(stripslashes(trim($data['friend_name']))) : '');
		$friendemail = (!empty($data['friend_email'])	? strip_tags(stripslashes(trim($data['friend_email']))) : '');

		if ($id > 0) {
			$post_type = get_post_type($id);
			$query_post = 'p=' . $id . '&post_type=' . $post_type;
		}

		query_posts($query_post);
		if (have_posts()) {
			while (have_posts()) {
				the_post();
				$post_title = email_get_title();
				$post_author = get_the_author();
				$post_date = get_the_time(get_option('date_format') . ' (' . get_option('time_format') . ')', '', '', false);
				$post_category = email_category(__(',', 'wp-email') . ' ');
				$post_category_alt = strip_tags($post_category);
				$post_excerpt = get_the_excerpt();
				$post_content = email_content();
				$post_content_alt = email_content_alt();
			}
		}

		// Error
		$error = array('status' => false);

		// Multiple Names/Emails
		$friends = array();
		$friendname_count = 0;
		$friendemail_count = 0;
		$multiple_names = explode(',', $friendname);
		$multiple_emails = explode(',', $friendemail);

		$multiple_max = intval(get_option('email_multiple'));
		if ($multiple_max == 0) {
			$multiple_max = 1;
		}

		$error = [];

		// Checking Your Name Field For Errors
		if (empty($yourname)) {
			$error['message'] = 'Your Name is empty';
		} else if (!is_valid_name($yourname)) {
			$error['message'] = 'Your Name is invalid';
		} else if (empty($youremail)) {
			$error['message'] = 'Your Email is empty';
		} else if (!is_valid_email($youremail)) {
			$error['message'] = 'Your Email is invalid';
		} else if (!is_valid_remarks($yourremarks)) {
			$error['message'] = 'Your Remarks is invalid';
		}
		if (empty($friendname)) {
			$error['message'] = 'Friend Name(s) is empty';
		} else if (count($multiple_names)) {
			foreach ($multiple_names as $multiple_name) {
				$multiple_name = trim($multiple_name);
				if (empty($multiple_name)) {
					$error['message'] = sprintf('Friend Name is empty: %s', $multiple_name);
				} elseif (!is_valid_name($multiple_name)) {
					$error['message'] = sprintf('Friend Name is invalid: %s', $multiple_name);
				} else {
					$friends[$friendname_count]['name'] = $multiple_name;
					$friendname_count++;
				}
				if ($friendname_count > $multiple_max) {
					break;
				}
			}
		}
		if (empty($error['message'])) {
			if (empty($friendemail)) {
				$error['message'] = 'Friend Email(s) is empty';
			} else if (count($multiple_emails)) {
				foreach ($multiple_emails as $multiple_email) {
					$multiple_email = trim($multiple_email);
					if (empty($multiple_email)) {
						$error['message'] = sprintf('Friend Email is empty: %s', $multiple_email);
					} elseif (!is_valid_email($multiple_email)) {
						$error['message'] = sprintf('Friend Email is invalid: %s', $multiple_email);
					} else {
						$friends[$friendemail_count]['email'] = $multiple_email;
						$friendemail_count++;
					}
					if ($friendemail_count > $multiple_max) {
						break;
					}
				}
			} else if (sizeof($friends) > $multiple_max) {
				$error['message'] = sprintf(_n('Maximum %s Friend allowed', 'Maximum %s Friend(s) allowed', $multiple_max, 'wp-email'), number_format_i18n($multiple_max));
			} else if ($friendname_count != $friendemail_count) {
				$error['message'] = 'Friend Name(s) count does not tally with Friend Email(s) count';
			}
		}


		// If There Is No Error, We Process The E-Mail
		if (!array_key_exists('message', $error)) {
			if (not_spamming()) {
				// If Remarks Is Empty, Assign N/A
				if (empty($yourremarks)) {
					$yourremarks = 'N/A';
				}

				// Template For E-Mail Subject
				$template_email_subject = stripslashes(get_option('email_template_subject'));
				$template_email_subject = str_replace("%EMAIL_YOUR_NAME%", $yourname, $template_email_subject);
				$template_email_subject = str_replace("%EMAIL_YOUR_EMAIL%", $youremail, $template_email_subject);
				$template_email_subject = str_replace("%EMAIL_POST_TITLE%", $post_title, $template_email_subject);
				$template_email_subject = str_replace("%EMAIL_POST_AUTHOR%", $post_author, $template_email_subject);
				$template_email_subject = str_replace("%EMAIL_POST_DATE%", $post_date, $template_email_subject);
				$template_email_subject = str_replace("%EMAIL_POST_CATEGORY%", $post_category_alt, $template_email_subject);
				$template_email_subject = str_replace("%EMAIL_BLOG_NAME%", get_bloginfo('name'), $template_email_subject);
				$template_email_subject = str_replace("%EMAIL_BLOG_URL%", get_bloginfo('url'), $template_email_subject);
				$template_email_subject = str_replace("%EMAIL_PERMALINK%", get_permalink(), $template_email_subject);

				// Template For E-Mail Body
				$template_email_body = stripslashes(get_option('email_template_body'));
				$template_email_body = str_replace("%EMAIL_YOUR_NAME%", $yourname, $template_email_body);
				$template_email_body = str_replace("%EMAIL_YOUR_EMAIL%", $youremail, $template_email_body);
				$template_email_body = str_replace("%EMAIL_YOUR_REMARKS%", $yourremarks, $template_email_body);
				$template_email_body = str_replace("%EMAIL_FRIEND_NAME%", $friendname, $template_email_body);
				$template_email_body = str_replace("%EMAIL_FRIEND_EMAIL%", $friendemail, $template_email_body);
				$template_email_body = str_replace("%EMAIL_POST_TITLE%", $post_title, $template_email_body);
				$template_email_body = str_replace("%EMAIL_POST_AUTHOR%", $post_author, $template_email_body);
				$template_email_body = str_replace("%EMAIL_POST_DATE%", $post_date, $template_email_body);
				$template_email_body = str_replace("%EMAIL_POST_CATEGORY%", $post_category, $template_email_body);
				$template_email_body = str_replace("%EMAIL_POST_EXCERPT%", $post_excerpt, $template_email_body);
				$template_email_body = str_replace("%EMAIL_POST_CONTENT%", $post_content, $template_email_body);
				$template_email_body = str_replace("%EMAIL_BLOG_NAME%", get_bloginfo('name'), $template_email_body);
				$template_email_body = str_replace("%EMAIL_BLOG_URL%", get_bloginfo('url'), $template_email_body);
				$template_email_body = str_replace("%EMAIL_PERMALINK%", get_permalink(), $template_email_body);

				if ('rtl' == $text_direction) {
					$template_email_body = "<div style=\"direction: rtl;\">$template_email_body</div>";
				}

				// Template For E-Mail Alternate Body
				$template_email_bodyalt = stripslashes(get_option('email_template_bodyalt'));
				$template_email_bodyalt = str_replace("%EMAIL_YOUR_NAME%", $yourname, $template_email_bodyalt);
				$template_email_bodyalt = str_replace("%EMAIL_YOUR_EMAIL%", $youremail, $template_email_bodyalt);
				$template_email_bodyalt = str_replace("%EMAIL_YOUR_REMARKS%", $yourremarks, $template_email_bodyalt);
				$template_email_bodyalt = str_replace("%EMAIL_FRIEND_NAME%", $friendname, $template_email_bodyalt);
				$template_email_bodyalt = str_replace("%EMAIL_FRIEND_EMAIL%", $friendemail, $template_email_bodyalt);
				$template_email_bodyalt = str_replace("%EMAIL_POST_TITLE%", $post_title, $template_email_bodyalt);
				$template_email_bodyalt = str_replace("%EMAIL_POST_AUTHOR%", $post_author, $template_email_bodyalt);
				$template_email_bodyalt = str_replace("%EMAIL_POST_DATE%", $post_date, $template_email_bodyalt);
				$template_email_bodyalt = str_replace("%EMAIL_POST_CATEGORY%", $post_category_alt, $template_email_bodyalt);
				$template_email_bodyalt = str_replace("%EMAIL_POST_EXCERPT%", $post_excerpt, $template_email_bodyalt);
				$template_email_bodyalt = str_replace("%EMAIL_POST_CONTENT%", $post_content_alt, $template_email_bodyalt);
				$template_email_bodyalt = str_replace("%EMAIL_BLOG_NAME%", get_bloginfo('name'), $template_email_bodyalt);
				$template_email_bodyalt = str_replace("%EMAIL_BLOG_URL%", get_bloginfo('url'), $template_email_bodyalt);
				$template_email_bodyalt = str_replace("%EMAIL_PERMALINK%", get_permalink(), $template_email_bodyalt);

				// PHP Mailer Variables
				if (!class_exists("phpmailer")) {
					require_once(ABSPATH . WPINC . '/class-phpmailer.php');
				}
				$mail = new PHPMailer();
				$mail->From     = "editor@luxurymarketer.com";
				$mail->FromName = $yourname;
				foreach ($friends as $friend) {
					$mail->AddAddress($friend['email'], $friend['name']);
				}
				$mail->CharSet = strtolower(get_bloginfo('charset'));
				$mail->Username = $email_smtp['username'];
				$mail->Password = $email_smtp['password'];
				$mail->Host     = $email_smtp['server'];
				$mail->Mailer   = get_option('email_mailer');
				if ($mail->Mailer == 'smtp') {
					$mail->SMTPAuth = true;
				}
				$mail->ContentType =  get_option('email_contenttype');
				$mail->Subject = $template_email_subject;
				if (get_option('email_contenttype') == 'text/plain') {
					$mail->Body    = $template_email_bodyalt;
				} else {
					$mail->Body    = $template_email_body;
					$mail->AltBody = $template_email_bodyalt;
				}
				// Send The Mail if($mail->Send()) {
				if ($mail->Send()) {
					$email_status = 'Success';
					// Template For Sent Successfully
					$template_email_sentsuccess = stripslashes(get_option('email_template_sentsuccess'));
					$template_email_sentsuccess = str_replace("%EMAIL_FRIEND_NAME%", $friendname, $template_email_sentsuccess);
					$template_email_sentsuccess = str_replace("%EMAIL_FRIEND_EMAIL%", $friendemail, $template_email_sentsuccess);
					$template_email_sentsuccess = str_replace("%EMAIL_POST_TITLE%", $post_title, $template_email_sentsuccess);
					$template_email_sentsuccess = str_replace("%EMAIL_BLOG_NAME%", get_bloginfo('name'), $template_email_sentsuccess);
					$template_email_sentsuccess = str_replace("%EMAIL_BLOG_URL%", get_bloginfo('url'), $template_email_sentsuccess);
					$template_email_sentsuccess = str_replace("%EMAIL_PERMALINK%", get_permalink(), $template_email_sentsuccess);
					// If There Is Error Sending
				} else {
					if ($yourremarks == 'N/A') {
						$yourremarks = '';
					}

					$email_status = 'Failed';

					// Template For Sent Failed
					$template_email_sentfailed = stripslashes(get_option('email_template_sentfailed'));
					$template_email_sentfailed = str_replace("%EMAIL_FRIEND_NAME%", $friendname, $template_email_sentfailed);
					$template_email_sentfailed = str_replace("%EMAIL_FRIEND_EMAIL%", $friendemail, $template_email_sentfailed);
					$template_email_sentfailed = str_replace("%EMAIL_ERROR_MSG%", $mail->ErrorInfo, $template_email_sentfailed);
					$template_email_sentfailed = str_replace("%EMAIL_POST_TITLE%", $post_title, $template_email_sentfailed);
					$template_email_sentfailed = str_replace("%EMAIL_BLOG_NAME%", get_bloginfo('name'), $template_email_sentfailed);
					$template_email_sentfailed = str_replace("%EMAIL_BLOG_URL%", get_bloginfo('url'), $template_email_sentfailed);
					$template_email_sentfailed = str_replace("%EMAIL_PERMALINK%", get_permalink(), $template_email_sentfailed);
				}

				// Logging
				$email_yourname = addslashes($yourname);
				$email_youremail = addslashes($youremail);
				$email_yourremarks = addslashes($yourremarks);
				$email_postid = intval(get_the_id());
				$email_posttitle = addslashes($post_title);
				$email_timestamp = current_time('timestamp');
				$email_ip = get_email_ipaddress();
				$email_host = esc_attr(@gethostbyaddr($email_ip));
				foreach ($friends as $friend) {
					$email_friendname = addslashes($friend['name']);
					$email_friendemail = addslashes($friend['email']);
					$wpdb->query("INSERT INTO $wpdb->email VALUES (0, '$email_yourname', '$email_youremail', '$email_yourremarks', '$email_friendname', '$email_friendemail', $email_postid, '$email_posttitle', '$email_timestamp', '$email_ip', '$email_host', '$email_status')");
				}
				if ($email_status == 'Success') {
					$output = $template_email_sentsuccess;
				} else {
					$output = $template_email_sentfailed;
				}

				$success = array('status' => true, 'message' => "Email Sent Successfully", 'data' => $output);
				return new WP_REST_Response($success, 200);
			} else {
				$error['message'] = sprintf('Please wait for %s Minute before sending the next article.', email_flood_interval(false));
				return new WP_REST_Response($error, 400);
			}
		} else {
			return new WP_REST_Response($error, 412);
		}
	} catch (\Throwable $th) {
		$error = array('status' => false, 'message' => $th->getMessage(), 'data' => null);
		return new WP_REST_Response($error, 500);
	}
}

function register_device($data)
{
	try {
		global $wpdb;

		$deviceId =  (!empty($data['device_id']) ? strip_tags(stripslashes(trim($data['device_id']))) : '');
		$deviceType =  (!empty($data['device_type']) ? strip_tags(stripslashes(trim($data['device_type']))) : '');
		$deviceToken =  (!empty($data['device_token']) ? strip_tags(stripslashes(trim($data['device_token']))) : '');

		$error = [];

		if (empty($deviceId)) {
			$error['message'] = 'device_id parameter not null or blank';
		} else if (empty($deviceType)) {
			$error['message'] = 'device_type parameter not null or blank';
		} else if (strcmp($deviceType, "Android") != 0 && strcmp($deviceType, 'iOS') != 0) {
			$error['message'] = 'device_type parameter value invalid';
		} else if (empty($deviceToken)) {
			$error['message'] = 'device_token parameter not null or blank';
		}

		if (!array_key_exists('message', $error)) {
			$getDeviceInfoQuery = "Select * From $wpdb->prefix" . "mobile_user Where device_id = '$deviceId'";
			$result = $wpdb->get_results($getDeviceInfoQuery);
			if (count($result) == 0) {
				$insertDeviceInfo = "INSERT INTO $wpdb->prefix" . "mobile_user (device_id, device_type, device_token) VALUES ('$deviceId', '$deviceType', '$deviceToken')";
				$result = $wpdb->query($insertDeviceInfo);
				if ($result) {
					$result = true;
				} else {
					$result = false;
				}
			} else {
				$updateDeviceInfo = "UPDATE $wpdb->prefix" . "mobile_user SET device_token = '$deviceToken' Where device_id = '$deviceId'";
				$result = $wpdb->query($updateDeviceInfo);
				$result = true;
			}

			if ($result) {
				$success = array('status' => true, 'message' => "Device register successfully");
				return new WP_REST_Response($success, 200);
			} else {
				$success = array('status' => false, 'message' => "Device register failed");
				return new WP_REST_Response($success, 400);
			}
		} else {
			return new WP_REST_Response($error, 412);
		}
	} catch (\Throwable $th) {
		$error = array('status' => false, 'message' => $th->getMessage(), 'data' => null);
		return new WP_REST_Response($error, 500);
	}
}

function subscribe_category($data)
{
	try {
		global $wpdb;

		$deviceId =  (!empty($data['device_id']) ? strip_tags(stripslashes(trim($data['device_id']))) : '');

		$error = [];

		if (empty($deviceId)) {
			$error['message'] = 'device_id parameter not null or blank';
		}

		if (isset($error) && array_key_exists('message', $error)) {
			return new WP_REST_Response($error, 412);
		} else {
			$getDeviceInfoQuery = "Select * From $wpdb->prefix" . "user_subscribe_cat Where device_id = '$deviceId'";
			$result = $wpdb->get_results($getDeviceInfoQuery);
			if (count($result) > 0) {
				$categoryList = array();
				foreach ($result as $cat) {
					$cat->id = (int)$cat->id;
					$cat->category_id = (int)$cat->category_id;
					array_push($categoryList, $cat);
				}
				$success = array('status' => true, 'message' => "Subscribe category found", 'data' => $categoryList);
				return new WP_REST_Response($success, 200);
			} else {
				$success = array('status' => true, 'message' => "No subscribe categories found", 'data' => null);
				return new WP_REST_Response($success, 200);
			}
		}
	} catch (\Throwable $ex) {
		$error = array('status' => false, 'message' => $ex->getMessage(), 'data' => null);
		return new WP_REST_Response($error, 500);
	}
}

function sub_cat_for_alert($data)
{
	try {
		global $wpdb;

		$deviceId =  (!empty($data['device_id']) ? strip_tags(stripslashes(trim($data['device_id']))) : '');
		$categoryId =  (!empty($data['category_id']) ? intval($data['category_id']) : 0);
		$isSubscribed =  $data['is_subscribe'];

		$error = [];

		if (empty($deviceId)) {
			$error['message'] = 'device_id parameter not null or blank';
		} else if ($categoryId == 0) {
			$error['message'] = 'category_id parameter is not 0 or blank';
		} else if (!isset($isSubscribed)) {
			$error['message'] = 'is_subscribe parameter not null or blank';
		}

		if (!array_key_exists('message', $error)) {
			if ($isSubscribed) {
				$insertSubCat = "INSERT INTO $wpdb->prefix" . "user_subscribe_cat (device_id, category_id) VALUES ('$deviceId', $categoryId)";
				$result = $wpdb->query($insertSubCat);
			} else {
				$deleteSubCat = "DELETE FROM $wpdb->prefix" . "user_subscribe_cat WHERE category_id = $categoryId and device_id = '$deviceId'";
				$result = $wpdb->query($deleteSubCat);
			}

			if ($result) {
				$success = array('status' => true, 'message' => ($isSubscribed ? "Subscribe " : "Unsubscribe ") . "category successfully");
				return new WP_REST_Response($success, 200);
			} else {
				$success = array('status' => false, 'message' => "Error geting " . ($isSubscribed ? "Subscribe " : "Unsubscribe ") . " category");
				return new WP_REST_Response($success, 400);
			}
		} else {
			return new WP_REST_Response($error, 412);
		}
	} catch (\Throwable $th) {
		$error = array('status' => false, 'message' => $th->getMessage(), 'data' => null);
		return new WP_REST_Response($error, 500);
	}
}

add_action('publish_post', 'send_push_notification');
function send_push_notification($data)
{
	try {

		global $wpdb;

		$postId =  (!empty($data['post_id']) ? strip_tags(stripslashes(trim($data['post_id']))) : '');
		
		if(!$postId) {
			$postId = get_the_ID();
		}

		$error = [];

		if (empty($postId)) {
			$error['message'] = 'post_id parameter not null or blank';
		}

		if (!array_key_exists('message', $error)) {

			$postDetail = get_post($postId);
			$categoryList = get_the_category($postId);
			$categoryIds = [];
			foreach ($categoryList as $key => $value) {
				array_push($categoryIds, $value->cat_ID);
			}

			$getDeviceIdQuery = "SELECT * FROM $wpdb->prefix" . "mobile_user as mu INNER JOIN $wpdb->prefix" . "user_subscribe_cat as usc ON mu.device_id = usc.device_id WHERE usc.category_id
IN (" . implode(',', $categoryIds) . ") GROUP BY mu.device_id";
			$deviceListResult = $wpdb->get_results($getDeviceIdQuery);
			$deviceTokens = [];
			foreach ($deviceListResult as $value) {
				array_push($deviceTokens, $value->device_token);
			}
			if (count($deviceTokens)) {
				$url = "https://fcm.googleapis.com/fcm/send";

				$fields = array(
					"registration_ids" => $deviceTokens,
					"notification" => array(
						"body" => $postDetail->post_excerpt,
						"title" => $postDetail->post_title,
						"sound" => "default"
					),
					"data" => array(
						"post_id" => $postDetail->ID,
						"click_action" => "FLUTTER_NOTIFICATION_CLICK",
						"status" => "done"
					),

				);

				$headers = array(
					'Authorization: key=AAAAiNKvFRI:APA91bEa8z5npqSPQYqrRT6pfIJEtsIlm_eHSmFAzFHwwhPDBL_hiotNqL-F2WXM2B1ROOJLg9qoQIEpH6gTELuFqqR19LaGG2INvDzmpMwjCAZ_qKswHLQiYIAoQMjBhYdKerdSA-Uo',
					'Content-Type:application/json'
				);

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
				$result = curl_exec($ch);
				curl_close($ch);

				$result = json_decode($result);

				
			if ($result) {
				$success = array('status' => true, 'message' => "Push sent successfully", 'data' => $result);
				return new WP_REST_Response($success, 200);
			} else {
				$success = array('status' => false, 'message' => "Push not sent");
				return new WP_REST_Response($success, 400);
			}
			
			} else {
				$success = array('status' => false, 'message' => "No devices founds");
				return new WP_REST_Response($success, 200);
			}
		} else {
			return new WP_REST_Response($error, 412);
		}
	} catch (\Throwable $th) {
		$error = array('status' => false, 'message' => $th->getMessage(), 'data' => null);
		return new WP_REST_Response($error, 500);
	}
}

function get_banner_posts($index = 1)
{
	
	$index = $index - 1;

	return get_posts(array(
		'post_type' => 'attachment',
		'post_mime_type' => 'image/jpeg,image/gif,image/jpg,image/png',
		'meta_query' => array(
			'relation'      => 'AND',
			array(
				'key'   => 'active-disable',
				'value' => '1',
				'compare'   => '=',
			),
		),
		'orderby' => 'modified',
		'offset' => $index,
		'posts_per_page' => 1,
	));
}

function get_banner_detail()
{
	try {

		$banners = get_banner_posts();

		if ($banners && count($banners) > 0) {
			$hyperlink = get_field('hyperlink',  $banners[0]->ID);
			$banner = array('ID' => $banners[0]->ID, 'post_title' => $banners[0]->post_title, 'guid' => $banners[0]->guid, 'hyperlink' => $hyperlink);

			$success = array('status' => true, 'message' => "Banners Found", 'data' => $banner);
			return new WP_REST_Response($success, 200);
		} else {
			$error = array('status' => false, 'message' => "Banners Not Found", 'data' => null);
			return new WP_REST_Response($error, 200);
		}
	} catch (\Throwable $th) {
		$error = array('status' => false, 'message' => $th->getMessage(), 'data' => null);
		return new WP_REST_Response($error, 500);
	}
}

add_action('rest_api_init', function () {
	register_rest_route('wp/v2', '/send-email/(?P<id>\d+)', array(
		'methods' => 'POST',
		'callback' => 'send_email',
		'permission_callback' => function () {
			return true;
		}
	));
});

add_action('rest_api_init', function () {
	register_rest_route('wp/v2', '/most-popular', array(
		'methods' => 'GET',
		'callback' => 'get_most_popular_post',
		'permission_callback' =>  function () {
			return true;
		}
	));
});

add_action('rest_api_init', function () {
	register_rest_route('wp/v2', '/register_device', array(
		'methods' => 'POST',
		'callback' => 'register_device',
		'args' => array(
			'device_id' => array(
				'required' => "true",
				'type' => 'string',
			),
			'device_type' => array(
				'required' => "true",
				'type' => 'string',
				'discription' => 'Device type should be Android/iOS',
				'enum' => array(
					0 => 'Android',
					1 => 'iOS'
				),
			),
			'device_token' => array(
				'required' => "true",
				'type' => 'string',
			),

		),
		'permission_callback' =>  function () {
			return true;
		}
	));
});

add_action('rest_api_init', function () {
	register_rest_route('wp/v2', '/subscribe_category', array(
		'methods' => 'GET',
		'callback' => 'subscribe_category',
		'args' => array(
			'device_id' => array(
				'required' => "true",
				'type' => 'string',
			),

		),
		'permission_callback' =>  function () {
			return true;
		}
	));
});

add_action('rest_api_init', function () {
	register_rest_route('wp/v2', '/subscribe_category', array(
		'methods' => 'POST',
		'callback' => 'sub_cat_for_alert',
		'args' => array(
			'device_id' => array(
				'required' => "true",
				'type' => 'string',
			),
			'category_id' => array(
				'required' => "true",
				'type' => 'integer',
			),
		),
		'permission_callback' =>  function () {
			return true;
		}
	));
});

add_action('rest_api_init', function () {
	register_rest_route('wp/v2', '/get_banner', array(
		'methods' => 'GET',
		'callback' => 'get_banner_detail',
		'permission_callback' =>  function () {
			return true;
		}
	));
});


add_action('rest_api_init', function () {
	register_rest_route('wp/v2', '/send_push', array(
		'methods' => 'POST',
		'callback' => 'send_push_notification',
		'args' => array(
			'post_id' => array(
				'required' => "ture",
				'type' => 'integer',
			),

		),
	));
});


function filter_rest_allow_anonymous_comments()
{
	return true;
}
add_filter('rest_allow_anonymous_comments', 'filter_rest_allow_anonymous_comments');

// Handle comment submissions via admin-ajax to bypass Cloudflare challenges
add_action('wp_ajax_submit_comment', 'lm_handle_comment_submission');
add_action('wp_ajax_nopriv_submit_comment', 'lm_handle_comment_submission');

function lm_handle_comment_submission() {
	// Verify request method
	if ('POST' !== $_SERVER['REQUEST_METHOD']) {
		wp_send_json_error(array('message' => 'Invalid request method'), 405);
		return;
	}

	// Handle comment submission using WordPress's built-in function
	$comment = wp_handle_comment_submission(wp_unslash($_POST));
	
	if (is_wp_error($comment)) {
		$data = $comment->get_error_data();
		$status = !empty($data) ? (int) $data : 400;
		wp_send_json_error(array(
			'message' => $comment->get_error_message(),
			'data' => $data
		), $status);
		return;
	}

	// Set comment cookies
	$user = wp_get_current_user();
	$cookies_consent = (isset($_POST['wp-comment-cookies-consent']));
	do_action('set_comment_cookies', $comment, $user, $cookies_consent);

	// Determine redirect location
	$location = empty($_POST['redirect_to']) ? get_comment_link($comment) : $_POST['redirect_to'] . '#comment-' . $comment->comment_ID;
	
	// If user didn't consent to cookies, add specific query arguments
	if (!$cookies_consent && 'unapproved' === wp_get_comment_status($comment) && !empty($comment->comment_author_email)) {
		$location = add_query_arg(
			array(
				'unapproved' => $comment->comment_ID,
				'moderation-hash' => wp_hash($comment->comment_date_gmt),
			),
			$location
		);
	}

	$location = apply_filters('comment_post_redirect', $location, $comment);

	// Return success response
	wp_send_json_success(array(
		'location' => $location,
		'comment_id' => $comment->comment_ID
	));
}

