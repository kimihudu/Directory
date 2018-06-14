<?php
/**
 * Wsed with other components of the framework admin. this file is for
 * setting up any basic features and holding additional admin helper functions.
 *
 * @package WordPress
 * @subpackage Directory
 */

add_action( 'admin_menu', 'tmpl_admin_setup' );
/**
 * Sets up the administration functionality for the framework and themes.
 */
function tmpl_admin_setup() {
	/* Loads admin stylesheets for the framework. */
	add_action( 'admin_enqueue_scripts', 'tmpl_admin_enqueue_styles' );
}

/**
 * Loads the admin.css stylesheet for admin-related features.
 *
 * @param string $suffix 				Page name.
 */
function tmpl_admin_enqueue_styles( $suffix ) {
	/* Load admin styles if on the widgets screen and the current theme supports 'tmpldir-core-widgets'. */
	if ( current_theme_supports( 'tmpldir-core-widgets' ) && 'widgets.php' == $suffix ) {
		wp_enqueue_style( 'supreme-core-admin' );
	}
}
/**
 * Function for getting an array of available custom templates with a specific header. ideally, this function would be used to grab custom
 * singular post (any post type) templates.  it is a recreation of the wordpress page templates function because it doesn't allow for other
 * types of templates.
 *
 * @param array $args 				Template included.
 */
function supreme_get_post_templates( $args = array() ) {
	/* Parse the arguments with the defaults. */
	$args = wp_parse_args( $args, array(
									'label' => array( 'post template' ),
									)
	);
	/* Get theme and templates variables. */
	$themes = wp_get_themes();
	$theme = wp_get_theme();
	$templates = $themes[ $theme ]['template files'];
	$post_templates = array();
	/* If there's an array of templates, loop through each template. */
	if ( is_array( $templates ) ) {
		/* set up a $base path that we'll use to remove from the file name. */
		$base = array( trailingslashit( get_template_directory() ), trailingslashit( get_stylesheet_directory() ) );
		/* loop through the post templates. */
		foreach ( $templates as $template ) {
			/* remove the base (parent/child theme path) from the template file name. */
			$basename = str_replace( $base, '', $template );
			/* get the template data. */
			$template_data = implode( '', file( $template ) );
			/* make sure the name is set to an empty string. */
			$name = '';
			/* loop through each of the potential labels and see if a match is found. */
			foreach ( $args['label'] as $label ) {
				if ( preg_match( "|{$label}:(.*)$|mi", $template_data, $name ) ) {
					$name = _cleanup_header_comment( $name[1] );
					break;
				}
			}
			/* If a post template was found, add its name and file name to the $post_templates array. */
			if ( ! empty( $name ) ) {
				$post_templates[ trim( $name ) ] = $basename;
			}
		}
	}
	/* Return array of post templates. */
	return $post_templates;
}

/*=========================== load theme customization options ===========================================*/

/* Load custom control classes. */
add_action( 'customize_register', 'tmpldir_customize_controls', 1 );
/* Register custom sections, settings, and controls. */
add_action( 'customize_register', 'tmpldir_customize_register' );
/* Add the footer content ajax to the correct hooks. */
add_action( 'wp_ajax_supreme_customize_footer_content', 'supreme_customize_footer_content_ajax' );
add_action( 'wp_ajax_nopriv_supreme_customize_footer_content', 'supreme_customize_footer_content_ajax' );

/**
 * Registers custom sections, settings, and controls for the $wp_customize instance.
 *
 * @param array $wp_customize 				Customizer array for backend.
 */
