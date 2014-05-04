<?php
add_action('widgets_init', create_function('', 'return register_widget("stag_widget_top_rated_products");'));

class stag_widget_top_rated_products extends WP_Widget{
	function stag_widget_top_rated_products(){
		$widget_ops  = array( 'classname' => 'widget-top-rated-products', 'description' => __( 'Display a list of top rated products on your site.', 'stag' ) );
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'stag_widget_top_rated_products' );
		$this->WP_Widget( 'stag_widget_top_rated_products', __( 'Section: Custom Top Rated Products', 'stag' ), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		extract($args);

		// VARS FROM WIDGET SETTINGS
		$title = apply_filters('widget_title', $instance['title'] );
		$count = $instance['count'];

		echo $before_widget;

		?>

		<div class="inside">

			<?php
				if ( $title ) {
					echo $before_title . $title . $after_title;
				}
			?>

			<div class="grids products">
			<?php
				global $woocommerce;

				add_filter( 'posts_clauses',  array( $woocommerce->query, 'order_by_rating_post_clauses' ) );

				$meta_query = $woocommerce->query->get_meta_query();

				$args = array(
					'posts_per_page' => absint($count),
					'no_found_rows'  => 1,
					'post_status'    => 'publish',
					'post_type'      => 'product'
				);

				$products = new WP_Query( $args );

				if ( $products->have_posts() ) : ?>

				    <?php while ( $products->have_posts() ) : $products->the_post(); ?>

				        <?php woocommerce_get_template_part( 'content', 'product' ); ?>

				    <?php endwhile; // end of the loop. ?>

				<?php

				endif;

				wp_reset_query();

				remove_filter( 'posts_clauses', array( $woocommerce->query, 'order_by_rating_post_clauses' ) );

			?>
			</div>
		</div>

		<?php

		echo $after_widget;

	}

	function update($new_instance, $old_instance){
		$instance = $old_instance;

		// STRIP TAGS TO REMOVE HTML
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['count'] = strip_tags(absint($new_instance['count']));

		return $instance;
	}

	function form($instance){
		$defaults = array(
			/* Deafult options goes here */
			'title' => __( 'Top Rated Products', 'stag' ),
			'count' => 4
		);

		$instance = wp_parse_args((array) $instance, $defaults);

	/* HERE GOES THE FORM */
	?>

	<p>
		<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'stag'); ?></label>
		<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('Count:', 'stag'); ?></label>
		<input type="number" class="widefat" id="<?php echo $this->get_field_id( 'count' ); ?>" name="<?php echo $this->get_field_name( 'count' ); ?>" value="<?php echo $instance['count']; ?>" />
	</p>

	<?php
  }
}
