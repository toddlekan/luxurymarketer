<?php
/*
 * WordPress Plugin: WP-Print
 * Copyright (c) 2012 Lester "GaMerZ" Chan
 *
 * File Written By:
 * - Lester "GaMerZ" Chan
 * - http://lesterchan.net
 *
 * File Information:
 * - Printer Friendly Post/Page Template
 * - wp-content/plugins/wp-print/print-posts.php
 */

global $text_direction;
$url_root = get_template_directory_uri();
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title><?php bloginfo('name'); ?> <?php wp_title(); ?></title>
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
	<meta name="Robots" content="noindex, nofollow" />
	<?php if (@file_exists(get_stylesheet_directory() . '/print-css.css')) : ?>
		<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/print-css.css" type="text/css" media="screen, print" />
	<?php elseif (@file_exists(get_template_directory() . '/print-css.css')) : ?>
		<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/print-css.css" type="text/css" media="screen, print" />
	<?php else : ?>
		<link rel="stylesheet" href="<?php echo plugins_url('wp-print/print-css.css'); ?>" type="text/css" media="screen, print" />
	<?php endif; ?>
	<?php if ('rtl' == $text_direction) : ?>
		<?php if (@file_exists(get_stylesheet_directory() . '/print-css-rtl.css')) : ?>
			<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/print-css-rtl.css" type="text/css" media="screen, print" />
		<?php else : ?>
			<link rel="stylesheet" href="<?php echo plugins_url('wp-print/print-css-rtl.css'); ?>" type="text/css" media="screen, print" />
		<?php endif; ?>
	<?php endif; ?>
	<link rel="canonical" href="<?php the_permalink(); ?>" />
	<script src="/wp-content/themes/LD2016/js/jquery-1.11.1.min.js"></script>
	<script src="/wp-content/themes/LD2016/js/aes.js"></script>
	<script src="/wp-content/themes/LD2016/js/ld.js"></script>
</head>

