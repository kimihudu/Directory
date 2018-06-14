<?php
/**
 * Theme Layouts - A WordPress script for creating dynamic layouts.
 *
 * @package WordPress
 * @subpackage Directory
 */

/* Register metadata with WordPress. */
add_action( 'init', 'theme_layouts_register_meta' );
/* Add post type support for theme layouts. */
add_action( 'init', 'theme_layouts_add_post_type_support' );
/* Set up the custom post layouts. */
add_action( 'admin_menu', 'theme_layouts_admin_setup' );
/* Filters the body_class hook to add a custom class. */
add_filter( 'body_class', 'theme_layouts_body_class' );

/**
 * Registers the theme layouts meta key ('Layout') for specific object types and provides a function to sanitize
 * the metadata on update.
 */
function theme_layouts_register_meta() {
	register_meta( 'post', theme_layouts_get_meta_key(), 'theme_layouts_sanitize_meta' );
	register_meta( 'user', theme_layouts_get_meta_key(), 'theme_layouts_sanitize_meta' );
}

/**
 * Return sanitize value for layout.
 *
 * @param string $meta_value 				Return the meta value for layout.
 * @param string $meta_key 					Key to layouts.
 * @param string $meta_type 				Return a value or an array.
 */
function theme_layouts_sanitize_meta( $meta_value, $meta_key, $meta_type ) {
	return esc_attr( strip_tags( $meta_value ) );
}
/**
 * Adds post type support to all 'public' post types.  This allows themes to remove support for the 'theme-layouts'
 * feature with remove_post_type_support().
 */
function theme_layouts_add_post_type_support() {
	/* Gets available public post types. */
	$post_types = get_post_types( array(
									'public' => true,
								)
	);
	/* For each available post type, create a meta box on its edit page if it supports '$prefix-post-settings'. */
	foreach ( $post_types as $type ) {
		add_post_type_support( $type, 'templatic' );
	}
}
/**
 * Gets the layout for the current post based off the 'Layout' custom field key if viewing a singular post
 * entry.  All other pages are given a default layout of 'layout-default'.
 */
function theme_layouts_get_layout() {
	/* Get the available post layouts. */
	$post_layouts = get_theme_support( 'theme-layouts' );
	/* Set the layout to an empty string. */
	$layout = '';
	$multi_city = ($city_slug)? $city_slug : 'city';
	/* If viewing a singular post, check if a layout has been specified. */
	if ( ( is_home() && '' != get_option( 'page_for_posts' ) ) || ( get_query_var( 'page_id' ) == get_option( 'page_on_front' ) ) ) {
//	if ( strstr( $_SERVER['REQUEST_URI'],'/' . $multi_city . '/' ) && $wp_query->is_home || $wp_query->is_front_page ) {
		/* Get the current post ID. */
		$post_id = get_query_var( 'page_id' );
		/* Get the post layout. */
		$layout = get_post_layout( $post_id );
	}elseif ( is_singular() ) { 
			/* Get the current post ID. */
		$post_id = get_queried_object_id();
		/* Get the post layout. */
		$layout = get_post_layout( $post_id );
	}elseif ( is_author() ) {
		/* Get the current user ID. */
		$user_id = get_queried_object_id();
		/* Get the user layout. */
		$layout = get_user_layout( $user_id );
	}
	/* Make sure the given layout is in the array of available post layouts for the theme. */
	if ( empty( $layout ) || ! in_array( $layout, $post_layouts[0] ) ) {
		$layout = 'default';
	}
	/* If the theme set a default layout, use it if the layout should be set to default. */
	if ( 'default' == $layout && ! empty( $post_layouts[1] ) && isset( $post_layouts[1]['default'] ) ) {
		$layout = $post_layouts[1]['default'];
	}
	/* @deprecated 0.2.0. Use the 'get_theme_layout' hook. */
	$layout = apply_filters( 'get_post_layout', "layout-{$layout}" );
	/* Return the layout and allow plugin/theme developers to override it. */
	return esc_attr( apply_filters( 'get_theme_layout', $layout ) );
}
/**
 * Get the post layout based on the given post ID.
 *
 * @param int $post_id 			Post ID.
 */
function get_post_layout( $post_id ) {
	/* Get the post layout. */
	$layout = get_post_meta( $post_id, theme_layouts_get_meta_key(), true );
	/* Return the layout if one is found.  Otherwise, return 'default'. */
	return ( ! empty( $layout ) ? $layout : 'default' );
}
/**
 * Update/set the post layout based on the given post ID and layout.
 *
 * @param int    $post_id 			Post ID.
 * @param string $layout 			Page layout.
 */
