<?php
/**
 * Templatic theme options page template.
 *
 * @package WordPress
 * @subpackage Directory
 */

if ( isset( $_POST['theme_options_nonce'] ) && '' != $_POST['theme_options_nonce'] ) {
	if ( wp_verify_nonce( @$_POST['theme_options_nonce'], basename( __FILE__ ) ) ) {
		if ( function_exists( 'supreme_prefix' ) ) {
			$pref = supreme_prefix();
		} else {
			$pref = sanitize_key( apply_filters( 'hybrid_prefix', get_template() ) );
		}
		$theme_options = get_option( $pref . '_theme_settings' );
		foreach ( $_POST as $key => $value ) {
			if ( 'theme_options_nonce' != $key && 'Submit' != $key && 'hide_ajax_notification' != $key ) {
				$theme_options[ $key ] = $value;
			}
		}
		$theme_options['supreme_global_layout'] = ( $_POST['supreme_global_layout'] ) ? sanitize_text_field( $_POST['supreme_global_layout'] ) : '';
		$theme_options['customcss'] = ( $_POST['customcss'] ) ? sanitize_text_field( $_POST['customcss'] ) : '';
		$theme_options['rtlcss'] = ( $_POST['rtlcss'] ) ? sanitize_text_field( $_POST['rtlcss'] ) : '';
		$theme_options['enable_sticky_header_menu'] = ( $_POST['enable_sticky_header_menu'] ) ? sanitize_text_field( $_POST['enable_sticky_header_menu'] ) : '';
		$theme_options['supreme_author_bio_pages'] = ( $_POST['supreme_author_bio_pages'] ) ? sanitize_text_field( $_POST['supreme_author_bio_pages'] ) : '';
		$theme_options['supreme_show_breadcrumb'] = ( $_POST['supreme_show_breadcrumb'] ) ? sanitize_text_field( $_POST['supreme_show_breadcrumb'] ) : '';
		$theme_options['supreme_global_contactus_captcha'] = ( $_POST['supreme_global_contactus_captcha'] ) ? sanitize_text_field( $_POST['supreme_global_contactus_captcha'] ) : '';
		$theme_options['enable_inquiry_form'] = ( $_POST['enable_inquiry_form'] ) ? sanitize_text_field( $_POST['enable_inquiry_form'] ) : '';
		$theme_options['post_type_label'] = ( $_POST['post_type_label'] ) ? sanitize_text_field( $_POST['post_type_label'] ) : '';
		$theme_options['supreme_gogle_analytics_code'] = ( $_POST['supreme_gogle_analytics_code'] ) ? ( $_POST['supreme_gogle_analytics_code'] ) : '';
		$theme_options['supreme_archive_display_excerpt'] = ( $_POST['supreme_archive_display_excerpt'] ) ? sanitize_text_field( $_POST['supreme_archive_display_excerpt'] ) : '';
		$theme_options['templatic_excerpt_length'] = ( $_POST['templatic_excerpt_length'] ) ? sanitize_text_field( $_POST['templatic_excerpt_length'] ) : '';
		$theme_options['templatic_excerpt_link'] = ( $_POST['templatic_excerpt_link'] ) ? sanitize_text_field( $_POST['templatic_excerpt_link'] ) : '';
		$theme_options['enable_comments_on_page'] = ( $_POST['enable_comments_on_page'] ) ? sanitize_text_field( $_POST['enable_comments_on_page'] ) : '';
		$theme_options['enable_comments_on_post'] = ( $_POST['enable_comments_on_post'] ) ? sanitize_text_field( $_POST['enable_comments_on_post'] ) : '';
		$theme_options['tmpl_mobile_view'] = ( $_POST['tmpl_mobile_view'] ) ? sanitize_text_field( $_POST['tmpl_mobile_view'] ) : '0';


		if ( ! $_POST['hide_ajax_notification'] ) {
			$_POST['hide_ajax_notification'] = 0;
		}

		update_option( 'hide_ajax_notification', wp_kses_post( $_POST['hide_ajax_notification'] ) );
		update_option( $pref . '_theme_settings', $theme_options );
		wp_safe_redirect( admin_url( 'themes.php?page=theme-settings-page&updated=1' ) );
	} else {
		wp_die( esc_html__( 'You do not have permission to edit theme settings.', 'templatic-admin' ) );
	} // End if().
} // End if().

