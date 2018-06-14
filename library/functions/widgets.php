<?php
/**
 * This file contains the all core supreme widgets which work with posts in wp.
 *
 * @package WordPress
 * @subpackage Directory
 */

add_action( 'widgets_init', 'supreme_unregister_widgets' );
add_action( 'widgets_init', 'supreme_register_widgets' );

/**
 * Unregister WP widgets.
 */
function supreme_register_widgets() {
	/* Register the flicker photos widget. */
	register_widget( 'supreme_flickr_Widget' );
	/* Register the archives widget. */
	register_widget( 'supreme_widget_archives' );
	/* Register the authors widget. */
	register_widget( 'supreme_authors_widget' );
	/* Register the bookmarks widget. */
	register_widget( 'supreme_bookmarks_widget' );
	/* Register the calendar widget. */
	register_widget( 'supreme_calendar_widget' );
	/* Register the categories widget. */
	register_widget( 'supreme_categories_widget' );
	/* Register the post formats widget. */

	/* Register the nav menu widget. */
	register_widget( 'supreme_nav_menu_widget' );
	/* Register the pages widget. */
	register_widget( 'supreme_pages_widget' );
	/* Register the search widget. */
	register_widget( 'supreme_search_widget' );
	/* Register the tags widget. */
	register_widget( 'supreme_tags_widget' );
	/* Register Google Map Widget */
	register_widget( 'supreme_google_map' );
	/* Register Social media Widget */
	register_widget( 'supreme_social_media' );
	/* Subscriber Widget */
	register_widget( 'supreme_subscriber_widget' );
	/* Testimonial Widget */
	register_widget( 'supreme_testimonials_widget' );
	/* slider Widget */
	register_widget( 'supreme_banner_slider' );
	register_widget( 'supreme_contact_widget' );
	register_widget( 'supreme_popular_post' );
	register_widget( 'supreme_recent_post' );
	register_widget( 'supreme_recent_review' );
	register_widget( 'templatic_text' );
	if ( is_plugin_active( 'woocommerce-bookings/woocommerce-bookings.php' ) ) {
		register_widget( 'Listing_Sidebar_Products_Widget' );
	}
	/*	Code By Templatic End. */
}
/**
 * Unregister default WordPress widgets that are replaced by the framework's widgets.  Widgets that
 * aren't replaced by the framework widgets are not unregistered.
 */
function supreme_unregister_widgets() {
	/* Unregister the default WordPress widgets. */
	unregister_widget( 'WP_Widget_Archives' );
	unregister_widget( 'WP_Widget_Calendar' );
	unregister_widget( 'WP_Widget_Categories' );
	unregister_widget( 'WP_Nav_Menu_Widget' );
	unregister_widget( 'WP_Widget_Pages' );
	unregister_widget( 'WP_Widget_Recent_Posts' );
	unregister_widget( 'WP_Widget_Search' );
	unregister_widget( 'WP_Widget_Tag_Cloud' );
	unregister_sidebar( 'entry' );
}


if ( ! class_exists( 'supreme_flickr_Widget' ) ) {
	/**
	 * Supreme Flickr Widget start
	 */
	class supreme_flickr_Widget extends WP_Widget {
		/**
		 * Constructor.
		 */
		function __construct() {

			$widget_ops = array(
							'classname' => 'flicker_photos ',
							'description' => esc_html__( 'Showcase your Flickr photos using this simple widget. Works best in sidebar areas.', 'templatic-admin' ),
							);
			parent::__construct( 'flicker_Widget', esc_html__( 'T &rarr; Flickr Photos', 'templatic-admin' ), $widget_ops );
		}

		/**
		 *
		 * Start of a widget
		 *
		 * @param array $args     agurment of widget area.
		 * @param array $instance instances of widget.
		 */
		function widget( $args, $instance ) {

			extract( $args, EXTR_SKIP );
			$title = empty( $instance['title'] ) ? '' : apply_filters( 'widget_title', $instance['title'] );
			$desc = empty( $instance['desc'] ) ? esc_html__( 'The Beautiful Natural Scenery Photos', 'templatic' ) : apply_filters( 'widget_desc', $instance['desc'] );
			$flicker_id = empty( $instance['flicker_id'] ) ? '73963570@N00' : apply_filters( 'widget_flicker_id', $instance['flicker_id'] );
			$flicker_number = empty( $instance['flicker_number'] ) ? '8' : apply_filters( 'widget_flicker_number', $instance['flicker_number'] );
			echo wp_kses_post( $args['before_widget'] );?>
			<div class="Flicker"><?php
			if ( function_exists( 'icl_register_string' ) ) {
				icl_register_string( 'templatic', 'flickr_desc', $desc );
				$desc = icl_t( 'templatic', 'flickr_desc', $desc );
			}

			if ( '' != $title ) :
				echo wp_kses_post( $args['before_title'] . $title . $args['after_title'] );
			endif;
			if ( '' != $desc ) {
				echo '<span class="flickr_description">' . wp_kses_post( $desc ) . '</span>';
			}
					?>
				<div class="flickr_pics_wrap">
					<?php
					if ( is_ssl() ) {
						$http = 'https://';
					} else {
						$http = 'http://';
					} ?>
					<script type="text/javascript" src="<?php echo wp_kses_post( $http ); ?>www.flickr.com/badge_code_v2.gne?count=<?php echo intval( $flicker_number ); ?>&amp;display=latest&amp;size=s&amp;layout=x&amp;source=user&amp;user=<?php echo wp_kses_post( $flicker_id ); ?>">
					</script>
				</div>
			</div>
		<?php
		echo wp_kses_post( $args['after_widget'] );
		}
		/**
		 *
		 * Save the widget.
		 *
		 * @param array $new_instance     new instance of widget when saved from widget area.
		 * @param array $old_instance 	  old instances of widget.
		 */
		function update( $new_instance, $old_instance ) {
			return $new_instance;
		}

		/**
		 *
		 * Save the widget.
		 *
		 * @param array $instance     instance of widget.
		 */
		function form( $instance ) {

			$instance = wp_parse_args( (array) $instance, array(
															'title' => esc_html__( 'Photos From Flickr', 'templatic' ),
															'desc' => esc_html__( 'The Beautiful Natural Scenery Photos', 'templatic' ),
															'flicker_id' => '73963570@N00',
															'flicker_number' => 8,
														)
			);
			?>
			<p>
				<label for="<?php echo wp_kses_post( $this->get_field_id( 'title' ) ); ?>">
					<?php echo esc_html__( 'Title:', 'templatic-admin' );?>
					<input class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'title' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo wp_kses_post( esc_attr( $instance['title'] ) ); ?>" />
				</label>
			</p>
			<p>
				<label for="<?php echo wp_kses_post( $this->get_field_id( 'desc' ) ); ?>">
					<?php echo esc_html__( 'Description:', 'templatic-admin' );?>
					<input class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'desc' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'desc' ) ); ?>" type="text" value="<?php echo wp_kses_post( esc_attr( $instance['desc'] ) ); ?>" />
				</label>
			</p>
			<p>
				<label for="<?php echo wp_kses_post( @$this->get_field_id( 'flicker_id' ) ); ?>">
					<?php echo esc_html__( 'Flickr ID:', 'templatic-admin' );?>
					<b> (get id <a href="http://idgettr.com/" target="blank"> here </a>) </b>
					<input class="widefat" id="<?php echo wp_kses_post( @$this->get_field_id( 'flicker_id' ) ); ?>" name="<?php echo wp_kses_post( @$this->get_field_name( 'flicker_id' ) ); ?>" type="text" value="<?php echo wp_kses_post( esc_attr( @$instance['flicker_id'] ) ); ?>" />
				</label>
			</p>
			<p>
				<label for="<?php echo wp_kses_post( @$this->get_field_id( 'flicker_number' ) ); ?>">
					<?php echo esc_html__( 'Number of photos:', 'templatic-admin' );?>
					<input class="widefat" id="<?php echo wp_kses_post( @$this->get_field_id( 'flicker_number' ) ); ?>" name="<?php echo wp_kses_post( @$this->get_field_name( 'flicker_number' ) ); ?>" type="text" value="<?php echo wp_kses_post( esc_attr( @$instance['flicker_number'] ) ); ?>" />
				</label>
			</p>
			<?php
		}
	}
} // End if().


if ( ! class_exists( 'supreme_widget_archives' ) ) {
	/**
	 * Archives widget class.
	 */
	class supreme_widget_archives extends WP_Widget {
		/**
		 * Set up the widget's unique name, ID, class, description, and other options.
		 */
		function __construct() {
			/* Set up the widget options. */
			$widget_options = array(
				'classname'  => 'archives',
				'description' => esc_html__( 'An advanced widget that gives you total control over the output of your archives.', 'templatic-admin' ),
				);
			/* Set up the widget control options. */
			$control_options = array(
				'height' => 350,
				);
			/* Create the widget. */
			parent::__construct(
				'hybrid-archives',
				__( 'Archives', 'templatic-admin' ),
				$widget_options,
				$control_options
			);
		}
		/**
		 * Outputs the widget based on the arguments input through the widget controls.
		 *
		 * @param array $sidebar  agurment of widget area.
		 * @param array $instance instances of widget.
		 */
		function widget( $sidebar, $instance ) {

			extract( $sidebar );
			/* Set the $args for wp_get_archives() to the $instance array. */
			$args = $instance;
			/* Overwrite the $echo argument and set it to false. */
			$args['echo'] = false;
			/* Output the theme's $before_widget wrapper. */
			echo wp_kses_post( $sidebar['before_widget'] );
			/* If a title was input by the user, display it. */
			if ( ! empty( $instance['title'] ) ) {
				echo wp_kses_post( $sidebar['before_title'] . apply_filters( 'widget_title',  $instance['title'], $instance, $this->id_base ) . $sidebar['after_title'] );
			}
			/* Get the archives list. */
			$archives = str_replace( array( "\r", "\n", "\t" ), '', wp_get_archives( $args ) );
			/* If the archives should be shown in a <select> drop-down. */
			if ( 'option' == $args['format'] ) {
				/* Create a title for the drop-down based on the archive type. */
				if ( 'yearly' == $args['type'] ) {
					$option_title = esc_html__( 'Select Year', 'templatic-admin' );
				} elseif ( 'monthly' == $args['type'] ) {
					$option_title = esc_html__( 'Select Month', 'templatic-admin' );
				} elseif ( 'weekly' == $args['type'] ) {
					$option_title = esc_html__( 'Select Week', 'templatic-admin' );
				} elseif ( 'daily' == $args['type'] ) {
					$option_title = esc_html__( 'Select Day', 'templatic-admin' );
				} elseif ( 'postbypost' == $args['type'] || 'alpha' == $args['type'] ) {
					$option_title = esc_html__( 'Select Post', 'templatic-admin' );
				}
				/* Output the <select> element and each <option>. */
				echo '<p><select name="archive-dropdown" onchange=\'document.location.href=this.options[this.selectedIndex].value;\'>';
				echo '<option value="">' . wp_kses_post( $option_title ) . '</option>';
				echo wp_kses_post( $archives );
				echo '</select></p>';
			} elseif ( 'html' == $args['format'] ) {
				echo '<ul class="xoxo archives">' . wp_kses_post( $archives ) . '</ul><!-- .xoxo .archives -->';
			} else {
				echo wp_kses_post( $archives );
			}
			/* Close the theme's widget wrapper. */
			echo wp_kses_post( $sidebar['after_widget'] );
		}
		/**
		 *
		 * Save the widget.
		 *
		 * @param array $new_instance     new instance of widget when saved from widget area.
		 * @param array $old_instance 	  old instances of widget.
		 */
		function update( $new_instance, $old_instance ) {
			$instance = $new_instance;
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['before'] = strip_tags( $new_instance['before'] );
			$instance['after'] = strip_tags( $new_instance['after'] );
			$instance['limit'] = strip_tags( $new_instance['limit'] );
			$instance['show_post_count'] = ( isset( $new_instance['show_post_count'] ) ? 1 : 0 );
			return $instance;
		}
		/**
		 *
		 * Save the widget.
		 *
		 * @param array $instance     instance of widget.
		 */
		function form( $instance ) {
			/* Set up the default form values. */
			$defaults = array(
				'title'          => esc_attr__( 'Archives', 'templatic' ),
				'limit'          => 10,
				'type'           => 'monthly',
				'format'         => 'html',
				'before'         => '',
				'after'          => '',
				'show_post_count' => false,
				);
			/* Merge the user-selected arguments with the defaults. */
			$instance = wp_parse_args( (array) $instance, $defaults );
			$type = array(
						'alpha' 		=> esc_attr__( 'Alphabetical', 'templatic-admin' ),
						'daily' 		=> esc_attr__( 'Daily', 'templatic-admin' ),
						'monthly' 		=> esc_attr__( 'Monthly', 'templatic-admin' ),
						'postbypost'	=> esc_attr__( 'Post By Post', 'templatic-admin' ),
						'weekly'    	=> esc_attr__( 'Weekly', 'templatic-admin' ),
						'yearly'    	=> esc_attr__( 'Yearly', 'templatic-admin' ),
			);

			$format = array(
						'custom' => esc_attr__( 'Custom', 'templatic-admin' ),
						'html'   => esc_attr__( 'HTML', 'templatic-admin' ),
						'option' => esc_attr__( 'Option', 'templatic-admin' ),
					);
			?>
			<p>
				<label for="<?php echo wp_kses_post( $this->get_field_id( 'title' ) ); ?>">
					<?php echo esc_html__( 'Title', 'templatic-admin' ); ?>:
				</label>
				<input type="text" class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'title' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />
			</p>
			<p>
				<label for="<?php echo wp_kses_post( $this->get_field_id( 'limit' ) ); ?>"> <code> limit </code> </label>
				<input type="text" class="widefat smallfat code" id="<?php echo wp_kses_post( $this->get_field_id( 'limit' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'limit' ) ); ?>" value="<?php echo esc_attr( $instance['limit'] ); ?>" />
			</p>
			<p>
				<label for="<?php echo wp_kses_post( $this->get_field_id( 'type' ) ); ?>"> <code> type </code> </label>
				<select class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'type' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'type' ) ); ?>">
					<?php
					foreach ( $type as $option_value => $option_label ) {
						?>
						<option value="<?php echo esc_attr( $option_value ); ?>" <?php selected( $instance['type'], $option_value ); ?>> <?php echo esc_html( $option_label ); ?> </option>
						<?php
					} ?>
				</select>
			</p>
			<p>
				<label for="<?php echo wp_kses_post( $this->get_field_id( 'format' ) ); ?>"> <code> format </code> </label>
				<select  class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'format' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'format' ) ); ?>">
					<?php
					foreach ( $format as $option_value => $option_label ) {
						?>
						<option value="<?php echo esc_attr( $option_value ); ?>" <?php selected( $instance['format'], $option_value ); ?>> <?php echo esc_html( $option_label ); ?> </option>
						<?php
					} ?>
				</select>
			</p>
			<p>
				<label for="<?php echo wp_kses_post( $this->get_field_id( 'before' ) ); ?>"> <code> before </code> </label>
				<input type="text" class="widefat code" id="<?php echo wp_kses_post( $this->get_field_id( 'before' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'before' ) ); ?>" value="<?php echo esc_attr( $instance['before'] ); ?>" />
			</p>
			<p>
				<label for="<?php echo wp_kses_post( $this->get_field_id( 'after' ) ); ?>"> <code> after </code> </label>
				<input type="text" class="widefat code" id="<?php echo wp_kses_post( $this->get_field_id( 'after' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'after' ) ); ?>" value="<?php echo esc_attr( $instance['after'] ); ?>" />
			</p>
			<p>
				<label for="<?php echo wp_kses_post( $this->get_field_id( 'show_post_count' ) ); ?>">
					<input class="checkbox" type="checkbox" <?php checked( $instance['show_post_count'], true ); ?> id="<?php echo wp_kses_post( $this->get_field_id( 'show_post_count' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'show_post_count' ) ); ?>" />
					<?php echo esc_html__( 'Show post count?', 'templatic-admin' ); ?>
					<code> show_post_count </code> </label>
			</p>
			<div style="clear:both;"> &nbsp; </div>
			<?php
		}
	}
} // End if().

/**
 * Templatic Text widget Class.
 **/
class templatic_text extends WP_Widget{
	/**
	 * Constructor.
	 */
	function __construct() {

		$widget_ops = array(
						'classname' => 'templatic_text',
						'description' => esc_html__( 'Arbitrary text or HTML', 'templatic-admin' ),
						'before_widget' => '<div class="column_wrap">',
					);
		parent::__construct( 'templatic_text', esc_html__( 'Text', 'templatic-admin' ), $widget_ops );
	}
	/**
	 * Outputs the widget based on the arguments input through the widget controls.
	 *
	 * @param array $args 		agurment of widget area.
	 * @param array $instance 	instances of widget.
	 */
	function widget( $args, $instance ) {
		extract( $args, EXTR_SKIP );
		$title = empty( $instance['title'] ) ? '' : apply_filters( 'widget_title', $instance['title'] );
		$about_us = empty( $instance['text'] ) ? '' : apply_filters( 'widget_text', $instance['text'] );
		echo wp_kses_post( $args['before_widget'] );
		if ( function_exists( 'icl_register_string' ) ) {
			icl_register_string( 'templatic' , $args['widget_id'] . '_title' . $title,$title );
			$title    = icl_t( 'templatic', $args['widget_id'] . '_title' . $title,$title );

			icl_register_string( 'templatic' , $args['widget_id'] . '_description' , $about_us );
			$about_us = icl_t( 'templatic', $args['widget_id'] . '_description' , $about_us );
		}
		if ( '' <> $title ) {
			echo wp_kses_post( $args['before_title'] . $title . $args['after_title'] );
		}
		?>
		<div class="textwidget">
			<?php echo $about_us;?>
		</div>
		<?php
		echo wp_kses_post( $args['after_widget'] );
	}
	/**
	 *
	 * Save the widget.
	 *
	 * @param array $new_instance     new instance of widget when saved from widget area.
	 * @param array $old_instance 	  old instances of widget.
	 */
	function update( $new_instance, $old_instance ) {

		$instance = $old_instance;
		$instance['title'] = $new_instance['title'];
		$instance['text'] = $new_instance['text'];
		return $instance;
	}
	/**
	 *
	 * Save the widget.
	 *
	 * @param array $instance     instance of widget.
	 */
	function form( $instance ) {

		$instance = wp_parse_args( (array) $instance, array(
														'title' => '',
														'text' => '',
														)
		);
		$title = $instance['title'];
		$text  = trim( $instance['text'] );
		?>
		<p>
			<label for="<?php echo wp_kses_post( $this->get_field_id( 'title' ) ); ?>">
				<?php echo esc_html__( 'Title:', 'templatic-admin' );?>
				<input class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'title' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</label>
		</p>
		<p>
			<label for="<?php echo wp_kses_post( $this->get_field_id( 'text' ) ); ?>">
				<?php echo esc_html__( 'Description:', 'templatic-admin' );?>
				<textarea class="widefat" name="<?php echo wp_kses_post( $this->get_field_name( 'text' ) ); ?>" cols="20" rows="16"><?php echo esc_attr( $text ); ?></textarea>
			</label>
		</p>
		<?php
	}
}

if ( ! class_exists( ' supreme_authors_widget' ) ) {
	/**
	 * Authors Widget Class.
	 **/
	class supreme_authors_widget extends WP_Widget {
		/**
		 * Set up the widget's unique name, ID, class, description, and other options.
		 */
		function __construct() {

			$widget_options = array(
								'classname'  => 'authors',
								'description' => esc_html__( 'An advanced widget that gives you total control over the output of your author lists.', 'templatic-admin' ),
							);
			/* Set up the widget control options. */
			$control_options = array(
				'height' => 350,
				);
			/* Create the widget. */
			parent::__construct(
				'hybrid-authors',
				__( 'Authors', 'templatic-admin' ),
				$widget_options,
				$control_options
			);
		}
		/**
		 * Outputs the widget based on the arguments input through the widget controls.
		 *
		 * @param array $sidebar 	agurment of widget area.
		 * @param array $instance 	instances of widget.
		 */
		function widget( $sidebar, $instance ) {
			extract( $sidebar );
			/* Set the $args for wp_list_authors() to the $instance array. */
			$args = $instance;
			/* Overwrite the $echo argument and set it to false. */
			$args['echo'] = false;
			/* Output the theme's $args['before_widget'] wrapper. */
			echo wp_kses_post( $args['before_widget'] );
			/* If a title was input by the user, display it. */
			if ( ! empty( $instance['title'] ) ) {
				echo wp_kses_post( $sidebar['before_title'] . apply_filters( 'widget_title',  $instance['title'], $instance, $this->id_base ) . $sidebar['after_title'] );
			}
			/* Get the authors list. */
			$authors = str_replace( array( "\r", "\n", "\t" ), '', wp_list_authors( $args ) );
			/* If 'list' is the style and the output should be HTML, wrap the authors in a <ul>. */
			if ( 'list' == $args['style'] && $args['html'] ) {
				$authors = '<ul class="xoxo authors">' . $authors . '</ul><!-- .xoxo .authors -->';
			}
			/* Display the authors list. */
			echo wp_kses_post( $authors );
			/* Close the theme's widget wrapper. */
			echo wp_kses_post( $args['after_widget'] );
		}
		/**
		 *
		 * Save the widget.
		 *
		 * @param array $new_instance     new instance of widget when saved from widget area.
		 * @param array $old_instance 	  old instances of widget.
		 */
		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$instance = $new_instance;
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['feed'] = strip_tags( $new_instance['feed'] );
			$instance['order'] = strip_tags( $new_instance['order'] );
			$instance['orderby'] = strip_tags( $new_instance['orderby'] );
			$instance['number'] = strip_tags( $new_instance['number'] );
			$instance['html'] = ( isset( $new_instance['html'] ) ? 1 : 0 );
			$instance['optioncount'] = ( isset( $new_instance['optioncount'] ) ? 1 : 0 );
			$instance['exclude_admin'] = ( isset( $new_instance['exclude_admin'] ) ? 1 : 0 );
			$instance['show_fullname'] = ( isset( $new_instance['show_fullname'] ) ? 1 : 0 );
			$instance['hide_empty'] = ( isset( $new_instance['hide_empty'] ) ? 1 : 0 );
			return $instance;
		}
		/**
		 *
		 * Save the widget.
		 *
		 * @param array $instance     instance of widget.
		 */
		function form( $instance ) {
			/* Set up the default form values. */
			$defaults = array(
				'title'         => esc_attr__( 'Authors', 'templatic' ),
				'order'         => 'ASC',
				'orderby'       => 'display_name',
				'number'        => '',
				'optioncount'   => false,
				'exclude_admin' => false,
				'show_fullname' => true,
				'hide_empty'    => true,
				'style'         => 'list',
				'html'          => true,
				'feed'          => '',
				'feed_image'    => '',
				);
			/* Merge the user-selected arguments with the defaults. */
			$instance = wp_parse_args( (array) $instance, $defaults );
			$order = array(
						'ASC' => esc_attr__( 'Ascending', 'templatic-admin' ),
						'DESC' => esc_attr__( 'Descending', 'templatic-admin' ),
					);
			$orderby = array(
						'display_name' => esc_attr__( 'Display Name', 'templatic-admin' ),
						'email'       => esc_attr__( 'Email', 'templatic-admin' ),
						'ID'          => esc_attr__( 'ID', 'templatic-admin' ),
						'nicename'    => esc_attr__( 'Nice Name', 'templatic-admin' ),
						'post_count'  => esc_attr__( 'Post Count', 'templatic-admin' ),
						'registered'  => esc_attr__( 'Registered', 'templatic-admin' ),
						'url'         => esc_attr__( 'URL', 'templatic-admin' ),
						'user_login'  => esc_attr__( 'Login', 'templatic-admin' ),
					);
			?>
	<div class="hybrid-widget-controls columns-2">
		<p>
			<label for="<?php echo wp_kses_post( $this->get_field_id( 'title' ) ); ?>">
				<?php echo esc_html__( 'Title:', 'templatic-admin' ); ?>
			</label>
			<input type="text" class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'title' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>
		<p>
			<label for="<?php echo wp_kses_post( $this->get_field_id( 'order' ) ); ?>"> <code> order </code> </label>
			<select class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'order' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'order' ) ); ?>">
				<?php
				foreach ( $order as $option_value => $option_label ) {
					?>
					<option value="<?php echo esc_attr( $option_value ); ?>" <?php selected( $instance['order'], $option_value ); ?>> <?php echo esc_html( $option_label ); ?> </option>
					<?php
				} ?>
			</select>
		</p>
		<p>
			<label for="<?php echo wp_kses_post( $this->get_field_id( 'orderby' ) ); ?>"> <code> orderby </code> </label>
			<select class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'orderby' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'orderby' ) ); ?>">
				<?php
				foreach ( $orderby as $option_value => $option_label ) {
					?>
					<option value="<?php echo esc_attr( $option_value ); ?>" <?php selected( $instance['orderby'], $option_value ); ?>> <?php echo esc_html( $option_label ); ?> </option>
					<?php
				} ?>
			</select>
		</p>
		<p>
			<label for="<?php echo wp_kses_post( $this->get_field_id( 'number' ) ); ?>"> <code> number </code> </label>
			<input type="text" class="smallfat code" id="<?php echo wp_kses_post( $this->get_field_id( 'number' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'number' ) ); ?>" value="<?php echo esc_attr( $instance['number'] ); ?>" />
		</p>
		<p>
			<label for="<?php echo wp_kses_post( $this->get_field_id( 'style' ) ); ?>"> <code> style </code> </label>
			<select class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'style' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'style' ) ); ?>">
				<?php
				foreach ( array(
							'list' => esc_attr__( 'List', 'templatic-admin' ),
							'none' => esc_attr__( 'None', 'templatic-admin' ),
						) as $option_value => $option_label ) {
					?>
					<option value="<?php echo esc_attr( $option_value ); ?>" <?php selected( $instance['style'], $option_value ); ?>> <?php echo esc_html( $option_label ); ?> </option>
					<?php
				} ?>
			</select>
		</p>
	</div>
	<div class="hybrid-widget-controls columns-2 column-last">
		<p>
			<label for="<?php echo wp_kses_post( $this->get_field_id( 'feed' ) ); ?>"> <code> feed </code> </label>
			<input type="text" class="widefat code" id="<?php echo wp_kses_post( $this->get_field_id( 'feed' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'feed' ) ); ?>" value="<?php echo esc_attr( $instance['feed'] ); ?>" />
		</p>
		<p>
			<label for="<?php echo wp_kses_post( $this->get_field_id( 'feed_image' ) ); ?>"> <code> feed_image </code> </label>
			<input type="text" class="widefat code" id="<?php echo wp_kses_post( $this->get_field_id( 'feed_image' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'feed_image' ) ); ?>" value="<?php echo esc_attr( $instance['feed_image'] ); ?>" />
		</p>
		<p>
			<label for="<?php echo wp_kses_post( $this->get_field_id( 'html' ) ); ?>">
				<input class="checkbox" type="checkbox" <?php checked( $instance['html'], true ); ?> id="<?php echo wp_kses_post( $this->get_field_id( 'html' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'html' ) ); ?>" />
				<?php echo '<acronym title="Hypertext Markup Language">' . esc_html__( 'HTML', 'templatic-admin' ) . '</acronym>?'; ?>
				<code> html </code> </label>
			</p>
			<p>
				<label for="<?php echo wp_kses_post( $this->get_field_id( 'optioncount' ) ); ?>">
					<input class="checkbox" type="checkbox" <?php checked( $instance['optioncount'], true ); ?> id="<?php echo wp_kses_post( $this->get_field_id( 'optioncount' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'optioncount' ) ); ?>" />
					<?php echo esc_html__( 'Show post count?', 'templatic-admin' ); ?>
					<code> optioncount </code> </label>
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'exclude_admin' ) ); ?>">
						<input class="checkbox" type="checkbox" <?php checked( $instance['exclude_admin'], true ); ?> id="<?php echo wp_kses_post( $this->get_field_id( 'exclude_admin' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'exclude_admin' ) ); ?>" />
						<?php echo esc_html__( 'Exclude admin?', 'templatic-admin' ); ?>
						<code> exclude_admin </code> </label>
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'show_fullname' ) ); ?>">
						<input class="checkbox" type="checkbox" <?php checked( $instance['show_fullname'], true ); ?> id="<?php echo wp_kses_post( $this->get_field_id( 'show_fullname' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'show_fullname' ) ); ?>" />
						<?php echo esc_html__( 'Show full name?', 'templatic-admin' ); ?>
						<code> show_fullname </code> </label>
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'hide_empty' ) ); ?>">
						<input class="checkbox" type="checkbox" <?php checked( $instance['hide_empty'], true ); ?> id="<?php echo wp_kses_post( $this->get_field_id( 'hide_empty' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'hide_empty' ) ); ?>" />
						<?php echo esc_html__( 'Hide empty?', 'templatic-admin' ); ?>
						<code> hide_empty </code> </label>
				</p>
			</div>
			<div style="clear:both;"> &nbsp; </div>
			<?php
		}
	}
} // End if().

