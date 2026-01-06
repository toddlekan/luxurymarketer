<?php

/**
 * The template for displaying all single posts and attachments
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

					<a class="image" href="<?= the_permalink() ?>"><img class="alignnone size-full wp-image-152895" src="<?= ld16_get_post_meta($post->ID, 'Image', true); //ld16_get_image() 
																															?>"> </a>

					<p class="caption main">
						<font color="gray">
							<?php

							$id = $post->ID;

							$category = get_post_meta($id, 'catname', true);
							$category_id = get_cat_ID($category);
							$category_link = get_category_link($category_id);

							//parse up content
							$content = get_the_content();
							$content = '<p>' . str_replace("\r\n", '</p><p>', $content) . '</p>';
							$content = '<p>' . str_replace("\n", '</p><p>', $content) . '</p>';
							$content = str_replace("</strong></p><p>", '</strong><br />', $content);
							$content = str_replace('src="https://www.luxurymarketer.com', 'src="https://www.luxurymarketer.com', $content);


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
					// Check if this is an email page - if so, use the_content() so wp-email plugin can filter it
					if (get_query_var('email') == 1) {
						?>
						<div class="entry-content">
							<?php the_content(); ?>
						</div>
						<?php
					} else {
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

						$paragraphAfter = 3; //shows the ad after paragraph 1
						$lockAfter = ld16_is_locked() ? 3 : -1;


						$content = explode("</p>", $content);
						$hide_content = false;

						?>

						<div class="body <?= isset($locked) ? $locked : '' ?>" post-id="<?= $post->ID ?>" token="<?= ld16_get_token($post->ID) ?>">

							<?php

							for ($i = 0; $i < count($content); $i++) {
								echo $content[$i] . "</p>";

								if ($i == $lockAfter && ld16_is_locked()) {
							?>
									<div class="row call-to-action" style="display:block;">

										<div class="col-lg-12">

											<center>
												This content is accessible only to subscribers of Luxury Marketer. We would love for you to become a subscriber and enjoy the many benefits soon after. <a href="https://www.cambeywest.com/subscribe2/?p=LXM&f=paid" target="_blank">Please click here to enroll as a subscriber of Luxury Marketer.</a> Already a subscriber? 
												<a href="/log-in?redirect=<?= get_the_permalink() ?>" class="pop-login">Please log in.</a>

											</center>

										</div>
									</div>


								<?php
									break;
								}


								if ($i == $paragraphAfter) {

								?>

									<!--p class="banner">

                              <a href="https://pubads.g.doubleclick.net/gampad/jump?iu=/60923973/mid-article-micro-bar&sz=234x60&c=85099116&tile=1" target="_blank">
                              <img src="https://pubads.g.doubleclick.net/gampad/ad?iu=/60923973/mid-article-micro-bar&sz=234x60&c=85099116&tile=1" width="">
                              </a>

                      </p-->

							<?php

								}
							}

							?>

						</div>
					<?php
					}
					?>

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

					<hr />

					<ul class="tools clr smallest bottom">
						<li class="emailTool">
							<a href="<?php the_permalink() ?>/?email=1">Email this</a>
						</li>
						<li class="printTool">
							<a href="<?= the_permalink(); ?>/?print=1" title="Print" rel="nofollow">Print</a>
						</li>
						<li class="reprintsTool">
							<a href="mailto:reprints@napean.com">Reprints</a>
						</li>

						<li class="social liTool">
							<span class="mr_social_sharing"> <a alt="Link" href="http://www.linkedin.com/shareArticle?mini=true&url=<?= urlencode(get_permalink()); ?>&title=<?= urlencode(get_the_title()); ?>" class="mr_social_sharing_popup_link" rel="nofollow"></a></span>
						</li>

						<li class="">
							<a href="<?= ld16_pdf() ?>" class="download-pdf" rel="nofollow">Download PDF</a>
						</li>
					</ul>


					<p class="like-this red">
						<a class="red bold share" href="#" target="_new">Share your thoughts. <span class="click">Click here</span></a>
					</p>

					<?php comments_template(); ?>



					<form action="<?php echo esc_url(admin_url('admin-ajax.php')); ?>" method="post" id="commentform" class="comment-form" style="display: none;">
						<input type="hidden" name="action" value="submit_comment">


						<p><input type="text" name="author" id="author" value="" size="22" tabindex="1" class="form-control input-box placeholder" placeholder="NAME"></p>

						<br />
						<p><input type="text" name="email" id="email" value="" size="22" tabindex="2" class="form-control input-box placeholder" placeholder="EMAIL">
						</p>
						<br />
						<p><input type="text" name="url" id="url" value="" size="22" tabindex="3" class="form-control input-box placeholder" placeholder="WEBSITE">
						</p>
						<br />
						<p><textarea name="comment" cols="%" rows="10" tabindex="4" class="form-control input-box"></textarea></p>
						<br />
						<p>
							<input type="hidden" name="comment_post_ID" value="<?= $post->ID ?>">
						</p>
						<p style="display: none;"><input type="hidden" id="akismet_comment_nonce" name="akismet_comment_nonce" value="bb7d7d3f76"></p>

						<?php do_action('comment_form', $post->ID); ?>

						<div id="recaptcha-submit-btn-area">

							<input name="submit" type="submit" id="submit" tabindex="6" value="Submit Comment" />

							<span id="comment-status"></span>

						</div>

					</form>


					<div class="navigation">
						<div class="previousnav"><?php previous_post_link('%link', '&laquo; Previous article', TRUE); ?></div>
						<div class="nextnav"><?php next_post_link('%link', 'Next article &raquo;', TRUE) ?></div>

					</div>
				</div>

			<?php
			endwhile; //most recent article
			?>




			<div class="mobile divider">&nbsp;</div>

			<div class="col-lg-1"></div>

			<div class="col-lg-4 headline-list sidebar">

				<div class="heading">
					<a style="color: #999;" href="/category/news">NEW INTELLIGENCE</a>
				</div>


				<ul class="">

					<?php
					//3 next articles
					$args['posts_per_page'] = 10;
					$args['offset'] = 0;
					$args['paged'] = 1;

					query_posts($args);

					while (have_posts()) : the_post();

					?>
						<li>
							<h6><a class="reverse" href="<?php the_permalink() ?>"><?php the_title() ?> <?= ld16_showkey() ?></a></h6>
						</li>
					<?php

					endwhile; //next 3

					?>

				</ul>

				<div class="ad large rectangle ad-1">

					<!-- /60923973/large-rectangle-1-article -->
					<!--div id='am-large-rectangle-1-home' style='height:280px; width:336px;'>
                    <script>
                    googletag.cmd.push(function() { googletag.display('am-large-rectangle-1-home'); });
                    </script>
                  </div-->
					<!-- <div id='large-rectangle-1-home' style='height:280px; width:336px;'>
						<a href="https://subscribe.luxurydaily.com/LUX/?f=annualnsm&s=18ANNMIDRA" target="_blank" alt="Annual offer is best option -- don't wait!">
							<img src="/ads/house1.jpg" width="">
						</a>
					</div> -->
					<div id='large-rectangle-1-home' class="banner-ad" style='height:280px; width:336px;'>
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

				<div style="clear: both;"></br></div>

				<div class="heading">
					MOST READ
				</div>

				<ol class="thicken most-popular">

					<?php include('/home/i9o51hwyv6wy/tmp/most_popular_2016.lr.cache'); ?>

				</ol>
				<br />
				<div style="clear: both;"></div>

				<div class="ad large rectangle ad-2">
					<!-- /60923973/am-large-rectangle-1-article -->
					<div id='am-large-rectangle-2-home' style='height:280px; width:336px;'>
						<script>
							googletag.cmd.push(function() {
								googletag.display('am-large-rectangle-2-home');
							});
						</script>
					</div>
				</div>

				<div style="clear: both;"></div>

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

		<div class="row text">

			<div class="col-lg-12 related">

				<!--RELATED START-->

				<div class="col-lg-12">

					<hr />
					<br class="clear" />
					<div class="heading">
						<a href="<?= $category_link ?>">MORE IN <?= $category ?></a>
					</div>
				</div>

				<div class="col-lg-12">

					<?php
					//$args = "post__not_in=$id&cat=$category_id&showposts=6";

					$args = array('post__not_in' => array($id), 'cat' => $category_id, 'showposts' => 3);

					query_posts($args);
					$column = 1;
					while (have_posts()) : the_post();

						get_template_part('template-parts/content', 'related-item', ['column' => $column]);
						if ($column < 4) {
							$column++;
						} else {
							$column = 1;
						}
					endwhile;
					?>

				</div>

				<div class="col-lg-12">

					<?php
					//$args = "post__not_in=$id&cat=$category_id&showposts=6";

					$args = array('post__not_in' => array($id), 'cat' => $category_id, 'showposts' => 3, 'offset' => 3);

					query_posts($args);

					while (have_posts()) : the_post();

						get_template_part('template-parts/content', 'related-item', ['column' => $column]);
						if ($column < 4) {
							$column++;
						} else {
							$column = 1;
						}
					endwhile;
					?>

				</div>

				<br class="clear" />

				<ul class="list-unstyled">

					<li class="pull-right">
						<a class="reverse" href="#top">Back to top</a>
					</li>
				</ul>

				<!--RELATED END-->

				<br class="clear" />
				<hr />
				<br class="clear" />
				<div class="col-lg-12">
					<div class="heading">
						RECENT INTELLIGENCE
					</div>
				</div>

				<div class="col-lg-12">

					<?php
					//$args = "post__not_in=$id&cat=$category_id&showposts=6";

					$args = array('post__not_in' => array($id), 'showposts' => 3);

					query_posts($args);
					$column = 1;
					while (have_posts()) : the_post();

						get_template_part('template-parts/content', 'related-item', ['column' => $column]);
						if ($column < 4) {
							$column++;
						} else {
							$column = 1;
						}
					endwhile;
					?>

				</div>

				<br class="clear" />

				<div class="col-lg-12">

					<?php
					//$args = "post__not_in=$id&cat=$category_id&showposts=6";

					$args = array('post__not_in' => array($id), 'showposts' => 3, 'offset' => 3);

					query_posts($args);
					
					while (have_posts()) : the_post();

						get_template_part('template-parts/content', 'related-item', ['column' => $column]);
						if ($column < 4) {
							$column++;
						} else {
							$column = 1;
						}
					endwhile;
					?>

				</div>



				<br class="clear" />

				<ul class="list-unstyled">

					<li class="pull-right">
						<a class="reverse" href="#top">Back to top</a>
					</li>
				</ul>

				<!--RELATED END-->

			</div>

			<div class="col-lg-4 headline-list sidebar">

				<?php
				//include ($file_root . '/inc/sidebar/ad3.php');
				?>
			</div>
		</div>

	</div>

<?php
else : //not found
	get_template_part('template-parts/content', 'none');
endif;

get_footer();
?>