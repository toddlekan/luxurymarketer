<?php
/*
Template Name: Luxury Mobile Summit
*/

//error_reporting(E_ALL);
//ini_set('display_errors', 1);

$file_root=ld16_get_file_root();
$url_root=ld16_cdn(get_template_directory_uri());


get_header();

?>

<div  class="section clearfix main galleries">

    <div class="row text">

		<div class="col-lg-8">

			<div class="col-lg-12">
				<h1 class="sector category">
	                <?the_title();?>
	            </h1>
            </div>

			<div class="col-lg-12">


			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				<div class="entry-content">

					<?php

						while ( have_posts() ) : the_post();

							the_content();

						endwhile;

					?>


					<?php

					//get url
					$url = $_SERVER["REQUEST_URI"];

					//cut off hooks
					$url_arr = explode('?', $url);
					$url = $url_arr[0];

					//cut off slash
					$url = trim($url, '/');

					//get last url part
					$url_arr = explode('/', $url);
					$last_url_part = $url_arr[count($url_arr) -1];

					$page = $last_url_part;

					$offset = 0;
					$number = 30;
					$prev = 2;
					$next = 0;

					//if numeric, apply offset
					if(!is_numeric($page) || !$page){
						$page = 1;
					}

					$offset = $number * ($page - 1);

					$prev = $page + 1;
					$next = $page - 1;

					//wp_list_pages("offset=$offset&number=$number&child_of=89&link_after=<br /><br />&sort_column=post_date&sort_order=desc&title_li=");

					$query = "SELECT p.ID, p.post_title, p.post_name
					FROM wp_posts AS p
					JOIN wp_term_relationships AS tr ON p.id = tr.object_id
					JOIN wp_term_taxonomy AS tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
					JOIN wp_terms AS t ON tt.term_id = t.term_id
					WHERE t.name like 'Mobile Insights Summit%' AND p.post_type LIKE 'post'
					GROUP BY p.id
					ORDER BY p.post_date DESC
					LIMIT $offset, $number
					";

					$results = $wpdb->get_results($query);

			    foreach( $results as $result ) {

						 print '<li class="page_item page-item-';
 						 print $result->ID;
 						 print '"><a href="/';
 						 print $result->post_name;
 						 print '">';
 						 print $result->post_title;
 						 print '<br><br></a></li>';


			    }


					?>




					<br />
					<div class="navigation clearfix">

						<div class="previousnav">
							<a href="/luxury-mobile-summit/<?=$prev?>" rel="prev">« Previous archives</a>

						</div>
						<div class="nextnav">

							<?php if($next){?>
								<a href="/luxury-mobile-summit/<?=$next?>" rel="next">Next archives »</a>
							<?php } ?>

						</div>
					</div>

				</div>

			</article>

			</div>
        </div>

        <div class="col-lg-4 headline-list sidebar">

            <?php
            $ad_page = 'article';
			include ($file_root.'/inc/sidebar_1.php');

            ?>


        </div>
    </div>
</div>

<?get_footer(); ?>