if ( ! class_exists( 'supreme_bookmarks_widget' ) ) {
	/**
	 * Bookmarks Widget Class.
	 **/
	class supreme_bookmarks_widget extends WP_Widget {
		/**
		 * Set up the widget's unique name, ID, class, description, and other options.
		 */
		function __construct() {
			/* Set up the widget options. */
			$widget_options = array(
				'classname'  => 'bookmarks',
				'description' => esc_html__( 'An advanced widget that gives you total control over the output of your bookmarks (links).',  'templatic-admin' ),
				);
			/* Set up the widget control options. */
			$control_options = array(
				'height' => 350,
				);
			/* Create the widget. */
			parent::__construct(
				'hybrid-authors',
				__( 'Authors', 'templatic-admin' ),
				$widget_options,
				$control_options
			);
		}
		/**
		 * Outputs the widget based on the arguments input through the widget controls.
		 *
		 * @param array $sidebar 	agurment of widget area.
		 * @param array $instance 	instances of widget.
		 */
		function widget( $sidebar, $instance ) {
			extract( $sidebar );
			/* Set up the $before_widget ID for multiple widgets created by the bookmarks widget. */
			if ( ! empty( $instance['categorize'] ) ) {
				$before_widget = preg_replace( '/id="[^"]*"/', 'id="%id"', $args['before_widget'] );
			}
			/* Add a class to $before_widget if one is set. */
			if ( ! empty( $instance['class'] ) ) {
				$before_widget = str_replace( 'class="', 'class="' . esc_attr( $instance['class'] ) . ' ', $before_widget );
			}
			/* Set the $args for wp_list_bookmarks() to the $instance array. */
			$args = $instance;
			/* wp_list_bookmarks() hasn't been updated in WP to use wp_parse_id_list(), so we have to pass strings for includes/excludes. */
			if ( ! empty( $args['category'] ) && is_array( $args['category'] ) ) {
				$args['category'] = join( ', ', $args['category'] );
			}
			if ( ! empty( $args['exclude_category'] ) && is_array( $args['exclude_category'] ) ) {
				$args['exclude_category'] = join( ', ', $args['exclude_category'] );
			}
			if ( ! empty( $args['include'] ) && is_array( $args['include'] ) ) {
				$args['include'] = join( ',', $args['include'] );
			}
			if ( ! empty( $args['exclude'] ) && is_array( $args['exclude'] ) ) {
				$args['exclude'] = join( ',', $args['exclude'] );
			}
			/* If no limit is given, set it to -1. */
			$args['limit'] = empty( $args['limit'] ) ? - 1 : $args['limit'];
			/* Some arguments must be set to the sidebar arguments to be output correctly. */
			$args['title_li'] = apply_filters( 'widget_title', ( empty( $args['title_li'] ) ? esc_html__( 'Bookmarks', 'templatic' ) : $args['title_li'] ), $instance, $this->id_base );
			$args['title_before'] = $args['before_title'];
			$args['title_after'] = $args['after_title'];
			$args['category_before'] = $before_widget;
			$args['category_after'] = $after_widget;
			$args['category_name'] = '';
			$args['echo'] = false;
			/* Output the bookmarks widget. */
			$bookmarks = str_replace( array( "\r", "\n", "\t" ), '', wp_list_bookmarks( $args ) );
			/* If no title is given and the bookmarks aren't categorized, add a wrapper <ul>. */
			if ( empty( $args['title_li'] ) && false === $args['categorize'] ) {
				$bookmarks = '<ul class="xoxo bookmarks">' . $bookmarks . '</ul>';
			}
			/* Output the bookmarks. */
			echo wp_kses_post( $bookmarks );
		}
		/**
		 *
		 * Save the widget.
		 *
		 * @param array $new_instance     new instance of widget when saved from widget area.
		 * @param array $old_instance 	  old instances of widget.
		 */
		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			/* Set the instance to the new instance. */
			$instance = $new_instance;
			$instance['title_li'] = strip_tags( $new_instance['title_li'] );
			$instance['limit'] = strip_tags( $new_instance['limit'] );
			$instance['class'] = strip_tags( $new_instance['class'] );
			$instance['search'] = strip_tags( $new_instance['search'] );
			$instance['category_order'] = $new_instance['category_order'];
			$instance['category_orderby'] = $new_instance['category_orderby'];
			$instance['orderby'] = $new_instance['orderby'];
			$instance['order'] = $new_instance['order'];
			$instance['between'] = $new_instance['between'];
			$instance['link_before'] = $new_instance['link_before'];
			$instance['link_after'] = $new_instance['link_after'];
			$instance['categorize'] = ( isset( $new_instance['categorize'] ) ? 1 : 0 );
			$instance['hide_invisible'] = ( isset( $new_instance['hide_invisible'] ) ? 1 : 0 );
			$instance['show_private'] = ( isset( $new_instance['show_private'] ) ? 1 : 0 );
			$instance['show_rating'] = ( isset( $new_instance['show_rating'] ) ? 1 : 0 );
			$instance['show_updated'] = ( isset( $new_instance['show_updated'] ) ? 1 : 0 );
			$instance['show_images'] = ( isset( $new_instance['show_images'] ) ? 1 : 0 );
			$instance['show_name'] = ( isset( $new_instance['show_name'] ) ? 1 : 0 );
			$instance['show_description'] = ( isset( $new_instance['show_description'] ) ? 1 : 0 );
			return $instance;
		}
		/**
		 *
		 * Save the widget.
		 *
		 * @param array $instance     instance of widget.
		 */
		function form( $instance ) {
			/* Set up the default form values. */
			$defaults = array(
				'title_li'        => esc_attr__( 'Bookmarks', 'templatic' ),
				'categorize'      => true,
				'category_order'  => 'ASC',
				'category_orderby' => 'name',
				'category'         => array(),
				'exclude_category' => array(),
				'limit'           => - 1,
				'order'           => 'ASC',
				'orderby'         => 'name',
				'include'          => array(),
				'exclude'          => array(),
				'search'          => '',
				'hide_invisible'  => true,
				'show_description' => false,
				'show_images'     => false,
				'show_rating'     => false,
				'show_updated'    => false,
				'show_private'    => false,
				'show_name'       => false,
				'class'           => 'linkcat',
				'link_before'     => '<span>',
				'link_after'      => '</span>',
				'between'         => '<br />',
				);
			/* Merge the user-selected arguments with the defaults. */
			$instance = wp_parse_args( (array) $instance, $defaults );
			$terms     = get_terms( 'link_category' );
			$bookmarks = get_bookmarks( array(
											'hide_invisible' => false,
										)
			);
			$category_order = array(
								'ASC' => esc_attr__( 'Ascending', 'templatic' ),
								'DESC' => esc_attr__( 'Descending', 'templatic' ),
							);
			$category_orderby = array(
								'count' => esc_attr__( 'Count', 'templatic' ),
								'ID'   => esc_attr__( 'ID', 'templatic' ),
								'name' => esc_attr__( 'Name', 'templatic' ),
								'slug' => esc_attr__( 'Slug', 'templatic' ),
							);
			$order = array(
						'ASC' => esc_attr__( 'Ascending', 'templatic' ),
						'DESC' => esc_attr__( 'Descending', 'templatic' ),
					);
			$orderby = array(
						'id' => esc_attr__( 'ID', 'templatic' ),
						'description' => esc_attr__( 'Description',  'templatic' ),
						'length'     => esc_attr__( 'Length',  'templatic' ),
						'name'       => esc_attr__( 'Name',  'templatic' ),
						'notes'      => esc_attr__( 'Notes',  'templatic' ),
						'owner'      => esc_attr__( 'Owner',  'templatic' ),
						'rand'       => esc_attr__( 'Random',  'templatic' ),
						'rating'     => esc_attr__( 'Rating',  'templatic' ),
						'rel'        => esc_attr__( 'Rel',  'templatic' ),
						'rss'        => esc_attr__( 'RSS',  'templatic' ),
						'target'     => esc_attr__( 'Target',  'templatic' ),
						'updated'    => esc_attr__( 'Updated',  'templatic' ),
						'url'        => esc_attr__( 'URL',  'templatic' ),
					);
			?>
			<div class="hybrid-widget-controls columns-3">
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'title_li' ) ); ?>">
						<?php echo esc_html__( 'Title:', 'templatic' ); ?>
					</label>
					<input type="text" class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'title_li' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'title_li' ) ); ?>" value="<?php echo esc_attr( $instance['title_li'] ); ?>" />
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'category_order' ) ); ?>"> <code> category_order </code> </label>
					<select class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'category_order' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'category_order' ) ); ?>">
						<?php
						foreach ( $category_order as $option_value => $option_label ) { ?>
						<option value="<?php echo esc_attr( $option_value ); ?>" <?php selected( $instance['category_order'], $option_value ); ?>> <?php echo esc_html( $option_label ); ?> </option>
						<?php } ?>
					</select>
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'category_orderby' ) ); ?>"> <code> category_orderby </code> </label>
					<select class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'category_orderby' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'category_orderby' ) ); ?>">
						<?php
						foreach ( $category_orderby as $option_value => $option_label ) {
							?>
							<option value="<?php echo esc_attr( $option_value ); ?>" <?php selected( $instance['category_orderby'], $option_value ); ?>> <?php echo esc_html( $option_label ); ?> </option>
							<?php
						} ?>
					</select>
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'category' ) ); ?>"> <code> category </code> </label>
					<select class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'category' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'category' ) ); ?>[]" size="4" multiple="multiple">
						<?php
						foreach ( $terms as $term ) {
							?>
							<option value="<?php echo esc_attr( $term->term_id ); ?>" <?php echo ( in_array( $term->term_id, (array) $instance['category'] ) ? 'selected="selected"' : '' ); ?>> <?php echo esc_html( $term->name ); ?> </option>
							<?php
						} ?>
					</select>
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'exclude_category' ) ); ?>"> <code> exclude_category </code> </label>
					<select class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'exclude_category' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'exclude_category' ) ); ?>[]" size="4" multiple="multiple">
						<?php
						foreach ( $terms as $term ) {
							?>
							<option value="<?php echo esc_attr( $term->term_id ); ?>" <?php echo ( in_array( $term->term_id, (array) $instance['exclude_category'] ) ? 'selected="selected"' : '' ); ?>> <?php echo esc_html( $term->name ); ?> </option>
							<?php
						} ?>
					</select>
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'class' ) ); ?>"> <code> class </code> </label>
					<input type="text" class="smallfat code" id="<?php echo wp_kses_post( $this->get_field_id( 'class' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'class' ) ); ?>" value="<?php echo esc_attr( $instance['class'] ); ?>" />
				</p>
			</div>


			<div class="hybrid-widget-controls columns-3">
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'limit' ) ); ?>"> <code> limit </code> </label>
					<input type="text" class="smallfat code" id="<?php echo wp_kses_post( $this->get_field_id( 'limit' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'limit' ) ); ?>" value="<?php echo esc_attr( $instance['limit'] ); ?>" />
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'order' ) ); ?>"> <code> order </code> </label>
					<select class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'order' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'order' ) ); ?>">
						<?php
						foreach ( $order as $option_value => $option_label ) {
							?>
							<option value="<?php echo esc_attr( $option_value ); ?>" <?php selected( $instance['order'], $option_value ); ?>> <?php echo esc_html( $option_label ); ?> </option>
							<?php } ?>
						</select>
					</p>
					<p>
						<label for="<?php echo wp_kses_post( $this->get_field_id( 'orderby' ) ); ?>"> <code> orderby </code> </label>
						<select class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'orderby' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'orderby' ) ); ?>">
							<?php
							foreach ( $orderby as $option_value => $option_label ) { ?>
							<option value="<?php echo esc_attr( $option_value ); ?>" <?php selected( $instance['orderby'], $option_value ); ?>> <?php echo esc_html( $option_label ); ?> </option>
							<?php } ?>
						</select>
					</p>
					<p>
						<label for="<?php echo wp_kses_post( $this->get_field_id( 'include' ) ); ?>"> <code> include </code> </label>
						<select class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'include' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'include' ) ); ?>[]" size="4" multiple="multiple">
							<?php
							foreach ( $bookmarks as $bookmark ) {
								?>
								<option value="<?php echo esc_attr( $bookmark->link_id ); ?>" <?php echo ( in_array( $bookmark->link_id, (array) $instance['include'] ) ? 'selected="selected"' : '' ); ?>> <?php echo esc_html( $bookmark->link_name ); ?> </option>
								<?php
							} ?>
						</select>
					</p>
					<p>
						<label for="<?php echo wp_kses_post( $this->get_field_id( 'exclude' ) ); ?>"> <code> exclude </code> </label>
						<select class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'exclude' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'exclude' ) ); ?>[]" size="4" multiple="multiple">
							<?php foreach ( $bookmarks as $bookmark ) { ?>
							<option value="<?php echo esc_attr( $bookmark->link_id ); ?>" <?php echo ( in_array( $bookmark->link_id, (array) $instance['exclude'] ) ? 'selected="selected"' : '' ); ?>> <?php echo esc_html( $bookmark->link_name ); ?> </option>
							<?php } ?>
						</select>
					</p>
					<p>
						<label for="<?php echo wp_kses_post( $this->get_field_id( 'search' ) ); ?>"> <code> search </code> </label>
						<input type="text" class="widefat code" id="<?php echo wp_kses_post( $this->get_field_id( 'search' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'search' ) ); ?>" value="<?php echo esc_attr( $instance['search'] ); ?>" />
					</p>
				</div>


				<div class="hybrid-widget-controls columns-3 column-last">
					<p>
						<label for="<?php echo wp_kses_post( $this->get_field_id( 'between' ) ); ?>"> <code> between </code> </label>
						<input type="text" class="smallfat code" id="<?php echo wp_kses_post( $this->get_field_id( 'between' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'between' ) ); ?>" value="<?php echo esc_attr( $instance['between'] ); ?>" />
					</p>
					<p>
						<label for="<?php echo wp_kses_post( $this->get_field_id( 'link_before' ) ); ?>"> <code> link_before </code> </label>
						<input type="text" class="smallfat code" id="<?php echo wp_kses_post( $this->get_field_id( 'link_before' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'link_before' ) ); ?>" value="<?php echo esc_attr( $instance['link_before'] ); ?>" />
					</p>
					<p>
						<label for="<?php echo wp_kses_post( $this->get_field_id( 'link_after' ) ); ?>"> <code> link_after </code> </label>
						<input type="text" class="smallfat code" id="<?php echo wp_kses_post( $this->get_field_id( 'link_after' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'link_after' ) ); ?>" value="<?php echo esc_attr( $instance['link_after'] ); ?>" />
					</p>
					<p>
						<label for="<?php echo wp_kses_post( $this->get_field_id( 'categorize' ) ); ?>">
							<input class="checkbox" type="checkbox" <?php checked( $instance['categorize'], true ); ?> id="<?php echo wp_kses_post( $this->get_field_id( 'categorize' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'categorize' ) ); ?>" />
							<?php echo esc_html__( 'Categorize?', 'templatic' ); ?>
							<code> categorize </code> </label>
					</p>
					<p>
						<label for="<?php echo wp_kses_post( $this->get_field_id( 'show_description' ) ); ?>">
							<input class="checkbox" type="checkbox" <?php checked( $instance['show_description'], true ); ?> id="<?php echo wp_kses_post( $this->get_field_id( 'show_description' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'show_description' ) ); ?>" />
							<?php echo esc_html__( 'Show description?', 'templatic' ); ?>
							<code> show_description </code> </label>
					</p>
					<p>
						<label for="<?php echo wp_kses_post( $this->get_field_id( 'hide_invisible' ) ); ?>">
							<input class="checkbox" type="checkbox" <?php checked( $instance['hide_invisible'], true ); ?> id="<?php echo wp_kses_post( $this->get_field_id( 'hide_invisible' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'hide_invisible' ) ); ?>" />
							<?php echo esc_html__( 'Hide invisible?', 'templatic' ); ?>
							<code> hide_invisible </code> </label>
						</p>
						<p>
							<label for="<?php echo wp_kses_post( $this->get_field_id( 'show_rating' ) ); ?>">
								<input class="checkbox" type="checkbox" <?php checked( $instance['show_rating'], true ); ?> id="<?php echo wp_kses_post( $this->get_field_id( 'show_rating' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'show_rating' ) ); ?>" />
								<?php echo esc_html__( 'Show rating?', 'templatic' ); ?>
								<code> show_rating </code> </label>
						</p>
						<p>
							<label for="<?php echo wp_kses_post( $this->get_field_id( 'show_updated' ) ); ?>">
								<input class="checkbox" type="checkbox" <?php checked( $instance['show_updated'], true ); ?> id="<?php echo wp_kses_post( $this->get_field_id( 'show_updated' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'show_updated' ) ); ?>" />
								<?php echo esc_html__( 'Show updated?', 'templatic' ); ?>
								<code> show_updated </code> </label>
						</p>
						<p>
							<label for="<?php echo wp_kses_post( $this->get_field_id( 'show_images' ) ); ?>">
								<input class="checkbox" type="checkbox" <?php checked( $instance['show_images'], true ); ?> id="<?php echo wp_kses_post( $this->get_field_id( 'show_images' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'show_images' ) ); ?>" />
								<?php echo esc_html__( 'Show images?', 'templatic' ); ?>
								<code> show_images </code> </label>
						</p>
						<p>
							<label for="<?php echo wp_kses_post( $this->get_field_id( 'show_name' ) ); ?>">
								<input class="checkbox" type="checkbox" <?php checked( $instance['show_name'], true ); ?> id="<?php echo wp_kses_post( $this->get_field_id( 'show_name' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'show_name' ) ); ?>" />
								<?php echo esc_html__( 'Show name?', 'templatic' ); ?>
								<code> show_name </code> </label>
						</p>
						<p>
							<label for="<?php echo wp_kses_post( $this->get_field_id( 'show_private' ) ); ?>">
								<input class="checkbox" type="checkbox" <?php checked( $instance['show_private'], true ); ?> id="<?php echo wp_kses_post( $this->get_field_id( 'show_private' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'show_private' ) ); ?>" />
								<?php echo esc_html__( 'Show private?', 'templatic' ); ?>
								<code> show_private </code> </label>
						</p>
					</div>
				<div style="clear:both;"> &nbsp; </div>
				<?php
		}
	}
} // End if().

if ( ! class_exists( 'supreme_calendar_widget' ) ) {
	/**
	 * Calendar Widget Class.
	 **/
	class supreme_calendar_widget extends WP_Widget {
		/**
		 * Set up the widget's unique name, ID, class, description, and other options.
		 */
		function __construct() {
			/* Set up the widget options. */
			$widget_options = array(
				'classname'  => 'calendar',
				'description' => esc_html__( 'An advanced widget that gives you total control over the output of your calendar.', 'templatic-admin' ),
				);
			/* Set up the widget control options. */
			$control_options = array(
							'height' => 350,
						);
			/* Create the widget. */
			parent::__construct(
				'hybrid-calendar',
				__( 'Calendar', 'templatic-admin' ),
				$widget_options,
				$control_options
			);
		}
		/**
		 * Outputs the widget based on the arguments input through the widget controls.
		 *
		 * @param array $sidebar 	agurment of widget area.
		 * @param array $instance 	instances of widget.
		 */
		function widget( $sidebar, $instance ) {
			extract( $sidebar );
			/* Get the $initial argument. */
			$initial = ! empty( $instance['initial'] ) ? true : false;
			/* Output the theme's widget wrapper. */
			echo wp_kses_post( $args['before_widget'] );
			/* If a title was input by the user, display it. */
			if ( ! empty( $instance['title'] ) ) {
				echo wp_kses_post( $sidebar['before_title'] . apply_filters( 'widget_title',  $instance['title'], $instance, $this->id_base ) . $sidebar['after_title'] );
			}
			/* Display the calendar. */
			echo '<div class="calendar-wrap">';
			echo str_replace( array( "\r", "\n", "\t" ), '', get_calendar( $initial, false ) );
			echo '</div><!-- .calendar-wrap -->';
			/* Close the theme's widget wrapper. */
			echo wp_kses_post( $args['after_widget'] );
		}
		/**
		 *
		 * Updates the widget control options for the particular instance of the widget.
		 *
		 * @param array $new_instance     new instance of widget when saved from widget area.
		 * @param array $old_instance 	  old instances of widget.
		 */
		function update( $new_instance, $old_instance ) {
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['initial'] = ( isset( $new_instance['initial'] ) ? 1 : 0 );
			return $instance;
		}
		/**
		 *
		 * Save the widget.
		 *
		 * @param array $instance     instance of widget.
		 */
		function form( $instance ) {
			/* Set up the default form values. */
			$defaults = array(
				'title'  => esc_attr__( 'Calendar', 'templatic' ),
				'initial' => false,
				);
			/* Merge the user-selected arguments with the defaults. */
			$instance = wp_parse_args( (array) $instance, $defaults ); ?>
			<div class="hybrid-widget-controls columns-1">
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'title' ) ); ?>">
						<?php echo esc_html__( 'Title:', 'templatic' ); ?>
					</label>
					<input type="text" class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'title' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />
				</p>
				<p>
					<input class="checkbox" type="checkbox" <?php checked( $instance['initial'], true ); ?> id="<?php echo wp_kses_post( $this->get_field_id( 'initial' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'initial' ) ); ?>" />
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'initial' ) ); ?>">
						<?php echo esc_html__( 'One-letter abbreviation?', 'templatic' ); ?>
						<code>
							<?php echo esc_html__( 'initial', 'templatic' ); ?>
						</code>
					</label>
				</p>
			</div>
			<?php
		}
	}
} // End if().

if ( ! class_exists( 'supreme_categories_widget' ) ) {
	/**
	 * Categories Widget Class.
	 **/
	class supreme_categories_widget extends WP_Widget {
		/**
		 * Set up the widget's unique name, ID, class, description, and other options.
		 */
		function __construct() {
			/* Set up the widget options. */
			$widget_options = array(
				'classname'  => 'categories',
				'description' => esc_html__( 'An advanced widget that gives you total control over the output of your category links.', 'templatic-admin' ),
				);
			/* Set up the widget control options. */
			$control_options = array(
				'height' => 350,
				);
			/* Create the widget. */
			parent::__construct(
				'hybrid-categories',
				__( 'Categories', 'templatic-admin' ),
				$widget_options,
				$control_options
			);
		}
		/**
		 * Outputs the widget based on the arguments input through the widget controls.
		 *
		 * @param array $sidebar 	agurment of widget area.
		 * @param array $instance 	instances of widget.
		 */
		function widget( $sidebar, $instance ) {
			extract( $sidebar );
			/* Set the $args for wp_list_categories() to the $instance array. */
			$args = $instance;
			/* Set the $title_li and $echo arguments to false. */
			$args['title_li'] = false;
			$args['echo'] = false;
			$args['pad_counts'] = false;

			/* Output the theme's widget wrapper. */
			echo wp_kses_post( $sidebar['before_widget'] );
			/* If a title was input by the user, display it. */
			if ( ! empty( $instance['title'] ) ) {
				echo wp_kses_post( $sidebar['before_title'] . apply_filters( 'widget_title',  $instance['title'], $instance, $this->id_base ) . $sidebar['after_title'] );
			}

			/* Get the categories list. */
			do_action( 'tevolution_category_query' );

			$categories = str_replace( array( "\r", "\n", "\t" ), '', wp_list_categories( $args ) );
			/* If 'list' is the user-selected style, wrap the categories in an unordered list. */

			$d = ! empty( $instance['dropdown'] ) ? '1' : '0';

			if ( $d ) {

				$tmpl_rand_id = 'tmpl_widget_' . rand( 1, 999 );
				$dropdown_args = array(
					'taxonomy'           => $args['taxonomy'],
					'order'              => $args['order'],
					'orderby'            => $args['orderby'],
					'depth'              => $args['depth'],
					'exclude'            => $args['exclude'],
					'include'            => $args['include'],
					'child_of'           => $args['child_of'],
					'hierarchical'       => $args['hierarchical'],
					'show_count'         => $args['show_count'],
					'hide_empty'         => $args['hide_empty'],
					'id'       			 => $tmpl_rand_id,
					'name'				 => 'dropcat',

					);

				$dropdown_args['show_option_none'] = esc_html__( 'Select Category', 'templatic' );
				$dropdown_args['title_li'] = false;
				$dropdown_args['echo'] = false;
				$dropdown_args['pad_counts'] = false;

				/* filter for category dropdown this filter is in same file */
				add_filter( 'wp_dropdown_cats', 'tmpl_wp_dropdown_cats', 10, 2 );

				/**
				 * Filter the arguments for the Categories widget drop-down.
				 *
				 * @since 2.8.0
				 *
				 * @see wp_dropdown_categories()
				 *
				 * @param array $cat_args An array of Categories widget drop-down arguments.
				 */

				echo wp_dropdown_categories( apply_filters( 'widget_categories_dropdown_args', $dropdown_args ) );

			?>

			<script type='text/javascript'>

				jQuery(document).ready(function(){
					jQuery( '#<?php echo floatval( $tmpl_rand_id ); ?> option' ).each(function(){
						if (jQuery(this).text() == '<?php single_cat_title(); ?>' )
						{
							jQuery(this).attr( 'selected','selected' );
						}

					});
					jQuery(document).on( 'change','#<?php echo floatval( $tmpl_rand_id ); ?>', function(e){

						var cat_id = jQuery(this).val();

						if (cat_id == -1 )
						{
							e.stopPropagation();
							return false;
						}
						/* for default post type category and tags */
						if ( '<?php echo esc_attr( $args['taxonomy'] );?>' == 'category' ){
							location.href = "<?php echo esc_url( home_url() ); ?>/?cat="+cat_id;
						} else { /* our custom post type category and tags */
							cat_name = cat_id.replace(/\s/g,"-");
							location.href = "<?php echo esc_url( home_url() ) . '/?' . esc_attr( $args['taxonomy'] ); ?>="+cat_name;
						}

					});

				});

			</script>

			<?php
			} else {
				if ( 'list' == $args['style'] || '' == $args['style'] ) {
					$categories = '<ul class="xoxo categories">' . $categories . '</ul><!-- .xoxo .categories -->';
				}
				/* Output the categories list. */
				echo $categories;
				/* Close the theme's widget wrapper. */
			} // End if().
			echo wp_kses_post( $sidebar['after_widget'] );
		}
		/**
		 *
		 * Updates the widget control options for the particular instance of the widget.
		 *
		 * @param array $new_instance     new instance of widget when saved from widget area.
		 * @param array $old_instance 	  old instances of widget.
		 */
		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			/* Set the instance to the new instance. */
			$instance = $new_instance;
			/* If new taxonomy is chosen, reset includes and excludes. */
			if ( $instance['taxonomy'] !== $old_instance['taxonomy'] && '' !== $old_instance['taxonomy'] ) {
				$instance['include'] = array();
				$instance['exclude'] = array();
			}
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['taxonomy'] = $new_instance['taxonomy'];
			$instance['depth'] = strip_tags( $new_instance['depth'] );
			$instance['number'] = strip_tags( $new_instance['number'] );
			$instance['child_of'] = strip_tags( $new_instance['child_of'] );
			$instance['current_category'] = strip_tags( $new_instance['current_category'] );
			$instance['feed'] = strip_tags( $new_instance['feed'] );
			$instance['feed_image'] = esc_url( $new_instance['feed_image'] );
			$instance['search'] = strip_tags( $new_instance['search'] );
			$instance['include'] = preg_replace( '/[^0-9,]/', '', $new_instance['include'] );
			$instance['exclude'] = preg_replace( '/[^0-9,]/', '', $new_instance['exclude'] );
			$instance['exclude_tree'] = preg_replace( '/[^0-9,]/', '', $new_instance['exclude_tree'] );
			$instance['hierarchical'] = ( isset( $new_instance['hierarchical'] ) ? 1 : 0 );
			$instance['use_desc_for_title'] = ( isset( $new_instance['use_desc_for_title'] ) ? 1 : 0 );
			$instance['show_count'] = ( isset( $new_instance['show_count'] ) ? 1 : 0 );
			$instance['hide_empty'] = ( isset( $new_instance['hide_empty'] ) ? 1 : 0 );
			$instance['dropdown'] = ( isset( $new_instance['dropdown'] ) ? 1 : 0 );
			return $instance;
		}
		/**
		 *
		 * Save the widget.
		 *
		 * @param array $instance     instance of widget.
		 */
		function form( $instance ) {
			/* Set up the default form values. */
			$defaults = array(
				'title'             => esc_attr__( 'Categories', 'templatic' ),
				'taxonomy'          => 'category',
				'style'             => 'list',
				'include'           => '',
				'exclude'           => '',
				'exclude_tree'      => '',
				'child_of'          => '',
				'current_category'  => '',
				'search'            => '',
				'hierarchical'      => true,
				'hide_empty'        => true,
				'order'             => 'ASC',
				'orderby'           => 'name',
				'depth'             => 0,
				'number'            => '',
				'feed'              => '',
				'feed_type'         => '',
				'feed_image'        => '',
				'use_desc_for_title' => false,
				'show_count'        => false,
				);
			/* Merge the user-selected arguments with the defaults. */
			$instance = wp_parse_args( (array) $instance, $defaults );
			/* <select> element options. */
			$taxonomies = get_taxonomies( array(
											'show_tagcloud' => true,
										), 'objects'
			);
			$terms = get_terms( $instance['taxonomy'] );
			$style = array(
						'list' => esc_attr__( 'List', 'templatic-admin' ),
						'none' => esc_attr__( 'None', 'templatic-admin' ),
					);
			$order = array(
						'ASC' => esc_attr__( 'Ascending', 'templatic-admin' ),
						'DESC' => esc_attr__( 'Descending', 'templatic-admin' ),
					);
			$orderby = array(
						'count'     => esc_attr__( 'Count', 'templatic-admin' ),
						'ID'        => esc_attr__( 'ID', 'templatic-admin' ),
						'name'      => esc_attr__( 'Name', 'templatic-admin' ),
						'slug'      => esc_attr__( 'Slug', 'templatic-admin' ),
						'term_group' => esc_attr__( 'Term Group', 'templatic-admin' ),
					);
			$feed_type = array(
							''     => '',
							'atom' => esc_attr__( 'Atom', 'templatic-admin' ),
							'rdf'  => esc_attr__( 'RDF', 'templatic-admin' ),
							'rss'  => esc_attr__( 'RSS', 'templatic-admin' ),
							'rss2' => esc_attr__( 'RSS 2.0', 'templatic-admin' ),
						);
			$shoblock = ( 1 == $instance['dropdown'] ) ? 'style="display:none"': '';
			?>
			<div class="hybrid-widget-controls columns-3">
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'title' ) ); ?>">
						<?php echo esc_html__( 'Title:', 'templatic-admin' ); ?>
					</label>
					<input type="text" class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'title' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'taxonomy' ) ); ?>"> <code> taxonomy </code> </label>
					<select class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'taxonomy' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'taxonomy' ) ); ?>">
						<?php
						foreach ( $taxonomies as $taxonomy ) {
							?>
							<option value="<?php echo esc_attr( $taxonomy->name ); ?>" <?php selected( $instance['taxonomy'], $taxonomy->name ); ?>> <?php echo esc_html( $taxonomy->labels->menu_name ); ?> </option>
							<?php
						} ?>
					</select>
				</p>
				<p class="cat_opt" <?php echo esc_attr( $shoblock ); ?>>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'style' ) ); ?>"> <code> style </code> </label>
					<select class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'style' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'style' ) ); ?>">
						<?php
						foreach ( $style as $option_value => $option_label ) {
							?>
							<option value="<?php echo esc_attr( $option_value ); ?>" <?php selected( $instance['style'], $option_value ); ?>> <?php echo esc_html( $option_label ); ?> </option>
							<?php
						} ?>
					</select>
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'order' ) ); ?>"> <code> order </code> </label>
					<select class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'order' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'order' ) ); ?>">
						<?php
						foreach ( $order as $option_value => $option_label ) {
							?>
							<option value="<?php echo esc_attr( $option_value ); ?>" <?php selected( $instance['order'], $option_value ); ?>> <?php echo esc_html( $option_label ); ?> </option>
							<?php
						} ?>
					</select>
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'orderby' ) ); ?>"> <code> orderby </code> </label>
					<select class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'orderby' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'orderby' ) ); ?>">
						<?php
						foreach ( $orderby as $option_value => $option_label ) {
							?>
							<option value="<?php echo esc_attr( $option_value ); ?>" <?php selected( $instance['orderby'], $option_value ); ?>> <?php echo esc_html( $option_label ); ?> </option>
							<?php
						} ?>
					</select>
				</p>
			</div>
			<div class="hybrid-widget-controls columns-3">
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'depth' ) ); ?>"> <code> depth </code> </label>
					<input type="text" class="smallfat code" id="<?php echo wp_kses_post( $this->get_field_id( 'depth' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'depth' ) ); ?>" value="<?php echo esc_attr( $instance['depth'] ); ?>" />
					<?php echo esc_html__( '(Applicable only when Hierarchical is enabled)', 'templatic-admin' );?>
				</p>
				<p class="cat_opt" <?php echo esc_attr( $shoblock ); ?>>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'number' ) ); ?>"> <code> number </code> </label>
					<input type="text" class="smallfat code" id="<?php echo wp_kses_post( $this->get_field_id( 'number' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'number' ) ); ?>" value="<?php echo esc_attr( $instance['number'] ); ?>" />
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'include' ) ); ?>"> <code> include </code> </label>
					<input type="text" class="smallfat code" id="<?php echo wp_kses_post( $this->get_field_id( 'include' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'include' ) ); ?>" value="<?php echo esc_attr( $instance['include'] ); ?>" />
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'exclude' ) ); ?>"> <code> exclude </code> </label>
					<input type="text" class="smallfat code" id="<?php echo wp_kses_post( $this->get_field_id( 'exclude' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'exclude' ) ); ?>" value="<?php echo esc_attr( $instance['exclude'] ); ?>" />
				</p>
				<p class="cat_opt" <?php echo esc_attr( $shoblock ); ?>>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'exclude_tree' ) ); ?>"> <code> exclude_tree </code> </label>
					<input type="text" class="smallfat code" id="<?php echo wp_kses_post( $this->get_field_id( 'exclude_tree' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'exclude_tree' ) ); ?>" value="<?php echo esc_attr( $instance['exclude_tree'] ); ?>" />
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'child_of' ) ); ?>"> <code> child_of </code> </label>
					<input type="text" class="smallfat code" id="<?php echo wp_kses_post( $this->get_field_id( 'child_of' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'child_of' ) ); ?>" value="<?php echo esc_attr( $instance['child_of'] ); ?>" />
				</p>
				<p class="cat_opt" <?php echo esc_attr( $shoblock ); ?>>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'current_category' ) ); ?>"> <code> current_category </code> </label>
					<input type="text" class="smallfat code" id="<?php echo wp_kses_post( $this->get_field_id( 'current_category' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'current_category' ) ); ?>" value="<?php echo esc_attr( $instance['current_category'] ); ?>" />
				</p>
				<p class="cat_opt" <?php echo esc_attr( $shoblock ); ?>>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'search' ) ); ?>"> <code> search </code> </label>
					<input type="text" class="widefat code" id="<?php echo wp_kses_post( $this->get_field_id( 'search' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'search' ) ); ?>" value="<?php echo esc_attr( $instance['search'] ); ?>" />
				</p>
			</div>
			<div class="hybrid-widget-controls columns-3 column-last">
				<p class="cat_opt" <?php echo esc_attr( $shoblock ); ?>>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'feed' ) ); ?>"> <code> feed </code> </label>
					<input type="text" class="widefat code" id="<?php echo wp_kses_post( $this->get_field_id( 'feed' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'feed' ) ); ?>" value="<?php echo esc_attr( $instance['feed'] ); ?>" />
				</p>
				<p class="cat_opt" <?php echo esc_attr( $shoblock ); ?>>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'feed_type' ) ); ?>"> <code> feed_type </code> </label>
					<select class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'feed_type' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'feed_type' ) ); ?>">
						<?php
						foreach ( $feed_type as $option_value => $option_label ) {
							?>
							<option value="<?php echo esc_attr( $option_value ); ?>" <?php selected( $instance['feed_type'], $option_value ); ?>> <?php echo esc_html( $option_label ); ?> </option>
							<?php
						} ?>
					</select>
				</p>
				<p class="cat_opt" <?php echo esc_attr( $shoblock ); ?>>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'feed_image' ) ); ?>"> <code> feed_image </code> </label>
					<input type="text" class="widefat code" id="<?php echo wp_kses_post( $this->get_field_id( 'feed_image' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'feed_image' ) ); ?>" value="<?php echo esc_attr( $instance['feed_image'] ); ?>" />
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'hierarchical' ) ); ?>">
						<input class="checkbox" type="checkbox" <?php checked( $instance['hierarchical'], true ); ?> id="<?php echo wp_kses_post( $this->get_field_id( 'hierarchical' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'hierarchical' ) ); ?>" />
						<?php echo esc_html__( 'Hierarchical?', 'templatic-admin' ); ?>
						<code> hierarchical </code> </label>
				</p>
				<p class="cat_opt" <?php echo esc_attr( $shoblock ); ?>>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'use_desc_for_title' ) ); ?>">
						<input class="checkbox" type="checkbox" <?php checked( $instance['use_desc_for_title'], true ); ?> id="<?php echo wp_kses_post( $this->get_field_id( 'use_desc_for_title' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'use_desc_for_title' ) ); ?>" />
						<?php echo esc_html__( 'Use description?', 'templatic-admin' ); ?>
						<code> use_desc_for_title </code> </label>
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'show_count' ) ); ?>">
						<input class="checkbox" type="checkbox" <?php checked( $instance['show_count'], true ); ?> id="<?php echo wp_kses_post( $this->get_field_id( 'show_count' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'show_count' ) ); ?>" />
						<?php echo esc_html__( 'Show count?', 'templatic-admin' ); ?>
						<code> show_count </code> </label>
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'hide_empty' ) ); ?>">
						<input class="checkbox" type="checkbox" <?php checked( $instance['hide_empty'], true ); ?> id="<?php echo wp_kses_post( $this->get_field_id( 'hide_empty' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'hide_empty' ) ); ?>" />
						<?php echo esc_html__( 'Hide empty?', 'templatic-admin' ); ?>
						<code> hide_empty </code> </label>
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'dropdown' ) ); ?>">
						<input class="checkbox" type="checkbox" <?php checked( $instance['dropdown'], true ); ?> id="<?php echo wp_kses_post( $this->get_field_id( 'dropdown' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'dropdown' ) ); ?>" />
						<?php echo esc_html__( 'Display as dropdown', 'templatic-admin' ); ?>
						<code> display_as_dropdown </code> </label>
				</p>
				<p><strong><?php echo esc_html__( 'Note: ', 'templatic-admin' )?></strong><?php echo esc_html__( 'Use only one Categories widget on the <b>single page</b> if you choose "dropdown" option.' );?></p>
			</div>
			<script>
				jQuery(document).ready(function(){

					jQuery( '#<?php echo wp_kses_post( $this->get_field_id( 'dropdown' ) ); ?>' ).bind( 'click',function(){
						if (jQuery(this).is( ':checked' ) ) {
							jQuery( '.cat_opt' ).hide();
						}
						else
							jQuery( '.cat_opt' ).show();
					});
				});
			</script>
			<div style="clear:both;"> &nbsp; </div>
			<?php
		}
	}
} // End if().

