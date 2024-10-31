<?php
/**
 * Product Spotlight Badge for WooCommerce - Settings Manager
 *
 * This file contains the PSBWP_Settings_Manager class, which handles the admin
 * settings page for the Product Spotlight Badge plugin.
 *
 * @package ProductSpotlightBadge
 * @since 1.0.0
 */

/**
 * PSBWP_Settings_Manager Class
 *
 * Manages the settings page for the Product Spotlight Badge plugin.
 *
 * @since 1.0.0
 */
class PSBWP_Settings_Manager {
	/**
	 * Stores the plugin options.
	 *
	 * @var array
	 */
	private $options;

	/**
	 * Class constructor.
	 *
	 * Sets up the admin menu and initializes the settings fields.
	 * Retrieves the current options from the database and stores them in the
	 * $options property.
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'page_init' ) );
		$this->options = get_option(
			'psbwp_options',
			array(
				'badge_text'             => 'NEW!',
				'badge_text_color'       => '#ff0000',
				'badge_background_color' => '#ffffff',
				'days_to_show'           => 10,
			)
		);
	}

	/**
	 * Adds the settings page for the plugin to the WordPress admin menu.
	 *
	 * Creates an options page with the title "Product Spotlight Badge Settings".
	 * The page is accessible from the WordPress admin menu under Settings.
	 *
	 * @return void
	 */
	public function add_plugin_page() {
		add_options_page(
			__( 'Product Spotlight Badge Settings', 'product-spotlight-badge' ),
			__( 'Product Spotlight Badge', 'product-spotlight-badge' ),
			'manage_options',
			'psbwp-settings',
			array( $this, 'create_admin_page' )
		);
	}

	/**
	 * Renders the admin page for Product Spotlight Badge Settings.
	 *
	 * Displays a form for setting up badge options.
	 */
	public function create_admin_page() {
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Product Spotlight Badge Settings', 'product-spotlight-badge' ); ?></h1>
			<form method="post" action="options.php">
			<?php
				settings_fields( 'psbwp_group' );
				do_settings_sections( 'psbwp-settings' );
				submit_button();
			?>
			</form>
		</div>
		<?php
	}

	/**
	 * Initializes and registers the settings and fields for the plugin's admin page.
	 *
	 * This method sets up the settings, sections, and fields for the Product Spotlight
	 * Badge plugin. It registers the settings group and options, adds the settings
	 * section, and defines the fields for badge text, text color, background color,
	 * and the number of days the badge should be displayed.
	 *
	 * @return void
	 */
	public function page_init() {
		register_setting(
			'psbwp_group',
			'psbwp_options',
			array( $this, 'sanitize' )
		);

		add_settings_section(
			'psbwp_section',
			__( 'Badge Settings', 'product-spotlight-badge' ),
			array( $this, 'print_section_info' ),
			'psbwp-settings'
		);

		add_settings_field(
			'badge_text',
			__( 'Badge Text', 'product-spotlight-badge' ),
			array( $this, 'badge_text_callback' ),
			'psbwp-settings',
			'psbwp_section'
		);

		add_settings_field(
			'badge_text_color',
			__( 'Badge Text Color', 'product-spotlight-badge' ),
			array( $this, 'badge_text_color_callback' ),
			'psbwp-settings',
			'psbwp_section'
		);

		add_settings_field(
			'badge_background_color',
			__( 'Badge Background Color', 'product-spotlight-badge' ),
			array( $this, 'badge_background_color_callback' ),
			'psbwp-settings',
			'psbwp_section'
		);

		add_settings_field(
			'days_to_show',
			__( 'Days to Show Badge', 'product-spotlight-badge' ),
			array( $this, 'days_to_show_callback' ),
			'psbwp-settings',
			'psbwp_section'
		);
	}

	/**
	 * Sanitizes the options for the plugin's settings.
	 *
	 * This method takes in the unsanitized input, sanitizes it, and returns the
	 * sanitized options array.
	 *
	 * @param array $input The unsanitized options array.
	 *
	 * @return array The sanitized options array.
	 */
	public function sanitize( $input ) {
		$new_input = array();

		if ( isset( $input['badge_text'] ) ) {
			$new_input['badge_text'] = sanitize_text_field( $input['badge_text'] );
		}

		if ( isset( $input['badge_text_color'] ) ) {
			$new_input['badge_text_color'] = sanitize_hex_color( $input['badge_text_color'] );
		}

		if ( isset( $input['badge_background_color'] ) ) {
			$new_input['badge_background_color'] = sanitize_hex_color( $input['badge_background_color'] );
		}

		if ( isset( $input['days_to_show'] ) ) {
			$new_input['days_to_show'] = absint( $input['days_to_show'] );
		}

		return $new_input;
	}

	/**
	 * Prints the section info text.
	 *
	 * This function is intended to print some text in the settings section.
	 */
	public function print_section_info() {
		// Uncomment the line below to add section information.
		// echo __( 'Enter your settings below:', 'product-spotlight-badge' );
	}

	/**
	 * Prints the text input field for the badge text option.
	 *
	 * Displays an input field for the badge text option. The value is retrieved
	 * from the current options array and sanitized using esc_attr.
	 */
	public function badge_text_callback() {
		printf(
			'<input type="text" id="badge_text" name="psbwp_options[badge_text]" value="%s" />',
			isset( $this->options['badge_text'] ) ? esc_attr( $this->options['badge_text'] ) : ''
		);
	}

	/**
	 * Prints the color input field for the badge text color option.
	 *
	 * Displays an input field for the badge text color option. The value is retrieved
	 * from the current options array and sanitized using esc_attr. The default value
	 * is #ff0000 (red).
	 */
	public function badge_text_color_callback() {
		printf(
			'<input type="color" id="badge_text_color" name="psbwp_options[badge_text_color]" value="%s" />',
			isset( $this->options['badge_text_color'] ) ? esc_attr( $this->options['badge_text_color'] ) : '#ff0000'
		);
	}

	/**
	 * Prints the color input field for the badge background color option.
	 *
	 * Displays an input field for the badge background color option. The value is retrieved
	 * from the current options array and sanitized using esc_attr. The default value
	 * is #ffffff (white).
	 */
	public function badge_background_color_callback() {
		printf(
			'<input type="color" id="badge_background_color" name="psbwp_options[badge_background_color]" value="%s" />',
			isset( $this->options['badge_background_color'] ) ? esc_attr( $this->options['badge_background_color'] ) : '#ffffff'
		);
	}

	/**
	 * Prints the number input field for the days to show badge option.
	 *
	 * Displays an input field for the "Days to Show Badge" option. The value is
	 * retrieved from the current options array and sanitized using esc_attr. The
	 * default value is 10.
	 */
	public function days_to_show_callback() {
		printf(
			'<input type="number" id="days_to_show" name="psbwp_options[days_to_show]" value="%s" />',
			isset( $this->options['days_to_show'] ) ? esc_attr( $this->options['days_to_show'] ) : '10'
		);
	}

	/**
	 * Returns the current options array.
	 *
	 * This method retrieves the current options from the database and returns
	 * them as an array. This array contains the values for the badge text,
	 * text color, background color, and the number of days to display the badge.
	 *
	 * @return array The current options array.
	 */
	public function get_options() {
		return $this->options;
	}
}