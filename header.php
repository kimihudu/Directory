<?php
/**
 * The Header for our theme.
 *
 * The header template is generally used on every page of your site. Nearly all other templates call it
 * somewhere near the top of the file. It is used mostly as an opening wrapper, which is closed with the
 * footer.php file. It also executes key functions needed by the theme, child themes, and plugins.
 */
?>
<!DOCTYPE html>
<!--[if lt IE 7 ]> <html class="ie6"> <![endif]-->
<!--[if IE 7 ]>    <html class="ie7"> <![endif]-->
<!--[if IE 8 ]>    <html class="ie8"> <![endif]-->
<!--[if IE 9 ]>    <html class="ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html data-useragent="Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.2; Trident/6.0)" <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
	<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=0">
	<!-- <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge"> <!-- Specially to make clustering work in IE -->

	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<title>
		<?php wp_title();?>
	</title>
	<?php
	/* get favicon icon */
	$supreme2_theme_settings = get_option( supreme_prefix() . '_theme_settings' );
	if ( function_exists( 'supreme_get_favicon' ) ) {
		if ( supreme_get_favicon() ) {
			echo '<link rel="shortcut icon" href="' . supreme_get_favicon() . '" />';
		}
	}

	wp_head();

	if ( file_exists( get_template_directory() . '/planet_legal.css' ) && file_get_contents( get_template_directory() . '/planet_legal.css' ) != '' ) {
		echo '<link href="' . get_template_directory_uri() . '/planet_legal.css" rel="stylesheet" type="text/css" />';
	}
	do_action( 'supreme_enqueue_script' );
	?>
<!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<!--[if IE]>
<style>
body{word-wrap:inherit!important;}
</style>
<![endif]-->
<script src="jquery.enllax.min.js"></script>
<script type="text/javascript">
	jQuery( document ).ready(function() {
		if(jQuery( window ).width() < 980 ){
			jQuery('.preview_submit_from_data .b_getdirection.getdir').html("<i class='fa fa-map-marker'></i>");
			jQuery('.preview_submit_from_data .b_getdirection.large_map').html("<i class='fa fa-retweet'></i>");
		}
		jQuery( window ).resize(function() {
			if(jQuery( window ).width() < 980 ){
				jQuery('.preview_submit_from_data .b_getdirection.getdir').html("<i class='fa fa-map-marker'></i>");
				jQuery('.preview_submit_from_data .b_getdirection.large_map').html("<i class='fa fa-retweet'></i>");
			}
		});

	});

</script>
</head>


<body class="<?php supreme_body_class(); ?>">
<?php /* Top Banner ad added by MK, comment added by TE */;?>
<div class="sl-topAd"> <?php echo adrotate_ad(13); ?> </div>
	<?php do_action( 'after_body' );?>
	<div class="supreme_wrapper">
		<?php
		do_action( 'open_body' ); // supreme_open_body
		$theme_name = get_option( 'stylesheet' );
		$nav_menu = get_option( 'theme_mods_' . strtolower( $theme_name ) );
		remove_action( 'pre_get_posts', 'home_page_feature_listing' );
?>
<div class="off-canvas-wrap" data-offcanvas> <!-- off-canvas-wrap start -->
	<!-- inner-wrap start -->
	<div class="inner-wrap">

		<!-- Navigation  - Contain logo and site title -->
		<nav class="tab-bar hide-for-large-up">
			<section class="left-small">
				<a class="left-off-canvas-toggle menu-icon" href="#"><span></span></a> <!-- off canvas icon -->
			</section>
			<?php do_action( 'tmpl_after_logo' ); ?>
			<section class="middle tab-bar-section">
				<a href="/" title="<?php echo bloginfo( 'name' ); ?>" rel="Home">
					<img class="logo" src="<?php echo supreme_get_settings( 'supreme_logo_url' ); ?>" alt="<?php echo bloginfo( 'name' ); ?>" />
				</a>
			</section>
		</nav>


		<aside class="left-off-canvas-menu"> <!-- off canvas side menu -->
			<?php
			apply_filters( 'tmpl_supreme_header_primary',supreme_header_primary_navigation() );
			if ( is_active_sidebar( 'mega_menu' ) ) {
				if ( function_exists( 'dynamic_sidebar' ) ) {
					echo '<div id="nav" class="nav_bg">
					<div id="menu-mobi-secondary" class="menu-container">
						<nav role="navigation" class="wrap">
							<div id="menu-mobi-secondary-title">';
								_e( 'Menu', 'templatic' );
								echo '</div>';
					dynamic_sidebar( 'mega_menu' ); // jQuery mega menu
					echo '</nav></div></div>';
				}
			} elseif ( isset( $nav_menu['nav_menu_locations'] )  && isset( $nav_menu['nav_menu_locations']['secondary'] ) && $nav_menu['nav_menu_locations']['secondary'] != 0 ) {
				echo '<div id="nav" class="nav_bg">';
						apply_filters( 'tmpl_supreme_header_secondary',supreme_header_secondary_mobile_navigation() ); // Loads the menu-secondary template.
						echo '</div>';
			} else {
				?>
				<ul class="off-canvas-list">
					<?php wp_list_pages( 'title_li=&depth=0&child_of=0&number=5&show_home=1&sort_column=ID&sort_order=DESC' );?>
				</ul>
				<?php } ?>
