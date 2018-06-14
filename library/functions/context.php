<?php
/**
 * Functions for making various theme elements context-aware.  Controls things such as the smart
 * and logical body, post, and comment CSS classes as well as context-based action and filter hooks.
 * The functions also integrate with WordPress' implementations of body_class, post_class, and
 * comment_class, so your theme won't have any trouble with plugin integration.
 *
 * @package WordPress
 * @subpackage Directory
 */

/**
 * Return the page context eg. archive page,category page( for post and custom taxonmy ).
 */
function supreme_get_context() {
	global $supreme;
	/* If $supreme->context has been set, don't run through the conditionals again. Just return the variable. */
	if ( isset( $supreme->context ) ) {
		return $supreme->context;
	}
	/* Set some variables for use within the function. */
	$supreme->context = array();
	$object = get_queried_object();
	$object_id = get_queried_object_id();
	/* Front page of the site. */
	if ( is_front_page() ) {
		$supreme->context[] = 'home';
	}
	/* Blog page. */
	if ( is_home() ) {
		$supreme->context[] = 'blog';
	} elseif ( is_singular() ) {
		$supreme->context[] = 'singular';
		$supreme->context[] = "singular-{$object->post_type}";
		$supreme->context[] = "singular-{$object->post_type}-{$object_id}";
	} elseif ( is_archive() ) {
		$supreme->context[] = 'archive';
		/* Taxonomy archives. */
		if ( is_tax() || is_category() || is_tag() ) {
			$supreme->context[] = 'taxonomy';
			$supreme->context[] = "taxonomy-{$object->taxonomy}";
			$slug = ( ( 'post_format' == $object->taxonomy ) ? str_replace( 'post-format-', '', $object->slug ) : $object->slug );
			$supreme->context[] = "taxonomy-{$object->taxonomy}-" . sanitize_html_class( $slug, $object->term_id );
		} elseif ( is_post_type_archive() ) {
			$post_type = get_post_type_object( get_query_var( 'post_type' ) );
		} elseif ( is_author() ) {
			$supreme->context[] = 'user';
			$supreme->context[] = 'user-' . sanitize_html_class( get_the_author_meta( 'user_nicename', $object_id ), $object_id );
		} else {
			if ( is_date() ) {
				$supreme->context[] = 'date';
				if ( is_year() ) {
					$supreme->context[] = 'year';
				}
				if ( is_month() ) {
					$supreme->context[] = 'month';
				}
				if ( get_query_var( 'w' ) ) {
					$supreme->context[] = 'week';
				}
				if ( is_day() ) {
					$supreme->context[] = 'day';
				}
			}
			if ( is_time() ) {
				$supreme->context[] = 'time';
				if ( get_query_var( 'hour' ) ) {
					$supreme->context[] = 'hour';
				}
				if ( get_query_var( 'minute' ) ) {
					$supreme->context[] = 'minute';
				}
			}
		} // End if().
	} elseif ( is_search() ) {
		$supreme->context[] = 'search';
	} elseif ( is_404() ) {
		$supreme->context[] = 'error-404';
	} // End if().

	return array_map( 'esc_attr', $supreme->context );
}
/**
 * Creates a set of classes for each site entry upon display. Each entry is given the class of
 * 'hentry'. Posts are given category, tag, and author classes. Alternate post classes of odd,
 * even, and alt are added.
 *
 * @param string  $class 		Class.
 * @param integer $post_id 		Post id.
 */
