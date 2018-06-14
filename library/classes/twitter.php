<?php
/**
 * Twitter Widget Class.
 *
 * @package WordPress
 * @subpackage Directory
 */

if ( ! function_exists( 'is_curl_installed' ) ) {
	/**
	 * Check whether curk is enabled or not.
	 */
	function is_curl_installed() {
		if ( in_array( 'curl', get_loaded_extensions() ) ) {
			return true;
		} else {
			return false;
		}
	}
}
if ( ! class_exists( ' templatic_twiter' ) ) {
	if ( ! class_exists( 'span' ) ) {
		require_once( 'Oauth/twitteroauth.php' );
	}
	/**
	 * Twitter Widget Class.
	 **/
	class templatic_twiter extends WP_Widget {
		/**
		 * Set up the widget's unique name, ID, class, description, and other options.
		 */
		function __construct() {
			$this->options = array(
				array(
					'name'	=> 'title',
					'label'	=> esc_html__( 'Title:', 'templatic-admin' ),
					'type'	=> 'text',
				),
				array(
					'name'	=> 'twitter_username',
					'label'	=> esc_html__( 'Twitter Username:', 'templatic-admin' ),
					'type'	=> 'text',
				),
				array(
					'name'	=> 'consumer_key',
					'label'	=> esc_html__( 'Consumer Key:', 'templatic-admin' ),
					'type'	=> 'text',
				),
				array(
					'name'	=> 'consumer_secret',
					'label'	=> esc_html__( 'Consumer Secret:', 'templatic-admin' ),
					'type'	=> 'text',
				),
				array(
					'name'	=> 'access_token',
					'label'	=> esc_html__( 'Access Token:', 'templatic-admin' ),
					'type'	=> 'text',
				),
				array(
					'name'	=> 'access_token_secret',
					'label'	=> esc_html__( 'Access Token Secret:', 'templatic-admin' ),
					'type'	=> 'text',
				),
				array(
					'name'	=> 'twitter_number',
					'label'	=> esc_html__( 'Number Of Tweets:', 'templatic-admin' ),
					'type'	=> 'text',
				),
				array(
					'name'	=> 'follow_text',
					'label'	=> esc_html__( 'Twitter button text <small>(for eg. Follow us, Join me on Twitter, etc.)</small>:', 'templatic-admin' ),
					'type'	=> 'text',
				),
			);
			$widget_options = array(
				'classname'		=> 'twitter',
				'description'	=> esc_html__( 'Display latest tweets from your Twitter account. Works best in sidebar areas.', 'templatic-admin' ),
			);
			parent::__construct( false, esc_html__( 'T &rarr; Twitter Feed', 'templatic-admin' ), $widget_options );
		}
		/**
		 * Outputs the widget based on the arguments input through the widget controls.
		 *
		 * @param array $args 		agurment of widget area.
		 * @param array $instance 	instances of widget.
		 */
		function widget( $args, $instance ) {
			extract( $args, EXTR_SKIP );
			$title = ( $instance['title'] ) ? $instance['title'] : 'Latest Tweets';
			$twitter_username = ( $instance['twitter_username'] ) ? $instance['twitter_username'] : 'templatic';
			$consumer_key = ( $instance['consumer_key'] ) ? $instance['consumer_key'] : '2OKhVHTMKJEdF018VBC4g';
			$consumer_secret = ( $instance['consumer_secret'] ) ? $instance['consumer_secret'] : 'tnn0vWD1NSxra4D3HnXnCIg8iqTJ9QiwDEYCTg0UP4';
			$access_token = ( $instance['access_token'] ) ? $instance['access_token'] : '147532710-UYn0B9Xg1lcxDpM40622HKA8dTXgeF8DcnJW5vQe';
			$access_token_secret = ( $instance['access_token_secret'] ) ? $instance['access_token_secret'] : 'FkGYg0ZtJTLAva4Lw9FHBe6o18DPnj0xmtVKMlnTIM';
			$twitter_number = ( $instance['twitter_number'] ) ? $instance['twitter_number'] : '5';
			$follow_text = ( $instance['follow_text'] ) ? apply_filters( 'widget_title', $instance['follow_text'] ) : esc_html__( 'Follow Us', 'templatic' );

			echo wp_kses_post( $before_widget );
			echo '<div id="twitter" style="margin: auto;" >';
			if ( $title ) {
				echo '<h3 class="widget-title">' . wp_kses_post( $title ) . '</h3>';
			}
			if ( '' != $twitter_username ) {
				if ( ! is_curl_installed() ) {
					esc_html_e( 'cURL is NOT installed on this server', 'templatic' );
				} else {
					if ( '' != $twitter_username ) {
						templatic_twitter_messages( $instance );
					}
				}
			}
			echo '</div>';
			echo wp_kses_post( $after_widget );
		}

		/**
		 *
		 * Updates the widget control options for the particular instance of the widget.
		 *
		 * @param array $new_instance     new instance of widget when saved from widget area.
		 * @param array $old_instance 	  old instances of widget.
		 */
		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			foreach ( $this->options as $val ) {
				if ( 'text' == $val['type'] ) {
					$instance[ $val['name'] ] = strip_tags( $new_instance[ $val['name'] ] );
				} elseif ( 'checkbox' == $val['type'] ) {
					$instance[ $val['name'] ] = ( 'on' == $new_instance[ $val['name'] ] ) ? true : false;
				}
			}
			return $instance;
		}
		/**
		 *
		 * Save the widget.
		 *
		 * @param array $instance     instance of widget.
		 */
		function form( $instance ) {
			if ( empty( $instance ) ) {
				$instance['title']					= esc_html__( 'Latest Tweets', 'templatic' );
				$instance['twitter_username']		= '';
				$instance['consumer_key']			= '';
				$instance['consumer_secret']		= '';
				$instance['access_token']			= '';
				$instance['access_token_secret']	= '';
				$instance['twitter_number']			= 5;
				$instance['follow_text']			= esc_html__( 'Follow Us', 'templatic' );
			}
			echo esc_html__( '<p><span style="font-size:11px">To use this widget, <a href="https://dev.twitter.com/apps/new" style="text-decoration:none;" target="_blank">create</a> an application or <a href="https://dev.twitter.com/apps" target="_blank" style="text-decoration:none;" >click here</a> if you already have it, and fill required fields below. Need help? Read <a href="http://templatic.com/docs/latest-changes-in-twitter-widget-for-all-templatic-themes/" title="Tweeter Widget Guide" target="_blank" style="text-decoration:none;" >Twitter Guide</a>.</small></p>', 'templatic-admin' );
			foreach ( $this->options as $val ) {
				$label = '<label for="' . wp_kses_post( $this->get_field_id( $val['name'] ) ) . '">' . $val['label'] . '</label>';
				if ( 'separator' == $val['type'] ) {
					echo '<hr />';
				} elseif ( 'text' == $val['type'] ) {
					echo '<p>' . wp_kses_post( $label ) . '<br />';
					echo '<input class="widefat" id="' . wp_kses_post( $this->get_field_id( $val['name'] ) ) . '" name="' . wp_kses_post( $this->get_field_name( $val['name'] ) ) . '" type="text" value="' . esc_attr( $instance[ $val['name'] ] ) . '" /></p>';
				} elseif ( 'checkbox' == $val['type'] ) {
					$checked = ( $instance[ $val['name'] ] ) ? 'checked="checked"' : '';
					echo '<input id="' . wp_kses_post( $this->get_field_id( $val['name'] ) ) . '" name="' . wp_kses_post( $this->get_field_name( $val['name'] ) ) . '" type="checkbox" ' . esc_attr( $checked ) . ' /> ' . wp_kses_post( $label ) . '<br />';
				}
			}
		}
	}
	// Register Widget.
	add_action( 'widgets_init', create_function( '', 'return register_widget("templatic_twiter");' ) );
} // End if().

