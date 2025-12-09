<div class="sidebar-videos">
<div class="heading">
	<a href="/videos">VIDEOS</a>
</div>

<ul class="thicken">


	<?
	$showposts = 10;
	$offset = 0;
	query_posts("meta_key=video_1&orderby=date&showposts=$showposts&offset=$offset");

	while (have_posts()) : the_post();

		for($i = 1; $i < 4; $i++){

			$vid = ld16_get_custom_field("video_".$i);

			if($vid){

				$videos[] = array(
					'url' => $vid,
					'headline' => ld16_get_custom_field("video_".$i."_headline"),
					'article_id' => get_the_ID(),
					'number' => $i,
					'date' => get_the_time('F j, Y'),
					'caption' => ld16_get_custom_field("video_".$i."_caption")
				);

				?>
					<li>
						<a class="reverse " href="/videos?id=<?the_ID()?>&num=<?=$i?>"><?=ld16_get_custom_field("video_".$i."_headline")?> <?=ld16_showkey()?></a>
					</li>
				<?


				if(count($videos) == $showposts){

					break 2;

				}


			}
		}


	endwhile;


	?>

</ul>

<a class="more reverse" href="/videos"><span class="gt-label">More Videos</span> <span class="glyphicon glyphicon-menu-right gt-one"></span><span class="glyphicon glyphicon-menu-right gt-two"></span></a>
</div>
