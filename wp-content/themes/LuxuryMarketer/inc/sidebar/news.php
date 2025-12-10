
<div class="heading">
	<a class="red bold" href="/category/news">NEWS AND ANALYSIS</a>
</div>


<ul class="">

	<?
	//3 next articles
	for($i = 5; $i < 10; $i++){
		$post = get_post($post_arr_2[$i]);
		setup_postdata($post);
	?>
		<li>
			<h6><a  class="reverse" href="<?php the_permalink() ?>"><?php the_title() ?> <?=ld16_showkey()?></a></h6>
		</li>
	<?

};//next 5

	?>

</ul>

<?	//memos
    query_posts("category_name=special-reports&showposts=1");

	if(have_posts()){
?>

<div class="heading">
	<a class="red bold" href="/category/sectors/special-reports">LUXURY MEMO</a>
</div>


<ul class="">


	<?


		while (have_posts()) : the_post();

		?>
			<li>
				<h6><a  class="reverse <?=ld16_showkey()?>" href="<?php the_permalink() ?>"><?php the_title() ?></a></h6>
			</li>
		<?
		endwhile;//memos

	?>



</ul>

<?}?>

<div class="home">
<?include($file_root.'/inc/sidebar/ad1.php');?>
</div>
