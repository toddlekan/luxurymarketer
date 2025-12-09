<?php
/**
 * The template part for displaying a message that posts cannot be found
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */

 $url_root=ld16_cdn(get_template_directory_uri());
?>

<section class="no-results not-found">
	<header class="page-header" style="border-top: 0px">
		<h1 class="page-title"><?php _e( 'Search', 'twentysixteen' ); ?></h1>
	</header><!-- .page-header -->

	<div class="page-content">
		<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>

			<p><?php printf( __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'twentysixteen' ), esc_url( admin_url( 'post-new.php' ) ) ); ?></p>

		<?php elseif ( is_search() ) : ?>

			<p><?php //_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'twentysixteen' ); ?></p>
			<?php //get_search_form(); ?>
			<ul class="clr">
				<li><input type="text" class="form-control" placeholder="SEARCH"></li>
				<li><a href="#" class="footer-magnify"

					style="height: 39px; width: 39px; margin-top: 0px"
					><img src="<?=$url_root?>/img/magnify-new.png"
						style="width: 32px;"
					></a></li>
			</ul>

		<?php else : ?>

			<p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'twentysixteen' ); ?></p>
			<?php //get_search_form(); ?>

		<?php endif; ?>
	</div><!-- .page-content -->
</section><!-- .no-results -->
