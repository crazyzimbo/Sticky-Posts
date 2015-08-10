<?php
/*
Plugin Name: Sticky Posts Widget
Description: Adds a widget that shows the sticky posts.
Author: Pieter Ferreira
Version: 1.0.1
*/
// Creating the widget 

class hps_widget extends WP_Widget {

function __construct() {
parent::__construct(
// Base ID of your widget
'hps_widget', 

// Widget name will appear in UI
__('Sticky Posts Widget', 'hps_widget_domain'), 

// Widget description
array( 'description' => __( 'Display a list of your sticky posts', 'hps_widget_domain' ), ) 
);
}

// Creating widget front-end
// This is where the action happens
public function widget( $args, $instance ) {
$title = apply_filters( 'widget_title', $instance['title'] );
// before and after widget arguments are defined by themes
echo $args['before_widget'];
if ( ! empty( $title ) )
echo $args['before_title'] . $title . $args['after_title'];

// This is where you run the code and display the output
   			$args = array(
			'posts_per_page' =>  $instance["num"] ,
			'post__in'  => get_option( 'sticky_posts' ),
			'ignore_sticky_posts' => 1
			);
			$query = new WP_Query( $args );

			echo '<div class="featured_main">';
   				$featured = new WP_Query($args); 
				if ($featured->have_posts()): while($featured->have_posts()): $featured->the_post(); ?>
				<?php if (has_post_thumbnail()) : ?>

				<div class="featured_head">
					<div class="featured_top">
						<div class="feat_imag">
							<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('full'); ?>
							<div class="image_overlay"></div>
							</a>
						</div>
						<div class="cat_ttl"><?php the_category(', '); ?></div>
					</div>

					<div class="featured_bottom">
						<p class="feat_author">BY: <?php the_author_posts_link(); ?></p>
						<div class="art_ttl"><h3><a href="<?php the_permalink(); ?>"> <?php the_title(); ?></a></h3></div>
					</div>
				</div>
				<?php
				endif;
				endwhile; else:
			echo '</div>';
			endif;
echo $args['after_widget'];
}
		
// Widget Backend 
public function form( $instance ) {

if ( isset( $instance[ 'title' ] ) ) {
$title = $instance[ 'title' ];
$num = $instance[ 'num' ];
}
else {
$title = __( '' );
}
// Widget admin form
?>
<p>
<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
</p>

<p>
<label for="<?php echo $this->get_field_id( 'num' ); ?>"><?php _e( 'Number of posts to show:' ); ?></label> 
<input class="num" id="<?php echo $this->get_field_id( 'num' ); ?>" name="<?php echo $this->get_field_name( 'num' ); ?>" type="text" value="<?php echo esc_attr( $num ); ?>" />
</p>
<?php 
}
	
// Updating widget replacing old instances with new
public function update( $new_instance, $old_instance ) {
$instance = array();
$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
$instance['num'] = ( ! empty( $new_instance['num'] ) ) ? strip_tags( $new_instance['num'] ) : '';
return $instance;
}
} // Class hps_widget ends here

// Register and load the widget
function hps_load_widget() {
	register_widget( 'hps_widget' );
}
add_action( 'widgets_init', 'hps_load_widget' );
?>