<body>
	<?php
	$isMobileApp = isset($_GET['mobile']) ? $_GET['mobile'] : '';
	$isMobileApp = (!empty($isMobileApp) && $isMobileApp == 'true' ? TRUE : FALSE);
	if ($isMobileApp && !is_home()) {
	?>
		<div class="clearfix" style="margin-bottom:20px;">
			<span id="mobile-back" class="mobile-back" style="top:0px; margin-top:10px;">
				<div class="glyphicon glyphicon-menu-left"></div>
			</span>
			<img style="float:right; margin-right:15px; margin-top:15px; width:180px; height:auto;" width="180" src=<?php echo $url_root . "/img/LuxuryRoundtable.png" ?> />
		</div>
	<?php
	} else {
	?>
		<center><img width="400" src=<?php echo $url_root . "/img/LuxuryRoundtable.png" ?> /></center>
	<?php
	}
	?>

	<main role="main" class="center">

		<?php if (have_posts()) : ?>

			<header class="entry-header">

				<span class="hat">
					<strong>
						- <?php bloginfo('name'); ?>
						-
						<span dir="ltr"><?php bloginfo('url') ?></span>
						-
					</strong>
				</span>

				<?php while (have_posts()) : the_post(); ?>

					<h1 class="entry-title">
						<?php the_title(); ?>
					</h1>

					<span class="entry-date">

						<?php _e('Posted By', 'wp-print'); ?>

						<cite><?php the_author(); ?></cite>

						<?php _e('On', 'wp-print'); ?>

						<time>
							<?php the_time(sprintf(
								__('%s @ %s', 'wp-print'),
								get_option('date_format'),
								get_option('time_format')
							));
							?>
						</time>

						<span>
							<?php _e('In', 'wp-print'); ?>
							<?php print_categories(); ?> |
						</span>

						<a href='#comments_controls'>
							<?php print_comments_number(); ?>
						</a>

					</span>

			</header>

			<?php if (print_can('thumbnail')) : ?>
				<?php if (has_post_thumbnail()) : ?>
					<div class="thumbnail">
						<?php the_post_thumbnail('medium'); ?>
					</div>
				<?php endif; ?>
			<?php endif; ?>

			<div class="entry-content">
				<div class="body locked" post-id="<?= the_ID() ?>" token="<?= ld16_get_token(the_ID()) ?>">
					<?php
					$paragraphAfter = 3; //shows the ad after paragraph 1
					$lockAfter = 3;

					$content = get_the_content();

					$content = '<p>' . str_replace("\r\n", '</p><p>', $content) . '</p>';
					$content = str_replace("</strong></p><p>", '</strong><br />', $content);
					$content = str_replace('src="https://www.luxuryourndtable.com', 'src="https://luxuryourndtable.com', $content);


					preg_match_all('#(\[caption.*?\[\/caption\])#', $content, $captions);

					if (count($captions)) {
						foreach ($captions[0] as $key => $caption) {

							$stripped_caption = str_replace("[/caption]", "", $caption);

							$stripped_caption_arr = explode("]", $stripped_caption);

							if (count($stripped_caption_arr) > 1) {

								if ($key == 0) {

									print $stripped_caption_arr[1];

									$content = str_replace($caption, "", $content);
								} else {

									$content = str_replace($caption, '<p class="caption"><font color="gray">' . $stripped_caption_arr[1] . '</font></p>', $content);
								}
							} else {
								$content = str_replace($caption, "", $content);
							}
						}
					}

					?>
					</font>
					</p>

					<div class="divider">&nbsp;</div>

					<?php
					$content = str_replace(chr(194) . chr(160), ' ', $content);

					preg_match_all('#(By \<a )#', $content, $bylines);
					if (count($bylines)) {
						if (!count($bylines[0])) {
					?>
							<p class="byline-container">
								By <a style="color:#777; font-size: 13px;" class="byline reverse" href="<?= ld16_get_author_url() ?>"><?= ld16_get_author_name() ?></a>

							</p>
					<?php
						}
					}
					
					$lockAfter = ld16_is_locked() ? 3 : -1;

					$content = explode("</p>", $content);
					$hide_content = false;

					for ($i = 0; $i < count($content); $i++) {

						echo $content[$i] . "</p>";

						if ($i == $lockAfter && ld16_is_locked()) {
							break;
						}
					}
					?>
				</div>
			</div>

			<div id="encrypted" style="display:none;">
				<?php
					$encrypted = "";
					for ($i = 0; $i < count($content); $i++) {

						$encrypted .= $content[$i] . "</p>";
					}

					$token = ld16_get_token($post->ID);
					$encrypted = ld16_encrypt($encrypted, $token);

					print $encrypted;

				?></div>

		<?php endwhile; ?>

		<div class="comments">
			<?php if (print_can('comments')) : ?>
				<?php comments_template(); ?>
			<?php endif; ?>
		</div>

		<footer class="footer">
			<p>
				<?php _e('Article printed from', 'wp-print'); ?>
				<?php bloginfo('name'); ?>:

				<strong dir="ltr">
					<?php bloginfo('url'); ?>
				</strong>
			</p>

			<p>
				<?php _e('URL to article', 'wp-print'); ?>:
				<strong dir="ltr">
					<?php the_permalink(); ?>
				</strong>
			</p>

			<?php if (print_can('links')) : ?>
				<p><?php print_links(); ?></p>
			<?php endif; ?>

			<p style="text-align: <?php echo ('rtl' == $text_direction) ? 'left' : 'right'; ?>;" id="print-link">
				<a href="#Print" onclick="window.print(); return false;" title="<?php _e('Click here to print.', 'wp-print'); ?>">
					<?php _e('Click', 'wp-print'); ?>
					<?php _e('here', 'wp-print'); ?>
					<?php _e('to print.', 'wp-print'); ?>
				</a>
			</p>

		<?php else : ?>
			<p>
				<?php _e('No posts matched your criteria.', 'wp-print'); ?>
			</p>
		<?php endif; ?>

		<p style="text-align: center;">
			<?php echo stripslashes($print_options['disclaimer']); ?>
		</p>
		</footer>

	</main>

	<script type="text/javascript">
		<!--
		window.print();
		//
		-->
	</script>
</body>

</html>