function tmpldir_customize_register( $wp_customize ) {
	/* get supported theme settings. */
	$supports = get_theme_support( 'supreme-core-theme-settings' );
	/* get the theme prefix. */
	$prefix = supreme_prefix();
	/* get the default theme settings. */
	$default_settings = supreme_default_theme_settings();
	/* Add the footer section, setting, and control if theme supports the 'footer' setting. */
	if ( is_array( $supports[0] ) && in_array( 'footer', $supports[0] ) ) {
		/* add the footer section. */
		$wp_customize->add_section(
			'supreme-core-footer',
			array(
				'title' => esc_html__( 'Footer', 'templatic-admin' ),
				'priority' => 200,
				'capability' => 'edit_theme_options',
			)
		);
		/* add the 'footer_insert' setting. */
		$wp_customize->add_setting(
			"{$prefix}_theme_settings[footer_insert]",
			array(
				'label' => ' html tags allow, enter whatever you want to display in footer section.',
				'default' => @$default_settings['footer_insert'],
				'type' => 'option',
				'capability' => 'edit_theme_options',
				'sanitize_callback' => 'supreme_customize_sanitize',
				'sanitize_js_callback' => 'supreme_customize_sanitize',
				'transport' => 'postmessage',
			)
		);
		/* add the textarea control for the 'footer_insert' setting. */
		$wp_customize->add_control(
			new hybrid_customize_control_textarea(
				$wp_customize,
				'supreme-core-footer',
				array(
					'label' => esc_html__( 'Footer', 'templatic-admin' ),
					'section' => 'supreme-core-footer',
					'settings' => "{$prefix}_theme_settings[footer_insert]",
				)
			)
		);
		/* If viewing the customize preview screen, add a script to show a live preview. */
		if ( $wp_customize->is_preview() && ! is_admin() ) {
			add_action( 'wp_footer', 'supreme_customize_preview_script', 21 );
		}
	} // End if().
}
/**
 * Sanitizes the footer content on the customize screen.  users with the 'unfiltered_html' cap can post
 * anything.  for other users, wp_filter_post_kses() is ran over the setting.
 *
 * @param string $setting 				Footer settings customizer for backend.
 * @param array  $object 				An array of customizer.
 */
function supreme_customize_sanitize( $setting, $object ) {
	/* get the theme prefix. */
	$prefix = supreme_prefix();
	/* make sure we kill evil scripts from users without the 'unfiltered_html' cap. */
	if ( '{$prefix_theme_settings[footer_insert]' == $object->id && ! current_user_can( 'unfiltered_html' ) ) {
		$setting = stripslashes( wp_filter_post_kses( addslashes( $setting ) ) );
	}
	/* return the sanitized setting and apply filters. */
	return apply_filters( "{$prefix_customize_sanitize}", $setting, $object );
}
/**
 * Runs the footer content posted via ajax through the do_shortcode() function.  this makes sure the short codes are output correctly in
 * the live preview.
 */
function supreme_customize_footer_content_ajax() {
	/* check the ajax nonce to make sure this is a valid request. */
	check_ajax_referer( 'supreme_customize_footer_content_nonce' );
	/* if footer content has been posted, run it through the do_shortcode() function. */
	if ( isset( $_post['footer_content'] ) ) {
		echo do_shortcode( wp_kses_stripslashes( $_post['footer_content'] ) );
	}
	/* Always die() when handling ajax. */
	die();
}
/**
 * Handles changing settings for the live preview of the theme.
 */
function supreme_customize_preview_script() {
	/* Create a nonce for the ajax. */
	$nonce = wp_create_nonce( 'supreme_customize_footer_content_nonce' );
	?>
	<script type="text/javascript">
		wp.customize(
			'<?php echo wp_kses_post( supreme_prefix() ); ?>_theme_settings[footer_insert]',
			function( value ) {
			value.bind(
				function( to ) {
					jquery.post(
						'<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>',
						{
							action: 'supreme_customize_footer_content',
							_ajax_nonce: '<?php echo wp_kses_post( $nonce ); ?>',
							footer_content: to
						},
						function( response ) {
							jquery( '.footer-content' ).html( response );
						}
					);
				}
			);
		}
	);
	</script>
<?php
}
/**
 * Theme customizer settings for wordpress customizer.
 */
global $pagenow;
if ( is_admin() && 'admin.php' == $pagenow ) {

	if ( ! current_user_can( 'manage_options' ) ) {
		/* for translator page access */
		if ( is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) ) {

		} else {
			wp_die( esc_html__( 'you do not have sufficient permissions to access this section.', 'templatic-admin' ) );
		}
	}
}
/* Add action for customizer   start	*/
add_action( 'customize_register',  'templatic_register_customizer_settings' );
/* Add action for customizer   end	*/

