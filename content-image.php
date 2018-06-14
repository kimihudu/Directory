<?php
/**
 * Template part for displaying image posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Directory
 */

?>

<?php
	/* get the image code - show image if Display imege option is enable from backend - Start */
	global $post;
?>
<a class="image-list" href="<?php echo esc_url( get_permalink() ); ?>" title="<?php the_title_attribute( 'echo=1' ); ?>" rel="bookmark" class="featured-image-link">
<?php
if ( function_exists( 'the_post_format_image' ) ) {
	the_post_format_image( 'medium' ); // wordpress 3.6.
} else {
	$feat_image = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) );
	if ( $feat_image ) {
		echo '<a href="' . esc_url( get_permalink( $post->ID ) ) . '" title="' . esc_attr( $post->post_title ) . '">';
		?>
<img src="<?php echo esc_url( $feat_image ); ?>" alt="<?php echo esc_attr( $post->post_title );?>" />
<?php
			echo '</a>';
	} else {
		$arr_images =& get_children( 'order=ASC&orderby=menu_order ID&post_type=attachment&post_mime_type=image&post_parent=' . $post->ID );
		if ( $arr_images ) {
			foreach ( $arr_images as $key => $val ) {
				$id = $val->ID;
				$img_arr = wp_get_attachment_image_src( $id, 'thumbnail' ); // Get the thumbnail url for the attachment.
				$return_arr[] = $img_arr[0];

			}
			echo '<img src="' . esc_url( $return_arr[0] ) . '"  />';
		}
	}
}
?>
</a>
<h2 class="entry-title"><a href="<?php esc_url( the_permalink() ); ?>" title="<?php echo esc_attr( sprintf( esc_html__( 'Permalink to %s', 'templatic' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark">
<?php the_title(); ?>
</a></h2>