function supreme_entry_class( $class = '', $post_id = null ) {
	static $post_alt;
	$post = get_post( $post_id );
	/* Make sure we have a real post first. */
	if ( ! empty( $post ) ) {
		$post_id = $post->ID;
		/* Add hentry for microformats compliance, the post type, and post status. */
		$classes = array( 'hentry', $post->post_type, $post->post_status );
		/* Post alt class. */
		$classes[] = 'post-' . ( ++$post_alt );
		$classes[] = ( $post_alt % 2 ) ? 'odd' : 'even alt';
		/* Author class. */
		$classes[] = 'author-' . sanitize_html_class( get_the_author_meta( 'user_nicename' ), get_the_author_meta( 'ID' ) );
		/* Sticky class (only on home/blog page). */
		if ( is_home() && is_sticky() && ! is_paged() ) {
			$classes[] = 'sticky';
		}
		/* Password-protected posts. */
		if ( post_password_required() ) {
			$classes[] = 'protected';
		}
		/* Has excerpt. */
		if ( post_type_supports( $post->post_type, 'excerpt' ) && has_excerpt() ) {
			$classes[] = 'has-excerpt';
		}
		/* Has <!--more--> link. */
		if ( ! is_singular() && false !== strpos( $post->post_content, '<!--more-->' ) ) {
			$classes[] = 'has-more-link';
		}
		/* Post format. */
		if ( current_theme_supports( 'post-formats' ) && post_type_supports( $post->post_type, 'post-formats' ) ) {
			global $post_format;
			$post_format = get_post_format( $post_id );
			$classes[] = ( ( empty( $post_format ) || is_wp_error( $post_format ) ) ? 'format-standard' : "format-{$post_format}" );
		}

		/* Add category and post tag terms as classes. */
		if ( 'post' == $post->post_type ) {
			foreach ( array( 'category', 'post_tag' ) as $tax ) {
				foreach ( (array) get_the_terms( $post->ID, $tax ) as $term ) {
					if ( ! empty( $term->slug ) ) {
						$classes[] = $tax . '-' . sanitize_html_class( $term->slug, $term->term_id );
					}
				}
			}
		}
	} else {
		$classes = array( 'hentry', 'error' );
	} // End if().
	/* User-created classes. */
	if ( ! empty( $class ) ) {
		if ( ! is_array( $class ) ) {
			$class = preg_split( '#\s+#', $class );
		}
		$classes = array_merge( $classes, $class );
	}
	/* Apply the filters for WP's 'post_class'. */
	$classes = apply_filters( 'post_class', $classes, $class, $post_id );
	/* Join all the classes into one string and echo them. */
	$class = join( ' ', $classes );
	echo wp_kses_post( apply_atomic( 'entry_class', $class ) );
}
/**
 * Sets a class for each comment. Sets alt, odd/even, and author/user classes. Adds author, user,
 * and reader classes. Needs more work because WP, by default, assigns even/odd backwards
 * (Odd should come first, even second).
 *
 * @param string $class 		Class.
 */
function supreme_comment_class( $class = '' ) {
	global $post, $comment, $supreme;
	/* Gets default WP comment classes. */
	$classes = get_comment_class( $class );
	/* Get the comment type. */
	$comment_type = get_comment_type();
	/* If the comment type is 'pingback' or 'trackback', add the 'ping' comment class. */
	if ( 'pingback' == $comment_type || 'trackback' == $comment_type ) {
		$classes[] = 'ping';
	}
	/* User classes to match user role and user. */
	if ( $comment->user_id > 0 ) {
		/* Create new user object. */
		$user = new WP_User( $comment->user_id );
		/* Set a class with the user's role(s). */
		if ( is_array( $user->roles ) ) {
			foreach ( $user->roles as $role ) {
				$classes[] = sanitize_html_class( "role-{$role}" );
			}
		}
		/* Set a class with the user's name. */
		$classes[] = sanitize_html_class( "user-{$user->user_nicename}", "user-{$user->ID}" );
	} else {
		$classes[] = 'reader';
	}
	/* Comment by the entry/post author. */
	if ( get_post( $post_id ) == $post ) {
		if ( $comment->user_id === $post->post_author ) {
			$classes[] = 'entry-author';
		}
	}
	/* Get comment types that are allowed to have an avatar. */
	$avatar_comment_types = apply_filters( 'get_avatar_comment_types', array( 'comment' ) );
	/* If avatars are enabled and the comment types can display avatars, add the 'has-avatar' class. */
	if ( get_option( 'show_avatars' ) && in_array( $comment->comment_type, $avatar_comment_types ) ) {
		$classes[] = 'has-avatar';
	}
	/* Make sure comment classes doesn't have any duplicates. */
	$classes = array_unique( $classes );
	/* Join all the classes into one string and echo them. */
	$class = join( ' ', $classes );
	echo wp_kses_post( apply_filters( "{$supreme->prefix}_comment_class", $class ) );
}
/**
 * Provides classes for the <body> element depending on page context.
 *
 * @param string $class 		Class.
 */