/* Function to create sections, settings, controls for wordpress customizer start.  */
global $support_woocommerce;
$support_woocommerce = get_theme_support( 'supreme_woocommerce_layout' );
/**
 * Register customizer settings option , it returns the options for theme->customizer.php
 *
 * @param array $wp_customize 				Customizer object.
 */
function templatic_register_customizer_settings( $wp_customize ) {
	global $support_woocommerce;
	/* Add section for different controls in customizer start header image section settings start. */
	$wp_customize->get_section( 'header_image' )->priority = 5;

	/* Header image section settings end navigation menu section settings start. */
	if ( $wp_customize->get_section( 'nav' ) ) {
		$wp_customize->get_section( 'nav' )->priority = 6;
	}

	/* Navigation menu section settings end	color section settings start. */
	$wp_customize->get_section( 'colors' )->title = esc_html__( 'Colors Settings' , 'templatic-admin' );
	$wp_customize->get_section( 'colors' )->priority = 7;

	/* colour section settings end	background section settings start */
	$wp_customize->get_section( 'background_image' )->title = esc_html__( 'Background Settings', 'templatic-admin' );
	$wp_customize->get_section( 'background_image' )->priority = 8;

	/* Background section settings end add site logo section start. */
	$wp_customize->add_section( 'templatic_logo_settings', array(
															'title' 	=> esc_html__( 'Site Logo', 'templatic-admin' ),
															'priority' 	=> 1,
															)
	);

	/* Site title section settings start. */
	$wp_customize->get_section( 'title_tagline' )->priority = 2;

	/* Static front page section settings start. */
	$wp_customize->get_section( 'static_front_page' )->priority = 12;

	/* Supreme core footer section settings start */
	$wp_customize->get_section( 'supreme-core-footer' )->priority = 17;

	$wp_customize->add_setting( supreme_prefix() . '_theme_settings[supreme_logo_url]',array(
																							'default' => '',
																							'type' => 'option',
																							'capabilities' => 'edit_theme_options',
																							'sanitize_callback' => 'templatic_customize_supreme_logo_url',
																							'sanitize_js_callback' => 'templatic_customize_supreme_logo_url',
																						)
	);

	/* Add settings for hide/show site description start. */
	$wp_customize->add_setting( supreme_prefix() . '_theme_settings[supreme_site_description]',array(
																								'default' => '',
																								'type' => 'option',
																								'capabilities' => 'edit_theme_options',
																								'sanitize_callback' => 'templatic_customize_supreme_site_description',
																								'sanitize_js_callback' => 'templatic_customize_supreme_site_description',
																								)
	);
	/* Add settings for hide/show site description finish. */

	$wp_customize->add_setting( supreme_prefix() . '_theme_settings[footer_lbl]', array(
																					'default' => '',
																				)
	);

	/* Color settings start. */
	$wp_customize->add_setting( supreme_prefix() . '_theme_settings[color_picker_color1]',array(
																							'default' => '',
																							'type' => 'option',
																							'capabilities' => 'edit_theme_options',
																							'sanitize_callback' => 'templatic_customize_supreme_color1',
																							'sanitize_js_callback' => 'templatic_customize_supreme_color1',
																						)
	);

		$wp_customize->add_setting( supreme_prefix() . '_theme_settings[color_picker_color2]',array(
																								'default' => '',
																								'type' => 'option',
																								'capabilities' => 'edit_theme_options',
																								'sanitize_callback' => 'templatic_customize_supreme_color2',
																								'sanitize_js_callback' => 'templatic_customize_supreme_color2',
																								)
		);

		$wp_customize->add_setting( supreme_prefix() . '_theme_settings[color_picker_color3]',array(
																								'default' => '',
																								'type' => 'option',
																								'capabilities' => 'edit_theme_options',
																								'sanitize_callback' => 'templatic_customize_supreme_color3',
																								'sanitize_js_callback' => 'templatic_customize_supreme_color3',
																							)
		);

		$wp_customize->add_setting( supreme_prefix() . '_theme_settings[color_picker_color4]',array(
																								'default' => '',
																								'type' => 'option',
																								'capabilities' => 'edit_theme_options',
																								'sanitize_callback' => 'templatic_customize_supreme_color4',
																								'sanitize_js_callback' => 'templatic_customize_supreme_color4',
																							)
		);

		$wp_customize->add_setting( supreme_prefix() . '_theme_settings[color_picker_color5]',array(
																								'default' => '',
																								'type' => 'option',
																								'capabilities' => 'edit_theme_options',
																								'sanitize_callback' => 'templatic_customize_supreme_color5',
																								'sanitize_js_callback' => 'templatic_customize_supreme_color5',
																							)
		);

		$wp_customize->add_setting( supreme_prefix() . '_theme_settings[color_picker_color6]',array(
																								'default' => '',
																								'type' => 'option',
																								'capabilities' => 'edit_theme_options',
																								'sanitize_callback' => 'templatic_customize_supreme_color6',
																								'sanitize_js_callback' => 'templatic_customize_supreme_color6',
																							)
		);

	/* Callback function: templatic_customize_supreme_header_background_image.*/
	$wp_customize->add_setting( 'header_image', array(
													'default'        => get_theme_support( 'custom-header', 'default-image' ),
													'theme_supports' => 'custom-header',
												)
	);

	$wp_customize->add_setting( supreme_prefix() . '_theme_settings[header_image_display]',array(
																							'default' => 'after_nav',
																							'type' => 'option',
																							'capabilities' => 'edit_theme_options',
																							'sanitize_callback' => 'templatic_customize_header_image_display',
																							'sanitize_js_callback' => 'templatic_customize_header_image_display',
																						)
	);

	/* Add settings for hide/show header text start. */
	$wp_customize->add_setting( supreme_prefix() . '_theme_settings[display_header_text]',array(
																							'default' => 1,
																							'type' => 'option',
																							'capabilities' => 'edit_theme_options',
																							'sanitize_callback' => 'templatic_customize_display_header_text',
																							'sanitize_js_callback' => 'templatic_customize_display_header_text',
																						)
	);

	/* Added site logo control start. */
	$wp_customize->add_control( new wp_customize_image_control( $wp_customize,  supreme_prefix() . '_theme_settings[supreme_logo_url]', array(
			'label' => esc_html__( ' Upload Image For Logo', 'templatic-admin' ),
			'section' => 'templatic_logo_settings',
			'settings' => supreme_prefix() . '_theme_settings[supreme_logo_url]',
		)
	) );

	/**
	 * Override class for image extension for favicon
	 */
	class tmpl_customize_favicon_control extends wp_customize_image_control {
		public function __construct( $manager, $id, $args ) {

			$this->extensions[] = 'ico';/* new extension for upload */

			return parent::__construct( $manager, $id, $args );
		}
	}

	/* Added site favicon icon control finish. */
	$wp_customize->add_control( 'supreme_site_description', array(
		'label' => esc_html__( 'Display tagline ', 'templatic-admin' ),
		'section' => 'title_tagline',
		'settings' => supreme_prefix() . '_theme_settings[supreme_site_description]',
		'type' => 'checkbox',
		'priority' => 106,
	));

	/* added show/hide site description control finish */

	$wp_customize->add_control( new supreme_custom_lable_control( $wp_customize,  supreme_prefix() . '_theme_settings[footer_lbl]', array(
		'label' => esc_html__( 'footer text ( e.g. <p class="copyright">&copy;', 'templatic-admin' ) . ' ' . date( 'y' ) . ' ' . esc_html__( '<a rel="nofollow" href="http://templatic.com/demos/responsive">responsive</a>. all rights reserved. </p>)', 'templatic-admin' ),
		'section' => 'supreme-core-footer',
		'priority' => 1,
	)));

	/**
	 * Color settings control start.
	 * Primary: 	 effect on buttons, links and main headings.
	 * Secondary: 	 effect on sub-headings.
	 * Content: 	 effect on content.
	 * Sub-text: 	 effect on sub-texts.
	 * Background:  effect on body & menu background.
	 */
	$wp_customize->add_control( new wp_customize_color_control( $wp_customize, 'color_picker_color1', array(
		'label'   => esc_html__( 'Body Background Color', 'templatic-admin' ),
		'section' => 'colors',
		'settings'   => supreme_prefix() . '_theme_settings[color_picker_color1]',
		'priority' => 1,
	) ) );

	$wp_customize->add_control( new wp_customize_color_control( $wp_customize, 'color_picker_color2', array(
		'label'   => esc_html__( 'Primary And Secondary Navigation, Footer, Background Color', 'templatic-admin' ),
		'section' => 'colors',
		'settings'   => supreme_prefix() . '_theme_settings[color_picker_color2]',
		'priority' => 2,
	) ) );

	$wp_customize->add_control( new wp_customize_color_control( $wp_customize, 'color_picker_color3', array(
		'label'   => esc_html__( 'Text Color Of Content Area', 'templatic-admin' ),
		'section' => 'colors',
		'settings'   => supreme_prefix() . '_theme_settings[color_picker_color3]',
		'priority' => 3,
	) ) );

	$wp_customize->add_control( new wp_customize_color_control( $wp_customize, 'color_picker_color4', array(
		'label'   => esc_html__( 'Categories Links, Navigation Links, Footer Links Hover And Sub Text Of Page Color', 'templatic-admin' ),
		'section' => 'colors',
		'settings'   => supreme_prefix() . '_theme_settings[color_picker_color4]',
		'priority' => 4,
	) ) );

	$wp_customize->add_control( new wp_customize_color_control( $wp_customize, 'color_picker_color5', array(
		'label'   => esc_html__( 'Meta Text, Breadcrumb, Pagination Text And All Grey Color Text', 'templatic-admin' ),
		'section' => 'colors',
		'settings'   => supreme_prefix() . '_theme_settings[color_picker_color5]',
		'priority' => 5,
	) ) );

	$wp_customize->add_control( new wp_customize_color_control( $wp_customize, 'color_picker_color6', array(
		'label'   => esc_html__( 'Buttons, Date And Recurrences Label Color', 'templatic-admin' ),
		'section' => 'colors',
		'settings'   => supreme_prefix() . '_theme_settings[color_picker_color6]',
		'priority' => 6,
	) ) );

	/* remove wordpress default control start.*/
	$wp_customize->remove_control( 'background_color' );

	/* added header background image control start */
	$wp_customize->add_control( new wp_customize_header_image_control( $wp_customize ) );

	$wp_customize->add_control(  supreme_prefix() . '_theme_settings[header_image_display]', array(
		'label' => esc_html__( 'display header image ( go in appearance -> header to set/change the image )', 'templatic-admin' ),
		'section' => 'header_image',
		'settings' => supreme_prefix() . '_theme_settings[header_image_display]',
		'type' => 'select',
		'choices' => array(
							'before_nav' 	=> 'before secondary menu',
							'after_nav' 	=> 'after secondary menu',
						  ),
	) );

	/* added display header text control start */
	$wp_customize->add_control(  supreme_prefix() . '_theme_settings[display_header_text]', array(
		'label' => esc_html__( 'Display site title', 'templatic-admin' ),
		'section' => 'title_tagline',
		'settings' => supreme_prefix() . '_theme_settings[display_header_text]',
		'type' => 'checkbox',
		'priority' => 105,
	));

	// Added header background image control finish.
	$wp_customize->remove_control( 'header_textcolor' );
	$wp_customize->remove_control( 'display_header_text' );
}