if ( ! class_exists( 'supreme_nav_menu_widget' ) ) {
	/**
	 * Nav Menu Widget Class.
	 **/
	class supreme_nav_menu_widget extends WP_Widget {
		/**
		 * Set up the widget's unique name, ID, class, description, and other options.
		 */
		function __construct() {
			/* Set up the widget options. */
			$widget_options = array(
				'classname'  => 'nav-menu widget-nav-menu',
				'description' => esc_html__( 'An advanced widget that gives you total control over the output of your menus.', 'templatic-admin' ),
				);
			/* Set up the widget control options. */
			$control_options = array(
					'height' => 350,
					);
			/* Create the widget. */
			parent::__construct(
				'hybrid-nav-menu',
				__( 'Navigation Menu', 'templatic-admin' ),
				$widget_options,
				$control_options
			);
		}
		/**
		 * Outputs the widget based on the arguments input through the widget controls.
		 *
		 * @param array $sidebar 	agurment of widget area.
		 * @param array $instance 	instances of widget.
		 */
		function widget( $sidebar, $instance ) {
			extract( $sidebar );
			/* Set the $args for wp_nav_menu() to the $instance array. */
			$args = $instance;
			/* Overwrite the $echo argument and set it to false. */
			$args['echo'] = false;
			/* Output the theme's widget wrapper. */
			echo wp_kses_post( $sidebar['before_widget'] );
			/* If a title was input by the user, display it. */
			if ( ! empty( $instance['title'] ) ) {
				echo wp_kses_post( $sidebar['before_title'] . apply_filters( 'widget_title',  $instance['title'], $instance, $this->id_base ) . $sidebar['after_title'] );
			}
			/* Output the nav menu. */
			echo wp_kses_post( str_replace( array( "\r", "\n", "\t" ), '', wp_nav_menu( $args ) ) );
			/* Close the theme's widget wrapper. */
			echo wp_kses_post( $sidebar['after_widget'] );
		}
		/**
		 *
		 * Updates the widget control options for the particular instance of the widget.
		 *
		 * @param array $new_instance     new instance of widget when saved from widget area.
		 * @param array $old_instance 	  old instances of widget.
		 */
		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$instance = $new_instance;
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['depth'] = strip_tags( $new_instance['depth'] );
			$instance['container_id'] = strip_tags( $new_instance['container_id'] );
			$instance['container_class'] = strip_tags( $new_instance['container_class'] );
			$instance['menu_id'] = strip_tags( $new_instance['menu_id'] );
			$instance['menu_class'] = strip_tags( $new_instance['menu_class'] );
			$instance['fallback_cb'] = strip_tags( $new_instance['fallback_cb'] );
			$instance['walker'] = strip_tags( $new_instance['walker'] );
			return $instance;
		}
		/**
		 *
		 * Save the widget.
		 *
		 * @param array $instance     instance of widget.
		 */
		function form( $instance ) {
			/* Set up the default form values. */
			$defaults = array(
				'title'          => esc_attr__( 'Navigation', 'templatic' ),
				'menu'           => '',
				'container'      => 'div',
				'container_id'   => '',
				'container_class' => '',
				'menu_id'        => '',
				'menu_class'     => 'nav-menu',
				'depth'          => 0,
				'before'         => '',
				'after'          => '',
				'link_before'    => '',
				'link_after'     => '',
				'fallback_cb'    => 'wp_page_menu',
				'walker'         => '',
				);
			/* Merge the user-selected arguments with the defaults. */
			$instance = wp_parse_args( (array) $instance, $defaults );
			$container = apply_filters( 'wp_nav_menu_container_allowedtags', array( 'div', 'nav' ) );
			?>
			<div class="hybrid-widget-controls columns-2">
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'title' ) ); ?>">
						<?php echo esc_html__( 'Title:', 'templatic-admin' ); ?>
					</label>
					<input type="text" class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'title' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'menu' ) ); ?>"> <code> menu </code> </label>
					<select class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'menu' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'menu' ) ); ?>">
						<?php	foreach ( wp_get_nav_menus() as $menu ) { ?>
						<option value="<?php echo esc_attr( $menu->term_id ); ?>" <?php selected( $instance['menu'], $menu->term_id ); ?>> <?php echo esc_html( $menu->name ); ?> </option>
						<?php } ?>
					</select>
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'container' ) ); ?>"> <code> container </code> </label>
					<select class="smallfat" id="<?php echo wp_kses_post( $this->get_field_id( 'container' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'container' ) ); ?>">
						<?php	foreach ( $container as $option ) { ?>
						<option value="<?php echo esc_attr( $option ); ?>" <?php selected( $instance['container'], $option ); ?>> <?php echo esc_html( $option ); ?> </option>
						<?php } ?>
					</select>
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'container_id' ) ); ?>"> <code> container_id </code> </label>
					<input type="text" class="smallfat code" id="<?php echo wp_kses_post( $this->get_field_id( 'container_id' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'container_id' ) ); ?>" value="<?php echo esc_attr( $instance['container_id'] ); ?>" />
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'container_class' ) ); ?>"> <code> container_class </code> </label>
					<input type="text" class="smallfat code" id="<?php echo wp_kses_post( $this->get_field_id( 'container_class' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'container_class' ) ); ?>" value="<?php echo esc_attr( $instance['container_class'] ); ?>" />
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'menu_id' ) ); ?>"> <code> menu_id </code> </label>
					<input type="text" class="smallfat code" id="<?php echo wp_kses_post( $this->get_field_id( 'menu_id' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'menu_id' ) ); ?>" value="<?php echo esc_attr( $instance['menu_id'] ); ?>" />
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'menu_class' ) ); ?>"> <code> menu_class </code> </label>
					<input type="text" class="smallfat code" id="<?php echo wp_kses_post( $this->get_field_id( 'menu_class' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'menu_class' ) ); ?>" value="<?php echo esc_attr( $instance['menu_class'] ); ?>" />
				</p>
			</div>
			<div class="hybrid-widget-controls columns-2 column-last">
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'depth' ) ); ?>"> <code> depth </code> </label>
					<input type="text" class="smallfat code" id="<?php echo wp_kses_post( $this->get_field_id( 'depth' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'depth' ) ); ?>" value="<?php echo esc_attr( $instance['depth'] ); ?>" />
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'before' ) ); ?>"> <code> before </code> </label>
					<input type="text" class="smallfat code" id="<?php echo wp_kses_post( $this->get_field_id( 'before' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'before' ) ); ?>" value="<?php echo esc_attr( $instance['before'] ); ?>" />
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'after' ) ); ?>"> <code> after </code> </label>
					<input type="text" class="smallfat code" id="<?php echo wp_kses_post( $this->get_field_id( 'after' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'after' ) ); ?>" value="<?php echo esc_attr( $instance['after'] ); ?>" />
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'link_before' ) ); ?>"> <code> link_before </code> </label>
					<input type="text" class="smallfat code" id="<?php echo wp_kses_post( $this->get_field_id( 'link_before' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'link_before' ) ); ?>" value="<?php echo esc_attr( $instance['link_before'] ); ?>" />
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'link_after' ) ); ?>"> <code> link_after </code> </label>
					<input type="text" class="smallfat code" id="<?php echo wp_kses_post( $this->get_field_id( 'link_after' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'link_after' ) ); ?>" value="<?php echo esc_attr( $instance['link_after'] ); ?>" />
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'fallback_cb' ) ); ?>"> <code> fallback_cb </code> </label>
					<input type="text" class="widefat code" id="<?php echo wp_kses_post( $this->get_field_id( 'fallback_cb' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'fallback_cb' ) ); ?>" value="<?php echo esc_attr( $instance['fallback_cb'] ); ?>" />
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'walker' ) ); ?>"> <code> walker </code> </label>
					<input type="text" class="widefat code" id="<?php echo wp_kses_post( $this->get_field_id( 'walker' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'walker' ) ); ?>" value="<?php echo esc_attr( $instance['walker'] ); ?>" />
				</p>
			</div>
			<?php
		}
	}
} // End if().

if ( ! class_exists( ' supreme_pages_widget' ) ) {
	/**
	 * Pages Widget Class.
	 **/
	class supreme_pages_widget extends WP_Widget {
		/**
		 * Set up the widget's unique name, ID, class, description, and other options.
		 */
		function __construct() {
			/* Set up the widget options. */
			$widget_options = array(
				'classname'  => 'pages',
				'description' => esc_html__( 'An advanced widget that gives you total control over the output of your page links.', 'templatic-admin' ),
				);
			/* Set up the widget control options. */
			$control_options = array(
				'height' => 350,
				);
			/* Create the widget. */
			parent::__construct(
				'hybrid-pages',
				__( 'Pages', 'templatic-admin' ),
				$widget_options,
				$control_options
			);

		}
		/**
		 * Outputs the widget based on the arguments input through the widget controls.
		 *
		 * @param array $sidebar 	agurment of widget area.
		 * @param array $instance 	instances of widget.
		 */
		function widget( $sidebar, $instance ) {
			extract( $sidebar );
			/* Set the $args for wp_list_pages() to the $instance array. */
			$args = $instance;
			/* Set the $title_li and $echo to false. */
			$args['title_li'] = false;
			$args['echo'] = false;
			/* Open the output of the widget. */
			echo wp_kses_post( $sidebar['before_widget'] );
			/* If a title was input by the user, display it. */
			if ( ! empty( $instance['title'] ) ) {
				echo wp_kses_post( $sidebar['before_title'] . apply_filters( 'widget_title',  $instance['title'], $instance, $this->id_base ) . $sidebar['after_title'] );
			}
			/* Output the page list. */
			echo wp_kses_post( '<ul class="xoxo pages">' . str_replace( array( "\r", "\n", "\t" ), '', wp_list_pages( $args ) ) . '</ul>' );
			/* Close the output of the widget. */
			echo wp_kses_post( $sidebar['after_widget'] );
		}
		/**
		 *
		 * Updates the widget control options for the particular instance of the widget.
		 *
		 * @param array $new_instance     new instance of widget when saved from widget area.
		 * @param array $old_instance 	  old instances of widget.
		 */
		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			/* Set the instance to the new instance. */
			$instance = $new_instance;
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['depth'] = strip_tags( $new_instance['depth'] );
			$instance['child_of'] = strip_tags( $new_instance['child_of'] );
			$instance['meta_key'] = strip_tags( $new_instance['meta_key'] );
			$instance['meta_value'] = strip_tags( $new_instance['meta_value'] );
			$instance['date_format'] = strip_tags( $new_instance['date_format'] );
			$instance['number'] = strip_tags( $new_instance['number'] );
			$instance['offset'] = strip_tags( $new_instance['offset'] );
			$instance['include'] = preg_replace( '/[^0-9,]/', '', $new_instance['include'] );
			$instance['exclude'] = preg_replace( '/[^0-9,]/', '', $new_instance['exclude'] );
			$instance['exclude_tree'] = preg_replace( '/[^0-9,]/', '', $new_instance['exclude_tree'] );
			$instance['authors'] = preg_replace( '/[^0-9,]/', '', $new_instance['authors'] );
			$instance['post_type'] = $new_instance['post_type'];
			$instance['sort_column'] = $new_instance['sort_column'];
			$instance['sort_order'] = $new_instance['sort_order'];
			$instance['show_date'] = $new_instance['show_date'];
			$instance['link_before'] = $new_instance['link_before'];
			$instance['link_after'] = $new_instance['link_after'];
			$instance['hierarchical'] = ( isset( $new_instance['hierarchical'] ) ? 1 : 0 );
			return $instance;
		}
		/**
		 *
		 * Save the widget.
		 *
		 * @param array $instance     instance of widget.
		 */
		function form( $instance ) {
			/* Set up the default form values. */
			$defaults = array(
				'title'       => esc_attr__( 'Pages', 'templatic' ),
				'post_type'   => 'page',
				'depth'       => 0,
				'number'      => '',
				'offset'      => '',
				'child_of'    => '',
				'include'     => '',
				'exclude'     => '',
				'exclude_tree' => '',
				'meta_key'    => '',
				'meta_value'  => '',
				'authors'     => '',
				'link_before' => '',
				'link_after'  => '',
				'show_date'   => '',
				'hierarchical' => true,
				'sort_column' => 'post_title',
				'sort_order'  => 'ASC',
				'date_format' => get_option( 'date_format' ),
				);
			/* Merge the user-selected arguments with the defaults. */
			$instance = wp_parse_args( (array) $instance, $defaults );
			$post_types = get_post_types( array(
											'public'       => true,
											'hierarchical' => true,
			), 'objects' );
			$sort_order = array(
							'ASC'  => esc_attr__( 'Ascending', 'templatic-admin' ),
							'DESC' => esc_attr__( 'Descending', 'templatic-admin' ),
						);
			$sort_column = array(
							'post_author'   => esc_attr__( 'Author', 'templatic-admin' ),
							'post_date'     => esc_attr__( 'Date', 'templatic-admin' ),
							'ID'            => esc_attr__( 'ID', 'templatic-admin' ),
							'menu_order'    => esc_attr__( 'Menu Order', 'templatic-admin' ),
							'post_modified' => esc_attr__( 'Modified', 'templatic-admin' ),
							'post_name'     => esc_attr__( 'Slug', 'templatic-admin' ),
							'post_title'    => esc_attr__( 'Title', 'templatic-admin' ),
						);
			$show_date = array(
							''         => '',
							'created'  => esc_attr__( 'Created', 'templatic-admin' ),
							'modified' => esc_attr__( 'Modified', 'templatic-admin' ),
						);
			$meta_key = array_merge( array( '' ), (array) get_meta_keys() );
			?>
			<div class="hybrid-widget-controls columns-3">
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'title' ) ); ?>">
						<?php echo esc_html__( 'Title:', 'templatic-admin' ); ?>
					</label>
					<input type="text" class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'title' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'post_type' ) ); ?>"> <code> post_type </code> </label>
					<select class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'post_type' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'post_type' ) ); ?>">
						<?php
						foreach ( $post_types as $post_type ) {
							?>
							<option value="<?php echo esc_attr( $post_type->name ); ?>" <?php selected( $instance['post_type'], $post_type->name ); ?>> <?php echo esc_html( $post_type->labels->singular_name ); ?> </option>
							<?php
						} ?>
					</select>
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'sort_order' ) ); ?>"> <code> sort_order </code> </label>
					<select class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'sort_order' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'sort_order' ) ); ?>">
						<?php
						foreach ( $sort_order as $option_value => $option_label ) {
							?>
							<option value="<?php echo esc_attr( $option_value ); ?>" <?php selected( $instance['sort_order'], $option_value ); ?>> <?php echo esc_html( $option_label ); ?> </option>
							<?php
						} ?>
					</select>
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'sort_column' ) ); ?>"> <code> sort_column </code> </label>
					<select class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'sort_column' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'sort_column' ) ); ?>">
						<?php
						foreach ( $sort_column as $option_value => $option_label ) {
							?>
							<option value="<?php echo esc_attr( $option_value ); ?>" <?php selected( $instance['sort_column'], $option_value ); ?>> <?php echo esc_html( $option_label ); ?> </option>
							<?php
						} ?>
					</select>
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'depth' ) ); ?>"> <code> depth </code> </label>
					<input type="text" class="smallfat code" id="<?php echo wp_kses_post( $this->get_field_id( 'depth' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'depth' ) ); ?>" value="<?php echo esc_attr( $instance['depth'] ); ?>" />
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'number' ) ); ?>"> <code> number </code> </label>
					<input type="text" class="smallfat code" id="<?php echo wp_kses_post( $this->get_field_id( 'number' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'number' ) ); ?>" value="<?php echo esc_attr( $instance['number'] ); ?>" />
				</p>
			</div>
			<div class="hybrid-widget-controls columns-3">
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'offset' ) ); ?>"> <code> offset </code> </label>
					<input type="text" class="smallfat code" id="<?php echo wp_kses_post( $this->get_field_id( 'offset' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'offset' ) ); ?>" value="<?php echo esc_attr( $instance['offset'] ); ?>"  />
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'child_of' ) ); ?>"> <code> child_of </code> </label>
					<input type="text" class="smallfat code" id="<?php echo wp_kses_post( $this->get_field_id( 'child_of' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'child_of' ) ); ?>" value="<?php echo esc_attr( $instance['child_of'] ); ?>" />
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'include' ) ); ?>"> <code> include </code> </label>
					<input type="text" class="smallfat code" id="<?php echo wp_kses_post( $this->get_field_id( 'include' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'include' ) ); ?>" value="<?php echo esc_attr( $instance['include'] ); ?>" />
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'exclude' ) ); ?>"> <code> exclude </code> </label>
					<input type="text" class="smallfat code" id="<?php echo wp_kses_post( $this->get_field_id( 'exclude' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'exclude' ) ); ?>" value="<?php echo esc_attr( $instance['exclude'] ); ?>" />
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'exclude_tree' ) ); ?>"> <code> exclude_tree </code> </label>
					<input type="text" class="smallfat code" id="<?php echo wp_kses_post( $this->get_field_id( 'exclude_tree' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'exclude_tree' ) ); ?>" value="<?php echo esc_attr( $instance['exclude_tree'] ); ?>" />
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'meta_key' ) ); ?>"> <code> meta_key </code> </label>
					<select class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'meta_key' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'meta_key' ) ); ?>">
						<?php
						foreach ( $meta_key as $meta ) {
							?>
							<option value="<?php echo esc_attr( $meta ); ?>" <?php selected( $instance['meta_key'], $meta ); ?>> <?php echo esc_html( $meta ); ?> </option>
							<?php
						} ?>
					</select>
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'meta_value' ) ); ?>"> <code> meta_value </code> </label>
					<input type="text" class="widefat code" id="<?php echo wp_kses_post( $this->get_field_id( 'meta_value' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'meta_value' ) ); ?>" value="<?php echo esc_attr( $instance['meta_value'] ); ?>" />
				</p>
			</div>
			<div class="hybrid-widget-controls columns-3 column-last">
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'authors' ) ); ?>"> <code> authors </code> </label>
					<input type="text" class="smallfat code" id="<?php echo wp_kses_post( $this->get_field_id( 'authors' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'authors' ) ); ?>" value="<?php echo esc_attr( $instance['authors'] ); ?>" />
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'link_before' ) ); ?>"> <code> link_before </code> </label>
					<input type="text" class="smallfat code" id="<?php echo wp_kses_post( $this->get_field_id( 'link_before' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'link_before' ) ); ?>" value="<?php echo esc_attr( $instance['link_before'] ); ?>" />
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'link_after' ) ); ?>"> <code> link_after </code> </label>
					<input type="text" class="smallfat code" id="<?php echo wp_kses_post( $this->get_field_id( 'link_after' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'link_after' ) ); ?>" value="<?php echo esc_attr( $instance['link_after'] ); ?>" />
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'show_date' ) ); ?>"> <code> show_date </code> </label>
					<select class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'show_date' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'show_date' ) ); ?>">
						<?php
						foreach ( $show_date as $option_value => $option_label ) {
							?>
							<option value="<?php echo esc_attr( $option_value ); ?>" <?php selected( $instance['show_date'], $option_value ); ?>> <?php echo esc_html( $option_label ); ?> </option>
							<?php
						} ?>
					</select>
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'date_format' ) ); ?>"> <code> date_format </code> </label>
					<input type="text" class="smallfat code" id="<?php echo wp_kses_post( $this->get_field_id( 'date_format' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'date_format' ) ); ?>" value="<?php echo esc_attr( $instance['date_format'] ); ?>" />
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'hierarchical' ) ); ?>">
						<input class="checkbox" type="checkbox" <?php checked( $instance['hierarchical'], true ); ?> id="<?php echo wp_kses_post( $this->get_field_id( 'hierarchical' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'hierarchical' ) ); ?>" />
						<?php echo esc_html__( 'Hierarchical?', 'templatic-admin' ); ?>
						<code> hierarchical </code> </label>
				</p>
			</div>
			<div style="clear:both;"> &nbsp; </div>
			<?php
		}
	}
} // End if().

if ( ! class_exists( 'supreme_search_widget' ) ) {
	/**
	 * Search Widget Class.
	 **/
	class supreme_search_widget extends WP_Widget {
		/**
		 * Set up the widget's unique name, ID, class, description, and other options.
		 */
		function __construct() {
			/* Set up the widget options. */
			$widget_options = array(
				'classname'  => 'search',
				'description' => esc_html__( 'An advanced widget that gives you total control over the output of your search form.', 'templatic-admin' ),
				);
			/* Create the widget. */
			parent::__construct(
				'hybrid-search',
				__( 'Search', 'templatic-admin' ),
				@$widget_options,
				$control_options
			);
		}
		/**
		 * Outputs the widget based on the arguments input through the widget controls.
		 *
		 * @param array $sidebar 	agurment of widget area.
		 * @param array $instance 	instances of widget.
		 */
		function widget( $sidebar, $instance ) {
			extract( $sidebar );
			/* Output the theme's $args['before_widget'] wrapper. */
			echo wp_kses_post( $sidebar['before_widget'] );
			/* If a title was input by the user, display it. */
			if ( ! empty( $instance['title'] ) ) {
				echo wp_kses_post( $sidebar['before_title'] . apply_filters( 'widget_title',  $instance['title'], $instance, $this->id_base ) . $sidebar['after_title'] );
			}
			/* If the user chose to use the theme's search form, load it. */
			if ( ! empty( $instance['theme_search'] ) ) {
				get_search_form();
			} else {
				/* Set up some variables for the search form. */
				if ( empty( $instance['search_text'] ) ) {
					$instance['search_text'] = '';
				}
				$search_text = ( ( is_search() ) ? esc_attr( get_search_query() ) : esc_attr( $instance['search_text'] ) );
				/* Open the form. */
				$search      = '<form method="get" class="search-form" id="search-form' . esc_attr( $this->id_base ) . '" action="' . home_url() . '/"><div>';
				/* If a search label was set, add it. */
				if ( ! empty( $instance['search_label'] ) ) {
					$search .= '<label for="search-text' . esc_attr( $this->id_base ) . '">' . $instance['search_label'] . '</label>';
				}
				/* Search form text input. */
				$search .= '<input class="search-text" type="text" name="s" id="search-text' . esc_attr( $this->id_base ) . '" value="' . $search_text . '" onfocus="if (this.value==this.defaultValue)this.value=\'\';" onblur="if (this.value==\'\' )this.value=this.defaultValue;" />';
				/* Search form submit button. */
				if ( isset( $instance['search_submit'] ) && $instance['search_submit'] ) {
					$search .= '<input class="search-submit button" name="submit" type="submit" id="search-submit' . esc_attr( $this->id_base ) . '" value="' . esc_attr( $instance['search_submit'] ) . '" />';
				}
				/* Close the form. */
				$search .= '</div></form>';
				/* Display the form. */
				echo wp_kses_post( $search );
			}
			/* Close the theme's widget wrapper. */
			echo wp_kses_post( $sidebar['after_widget'] );
		}
		/**
		 *
		 * Updates the widget control options for the particular instance of the widget.
		 *
		 * @param array $new_instance     new instance of widget when saved from widget area.
		 * @param array $old_instance 	  old instances of widget.
		 */
		function update( $new_instance, $old_instance ) {
			$instance = $new_instance;
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['search_label'] = strip_tags( $new_instance['search_label'] );
			$instance['search_text'] = strip_tags( $new_instance['search_text'] );
			$instance['search_submit'] = strip_tags( $new_instance['search_submit'] );
			$instance['theme_search'] = ( isset( $new_instance['theme_search'] ) ? 1 : 0 );
			return $instance;
		}
		/**
		 *
		 * Save the widget.
		 *
		 * @param array $instance     instance of widget.
		 */
		function form( $instance ) {
			/* Set up the default form values. */
			$defaults = array(
				'title'         => esc_attr__( 'Search', 'templatic' ),
				'theme_search'  => false,
				'search_label'  => '',
				'search_text'   => '',
				'search_submit' => '',
				);
			/* Merge the user-selected arguments with the defaults. */
			$instance = wp_parse_args( (array) $instance, $defaults ); ?>
			<div class="hybrid-widget-controls columns-2">
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'title' ) ); ?>">
						<?php echo esc_html__( 'Title:', 'templatic-admin' ); ?>
					</label>
					<input type="text" class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'title' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'search_label' ) ); ?>">
						<?php echo esc_html__( 'Search Label:', 'templatic-admin' ); ?>
					</label>
					<input type="text" class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'search_label' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'search_label' ) ); ?>" value="<?php echo esc_attr( $instance['search_label'] ); ?>" />
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'search_text' ) ); ?>">
						<?php echo esc_html__( 'Search Text:', 'templatic-admin' ); ?>
					</label>
					<input type="text" class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'search_text' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'search_text' ) ); ?>" value="<?php echo esc_attr( $instance['search_text'] ); ?>" />
				</p>
			</div>
			<div class="hybrid-widget-controls columns-2 column-last">
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'search_submit' ) ); ?>">
						<?php echo esc_html__( 'Search Submit:', 'templatic-admin' ); ?>
					</label>
					<input type="text" class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'search_submit' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'search_submit' ) ); ?>" value="<?php echo esc_attr( $instance['search_submit'] ); ?>" />
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'theme_search' ) ); ?>">
						<input class="checkbox" type="checkbox" <?php checked( $instance['theme_search'], true ); ?> id="<?php echo wp_kses_post( $this->get_field_id( 'theme_search' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'theme_search' ) ); ?>" />
						<?php echo esc_html__( 'Use theme\'s <code>searchform.php</code>?', 'templatic-admin' ); ?>
					</label>
				</p>
			</div>
			<div style="clear:both;"> &nbsp; </div>
			<?php
		}
	}
} // End if().

if ( ! class_exists( 'supreme_tags_widget' ) ) {
	/**
	 * Tags Widget Class.
	 **/
	class supreme_tags_widget extends WP_Widget {
		/**
		 * Set up the widget's unique name, ID, class, description, and other options.
		 */
		function __construct() {
			/* Set up the widget options. */
			$widget_options = array(
				'classname'  => 'tags',
				'description' => esc_html__( 'An advanced widget that gives you total control over the output of your tags.' , 'templatic-admin' ),
				);
			/* Set up the widget control options. */
			$control_options = array(
					'height' => 350,
				);
			/* Create the widget. */
			parent::__construct(
				'hybrid-tags',
				__( 'Tags', 'templatic-admin' ),
				$widget_options,
				$control_options
			);
		}
		/**
		 * Outputs the widget based on the arguments input through the widget controls.
		 *
		 * @param array $sidebar 	agurment of widget area.
		 * @param array $instance 	instances of widget.
		 */
		function widget( $sidebar, $instance ) {
			extract( $sidebar );
			/* Set the $args for wp_tag_cloud() to the $instance array. */
			$args = $instance;
			/* Make sure empty callbacks aren't passed for custom functions. */
			$args['topic_count_text_callback'] = ! empty( $args['topic_count_text_callback'] ) ? $args['topic_count_text_callback'] : 'default_topic_count_text';
			$args['topic_count_scale_callback'] = ! empty( $args['topic_count_scale_callback'] ) ? $args['topic_count_scale_callback'] : 'default_topic_count_scale';
			/* If the separator is empty, set it to the default new line. */
			$args['separator'] = ! empty( $args['separator'] ) ? $args['separator'] : "\n";
			/* Overwrite the echo argument. */
			$args['echo'] = false;
			/* Output the theme's $args['before_widget'] wrapper. */
			echo wp_kses_post( $sidebar['before_widget'] );
			/* If a title was input by the user, display it. */
			if ( ! empty( $instance['title'] ) ) {
				echo wp_kses_post( $sidebar['before_title'] . apply_filters( 'widget_title',  $instance['title'], $instance, $this->id_base ) . $sidebar['after_title'] );
			}
			/* Get the tag cloud. */
			$tags = str_replace( array( "\r", "\n", "\t" ), ' ', wp_tag_cloud( $args ) );
			/* If $format should be flat, wrap it in the <p> element. */
			if ( 'flat' == $instance['format'] ) {
				$tags = '<p class="' . sanitize_html_class( "{$instance['taxonomy']}-cloud" ) . ' term-cloud">' . $tags . '</p>';
			}
			/* Output the tag cloud. */
			echo wp_kses_post( $tags );
			/* Close the theme's widget wrapper. */
			echo wp_kses_post( $sidebar['after_widget'] );
		}
		/**
		 *
		 * Updates the widget control options for the particular instance of the widget.
		 *
		 * @param array $new_instance     new instance of widget when saved from widget area.
		 * @param array $old_instance 	  old instances of widget.
		 */
		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			/* Set the instance to the new instance. */
			$instance = $new_instance;
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['smallest'] = strip_tags( $new_instance['smallest'] );
			$instance['largest'] = strip_tags( $new_instance['largest'] );
			$instance['number'] = strip_tags( $new_instance['number'] );
			$instance['separator'] = strip_tags( $new_instance['separator'] );
			$instance['name__like'] = strip_tags( $new_instance['name__like'] );
			$instance['search'] = strip_tags( $new_instance['search'] );
			$instance['child_of'] = strip_tags( $new_instance['child_of'] );
			$instance['parent'] = strip_tags( $new_instance['parent'] );
			$instance['topic_count_text_callback'] = strip_tags( $new_instance['topic_count_text_callback'] );
			$instance['topic_count_scale_callback'] = strip_tags( $new_instance['topic_count_scale_callback'] );
			$instance['include'] = preg_replace( '/[^0-9,]/', '', $new_instance['include'] );
			$instance['exclude'] = preg_replace( '/[^0-9,]/', '', $new_instance['exclude'] );
			$instance['unit'] = $new_instance['unit'];
			$instance['format'] = $new_instance['format'];
			$instance['orderby'] = $new_instance['orderby'];
			$instance['order'] = $new_instance['order'];
			$instance['taxonomy'] = $new_instance['taxonomy'];
			$instance['link'] = $new_instance['link'];
			$instance['pad_counts'] = ( isset( $new_instance['pad_counts'] ) ? 1 : 0 );
			$instance['hide_empty'] = ( isset( $new_instance['hide_empty'] ) ? 1 : 0 );
			return $instance;
		}
		/**
		 *
		 * Save the widget.
		 *
		 * @param array $instance     instance of widget.
		 */
		function form( $instance ) {
			/* Set up the default form values. */
			$defaults = array(
				'title'                     => esc_attr__( 'Tags', 'templatic' ),
				'order'                     => 'ASC',
				'orderby'                   => 'name',
				'format'                    => 'flat',
				'include'                   => '',
				'exclude'                   => '',
				'unit'                      => 'pt',
				'smallest'                  => 8,
				'largest'                   => 22,
				'link'                      => 'view',
				'number'                    => 45,
				'separator'                 => ' ',
				'child_of'                  => '',
				'parent'                    => '',
				'taxonomy'                   => array( 'post_tag' ),
				'hide_empty'                => 1,
				'pad_counts'                => false,
				'search'                    => '',
				'name__like'                => '',
				'topic_count_text_callback' => 'default_topic_count_text',
				'topic_count_scale_callback' => 'default_topic_count_scale',
				);
			/* Merge the user-selected arguments with the defaults. */
			$instance = wp_parse_args( (array) $instance, $defaults );
			/* <select> element options. */
			$taxonomies = get_taxonomies( array(
											'show_tagcloud' => true,
			), 'objects' );
			$link = array(
						'view' => esc_attr__( 'View', 'templatic-admin' ),
						'edit' => esc_attr__( 'Edit', 'templatic-admin' ),
					);
			$format = array(
						'flat' => esc_attr__( 'Flat', 'templatic-admin' ),
						'list' => esc_attr__( 'List', 'templatic-admin' ),
					);
			$order = array(
						'ASC'  => esc_attr__( 'Ascending', 'templatic-admin' ),
						'DESC' => esc_attr__( 'Descending', 'templatic-admin' ),
						'RAND' => esc_attr__( 'Random', 'templatic-admin' ),
					);
			$orderby = array(
						'count' => esc_attr__( 'Count', 'templatic-admin' ),
						'name' => esc_attr__( 'Name', 'templatic-admin' ),
					);
			$unit = array(
						'pt' => 'pt',
						'px' => 'px',
						'em' => 'em',
						'%' => '%',
					);
			?>
			<div class="hybrid-widget-controls columns-3">
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'title' ) ); ?>">
						<?php echo esc_html__( 'Title:', 'templatic-admin' ); ?>
					</label>
					<input type="text" class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'title' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'taxonomy' ) ); ?>"> <code> taxonomy </code> </label>
					<select class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'taxonomy' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'taxonomy' ) ); ?>[]" size="4" multiple="multiple">
						<?php	foreach ( $taxonomies as $taxonomy ) { ?>
						<option value="<?php echo esc_attr( $taxonomy->name ); ?>" <?php selected( in_array( $taxonomy->name, (array) $instance['taxonomy'] ) ); ?>> <?php echo esc_attr( $taxonomy->labels->menu_name ); ?> </option><?php } ?>
					</select>
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'format' ) ); ?>"> <code> format </code> </label>
					<select class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'format' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'format' ) ); ?>">
						<?php foreach ( $format as $option_value => $option_label ) { ?>
						<option value="<?php echo esc_attr( $option_value ); ?>" <?php selected( $instance['format'], $option_value ); ?>> <?php echo esc_attr( $option_label ); ?> </option>
						<?php } ?>
					</select>
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'order' ) ); ?>"> <code> order </code> </label>
					<select class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'order' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'order' ) ); ?>">
						<?php foreach ( $order as $option_value => $option_label ) { ?>
						<option value="<?php echo esc_attr( $option_value ); ?>" <?php selected( $instance['order'], $option_value ); ?>> <?php echo esc_attr( $option_label ); ?> </option>
						<?php } ?>
					</select>
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'orderby' ) ); ?>"> <code> orderby </code> </label>
					<select class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'orderby' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'orderby' ) ); ?>">
						<?php foreach ( $orderby as $option_value => $option_label ) { ?>
						<option value="<?php echo esc_attr( $option_value ); ?>" <?php selected( $instance['orderby'], $option_value ); ?>> <?php echo esc_attr( $option_label ); ?> </option>
						<?php } ?>
					</select>
				</p>
			</div>
			<div class="hybrid-widget-controls columns-3">
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'include' ) ); ?>"> <code> include </code> </label>
					<input type="text" class="smallfat code" id="<?php echo wp_kses_post( $this->get_field_id( 'include' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'include' ) ); ?>" value="<?php echo esc_attr( $instance['include'] ); ?>" />
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'exclude' ) ); ?>"> <code> exclude </code> </label>
					<input type="text" class="smallfat code" id="<?php echo wp_kses_post( $this->get_field_id( 'exclude' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'exclude' ) ); ?>" value="<?php echo esc_attr( $instance['exclude'] ); ?>" />
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'number' ) ); ?>"> <code> number </code> </label>
					<input type="text" class="smallfat code" id="<?php echo wp_kses_post( $this->get_field_id( 'number' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'number' ) ); ?>" value="<?php echo esc_attr( $instance['number'] ); ?>" />
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'largest' ) ); ?>"> <code> largest </code> </label>
					<input type="text" class="smallfat code" id="<?php echo wp_kses_post( $this->get_field_id( 'largest' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'largest' ) ); ?>" value="<?php echo esc_attr( $instance['largest'] ); ?>" />
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'smallest' ) ); ?>"> <code> smallest </code> </label>
					<input type="text" class="smallfat code" id="<?php echo wp_kses_post( $this->get_field_id( 'smallest' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'smallest' ) ); ?>" value="<?php echo esc_attr( $instance['smallest'] ); ?>" />
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'unit' ) ); ?>"> <code> unit </code> </label>
					<select class="smallfat" id="<?php echo wp_kses_post( $this->get_field_id( 'unit' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'unit' ) ); ?>">
						<?php
						foreach ( $unit as $option_value => $option_label ) {
							?>
							<option value="<?php echo esc_attr( $option_value ); ?>" <?php selected( $instance['unit'], $option_value ); ?>> <?php echo esc_attr( $option_label ); ?> </option>
							<?php
						} ?>
					</select>
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'separator' ) ); ?>"> <code> separator </code> </label>
					<input type="text" class="smallfat code" id="<?php echo wp_kses_post( $this->get_field_id( 'separator' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'separator' ) ); ?>" value="<?php echo esc_attr( $instance['separator'] ); ?>" />
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'child_of' ) ); ?>"> <code> child_of </code> </label>
					<input type="text" class="smallfat code" id="<?php echo wp_kses_post( $this->get_field_id( 'child_of' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'child_of' ) ); ?>" value="<?php echo esc_attr( $instance['child_of'] ); ?>" />
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'parent' ) ); ?>"> <code> parent </code> </label>
					<input type="text" class="smallfat code" id="<?php echo wp_kses_post( $this->get_field_id( 'parent' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'parent' ) ); ?>" value="<?php echo esc_attr( $instance['parent'] ); ?>" />
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'link' ) ); ?>"> <code> link </code> </label>
					<select class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'link' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'link' ) ); ?>">
						<?php
						foreach ( $link as $option_value => $option_label ) {
							?>
							<option value="<?php echo esc_attr( $option_value ); ?>" <?php selected( $instance['link'], $option_value ); ?>> <?php echo esc_attr( $option_label ); ?> </option>
							<?php
						} ?>
					</select>
				</p>
			</div>
			<div class="hybrid-widget-controls columns-3 column-last">
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'search' ) ); ?>"> <code> search </code> </label>
					<input type="text" class="widefat code" id="<?php echo wp_kses_post( $this->get_field_id( 'search' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'search' ) ); ?>" value="<?php echo esc_attr( $instance['search'] ); ?>" />
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'name__like' ) ); ?>"> <code> name__like </code> </label>
					<input type="text" class="widefat code" id="<?php echo wp_kses_post( $this->get_field_id( 'name__like' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'name__like' ) ); ?>" value="<?php echo esc_attr( $instance['name__like'] ); ?>" />
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'topic_count_text_callback' ) ); ?>"> <code> topic_count_text_callback </code> </label>
					<input type="text" class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'topic_count_text_callback' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'topic_count_text_callback' ) ); ?>" value="<?php echo esc_attr( $instance['topic_count_text_callback'] ); ?>" />
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'topic_count_scale_callback' ) ); ?>"> <code> topic_count_scale_callback </code> </label>
					<input type="text" class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'topic_count_scale_callback' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'topic_count_scale_callback' ) ); ?>" value="<?php echo esc_attr( $instance['topic_count_scale_callback'] ); ?>" />
				</p>
				<p>
			<label for="<?php echo wp_kses_post( $this->get_field_id( 'pad_counts' ) ); ?>">
				<input class="checkbox" type="checkbox" <?php checked( $instance['pad_counts'], true ); ?> id="<?php echo wp_kses_post( $this->get_field_id( 'pad_counts' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'pad_counts' ) ); ?>" />
				<?php echo esc_html__( 'Pad counts?', 'templatic-admin' ); ?>
				<code> pad_counts </code> </label>
			</p>
			<p>
				<label for="<?php echo wp_kses_post( $this->get_field_id( 'hide_empty' ) ); ?>">
					<input class="checkbox" type="checkbox" <?php checked( $instance['hide_empty'], true ); ?> id="<?php echo wp_kses_post( $this->get_field_id( 'hide_empty' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'hide_empty' ) ); ?>" />
					<?php echo esc_html__( 'Hide empty?', 'templatic-admin' ); ?>
					<code> hide_empty </code> </label>
				</p>
			</div>
			<div style="clear:both;"> &nbsp; </div>
			<?php
		}
	}
} // End if().

