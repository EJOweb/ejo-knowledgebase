<?php
/**
 * Plugin Name: 		EJO Knowledgebase
 * Plugin URI: 			http://github.com/ejoweb/ejo-knowledgebase
 * Description: 		Knowledgebase. By EJOweb.
 * Version: 			0.3
 * Author: 				Erik Joling
 * Author URI: 			http://www.ejoweb.nl/
 *
 * GitHub Plugin URI: 	https://github.com/ejoweb/ejo-knowledgebase
 * GitHub Branch: 		overhaul
 */

final class EJO_Knowledgebase 
{
	//* Holds the instance of this class.
	protected static $_instance = null;

	//* Stores the version of this plugin.
	public static $version;

	//* Stores the id of this plugin.
	public static $id = 'ejo-knowledgebase';

	//* Stores the name of the post-type
	public static $post_type = 'knowledgebase';

	//* Stores the directory path for this plugin.
	public static $dir;

	//* Stores the directory URI for this plugin.
	public static $uri;

    //* Returns the instance.
    public static function instance() 
    {
        if ( !self::$_instance )
            self::$_instance = new self;
        return self::$_instance;
    }

	//* Plugin setup.
	private function __construct() 
	{
		//* Set the properties needed by the plugin.
		self::setup();

		// Include required files
        self::includes();

		//* Register Knowledgebase post type
		add_action( 'init', array( $this, 'register_knowledgebase_post_type' ) );

		//* Register Columns
		add_filter( 'manage_'.self::$post_type.'_posts_columns', array( $this, 'manage_columns' ) );
		add_action( 'manage_'.self::$post_type.'_posts_custom_column', array( $this, 'manage_columns_output' ), 10, 2 );

		//* Register Knowledgebase widget
		add_action( 'widgets_init', array( $this, 'register_knowledgebase_widget' ) );
	}

	//* Defines the directory path and URI for the plugin.
	public function setup() 
	{
		// Store directory path and url of this plugin
		self::$dir = trailingslashit( plugin_dir_path( __FILE__ ) );
		self::$uri = trailingslashit( plugin_dir_url(  __FILE__ ) );

		//* Set version based on metadata at top of this file
		$plugin_data = get_file_data( __FILE__, array('Version' => 'Version') );
		self::$version = $plugin_data['Version'];
	}

	//* Includes
    private static function includes() 
    {
		include_once( self::$dir . 'inc/helpers.php' );
		include_once( self::$dir . 'inc/class-widget.php' );
	}

	//*
	public function register_knowledgebase_post_type()
	{
		register_post_type( self::$post_type, array(
			'description'           => 'Kennisbank',
			'labels'                => array(
				'name'                  => 'Kennisbank',
				'singular_name'         => 'Artikel',
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
	}



	//* 
	public function manage_columns( $columns ) 
	{
		$columns = array_insert_after('title', $columns, 'categories', 'Categorieën' );
				
		return $columns;
	}

	public function manage_columns_output( $column, $post_id ) 
	{
		wp_die('test');
		write_log('test');
		if ( $column == 'categories' ) {

			/* Get the genres for the post. */
			$terms = get_the_terms( $post_id, 'knowledgebase_category' );

			// /* If terms were found. */
			// if ( !empty( $terms ) ) {

			// 	$out = array();

			// 	/* Loop through each term, linking to the 'edit posts' page for the specific term. */
			// 	foreach ( $terms as $term ) {
			// 		$out[] = sprintf( '<a href="%s">%s</a>',
			// 			esc_url( add_query_arg( array( 'post_type' => $post->post_type, 'knowledgebase_category' => $term->slug ), 'edit.php' ) ),
			// 			esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, 'knowledgebase_category', 'display' ) )
			// 		);
			// 	}

			// 	/* Join the terms, separating them with a comma. */
			// 	echo join( ', ', $out );
			// }

			/* If no terms were found, output a default message. */
			// else {
				echo 'test';
			// }
		}
	}

	//*
	public function register_knowledgebase_widget()
	{
		register_widget( 'EJO_Knowledgebase_Widget' ); 
	}

	//* Multiple functionalities
	public function main()
	{

	}
}

EJO_Knowledgebase::instance();