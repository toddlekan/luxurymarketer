<?php
require( 'wp-load.php' );

error_reporting(E_ALL);
ini_set('display_errors',1);

ini_set('memory_limit', '-1');

set_time_limit(0);

$querystr = "
   SELECT $wpdb->posts.*
   FROM $wpdb->posts
   WHERE
   $wpdb->posts.post_type in ('post', 'page')
   AND $wpdb->posts.post_status in ('publish')
   ORDER BY $wpdb->posts.ID DESC
";

$postsForSitemap = $wpdb->get_results($querystr, OBJECT);

$sitemap = '<?xml version="1.0" encoding="UTF-8"?>'."\r\n";
$sitemap .= '<urlset xmlns="http://www.google.com/schemas/sitemap/0.90">'."\r\n";

foreach( $postsForSitemap as $post ) {
   setup_postdata( $post);

   //parse sitemap
   $sitemap .= "<url>\r\n";
   $sitemap .= "<loc>".get_permalink($post->ID)."</loc>\r\n";
   //2017-03-27T23:55:42+01:00
   $sitemap .= "<lastmod>".date('Y-m-d').'T'.date('H:i:s')."+00:00</lastmod>\r\n";
   $sitemap .= "<changefreq>daily</changefreq>\r\n";
   $sitemap .= "<priority>0.5</priority>\r\n";
   $sitemap .= "</url>\r\n";

}

$sitemap .= "</urlset>";

//overwrite sitemap
file_put_contents('/mnt/www/luxurydaily/sitemap.xml', $sitemap);

if ( function_exists( 'rocket_clean_domain' ) ) {
    rocket_clean_domain();
}
