<?php
/**
 * Template for displaying email form pages
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */

$file_root = dirname(__FILE__);
$url_root = ld16_cdn(get_template_directory_uri());

get_header();

if (have_posts()) :

?>

	<div class="section clearfix main article">

		<div class="row text">

			<div class="col-lg-1">

				<a class="comment-button" href="#"><img style="width: 36px; margin: 0 27px;" src="<?= $url_root ?>/img/comment-75-light.png" /></a>
				<a href="#" class="comment-link">Comment</a>
			</div>


			<?php
			// Ensure the email filter is added before the loop starts
			global $emailfilters_count;
			$emailfilters_count = 0;
			if (function_exists('email_addfilters')) {
				email_addfilters();
			}
			
			while (have_posts()) : the_post();

			?>

				<div class="col-lg-6">

					<ul class="tools clr smallest top">
						<li class="emailTool"><a href="<?php the_permalink() ?>/?email=1">Email</a> </li>
						<li class="printTool">
							<a href="<?php the_permalink() ?>/?print=1">Print</a>
						</li>

					</ul>
					<br style="clear: both;" />

					<p class="sector category">
						<font color=""><a class="smallest lighter-grey smallest" href="<?= get_post_meta($post->ID, 'cat', true) ?>"><?= get_post_meta($post->ID, 'catname', true) ?></a></font>
					</p>

					<h1><a href="<?php the_permalink() ?>" class="reverse "><?php the_title() ?> <?= ld16_showkey() ?></a></h1>

					<p class="date">
						<font color="gray"><?php the_time('F j, Y') ?></font>
					</p>

					<a class="image" href="<?= the_permalink() ?>"><img class="alignnone size-full wp-image-152895" src="<?= ld16_get_post_meta($post->ID, 'Image', true); ?>"> </a>

					<p class="caption main">
						<font color="gray">
							<?php

							$id = $post->ID;

							$category = get_post_meta($id, 'catname', true);
							$category_id = get_cat_ID($category);
							$category_link = get_category_link($category_id);

							?>
						</font>
					</p>

					<div class="divider">&nbsp;</div>

					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

						<div class="entry-content">
							<?php 
							// Call email_form directly to display the form
							if (function_exists('email_form')) {
								// Call with: content='', echo=false (so it returns), subtitle=true, div=true, error_field=''
								// Then echo the returned value
								$form_output = email_form('', false, true, true, '');
								if (empty($form_output)) {
									echo 'DEBUG: email_form returned empty';
								} else {
									echo "DEBUG: email_form returned not empty";
									echo $form_output;
									echo "DEBUG: email_form output end";
								}
							} else {
								the_content();
							}
							?>
						</div>

					</article>

				</div>

			<?php endwhile; ?>

		</div>

	</div>

<?php
endif;

get_footer();

