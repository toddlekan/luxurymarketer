<?php

/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */

$file_root = ld16_get_file_root();
$url_root = ld16_cdn(get_template_directory_uri());

get_header();

$main_left_col = '5';
$main_right_col = '7';

?>
<style>
	.headline-list.main ul li a.img-container,
	.headline-list.main ul li a.img-container:hover {
		width: 270px;
	}

	.extra-headline {
		display: none;
	}
</style>
<?php
if (have_posts()) {

	//error_reporting(E_ALL);
	//ini_set('display_errors', '1');

	$post_arr_2 = ld16_merge_posts();

?>


	<div id="homePage" class="home-page slides">

		<!-- DESKTOP HERO START -->

		<?php
		//hero
		$hero_args['posts_per_page'] = 5;
		$hero_args['offset'] = 0;
		$hero_args['meta_key'] = 'hero_img';

		query_posts($hero_args);
		$post_id = 1;
		while (have_posts()) : the_post();

		?>

			<section id=<?= "hero-section-" . $post_id ?> class="section main photos clearfix desktop">

				<div class="row">

					<div class="col-lg-12 hero-frame" id=<?= "hero-frame-" . $post_id ?>>

						<img src="<?= ld16_get_custom_field('hero_img') ?>" class="hero-frame-img-anim" />
						<div class="scroll">
							<a href=<?= "#hero-section-" . ($post_id + 1) ?>><span></span>Scroll</a>
						</div>
						<div class="hero-text container">
							<?php if ($post_id == 1) { ?>
								<div class="first">
									Luxury Roundtable Presents
								</div>
							<?php } else {
							?>
								<div class="categories">
									<a  href="<?= ld16_cat_id(get_the_id()) ?>">
										<?= ld16_cat_name(get_the_id()) ?>
									</a>
								</div>
							<?php } ?>
							<div class="title">
								<?= the_title() ?> <?= ld16_showkey(get_the_id()) ?>
							</div>
							<!--div class="excerpt">
								<?= the_excerpt() ?>
							</div-->
							<a href="<?= the_permalink() ?>">DISCOVER NOW</a>
						</div>

					</div><!-- col-lg-12 -->

				</div> <!-- row -->

			</section> <!-- section -->

		<?php
			$post_id += 1;
		endwhile; //hero
		?>

		<!-- DESKTOP HERO END -->

		<!-- MOBILE TOP START -->
		<section id=<?= "hero-section-" . $post_id ?> class="section clearfix categories mobile">

			<div class="row">

				<div class="col-lg-8">

					<?php

					$post = get_post($post_arr_2[0]);
					setup_postdata($post);
					get_template_part('template-parts/content', 'mobile-item-first');

					for ($i = 1; $i < 5; $i++) {

						$post = get_post($post_arr_2[$i]);
						setup_postdata($post);
						get_template_part('template-parts/content', 'newswell-item');
					};

					?>

				</div>

			</div>
		</section>

		<!-- MOBILE TOP END -->

		<?php $post_id += 1; ?>

		<section id=<?= "hero-section-" . $post_id ?> class="section clearfix categories ">

			<div class="row">

				<div class="col-lg-12">

					<div class="newswell-divider section divider"></div>

					<div class="newswell">
					<?php
					//newswell
					$newswell_count = 0;
					$divider_set = false;
					if (count($post_arr_2) >= 5) {
						for ($i = 5; $i < 11; $i++) {

							$post = get_post($post_arr_2[$i]);
							setup_postdata($post);
							get_template_part('template-parts/content', 'lead-story');

							$newswell_count++;

							if ($newswell_count == 3) {
								$newswell_count = 0;

								if ($divider_set) {

									print "<div style='clear:both;'></div>";

								} else {
									$divider_set = true;
									print "<div class='newswell-divider section divider' style='clear:both;'></div>";
								}

							}
						} //next 12
					}
					?>

					</div>
					<?php
					if (count($post_arr_2) >= 5) { ?>
					<footer class="thicken container">
						<div class="row copyright">
							<div class="pull-right">
								<a class="more reverse" href="/more-stories"><span class="gt-label">More Stories</span> <span class="glyphicon glyphicon-menu-right gt-one"></span><span class="glyphicon glyphicon-menu-right gt-two"></span></a>
							</div>
					</div>
					</footer>
					<?php } ?>
					<br />
					<br />
				</div>
			</div>
		</section>

	</div> <!--home-page-->

<?php
} else {

	get_template_part('template-parts/content', 'none');
}


get_footer(); ?>