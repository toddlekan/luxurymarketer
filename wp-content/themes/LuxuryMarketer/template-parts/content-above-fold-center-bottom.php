<div class="above-fold-center-bottom">
	<hr />

		<a class="img-container main mobile-only" href="<?php the_permalink() ?>"><img src="<?= ld16_get_image() ?>"></a>

		<h1><a href="<?php the_permalink() ?>" class="reverse"><?= the_title() ?><?= ld16_showkey() ?></a></h1>

		<div class="blurb">
			<?php the_excerpt(); ?>
		</div>



</div>
