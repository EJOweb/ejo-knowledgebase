<?php

remove_action( 'genesis_loop', 'genesis_do_loop' );
add_action( 'genesis_loop', 'ejo_knowledgebase_search_loop' );

add_action( 'genesis_after_loop', 'ejo_knowledgebase_search' );
add_action( 'genesis_after_loop', 'ejo_knowledgebase_add_meta' );

/* Load search loop */
function ejo_knowledgebase_search_loop()
{	
	echo '<h1 class="entry-title">U zocht naar: ' . get_search_query() . '</h1>';

	echo '<div class="knowledgebase-category">';

	ejo_knowledgebase_result_loop();

	echo '</div>';
}

genesis();