/**
 * Function to convert date to time ago format
 *
 * @param string $ptime 				Date.
 */
function time_elapsed_string( $ptime ) {
	$etime = time() - $ptime;

	if ( $etime < 1 ) {
		return esc_html__( '0 seconds', 'templatic' );
	}

	$a = array(
			12 * 30 * 24 * 60 * 60  => esc_html__( 'year', 'templatic' ),
			30 * 24 * 60 * 60       => esc_html__( 'month', 'templatic' ),
			24 * 60 * 60            => esc_html__( 'day', 'templatic' ),
			60 * 60                 => esc_html__( 'hour', 'templatic' ),
			60                      => esc_html__( 'minute', 'templatic' ),
			1                       => esc_html__( 'second', 'templatic' ),
		);

	foreach ( $a as $secs => $str ) {
		$d = $etime / $secs;
		if ( $d >= 1 ) {
			$r = round( $d );
			return $r . ' ' . $str . ( $r > 1 ? esc_html__( 's', 'templatic' ) : '') . ' ' . esc_html__( 'ago', 'templatic' );
		}
	}
}
/**
 * Function to convert date to time ago format.
 *
 * @param array $options 				Twitter message array.
 */
function templatic_twitter_messages( $options ) {
	$twitter_username	 = $options['twitter_username'];
	$consumer_key		 = $options['consumer_key'];
	$consumer_secret	 = $options['consumer_secret'];
	$access_token		 = $options['access_token'];
	$access_token_secret = $options['access_token_secret'];
	$twitter_number		 = $options['twitter_number'];
	$follow_text		 = $options['follow_text'];

	if ( ! function_exists( 'getConnectionWithAccessToken' ) ) {
		/**
		 * Function to convert date to time ago format.
		 *
		 * @param string $cons_key 				Twitter key.
		 * @param string $cons_secret 			Twitter secret key.
		 * @param string $oauth_token 			Twitter Access Token.
		 * @param string $oauth_token_secret	Twitter secret token.
		 */
		function getConnectionWithAccessToken( $cons_key, $cons_secret, $oauth_token, $oauth_token_secret ) {
			$connection = new TwitterOAuth( $cons_key, $cons_secret, $oauth_token, $oauth_token_secret );
			return $connection;
		}
	}

	$connection = getConnectionWithAccessToken( $consumer_key, $consumer_secret, $access_token, $access_token_secret );
	$tweets = $connection->get( 'https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=' . $twitter_username . '&count=' . $twitter_number );
	$tweet_errors = (array) @$tweets->errors;

	if ( empty( $tweets ) ) {
		esc_html_e( 'No public tweets', 'templatic' );
	} elseif ( ! empty( $tweet_errors ) ) {
		$twitter_error = $tweet_errors;
		$debug = '<br />Error:(' . $twitter_error[0]->code . ')<br/> ' . $twitter_error[0]->message;
		esc_html_e( 'Unable to get tweets', 'templatic' ) . ' ' . $debug;
	} else {
		echo '<ul id="twitter_update_list" class="templatic_twitter_widget">';
		foreach ( $tweets  as $tweet ) {
			$twitter_timestamp = strtotime( $tweet->created_at );
			$tweet_date = time_elapsed_string( $twitter_timestamp );
			echo '<li>';
			echo parseTweet( $tweet->text );
			echo '<span class="twit_time" style="display: block;">' . wp_kses_post( $tweet_date ) . '</span>';
			echo '</li>';
		}
		echo '</ul>';
		if ( $follow_text ) {
			echo "<a href='http://www.twitter.com/$twitter_username/' title='$follow_text' class='twitter_title_link follow_us_twitter' target='_blank'>$follow_text</a>";
		}
	}
}
if ( ! function_exists( 'parseTweet' ) ) {
	/**
	 * Parsing the tweet messages.
	 *
	 * @param string $text 				Twitter message.
	 */
	function parseTweet( $text ) {
		$text = preg_replace( '#http://[a-z0-9._/-]+#i', '<a  target="_blank" href="$0">$0</a>', $text ); // Link.
		$text = preg_replace( '#@([a-z0-9_]+)#i', '@<a  target="_blank" href="http://twitter.com/$1">$1</a>', $text ); // Usernames.
		$text = preg_replace( '# \#([a-z0-9_-]+)#i', ' #<a target="_blank" href="http://search.twitter.com/search?q=%23$1">$1</a>', $text ); // Hashtags.
		$text = preg_replace( '#https://[a-z0-9._/-]+#i', '<a  target="_blank" href="$0">$0</a>', $text ); // Links.
		return $text;
	}
}
