<?php
/**
 * Loop Meta Template
 *
 * Displays information at the top of the page about archive and search results when viewing those pages.
 * This is not shown on the front page or singular views.
 *
 * @package WordPress
 * @subpackage Directory
 */

?>
<?php if ( is_home() && ! is_front_page() ) : ?>
	<div class="loop-meta">
		<h1 class="loop-title"><?php echo wp_kses_post( get_post_field( 'post_title', get_queried_object_id() ) ); ?></h1>
		<div class="loop-description"> <?php echo wp_kses_post( apply_filters( 'the_excerpt', wp_kses_post( get_post_field( 'post_excerpt', get_queried_object_id() ) ) ) ); ?> </div>
		<!-- .loop-description -->
	</div>
	<!-- .loop-meta -->
<?php elseif ( is_category() ) : ?>
	<div class="loop-meta">
		<h1 class="loop-title">
			<?php single_cat_title(); ?>
		</h1>
		<div class="loop-description"> <?php echo category_description(); ?> </div>
		<!-- .loop-description -->
	</div>
	<!-- .loop-meta -->
<?php elseif ( is_tag() ) : ?>
	<div class="loop-meta">
		<h1 class="loop-title">
			<?php single_tag_title(); ?>
		</h1>
		<div class="loop-description"> <?php echo tag_description(); ?> </div>
		<!-- .loop-description -->
	</div>
	<!-- .loop-meta -->
<?php elseif ( is_tax() ) : ?>
	<div class="loop-meta">
		<h1 class="loop-title">
			<?php single_term_title(); ?>
		</h1>
		<div class="loop-description"> <?php echo term_description( '', get_query_var( 'taxonomy' ) ); ?> </div>
		<!-- .loop-description -->
	</div>
	<!-- .loop-meta -->
<?php elseif ( is_author() ) : ?>
	<?php $user_id = get_query_var( 'author' ); ?>
	<!-- Display author box on author dashboard -->
	<div id="hcard-<?php the_author_meta( 'user_nicename', $user_id ); ?>" class="loop-meta vcard clearfix">
		<h1 class="loop-title fn n">
			<?php the_author_meta( 'display_name', $user_id ); ?>
		</h1>
		<div class="author_photo">
			<?php $curauth = get_userdata( $user_id );

			if ( get_current_user_id() == $user_id ) :
				$profile_page_id = get_option( 'tevolution_profile' );
				$profile_url = get_permalink( $profile_page_id );
				if ( '' != $profile_url ) :
				?>
			<div class="editProfile"><a href="<?php echo esc_url( $profile_url );?>" ><?php echo esc_attr( PROFILE_EDIT_TEXT );?> </a> </div>
		<?php endif;?>
	<?php endif; ?>
</div>
<div class="author_content">
	<div class="agent_biodata">
		<?php
		global $form_fields_usermeta;
		if ( is_array( $form_fields_usermeta ) && ! empty( $form_fields_usermeta ) ) {
			foreach ( $form_fields_usermeta as $key => $_form_fields_usermeta ) {
				if ( '' != get_user_meta( $user_id, $key, true ) ) :
					if ( $_form_fields_usermeta['on_author_page'] ) :
						if ( 'upload' != $_form_fields_usermeta['type'] ) :
							if ( 'multicheckbox' == $_form_fields_usermeta['type'] ) :  ?>
						<?php
						$checkbox = '';
						foreach ( get_user_meta( $user_id, $key, true ) as $check ) :
							$checkbox .= $check . ',';
						endforeach; ?>
						<p>
							<label><?php echo wp_kses_post( $_form_fields_usermeta['label'] ); ?></label>
							: <?php echo wp_kses_post( substr( $checkbox, 0, -1 ) ); ?></p>
						<?php else : ?>
							<p>
								<label><?php echo wp_kses_post( $_form_fields_usermeta['label'] ); ?></label>
								: <?php echo wp_kses_post( get_user_meta( $user_id, $key, true ) ); ?></p>
							<?php endif;
							endif;
						if ( 'upload' == $_form_fields_usermeta['type'] ) {?>
							<p>
								<label  style='vertical-align:top;'><?php echo wp_kses_post( $_form_fields_usermeta['label'] ) . ' : '; ?></label>
								<img src="<?php echo esc_url( get_user_meta( $user_id, $key, true ) );?>" style="width:150px;height:150px" /></p>
								<?php }
								endif;
								endif;
			}
		} // End if(). ?>
						<?php
						if ( $curauth->user_url ) :
							$website = $curauth->user_url;
							$facebook = $curauth->facebook;
							$twitter = $curauth->twitter;
							if ( ! strstr( $website, 'http' ) ) {
								$website = 'http://' . $curauth->user_url;	?>
								<span><a href="<?php echo esc_url( $website ); ?>" target="_blank">
								<?php esc_html_e( 'Link', 'templatic' );?>
								</a></span>
						<?php } if ( $facebook ) { ?>
								<span><a href="<?php echo esc_url( $facebook ); ?>" target="_blank">
								<?php esc_html_e( 'Facebook', 'templatic' );?>
								</a></span>
						<?php }
