<?php
/**
 * Verify the draft ZPM landing plugin renders, keeps every CTA, and
 * does not leak landing HTML for password-protected pages.
 *
 * Usage: php scripts/verify-zpm-landing.php
 *
 * @package Zencare
 */

$failures = array();

/**
 * Record a failed check.
 *
 * @param string $message Failure.
 */
function zpm_verify_fail( $message ) {
	global $failures;
	$failures[] = $message;
	fwrite( STDERR, "FAIL: {$message}\n" );
}

/**
 * Record a passed check.
 *
 * @param string $message Check.
 */
function zpm_verify_ok( $message ) {
	fwrite( STDOUT, "OK: {$message}\n" );
}

$root = dirname( __DIR__ );
require_once $root . '/scripts/build-zpm-landing.php';

$php_files = array(
	$root . '/wordpress/zencare-zpm-landing/zencare-zpm-landing.php',
	$root . '/wordpress/zencare-zpm-landing/includes/helpers.php',
	$root . '/wordpress/zencare-zpm-landing/includes/template-loader.php',
	$root . '/wordpress/zencare-zpm-landing/templates/landing.php',
	$root . '/scripts/build-zpm-landing.php',
	$root . '/scripts/verify-zpm-landing.php',
);

foreach ( $php_files as $php_file ) {
	$output = array();
	$code   = 1;
	exec( 'php -l ' . escapeshellarg( $php_file ), $output, $code );
	if ( 0 !== $code ) {
		zpm_verify_fail( 'php -l ' . $php_file . ' ' . implode( ' ', $output ) );
	}
}
if ( empty( $failures ) ) {
	zpm_verify_ok( 'php -l on plugin and scripts' );
}

$generated = zpm_landing_generate_template_php( zpm_landing_read_source_html() );
$stored    = file_get_contents( $root . '/wordpress/zencare-zpm-landing/templates/landing.php' );
if ( $generated !== $stored ) {
	zpm_verify_fail( 'templates/landing.php does not match index.html. Run php scripts/build-zpm-landing.php' );
} else {
	zpm_verify_ok( 'landing.php matches index.html' );
}

foreach ( zpm_landing_asset_files() as $asset_file ) {
	$from = $root . '/assets/' . $asset_file;
	$to   = $root . '/wordpress/zencare-zpm-landing/assets/' . $asset_file;
	if ( ! is_file( $to ) || hash_file( 'sha256', $from ) !== hash_file( 'sha256', $to ) ) {
		zpm_verify_fail( 'Plugin asset missing or checksum differs: ' . $asset_file );
	}
}
if ( ! preg_grep( '/Plugin asset missing/', $failures ) ) {
	zpm_verify_ok( 'plugin assets match repo assets' );
}

if ( ! function_exists( 'apply_filters' ) ) {
	/**
	 * Test double. Only the GTM override is special-cased.
	 *
	 * @param string $tag   Hook.
	 * @param mixed  $value Value.
	 * @return mixed
	 */
	function apply_filters( $tag, $value ) {
		if ( 'zpm_landing_gtm_id' === $tag && array_key_exists( 'zpm_gtm_override', $GLOBALS ) && null !== $GLOBALS['zpm_gtm_override'] ) {
			return $GLOBALS['zpm_gtm_override'];
		}
		return $value;
	}
}

if ( ! function_exists( '__' ) ) {
	/**
	 * Test double for translations.
	 *
	 * @param string $text   Text.
	 * @param string $domain Domain.
	 * @return string
	 */
	function __( $text, $domain = 'default' ) {
		unset( $domain );
		return $text;
	}
}

if ( ! function_exists( 'is_page' ) ) {
	/**
	 * Test double.
	 *
	 * @return bool
	 */
	function is_page() {
		return ! empty( $GLOBALS['zpm_is_page'] );
	}
}

if ( ! function_exists( 'get_page_template_slug' ) ) {
	/**
	 * Test double.
	 *
	 * @return string
	 */
	function get_page_template_slug() {
		return isset( $GLOBALS['zpm_template_slug'] ) ? (string) $GLOBALS['zpm_template_slug'] : '';
	}
}

