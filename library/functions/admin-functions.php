<?php
/**
 * File contain the functions which run & execute at the time of theme activation.
 *
 * @package WordPress
 * @subpackage Directory
 */

global $pagenow;
$page = ( isset( $_REQUEST['page'] ) ) ? sanitize_text_field( $_REQUEST['page'] ) : '';
if ( is_admin() && ( 'themes.php' == $pagenow || strstr( $_SERVER['REQUEST_URI'], 'update.php' ) || strstr( $_SERVER['REQUEST_URI'], 'update-core.php' ) || 'update-core.php' == $pagenow || 'post.php' == $pagenow || 'edit.php' == $pagenow || 'admin-ajax.php' == $pagenow || trim( $page ) == trim( 'tmpl_theme_update' ) ) && file_exists( get_template_directory() . '/wp_theme_update.php' ) && ! DOING_AJAX ) {

	$is_update_page = 0;
	if ( strstr( $_SERVER['REQUEST_URI'], 'update.php' ) || strstr( $_SERVER['REQUEST_URI'], 'update-core.php' ) || 'update-core.php' == $pagenow ) {
		$is_update_page = 1;
	}
	$date_timestamp1 = get_option( 'tmpl_update_check_date' );
	if ( '' != trim( $date_timestamp1 ) ) {
		$date_timestamp1 = strtotime( $date_timestamp1 );
	} else {
		update_option( 'tmpl_update_check_date', date( 'Y-m-d H:i:s' ) );
	}
	$date_timestamp1 = strtotime( get_option( 'tmpl_update_check_date' ) );
	$date_timestamp2 = strtotime( date( 'Y-m-d H:i:s' ) );
	$interval = abs( $date_timestamp2 - $date_timestamp1 );
	$hour_diff = intval( round( $interval / 60 ) / 60 );
	if ( $hour_diff > 3 || 1 == $is_update_page ) {
		require_once( get_template_directory() . '/wp_theme_update.php' );
		new WPUpdates_Supreme_Updater( 'https://templatic.com/_data/updates/api/', basename( get_template_directory_uri() ) );

		if ( 0 == $is_update_page ) {
			update_option( 'tmpl_update_check_date', date( 'Y-m-d H:i:s' ) );
		}
	} else {
		require_once( get_template_directory() . '/wp_theme_update.php' );
		new WPUpdates_Supreme_Updater( '', basename( get_template_directory_uri() ) );
	}
}

add_action( 'admin_init','include_auto_install_xml_file' );
add_action( 'admin_init','theme_activation' );
add_action( 'admin_init', 'custom_admin', 11 );
add_action( 'admin_init','supreme_wpup_changes', 20 );
/**
 * Theme setup function.  This function adds support for theme features and defines the default theme
 * actions and filters.
 */
function theme_activation() {
	global $pagenow;
	if ( is_admin() && isset( $_GET['activated'] ) && 'themes.php' == $pagenow ) {
		$b = array(
				'supreme_logo_url' 					=> get_template_directory_uri() . '/images/logo.png',
				'supreme_site_description'			=> 1,
				'customcss'                         => 1,
				'display_publish_date'				=> 1,
				'display_post_terms'				=> 1,
				'supreme_display_noimage'			=> 1,
				'supreme_archive_display_excerpt'	=> 1,
				'templatic_excerpt_length'			=> 27,
				'display_header_text'				=> 1,
				'tmpl_mobile_view'				=> 1,
				'supreme_show_breadcrumb'			=> 1,
				'footer_insert' 					=> '<p class="copyright">&copy; ' . date( 'Y' ) . ' <a href="http://templatic.com/demos/directory">Directory 2</a>. &nbsp;Designed by <a href="http://templatic.com" class="footer-logo"><img src="' . esc_url( get_template_directory_uri() ) . '/library/images/templatic-wordpress-themes.png" alt="WordPress Directory Theme" /></a></p>',
				'theme_activate'                     => 1,

			);
		if ( function_exists( 'supreme_prefix' ) ) {
			$supreme_prefix = supreme_prefix();
		} else {
			$supreme_prefix = sanitize_key( apply_filters( 'hybrid_prefix', get_template() ) );
		}

		/*Check theme activate option on theme setting option for if already theme activation setting set then didnt resetting on theme switch */
		$theme_settings = get_option( $supreme_prefix . '_theme_settings' );
		if ( ! $theme_settings['theme_activate'] ) {
			update_option( $supreme_prefix . '_theme_settings', $b );
		}
	}
}
/**
 * Include xml file for auto install.
 */