if ( ! class_exists( ' supreme_google_map' ) ) {
	/**
	 * The Google Map widget displays the google map to user. Users will able to see their own address on google map.
	 **/
	class supreme_google_map extends WP_Widget {
		/**
		 * Set up the widget's unique name, ID, class, description, and other options.
		 */
		function __construct() {
			$description         = esc_html__( 'Display a specific location on the map. Works in almost all widget areas.', 'templatic-admin' );
			$default_description = apply_filters( 'google_map_widget_description' , $description );
			$widget_options      = array(
									'classname'  => 'googlemap',
									'description' => apply_filters( 'google_map_description' , $default_description ),
				);
			/* Set up the widget control options. */
			$control_options = array(
				'height' => 350,
				);
			 /* Create the widget. */
			parent::__construct(
				'templatic_google_map',
				__( 'T &rarr; Google Map Location', 'templatic-admin' ),
				$widget_options,
				$control_options
			);
		}
		/**
		 * Outputs the widget based on the arguments input through the widget controls.
		 *
		 * @param array $args 	agurment of widget area.
		 * @param array $instance 	instances of widget.
		 */
		function widget( $args, $instance ) {

			extract( $args, EXTR_SKIP );
			$title = empty( $instance['title'] ) ? '' : apply_filters( 'widget_title', $instance['title'] );
			$address_latitude = empty( $instance['address_latitude'] ) ? '0' : apply_filters( 'widget_address_latitude', $instance['address_latitude'] );
			$address_longitude = empty( $instance['address_longitude'] ) ? '34' : apply_filters( 'widget_address_longitude', $instance['address_longitude'] );
			$address = empty( $instance['address'] ) ? '' : apply_filters( 'widget_address', $instance['address'] );
			$map_type = empty( $instance['map_type'] ) ? 'ROADMAP' : apply_filters( 'widget_map_type', $instance['map_type'] );
			$map_width = empty( $instance['map_width'] ) ? 200 : apply_filters( 'widget_map_width', $instance['map_width'] );
			$map_height = empty( $instance['map_height'] ) ? 425 : apply_filters( 'widget_map_height', $instance['map_height'] );
			$scale = empty( $instance['scale'] ) ? 17 : apply_filters( 'widget_scale', $instance['scale'] );
			echo wp_kses_post( $args['before_widget'] );

			/* language code */
			$lang = '';

			/* google api key */
			$key = '';

			/**
			 * Translate google map by language set by WPML
			 * set language parameter when wpml is activated and append to google map script as query string
			 * Variables : $lang ,value: current language constatne set by WPML
			 */
			if ( is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) && defined( 'ICL_LANGUAGE_CODE' ) ) {
				$lang = '&amp;language=' . ICL_LANGUAGE_CODE;
			}

			$templatic_settings = get_option( 'templatic_settings' );

			/* get API key for map added in map settings */
			if ( '' != $templatic_settings['tmpl_api_key'] ) {
				$key = '&amp;key=' . $templatic_settings['tmpl_api_key'];
			}
			if ( ! empty( $instance['title'] ) ) {
				echo wp_kses_post( $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'] );
			}
			$pin_img = get_template_directory_uri() . '/images/contact.png';
			if ( is_ssl() ) {
				$http = 'https://';
			} else {
				$http = 'http://';
			}

			$google_map_customizer = get_option( 'google_map_customizer' ); // Store google map customizer required formate.
			?>

			<?php
			wp_enqueue_script( 'google-maps-apiscript', $http . 'maps.googleapis.com/maps/api/js?v=3.exp&libraries=places' . $lang . $key, true );
			wp_enqueue_script( 'library-google-infobox', get_template_directory_uri() . '/library/js/infobox.js' );
			?>
			<script type="text/javascript">
				var geocoder;
				var map;
				var infobox;
				function initialize()
				{
					geocoder = new google.maps.Geocoder();
					var isDraggable = jQuery(document).width() > 480 ? true : false;
					var latlng = new google.maps.LatLng(-34.397, 150.644);
					var myOptions =
					{
						zoom: <?php echo intval( $scale ); ?>,
						draggable: isDraggable,
						center: latlng,
						mapTypeId: google.maps.MapTypeId.<?php echo esc_attr( $map_type ); ?>
					}
					map = new google.maps.Map(document.getElementById("map-canvas"), myOptions);
					var styles = [<?php echo wp_kses_post( substr( $google_map_customizer ), 0, -1 );?>];
					map.setOptions({styles: styles});
					codeAddress();
				}
				function codeAddress()
				{
					var address = '<?php echo wp_kses_post( $address ); ?>';
					geocoder.geocode(
					{
						'address': address
					}, function(results, status)
					{
						if (status == google.maps.GeocoderStatus.OK)
						{
							map.setCenter(results[0].geometry.location); var marker = new google.maps.Marker(
							{
								map: map, position: results[0].geometry.location,
								icon: '<?php echo wp_kses_post( $pin_img ); ?>',
					}); //Start
					var boxText = document.createElement("div");
					boxText.innerHTML ='<div class=google-map-info><div class=map-inner-wrapper><div class="map-item-info"><p><?php echo wp_kses_post( $address );?></p></div><div class=map-arrow></div></div></div>';
					var myinfoOptions =
					{
						content: boxText,disableAutoPan: false,maxWidth: 0 ,pixelOffset: new google.maps.Size(-118, -175) ,zIndex: null ,boxStyle:
						{
							opacity: 1 ,width: "240px",
							background:'white',
						},closeBoxURL: "http://www.google.com/intl/en_us/mapfiles/close.gif"
						,infoBoxClearance: new google.maps.Size(1, 1)
						,isHidden: false
						,pane: "floatPane"
						,enableEventPropagation: false
					};
					infobox = new InfoBox(myinfoOptions);
					infobox.open(map, marker);

					google.maps.event.addListener(marker, 'click', function() {
						infobox.open(map, marker);
					});
					infobox.open(map, marker);

		//End
				} else {
					alert("<?php esc_html_e( 'Geocode was not successful for the following reason', 'templatic' ); ?>: " + status);
				}
			});
		}
		google.maps.event.addDomListener(window, 'load', initialize);
		</script>
		<div class="wid_gmap graybox">
			<div id="map-canvas" style="height:<?php echo intval( $map_height ); ?>px; "> </div>
		</div>
		<?php
			echo wp_kses_post( $args['after_widget'] );
		}
		/**
		 *
		 * Updates the widget control options for the particular instance of the widget.
		 *
		 * @param array $new_instance     new instance of widget when saved from widget area.
		 * @param array $old_instance 	  old instances of widget.
		 */
		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$instance['title'] = ( $new_instance['title'] );
			$instance['address'] = ( $new_instance['address'] );
			$instance['address_latitude'] = strip_tags( $new_instance['address_latitude'] );
			$instance['address_longitude'] = strip_tags( $new_instance['address_longitude'] );
			$instance['map_width'] = strip_tags( $new_instance['map_width'] );
			$instance['map_height'] = strip_tags( $new_instance['map_height'] );
			$instance['map_type'] = strip_tags( $new_instance['map_type'] );
			$instance['scale'] = strip_tags( $new_instance['scale'] );
			return $instance;
		}
		/**
		 *
		 * Save the widget.
		 *
		 * @param array $instance     instance of widget.
		 */
		function form( $instance ) {
			$instance = wp_parse_args( (array) $instance, array(
															'title' => esc_html__( 'Find us on map', 'templatic' ),
			) );
			$title   = ( $instance['title'] );
			$address = ( isset( $instance['address'] ) ) ? ( $instance['address'] ) : '230 Vine Street And locations throughout Old City, Philadelphia, PA 19106';
			$address_latitude = ( isset( $instance['address'] ) ) ? strip_tags( $instance['address_latitude'] ) : '';
			$address_longitude = ( isset( $instance['address'] ) ) ? strip_tags( $instance['address_longitude'] ) : '';
			$map_width = ( isset( $instance['address'] ) ) ? strip_tags( $instance['map_width'] ) : '';
			$map_height = ( isset( $instance['address'] ) ) ? strip_tags( $instance['map_height'] ) : 425;
			$map_type = ( isset( $instance['address'] ) ) ? strip_tags( $instance['map_type'] ) : 'ROADMAP';
			$scale = ( isset( $instance['address'] ) ) ? strip_tags( $instance['scale'] ) : 17;
			?>
			<p>
				<label for="<?php  echo wp_kses_post( $this->get_field_id( 'title' ) ); ?>">
					<?php echo esc_html__( 'Title', 'templatic-admin' );?>
					:
					<input class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'title' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
				</label>
			</p>
			<p>
				<label for="<?php echo wp_kses_post( $this->get_field_id( 'address' ) ); ?>">
					<?php  echo esc_html__( 'Address: <small>(eg: 230 Vine Street And locations throughout Old City, Philadelphia, PA 19106)</small>', 'templatic-admin' );?>
					<input type="text" class="widefat" rows="6" cols="20" id="<?php echo wp_kses_post( $this->get_field_id( 'address' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'address' ) ); ?>"  value="<?php echo esc_attr( $address ); ?>">
				</label>
			</p>
			<p>
				<label for="<?php echo wp_kses_post( $this->get_field_id( 'map_height' ) ); ?>">
					<?php  echo esc_html__( 'Map Height in pixels: <small>(To change, only enter a numeric value)</small>', 'templatic-admin' );?>
					<input type="text" class="widefat" rows="6" cols="20" id="<?php echo wp_kses_post( $this->get_field_id( 'map_height' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'map_height' ) ); ?>" value="<?php echo esc_attr( $map_height ); ?>">
				</label>
			</p>
			<p>
				<label for="<?php echo wp_kses_post( $this->get_field_id( 'scale' ) ); ?>">
					<?php  echo esc_html__( 'Map Zoom Factor', 'templatic-admin' );?>
					:
					<select id="<?php echo wp_kses_post( $this->get_field_id( 'scale' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'scale' ) ); ?>">
						<?php
						for ( $i = 3; $i < 20; $i++ ) {
							?>
							<option value="<?php echo intval( $i );?>" <?php
							if ( esc_attr( $scale ) == $i ) {
								echo 'selected="selected"';
							}?> >
							<?php echo intval( $i ); ?>
							</option>
						<?php
						}
						?>
					</select>
				</label>
			</p>
			<p>
				<label for="<?php echo wp_kses_post( $this->get_field_id( 'map_type' ) ); ?>">
					<?php  echo esc_html__( 'Select Map Type', 'templatic-admin' );?>
					:
					<select id="<?php echo wp_kses_post( $this->get_field_id( 'map_type' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'map_type' ) ); ?>">
						<option value="ROADMAP" <?php
						if ( 'ROADMAP' == esc_attr( $map_type ) ) {
							echo 'selected="selected"';
						}?> >
						<?php  echo esc_html__( 'Road Map', 'templatic-admin' );?>
						</option>
						<option value="SATELLITE" <?php
						if ( 'SATELLITE' == esc_attr( $map_type ) ) {
							echo 'selected="selected"';
						}?>>
							<?php  echo esc_html__( 'Satellite Map', 'templatic-admin' );?>
						</option>
					</select>
				</label>
			</p>
		<?php
		}
	}
} // End if().

if ( ! class_exists( 'supreme_social_media' ) ) {
	/**
	 * Social Widget Class.
	 **/
	class supreme_social_media extends WP_Widget {
		/**
		 * Set up the widget's unique name, ID, class, description, and other options.
		 */
		function __construct() {

			$widget_ops = array(
							'classname'  	=> 'social_media',
							'description'	=> apply_filters( 'supreme_social_media_description', esc_html__( 'Add icons and links to your social media accounts. Works in header, footer, main content and sidebar widget areas.', 'templatic-admin' ) ),
						);
			parent::__construct( 'social_media', esc_html__( 'T &rarr; Social Media', 'templatic-admin' ), $widget_ops );
		}
		/**
		 * Outputs the widget based on the arguments input through the widget controls.
		 *
		 * @param array $args 	agurment of widget area.
		 * @param array $instance 	instances of widget.
		 */
		function widget( $args, $instance ) {
			extract( $args, EXTR_SKIP );
			echo wp_kses_post( $args['before_widget'] );

			$title = empty( $instance['title'] ) ? '' : apply_filters( 'widget_title', $instance['title'] );
			$social_description = empty( $instance['social_description'] ) ? '' : apply_filters( 'social_description', $instance['social_description'] );
			$social_link = empty( $instance['social_link'] ) ? '' : apply_filters( 'widget_social_link', $instance['social_link'] );
			$social_icon = empty( $instance['social_icon'] ) ? '' : apply_filters( 'widget_social_icon', $instance['social_icon'] );
			$social_text = empty( $instance['social_text'] ) ? '' : apply_filters( 'widget_social_text', $instance['social_text'] );
			if ( function_exists( 'icl_register_string' ) ) {
				icl_register_string( 'templatic','social_media_title' , $title );
				$title              = icl_t( 'templatic','social_media_title' , $title );
				icl_register_string( 'templatic','social_description' , $social_description );
				$social_description = icl_t( 'templatic','social_description' , $social_description );
			}
			if ( '' != $title ) {
				echo wp_kses_post( $args['before_title'] );
				echo wp_kses_post( $title );
				echo wp_kses_post( $args['after_title'] );
			}
			if ( '' != $social_description ) : ?>
				<p class="social_description"> <?php echo wp_kses_post( $social_description );?> </p>
		<?php endif;?>
		<div class="social_media">
			<ul class="social_media_list">
				<?php
				$social_icon_count = count( $social_icon );
				for ( $c = 0; $c < $social_icon_count; $c++ ) {
					if ( function_exists( 'icl_register_string' ) ) {
						icl_register_string( 'templatic', @$social_text[ $c ], @$social_text[ $c ] );
						$social_text[ $c ] = icl_t( 'templatic', @$social_text[ $c ], @$social_text[ $c ] );
					}
					?>
					<li> <a href="<?php echo wp_kses_post( @$social_link[ $c ] ); ?>" target="_blank" >
						<?php
						if ( '' != @$social_icon[ $c ] ) :?>
						<span class="social_icon"> <img src="<?php echo esc_url( @$social_icon[ $c ] );?>" alt="<?php echo sprintf(__( '%s', 'templatic' ), wp_kses_post( @$social_text[ $c ] ) );?>" /> </span>
					<?php endif;?>
					<?php echo ( isset( $social_text[ $c ] ) ) ? sprintf(__( '%s', 'templatic' ), wp_kses_post( @$social_text[ $c ] ) ) : '';?> </a> </li>
					<?php
				}
				?>
			</ul>
		</div>
		<?php

			echo wp_kses_post( $args['after_widget'] );
		}
		/**
		 *
		 * Updates the widget control options for the particular instance of the widget.
		 *
		 * @param array $new_instance     new instance of widget when saved from widget area.
		 * @param array $old_instance 	  old instances of widget.
		 */
		function update( $new_instance, $old_instance ) {
			return $new_instance;
		}
		/**
		 *
		 * Save the widget.
		 *
		 * @param array $instance     instance of widget.
		 */
		function form( $instance ) {

			$instance = wp_parse_args( (array) $instance, array(
															'title' => esc_html__( 'Connect To Us', 'templatic-admin' ),
															'social_description' => esc_html__( 'Find Us On Social Sites', 'templatic-admin' ),
															'social_link' => '',
															'social_text' => '',
														)
			);
			$title              = strip_tags( $instance['title'] );
			$social_description = $instance['social_description'];
			$social_link1       = ( $instance['social_link'] );
			$social_icon1       = ( $instance['social_icon'] );
			$social_text1       = ( $instance['social_text'] );
			global $social_link, $social_icon, $social_text;
			$text_social_link = $this->get_field_name( 'social_link' );
			$text_social_icon = $this->get_field_name( 'social_icon' );
			$text_social_text = $this->get_field_name( 'social_text' );
			?>
			<p>
				<label for="<?php echo wp_kses_post( $this->get_field_id( 'title' ) ); ?>">
					<?php echo esc_html__( 'Title', 'templatic-admin' );?>
					:
					<input class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'title' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
				</label>
			</p>
			<p>
				<label for="<?php echo wp_kses_post( $this->get_field_id( 'social_description' ) ); ?>">
					<?php echo esc_html__( 'Description', 'templatic-admin' );?>
					:
					<input class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'social_description' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'social_description' ) ); ?>" type="text" value="<?php echo wp_kses_post( $social_description ); ?>" />
				</label>
			</p>
			<p> <i> <?php echo esc_html__( 'Please enter full URL to your profiles.', 'templatic-admin' ); ?> </i> </p>
			<p>
				<label for="<?php echo wp_kses_post( $this->get_field_id( 'social_link' ) ); ?>">
					<?php echo esc_html__( 'Social Link', 'templatic-admin' );?>
					:
					<input class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'social_link' ) ); ?>" name="<?php echo wp_kses_post( $text_social_link ); ?>[]" type="text" value="<?php echo esc_attr( @$social_link1[0] ); ?>" />
				</label>
			</p>
			<p>
				<label for="<?php echo wp_kses_post( $this->get_field_id( 'social_icon' ) ); ?>">
					<?php echo esc_html__( 'Social Icon', 'templatic-admin' );?>
					:
					<input class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'social_icon' ) ); ?>" name="<?php echo wp_kses_post( $text_social_icon ); ?>[]" type="text" value="<?php echo esc_attr( @$social_icon1[0] ); ?>" />
				</label>
			</p>
			<p>
				<label for="<?php echo wp_kses_post( $this->get_field_id( 'social_text1' ) ); ?>">
					<?php echo esc_html__( 'Social Text', 'templatic-admin' );?>
					:
					<input class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'social_text1' ) ); ?>" name="<?php echo wp_kses_post( $text_social_text ); ?>[]" type="text" value="<?php echo esc_attr( @$social_text1[0] ); ?>" />
				</label>
			</p>
			<div id="social_tGroup" class="social_tGroup">
			<?php
			$social_link1_count = count( $social_link1 );
			for ( $i = 1; $i < $social_link1_count; $i++ ) {
				if ( '' != $social_link1[ $i ] ) {
					$j = $i + 1;
					echo '<div  class="SocialTextDiv' . intval( $j ) . '">';
					echo '<p>';
					echo '<label>' . esc_html__( 'Social Link', 'templatic-admin' ) . ' ' . intval( $j );
					echo '<input class="widefat" name="' . wp_kses_post( $text_social_link ) . '[]" type="text" value="' . esc_attr( $social_link1[ $i ] ) . '" />';
					echo '</label>';
					echo '</p>';
					echo '<p>';
					echo '<label>Social Icon ' . intval( $j );
					echo ' <input type="text" class="widefat"  name="' . wp_kses_post( $text_social_icon ) . '[]" value="' . esc_attr( $social_icon1[ $i ] ) . '">';
					echo '</label>';
					echo '</p>';
					echo '<p>';
					echo '<label>Social Text ' . intval( $j );
					echo ' <input type="text" class="widefat"  name="' . wp_kses_post( $text_social_text ) . '[]" value="' . esc_attr( $social_text1[ $i ] ) . '">';
					echo '</label>';
					echo '</p>';
					echo '</div>';
				}
			}

			?>
		</div>
		<script type="text/javascript">
			var social_counter = <?php echo intval( $j + 1 );?>;
		</script>
		<a
		href="javascript:void(0);" id="addtButton" class="addButton" onclick="social_add_tfields( '<?php echo wp_kses_post( $text_social_link ); ?>','<?php echo wp_kses_post( $text_social_icon ); ?>','<?php echo wp_kses_post( $text_social_text ); ?>' );"> + <?php esc_html_e( 'Add more', 'templatic' );?> </a> &nbsp; | &nbsp;
		<a
		href="javascript:void(0);" id="removetButton" class="removeButton" onclick="social_remove_tfields();">- <?php esc_html_e( 'Remove', 'templatic' );?> </a>
		<?php
		}
	}
	add_action( 'admin_head', 'supreme_add_script_addnew_1' );
	add_action( 'customize_controls_enqueue_scripts', 'supreme_add_script_addnew_1', 999 );
	if ( ! function_exists( 'supreme_add_script_addnew_1' ) ) {
		/**
		 *
		 * Script to add mulitple icon.
		 */
		function supreme_add_script_addnew_1() {
			global $social_link, $social_icon, $social_text;
			?>
			<script type="application/javascript">
				function social_add_tfields(name,ilname,sname)
				{
					var SocialTextDiv = jQuery(document.createElement( 'div' ) ).attr("class", 'SocialTextDiv' + social_counter);
					SocialTextDiv.html( '<p><label><?php echo esc_html__( 'Social Link', 'templatic-admin' ); ?> '+ social_counter+': </label>'+'<input type="text" class="widefat" name="'+name+'[]" id="textbox' + social_counter + '" value="" /></p>' );
					SocialTextDiv.append( '<p><label><?php echo esc_html__( 'Social Icon', 'templatic-admin' ); ?> '+ social_counter+': </label>'+'<input type="text" class="widefat" name="'+ilname+'[]" id="textbox' + social_counter + '" value="" ></p>' );
					SocialTextDiv.append( '<p><label><?php echo esc_html__( 'Social Text', 'templatic-admin' ); ?> '+ social_counter+': </label>'+'<input type="text" class="widefat" name="'+sname+'[]" id="textbox' + social_counter + '" value="" ></p>' );
					SocialTextDiv.appendTo(".social_tGroup");
					social_counter++;
				}
				function social_remove_tfields()
				{
					if (social_counter-1==1)
					{
						alert("<?php echo esc_html__( 'You need one textbox required.', 'templatic-admin' )?>");
						return false;
					}
					social_counter--;
					jQuery(".SocialTextDiv" + social_counter).remove();
				}
			</script>
			<?php
		}
	}
} // End if().

if ( ! class_exists( 'supreme_subscriber_widget' ) ) {
	/**
	 * Subscriber Widget Class.
	 **/
	class supreme_subscriber_widget extends WP_Widget {
		/**
		 * Set up the widget's unique name, ID, class, description, and other options.
		 */
		function __construct() {

			$widget_ops = array(
							'classname'  	=> 'subscribe',
							'description'	=> apply_filters( 'supreme_subscriber_widget_title', esc_html__( 'Shows a subscribe box with which users can subscribe to your newsletter. Works best in main content, subsidiary and footer areas.', 'templatic-admin' ) ),
						);

			parent::__construct( 'supreme_subscriber_widget', apply_filters( 'subscribewidget_filter', esc_html__( 'T &rarr; Newsletter', 'templatic-admin' ) ), $widget_ops );
		}
		/**
		 * Outputs the widget based on the arguments input through the widget controls.
		 *
		 * @param array $args 	agurment of widget area.
		 * @param array $instance 	instances of widget.
		 */
		function widget( $args, $instance ) {

			extract( $args, EXTR_SKIP );
			global $mailchimp_api_key,$mailchimp_list_id;
			$feedburner_id = empty( $instance['feedburner_id'] ) ? '' : apply_filters( 'widget_feedburner_id', $instance['feedburner_id'] );
			$title = empty( $instance['title'] ) ? '' : apply_filters( 'widget_title', $instance['title'] );

			do_action( 'wpml_register_single_string', 'templatic', 'subscribe_text', $instance['text'] );

			$text = empty( $instance['text'] ) ? '': apply_filters( 'widget_text', $instance['text'] );
			$newsletter_provider = empty( $instance['newsletter_provider'] ) ? 'feedburner' : apply_filters( 'widget_newsletter_provider', $instance['newsletter_provider'] );
			$mailchimp_api_key = empty( $instance['mailchimp_api_key'] ) ? '' : apply_filters( 'widget_mailchimp_api_key', $instance['mailchimp_api_key'] );
			$mailchimp_list_id = empty( $instance['mailchimp_list_id'] ) ? '' : apply_filters( 'widget_mailchimp_list_id', $instance['mailchimp_list_id'] );
			$aweber_list_name = empty( $instance['aweber_list_name'] ) ? '' : apply_filters( 'widget_aweber_list_name', $instance['aweber_list_name'] );
			$feedblitz_list_id = empty( $instance['feedblitz_list_id'] ) ? '' : apply_filters( 'widget_feedblitz_list_id', $instance['feedblitz_list_id'] );
			add_action( 'wp_footer','attach_mailchimp_js' );
			echo wp_kses_post( $args['before_widget'] );

			if ( function_exists( 'icl_register_string' ) ) {
				icl_register_string( 'templatic' , $title, $title );
				$title1 = icl_t( 'templatic' , $title, $title );
			} else {
				$title1 = $title;
			}
			if ( $title1 && current_theme_supports( 'newsletter_title_abodediv' ) ) {
				echo wp_kses_post( $args['before_title'] . $title1 . $args['after_title'] );
			}
			if ( $title1 && ! current_theme_supports( 'newsletter_title_abodediv' ) ) {	?>
				<h3 class="widget-title"> <?php echo wp_kses_post( $title1 ); ?> </h3>
			<?php }
			if ( function_exists( 'icl_register_string' ) ) {
				icl_register_string( 'templatic', 'subscribe_text' , $text );
				$text1 = icl_t( 'templatic', 'subscribe_text' , $text );
			} else {
				$text1 = $text;
			}
			if ( $text1 ) {
				?>
				<p> <?php echo wp_kses_post( $text1 ); ?> </p>
				<?php
			}?>
			<span class="newsletter_msg" id="newsletter_msg"> </span>
			<?php
			if ( 'feedburner' == $newsletter_provider ) {
				/* For Feed burner */
				?>
				<div class="subscriber_container">
					<form action="//feedburner.google.com/fb/a/mailverify" method="post" target="popupwindow" onsubmit="window.open( '//feedburner.google.com/fb/a/mailverify?uri=<?php echo wp_kses_post( $feedburner_id ); ?>', 'popupwindow', 'scrollbars=yes,width=550,height=520' );return true" >
						<input type="text" id="newsletter_name" name="name" value="" class="field" onfocus="if (this.placeholder == '<?php esc_html_e( 'Your Name', 'templatic' ); ?>' ) {this.placeholder = '';}" onblur="if (this.placeholder == '' ) {this.placeholder = '<?php esc_html_e( 'Your Name', 'templatic' ); ?>';}" placeholder="<?php esc_html_e( 'Your Name', 'templatic' ); ?>" />
						<input type="text" id="newsletter_email" name="email" value="" class="field" onfocus="if (this.placeholder == '<?php esc_html_e( 'Your Email Address', 'templatic' ); ?>' ) {this.placeholder = '';}" onblur="if (this.placeholder == '' ) {this.placeholder = '<?php esc_html_e( 'Your Email Address', 'templatic' ); ?>';}" placeholder="<?php esc_html_e( 'Your Email Address', 'templatic' ); ?>"/>
						<input type="hidden" value="<?php echo wp_kses_post( $feedburner_id ); ?>" name="uri"   />
						<input type="hidden" value="<?php bloginfo( 'name' ); ?>" name="title" />
						<input type="hidden" name="loc" value="en_US"/>
						<input class="replace" type="submit" name="submit" value="<?php esc_html_e( 'Subscribe', 'templatic' );?>" />
					</form>
				</div>
				<?php
			} elseif ( 'mailchimp' == $newsletter_provider ) {
				/* For MailChimp */
				?>
				<div class="subscriber_container">
					<input type="text" name="name" id="name" value="" placeholder="<?php esc_html_e( 'Name', 'templatic' );?>" class="field" onfocus="if (this.placeholder == 'Name' ) {this.placeholder = '';}" onblur="if (this.placeholder == '' ) {this.placeholder = '<?php esc_html_e( 'Name', 'templatic' );?>';}"  />
					<input type="text" name="email" id="email" value="" class="field" onfocus="if (this.placeholder == '<?php esc_html_e( 'Your Email Address', 'templatic' ); ?>' ) {this.placeholder = '';}" onblur="if (this.placeholder == '' ) {this.placeholder = '<?php esc_html_e( 'Your Email Address', 'templatic' ); ?>';}" placeholder="<?php esc_html_e( 'Your Email Address', 'templatic' ); ?>"/>
					<input class="replace" type="submit" name="mailchimp_submit" id="mailchimp_submit" value="<?php esc_html_e( 'Subscribe', 'templatic' );?>" />
					<span id='process' style='display:none;'> <img src="<?php echo esc_url( get_template_directory_uri() ) . '/library/images/process.gif'; ?>" alt='Processing..' /> </span>
				</div>
				<?php
			} elseif ( 'feedblitz' == $newsletter_provider ) {
				/* For Feed Bliz */
				?>
				<div class="subscriber_container">
					<form Method="POST" action="http://www.feedblitz.com/f/f.fbz?AddNewUserDirect" target="popupwindow" onsubmit="window.open( 'http://www.feedblitz.com/f/f.fbz?AddNewUserDirect', 'popupwindow', 'scrollbars=yes,width=600,height=730' );return true" >
						<input type="text" name="email" id="email" value="" class="field" onfocus="if (this.placeholder == '<?php esc_html_e( 'Your Email Address', 'templatic' ); ?>' ) {this.placeholder = '';}" onblur="if (this.placeholder == '' ) {this.placeholder = '<?php esc_html_e( 'Your Email Address', 'templatic' ); ?>';}" placeholder="<?php esc_html_e( 'Your Email Address', 'templatic' ); ?>"/>
						<input name="FEEDID" type="hidden" value="<?php echo wp_kses_post( $feedblitz_list_id );?>">
						<input type="submit" name="feedblitz_submit" value="<?php esc_html_e( 'Subscribe', 'templatic' );?>">
						<br />
					</form>
				</div>
				<?php
			} elseif ( 'aweber' == $newsletter_provider ) {
				/* For Awaber subscription */
				$url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
				?>
				<div class="subscriber_container">
					<form method="post" action="http://www.aweber.com/scripts/addlead.pl">
						<input type="hidden" name="listname" value="<?php echo wp_kses_post( $aweber_list_name );?>" />
						<input type="hidden" name="redirect" value="<?php echo esc_url ( $url );?>" />
						<input type="hidden" name="meta_adtracking" value="custom form" />
						<input type="hidden" name="meta_message" value="1" />
						<input type="hidden" name="meta_required" value="name,email" />
						<input type="hidden" name="meta_forward_vars" value="1" />
						<input type="text" name="name" id="name" value="" class="field" onfocus="if (this.placeholder == '<?php esc_html_e( 'Name', 'templatic' ); ?>' ) {this.placeholder = '';}" onblur="if (this.placeholder == '' ) {this.placeholder = '<?php esc_html_e( 'Names', 'templatic' ); ?>';}" placeholder="<?php esc_html_e( 'Names', 'templatic' ); ?>"/>
						<input type="text" name="email" id="email" value="" class="field" onfocus="if (this.placeholder == '<?php esc_html_e( 'Your Email Address', 'templatic' ); ?>' ) {this.placeholder = '';}" onblur="if (this.placeholder == '' ) {this.placeholder = '<?php esc_html_e( 'Your Email Address', 'templatic' ); ?>';}" placeholder="<?php esc_html_e( 'Your Email Address', 'templatic' ); ?>"/>
						<input type="submit" name="aweber_submit" value="Subscribe" />
					</form>
				</div>
				<?php
			} // End if().	?>



			<!--End mc_embed_signup-->
			<?php
			echo wp_kses_post( $args['after_widget'] );
		}
		/**
		 *
		 * Updates the widget control options for the particular instance of the widget.
		 *
		 * @param array $new_instance     new instance of widget when saved from widget area.
		 * @param array $old_instance 	  old instances of widget.
		 */
		function update( $new_instance, $old_instance ) {
			return $new_instance;
		}
		/**
		 *
		 * Save the widget.
		 *
		 * @param array $instance     instance of widget.
		 */
		function form( $instance ) {

			$instance = wp_parse_args( (array) $instance, array(
															'title' => esc_html__( 'Subscribe To Newsletter', 'templatic-admin' ),
															'text' 	=> esc_html__( 'Subscribe to get our latest news', 'templatic-admin' ),
															'newsletter_provider' => 'feedburner',
															'feedburner_id'      => '',
															'mailchimp_api_key'  => '',
															'mailchimp_list_id'  => '',
															'aweber_list_name'   => '',
															'feedblitz_list_id'  => '',
														)
			);
			$feedburner_id       = strip_tags( $instance['feedburner_id'] );
			$title               = strip_tags( $instance['title'] );
			$text                = $instance['text'];
			$newsletter_provider = strip_tags( $instance['newsletter_provider'] );
			$mailchimp_api_key   = strip_tags( $instance['mailchimp_api_key'] );
			$mailchimp_list_id   = strip_tags( $instance['mailchimp_list_id'] );
			$aweber_list_name    = strip_tags( $instance['aweber_list_name'] );
			$feedblitz_list_id   = strip_tags( $instance['feedblitz_list_id'] );?>
			<p>
				<label for="<?php echo wp_kses_post( $this->get_field_id( 'title' ) ); ?>">
					<?php echo esc_html__( 'Title:', 'templatic-admin' );?>
					<input class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'title' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
				</label>
			</p>
			<p>
				<label for="<?php echo wp_kses_post( $this->get_field_id( 'text' ) ); ?>">
					<?php echo esc_html__( 'Text Below Title:', 'templatic-admin' );?>
					<input class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'text' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'text' ) ); ?>" type="text" value="<?php echo esc_attr( $text ); ?>" />
				</label>
			</p>
			<p>
				<label for="<?php echo wp_kses_post( $this->get_field_id( 'newsletter_provider' ) ); ?>">
					<?php echo esc_html__( 'Newsletter Provider', 'templatic-admin' );?>
					:
					<select id="<?php echo wp_kses_post( $this->get_field_id( 'newsletter_provider' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'newsletter_provider' ) ); ?>" onchange="show_hide_divs(this.value,'<?php echo wp_kses_post( $this->get_field_id( 'feedburner_id1' ) ); ?>','<?php echo wp_kses_post( $this->get_field_id( 'mailchimp_id1' ) ); ?>','<?php echo wp_kses_post( $this->get_field_id( 'feedblitz_id1' ) ); ?>','<?php echo wp_kses_post( $this->get_field_id( 'aweber_id1' ) ); ?>' );" >
						<option value="">
							<?php echo esc_html__( 'Please select', 'templatic-admin' );?>
						</option>
						<option value="feedburner" <?php
						if ( 'feedburner' == $newsletter_provider ) {
							echo 'selected=selected';
						}?>>
						<?php echo esc_html__( 'Feedburner', 'templatic-admin' );?>
						</option>
						<option value="mailchimp" <?php
						if ( 'mailchimp' == $newsletter_provider ) {
							echo 'selected=selected';
						}?>>
						<?php echo esc_html__( 'MailChimp', 'templatic-admin' );?>
						</option>
						<option value="feedblitz" <?php
						if ( 'feedblitz' == $newsletter_provider ) {
							echo 'selected=selected';
						}?>>
						<?php echo esc_html__( 'FeedBlitz', 'templatic-admin' );?>
						</option>
						<option value="aweber" <?php
						if ( 'aweber' == $newsletter_provider ) {
							echo 'selected=selected';
						}?>>
						<?php echo esc_html__( 'Aweber', 'templatic-admin' );?>
						</option>
					</select>
				</label>
			</p>
			<p id="<?php echo wp_kses_post( $this->get_field_id( 'feedburner_id1' ) ); ?>" style="<?php
			if ( 'feedburner' == $newsletter_provider ) {
				echo 'display:block';
			} else {
				echo 'display:none';
			};?>">
				<label for="<?php echo wp_kses_post( $this->get_field_id( 'feedburner_id' ) ); ?>">
					<?php echo esc_html__( 'ID:', 'templatic-admin' );?>
					<input class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'feedburner_id' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'feedburner_id' ) ); ?>" type="text" value="<?php echo esc_attr( $feedburner_id ); ?>" />
				</label>
			</p>
		<p id="<?php echo wp_kses_post( $this->get_field_id( 'mailchimp_id1' ) ); ?>" style="<?php
		if ( 'mailchimp' == $newsletter_provider ) {
			echo 'display:block';
		} else {
			echo 'display:none';
		};?>">
			<label for="<?php echo wp_kses_post( $this->get_field_id( 'mailchimp_api_key' ) ); ?>">
				<?php echo esc_html__( 'Mailchimp API Key:', 'templatic-admin' );?>
				<a href="https://us1.admin.mailchimp.com/account/api/" target="_blank"> (?) </a>
				<input class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'mailchimp_api_key' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'mailchimp_api_key' ) ); ?>" type="text" value="<?php echo esc_attr( $mailchimp_api_key ); ?>" />
			</label>
			<label for="<?php echo wp_kses_post( $this->get_field_id( 'mailchimp_list_id' ) ); ?>">
				<?php echo esc_html__( 'List Id:', 'templatic-admin' );?>
				<a href="http://kb.mailchimp.com/article/how-can-i-find-my-list-id" target="_blank"> (?) </a>
				<input class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'mailchimp_list_id' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'mailchimp_list_id' ) ); ?>" type="text" value="<?php echo esc_attr( $mailchimp_list_id ); ?>" />
			</label>
		</p>
		<p id="<?php echo wp_kses_post( $this->get_field_id( 'feedblitz_id1' ) ); ?>" style="<?php
		if ( 'feedblitz' == $newsletter_provider ) {
			echo 'display:block';
		} else {
			echo 'display:none';
		};?>">
			<label for="<?php echo wp_kses_post( $this->get_field_id( 'feedblitz_list_id' ) ); ?>">
				<?php echo esc_html__( 'List ID:', 'templatic-admin' );?>
				<input class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'feedblitz_list_id' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'feedblitz_list_id' ) ); ?>" type="text" value="<?php echo esc_attr( $feedblitz_list_id ); ?>" />
			</label>
		</p>
		<p id="<?php echo wp_kses_post( $this->get_field_id( 'aweber_id1' ) ); ?>" style="<?php
		if ( 'aweber' == $newsletter_provider ) {
			echo 'display:block';
		} else {
			echo 'display:none';
		};?>">
			<label for="<?php echo wp_kses_post( $this->get_field_id( 'aweber_list_name' ) ); ?>">
				<?php echo esc_html__( 'Unique List Id:', 'templatic-admin' );?>
				<input class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'aweber_list_name' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'aweber_list_name' ) ); ?>" type="text" value="<?php echo esc_attr( $aweber_list_name ); ?>" />
			</label>
		</p>
		<script type="text/javascript">
			function show_hide_divs(newsletter_provider,feedburner_id,mailchimp_id,feedblitz_id,aweber_id)
			{
				if (newsletter_provider == 'feedburner' )
				{
					jQuery( '#'+feedburner_id).show( 'slow' );
					jQuery( '#'+mailchimp_id).hide( 'slow' );
					jQuery( '#'+feedblitz_id).hide( 'slow' );
					jQuery( '#'+aweber_id).hide( 'slow' );
				}else if (newsletter_provider == 'mailchimp' )
				{
					jQuery( '#'+mailchimp_id).show( 'slow' );
					jQuery( '#'+feedburner_id).hide( 'slow' );
					jQuery( '#'+feedblitz_id).hide( 'slow' );
					jQuery( '#'+aweber_id).hide( 'slow' );
				}else if (newsletter_provider == 'feedblitz' )
				{
					jQuery( '#'+feedblitz_id).show( 'slow' );
					jQuery( '#'+mailchimp_id).hide( 'slow' );
					jQuery( '#'+feedburner_id).hide( 'slow' );
					jQuery( '#'+aweber_id).hide( 'slow' );
				}else if (newsletter_provider == 'aweber' )
				{
					jQuery( '#'+aweber_id).show( 'slow' );
					jQuery( '#'+feedblitz_id).hide( 'slow' );
					jQuery( '#'+mailchimp_id).hide( 'slow' );
					jQuery( '#'+feedburner_id).hide( 'slow' );
				}
			}
		</script>
		<?php
		}
	}

	if ( ! function_exists( 'attach_mailchimp_js' ) ) {
		/**
		 * Script for mailchmp.
		 */
		function attach_mailchimp_js() {
			global $mailchimp_api_key, $mailchimp_list_id;
			?>
			<script type="text/javascript">
				jQuery.noConflict();
				jQuery(document).ready(function()
				{
					jQuery( '#mailchimp_submit' ).click(function()
					{
						jQuery( '#process' ).css( 'display','block' );
						var datastring = '&name=' + escape(jQuery( '.subscriber_container #name' ).val()) + '&email=' + escape(jQuery( '.subscriber_container #email' ).val()) + '&api_key=<?php echo wp_kses_post( $mailchimp_api_key );?>&list_id=<?php echo wp_kses_post( $mailchimp_list_id );?>';
						jQuery.ajax(
						{
							url: '<?php echo esc_url( get_template_directory_uri() ) . '/library/classes/process_mailchimp.php';?>',
							data: datastring,
							success: function(msg)
							{
								jQuery( '#process' ).css( 'display','none' );
								jQuery( '#newsletter_msg' ).html(msg);
							},
							error: function(msg)
							{
								jQuery( '#process' ).css( 'display','none' );
								jQuery( '#newsletter_msg' ).html(msg);
							}
						});
						return false;
					});
				});
			</script>
			<?php
		}
	} // End if().
} // End if().

