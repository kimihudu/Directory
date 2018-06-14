<?php
/**
 * Include theme related functions and filters.
 *
 * @package WordPress
 * @subpackage Directory
 */

add_action( 'before_main', 'directory_theme_before_main' );
/**
 * Display Goggle map full widget on list and event listing page.
 */
function directory_theme_before_main() {
	global $post;
	if ( ! is_single() && ! is_author() && ! is_home() ) {
		$tmpdata = get_option( 'templatic_settings' );
		if ( isset( $tmpdata['google_map_full_width'] ) && 'yes' == $tmpdata['google_map_full_width'] ) {
			remove_action( 'after_ecategory_header', 'tmpl_after_ecategory_header' );
			remove_action( 'after_directory_header', 'after_directory_header' );
			if ( function_exists( 'tevolution_get_post_type' ) ) {
				$custom_post_type = apply_filters( 'directory_post_type_template', tevolution_get_post_type() );
			}

			$taxonomies = get_object_taxonomies( get_post_type() );
			$texonomy_name = $taxonomies[0];
			if ( is_array( @$custom_post_type ) && in_array( get_post_type(), @$custom_post_type ) ) {
				if ( is_active_sidebar( 'after_directory_header' ) && ( 'listing' == get_post_type() ) ) :
					?>
				<div id="category-widget" class="category-widget">
					<?php dynamic_sidebar( 'after_directory_header' ); ?>
				</div>
			<?php else : ?>
				<div id="category-widget" class="category-widget">
					<?php dynamic_sidebar( 'after_' . $texonomy_name . '_header' ); ?>
				</div>
				<?php
				endif;
			}
		}
	}
}

add_filter( 'post_class', 'featured_post_class' );

/**
 * Add class to featured post.
 *
 * @param string $class 		Class.
 */
function featured_post_class( $class ) {
	global $post;
	if ( is_author() ) {
		$featured = get_post_meta( $post->ID, 'featured_c', true );
		$featured = ( 'c' == $featured ) ? 'featured_c' : '';
		$class[] = $featured;
	} else {
		if ( is_search() ) {
			$featured = get_post_meta( $post->ID, 'featured_c', true );
		}
		$featured = ( 'c' == @$featured ) ? 'featured_c' : '';
		$class[] = ' post ' . $featured;
	}

	return $class;
}

if ( ! function_exists( 'directory_bdw_get_images_plugin' ) ) {
	/**
	 * Resize image
	 *
	 * @param integer $i_post_id          Post id.
	 * @param string  $img_size         Image Size.
	 * @param string  $no_images        No image.
	 */
	function directory_bdw_get_images_plugin( $i_post_id, $img_size = 'thumb', $no_images = '' ) {
		$arr_images = get_children( 'order=ASC&orderby=menu_order ID&post_type=attachment&post_mime_type=image&post_parent=' . $i_post_id );
		$counter = 0;
		$return_arr = array();

		if ( has_post_thumbnail( $i_post_id ) && is_tax() ) {
			$img_arr = wp_get_attachment_image_src( get_post_thumbnail_id( $i_post_id ), 'thumbnail' );
			$imgarr['id'] = $id;
			$imgarr['file'] = $img_arr[0];
			$return_arr[] = $imgarr;
		} else {

			if ( $arr_images ) {
				foreach ( $arr_images as $key => $val ) {
					$id = $val->ID;
					if ( '' != $val->post_title ) {
						if ( 'thumb' == $img_size ) {
							$img_arr = wp_get_attachment_image_src( $id, 'thumbnail' ); // Get the thumbnail url for the attachment.
							$imgarr['id'] = $id;
							$imgarr['file'] = $img_arr[0];
							$return_arr[] = $imgarr;
						} else {
								$img_arr = wp_get_attachment_image_src( $id, $img_size );
								$imgarr['id'] = $id;
								$imgarr['file'] = $img_arr[0];
								$return_arr[] = $imgarr;
						}
					}
					$counter++;
					if ( '' != $no_images && $counter == $no_images ) {
						break;
					}
				}
			}
		}
		return $return_arr;
	}
} // End if().
add_action( 'templ_before_container_breadcrumb', 'breadcrumb_trail' );
/**
 * Conditional logic deciding the layout of certain pages.
 */
function supreme_layouts() {
	if ( current_theme_supports( 'theme-layouts' ) ) {
		$global_layout = supreme_get_settings( 'supreme_global_layout' );
		$woocommerce_layout = supreme_get_settings( 'supreme_woocommerce_layout' );
		$layout = theme_layouts_get_layout();
		if ( ! is_singular() && 'layout_default' != $global_layout && function_exists( "supreme_{$global_layout}" ) ) {
			add_filter( 'get_theme_layout', 'supreme_' . $global_layout );
		}
		if ( is_singular() && 'layout-default' == $layout && 'layout_default' !== $global_layout  && function_exists( "supreme_{$global_layout}" ) ) {
			add_filter( 'get_theme_layout', 'supreme_' . $global_layout );
		}
		if ( function_exists( 'bbp_loaded' ) ) {
			if ( is_bbpress() && ! is_singular() && 'layout_default' !== $bbpress_layout && function_exists( "supreme_{$bbpress_layout}" ) ) {
				add_filter( 'get_theme_layout', 'supreme_' . $bbpress_layout );
			} elseif ( is_bbpress() && is_singular() && 'layout-default' == $layout && 'layout_default' !== $bbpress_layout && function_exists( "supreme_{$bbpress_layout}" ) ) {
				add_filter( 'get_theme_layout', 'supreme_' . $bbpress_layout );
			}
		}
		remove_post_type_support( 'admanager', 'theme-layouts' );
		if ( function_exists( 'is_woocommerce' ) ) {
			if ( is_woocommerce() && ! is_singular() && 'layout_default' !== $woocommerce_layout && function_exists( "supreme_{$woocommerce_layout}" ) ) {
				add_filter( 'get_theme_layout', 'supreme_' . $woocommerce_layout );
			} elseif ( is_woocommerce() && is_singular() && 'layout-default' == $layout && 'layout_default' !== $woocommerce_layout && function_exists( "supreme_{$woocommerce_layout}" ) ) {
				add_filter( 'get_theme_layout', 'supreme_' . $woocommerce_layout );
			}
		}
	}
}

/**
 * Pagination start BOF
 * Function that performs a Boxed Style Numbered Pagination (also called Page Navigation).
 * Function is largely based on Version 2.4 of the WP-PageNavi plugin
 *
 * @param string $before 			Before pagination.
 * @param string $after 			After pagination.
 */
function directory_pagenavi_plugin( $before = '', $after = '' ) {
	global $wpdb, $wp_query, $paged;

	$pagenavi_options = array();

	$pagenavi_options['pages_text'] = '';
	$pagenavi_options['current_text'] = '%PAGE_NUMBER%';
	$pagenavi_options['page_text'] = '%PAGE_NUMBER%';
	$pagenavi_options['first_text'] = esc_html__( 'First Page', 'templatic' );
	$pagenavi_options['last_text'] = esc_html__( 'Last Page', 'templatic' );
	$pagenavi_options['next_text'] = '<strong class="next page-numbers">' . esc_html__( 'Next', 'templatic' ) . '<span class="meta-nav">&rarr;</span></strong>';
	$pagenavi_options['prev_text'] = '<strong class="prev page-numbers"><span class="meta-nav">&larr;</span> ' . esc_html__( 'Previous', 'templatic' ) . '</strong>';
	$pagenavi_options['dotright_text'] = '...';
	$pagenavi_options['dotleft_text'] = '...';
	$pagenavi_options['num_pages'] = 5; // Continuous block of page numbers.
	$pagenavi_options['always_show'] = 0;
	$pagenavi_options['num_larger_page_numbers'] = 0;
	$pagenavi_options['larger_page_numbers_multiple'] = 5;

	if ( ! is_single() ) {
		$request = $wp_query->request;
		$posts_per_page = intval( get_query_var( 'posts_per_page' ) );
		$paged = intval( get_query_var( 'paged' ) );
		$numposts = $wp_query->found_posts;
		$max_page = $wp_query->max_num_pages;

		if ( empty( $paged ) || 0 == $paged ) {
			$paged = 1;
		}

		$pages_to_show = intval( $pagenavi_options['num_pages'] );
		$larger_page_to_show = intval( $pagenavi_options['num_larger_page_numbers'] );
		$larger_page_multiple = intval( $pagenavi_options['larger_page_numbers_multiple'] );
		$pages_to_show_minus_1 = $pages_to_show - 1;
		$half_page_start = floor( $pages_to_show_minus_1 / 2 );
		$half_page_end = ceil( $pages_to_show_minus_1 / 2 );
		$start_page = $paged - $half_page_start;

		if ( $start_page <= 0 ) {
			$start_page = 1;
		}

		$end_page = $paged + $half_page_end;
		if ( ( $end_page - $start_page) != $pages_to_show_minus_1 ) {
			$end_page = $start_page + $pages_to_show_minus_1;
		}
		if ( $end_page > $max_page ) {
			$start_page = $max_page - $pages_to_show_minus_1;
			$end_page = $max_page;
		}
		if ( $start_page <= 0 ) {
			$start_page = 1;
		}

		$larger_per_page = $larger_page_to_show * $larger_page_multiple;

		$larger_start_page_start = ( templ_round_num( $start_page, 10 ) + $larger_page_multiple ) - $larger_per_page;
		$larger_start_page_end = templ_round_num( $start_page, 10 ) + $larger_page_multiple;
		$larger_end_page_start = templ_round_num( $end_page, 10 ) + $larger_page_multiple;
		$larger_end_page_end = templ_round_num( $end_page, 10 ) + ( $larger_per_page );

		if ( $larger_start_page_end - $larger_page_multiple == $start_page ) {
			$larger_start_page_start = $larger_start_page_start - $larger_page_multiple;
			$larger_start_page_end = $larger_start_page_end - $larger_page_multiple;
		}
		if ( $larger_start_page_start <= 0 ) {
			$larger_start_page_start = $larger_page_multiple;
		}
		if ( $larger_start_page_end > $max_page ) {
			$larger_start_page_end = $max_page;
		}
		if ( $larger_end_page_end > $max_page ) {
			$larger_end_page_end = $max_page;
		}
		if ( $max_page > 1 || 1 == intval( $pagenavi_options['always_show'] ) ) {
			$pages_text = str_replace( '%CURRENT_PAGE%', number_format_i18n( $paged ), @$pagenavi_options['pages_text'] );
			$pages_text = str_replace( '%TOTAL_PAGES%', number_format_i18n( $max_page ), $pages_text );
			previous_posts_link( $pagenavi_options['prev_text'] );

			if ( $start_page >= 2 && $pages_to_show < $max_page ) {
				$first_page_text = str_replace( '%TOTAL_PAGES%', number_format_i18n( $max_page ), $pagenavi_options['first_text'] );
				echo '<a href="' . esc_url( get_pagenum_link() ) . '" class="first page-numbers" title="' . wp_kses_post( $first_page_text ) . '">' . wp_kses_post( $first_page_text ) . '</a>';
				if ( ! empty( $pagenavi_options['dotleft_text'] ) ) {
					echo wp_kses_post( '<span class="expand page-numbers">' . $pagenavi_options['dotleft_text'] . '</span>' );
				}
			}

			if ( $larger_page_to_show > 0 && $larger_start_page_start > 0 && $larger_start_page_end <= $max_page ) {
				for ( $i = $larger_start_page_start; $i < $larger_start_page_end; $i += $larger_page_multiple ) {
					$page_text = str_replace( '%PAGE_NUMBER%', number_format_i18n( $i ), $pagenavi_options['page_text'] );
					echo wp_kses_post( '<a href="' . esc_url( get_pagenum_link( $i ) ) . '" class="page-numbers" title="' . wp_kses_post( $page_text ) . '">' . wp_kses_post( $page_text ) . '</a>' );
				}
			}

			for ( $i = $start_page; $i <= $end_page; $i++ ) {
				if ( $i == $paged ) {
					$current_page_text = str_replace( '%PAGE_NUMBER%', number_format_i18n( $i ), $pagenavi_options['current_text'] );
					echo '<a  class="current page-numbers">' . wp_kses_post( $current_page_text ) . '</a>';
				} else {
					$page_text = str_replace( '%PAGE_NUMBER%', number_format_i18n( $i ), $pagenavi_options['page_text'] );
					echo wp_kses_post( '<a href="' . esc_url( get_pagenum_link( $i ) ) . '" class="page-numbers" title="' . $page_text . '"><strong>' . $page_text . '</strong></a>' );
				}
			}

			if ( $end_page < $max_page ) {
				if ( ! empty( $pagenavi_options['dotright_text'] ) ) {
					echo '<span class="expand page-numbers">' . wp_kses_post( $pagenavi_options['dotright_text'] ) . '</span>';
				}
				$last_page_text = str_replace( '%TOTAL_PAGES%', number_format_i18n( $max_page ), $pagenavi_options['last_text'] );
				echo wp_kses_post( '<a class="last page-numbers" href="' . esc_url( get_pagenum_link( $max_page ) ) . '" title="' . $last_page_text . '">' . $last_page_text . '</a>' );
			}

			if ( $larger_page_to_show > 0 && $larger_end_page_start < $max_page ) {
				for ( $i = $larger_end_page_start; $i <= $larger_end_page_end; $i += $larger_page_multiple ) {
					$page_text = str_replace( '%PAGE_NUMBER%', number_format_i18n( $i ), $pagenavi_options['page_text'] );
					echo wp_kses_post( '<a href="' . esc_url( get_pagenum_link( $i ) ) . '" class="page-numbers" title="' . $page_text . '">' . $page_text . '</a>' );
				}
			}
			echo wp_kses_post( $after );
			next_posts_link( $pagenavi_options['next_text'], $max_page );
		} // End if().
	} // End if().
}

