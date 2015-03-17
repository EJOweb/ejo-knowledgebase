<?php
/**
 * Plugin Name: Knowledgebase
 * Plugin URI: http://github.com/ejoweb
 * Description: Knowledgebase. By EJOweb.
 * Version: 0.0.1
 * Author: Erik Joling
 * Author URI: http://www.erikjoling.nl/
 *
 * Minimum PHP version: 5.3.0
 *
 * @package   Knowledgebase
 * @since     0.0.1
 */

class EJO_Knowledgebase 
{
	//* Holds the instance of this class.
	private static $instance;

	//* Stores the version of this plugin.
	public static $version;

	//* Stores the directory path for this plugin.
	public static $dir;

	//* Stores the directory URI for this plugin.
	public static $uri;

	//* Plugin setup.
	private function __construct() {

		//* Set the properties needed by the plugin.
		add_action( 'plugins_loaded', array( $this, 'setup' ) );

		//* Register Knowledgebase post type
		add_action( 'init', array( $this, 'register_post_type_knowledgebase' ) );

		//* Load templates for knowledgebase
		add_action( 'template_redirect', array( $this, 'load_knowledgebase_templates' ) );

		//* Load stylesheet
		add_action( 'wp_enqueue_scripts', array( $this, 'load_stylesheet' ) );

		//* Register Knowledgebase widget
		add_action( 'widgets_init', array( $this, 'register_knowledgebase_widget' ) );

		// Force page to be password protected
		// add_action('post_submitbox_misc_actions', array( $this, 'admin_knowledge_article_visibility_password' ) );

		//* Remove protected prefix from title
		// add_action('init', array($this, 'main'));

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

	//*
	public function register_post_type_knowledgebase()
	{
		require_once( self::$dir . 'inc/register-post-type.php' );
	}

	//*
	public function load_knowledgebase_templates()
	{
		//* include functions used by template files
		include_once( self::$dir . 'inc/templates/functions.php');

		//* Include template files where necessary
		add_filter( 'template_include', array( $this, 'manage_knowledgebase_templates' ) );
	}

	//*
	public function manage_knowledgebase_templates( $template )
	{
		$new_template = '';

		if ( is_search() && isset($_GET['post_type']) && $_GET['post_type']=='knowledgebase' ) {
			$new_template = self::$dir . 'inc/templates/search.php';
		}

		elseif ( is_post_type_archive('knowledgebase') ) {
			$new_template = self::$dir . 'inc/templates/archive.php';
		}

		elseif ( is_tax( 'knowledgebase_category' ) ) {
			$new_template = self::$dir . 'inc/templates/category.php';
		}

		elseif ( is_singular( 'knowledgebase' ) ) {
			$new_template = self::$dir . 'inc/templates/single.php';
		}

		//* Load new template if it exists
		$template = ( !empty($new_template) && file_exists($new_template) ) ? $new_template : $template;

		return $template;
	}

	//* Load the stylesheet
	public function load_stylesheet()
	{
		wp_enqueue_style( 'knowledgebase-style', self::$uri . 'css/style.css', array(), self::$version );
	}

	//*
	public function register_knowledgebase_widget()
	{

	}

	public function admin_knowledge_article_visibility_password()
	{
		global $post;

		// Only change password for preview videos		
		if($post->post_type != 'knowledgebase')
			return false;
		?>

		<script type="text/javascript">
			(function($){
				try {
					// Set the default text
					$('#post-visibility-display').text('Password Protected');

					// Set hidden post visibility value
					$('#hidden-post-visibility').val('password');

					// Check the radio button
					$('#visibility-radio-password').prop('checked', true);

					// Open the visibility display
					$('#post-visibility-select').show();
				} catch(err){}
			}) (jQuery);
		</script>

		<?php
	}

	//* Multiple functionalities
	public function main()
	{
		add_filter( 'protected_title_format', 'yourprefix_private_title_format' );

		function yourprefix_private_title_format( $format ) {
			return '%s';
		}
	}


	//* Returns the instance.
	public static function init() 
	{
		if ( !self::$instance )
			self::$instance = new self;

		return self::$instance;
	}

	//* Check if Fontawesome
	public function fontawesome() 
	{

	}
}

EJO_Knowledgebase::init();
