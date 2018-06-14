<?php
/**
 * Loop Error Template
 *
 * Displays an error message when no posts are found.
 *
 * @package WordPress
 * @subpackage Directory
 */

?>
<ul class="looperror clearfix">

	<div class="entry-summary">
		<p class="looperror_msg">
			<?php

			if ( isset( $_REQUEST['sort'] ) && 'favourites' == $_REQUEST['sort'] ) {
				esc_html_e( 'Whoops. Looks like there are no favourites available here.', 'templatic' );
			} else {
				esc_html_e( 'Whoops. Looks like there are no entries available here.', 'templatic' );
			}
			/* return the submit form link on author page */
			if ( function_exists( 'tmpl_get_submitfrm_link' ) ) {
				if ( isset( $_REQUEST['custom_post'] ) && '' != $_REQUEST['custom_post'] ) {

					echo wp_kses_post( tmpl_get_submitfrm_link( sanitize_text_field( wp_unslash( $_REQUEST['custom_post'] ) ) ) );

				}
			}
			?>
		</p>
	</div>
	<!-- .entry-summary -->
	<!-- .hentry .error -->
</ul>