add_filter( 'get_search_form', 'search_form_for_404_display' );
/**
 * Search for filter for 404 page.
 *
 * @param html $searchform 			search form html.
 */
function search_form_for_404_display( $searchform ) {
	$searchform = '<div class="404_search">
	<form role="search" method="get" class="search-form" action="' . esc_url( home_url( '/' ) ) . '">
		<input type="text" size="100" placeholder="' . esc_html__( 'Looking for....', 'templatic' ) . '" class="searchpost " id="s" name="s" value="" autocomplete="off">
		<input type="submit" alt="" class="sgo" value="' . esc_html__( 'Search', 'templatic' ) . '" />
	</form></div>';
	return $searchform;
}

add_action( 'wp_head', 'remove_woocommerce_script' );
/**
 * Remove unnecessary woocommece js from listing and detail page.
 */
function remove_woocommerce_script() {
	if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
		global $post;
		if ( function_exists( 'is_woocommerce' ) && ! is_woocommerce() && ! is_checkout() && ! is_cart() ) {
			wp_deregister_script( 'jquery-cookie' );
			wp_deregister_script( 'wc-cart-fragments' );
			wp_deregister_script( 'wc-add-to-cart' );
			wp_deregister_script( 'jquery-placeholder' );
		}

		/* Remove the wp auto p tag add in woocommerce page */
		if ( get_option( 'woocommerce_cart_page_id' ) == $post->ID || get_option( 'woocommerce_checkout_page_id' ) == $post->ID || get_option( 'woocommerce_pay_page_id' ) == $post->ID || get_option( 'woocommerce_thanks_page_id' ) == $post->ID || get_option( 'woocommerce_myaccount_page_id' ) == $post->ID || get_option( 'woocommerce_edit_address_page_id' ) == $post->ID || get_option( 'woocommerce_view_order_page_id' ) == $post->ID || get_option( 'woocommerce_change_password_page_id' ) == $post->ID || get_option( 'woocommerce_logout_page_id' ) == $post->ID || get_option( 'woocommerce_lost_password_page_id' ) == $post->ID ) {
			remove_filter( 'the_content', 'wpautop', 12 );
		}
	}
}

add_action( 'tevolution_add_custom_post_field', 'directory_add_custom_post_field' );
/**
 * Add google map marker option in add/edit taxonomy page on backend.
 *
 * @param array $edit_post 			Post Array.
 */
function directory_add_custom_post_field( $edit_post ) {
	global $wpdb;
	$tevolution_taxonomy_marker = get_option( 'tevolution_taxonomy_marker' );
	$taxonomy = @$edit_post[ wp_kses_post( @$_REQUEST['post-type'] ) ]['slugs'][0];

	/* directory plug in and event plug in taxonomy available then return marker custom field */
	?>
	<tr>
		<th valign="top"><label for="marker" class="form-textarea-label"><?php echo esc_html__( 'Category map markers', 'templatic-admin' ); ?></label></th>
		<td>
			<div class="input-switch">
				<input type="checkbox" id="taxonomy_marker" name="taxonomy_marker" value="enable" <?php if ( '' != $taxonomy && 'enable' == $tevolution_taxonomy_marker[ $taxonomy ] ) {
					echo 'checked';} ?> />&nbsp;<label for="taxonomy_marker"><?php echo esc_html__( 'Enable', 'templatic-admin' ); ?></label>
			</div>
			<p class="description"><?php echo esc_html__( 'Enabling this will allow you to assign a custom icon(marker) for categories within this post type. The marker can be set while creating the category.', 'templatic-admin' ); ?></p>
		</td>
	</tr>
	<?php
	if ( isset( $_POST['submit-taxonomy'] ) && '' != $_POST['submit-taxonomy'] && isset( $_POST['taxonomy_marker'] ) && '' != $_POST['taxonomy_marker'] ) {

		$tevolution_taxonomy_marker = get_option( 'tevolution_taxonomy_marker' );
		if ( $tevolution_taxonomy_marker ) {
			$taxonomy_marker = array(
				$_POST['taxonomy_slug'] => wp_kses_post( wp_unslash( $_POST['taxonomy_marker'] ) ),
				);
			$tevolution_taxonomy_marker = array_merge( $tevolution_taxonomy_marker, $taxonomy_marker );
			update_option( 'tevolution_taxonomy_marker', $tevolution_taxonomy_marker );
		} else {
			$tevolution_taxonomy_marker = array(
											$_POST['taxonomy_slug'] => wp_kses_post( wp_unslash( $_POST['taxonomy_marker'] ) ),
										);
			update_option( 'tevolution_taxonomy_marker', $tevolution_taxonomy_marker );
		}
	} else {
		$tevolution_taxonomy_marker = get_option( 'tevolution_taxonomy_marker' );
		unset( $tevolution_taxonomy_marker[ @$_POST['taxonomy_slug'] ] );
		update_option( 'tevolution_taxonomy_marker', $tevolution_taxonomy_marker );
	}
}

/**
 * Return breadcrumb.
 */
function directory_theme_breadcrumb() {
	global $wpdb, $post;
	if ( current_theme_supports( 'breadcrumb-trail' ) && supreme_get_settings( 'supreme_show_breadcrumb' ) ) {
		breadcrumb_trail( array(
							'separator' => '&raquo;',
							)
		);
	}
}

add_filter( 'get_theme_layout', 'directory_custom_page_layout' );
/**
 * Return page layouts for pages, preview page and success page( comes with tevolution plugin ).
 *
 * @param string $global_layout 		Different layout.
 */
function directory_custom_page_layout( $global_layout ) {

	if ( isset( $_REQUEST['page'] ) && ( 'preview' == $_REQUEST['page'] || 'success' == $_REQUEST['page'] ) ) {
		$global_layout = supreme_plugin_layouts( $global_layout );
	}
	return $global_layout;
}

add_filter( 'get_avatar', 'directory_site_get_avatar', 10, 5 );
/**
 * Profile_photo user custom filed not blank then display user custom photo.
 *
 * @param html    $avatar 			User Profile Picture.
 * @param integer $id_or_email 		User id or email.
 * @param integer $size 			Size of avatar.
 * @param html    $default 			Default avatar.
 * @param string  $alt 				Alter tag for avatar.
 */
function directory_site_get_avatar( $avatar, $id_or_email, $size, $default, $alt ) {
	global $pagenow;
	if ( 'edit-comments.php' == $pagenow ) {
		return $avatar;
	}
	if ( is_object( $id_or_email ) && @$id_or_email->comment_author_email ) {
		$users = get_user_by( 'email', @$id_or_email->comment_author_email );
		if ( '' != @get_user_meta( $users->ID, 'profile_photo', true ) && @is_single() ) {
			$imgpath = get_user_meta( $users->ID, 'profile_photo', true );
			$avatar = '<img class="avatar avatar-' . $size . ' photo" src="' . $imgpath . '" alt="' . $alt . '" height="' . $size . '" width="' . $size . '" />';
		}
	} else {
		if ( '' != get_user_meta( $id_or_email, 'profile_photo', true ) ) {
			$imgpath = get_user_meta( $id_or_email, 'profile_photo', true );
			$avatar = '<img class="avatar avatar-' . $size . ' photo" src="' . $imgpath . '" alt="' . $alt . '" height="' . $size . '" width="' . $size . '" />';
		}
	}

	return $avatar;
}

/**
 * Display the language string for the number of comments the current post has.
 *
 * @param integer $zero 			Number of comment.
 * @param integer $one 				Number of comment.
 * @param string  $more 			More string in comment.
 * @param integer $deprecated 		Depcrecated or not.
 */
function templatic_comments_number( $zero = false, $one = false, $more = false, $deprecated = '' ) {

	$number = get_comments_number();

	if ( $number > 1 ) {
		$output = ( false === $more ) ? number_format_i18n( $number ) . ' ' . esc_html__( 'Comments', 'templatic' ) : number_format_i18n( $number ) . ' ' . $more;
	} elseif ( 0 == $number ) {
		$output = ( false === $zero ) ? esc_html__( 'No Comments', 'templatic' ) : $zero;
	} else {
		$output = ( false === $one ) ? esc_html__( '1 Comment', 'templatic' ) : $one;
	}
	echo wp_kses_post( apply_filters( 'comments_number', $output, $number ) );
}

if ( ! function_exists( 'supreme_entry_meta' ) ) :

	add_action( 'templ_the_taxonomies', 'supreme_entry_meta' );
	/**
	 * Prints HTML with meta information for current post: categories, tags, permalink, author, and date.
	 */
	function supreme_entry_meta() {

		$utility_text = '';
		$categories_list = get_the_category_list( ', ' );

		$tag_list = get_the_tag_list( '', ', ' );
		$date = sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a>', esc_url( get_permalink() ), esc_attr( get_the_time() ), esc_attr( get_the_date( 'c' ) ), esc_html( get_the_date() )
			);

		if ( $tag_list ) {
			echo '<div class="post_info_meta">';
			echo '<span>';
			esc_html_e( 'Posted in', 'templatic' );
			echo '</span> ';
			echo ' ' . wp_kses_post( $categories_list ) . ' ';
			echo '<span>';
			esc_html_e( 'and tagged', 'templatic' );
			echo '</span> ';
			echo ' ' . wp_kses_post( $tag_list ) . ' ';
			do_action( 'tmpl_archive_post_meta' );
			echo '</div>';
		}
	}

