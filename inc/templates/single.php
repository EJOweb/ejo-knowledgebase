<?php

remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
remove_action( 'genesis_entry_footer', 'genesis_post_meta' );

add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );  

add_action( 'genesis_after_endwhile', 'eoo_show_share_buttons' );
// add_action( 'genesis_after_endwhile', 'ejo_knowledgebase_search' );
add_action( 'genesis_after_endwhile', 'ejo_knowledgebase_add_meta' );

genesis();