define( 'TITLE_TEXT', esc_html__( 'Title', 'templatic-admin' ) );
define( 'SET_TIME_OUT_TEXT', esc_html__( 'Set Time Out', 'templatic-admin' ) );
define( 'SET_THE_SPEED_TEXT', esc_html__( 'Set the speed', 'templatic-admin' ) );
define( 'QUOTE_TEXT', esc_html__( 'Quote text', 'templatic-admin' ) );
define( 'AUTHOR_NAME_TEXT', esc_html__( 'Author name', 'templatic-admin' ) );

if ( ! class_exists( ' supreme_testimonials_widget' ) ) {
	/**
	 * Testimonials Widget Class.
	 **/
	class supreme_testimonials_widget extends WP_Widget {
		/**
		 * Set up the widget's unique name, ID, class, description, and other options.
		 */
		function __construct() {

			$widget_ops = array(
							'classname'   => 'testimonials',
							'description' => esc_html__( 'Display a set of sliding testimonials. Works best in sidebar areas.', 'templatic-admin' ),
						);
			parent::__construct( 'testimonials_widget', apply_filters( 'templ_testimonial_widget_title_filter', esc_html__( 'T &rarr; Testimonials', 'templatic-admin' ) ), $widget_ops );
		}
		/**
		 * Outputs the widget based on the arguments input through the widget controls.
		 *
		 * @param array $args 	agurment of widget area.
		 * @param array $instance 	instances of widget.
		 */
		function widget( $args, $instance ) {
			extract( $args, EXTR_SKIP );
			echo wp_kses_post( $args['before_widget'] );
			$title = empty( $instance['title'] ) ? '' : apply_filters( 'widget_title', $instance['title'] );
			$link_text = empty( $instance['link_text'] ) ? '' : apply_filters( 'widget_title', $instance['link_text'] );
			$link_url = empty( $instance['link_url'] ) ? '' : apply_filters( 'widget_title', $instance['link_url'] );
			$fadin = empty( $instance['fadin'] ) ? '3000' : apply_filters( 'widget_fadin', $instance['fadin'] );
			$fadout = empty( $instance['fadout'] ) ? '2000' : apply_filters( 'widget_fadout', $instance['fadout'] );
			$transition = empty( $instance['transition'] ) ? 'fade' : apply_filters( 'widget_fadout', $instance['transition'] );
			$author_text = empty( $instance['author'] ) ? '' : apply_filters( 'widget_author', $instance['author'] );
			$quote_text = empty( $instance['quotetext'] ) ? '' : apply_filters( 'widget_quotetext', $instance['quotetext'] );
			if ( $quote_text ) {
				do_action( 'testimonial_script' , $transition,$fadin,$fadout );
			}?>
			<div class="testimonials">
				<?php
				if ( $title ) {
					if ( function_exists( 'icl_register_string' ) ) {
						icl_register_string( 'templatic', 'testimonial_title' , $title );
						$title = icl_t( 'templatic', 'testimonial_title' , $title );
					}
					echo wp_kses_post( $args['before_title'] . $title . $args['after_title'] );
				}?>
				<div id="testimonials" class="testimonials_wrap">
					<?php
					$author_text_count = count( $author_text );
					for ( $c = 0; $c < $author_text_count; $c++ ) {
						if ( '' != @$author_text[ $c ] ) {
							?>
							<div class="active">
								<?php
								if ( function_exists( 'icl_register_string' ) ) {
									icl_register_string( 'templatic', 'quote_text' . $c, $quote_text[ $c ] );
									$quote_text[ $c ] = icl_t( 'templatic', 'quote_text' . $c, $quote_text[ $c ] );
									icl_register_string( 'templatic', 'author_text' . $c, $author_text[ $c ] );
									$author_text[ $c ] = icl_t( 'templatic', 'author_text' . $c, $author_text[ $c ] );
								}
								do_action( 'tmpl_testimonial_add_extra_field' , $c ,$instance );
								do_action( 'tmpl_testimonial_quote_text' , $c ,$instance );
								?>
							</div>
							<?php }
					} ?>
					</div>
					<?php
					if ( '' != $link_url && '' != $link_text ) {
						if ( function_exists( 'icl_register_string' ) ) {
							icl_register_string( 'templatic' , $link_text, $link_text );
							$link_text = icl_t( 'templatic' , $link_text, $link_text );
						} else {
							$link_text = sprintf( esc_html__( '%s', 'templatic' ), $link_text );
						}
						?>
						<a href="<?php echo esc_url( $link_url ); ?>" class="testimonial_external_link"> <?php echo wp_kses_post( $link_text ); ?> </a>
						<?php
					}
					do_action( 'show_bullet' );
					?>
				</div>
				<?php
				echo wp_kses_post( $args['after_widget'] );
		}
			/**
			 *
			 * Updates the widget control options for the particular instance of the widget.
			 *
			 * @param array $new_instance     new instance of widget when saved from widget area.
			 * @param array $old_instance 	  old instances of widget.
			 */
		function update( $new_instance, $old_instance ) {
			return $new_instance;
		}
		/**
		 *
		 * Save the widget.
		 *
		 * @param array $instance     instance of widget.
		 */
		function form( $instance ) {
			$instance = wp_parse_args( (array) $instance, array(
															'title' => 'People Talking About Templatic',
															'link_text' => '',
															'link_url'  => '',
															'author' => array( 'Brain Storm', 'Linda', 'Mark Anthony' ),
															'quotetext' => array( 'Templatic offers world class WordPress theme support and unique, highly innovative and professionally useful WordPress themes. So glad to have found you! All the best and many more years of creativity, productivity and success.', 'Templatic has the best WordPress Themes and an exceptional and out-of-this-world customer service. I always receive a response in less than 24 hours, sometimes in less than one hour, this is amazing. I will recommend it to all my friends. Keep up the good work!', 'Templatic is reliable, it has a good support, and very accurate. Beside that, it has a big community of members who contribute.' ),
															'fadin'     => '2700',
															'fadout'    => '1500',
															'transition' => 'fade',
														)
			);
			$title     = strip_tags( $instance['title'] );
			$link_text = strip_tags( $instance['link_text'] );
			$link_url  = strip_tags( $instance['link_url'] );
			$fadin     = ( $instance['fadin'] );
			$fadout    = ( $instance['fadout'] );
			$transition = ( $instance['transition'] );
			$author1   = ( $instance['author'] );
			$quotetext1 = ( $instance['quotetext'] );
			global $author, $quotetext;
			$text_author    = wp_kses_post( $this->get_field_name( 'author' ) );
			$text_quotetext = wp_kses_post( $this->get_field_name( 'quotetext' ) );
			?>
			<p>
				<label for="<?php echo wp_kses_post( $this->get_field_id( 'title' ) ); ?>"> <?php echo esc_attr( TITLE_TEXT );?>:
					<input class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'title' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
				</label>
			</p>
			<p>
				<label for="<?php echo wp_kses_post( $this->get_field_id( 'fadin' ) ); ?>"> <?php echo esc_attr( SET_TIME_OUT_TEXT );?>:
					<input class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'fadin' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'fadin' ) ); ?>" type="text" value="<?php echo esc_attr( $fadin ); ?>" />
				</label>
			</p>
			<p>
				<label for="<?php echo wp_kses_post( $this->get_field_id( 'fadout' ) ); ?>"> <?php echo esc_attr( SET_THE_SPEED_TEXT );?>:
					<input class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'fadout' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'fadout' ) ); ?>" type="text" value="<?php echo esc_attr( $fadout ); ?>" />
				</label>
			</p>
			<p>
				<label for="<?php echo wp_kses_post( $this->get_field_id( 'transition' ) ); ?>">
					<?php echo esc_html__( 'Transition type', 'templatic-admin' );?>
					:
					<select id="<?php echo wp_kses_post( $this->get_field_id( 'transition' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'transition' ) ); ?>" >
						<option value="fade" <?php
						if ( 'fade' == $transition ) {
							echo 'selected=selected';
						}?>>
						<?php echo esc_html__( 'Fade', 'templatic-admin' );?>
					</option>
					<option value="scrollUp" <?php
					if ( 'scrollUp' == $transition ) {
						echo 'selected=selected';
					}?>>
					<?php echo esc_html__( 'Scroll Up', 'templatic-admin' );?>
						</option>
						<option value="scrollRight" <?php
						if ( 'scrollRight' == $transition ) {
							echo 'selected=selected';
						}?>>
						<?php echo esc_html__( 'Scroll Right', 'templatic-admin' );?>
					</option>
					<option value="shuffle" <?php
					if ( 'shuffle' == $transition ) {
						echo 'selected=selected';
					}?>>
					<?php echo esc_html__( 'Shuffle', 'templatic-admin' );?>
				</option>
			</select>
		</label>
	</p>
	<p>
		<label for="<?php echo wp_kses_post( $this->get_field_id( 'quotetext' ) ); ?>">
			<?php echo esc_html__( 'Quote text', 'templatic-admin' );?>
			:
			<textarea class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'quotetext' ) ); ?>" name="<?php echo wp_kses_post( $text_quotetext ); ?>[]" type="text" ><?php echo esc_attr( @$quotetext1[0] ); ?></textarea>
		</label>
	</p>
	<p>
		<label for="<?php echo wp_kses_post( $this->get_field_id( 'author' ) ); ?>">
			<?php echo esc_html__( 'Author name', 'templatic-admin' );?>
			:
			<input class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'author' ) ); ?>" name="<?php echo wp_kses_post( $text_author ); ?>[]" type="text" value="<?php echo esc_attr( @$author1[0] ); ?>" />
		</label>
	</p>
	<?php
	do_action( 'tmpl_after_testimonial_title' , $instance, $this );
	?>
	<div id="tGroup" class="tGroup">
		<?php
		$author1_coumt = count( $author1 );
		for ( $i = 1; $i < $author1_coumt ; $i++ ) {
			if ( '' != $author1[ $i ] ) {
				$j = $i + 1;
				echo '<div class="TextDiv' . intval( $j ) . '">';
				echo '<p>';
				echo '<label>' . esc_attr( QUOTE_TEXT ) . intval( $j );
				echo ': <textarea class="widefat"  name="' . wp_kses_post( $text_quotetext ) . '[]" >' . esc_attr( $quotetext1[ $i ] ) . '</textarea>';
				echo '</label>';
				echo '</p>';
				echo '<p>';
				echo '<label>' . esc_attr( AUTHOR_NAME_TEXT ) . intval( $j );
				echo ': <input type="text" class="widefat"  name="' . esc_attr( $text_author ) . '[]" value="' . esc_attr( $author1[ $i ] ) . '"></label>';
				echo '</label>';
				echo '</p>';
				do_action( 'tmpl_testimonial_field' , $j, $instance, $this );
				echo '</div>';
			}
		}
		?>
		</div>
		<p>
			<?php
			do_action( 'add_testimonial_submit' , $instance, $text_quotetext, $text_author );
			?>
			<a	href="javascript:void(0);" id="removetButton" class="removeButton" type="button" onclick="remove_tfields();">- Remove </a> </p>
			<p>
				<label for="<?php echo wp_kses_post( $this->get_field_id( 'link_text' ) ); ?>">
					<?php echo esc_html__( 'Link Text', 'templatic-admin' );?>
					:
					<input class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'link_text' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'link_text' ) ); ?>" type="text" value="<?php echo esc_attr( $link_text ); ?>" />
				</label>
			</p>
			<p>
				<label for="<?php echo wp_kses_post( $this->get_field_id( 'link_url' ) ); ?>">
					<?php echo esc_html__( 'Link Url', 'templatic-admin' );?>
					:
					<input class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'link_url' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'link_url' ) ); ?>" type="text" value="<?php echo esc_attr( $link_url ); ?>" />
				</label>
			</p>
			<?php
		}
	}
} // End if().
add_action( 'tmpl_testimonial_quote_text', 'add_testimonial_quote_text', 10, 2 );
/**
 *
 * Updates the widget control options for the particular instance of the widget.
 *
 * @param array $c     		new instance of widget when saved from widget area.
 * @param array $instance 	old instances of widget.
 */
function add_testimonial_quote_text( $c, $instance ) {
	$quote_text = empty( $instance['quotetext'] ) ? '' : apply_filters( 'widget_quotetext', $instance['quotetext'] );
	$author_text = empty( $instance['author'] ) ? '' : apply_filters( 'widget_author', $instance['author'] );
	if ( function_exists( 'icl_register_string' ) ) {
		icl_register_string( 'templatic','quote_text' . $c, $quote_text[ $c ] );
		$quote_text[ $c ] = icl_t( 'templatic','quote_text' . $c, $quote_text[ $c ] );
		icl_register_string( 'templatic','author_text' . $c, $author_text[ $c ] );
		$author_text[ $c ] = icl_t( 'templatic','author_text' . $c, $author_text[ $c ] );
	}
	echo  wp_kses_post( $quote_text[ $c ] );
	if ( $author_text[ $c ] ) {	?>
		<cite> - <?php echo wp_kses_post( $author_text[ $c ] ); ?> </cite>
	<?php
	}
}

add_action( 'add_testimonial_submit', 'add_testimonial_submit_button', 10,3 );

/**
 * Templatic Slider widget init
 *
 * @param array $instance    	instances of widget.
 * @param array $text_quotetext	quote's array.
 * @param array $text_author	array of author.
 */
function add_testimonial_submit_button( $instance, $text_quotetext, $text_author ) {
	?>
	<a	href="javascript:void(0);" id="addtButton" class="addButton" type="button" onclick="add_tfields( '<?php echo wp_kses_post( $text_author ); ?>','<?php echo wp_kses_post( $text_quotetext ); ?>' );">+ <?php esc_html_e( 'Add More', 'templatic-admin' );?></a>
	<?php
}

add_action( 'admin_head', 'supreme_add_script_addnew_' );
if ( ! function_exists( 'supreme_add_script_addnew_' ) ) {
	/**
	 * Add widget script on <head>
	 */
	function supreme_add_script_addnew_() {
		global $author,$quotetext;
		?>
		<script type="application/javascript">
			var counter1 = 2;
			function add_tfields(name,ilname)
			{
				var newTextBoxDiv = jQuery(document.createElement( 'div' ) ).attr("class", 'TextDiv' + counter1);
				newTextBoxDiv.html( '<p><label>Quote text '+ counter1+': </label>'+'<textarea  class="widefat" name="'+ilname+'[]" id="textbox' + counter1 + '" value="" ></textarea></p>' );
				newTextBoxDiv.append( '<p><label>Author name '+ counter1+': </label>'+'<input type="text" class="widefat" name="'+name+'[]" id="textbox' + counter1 + '" value="" ></p>' );
				newTextBoxDiv.appendTo(".tGroup");
				counter1++;
			}
			function remove_tfields()
			{
				if (counter1-1==1)
				{
					alert("<?php echo esc_html__( 'One textbox is required.', 'templatic' ); ?>");
					return false;
				}
				counter1--;
				jQuery(".TextDiv" + counter1).remove();
			}
		</script>
		<?php
	}
}