function include_auto_install_xml_file() {
	if ( isset( $_REQUEST['dummy_insert'] ) && '' != $_REQUEST['dummy_insert'] ) {
		/* Check if theme specific auto install file is exists or not */
		$theme_name = strtolower( wp_get_theme() );
		if ( file_exists( get_template_directory() . '/library/functions/' . $theme_name . '_auto_install/auto_install_xml.php' ) ) {
			require_once( get_template_directory() . '/library/functions/' . $theme_name . '_auto_install/auto_install_xml.php' );
		} else {
			if ( file_exists( get_stylesheet_directory() . '/functions/auto_install/auto_install_xml.php' ) ) {
				require_once( get_stylesheet_directory() . '/functions/auto_install/auto_install_xml.php' );
			} else {
				if ( file_exists( get_template_directory() . '/library/functions/auto_install/auto_install_xml.php' ) ) {
					require_once( get_template_directory() . '/library/functions/auto_install/auto_install_xml.php' );
				}
			}
		}
	}
}
/**
 * Code to auto extract plugins
 *
 * @param string  $source 		Source path to copy plugin folder.
 * @param string  $target 		Target path to move plugin folder.
 * @param string  $plug_path 	Plugin path.
 * @param integer $add_msg 		To show message.
 */
function zip_copy( $source, $target, $plug_path, $add_msg = 0 ) {
	if ( ! @copy( $source,$target ) ) {
		add_action( 'admin_notices','dir_one_click_install' );
		$errors = error_get_last();
		echo wp_kses_post( '<span style="color:red;">' . esc_html__( 'COPY ERROR:', 'templatic-admin' ) . '</span> ' . $errors['type'] );
		echo '<br />\n' . wp_kses_post( $errors['message'] );
	} else {
		$file = explode( '.', $target );

		if ( file_exists( $target ) ) {
			$message = '<span style="color:green;">' . esc_html__( 'File copied from remote!', 'templatic-admin' ) . '</span><br/>';

			$zip = new ZipArchive();
			$x = $zip->open( $target );

			if ( true === $x && file_exists( $target ) ) {
				$zip->extractTo( get_tmpl_plugin_directory() ); // Change this to the correct site path.
				$zip->close();

				unlink( $target );
				$message = esc_html__( 'Your .zip file was uploaded and unpacked.', 'templatic-admin' ) . '<br/>';
			}
		}
		if ( 1 == $add_msg && strstr( $_SERVER['REQUEST_URI'], 'themes.php' ) ) {
			update_option( 'tev_on_go', 1 );

			$plug_path2 = 'Tevolution-Directory/directory.php';  // Change this to the correct site path.
			$plug_path3 = 'Tevolution-LocationManager/location-manager.php';  // Change this to the correct site path.
			$plug_path1 = 'Tevolution/templatic.php';  // Change this to the correct site path.

			activate_plugin( $plug_path1 );
			activate_plugin( $plug_path2 );
			activate_plugin( $plug_path3 );

			$location_post_type[] = 'post,category,post_tag';
			$location_post_type[] = 'listing,listingcategory,listingtags';
			$post_types = update_option( 'location_post_type', $location_post_type );
		}
	} // End if().
}
if ( ! function_exists( 'custom_admin' ) ) {

	/**
	 * Include files for auto install.
	 */
	function custom_admin() {

		/* auto install for theme */
		$theme_name = strtolower( wp_get_theme() );
		if ( strstr( $_SERVER['REQUEST_URI'], 'themes.php' ) || ( isset( $_REQUEST['page'] ) && 'templatic_system_menu' == $_REQUEST['page'] ) || defined( 'DOING_AJAX' ) ) {
			/* Check if theme specific auto install is available or not */
			if ( file_exists( get_stylesheet_directory() . '/functions/' . $theme_name . '_auto_install/auto_install.php' ) ) {
				include_once( get_stylesheet_directory() . '/functions/' . $theme_name . '_auto_install/auto_install.php' );
			} elseif ( file_exists( get_template_directory() . '/library/functions/' . $theme_name . '_auto_install/auto_install.php' ) ) {
				include_once( get_template_directory() . '/library/functions/' . $theme_name . '_auto_install/auto_install.php' );
			} else {
				/* If theme specific auto install is not exists then take the default auto install. */
				if ( file_exists( get_stylesheet_directory() . '/functions/auto_install/auto_install.php' ) ) {
					include_once( get_stylesheet_directory() . '/functions/auto_install/auto_install.php' );
				} elseif ( file_exists( get_template_directory() . '/library/functions/auto_install/auto_install.php' ) ) {
					include_once( get_template_directory() . '/library/functions/auto_install/auto_install.php' );
				}
			}
		}
	}
}
/**
 * Remove theme udpate row.
 */
