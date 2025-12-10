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

				<div id="newsletter" style="display:none;">
					<?php

						while ( have_posts() ) : the_post();

							the_content();

						endwhile;

					?>
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
