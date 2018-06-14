<?php
/**
 * This file containss all the functions related to display post metas like date, author, edit link etc.
 *
 * @package WordPress
 * @subpackage Directory
 */

add_action( 'init', 'supreme_init_filters' );
/**
 * Add entry-specific filters.
 */
function supreme_init_filters() {
	add_action( 'entry-title', 'supreme_entry_title' );
	add_action( 'entry-author', 'supreme_entry_author' );

	if ( supreme_get_settings( 'enable_comments_on_post' ) ) {
		add_action( 'entry-comments-link', 'supreme_entry_comments_link' );
	} else {
		remove_action( 'entry-comments-link' , 'supreme_entry_comments_link' );
	}
	add_action( 'entry-published', 'supreme_entry_published' );
	add_action( 'entry-edit-link', 'supreme_entry_edit_link' );
	add_action( 'entry-permalink', 'supreme_entry_permalink' );
}
/**
 * Displays the edit link for an individual post.
 *
 * @param array $args 			Edit Link.
 */
function supreme_entry_edit_link( $args ) {
	$post_type = get_post_type_object( get_post_type() );
	if ( ! current_user_can( $post_type->cap->edit_post, get_the_ID() ) ) {
		return '';
	}
	$args = wp_parse_args( array(
							'before' => '',
							'after' => ' ',
	), $args );
	echo wp_kses_post( $args['before'] . '&nbsp;<span class="edit"><a class="post-edit-link" href="' . esc_url( get_edit_post_link( get_the_ID() ) ) . '" title="' . sprintf( esc_attr__( 'Edit %1$s', 'templatic' ), $post_type->labels->singular_name ) . '">' . esc_html__( 'Edit', 'templatic' ) . '</a></span>' . $args['after'] );
}
/**
 * Displays the published date of an individual post.
 *
 * @param array $args 			Published Link.
 */
function supreme_entry_published( $args ) {
	$args = wp_parse_args( array(
								'before' => esc_html__( 'On', 'templatic' ) . ' ',
								'after' => ' ',
								'format' => get_option( 'date_format' ),
	), $args );
	$published = '<abbr class="published" title="' . sprintf( get_the_time( esc_attr__( 'l, F jS, Y, g:i a', 'templatic' ) ) ) . '">' . sprintf( get_the_time( $args['format'] ) ) . '</abbr>';
	echo wp_kses_post( $args['before'] . $published . $args['after'] );
}
/**
 * Displays a post's number of comments wrapped in a link to the comments area.
 *
 * @param array $args 			Number of comment wrapper.
 */
function supreme_entry_comments_link( $args ) {
	$comments_link = '';
	$number = doubleval( get_comments_number() );
	$args = shortcode_atts( array(
							'zero' => esc_html__( 'Leave a response', 'templatic' ),
							'one' => apply_filters( 'comment_response_link', esc_html__( '%1$s Response', 'templatic' ) ),
							'more' => esc_html__( '%1$s Responses', 'templatic' ),
							'css_class' => 'comments-link',
							'none' => '',
							'before' => '',
							'after' => '',
	), $args );
	if ( 0 == $number && ! comments_open() && ! pings_open() ) {
		if ( $args['none'] ) {
			$comments_link = '<span class="' . esc_attr( $args['css_class'] ) . '">' . sprintf( $args['none'], number_format_i18n( $number ) ) . '</span>';
		}
	} elseif ( 0 == $number ) {
		$comments_link = '<a class="' . esc_attr( $args['css_class'] ) . '" href="' . esc_url( get_permalink() ) . '#respond" title="' . sprintf( esc_attr__( 'Comment on %1$s', 'templatic' ), the_title_attribute( 'echo=0' ) ) . '">' . sprintf( $args['zero'], number_format_i18n( $number ) ) . '</a> ';
	} elseif ( 1 == $number ) {
		$comments_link = '<a class="' . esc_attr( $args['css_class'] ) . '" href="' . esc_url( get_comments_link() ) . '" title="' . sprintf( esc_attr__( 'Comment on %1$s', 'templatic' ), the_title_attribute( 'echo=0' ) ) . '">' . sprintf( $args['one'], number_format_i18n( $number ) ) . '</a> ';
	} elseif ( 1 < $number ) {
		$comments_link = '<a class="' . esc_attr( $args['css_class'] ) . '" href="' . esc_url( get_comments_link() ) . '" title="' . sprintf( esc_attr__( 'Comment on %1$s', 'templatic' ), the_title_attribute( 'echo=0' ) ) . '">' . sprintf( $args['more'], number_format_i18n( $number ) ) . '</a> ';
	}
	if ( $comments_link ) {
		$comments_link = $args['before'] . $comments_link . $args['after'];
	}
	echo wp_kses_post( $comments_link );
}
/**
 * Displays an individual post's author with a link to his or her archive.
 *
 * @param array $args 			Author Link wrapper.
 */
