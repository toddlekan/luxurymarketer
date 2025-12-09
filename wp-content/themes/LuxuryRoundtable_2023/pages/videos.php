<?/*
 Template Name: Videos
 */

//error_reporting(E_ALL);
//ini_set('display_errors', 1);

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

$num = 1;
if(array_key_exists('num',$_GET)) {

	$num=$_GET['num'];

}

get_header();
?>

<div class="section clearfix main videos">

    <div class="row text">

        <div class="col-lg-8">

			<div class="col-lg-12">
				<h1 class="sector category">
	                <a href="/videos" class="reverse">Videos</a>
	            </h1>
            </div>

            <?if($post_id){

				$post = get_post( $post_id );

				$vid = ld16_get_custom_field("video_".$num, true, $post_id);

 				$v = array(
							'url' => $vid,
							'headline' => ld16_get_custom_field("video_".$num."_headline", true, $post_id),
							'article_id' => $post_id,
							'number' => $num,
							'date' => get_the_time('F j, Y', $post_id),
							'caption' => ld16_get_custom_field("video_".$num."_caption")
						);

			?>
			<div class="col-lg-12">
				<div class="viewer">

					<iframe class="side" scrolling="no" src="<?=$v['url'] ?>" width="227" height="152" style="width: 424px; height: 240px;" allowfullscreen="allowfullscreen"></iframe>

					<h6 class="headline"><?=$v['headline'] ?></h6>

					<span class="thicken text"><?=$v['date'] ?></span>

					<div class="caption"><em><?=$v['caption'] ?></em></div>

				</div>

           </div>
            <? } ?>

            <div class="col-lg-12">
        	<?

        	$videos = array();

			query_posts("meta_key=video_1&orderby=date&showposts=$showposts&offset=$offset");

			while (have_posts()) : the_post();

				for($i = 1; $i < 4; $i++){

					$vid = ld16_get_custom_field("video_".$i);
					$img = ld16_get_video_thumbnail("video_".$i);

					if($vid){

						$videos[] = array(
							'url' => $vid,
							'img' => $img,
							'headline' => ld16_get_custom_field("video_".$i."_headline"),
							'article_id' => get_the_ID(),
							'number' => $i,
							'date' => get_the_time('F j, Y'),
							'caption' => ld16_get_custom_field("video_".$i."_caption")
						);

						if(count($videos) == $showposts){

							break 2;

						}


					}
				}


			endwhile;

			$count = 0;
			$row_count = 0;
			foreach($videos as $v){

				?>
				<div class="col-lg-4 newsbox left">

					<div class="collapsed" row="<?=$row_count ?>">

						<div class="iframe-container">

							<?if($v['img']){?>

							<a href="#"><img src="<?=$v['img'] ?>" width="227" height="152" /></a>
							<span style="display: none;">
							<iframe class="side" scrolling="no" no_src="<?=$v['url'] ?>" width="227" height="152" allowfullscreen="allowfullscreen"></iframe>
							</span>

							<?} else {?>

							<iframe class="side" scrolling="no" src="<?=$v['url'] ?>" width="227" height="152" allowfullscreen="allowfullscreen"></iframe>
							<a href="#"></a>
							<?}?>


						</div>

						<h6 class="headline"><a class="reverse" href="#"><?=$v['headline'] ?></a></h6>

						<span class="thicken text"><?=$v['date']; ?></span>

						<div class="caption"><?=$v['caption']; ?></div>

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
                    <a href="/videos?page=<?=$next ?>" rel="prev">« Previous videos</a>
                </div>

                <?if($prev > 0){?>
                <div class="nextnav">
                    <a href="/videos?page=<?=$prev ?>" rel="next">Newer videos »</a>
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
