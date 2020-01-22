<?php
/**
 * The admin-options page of the plugin.
 *
 * @link       https://www.vedathemes.com
 * @since      1.0.0
 *
 * @package    Podcast_Player
 * @subpackage Podcast_Player/admin
 */

namespace Podcast_Player;

/**
 * The admin-options page of the plugin.
 *
 * @package    Podcast_Player
 * @subpackage Podcast_Player/admin
 * @author     vedathemes <contact@vedathemes.com>
 */
class Options {

	/**
	 * Holds the instance of this class.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    object
	 */
	protected static $instance = null;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {}

	/**
	 * Register hooked functions.
	 *
	 * @since 1.0.0
	 */
	public static function init() {
		add_action( 'admin_menu', [ self::get_instance(), 'add_options_page' ] );
		add_action( 'admin_init', [ self::get_instance(), 'add_settings' ] );
	}

	/**
	 * Add plugin specific options page.
	 *
	 * @since    1.5
	 */
	public function add_options_page() {
		add_options_page(
			esc_html__( 'Podcast Player', 'podcast-player' ),
			esc_html__( 'Podcast Player', 'podcast-player' ),
			'manage_options',
			'pp-options',
			array( $this, 'pp_options' )
		);
	}

	/**
	 * Display podcast player options page.
	 *
	 * @since    1.0.0
	 */
	public function add_settings() {
		// Register settings with Validation callback.
		register_setting(
			'podcast-player-options',
			'pp-legacy-player',
			array( 'sanitize_callback' => array( $this, 'validate_settings' ) )
		);

		// Settings section.
		add_settings_section(
			'podcast-player-general-section',
			esc_html__( 'Podcast Player Options', 'podcast-player' ),
			array( $this, 'display_section' ),
			'pp-options'
		);

		add_settings_field(
			'pp-legacy-player',
			esc_html__( 'Switch back to legacy player', 'podcast-player' ),
			array( $this, 'display_setting' ),
			'pp-options',
			'podcast-player-general-section',
			array( 'id' => 'pp-legacy-player' ) // Extra arguments used when outputting the field.
		);
	}

	/**
	 * Function to validate plugin options.
	 *
	 * @since    1.0.0
	 *
	 * @param bool $input Checkbox option.
	 */
	public function validate_settings( $input ) {
		return $input ? 'on' : '';
	}

	/**
	 * Function to add extra text to display on manta section.
	 *
	 * @since    1.0.0
	 */
	public function display_section() {
		printf(
			'<p>%1$s</p><p>%2$s<a href="mailto:contact@vedathemes.com">contact@vedathemes.com</a></p>',
			esc_html__( 'If you are not happy with latest design of the player, you can switch back to the older design. Just check the box below and click save button.', 'podcast-player' ),
			esc_html__( 'Also, kindly help us to improve the latest design. Give your improvement suggestions at ', 'podcast-player' )
		);
	}

	/**
	 * Function to display the settings on the page.
	 *
	 * @since    1.0.0
	 */
	public function display_setting() {
		$option = get_option( 'pp-legacy-player' );

		$checked = '';
		if ( $option && 'on' === $option ) {
			$checked = 'checked="checked"';
		}

		echo '<label class="switch">';
		echo '<input type="checkbox" name="pp-legacy-player" ' . $checked . '/>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '</label>';
	}

	/**
	 * Render Manta Plus settings page.
	 *
	 * @since    1.0.0
	 */
	public function pp_options() {

		printf( '<form action="options.php" method="post">' );
		settings_fields( 'podcast-player-options' );
		do_settings_sections( 'pp-options' );
		submit_button( esc_html__( 'Save', 'podcast-player' ) );
		echo '</form>';
	}

	/**
	 * Returns the instance of this class.
	 *
	 * @since  1.0.0
	 *
	 * @return object Instance of this class.
	 */
	public static function get_instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
}

Options::init();