if ( ! class_exists( 'supreme_banner_slider' ) && current_theme_supports( 'supreme_banner_slider' ) ) {
	/**
	 * Home page slider Widget Class.
	 **/
	class supreme_banner_slider extends WP_Widget {
		/**
		 * Set up the widget's unique name, ID, class, description, and other options.
		 */
		function __construct() {
			parent::__construct( 'supreme_banner_slider', esc_html__( 'T &rarr; Homepage Banner', 'templatic-admin' ),
				array(
					'description' => apply_filters( 'tmpl_banner_widget_description', esc_html__( 'Display images from a specific category or a collection of static images. The widget works best in the Homepage Banner area.', 'templatic-admin' ) ),
				)
			);
		}
		/**
		 * Outputs the widget based on the arguments input through the widget controls.
		 *
		 * @param array $slider_args 	agurment of widget area.
		 * @param array $instance 		instances of widget.
		 */
		function widget( $slider_args, $instance ) {
			extract( $slider_args, EXTR_SKIP );
			echo wp_kses_post( $slider_args['before_widget'] );
			/**
			 *  Add flexslider script and style sheet in head tag
			 */
			$custom_banner_temp = empty( $instance['custom_banner_temp'] ) ? '' : $instance['custom_banner_temp'];
			$post_type = empty( $instance['post_type'] ) ? 'post,1' : apply_filters( 'widget_category', $instance['post_type'] );
			$title    = empty( $instance['title'] ) ? '' : apply_filters( 'widget_title', $instance['title'] );
			$sdesc = empty( $instance['sdesc'] ) ? '' : apply_filters( 'widget_sdesc', $instance['sdesc'] );
			$s1 = empty( $instance['s1'] ) ? '' : apply_filters( 'widget_s1', $instance['s1'] );
			$s1_title_link = empty( $instance['s1_title_link'] ) ? '' : apply_filters( 'widget_s1_title_link', $instance['s1_title_link'] );
			$s1_title = empty( $instance['s1_title'] ) ? '' : apply_filters( 'widget_s1_title', $instance['s1_title'] );
			$animation = empty( $instance['animation'] ) ? 'slide' : apply_filters( 'widget_number', $instance['animation'] );
			$number = empty( $instance['number'] ) ? '5' : apply_filters( 'widget_number', $instance['number'] );
			$height = empty( $instance['height'] ) ? '' : apply_filters( 'widget_height', $instance['height'] );
			$autoplay = empty( $instance['autoplay'] ) ? '' : apply_filters( 'widget_autoplay', $instance['autoplay'] );
			$slide_show_speed = empty( $instance['slideshowSpeed'] ) ? '' : apply_filters( 'widget_autoplay', $instance['slideshowSpeed'] );
			$sliding_direction = empty( $instance['sliding_direction'] ) ? 'horizontal' : $instance['sliding_direction'];
			$reverse = empty( $instance['reverse'] ) ? 'false' : $instance['reverse'];
			$animation_speed = empty( $instance['animation_speed'] ) ? '2000' : $instance['animation_speed'];
			$content = empty( $instance['content'] ) ? '' : $instance['content'];
			$content_len = empty( $instance['content_len'] ) ? '60' : $instance['content_len'];
			// Carousel Slider Settings.
			$is_carousel = empty( $instance['is_Carousel'] ) ? '' : $instance['is_Carousel'];
			if ( $is_carousel ) {
				$item_width = empty( $instance['item_width'] ) ? 925 : $instance['item_width'];
				$min_item = empty( $instance['min_item'] ) ? '0' : $instance['min_item'];
				$max_items = empty( $instance['max_items'] ) ? '0' : $instance['max_items'];
				$item_move = empty( $instance['item_move'] ) ? '0' : $instance['item_move'];
			} else {
				$item_width = empty( $instance['item_width'] ) ? 925 : $instance['item_width'];
				$min_item = 0;
				$max_items = 0;
				$item_move = 0;
			}
			if ( $is_carousel ) {
				$width  = apply_filters( 'carousel_slider_width', $item_width, 12 );
				$height = apply_filters( 'carousel_slider_height', 350 );
			} else {
				$width  = apply_filters( 'supreme_slider_width', $item_width, 12 );
				$height = apply_filters( 'supreme_slider_height', 350 );
			}
			$class = 'flexslider' . rand();
			if ( '' == $autoplay ) {
				$autoplay = 'false';
			}
			if ( '' == $slide_show_speed ) {
				$slide_show_speed = '300000';
			}
			if ( '' == $animation_speed ) {
				$animation_speed = '2000';
			}
			if ( 'false' == $autoplay ) {
				$animation_speed = '300000';
			}
			/*get the theme setting option*/
			$supreme2_theme_settings = ( function_exists( 'supreme_prefix' ) ) ? get_option( supreme_prefix() . '_theme_settings' ) : '';

			global $post, $wpdb;
			$counter = 0;
			$postperslide = 1;
			$slider_post = $post_type;
			$slider_post_count = count( $slider_post );
			for ( $k = 0; $k < $slider_post_count; $k++ ) {

				$posttype = explode( ',', $slider_post[ $k ] );
				$post_type = $posttype[0];
				$catid = $posttype[1];
				$cat_name = @$posttype[2];
				$taxonomies = get_object_taxonomies( (object) array(
																'post_type' => $post_type,
																'public'   	=> true,
																'_builtin' 	=> true,
															)
				);

				/* if WPML is activate then get the translated term id */
				if ( is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) ) {
					$language = ICL_LANGUAGE_CODE;
					$current_lang  = icl_object_id( $catid, $taxonomies[0], true, $language );
					$category_id = get_term_by( 'term_id', $current_lang, $taxonomies[0] );

					/* get translated category id */
					$catid = $category_id->term_id;
				}

				if ( 'product' == $post_type ) {
					$taxonomies[0] = $taxonomies[1];
				}
				$term = get_term( $catid, $taxonomies[0] );
				$cat_id[] = $term->term_id;

			}
			if ( is_plugin_active( 'woocommerce/woocommerce.php' ) && 'product' == $post_type ) {
				$taxonomies[0] = $taxonomies[1];
			}
			/* if post type set as post then different query will execute */
			if ( 'post' != $post_type ) {
				$args = array(
						'post_type' => $post_type,
						'posts_per_page' => $number,
						'post_status' => 'publish',
						'tax_query' => array(
											array(
												'taxonomy' => $taxonomies[0],
												'field' => 'id',
												'terms' => $cat_id,
												'operator'  => 'IN',
											),
										),
						);
				$slide = null;
				remove_all_actions( 'posts_where' );

				/* get loaction wise enabled post type */
				$location_post_type = @implode( ',', get_option( 'location_post_type' ) );

				if ( is_plugin_active( 'Tevolution-LocationManager/location-manager.php' ) && false !== strpos( $location_post_type,$post_type . ',' ) ) {
					add_filter( 'posts_where', 'location_multicity_where' );
				}
				add_filter( 'posts_join', 'templatic_posts_where_filter' );
				$slide   = new WP_Query( $args );
			} else {
				if ( count( $cat_id ) > 0 ) {
					$cat_id = rtrim( implode( ',', $cat_id ), ',' );
				}
				$slide = new WP_Query( "cat = $cat_id" );
			}

			if ( is_plugin_active( 'Tevolution-LocationManager/location-manager.php' ) ) {
				remove_filter( 'posts_where', 'location_multicity_where' );
			}
			remove_filter( 'posts_join', 'templatic_posts_where_filter' );
			$slider_image_size = apply_filters( 'slider_image_thumb','large' );
			remove_filter( 'intermediate_image_sizes','unset_slider_thumnail_size' );

			$banner_number = count( $slide->posts );
			$no_bannerclass = ( 1 != $custom_banner_temp  && 0 == $banner_number ) ? 'no_bannerclass' : '';

			/* do not show navigation arrow if only one image is there */
			$show_nav = ( ( 1 != $custom_banner_temp && $banner_number <= 1) || count( $s1 ) <= 1 ) ? 'false' : 'true';

			?>
			<script type="text/javascript">

				jQuery(document).ready(function()
				{
			// store the slider in a local variable
			var $window = jQuery(window), flexslider;

			// tiny helper function to add breakpoints
			function getGridSize() {
				return (window.innerWidth < 600) ? 1 :
				(window.innerWidth < 900) ? 3 : <?php echo intval( $max_items );?>;
			}
			jQuery( '.<?php echo esc_attr( $class );?>' ).flexslider(
			{

				animation: '<?php echo esc_attr( $animation );?>',
				slideshow: <?php echo esc_attr( $autoplay );?>,
				direction: '<?php echo esc_attr( $sliding_direction );?>',
				slideshowSpeed: <?php echo intval( $slide_show_speed );?>,
				<?php if ( 'true' == $autoplay ) : ?> animationSpeed: <?php echo esc_attr( $animation_speed );?>,<?php endif;?>
				animationLoop: true,
				startAt: 0,
				easing: "swing",
				pauseOnHover: true,
				video: true,
				<?php if ( current_theme_supports( 'slider_thumb_image' ) && '' == $custom_banner_temp ) {
					?>
					controlNav: "thumbnails",
					<?php
} else {
					?>
					controlNav: true,
					reverse: <?php echo esc_attr( $reverse );?>,
					<?php
}?>

				directionNav: <?php echo esc_attr( $show_nav ); ?>,

				prevText: '<?php echo wp_kses_post( apply_filters( 'tmpl_homepage_slider_left_aerrow', '<i class="fa fa-chevron-left"></i>' ) ); ?>',
				nextText: '<?php echo wp_kses_post( apply_filters( 'tmpl_homepage_slider_right_aerrow', '<i class="fa fa-chevron-right"></i>' ) );?>',
				<?php if ( 1 == $supreme2_theme_settings['rtlcss'] ) { ?>
				rtl: true,
				<?php } ?>
				touch:true,
				<?php if ( isset( $is_carousel ) && 1 == $is_carousel ) {
					?>
					// Carousel Slider Options
					itemWidth: <?php echo intval( $item_width );?>,                   //{NEW} Integer: Box-model width of individual carousel items, including horizontal borders and padding.
					itemMargin: 40,                  //{NEW} Integer: Margin between carousel items.
					move: <?php echo intval( $item_move );?>,                        //{NEW} Integer: Number of carousel items that should move on animation. If 0, slider will move all visible items.
					minItems: getGridSize(), // use function to pull in initial value
					maxItems: getGridSize(), // use function to pull in initial value
					<?php
} else { ?>
				smoothHeight: true,
				<?php } ?>
				start: function(slider)
				{
				jQuery( 'body' ).removeClass( 'loading' );

			}
			});
			});
			//FlexSlider: Default Settings
			</script>
			<!-- flexslider container start -->
			<?php
			if ( '' != $custom_banner_temp ) {
				$custom_class_name = ' image_slider';
			} else {
				$custom_class_name = ' post_slider';
			}
			if ( $is_carousel ) {
				$is_carousel = ' slider_carousel';
			}
			if ( 'fade' == $animation ) {
				$animation_class = 'fade ';
			} else {
				$animation_class = 'slide ';
			}
			?>
			<div class="flexslider clearfix <?php echo esc_attr( $animation_class . $class . $custom_class_name . $is_carousel ); ?>" >
				<?php
				if ( $title ) {	?>
					<h3 class="widget-title"> <?php echo wp_kses_post( $title ); ?> </h3>
				<?php
				}
				if ( function_exists( 'icl_register_string' ) ) {
					icl_register_string( 'templatic', 'slider_description', $sdesc );
					$sdesc = icl_t( 'templatic', 'slider_description', $sdesc );
				}

				if ( $sdesc ) { ?>
					<p> <?php echo wp_kses_post( $sdesc ); ?> </p>
				<?php
				} ?>

			<div class="slides_container clearfix">
				<?php do_action( 'templ_slider_search_widget', $instance ); // Add action for display additional field. ?>
				<ul class="slides<?php if ( $no_bannerclass ) { echo wp_kses_post( ' ' . $no_bannerclass ); }?>">
					<?php
					if ( isset( $instance['custom_banner_temp'] ) && 1 == $instance['custom_banner_temp'] ) :
						if ( is_array( $s1 ) ) :
							$s1_count = count( $s1 );
							for ( $i = 0; $i < $s1_count; $i++ ) :?>
						<?php
						if ( function_exists( 'icl_register_string' ) ) {
							icl_register_string( 'templatic', $slider_args['widget_id'] . 'silder_image' . $i, $s1[ $i ] );
							$s1[ $i ] = icl_t( 'templatic',$slider_args['widget_id'] . 'silder_image' . $i, $s1[ $i ] );
						}
						if ( function_exists( 'icl_register_string' ) ) {
							icl_register_string( 'templatic', $slider_args['widget_id'] . 'silder_link' . $i, $s1_title_link[ $i ] );
							$s1_title_link[ $i ] = icl_t( 'templatic',$slider_args['widget_id'] . 'silder_link' . $i, $s1_title_link[ $i ] );
						}
						if ( '' != $s1[ $i ] ) : ?>
						<li class="post_img" >
							<?php if ( '' != $s1_title_link[ $i ] ) {?>
								<a href="<?php echo wp_kses_post( $s1_title_link[ $i ] ); ?>" target="_blank">
							<?php } ?>
							<img src="<?php echo esc_url( $s1[ $i ] ); ?>" alt="<?php echo esc_url( $s1[ $i ] ); ?>" />
							<?php
							if ( '' != $s1_title[ $i ] ) :?>
							<h2>
								<?php
								if ( function_exists( 'icl_register_string' ) ) {
									icl_register_string( 'templatic', $slider_args['widget_id'] . 'silder_title' . $i, $s1_title[ $i ] );
									$s1_title[ $i ] = icl_t( 'templatic',$slider_args['widget_id'] . 'silder_title' . $i, $s1_title[ $i ] );
								}
								echo sprintf( __( '%s', 'templatic' ), $s1_title[ $i ] ); ?>
							</h2>
						<?php endif;?>
						<?php if ( '' != $s1_title_link[ $i ] ) { ?>
								</a>
						<?php } ?>
						</li>
					<?php endif;
				endfor; // End forloop().
			endif;
			else :
				if ( $slide->have_posts() ) {
					while ( $slide->have_posts() ) : $slide->the_post();

						global $post;
						$large_image = get_the_image( array(
														'size'		  	=> $slider_image_size,
														'echo' 			=> false,
														'default_image'	=> 'http://placehold.it/60x60',
														)
						);
						if ( '' != get_post_meta( $post->ID,'portfolio_image',true ) ) {
							$post_image = '<a class="image_roll" href=' . esc_url( get_permalink( $post->ID ) ) . ' target="_SELF"><img src=' . get_post_meta( $post->ID, 'portfolio_image', true ) . ' title=' . esc_attr( $post->post_title ) . ' alt=' . esc_attr( $post->post_title ) . '/></a>';
							$flag = 0;
						} else {
							if ( $is_carousel ) {
								$post_image = get_the_image( array(
																'size' 			=> $slider_image_size,
																'echo'			=> false,
																'default_image'	=> 'http://placehold.it/60x60',
																)
								);
							} else {
								$post_image = get_the_image( array(
																'size' 				=> $slider_image_size,
																'echo'				=> false,
																'width'        		=> $width,
																'height'	  	    => $height,
																'default_image'		=> 'http://placehold.it/880x440',
																)
								);
								$flag = 1;
							}
						}
						$post_images = $post_image ;
						$thumb_image = '';
						if ( '0' == $counter || 0 == $counter % $postperslide ) {
							?>
							<li <?php
							if ( current_theme_supports( 'slider_thumb_image' ) && '' == $custom_banner_temp ) {
								$thumb_image_size = apply_filters( 'slider_thumb_image', 'thumbnail' );?> data-thumb = "<?php get_the_image( array(
										'size' 			=> $thumb_image_size,
										'echo' 			=> 'false',
										'default_image'	=> 'http://placehold.it/60x60',
										)
								);
								?>"
							<?php } ?> >
						<?php } ?>
							<!-- post start -->
							<div class="post_list">
								<?php if ( 'image' == get_post_format( $post->ID ) ) : ?>
									<div class="post_img">
										<?php
										if ( '' != $large_image ) {
											echo wp_kses_post( $large_image );
										} ?>
									</div>
								<?php else : ?>
									<div <?php if ( 1 == $flag ) {	?> class="post_img" style="width:<?php echo intval( $width ) . 'px'; ?>"<?php } ?>>
										<?php
										if ( '' != $post_images ) {
											echo wp_kses_post( $post_images );
										} ?>
									</div>
								<?php endif;
if ( 1 == $flag ) {
									?>
									<div class="slider-post">
										<?php
										if ( current_theme_supports( 'slider_thumb_image' ) ) {
											if ( 'h' == get_post_meta( $post->ID, 'featured_h', true ) ) {
												?>
												<span class="featured_tag">
													<?php echo esc_html__( 'Featured', 'templatic' ); ?>
												</span>
												<?php				}
										}
											?>
											<h2> <a href="<?php the_permalink() ?>" rel="bookmark">
												<?php the_title(); ?>
											</a> </h2>
											<?php
											do_action( 'slider_extra_content', get_the_ID() ); // Do action for display the extra content.
											if ( current_theme_supports( 'slider-post-content' ) && '' != $content ) {
												global $legnth_content;
												$legnth_content = $content_len;
												add_filter( 'excerpt_length', 'slider_excerpt_length', 20 );
												if ( is_plugin_active( 'woocommerce/woocommerce.php' ) && 'product' == $post_type ) {
													echo wp_kses_post( '<div class="slider_post_excerpt"> ' . string_limit_words( get_the_excerpt(), $legnth_content ) . '</div>' );
												} else {
													echo wp_kses_post( '<div class="slider_post_excerpt">' . string_limit_words( get_the_excerpt(), $legnth_content ) . '</div>' );
												}
											}
						?>
						</div>
						<?php
} else { ?>
							<h2> <a href="<?php the_permalink() ?>" rel="bookmark">
								<?php the_title(); ?>
							</a> </h2>
						<?php
} // End if(). ?>
						</div>
						<!-- post end -->
						<?php
						$counter++;
						if ( 0 == $counter % $postperslide ) {
							echo '</li>';
						}
					endwhile;
				} else {
					esc_html_e( 'No results found.', 'templatic' );
				} // End if().
				wp_reset_query();
			endif;?>
			</ul>
			</div>
			</div>
			<!-- flexslider container end -->
			<?php
			echo wp_kses_post( $slider_args['after_widget'] );
		}
		/**
		 *
		 * Updates the widget control options for the particular instance of the widget.
		 *
		 * @param array $new_instance     new instance of widget when saved from widget area.
		 * @param array $old_instance 	  old instances of widget.
		 */
		function update( $new_instance, $old_instance ) {
			return $new_instance;
		}
		/**
		 *
		 * Save the widget.
		 *
		 * @param array $instance     instance of widget.
		 */
		function form( $instance ) {

			$instance = wp_parse_args( (array) $instance, array(
															'search'            => '',
															'search_post_type'  => '',
															'location'          => '',
															'distance'          => '',
															'radius'            => '',
															'post_type'         => '',
															'number'            => '',
															'animation'         => 'fade',
															'slideshowSpeed'    => '4700',
															'animation_speed'   => '800',
															'sliding_direction' => '',
															'reverse'           => 'true',
															'item_width'        => '',
															'is_Carousel_temp'  => '',
															'min_item'          => '',
															'max_items'         => '',
															'item_move'         => '',
															'custom_banner_temp' => '',
															's1'                => array(
																					get_template_directory_uri() . '/images/slide1.jpg',
																					get_template_directory_uri() . '/images/slide2.jpg',
																					get_template_directory_uri() . '/images/slide1.jpg',
																					get_template_directory_uri() . '/images/slide2.jpg',
																					),
															's1_title'          => array(
																					esc_html__( 'Slider Title 1', 'templatic-admin' ),
																					esc_html__( 'Slider Title 2', 'templatic-admin' ),
																					esc_html__( 'Slider Title 3', 'templatic-admin' ),
																					esc_html__( 'Slider Title 4', 'templatic-admin' ),
																					),
															's1_title_link' 	=> array( '#', '#', '#', '#' ),
															'postperslide'      => '',
															'content_len'       => '',
															'autoplay'	        => 'true',
															)
			);

			$title              = strip_tags( @$instance['title'] );
			$sdesc              = strip_tags( @$instance['sdesc'] );
			$custom_banner_temp = (strip_tags( $instance['custom_banner_temp'] )) ? strip_tags( $instance['custom_banner_temp'] ) : '';
			$post_type = $instance['post_type'];
			$number             = strip_tags( $instance['number'] );
			$content            = empty( $instance['content'] )? '' : strip_tags( $instance['content'] );
			$content_len = empty( $instance['content_len'] )? '60' : strip_tags( $instance['content_len'] );
			// Slider Basic Settings.
			$autoplay = empty( $instance['autoplay'] )? '' : strip_tags( $instance['autoplay'] );
			$animation         = strip_tags( $instance['animation'] );
			$slide_show_speed    = strip_tags( $instance['slideshowSpeed'] );
			$sliding_direction = strip_tags( $instance['sliding_direction'] );
			$reverse           = strip_tags( $instance['reverse'] );
			$animation_speed   = strip_tags( $instance['animation_speed'] );

			// Carousel Slider Settings.
			$is_carousel       = empty( $instance['is_Carousel'] )? '' : strip_tags( $instance['is_Carousel'] );
			$item_width       = strip_tags( $instance['item_width'] );

			$min_item         = strip_tags( $instance['min_item'] );
			$max_items        = strip_tags( $instance['max_items'] );
			$item_move        = strip_tags( $instance['item_move'] );
			$is_carousel_temp = strip_tags( $instance['is_Carousel_temp'] );
			$item_width       = strip_tags( $instance['item_width'] );

			$min_item         = strip_tags( $instance['min_item'] );
			$max_items        = strip_tags( $instance['max_items'] );
			$item_move        = strip_tags( $instance['item_move'] );
			$postperslide     = empty( $instance['postperslide'] )? '' : strip_tags( $instance['postperslide'] );

			$s1            = ( $instance['s1'] );
			$s1_title      = ( $instance['s1_title'] );
			$s1_title_link = ( $instance['s1_title_link'] ); ?>
			<script type="text/javascript">
				function select_custom_image(id,div_custom,div_def) {
					var checked=id.checked;
					if (checked) {
						jQuery( '#'+div_def).slideToggle( 'slow' );
						jQuery( '#'+div_custom).slideToggle( 'slow' );
					} else {
						jQuery( '#'+div_custom).hide();
						jQuery( '#'+div_def).show();
					}
				}
				function select_is_Carousel(id,div_def) {
					var checked=id.checked;
					jQuery( '#'+div_def).slideToggle( 'slow' );
				}
			</script>
			<p>
				<label for="<?php echo wp_kses_post( $this->get_field_id( 'title' ) ); ?>">
					<?php echo esc_html__( 'Slider Title', 'templatic-admin' ); ?>
					:
					<input class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'title' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
				</label>
			</p>
			<p>
				<label for="<?php echo wp_kses_post( $this->get_field_id( 'sdesc' ) ); ?>">
					<?php echo esc_html__( 'Slider Description', 'templatic-admin' ); ?>
					:
					<textarea class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'sdesc' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'sdesc' ) ); ?>" type="text" ><?php echo esc_attr( $sdesc ); ?></textarea>
				</label>
			</p>
			<p>
				<label for="<?php echo wp_kses_post( $this->get_field_id( 'animation' ) ); ?>">
					<?php echo esc_html__( 'Animation', 'templatic-admin' ); ?>
					:
					<select class="widefat" name="<?php echo wp_kses_post( $this->get_field_name( 'animation' ) ); ?>" id="<?php echo wp_kses_post( $this->get_field_id( 'animation' ) ); ?>">
						<option <?php
						if ( 'fade' == esc_attr( $animation ) ) {?>
							selected="selected"
						<?php }?> value="fade">
						<?php echo esc_html__( 'Fade', 'templatic-admin' );?>
					</option>
					<option <?php
					if ( 'slide' == esc_attr( $animation ) ) {?>
						selected="selected"
					<?php }?> value="slide">
					<?php echo esc_html__( 'Slide', 'templatic-admin' );?>
				</option>
			</select>
		</label>
		</p>
		<p>
			<label for="<?php echo wp_kses_post( $this->get_field_id( 'autoplay' ) ); ?>">
				<?php echo esc_html__( 'Slide show', 'templatic-admin' ); ?>
				:
				<select class="widefat" name="<?php echo wp_kses_post( $this->get_field_name( 'autoplay' ) ); ?>" id="<?php echo wp_kses_post( $this->get_field_id( 'autoplay' ) ); ?>">
					<option <?php
					if ( 'true' == esc_attr( $autoplay ) ) {?>
						selected="selected"
					<?php }?> value="true">
					<?php echo esc_html__( 'Yes', 'templatic-admin' );?>
				</option>
				<option <?php
				if ( 'false' == esc_attr( $autoplay ) ) {?>
					selected="selected"
				<?php }?> value="false">
				<?php echo esc_html__( 'No', 'templatic-admin' );?>
			</option>
		</select>
		</label>
		</p>
		<p>
			<label for="<?php echo wp_kses_post( $this->get_field_id( 'sliding_direction' ) ); ?>">
				<?php echo esc_html__( 'Sliding Direction', 'templatic-admin' ); ?>
				:
				<select class="widefat" name="<?php echo wp_kses_post( $this->get_field_name( 'sliding_direction' ) ); ?>" id="<?php echo wp_kses_post( $this->get_field_id( 'sliding_direction' ) ); ?>">
					<option <?php
					if ( 'horizontal' == esc_attr( $sliding_direction ) ) {?>
						selected="selected"
					<?php }?> value="horizontal">
					<?php echo esc_html__( 'Horizontal', 'templatic-admin' );?>
				</option>
				<option <?php
				if ( 'vertical' == esc_attr( $sliding_direction ) ) {?>
					selected="selected"
				<?php }?> value="vertical">
				<?php echo esc_html__( 'Vertical', 'templatic-admin' );?>
			</option>
		</select>
		</label>
		</p>
		<p>
			<label for="<?php echo wp_kses_post( $this->get_field_id( 'reverse' ) ); ?>">
				<?php echo esc_html__( 'Reverse Animation Direction', 'templatic-admin' ); ?>
				:
				<select class="widefat" name="<?php echo wp_kses_post( $this->get_field_name( 'reverse' ) ); ?>" id="<?php echo wp_kses_post( $this->get_field_id( 'reverse' ) ); ?>">
					<option <?php
					if ( 'false' == esc_attr( $reverse ) ) {?>
						selected="selected"
					<?php }?> value="false">
					<?php echo esc_html__( 'False', 'templatic-admin' );?>
				</option>
				<option <?php
				if ( 'true' == esc_attr( $reverse ) ) {?>
					selected="selected"
				<?php }?> value="true">
				<?php echo esc_html__( 'True', 'templatic-admin' );?>
			</option>
		</select>
		</label>
		</p>
		<p>
			<label for="<?php echo wp_kses_post( $this->get_field_id( 'slideshowSpeed' ) ); ?>">
				<?php echo esc_html__( 'Slide Show Speed', 'templatic-admin' ); ?>
				:
				<input class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'slideshowSpeed' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'slideshowSpeed' ) ); ?>" type="text" value="<?php echo esc_attr( $slide_show_speed ); ?>" />
			</label>
		</p>
		<p>
			<label for="<?php echo wp_kses_post( $this->get_field_id( 'animation_speed' ) ); ?>">
				<?php echo esc_html__( 'Animation Speed', 'templatic-admin' ); ?>
				:
				<input class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'animation_speed' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'animation_speed' ) ); ?>" type="text" value="<?php echo esc_attr( $animation_speed ); ?>" />
			</label>
		</p>
		<!--is_Carousel -->
		<?php if ( current_theme_supports( 'show_carousel_slider' ) ) { ?>
		<p> <br/>
			<label for="<?php echo wp_kses_post( $this->get_field_id( 'is_Carousel' ) ); ?>">
				<input id="<?php echo wp_kses_post( $this->get_field_id( 'is_Carousel' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'is_Carousel' ) ); ?>" type="checkbox" value="1" <?php
				if ( '1' == $is_carousel ) {
					?>checked=checked<?php
				}
				?>style="width:10px;" onclick="select_is_Carousel(this,'<?php echo wp_kses_post( $this->get_field_id( 'home_slide_carousel' ) ); ?>' );"/>
				<?php echo esc_html__( '<b>Settings for Carousel slider option?</b>', 'templatic-admin' );?>
			</label>
		</p>
		<div id="<?php echo wp_kses_post( $this->get_field_id( 'home_slide_carousel' ) ); ?>" style="<?php if ( '1' == $is_carousel ) { ?>display:block;<?php } else { ?>display:none;<?php }?>">
			<p>
				<label for="<?php echo wp_kses_post( $this->get_field_id( 'item_width' ) ); ?>">
					<?php echo esc_html__( 'Item Width <br/><small>(Box-model width of individual items, including horizontal borders and padding.)</small>', 'templatic-admin' ); ?>
					<input class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'item_width' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'item_width' ) ); ?>" type="text" value="<?php echo esc_attr( $item_width ); ?>" />
				</label>
			</p>
			<p>
				<label for="<?php echo wp_kses_post( $this->get_field_id( 'min_item' ) ); ?>">
					<?php echo esc_html__( 'Min Item <br/><small>(Minimum number of items that should be visible. Items will resize fluidly when below this.)</small>', 'templatic-admin' ); ?>
					<input class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'min_item' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'min_item' ) ); ?>" type="text" value="<?php echo esc_attr( $min_item ); ?>" />
				</label>
			</p>
			<p>
				<label for="<?php echo wp_kses_post( $this->get_field_id( 'max_items' ) ); ?>">
					<?php echo esc_html__( 'Max Item <br/><small>(Maximum number of items that should be visible. Items will resize fluidly when above this limit.)</small>', 'templatic-admin' ); ?>
					<input class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'max_items' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'max_items' ) ); ?>" type="text" value="<?php echo esc_attr( $max_items ); ?>" />
				</label>
			</p>
			<p>
				<label for="<?php echo wp_kses_post( $this->get_field_id( 'item_move' ) ); ?>">
					<?php echo esc_html__( 'Items Move <br/><small>(Number of items that should move on animation. If 0, slider will move all visible items.)</small>', 'templatic-admin' ); ?>
					<input class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'item_move' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'item_move' ) ); ?>" type="text" value="<?php echo esc_attr( $item_move ); ?>" />
				</label>
			</p>
			<?php
			if ( current_theme_supports( 'postperslide' ) ) : ?>
			<p>
				<label for="<?php echo wp_kses_post( $this->get_field_id( 'postperslide' ) ); ?>">
					<?php echo esc_html__( 'Posts Per Slide <br/><small>Number of items you want to show in one slide. this option is work with LI tag, it will show all images in one LI tag. </small>', 'templatic-admin' ); ?>
					<input class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'postperslide' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'postperslide' ) ); ?>" type="text" value="<?php echo esc_attr( $postperslide ); ?>" />
				</label>
			</p>
		<?php endif; ?>
		</div>
		<?php } // End if(). ?>
		<!-- Finish is_Carousel -->
		<?php
		if ( current_theme_supports( 'slider-post-content' ) ) { ?>
		<p>
			<label for="<?php echo wp_kses_post( $this->get_field_id( 'content' ) ); ?>">
				<input <?php
				if ( $content ) {
					echo 'checked=checked';
				}?> id="<?php echo wp_kses_post( $this->get_field_id( 'content' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'content' ) ); ?>" type="checkbox" value="1"/>
				<b>
					<?php echo esc_html__( 'Enable Post Excerpt In Slider', 'templatic-admin' );?>
				</b> </label>
			</p>
			<?php
		}

		if ( current_theme_supports( 'slider-post-inslider' ) ) { ?>
		<div id="<?php echo wp_kses_post( $this->get_field_id( 'home_slide_default_temp' ) ); ?>" style="<?php
		if ( '1' == $custom_banner_temp ) {
			?>display:none;<?php
		} else {
			?>display:block;
		<?php }?>">

			<p>
				<label for="<?php echo wp_kses_post( $this->get_field_id( 'post_type' ) );?>" >
					<?php echo esc_html__( 'Select Category', 'templatic-admin' );?>
					<select id="<?php echo wp_kses_post( $this->get_field_id( 'post_type' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'post_type' ) ); ?>[]" class="widefat"  multiple="multiple" size="8">
						<?php $taxonomies = get_taxonomies( array(
																'public' => true,
															), 'objects'
						);
						foreach ( $taxonomies as $taxonomy ) {
							$query_label = '';
							if ( ! empty( $taxonomy->query_var ) ) {
								$query_label = $taxonomy->query_var;
							} else {
								$query_label = $taxonomy->name;
							}

							if ( 'Tags' != $taxonomy->labels->name && 'Format' != $taxonomy->labels->name   && ! strstr( $taxonomy->labels->name, 'tag' ) && ! strstr( $taxonomy->labels->name, 'Tags' ) && ! strstr( $taxonomy->labels->name, 'format' ) && ! strstr( $taxonomy->labels->name, 'Shipping Classes' ) && ! strstr( $taxonomy->labels->name, 'Order statuses' ) && ! strstr( $taxonomy->labels->name, 'genre' ) && ! strstr( $taxonomy->labels->name, 'platform' ) && ! strstr( $taxonomy->labels->name, 'colour' ) && ! strstr( $taxonomy->labels->name, 'size' ) ) :
								?>
							<optgroup label="<?php echo esc_attr( $taxonomy->object_type[0] ) . '-' . esc_attr( $taxonomy->labels->name ); ?>">
								<?php
								$terms = get_terms( $taxonomy->name, 'orderby=name&hide_empty=0' );
								foreach ( $terms as $term ) {
									$term_value = esc_attr( $taxonomy->object_type[0] ) . ',' . $term->term_id . ',' . $query_label;
									?>
									<option style="margin-left: 8px; padding-right:10px;" value="<?php echo wp_kses_post( $term_value ); ?>" <?php if ( isset( $term_value ) && ! empty( $post_type ) && in_array( trim( $term_value ), $post_type ) ) {
										 echo 'selected'; }?>>
										 	<?php echo '-' . esc_attr( $term->name ); ?></option>
									<?php } ?>
								</optgroup>
								<?php
								endif;
						} // End foreach(). ?>
						</select>
					</label>
					<small>
						<?php echo esc_html__( 'Select the categories you want to display inside the slider. All categories must be from a single post type.', 'templatic-admin' );?>
					</small> </p>
					<p>
						<label for="<?php echo wp_kses_post( $this->get_field_id( 'number' ) ); ?>">
							<?php echo esc_html__( 'Number of posts:', 'templatic-admin' );?>
							<input class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'number' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'number' ) ); ?>" type="text" value="<?php echo esc_attr( $number ); ?>" />
						</label>
					</p>
					<p>
						<label for="<?php echo wp_kses_post( $this->get_field_id( 'content_len' ) ); ?>">
							<?php echo esc_html__( 'Excerpt Length:', 'templatic-admin' );?>
							<input class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'content_len' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'content_len' ) ); ?>" type="text" value="<?php echo esc_attr( $content_len ); ?>" />
						</label>
					</p>

				</div>
				<?php } // End if(). ?>
				<p> <br/>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'custom_banner_temp' ) ); ?>">
						<input id="<?php echo wp_kses_post( $this->get_field_id( 'custom_banner_temp' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'custom_banner_temp' ) ); ?>" type="checkbox" value="1" <?php
						if ( '1' == $custom_banner_temp ) {
							?>  checked="checked"  <?php
						} ?>  onclick="select_custom_image(this,'<?php echo wp_kses_post( $this->get_field_id( 'home_slide_custom_temp' ) ); ?>','<?php echo wp_kses_post( $this->get_field_id( 'home_slide_default_temp' ) ); ?>' );" />
						&nbsp;
						<?php echo '<b>'. esc_html__( 'Use Custom Images?', 'templatic-admin' ).'</b>';?>
						<br/>
					</label>
					<br/>
				</p>
				<div id="<?php echo wp_kses_post( $this->get_field_id( 'home_slide_custom_temp' ) ); ?>" class="<?php echo 'home_slide_custom_temp'; ?>" style="<?php
				if ( '1' == $custom_banner_temp ) { ?>
					display:block;
				<?php } else { ?>
					display:none;
				<?php }?>">
					<div id="TextBoxesGroup" class="TextBoxesGroup">
						<div id="TextBoxDiv1" class="TextBoxDiv1">
							<?php do_action( 'tmpl_before_slider_title', $instance, $this );	?>
							<p>
								<?php global $textbox_title;
								$textbox_title = wp_kses_post( $this->get_field_name( 's1_title' ) );
								?>
								<label for="<?php echo wp_kses_post( $this->get_field_id( 's1_title' ) ); ?>">
									<?php echo esc_html__( 'Banner Slider Title 1', 'templatic-admin' );?>
									<input type="text" class="widefat"  name="<?php echo wp_kses_post( $textbox_title ); ?>[]" value="<?php echo esc_attr( $s1_title[0] ); ?>">
								</label>
							</p>
							<?php
							do_action( 'tmpl_after_slider_title', $instance, $this );
							?>
							<p>
								<?php global $textbox_title_link;
								$textbox_title_link = wp_kses_post( $this->get_field_name( 's1_title_link' ) );
								?>
								<label for="<?php echo wp_kses_post( $this->get_field_id( 's1_title_link' ) ); ?>">
									<?php echo esc_html__( 'Banner Slider Title Link 1', 'templatic-admin' );?>
									<input type="text" class="widefat"  name="<?php echo wp_kses_post( $textbox_title_link ); ?>[]" value="<?php echo esc_attr( $s1_title_link[0] ); ?>">
								</label>
							</p>
							<p>
								<?php global $textbox_name;
								$textbox_name = wp_kses_post( $this->get_field_name( 's1' ) );
								?>
								<label for="<?php echo wp_kses_post( $this->get_field_id( 's1' ) ); ?>">
									<?php echo esc_html__( 'Banner Slider Image 1 full URL <small>(ex.http://templatic.com/images/banner1.png )</small>  :', 'templatic-admin' );?>
									<input type="text" class="widefat"  name="<?php echo wp_kses_post( $textbox_name ); ?>[]" value="<?php echo esc_attr( $s1[0] ); ?>">
								</label>
							</p>
						</div>
						<?php
						$s1_count = count( $s1 );
						for ( $i = 1; $i < $s1_count; $i++ ) {
							if ( '' != $s1[ $i ] ) {
								$j = $i + 1;
								echo '<div  class="TextBoxDiv' . intval( $j ) . '">';
								echo '<p>';
								echo '<label>' . esc_html__( 'Banner Slider Title', 'templatic-admin' ) . intval( $j );
								echo ' <input type="text" class="widefat"  name="' . wp_kses_post( $textbox_title ) . '[]" value="' . esc_attr( $s1_title[ $i ] ) . '">';
								echo '</label>';
								echo '</p>';
								do_action( 'tmpl_image_link', $j, $instance, $this );
								echo '<p>';
								echo '<label>' . esc_html__( 'Banner Slider Title Link', 'templatic-admin' ) . intval( $j );
								echo ' <input type="text" class="widefat"  name="' . wp_kses_post( $textbox_title_link ) . '[]" value="' . esc_attr( $s1_title_link[ $i ] ) . '">';
								echo '</label>';
								echo '</p>';
								echo '<p>';
								echo '<label>' . sprintf( esc_html__( 'Banner Slider Image %s full URL', 'templatic-admin' ), intval( $j ) );
								echo ' <input type="text" class="widefat"  name="' . wp_kses_post( $textbox_name ) . '[]" value="' . esc_attr( $s1[ $i ] ) . '">';
								echo '</label>';
								echo '</p>';
								echo '</div>';
							}
						}	?>
					</div>
					<a href="javascript:void(0);" id="addButton" class="addButton" onclick="add_textbox( '<?php echo wp_kses_post( $textbox_name );?>','<?php echo wp_kses_post( $textbox_title_link ); ?>','<?php echo wp_kses_post( $textbox_title );?>' );"> <?php echo esc_html__( '+Add more', 'templatic-admin' ); ?> </a> &nbsp; | &nbsp;
					<a	href="javascript:void(0);" id="removeButton" class="removeButton" onclick="remove_textbox();"><?php echo esc_html__( '-Remove', 'templatic-admin' ); ?> </a>
				</div>
				<?php
		}
	} // End if().

	add_action( 'admin_footer', 'supreme_multitext_box' );
	add_action( 'customize_controls_enqueue_scripts', 'supreme_multitext_box', 999 );
	/**
	 * Templatic Slider widget init.
	 */
	function supreme_multitext_box() {
		global $textbox_name, $textbox_title_link, $textbox_title;
		?>
		<script type="application/javascript">
			var banner_counter = 2;
			function add_textbox(name,title_link,title)
			{
				var BannerNewTextBoxDiv = jQuery(document.createElement( 'div' ) ).attr("class", 'TextBoxDiv' + banner_counter);
				BannerNewTextBoxDiv.html( '<p><label>Banner Slider Title '+ banner_counter + ' </label>'+'<input type="text" class="widefat" name="'+title+'[]" id="textbox' + banner_counter + '" value="" ></p><p><label>Banner Slider Title Link '+ banner_counter + ' </label>'+'<input type="text" class="widefat" name="'+title_link+'[]" id="textbox' + banner_counter + '" value="" ></p><p><label>Banner Slider Image '+ banner_counter + ' full URL : </label>'+'<input type="text" class="widefat" name="'+name+'[]" id="textbox' + banner_counter + '" value="" ></p>' );
				BannerNewTextBoxDiv.appendTo(".TextBoxesGroup");
				banner_counter++;
			}
			function remove_textbox()
			{
				if (banner_counter-1==1)
				{
					alert("<?php echo esc_html__( 'One textbox required.', 'templatic-admin' ); ?>");
					return false;
				}
				banner_counter--;
				jQuery(".TextBoxDiv" + banner_counter).remove();
			}
		</script>
		<?php
	}
} // End if().