if ( ! function_exists( 'wp_dequeue_style' ) ) {
	/**
	 * Test double.
	 *
	 * @param string $handle Handle.
	 */
	function wp_dequeue_style( $handle ) {
		$GLOBALS['zpm_dequeued_styles'][] = $handle;
	}
}

if ( ! function_exists( 'wp_dequeue_script' ) ) {
	/**
	 * Test double.
	 *
	 * @param string $handle Handle.
	 */
	function wp_dequeue_script( $handle ) {
		$GLOBALS['zpm_dequeued_scripts'][] = $handle;
	}
}

$GLOBALS['zpm_password_required'] = false;
$review                           = zpm_landing_render_review_html();
$review_path                      = $root . '/vercel-review/index.html';
$review_stored                    = file_get_contents( $review_path );
if ( $review !== $review_stored ) {
	zpm_verify_fail( 'vercel-review/index.html is stale. Run php scripts/build-zpm-landing.php' );
} else {
	zpm_verify_ok( 'vercel-review/index.html matches the template render' );
}

/**
 * Collect Start free trial anchors.
 *
 * @param string $html HTML.
 * @return array<int, array{location: string, href: string}>
 */
function zpm_verify_ctas( $html ) {
	$previous = libxml_use_internal_errors( true );
	$dom      = new DOMDocument();
	$dom->loadHTML( $html );
	libxml_clear_errors();
	libxml_use_internal_errors( $previous );
	$found = array();
	foreach ( $dom->getElementsByTagName( 'a' ) as $anchor ) {
		if ( ! $anchor->hasAttribute( 'data-cta-location' ) ) {
			continue;
		}
		$found[] = array(
			'location' => $anchor->getAttribute( 'data-cta-location' ),
			'href'     => $anchor->getAttribute( 'href' ),
		);
	}
	return $found;
}

/**
 * Visible text with tags, scripts, styles, and comments removed.
 *
 * @param string $html HTML.
 * @return string
 */
function zpm_verify_text( $html ) {
	$html = preg_replace( '/<!--.*?-->/s', ' ', $html );
	$html = preg_replace( '/<script\b[^>]*>.*?<\/script>/is', ' ', $html );
	$html = preg_replace( '/<style\b[^>]*>.*?<\/style>/is', ' ', $html );
	$text = trim( preg_replace( '/\s+/', ' ', strip_tags( $html ) ) );
	return $text;
}

$expected_url       = zpm_landing_cta_url();
$expected_locations = array( 'header', 'hero', 'trial', 'final', 'mobile_sticky' );
$source_html        = zpm_landing_read_source_html();

foreach ( array( 'index.html' => $source_html, 'review' => $review ) as $label => $html ) {
	$ctas      = zpm_verify_ctas( $html );
	$locations = array_column( $ctas, 'location' );
	if ( $locations !== $expected_locations ) {
		zpm_verify_fail( $label . ' CTA locations are ' . implode( ',', $locations ) );
		continue;
	}
	$hrefs_ok = true;
	foreach ( $ctas as $cta ) {
		if ( $cta['href'] !== $expected_url ) {
			$hrefs_ok = false;
			zpm_verify_fail( $label . ' ' . $cta['location'] . ' href is ' . $cta['href'] );
		}
	}
	if ( $hrefs_ok ) {
		zpm_verify_ok( $label . ' has all five CTA locations and the locked URL' );
	}
	foreach ( array( 'ChatGPT', 'chatgpt', 'OpenAI' ) as $banned ) {
		if ( str_contains( $html, $banned ) ) {
			zpm_verify_fail( $label . ' contains ' . $banned );
		}
	}
	if ( preg_match( '/\$\s?\d|\b\d+\s*-?\s*days?\b/i', zpm_verify_text( $html ) ) ) {
		zpm_verify_fail( $label . ' appears to invent a price or trial length' );
	}
}