function supreme_body_class( $class = '' ) {
	global $wp_query;
	/* Text direction (which direction does the text flow). */
	$classes = array( 'wordpress', is_rtl( 'text_direction' ), get_locale() );
	/* Check if the current theme is a parent or child theme. */
	$classes[] = ( is_child_theme() ? 'child-theme' : 'parent-theme' );
	/* Multisite check adds the 'multisite' class and the blog ID. */
	if ( is_multisite() ) {
		$classes[] = 'multisite';
		$classes[] = 'blog-' . get_current_blog_id();
	}
	/* Date classes. */
	$time = time() + ( get_option( 'gmt_offset' ) * 3600 );
	$classes[] = strtolower( gmdate( '\yY \mm \dd \hH l', $time ) );
	/* Is the current user logged in. */
	$classes[] = ( is_user_logged_in() ) ? 'logged-in' : 'logged-out';
	/* WP admin bar. */
	if ( is_admin_bar_showing() ) {
		$classes[] = 'admin-bar';
	}
	/* Use the '.custom-background' class to integrate with the WP background feature. */
	if ( get_background_image() || get_background_color() ) {
		$classes[] = 'custom-background';
	}
	/* Add the '.custom-header' class if the user is using a custom header. */
	if ( get_header_image() ) {
		$classes[] = 'custom-header';
	}
	$theme_options = get_option( supreme_prefix() . '_theme_settings' );
	$supreme_display_noimage = $theme_options['supreme_display_noimage'];

	if ( ! $supreme_display_noimage && is_category() ) {
		$classes[] = 'full-width-posts';
	}
	/* Merge base contextual classes with $classes. */
	$classes = array_merge( $classes, supreme_get_context() );
	/* Singular post (post_type) classes. */
	if ( is_singular() ) {
		/* Get the queried post object. */
		$post = get_queried_object();
		/* Checks for custom template. */
		$template = str_replace( array( "{$post->post_type}-template-", "{$post->post_type}-", '.php' ), '', get_post_meta( get_queried_object_id(), "_wp_{$post->post_type}_template", true ) );
		if ( ! empty( $template ) ) {
			$classes[] = "{$post->post_type}-template-{$template}";
		}

		/* Attachment mime types. */
		if ( is_attachment() ) {
			foreach ( explode( '/', get_post_mime_type() ) as $type ) {
				$classes[] = "attachment-{$type}";
			}
		}
	}
	/* Paged views. */
	if ( ( ( $page == $wp_query->get( 'paged' ) ) || ( $page == $wp_query->get( 'page' ) ) ) && $page > 1 ) {
		$classes[] = 'paged paged-' . intval( $page );
	}
	/* Input class. */
	if ( ! empty( $class ) ) {
		if ( ! is_array( $class ) ) {
			$class = preg_split( '#\s+#', $class );
		}
		$classes = array_merge( $classes, $class );
	}
	/* Apply the filters for WP's 'body_class'. */
	$classes = apply_filters( 'body_class', $classes, $class );
	/* Join all the classes into one string. */
	$class = join( ' ', $classes );
	/* Print the body class. */
	echo wp_kses_post( apply_atomic( 'body_class', $class ) );
}

/* pass theme title if all in one seo is not activate */

if ( ! is_plugin_active( 'all-in-one-seo-pack/all_in_one_seo_pack.php' ) && ! is_plugin_active( 'wordpress-seo/wp-seo.php' ) && ! is_plugin_active( 'wordpress-seo-premium/wp-seo-premium.php' ) ) {
	add_filter( 'wp_title', 'supreme_document_title' );
}
/* when seo plugin is activated */
if ( is_plugin_active( 'wordpress-seo/wp-seo.php' ) ) {
	add_filter( 'wp_title', 'templ_wp_document_title', 20 );

}

if ( ! function_exists( 'templ_wp_document_title' ) ) {
	/**
	 * Provides classes for the <body> element depending on page context.
	 *
	 * @param string $title 		header title when seo plugin is activate.
	 */
	function templ_wp_document_title( $title ) {
		if ( ( is_front_page() && is_home() || get_query_var( 'page_id' ) == get_option( 'page_on_front' ) ) && '' == $title ) {
			global $post;

			$title = WPSEO_Frontend::$instance->get_content_title( $post );

			if ( ( $modified_title && empty( $title ) ) || ! empty( $title_part ) ) {
				$title = $this->get_default_title( $separator, $separator_location, $title_part );
			}

			if ( defined( 'ICL_LANGUAGE_CODE' ) && false !== strpos( $title, ICL_LANGUAGE_CODE ) ) {
				$title = str_replace( ' @' . ICL_LANGUAGE_CODE, '', $title );
			}

			/**
			 * Filter: 'wpseo_title' - Allow changing the Yoast SEO <title> output
			 *
			 * @api string $title The page title being put out.
			 */

			return esc_html( strip_tags( stripslashes( apply_filters( 'wpseo_title', $title ) ) ) );
		}

		return $title;
	}
}
/**
 * Function for handling what the browser/search engine title should be. Attempts to handle every
 * possible situation WordPress throws at it for the best optimization.
 */