endif;
/* Add extra support for post types. */
add_action( 'init', 'supreme_add_post_type_support' );

/**
 * This function is for adding extra support for features not default to the core post types.
 * Excerpts are added to the 'page' post type.  Comments and trackbacks are added for the
 * 'attachment' post type.  Technically, these are already used for attachments in core, but
 * they're not registered.
 */
function supreme_add_post_type_support() {
	/* Add support for excerpts to the 'page' post type. */
	add_post_type_support( 'page', array( 'excerpt' ) );
	/* Add support for track backs to the 'attachment' post type. */
	add_post_type_support( 'attachment', array( 'trackbacks' ) );
}

/**
 * Generates the relevant template info.  Adds template meta with theme version.  Uses the theme
 * name and version from style.css.
 */
function supreme_meta_template() {
	$theme = wp_get_theme( get_template(), get_theme_root( get_template_directory() ) );

	$template = '<meta name="template" content="' . esc_attr( $theme->get( 'Name' ) . ' ' . $theme->get( 'Version' ) ) . '" />' . "\n";

	echo wp_kses_post( apply_atomic( 'meta_template', $template ) );
}

/**
 * Dynamic element to wrap the site title in.  If it is the front page, wrap it in an <h1> element.  One other
 * pages, wrap it in a <div> element.
 */
function supreme_site_title() {
	/* If viewing the front page of the site, use an <h1> tag.  Otherwise, use a <div> tag. */
	$tag = 'h1';
	/* Get the site title.  If it's not empty, wrap it with the appropriate HTML. */
	if ( get_bloginfo( 'name' ) == $title ) {
		if ( function_exists( 'icl_register_string' ) ) {
			icl_register_string( 'templatic', $title, $title );
			$title1 = icl_t( 'templatic', $title, $title );
		} else {
			$title1 = $title;
		}
		$title = sprintf( '<%1$s id="site-title"><a href="%2$s" title="%3$s" rel="home"><span>%4$s</span></a></%1$s>', tag_escape( $tag ), esc_url( home_url() ), esc_attr( $title1 ), $title1 );
	}
	/* Display the site title and apply filters for developers to overwrite. */
	echo wp_kses_post( apply_atomic( 'site_title', $title ) );
}

/**
 * Dynamic element to wrap the site description in.  If it is the front page, wrap it in an <h2> element.
 * On other pages, wrap it in a <div> element.
 */
function supreme_site_description() {
	/* If viewing the front page of the site, use an <h2> tag.  Otherwise, use a <div> tag. */
	$tag = ( is_front_page() ) ? 'h2' : 'div';
	$tmpdata = get_option( supreme_prefix() . '_theme_settings' );
	/* Get the site description.  If it's not empty, wrap it with the appropriate HTML. */
	if ( get_bloginfo( 'name' ) == $desc ) {
		if ( function_exists( 'icl_register_string' ) ) {
			icl_register_string( 'templatic', $desc, $desc );
			$desc1 = icl_t( 'templatic', $desc, $desc );
		} else {
			$desc1 = $desc;
		}
		$desc = sprintf( '<%1$s id="site-description"><span>%2$s</span></%1$s>', tag_escape( $tag ), $desc1 );
	}
	/* Display the site description and apply filters for developers to overwrite. */
	echo wp_kses_post( apply_atomic( 'site_description', $desc ) );
}

/**
 * Checks if a post of any post type has a custom template.  This is the equivalent of WordPress'
 * is_page_template() function with the exception that it works for all post types.
 *
 * @param string $template 			Template path.
 */
function supreme_has_post_template( $template = '' ) {
	/* Assume we're viewing a singular post. */
	if ( is_singular() ) {
		/* Get the queried object. */
		$post = get_queried_object();
		/* Get the post template, which is saved as metadata. */
		$post_template = get_post_meta( get_queried_object_id(), "_wp_{$post->post_type}_template", true );
		/* If a specific template was input, check that the post template matches. */
		if ( ! empty( $template ) && ( $template == $post_template ) ) {
			return true;
		} elseif ( empty( $template ) && ! empty( $post_template ) ) {
			return true;
		}
	}
	/* Return false for everything else. */
	return false;
}

/**
 * Defines the theme prefix. This allows developers to infinitely change the theme. In theory,
 * one could use the Hybrid core to create their own theme or filter 'hybrid_prefix' with a
 * plugin to make it easier to use hooks across multiple themes without having to figure out
 * each theme's hooks (assuming other themes used the same system).
 */
function supreme_prefix() {
	global $supreme;
	$supreme->prefix = sanitize_key( apply_filters( 'hybrid_prefix', get_template() ) );
	return $supreme->prefix;
}

/**
 * Adds contextual filter hooks to the theme.  This allows users to easily filter context-based content
 * without having to know how to use WordPress conditional tags.  The theme handles the logic.
 *
 * An example of a basic hook would be 'hybrid_entry_meta'.  The apply_atomic() function extends
 * that to give extra hooks such as 'hybrid_singular_entry_meta', 'hybrid_singular-post_entry_meta',
 * and 'hybrid_singular-post-ID_entry_meta'.
 *
 * @param html   $tag 			Html tag.
 * @param string $value 		Value.
 */
function apply_atomic( $tag = '', $value = '' ) {
	if ( empty( $tag ) ) {
		return false;
	}
	/* Get theme prefix. */
	$pre = supreme_prefix();
	/* Get the args passed into the function and remove $tag. */
	$args = func_get_args();
	array_splice( $args, 0, 1 );
	/* Apply filters on the basic hook. */
	$value = $args[0] = apply_filters_ref_array( "{$pre}_{$tag}", $args );
	/* Loop through context array and apply filters on a contextual scale. */
	foreach ( supreme_get_context() as $context ) {
		$value = $args[0] = apply_filters_ref_array( "{$pre}_{$context}_{$tag}", $args );
	}
	/* Return the final value once all filters have been applied. */
	return $value;
}

/**
 * Wraps the output of apply_atomic() in a call to do_shortcode(). This allows developers to use
 * context-aware functionality alongside shortcodes. Rather than adding a lot of code to the
 * function itself, developers can create individual functions to handle shortcodes.
 *
 * @param html   $tag 			Html tag.
 * @param string $value 		Value.
 */
function apply_atomic_shortcode( $tag = '', $value = '' ) {
	return do_shortcode( apply_atomic( $tag, $value ) );
}

/**
 * The theme can save multiple things in a transient to help speed up page load times. We're
 * setting a default of 12 hours or 43,200 seconds (60 * 60 * 12).
 */
function hybrid_get_transient_expiration() {
	return apply_filters( supreme_prefix() . '_transient_expiration', 43200 );
}

/**
 * Function for setting the content width of a theme.  This does not check if a content width has been set; it
 * simply overwrites whatever the content width is.
 *
 * @param integer $width 			Width Html tag.
 */
function supreme_set_content_width( $width = '' ) {
	global $content_width;
	$content_width = absint( $width );
}

/**
 * Loads the Hybrid theme settings once and allows the input of the specific field the user would
 * like to show.  Hybrid theme settings are added with 'autoload' set to 'yes', so the settings are
 * only loaded once on each page load.
 *
 * @param string $option 			return value from option table.
 */
function supreme_get_settings( $option = '' ) {

	if ( ! $option ) {
		return false;
	}


	if ( function_exists( 'supreme_prefix' ) ) {
		$pref = supreme_prefix();
	} else {
		$pref = get_template();
	}

	$theme_settings = get_option( $pref . '_theme_settings' );
	if ( isset( $theme_settings[ $option ] ) ) :
		return $theme_settings[ $option ];
	else :
		return '';
	endif;
}

if ( ! function_exists( 'theme_get_settings' ) ) {
	/**
	 * Loads the Hybrid theme settings once and allows the input of the specific field the user would
	 * like to show.  Hybrid theme settings are added with 'autoload' set to 'yes', so the settings are
	 * only loaded once on each page load.
	 *
	 * @param string $option 			return value from option table.
	 */
	function theme_get_settings( $option = '' ) {
		global $supreme;
		/* If no specific option was requested, return false. */
		if ( ! $option ) {
			return false;
		}
		/* Call get_option() to get an array of theme settings. */
		$theme_options = get_option( supreme_prefix() . '_theme_settings' );
		/* If the settings isn't an array or the specific option isn't in the array, return false. */
		if ( ! is_array( $theme_options ) || empty( $theme_options ) ) {
			return false;
		}
		/* If the specific option is an array, return it. */
		if ( is_array( $theme_options ) && isset( $theme_options[ $option ] ) ) {
			return wp_kses_stripslashes( $theme_options[ $option ] );
			/* Strip slashes from the setting and return. */
		} else {
			return false;
		}
	}
}

/**
 * Get supreme default theme settings.
 */
function supreme_default_theme_settings() {
	/* Set up some default variables. */
	$settings = array();
	$prefix = supreme_prefix();
	/* Get theme-supported meta boxes for the settings page. */
	$supports = get_theme_support( 'supreme-core-theme-settings' );
	/* If the current theme supports the footer meta box and shortcodes, add default footer settings. */
	if ( is_array( $supports[0] ) && in_array( 'footer', $supports[0] ) && current_theme_supports( 'supreme-core-shortcodes' ) ) {
		/* If there is a child theme active, add the [child-link] shortcode to the $footer_insert. */
		if ( is_child_theme() ) {
			$settings['footer_insert'] = '<p class="copyright">' . esc_html__( 'Copyright &#169; [the-year] [site-link].', 'templatic' ) . '</p>' . "\n\n" . '<p class="credit">' . esc_html__( 'Powered by [wp-link], [theme-link], and [child-link].', 'templatic' ) . '</p>';
		} else {
			$settings['footer_insert'] = '<p class="copyright">' . esc_html__( 'Copyright &#169; [the-year] [site-link].', 'templatic' ) . '</p>' . "\n\n" . '<p class="credit">' . esc_html__( 'Powered by [wp-link] and [theme-link].', 'templatic' ) . '</p>';
		}
	}
	/* Return the $settings array and provide a hook for overwriting the default settings. */
	return apply_filters( "{$prefix}_default_theme_settings", $settings );
}

/**
 * Return the categories of post.
 *
 * @param string $label         Label to show category.
 * @param string $taxonomy      Show category of which taxonomy.
 * @param string $class         Class.
 * @param string $tags_label    label of tags.
 * @param string $tag_taxonomy  Show tag of which taxonomy.
 */
function supreme_get_categories( $label, $taxonomy, $class, $tags_label, $tag_taxonomy ) {
	$label = $label;
	$tags_label = $tags_label;

	if ( function_exists( 'icl_register_string' ) ) {
		icl_register_string( 'templatic', $label, $label );
	}

	if ( function_exists( 'icl_t' ) ) {
		$label1 = icl_t( 'templatic', $label, $label );
	} else {
		$label1 = $label;
	}
	if ( function_exists( 'icl_register_string' ) ) {
		icl_register_string( 'templatic', $tags_label, $tags_label );
	}

	if ( function_exists( 'icl_t' ) ) {
		$tags_label1 = icl_t( 'templatic', $tags_label, $tags_label );
	} else {
		$tags_label1 = $tags_label;
	}
	echo apply_atomic_shortcode( 'entry_meta', '<div class="entry-meta ' . $class . '">' . ' ' . sprintf( __( '[entry-terms taxonomy="%s" before="%s"] [entry-terms taxonomy="%s" before="%s"]', 'templatic' ), $taxonomy, $label1, $tag_taxonomy, $tags_label1 ) . '</div>' );
}