function supreme_entry_author( $args ) {
	$args = wp_parse_args( array(
							'before' => esc_html__( 'Published by', 'templatic' ) . ' ',
							'after' => ' ',
	), $args );
	$author = '<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '" title="' . esc_attr( get_the_author_meta( 'display_name' ) ) . '">' . get_the_author_meta( 'display_name' ) . '</a></span>';
	echo wp_kses_post( $args['before'] . $author . $args['after'] );
}
/**
 * Displays a post's title with a link to the post.
 *
 * @param array $args 			Title Link wrapper.
 */
function supreme_entry_title( $args ) {
	global $post;
	$args = wp_parse_args( array(
								'permalink' => true,
	), $args );
	$tag = ( is_single() || is_page() ) ? 'h1' : 'h2';
	$class = sanitize_html_class( get_post_type() ) . '-title entry-title';
	$title = the_title( "<{$tag} class='{$class}'><a href='" . esc_url( get_permalink() ) . "'>", "</a></{$tag}>", false );
	if ( empty( $title ) && ! is_single() && ! is_page() && ! is_404() && '' != $post->post_title ) {
		$title = "<{$tag} class='{$class}'><a href='" . esc_url( get_permalink() ) . "'>" . esc_html__( '(Untitled)', 'templatic' ) . "</a></{$tag}>";
	}

	if ( (is_single() || is_page() || is_404()) && '' != $post->post_title ) {
		$title = the_title( "<{$tag} class='{$class}'>", "</{$tag}>", false );
	}

	/* Added condition to not to show page title on success page. */
	if ( ! get_post_meta( $post->ID,'is_tevolution_success_page',true ) ) {
		echo wp_kses_post( $title );
	}
}
/**
 * Returns the output of the [entry-permalink] shortcode, which is a link back to the post permalink page.
 *
 * @param array $args 			Permalink wrapper.
 */
function supreme_entry_permalink( $args ) {
	$args = wp_parse_args( array(
							'before' => '',
							'after' => '',
	), $args );
	echo wp_kses_post( $args['before'] . '<a href="' . esc_url( get_permalink() ) . '" class="permalink">' . esc_html__( 'Permalink', 'templatic' ) . '</a>' . $args['after'] );
}

/**
 * Returns the output of the [entry-permalink] shortcode, which is a link back to the post permalink page.
 *
 * @param array $atts 			Shortcode Attributes.
 * @param array $content 		Content.
 */
function st_button( $atts, $content = null ) {
	extract( shortcode_atts( array(
								'link' => '',
								'size' => 'medium',
								'color' => '',
								'target' => '_self',
								'caption' => '',
								'align' => 'right',
	), $atts ) );
	$button = '';
	$button .= '<div class="button ' . $size . ' ' . $align . '">';
	$button .= '<a target="' . $target . '" class="button ' . $color . '" href="' . $link . '">';
	$button .= $content;
	if ( '' != $caption ) {
		$button .= '<br /><span class="btn_caption">' . $caption . '</span>';
	}
	$button .= '</a></div>';
	return $button;
}
add_shortcode( 'button', 'st_button' );

add_shortcode( 'tabgroup', 'st_tabgroup' );
/**
 * Returns the output of the Tab shortcode.
 *
 * @param array $atts 			Shortcode Attributes.
 * @param array $content 		Content.
 */
function st_tabgroup( $atts, $content ) {

	$GLOBALS['tab_count'] = 0;
	do_shortcode( $content );
	if ( is_array( $GLOBALS['tabs'] ) ) {

		foreach ( $GLOBALS['tabs'] as $tab ) {
			$tabs[] = '<li><a href="#' . $tab['id'] . '">' . $tab['title'] . '</a></li>';
			$panes[] = '<li id="' . $tab['id'] . 'Tab">' . $tab['content'] . '</li>';
		}
		$return = "\n" . '<!-- the tabs --><ul class="tabs">' . implode( "\n", $tabs ) . '</ul>' . "\n" . '<!-- tab "panes" --><ul class="tabs-content">' . implode( "\n", $panes ) . '</ul>' . "\n";
	}
	return $return;
}

