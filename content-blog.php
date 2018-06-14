<?php
/**
 * Template part for displaying posts
 *
 * @package WordPress
 * @subpackage Directory
 */

?>

<?php // supreme_open_entry.
	$post_type = get_post_type( $post->ID );

	do_action( 'open_entry' . $post_type );

	$featured = get_post_meta( get_the_ID(), 'featured_c' , true );
		$featured = ( 'c' == $featured ) ? 'featured_c' : '';

if ( isset( $_REQUEST['sort'] ) && 'favourites' == $_REQUEST['sort'] ) {
	$post_type_tag = $post->post_type;
} else {
	$post_type_tag = '';
}

if ( is_sticky() && is_home() && ! is_paged() ) : ?>
	<div class="featured-post">
		<?php esc_html_e( 'Featured post', 'templatic' ); ?>
	</div>
	<?php
endif;

	/* get the image code - show image if Display imege option is enable from backend - Start */
	$theme_options = get_option( supreme_prefix() . '_theme_settings' );
	$supreme_display_image = $theme_options['supreme_display_image'];

	do_action( 'supreme_before-image' . $post_type );
	$image = get_the_image( array(
								 'echo' => false,''
								)
	);



	if ( $image && has_post_thumbnail() ) : ?>
	<div class="listing_img">
		<?php if ( $featured ) {
			echo '<span class="featured_tag">';
			esc_html_e( 'Featured', 'templatic' );
			echo '</span>';
} ?>
		<a href="<?php echo esc_url( get_permalink() ); ?>" title="<?php the_title_attribute( 'echo=1' ); ?>" rel="bookmark" class="featured-image-link"><img src="<?php
		get_the_image( array(
							'size' => 'thumb',
							'link_to_post' => false,
						)
		); ?>"/></a>
	</div>
	<?php
	else :
		$post_image = '';
		if ( function_exists( 'directory_bdw_get_images_plugin' ) ) {
			/* if mobile view is enable then show mobile thumbnail */
			if ( function_exists( 'tmpl_wp_is_mobile' ) && tmpl_wp_is_mobile() ) {
				$post_img = directory_bdw_get_images_plugin( $post->ID, 'mobile-thumbnail' );
			} else {
				$post_img = directory_bdw_get_images_plugin( $post->ID, 'thumb' );
			}

			$post_image = @$post_img[0]['file'];
		}

		if ( ! $post_image ) {
			$theme_options = get_option( supreme_prefix() . '_theme_settings' );
			$supreme_display_noimage = $theme_options['supreme_display_noimage'];
			if ( $supreme_display_noimage ) {

				$post_image = apply_filters( 'supreme_noimage-url', get_template_directory_uri() . '/images/noimage.jpg' );

			}
		}
		if ( is_home() || is_front_page() ) {
			$featured = get_post_meta( get_the_ID(), 'featured_h', true );
			$featured = ( 'h' == $featured ) ? 'featured_c' : '';
		} else {
			$featured = get_post_meta( get_the_ID(), 'featured_c', true );
			$featured = ( 'c' == $featured ) ? 'featured_c' : '';
		}

		if ( '' != $post_image ) {
		?>
		<div class="listing_img">
		<?php if ( $featured ) {
			echo '<span class="featured_tag">';
			esc_html_e( 'Featured', 'templatic' );
			echo '</span>';

}
		?>
			<a href="<?php echo esc_url( get_permalink() ); ?>" title="<?php the_title_attribute( 'echo=1' ); ?>" rel="bookmark" class="featured-image-link"><img src="<?php echo apply_filters( 'supreme_post_images', $post_image ); ?>" alt="<?php the_title_attribute( 'echo=1' ); ?>"/></a>
		</div>
		<?php
		}
	endif;

	do_action( 'supreme_after-image' . $post_type );

	/* get the image code - show image if Display image option is enable from backend - Start */
	?>
	<div class="entry-header">
		<?php do_action( 'supreme_before-title_' . $post_type );/* do action for display the single post title */
				do_action( 'templ_post_title' );
			do_action( 'tevolution_blog_title_text' );
		if ( ! is_author() ) {
			apply_filters( 'supreme-post-info', supreme_core_post_info( $post ) ); // do not show by line for blog post page for home page.
		} else {
			/* display different meta on author page */
			do_action( 'tmpl_author_meta' );
		}

			do_action( 'supreme_after-title_' . $post_type );

			do_action( 'tmpl-before-entry' . $post_type ); // Loads the sidebar-entry.
			$theme_options = get_option( supreme_prefix() . '_theme_settings' );
			$supreme_archive_display_excerpt = $theme_options['supreme_archive_display_excerpt'];
			$templatic_excerpt_link = $theme_options['templatic_excerpt_link'];

			/* to hide the excerpt and content from author page */
		if ( ! is_author() ) {
			if ( $supreme_archive_display_excerpt ) { ?>

				  <div class="entry-summary">
					<?php the_excerpt( $templatic_excerpt_link );  ?>
					<?php do_action( 'single_post_custom_fields' ); ?>
				  </div>
				  <!-- .entry-summary -->

			<?php } else {
				if ( is_tevolution_active() && tmpl_donot_display_description() ) { ?>
				  	<?php } else { ?>
				  <section class="entry-content">
					<?php
						the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'templatic' ) );
						wp_link_pages( array(
											'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'templatic' ),
											'after' => '</div>',
											)
						);
						do_action( 'single_post_custom_fields' ); ?>
				  </section>
				  <!-- .entry-content -->
			<?php	}
}

/* blog post categories */
$taxonomies = supreme_get_post_taxonomies( $post );
$cat_slug = $taxonomies [0];
$tag_slug = $taxonomies [1];
$theme_options = get_option( supreme_prefix() . '_theme_settings' );

if ( 'post' != get_post_type() ) {
	supreme_entry_meta();
}
		} else {
			echo do_action( 'listing_post_info' );
			do_action( 'templ_taxonomy_content' );
			do_action( 'directory_after_post_content' );
			/* Hook for before listing categories     */
			do_action( 'directory_before_taxonomies' );

			/* Display listing categories     */
			do_action( 'templ_the_taxonomies' );

			/* Hook to display the listing comments, add to favourite and pinpoint   */
			do_action( 'directory_after_taxonomies' );

			do_action( 'tmpl_before_entry_end' );
		} // End if().
		do_action( 'supreme_aftercontent' . $post_type );

		do_action( 'close_entry' . $post_type ); // supreme_close_entry. ?>
<!-- #post -->
</div>
