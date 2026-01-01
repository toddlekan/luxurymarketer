<div class="above-fold-center-top">

		<a class="img-container main" href="<?php the_permalink() ?>"><img class="above-fold-center-top" src="<?= ld16_get_image() ?>"></a>


		<h1 style="margin-top: 19px;"><a href="<?php the_permalink() ?>" class="reverse"><?= the_title() ?><?= ld16_showkey() ?></a></h1>

		<div class="blurb">
			<?php the_excerpt(); ?>
		</div>



</div>