</aside>

<div id="container" class="container-wrap">
	<header class="header_container clearfix">

		<div class="primary_menu_wrapper clearfix">
			<div class="primary_menu_wrap row">
				<?php
				do_action( 'before_desk_menu_primary' );
				supreme_primary_navigation();
				do_action( 'after_desk_menu_primary' ); ?>
			</div>
		</div>
		<?php do_action( 'before_header' );
		$header_image = get_header_image();
		if ( function_exists( 'get_header_image_location' ) ) {
			$header_image_location = get_header_image_location(); // 0 = before secondary navigation menu, 1 = after secondary navigation menu
		} else {
			$header_image_location = 1;
		} ?>
<div id="header" class="row clearfix">
	<?php do_action( 'header_container_start' );
	do_action( 'open_header' ); ?>
	<div class="header-wrap">

		<div id="branding" class="large-4 columns">
			<hgroup>
				<?php
				if ( supreme_get_settings( 'display_header_text' ) ) {
					if ( supreme_get_settings( 'supreme_logo_url' ) ) :?>
					<div id="site-title">
						<?php
						global $wpdb,$country_table,$zones_table,$multicity_table;
						$city_id = $_SESSION['post_city_id'];
						$cityinfo = $wpdb->get_row( "SELECT mc.cityname as cityname, mc.city_slug as city_slug,mc.city_id as city_id FROM $multicity_table mc where mc.city_id in('$city_id') order by mc.cityname ASC LIMIT 0,1 " );
						$city = $cityinfo->city_slug;
						if ( $city != '' ) { $req = "/city/".$city;
						} else { $req = ''; }?>
						<a href="<?php echo home_url(); ?><?php echo $req; ?>" title="<?php echo bloginfo( 'name' ); ?>" rel="Home">
							<img class="logo" src="<?php echo supreme_get_settings( 'supreme_logo_url' ); ?>" alt="<?php echo bloginfo( 'name' ); ?>" />
						</a>
					</div>
				<?php else :
				supreme_site_title();
				endif;
				}
				if ( supreme_get_settings( 'supreme_site_description' ) ) : // If hide description setting is un-checked, display the site description.
								supreme_site_description();
endif; ?>
</hgroup>
</div>
<!-- #branding -->

<div class="large-8 columns">
	<div class="header-widget-wrap">
		<?php
		if ( is_active_sidebar( 'header' ) && function_exists( 'supreme_header_sidebar' ) ) :
			apply_filters( 'tmpl-header',supreme_header_sidebar() ); // Loads the sidebar-header.
endif;
		do_action( 'header' ); ?>
</div>
</div> <!-- large-8 columns -->

</div>
<!-- .wrap -->
<?php if ( ! empty( $header_image ) && $header_image_location == 0 ) { ?>
<div class="templatic_header_image"><img src="<?php echo esc_url( $header_image ); ?>" class="header-image" width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>" alt="" /></div>
<?php }

do_action( 'close_header' );
/* Secondary navigation menu for desk top */
supreme_secondary_navigation();
?>

