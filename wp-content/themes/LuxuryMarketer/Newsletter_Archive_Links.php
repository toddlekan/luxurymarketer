<?php
/*
Template Name: Newsletter Archive Links
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
	                <?php the_title();?>
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

					wp_list_pages("offset=$offset&number=$number&child_of=89&link_after=<br /><br />&sort_column=post_date&sort_order=desc&title_li="); ?>

					<br />
					<div class="navigation clearfix">

						<div class="previousnav">
							<a href="/newsletter-archive/<?=$prev?>" rel="prev">« Previous archives</a>

						</div>
						<div class="nextnav">

							<?php if($next){?>
								<a href="/newsletter-archive/<?=$next?>" rel="next">Next archives »</a>
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

<?php get_footer(); ?>