if ( zpm_verify_text( $source_html ) !== zpm_verify_text( $review ) ) {
	zpm_verify_fail( 'Review text does not match index.html text' );
} else {
	zpm_verify_ok( 'Review copy matches index.html' );
}

foreach ( array( 'Figtree', 'zpm-calendar.png', 'zpm-notes.png', 'favicon.ico', 'GTM-PQF8HF5T', 'therapist.zencare.co', 'zpm.zencare.co' ) as $needle ) {
	if ( ! str_contains( $source_html, $needle ) || ! str_contains( $review, $needle ) ) {
		zpm_verify_fail( 'Missing ' . $needle . ' from source or review' );
	}
}
if ( str_contains( $source_html, "hostname === 'therapist.zencare.co'" ) ) {
	zpm_verify_fail( 'index.html still gates GTM on therapist.zencare.co alone' );
} else {
	zpm_verify_ok( 'GTM allowlist includes both production hosts' );
}

$GLOBALS['zpm_password_required'] = true;
ob_start();
include $root . '/wordpress/zencare-zpm-landing/templates/landing.php';
$password_html = ob_get_clean();
if ( '' !== $password_html || str_contains( (string) $password_html, 'Start free trial' ) ) {
	zpm_verify_fail( 'Password-protected render leaked landing HTML' );
} else {
	zpm_verify_ok( 'Password-protected render outputs no landing HTML' );
}
$GLOBALS['zpm_password_required'] = false;

require_once $root . '/wordpress/zencare-zpm-landing/includes/template-loader.php';

$registered = zpm_landing_register_template(
	array(
		'page.php' => 'Default page',
	)
);
if ( ! isset( $registered['page.php'], $registered['zencare-zpm-landing.php'] ) || 2 !== count( $registered ) ) {
	zpm_verify_fail( 'Template registration did not add exactly one template' );
} else {
	zpm_verify_ok( 'Only the landing page template is registered' );
}

$cases = array(
	array( false, 'zencare-zpm-landing.php', false, false, 'not a page' ),
	array( true, 'page.php', false, false, 'other template' ),
	array( true, 'zencare-zpm-landing.php', true, false, 'password required' ),
	array( true, 'zencare-zpm-landing.php', false, true, 'landing page' ),
);
foreach ( $cases as $case ) {
	$result = zpm_landing_should_use_plugin_template( $case[0], $case[1], $case[2] );
	if ( $result !== $case[3] ) {
		zpm_verify_fail( 'Template gate failed for ' . $case[4] );
	}
}
zpm_verify_ok( 'Template gate keeps password-protected and other templates on the theme' );

$GLOBALS['zpm_is_page']       = true;
$GLOBALS['zpm_template_slug'] = 'zencare-zpm-landing.php';
$theme_template               = '/themes/zencare-blog/page.php';
if ( zpm_landing_template_include( $theme_template ) !== zpm_landing_template_path() ) {
	zpm_verify_fail( 'template_include did not return the plugin template' );
} else {
	zpm_verify_ok( 'template_include returns the plugin template' );
}

$GLOBALS['zpm_password_required'] = true;
if ( zpm_landing_template_include( $theme_template ) !== $theme_template ) {
	zpm_verify_fail( 'template_include replaced the theme template while a password is required' );
} else {
	zpm_verify_ok( 'template_include leaves the theme template when a password is required' );
}
$GLOBALS['zpm_password_required'] = false;

$GLOBALS['zpm_template_slug'] = 'page.php';
if ( zpm_landing_template_include( $theme_template ) !== $theme_template ) {
	zpm_verify_fail( 'template_include changed an unrelated template' );
} else {
	zpm_verify_ok( 'template_include ignores other templates' );
}