function supreme_document_title() {
	global $wp_query;
	/* Set up some default variables. */
	$doctitle = '';
	$separator = ':';
	$title = get_bloginfo( 'name' );
	$desc  = get_bloginfo( 'description' );

	/* If viewing the front page and posts page of the site. */
	if ( function_exists( 'icl_register_string' ) ) {
		icl_register_string( 'templatic', $title, $title );
		icl_register_string( 'templatic', $desc, $desc );
		$title1 = icl_t( 'templatic', $title, $title );
		$desc1 = icl_t( 'templatic', $desc, $desc );
	} else {
		$title1 = $title;
		$desc1 = $desc;
	}

	if ( is_front_page() && is_home() || get_query_var( 'page_id' ) == get_option( 'page_on_front' ) ) {
		$doctitle = $title1 . $separator . ' ' . $desc1;
	} elseif ( is_home() || is_singular() ) {
		$doctitle = get_post_meta( get_queried_object_id(), 'Title', true );
		if ( empty( $doctitle ) && is_front_page() ) {
			$doctitle = $title1 . $separator . ' ' . $desc1;
		} elseif ( empty( $doctitle ) ) {
			$doctitle = single_post_title( '', false );
			if ( empty( $doctitle ) && is_home() ) {
				$doctitle = $title1 . $separator . ' ' . $desc1;
			}
		}
	} elseif ( is_archive() ) {
		/* If viewing a taxonomy term archive. */
		if ( is_category() || is_tag() || is_tax() ) {
			remove_filter( 'single_term_title', 'tmpl_custom_page_title' );
			$doctitle = single_term_title( '', false );
			if ( function_exists( 'tmpl_custom_page_title' ) ) {
				add_filter( 'single_term_title', 'tmpl_custom_page_title' );
			}
		} elseif ( is_post_type_archive() ) {
			$post_type = get_post_type_object( get_query_var( 'post_type' ) );
			$doctitle = $post_type->labels->name;
		} elseif ( is_author() ) {
			$doctitle = get_user_meta( get_query_var( 'author' ), 'Title', true );
			if ( empty( $doctitle ) ) {
				$doctitle = get_the_author_meta( 'display_name', get_query_var( 'author' ) );
			}
		} elseif ( is_date() ) {
			if ( get_query_var( 'minute' ) && get_query_var( 'hour' ) ) {
				$doctitle = esc_html__( 'Archive for ', 'templatic' ) . get_the_time( esc_html__( 'g:i a', 'templatic' ) );
			} elseif ( get_query_var( 'minute' ) ) {
				$doctitle = esc_html__( 'Archive for minute ', 'templatic' ) . get_the_time( esC_html__( 'i', 'templatic' ) );
			} elseif ( get_query_var( 'hour' ) ) {
				$doctitle = esc_html__( 'Archive for ', 'templatic' ) . get_the_time( esc_html__( 'g a', 'templatic' ) );
			} elseif ( is_day() ) {
				$doctitle = esc_html__( 'Archive for ', 'templatic' ) . get_the_time( esc_html__( 'F jS, Y', 'templatic' ) );
			} elseif ( get_query_var( 'w' ) ) {
				$doctitle = esc_html__( 'Archive for week ', 'templatic' ) . ' ' . get_the_time( esc_html__( 'W', 'templatic' ) ) . esc_html__( 'of', 'templatic' ) . ' ' . get_the_time( esc_html__( 'Y', 'templatic' ) );
			} elseif ( is_month() ) {
				$doctitle = esc_html__( 'Archive for ', 'templatic' ) . single_month_title( ' ', false );
			} elseif ( is_year() ) {
				$doctitle = esc_html__( 'Archive for ', 'templatic' ) . get_the_time( esc_html__( 'Y', 'templatic' ) );
			}
		} else {
			$doctitle = esc_html__( 'Archives', 'templatic' );
		}
	} elseif ( is_search() ) {
		$doctitle = esc_html__( 'Search results for ', 'templatic' ) . '&quot;' . esc_attr( get_search_query() ) . '&quot;';
	} elseif ( is_404() ) {
		$doctitle = __( '404 Not Found', 'templatic' );
	} // End if().
	/* If the current page is a paged page. */
	if ( ( ( $page == $wp_query->get( 'paged' ) ) || ( $page == $wp_query->get( 'page' ) ) ) && $page > 1 ) {
		$doctitle = sprintf( esc_html__( '%1$s Page %2$s', 'templatic' ), $doctitle . $separator, number_format_i18n( $page ) );
	}
	/* Apply the wp_title filters so we're compatible with plugins. */

	if ( ( isset( $_REQUEST['pmethod'] ) && '' != $_REQUEST['pmethod'] ) || ( isset( $_REQUEST['paydeltype'] ) && '' != $_REQUEST['paydeltype'] ) ) {
		$doctitle = PAYMENT_SUCCESS_TITLE;
	} elseif ( isset( $_REQUEST['page'] ) && 'success' == $_REQUEST['page'] ) {
		$doctitle = esc_html__( 'Submitted Successfully', 'templatic' );
	}
	/* Trim separator + space from beginning and end in case a plugin adds it. */
	$doctitle = sanitize_text_field( $doctitle, "{$separator} " );
	/* Print the title to the screen. */
	echo wp_kses_post( apply_atomic( 'document_title', esc_attr( $doctitle ) ) );
}
