<?/*
 Template Name: Galleries
 */
//error_reporting(E_ALL);
//ini_set('display_errors',1);
?>

<?php
$ad_page = 'archive';

$file_root=ld16_get_file_root();
$url_root=ld16_cdn(get_template_directory_uri());
$showposts=21;
$offset=0;
$page=1;
$prev=0;
$next=2;

$url = $_SERVER['REQUEST_URI'];

$url_arr = explode('/', $url);

$end = $url_arr[count($url_arr) - 2];

if(array_key_exists('page',$_GET)) {

	$page=$_GET['page'];

}elseif(is_numeric($end)){

	$page = $end;

}


if($page > 1){
	$prev=$page-1;
	$next=$page+1;
	$offset=($prev)*$showposts;

}

$post_id = 0;
if(array_key_exists('id',$_GET)) {

	$post_id=$_GET['id'];

}

get_header();
?>

<div class="section clearfix main galleries">

    <div class="row text">

        <div class="col-lg-8">

			<div class="col-lg-12">
				<h1 class="sector category">
	                <a href="/galleries" class="reverse">Photo Galleries</a>
	            </h1>
            </div>

			<div class="col-lg-12">
				<form class="navbar-form navbar-left search" role="search">
				    <div class="form-group">
				        <ul class="clr"><li><input type="text" class="form-control" placeholder="Search"></li><li><a href="#" class="header-magnify"><img width="16" src="<?=$url_root?>/img/magnify-new.png"></a></li></ul>
				    </div>

				</form>
            </div>

            <?if($post_id){

				$post = get_post( $post_id );

				$images = ld16_get_custom_field("gallery_img", false, $post_id);

				$include_images = array();

				foreach($images as $img){

					if(!in_array($img, $include_images)){
						$include_images[] = $img;
					}
				}

 				$gal = array(
						'url' => $img,
						'headline' => get_the_title(),
						'article_id' => $post_id,
						'date' => get_the_time('F j, Y', $post_id),
						'caption' => get_the_excerpt($post_id),
						'images' => $include_images

					);


				//gallery_img

			?>
			<div class="col-lg-12">
				<div class="viewer main">

						<div class="slideshow-container">

							<ul class="clr">
								<li>
									<a class="left-arrow arrow disabled" href="#"><img src="<?=$url_root?>/img/left-arrow.jpg" /></a>
								</li>
								<li class="slideshow-container-inner">
									<ul class="clr">

										<?foreach($gal['images'] as $img){?>

											<li>
												<img class="side" width="" src="<?=ld16_cdn($img) ?>" />
											</li>

										<?}?>

									</ul>
								</li>
								<li>
									<a class="right-arrow arrow" href="#"><img src="<?=$url_root?>/img/right-arrow.jpg" /></a>
								</li>
							</ul>
						</div>

						<h6 class="headline"><a class="reverse" href="#"><?=$gal['headline'] ?> <?=ld16_showkey()?></a></h6>

						<span class="thicken text"><?=$gal['date']; ?></span>

						<div class="caption"><?=$gal['caption']; ?></div>

				</div>

           </div>
            <? } ?>

            <div class="col-lg-12">
        	<?

        	$galleries = array();

			query_posts("meta_key=has_gallery&orderby=date&showposts=$showposts&offset=$offset");


			while (have_posts()) : the_post();


				$img = ld16_get_custom_field("main_img", true);

				$images = ld16_get_custom_field("gallery_img", false);

				$include_images = array();

				foreach($images as $img){

					if(!in_array($img, $include_images)){
						$include_images[] = $img;
					}
				}

				if(count($include_images)){
	 				$galleries[] = array(
							'url' => $img,
							'headline' => get_the_title(),
							'article_id' => $post_id,
							'date' => get_the_time('F j, Y'),
							'caption' => get_the_excerpt(),
							'images' => $include_images

						);

				}

			endwhile;

			$count = 0;
			$row_count = 0;
			foreach($galleries as $g){

				?>
				<div class="col-lg-4 newsbox left">

					<div class="collapsed" row="<?=$row_count ?>">

						<div class="img-container">
							<a class="reverse" href="#"><img class="side" src="<?=ld16_cdn($g['url']) ?>" width="227" /></a>

						</div>


						<div class="slideshow-container" style="display: none;">

							<ul class="clr">
								<li>
									<a class="left-arrow arrow disabled" href="#"><img src="<?=$url_root?>/img/left-arrow.jpg" /></a>
								</li>
								<li class="slideshow-container-inner">
									<ul class="clr">

										<?foreach($g['images'] as $img){?>

											<li>
												<img class="side" src="<?=ld16_cdn($img) ?>" />
											</li>

										<?}?>

									</ul>
								</li>
								<li>
									<a class="right-arrow arrow" href="#"><img src="<?=$url_root?>/img/right-arrow.jpg" /></a>
								</li>
							</ul>
						</div>

						<h6 class="headline"><a class="reverse <?=ld16_showkey()?>" href="#"><?=$g['headline'] ?></a></h6>

						<span class="thicken text"><?=$g['date']; ?></span>

						<div class="caption"><?=$g['caption']; ?></div>

					</div>

				</div>
				<?
				$count++;
				if($count==3) {
					print '<div class="viewer clear"  row="'.$row_count.'"></div>';
					$count=0;
					$row_count++;
				}
			}
			?>
			</div>

			<div class="col-lg-12 navigation">

                <div class="previousnav">
                    <a href="/galleries?page=<?=$next ?>" rel="prev">« Previous galleries</a>
                </div>

                <?if($prev > 0){?>
                <div class="nextnav">
                    <a href="/galleries?page=<?=$prev ?>" rel="next">Newer galleries »</a>
                </div>
                <?} ?>

            </div>
        </div>

        <div class="col-lg-4 headline-list sidebar">

            <?php
			include ($file_root.'/inc/sidebar_1.php');
            ?>


        </div>
    </div>
</div>

<?php get_footer(); ?>
