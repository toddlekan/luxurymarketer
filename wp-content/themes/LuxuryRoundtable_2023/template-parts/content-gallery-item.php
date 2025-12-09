<li>

	<span class="desktop newsbox">
		<a class="category thicken grey uppercase"
			href="<?=ld16_cat_id($post->ID)?>">
			<?=ld16_cat_name($post->ID)?>
		</a>
	</span>

	<a class="img-container light-grey" href="<?php the_permalink() ?>"><img src="<?= ld16_get_image()?>"></a>


	<span class="mobile newsbox">
		<a class="category thicken grey uppercase"
			href="<?=ld16_cat_id($post->ID)?>">
			<?=ld16_cat_name($post->ID)?>
		</a>
	</span>

	<h6><a class="reverse" href="<?php the_permalink() ?>"><?php the_title() ?> <?= ld16_showkey()?></a></h6>


</li>
