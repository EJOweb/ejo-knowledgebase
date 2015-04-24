<?php

/* EJO Simple Testimonials Widget Class
--------------------------------------------- */
class EJO_Knowledgebase_Widget extends WP_Widget {

	//* Holds widget settings defaults, populated in constructor.
	protected $defaults;

	//* Constructor. Set the default widget options and create widget.
	function __construct() 
	{
		$widget_title = __( 'Laatste Kennisbank artikelen', EJO_Knowledgebase::$id );

		$widget_ops = array(
			'classname'   => EJO_Knowledgebase::$id . '-widget',
			'description' => __( 'Toont de laatste kennisbankartikelen', EJO_Knowledgebase::$id ),
		);

		$control_ops = array(
			'id_base' => EJO_Knowledgebase::$id . '-widget'
		);

		parent::__construct( EJO_Knowledgebase::$id . '-widget', $widget_title, $widget_ops, $control_ops );
	}

	//* Echo the widget content.
	function widget( $args, $instance ) 
	{
		/** This filter is documented in wp-includes/default-widgets.php */
		$instance['title'] = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

		echo $args['before_widget'];

		if ( !empty($instance['title']) )
			echo $args['before_title'] . $instance['title'] . $args['after_title'];

		$kb_args = array(
			'post_type' 		=> 'knowledgebase',
			'orderby' 			=> 'date',
			'order'				=> 'DESC',
			'posts_per_page' 	=> '5',
		);
		$kb_posts = new WP_Query( $kb_args );
?>
		<div class="knowledgebase-items">
		<?php while ( $kb_posts->have_posts() ) : $kb_posts->the_post(); ?>
			<div class="knowledgebase-article" id="post-<?php the_ID(); ?>">
				<i class="fa fa-file-text-o"><!-- icon --></i><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			</div>
		<?php endwhile; ?>
		</div>
<?php
		// use reset postdata to restore orginal query
		wp_reset_postdata();

		echo $args['after_widget'];
	}

	//* Update a particular instance.
	function update( $new_instance, $old_instance ) {

		$new_instance['title'] = strip_tags( $new_instance['title'] );

		return $new_instance;
	}

	//* Echo the settings update form.
	function form( $instance ) {

		$title = isset( $instance['title'] ) ? $instance['title'] : '';
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" />
		</p>
		<?php
	}
}
?>