function set_post_layout( $post_id, $layout ) {
	return update_post_meta( $post_id, theme_layouts_get_meta_key(), $layout );
}
/**
 * Deletes a post layout.
 *
 * @param int $post_id 				Post ID.
 */
function delete_post_layout( $post_id ) {
	return delete_post_meta( $post_id, theme_layouts_get_meta_key() );
}
/**
 * Checks if a specific post's layout matches that of the given layout.
 *
 * @param string $layout 			Page layout.
 * @param int    $post_id 			Post ID.
 */
function has_post_layout( $layout, $post_id = '' ) {
	/* If no post ID is given, use WP's get_the_ID() to get it and assume we're in the post loop. */
	if ( empty( $post_id ) ) {
		$post_id = get_the_ID();
	}
	/* Return true/false based on whether the layout matches. */
	return ( $layout == get_post_layout( $post_id ) ? true : false );
}
/**
 * Get the layout for a user/author archive page based on a specific user ID.
 *
 * @param int $user_id 			User ID.
 */
function get_user_layout( $user_id ) {
	/* Get the user layout. */
	$layout = get_user_meta( $user_id, theme_layouts_get_meta_key(), true );
	/* Return the layout if one is found.  Otherwise, return 'default'. */
	return ( ! empty( $layout ) ? $layout : 'default' );
}
/**
 * Update/set the layout for a user/author archive paged based on the user ID.
 *
 * @param int    $user_id 			User ID.
 * @param string $layout 			Page layout.
 */
function set_user_layout( $user_id, $layout ) {
	return update_user_meta( $user_id, theme_layouts_get_meta_key(), $layout );
}
/**
 * Deletes a user layout.
 *
 * @param int $user_id 			User ID.
 */
function delete_user_layout( $user_id ) {
	return delete_user_meta( $user_id, theme_layouts_get_meta_key() );
}
/**
 * Checks if a specific user's layout matches that of the given layout.
 *
 * @param string $layout 			Page layout.
 * @param int    $user_id 			User ID.
 */
function has_user_layout( $layout, $user_id = '' ) {
	/* If no user ID is given, assume we're viewing an author archive page and get the user ID. */
	if ( empty( $user_id ) ) {
		$user_id = get_query_var( 'author' );
	}
	/* Return true/false based on whether the layout matches. */
	return ( $layout == get_user_layout( $user_id ) ? true : false );
}
/**
 * Adds the post layout class to the WordPress body class in the form of "layout-$layout".  This allows
 * theme developers to design their theme layouts based on the layout class.  If designing a theme with
 * this extension, the theme should make sure to handle all possible layout classes.
 *
 * @param string $classes 			Adds the class to the body tag.
 */
function theme_layouts_body_class( $classes ) {
	/* Adds the layout to array of body classes. */
	$classes[] = sanitize_html_class( theme_layouts_get_layout() );
	/* Return the $classes array. */
	return $classes;
}
/**
 * Creates default text strings based on the default post layouts.  Theme developers that add custom
 * layouts should filter 'post_layouts_strings' to add strings to match the custom layouts, but it's not
 * required.  The layout name will be used if no text string is found.
 */
function theme_layouts_strings() {
	/* Set up the default layout strings. */
	$strings = array(
		'default' 	=> esc_html__( 'Default', 'templatic' ),
		'1c'		=> esc_html__( 'One Column', 'templatic' ),
		'2c-l' 		=> esc_html__( 'Two Columns, Left', 'templatic' ),
		'2c-r' 		=> esc_html__( 'Two Columns, Right', 'templatic' ),
		'3c-l' 		=> esc_html__( 'Three Columns, Left', 'templatic' ),
		'3c-r' 		=> esc_html__( 'Three Columns, Right', 'templatic' ),
		'3c-c' 		=> esc_html__( 'Three Columns, Center', 'templatic' ),
	);
	/* Allow devs to filter the strings for custom layouts. */
	return apply_filters( 'theme_layouts_strings', $strings );
}
/**
 * Get a specific layout's text string.
 *
 * @param string $layout 			Layout.
 */
function theme_layouts_get_string( $layout ) {
	/* Get an array of post layout strings. */
	$strings = theme_layouts_strings();
	/* Return the layout's string if it exists. Else, return the layout slug. */
	return ( ( isset( $strings[ $layout ] ) ) ? $strings[ $layout ] : $layout );
}
/**
 * Post layouts admin setup.  Registers the post layouts meta box for the post editing screen.  Adds the
 * metadata save function to the 'save_post' hook.
 */
