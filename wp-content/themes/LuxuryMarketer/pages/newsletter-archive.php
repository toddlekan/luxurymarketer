<?php
/**
Template Name: Newsletter Archive Page NEW
 */

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

				<?php
				while ( have_posts() ) :
					the_post();
					if ( ld16_is_locked() ) :
						?>
				<div class="entry-content">
						<?php ld16_the_page_content(); ?>
				</div>
						<?php
					else :
						?>
				<div id="newsletter" style="display:none;">
						<?php the_content(); ?>
				</div>

				<iframe id="contents"></iframe>

				<script>
					$(document).ready(function(){
						var $iframe = $('#contents');
						$iframe.ready(function() {
						    $iframe.contents().find("body").append($("#newsletter").html());

						});
					});
				</script>
						<?php
					endif;
				endwhile;
				?>
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
