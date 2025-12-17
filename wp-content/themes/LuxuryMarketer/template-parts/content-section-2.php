<div class="col-lg-4 section-2">

		<a class="img-container main" href="<?php the_permalink() ?>"><img src="<?= ld16_get_image() ?>"></a>

		<p class="sector">
			<a style="color: #999;" href="<?= ld16_cat_id($post->ID) ?>">
				<?= ld16_cat_name($post->ID) ?>
			</a>
		</p>

		<h1 class="smaller"><a href="<?php the_permalink() ?>" class="reverse"><?= the_title() ?><?= ld16_showkey() ?></a></h1>

		<div class="blurb">
			<?php the_excerpt(); ?>
		</div>



</div>
