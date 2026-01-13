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
	$post_id = 1; // Initialize post_id counter

?>
	<div id="homePage" class="home-page slides">

		<section id=<?= "hero-section-" . $post_id ?> class="section clearfix categories">

			<div class="row">
				<div class="col-lg-12">
					<div class="newswell">

						<div class="mobile-only">
						
							<?php
								for ($i = 0; $i < 6; $i++) {
									if (isset($post_arr_2[$i])) {
										$post = get_post($post_arr_2[$i]);
										setup_postdata($post);
										get_template_part('template-parts/content', 'section-2');
									}
								};
							?>
						</div>
				
						<div class="col-lg-3 above-fold-left-top-col desktop-only">
			
							<?php



							for ($i = 1; $i < 3; $i++) {
								if (isset($post_arr_2[$i])) {
									$post = get_post($post_arr_2[$i]);
									setup_postdata($post);
									get_template_part('template-parts/content', 'above-fold-rail');
								}
							};

							?>

						</div>
						<div class="col-lg-6 above-fold-center-top-col desktop-only">
					
							<?php


							for ($i = 0; $i < 1; $i++) {
								if (isset($post_arr_2[$i])) {
									$post = get_post($post_arr_2[$i]);
									setup_postdata($post);
									get_template_part('template-parts/content', 'above-fold-center-top');
								}
							};

							for ($i = 5; $i < 6; $i++) {
								if (isset($post_arr_2[$i])) {
									$post = get_post($post_arr_2[$i]);
									setup_postdata($post);
									get_template_part('template-parts/content', 'above-fold-center-bottom');
								}
							};
							?>

						</div>
						<div class="col-lg-3 desktop-only">
						
							<?php


							for ($i = 3; $i < 5; $i++) {
								if (isset($post_arr_2[$i])) {
									$post = get_post($post_arr_2[$i]);
									setup_postdata($post);
									get_template_part('template-parts/content', 'above-fold-rail');
								}
							};

							?>

						</div>

				   </div>
				</div>
			</div>
		</section>

		<?php $post_id += 1; ?>

		<section id=<?= "hero-section-" . $post_id ?> class="section clearfix categories ">

			<div class="row">
				<div class="col-lg-12">
					<div class="newswell-divider section divider"></div>
				</div>
				<div class="col-lg-9">

					<div class="newswell">
					<?php
					//newswell
					$newswell_count = 0;
					$divider_set = false;
					if (count($post_arr_2) >= 7) {
						for ($i = 7; $i < 16; $i++) {

							$post = get_post($post_arr_2[$i]);
							setup_postdata($post);

							if ($i < 13) {
								get_template_part('template-parts/content', 'section-2');
							} else {
								get_template_part('template-parts/content', 'section-2-no-blurb');
							}

							$newswell_count++;

							if ($newswell_count == 3) {
								$newswell_count = 0;

								if ($divider_set) {

									print "<div style='clear:both;'></div>";

								} else {
									$divider_set = true;
									print "<div style='clear:both;'></div>";
									// print "<div class='newswell-divider section divider' style='clear:both;'></div>";
								}

							}
						} //next 12
					}
					?>

					</div>
	
				</div>

				<div class="col-lg-3 headline-list sidebar">

					<div class="ad large rectangle ad-1">
			
						<div id='large-rectangle-1-home' style='height:280px; width:336px;'>
			BANNERS!
						<?php $banners = get_banner_posts();

						if ($banners && count($banners) > 0) {
						?>
							<a href="<?= get_field('hyperlink', $banners[0]->ID) ?: "#" ?>" target="_blank" alt="<?= $banners[0]->post_title ?: "" ?>">
								<img src=<?= $banners[0]->guid ?> style="object-fit: cover; width: 100%; height: 100%;">
							</a>
						<?php }

						?>

						</div>
					</div>

					<div style="clear: both;"></div>

					<div class="heading most-read">
						MOST READ
					</div>

					<ol class="thicken most-popular">

						<?php include('/home/i9o51hwyv6wy/tmp/most_popular.lr.cache');?>

					</ol>
					
					<div class="ad large rectangle ad-2">
			
						<div id='large-rectangle-1-home' style='height:280px; width:336px;'>
						<a href="https://americanmarketer.com/subscription-form/" target="_blank" alt="American Marketer 336x280 large rectangle banner">
							<img src="https://americanmarketer.com/wp-content/uploads/2024/03/American-Marketer-336x280-large-rectangle-banner.png" width="">
						</a>
						</div>
					</div>

					<div style="clear: both;"></div>
	
					<div class="ad large rectangle ad-3">
			
						<div id='large-rectangle-1-home' style='height:280px; width:336px;'>
						<a href="https://americanmarketer.com/subscription-form/" target="_blank" alt="American Marketer 336x280 large rectangle banner">
							<img src="https://americanmarketer.com/wp-content/uploads/2024/03/American-Marketer-336x280-large-rectangle-banner.png" width="">
						</a>
						</div>
					</div>
					
					<div style="clear: both;"></div>
					
					<footer class="">
						<div class="row copyright">
						
							<a class="more articles reverse " href="/more-stories"><span class="gt-label">More Articles</span> <span class="glyphicon glyphicon-menu-right gt-one"></span><span class="glyphicon glyphicon-menu-right gt-two"></span></a>
			
					    </div>
					</footer>
					
				</div>
			</div>
		</section>


		<?php $post_id += 1; ?>
		
		<!-- section 3 -->
		<section id=<?= "hero-section-" . $post_id ?> class="section clearfix categories ">

			<div class="row">
				<div class="col-lg-12">
					<div class="newswell-divider section divider"></div>
				</div>
				<div class="col-lg-12">

					<div class="newswell">

					<div class="heading">
						<a href="/category/opinion/columns/">COLUMNS</a>
					</div>

					<?php
					$opinion_args['posts_per_page'] = 3;
					$opinion_args['category_name'] = 'columns';
					query_posts($opinion_args);

					while (have_posts()) : the_post();

						get_template_part('template-parts/content', 'section-3');

					endwhile;//opinion
					wp_reset_query();
					?>
					</div>

					<a class="more reverse" href="/category/opinion/columns/"><span class="gt-label">More Columns</span> <span class="glyphicon glyphicon-menu-right gt-one"></span><span class="glyphicon glyphicon-menu-right gt-two"></span></a>

				</div>
			</div>
		</section>

		<?php $post_id += 1; ?>
		
		<!-- section 4 -->
		<section id=<?= "hero-section-" . $post_id ?> class="section clearfix categories ">

			<div class="row">

				<div class="col-lg-12">

					<div class="newswell">

						<div class="heading">
							<a href="/category/profiles/">PROFILES</a>
						</div>

						<?php
						$opinion_args['posts_per_page'] = 3;
						$opinion_args['category_name'] = 'profiles';
						query_posts($opinion_args);

						while (have_posts()) : the_post();

							get_template_part('template-parts/content', 'section-3');

						endwhile;//opinion
						wp_reset_query();
						?>
					
						<a class="more reverse" href="/category/networking-and-events/profiles/"><span class="gt-label">More Profiles</span> <span class="glyphicon glyphicon-menu-right gt-one"></span><span class="glyphicon glyphicon-menu-right gt-two"></span></a>



					</div>
					
				</div>
			</div>
		</section>

		<?php $post_id += 1; ?>
		<!-- section 5 -->
		<section id=<?= "hero-section-" . $post_id ?> class="section clearfix categories ">

			<div class="row">
				<div class="col-lg-12">
					<div class="newswell-divider section divider"></div>
				</div>
				<div class="col-lg-12">

					<div class="newswell">

					<div class="heading">
						<a href="/category/networking-and-events/">EVENTS</a>
					</div>

					<?php
					$opinion_args['posts_per_page'] = 3;
					$opinion_args['category_name'] = 'networking-and-events';
					query_posts($opinion_args);

					while (have_posts()) : the_post();

						get_template_part('template-parts/content', 'section-3');

					endwhile;//opinion
					wp_reset_query();
					?>
					</div>

					<a class="more reverse" href="/category/networking-and-events/"><span class="gt-label">More Events</span> <span class="glyphicon glyphicon-menu-right gt-one"></span><span class="glyphicon glyphicon-menu-right gt-two"></span></a>

				</div>
			</div>
		</section>

		<?php $post_id += 1; ?>

		<!-- section 6 
		 
					This section should have 5 rows of 4 columns each.
					Each column should have a heading that is the category name with a link to the category page.
					Each column should have 4 posts.
					The first post should have a main image.
					The posts should be sorted by date, newest first.
					The posts should be limited to 5 rows.
					The posts should be limited to 4 columns.
					The posts should be limited to 4 posts per category.
		-->
		<section id=<?= "hero-section-" . $post_id ?> class="section clearfix categories ">

			<div class="row">
				<div class="col-lg-12">
					<div class="newswell-divider section divider"></div>
				</div>
				<div class="col-lg-12 inner">

					<div class="newswell">

						<?php

						/*
						ART & AUCTIONS
						CARS, JETS & YACHTS  
						CHINA
						EDUCATION
						ENTERTAINMENT
						FASHION & LEATHER GOODS  
						HEALTH & WELLNESS
						LEGAL & REGULATION
						MARKETING
						MEDIA & PUBLISHING
						OUTLOOK
						PERFUMES & COSMETICS
						PHILANTHROPY
						PROFILES
						REAL ESTATE & DESIGN  
						RESEARCH
						RETAIL  
						SPORTS
						SUSTAINABILITY 
						TECH, AI & AUTOMATION
						TRAVEL & HOSPITALITY  
						WATCHES & JEWELRY
						WEALTH MANAGEMENT
						WINES & SPIRITS
						*/

						$category_arr = array(
							'art-and-auctions', 'cars-jets-and-yachts', 'china', 'education', 'entertainment', 
							'fashion-and-leather-goods', 'health-and-wellness', 'legal-and-regulation', 'marketing', 
							'media-and-publishing', 'outlook', 'perfumes-and-cosmetics', 'philanthropy-foundations-and-nonprofits', 'profiles',
							'real-estate', 'research', 'retail', 'sports', 'environment-and-sustainability', 'ai-and-automation', 
							'travel-and-hospitality', 'watches-and-jewelry', 'wealth-management', 'food-fine-dining-wines-and-spirits');

						// Limit to 6 rows × 4 columns = 24 categories
						$category_arr = array_slice($category_arr, 0, 24);
						
						$column_count = 0;
						foreach ($category_arr as $category) {
							// Start a new row every 4 columns
							if ($column_count % 4 == 0) {
								if ($column_count > 0) {
									print '</div>'; // Close previous row
								}
								print '<div class="row">';
							}
							
							$opinion_args = array(
								'posts_per_page' => 4,
								'category_name' => $category,
								'orderby' => 'date',
								'order' => 'DESC'
							);
							query_posts($opinion_args);

							// Get category object for proper name
							$category_obj = get_category_by_slug($category);
							$category_name = $category_obj ? $category_obj->name : ucwords(str_replace('-', ' ', $category));
							
						    $category_name = str_replace(' And ', ' &amp; ', $category_name);
						    
							$category_link = get_category_link($category_obj ? $category_obj->term_id : 0);

							print '<div class="col-lg-3 section-7">';	
							print '<div class="heading sector"><a href="'.esc_url($category_link).'">'.esc_html($category_name).'</a></div>';

							$count = 0;
							while (have_posts()) : the_post();
								$count++;
								if ($count > 1) {
									$mobile_only = 'mobile-only';
									$bold = 'thin';
									
								} else {
								    $bold = 'bold';
									$mobile_only = '';
								}
								print '<a class="img-container main '.$mobile_only.'" href="'.get_the_permalink().'"><img src="'.ld16_get_image().'"></a>';
								print '<h1 class="smallest"><a href="'.get_the_permalink().'" class="reverse '.$bold.'">'.get_the_title().ld16_showkey().'</a></h1>';

							endwhile;
							print '</div>';
							wp_reset_query();
							
							$column_count++;
						}
						
						// Close the last row
						if ($column_count > 0) {
							print '</div>';
						}
						?>
					</div>

				</div>
			</div>
	
		</section>
	</div> <!--home-page-->
<?php
} else {

	get_template_part('template-parts/content', 'none');
}


get_footer(); ?>