/**
 * Customizer function to save logo.
 *
 * @param string $setting 				Customizer settings key.
 * @param array  $object 				Object of customizer.
 */
function templatic_customize_supreme_logo_url( $setting, $object ) {

	/* make sure we kill evil scripts from users without the 'unfiltered_html' cap. */
	if ( supreme_prefix() . '_theme_settings[supreme_logo_url]' == $object->id && ! current_user_can( 'unfiltered_html' ) ) {
		$setting = stripslashes( wp_filter_post_kses( addslashes( $setting ) ) );
	}
	/* return the sanitized setting and apply filters. */
	return apply_filters( 'templatic_customize_supreme_logo_url', $setting, $object );
}

/**
 * Customizer function to save site description.
 *
 * @param string $setting 				Customizer settings key.
 * @param array  $object 				Object of customizer.
 */
function templatic_customize_supreme_site_description( $setting, $object ) {

	/* make sure we kill evil scripts from users without the 'unfiltered_html' cap. */
	if ( supreme_prefix() . '_theme_settings[supreme_site_description]' == $object->id && ! current_user_can( 'unfiltered_html' ) ) {
		$setting = stripslashes( wp_filter_post_kses( addslashes( $setting ) ) );
	}
	/* return the sanitized setting and apply filters. */
	return apply_filters( 'templatic_customize_supreme_site_description', $setting, $object );
}

