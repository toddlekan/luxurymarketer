
	<div class="heading">
		<span style="color: #999;" href="/category/news">FREE ARTICLES</span>
	</div>


	<ul class="">

		<?
		//3 next articles

		$args['posts_per_page'] = 25;
    $args['offset'] = 0;
		$args['paged'] = 1;
		$args['meta_key'] = 'unlocked';
		$args['meta_value'] = 'true';

		query_posts($args);

		$count = 0;
		$article_arr = array();

		while (have_posts()) : the_post();

			$title = get_the_title();

			if(in_array($title, $article_arr)){

					continue;

			}

			$article_arr[] = $title;

		?>
			<li>
				<h6><a style="font-size: 14px;" class="reverse <?=ld16_showkey()?>" href="<?php the_permalink() ?>"><?php the_title() ?></a></h6>
			</li>
		<?

			$count++;

			if($count == 5){
				break;
			}

		endwhile;//next 3

		?>

	</ul>

	<div style="clear: both; height: 10.5px"></div>