function supreme_wpup_changes() {
	 remove_action( 'after_theme_row_supreme', 'wp_theme_update_row' ,10, 2 );
}

add_action( 'admin_init','tevolution_add_marker_fields' );
/**
 * Display marker upload field in category page on backend.
 */
function tevolution_add_marker_fields() {
	$tevolution_taxonomy_marker = get_option( 'tevolution_taxonomy_marker' );
	if ( ! empty( $tevolution_taxonomy_marker ) ) {
		foreach ( $tevolution_taxonomy_marker as $key => $value ) {
			if ( 'ecategory' == $key || 'listingcategory' == $key ) {
				continue;
			}
			add_action( 'edited_' . $key,'marker_custom_fields_AlterFields' );
			add_action( 'created_' . $key,'marker_custom_fields_AlterFields' );
			add_filter( 'manage_' . $key . '_custom_column', 'manage_marker_category_columns', 10, 3 );
			add_filter( 'manage_edit-' . $key . '_columns', 'marker_category_columns' );

			if ( isset( $_GET['taxonomy'] ) && ( $_GET['taxonomy'] == $key ) ) {
				$taxnow = wp_kses_post( wp_unslash( $_GET['taxonomy'] ) );
				add_action( $taxnow . '_edit_form_fields', 'marker_custom_fields_EditFields', 11 );
				add_action( $taxnow . '_add_form_fields', 'marker_custom_fields_AddFieldsAction', 11 );
			}
		}
	}
}

/**
 * Display field in category page on backend whileccategory edit.
 *
 * @param string $tag 		category type.
 */
function marker_custom_fields_EditFields( $tag ) {
	marker_custom_fields_AddFields( $tag,'edit' );
}
/**
 * Display field in category page on backend whileccategory add.
 *
 * @param string $tag 		category type.
 */
function marker_custom_fields_AddFieldsAction( $tag ) {
	marker_custom_fields_AddFields( $tag,'add' );
}
/**
 *	Display custom field in event and listing category page.
 *
 * @param string $tag 		category type.
 * @param string $screen 	screen while adding or editing.
 */
function marker_custom_fields_AddFields( $tag, $screen ) {
	$tax = @$tag->taxonomy;
	?>
	<div class="form-field-category">
		<tr class="form-field form-field-category">
			<th scope="row" valign="top"><label for="cat_icon"><?php echo esc_html__( 'Map Marker', 'templatic-admin' ); ?></label></th>
			<td>
					<input id="cat_icon" type="text" size="60" name="cat_icon" value="<?php echo ( @$tag->term_icon) ? wp_kses_post( @$tag->term_icon ) : ''; ?>"/>
					<?php echo esc_html__( 'Or', 'templatic-admin' );?>
					<a data-id="cat_icon" id="Map Marker" type="submit" class="upload_file_button button"><?php  echo esc_html__( 'Browse', 'templatic-admin' );?></a>
					<p class="description"><?php echo esc_html__( 'It will appear on the homepage Google map for listings placed in this category. ', 'templatic-admin' );?></p>
			</td>
		</tr>
	</div>
	<?php
}

