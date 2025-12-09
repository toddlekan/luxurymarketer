<?
/* Template Name: Site Map */

error_reporting(E_ALL);
ini_set('display_errors',1);

ini_set('memory_limit', '-1');

if ( str_replace( '-', '', get_option( 'gmt_offset' ) ) < 10 ) {
    $tempo = '-0' . str_replace( '-', '', get_option( 'gmt_offset' ) );
} else {
    $tempo = get_option( 'gmt_offset' );
}
if( strlen( $tempo ) == 3 ) { $tempo = $tempo . ':00'; }

$querystr = "
   SELECT $wpdb->posts.*
   FROM $wpdb->posts
   WHERE
   $wpdb->posts.post_type in ('post', 'page')
   AND $wpdb->posts.post_status in ('publish')
   ORDER BY $wpdb->posts.ID DESC
";

$postsForSitemap = $wpdb->get_results($querystr, OBJECT);

$sitemap = "";
$sitemap .= '<?xml version="1.0" encoding="UTF-8"?>' . '<?xml-stylesheet type="text/xsl" href="' . esc_url( home_url( '/' ) ) . 'sitemap.xsl"?>';
$sitemap .= "\n" . '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
$sitemap .= "\t" . '<url>' . "\n" .
    "\t\t" . '<loc>' . esc_url( home_url( '/' ) ) . '</loc>' .
    "\n\t\t" . '<lastmod>' . date( "Y-m-d\TH:i:s", current_time( 'timestamp', 0 ) ) . $tempo . '</lastmod>' .
    "\n\t\t" . '<changefreq>daily</changefreq>' .
    "\n\t\t" . '<priority>1.0</priority>' .
    "\n\t" . '</url>' . "\n";
foreach( $postsForSitemap as $post ) {
    setup_postdata( $post);
    $postdate = explode( " ", $post->post_modified );
    $sitemap .= "\t" . '<url>' . "\n" .
        "\t\t" . '<loc>' . get_permalink( $post->ID ) . '</loc>' .
        "\n\t\t" . '<lastmod>' . $postdate[0] . 'T' . $postdate[1] . $tempo . '</lastmod>' .
        "\n\t\t" . '<changefreq>Weekly</changefreq>' .
        "\n\t\t" . '<priority>0.5</priority>' .
        "\n\t" . '</url>' . "\n";

    if($post -> post_type == 'post'){
      $sitemap .= "\t" . '<url>' . "\n" .
          "\t\t" . '<loc>https://www.luxurydaily.com/get-body/token/' . ld16_get_token($post -> ID) . '</loc>' .
          "\n\t\t" . '<lastmod>' . $postdate[0] . 'T' . $postdate[1] . $tempo . '</lastmod>' .
          "\n\t\t" . '<changefreq>Weekly</changefreq>' .
          "\n\t\t" . '<priority>0.5</priority>' .
          "\n\t" . '</url>' . "\n";
    }

}
$sitemap .= '</urlset>';

print $sitemap;
