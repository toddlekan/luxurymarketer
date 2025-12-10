<div class="section clearfix features">

	<div class="row columns">

		<div class="col-lg-3 newsbox">

			<div class="heading">
				<a href="/category/editors-choice">EDITOR'S CHOICE</a>
			</div>

			<?

			query_posts("category_name=editors-choice&showposts=1");

			while (have_posts()) : the_post();

				get_template_part( 'template-parts/content', 'special-item' );

			endwhile;//opinion

			?>


		</div>

		<div class="col-lg-3 newsbox">

			<div class="heading">
				<a href="/category/sectors/special-reports">SPECIAL REPORTS</a>
			</div>

			<?

		    query_posts("category_name=special-reports&showposts=1");

			while (have_posts()) : the_post();

				get_template_part( 'template-parts/content', 'special-item' );

			endwhile;//opinion

			?>
		</div>

		<div class="col-lg-3 newsbox">

			<div class="heading">
				<a href=".#">SUBSCRIPTIONS</a>
			</div>
			<a class="img-container" href="/subscription-form"><img class="size-full wp-image-96129" src="https://www.luxurydaily.com/wp-content/uploads/2016/08/Luxury-Daily-Web-site-screenshot--300x169.png"></a>
			
			<h6><a class="reverse" href="/subscription-form">Subscriptions</a></h6>

			<?
		/*
		    $opinion_args['posts_per_page'] = 1;
			$opinion_args['meta_key'] = 'fp_subscription';
			$opinion_args['meta_value'] = 'true';
			query_posts($opinion_args);

			while (have_posts()) : the_post();

				get_template_part( 'template-parts/content', 'special-item' );

			endwhile;//opinion
			*/
			?>
		</div>

		<div class="col-lg-3 newsbox">

			<div class="heading">
				<a href=".#">STORE</a>
			</div>

			<?
			//https://www.luxurydaily.com/wp-content/uploads/2017/01/State-of-Luxury-2017.jpg
			//194592
			/*
			$opinion_args['posts_per_page'] = 1;
	    $opinion_args['offset'] = 0;
			$opinion_args['paged'] = 1;

			$opinion_args['meta_key'] = 'fp_store';
			$opinion_args['meta_value'] = 'true';

			//query_posts('p=194592');
			//print_r(get_post_meta(194592));

			query_posts($opinion_args);

			while (have_posts()) : the_post();

				get_template_part( 'template-parts/content', 'special-item' );

			endwhile;//opinion
			*/

			$the_query = new WP_Query('meta_key=fp_store&meta_value=true&posts_per_page=1');

			// The Loop
			while ( $the_query->have_posts() ) : $the_query->the_post();

				get_template_part( 'template-parts/content', 'special-item' );

			endwhile;

			// Reset Post Data
			wp_reset_postdata();

			?>
		</div>

	</div>

</div>
