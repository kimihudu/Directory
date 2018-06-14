<?php
/**
 * Create the templatic facebook post widget
 *
 * @package WordPress
 * @subpackage Directory
 */

/**
 * Facebook Widget Class.
 */
class supreme_facebook extends WP_Widget {
	/**
	 * Set up the widget's unique name, ID, class, description, and other options.
	 */
	function __construct() {

		$widget_ops = array(
						'classname' 	=> 't_facebook_fans',
						'description' 	=> esc_html__( 'Display a like box for your Facebook page. Works best in sidebar areas.', 'templatic-admin' ),
					);
		parent::__construct( 'supreme_facebook', esc_html__( 'T &rarr; Facebook Like Box', 'templatic-admin' ), $widget_ops );
	}
	/**
	 * Outputs the widget based on the arguments input through the widget controls.
	 *
	 * @param array $args 		agurment of widget area.
	 * @param array $instance 	instances of widget.
	 */
	function widget( $args, $instance ) {

		extract( $args, EXTR_SKIP );
		echo wp_kses_post( $before_widget );
		$facebook_page_url = empty( $instance['facebook_page_url'] ) ? 'http://facebook.com/templatic' : apply_filters( 'widget_facebook_page_url', $instance['facebook_page_url'] );
		$width = empty( $instance['width'] ) ? 300 : apply_filters( 'widget_width', $instance['width'] );
		$show_faces = empty( $instance['show_faces'] ) ? 0 : apply_filters( 'widget_show_faces', $instance['show_faces'] );
		$show_stream = empty( $instance['show_stream'] ) ? 0 : apply_filters( 'widget_show_stream', $instance['show_stream'] );
		$show_header = empty( $instance['show_header'] ) ? 0 : apply_filters( 'widget_show_header', $instance['show_header'] );

		$face = ( 1 == $show_faces ) ? 'true':'false';
		$stream = ( 1 == $show_stream ) ? 'true':'false';
		$header = ( 1 == $show_header ) ? 'true':'false';

		?>
		<div id="fb-root"></div>
		<script src="//connect.facebook.net/en_US/all.js#xfbml=1"></script>
		<fb:like-box href="<?php echo esc_url( $facebook_page_url ); ?>" width="<?php echo intval( $width ); ?>" show_faces="<?php echo wp_kses_post( $face ); ?>" border_color="" stream="<?php echo wp_kses_post( $stream ); ?>" header="<?php echo wp_kses_post( $header ); ?>"></fb:like-box>
		<?php
		echo wp_kses_post( $after_widget );
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
														'width'				=> 300,
														'facebook_page_url'	=> 'http://facebook.com/templatic',
														'show_faces'		=> 1,
														'show_stream' 		=> 1,
														'show_header' 		=> 1,
													)
		);
		$facebook_page_url = strip_tags( $instance['facebook_page_url'] );
		$width = strip_tags( $instance['width'] );
		$show_faces = strip_tags( $instance['show_faces'] );
		$show_stream = strip_tags( $instance['show_stream'] );
		$show_header = strip_tags( $instance['show_header'] );

		?>
		<script type="text/javascript">
			function show_facebook_header(str,id){
				var value=jQuery( '#'+id).val();
				if (str.value==1 || value==1){
					jQuery( 'p#facebook_show_header').fadeIn( 'slow');
				} else {
					jQuery( 'p#facebook_show_header').fadeOut("slow");
				}
			}
		</script>
		<p>
			<label for="<?php echo wp_kses_post( $this->get_field_id( 'facebook_page_url' ) ); ?>">
				<?php  echo esc_html__( 'Facebook Page Full URL', 'templatic-admin' ); ?>
				:
				<input class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'facebook_page_url' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'facebook_page_url' ) ); ?>" type="text" value="<?php echo esc_attr( $facebook_page_url ); ?>" />
			</label>
		</p>
		<p>
			<label for="<?php echo wp_kses_post( $this->get_field_id( 'width' ) ); ?>">
				<?php  echo esc_html__( 'Width', 'templatic-admin' )?>
				:
				<input class="widefat" id="<?php echo wp_kses_post( $this->get_field_id( 'width' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'width' ) ); ?>" type="text" value="<?php echo esc_attr( $width ); ?>" />
			</label>
		</p>
		<p>
			<label for="<?php echo wp_kses_post( $this->get_field_id( 'show_faces' ) ); ?>">
				<?php  echo esc_html__( 'Show Faces', 'templatic-admin' )?>
				:
				<select id="<?php echo wp_kses_post( $this->get_field_id( 'show_faces' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'show_faces' ) ); ?>" style="width:50%;" onchange="show_facebook_header(this,'<?php echo wp_kses_post( $this->get_field_id( 'show_stream' ) ); ?>');">
					<option value="1" <?php if ( '1' == esc_attr( $show_faces ) ) {
						echo 'selected="selected"'; }?>>
					<?php echo esc_html__( 'Yes', 'templatic-admin' ); ?>
					</option>
					<option value="0" <?php if ( '0' == esc_attr( $show_faces ) ) {
						echo 'selected="selected"';
}?>>
					<?php echo esc_html__( 'No', 'templatic-admin' ); ?>
					</option>
				</select>
			</label>
		</p>
		<p>
			<label for="<?php echo wp_kses_post( $this->get_field_id( 'show_stream' ) ); ?>">
				<?php  echo esc_html__( 'Show Stream', 'templatic-admin' )?>
				:
				<select id="<?php echo wp_kses_post( $this->get_field_id( 'show_stream' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'show_stream' ) ); ?>" style="width:50%;" onchange="show_facebook_header(this,'<?php echo wp_kses_post( $this->get_field_id( 'show_faces' ) ); ?>');">
					<option value="1" <?php if ( '1' == esc_attr( $show_stream ) ) {
						echo 'selected="selected"';}
					?>>
					<?php echo esc_html__( 'Yes', 'templatic-admin' ); ?>
					</option>
					<option value="0" <?php if ( '0' == esc_attr( $show_stream ) ) {
						echo 'selected="selected"'; }?>>
						<?php echo esc_html__( 'No', 'templatic-admin' ); ?>
					</option>
				</select>
			</label>
		</p>
		<p id="facebook_show_header" <?php if ( '1' == esc_attr( $show_stream ) || '1' == esc_attr( $show_faces ) ) {
				echo "style='display:block;'"; } else {
				echo "style='display:none;'"; }?>>
			<label for="<?php echo wp_kses_post( $this->get_field_id( 'show_header' ) ); ?>">
				<?php  echo esc_html__( 'Show Header', 'templatic-admin' ); ?>
				:
				<select id="<?php echo wp_kses_post( $this->get_field_id( 'show_header' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'show_header' ) ); ?>" style="width:50%;">
					<option value="1" <?php if ( '1' == esc_attr( $show_header ) ) {
							echo 'selected="selected"';}?>>
						<?php echo esc_html__( 'Yes', 'templatic-admin' ); ?>
					</option>
					<option value="0" <?php if ( '0' == esc_attr( $show_header ) ) {
						echo 'selected="selected"'; }?>>
						<?php echo esc_html__( 'No', 'templatic-admin' ); ?>
					</option>
				</select>
			</label>
		</p>
		<?php
	}

}
/**
 * Templatic templatic facebook widget init.
 */
register_widget( 'supreme_facebook' );
