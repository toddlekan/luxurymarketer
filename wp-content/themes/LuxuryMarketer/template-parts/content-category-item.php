<?php
$url_root = get_template_directory_uri();
?>
<div class="col-lg-4 newsbox left">
	<?php $column = $args['column'];
	//echo $column; 
	?>

	<div class="background" onclick="location.href='<?= the_permalink() ?>';" style="<?= 'background-image: url(' . ld16_get_image() . '); ' ?>  <?php if ($column == 1 || $column == 4) {
																									echo ' display: flex; flex-direction: column; justify-content: flex-end;';
																								} else if ($column == 2) {
																									echo ' justify-content: flex-end; display: flex;';
																								} ?>">
		<div class="category-overlay" style="padding:10px; <?php if ($column == 1 || $column == 4) {
														echo 'width: 100%; min-height: 100px; max-height: fit-content;';
													} else if ($column == 2) {
														echo ' width:50%; height:100%;';
													}
													if ($column != 4) {
														echo "text-align:start; ";
													} else {
														echo "text-align:center; ";
													}
													?>">


			<h6><a class="reverse" href="<?= the_permalink() ?>"><?php the_title() ?> <?= ld16_showkey() ?></a></h6>

			<span class="thicken smaller text"><?= the_time('F j, Y'); ?></span>

		</div>
	</div>


</div>