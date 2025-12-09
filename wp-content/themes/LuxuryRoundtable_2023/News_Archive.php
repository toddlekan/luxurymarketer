<?php
/**
Template Name: Newsletter Archive Placeholder
 */

$file_root=ld16_get_file_root();
$url_root=ld16_cdn(get_template_directory_uri());


get_header();

?>

<div class="section clearfix main galleries">

    <div class="row text">

		<div class="col-lg-12">

			<div class="col-lg-12">
				<h1 class="sector category">
	                <?php the_title();?>
	            </h1>
            </div>

			<div class="col-lg-12">

				<div id="newsletter" style="display:none;">
					<?php

						while ( have_posts() ) : the_post();

							$content = get_the_content();

							$content = str_replace("https://luxurydaily.com//ads", "https://www.luxurydaily.com/ads", $content);

							$content = str_replace("http://", "https://", $content);

							print $content;

						endwhile;

					?>
				</div>


				<script>

					$(document).ready(function(){

						/*d = new Date();

						$("img").each(function(){

							src = $(this).attr("src")  + "?"+d.getTime() ;

							console.log(src);

							$(this).attr("src", src);


						});*/

						var $iframe = $('#contents');
						$iframe.ready(function() {


							var contents = $("#newsletter").html();

						    $iframe.contents().find("body").append(contents);

						    var iFrame = document.getElementById( 'contents' );
						    iFrame.height = iFrame.contentWindow.document.body.scrollHeight;


                if(iFrame.height < 1){
                  iFrame.height = 600;

                }


						});





					});

				</script>

				<div style="float: right;">Scroll below to see newsletter</div>
				<iframe id="contents" style="width: 100%; height: 600; border: 0;"></iframe>




			</div>
        </div>


    </div>
</div>

<?get_footer(); ?>