function theme_layouts_admin_setup() {
	/* Load the post meta boxes on the new post and edit post screens. */
	add_action( 'load-post.php', 'theme_layouts_load_meta_boxes' );
	add_action( 'load-post-new.php', 'theme_layouts_load_meta_boxes' );
	/* If the attachment post type supports 'theme-layouts', add form fields for it. */
	if ( post_type_supports( 'attachment', 'theme-layouts' ) ) {
		/* Adds a theme layout <select> element to the attachment edit form. */
		add_filter( 'attachment_fields_to_edit', 'theme_layouts_attachment_fields_to_edit', 10, 2 );
		/* Saves the theme layout for attachments. */
		add_filter( 'attachment_fields_to_save', 'theme_layouts_attachment_fields_to_save', 10, 2 );
	}
}
/**
 * Hooks into the 'add_meta_boxes' hook to add the theme layouts meta box and the 'save_post' hook
 * to save the metadata.
 */
function theme_layouts_load_meta_boxes() {
	/* Add the layout meta box on the 'add_meta_boxes' hook. */
	add_action( 'add_meta_boxes', 'theme_layouts_add_meta_boxes', 10, 2 );
	/* Saves the post format on the post editing page. */
	add_action( 'save_post', 'theme_layouts_save_post', 10, 2 );
}
/**
 * Adds the theme layouts meta box if the post type supports 'theme-layouts' and the current user has
 * permission to edit post meta.
 *
 * @param string $post_type 				Listing Post type.
 * @param array  $post 						Post Array.
 */
function theme_layouts_add_meta_boxes( $post_type, $post ) {
	/* Add the meta box if the post type supports 'post-stylesheets'. */
	if ( $post_type != 'admanager' ) {
		add_post_type_support( $post_type, 'theme-layouts' );
	}
	if ( ( post_type_supports( $post_type, 'theme-layouts' ) ) && ( current_user_can( 'edit_post_meta', $post->ID ) || current_user_can( 'add_post_meta', $post->ID ) || current_user_can( 'delete_post_meta', $post->ID ) ) ) {
		add_meta_box( 'theme-layouts-post-meta-box', __( 'Layout', 'templatic' ), 'theme_layouts_post_meta_box', $post_type, 'side', 'default' );
	}
}
/**
 * Displays a meta box of radio selectors on the post editing screen, which allows theme users to select
 * the layout they wish to use for the specific post.
 *
 * @param array  $post 				Post Array.
 * @param string $box 				Post meta box.
 */
function theme_layouts_post_meta_box( $post, $box ) {
	/* Get theme-supported theme layouts. */
	$layouts = get_theme_support( 'theme-layouts' );
	$post_layouts = $layouts[0];
	/* Get the current post's layout. */
	$post_layout = get_post_layout( $post->ID ); ?>
	<div class="post-layout">
		<?php wp_nonce_field( basename( __FILE__ ), 'theme-layouts-nonce' ); ?>
	  <p>
		<?php echo __( 'Layout is a theme-specific structure for the single view of the post.', 'templatic-admin' ); ?>
	  </p>
	  <div class="post-layout-wrap">
		<ul>
		  <li>
			<input type="radio" name="post-layout" id="post-layout-default" value="default" <?php checked( $post_layout, 'default' );?> />
			<label for="post-layout-default"><?php echo esc_html( theme_layouts_get_string( 'default' ) ); ?></label>
		  </li>
			<?php foreach ( $post_layouts as $layout ) { ?>
		  <li>
			<input type="radio" name="post-layout" id="post-layout-<?php echo esc_attr( $layout ); ?>" value="<?php echo esc_attr( $layout ); ?>" <?php checked( $post_layout, $layout ); ?> />
			<label for="post-layout-<?php echo esc_attr( $layout ); ?>"><?php echo esc_html( theme_layouts_get_string( $layout ) ); ?></label>
		  </li>
			<?php } ?>
		</ul>
	  </div>
	</div>
<?php
}
/**
 * Saves the post layout metadata if on the post editing screen in the admin.
 *
 * @param string $post_id 			Post id.
 * @param array  $post 				Post Array.
 */
