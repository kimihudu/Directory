<?php
/**
 * Metadata functions used in the core.  This file registers meta keys for use in WordPress
 * in a safe manner by setting up a custom sanitize callback.
 *
 * @package WordPress
 * @subpackage Directory
 */

/**
 * Callback function for sanitizing meta when add_metadata() or update_metadata() is called by WordPress.
 * If a developer wants to set up a custom method for sanitizing the data, they should use the
 * "sanitize_{$meta_type}_meta_{$meta_key}" filter hook to do so.
 *
 * @param string $meta_value 				Value of meta.
 * @param string $meta_key 					Key of post meta.
 * @param string $meta_type 				Type of meta.
 */
function supreme_sanitize_meta( $meta_value, $meta_key, $meta_type ) {
	return strip_tags( $meta_value );
}
add_action( 'wp_dashboard_setup', 'templatic_dashboard_widget_setup' );
/**
 * Add a admin Dashboard widget.
 */
function templatic_dashboard_widget_setup() {
	global $current_user;
	if ( is_super_admin( $current_user->ID ) ) {
		add_meta_box( 'templatic_dashboard_news_widget', esc_html__( 'News From Templatic', 'templatic-admin' ), 'templatic_dashboard_widget_function', 'dashboard', 'normal', 'high' );
	}
}

/**
 * TemplaticDashboardWidgetFunction - Admin dashboard widget to show templatic news.
 */
function templatic_dashboard_widget_function() {
	?>
	<div class="table table_tnews">
		<p class="sub"><strong>
			<?php echo esc_html__( 'Templatic News', 'templatic-admin' ); ?>
		</strong></p>
		<div class="trss-widget">
			<?php
			$items = get_transient( 'templatic_dashboard_news' );

			if ( empty( $items ) ) {
				include_once( ABSPATH . WPINC . '/class-simplepie.php' );
				$trss = new SimplePie();
				$trss->set_timeout( 5 );
				$trss->set_feed_url( 'http://feeds.feedburner.com/Templatic' );
				$trss->strip_htmltags( array_merge( $trss->strip_htmltags, array( 'h1', 'a' ) ) );
				$trss->enable_cache( false );
				$trss->init();

				if ( is_wp_error( $trss ) ) {
					if ( is_admin() || current_user_can( 'manage_options' ) ) {
						echo '<div class="rss-widget"><p>';
						printf( esc_html__( '<strong>RSS Error</strong>: %s', 'templatic' ), $trss->get_error_message() );
						echo '</p></div>';
					}
				}

				$items = $trss->get_items( 0, 6 );
				$cached = array();

				foreach ( $items as $item ) {
					preg_match( '/(.{128}.*?)\b/', $item->get_content(), $matches );
					$cached[] = array(
									'url' => $item->get_permalink(),
									'title' => $item->get_title(),
									'date' => $item->get_date( 'd M Y' ),
									'content' => rtrim( $matches[1] ) . '...',
					);
				}
				$items = $cached;
				set_transient( 'templatic_dashboard_news', $cached, 60 * 60 * 24 );
			}
			?>
			<ul class="news">
				<?php foreach ( $items as $item ) : ?>
					<li class="post"> <a href="<?php echo esc_url( $item['url'] ); ?>" class="rsswidget"><?php echo esc_attr( $item['title'] ); ?></a> <span class="rss-date"><?php echo wp_kses_post( $item['date'] ); ?></span>
						<div class="rssSummary"><?php echo wp_kses_post( strip_tags( $item['content'] ) ); ?></div>
					</li>
				<?php endforeach;?>
			</ul>
		</div>
	</div>
	<div class="t_theme">
		<div class="t_thumb">
			<?php
			$last_theme = '';
			$cache_key = 'dash_templatic_latest_theme';
			$last_theme = get_transient( $cache_key );
			if ( false !== $last_theme ) {
				echo $last_theme;
			} else {
				$raw_response = wp_remote_post( 'http://templatic.com/latest-theme/' );
				if ( ! is_wp_error( $raw_response ) ) {
					$last_theme = $raw_response['body'];
				}
				if ( $last_theme ) {
					echo $last_theme;
				}
				set_transient( $cache_key, $last_theme, 12 * HOUR_IN_SECONDS ); // Default lifetime in cache of 12 hours (same as the feeds).
			}
		?>
</div>
<hr/>
<p class="sub"><strong>
	<?php echo esc_html__( 'More...', 'templatic-admin' ); ?>
</strong></p>
<ul id="templatic-services">
	<li><a href="http://templatic.com/support"><?php echo esc_html__( 'Need support?', 'templatic-admin' );?> </a></li>
	<li><a href="http://templatic.com/free-theme-install-service/"><?php echo esc_html__( 'Custom services', 'templatic-admin' );?></a></li>
	<li><a href="http://templatic.com/premium-themes-club"><?php echo esc_html__( 'Join our theme club', 'templatic-admin' );?></a></li>
</ul>
</div>
<div class="clearfix"></div>
<?php
}
