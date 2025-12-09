    <?php
    //3 next articles
    $args['posts_per_page'] = 10;
    $args['offset'] = 0;
    $args['paged'] = 1;

    query_posts($args);

    while (have_posts()) : the_post();

    ?>
        <li>
            <a href="<?php the_permalink() ?>"><?php the_title() ?> <?=ld16_showkey()?></a>
        </li>
    <?php

    endwhile;//next 3
    wp_reset_query();
    ?>

<div style="clear: both;"></div>