if ( ! class_exists( 'supreme_contact_widget' ) ) {
	/**
	 * Contact Us widget - specially to display contact form in sidebar
	 **/
	class supreme_contact_widget extends WP_Widget {
		/**
		 * Set up the widget's unique name, ID, class, description, and other options.
		 */
		function __construct() {

			$widget_ops = array(
							'classname'  	=> 'contact_us',
							'description'	=> apply_filters( 'templ_contact_widget_desc_filter', esc_html__( 'A simple contact form visitors can use to get in touch with you. Works best in sidebar areas.', 'templatic-admin' ) ),
						);
			parent::__construct( 'supreme_contact_widget', apply_filters( 'templ_contact_widget_title_filter', esc_html__( 'T &rarr; Contact Us', 'templatic-admin' ) ), $widget_ops );
		}
		/**
		 * Outputs the widget based on the arguments input through the widget controls.
		 *
		 * @param array $args 		agurment of widget area.
		 * @param array $instance 	instances of widget.
		 */
		function widget( $args, $instance ) {

			extract( $args, EXTR_SKIP );
			$title = empty( $instance['title'] ) ? '' : apply_filters( 'widget_title', $instance['title'] );

			echo $args['before_widget'];
			$tmpdata = get_option( 'templatic_settings' );

			?>
			<div class="widget contact_widget" id="contact_widget">
				<?php
				if ( $title ) {
					echo wp_kses_post( $args['before_title'] . $title . $args['after_title'] );
				}

				$captcha = 0;

				/* Removed option but ept for already there is an option.Will remove after some time. */
				$contactus_captcha = empty( $instance['contactus_captcha'] ) ? '' : apply_filters( 'widget_desc1', @$instance['contactus_captcha'] );

				if ( empty( $tmpdata['user_verification_page'] ) ) {
					$tmpdata['user_verification_page'] = array();
				}
				if ( in_array( 'registration', $tmpdata['user_verification_page'] ) || '' != $contactus_captcha ) {
					$captcha = 1;
				} else {
					$captcha = 0;
				}

				if ( isset( $_POST['contact_widget'] ) && '' != $_POST['contact_widget'] ) {
					if ( isset( $_POST['your-email'] ) && '' != $_POST['your-email'] ) {
						/**
						 * Send mail to admin submitted by user
						 *
						 * @param array $data 		data submitted by user.
						 */
						function widget_send_contact_email( $data ) {
							$to_email_name = get_option( 'blogname' );
							$to_email = get_option( 'admin_email' );
							$subject = $data['your-subject'];
							$message = '';
							$tmpdata = get_option( 'templatic_settings' );
							if ( '' != $tmpdata['contact_us_email_content'] ) {
								$message = stripslashes( $tmpdata['contact_us_email_content'] );
								$search_array = array( '[#to_name#]', '[#user_name#]', '[#user_email#]', '[#user_message#]' );
								$replace_array_admin = array( $to_email_name, $data['your-name'], $data['your-email'], nl2br( $data['your-message'] ) );
								$message = str_replace( $search_array, $replace_array_admin, $message );
							} else {
								$message .= '<p>' . esc_html__( 'Dear', 'templatic' ) . ' ' . $to_email_name . ',</p>';
								$message .= '<p>' . esc_html__( 'You have an inquiry message. Here are the details', 'templatic' ) . ',</p>';
								$message .= '<p>' . esc_html__( 'Name', 'templatic' ) . ': ' . $data['your-name'] . '</p>';
								$message .= '<p>' . esc_html__( 'Email', 'templatic' ) . ': ' . $data['your-email'] . '</p>';
								$message .= '<p>' . esc_html__( 'Message', 'templatic' ) . ': ' . nl2br( $data['your-message'] ) . '</p>';

							}

							/* Mail it */

							templ_send_email( $data['your-email'], $data['your-name'], $to_email, $to_email_name, $subject, $message );

							if ( strstr( $_REQUEST['request_url'], '?' ) ) {
								if ( strstr( $_REQUEST['request_url'], '?capt' ) ) {
									$url = explode( '?', $_REQUEST['request_url'] );
									$url = $url[0] . '?message=success';
								} else {
									$url =  $_REQUEST['request_url'] . '&message=success';
								}
							} else {
								$url = $_REQUEST['request_url'] . '?message=success';
							}
							echo '<script type="text/javascript">location.href="' . esc_url( $url ) . '#contact_widget";</script>';
						}
						if ( 1 == $captcha ) {

							/*fetch captcha private key*/
							$privatekey = $tmpdata['secret'];
							/*get the response from captcha that the entered captcha is valid or not*/
							$response = wp_remote_get( 'https://www.google.com/recaptcha/api/siteverify?secret=' . $privatekey . '&response=' . $_REQUEST['g-recaptcha-response'] . '&remoteip=' . getenv( 'REMOTE_ADDR' ) );

							/* show response error */
							if ( is_wp_error( $response ) ) {
								$error_message = $response->get_error_message();
								echo wp_kses_post( $error_message ) . ' <br/>';
								esc_html_e( 'Please contact your host provider.', 'templatic' );
							}

							/* Decode the captcha response. */
							$responde_encode = json_decode( $response['body'] );
							/*check the response is valid or not*/
							if ( ! $responde_encode->success ) {
								if ( strstr( $_REQUEST['request_url'], '?' ) ) {
									if ( strstr( $_REQUEST['request_url'], '?message' ) ) {
										$url = explode( '?', $_REQUEST['request_url'] );
										$url = $url[0] . '?capt=captch';
									} else {
										$url = $_REQUEST['request_url'] . '&capt=captch';
									}
								} else {
									$url = $_REQUEST['request_url'] . '?capt=captch';
								}
								echo '<script type="text/javascript">location.href="' . esc_url( $url ) . '#contact_widget";</script>';
							} else {
								$data = $_POST;
								widget_send_contact_email( $data );
							}
						} else {
							$data = $_POST;
							widget_send_contact_email( $data );
						} // End if().
					} else {
						if ( strstr( $_REQUEST['request_url'], '?' ) ) {
							$url = $_REQUEST['request_url'] . '&err=empty';
						} else {
							$url = $_REQUEST['request_url'] . '?err=empty';
						}
						echo '<script type="text/javascript">location.href="' . esc_url( $url ) . '#contact_widget";</script>';
					} // End if().
				} // End if().
				?>
				<script type="text/javascript">
					var $cwidget = jQuery.noConflict();
					$cwidget(document).ready(function() {
//global vars
var contact_widget_frm = $cwidget("#contact_widget_frm");
var your_name = $cwidget("#widget_your-name");
var your_email = $cwidget("#widget_your-email");
var your_subject = $cwidget("#widget_your-subject");
var your_message = $cwidget("#widget_your-message");
var your_name_Info = $cwidget("#widget_your_name_Info");
var your_emailInfo = $cwidget("#widget_your_emailInfo");
var your_subjectInfo = $cwidget("#widget_your_subjectInfo");
var your_messageInfo = $cwidget("#widget_your_messageInfo");
var recaptcha_response_field = $cwidget("#recaptcha_response_field");
var recaptcha_response_fieldInfo = $cwidget("#recaptcha_response_fieldInfo");
//On blur
your_name.blur(validate_widget_your_name);
your_email.blur(validate_widget_your_email);
your_subject.blur(validate_widget_your_subject);
your_message.blur(validate_widget_your_message);
//On key press
your_name.keyup(validate_widget_your_name);
your_email.keyup(validate_widget_your_email);
your_subject.keyup(validate_widget_your_subject);
your_message.keyup(validate_widget_your_message);
//On Submitting
contact_widget_frm.submit(function()
{
	if (validate_widget_your_name() & validate_widget_your_email() & validate_widget_your_subject() & validate_widget_your_message()
		<?php
		if ( 1 == $captcha ) {
			{
				?>
				& validate_widget_recaptcha()
				<?php }
		}
			?>
			) {

			hideform();
			return true
		}
		else
		{
		return false;
	}
});
//validation functions
function validate_widget_your_name()
{
	if ( $cwidget("#widget_your-name").val() == '' )
	{
	your_name.addClass("error");
	your_name_Info.text("<?php esc_html_e( 'Please Enter Name', 'templatic' ); ?>");
	your_name_Info.addClass("message_error");
	return false;
}
else
{
	your_name.removeClass("error");
	your_name_Info.text("");
	your_name_Info.removeClass("message_error");
	return true;
}
}
function validate_widget_your_email()
{
	$cwidget("#widget_your-email").val(( $cwidget("#widget_your-email").val().trim()));
	var isvalidemailflag = 0;
	if ( $cwidget("#widget_your-email").val() == '' )
	{
	isvalidemailflag = 1;
}else
if ( $cwidget("#widget_your-email").val() != '' )
{
	var a = $cwidget("#widget_your-email").val();
	var filter = /^[a-zA-Z0-9]+[a-zA-Z0-9_.-]+[a-zA-Z0-9_-]+@[a-zA-Z0-9]+[a-zA-Z0-9.-]+.[a-z]{2,4}$/;
	//if it's valid email
	if (filter.test(a))
	{
	isvalidemailflag = 0;
}else
{
	isvalidemailflag = 1;
}
}
if (isvalidemailflag)
{
	your_email.addClass("error");
	your_emailInfo.text("<?php esc_html_e( 'Please Enter valid Email', 'templatic' ); ?>");
	your_emailInfo.addClass("message_error");
	return false;
}else
{
	your_email.removeClass("error");
	your_emailInfo.text("");
	your_emailInfo.removeClass("message_error");
	return true;
}
}
function validate_widget_your_subject()
{
	if ( $cwidget("#widget_your-subject").val() == '' )
	{
	your_subject.addClass("error");
	your_subjectInfo.text("<?php esc_html_e( 'Please Enter Subject', 'templatic' ); ?>");
	your_subjectInfo.addClass("message_error");
	return false;
}
else
{
	your_subject.removeClass("error");
	your_subjectInfo.text("");
	your_subjectInfo.removeClass("message_error");
	return true;
}
}
function validate_widget_your_message()
{
	if ( $cwidget("#widget_your-message").val() == '' )
	{
	your_message.addClass("error");
	your_messageInfo.text("<?php esc_html_e( 'Please Enter Message', 'templatic' ); ?>");
	your_messageInfo.addClass("message_error");
	return false;
}
else
{
	your_message.removeClass("error");
	your_messageInfo.text("");
	your_messageInfo.removeClass("message_error");
	return true;
}
}
function validate_widget_recaptcha()
{
	if ( $cwidget("#recaptcha_response_field").val() == '' )
	{
	recaptcha_response_field.addClass("error");
	recaptcha_response_fieldInfo.text(" <?php esc_html_e( 'Please enter captcha', 'templatic' ); ?> ");
	recaptcha_response_fieldInfo.addClass("message_error");
	return false;
}
else{
recaptcha_response_field.removeClass("error");
recaptcha_response_fieldInfo.text("");
recaptcha_response_fieldInfo.removeClass("message_error");
return true;
}
}
});
</script>
<?php
if ( isset( $_REQUEST['capt'] ) &&  'captch' == $_REQUEST['capt'] && ! isset( $_REQUEST['message'] ) ) {
	?>
	<p class="error_msg">
		<?php esc_html_e( 'Please fill the captcha form.', 'templatic' );?>
	</p>
	<?php }
if ( 'playthru' == isset( $_REQUEST['invalid'] ) ) {
	echo '<div class="error_msg">';
	esc_html_e( 'You need to play the game to contact us.', 'templatic' );
	echo '</div>';
}
	if ( isset( $_REQUEST['message'] ) && 'success' == $_REQUEST['message'] ) { ?>
	<p class="success_msg"> <?php esc_html_e( 'Thank you! Your message has been successfully sent.', 'templatic' ); ?> </p>
	<?php
} elseif (isset( $_REQUEST['err'] ) && 'empty' == $_REQUEST['err'] ) {	?>
<p class="error_msg"> <?php esc_html_e( 'Please fill out all the fields before submitting.', 'templatic' );?> </p>
<?php }	?>
<form action="<?php echo $_SERVER['REQUEST_URI'];?>" method="post" id="contact_widget_frm" name="contact_frm" class="wpcf7-form">
	<input type="hidden" name="contact_widget" value="1" />
	<input type="hidden" name="request_url" value="<?php echo $_SERVER['REQUEST_URI'];?>" />
	<?php do_action( 'after_contact_form' ); ?>
	<div class="form_row ">
		<input type="text" name="your-name" id="widget_your-name" value="" class="textfield" size="40" placeholder="<?php esc_html_e( 'Name', 'templatic' );?>*" />
		<span id="widget_your_name_Info" class="error"></span> </div>
		<div class="form_row ">
			<input type="text" name="your-email" id="widget_your-email" value="" class="textfield" size="40" placeholder="<?php esc_html_e( 'Email', 'templatic' );?>*"/>
			<span id="widget_your_emailInfo"  class="error"></span> </div>
			<div class="form_row ">
				<input type="text" name="your-subject" id="widget_your-subject" value="" size="40" class="textfield" placeholder="<?php esc_html_e( 'Subject', 'templatic' );?>*"/>
				<span id="widget_your_subjectInfo"></span> </div>
				<?php
				do_action( 'after_contact_form_end' );
				do_action( 'after_contact_message_start' );
				?>
				<div class="form_row clearfix">
					<textarea name="your-message" id="widget_your-message" cols="40" class="textarea textarea2" rows="10" placeholder="<?php esc_html_e( 'Message', 'templatic' );?>"></textarea>
					<span id="widget_your_messageInfo"  class="error"></span> </div>
					<?php  do_action( 'after_contact_message_end' ); ?>
					<div class="clearfix">
						<?php
						if ( 1 == $captcha ) {
							/*do action to fetch captcha form*/
							do_action( 'tmpl_contact_us_captcha_script' );
							?>
							<div  id="contact_recaptcha_div"></div>
							<script>
								var onloadCallback = function() {
									if (jQuery( '#contact_recaptcha_div' ).length > 0) {
										grecaptcha.render( 'contact_recaptcha_div', {
											'sitekey' : '<?php echo wp_kses_post( $tmpdata['site_key'] ); ?>',
											'theme' : '<?php echo wp_kses_post( $tmpdata['comments_theme'] ); ?>'
										});
									}
								}
							</script>

							<?php

							$display = @$tmpdata['user_verification_page'];
							if ( empty( $display ) && ( ! in_array( 'registration', $display ) && ! in_array( 'submit', $display ) && ! in_array( 'claim', $display ) && ! in_array( 'emaitofrd', $display ) && ! in_array( 'sendinquiry', $display ) ) ) {

								echo '<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&amp;render=explicit&hl=' . wp_kses_post( $tmpdata['captcha_language'] ) . '" async ></script>';
							}
						}
						?>
						<input type="submit" value="<?php esc_html_e( 'Send', 'templatic' );?>" class="b_submit" />
					</div>
				</form>
			</div>
			<?php
			echo wp_kses_post( $args['after_widget'] );
		}
		/**
		 *
		 * Updates the widget control options for the particular instance of the widget.
		 *
		 * @param array $new_instance     new instance of widget when saved from widget area.
		 * @param array $old_instance 	  old instances of widget.
		 */
		function update( $new_instance, $old_instance ) {
			return $new_instance;
		}
		/**
		 *
		 * Save the widget.
		 *
		 * @param array $instance     instance of widget.
		 */
		function form( $instance ) {
			$instance = wp_parse_args( (array) $instance, array(
															'title' => '',
															)
			);
			$title = (strip_tags( $instance['title'] )) ? strip_tags( $instance['title'] ) : '';
			?>
			<p>
				<label for="<?php echo wp_kses_post( $this->get_field_id( 'title' ) ); ?>">
					<?php echo esc_html__( 'Widget Title', 'templatic-admin' );?>
					:
					<input class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'title' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
				</label>
			</p>
			<?php
		}
	}
} // End if().

if ( ! class_exists( 'supreme_recent_post' ) ) {
	/**
	 * Recent Post Widget Class.
	 **/
	class supreme_recent_post extends WP_Widget {
		/**
		 * Set up the widget's unique name, ID, class, description, and other options.
		 */
		function __construct() {
			$widget_ops = array(
							'classname'  	=> 'listing_post',
							'description'	=> esc_html__( 'Showcase posts from a particular category and post type. Use in main content, subsidiary and sidebar areas.', 'templatic-admin' ),
			);
			parent::__construct( 'supreme_recent_post', esc_html__( 'T &rarr; Display Posts', 'templatic-admin' ), $widget_ops );
		}
		/**
		 * Outputs the widget based on the arguments input through the widget controls.
		 *
		 * @param array $recent_post_args 	agurment of widget area.
		 * @param array $instance 			instances of widget.
		 */
		function widget( $recent_post_args, $instance ) {
			extract( $recent_post_args, EXTR_SKIP );

			$instance = wp_parse_args( (array) $instance, array(
															'title'             => 'Blog Listings',
															'post_type'         => 'post',
															'post_type_taxonomy' => '',
															'post_number'       => 5,
															'orderby'           => 'date',
															'order'             => 'DESC',
															'show_image'        => 0,
															'image_alignment'   => '',
															'image_size'        => '',
															'show_gravatar'     => 0,
															'gravatar_alignment' => '',
															'gravatar_size'     => '',
															'show_title'        => 0,
															'show_byline'       => 0,
															'post_info'         => '[post_date] ' . esc_html__( 'By', 'templatic' ) . ' [post_author_posts_link] [post_comments]',
															'show_content'      => 'content',
															'content_limit'     => 25,
															'enable_categories' => '',
															'more_text'         => esc_html__( '[Read More...]', 'templatic' ),
				)
			);
			echo wp_kses_post( $recent_post_args['before_widget'] );
			remove_all_actions( 'posts_where' );
			$unique_string = rand();
			echo '<div id="display_post_widget_' . intval( $unique_string ) . '">';
			
				$taxonomies = get_object_taxonomies( (object) array(
																'post_type' => $instance['post_type'],
																'public'   	=> true,
																'_builtin' 	=> true,
															)
				);
				if ( $instance['post_type_taxonomy'] ) {
					$cat_id = $instance['post_type_taxonomy'];
				}

				if ( is_plugin_active( 'woocommerce/woocommerce.php' ) && 'product' == $instance['post_type'] ) {
					$taxonomies[0] = $taxonomies[1];
				}

				if ( $instance['post_type_taxonomy'] && 'post' != $instance['post_type'] ) {

					$taxonomy_fetch = get_term( $cat_id, $taxonomies[0] );
					if ( empty( $taxonomy_fetch ) ) {
						$taxonomy_fetch = get_term( $cat_id, $taxonomies[1] );
					}

					$featured_arg = array(
										'post_type' => $instance['post_type'],
										'showposts' => $instance['post_number'],
										'orderby'  	=> $instance['orderby'],
										'order'     => $instance['order'],
										'post_status' => 'publish',
										'tax_query' => array(
															array(
																'taxonomy' 	=> $taxonomy_fetch->taxonomy,
																'field'   	=> 'id',
																'terms'    	=> array( $cat_id ),
																'operator'	=> 'IN',
																),
															),
										);
				} else if ( $instance['post_type_taxonomy'] && 'post' == $instance['post_type'] ) {

					$featured_arg = array(
										'post_type'		=> $instance['post_type'],
										'showposts' 	=> $instance['post_number'],
										'orderby'	  	=> $instance['orderby'],
										'order'		    => $instance['order'],
										'post_status'	=> 'publish',
										'tax_query' 	=> array(
																array(
																	'taxonomy' => 'category',
																	'field'    => 'id',
																	'terms'    => array( $cat_id ),
																	'operator' => 'IN',
																	),
															),
										);
				} else {
					if ( $cat_id ) {
						$cats = array( $cat_id );
					}

					$featured_arg = array(
										'post_type' 	=> $instance['post_type'],
										'post_status'	=> 'publish',
										'showposts'		=> $instance['post_number'],
										'orderby'		=> $instance['orderby'],
										'category__in'	=> $cats,
										'order'		    => $instance['order'],
						);
				} // End if().

				remove_all_actions( 'posts_orderby' );

				if ( is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) && function_exists( 'templatic_widget_wpml_filter' ) ) {
					if ( 'post' == $instance['post_type'] ) {
						remove_all_actions( 'posts_join' );
						add_filter( 'posts_join','tmpl_wpml_posts_joins' );
					} else {
						add_filter( 'posts_where','templatic_widget_wpml_filter' );
					}
				}

				/* Add Do action for insert extra post where filter */
				do_action( 'post_listing_widget_before_post_where', $instance );
				$featured_posts = new WP_Query( $featured_arg );
				if ( ! empty( $instance['title'] ) && 0 != $featured_posts->found_posts ) {
					echo wp_kses_post( $before_title . apply_filters( 'widget_title', $instance['title'] ) . $after_title );
				}

				if ( function_exists( 'templatic_widget_wpml_filter' ) ) {
					remove_filter( 'posts_where','templatic_widget_wpml_filter' );
				}
				/* Add Do action for remove extra added post where filter */
				do_action( 'post_listing_widget_after_post_where', $instance );

				global $post;
				if ( $featured_posts->have_posts() ) :
					?>
				<div class="listing_post_wrapper">
					<?php
					while ( $featured_posts->have_posts() ) : $featured_posts->the_post(); ?>
					<div <?php post_class(); ?>><div class="post-blog-image">
						<?php
						/*Show Avatar */
						if ( ! empty( $instance['show_gravatar'] ) ) :
							echo '<span class="' . esc_attr( $instance['gravatar_alignment'] ) . '">';
							echo wp_kses_post( get_avatar( get_the_author_meta( 'ID' ), $instance['gravatar_size'] ) );
							echo '</span>';
						endif;
						/* show post title*/
						do_action( 'listing_post_before_image', $instance );
						$a     = get_intermediate_image_sizes();
						$width = '';
						$height = '';
						global $_wp_additional_image_sizes;
						$_wp_additional_image_sizes_count = count( $_wp_additional_image_sizes );
						for ( $i = 0; $i < $_wp_additional_image_sizes_count; $i++ ) {
							$a = array_keys( $_wp_additional_image_sizes );
							$a_count = count( $a );
							for ( $k = 0; $k < $a_count; $k++ ) {
								if ( $a[ $k ] == $instance['image_size'] ) {
									$width  = $_wp_additional_image_sizes[ $a[ $k ] ]['width'];
									$height = $_wp_additional_image_sizes[ $a[ $k ] ]['height'];
								}
							}
						}
						if ( ! $width ) {
							$width = '250px';
						}

						if ( ! $height ) {
							$height = '165px';
						}
						/* Display image */

						if ( ! empty( $instance['show_image'] ) ) :

							$post_image = '';
							if ( has_post_thumbnail( $post->ID ) ) {
								$img_arr = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), $instance['image_size'] );
								$post_image = $img_arr[0];
								$style = '';
							} elseif ( function_exists( 'bdw_get_images_plugin' ) ) {
								$post_img = bdw_get_images_plugin( $post->ID,$instance['image_size'] );
								$post_image = @$post_img[0]['file'];
								$style = '';
							}
							if ( ! $post_image ) {
								$theme_options = get_option( supreme_prefix() . '_theme_settings' );
								$post_image = apply_filters( 'supreme_noimage-url', 'http://placehold.it/250x165/F6F6F6/333&text=No%20image' );

								$style = apply_filters( 'supreme_noimage-size', 'width="50px" height="50px"' );

							}
							?>

							<?php if ( @$featured ) {
									echo '<span class="featured_tag">' . esc_html__( 'Featured', 'templatic' ) . '</span>';
	}?>
							<a href="<?php echo esc_url( get_permalink() ); ?>" title="<?php the_title_attribute( 'echo=1' ); ?>" rel="bookmark" class="featured-image-link"><img src="<?php echo esc_url( $post_image ); ?>" width="<?php echo intval( $width );?>" height="<?php echo intval( $height );?>" alt="<?php the_title_attribute( 'echo=1' ); ?>" <?php echo wp_kses_post( $style ); ?>/></a>
							<?php
					endif; ?>
				</div>
				<?php
					do_action( 'listing_post_after_image', $instance );

					$more_text = $instance['more_text'];
				if ( function_exists( 'icl_register_string' ) ) {
					icl_register_string( 'templatic', $args['widget_id'] . 'more_text' . $more_text, $more_text );
					$more_text = icl_t( 'templatic', $args['widget_id'] . 'more_text' . $more_text, $more_text );
				}

					echo "<div class='entry-header'>";
					do_action( $post->post_type . '_before_title', $instance );
				if ( ! empty( $instance['show_title'] ) ) :
					printf( '<h2><a href="%s" title="%s">%s</a></h2>', get_permalink(), the_title_attribute( 'echo=0' ), the_title_attribute( 'echo=0' ) );
				endif;
				if ( 'post' != $post->post_type ) {
					do_action( $post->post_type . '_after_title', $instance );
				}
				if ( ! empty( $instance['show_content'] ) ) :
					echo "<div class='post-summery'>";
					if ( 'excerpt' == $instance['show_content'] ) :
							the_excerpt();
						elseif ( 'content-limit' == $instance['show_content'] ) :
							echo the_content_limit( (int) $instance['content_limit'], esc_html( $more_text ) );
						else :
							the_content( esc_html( $more_text ) );
						endif;
						echo '</div>';
					endif;
					do_action( 'listng_post_after_content' );
					echo '</div>';

					echo '</div><!--end post_class()-->';
				endwhile;
					wp_reset_query();
				?>
			</div>
			<!-- listing_post_wrapper end-->
			<?php
			endif;
		
		echo '</div>';
			echo wp_kses_post( $recent_post_args['after_widget'] );
		}
		/**
		 *
		 * Updates the widget control options for the particular instance of the widget.
		 *
		 * @param array $new_instance     new instance of widget when saved from widget area.
		 * @param array $old_instance 	  old instances of widget.
		 */
		function update( $new_instance, $old_instance ) {
			return $new_instance;
		}
		/**
		 *
		 * Save the widget.
		 *
		 * @param array $instance     instance of widget.
		 */
		function form( $instance ) {

			$instance = wp_parse_args( (array) $instance, array(
															'title'             => 'Blog Listings',
															'post_type'         => 'post',
															'post_type_taxonomy' => '',
															'post_number'       => 5,
															'orderby'           => 'date',
															'order'             => 'DESC',
															'show_image'        => 0,
															'image_alignment'   => '',
															'image_size'        => '',
															'show_gravatar'     => 0,
															'gravatar_alignment' => '',
															'gravatar_size'     => '',
															'show_title'        => 0,
															'show_byline'       => 0,
															'post_info'         => '[post_date] ' . esc_html__( 'By', 'templatic' ) . ' [post_author_posts_link] [post_comments]',
															'show_content'      => 'content',
															'content_limit'     => 25,
															'enable_categories' => '',
															'more_text'         => esc_html__( '[Read More...]', 'templatic' ),
															)
			);
				?>
				<script type="text/javascript">
					function select_show_content_limit(str,div) {
						if (str.value=='content-limit' ) {
							jQuery( '#'+div).slideToggle( 'slow' );
						} else {
							jQuery( '#'+div).hide();
						}
					}
				</script>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'title' ) ); ?>">
						<?php echo esc_html__( 'Title:', 'templatic' );?>
						<input class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'title' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo wp_kses_post( $instance['title'] ); ?>" />
					</label>
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'post_type' ) );?>" >
						<?php echo esc_html__( 'Select Post type:', 'templatic' );?>
						<select  id="<?php echo wp_kses_post( $this->get_field_id( 'post_type' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'post_type' ) ); ?>" class="widefat">
							<?php
							$all_post_types = get_post_types();
							foreach ( $all_post_types as $post_types ) {
								if ( 'page' != $post_types && 'attachment' != $post_types && 'revision' != $post_types && 'nav_menu_item' != $post_types && 'product_variation' != $post_types && 'shop_order' != $post_types && 'shop_coupon' != $post_types && 'admanager' != $post_types && 'custom_css' != $post_types && 'customize_changeset' != $post_types ) {
									?>
								<option value="<?php echo esc_attr( $post_types );?>" <?php
								if ( $post_types == $instance['post_type'] ) {
										echo 'selected';
								} ?>> <?php echo esc_attr( $post_types );?> </option>
									<?php
								}
							}
								?>
							</select>
						</label>
					</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'post_type_taxonomy' ) ); ?>" >
					<?php echo esc_html__( 'Select Category:', 'templatic-admin' );?>
					<select id="<?php echo wp_kses_post( $this->get_field_id( 'post_type_taxonomy' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'post_type_taxonomy' ) ); ?>" class="widefat" >
						<option value="">
							<?php echo esc_html__( '---Select Category wise recent post ---', 'templatic-admin' ); ?>
						</option>
						<?php
						$taxonomies = get_taxonomies( array(
														'public' => true,
														), 'objects'
						);
						$taxonomies = apply_filters( 'templatic_exclude_taxonomies', $taxonomies );?>
						<?php
						foreach ( $taxonomies as $taxonomy ) {
							$query_label = '';
							if ( ! empty( $taxonomy->query_var ) ) {
								$query_label = $taxonomy->query_var;
							} else {
								$query_label = $taxonomy->name;
							}
							if ( 'Tags' != $taxonomy->labels->name && 'Format' != $taxonomy->labels->name ) :
								?>
							<optgroup label="<?php echo esc_attr( $taxonomy->object_type[0] ) . '-' . esc_attr( $taxonomy->labels->name ); ?>">
								<?php
								$terms = get_terms( $taxonomy->name, 'orderby=name&hide_empty=1' );
								foreach ( $terms as $term ) {
									$term_value = $term->term_id;	?>
									<option style="margin-left: 8px; padding-right:10px;" value="<?php echo intval( $term_value ); ?>" <?php
									if ( $instance['post_type_taxonomy'] == $term_value ) {
										echo 'selected';
									}?>> <?php echo '-' . esc_attr( $term->name ); ?> </option>
									<?php
								} ?>
								</optgroup>
								<?php
							endif;
						}
							?>
						</select>
					</label>
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'post_number' ) ); ?>">
						<?php echo esc_html__( 'Number of posts:', 'templatic-admin' );?>
						<input class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'post_number' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'post_number' ) ); ?>" type="text" value="<?php echo wp_kses_post( $instance['post_number'] ); ?>" />
					</label>
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'orderby' ) ); ?>">
						<?php echo esc_html__( 'Order By', 'templatic-admin' ); ?>
						: </label>
						<select id="<?php echo wp_kses_post( $this->get_field_id( 'orderby' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'orderby' ) ); ?>">
							<option style="padding-right:10px;" value="date" <?php selected( 'date', $instance['orderby'] ); ?>>
								<?php echo esc_html__( 'Date', 'templatic-admin' ); ?>
							</option>
							<option style="padding-right:10px;" value="title" <?php selected( 'title', $instance['orderby'] ); ?>>
								<?php echo esc_html__( 'Title', 'templatic-admin' ); ?>
							</option>
							<option style="padding-right:10px;" value="ID" <?php selected( 'ID', $instance['orderby'] ); ?>>
								<?php echo esc_html__( 'ID', 'templatic-admin' ); ?>
							</option>
							<option style="padding-right:10px;" value="comment_count" <?php selected( 'comment_count', $instance['orderby'] ); ?>>
								<?php echo esc_html__( 'Comment Count', 'templatic-admin' ); ?>
							</option>
							<option style="padding-right:10px;" value="rand" <?php selected( 'rand', $instance['orderby'] ); ?>>
								<?php echo esc_html__( 'Random', 'templatic-admin' ); ?>
							</option>
						</select>
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'order' ) ); ?>">
						<?php echo esc_html__( 'Sort Order', 'templatic-admin' ); ?>
						: </label>
						<select id="<?php echo wp_kses_post( $this->get_field_id( 'order' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'order' ) ); ?>">
							<option style="padding-right:10px;" value="DESC" <?php selected( 'DESC', $instance['order'] ); ?>>
								<?php echo esc_html__( 'Descending (3, 2, 1)', 'templatic-admin' ); ?>
							</option>
							<option style="padding-right:10px;" value="ASC" <?php selected( 'ASC', $instance['order'] ); ?>>
								<?php echo esc_html__( 'Ascending (1, 2, 3)', 'templatic-admin' ); ?>
							</option>
						</select>
				</p>
				<p>
					<input id="<?php echo wp_kses_post( $this->get_field_id( 'show_gravatar' ) ); ?>" type="checkbox" name="<?php echo wp_kses_post( $this->get_field_name( 'show_gravatar' ) ); ?>" value="1" <?php checked( 1, $instance['show_gravatar'] ); ?>/>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'show_gravatar' ) ); ?>">
						<?php echo esc_html__( 'Show Author Gravatar', 'templatic-admin' ); ?>
					</label>
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'gravatar_size' ) ); ?>">
						<?php echo esc_html__( 'Gravatar Size', 'templatic-admin' ); ?>
						: </label>
						<select id="<?php echo wp_kses_post( $this->get_field_id( 'gravatar_size' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'gravatar_size' ) ); ?>">
							<option style="padding-right:10px;" value="45" <?php selected( 45, $instance['gravatar_size'] ); ?>>
								<?php echo esc_html__( 'Small (45px)', 'templatic-admin' ); ?>
							</option>
							<option style="padding-right:10px;" value="65" <?php selected( 65, $instance['gravatar_size'] ); ?>>
								<?php echo esc_html__( 'Medium (65px)', 'templatic-admin' ); ?>
							</option>
							<option style="padding-right:10px;" value="85" <?php selected( 85, $instance['gravatar_size'] ); ?>>
								<?php echo esc_html__( 'Large (85px)', 'templatic-admin' ); ?>
							</option>
							<option style="padding-right:10px;" value="125" <?php selected( 125, $instance['gravatar_size'] ); ?>>
								<?php echo esc_html__( 'Extra Large (125px)', 'templatic-admin' ); ?>
							</option>
						</select>
				</p>
				<p>
					<input id="<?php echo wp_kses_post( $this->get_field_id( 'show_image' ) ); ?>" type="checkbox" name="<?php echo wp_kses_post( $this->get_field_name( 'show_image' ) ); ?>" value="1" <?php checked( 1, $instance['show_image'] ); ?>/>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'show_image' ) ); ?>">
						<?php echo esc_html__( 'Show Featured Image', 'templatic-admin' ); ?>
					</label>
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'image_size' ) ); ?>">
						<?php echo esc_html__( 'Image Size', 'templatic-admin' ); ?>
						: </label>
						<?php
						if ( function_exists( 'get_additional_image_sizes' ) ) {
							$sizes = get_additional_image_sizes();
						} else {
							$sizes = supreme_get_additional_image_sizes();
						} ?>
						<select id="<?php echo wp_kses_post( $this->get_field_id( 'image_size' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'image_size' ) ); ?>">
							<option style="padding-right:10px;" value="thumbnail">
								<?php echo esc_html__( 'thumb', 'templatic-admin' ); ?>
								(<?php echo intval( get_option( 'thumbnail_size_w' ) ); ?>x<?php echo intval( get_option( 'thumbnail_size_h' ) ); ?>) </option>
								<?php
								foreach ( (array) $sizes as $name => $size ) :
									echo '<option style="padding-right: 10px;" value="' . esc_attr( $name ) . '" ' . selected( $name, $instance['image_size'], false ) . '>' . esc_html( $name ) . ' ( ' . intval( $size['width'] ) . 'x' . intval( $size['height'] ) . ' )</option>';
								endforeach;
								?>
							</select>
				</p>
				<p>
					<input id="<?php echo wp_kses_post( $this->get_field_id( 'show_title' ) ); ?>" type="checkbox" name="<?php echo wp_kses_post( $this->get_field_name( 'show_title' ) ); ?>" value="1" <?php checked( 1, $instance['show_title'] ); ?>/>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'show_title' ) ); ?>">
						<?php echo esc_html__( 'Show Post Title', 'templatic-admin' ); ?>
					</label>
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'show_content' ) ); ?>">
						<?php echo esc_html__( 'Content Type', 'templatic-admin' ); ?>
						: </label>
						<select id="<?php echo wp_kses_post( $this->get_field_id( 'show_content' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'show_content' ) ); ?>" onchange="select_show_content_limit(this,'<?php echo wp_kses_post( $this->get_field_id( 'show_content_limit' ) ); ?>' )">
							<option value="content" <?php selected( 'content' , $instance['show_content'] ); ?>>
								<?php echo esc_html__( 'Show Content', 'templatic-admin' ); ?>
							</option>
							<option value="excerpt" <?php selected( 'excerpt' , $instance['show_content'] ); ?>>
								<?php echo esc_html__( 'Show Excerpt', 'templatic-admin' ); ?>
							</option>
							<option value="content-limit" <?php selected( 'content-limit' , $instance['show_content'] ); ?>>
								<?php echo esc_html__( 'Show Content Limit', 'templatic-admin' ); ?>
							</option>
							<option value="" <?php selected( '' , $instance['show_content'] ); ?>>
								<?php echo esc_html__( 'No Content', 'templatic-admin' ); ?>
							</option>
						</select>
				</p>
				<div id="<?php echo wp_kses_post( $this->get_field_id( 'show_content_limit' ) ); ?>" style="<?php if ( 'content-limit' != $instance['show_content'] ) {
						echo 'display:none'; } ?>">
					<p>
						<label for="<?php echo wp_kses_post( $this->get_field_id( 'content_limit' ) ); ?>">
							<?php echo esc_html__( 'Limit content to', 'templatic-admin' ); ?>
						</label>
						<input type="text" id="<?php echo wp_kses_post( $this->get_field_id( 'image_alignment' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'content_limit' ) ); ?>" value="<?php echo esc_attr( intval( $instance['content_limit'] ) ); ?>" size="3" />
						<?php echo esc_html__( 'characters', 'templatic-admin' ); ?>
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'more_text' ) ); ?>">
						<?php echo esc_html__( 'More Text (if applicable)', 'templatic-admin' ); ?>
						: </label>
						<input type="text" id="<?php echo wp_kses_post( $this->get_field_id( 'more_text' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'more_text' ) ); ?>" value="<?php echo esc_attr( $instance['more_text'] ); ?>" />
				</p>
			</div>
			<?php
		}
	}
} // End if().