/**
 * Add/ edit listing and event custom taxonomy custom field.
 *
 * @param integer $termId 		Category id.
 */
function marker_custom_fields_AlterFields( $termId ) {
	global $wpdb;
	$term_table = $wpdb->prefix . 'terms';
	$cat_icon = wp_kses_post( wp_unslash( $_POST['cat_icon'] ) );

	if ( isset( $_POST['cat_icon'] ) ) {
		$sql = "update $term_table set term_icon='" . $cat_icon . "' where term_id=" . $termId;
		$wpdb->query( $sql );
	}
}

/**
 * Manage columns for event and listing custom taxonomy.
 *
 * @param string $columns 		Map marker column.
 */
function marker_category_columns( $columns ) {
	$columns['icon'] = esc_html__( 'Map Marker', 'templatic-admin' );
	return $columns;
}
/**
 *	Display listing and event custom taxonomy custom field display in category columns
 *
 * @param html    $out 			output of map marker.
 * @param string  $column_name 	Column Name.
 * @param integer $term_id 		Term id.
 */
function manage_marker_category_columns( $out, $column_name, $term_id ) {
	global $wpdb;
	$term_table = $wpdb->prefix . 'terms';
	$sql = "select * from $term_table where term_id=" . $term_id;
	$term = $wpdb->get_results( $sql );

	switch ( $column_name ) {
		case 'icon':
				 $out = ( $term[0]->term_icon ) ? '<img src="' . $term[0]->term_icon . '" >':'<img src="' . apply_filters( 'tmpl_default_map_icon', TEVOLUTION_PAGE_TEMPLATES_URL . 'images/pin.png' ) . '" >';
			break;
		default:
			break;
	}
	return $out;
}

add_action( 'admin_init', 'directory_map_marker_enable' );
/**
 * Default markers available for listings and taxonomies
 */
function directory_map_marker_enable() {
	if ( ! get_option( 'existing_user' ) ) {
		$tevolution_taxonomy_marker = get_option( 'tevolution_taxonomy_marker' );
		if ( empty( $tevolution_taxonomy_marker ) ) {
			update_option( 'tevolution_taxonomy_marker', array(
															'listingcategory' => 'enable',
															'ecategory' => 'enable',
															)
			);
		} else {
			update_option( 'tevolution_taxonomy_marker', array_merge( $tevolution_taxonomy_marker, array(
																										'listingcategory' => 'enable',
																										'ecategory' => 'enable',
																										)
			) );
		}
		update_option( 'existing_user', 1 );
	}
}

if ( file_exists( get_template_directory() . '/library/functions/theme_options.php' ) ) {
	require_once( get_template_directory() . '/library/functions/theme_options.php' );
}

/**
 * Remove post type option for post type page.
 */
function remove_post_type_option() {
	if ( 'page' == get_current_screen()->id ) {
		echo '<style type="text/css">
				.metabox-prefs label[for="supreme2_post_type_meta-hide"] { display: none !important; }
			</style>';
	}
}
add_action( 'admin_head', 'remove_post_type_option', 10 );

add_action( 'admin_footer','delete_sample_data' );

if ( ! function_exists( 'delete_sample_data' ) ) {
	/**
	 * Alert while delete of sample data.
	 */
	function delete_sample_data() {
	?>
	<script type="text/javascript">
	jQuery(document).ready( function(){
		jQuery( '.button_delete' ).click( function() {
			if ( confirm(" Delete the dummy data only if you haven't changed the added data (posts, pages, etc). If you have, all those changes will be lost. Deleting dummy data might also cause changes with your widgets." ) ) {
				window.location = "<?php echo esc_url( home_url() ); ?>/wp-admin/themes.php?dummy=del";
			} else {
				return false;
			}
		});
	});
	</script>
	<?php
	}
}