/**
 * Customizer function to save color.
 *
 * @param string $setting 				Customizer settings key.
 * @param array  $object 				Object of customizer.
 */
function templatic_customize_supreme_color1( $setting, $object ) {

	/* make sure we kill evil scripts from users without the 'unfiltered_html' cap. */
	if ( supreme_prefix() . '_theme_settings[color_picker_color1]' == $object->id && ! current_user_can( 'unfiltered_html' ) ) {
		$setting = stripslashes( wp_filter_post_kses( addslashes( $setting ) ) );
	}
	/* return the sanitized setting and apply filters. */
	return apply_filters( 'templatic_customize_supreme_color1', $setting, $object );
}

/**
 * Customizer function to save color.
 *
 * @param string $setting 				Customizer settings key.
 * @param array  $object 				Object of customizer.
 */
function templatic_customize_supreme_color2( $setting, $object ) {

	/* make sure we kill evil scripts from users without the 'unfiltered_html' cap. */
	if ( supreme_prefix() . '_theme_settings[color_picker_color2]' == $object->id && ! current_user_can( 'unfiltered_html' ) ) {
		$setting = stripslashes( wp_filter_post_kses( addslashes( $setting ) ) );
	}
	/* return the sanitized setting and apply filters. */
	return apply_filters( 'templatic_customize_supreme_color2', $setting, $object );
}