/* Functions file for loading scripts and stylesheets.  This file also handles the output of attachment files  */
add_action( 'wp_enqueue_scripts', 'supreme_register_scripts', 1 ); /* Register Supreme Core scripts. */
add_action( 'wp_enqueue_scripts', 'supreme_enqueue_scripts' ); /* Load Supreme Core scripts. */
add_filter( 'stylesheet_uri', 'supreme_debug_stylesheet', 10, 2 ); /* Load the development stylsheet in script debug mode. */
add_filter( 'image_size_names_choose', 'hybrid_image_size_names_choose' );

/**
 * Registers JavaScript files for the framework.  This function merely registers scripts with WordPress using
 * the wp_register_script() function.
 */
function supreme_register_scripts() {
	/* Supported JavaScript. */
	$supports = get_theme_support( 'supreme-core-javascript' );
}

/**
 * Tells WordPress to load the scripts needed for the framework using the wp_enqueue_script() function.
 */
function supreme_enqueue_scripts() {
	/* Supported JavaScript. */
	$supports = get_theme_support( 'supreme-core-javascript' );
	/* Load the comment reply script on singular posts with open comments if threaded comments are supported. */
	if ( is_single() && get_option( 'thread_comments' ) && comments_open() ) {
		wp_enqueue_script( 'comment-reply' );
	}
}

/**
 * Function for using a debug stylesheet when developing.  To develop with the debug stylesheet,
 * SCRIPT_DEBUG must be set to 'true' in the 'wp-config.php' file.
 *
 * @param string $stylesheet_uri        Relative url.
 * @param string $stylesheet_dir_uri 	Absolute url.
 */
function supreme_debug_stylesheet( $stylesheet_uri, $stylesheet_dir_uri ) {
	/* If SCRIPT_DEBUG is set to true and the theme supports 'dev-stylesheet'. */
	if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG && current_theme_supports( 'dev-stylesheet' ) ) {
		/* Remove the stylesheet directory URI from the file name. */
		$stylesheet = str_replace( trailingslashit( $stylesheet_dir_uri ), '', $stylesheet_uri );
		/* Change the stylesheet name to 'style.dev.css'. */
		$stylesheet = str_replace( '.css', '.dev.css', $stylesheet );
		/* If the stylesheet exists in the stylesheet directory, set the stylesheet URI to the dev stylesheet. */
		if ( file_exists( trailingslashit( get_stylesheet_directory() ) . $stylesheet ) ) {
			$stylesheet_uri = trailingslashit( $stylesheet_dir_uri ) . $stylesheet;
		}
	}
	/* Return the theme stylesheet. */
	return $stylesheet_uri;
}

/**
 * Adds theme/plugin custom images sizes added with add_image_size() to the image uploader/editor.  This
 * allows users to insert these images within their post content editor.
 *
 * @param array $sizes 		Array of image sizes.
 */
function hybrid_image_size_names_choose( $sizes ) {
	/* Get all intermediate image sizes. */
	$intermediate_sizes = get_intermediate_image_sizes();
	$add_sizes = array();
	/* Loop through each of the intermediate sizes, adding them to the $add_sizes array. */
	foreach ( $intermediate_sizes as $size ) {
		$add_sizes[ $size ] = $size;
	}
	/* Merge the original array, keeping it intact, with the new array of image sizes. */
	$sizes = array_merge( $add_sizes, $sizes );
	/* Return the new sizes plus the old sizes back. */
	return $sizes;
}

/**
 * Loads the correct function for handling attachments.  Checks the attachment mime type to call
 * correct function. Image attachments are not loaded with this function.  The functionality for them
 * should be handled by the theme's attachment or image attachment file.
 */
function hybrid_attachment() {
	$file = wp_get_attachment_url();
	$mime = get_post_mime_type();
	$mime_type = explode( '/', $mime );
	/* Loop through each mime type. If a function exists for it, call it. Allow users to filter the display. */
	foreach ( $mime_type as $type ) {
		if ( function_exists( "hybrid_{$type}_attachment" ) ) {
			$attachment = call_user_func( "hybrid_{$type}_attachment", $mime, $file );
		}
		$attachment = apply_atomic( "{$type}_attachment", $attachment );
	}
	echo wp_kses_post( apply_atomic( 'attachment', $attachment ) );
}

/**
 * Handles application attachments on their attachment pages.  Uses the <object> tag to embed media
 * on those pages.
 *
 * @param string $mime 			Mine type of image.
 * @param String $file 			File url.
 */
function hybrid_application_attachment( $mime = '', $file = '' ) {
	$embed_defaults = wp_embed_defaults();
	$application = '<object class="text" type="' . esc_attr( $mime ) . '" data="' . esc_url( $file ) . '" width="' . esc_attr( $embed_defaults['width'] ) . '" height="' . esc_attr( $embed_defaults['height'] ) . '">';
	$application .= '<param name="src" value="' . esc_url( $file ) . '" />';
	$application .= '</object>';
	return $application;
}

/**
 * Handles text attachments on their attachment pages.  Uses the <object> element to embed media
 * in the pages.
 *
 * @param string $mime 			Mine type of image.
 * @param String $file 			File url.
 */
function hybrid_text_attachment( $mime = '', $file = '' ) {
	$embed_defaults = wp_embed_defaults();
	$text = '<object class="text" type="' . esc_attr( $mime ) . '" data="' . esc_url( $file ) . '" width="' . esc_attr( $embed_defaults['width'] ) . '" height="' . esc_attr( $embed_defaults['height'] ) . '">';
	$text .= '<param name="src" value="' . esc_url( $file ) . '" />';
	$text .= '</object>';
	return $text;
}

/**
 * Handles audio attachments on their attachment pages.  Puts audio/mpeg and audio/wma files into
 * an <object> element.
 *
 * @param string $mime 			Mine type of image.
 * @param String $file 			File url.
 */
function hybrid_audio_attachment( $mime = '', $file = '' ) {
	$embed_defaults = wp_embed_defaults();
	$audio = '<object type="' . esc_attr( $mime ) . '" class="player audio" data="' . esc_url( $file ) . '" width="' . esc_attr( $embed_defaults['width'] ) . '" height="' . esc_attr( $embed_defaults['height'] ) . '">';
	$audio .= '<param name="src" value="' . esc_url( $file ) . '" />';
	$audio .= '<param name="autostart" value="false" />';
	$audio .= '<param name="controller" value="true" />';
	$audio .= '</object>';
	return $audio;
}

/**
 * Handles video attachments on attachment pages.  Add other video types to the <object> element.
 *
 * @param string $mime 			Mine type of image.
 * @param String $file 			File url.
 */
function hybrid_video_attachment( $mime = false, $file = false ) {
	$embed_defaults = wp_embed_defaults();
	if ( 'video/asf' == $mime ) {
		$mime = 'video/x-ms-wmv';
	}
	$video = '<object type="' . esc_attr( $mime ) . '" class="player video" data="' . esc_url( $file ) . '" width="' . esc_attr( $embed_defaults['width'] ) . '" height="' . esc_attr( $embed_defaults['height'] ) . '">';
	$video .= '<param name="src" value="' . esc_url( $file ) . '" />';
	$video .= '<param name="autoplay" value="false" />';
	$video .= '<param name="allowfullscreen" value="true" />';
	$video .= '<param name="controller" value="true" />';
	$video .= '</object>';
	return $video;
}

/**
 * Return pagination
 *
 * @param array $args 		Array as an argument.
 */
function loop_pagination( $args = array() ) {
	global $wp_rewrite, $wp_query;
	/* If there's not more than one page, return nothing. */
	if ( 1 >= $wp_query->max_num_pages ) {
		return;
	}
	/* Get the current page. */
	$current = ( get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1 );
	/* Get the max number of pages. */
	$max_num_pages = intval( $wp_query->max_num_pages );
	/* Set up some default arguments for the paginate_links() function. */
	$defaults = array(
		'base' => esc_url( add_query_arg( 'paged', '%#%' ) ),
		'format' => '',
		'total' => $max_num_pages,
		'current' => $current,
		'prev_next' => true,
		'show_all' => false,
		'end_size' => 1,
		'mid_size' => 1,
		'add_fragment' => '',
		'type' => 'plain',
		'before_page_number' => '<strong>',
		'after_page_number' => '</strong>',
		'before' => '<div class="pagination loop-pagination">',
		'after' => '</div>',
		'echo' => true,
		);
	/* Add the $base argument to the array if the user is using permalinks. */
	if ( $wp_rewrite->using_permalinks() && ! is_search() ) {
		$defaults['base'] = user_trailingslashit( trailingslashit( get_pagenum_link() ) . 'page/%#%' );
	}
	/* Allow developers to overwrite the arguments with a filter. */
	$args = apply_filters( 'loop_pagination_args', $args );
	/* Merge the arguments input with the defaults. */
	$args = wp_parse_args( $args, $defaults );
	/* Don't allow the user to set this to an array. */
	if ( 'array' == $args['type'] ) {
		$args['type'] = 'plain';
	}
	/* Get the paginated links. */
	$page_links = paginate_links( $args );
	/* Remove 'page/1' from the entire output since it's not needed. */
	$page_links = str_replace( array( '&#038;paged=1\'', '/page/1\'', '/page/1/\'' ), '\'', $page_links );
	/* Wrap the paginated links with the $before and $after elements. */
	$page_links = $args['before'] . $page_links . $args['after'];
	/* Allow devs to completely overwrite the output. */
	$page_links = apply_filters( 'loop_pagination', $page_links );
	/* Return the paginated links for use in themes. */
	if ( $args['echo'] ) {
		echo wp_kses_post( $page_links );
	} else {
		return $page_links;
	}
}

/**
 * Get Taxonomies
 *
 * @param array $post 				Post Array.
 */
function supreme_get_post_taxonomies( $post ) {
	$post_type = get_post_type( $post );
	$taxonomies = get_object_taxonomies( $post_type, 'objects' );
	$key_ = '';
	foreach ( $taxonomies as $key => $var ) {
		$key_ .= $key . ',';
	}
	return explode( ',', $key_ );
}

if ( ! function_exists( 'the_content_limit' ) ) {
	/**
	 * Display the limited content
	 *
	 * @param integer $max_char 			Maximum character length.
	 * @param string  $more_link_text 		Read more tect.
	 * @param string  $stripteaser 			stripteaser.
	 * @param string  $more_file 			More file.
	 */
	function the_content_limit( $max_char, $more_link_text = '', $stripteaser = true, $more_file = '' ) {
		global $post;
		$post_id = $post->ID;
		$more_link_text = ( '' != $more_link_text ) ? $more_link_text : esc_html__( 'Read More &raquo;', 'templatic' );
		$content = get_the_content();
		$content = apply_filters( 'the_content', $content );
		$content = strip_tags( $content );
		$content = substr( $content, 0, $max_char );
		$content = substr( $content, 0, strrpos( $content, ' ' ) );
		$more_link_text = '<a href="' . esc_url( get_permalink( $post_id ) ) . '">' . $more_link_text . '</a>';
		$content = $content . ' ' . $more_link_text;
		return wp_kses_post( $content );
	}
}

