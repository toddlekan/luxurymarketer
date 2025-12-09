<?/*
 Template Name: Article Page Subscription Promotion
 */
//error_reporting(E_ALL);
//ini_set('display_errors',1);
?>

<?php
$file_root=ld16_get_file_root();
$url_root=ld16_cdn(get_template_directory_uri());

$data = "";

if(array_key_exists('subscriber_pass', $_POST)){

	$data = cambey_login();

}


get_header();
?>

<div class="section clearfix main galleries">

    <div class="row text">

        <div class="col-lg-12">

        	<div class="article-page subscription-promotion">
        		<?the_content()?>
        	</div>


		</div>
    </div>
</div>

<?php get_footer(); ?>
