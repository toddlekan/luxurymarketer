<?php
/**
 * The template for displaying pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other "pages" on your WordPress site will use a different template.
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */

$file_root=ld16_get_file_root();
$url_root=ld16_cdn(get_template_directory_uri());


get_header();

?>

<div class="section clearfix main galleries">

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
