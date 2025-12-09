<div class="col-lg-4 newsbox left">

	<a class="img-container" href="<?php the_permalink() ?>">
		<img src="<?= ld16_get_image()?>" />
	</a>

	<h6><a class="reverse" href="<?php the_permalink() ?>"><?php the_title() ?> <?= ld16_showkey()?></a></h6>

	<span class="thicken smaller text"><?php the_time('F j, Y'); ?></span>

	<small class="thicken">By <a style="color:#777; font-size: 13px;" class="byline reverse" href="<?=ld16_get_author_url()?>"><?=ld16_get_author_name()?></a></small>

	<span class="thicken smaller text blurb"><?= the_excerpt()?></span>

</div>