function theme_layouts_save_post( $post_id, $post ) {
	/* Verify the nonce for the post formats meta box. */
	if ( ! isset( $_POST['theme-layouts-nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['theme-layouts-nonce'] ) ) , basename( __FILE__ ) ) ) {
		return $post_id;
	}
	/* Get the meta key. */
	$meta_key = theme_layouts_get_meta_key();
	/* Get the previous post layout. */
	$meta_value = get_post_layout( $post_id );
	/* Get the submitted post layout. */
	if ( isset( $_POST['post-layout'] ) && '' != $_POST['post-layout'] ) {
		$new_meta_value = sanitize_text_field( wp_unslash( $_POST['post-layout'] ) );
	}
	/* If there is no new meta value but an old value exists, delete it. */
	if ( current_user_can( 'delete_post_meta', $post_id, $meta_key ) && '' == $new_meta_value && $meta_value ) {
		delete_post_layout( $post_id );
	} // End if().

	elseif ( current_user_can( 'add_post_meta', $post_id, $meta_key ) && $new_meta_value && '' == $meta_value ) {
		set_post_layout( $post_id, $new_meta_value );
	} /* If the old layout doesn't match the new layout, update the post layout meta. */
	elseif ( current_user_can( 'edit_post_meta', $post_id, $meta_key ) && $meta_value !== $new_meta_value ) {
		set_post_layout( $post_id, $new_meta_value );
	}
}
/**
 * Adds a select drop-down element to the attachment edit form for selecting the attachment layout.
 *
 * @param array $fields 			Custom fields Array.
 * @param array $post 				Post Array.
 */
function theme_layouts_attachment_fields_to_edit( $fields, $post ) {
	/* Get theme-supported theme layouts. */
	$layouts = get_theme_support( 'theme-layouts' );
	$post_layouts = $layouts[0];
	/* Get the current post's layout. */
	$post_layout = get_post_layout( $post->ID );
	/* Set the default post layout. */
	$select = '<option id="post-layout-default" value="default" ' . selected( $post_layout, 'default', false ) . '>' . esc_html( theme_layouts_get_string( 'default' ) ) . '</option>';
	/* Loop through each theme-supported layout, adding it to the select element. */
	foreach ( $post_layouts as $layout ) {
		$select .= '<option id="post-layout-' . esc_attr( $layout ) . '" value="' . esc_attr( $layout ) . '" ' . selected( $post_layout, $layout, false ) . '>' . esc_html( theme_layouts_get_string( $layout ) ) . '</option>';
	}
	/* Set the HTML for the post layout select drop-down. */
	$select = '<select name="attachments[' . $post->ID . '][theme-layouts-post-layout]" id="attachments[' . $post->ID . '][theme-layouts-post-layout]">' . $select . '</select>';
	/* Add the attachment layout field to the $fields array. */
	$fields['theme-layouts-post-layout'] = array(
		'label' => __( 'Layout', 'templatic' ),
		'input' => 'html',
		'html' => $select,
	);
	/* Return the $fields array back to WordPress. */
	return $fields;
}
/**
 * Saves the attachment layout for the attachment edit form.
 *
 * @param array $post 				Post Array.
 * @param array $fields 			Custom fields Array.
 */
function theme_layouts_attachment_fields_to_save( $post, $fields ) {
	/* If the theme layouts field was submitted. */
	if ( isset( $fields['theme-layouts-post-layout'] ) ) {
		/* Get the meta key. */
		$meta_key = theme_layouts_get_meta_key();
		/* Get the previous post layout. */
		$meta_value = get_post_layout( $post['ID'] );
		/* Get the submitted post layout. */
		$new_meta_value = $fields['theme-layouts-post-layout'];
		/* If there is no new meta value but an old value exists, delete it. */
		if ( current_user_can( 'delete_post_meta', $post['ID'], $meta_key ) && '' == $new_meta_value && $meta_value ) {
			delete_post_layout( $post['ID'] );
		} // End if().

		elseif ( current_user_can( 'add_post_meta', $post['ID'], $meta_key ) && $new_meta_value && '' == $meta_value ) {
			set_post_layout( $post['ID'], $new_meta_value );
		} /* If the old layout doesn't match the new layout, update the post layout meta. */
		elseif ( current_user_can( 'edit_post_meta', $post['ID'], $meta_key ) && $meta_value !== $new_meta_value ) {
			set_post_layout( $post['ID'], $new_meta_value );
		}
	}
	/* Return the attachment post array. */
	return $post;
}
/**
 * Wrapper function for returning the metadata key used for objects that can use layouts.
 */
function theme_layouts_get_meta_key() {
	return apply_filters( 'theme_layouts_meta_key', 'Layout' );
}

/**
 * Return Page Layout.
 */
function post_layouts_get_layout() {
	return theme_layouts_get_layout();
}