if ( $twitter ) { ?>
	<span><a href="<?php echo esc_url( $twitter ); ?>" target="_blank">
		<?php esc_html_e( 'Twitter', 'templatic' );?>
	</a></span>
<?php } ?>
<br class="clearfix"  />
<?php endif; // Finish check current author user url.

						do_action( 'supreme_author_info' ); // Use this action to show total posting of users or.....
						/* Payment type details */
						$price_pkg = get_user_meta( $curauth->ID, 'package_select', true );
						$pagd_data = get_post( $price_pkg );
						$package_name = $pagd_data->post_title;
						$pkg_type = get_post_meta( $price_pkg, 'package_type', true );
						$limit_no_post = get_post_meta( $price_pkg, 'limit_no_post' ,true );

						$submited = get_user_meta( $curauth->ID, 'list_of_post', true );
						$remaining = intval( $limit_no_post ) - intval( $submited );
						if ( 2 == $pkg_type ) {
							echo '<p>';
							esc_html_e( 'You have subscribed to ', 'templatic' ) . '<strong>' . $package_name . '</strong> ';
							esc_html_e( ' which allows you to submit ', 'templatic' ) . ' <strong>' . $limit_no_post . '</strong>  events';
							$msg = '';
							if ( $remaining > 0 ) {
								esc_html_e( ' You have submitted ', 'templatic' ) . '<strong>' . $submited . ' </strong>';
								esc_html_e( 'events till now, go and submit remaining ', 'templatic' ) . '<strong>' . $remaining . ' </strong>';

							} else {
								esc_html_e( ' and you have already submitted.', 'templatic' );
								echo '.<strong>' . intval( $limit_no_post ) . '</strong> ';
								esc_html_e( 'to continue the listing Click on add/submit events.', 'templatic' );
							}

							echo '.</p>';
						} // End if().