add_shortcode( 'tab', 'st_tab' );
/**
 * Returns the output of the Tab shortcode.
 *
 * @param array $atts 			Shortcode Attributes.
 * @param array $content 		Content.
 */
function st_tab( $atts, $content ) {
	extract( shortcode_atts( array(
								'title' => '%d',
								'id' => '%d',
	), $atts ) );
	$x = $GLOBALS['tab_count'];
	$GLOBALS['tabs'][ $x ] = array(
								'title' => sprintf( $title, $GLOBALS['tab_count'] ),
								'content' => do_shortcode( $content ),
								'id' => $id,
								);
	$GLOBALS['tab_count']++;
}

/**
 * Returns the output of the Toggle shortcode.
 *
 * @param array $atts 			Shortcode Attributes.
 * @param array $content 		Content.
 */
function st_toggle( $atts, $content = null ) {
	extract( shortcode_atts( array(
								'title' => '',
								'style' => 'list',
	), $atts ) );
	output;
	$output .= '<div class="' . $style . '"><p class="trigger"><a href="#">' . $title . '</a></p>';
	$output .= '<div class="toggle_container"><div class="block">';
	$output .= do_shortcode( $content );
	$output .= '</div></div></div>';
	return $output;
}
add_shortcode( 'toggle', 'st_toggle' );

/**
 * Detail  post information, return authorname ,published name, comments link ,edit link and permalink.
 *
 * @param array $post 				Post Array.
 */
function supreme_core_post_info( $post ) {
	echo '<div class="byline">';
		$theme_options = get_option( supreme_prefix() . '_theme_settings' );

		do_action( 'entry-author' );

		$theme_options = get_option( supreme_prefix() . '_theme_settings' );

		/* display publish date */
		do_action( 'entry-published' );

		/* display comments link */
		do_action( 'entry-comments-link' );

		 do_action( 'entry-edit-link' );
	echo '</div>';

}
/**
 * Detail  post information, return authorname ,published name, comments link ,edit link and permalink for home page.
 */
function supreme_front_post_info() {
	global $post;
	echo '<div class="byline">';
	if ( supreme_get_settings( 'display_author_name' ) ) {
		do_action( 'entry-author' );
	}
	if ( supreme_get_settings( 'display_publish_date' ) ) {
		do_action( 'entry-published' );
	}
	do_action( 'entry-comments-link' );

	echo '</div>';
}

add_action( 'supreme-single-post-info' , 'supreme_single_post_info' );
/**
 * Detail  post information, return authorname ,published name, comments link ,edit link and permalink for detail page.
 */
function supreme_single_post_info() {
	echo '<div class="byline">';
	echo '<span>';
	do_action( 'entry-author' );
	echo '</span>';
	echo '<span>';
	do_action( 'entry-published' );
	echo '</span>';
	echo '<span>';
	do_action( 'entry-comments-link' );
	echo '</span>';
	echo '<span>';
	do_action( 'entry-edit-link' );
	echo '</span>';
	echo '<span>';
	do_action( 'entry-extra-parameters' );
	echo '</span>';

	echo '</div>';
}
/**
 * Detail  post information, return authorname ,published name and permalink for detail page.
 */
function supreme_gallery_post_info() {
		echo '<div class="byline">';
		do_action( 'entry-author' );
		do_action( 'entry-published' );
		do_action( 'entry-edit-link' );
		echo '</div>';
}
/**
 * Detail  post information, return authorname ,published name and permalink for listing page of post format status.
 */
function supreme_content_format_post_info() {
	echo '<div class="byline">';
		$display_author_name = supreme_get_settings( 'display_author_name' );
		$display_publish_date = supreme_get_settings( 'display_publish_date' );

		do_action( 'entry-author' );

		do_action( 'entry-published' );

		do_action( 'entry-comments-link' );

		do_action( 'entry-edit-link' );
	echo '</div>';
}

add_action( 'tmpl_author_meta' , 'tmpl_author_meta_fn' );
/**
 * To display the different post meta on author page.
 */
function tmpl_author_meta_fn() {
	/* Show published date only on author page. */
	echo '<div class="byline">';
	$theme_options = get_option( supreme_prefix() . '_theme_settings' );

	do_action( 'entry-published' );

	do_action( 'entry-edit-link' );

	echo '</div>';
}
