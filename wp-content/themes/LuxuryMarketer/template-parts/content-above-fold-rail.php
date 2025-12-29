<div class="above-fold-rail">

		<a class="img-container main" href="<?php the_permalink() ?>"><img src="<?= ld16_get_image() ?>"></a>

		<span class="desktop newsbox">
			<a class="category thicken grey uppercase"
				href="<?=ld16_cat_id($post->ID)?>">
				<?=ld16_cat_name($post->ID)?>
			</a>
		</span>

		<h1 class="smaller"><a href="<?php the_permalink() ?>" class="reverse"><?= the_title() ?><?= ld16_showkey() ?></a></h1>

</div>