add_action( 'admin_menu', 'register_theme_settings_menu', 9999 );
if ( ! function_exists( 'register_theme_settings_menu' ) ) {
	/**
	 * Add theme settings and custom css menu.
	 */
	function register_theme_settings_menu() {
		add_theme_page( esc_html__( 'Theme Settings', 'templatic-admin' ), esc_html__( 'Theme Settings', 'templatic-admin' ), 'manage_options', 'theme-settings-page', 'theme_settings_page_callback' );
		add_theme_page( esc_html__( 'Custom CSS Editor', 'templatic-admin' ), esc_html__( 'Custom CSS Editor', 'templatic-admin' ), 'edit_theme_options', 'templatic_custom_css_editor', 'templatic_custom_css_editor_settings' );
	}
}

/**
 * Save custom css in an option table
 */
function templatic_custom_css_editor_settings() {
	$title = esc_html__( 'Custom CSS Editor', 'templatic-admin' );
	$file = TEMPLATE_DIR . 'custom.css';
	$theme = 'directory';
	if ( $theme ) {
		$stylesheet = $theme;
	} else {
		$stylesheet = get_stylesheet();
	}

	$theme = wp_get_theme( $stylesheet );
	$allowed_files = $theme->get_files( 'php', 1 );
	$has_templates = ! empty( $allowed_files );
	$style_files = $theme->get_files( 'css' );
	$allowed_files['style.css'] = $style_files['style.css'];
	$allowed_files += $style_files;

	$relative_file = 'custom.css';
	if ( isset( $_POST['action'] ) && 'update' == $_POST['action'] ) {

		check_admin_referer( 'edit-theme_' . $file . $stylesheet );
		$newcontent = $_POST['custom_css_content'];
		update_option( 'directory_custom_css', $newcontent );
		$location = 'themes.php?page=templatic_custom_css_editor';
		$location .= '&updated=true';
		wp_redirect( $location );
		exit;
	}
	$content = '';
	if ( file_exists( $file ) ) {
		if ( ! $error && filesize( $file ) > 0 ) {
			$f = fopen( $file, 'r' );
			$content = fread( $f, filesize( $file ) );

			if ( '.php' == substr( $file, strrpos( $file, '.' ) ) ) {
				$functions = wp_doc_link_parse( $content );

				$docs_select = '<select name="docs-list" id="docs-list">';
				$docs_select .= '<option value="">' . esc_attr__( 'Function Name&hellip;' ) . '</option>';
				foreach ( $functions as $function ) {
					$docs_select .= '<option value="' . esc_attr( urlencode( $function ) ) . '">' . htmlspecialchars( $function ) . '()</option>';
				}
				$docs_select .= '</select>';
			}

			$content = esc_textarea( $content );
		}
	}
	?>
		<div class="wrap">
		<h2><?php echo esc_html( $title ); ?></h2>
		 <p> <?php echo sprintf( esc_html__( 'You can customize the theme by entering CSS classes in this section. Enter only the classes you want to overwrite (not the whole style.css file). For details on using custom.css open %s.', 'templatic-admin' ),'<a href="http://templatic.com/docs/using-custom-css-for-theme-customizations/">' . esc_html__( 'this article', 'templatic-admin' ) . '</a>' );?></p>

		<?php if ( isset( $_GET['updated'] ) && 'true' == $_GET['updated'] ) { ?>
			<div class="updated" id="message"><p><?php echo esc_html__( 'File edited successfully.', 'templatic-admin' ); ?></p></div>
		<?php } ?>
		<form name="custom_css" id="template" action="" method="post">
		<?php wp_nonce_field( 'edit-theme_' . $file . $stylesheet ); ?>
			<div><textarea cols="70" rows="30" name="custom_css_content" id="custom_css_content" aria-describedby="newcontent-description"><?php echo wp_kses_post( $content ); ?></textarea>
			<input type="hidden" name="action" value="update" />
			<input type="hidden" name="file" value="<?php echo esc_attr( $relative_file ); ?>" />
			<input type="hidden" name="theme" value="<?php echo esc_attr( $theme->get_stylesheet() ); ?>" />
			<input type="hidden" name="scrollto" id="scrollto" value="0" />
			<?php
			if ( get_option( 'directory_custom_css' ) ) {
				if ( is_writeable( $file ) ) :
					submit_button( esc_html__( 'Update File', 'templatic-admin' ), 'primary', 'submit', true );
				else : ?>
			<p><em><?php esc_html__( 'You need to make this file writable before you can save your changes. See <a href="http://codex.wordpress.org/Changing_File_Permissions">the Codex</a> for more information in directory root folder.', 'templatic-admin' ); ?></em></p>
			<?php endif;
			} else {
				submit_button( esc_html__( 'Update File', 'templatic-admin' ), 'primary', 'submit', true );
			}?>
			</div>
		</form>
		</div>
<?php
}

