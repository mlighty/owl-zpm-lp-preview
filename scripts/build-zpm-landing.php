<?php
/**
 * Build the draft ZPM landing plugin template, static review page, and zip.
 *
 * Source of truth for copy, CSS, and CTAs is the repo root index.html.
 *
 * Usage: php scripts/build-zpm-landing.php
 *
 * @package Zencare
 */

if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __DIR__ ) . '/' );
}

/**
 * Repo root.
 *
 * @return string
 */
function zpm_landing_repo_root() {
	return dirname( __DIR__ );
}

/**
 * Locked CTA href as it appears in index.html (ampersands escaped).
 *
 * @return string
 */
function zpm_landing_source_cta_href() {
	return 'https://members.zencare.co/practice-management?fx_sid=8fa8a5f1-b3c7-4e1e-9d1e-793242e3ccaa&amp;fx_lp=https%3A%2F%2Ftherapist.zencare.co%2F&amp;fx_uid=e4d68770-086e-484a-8a40-2088f73583e2&amp;fx_gaId=GA1.1.61862098.1788981986&amp;fx_s=direct&amp;fx_m=direct&amp;fx_ch=direct&amp;fx_sc=direct_direct&amp;utm_content=therapist.zencare.co&amp;fx_utmct=therapist.zencare.co';
}

/**
 * Asset files the landing page references.
 *
 * @return string[]
 */
function zpm_landing_asset_files() {
	return array(
		'favicon-32x32.png',
		'favicon-16x16.png',
		'favicon.ico',
		'apple-touch-icon.png',
		'zencare-logo.webp',
		'zpm-calendar.png',
		'zpm-notes.png',
	);
}

/**
 * Read the static landing page.
 *
 * @return string
 */
function zpm_landing_read_source_html() {
	$path = zpm_landing_repo_root() . '/index.html';
	$html = file_get_contents( $path );
	if ( ! is_string( $html ) || '' === $html ) {
		throw new RuntimeException( 'Could not read index.html' );
	}
	return $html;
}

/**
 * Turn index.html into the plugin page template.
 *
 * @param string $html Source HTML.
 * @return string
 */
