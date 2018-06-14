<?php
/**
 * Template part for displaying gallery posts
 *
 * @package WordPress
 * @subpackage Directory
 */

?>

<h2 class="entry-title"> <a href="<?php esc_url( the_permalink() ); ?>" title="<?php echo esc_attr( sprintf( esc_html__( 'Permalink to %s', 'templatic' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark">
	<?php the_title(); ?>
</a> </h2>
<?php
do_action( 'supreme-post-info' );
if ( function_exists( 'the_post_format_gallery' ) ) {
	the_post_format_gallery(); // wordpre 3.6 compatibility.
} else {
	the_content();
}
?>