add_action( 'admin_head', 'tmpl_admin_popupcss', 11 ); // Add css for auto update section in backend.
/**
 * Css for admin pop up.
 */
function tmpl_admin_popupcss() {
?>
	<style>
	.table_tnews{float:right;width:63%}.t_theme{float:left;width:34%;margin-right:3%}.t_theme img{max-width:100%}.pimg img{border:1px solid #ccc}.clearfix{clear:both}ul#templatic-services li{list-style:disc inside}.clearfix:after{clear:both;content:".";display:block;font-size:0;height:0;line-height:0;visibility:hidden}.theme_meta .more a.btn_viewdemo,.theme_meta .more a.btn_viewdetails{margin:0}.table_tnews .news li p{margin-top:0}.templatic-dismiss{background:url(../images/xit.gif) no-repeat scroll 0 2px transparent;position:absolute;right:60px;top:8px;width:0;font-size:13px;line-height:1;padding:0 0 0 10px;text-decoration:none;text-indent:3px}.templatic-dismiss:hover{background-position:-10px 2px}.templatic_autoinstall{position:relative;padding:12px!important},.login .message,div.updated{background:#FFFBE4;border-color:#DFDFDF}.postbox .inside{margin:15px 0!important}.themeunit{margin-bottom:10px}#TB_iframeContent,#TB_window{height:460px!important;margin-top:0!important}#TB_iframeContent body{padding:0!important}body{height:auto}.templatic_login{background:none repeat scroll 0 0 #FFF;border:0!important;margin:0!important;font-size:14px;font-weight:400;padding:15px;padding-top:20px;width:40%}.templatic_login label{color:#777;font-size:14px}.templatic_login form .input,.templatic_login input[type=password],.templatic_login input[type=text]{background:none repeat scroll 0 0 #FBFBFB;border:1px solid #E5E5E5;box-shadow:1px 1px 2px rgba(200,200,200,.2)inset;color:#555;font-size:24px;font-weight:200;line-height:1;margin-bottom:16px;margin-right:6px;margin-top:2px;outline:0 none;padding:10px 8px 6px;width:100%}.templatic_login input[type=submit]{background-color:#21759b;background-image:-webkit-gradient(linear,left top,left bottom,from(#2a95c5),to(#21759b));background-image:-webkit-linear-gradient(top,#2a95c5,#21759b);background-image:-moz-linear-gradient(top,#2a95c5,#21759b);background-image:-ms-linear-gradient(top,#2a95c5,#21759b);background-image:-o-linear-gradient(top,#2a95c5,#21759b);background-image:linear-gradient(to bottom,#2a95c5,#21759b);border-color:#21759b;box-shadow:0 1px 0 rgba(120,200,230,.5)inset;color:#FFF;text-decoration:none;text-shadow:0 1px 0 rgba(0,0,0,.1);height:30px;line-height:28px;padding:0 12px 2px;border-radius:3px;border-style:solid;border-width:1px;cursor:pointer;display:inline-block;font-size:12px;margin-right:10px}.templatic_login p.info{margin-top:0}body{min-width:380px!important}#pblogo{margin-top:10px;text-align:left!important}#TB_window{left:53%!important;top:100px!important}
	</style>
<?php }