/**
 * Customizer function to save color.
 *
 * @param string $setting 				Customizer settings key.
 * @param array  $object 				Object of customizer.
 */
function templatic_customize_supreme_color3( $setting, $object ) {

	/* make sure we kill evil scripts from users without the 'unfiltered_html' cap. */
	if ( supreme_prefix() . '_theme_settings[color_picker_color3]' == $object->id && ! current_user_can( 'unfiltered_html' ) ) {
		$setting = stripslashes( wp_filter_post_kses( addslashes( $setting ) ) );
	}
	/* return the sanitized setting and apply filters. */
	return apply_filters( 'templatic_customize_supreme_color3', $setting, $object );
}

/**
 * Customizer function to save color.
 *
 * @param string $setting 				Customizer settings key.
 * @param array  $object 				Object of customizer.
 */
function templatic_customize_supreme_color4( $setting, $object ) {

	/* make sure we kill evil scripts from users without the 'unfiltered_html' cap. */
	if ( supreme_prefix() . '_theme_settings[color_picker_color4]' == $object->id && ! current_user_can( 'unfiltered_html' ) ) {
		$setting = stripslashes( wp_filter_post_kses( addslashes( $setting ) ) );
	}
	/* return the sanitized setting and apply filters. */
	return apply_filters( 'templatic_customize_supreme_color4', $setting, $object );
}

/**
 * Customizer function to save color.
 *
 * @param string $setting 				Customizer settings key.
 * @param array  $object 				Object of customizer.
 */
function templatic_customize_supreme_color5( $setting, $object ) {

	/* make sure we kill evil scripts from users without the 'unfiltered_html' cap. */
	if ( supreme_prefix() . '_theme_settings[color_picker_color5]' == $object->id && ! current_user_can( 'unfiltered_html' ) ) {
		$setting = stripslashes( wp_filter_post_kses( addslashes( $setting ) ) );
	}
	/* return the sanitized setting and apply filters. */
	return apply_filters( 'templatic_customize_supreme_color5', $setting, $object );
}

