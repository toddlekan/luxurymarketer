<div class="heading">
    <a style="color: #999;" href="/category/news">LATEST HEADLINES</a>
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
            <h6><a  class="reverse" href="<?php the_permalink() ?>"><?php the_title() ?> <?=ld16_showkey()?></a></h6>
        </li>
    <?php

    endwhile;//next 3

    ?>

</ul>

<div class="ad large rectangle ad-1">

<div id='large-rectangle-1-home' class="banner-ad" style='height:280px; width:336px;'>
							<?php $banners = get_banner_posts();

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

	<?php include('/home/i9o51hwyv6wy//tmp/most_popular.lr.cache');?>

</ol>
<br />
<div style="clear: both;"></div>

<div class="ad large rectangle ad-2">

  <div id='large-rectangle-2-home' style='height:280px; width:336px;'>
  <script>
  googletag.cmd.push(function() { googletag.display('large-rectangle-2-home'); });
  </script>
  </div>

</div>

<div style="clear: both;"></div>

<?php include($file_root.'/inc/sidebar/elsewhere.php')?>

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