if ( ! class_exists( 'supreme_popular_post' ) ) {
	/**
	 * Recent Post Widget Class.
	 **/
	class supreme_popular_post extends WP_Widget {
		/**
		 * Set up the widget's unique name, ID, class, description, and other options.
		 */
		function __construct() {
			$widget_ops = array(
							'classname'  	=> 'widget-twocolumn popular_posts',
							'description'	=> esc_html__( 'Show a list of popular posts from a particular post type. Popularity is determined by total/daily views or comments. Works best in sidebar areas.', 'templatic-admin' ),
			);
			parent::__construct( 'templatic_popular_post_technews', esc_html__( 'T &rarr; Popular Posts', 'templatic-admin' ), $widget_ops );
		}
		/**
		 * Outputs the widget based on the arguments input through the widget controls.
		 *
		 * @param array $args 		agurment of widget area.
		 * @param array $instance 	instances of widget.
		 */
		function widget( $args, $instance ) {
			extract( $args, EXTR_SKIP );
			global $wpdb,$posts,$post,$query_string;
			echo wp_kses_post( $args['before_widget'] );
			$title = empty( $instance['title'] ) ? '' : apply_filters( 'widget_title', $instance['title'] );
			$post_type = empty( $instance['post_type'] ) ? '' : apply_filters( 'widget_post_type', $instance['post_type'] );
			$number = empty( $instance['number'] ) ? 5 : apply_filters( 'widget_number', $instance['number'] );
			$slide = empty( $instance['slide'] ) ? 5 : apply_filters( 'widget_slide', $instance['slide'] );
			$popular_per = empty( $instance['popular_per'] ) ? 'comments' : apply_filters( 'widget_popular_per', $instance['popular_per'] );
			$show_excerpt = empty( $instance['show_excerpt'] ) ? 'comments' : apply_filters( 'widget_show_excerpt', $instance['show_excerpt'] );
			$show_excerpt_length = empty( $instance['show_excerpt_length'] ) ? 27 : apply_filters( 'widget_show_excerpt', $instance['show_excerpt_length'] );
			$pagination_position = empty( $instance['pagination_position'] ) ? 0 : apply_filters( 'widget_pagination_position', $instance['pagination_position'] );

			if ( post_type_exists( $post_type ) ) {
				$post_type  = $post_type ;
			} else {
				$post_type = 'post';
			}
			wp_reset_query();
			remove_all_filters( 'posts_where' ); /* made query default when no field is seleted in widget */
			if ( 'views' == $popular_per ) {
				$args_popular = array(
									'post_type'			=> $post_type,
									'post_status'		=> 'publish',
									'posts_per_page' 	=> $number,
									'meta_key'			=> 'viewed_count',
									'orderby' 			=> 'meta_value_num',
									'meta_value_num'	=> 'viewed_count',
									'order' 			=> 'DESC',
				);
			} elseif ( 'dailyviews' == $popular_per ) {
				$args_popular = array(
									'post_type'			=> $post_type,
									'post_status'		=> 'publish',
									'posts_per_page' 	=> $number,
									'meta_key'			=> 'viewed_count_daily',
									'orderby' 			=> 'meta_value_num',
									'meta_value_num'	=> 'viewed_count_daily',
									'order' 			=> 'DESC',
				);
			} else {
				$args_popular = array(
									'post_type'			=> $post_type,
									'post_status'		=> 'publish',
									'posts_per_page' 	=> $number,
									'orderby' 			=> 'comment_count',
									'order' 			=> 'DESC',
				);
			}

			$location_post_type = get_option( 'location_post_type' );
			if ( is_array( $location_post_type ) && ! empty( $location_post_type ) ) {
				foreach ( $location_post_type as $location_post_types ) {
					$post_types = explode( ',', $location_post_types );
					$post_type1[] = $post_types[0];
				}
			}

			/* filter for current city wise populer posts */
			if ( is_plugin_active( 'Tevolution-LocationManager/location-manager.php' ) && in_array( $post_type, $post_type1 ) ) {
				add_filter( 'posts_where', 'location_multicity_where' );
			}

			$popular_post_query = new WP_Query( $args_popular );

			if ( is_plugin_active( 'Tevolution-LocationManager/location-manager.php' ) ) {
				remove_filter( 'posts_where', 'location_multicity_where' );
			}

			$totalpost = $popular_post_query->posts;

			if ( ! empty( $totalpost ) ) {
				if ( '' <> $title ) {
					echo wp_kses_post( $args['before_title'] . $title . $args['after_title'] );
				}
			}

			if ( ! empty( $totalpost ) && count( $totalpost ) > 0 ) {
				@$countpost = ( count( $totalpost ) < $number ) ? count( $totalpost ) : $number;
				if ( 'views' == $popular_per || 'dailyviews' == $popular_per ) {
					$countpost = count( $totalpost );
				}
				$dot = ceil( $countpost / $slide );
			}
			if ( is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) ) {
				global $sitepress;
				$current_lang_code = ICL_LANGUAGE_CODE;
				$language          = $current_lang_code;
			}
			$rand = rand();
			if ( 1 == $pagination_position && ! empty( $totalpost ) && count( $totalpost ) > 0 ) {
				?>
				<div class="postpagination_<?php echo intval( $rand ); ?> postpagination clearfix">
					<?php if ( 1 != $dot ) {	?>
					<a num="1" rel="0" rev="<?php echo intval( $slide ); ?>" class="active"> 1 </a>
					<?php
					for ( $c = 1; $c < $dot; $c++ ) {
						$start = ( $c * $slide);
						echo '<a num="' . intval( $c + 1 ) . '" rel="' . intval( $start ) . '" rev="' . intval( $slide ) . '">' . intval( $c + 1 ) . '</a>';
					}
} ?>
			</div>
			<?php
			}
			if ( empty( $totalpost ) && count( $totalpost ) > 0 ) { ?>
				<ul class=" clearfix list" id="">
					<li>
						<?php esc_html_e( 'There is no post available right now.', 'templatic' );?>
					</li>
				</ul><?php
			}	?>

			<ul class="listingview clearfix list" id="list_<?php echo intval( $rand ); ?>"></ul>

			<?php
			if ( 1 != @$pagination_position && ! empty( $totalpost ) && count( $totalpost ) > 0 ) {	?>
			<div class="postpagination_<?php echo intval( $rand ); ?> postpagination clearfix">
				<?php
				if ( 1 != $dot ) {?>
				<a num="1" rel="0" rev="<?php echo intval( $slide ); ?>" class="active"> 1 </a>
				<?php
				for ( $c = 1; $c < $dot; $c++ ) {
					$start = ( $c * $slide );
					echo '<a num="' . esc_attr( ( $c + 1 ) ) . '" rel="' . esc_attr( $start ) . '" rev="' . esc_attr( $slide ) . '">' . esc_attr( ( $c + 1 ) ) . '</a>';
				}
				} ?>
			</div>
			<?php
			}

			if ( is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) ) {

				$site_url = apply_filters( 'bloginfo_url', site_url() ) . '/wp-admin/admin-ajax.php?lang=' . ICL_LANGUAGE_CODE ;
			} else {
				$site_url = apply_filters( 'bloginfo_url', site_url() ) . '/wp-admin/admin-ajax.php';
			}

		?>
		<script type="text/javascript">
			jQuery(document).ready(function() {
				var ajaxUrl = "<?php echo esc_js( $site_url ); ?>";
				var limitarr = [ 0, <?php echo intval( $slide ); ?>, <?php echo intval( $number ); ?>, '<?php echo esc_attr( $post_type );?>', 1, '<?php echo esc_attr( $popular_per );?>', <?php echo intval( $number );?>, '<?php echo esc_attr( @$language ); ?>', '<?php echo esc_attr( $show_excerpt ); ?>','<?php echo intval( $show_excerpt_length ); ?>' ];

				/* when tevolution activated load popular post with custom ajax only */

				if ( '' != tevolutionajaxUrl ) {
					var ajaxUrl = tevolutionajaxUrl;
				}
				/* Ajax request for populer posts when click on pages */
				jQuery( '.postpagination_<?php echo intval( $rand ); ?> a' ).click(function()
				{
					var start =  parseInt(jQuery(this).attr( 'rel' ) );
					var end =  parseInt(jQuery(this).attr( 'rev' ) );
					var num =parseInt(jQuery(this).attr( 'num' ) );
					limitarr = [ 0, <?php echo intval( $slide ); ?>, <?php echo intval( $number ); ?>, '<?php echo esc_attr( $post_type );?>', num, '<?php echo esc_attr( $popular_per );?>', <?php echo intval( $number );?>, '<?php echo esc_attr( @$language ); ?>', '<?php echo esc_attr( $show_excerpt ); ?>','<?php echo intval( $show_excerpt_length ); ?>' ];
					jQuery( '.postpagination a' ).attr( 'class','' );
					jQuery(this).attr( 'class','active' );

					jQuery.ajax({
						url: ajaxUrl,
						type:'POST',
						async: true,
						data:'action=load_populer_post&limitarr='+limitarr,
						success:function(results) {
							jQuery( '#list_<?php echo intval( $rand ); ?>' ).html(results);
						}
					});
				});

				/* Ajax request for populer posts when page loads */
				jQuery.ajax({
					url: ajaxUrl,
					type:'POST',
					async: true,
					data:'action=load_populer_post&limitarr='+limitarr,
					success:function(results) {
						jQuery( '#list_<?php echo intval( $rand ); ?>' ).html(results);
					}
				});
			});
			</script>
			<?php
			echo wp_kses_post( $args['after_widget'] );
		}
		/**
		 *
		 * Updates the widget control options for the particular instance of the widget.
		 *
		 * @param array $new_instance     new instance of widget when saved from widget area.
		 * @param array $old_instance 	  old instances of widget.
		 */
		function update( $new_instance, $old_instance ) {
			return $new_instance;
		}
		/**
		 *
		 * Save the widget.
		 *
		 * @param array $instance     instance of widget.
		 */
		function form( $instance ) {
			$instance = wp_parse_args( (array) $instance, array(
															'title'               => 'Popular Posts',
															'post_type'           => 'post',
															'number'              => 10,
															'slide'               => 3,
															'popular_per'         => 'comments',
															'show_excerpt'        => 1,
															'show_excerpt_length' => 27,
															'pagination_position' => 0,
															)
			);
			?>
			<p>
				<label for="<?php  echo wp_kses_post( $this->get_field_id( 'title' ) ); ?>">
					<?php echo esc_html__( 'Title', 'templatic-admin' );?>
					:
					<input class="widefat" id="<?php  echo wp_kses_post( $this->get_field_id( 'title' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo wp_kses_post( $instance['title'] ); ?>" />
				</label>
			</p>
			<p>
				<label for="<?php echo wp_kses_post( $this->get_field_id( 'post_type' ) );?>" >
					<?php echo esc_html__( 'Select Post type', 'templatic-admin' );?>
					:
					<select id="<?php echo wp_kses_post( $this->get_field_id( 'post_type' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'post_type' ) ); ?>" class="widefat" >
						<?php
						$all_post_types = get_post_types();
						foreach ( $all_post_types as $post_types ) {
							if ( 'post' != $post_types && 'page' != $post_types && 'attachment' != $post_types && 'revision' != $post_types && 'nav_menu_item' != $post_types && 'product' != $post_types && 'product_variation' != $post_types && 'shop_order' != $post_types && 'shop_coupon' != $post_types && 'admanager' != $post_types ) {
							?>
								<option value="<?php echo esc_attr( $post_types );?>" <?php
								if ( $post_types == $instance['post_type'] ) {
									echo 'selected';
								}?>> <?php echo esc_attr( $post_types );?> </option>
								<?php
							}
						}
						?>
					</select>
				</label>
			</p>
			<p>
				<label for="<?php echo wp_kses_post( $this->get_field_id( 'number' ) ); ?>">
					<?php echo esc_html__( 'Total Number of Posts', 'templatic-admin' );?>
					:
					<input class="widefat" id="<?php  echo wp_kses_post( $this->get_field_id( 'number' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'number' ) ); ?>" type="text" value="<?php echo wp_kses_post( $instance['number'] ); ?>" />
				</label>
			</p>
			<?php
			if ( current_theme_supports( 'excerpt_in_popular_post' ) ) {
				?>
				<p>
					<?php
					if ( 1 == $instance['show_excerpt'] ) {
						$chk = 'checked=cheked';
					} else {
						$chk = '';
					} ?>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'show_excerpt' ) ); ?>">
						<input id="<?php  echo wp_kses_post( $this->get_field_id( 'show_excerpt' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'show_excerpt' ) ); ?>" type="checkbox" value="1" <?php echo esc_attr( $chk ); ?>/>
						<?php echo esc_html__( 'Show excerpt', 'templatic-admin' );?>
					</label>
					<br/>
					<small>
						<?php echo esc_html__( 'You can change Excerpt length from customizer Listing Page Settings', 'templatic-admin' );?>
					</small>
				</p>
				<p>
					<label for="<?php echo wp_kses_post( $this->get_field_id( 'show_excerpt_length' ) ); ?>">
						<?php echo esc_html__( 'Excerpt Length', 'templatic-admin' );?>
						:
						<input  class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'show_excerpt_length' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'show_excerpt_length' ) ); ?>" type="text" value="<?php echo wp_kses_post( $instance['show_excerpt_length'] ); ?>"/>
					</label>
				</p>
				<?php
			} ?>
			<p>
				<label for="<?php echo wp_kses_post( $this->get_field_id( 'slide' ) ); ?>">
					<?php echo esc_html__( 'Number of Posts Per Slide', 'templatic-admin' );?>
					:
					<input class="widefat" id="<?php  echo wp_kses_post( $this->get_field_id( 'slide' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'slide' ) ); ?>" type="text" value="<?php echo wp_kses_post( $instance['slide'] ); ?>" />
				</label>
			</p>
			<p>
				<label for="<?php echo wp_kses_post( $this->get_field_id( 'popular_per' ) ); ?>">
					<?php echo esc_html__( 'Shows post as per view counting/comments', 'templatic-admin' );?>
					:
					<select class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'popular_per' ) ); ?>" onchange="show_hide_info(this.value,'<?php echo wp_kses_post( $this->get_field_id( 'daily_view' ) ); ?>' )" name="<?php echo wp_kses_post( $this->get_field_name( 'popular_per' ) ); ?>">
						<option value="views" <?php
						if ( 'views' == $instance['popular_per'] ) { ?>
								selected='selected'
							<?php } ?>>
						<?php echo esc_html__( 'Total views', 'templatic-admin' ); ?>
						</option>
						<option value="dailyviews" <?php
						if ( 'dailyviews' == $instance['popular_per'] ) { ?>
								selected='selected'
							<?php } ?>>
								<?php echo esc_html__( 'Daily views', 'templatic-admin' ); ?>
						</option>
						<option value="comments" <?php
						if ( 'comments' == $instance['popular_per'] ) { ?>
								selected='selected'
							<?php } ?>>
							<?php echo esc_html__( 'Total comments', 'templatic-admin' ); ?>
						</option>
					</select>
				</label>
			</p>
			<p id="<?php echo wp_kses_post( $this->get_field_id( 'daily_view' ) ); ?>" style="margin:0 0 20px;<?php
			if ( '' == @$instance['popular_per'] ) {
				echo 'display:none';
			} elseif ( 'views' == $instance['popular_per'] || 'dailyviews' == $instance['popular_per'] ) {
				echo 'display:block';
			} else {
				echo 'display:none';
			}?>"> <small>
			<?php echo esc_html__( 'To make this widget work with daily views/total views, enable view counter from Tevolution - &gt; General Settings - &gt; Detail/Single Page Settings', 'templatic-admin' );?>
			</small>
			</p>
			<p>
				<label for="<?php echo wp_kses_post( $this->get_field_id( 'pagination_position' ) ); ?>">
					<?php echo esc_html__( 'Pagination Position', 'templatic-admin' );?>
					:
					<select class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'pagination_position' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'pagination_position' ) ); ?>">
						<option value="0" <?php
						if ( 0 == $instance['pagination_position'] ) { ?>
							selected='selected'
						<?php } ?>>
						<?php echo esc_html__( 'After Posts', 'templatic-admin' ); ?>
						</option>
						<option value="1" <?php
						if ( 1 == $instance['pagination_position'] ) { ?>
							selected='selected'
						<?php } ?>>
						<?php echo esc_html__( 'Before Posts', 'templatic-admin' ); ?>
						</option>
					</select>
				</label>
			</p>
			<script type="text/javascript">
				function show_hide_info(value,p_id)
				{
					if ( "views" == value || "dailyviews" == value )
					{
						document.getElementById(p_id).style.display="block";
					}else
					{
						document.getElementById(p_id).style.display="none";
					}
				}
			</script>
			<?php
		}
	}
} // End if().

define( 'NUMBER_REVIEWS_TEXT', esc_html__( 'Number of Reviews', 'templatic-admin' ) );
if ( ! class_exists( 'supreme_recent_review' ) ) {
	/**
	 * Recent Reviews Widget Class.
	 **/
	class supreme_recent_review extends WP_Widget {
		/**
		 * Set up the widget's unique name, ID, class, description, and other options.
		 */
		function __construct() {
			$widget_ops = array(
							'classname'  	=> 'widget-twocolumn recent_reviews',
							'description'	=> esc_html__( 'Display recent reviews from a specific post type. Works best in sidebar areas.', 'templatic-admin' ),
						);
			parent::__construct( 'widget_comment', esc_html__( 'T &rarr; Recent Reviews', 'templatic-admin' ), $widget_ops );
		}
		/**
		 * Outputs the widget based on the arguments input through the widget controls.
		 *
		 * @param array $args 		agurment of widget area.
		 * @param array $instance 	instances of widget.
		 */
		function widget( $args, $instance ) {
			extract( $args, EXTR_SKIP );
			$title = empty( $instance['title'] ) ? 'Recent Reviews' : apply_filters( 'widget_title', $instance['title'] );
			$post_type = empty( $instance['post_type'] ) ? '' : apply_filters( 'widget_post_type', $instance['post_type'] );
			$count = empty( $instance['count'] ) ? '5' : apply_filters( 'widget_count', $instance['count'] );
			global $wpdb, $tablecomments, $tableposts,$rating_table_name;
			$tablecomments = $wpdb->comments;
			$tableposts    = $wpdb->posts;

			if ( post_type_exists( $post_type ) ) {
				$post_type = $post_type;
			} else {
				$post_type = 'post';
			}
			$reviewargs = array(
				'status' => 'approve',
				'karma' => '',
				'number' => $no_comments,
				'offset' => '',
				'orderby' => 'comment_date',
				'order' => 'DESC',
				'post_type' => $post_type,
				);
			$location_post_type = get_option( 'location_post_type' );
			if ( is_array( $location_post_type ) && ! empty( $location_post_type ) ) {
				foreach ( $location_post_type as $location_post_types ) {
					$post_types = explode( ',', $location_post_types );
					$post_type1[] = $post_types[0];
				}
			}

			/* filter for current city wise populer posts */
			if ( is_plugin_active( 'Tevolution-LocationManager/location-manager.php' ) && in_array( $post_type, $post_type1 ) ) {
				add_filter( 'comments_clauses','location_comments_clauses' );
			}
			$comments = get_comments( $reviewargs );
			/* remove filter for current city wise populer posts */
			if ( is_plugin_active( 'Tevolution-LocationManager/location-manager.php' ) && in_array( $post_type,$post_type1 ) ) {
				remove_filter( 'comments_clauses','location_comments_clauses' );
			}
			if ( $comments ) {
				if ( isset( $args['before_widget'] ) ) {
					echo wp_kses_post( $args['before_widget'] );
				}
				$unique_string = rand();
				echo '<div id="recent_review_widget_' . intval( $unique_string ) . '">';
				if ( function_exists( 'recent_review_comments' ) ) {
					/* Rest api plugin active than result fetch with ajax */
					{
						/* recebt reviews will come from this function */
						recent_review_comments( 30, $count, 18, false, $post_type, $title );
					} // End if().
				} // End if().
				echo '</div>';
				if ( isset( $args['after_widget'] ) ) {
					echo wp_kses_post( $args['after_widget'] );
				}
			} // End if().
		}
		/**
		 *
		 * Updates the widget control options for the particular instance of the widget.
		 *
		 * @param array $new_instance     new instance of widget when saved from widget area.
		 * @param array $old_instance 	  old instances of widget.
		 */
		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['post_type'] = strip_tags( $new_instance['post_type'] );
			$instance['count'] = strip_tags( $new_instance['count'] );
			return $instance;
		}
		/**
		 *
		 * Save the widget.
		 *
		 * @param array $instance     instance of widget.
		 */
		function form( $instance ) {
			$instance = wp_parse_args( (array) $instance, array(
															'title'    	=> 'Recent Reviews',
															'post_type'	=> 'post',
															'count'    	=> 5,
															)
			);
			$title     = strip_tags( $instance['title'] );
			$post_type = strip_tags( $instance['post_type'] );
			$count     = strip_tags( $instance['count'] );
			?>
			<p>
				<label for="<?php echo wp_kses_post( $this->get_field_id( 'title' ) ); ?>"> <?php echo esc_attr( TITLE_TEXT ); ?>:
					<input class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'title' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title); ?>" />
				</label>
			</p>
			<p>
				<label for="<?php echo wp_kses_post( $this->get_field_id( 'post_type' ) );?>" >
					<?php echo esc_html__( 'Select Post:', 'templatic-admin' );?>
					<select  id="<?php echo wp_kses_post( $this->get_field_id( 'post_type' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'post_type' ) ); ?>" class="widefat">
						<?php
						$all_post_types = get_post_types();
						foreach ( $all_post_types as $post_types ) {
							if ( 'page' != $post_types && 'attachment' != $post_types && 'revision' != $post_types && 'nav_menu_item' != $post_types && 'product' != $post_types && 'product_variation' != $post_types && 'shop_order' != $post_types && 'shop_coupon' != $post_types && 'admanager' != $post_types ) {
								?>
								<option value="<?php echo esc_attr( $post_types );?>" <?php
								if ( $post_types == $post_type ) {
									echo 'selected';
								}?>>
								<?php echo esc_attr( $post_types );?>
								</option>
									<?php
							}
						}
						?>
					</select>
				</label>
			</p>
			<p>
				<label for="<?php echo wp_kses_post( $this->get_field_id( 'count' ) ); ?>"> <?php echo esc_attr( NUMBER_REVIEWS_TEXT ); ?>:
					<input class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'count' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'count' ) ); ?>" type="text" value="<?php echo esc_attr( $count ); ?>" />
				</label>
			</p>
			<?php
		}
	}
		/**
		 * Function for getting recent comments.
		 *
		 * @param number $g_size 			Gravatar size.
		 * @param number $no_comments		Number of comment.
		 * @param number $comment_lenth		Length of comment.
		 * @param bool 	 $show_pass_post	Show password post.
		 * @param string $post_type 		Post tyoe to show comment.
		 * @param string $title 			Widget title.
		 */
	function recent_review_comments( $g_size = 30, $no_comments = 10, $comment_lenth = 60, $show_pass_post = false, $post_type = 'post', $title = '' ) {
		global $wpdb, $tablecomments, $tableposts,$rating_table_name;
		$tablecomments = $wpdb->comments;
		$tableposts    = $wpdb->posts;

		if ( post_type_exists( $post_type ) ) {
			$post_type = $post_type;
		} else {
			$post_type = 'post';
		}
		$args = array(
			'status' => 'approve',
			'karma' => '',
			'number' => $no_comments,
			'offset' => '',
			'orderby' => 'comment_date',
			'order' => 'DESC',
			'post_type' => $post_type,
			);
		$location_post_type = get_option( 'location_post_type' );
		if ( is_array( $location_post_type ) && ! empty( $location_post_type ) ) {
			foreach ( $location_post_type as $location_post_types) {
				$post_types = explode( ',', $location_post_types );
				$post_type1[] = $post_types[0];
			}
		}

		/* filter for current city wise populer posts */
		if ( is_plugin_active( 'Tevolution-LocationManager/location-manager.php' ) && in_array( $post_type,$post_type1 ) ) {
			add_filter( 'comments_clauses','location_comments_clauses' );
		}
		$comments = get_comments( $args );
		/* remove filter for current city wise populer posts */
		if ( is_plugin_active( 'Tevolution-LocationManager/location-manager.php' ) && in_array( $post_type,$post_type1 ) ) {
			remove_filter( 'comments_clauses','location_comments_clauses' );
		}
		if ( isset( $comments ) ) {
			if ( '' <> $title ) {
				echo wp_kses_post( ' <h3 class="widget-title">' . $title . '</h3>' );
			}
			echo '<ul class="recent_comments">';

			foreach ( $comments as $comment ) {
				$comment_id           = $comment->comment_ID;
				$comment_content      = strip_tags($comment->comment_content);		
				$comment_excerpt      = wp_trim_words( $comment_content, $comment_lenth, '' );	
				$comment_author_email = $comment->comment_author_email;
				$comment_post_ID      = $comment->comment_post_ID;
				$permalink            = get_permalink($comment_post_ID)."#comment-".$comment->comment_ID;
				$post_title           = stripslashes(get_the_title($comment_post_ID));
				$size=60;
				echo '<li class="clearfix"><span class=\'li' . intval( $comment_id ) . '\'>';
				if ( '' == @$comment->comment_type || 'null' == @$comment->comment_type ) {
					echo  '<a href="' . esc_url( $permalink ) . '">';
					if ( get_user_meta( $comment->user_id,'profile_photo',true ) ) {
						echo '<img class="avatar avatar-' . absint( $size ) . ' photo" width="' . absint( $size ) . '" height="' . absint( $size ) . '" src="' . get_user_meta( $comment->user_id, 'profile_photo', true ) . '" />';
					} else {
						echo wp_kses_post( get_avatar( $comment->comment_author_email, 60 ) );
					}

					echo '</a>';
				} elseif ( ( 'trackback' == $comment->comment_type ) || ( 'pingback' == $comment->comment_type ) ) {
					echo  '<a href="' . esc_url( $permalink ) . '">';
					if ( get_user_meta( $comment->user_id, 'profile_photo', true ) ) {
						echo '<img class="avatar avatar-' . absint( $size ) . ' photo" width="' . absint( $size ) . '" height="' . absint( $size ) . '" src="' . get_user_meta( $comment->user_id, 'profile_photo', true ) . '" />';
					} else {
						echo wp_kses_post( get_avatar( $comment->comment_author_email, 60 ) );
					}
					echo '</a>';
				}
				echo '</span>' . "\n";
				echo '<div class="review_info" >' ;
				echo  '<a href="' . esc_url( $permalink ) . '" class="title">' . esc_attr( $comment->comment_author ) . '</a>';
				$tmpdata = get_option( 'templatic_settings' );
				$rating_table_name = $wpdb->prefix . 'ratings';
				if ( 'yes' == $tmpdata['templatin_rating'] ) :
					$comments1 = $wpdb->get_var( $wpdb->prepare( "select group_concat(comment_ID) from $wpdb->comments where comment_post_ID=\"%d\" and comment_approved=1 and comment_parent=0", $comment_post_ID ) );
					if ( $comments1 ) {
						$avg_rating = $wpdb->get_var( $wpdb->prepare( "select avg(rating_rating) from $rating_table_name where comment_id in ( $comments1) and rating_rating > 0 and rating_postid = '%s'", $comment_post_ID ) );
					}
					$post_rating = ceil( $avg_rating );

					if ( function_exists( 'draw_rating_star_plugin' ) ) {
						echo wp_kses_post( '<div class="rating">' . apply_filters( 'tmpl_show_tevolution_rating', '', $post_rating ) . '</div>' );
					}
				endif;
				do_action( 'display_multi_rating', $comment_id );
				echo wp_kses_post( $comment_excerpt );
				if ( function_exists( 'supreme_prefix' ) ) {
					$pref = supreme_prefix();
				} else {
					$pref = sanitize_key( apply_filters( 'hybrid_prefix', get_template() ) );
				}
				$theme_options = get_option( $pref . '_theme_settings' );
				if ( isset( $theme_options['templatic_excerpt_link'] ) && '' != $theme_options['templatic_excerpt_link'] ) {
					$read_more = $theme_options['templatic_excerpt_link'];
				} else {
					$read_more = esc_html__( 'Read more &raquo;', 'templatic' );
				}
				$view_comment = esc_html__( 'View the entire comment', 'templatic' );
				echo '<a class=\'comment_excerpt\' href=\'' . esc_url( $permalink ) . '\' title=\'' . esc_attr( $view_comment ) . '\'>';
				echo '&nbsp;' . wp_kses_post( $read_more );
				echo '</a></div>';
				echo '</li>';
			} // End foreach().
			echo '</ul>';
		} // End if().
	}
} // End if().

if ( function_exists( 'is_plugin_active' ) ) {
	if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
		if ( ! class_exists( ' templatic_woo_shopping_cart_info' ) ) {
			/**
			 * Woocommerce Shopping Cart Widget Class.
			 **/
			class templatic_woo_shopping_cart_info extends WP_Widget {
				/**
				 * Woocommerce widget class.
				 *
				 * @var string @woo_widget_cssclass		widget class.
				 */
				var $woo_widget_cssclass;
				/**
				 * Woocommerce widget description.
				 *
				 * @var string @woo_widget_cssclass		widget description.
				 */
				var $woo_widget_description;
				/**
				 * Woocommerce widget id.
				 *
				 * @var string @woo_widget_idbase		widget id.
				 */
				var $woo_widget_idbase;
				/**
				 * Woocommerce widget name.
				 *
				 * @var string @woo_widget_name			widget name.
				 */
				var $woo_widget_name;
				/**
				 * Set up the widget's unique name, ID, class, description, and other options.
				 */
				function __construct() {
					/* Widget variable settings. */
					$this->woo_widget_cssclass = 'woocommerce widget_shopping_cart';
					$this->woo_widget_description = esc_html__( 'Display Cart Informations with automatic cart update. Best to use it in \'Header right\' sidebar', 'templatic-admin' );
					$this->woo_widget_idbase = 'woocommerce_widget_cart';
					$this->woo_widget_name = esc_html__( 'T &rarr; WooCommerce Shopping Cart', 'templatic-admin' );

					$widget_ops = array(
									'classname'  	=> 'widget WooCommerce shopping cart info',
									'description'	=> apply_filters( 'supreme_woo_shop_cart_description', esc_html__( 'Displays Cart Information with automatic cart update. Most suitable widget area for it is "Header right area"', 'templatic-admin' ) ),
					);
					parent::__construct( 'templatic_woo_shopping_cart_info', $this->woo_widget_name, $widget_ops );
				}
				/**
				 * Outputs the widget based on the arguments input through the widget controls.
				 *
				 * @param array $args 		agurment of widget area.
				 * @param array $instance 	instances of widget.
				 */
				function widget( $args, $instance ) {
					global $woocommerce;
					extract( $args, EXTR_SKIP );

					if ( '' == $before_title || '' == $after_title ) {
						$before_title == $args['before_title'] . '<span>';
						$after_title == $args['after_title'] . '</span>';
					}
					?>
					<div class="widget templatic_shooping  widget_shopping_cart">
						<div  id="woo_shoppingcart_box" class="cart_items shoppingcart_box shoppingcart_box_bg" onclick="show_hide_cart_items();" style="cursor:pointer;">
							<?php
							if ( empty( $title ) ) {
								echo wp_kses_post( $before_title . 'Shopping Cart' . $after_title );
							} else {
								echo wp_kses_post( $before_title . $title . $after_title );
							}; ?>
							<div id="wocommerce_button">
								<p class="total"> <strong>
									<?php esc_html_e( 'Subtotal', 'templatic' ); ?>
									: </strong> <?php echo wp_kses_post( $woocommerce->cart->get_cart_subtotal() ); ?> </p>
									<p  class="buttons"> <a href="<?php echo esc_url( $woocommerce->cart->get_cart_url() ); ?>" class="button wc-forward">
										<?php esc_html_e( 'View Cart', 'templatic' ); ?>
									</a> <a href="<?php echo esc_url( $woocommerce->cart->get_checkout_url() ); ?>" class="button checkout wc-forward">
									<?php esc_html_e( 'Checkout', 'templatic' ); ?>
								</a> </p>
							</div>
						</div>
						<div id="woo_shopping_cart" style="display:none">
							<div class="widget_shopping_cart_content">
								<?php
								echo '<ul class="cart_list product_list_widget ';
								if ( $hide_if_empty ) {
									echo 'hide_cart_widget_if_empty';
								}
								echo '">';
								if ( count( $woocommerce->cart->get_cart() ) > 0 ) {
									foreach ( $woocommerce->cart->get_cart() as $cart_item_key => $cart_item ) {
										$_product = $cart_item['data'];
										if ( $_product->exists() && $cart_item['quantity'] > 0 ) {
											echo wp_kses_post( '<li><a href="' . ecs_url( get_permalink( $cart_item['product_id'] ) ) . '">' );
											echo wp_kses_post( $_product->get_image() ) . '</a><a href="' . esc_url( get_permalink( $cart_item['product_id'] ) ) . '">';
											echo wp_kses_post( apply_filters( 'woocommerce_cart_widget_product_title', $_product->get_title(), $_product ) ) . '</a>';
											$product_price = get_option( 'woocommerce_display_cart_prices_excluding_tax' ) == 'yes' || $woocommerce->customer->is_vat_exempt() ? $_product->get_price_excluding_tax() : $_product->get_price();
											$product_price = apply_filters( 'woocommerce_cart_item_price_html', woocommerce_price( $product_price ), $cart_item, $cart_item_key );
											echo wp_kses_post( '<span class="quantity">' . $cart_item['quantity'] . ' &times; ' . $product_price . '</span></li>' );
										}
									}
								}
								echo '</ul>';
								echo '<div class="woo_checkout_btn"><p class=""><strong>' . esc_html__( 'Subtotal', 'templatic' ) . ':</strong> ' . wp_kses_post( $woocommerce->cart->get_cart_subtotal() ) . '</p>';
								do_action( 'woocommerce_widget_shopping_cart_before_buttons' );
								echo '<div class="buttons"><a href="' . esc_url( $woocommerce->cart->get_cart_url() ) . '" class="button">' . esc_html__( 'Checkout &rarr;', 'templatic' ) . '</a></div></div>';
								?>
							</div>
						</div>
						<script type="text/javascript">
							function show_hide_cart_items()
							{
								var dis = document.getElementById( 'woo_mob_shopping_cart' ).style.display;
								if (dis == 'none' )
								{
									jQuery("#wocommerce_button").css( 'display','none' );
									jQuery("#woo_mob_shopping_cart").animate(
									{
										height:'toggle'
									});
								}else
								{
									jQuery("#woo_mob_shopping_cart").animate(
									{
										height:'toggle'
									});
									jQuery("#wocommerce_button").css( 'display','block' );
								}
							}
						</script>
					</div>
					<?php
				}
				/**
				 *
				 * Updates the widget control options for the particular instance of the widget.
				 *
				 * @param array $new_instance     new instance of widget when saved from widget area.
				 * @param array $old_instance 	  old instances of widget.
				 */
				function update( $new_instance, $old_instance ) {
					$instance = $old_instance;
					return $instance;
				}
				/**
				 *
				 * Save the widget.
				 *
				 * @param array $instance     instance of widget.
				 */
				function form( $instance ) {

					$instance = wp_parse_args( (array) $instance, array(
																	'' => ' ',
																	)
					);
				}
			}
			register_widget( 'templatic_woo_shopping_cart_info' );
		} // End if().
	} // End if().
} // End if().

/**
 * Listing Sidebar Products Widget Class.
 **/
class Listing_Sidebar_Products_Widget extends WP_Widget {

	/**
	 * Set Default vareaible.
	 *
	 * @var array $defaults 	Default value.
	 */
	private $defaults = array(
							'title'           => '',
							'subtitle'        => '',
		);
	/**
	 * Set up the widget's unique name, ID, class, description, and other options.
	 */
	function __construct() {

		parent::__construct( 'listing_sidebar_products', ' ' . esc_html__( 'T &rarr; Listing', 'templatic' ) . '  ' . esc_html__( 'Products', 'templatic' ), array(
								'description' => esc_html__( 'The products linked to the current listing.', 'templatic' ),
								)
		);
	}
	/**
	 * Outputs the widget based on the arguments input through the widget controls.
	 *
	 * @param array $args 		agurment of widget area.
	 * @param array $instance 	instances of widget.
	 */
	public function widget( $args, $instance ) {

		$placeholders 	 = $this->get_placeholder_strings();
		$title           = apply_filters( 'widget_sidebar_title', ( empty( $instance ) || ! isset( $instance['title'] ) ) ? $placeholders['title'] : $instance['title'], $instance, $this->id_base );
		$subtitle        = empty( $instance ) ? $placeholders['subtitle'] : $instance['subtitle'];

		$products_ids = templatic_get_linked_products();

		if ( ! empty( $products_ids ) ) :

			$query_args = apply_filters( 'woocommerce_related_products_args', array(
				'post_type'            => 'product',
				'ignore_sticky_posts'  => 1,
				'no_found_rows'        => 1,
				'posts_per_page'       => -1,
				'post__in'             => $products_ids,
				)
			);

			$products = new WP_Query( $query_args );

			echo wp_kses_post( $args['before_widget'] ); ?>

		<h3 class="widget_sidebar_title">
			<?php
			echo wp_kses_post( $title );

			if ( ! empty( $subtitle ) ) { ?>
				<span class="widget_subtitle">
					<?php echo wp_kses_post( $subtitle ); ?>
				</span>
			<?php } ?>
		</h3>

		<?php
		if ( $products->have_posts() ) : ?>

		<div class="listing-products__items">

			<?php
			while ( $products->have_posts() ) : $products->the_post();

				wc_get_template_part( 'content', 'single-product-listing-sidebar' );

			endwhile;

			wp_reset_postdata(); ?>

		</div><!-- .listing-products__items -->

		<?php
		endif;

		echo wp_kses_post( $args['after_widget'] );
		add_action( 'wp_footer', 'tmpl_remove_woocommerce_booking_class' );
		endif;
	}

	/**
	 *
	 * Save the widget.
	 *
	 * @param array $instance     instance of widget.
	 */
	public function form( $instance ) {
		$original_instance = $instance;

		$instance = wp_parse_args(
			(array) $instance,
			$this->defaults
		);

		$placeholders = $this->get_placeholder_strings();

		$title = esc_attr( $instance['title'] );
		if ( empty( $original_instance ) && empty( $title ) ) {
			$title = $placeholders['title'];
		}

		$subtitle = esc_attr( $instance['subtitle'] );
		if ( empty( $original_instance ) && empty( $subtitle ) ) {
			$subtitle = $placeholders['subtitle'];
		} ?>

		<p>
			<label for="<?php echo wp_kses_post( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'templatic' ); ?></label>
			<input class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'title' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo wp_kses_post( $title ); ?>" placeholder="<?php echo esc_attr( $placeholders['title'] ); ?>"/>
		</p>

		<p>
			<label for="<?php echo wp_kses_post( $this->get_field_id( 'subtitle' ) ); ?>"><?php esc_html_e( 'Subtitle:', 'templatic' ); ?></label>
			<input class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'subtitle' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'subtitle' ) ); ?>" type="text" value="<?php echo wp_kses_post( $subtitle ); ?>" />
		</p>

		<p>
			<?php echo wp_kses_post( $this->widget_options['description'] ); ?>
		</p>
<?php }

	/**
	 *
	 * Updates the widget control options for the particular instance of the widget.
	 *
	 * @param array $new_instance     new instance of widget when saved from widget area.
	 * @param array $old_instance 	  old instances of widget.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance                    = $old_instance;
		$instance['title']           = strip_tags( $new_instance['title'] );
		$instance['subtitle']        = strip_tags( $new_instance['subtitle'] );

		return $instance;
	}

	/**
	 *
	 * Listing Products Placeholder.
	 */
	private function get_placeholder_strings() {
		$placeholders = apply_filters( 'listing_sidebar_products_backend_placeholders', array() );

		$placeholders = wp_parse_args(
			(array) $placeholders,
			array(
				'title'    => esc_html__( 'Make an Online Reservation', 'templatic' ),
				'subtitle' => '',
				)
		);

		return $placeholders;
	}

} // Class Listing_Sidebar_Products_Widget.
