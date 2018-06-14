<?php
/**
 * Comments Template
 *
 * Lists comments and calls the comment form.  Individual comments have their own templates.  The
 * hierarchy for these templates is $comment_type.php, comment.php.
 *
 * @package WordPress
 * @subpackage Directory
 */

/* Kill the page if trying to access this template directly. */
if ( isset( $_SERVER['SCRIPT_FILENAME'] ) && '' != $_SERVER['SCRIPT_FILENAME'] && 'comments.php' == basename( sanitize_text_field( wp_unslash( $_SERVER['SCRIPT_FILENAME'] ) ) ) ) {
	die( esc_html__( 'Please do not load this page directly. Thanks!', 'templatic' ) );
}
/* If a post password is required or no comments are given and comments/pings are closed, return. */
if ( post_password_required() || ( ! have_comments() && ! comments_open() && ! pings_open() ) ) {
	return;
}
?>
<section id="comments-template">
	<div class="comments-wrap <?php if ( ! get_option( 'show_avatars' ) ) { echo 'no-gravatar'; } ?> ">
	<article id="comments">
		<?php if ( have_comments() ) :

			do_action( 'show_comment' );	?>
			<h3 id="comments-number" class="comments-header">
			<?php
			global $post;
			if ( 'post' == $post->post_type ) {
				templatic_comments_number( esc_attr( __( 'No Comment', 'templatic' ) ) , esc_attr( __( 'One Comment', 'templatic' ) ),  esc_attr( __( 'Comments', 'templatic' ) ) );
			} else {
				templatic_comments_number( __( 'No Review', 'templatic' ), __( 'One Review', 'templatic' ), __( 'Reviews', 'templatic' ) );
			}?>
			</h3>
			<?php
			do_action( 'before_comment_list' );

			if ( get_option( 'page_comments' ) ) : ?>
				<div class="comment-navigation comment-pagination">
					<span class="page-numbers">
						<?php
						/* pagination for comments. */
						printf( __( 'Page %1$s of %2$s', 'templatic' ), ( get_query_var( 'cpage' ) ? absint( get_query_var( 'cpage' ) ) : 1 ), esc_attr( get_comment_pages_count() ) ); ?>
					</span>
				<?php paginate_comments_links(); ?>
				</div>
			<!-- .comment-navigation -->
			<?php endif; ?>
				<ol class="comment-list">
				<?php wp_list_comments( supreme_list_comments_args() ); ?>
				</ol>
		<!-- .comment-list -->
		<?php
		do_action( 'after_comment_list' );
		 endif;

if ( pings_open() && ! comments_open() ) : ?>
	<p class="comments-closed pings-open"> <?php
	esc_html_e( 'Reviews are disabled, but ','templatic' );
	echo '<a href=' . esc_url( get_trackback_url() ) . ' title="Track back URL for this post">';
		esc_html_e( 'trackbacks', 'templatic' );
	echo '</a> ';
	esc_html_e( 'and pingbacks are open.', 'templatic' );
	?>
	</p>
	<!-- .comments-closed .pings-open -->
<?php
	elseif ( ! comments_open() ) : ?>
	  <p class="comments-closed">
		<?php esc_html_e( 'Reviews are disabled.', 'templatic' ); ?>
	  </p>
	<!-- .comments-closed -->
	<?php
endif; ?>
	</article>
	<!-- #comments -->

	<?php
	if ( 'open' == get_option( 'default_comment_status' ) ) {
		comment_form( ( isset( $arg ) ) ? $arg : array() );
	} // End if().  ?>
	</div>
<!-- .comments-wrap -->
</section>
<!-- #comments-template -->
