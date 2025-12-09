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
	.headline-list.main ul li img {
		max-width: 270px;
	}

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


	<div class="home-page">










		<!-- DESKTOP HERO START -->

		<?php
		//hero
		$hero_args['posts_per_page'] = 1;
		$hero_args['offset'] = 0;
		$hero_args['meta_key'] = 'hero_img';

		query_posts($hero_args);

		while (have_posts()) : the_post();

		?>

			<style>
				#hero-frame {
					display: none;

					border: 1px solid #fff;
					border-width: 10px 56px 0 29px;
					height: 400px;
					overflow: hidden;
					padding-left: 0;
					padding-right: 0;
					background-color: #000;
				}

				#hero-frame img {

					opacity: 0.7;
					position: absolute;
					width: 200%;

					animation: shift 5s forwards;

				}

				@keyframes shift {
					0% {
						width: 200%;
					}

					100% {
						width: 100%;
					}
				}

				.hero-text {
					text-align: left;
					position: absolute;
					top: 60%;
					left: 75%;
					transform: translate(-50%, -50%);
					color: white;

				}

				.hero-text .first {
					text-align: left;
					position: relative;
					font-size: 30px;
				}

				.hero-text .second {
					opacity: 0;
					animation: fadeIn 5s forwards;
					font-size: 17px;
					text-align: left;
					position: relative;
					margin-top: 10px;
				}

				@keyframes fadeIn {
					0% {
						opacity: 0;
					}

					10% {
						opacity: .10;
					}

					20% {
						opacity: .20;
					}

					30% {
						opacity: .30;
					}

					40% {
						opacity: .40;
					}

					50% {
						opacity: .50;
					}

					60% {
						opacity: .60;
					}

					70% {
						opacity: .70;
					}

					80% {
						opacity: .80;
					}

					90% {
						opacity: .90;
					}

					100% {
						opacity: 1;
					}
				}

				.hero-text .second a {
					color: #fff;
					text-decoration: underline;
				}
			</style>

			<script>
				var image = new Image();
				image.onload = function() {
					document.getElementById('hero-frame').style.display = 'block'
				};
				image.src = '<?= ld16_get_custom_field('hero_img') ?>';
			</script>

			<div class="section main photos clearfix desktop">

				<div class="row">

					<div class="col-lg-12" id="hero-frame">

						<img src="<?= ld16_get_custom_field('hero_img') ?>" />

						<div class="hero-text">
							<div class="first">
								Leadership in Luxury
							</div>
							<div class="second">Become a more authoritative, connected and informed
								luxury professional – <a href="https://join.luxuryroundtable.com/LXR/?f=paid">join Luxury Roundtable now</a> and access your benefits. Make us your luxury program.</div>
						</div>

					</div><!-- col-lg-12 -->

				</div> <!-- row -->

			</div> <!-- section -->

		<?php endwhile; //hero
		?>

		<!-- DESKTOP HERO END -->






























		<!-- DESKTOP TOP START -->

		<div class="section main photos clearfix desktop" style="margin-bottom: 20px;">

			<div class="row text">

				<div class="col-lg-12">

					<div class="col-lg-<?= $main_left_col ?> lead-story">

						<?php
						//main gallery
						$gallery_args['posts_per_page'] = 1;
						$gallery_args['offset'] = 0;
						query_posts($gallery_args);

						while (have_posts()) : the_post();

						?>

							<a class="img-container main" href="<?php the_permalink() ?>"><img src="<?= ld16_get_image() ?>"></a>

							<!--div class="caption">Longwear Nail Duo: How to achieve a perfect long lasting manicure – Chanel</div-->

							<p class="sector">
								<a style="color: #999;" href="<?= ld16_cat_id($post->ID) ?>">
									<?= ld16_cat_name($post->ID) ?>
								</a>
							</p>

							<h1><a href="<?php the_permalink() ?>" class="reverse"><?= the_title() ?><?= ld16_showkey() ?></a></h1>

							<div class="blurb">
								<?php the_excerpt(); ?>
							</div>

						<?php endwhile; //main gallery
						?>

					</div>

					<div class="col-lg-<?= $main_right_col ?>">

						<div class="headline-list main col-lg-6">
							<ul>

								<?php
								//side galleries
								$gallery_args['posts_per_page'] = 2;
								$gallery_args['offset'] = 1;
								query_posts($gallery_args);

								while (have_posts()) : the_post();

									get_template_part('template-parts/content', 'gallery-item');

								endwhile; //side galleries

								?>

							</ul>

						</div>

						<div class="headline-list main col-lg-6">
							<ul>

								<?php
								//side galleries
								$gallery_args['posts_per_page'] = 2;
								$gallery_args['offset'] = 3;
								query_posts($gallery_args);

								while (have_posts()) : the_post();

									get_template_part('template-parts/content', 'gallery-item');

								endwhile; //side galleries

								?>

							</ul>

						</div>


					</div>

				</div>


			</div>

		</div>
		<!-- DESKTOP TOP END -->

		<!-- MOBILE TOP START -->
		<div class="section clearfix categories mobile">

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
		</div>

		<!-- MOBILE TOP END -->



		<div class="section clearfix categories ">

			<div class="row">

				<div class="col-lg-8">

					<?php
					//newswell
					$newswell_count = 0;
					if (count($post_arr_2) >= 5) {
						for ($i = 5; $i < 11; $i++) {

							$post = get_post($post_arr_2[$i]);
							setup_postdata($post);
							get_template_part('template-parts/content', 'newswell-item');

							$newswell_count++;

							if ($newswell_count == 3) {
								$newswell_count = 0;
								print "<div style='clear:both;'></div>";
							}
						} //next 12
					}
					?>

				</div>

				<div class="col-lg-4 headline-list sidebar">



					<div class="ad large rectangle ad-1">

						<div id='large-rectangle-1-home' style='height:280px; width:336px;'>
							<?php $banners = get_banner_posts(1);

							if ($banners && count($banners) > 0) {
							?>
								<a href="<?= get_field('hyperlink', $banners[0]->ID) ?: "#" ?>" target="_blank" alt="<?= $banners[0]->post_title ?: "" ?>">
									<img src=<?= $banners[0]->guid ?> width="">
								</a>
							<?php }

							?>

						</div>
					</div>

					<div style="clear: both;"></div>

					<div class="heading">
						MOST READ
					</div>

					<ol class="thicken most-popular">

						<?php include('/tmp/most_popular_2016.lr.cache'); ?>

					</ol>
					<br />
					<div style="clear: both;"></div>

					<!-- <div class="ad large rectangle ad-2">

						<div id='am-large-rectangle-2-home' style='height:280px; width:336px;'>
							<?php // $banners = get_banner_posts(2);

							//if ($banners && count($banners) > 0) {
							?>
								<a href="<?= get_field('hyperlink', $banners[0]->ID) ?: "#" ?>" target="_blank" alt="<?= $banners[0]->post_title ?: "" ?>">
									<img src=<?= $banners[0]->guid ?> width="">
								</a>
							<?php //}

							?>
						</div>
					</div>

					<div style="clear: both;"></div> -->

					<?php include($file_root . '/inc/sidebar/elsewhere.php') ?>

					<div style="clear: both;"></div>

					<!--
			<div class="sidebar-videos">
			  <div class="heading"> <a href="/videos">VIDEOS</a></div>
			  <ul class="thicken">
			    <li> <a class="reverse key" href="/videos?id=255968&amp;num=1">Audi raises the stakes for test drives in experiential campaign</a></li>
			    <li> <a class="reverse key" href="/videos?id=255926&amp;num=1">New York debates new luxury vibe: uptown or downtown?</a></li>
			    <li> <a class="reverse key" href="/videos?id=255926&amp;num=2">New York debates new luxury vibe: uptown or downtown?</a></li>
			    <li> <a class="reverse free" href="/videos?id=255953&amp;num=1">Happy Thanksgiving!</a></li>
			    <li> <a class="reverse key" href="/videos?id=255901&amp;num=1">Lexus turns to AI writer for innovative film</a></li>
			  </ul><a class="more reverse" href="/videos"><span class="gt-label">More Videos</span> <span class="glyphicon glyphicon-menu-right gt-one"></span><span class="glyphicon glyphicon-menu-right gt-two"></span></a></div>
			-->
				</div>


			</div>
		</div>
		<div style="clear: both; margin-top:20px;" class="section categories"></div>
		<div class="section main photos clearfix desktop" style="margin-bottom: 20px;">

			<div class="row text">

				<div class="col-lg-12">

					<div class="col-lg-<?= $main_left_col ?> lead-story">

						<?php
						//main gallery
						$gallery_args['posts_per_page'] = 1;
						$gallery_args['offset'] = 11;
						query_posts($gallery_args);

						while (have_posts()) : the_post();

						?>

							<a class="img-container main" href="<?php the_permalink() ?>"><img src="<?= ld16_get_image() ?>"></a>

							<!--div class="caption">Longwear Nail Duo: How to achieve a perfect long lasting manicure – Chanel</div-->

							<p class="sector">
								<a style="color: #999;" href="<?= ld16_cat_id($post->ID) ?>">
									<?= ld16_cat_name($post->ID) ?>
								</a>
							</p>

							<h1><a href="<?php the_permalink() ?>" class="reverse"><?= the_title() ?><?= ld16_showkey() ?></a></h1>

							<div class="blurb">
								<?php the_excerpt(); ?>
							</div>

						<?php endwhile; //main gallery
						?>

					</div>

					<div class="col-lg-<?= $main_right_col ?>">

						<div class="headline-list main col-lg-6">
							<ul>

								<?php
								//side galleries
								$gallery_args['posts_per_page'] = 2;
								$gallery_args['offset'] = 12;
								query_posts($gallery_args);

								while (have_posts()) : the_post();

									get_template_part('template-parts/content', 'gallery-item');

								endwhile; //side galleries

								?>

							</ul>

						</div>

						<div class="headline-list main col-lg-6">
							<ul>

								<?php
								//side galleries
								$gallery_args['posts_per_page'] = 2;
								$gallery_args['offset'] = 14;
								query_posts($gallery_args);

								while (have_posts()) : the_post();

									get_template_part('template-parts/content', 'gallery-item');

								endwhile; //side galleries

								?>

							</ul>

						</div>


					</div>
					<?php
					if (count($post_arr_2) >= 5) { ?>
						<a class="more reverse" href="/more-stories"><span class="gt-label">More Stories</span> <span class="glyphicon glyphicon-menu-right gt-one"></span><span class="glyphicon glyphicon-menu-right gt-two"></span></a>
					<?php } ?>
					<br />
					<br />
				</div>

				<!-- <div class="row footer-nav">
					<div class="col-lg-12">

						<ul class="list-unstyled">

							<li class="pull-right">
								<a class="reverse" href="#top">Back to top</a>
							</li>
						</ul>
					</div>
				</div> -->
			</div>

		</div>
		<!-- MOBILE TOP START -->
		<div class="section clearfix categories mobile">

			<div class="row">

				<div class="col-lg-8">

					<?php

					$post = get_post($post_arr_2[11]);
					setup_postdata($post);
					get_template_part('template-parts/content', 'mobile-item-first');

					for ($i = 12; $i < 16; $i++) {

						$post = get_post($post_arr_2[$i]);
						setup_postdata($post);
						get_template_part('template-parts/content', 'newswell-item');
					};

					?>

				</div>

			</div>
			<?php
			if (count($post_arr_2) >= 5) { ?>
				<a class="more reverse" href="/more-stories"><span class="gt-label">More Stories</span> <span class="glyphicon glyphicon-menu-right gt-one"></span><span class="glyphicon glyphicon-menu-right gt-two"></span></a>
			<?php } ?>
			<br />
			<br />
		</div>

		<!-- MOBILE TOP END -->
		<!-- <div class="section clearfix columns" style="border-bottom: 2px solid #f3f3f3">

			<div class="row">

				<div class="col-lg-3">
					<div class="heading">
						<a href="/category/opinion/columns/">COLUMNS</a>
					</div>
				</div>
			</div>

			<div class="row columns">

				<?php
				//4 opinion
				// $opinion_args['posts_per_page'] = 4;
				// $opinion_args['category_name'] = 'columns-opinion';
				// query_posts($opinion_args);

				// while (have_posts()) : the_post();

				// 	get_template_part('template-parts/content', 'opinion-item');

				// endwhile; //opinion

				?>

			</div>

			<a class="more reverse" href="/category/news/columns/"><span class="gt-label">More Columns</span> <span class="glyphicon glyphicon-menu-right gt-one"></span><span class="glyphicon glyphicon-menu-right gt-two"></span></a>

		</div> -->

		<?php
		function showSection($start, $end, $last = false)
		{

			$sectors = array(
				'Apparel and accessories' => '',
				'Arts and entertainment' => '',
				'Automotive' => 'automotive-industry-sectors',
				'Consumer electronics' => '',
				'Consumer packaged goods' => '',
				'Education' => '',
				'Financial services' => '',
				'Food and beverage' => '',
				'Fragrance and personal care' => '',
				'Government' => '',
				'Healthcare' => '',
				'Home furnishings' => '',
				'Jewelry' => '',
				'Legal and privacy' => '',
				'Marketing' => 'marketing-industry-sectors',
				'Media/publishing' => 'mediapublishing',
				'Nonprofits' => '',
				'Real estate' => '',
				'Research' => 'research',
				'Retail' => 'retail-industry-sectors',
				'Software and technology' => 'software-and-technology-industry-sectors',
				'Sports' => '',
				'Telecommunications' => '',
				'Travel and hospitality' => '',
				'Marketer Memo Special Reports' => 'special-reports'
			);

		?>

			<!--div class="section clearfix sectors">

			<div class="row">

				<div class="col-lg-12">

					<?php

					$count = 0;

					foreach ($sectors as $sector => $url) {


						if ($count >= $start && $count <= $end) {

							if (!$url) {
								$url = strtolower($sector);
								$url = str_replace(' ', '-', $url);
							}


					?>

							<div class="col-lg-3 newsbox left">

								<div class="heading">
									<a class="sector" href="/category/sectors/<?= $url ?>"><?= $sector ?></a>
								</div>

								<?php
								$img_count = 0;
								query_posts("category_name=$url&showposts=5");

								if (have_posts()) {


									while (have_posts()) : the_post();

										if (!$img_count) {

								?><a class="img-container" href="<?= the_permalink() ?>">
													<img src="<?= ld16_get_image() ?>">
												</a>
													<small><a class="reverse bold <?= ld16_showkey() ?>" href="<?= the_permalink() ?>"><?= the_title() ?></a></small>
												<?php

											} else {
												get_template_part('template-parts/content', 'sector-item');
											}
											$img_count++;



										endwhile; ?>

										<a class="more reverse" href="/category/sectors/<?= $url ?>"><span class="gt-label">More</span> <span class="glyphicon glyphicon-menu-right gt-one"></span><span class="glyphicon glyphicon-menu-right gt-two"></span></a>
								<?php } ?>

							</div>


					<?php

						}

						$count++;
					} ?>

				</div>

			</div>

		</div-->
		<?php
		}

		showSection(0, 3);
		showSection(4, 7);
		showSection(8, 11);
		showSection(12, 15);
		showSection(16, 19);
		showSection(20, 23, true);



		?>








	</div> <!--home-page-->

<?php
} else {

	get_template_part('template-parts/content', 'none');
}


get_footer(); ?>