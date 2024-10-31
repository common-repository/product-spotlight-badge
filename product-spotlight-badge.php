<?php
/**
 * Plugin Name: Product Spotlight Badge
 * Description: Show "NEW" badges for recently added WooCommerce products with customizable settings.
 * Plugin URI: https://wordpress.org/plugins/product-spotlight-badge/
 * Version: 1.0.1
 * Author: Huzaifa Al Mesbah
 * Author URI: https://www.linkedin.com/in/huzaifaalmesbah/
 * text-domain: product-spotlight-badge
 * Requires Plugins: woocommerce
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package ProductSpotlightBadge
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define plugin constants.
define( 'PSBWP_VERSION', '1.0.1' );
define( 'PSBWP_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'PSBWP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'PSBWP_ASSETS_URL', PSBWP_PLUGIN_URL . 'assets/' );

// Include necessary files.
require_once PSBWP_PLUGIN_PATH . 'includes/class-psbwp-badge-display.php';
require_once PSBWP_PLUGIN_PATH . 'includes/class-psbwp-settings-manager.php';

/**
 * Initializes the Product Spotlight Badge plugin.
 *
 * This function is hooked to the `plugins_loaded` action. It creates an instance
 * of the `PSBWP_Badge_Display` class and calls its `init` method to set up the
 * necessary hooks and filters.
 *
 * @since 1.0.0
 */
function psbwp_init() {
	$badge_display = new PSBWP_Badge_Display();
	$badge_display->init();
}

add_action( 'plugins_loaded', 'psbwp_init' );
