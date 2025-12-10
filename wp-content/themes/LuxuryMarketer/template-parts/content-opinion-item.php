<div class="col-lg-3 newsbox">

	<a class="img-container" href="<?php the_permalink() ?>"><img class="size-full wp-image-96129" src="<?= ld16_get_image()?>"></a>

	<h6><a class="reverse" href="<?php the_permalink() ?>"><?php the_title() ?> <?= ld16_showkey()?></a></h6>



	<small class="thicken">By <a style="color:#777; font-size: 13px;" class="byline reverse" href="<?=ld16_get_author_url()?>"><?=ld16_get_author_name()?></a></small>

	<span class="thicken smaller text blurb"><?the_excerpt()?></span>

</div>
