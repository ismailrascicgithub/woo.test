<?php
/**
 * UnderStrap enqueue scripts
 *
 * @package UnderStrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'understrap_scripts' ) ) {
	/**
	 * Load theme's JavaScript and CSS sources.
	 */
	function understrap_scripts() {
		// Get the theme data.
		$the_theme     = wp_get_theme();
		$theme_version = $the_theme->get( 'Version' );

		$css_version = $theme_version . '.' . filemtime(get_template_directory() . '/css/app.css');
		wp_enqueue_style( 'styles', get_stylesheet_directory_uri() . '/css/app.css', array(), $css_version );

		// wp_enqueue_script( 'jquery' ); we enque our own jquery inside app.js

		$js_version = $theme_version . '.' . filemtime(get_template_directory() . '/js/dist/app.js');
		wp_enqueue_script( 'scripts', get_template_directory_uri() . '/js/dist/app.js', array(), $js_version, true );
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}
} // End of if function_exists( 'understrap_scripts' ).

add_action( 'wp_enqueue_scripts', 'understrap_scripts' );