if ( ! function_exists( 'theme_settings_page_callback' ) ) {
	/**
	 * To display theme setting options in appearance -> theme settings
	 */
	function theme_settings_page_callback() {
		if ( function_exists( 'supreme_prefix' ) ) {
			$pref = supreme_prefix();
		} else {
			$pref = sanitize_key( apply_filters( 'hybrid_prefix', get_template() ) );
		}
		$theme_settings = get_option( $pref . '_theme_settings' );
		?>
		<div class="wrap">
			<form name="theme_options_settings" id="theme_options_settings" method="post" enctype="multipart/form-data">
				<input type="hidden" name="theme_options_nonce" value="<?php echo wp_create_nonce( basename( __FILE__ ) ); ?>" />
				<div class="icon32 icon32-posts-post" id="icon-edit"><br>
				</div>
				<h2>
					<?php echo esc_html__( 'Theme Settings', 'templatic-admin' );?>
				</h2>

				<?php if ( isset( $_REQUEST['updated'] ) && '' != $_REQUEST['updated'] ) {?>
				<div class="updated" id="message" style="clear:both">
					<p>
						<?php echo esc_html__( 'Theme Settings', 'templatic-admin' );?>
						<strong>
							<?php echo esc_html__( 'saved', 'templatic-admin' );?>
						</strong>.</p>
					</div>
					<?php }?>

					<div class="wp-filter tev-sub-menu" >
						<ul id="tev_theme_settings" class="filter-links">
							<li class="general_settings active"><a id="general_settings" href="javascript:void(0);" class="current">
								<?php echo esc_html__( 'General Settings', 'templatic-admin' );?>
							</a> </li>
							<li class="listing_settings active"><a id="listing_settings" href="javascript:void(0);">
								<?php echo esc_html__( 'Category/Tag Archive Page Settings', 'templatic-admin' );?>
							</a> </li>
							<li class="detail_settings "><a id="detail_settings" href="javascript:void(0);" >
								<?php echo esc_html__( 'Comments Settings', 'templatic-admin' );?>
							</a> </li>
						</ul>
					</div>
					<table id="general_settings" class="tmpl-theme_settings form-table active-tab">
						<tbody>
							<!-- General Settings -->
							<tr>
								<th><label for="supreme_global_layout">
									<?php echo esc_html__( 'Global layout', 'templatic-admin' );?>
								</label></th>
								<td><div class="element">
									<select style="vertical-align:top;width:200px;" name="supreme_global_layout" id="supreme_global_layout">
										<option value="layout_default" <?php echo ( 'layout_default' == $theme_settings['supreme_global_layout'] ) ? 'selected' : ''?>>
											<?php echo esc_html__( 'Default Layout', 'templatic-admin' );?>
										</option>
										<option value="layout_1c" <?php echo ( 'layout_1c' == $theme_settings['supreme_global_layout'] ) ? 'selected' : ''?>>
											<?php echo esc_html__( 'One Column', 'templatic-admin' );?>
										</option>
										<option value="layout_2c_l" <?php echo ( 'layout_2c_l' == $theme_settings['supreme_global_layout'] ) ? 'selected' : ''?>>
											<?php echo esc_html__( 'Two Columns, Left', 'templatic-admin' );?>
										</option>
										<option value="layout_2c_r" <?php echo ( 'layout_2c_r' == $theme_settings['supreme_global_layout'] ) ? 'selected' : ''?>>
											<?php echo esc_html__( 'Two Columns, Right', 'templatic-admin' );?>
										</option>
									</select>
								</div>
								<p class="description">
									<?php echo esc_html__( 'This setting can be overwritten by layout settings within individual posts/pages.', 'templatic-admin' );?>
								</p></td>
							</tr>

							<tr>
								<th><label for="rtlcss">
									<?php echo esc_html__( 'Right-to-Left (RTL) text orientation', 'templatic-admin' );?>
								</label></th>
								<td>
									<div class="input-switch">
										<input type="checkbox"  value="1" <?php echo ( 1 == @$theme_settings['rtlcss'] ) ? 'checked' : ''?> id="rtlcss" name="rtlcss">
										<label for="rtlcss">
											<?php echo esc_html__( 'Enable', 'templatic-admin' );?>
										</label>
									</div>
									<p class="description">
										<?php echo esc_html__( 'Enabling this option will activate the rtl.css file. This is a must for languages like Hebrew or Arabic.', 'templatic-admin' );?>
									</p></td>
								</tr>
								<tr>
									<th><label for="enable_sticky_header_menu">
										<?php echo esc_html__( 'Sticky header', 'templatic-admin' );?>
									</label></th>
									<td>
										<div class="input-switch">
											<input type="checkbox"  value="1" <?php echo ( 1 == @$theme_settings['enable_sticky_header_menu'] ) ? 'checked' : ''?> id="enable_sticky_header_menu" name="enable_sticky_header_menu">
											<label for="enable_sticky_header_menu">
												<?php echo esc_html__( 'Enable', 'templatic-admin' );?>
											</label>
										</div>
										<p class="description">
											<?php echo esc_html__( 'Sticky header is a persistent navigation bar that continues to show even when you scroll down the page.', 'templatic-admin' );?>
										</p></td>
									</tr>

									<tr>
										<th>
											<label for="supreme_show_breadcrumb">
												<?php echo esc_html__( 'Breadcrumbs', 'templatic-admin' );?>
											</label>
										</th>
										<td>
											<div class="input-switch">
												<input type="checkbox" value="1"  <?php echo ( 1 == @$theme_settings['supreme_show_breadcrumb'] ) ? 'checked' : ''?> id="supreme_show_breadcrumb" name="supreme_show_breadcrumb">
												<label for="supreme_show_breadcrumb">
													<?php echo esc_html__( 'Enable', 'templatic-admin' );?>
												</label>
											</div>
										</td>
									</tr>
									<tr>
										<th>
											<label for="supreme_show_breadcrumb">
												<?php echo esc_html__( 'Mobile app view', 'templatic-admin' );?>
											</label>
										</th>
										<td>
											<div class="input-switch">
												<input type="checkbox" value="1"  <?php echo ( 0 != @$theme_settings['tmpl_mobile_view'] ) ? 'checked' : ''?> id="tmpl_mobile_view" name="tmpl_mobile_view">
												<label for="tmpl_mobile_view">
													<?php echo esc_html__( 'Enable', 'templatic-admin' );?>
												</label>
											</div>
											<p class="description">
												<?php echo wp_kses_post( wp_unslash( __( 'This will enable an application like view when you visit your website in mobile devices, This application view is static in terms of content and will not display all the information which is there in the desktop view.</br> <strong>Note:</strong> Keep this option disabled if you want to display normal responsive view with all the content in mobile devices.', 'templatic-admin' ) ) );?>
											</p>
										</td>
									</tr>
									<tr>
										<th><label for="supreme_gogle_analytics_code">
											<?php echo esc_html__( 'Google Analytics tracking code', 'templatic-admin' );?>
										</label></th>
										<td><div class="element">
											<textarea name="supreme_gogle_analytics_code" id="supreme_gogle_analytics_code" rows="6" cols="60"><?php echo stripslashes( $theme_settings['supreme_gogle_analytics_code'] );?></textarea>
										</div>
										<p class="description">
											<?php echo wp_kses_post( wp_unslash( __( 'Enter the analytics code you received from GA or some other analytics software. e.g. <a href="https://www.google.co.in/analytics/">Google Analytics</a>', 'templatic-admin' ) ) );?>
										</p></td>
									</tr>
									<tr>
										<td colspan="2"><p style="clear: both;" class="submit">
											<input type="submit" value="<?php echo esc_html__( 'Save All Settings', 'templatic-admin' ); ?>" class="button button-primary button-hero" name="Submit">
										</p></td>
									</tr>
								</tbody>
							</table>

							<table id="listing_settings" class="tmpl-theme_settings form-table">
								<tbody>
									<!-- Listing Page Settings -->
									<tr>
										<th><label for="supreme_display_image">
											<?php echo esc_html__( 'Display excerpts', 'templatic-admin' );?>
										</label></th>
										<td><div class="input-switch">
											<input type="checkbox" value="1" <?php echo ( 1 == $theme_settings['supreme_archive_display_excerpt'] ) ? 'checked' : ''?> id="supreme_archive_display_excerpt" name="supreme_archive_display_excerpt">
											<label for="supreme_archive_display_excerpt">
												<?php echo esc_html__( 'Enable', 'templatic-admin' );?>
											</label>
											<p class="description"><?php echo wp_kses_post( wp_unslash( __( 'Enable this option to display <a href="http://codex.wordpress.org/Excerpt">excerpts*</a> instead of the full post description on category pages.', 'templatic-admin' ) ) );?></p>
										</div>
									</td>
								</tr>
								<tr>
									<th><label for="templatic_excerpt_length">
										<?php echo esc_html__( 'Excerpt length', 'templatic-admin' );?>
									</label></th>
									<td><div class="element">
										<input type="text" value="<?php echo wp_kses_post( $theme_settings['templatic_excerpt_length'] );?>" id="templatic_excerpt_length" name="templatic_excerpt_length">
										<br/>
									</div>
									<p class="description">
										<?php echo esc_html__( 'Enter the number of words that should be displayed from your post description. This option can be overwritten by entering the actual excerpt for the post.', 'templatic-admin' );?>
									</p></td>
								</tr>
								<tr>
									<th><label for="templatic_excerpt_link">
										<?php echo esc_html__( 'Read more link name', 'templatic-admin' );?>
									</label></th>
									<td><div class="element">
										<input type="text" value="<?php echo wp_kses_post( stripslashes( $theme_settings['templatic_excerpt_link'] ) );?>" id="templatic_excerpt_link" name="templatic_excerpt_link">
									</div>
									<p class="description">
										<?php echo esc_html__( 'Default link name is "Read More".', 'templatic-admin' );?>
									</p></td>
								</tr>
								<tr>
									<td colspan="2"><p style="clear: both;" class="submit">
										<input type="submit" value="<?php echo esc_html__( 'Save All Settings', 'templatic-admin' ); ?>" class="button button-primary button-hero" name="Submit">
									</p></td>
								</tr>
							</tbody>
						</table>

						<!-- Detail Page Settings -->
						<table id="detail_settings" class="tmpl-theme_settings form-table">
							<tbody>
								<tr>
									<th><label for="enable_comments_on_page">
										<?php echo esc_html__( 'Display comments on WordPress pages', 'templatic-admin' );?>
									</label></th>
									<td><div class="input-switch">
										<input type="checkbox" value="1" <?php echo ( 1 == $theme_settings['enable_comments_on_page'] ) ? 'checked' : ''?>  id="enable_comments_on_page" name="enable_comments_on_page">
										<label for="enable_comments_on_page">
											<?php echo esc_html__( 'Enable', 'templatic-admin' );?>
										</label>
									</div>
								</td>
							</tr>
							<tr>
								<th><label for="enable_comments_on_page">
									<?php echo esc_html__( 'Display comments on posts', 'templatic-admin' );?>
								</label></th>
								<td><div class="input-switch">
									<input type="checkbox" value="1" <?php echo ( 1 == $theme_settings['enable_comments_on_post'] ) ? 'checked' : ''?>  id="enable_comments_on_post" name="enable_comments_on_post">
									<label for="enable_comments_on_post">
										<?php echo esc_html__( 'Enable', 'templatic-admin' );?>
									</label>
									<p class="description"><?php echo esc_html__( 'This option affects all posts on the site, including any custom post types that you created.', 'templatic-admin' );?></p>
								</div></td>
							</tr>
							<tr>
								<td colspan="2"><p style="clear: both;" class="submit">
									<input type="submit" value="<?php echo esc_html__( 'Save All Settings', 'templatic-admin' ); ?>" class="button button-primary button-hero" name="Submit">
								</p></td>
							</tr>
						</tbody>
					</table>
				</form>
			</div>
			<?php
	}
} // End if().

add_action( 'admin_footer','tmpl_themeoptions_script' );
/**
 * Add script in footer to show hide theme options.
 */
function tmpl_themeoptions_script() {
	?>

	<script type="text/javascript">

		/* Script to add tabs without refresh in tevolution general settings */
		jQuery(document).ready(function () {
			jQuery("#theme_options_settings .tmpl-theme_settings").hide();
			jQuery("#theme_options_settings .active-tab").show();

			jQuery('#tev_theme_settings li a').click(function (e) {
				jQuery("#theme_options_settings .tmpl-theme_settings").hide();
				jQuery("#theme_options_settings .tmpl-theme_settings").removeClass('active-tab');
				jQuery("#tev_theme_settings li a").removeClass('current');

				jQuery(this).parents('li').addClass('active');
				jQuery(this).addClass('current');
				jQuery("#theme_options_settings table#"+this.id).show();
				jQuery("#theme_options_settings table#"+this.id).addClass('tmpl-theme_settings form-table active-tab');
			});
		});
	</script>
	<?php
}
