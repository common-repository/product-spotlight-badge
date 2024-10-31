<?php
/**
 * Product Spotlight Badge for WooCommerce
 *
 * This file defines the PSBWP_Badge_Display class, which handles the display
 * of the "NEW!" badge on WooCommerce product listings and single product pages.
 *
 * @package ProductSpotlightBadge
 * @version 1.0.0
 */

/**
 * Class PSBWP_Badge_Display
 *
 * Handles the display of the "NEW!" badge on WooCommerce product listings and single product pages.
 *
 * @package ProductSpotlightBadge
 */
class PSBWP_Badge_Display {
	/**
	 * Holds the settings manager instance.
	 *
	 * @var PSBWP_Settings_Manager
	 */
	private $settings;

	/**
	 * Initializes the plugin by creating an instance of the settings manager
	 * and hooking into the necessary actions.
	 *
	 * Hooks into the following actions:
	 *  - woocommerce_before_shop_loop_item_title
	 *  - woocommerce_before_single_product_summary
	 *  - wp_enqueue_scripts
	 *
	 * @return void
	 */
	public function init() {
		$this->settings = new PSBWP_Settings_Manager();
		add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'display_badge' ), 1 );
		add_action( 'woocommerce_before_single_product_summary', array( $this, 'display_badge' ), 3 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
	}

	/**
	 * Display the "NEW!" badge on the product loop and single product pages,
	 * if the product was added within the specified number of days.
	 *
	 * @return void
	 */
	public function display_badge() {
		global $product;
		$options                = $this->settings->get_options();
		$days_to_show           = isset( $options['days_to_show'] ) ? $options['days_to_show'] : 10;
		$product_published_date = strtotime( $product->get_date_created() );

		// Check if the product was published within the specified days.
		if ( ( time() - ( 60 * 60 * 24 * $days_to_show ) ) < $product_published_date ) {
			$badge_text             = isset( $options['badge_text'] ) ? $options['badge_text'] : 'NEW!';
			$badge_text_color       = isset( $options['badge_text_color'] ) ? $options['badge_text_color'] : '#ffffff'; // Default to white.
			$badge_background_color = isset( $options['badge_background_color'] ) ? $options['badge_background_color'] : '#ff0000'; // Default to red.
			echo '<span class="psbwp-badge" style="color: ' . esc_attr( $badge_text_color ) . '; background-color: ' . esc_attr( $badge_background_color ) . ';">' . esc_html( $badge_text ) . '</span>';
		}
	}

	/**
	 * Enqueues the CSS styles for the badge.
	 *
	 * @return void
	 */
	public function enqueue_styles() {
		if ( class_exists( 'WooCommerce' ) ) {
			if ( is_product() || is_shop() || is_product_category() ) {
				wp_enqueue_style( 'psbwp-badge-style', PSBWP_ASSETS_URL . 'css/psbwp-badge.css', array(), PSBWP_VERSION );
			}
		}
	}
}