add_action( 'listing_post_title_before_image', 'listing_post_title_before_image' );
if ( ! function_exists( 'listing_post_title_before_image' ) ) {
	/**
	 * Display the post title.
	 *
	 * @param array $instance 		Instacnes  of widget.
	 */
	function listing_post_title_before_image( $instance ) {
		if ( ! empty( $instance['show_title'] ) ) :
			printf( '<h2><a href="%s" title="%s">%s</a></h2>', get_permalink(), the_title_attribute( 'echo=0' ), the_title_attribute( 'echo=0' ) );
		endif;
	}
}

if ( ! function_exists( 'featured_get_image' ) ) {
	/**
	 * Pass post image;
	 *
	 * @param array $arg 				Get image html.
	 */
	function featured_get_image( $arg ) {
		global $post;
		if ( 'html' == $arg['format'] ) {
			$image = directory_bdw_get_images_plugin( $post->ID, $arg['size'] );
			$thumb_img = @$image[0]['file'];
			if ( $thumb_img ) {
				echo '<img class="img thumbnail " src="' . esc_url( $thumb_img ) . '" />';
			}
		} else {
			$image = directory_bdw_get_images_plugin( $post->ID, $arg['size'] );
			$thumb_img = @$image[0]['file'];
			echo esc_url( $thumb_img );
		}
	}
}
/**
 * Display all image size.
 */
function supreme_get_additional_image_sizes() {
	global $_wp_additional_image_sizes;
	if ( $_wp_additional_image_sizes ) {
		return $_wp_additional_image_sizes;
	}
	return array();
}

/**
 * Return pagination to detail pages.
 *
 * @param array $post 				Array of Post.
 */
function supreme_loop_navigation( $post ) {
	if ( is_attachment() ) :
	?>
		<div class="loop-nav">
			<?php previous_post_link( '%link', '<span class="previous"><span class="meta-nav">&larr;</span> ' . __( 'Return to entry', 'templatic' ) . '</span>' ); ?>
		</div>
		<!-- .loop-nav -->
<?php elseif ( is_singular( 'post' ) ) : ?>
		<div class="loop-nav">
			<?php
			previous_post_link( '%link', '<span class="previous"><span class="meta-nav">&larr;</span> ' . __( 'Previous', 'templatic' ) . '</span>' );

			next_post_link( '%link', '<span class="next">' . __( 'Next', 'templatic' ) . '  <span class="meta-nav">&rarr;</span></span>' );
			?>
		</div>
	<!-- .loop-nav -->
<?php elseif ( ! is_singular() && current_theme_supports( 'loop-pagination' ) ) : loop_pagination( array(
																									'prev_text' => '<strong class="prev page-numbers">' . esc_html__( 'Previous', 'templatic' ) . '</strong> ',
																									 'next_text' => ' <strong class="next page-numbers">' . __( 'Next', 'templatic' ) . '</strong>',
																						)
	);
	elseif ( ! is_singular() && $nav = get_posts_nav_link( array(
																'sep' => '',
																'prelabel' => '<span class="previous"><span class="meta-nav">&larr;</span> ' . esc_html__( 'Previous', 'templatic' ) . '</span>',
																'nxtlabel' => '<span class="next">' . __( 'Next', 'templatic' ) . ' &rarr;</span>',
																)
	)
									) : echo 'qwe'; ?>
	<div class="loop-nav"> <?php echo wp_kses_post( $nav ); ?> </div>
	<!-- .loop-nav -->
	<?php
	endif;
}

/**
 * Apply quats to content.
 *
 * @param string $content 				Post Content.
 */
function supreme_quote_post_content( $content ) {
	if ( has_post_format( 'quote' ) ) {
		preg_match( '/<blockquote.*?>/', $content, $matches );
		if ( empty( $matches ) ) {
			$content = "<blockquote>{$content}</blockquote>";
		}
	}
	return $content;
}

if ( ! function_exists( 'supreme_excerpt_length' ) ) {
	/**
	 * Filter for excerpt length.
	 */
	function supreme_excerpt_length() {
		$tmpdata = get_option( supreme_prefix() . '_theme_settings' );
		if ( $tmpdata['templatic_excerpt_length'] ) {
			return $tmpdata['templatic_excerpt_length'];
		} else {
			return 400;
		}
	}
}

if ( ! function_exists( 'slider_excerpt_length' ) ) {
	/**
	 * Filter for excerpt length.
	 */
	function slider_excerpt_length() {
		global $legnth_content;
		return $legnth_content;
	}
}
/**
 * Filter for excerpt length and Read more link filter
 *
 * @param string  $string 				String.
 * @param integer $word_limit 			Limit to show number of characters.
 */
function string_limit_words( $string, $word_limit ) {
	$words = explode( ' ', $string, ( $word_limit + 1) );
	if ( count( $words ) > $word_limit ) {
		array_pop( $words );
	}
	return implode( ' ', $words ) . new_excerpt_more();
}

if ( ! function_exists( 'new_excerpt_more' ) ) {
	/**
	 * Read more link filter.
	 *
	 * @param string $more 				Read more string.
	 */
	function new_excerpt_more( $more = '' ) {
		global $post;
		$tmpdata = get_option( supreme_prefix() . '_theme_settings' );
		if ( function_exists( 'icl_t' ) ) {
			icl_register_string( 'templatic', @$tmpdata['templatic_excerpt_link'], @$tmpdata['templatic_excerpt_link'] );
			$link = icl_t( 'templatic', @$tmpdata['templatic_excerpt_link'], @$tmpdata['templatic_excerpt_link'] );
		} else {
			$link = ( isset( $tmpdata['templatic_excerpt_link'] ) ) ? $tmpdata['templatic_excerpt_link'] : '';
		}
		if ( isset( $tmpdata['templatic_excerpt_link'] ) && $tmpdata['templatic_excerpt_link'] ) {
			return '... <a class="moretag" href="' . esC_url( get_permalink( $post->ID ) ) . '">' . $link . '</a>';
		} else {
			return '... <a class="moretag" href="' . esc_url( get_permalink( $post->ID ) ) . '">' . esc_html__( 'Read more &raquo;', 'templatic' ) . '</a>';
		}
	}
}
if ( ! function_exists( 'supreme_is_layout1c' ) ) {
	/**
	 * Check wheter layout is global or not.
	 */
	function supreme_is_layout1c() {
		$global_layout = theme_get_settings( 'supreme_global_layout' );
		if ( 'layout_1c' == $global_layout ) {
			return true;
		} else {
			return false;
		}
	}
}

/**
 * Is detail page content have gallery shortcode or not.
 */