?>
</div>
<?php  $desc = get_the_author_meta( 'description', $user_id ); ?>
<?php if ( ! empty( $desc ) ) { ?>
<?php echo get_avatar( get_the_author_meta( 'user_email', $user_id ), '60', '', get_the_author_meta( 'display_name', $user_id ) ); ?>
<p class="user-bio"> <?php echo wp_kses_post( $desc ); ?> </p>
<!-- .user-bio -->
<?php } ?>
</div>
</div>
<!-- Display author box on author dashboard -->
<?php elseif ( is_search() ) : ?>
	<div class="loop-meta extra-search-criteria-title">
		<h1 class="loop-title"><?php
			$location = '';
		if ( isset( $_REQUEST['location'] ) && '' != $_REQUEST['location'] ) {
			$location = sanitize_text_field( wp_unslash( $_REQUEST['location'] ) );
		}
			$search_text = ( ' ' != get_search_query() ) ? get_search_query() : $location;
			esc_html_e( 'Search results for', 'templatic' );
			echo ' "' . wp_kses_post( $search_text ) . '"'; ?></h1>
			<?php do_action( 'after_search_result_label' ); ?>
			<div class="loop-description no_search_found_msg">
				<?php
				global $current_cityinfo;
				if ( ( isset( $_REQUEST['radius'] ) && '' != $_REQUEST['radius'] ) || ( isset( $_REQUEST['location'] ) && '' != $_REQUEST['location'] ) ) {
					if ( isset( $_REQUEST['radius'] ) && 1 == $_REQUEST['radius'] ) {
						$radius_type = ( isset( $_REQUEST['radius_type'] ) && 'kilometer' == $_REQUEST['radius_type'] ) ? 'kilometer': 'mile';
					}
					if ( isset( $_REQUEST['radius'] ) && 1 != $_REQUEST['radius'] && '' != $_REQUEST['radius'] ) {
						$radius_type = ( isset( $_REQUEST['radius_type'] ) && 'kilometer' == $_REQUEST['radius_type'] ) ? 'kilometers': 'miles';
					}
					$radius = ( isset( $_REQUEST['location'] ) && '' != $_REQUEST['location'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['radius'] ) ) . ' ' . $radius_type . ' around "' . sanitize_text_field( wp_unslash( $_REQUEST['location'] ) ) . '"' : sanitize_text_field( wp_unslash( $_REQUEST['radius'] ) ) . ' ' . $radius_type . ' around "' . $current_cityinfo['cityname'] . '"';
				}
				if ( isset( $radius ) ) {
					?>
					<p><?php esc_html_e( 'You are browsing the search results for ', 'templatic' ) . '<strong>';
					esc_attr( get_search_query() ) . '</strong> <span>' . $radius . '</span>'; ?></p>
					<?php } ?>
				</div>
				<!-- .loop-description -->
			</div>
			<!-- .loop-meta -->
		<?php elseif ( is_date() ) : ?>
			<div class="loop-meta">
				<?php if ( is_day() ) : ?>
					<h1 class="loop-title"><?php esc_html_e( 'Daily Archives: ', 'templatic' ) . '<span>' . get_the_date() . '</span>';?></h1>
					<div class="loop-description">
						<p><?php esc_html_e( 'You are browsing the site archives by.', 'templatic' ) . '<span>' . get_the_date() . '</span>'; ?></p>
					</div>
					<!-- .loop-description -->
				<?php elseif ( is_month() ) :?>
					<h1 class="loop-title"><?php esc_html_e( 'Monthly Archives:', 'templatic' ) . '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'templatic' ) ) . '</span>';?></h1>
					<div class="loop-description">
						<p><?php esc_html_e( 'You are browsing the site archives by.', 'templatic' ) . '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'templatic' ) ) . '</span>'; ?></p>
					</div>
					<!-- .loop-description -->
				<?php elseif ( is_year() ) :?>
					<h1 class="loop-title"><?php esc_html_e( 'Yearly Archives:', 'templatic' ) . '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'templatic' ) ) . '</span>' ; ?></h1>
					<div class="loop-description">
						<p><?php esc_html_e( 'You are browsing the site archives by.', 'templatic' ) . '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'templatic' ) ) . '</span>' ; ?></p>
					</div>
					<!-- .loop-description -->
				<?php endif;?>
			</div>
			<!-- .loop-meta -->
		<?php elseif ( function_exists( 'is_post_type_archive' ) && is_post_type_archive() ) : ?>
			<?php $post_type = get_post_type_object( get_query_var( 'post_type' ) ); ?>
			<div class="loop-meta">
				<h1 class="loop-title">
					<?php post_type_archive_title(); ?>
				</h1>
				<div class="loop-description">
					<?php if ( ! empty( $post_type->description ) ) {
						echo wp_kses_post( "<p>{$post_type->description}</p>" );
} ?>
				</div>
				<!-- .loop-description -->
			</div>
			<!-- .loop-meta -->
		<?php elseif ( is_archive() ) : ?>
			<div class="loop-meta">
				<h1 class="loop-title">
					<?php esc_html_e( 'Archives', 'templatic' ); ?>
				</h1>
				<div class="loop-description">
					<p>
						<?php esc_html_e( 'You are browsing the site archives.', 'templatic' ); ?>
					</p>
				</div>
				<!-- .loop-description -->
			</div>
			<!-- .loop-meta -->
		<?php endif; ?>
