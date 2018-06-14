<?php
/**
 * 404 Template
 *
 * The 404 template is used when a reader visits an invalid URL on your site. By default, the template will
 *
 * @package WordPress
 * @subpackage Directory
 */

@header( 'HTTP/1.1 404 Not found', true, 404 );
add_filter( 'body_class', 'directory_404_page_class' );
/**
 * Added class for single layout
 *
 * @param array $class Classes for the body element.
 */
function directory_404_page_class( $class ) {
	$class[] = 'layout-1c';
	return $class;
}
get_header(); /* Loads the header.php template. */
global $post;
$single_post = $post;
do_action( 'directory_before_container_breadcrumb' ); ?>
<section id="content" class="error_404 large-9 small-12 columns">
	<?php do_action( 'open_content' ); ?>
	<div class="hfeed">
		<div id="post-0" >
			<div class="wrap404 clearfix">
				<div class="desc404">
					<h4><?php esc_html_e( 'Sorry, The page you\'re looking for cannot be found!', 'templatic' ); ?></h4>
					<p><?php esc_html_e( 'I can help you find the page you want to see, just help me with a few clicks please.', 'templatic' ); ?></p>
					<p><?php  	esc_html_e( 'I recommend you ', 'templatic' );
						echo '<a href=' . esc_url( home_url() ) . ' title="Home">';
						esc_html_e( 'go to home', 'templatic' );
						echo '</a>';
						esc_html_e( ' page or simply search what you want to see below', 'templatic' );
						?></p>
					</div>
					<div class="search404"><?php get_search_form(); // Loads the searchform.php template. ?></div>
				</div>
				<section class="entry-content">

				</section>
				<?php
				$supreme_theme_tettings_options = get_option( supreme_prefix() . '_theme_settings' );
				$get_all_post_types = explode( ',', @$supreme_theme_tettings_options['post_type_label'] );
				foreach ( $get_all_post_types as $post_type ) :
					if ( 'page' != $post_type && 'attachment' != $post_type && 'revision' != $post_type && 'nav_menu_item' != $post_type ) :
						$taxonomies = get_object_taxonomies( (object) array(
																			'post_type' => $post_type,
																			'public' => true,
																			'_builtin' => true,
																			)
						);
						$archive_query = new WP_Query( 'showposts=60&post_type=' . $post_type );
						if ( count( @$archive_query->posts ) > 0 ) {
							$post_type_object = get_post_type_object( $post_type );
							$post_type_name = @$post_type_object->labels->name;
						}
						$wp_list_custom_categories = wp_list_categories( 'title_li=&hierarchical=0&show_count=0&echo=0&taxonomy=' . $taxonomies[0] );
						if ( ( $wp_list_custom_categories ) && 'No categories' != $wp_list_custom_categories && '<li>No categories</li>' !== $wp_list_custom_categories ) {
							?>
							<div class="arclist">
								<div class="title-container">
									<h2 class="title_green"><span><?php echo esc_attr( ucfirst( $post_type_name ) );
																		esc_html_e( ' Categories', 'templatic' ); ?></span></h2>
								</div>
								<ul>
									<?php echo esc_html( $wp_list_custom_categories ); ?>
								</ul>
							</div>
							<?php }
							endif;
							endforeach;?>
						</div>
						<!-- .hentry -->
					</div>
					<!-- .hfeed -->
					<?php $post = $single_post;?>
				</section>
				<!-- #content -->
				<?php get_footer(); /* Loads the footer.php template.*/ ?>
