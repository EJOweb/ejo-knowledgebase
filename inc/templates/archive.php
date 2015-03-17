<?php

remove_action( 'genesis_loop', 'genesis_do_loop' );
add_action( 'genesis_loop', 'ejo_knowledgebase_archive_loop' );
add_action( 'genesis_after_loop', 'ejo_knowledgebase_search' );

add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );  

/* Load overviewloop */
function ejo_knowledgebase_archive_loop()
{	
	//* Get Kennisbank page content
	$kb_page = get_page_by_path('kennisbank');
	if ($kb_page !== NULL)	{

		echo '<h1 class="entry-title">' . get_the_title( $kb_page->ID ) . '</h1>';
		echo apply_filters('the_content',$kb_page->post_content);

	}

	//* Get knowledgebase categories
	$category_args = array(
		'parent'     => 0, 
		'orderby'    => 'name',
		'order'      => 'ASC',
		'hide_empty' => true
	);
	$kb_categories = get_terms('knowledgebase_category', $category_args);   
	
	echo '<div class="knowledgebase-container">';

	//* Loop throught the categories
	foreach ($kb_categories as $kb_category) { 

		// Define the query
		$args = array(
			'post_type' => 'knowledgebase',
			'knowledgebase_category' => $kb_category->slug
		);
		$kb_posts = new WP_Query( $args );
?>
		<div class="knowledgebase-category-block">
			<h2 class="knowledgebase-category">
				<i class="fa fa-folder-o"><!-- icon --></i><a href="<?php echo get_term_link($kb_category); ?>"><?php echo $kb_category->name; ?></a>
			</h2>	

			<?php while ( $kb_posts->have_posts() ) : $kb_posts->the_post(); ?>
				<div class="knowledgebase-article" id="post-<?php the_ID(); ?>">
					<i class="fa fa-file-text-o"><!-- icon --></i><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
				</div>
			<?php endwhile; ?>
		</div>
<?php
		// use reset postdata to restore orginal query
		wp_reset_postdata();

	} // END foreach

	echo '</div>';

}

genesis();