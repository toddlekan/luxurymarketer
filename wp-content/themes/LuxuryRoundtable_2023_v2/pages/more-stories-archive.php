<?php

/*
Template Name: More Stories Archive
*/

$file_root = ld16_get_file_root();
$url_root = ld16_cdn(get_template_directory_uri());

$showposts = 21;

$offset = 16;

$page = 1;
$prev = 0;
$next = 2;

$current_page = get_query_var("paged");
$latest = $_REQUEST['newer'];
if ($current_page && is_numeric($current_page)) {
    $page = $current_page;
    $prev = $page - 1;
    $next = $page + 1;
    $offset = ($prev * $showposts) +  $offset;
} else if (isset($latest) && $latest == true) {
    $offset = 0;
    $prev = -1;
    $next = $prev + 1;
    $showposts = $showposts - 5;
}



get_header();
?>

<div  class="section clearfix main">

    <div class="row text">

        <div class="col-lg-8">

            <?php
            $query = array(
                'showposts' => $showposts,
                'offset' => $offset
            );

            query_posts($query);
            $newswell_count = 0;
            while (have_posts()) : the_post();

                get_template_part('template-parts/content', 'category-item');

                $newswell_count++;

                if ($newswell_count == 3) {
                    $newswell_count = 0;
                    print "<br class='clear' />";
                }

            endwhile; //next 3
            ?>

            <div class="col-lg-12 navigation">

                <div class="previousnav">
                    <?php if ($next == 0) { ?>
                        <a href="/more-stories" rel="prev">« Previous <?= $label ?></a>
                    <?php } else { ?>
                        <a href="?paged=<?= $next ?>" rel="prev">« Previous <?= $label ?></a>
                    <?php } ?>
                </div>

                <?php if ($prev > 0) { ?>
                    <div class="nextnav">
                        <a href="?paged=<?= $prev ?>" rel="next">Newer <?= $label ?> »</a>
                    </div>
                <?php } else if ($prev == 0) { ?>
                    <div class="nextnav">
                        <a href="/more-stories?newer=true" rel="next">Newer <?= $label ?> »</a>
                    </div>
                <?php } ?>
            </div>
        </div>

        <div class="col-lg-4 headline-list sidebar">

            <?php
            $ad_page = 'archive';
            include($file_root . '/inc/sidebar_1.php');

            ?>


        </div>
    </div>
</div>

<?php get_footer(); ?>