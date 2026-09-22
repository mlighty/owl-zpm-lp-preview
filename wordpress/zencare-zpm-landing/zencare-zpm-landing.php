<?php
/**
 * Plugin Name: Zencare Practice Management Landing
 * Plugin URI: https://zencare.co
 * Description: Isolated draft page template for the Zencare Practice Management ads landing page. Registers one page template and does not change any other template.
 * Version: 1.0.0
 * Author: Zencare
 * Author URI: https://zencare.co
 * Text Domain: zencare
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package Zencare
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ZPM_LANDING_VERSION', '1.0.0' );
define( 'ZPM_LANDING_FILE', __FILE__ );
define( 'ZPM_LANDING_DIR', plugin_dir_path( __FILE__ ) );

require_once ZPM_LANDING_DIR . 'includes/helpers.php';
require_once ZPM_LANDING_DIR . 'includes/template-loader.php';
