<?php

function ejo_knowledgebase_result_loop($query_args = array())
{
	global $wp_query;

	if (!empty($query_args))
		$wp_query = new WP_Query( $query_args );

	if ( have_posts() ) :
		while ( have_posts() ) : the_post();

			printf( '<article class="%s">', 'knowledgebase-article' );

			$title = get_the_title();
			$title = sprintf( '<a rel="%s" href="%s">%s</a>', 'bookmark', get_the_permalink(), $title );
			$icon = sprintf( '<i class="%s">%s</i>', 'fa fa-file-text-o', '<!-- icon -->' );

			$heading = sprintf( '<h2 class="%s" itemprop="%s">%s%s</h2>', 'entry-title', 'headline', $icon, $title );
			$heading = sprintf( '<header class="%s">%s</header>', 'entry-header', $heading );

			echo $heading;

			printf( '</article>' );

		endwhile; //* end of one post
	else : //* if no posts exist

		echo 'Geen kennisbank artikelen gevonden.';

	endif; //* end loop
}

function ejo_knowledgebase_add_meta()
{	
	$knowledgebase_link = ejo_get_knowledgebase_link();
	$category_output = ejo_get_knowledgebase_article_categories();

	if ( empty($knowledgebase_link) && empty($category_output) )
		return;

	echo '<div class="knowledgebase-footer">';
	echo $knowledgebase_link;
	echo $category_output;
	echo '</div>';
}

function ejo_get_knowledgebase_link()
{
	$knowledgebase_link = '';

	//* Get knowledgebase link
	$kb_page = get_page_by_path('kennisbank');
	if ($kb_page !== NULL)
		$knowledgebase_link = sprintf( '<a href="%s" title="%s">%s</a>', get_permalink($kb_page->ID), 'kennisbank', get_the_title( $kb_page->ID ) );

	return $knowledgebase_link;
}

//* Get categories of singular categories
function ejo_get_knowledgebase_article_categories()
{
	$category_output = '';

	if ( !is_singular('knowledgebase') )
		return $category_output;

	$categories = wp_get_post_terms( get_the_ID(), 'knowledgebase_category' );
	
	if ( is_wp_error($categories) )
		return $category_output;

	//* Process each category link
	foreach ($categories as $category) {
		$category_output .= sprintf( 
			'<a href="%s">%s</a>', 
			get_term_link( $category->term_id, $category->taxonomy ), 
			$category->name
		);
		$category_output .= ', ';
	}
	$category_output = ' - Categorie: ' . rtrim($category_output, ', ');

	return $category_output;
}

//* knowledgebase search function
function ejo_knowledgebase_search() 
{
?>
	<form role="search" method="get" action="<?php echo site_url('/'); ?>" >
		<div class="knowledgebase-search">
			<input type="text" value="<?php if (is_search()) { echo get_search_query(); } ?>" name="s" placeholder="Doorzoek Kennisbank" />
			<span><input type="submit" class="button" value="Zoeken" /></span>
			<input type="hidden" name="post_type" value="knowledgebase" />
		</div>
	</form>
<?php
}