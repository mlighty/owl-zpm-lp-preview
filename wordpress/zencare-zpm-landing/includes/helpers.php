<?php
/**
 * Shared helpers for the Practice Management landing template.
 *
 * @package Zencare
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'ZPM_LANDING_FILE' ) ) {
	define( 'ZPM_LANDING_FILE', dirname( __DIR__ ) . '/zencare-zpm-landing.php' );
}

if ( ! defined( 'ZPM_LANDING_DIR' ) ) {
	define( 'ZPM_LANDING_DIR', dirname( __DIR__ ) . '/' );
}

/**
 * Locked Start free trial URL from the static landing page.
 *
 * The query string is the one already stored in index.html. Do not invent a
 * different destination, price, or trial length.
 *
 * @return string
 */
function zpm_landing_cta_url() {
	$url = 'https://members.zencare.co/practice-management?fx_sid=8fa8a5f1-b3c7-4e1e-9d1e-793242e3ccaa&fx_lp=https%3A%2F%2Ftherapist.zencare.co%2F&fx_uid=e4d68770-086e-484a-8a40-2088f73583e2&fx_gaId=GA1.1.61862098.1788981986&fx_s=direct&fx_m=direct&fx_ch=direct&fx_sc=direct_direct&utm_content=therapist.zencare.co&fx_utmct=therapist.zencare.co';

	if ( function_exists( 'apply_filters' ) ) {
		$url = apply_filters( 'zpm_landing_cta_url', $url );
	}

	return $url;
}

/**
 * Production GTM container already used by the static landing page.
 *
 * Define ZPM_LANDING_GTM_ID in wp-config.php to override. An empty or invalid
 * value prints a stub comment and does not guess a container.
 *
 * @return string
 */
function zpm_landing_gtm_id() {
	if ( defined( 'ZPM_LANDING_GTM_ID' ) ) {
		$id = (string) ZPM_LANDING_GTM_ID;
	} else {
		$id = 'GTM-PQF8HF5T';
	}

	if ( function_exists( 'apply_filters' ) ) {
		$id = apply_filters( 'zpm_landing_gtm_id', $id );
	}

	return (string) $id;
}

/**
 * Hosts allowed to load Google Tag Manager.
 *
 * therapist.zencare.co was the previous allowlist. zpm.zencare.co is required
 * so the same container loads on the Practice Management hostname.
 *
 * @return string[]
 */
function zpm_landing_gtm_hosts() {
	$hosts = array(
		'therapist.zencare.co',
		'zpm.zencare.co',
	);

	if ( function_exists( 'apply_filters' ) ) {
		$hosts = apply_filters( 'zpm_landing_gtm_hosts', $hosts );
	}

	if ( ! is_array( $hosts ) ) {
		return array();
	}

	$clean = array();
	foreach ( $hosts as $host ) {
		if ( is_string( $host ) && '' !== $host ) {
			$clean[] = $host;
		}
	}

	return array_values( $clean );
}

/**
 * Public URL for a file shipped in this plugin's assets directory.
 *
 * @param string $file File name relative to assets/.
 * @return string
 */
function zpm_landing_asset_uri( $file ) {
	$file = ltrim( (string) $file, '/' );

	if ( defined( 'ZPM_LANDING_ASSET_BASE' ) ) {
		return ZPM_LANDING_ASSET_BASE . $file;
	}

	if ( function_exists( 'plugins_url' ) ) {
		return plugins_url( 'assets/' . $file, ZPM_LANDING_FILE );
	}

	return 'assets/' . $file;
}

/**
 * JSON safe to embed in a script tag.
 *
 * @param mixed $value Value to encode.
 * @return string
 */
function zpm_landing_json_for_script( $value ) {
	$flags = JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE;

	if ( function_exists( 'wp_json_encode' ) ) {
		$encoded = wp_json_encode( $value );
		if ( is_string( $encoded ) ) {
			return $encoded;
		}
	}

	$encoded = json_encode( $value, $flags );
	if ( ! is_string( $encoded ) ) {
		return 'null';
	}

	return $encoded;
}

/**
 * Document title used when the theme does not support title-tag.
 *
 * @return string
 */
function zpm_landing_document_title_string() {
	return 'Mental Health EHR for Private Practices | Zencare Practice Management';
}

/**
 * Echo a lang attribute. Uses WordPress when the theme API is available.
 */
function zpm_landing_language_attributes() {
	if ( function_exists( 'language_attributes' ) ) {
		language_attributes();
		return;
	}

	echo 'lang="en"';
}

/**
 * Echo a class attribute, including the landing body class.
 */
function zpm_landing_body_attributes() {
	if ( function_exists( 'body_class' ) ) {
		body_class( 'zpm-landing' );
		return;
	}

	echo 'class="zpm-landing"';
}

/**
 * Print the GTM loader, or a stub when no valid container ID is configured.
 *
 * The loader checks the browser hostname so a cached copy still loads on both
 * production hosts and stays silent on draft previews and other hostnames.
 * A noscript iframe is omitted on purpose: it cannot see the hostname and
 * would fire the container on every host.
 */
function zpm_landing_print_gtm_snippet() {
	$id = zpm_landing_gtm_id();
	if ( ! preg_match( '/^GTM-[A-Z0-9]+$/', $id ) ) {
		echo "<!-- ZPM GTM stub: no valid container ID. Daniel, set ZPM_LANDING_GTM_ID in wp-config.php to the existing container. This template will not guess an ID. -->\n";
		return;
	}

	$hosts_json = zpm_landing_json_for_script( zpm_landing_gtm_hosts() );
	$id_json    = zpm_landing_json_for_script( $id );
	?>
<script>
window.dataLayer = window.dataLayer || [];
(function () {
  var allowedHosts = <?php echo $hosts_json; // Encoded with JSON_HEX_TAG so it cannot close the script. ?>;
  if (allowedHosts.indexOf(window.location.hostname) === -1) {
    return;
  }
  var id = <?php echo $id_json; // Encoded with JSON_HEX_TAG so it cannot close the script. ?>;
  (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});
  var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';
  j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
  })(window,document,'script','dataLayer',id);
})();
</script>
<?php
}