function zpm_landing_generate_template_php( $html ) {
	$gtm_pattern = '/[ \t]*<!-- Existing production GTM only\..*?<\/script>\n/s';
	if ( ! preg_match( $gtm_pattern, $html ) ) {
		throw new RuntimeException( 'GTM block not found in index.html' );
	}
	$html = preg_replace( $gtm_pattern, "  <?php zpm_landing_print_gtm_snippet(); ?>\n", $html, 1 );

	$title = '<title>Mental Health EHR for Private Practices | Zencare Practice Management</title>';
	if ( ! str_contains( $html, $title ) ) {
		throw new RuntimeException( 'Document title not found in index.html' );
	}
	$html = str_replace(
		$title,
		"<?php if ( ! function_exists( 'current_theme_supports' ) || ! current_theme_supports( 'title-tag' ) ) : ?>\n<title><?php echo esc_html( zpm_landing_document_title_string() ); ?></title>\n<?php endif; ?>",
		$html
	);

	$html = str_replace(
		'<html lang="en">',
		'<html <?php zpm_landing_language_attributes(); ?>>',
		$html
	);

	$body = "<body>\n";
	if ( ! str_contains( $html, $body ) ) {
		throw new RuntimeException( 'Body tag not found in index.html' );
	}
	$html = str_replace(
		$body,
		"<body <?php zpm_landing_body_attributes(); ?>>\n<?php if ( function_exists( 'wp_body_open' ) ) { wp_body_open(); } ?>\n",
		$html
	);

	$cta   = zpm_landing_source_cta_href();
	$count = 0;
	$html  = str_replace( $cta, '<?php echo esc_url( zpm_landing_cta_url() ); ?>', $html, $count );
	if ( 5 !== $count ) {
		throw new RuntimeException( 'Expected 5 locked CTA hrefs in index.html, found ' . $count );
	}

	$asset_count = 0;
	$html        = preg_replace_callback(
		'#(src|href)="\./assets/([^"]+)"#',
		static function ( $matches ) {
			$file = $matches[2];
			if ( ! preg_match( '/^[A-Za-z0-9._-]+$/', $file ) ) {
				throw new RuntimeException( 'Unexpected asset name: ' . $file );
			}
			return $matches[1] . '="<?php echo esc_url( zpm_landing_asset_uri( \'' . $file . '\' ) ); ?>"';
		},
		$html,
		-1,
		$asset_count
	);
	if ( count( zpm_landing_asset_files() ) !== $asset_count ) {
		throw new RuntimeException( 'Expected ' . count( zpm_landing_asset_files() ) . ' asset URLs, found ' . $asset_count );
	}

	$style_count = 0;
	$html        = preg_replace(
		'/<style>/',
		"<?php if ( function_exists( 'wp_head' ) ) { wp_head(); } ?>\n  <style>",
		$html,
		1,
		$style_count
	);
	if ( 1 !== $style_count ) {
		throw new RuntimeException( 'Landing stylesheet block not found' );
	}

	$closed = 0;
	$html   = str_replace(
		'</body>',
		"<?php if ( function_exists( 'wp_footer' ) ) { wp_footer(); } ?>\n</body>",
		$html,
		$closed
	);
	if ( 1 !== $closed ) {
		throw new RuntimeException( 'Expected one body close tag' );
	}

	$without_php = str_replace( '<?php', '', $html );
	if ( str_contains( $without_php, '<?' ) ) {
		throw new RuntimeException( 'Generated template contains a short open tag' );
	}

	$preamble = <<<'PHP'
<?php
/**
 * Zencare Practice Management landing markup.
 *
 * Generated from the repo root index.html by scripts/build-zpm-landing.php.
 * Edit index.html, then rebuild. Password-protected pages return before any
 * landing HTML is sent.
 *
 * Template Name: Zencare Practice Management Landing
 * Template Post Type: page
 *
 * @package Zencare
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( function_exists( 'post_password_required' ) && post_password_required() ) {
	return;
}

?>
PHP;

	return $preamble . $html;
}

/**
 * Copy landing images into the plugin so the zip is installable.
 */
function zpm_landing_copy_assets() {
	$source = zpm_landing_repo_root() . '/assets';
	$dest   = zpm_landing_repo_root() . '/wordpress/zencare-zpm-landing/assets';
	if ( ! is_dir( $dest ) && ! mkdir( $dest, 0775, true ) && ! is_dir( $dest ) ) {
		throw new RuntimeException( 'Could not create plugin assets directory' );
	}

	foreach ( zpm_landing_asset_files() as $file ) {
		$from = $source . '/' . $file;
		$to   = $dest . '/' . $file;
		if ( ! is_file( $from ) ) {
			throw new RuntimeException( 'Missing source asset: ' . $file );
		}
		if ( ! copy( $from, $to ) ) {
			throw new RuntimeException( 'Could not copy asset: ' . $file );
		}
		if ( hash_file( 'sha256', $from ) !== hash_file( 'sha256', $to ) ) {
			throw new RuntimeException( 'Asset checksum mismatch: ' . $file );
		}
	}
}

/**
 * Render the plugin template to static HTML for review without WordPress.
 *
 * @return string
 */
function zpm_landing_render_review_html() {
	if ( ! defined( 'ZPM_LANDING_ASSET_BASE' ) ) {
		define( 'ZPM_LANDING_ASSET_BASE', '../assets/' );
	}
	if ( ! defined( 'ZPM_LANDING_FILE' ) ) {
		define( 'ZPM_LANDING_FILE', zpm_landing_repo_root() . '/wordpress/zencare-zpm-landing/zencare-zpm-landing.php' );
	}
	if ( ! defined( 'ZPM_LANDING_DIR' ) ) {
		define( 'ZPM_LANDING_DIR', zpm_landing_repo_root() . '/wordpress/zencare-zpm-landing/' );
	}

	require_once zpm_landing_repo_root() . '/wordpress/zencare-zpm-landing/includes/helpers.php';

	if ( ! function_exists( 'esc_html' ) ) {
		/**
		 * Review stub for esc_html.
		 *
		 * @param string $text Text.
		 * @return string
		 */
		function esc_html( $text ) {
			return htmlspecialchars( (string) $text, ENT_QUOTES | ENT_HTML5, 'UTF-8' );
		}
	}
	if ( ! function_exists( 'esc_url' ) ) {
		/**
		 * Review stub for esc_url. Allows only https URLs and plugin-relative assets.
		 *
		 * @param string $url URL.
		 * @return string
		 */
		function esc_url( $url ) {
			$url = (string) $url;
			if ( ! preg_match( '#^(https://|\.\./assets/)#', $url ) ) {
				return '';
			}
			return htmlspecialchars( $url, ENT_QUOTES | ENT_HTML5, 'UTF-8' );
		}
	}

	$GLOBALS['zpm_password_required'] = false;
	if ( ! function_exists( 'post_password_required' ) ) {
		/**
		 * Review stub. Tests flip $GLOBALS['zpm_password_required'].
		 *
		 * @return bool
		 */
		function post_password_required() {
			return ! empty( $GLOBALS['zpm_password_required'] );
		}
	}

	ob_start();
	include zpm_landing_repo_root() . '/wordpress/zencare-zpm-landing/templates/landing.php';
	$rendered = ob_get_clean();
	if ( ! is_string( $rendered ) ) {
		throw new RuntimeException( 'Review render failed' );
	}

	return "<!-- Static review copy of the ZPM landing template. Not WordPress. Do not publish. -->\n" . $rendered;
}

/**
 * Write the plugin zip with the plugin folder at the archive root.
 *
 * @return string Zip path.
 */
function zpm_landing_write_zip() {
	$root    = zpm_landing_repo_root();
	$dest    = $root . '/dist/zencare-zpm-landing.zip';
	$staging = sys_get_temp_dir() . '/zpm-landing-zip-' . getmypid();
	$plugin  = $staging . '/zencare-zpm-landing';

	if ( is_dir( $staging ) ) {
		zpm_landing_remove_tree( $staging );
	}
	if ( ! mkdir( $plugin, 0775, true ) && ! is_dir( $plugin ) ) {
		throw new RuntimeException( 'Could not create zip staging directory' );
	}

	zpm_landing_copy_tree( $root . '/wordpress/zencare-zpm-landing', $plugin );
	if ( ! copy( $root . '/DRAFT-INSTALL.md', $plugin . '/DRAFT-INSTALL.md' ) ) {
		throw new RuntimeException( 'Could not copy DRAFT-INSTALL.md into the zip' );
	}
	if ( ! copy( $root . '/CHANGELOG.md', $plugin . '/CHANGELOG.md' ) ) {
		throw new RuntimeException( 'Could not copy CHANGELOG.md into the zip' );
	}

	if ( ! is_dir( $root . '/dist' ) && ! mkdir( $root . '/dist', 0775, true ) && ! is_dir( $root . '/dist' ) ) {
		throw new RuntimeException( 'Could not create dist directory' );
	}
	if ( is_file( $dest ) ) {
		unlink( $dest );
	}

	$command = 'cd ' . escapeshellarg( $staging ) . ' && zip -r -X ' . escapeshellarg( $dest ) . ' zencare-zpm-landing';
	$output  = array();
	$code    = 1;
	exec( $command, $output, $code );
	zpm_landing_remove_tree( $staging );
	if ( 0 !== $code || ! is_file( $dest ) ) {
		throw new RuntimeException( 'zip failed: ' . implode( "\n", $output ) );
	}

	return $dest;
}

/**
 * Copy a directory tree.
 *
 * @param string $from Source directory.
 * @param string $to   Destination directory.
 */
function zpm_landing_copy_tree( $from, $to ) {
	$iterator = new RecursiveIteratorIterator(
		new RecursiveDirectoryIterator( $from, FilesystemIterator::SKIP_DOTS ),
		RecursiveIteratorIterator::SELF_FIRST
	);
	foreach ( $iterator as $item ) {
		$target = $to . substr( $item->getPathname(), strlen( $from ) );
		if ( $item->isDir() ) {
			if ( ! is_dir( $target ) && ! mkdir( $target, 0775, true ) && ! is_dir( $target ) ) {
				throw new RuntimeException( 'Could not create ' . $target );
			}
			continue;
		}
		$parent = dirname( $target );
		if ( ! is_dir( $parent ) && ! mkdir( $parent, 0775, true ) && ! is_dir( $parent ) ) {
			throw new RuntimeException( 'Could not create ' . $parent );
		}
		if ( ! copy( $item->getPathname(), $target ) ) {
			throw new RuntimeException( 'Could not copy ' . $item->getPathname() );
		}
	}
}

/**
 * Delete a directory tree.
 *
 * @param string $dir Directory.
 */
function zpm_landing_remove_tree( $dir ) {
	if ( ! is_dir( $dir ) ) {
		return;
	}
	$iterator = new RecursiveIteratorIterator(
		new RecursiveDirectoryIterator( $dir, FilesystemIterator::SKIP_DOTS ),
		RecursiveIteratorIterator::CHILD_FIRST
	);
	foreach ( $iterator as $item ) {
		if ( $item->isDir() ) {
			rmdir( $item->getPathname() );
		} else {
			unlink( $item->getPathname() );
		}
	}
	rmdir( $dir );
}

/**
 * Write generated plugin template, assets, review HTML, and the zip.
 *
 * @return string Zip path.
 */
function zpm_landing_build_write_all() {
	$root     = zpm_landing_repo_root();
	$template = zpm_landing_generate_template_php( zpm_landing_read_source_html() );
	$path     = $root . '/wordpress/zencare-zpm-landing/templates/landing.php';
	if ( false === file_put_contents( $path, $template ) ) {
		throw new RuntimeException( 'Could not write landing.php' );
	}

	zpm_landing_copy_assets();

	$review_dir = $root . '/vercel-review';
	if ( ! is_dir( $review_dir ) && ! mkdir( $review_dir, 0775, true ) && ! is_dir( $review_dir ) ) {
		throw new RuntimeException( 'Could not create vercel-review' );
	}
	$review = zpm_landing_render_review_html();
	if ( false === file_put_contents( $review_dir . '/index.html', $review ) ) {
		throw new RuntimeException( 'Could not write vercel-review/index.html' );
	}

	return zpm_landing_write_zip();
}

if ( PHP_SAPI === 'cli' && isset( $argv[0] ) && realpath( $argv[0] ) === realpath( __FILE__ ) ) {
	try {
		$zip = zpm_landing_build_write_all();
		fwrite( STDOUT, "Wrote {$zip}\n" );
	} catch ( Throwable $error ) {
		fwrite( STDERR, $error->getMessage() . "\n" );
		exit( 1 );
	}
}