/**
 * Customizer function to save color.
 *
 * @param string $setting 				Customizer settings key.
 * @param array  $object 				Object of customizer.
 */
function templatic_customize_supreme_color6( $setting, $object ) {

	/* make sure we kill evil scripts from users without the 'unfiltered_html' cap. */
	if ( supreme_prefix() . '_theme_settings[color_picker_color6]' == $object->id && ! current_user_can( 'unfiltered_html' ) ) {
		$setting = stripslashes( wp_filter_post_kses( addslashes( $setting ) ) );
	}
	/* return the sanitized setting and apply filters. */
	return apply_filters( 'templatic_customize_supreme_color6', $setting, $object );
}

/**
 * Background header image function start.
 *
 * @param string $setting 				Customizer settings key.
 * @param array  $object 				Object of customizer.
 */
function templatic_customize_header_image_display( $setting, $object ) {

	/* make sure we kill evil scripts from users without the 'unfiltered_html' cap. */
	if ( supreme_prefix() . '_theme_settings[header_image_display]' == $object->id && ! current_user_can( 'unfiltered_html' ) ) {
		$setting = stripslashes( wp_filter_post_kses( addslashes( $setting ) ) );
	}
	/* return the sanitized setting and apply filters. */
	return apply_filters( 'templatic_customize_header_image_display', $setting, $object );
}

/**
 * Display header text function start.
 *
 * @param string $setting 				Customizer settings key.
 * @param array  $object 				Object of customizer.
 */
function templatic_customize_display_header_text( $setting, $object ) {

	/* make sure we kill evil scripts from users without the 'unfiltered_html' cap. */
	if ( supreme_prefix() . '_theme_settings[display_header_text]' == $object->id && ! current_user_can( 'unfiltered_html' ) ) {
		$setting = stripslashes( wp_filter_post_kses( addslashes( $setting ) ) );
	}
	/* return the sanitized setting and apply filters. */
	return apply_filters( 'templatic_customize_display_header_text', $setting, $object );
}

/**
 * loads framework-specific customize control classes.  customize control classes extend the wordpress
 * wp_customize_control class to create unique classes that can be used within the framework.
 */
function tmpldir_customize_controls() {
	 /*
	 * custom label customize control class.
	 */
	if ( class_exists( 'wp_customize_control' ) ) {
		class supreme_custom_lable_control extends wp_customize_control {
			/**
			 * Render script.
			 */
			public function render_content() {
			?>
				<label> <span><?php echo esc_html( $this->label ); ?></span> </label>
			<?php
			}
		}
	}

	if ( class_exists( 'wp_customize_control' ) ) {
		/**
		 * Text area customize control class.
		 */
		class hybrid_customize_control_textarea extends wp_customize_control {
			/**
			 * Text area customize control class.
			 *
			 * @var string $type
			 */
			public $type = 'textarea';
			public function __construct( $manager, $id, $args = array() ) {
				parent::__construct( $manager, $id, $args );
			}
			public function render_content() {
	?>
			<label>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<div class="customize-control-content">
				  <textarea cols="25" rows="5" <?php esc_html( $this->link() ); ?>><?php echo esc_textarea( $this->value() ); ?></textarea>
				</div>
			</label>
<?php 		}
		}
	}
}

if ( ! function_exists( 'get_header_image_location' ) ) {
	/**
	 * To display header image.
	 */
	function get_header_image_location() {
		$theme_name = get_option( 'stylesheet' );
		$theme_settings = get_option( supreme_prefix() . '_theme_settings' );
		if ( ! empty( $theme_settings ) ) {
			if ( isset( $theme_settings['header_image_display'] ) && '' != @$theme_settings['header_image_display'] && 'before_nav' == @$theme_settings['header_image_display'] ) {
				return 0;
			} elseif ( isset( $theme_settings['header_image_display'] ) && '' != @$theme_settings['header_image_display'] && 'after_nav' == @$theme_settings['header_image_display'] ) {
				return 1;
			}
		}
	}
}
