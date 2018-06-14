<?php
/**
 * Primary Sidebar Template
 *
 * @package WordPress
 * @subpackage Directory
 */

apply_filters( 'tmpl-primary', supreme_primary_sidebar() ); // Loads the sidebar-primary.
do_action( 'close_main' ); // supreme_close_main.
