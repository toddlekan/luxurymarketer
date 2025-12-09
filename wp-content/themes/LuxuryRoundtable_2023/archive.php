<?php

/**
 * The template for displaying archive pages
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * If you'd like to further customize these archive views, you may create a
 * new template file for each one. For example, tag.php (Tag archives),
 * category.php (Category archives), author.php (Author archives), etc.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */

/*
 print_r($category);
 $term_id = 14;
 $taxonomy_name = 'category';
		*/
/*global $wpdb;


 $sql = "UPDATE wp_term_taxonomy set parent = 196 where term_id in (

 )";
 $wpdb->get_results( $sql );

 $sql = "UPDATE wp_term_taxonomy set parent = 196 where term_id in (
	818,
	2063,
	4108,
	240,
	241,
	3880,

 )";
 $wpdb->get_results( $sql );

	*/

/*
 $sql = "SELECT *
 FROM wp_terms where term_id in
 (SELECT term_id FROM wp_term_taxonomy where taxonomy = 'category')  order by name asc";
 */

/*
 $sql = "SELECT *
 FROM wp_terms as A, wp_term_taxonomy as B
 where A.term_id = B.term_id
 and parent = 0
 order by parent";

 $children = $wpdb->get_results( $sql );
 print_r($children);
*/

/*$sql = "SELECT *
FROM wp_terms as A, wp_term_taxonomy as B
where A.term_id = B.term_id
and a.name = 'mail'
";
$children = $wpdb->get_results( $sql );
print_r($children);*/

//die();


$file_root = ld16_get_file_root();
$url_root = ld16_cdn(get_template_directory_uri());

$category = get_category(get_query_var('cat'));

$slug = $category->slug;
$cat_name = $category->cat_name;
$cat_id = $category->cat_ID;

$cat_link = get_category_link($cat_id);

$showposts = 21;

$offset = 0;

$page = 1;
$prev = 0;
$next = 2;

if (array_key_exists('page', $_GET)) {

	$page = $_GET['page'];
	$prev = $page - 1;
	$next = $page + 1;
	$offset = ($prev) * $showposts;
}

get_header();
//print_r($category);
?>

<div class="section clearfix main">

	<div class="row text">

		<div class="col-lg-8">

			<div class="col-lg-12">
				<h1 class="sector category">

					<?php
					$name = $cat_name;
					$link = $cat_link;
					$author_title = '';
					$author_id = 0;

					if (is_author()) {
						$link = ld16_get_author_url();
						$name = ld16_get_author_name();
						$author_email = get_the_author_meta('email');

						if (
							$author_email === 'giselle@mobilemarketer.com' ||
							$author_email === 'events@napean.com'

						) {
							$author_email = 'news@napean.com';
						}

						$author_email = '<a href=mailto:' . $author_email . '>' . $author_email . '</a>';
						//$author_title = '<h2>'.get_the_author_meta('aim') . ' &bull; ' . $author_email.'</h2>';
						$author_title = '<h2>' . $author_email . '</h2>';
						$author_id = get_the_author_meta('ID');
					}
					?>

					<a href="<?= $link ?>" class="reverse"><?= $name ?></a>
				</h1>
				<?= $author_title ?>
			</div>


			<div class="entry-content">
				<ul class="">

					<?php
					$args = "title_li=&hide_empty=0&parent=" . $category->term_id; ?>
					<?php
					if (!is_author()) {

						wp_list_categories($args);
					} ?>

				</ul>
			</div>




			<?php

			//$query = "category_name=$slug&showposts=$showposts&offset=$offset";
			$query = array(
				'showposts' => $showposts,
				'offset' => $offset
			);

			if ($author_id) {
				$query['author__in'] = array($author_id);
			} else {
				$query['category_name'] = $slug;
			};

			query_posts($query);
			$newswell_count = 0;
			while (have_posts()) : the_post();

				if ($cat_name == 'Columns') {
					get_template_part('template-parts/content', 'column-item');
				} else {
					get_template_part('template-parts/content', 'category-item');
				}

				$newswell_count++;

				if ($newswell_count == 3) {
					$newswell_count = 0;
					print "<br class='clear' />";
				}

			endwhile; //next 3
			?>

			<div class="col-lg-12 navigation">

				<?php if ($cat_name == 'Columns') {

					$label = 'columns';
				} else {
					$label = 'articles';
				}


				?>
				<div class="previousnav">
					<a href="<?= $cat_link ?>?page=<?= $next ?>" rel="prev">« Previous <?= $label ?></a>
				</div>

				<?php if ($prev > 0) { ?>
					<div class="nextnav">
						<a href="<?= $cat_link ?>?page=<?= $prev ?>" rel="next">Newer <?= $label ?> »</a>
					</div>
				<?php } ?>
			</div>
		</div>

		<div class="col-lg-4 headline-list sidebar">

			<?php
			$ad_page = 'archive';
			include($file_root . '/inc/sidebar_1.php');

			?>


		</div>
	</div>
</div>

<?php get_footer(); ?>