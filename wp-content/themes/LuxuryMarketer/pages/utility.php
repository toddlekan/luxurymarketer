<?/*
 Template Name: Utility
 */
die();
error_reporting(E_ALL);
ini_set('display_errors',1);

ini_set('memory_limit','999990999999999M');
set_time_limit ( 0 );


/*if(!$_GET['lyck']){
  die();
}*/

$args = array(
    'orderby' => 'post_title',
    'posts_per_page' => -1,
    'date_query' => array(
        array(
            // 'before'    => 'December 31st, 2016',
            'inclusive' => true,
        ),
    ),
);
$the_query = new WP_Query( $args );

$count = 0;

$post_arr = [];

if ( $the_query->have_posts() ) {

  while ( $the_query->have_posts() ) {
	  $count++;
    $the_query->the_post();
    $id = get_the_ID();
    $title = get_the_title();
    if(!in_array($title, $post_arr)){
      $post_arr[] = $title;
    } else {

      $my_post = array(
          'ID'           => $id,
          'post_status' => 'draft',
      );

      wp_update_post( $my_post );
      file_put_contents('/tmp/save_debug.txt', $id . ': ' . $count . "\r\n", FILE_APPEND);
    }

	}

	/* Restore original Post Data */
	wp_reset_postdata();
} else {
	// no posts found
}






print "DONE";



?>
