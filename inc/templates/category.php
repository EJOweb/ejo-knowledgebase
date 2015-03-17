<?php

remove_action( 'genesis_loop', 'genesis_do_loop' );
add_action( 'genesis_loop', 'ejo_knowledgebase_category_loop' );

add_action( 'genesis_after_loop', 'ejo_knowledgebase_add_meta' );

/* Load categoryloop */
function ejo_knowledgebase_category_loop()
{	
	global $wp_query;

	$category_title = $wp_query->queried_object->name;
	$category_slug = $wp_query->queried_object->slug;


	echo '<h1 class="entry-title">' . $category_title . '</h1>';

	echo '<div class="knowledgebase-category">';
	
	$query_args = array(
		'post_type' => 'knowledgebase', // exclude posts from this category
		'tax_query' => array(
			array(
				'taxonomy' => 'knowledgebase_category',
				'field'    => 'slug',
				'terms'    => $category_slug,
			),
		),
	);
	ejo_knowledgebase_result_loop($query_args);

	echo '</div>';

	//* Restore original query
	wp_reset_query();
}

genesis();