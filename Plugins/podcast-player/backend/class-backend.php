<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.vedathemes.com
 * @since      1.0.0
 *
 * @package    Podcast_Player
 * @subpackage Podcast_Player/admin
 */

namespace Podcast_Player;

/**
 * The admin-specific functionality of the plugin.
 *
 * Register custom widget and custom shortcode functionality. Enqueue admin area
 * scripts and styles.
 *
 * @package    Podcast_Player
 * @subpackage Podcast_Player/admin
 * @author     vedathemes <contact@vedathemes.com>
 */
class Backend {

	/**
	 * Holds the instance of this class.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    object
	 */
	protected static $instance = null;

	/**
	 * Holds all display styles.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var array
	 */
	protected $styles = [];

	/**
	 * Holds all display styles supported items.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var array
	 */
	protected $style_supported = [];

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
		add_action( 'admin_enqueue_scripts', [ self::get_instance(), 'enqueue_styles' ] );
		add_action( 'admin_enqueue_scripts', [ self::get_instance(), 'enqueue_scripts' ] );
		add_action( 'admin_notices', [ self::get_instance(), 'admin_notices' ] );

		/*
		 * This script must be loaded before mediaelement-migrate.js to work. admin_enqueue_scripts
		 * hook is very late for that. As migrate script added by script handle 'wp-edit-post' at
		 * very top of 'edit-form-blocks.php'.
		 */
		add_action( 'admin_init', [ self::get_instance(), 'mediaelement_migrate_error_fix' ] );

		// No block if legacy player is activated.
		if ( 'on' !== get_option( 'pp-legacy-player' ) ) {
			add_action( 'enqueue_block_editor_assets', [ self::get_instance(), 'enqueue_editor_scripts' ] );
			add_action( 'admin_footer', [ self::get_instance(), 'svg_icons' ], 9999 );
		}

		// Create plugin options page in the dashboard.
		require_once PODCAST_PLAYER_DIR . '/backend/inc/class-options.php';

		// Podcast player backend functionality.
		require_once PODCAST_PLAYER_DIR . '/backend/inc/class-player.php';
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( 'wp-color-picker' );

		/**
		 * Enqueue admin stylesheet.
		 */
		wp_enqueue_style(
			'ppadmin',
			plugin_dir_url( __FILE__ ) . 'css/podcast-player-admin.css',
			[],
			PODCAST_PLAYER_VERSION,
			'all'
		);
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_media();

		wp_enqueue_script(
			'ppadmin',
			plugin_dir_url( __FILE__ ) . 'js/admin.build.js',
			[ 'jquery', 'wp-color-picker' ],
			PODCAST_PLAYER_VERSION,
			true
		);

		// Theme localize scripts data.
		wp_localize_script(
			'ppadmin',
			'podcastplayerImageUploadText',
			[
				'uploader_title'       => esc_html__( 'Set Image', 'podcast-player' ),
				'uploader_button_text' => esc_html__( 'Select', 'podcast-player' ),
				'set_featured_img'     => esc_html__( 'Set Image', 'podcast-player' ),
			]
		);
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_editor_scripts() {
		$menus    = wp_get_nav_menus();
		$menus    = wp_list_pluck( $menus, 'name', 'term_id' );
		$menu_arr = [];
		foreach ( $menus as $key => $val ) {
			$menu_arr[] = [
				'value' => $key,
				'label' => $val,
			];
		}

		$style_arr = [];
		$styles    = $this->get_display_styles();
		foreach ( $styles as $key => $val ) {
			$style_arr[] = [
				'value' => $key,
				'label' => $val,
			];
		}

		// Scripts data.
		$cdata          = [];
		$cdata['menu']  = $menu_arr;
		$cdata['style'] = $style_arr;
		$cdata['stSup'] = $this->style_supported;
		$ppjs_settings  = apply_filters(
			'podcast_player_mediaelement_settings',
			[
				'pluginPath'  => includes_url( 'js/mediaelement/', 'relative' ),
				'classPrefix' => 'ppjs__',
				'stretching'  => 'responsive',
				'features'    => [ 'current', 'progress', 'duration', 'fullscreen' ],
			]
		);

		wp_enqueue_script(
			'podcast-player-block-js',
			plugins_url( '/js/blocks.build.js', __FILE__ ),
			array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-components', 'wp-editor', 'wp-api-fetch' ),
			PODCAST_PLAYER_VERSION,
			true
		);

		wp_enqueue_style(
			'podcast-player-block-css',
			plugins_url( '/frontend/css/podcast-player-editor.css', dirname( __FILE__ ) ),
			array(),
			PODCAST_PLAYER_VERSION
		);

		wp_enqueue_script(
			'ppeditor',
			plugins_url( '/frontend/js/ppeditor.build.js', dirname( __FILE__ ) ),
			[ 'jquery', 'mediaelement-core' ],
			PODCAST_PLAYER_VERSION,
			true
		);

		wp_localize_script( 'ppeditor', 'podcastPlayerData', $cdata );
		wp_localize_script( 'ppeditor', 'ppmejsSettings', $ppjs_settings );
	}

