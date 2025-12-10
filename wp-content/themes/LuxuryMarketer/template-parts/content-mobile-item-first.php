<div class="col-lg-4 newsbox left">



	<a class="img-container" href="<?php the_permalink() ?>">
		<img src="<?= ld16_get_image()?>" />
	</a>

	<a class="category thicken"
		href="<?=ld16_cat_id($post->ID)?>">
		<?=ld16_cat_name($post->ID)?>
	</a>

	<h6><a class="reverse first" href="<?php the_permalink() ?>"><?php the_title() ?> <?= ld16_showkey()?></a></h6>

	<span class="thicken smaller text blurb"><?= the_excerpt()?></span>

</div>
