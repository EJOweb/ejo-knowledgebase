<?php

register_post_type( 'knowledgebase', array(
	'description'           => 'Kennisbank',
	'labels'                => array(
		'name'                  => 'Kennisbank',
		'singular_name'         => 'Kennisbank artikel',
		'add_new'               => 'Nieuw artikel',  
		'add_new_item'          => 'Nieuw artikel toevoegen',  
		'edit_item'             => 'Wijzig artikel',  
		'new_item'              => 'Nieuw artikel',  
		'view_item'             => 'Bekijk artikel',  
		'search_items'          => 'Zoek artikelen',  
		'not_found'             => 'Geen artikel gevonden',  
		'not_found_in_trash'    => 'Geen artikel gevonden in Prullenbank',
		'all_items'             => 'Alle artikelen',
	),
	'public'                => true,
	'menu_position'         => 24,
	'rewrite'               => array(
		'slug'       => 'kennisbank',
		'with_front' => false,
	),
	'supports'              => array('title','editor','author'),
	'public'                => true,
	'show_ui'               => true,
	'publicly_queryable'    => true,
	'has_archive'           => true,
	'exclude_from_search'   => false
));

register_taxonomy( 'knowledgebase_category',array( 'knowledgebase' ),array( 
	'hierarchical'  => false,
	'labels'        => array(
		'name'              =>  'Categorieën',
		'singular_name'     =>  'Categorie',
		'search_items'      =>  'Zoek categorieën',
		'all_items'         =>  'Alle categorieën',
		'parent_item'       =>  'Hoofd categorie',
		'parent_item_colon' =>  'Hoofd categorie:',
		'edit_item'         =>  'Wijzig categorie',
		'update_item'       =>  'Update categorie',
		'add_new_item'      =>  'Nieuwe categorie toevoegen',
		'new_item_name'     =>  'Nieuwe categorie naam',
		'popular_items'     => NULL,
		'menu_name'         =>  'Categorieën' 
	),
	'show_ui'       => true,
	'public'        => true,
	'query_var'     => true,
	'hierarchical'  => true,
	'rewrite'       => array( 
		'slug' => 'kennisbank-categorie',
	)
));