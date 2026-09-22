<?php
/**
 * Registers the single Practice Management page template.
 *
 * @package Zencare
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Template slug stored in post meta. This file does not live in the theme.
 *
 * @return string
 */
function zpm_landing_template_slug() {
	return 'zencare-zpm-landing.php';
}

/**
 * Absolute path to the plugin page template.
 *
 * @return string
 */
function zpm_landing_template_path() {
	return ZPM_LANDING_DIR . 'templates/landing.php';
}

/**
 * Whether this request should render the landing template.
 *
 * Password-protected pages stay on the theme template so the landing HTML
 * is never sent in place of the password form.
 *
 * @param bool   $is_page            Whether the main query is a page.
 * @param string $slug               Page template slug.
 * @param bool   $password_required  Whether the page password is unsatisfied.
 * @return bool
 */
function zpm_landing_should_use_plugin_template( $is_page, $slug, $password_required ) {
	if ( ! $is_page ) {
		return false;
	}

	if ( (string) $slug !== zpm_landing_template_slug() ) {
		return false;
	}

	if ( $password_required ) {
		return false;
	}

	return true;
}

/**
 * Current request uses the landing template and is allowed to render it.
 *
 * @return bool
 */
function zpm_landing_is_request_for_landing() {
	if ( ! function_exists( 'is_page' ) || ! is_page() ) {
		return false;
	}

	$slug   = function_exists( 'get_page_template_slug' ) ? (string) get_page_template_slug() : '';
	$locked = function_exists( 'post_password_required' ) && post_password_required();

	return zpm_landing_should_use_plugin_template( true, $slug, $locked );
}

/**
 * Add the landing template to the page template dropdown.
 *
 * @param mixed $templates Existing templates.
 * @return array<string, string>
 */
function zpm_landing_register_template( $templates ) {
	if ( ! is_array( $templates ) ) {
		$templates = array();
	}

	$templates[ zpm_landing_template_slug() ] = __( 'Zencare Practice Management Landing', 'zencare' );

	return $templates;
}

/**
 * Swap in the plugin template only for the registered slug.
 *
 * @param string $template Theme template path.
 * @return string
 */
function zpm_landing_template_include( $template ) {
	if ( ! zpm_landing_is_request_for_landing() ) {
		return $template;
	}

	$path = zpm_landing_template_path();
	if ( is_readable( $path ) ) {
		return $path;
	}

	return $template;
}

/**
 * Keep theme and block styles off this one template.
 *
 * Admin bar styles stay so a logged-in draft preview remains usable.
 */
function zpm_landing_isolate_assets() {
	if ( ! zpm_landing_is_request_for_landing() ) {
		return;
	}

	if ( function_exists( 'wp_dequeue_style' ) ) {
		global $wp_styles;
		if ( isset( $wp_styles ) && is_object( $wp_styles ) && isset( $wp_styles->queue ) && is_array( $wp_styles->queue ) ) {
			$keep = array( 'admin-bar', 'dashicons' );
			foreach ( $wp_styles->queue as $handle ) {
				if ( in_array( $handle, $keep, true ) ) {
					continue;
				}
				wp_dequeue_style( $handle );
			}
		}
	}

	if ( function_exists( 'wp_dequeue_script' ) ) {
		wp_dequeue_script( 'zencare-theme' );
	}
}

/**
 * Force the ads-page title when WordPress prints the document title.
 *
 * @param string $title Existing title.
 * @return string
 */
function zpm_landing_pre_document_title( $title ) {
	if ( ! zpm_landing_is_request_for_landing() ) {
		return $title;
	}

	return zpm_landing_document_title_string();
}

if ( function_exists( 'add_filter' ) ) {
	add_filter( 'theme_page_templates', 'zpm_landing_register_template' );
	add_filter( 'template_include', 'zpm_landing_template_include' );
	add_filter( 'pre_get_document_title', 'zpm_landing_pre_document_title' );
}

if ( function_exists( 'add_action' ) ) {
	add_action( 'wp_enqueue_scripts', 'zpm_landing_isolate_assets', 99 );
}