<!-- 604lk - hamburger menu -->
<div class='container'>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#hamburger" aria-controls="hamburger"
		aria-expanded="false" aria-label="Toggle navigation">
		<div class="hamburger_icon"><span></span><span></span><span></span></div>
	</button>
	<div class="menu" id="hamburger">
		<form name="searchform_372881311" method="get" class="searchform_372881311 allinone" id="mega-searchform1" action="https://planet-legal.com/" style="position:relative;">
			<input type="hidden" name="t" value="6b4b5513eb">
			<input type="hidden" name="posttype[]" value="biz_legal_materials">
			<input type="hidden" name="posttype[]" value="busines_needs">
			<input type="hidden" name="posttype[]" value="business_files">
			<input type="hidden" name="posttype[]" value="event">
			<input type="hidden" name="posttype[]" value="needs">
			<input type="hidden" name="posttype[]" value="personal_files">
			<input type="hidden" name="posttype[]" value="prs_legal_materials">
			<input type="hidden" name="mkey[]" value="cats">
			<input type="hidden" name="mkey[]" value="tags">
			<input type="hidden" name="mkey[]" value="category">
			<input type="hidden" name="mkey[]" value="post_excerpt">
			<input type="hidden" name="mkey[]" value="blm_entityname">
			<input type="hidden" name="mkey[]" value="blm_website">
			<input type="hidden" name="mkey[]" value="all_language">
			<input type="hidden" name="mkey[]" value="company_name">
			<input type="hidden" name="mkey[]" value="company_url">
			<input type="hidden" name="mkey[]" value="about_company">
			<input type="hidden" name="mkey[]" value="application_deadline">
			<input type="hidden" name="mkey[]" value="needs_legal_budget">
			<input type="hidden" name="mkey[]" value="bf_entityname">
			<input type="hidden" name="mkey[]" value="bf_aboutentity">
			<input type="hidden" name="mkey[]" value="bf_pf_filereferral">
			<input type="hidden" name="mkey[]" value="address">
			<input type="hidden" name="mkey[]" value="st_date">
			<input type="hidden" name="mkey[]" value="st_time">
			<input type="hidden" name="mkey[]" value="end_time">
			<input type="hidden" name="mkey[]" value="reg_desc">
			<input type="hidden" name="mkey[]" value="organizer_website">
			<input type="hidden" name="mkey[]" value="organizer_desc">
			<input type="hidden" name="mkey[]" value="cpd">
			<input type="hidden" name="mkey[]" value="period_of_your_event">
			<input type="hidden" name="mkey[]" value="ev_access">
			<input type="hidden" name="mkey[]" value="pln_budget">
			<input type="hidden" name="mkey[]" value="plm_entityname">
			<input type="hidden" name="mkey[]" value="plm_website">
			<input type="hidden" name="custom_cat" value="">
			<input type="text" onclick="tmpl_insta_search_widget( 'searchform_372881311' )" onkeypress="tmpl_insta_search_widget( 'searchform_372881311' )" value="" name="s" id="search_near-17573754111" class="searchpost placeholder" placeholder="Looking For ..." size="100">
			<input type="hidden" name="t" value="6b4b5513eb">
			<input type="hidden" name="relation" class="sgo" value="OR">
			<input type="submit" class="sgo" onclick="tmpl_find_click(17573754111);" value="Search">
			<!--<ul id="search-result" class="ui-autocomplete ui-front ui-menu ui-widget ui-widget-content ui-corner-all" style="display:none;"></ul>-->
		</form>
		<ul class='list'>
			<li >
				<a href='#'>Home
				<span class="sub-indicator">»</span>
				</a>
				<ul class="sub-hamburger" style="">
					<li ><a href="#">About us</a></li>
					<li ><a href="#">Our Path</a></li>
				</ul>
			</li>
		</ul>
		<ul class='list'>
			<li ><a href="#">Find Lawyers</a></li>
			<li ><a href="#">Refer Files</a></li>
			<li>
				<a href='#'>Client Resources
				<span class="sub-indicator">»</span>
				</a>
				<ul class="sub-hamburger" style="">
					<li>
						<a href="#">Legal Materials
						<img src='https://planet-legal.com/wp-content/uploads/2018/06/en.png'/>
						</a>
					</li>
					<li >               <a href="#">Client Events
						<img src='https://planet-legal.com/wp-content/uploads/2018/06/en.png'/></a>
					</li>
					<li ><a href="#">Lawyer Information<img src='https://planet-legal.com/wp-content/uploads/2018/06/en.png'/></a></li>
				</ul>
			</li>
		</ul>
		<ul>
			<li ><a href="#">Find Clients</a></li>
			<li ><a href="#">News</a></li>
			<li ><a href="#">Advertises</a></li>
		</ul>
	</div>
</div>
<script>
        jQuery(document).ready(function($){
            $('.hamburger_icon').click(function(){
                jQuery(this).toggleClass('open');
				jQuery('#hamburger').toggleClass('open');
				jQuery('.container').toggleClass('open');
            });
        });
</script>




</div>
<!-- #header -->

</header>
<?php
$tmpdata = get_option( 'templatic_settings' );
$map_class = (isset( $tmpdata['google_map_full_width'] ) && $tmpdata['google_map_full_width'] == 'yes')?'clearfix map_full_width':'map_fixed_width';
/* get current page template */
$current_page_template = get_page_template_slug( $post->ID );
if ( ( ! is_page() && ! is_author() && ! is_404() && ! is_singular()) || (is_front_page() || is_home() || get_query_var( 'page_id' ) == get_option( 'page_on_front' )) || $current_page_template == 'page-templates/front-page.php' ) :?>
<div class="home_page_banner clear clearfix <?php echo $map_class;?>">
	<?php if ( ! empty( $header_image ) && $header_image_location == 1 ) { ?>
	<div class="templatic_header_image"><img src="<?php echo esc_url( $header_image ); ?>" class="header-image" width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>" alt="" /></div>
	<?php }
if ( ! is_home() ) {
	do_action( 'before_main' );
}
	?>
</div>
<?php endif;

if ( (is_singular() && ! is_page()) || (isset( $_REQUEST['page'] ) && $_REQUEST['page'] == 'preview') ) {
	do_action( 'before_opening_main' );
}
do_action( 'tmpl_after_custom_header' );
?>
<section id="main" class="clearfix">

	<?php

	do_action( 'tmpl_before_open_main' );
	do_action( 'tmpl_open_main' );
	do_action( 'tmpl_after_open_main' );

	do_action( 'open_main' ); ?>
	<div class="wrap row">
		<?php do_action( 'tmpl_open_wrap' ); ?>
