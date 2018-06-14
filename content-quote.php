<?php
/**
 * Template part for displaying quotes posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Directory
 */

?>

<div class="entry-header"> <?php echo apply_filters( 'supreme_content_format_post_info_', supreme_content_format_post_info() ); ?> </div>
<div class="entry-summary">
	<?php the_content(); ?>
</div>
<!-- .entry-summary -->