	/**
	 * Register the script to fix mediaelement migrate error.
	 *
	 * Mediaelement migrate WP script forces to use 'mejs-' class prefix for all
	 * mediaelements. Podcast player only work with 'ppjs__' class prefix. Hence,
	 * fixing this issue.
	 *
	 * @since    1.0.0
	 */
	public function mediaelement_migrate_error_fix() {
		/*
		 * This file must be loaded before mediaelement-migrate script.
		 * Mediaelement-migrate script loads in header in various admin windows.
		 * Therefore, loading in header.
		 */
		$in_footer = false;

		/**
		 * Register public facing stylesheets.
		 */
		wp_enqueue_script(
			'podcast-player-mmerrorfix',
			plugins_url( '/frontend/js/mmerrorfix.js', dirname( __FILE__ ) ),
			[ 'jquery', 'mediaelement-core' ],
			PODCAST_PLAYER_VERSION,
			$in_footer
		);
	}

	/**
	 * Add SVG definitions to the site footer.
	 *
	 * @since 1.0.0
	 */
	public function svg_icons() {

		/**
		 * This files defines all svg icons used by the plugin.
		 */
		require_once PODCAST_PLAYER_DIR . 'frontend/images/icons.svg';
	}

	/**
	 * Display message on plugin activation.
	 *
	 * @since    1.0.0
	 */
	public function admin_notices() {
		/*
		 * Add && 'on' !== get_option( 'pp-legacy-player' )
		 */
		if ( PODCAST_PLAYER_VERSION !== get_option( 'podcast-player-admin-notice' ) ) {
			printf(
				'<div class="updated notice is-dismissible pp-welcome-notice">
					<p>%s</p><p>%s</p><p class="pp-link"><a href="%s">%s</a></p><p class="pp-link"><a href="%s">%s</a></p>
				</div>',
				esc_html__( 'Thanks for trying/updating Podcast Player. The latest update contains critical bug fixes.', 'podcast-player' ),
				esc_html__( 'Note: Some major changes have been made in last update. Although, we have tested it extensively, still if you find some error, kindly inform us on contact@vedathemes.com OR raise a support ticket. We will try to fix it as soon as possible. Thanks', 'podcast-player' ),
				esc_url( 'https://wordpress.org/support/plugin/podcast-player/' ),
				esc_html__( 'Raise a support request', 'podcast-player' ),
				esc_url( 'https://wordpress.org/support/plugin/podcast-player/reviews/?filter=5' ),
				esc_html__( 'Give us 5 stars rating', 'podcast-player' )
			);

			?>
			<style type="text/css" media="screen">

				.pp-link {
					display: inline-block;
				}

				.pp-link + .pp-link {
					margin-left: 10px;
					padding: 0 0 0 10px;
					border-left: 1px solid #999;
				}

			</style>

			<?php

			/* Delete transient, only display this notice once. */
			update_option( 'podcast-player-admin-notice', PODCAST_PLAYER_VERSION );
		}
	}

	/**
	 * Get display styles.
	 *
	 * @return array
	 */
	public function get_display_styles() {
		if ( ! empty( $this->styles ) ) {
			return $this->styles;
		}

		$styles = apply_filters(
			'podcast_player_display_styles',
			[
				''       => [
					'label'   => esc_html__( 'Default Player', 'podcast-player' ),
					'support' => [],
				],
				'legacy' => [
					'label'   => esc_html__( 'Legacy Player', 'podcast-player' ),
					'support' => [],
				],
			]
		);
		foreach ( $styles as $style => $args ) {
			$this->styles[ $style ]          = $args['label'];
			$this->style_supported[ $style ] = $args['support'];
		}

		return $this->styles;
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

Backend::init();
