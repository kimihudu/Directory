<?php
/**
 * Search Form Template
 *
 * The search form template displays the search form.
 *
 * @package WordPress
 * @subpackage Directory
 */

?>
<form method="get" class="search-form" action="<?php echo esc_url( trailingslashit( home_url() ) ); ?>">
	<div>
		<input type="hidden" value="listing" name="post_type">
		<input type="hidden" value="all" name="mkey[]">
		<span role="status" aria-live="polite" class="ui-helper-hidden-accessible"></span><input type="text" size="100" placeholder="<?php esc_html_e( 'Looking For ...', 'templatic' ); ?>" class="searchpost "  name="s" value="" autocomplete="off">

		<input type="submit" value="<?php esc_html_e( 'Search', 'templatic' ); ?>"  class="sgo">
	</div>
</form>
<!-- .search-form -->