$GLOBALS['zpm_is_page']           = true;
$GLOBALS['zpm_template_slug']     = 'zencare-zpm-landing.php';
$GLOBALS['wp_styles']             = (object) array(
	'queue' => array( 'zencare-style', 'admin-bar', 'dashicons', 'wp-block-library' ),
);
$GLOBALS['zpm_dequeued_styles']   = array();
$GLOBALS['zpm_dequeued_scripts']  = array();
zpm_landing_isolate_assets();
$dequeued = $GLOBALS['zpm_dequeued_styles'];
sort( $dequeued );
if ( $dequeued !== array( 'wp-block-library', 'zencare-style' ) || $GLOBALS['zpm_dequeued_scripts'] !== array( 'zencare-theme' ) ) {
	zpm_verify_fail( 'Asset isolation dequeued unexpected handles: ' . implode( ',', $GLOBALS['zpm_dequeued_styles'] ) );
} else {
	zpm_verify_ok( 'Landing view dequeues theme styles and keeps the admin bar' );
}

$GLOBALS['zpm_password_required'] = true;
$GLOBALS['zpm_dequeued_styles']   = array();
$GLOBALS['zpm_dequeued_scripts']  = array();
zpm_landing_isolate_assets();
if ( array() !== $GLOBALS['zpm_dequeued_styles'] || array() !== $GLOBALS['zpm_dequeued_scripts'] ) {
	zpm_verify_fail( 'Asset isolation ran while a password is required' );
} else {
	zpm_verify_ok( 'Password form keeps theme styles' );
}

$GLOBALS['zpm_gtm_override'] = '';
ob_start();
zpm_landing_print_gtm_snippet();
$stub = ob_get_clean();
if ( str_contains( $stub, 'gtm.js' ) || ! str_contains( $stub, 'ZPM GTM stub' ) ) {
	zpm_verify_fail( 'Empty GTM ID did not leave a stub' );
} else {
	zpm_verify_ok( 'Empty GTM ID leaves a stub and does not invent a container' );
}
$GLOBALS['zpm_gtm_override'] = null;

$zip = $root . '/dist/zencare-zpm-landing.zip';
if ( ! is_file( $zip ) ) {
	zpm_verify_fail( 'dist/zencare-zpm-landing.zip is missing' );
} else {
	$listing = array();
	exec( 'zipinfo -1 ' . escapeshellarg( $zip ), $listing, $zip_code );
	$required = array(
		'zencare-zpm-landing/zencare-zpm-landing.php',
		'zencare-zpm-landing/readme.txt',
		'zencare-zpm-landing/DRAFT-INSTALL.md',
		'zencare-zpm-landing/CHANGELOG.md',
		'zencare-zpm-landing/templates/landing.php',
		'zencare-zpm-landing/includes/helpers.php',
		'zencare-zpm-landing/includes/template-loader.php',
		'zencare-zpm-landing/assets/zpm-calendar.png',
		'zencare-zpm-landing/assets/zpm-notes.png',
		'zencare-zpm-landing/assets/zencare-logo.webp',
		'zencare-zpm-landing/assets/favicon.ico',
		'zencare-zpm-landing/assets/favicon-32x32.png',
		'zencare-zpm-landing/assets/favicon-16x16.png',
		'zencare-zpm-landing/assets/apple-touch-icon.png',
	);
	$missing = array_diff( $required, $listing );
	if ( 0 !== $zip_code || array() !== $missing ) {
		zpm_verify_fail( 'Zip missing entries: ' . implode( ', ', $missing ) );
	} else {
		zpm_verify_ok( 'Install zip contains the plugin, notes, and assets' );
	}
}

$install = file_get_contents( $root . '/DRAFT-INSTALL.md' );
if ( ! str_contains( $install, 'Draft' ) || ! str_contains( $install, 'out of scope' ) || ! str_contains( $install, 'practice-management' ) ) {
	zpm_verify_fail( 'DRAFT-INSTALL.md is missing draft-only guidance' );
} else {
	zpm_verify_ok( 'Install notes say Draft-only and name the slug' );
}

if ( array() !== $failures ) {
	fwrite( STDERR, count( $failures ) . " check(s) failed\n" );
	exit( 1 );
}

fwrite( STDOUT, "All checks passed\n" );
