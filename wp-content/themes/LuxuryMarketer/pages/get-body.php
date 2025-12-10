
<?php
/* Template Name: Get Body */

//error_reporting(E_ALL);
//ini_set('display_errors',1);

$request_uri = $_SERVER['REQUEST_URI'];
$request_arr = explode('/', $request_uri);

$token = get_query_var( 'token' );

//print "TOKEN: $token";

$id = substr($token, 32);

function make_token($id, $date){

  //must match salt in functions.php ld16_get_token()
  $salt = "928374hsdafluEKSHF$*(E";
	$md5 = md5($id . $date . $salt);

  return $md5;

}

$today_token = make_token($id, date('Ymd'));

$yesterday_token = make_token($id, date('Ymd', strtotime("yesterday")));

$this_token = substr($token, 0, 32);

/*
print "ft: $token<p>\r\n";
print "today t: $today_token<p>\r\n";
print "yt: $yesterday_token<p>\r\n";
print "this t: $this_token<p>\r\n";
print "id: $id<p>\r\n";
*/


/*
$array=array();

if($this_token == $today_token || $this_token == $yesterday_token){

  $array=array($id);

}


$args = array(
'numberposts'     => 1,
'offset'          => 0,
'orderby'         => 'ID',
'order'           => 'DESC',
'post_type'       => 'post',
'post_status' => array('publish', 'pending', 'future', 'inherit'),
'post__in' => $array );

$posts = get_posts($args);

*/

$search_id = 0;
if($this_token == $today_token || $this_token == $yesterday_token){

  $search_id = $id;

}

$querystr = "
   SELECT $wpdb->posts.*
   FROM $wpdb->posts
   WHERE $wpdb->posts.ID = $search_id
";

$posts = $wpdb->get_results($querystr, OBJECT);

if(count($posts)){

  print "<html><body>";
}

foreach ($posts as $post) : setup_postdata( $post );

  $content = get_the_content();
  $content = '<p>' . str_replace("\r\n", '</p><p>', $content) . '</p>';
  $content = str_replace("</strong></p><p>", '</strong><br />', $content);
  $content = str_replace('src="https://www.luxurydaily.com', 'src="https://cache.luxurydaily.com', $content);

  preg_match_all('#(\[caption.*?\[\/caption\])#', $content, $captions);

  if (count($captions)) {
    foreach($captions[0] as $key => $caption){

      $stripped_caption = str_replace("[/caption]", "", $caption);

      $stripped_caption_arr = explode("]", $stripped_caption);

      if (count($stripped_caption_arr) > 1) {

        if($key == 0){

          $content = str_replace($caption, "", $content);

        } else {

          $content = str_replace($caption, '<p class="caption"><font color="gray">'.$stripped_caption_arr[1].'</font></p>', $content);

        }

      } else {
        $content = str_replace($caption, "", $content);
      }


    }
  }

  $paragraphAfter= 5; //shows the ad after paragraph 1

  $content = explode("</p>", $content);
  for ($i = 0; $i <count($content); $i++) {

    echo $content[$i] . "</p>";

  }

endforeach;

if(count($posts)){

  print "</body></html>";
}

?>
