<?php
/**
 * Template part for displaying links posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Directory
 */

?>

<div class="entry-header">
	<h2 class="entry-title"><a href="<?php esc_url( the_permalink() ); ?>" title="<?php echo esc_attr( sprintf( esc_html__( 'Permalink to %s', 'templatic' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark">
		<?php the_title(); ?>
	</a></h2>
	<?php echo apply_filters( 'supreme_content_format_post_info_', supreme_content_format_post_info() ); ?>
</div>