function supreme_havent_gallery() {
	global $post;
	if ( isset( $post->post_content ) && ! stripos( $post->post_content, '[gallery' ) ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Post gallery on detail page of "Post".
 * It will show only when gallery post format is selected
 *
 * @param array $post 				Post array.
 */
function supreme_post_gallery( $post ) {
	global $post;
	$post_images = directory_bdw_get_images_plugin( $post->ID, 'thumb' );
	$post_main_image = directory_bdw_get_images_plugin( $post->ID, 'large' );
	?>
	<?php if ( count( $post_images ) > 0 ) : ?>
		<!--Image Gallery Start -->
		<script type="text/javascript">
			jQuery(window).load(function () {
				// The slider being synced must be initialized first
				jQuery( '#carousel').flexslider({
					animation: "slide",
					controlNav: false,
					animationLoop: false,
					slideshow: false,
					itemWidth: 100,
					itemMargin: 5,
					asNavFor: '#slider',
					smoothHeight: true,
				});

				jQuery( '#slider').flexslider({
					animation: "slide",
					controlNav: false,
					animationLoop: false,
					slideshow: false,
					sync: "#carousel",
					smoothHeight: true,
				});
			});
			//FlexSlider: Default Settings
		</script>
		<div class="post_gallery_container">
			<div id="slider" class="flexslider clearfix ">
				<div class="slides_container">
					<ul id = "main_image" class="slides">
						<?php
						$post_main_image_count = count( $post_main_image );
						for ( $im = 0; $im < $$post_main_image_count; $im++ ) :
							$attachment_id = $post_main_image[ $im ]['id'];
							$alt = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
							$attach_data = get_post( $attachment_id );
							$title = $attach_data->post_title;
							?>
							<li> <a rel="example_group" href="<?php echo esc_url( $post_main_image[ $im ]['file'] ); ?>" title="<?php echo esc_attr( $title ); ?>"> <img src="<?php echo esc_url( $post_main_image[ $im ]['file'] ); ?>" title="<?php echo esc_attr( $title ); ?>" alt="<?php echo esc_attr( $alt ); ?>" /> </a> </li>
						<?php endfor; ?>
					</ul>
				</div>
			</div>
			<!--Finish image gallery -->
			<?php if ( count( $post_images ) > 1 ) { ?>
			<div id="carousel" class="flexslider">
				<ul class="slides">
						<?php
						$post_images_count = count( $post_images );
						for ( $im = 0; $im < $post_images_count; $im++ ) :
							$attachment_id = $post_images[ $im ]['id'];
							$alt = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
							$attach_data = get_post( $attachment_id );
							$title = $attach_data->post_title;
							?>
							<li> <a href="<?php echo esc_url( $post_images[ $im ]['file'] ); ?>" title="<?php echo esc_attr( $title ); ?>"> <img src="<?php echo esc_url( $post_images[ $im ]['file'] ) ; ?>" height="70" width="70"  title="<?php echo esc_attr( $title ); ?>" alt="<?php echo esc_attr( $alt ); ?>" /> </a> </li>
					<?php endfor; ?>
				</ul>
			</div>
		<?php } ?>
		</div>
<?php endif; ?>
<?php
}

/**
 * It will return the favicon icon.
 */
function supreme_get_favicon() {
	global $tmpdata;
	if ( supreme_get_settings( 'supreme_favicon_icon' ) ) {
		return supreme_get_settings( 'supreme_favicon_icon' );
	}
}

if ( ! function_exists( 'user_single_post_visit_count' ) ) :
	/**
	 * Function Name:user_single_post_visit_count
	 *
	 * @param integer $pid 			Post id.
	 */
	function user_single_post_visit_count( $pid ) {
		if ( get_post_meta( $pid, 'viewed_count', true ) ) {
			return get_post_meta( $pid, 'viewed_count', true );
		} else {
			return '0';
		}
	}

endif;

if ( ! function_exists( 'user_single_post_visit_count_daily' ) ) :
	/**
	 * User single post visit count daily.
	 *
	 * @param integer $pid 				Post id.
	 */
	function user_single_post_visit_count_daily( $pid ) {
		if ( get_post_meta( $pid, 'viewed_count_daily', true ) ) {
			return get_post_meta( $pid, 'viewed_count_daily', true );
		} else {
			return '0';
		}
	}

endif;

if ( ! function_exists( 'supreme_view_count' ) ) :
	/**
	 * This function will return the post views.
	 *
	 * @param string $content 				show count with post content.
	 */
	function supreme_view_count( $content ) {
		$custom_content = '';
		global $post;
		if ( is_single() || is_singular() && ! is_page() ) {
			$sep = ' , ';
			if ( 1 == user_single_post_visit_count( $post->ID ) ) {
				$time = ' ' . esc_html__( 'time', 'templatic' );
			} else {
				$time = ' ' . esc_html__( 'times', 'templatic' );
			}

			if ( 1 == user_single_post_visit_count_daily( $post->ID ) ) {
				$visit = ' ' . esc_html__( 'Visit', 'templatic' );
			} else {
				$visit = ' ' . esc_html__( 'Visits', 'templatic' );
			}
			$custom_content .= '<p><span class="view_counter">' . esc_html__( 'Visited', 'templatic' ) . '<b>' . sprintf(__( ' %s', 'templatic' ), user_single_post_visit_count( $post->ID ) ) . $time . '</b>';
			$custom_content .= $sep . ' <b>' . user_single_post_visit_count_daily( $post->ID ) . ' ' . $visit . '</b>' . ' ' . esc_html__( 'today', 'templatic' ) . '</span></p>';
			$custom_content .= $content;

			return $custom_content;
		}
		return $content;
	}

endif;

add_action( 'before_body_end', 'supreme_gogole_analytics' );
/**
 * Hook before body to add google analytics code
 */
function supreme_gogole_analytics() {
	echo stripslashes( supreme_get_settings( 'supreme_gogle_analytics_code' ) );
}

/**
 * Secondary navigation menu.
 */
function supreme_secondary_navigation() {
	$theme_name = get_option( 'stylesheet' );
	$nav_menu = get_option( 'theme_mods_' . strtolower( $theme_name ) );
	if ( is_active_sidebar( 'mega_menu' ) ) {
		if ( function_exists( 'dynamic_sidebar' ) ) {
			dynamic_sidebar( 'mega_menu' ); // jQuery mega menu.
		}
	} elseif ( isset( $nav_menu['nav_menu_locations'] ) && isset( $nav_menu['nav_menu_locations']['secondary'] ) && 0 != $nav_menu['nav_menu_locations']['secondary'] ) {
		?>
		<div id="nav-secondary" class="nav_bg columns">
			<?php apply_filters( 'tmpl_supreme_header_secondary', supreme_header_secondary_navigation() ); // Loads the menu-secondary template.?>
		</div>
		<?php

		apply_filters( 'tmpl_after-header', supreme_sidebar_after_header() ); // Loads the sidebar-after-header.
	} else {
		echo '<div id="nav-secondary" class="nav_bg columns"><div id="menu-secondary" class="menu-container clearfix"><nav class="wrap" role="navigation"><div class="menu"><ul id="menu-secondary-items" class="">';
		wp_list_pages( 'title_li=&depth=0&child_of=0&number=5&show_home=1&sort_column=ID&sort_order=DESC' );
		echo '</ul></div>';
		apply_filters( 'supreme-nav-right', dynamic_sidebar( 'secondary_navigation_right' ) );
		echo '</nav></div></div>';
	}
}

/**
 * Primary navigation menu.
 */
function supreme_primary_navigation() {

	if ( has_nav_menu( 'primary') || isset( $_REQUEST['ptype'] ) || isset( $_REQUEST['trans_id'] ) ) :
		do_action( 'before_menu_primary' );
		?>
		<!-- Primary Navigation Menu Start -->
		<div id="menu-primary" class="menu-container">
			<nav role="navigation" class="wrap">
				<div id="menu-primary-title">
					<?php esc_html_e( 'Menu', 'templatic' ); ?>
				</div>
				<!-- #menu-primary-title -->
				<?php
				do_action( 'open_menu_primary' );
				wp_nav_menu( array(
								'theme_location' 	=> 'primary',
								'container_class' 	=> 'menu',
								'menu_class' 		=> 'primary_menu clearfix',
								'menu_id' 			=> 'menu-primary-items',
								'fallback_cb' 		=> '',
								)
							);
				do_action( 'close_menu_primary' );
			?>
			</nav>
		</div>
		<!-- #menu-primary .menu-container -->
		<!-- Primary Navigation Menu End -->
		<?php
		do_action( 'after_menu_primary' );
	endif;
}

/**
 * To display slider above main.
 */
apply_filters( 'supreme_above_main-banner', add_action( 'before_main', 'supreme_home_banner_sidebar' ) ); // You can remove this filter from chid theme and add another one to show slider inside main like : add_action ( 'before-content','supreme_above_main-banner' ); .
/**
 * Show home page banner.
 */
function supreme_home_banner_sidebar() {
	/* get current page template */
	$current_page_template = get_page_template_slug( $post->ID );
	$as_posts_page = get_option( 'page_for_posts' );
	$queried_object = get_queried_object();

	/* If page is as post then don't show this widget area. */
	if ( ( is_home() || is_front_page() || 'page-templates/front-page.php' == $current_page_template ) && ( $queried_object->ID != $as_posts_page ) ) {
		dynamic_sidebar( 'home-page-banner' );
	}
}

/**
 * Filters 'get_theme_layout' to set layouts for specific installed plugin pages.
 *
 * @param string $layout 				Page layout.
 */
function supreme_plugin_layouts( $layout ) {
	if ( current_theme_supports( 'theme-layouts' ) ) {
		$global_layout = theme_get_settings( 'supreme_global_layout' );
		if ( 'layout-default' == $layout ) {
			if ( 'layout_1c' == $global_layout ) {
				$layout = 'layout-1c';
			} elseif ( 'layout_2c_l' == $global_layout ) {
				$layout = 'layout-2c-l';
			} elseif ( 'layout_2c_r' == $global_layout ) {
				$layout = 'layout-2c-r';
			} elseif ( 'layout_3c_c' == $global_layout ) {
				$layout = 'layout-3c-c';
			} elseif ( 'layout_3c_l' == $global_layout ) {
				$layout = 'layout-3c-l';
			} elseif ( 'layout_3c_r' == $global_layout ) {
				$layout = 'layout-3c-r';
			} elseif ( 'layout_hl_1c' == $global_layout ) {
				$layout = 'layout-hl-1c';
			} elseif ( 'layout_hl_2c_l' == $global_layout ) {
				$layout = 'layout-hl-2c-l';
			} elseif ( 'layout_hl_2c_r' == $global_layout ) {
				$layout = 'layout-hl-2c-r';
			} elseif ( 'layout_hr_1c' == $global_layout ) {
				$layout = 'layout-hr-1c';
			} elseif ( 'layout_hr_2c_l' == $global_layout ) {
				$layout = 'layout-hr-2c-l';
			} elseif ( 'layout_hr_2c_r' == $global_layout ) {
				$layout = 'layout-hr-2c-r';
			}
		}
	}
	return $layout;
}

/**
 * Filters 'theme_layouts_strings'.
 *
 * @param string $strings 				Show different layouts.
 */
function supreme_theme_layouts( $strings ) {
	/* Set up the layout strings. */
	$strings = array(
		'default' => esc_html__( 'Default', 'templatic-admin' ),
		'1c' => esc_html__( 'One Column', 'templatic-admin' ),
		'2c-l' => esc_html__( 'Two Columns, Left', 'templatic-admin' ),
		'2c-r' => esc_html__( 'Two Columns, Right', 'templatic-admin' ),
		);
	return $strings;
}

/**
 * Filters 'get_theme_layout'.
 *
 * @param string $layout 				Return selected layout.
 */
function supreme_layout_default( $layout ) {
	return 'layout-default';
}

/**
 * Filters 'get_theme_layout'.
 *
 * @param string $layout 				Return selected layout.
 */
function supreme_layout_1c( $layout ) {
	return 'layout-1c';
}

/**
 * Filters 'get_theme_layout'.
 *
 * @param string $layout 				Return selected layout.
 */
function supreme_layout_2c_l( $layout ) {
	return 'layout-2c-l';
}

/**
 * Filters 'get_theme_layout'.
 *
 * @param string $layout 				Return selected layout.
 */
function supreme_layout_2c_r( $layout ) {
	return 'layout-2c-r';
}

/**
 * Disables sidebars based on layout choices.
 *
 * @param array $sidebars_widgets 				Array of all registered widget area.
 */
function supreme_disable_sidebars( $sidebars_widgets ) {
	global $wp_query;
	if ( current_theme_supports( 'theme-layouts' ) && ! is_admin() ) {
		if ( 'layout-1c' == theme_layouts_get_layout() ) {
			$sidebars_widgets['front-page-sidebar'] = false;
			$sidebars_widgets['primary-sidebar'] = false;
			$sidebars_widgets['post-listing-sidebar'] = false;
			$sidebars_widgets['post-detail-sidebar'] = false;
			$sidebars_widgets['secondary'] = false;
			$sidebars_widgets['contact_page_sidebar'] = false;
			$args = array(
						'_builtin' => false,
						);

			$post_types = get_post_types( $args );
			foreach ( $post_types as $post_type ) {
				$sidebars_widgets[ "{$post_type}_category_listing_sidebar" ] = false;
				$sidebars_widgets[ "{$post_type}_detail_sidebar" ] = false;
				$sidebars_widgets[ "{$post_type}_tag_listing_sidebar" ] = false;
			}

			$theme_sidebar = '';
			$hide_theme_sidebars = apply_filters( 'theme_sidebar_hide', $theme_sidebar );
			if ( $hide_theme_sidebars ) {
				foreach ( $hide_theme_sidebars as $hide_theme_sidebar ) {
					$sidebars_widgets[ "{$hide_theme_sidebar}" ] = false;
				}
			}
		} elseif ( 'layout-hl-1c' == theme_layouts_get_layout() || 'layout-hr-1c' == theme_layouts_get_layout() ) {
			$sidebars_widgets['front-page-sidebar'] = false;
			$sidebars_widgets['primary-sidebar'] = false;
			$sidebars_widgets['secondary'] = false;
			$sidebars_widgets['after-header'] = false;
			$sidebars_widgets['after-header-2c'] = false;
			$sidebars_widgets['after-header-3c'] = false;
			$sidebars_widgets['after-header-4c'] = false;
			$sidebars_widgets['after-header-5c'] = false;
		} elseif ( 'layout-hl-2c-l' == theme_layouts_get_layout() || 'layout-hl-2c-r' == theme_layouts_get_layout() || 'layout-hr-2c-l' == theme_layouts_get_layout() || 'layout-hr-2c-r' == theme_layouts_get_layout() ) {
			$sidebars_widgets['after-header'] = false;
			$sidebars_widgets['after-header-2c'] = false;
			$sidebars_widgets['after-header-3c'] = false;
			$sidebars_widgets['after-header-4c'] = false;
			$sidebars_widgets['after-header-5c'] = false;
		} // End if().
	} // End if().
	return $sidebars_widgets;
}

/**
 * WPML compatible query , return the "joins" for wp query.
 *
 * @param string $join 				Join query for WPML Compatibility.
 */
function templatic_posts_where_filter( $join ) {
	global $wpdb, $pagenow, $wp_taxonomies;
	$language_where = '';
	if ( is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) ) {
		$language = ICL_LANGUAGE_CODE;
		$join .= ' AND t.language_code="' . $language . '"';
	}
	return $join;
}

/**
 * WPML compatible query , return the "where" for wp query in widgets.
 *
 * @param string $where 				Where query for WPML Compatibility.
 */
function templatic_widget_wpml_filter( $where ) {
	global $wpdb;
	if ( is_plugin_active( 'wpml-string-translation/plugin.php' ) ) {
		$language = ICL_LANGUAGE_CODE;
		$where .= ' AND t.language_code="' . $language . '"';
	}
	return $where;
}

if ( ! function_exists( 'check_if_woocommerce_active' ) ) {
	/**
	 * Check if woo commerce plug in is active or not
	 */
	function check_if_woocommerce_active() {
		$plugins = wp_get_active_and_valid_plugins();
		$flag = 'false';
		foreach ( $plugins as $plugins ) {
			if ( false !== strpos( $plugins, 'woocommerce.php' ) ) {
				$flag = 'true';
				break;
			} else {
				$flag = 'false';
			}
		}
		return $flag;
	}
}

if ( function_exists( 'check_if_woocommerce_active' ) ) {
	/**
	 * If woocommerce activated add the theme suppoert for woocommerce.
	 */
	$is_woo_active = check_if_woocommerce_active();
	if ( 'true' == $is_woo_active ) {
		add_theme_support( 'woocommerce' );
	}
}

/**
 * Show Daily view counter.
 */
function supreme2_view_counter() {
	$prefix = supreme_prefix();
	$settings = get_option( supreme_prefix() . '_theme_settings' );
	remove_filter( 'the_content', 'view_sharing_buttons' );
	remove_filter( 'the_content', 'view_count' );
	if ( isset( $settings['enable_view_counter'] ) && 1 == $settings['enable_view_counter'] ) {
		add_filter( 'before_content', 'supreme_get_custom_post_type_template' );
		add_filter( 'the_content', 'supreme_view_count' );
	}
	if ( function_exists( 'check_if_woocommerce_active' ) ) {
		$is_woo_active = check_if_woocommerce_active();
	}
	if ( 'true' == $is_woo_active && 'product' == get_post_type() ) {
		add_action( 'woocommerce_single_product_summary', 'supreme2_view_sharing_buttons', 100 );
	} else {
		add_action( 'after_entry', 'supreme2_view_sharing_buttons' );
	}
}

add_action( 'wp_head', 'supreme2_view_counter' );
if ( ! function_exists( 'supreme2_view_sharing_buttons' ) ) {
	/**
	 * Show sharing button.
	 *
	 * @param string $content 				Desciption of post.
	 */
	function supreme2_view_sharing_buttons( $content ) {
		global $post;
		if ( is_single() && ( 'product_variation' != $post->post_type ) ) {
			$post_thumbnail_id = get_post_thumbnail_id( $post->ID );
			$post_img = wp_get_attachment_image_src( $post_thumbnail_id, 'thumb' );
			$post_images = $post_img[0];
			$title = urlencode( $post->post_title );
			$url = urlencode( get_permalink( $post->ID ) );
			$summary = urlencode( htmlspecialchars( $post->post_content ) );
			$image = directory_bdw_get_images_plugin( $post->ID, 'thumb' );
			$image = $image[0]['file'];
			$settings = get_option( supreme_prefix() . '_theme_settings' );
			$excerpt = get_the_excerpt();
			if ( @$settings['facebook_share_detail_page'] || @$settings['google_share_detail_page'] || @$settings['twitter_share_detail_page'] || @$settings['pintrest_detail_page'] ) {
				echo '<div class="share_link">';
				if ( 1 == @$settings['facebook_share_detail_page'] ) {
					?>
					<a onClick="window.open( 'http://www.facebook.com/sharer.php?s=100&amp;p[title]=<?php echo esc_attr( $title ); ?>&amp;p[summary]=<?php echo wp_kses_post( $summary ); ?>&amp;p[url]=<?php echo esc_url( $url ); ?>&amp;&amp;p[images][0]=<?php echo esc_url( $image ); ?>', 'sharer', 'toolbar=0,status=0,width=548,height=325' );" href="javascript: void(0)" id="facebook_share_button">
						<?php esc_html_e( 'Facebook Share.', 'templatic' ); ?>
					</a>
					<?php
				}
				if ( 1 == @$settings['google_share_detail_page'] ) :
					?>
					<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>
					<div class="g-plus" data-action="share" data-annotation="bubble"></div>
			<?php endif;

				if ( 1 == @$settings['twitter_share_detail_page'] ) :
				?>
				<a href="https://twitter.com/share" class="twitter-share-button" data-lang="en" data-text='<?php echo wp_kses_post( $excerpt ); ?>' data-url="<?php echo esc_url( get_permalink( $post->ID ) ); ?>" data-counturl="<?php echo esc_url( get_permalink( $post->ID ) ); ?>">
					<?php esc_html_e( 'Tweet', 'templatic' ); ?>
				</a>
				<script>!function (d, s, id) {
					var js, fjs = d.getElementsByTagName(s)[0];
					if ( !d.getElementById(id)) {
						js = d.createElement(s);
						js.id = id;
						js.src = "https://platform.twitter.com/widgets.js";
						fjs.parentNode.insertBefore(js, fjs);
					}
				}(document, "script", "twitter-wjs");</script>
		<?php endif;

				if ( 1 == @$settings['pintrest_detail_page'] ) :
				?>
			<!-- Pinterest -->
				<div class="pinterest"> <a href="http://pinterest.com/pin/create/button/?url=<?php echo urlencode( esc_url( get_permalink( $post->ID ) ) ); ?>&media=<?php echo esc_url( $image ); ?>&description=<?php esc_attr( the_title() ); ?>" >
				<?php esc_html_e( 'Pin It', 'templatic' ); ?>
				</a>
				<script type="text/javascript" src="http://assets.pinterest.com/js/pinit.js"></script>
			</div>
			<?php
			endif;
				echo '</div>';
			} // End if().
		}// End if().
		return $content;
	}
} // End if().

if ( ! function_exists( 'supreme_get_custom_post_type_template' ) ) {
	/**
	 * Add single post view counter.
	 *
	 * @param string $single_template 				Page tmeplate.
	 */
	function supreme_get_custom_post_type_template( $single_template ) {
		global $post;
		if ( is_single() || is_singular() ) {
			supreme_view_counter_single_post( $post->ID );
		}
		return $single_template;
	}
}


if ( ! function_exists( 'supreme_view_counter_single_post' ) ) {
	/**
	 * Update view counter.
	 *
	 * @param integer $pid 				Post ID.
	 */
	function supreme_view_counter_single_post( $pid ) {
		if ( '' == @$_SERVER['HTTP_REFERER'] || ! strstr( @$_SERVER['HTTP_REFERER'], @$_SERVER['REQUEST_URI'] ) ) {
			$viewed_count = get_post_meta( $pid, 'viewed_count', true );
			$viewed_count_daily = get_post_meta( $pid, 'viewed_count_daily', true );
			$daily_date = get_post_meta( $pid, 'daily_date', true );

			update_post_meta( $pid, 'viewed_count', $viewed_count + 1 );

			if ( get_post_meta( $pid, 'daily_date', true ) == date( 'Y-m-d' ) ) {
				update_post_meta( $pid, 'viewed_count_daily', $viewed_count_daily + 1 );
			} else {
				update_post_meta( $pid, 'viewed_count_daily', '1' );
			}
			update_post_meta( $pid, 'daily_date', date( 'Y-m-d' ) );
		}
	}
}

if ( ! function_exists( 'supreme_get_link_url' ) ) {
	/**
	 * Return Link.
	 */
	function supreme_get_link_url() {
		$has_url = get_the_post_format_url();
		return ( $has_url ) ? $has_url : apply_filters( 'the_permalink', get_permalink() );
	}
}

add_action( 'paypal_successfull_return_content', 'successfull_return_paypal_status', 10, 4 );
/**
 * Update paypal status.
 */
function successfull_return_paypal_status() {
	update_post_meta( intval( $_REQUEST['pid'] ), 'status', 'Approved' );
}


add_action( 'load-themes.php', 'default_permalink_set' );
/**
 * Set Default permalink on theme activation.
 */
function default_permalink_set() {
	global $pagenow;
	if ( 'themes.php' == $pagenow && isset( $_GET['activated'] ) ) {
		// Set default permalink to postname start.
		global $wp_rewrite;
		$wp_rewrite->set_permalink_structure( '/%postname%/' );
		$wp_rewrite->flush_rules();
		if ( function_exists( 'flush_rewrite_rules' ) ) {
			flush_rewrite_rules( true );
		}
	}
}

add_action( 'testimonial_script', 'widget_testimonial_script', 20, 3 );
/**
 * Testimonial script.
 *
 * @param string  $transition 				Transition type, ex: fade, scrollUp, scrollRight, shuffle.
 * @param integer $fadin 					Time out.
 * @param integer $fadout 					Speed of testimonial rotation.
 */
function widget_testimonial_script( $transition, $fadin, $fadout ) {
	?>
	<script type="text/javascript">
		var $testimonials = jQuery.noConflict();
		$testimonials(document).ready(function () {
			$testimonials( '#testimonials')
			.cycle({
				fx: '<?php echo esc_attr( $transition ); ?>',
				timeout: '<?php echo esc_attr( $fadin ); ?>',
				speed: '<?php echo esc_attr( $fadout ); ?>',
			});
		});
	</script>
	<?php
}

add_action( 'init', 'popular_post_widget', 10 );

/**
 * Add image size for popular post widget.
 */
function popular_post_widget() {
	$supreme_thumbnail_height = apply_filters( 'supreme_thumbnail_height', 60 );
	$supreme_thumbnail_width = apply_filters( 'supreme_thumbnail_width', 60 );
	$mobile_thumbnail_height = apply_filters( 'mobile_thumbnail_height', 60 );
	$mobile_thumbnail_width = apply_filters( 'mobile_thumbnail_width', 60 );
	add_image_size( 'popular_post-thumbnail', $supreme_thumbnail_height, $supreme_thumbnail_width, true );
	add_image_size( 'mobile-thumbnail', $mobile_thumbnail_width, $mobile_thumbnail_height, true );
}

add_filter( 'popular_post_thumb_image', 'crop_popular_post_thumb_image', 10 );
/**
 * Return the image for popular post thumb.
 */
function crop_popular_post_thumb_image() {
	return get_the_image( array(
							'echo' => false,
							'size' => 'popular_post-thumbnail',
							'height' => 60,
							'width' => 60,
							'default_image' => apply_filters( 'popular_post_default_image', '//placehold.it/60x60' ),
							)
	);
}

if ( ! function_exists( 'excerpt' ) ) {
	/**
	 * Return the excerpt with specific length.
	 *
	 * @param integer $limit		Length of excerpt.
	 */
	function excerpt( $limit = 27 ) {
		$excerpt = explode( ' ', get_the_excerpt(), $limit );
		if ( count( $excerpt ) >= $limit ) {
			array_pop( $excerpt );
			$excerpt = implode( ' ', $excerpt );
		} else {
			$excerpt = implode( ' ', $excerpt );
		}
		$excerpt = preg_replace( '`\[[^\]]*\]`', '', $excerpt );
		return $excerpt;
	}
}
if ( ! function_exists( 'content' ) ) {
	/**
	 * Return the post content with specific length.
	 *
	 * @param integer $limit 			Length of content.
	 */
	function content( $limit = 27 ) {
		$content = explode( ' ', get_the_content(), $limit );
		if ( count( $content ) >= $limit ) {
			array_pop( $content );
			$content = implode( ' ', $content );
		} else {
			$content = implode( ' ', $content );
		}
		$content = preg_replace( '/\[.+\]/', '', $content );
		$content = apply_filters( 'the_content', $content );
		$content = str_replace( ']]>', ']]&gt;', $content );
		return $content;
	}
}


if ( ! function_exists( 'is_tevolution_active' ) ) {
	/**
	 * To check tevolution plugin is active or not.
	 */
	function is_tevolution_active() {
		if ( is_plugin_active( 'Tevolution/templatic.php' ) ) {
			return true;
		} else {
			return false;
		}
	}
}
/**
 * To hide description on author page
 */
function tmpl_donot_display_description() {
	global $post;
	$tmpdata = get_option( 'templatic_settings' );
	if ( is_tevolution_active() && ( '' == @$tmpdata['listing_hide_excerpt'] || ! in_array( $post->post_type, @$tmpdata['listing_hide_excerpt'] ) ) ) {
		return false;
	} else {
		return true;
	}
}

add_action( 'init', 'save_custom_css' );
/**
 * Save custom css in a file from option.
 */
function save_custom_css() {
	/* DOING_AJAX is define then return false for admin ajax */
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
		return;
	}
	$file = TEMPLATE_DIR . 'custom.css';
	$theme = 'directory';
	if ( $theme ) {
		$stylesheet = $theme;
	} else {
		$stylesheet = get_stylesheet();
	}

	$theme = wp_get_theme( $stylesheet );

	if ( ! file_exists( $file ) ) {
		fopen( $file, 'w+' );
	}

	$newcontent = get_option( 'directory_custom_css' );
	if ( is_writeable( $file ) ) {
		$f = fopen( $file, 'w+' );
		if ( false !== $f ) {
			fwrite( $f, $newcontent );
			fclose( $f );
			$theme->cache_delete();
		}
	}
}

