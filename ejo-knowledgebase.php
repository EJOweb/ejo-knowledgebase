<?php
/**
 * Plugin Name: 		EJO Knowledgebase
 * Plugin URI: 			http://github.com/ejoweb/ejo-knowledgebase
 * Description: 		Knowledgebase, the EJOweb way.
 * Version: 			0.3.1
 * Author: 				Erik Joling
 * Author URI: 			http://www.ejoweb.nl/
 *
 * GitHub Plugin URI: 	https://github.com/ejoweb/ejo-knowledgebase
 * GitHub Branch: 		overhaul
 */

// Store directory path of this plugin
define( 'EJO_KNOWLEDGEBASE_PLUGIN_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'EJO_KNOWLEDGEBASE_PLUGIN_URL', trailingslashit( plugin_dir_url( __FILE__ ) ) );

/* Load classes */
include_once( EJO_KNOWLEDGEBASE_PLUGIN_DIR . 'includes/settings-class.php' );

/* Knowledgebase */
EJO_Knowledgebase::init();

/* Settings */
EJO_Knowledgebase_Settings::init();

/**
 *
 */
final class EJO_Knowledgebase 
{
	/* Version number of this plugin */
	public static $version = '0.2';

	/* Holds the instance of this class. */
	private static $_instance = null;

	/* Store post type */
	public static $post_type = 'knowledgebase_article';

	/* Post type plural name */
	public static $post_type_plural = 'knowledgebase_articles';

	/* Post type archive */
	public static $post_type_archive = 'knowledgebase';

	/* Stores the directory path for this plugin. */
	public static $dir;

	/* Stores the directory URI for this plugin. */
	public static $uri;

	/* Plugin setup. */
	protected function __construct() 
	{
		/* Load Helper Functions */
        add_action( 'plugins_loaded', array( $this, 'helper_functions' ), 1 );

		/* Add Theme Features */
        add_action( 'after_setup_theme', array( $this, 'theme_features' ) );

		/* Register Post Type */
		add_action( 'init', array( $this, 'register_knowledgebase_post_type' ) );
	}

    /* Add helper functions */
    public function helper_functions() 
    {
        /* Use this function to filter custom theme support with arguments */
        include_once( EJO_KNOWLEDGEBASE_PLUGIN_DIR . 'includes/theme-support-arguments.php' );
    }


    /* Add Features */
    public function theme_features() 
    {	
		/* Allow arguments to be passed for theme-support */
		add_filter( 'current_theme_supports-ejo-knowledgebase', 'ejo_theme_support_arguments', 10, 3 );
	}

	/* Register Post Type */
	public function register_knowledgebase_post_type() 
	{
		/* Get knowledgebase settings */
		$knowledgebase_settings = get_option( 'knowledgebase_settings', array() );

		/* Archive title */
		$title = (isset($knowledgebase_settings['title'])) ? $knowledgebase_settings['title'] : self::$post_type_archive;

		/* Archive description */
		$description = (isset($knowledgebase_settings['description'])) ? $knowledgebase_settings['description'] : '';

		/* Archive slug */
		$archive_slug = (isset($knowledgebase_settings['archive-slug'])) ? $knowledgebase_settings['archive-slug'] : self::$post_type_archive;

		/* Category archive slug */
		$category_archive_slug = 'kennisbank-categorie';

		/* Register the Knowledgebase Article post type. */
		register_post_type(
			self::$post_type,
			array(
				'description'         => $description,
				'menu_position'       => 24,
				'menu_icon'           => 'dashicons-archive',
				'public'              => true,
				'has_archive'         => $archive_slug,

				/* The rewrite handles the URL structure. */
				'rewrite' => array(
					'slug'       => $archive_slug,
					'with_front' => false,
				),

				/* What features the post type supports. */
				'supports' => array(
					'title',
					'editor',
					'excerpt',
					'author',
					'thumbnail',
					'custom-header'
				),

				/* Labels used when displaying the posts. */
				'labels' => array(
					'name'               => $title,
					'singular_name'      => __( 'Article',                    'ejo-knowledgebase' ),
					'menu_name'          => __( 'Knowledgebase',              'ejo-knowledgebase' ),
					'name_admin_bar'     => __( 'Knowledgebase Article',      'ejo-knowledgebase' ),
					'add_new'            => __( 'Add New',                    'ejo-knowledgebase' ),
					'add_new_item'       => __( 'Add New Article',            'ejo-knowledgebase' ),
					'edit_item'          => __( 'Edit Article',               'ejo-knowledgebase' ),
					'new_item'           => __( 'New Article',                'ejo-knowledgebase' ),
					'view_item'          => __( 'View Article',               'ejo-knowledgebase' ),
					'search_items'       => __( 'Search Articles',            'ejo-knowledgebase' ),
					'not_found'          => __( 'No articles found',          'ejo-knowledgebase' ),
					'not_found_in_trash' => __( 'No articles found in trash', 'ejo-knowledgebase' ),
					'all_items'          => __( 'Articles',                   'ejo-knowledgebase' ),
				)
			)
		);

		/* Register Category Taxonomy */
		register_taxonomy( 
			'knowledgebase_category', 
			array( self::$post_type ),
			array( 
				'hierarchical'  => true,
				'rewrite'       => array( 
					'slug'       => $category_archive_slug,
					'with_front' => false,
				),

				'labels'        => array(
					'name'              => __( 'Category',               'ejo-knowledgebase' ),
					'singular_name'     => __( 'Knowledgebase Category', 'ejo-knowledgebase' ),
					'menu_name'         => __( 'Knowledgebase Category', 'ejo-knowledgebase' ),
					'search_items'      => __( 'Search Categories',      'ejo-knowledgebase' ),
					'all_items'         => __( 'Categories',             'ejo-knowledgebase' ),
					'parent_item'       => __( 'Parent Category',        'ejo-knowledgebase' ),
					'parent_item_colon' => __( 'Parent Category:',       'ejo-knowledgebase' ),
					'edit_item'         => __( 'Edit Category',          'ejo-knowledgebase' ),
					'update_item'       => __( 'Update Category',        'ejo-knowledgebase' ),
					'add_new_item'      => __( 'Add New Category',       'ejo-knowledgebase' ),
					'new_item_name'     => __( 'New Category ',          'ejo-knowledgebase' ),
					'popular_items'     => __( 'Popular Categories',     'ejo-knowledgebase' ),
					'not_found'			=> __( 'Category not found', 	 'ejo-knowledgebase' )
				),
			)
		);
	}

	/* Returns the instance. */
	public static function init() 
	{
		if ( !self::$_instance )
			self::$_instance = new self;
		return self::$_instance;
	}
}