add_action( 'wp_head', 'templatic_customizer_style' );

if ( ! function_exists( 'templatic_customizer_style' ) ) {
	/**
	 * Add customiser css in front style.css
	 */
	function templatic_customizer_style() {

		if ( file_exists( get_stylesheet_directory() . '/functions/admin-style.php' ) ) {
			include_once( get_stylesheet_directory() . '/functions/admin-style.php' ); // Child theme css file.
		} elseif ( file_exists( get_template_directory() . '/library/front-style.php' ) ) {
			include_once( get_template_directory() . '/library/front-style.php' );
		}
	}
}

/**
 * SEO and header functions
 */
add_action( 'wp_head', 'tmpldir_meta_robots', 1 );
add_action( 'wp_head', 'tmpldir_meta_author', 1 );
add_action( 'wp_head', 'tmpldir_meta_copyright', 1 );
add_action( 'wp_head', 'tmpldir_meta_revised', 1 );

if ( ! is_plugin_active( 'wordpress-seo/wp-seo.php' ) ) {
	add_action( 'wp_head', 'tmpldir_meta_description', 1 );
	add_action( 'wp_head', 'tmpldir_meta_keywords', 1 );
}

/**
 * Sets the default meta robots setting.  If private, don't send meta info to the header.  Runs the
 * supreme_meta_robots filter hook at the end.
 */
function tmpldir_meta_robots() {
	/* If the blog is set to private, don't show anything. */
	if ( ! get_option( 'blog_public' ) ) {
		return;
	}
	/* Create the HTML for the robots meta tag. */
	$robots = '<meta name="robots" content="index,follow" />' . "\n";
	echo wp_kses_post( apply_atomic( 'meta_robots', $robots ) );
}

/**
 * Generates the meta author.  For singular posts, it uses the post author's display name.  For user/author
 * archives, it uses the user's display name.
 */
function tmpldir_meta_author() {
	/* Set an empty $author variable. */
	$author = '';
	/* Get the queried object. */
	$object = get_queried_object();
	/* If viewing a singular post, get the post author's display name. */
	if ( is_singular() ) {
		$author = get_the_author_meta( 'display_name', $object->post_author );
	} elseif ( is_author() ) {
		$author = get_the_author_meta( 'display_name', get_queried_object_id() );
	}
	/* If an author was found, wrap it in the proper HTML and escape the author name. */
	if ( ! empty( $author ) ) {
		$author = '<meta name="author" content="' . esc_attr( $author ) . '" />' . "\n";
	}
	echo wp_kses_post( apply_atomic( 'meta_author', $author ) );
}

/**
 * Add the meta tag for copyright information to the header.  Singular posts display the date the post was
 * published.  All other pages will show the current year.
 */
function tmpldir_meta_copyright() {
	/* If viewing a singular post, get the post month and year. */
	if ( is_singular() ) {
		$date = get_the_time( esc_attr__( 'F Y', 'templatic' ) );
	} else {
		$date = date( esc_attr__( 'Y', 'templatic' ) );
	}
	/* Create the HTML for the copyright meta tag. */
	$copyright = '<meta name="copyright" content="' . sprintf( esc_attr__( 'Copyright (c) %1$s', 'templatic' ), $date ) . '" />' . "\n";
	echo wp_kses_post( apply_atomic( 'meta_copyright', $copyright ) );
}

/**
 * Add the revised meta tag on the singular view of posts.  This shows the last time the post was modified.
 */
function tmpldir_meta_revised() {
	/* Create an empty $revised variable. */
	$revised = '';
	/* If viewing a singular post, get the last modified date/time to use in the revised meta tag. */
	if ( is_singular() ) {
		$revised = '<meta name="revised" content="' . get_the_modified_time( esc_attr__( 'l, F jS, Y, g:i a', 'templatic' ) ) . '" />' . "\n";
	}
	echo wp_kses_post( apply_atomic( 'meta_revised', $revised ) );
}

/**
 * Generates the meta description based on either metadata or the description for the object.
 */
function tmpldir_meta_description() {
	/* Set an empty $description variable. */
	$description = '';
	/* If viewing the home/posts page, get the site's description. */
	if ( is_home() ) {
		$description = get_bloginfo( 'description' );
	} elseif ( is_singular() ) {
		/* Get the meta value for the 'Description' meta key. */
		$description = get_post_meta( get_queried_object_id(), 'Description', true );
		/* If no description was found and viewing the site's front page, use the site's description. */
		if ( empty( $description ) && is_front_page() ) {
			$description = get_bloginfo( 'description' );
		} elseif ( empty( $description ) ) {
			$description = get_post_field( 'post_excerpt', get_queried_object_id() );
		}
	} elseif ( is_archive() ) {
		/* If viewing a user/author archive. */
		if ( is_author() ) {
			/* Get the meta value for the 'Description' user meta key. */
			$description = get_user_meta(get_query_var( 'author' ), 'Description', true );
			/* If no description was found, get the user's description (biographical info). */
			if ( empty( $description ) ) {
				$description = get_the_author_meta( 'description', get_query_var( 'author' ) );
			}
		} elseif ( is_category() || is_tag() || is_tax() ) {
			$description = term_description( '', get_query_var( 'taxonomy' ) );
		} elseif ( is_post_type_archive() ) {
			/* Get the post type object. */
			$post_type = get_post_type_object( get_query_var( 'post_type' ) );
			/* If a description was set for the post type, use it. */
			if ( isset( $post_type->description ) ) {
				$description = $post_type->description;
			}
		}
	}
	/* Format the meta description. */
	if ( ! empty( $description ) ) {
		$description = '<meta name="description" content="' . str_replace( array( "\r", "\n", "\t" ), '', esc_attr( strip_tags( $description ) ) ) . '" />' . "\n";
	}
	echo wp_kses_post( apply_atomic( 'meta_description', $description ) );
}

/**
 * Generates meta keywords/tags for the site.
 */
function tmpldir_meta_keywords() {
	/* Set an empty $keywords variable. */
	$keywords = array();
	/* If on a singular post and not a preview. */
	if ( is_singular() && ! is_preview() ) {
		/* Get the queried post. */
		$post = get_queried_object();
		/* Get the meta value for the 'Keywords' meta key. */
		$keywords = get_post_meta( get_queried_object_id(), 'Keywords', true );
		/* If no keywords were found. */
		if ( empty( $keywords ) ) {
			/* Get all taxonomies for the current post type. */
			$taxonomies = get_object_taxonomies( $post->post_type );
			/* If taxonomies were found for the post type. */
			if ( is_array( $taxonomies ) ) {
				/* Loop through the taxonomies, getting the terms for the current post. */
				foreach ( $taxonomies as $tax ) {
					if ( $terms = get_the_term_list( get_queried_object_id(), $tax, '', ', ', '' ) ) {
						if ( empty( $terms->errors ) ) {
							$keywords = $terms;
						}
					}
				}
				/* If keywords were found, join the array into a comma-separated string. */
				if ( ! empty( $keywords ) ) {
					$keywords = join( ', ', $keywords );
				}
			}
		}
	} elseif ( is_author() ) {
		/* Get the meta value for the 'Keywords' user meta key. */
		$keywords = get_user_meta( get_query_var( 'author' ), 'Keywords', true );
	}
	/* If we have keywords, format for output. */
	if ( ! empty( $keywords ) ) {
		$keywords = '<meta name="keywords" content="' . esc_attr( strip_tags( $keywords ) ) . '" />' . "\n";
		echo wp_kses_post( apply_atomic( 'meta_keywords', $keywords ) );
	}
}

/**
 * Its plays the author's avatar and biography. This is typically shown on singular view pages only.
 *
 * @param array $post 				Array of post.
 */
function supreme_author_biography_( $post ) {
	global $post;
	$theme_options = get_option( supreme_prefix() . '_theme_settings' );
	$supreme_author_bio_pages = @$theme_options['supreme_author_bio_pages'];
	if ( is_page() && $supreme_author_bio_pages ) {
		?>
		<div class="entry-author-meta">
			<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" title="<?php echo esc_attr( get_the_author_meta( 'display_name' ) ); ?>" class="avatar-frame">
				<?php
				if ( '' != get_user_meta( get_the_author_meta( 'ID' ), 'profile_photo', true ) ) {
					echo '<img class="avatar" alt="avatar" src="' . esc_url ( get_user_meta( get_the_author_meta( 'ID' ), 'profile_photo', true ) ) . '" alt="' . esc_attr( get_the_author_meta( 'display_name' ) ) . '" title="' . esc_attr( get_the_author_meta( 'display_name' ) ) . '" width="60" height="60"/>';
				} else {
					echo get_avatar( get_the_author_meta( 'ID' ), '60', '', '' );
				}
				?>
			</a>
			<p class="author-name">
				<?php do_action( 'entry-author' ); ?>
			</p>
			<p class="author-description">
				<?php the_author_meta( 'description' ); ?>
			</p>
		</div>
		<!-- .entry-author -->
		<?php
	}
	$theme_options = get_option( supreme_prefix() . '_theme_settings' );
	$supreme_author_bio_posts = @$theme_options['supreme_author_bio_posts'];
	if ( is_single() && $supreme_author_bio_posts ) {
		?>
		<div class="entry-author-meta"> <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" title="<?php echo esc_attr( get_the_author_meta( 'display_name' ) ); ?>" class="avatar-frame"><?php
		if ( '' != get_user_meta( get_the_author_meta( 'ID' ), 'profile_photo', true ) ) {
			echo '<img class="avatar" alt="avatar" src="' . esc_url( get_user_meta( get_the_author_meta( 'ID' ), 'profile_photo', true ) ) . '" alt="' . esc_attr( get_the_author_meta( 'display_name' ) ) . '" title="' . esc_attr( get_the_author_meta( 'display_name' ) ) . '" width="60" height="60"/>';
		} else {
			echo get_avatar( get_the_author_meta( 'ID' ), '60', '', '' );
		}
			?></a>
			<p class="author-name">
				<?php do_action( 'entry-author' ); ?>
			</p>
			<p class="author-description">
				<?php the_author_meta( 'description' ); ?>
			</p>
		</div>
		<!-- .entry-author -->
		<?php
	}
}

/**
 * Add localization slug in wordpress
 *
 * @param string $join 				Join sql query for WPML support.
 */
function tmpl_wpml_posts_joins( $join ) {
	global $wpdb, $pagenow, $wp_taxonomies, $ljoin;
	$language_where = '';
	if ( is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) ) {
		$language = ICL_LANGUAGE_CODE;
		$join .= " {$ljoin} JOIN {$wpdb->prefix}icl_translations t1 ON {$wpdb->posts}.ID = t1.element_id AND t1.element_type IN ( 'post_post') JOIN {$wpdb->prefix}icl_languages l1 ON t1.language_code=l1.code AND l1.active=1 AND t1.language_code='{$language}'";
	}
	return $join;
}
