<?php
/**
 * Widget API: Display Podcast from feed url class
 *
 * @package podcast-player
 * @since 1.0.0
 */

namespace Podcast_Player;

/**
 * Class used to display podcast episodes from a feed url.
 *
 * @since 1.0.0
 *
 * @see WP_Widget
 */
class Widget extends \WP_Widget {

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
	 * Holds widget settings defaults, populated in constructor.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var array
	 */
	protected $defaults = [];

	/**
	 * Are we using modern player.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    bool
	 */
	protected $is_modern = true;

	/**
	 * Is this the premium version.
	 *
	 * @since  1.2.0
	 * @access protected
	 * @var    bool
	 */
	protected $is_premium = true;

	/**
	 * Sets up a new Blank widget instance.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// Set widget instance settings default values.
		$this->defaults = [
			'title'               => '',
			'pp_skin'             => 'light',
			'sortby'              => 'sort_date_desc',
			'filterby'            => '',
			'feed_url'            => '',
			'number'              => 10,
			'podcast_menu'        => '',
			'cover_image'         => '',
			'desc'                => '',
			'error'               => '',
			'pp_excerpt_length'   => 25,
			'pp_no_excerpt'       => '',
			'pp_grid_columns'     => 3,
			'pp_header_default'   => '',
			'pp_hide_header'      => '',
			'pp_hide_cover'       => '',
			'pp_hide_title'       => '',
			'pp_hide_description' => '',
			'pp_hide_subscribe'   => '',
			'pp_hide_search'      => '',
			'pp_hide_author'      => '',
			'pp_hide_content'     => '',
			'pp_hide_loadmore'    => '',
			'pp_hide_download'    => '',
			'pp_hide_social'      => '',
			'pp_accent_color'     => '',
			'pp_display_style'    => '',
			'pp_aspect_ratio'     => 'squr',
			'pp_crop_method'      => 'centercrop',
			'pp_fetch_method'     => 'feed',
			'pp_post_type'        => 'post',
			'pp_taxonomy'         => '',
			'pp_terms'            => '',
			'pp_podtitle'         => '',
			'pp_audiosrc'         => '',
			'pp_audiotitle'       => '',
			'pp_audiolink'        => '',
			'pp_ahide_download'   => '',
			'pp_ahide_social'     => '',
		];

		$legacy = get_option( 'pp-legacy-player' );
		if ( 'on' === $legacy ) {
			$this->is_modern = false;
		}

		$this->is_premium = apply_filters( 'podcast_player_is_premium', false );

		// Set the widget options.
		$widget_ops = [
			'classname'                   => 'podcast_player',
			'description'                 => esc_html__( 'Create a podcast player widget.', 'podcast-player' ),
			'customize_selective_refresh' => true,
		];
		parent::__construct( 'podcast_player_widget', esc_html__( 'Podcast Player', 'podcast-player' ), $widget_ops );
	}

	/**
	 * Outputs the content for the current widget instance.
	 *
	 * @since 2.8.0
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current widget instance.
	 */
	public function widget( $args, $instance ) {

		$args['widget_id'] = isset( $args['widget_id'] ) ? $args['widget_id'] : $this->id;

		// Merge with defaults.
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		$url = '';
		if ( 'feed' === $instance['pp_fetch_method'] ) {
			// Return if there is no feed url.
			$url = ! empty( $instance['feed_url'] ) ? $instance['feed_url'] : '';

			while ( stristr( $url, 'http' ) !== $url ) {
				$url = substr( $url, 1 );
			}

			if ( empty( $url ) ) {
				return;
			}
		}

		$title = ! empty( $instance['title'] ) ? $instance['title'] : '';

		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		podcast_player_display(
			[
				'url'              => $url,
				'skin'             => $instance['pp_skin'],
				'sortby'           => $instance['sortby'],
				'filterby'         => $instance['filterby'],
				'number'           => $instance['number'],
				'menu'             => $instance['podcast_menu'],
				'image'            => $instance['cover_image'],
				'description'      => $instance['desc'],
				'excerpt-length'   => $instance['pp_excerpt_length'],
				'no-excerpt'       => $instance['pp_no_excerpt'],
				'header-default'   => $instance['pp_header_default'],
				'hide-header'      => $instance['pp_hide_header'],
				'hide-title'       => $instance['pp_hide_title'],
				'hide-cover-img'   => $instance['pp_hide_cover'],
				'hide-description' => $instance['pp_hide_description'],
				'hide-subscribe'   => $instance['pp_hide_subscribe'],
				'hide-search'      => $instance['pp_hide_search'],
				'hide-author'      => $instance['pp_hide_author'],
				'hide-content'     => $instance['pp_hide_content'],
				'hide-loadmore'    => $instance['pp_hide_loadmore'],
				'hide-download'    => $instance['pp_hide_download'],
				'hide-social'      => $instance['pp_hide_social'],
				'accent-color'     => $instance['pp_accent_color'],
				'display-style'    => $instance['pp_display_style'],
				'aspect-ratio'     => $instance['pp_aspect_ratio'],
				'crop-method'      => $instance['pp_crop_method'],
				'grid-columns'     => $instance['pp_grid_columns'],
				'fetch-method'     => $instance['pp_fetch_method'],
				'post-type'        => $instance['pp_post_type'],
				'taxonomy'         => $instance['pp_taxonomy'],
				'terms'            => $instance['pp_terms'],
				'podtitle'         => $instance['pp_podtitle'],
				'audiosrc'         => $instance['pp_audiosrc'],
				'audiotitle'       => $instance['pp_audiotitle'],
				'audiolink'        => $instance['pp_audiolink'],
				'ahide-download'   => $instance['pp_ahide_download'],
				'ahide-social'     => $instance['pp_ahide_social'],
				'from'             => 'widget',
			],
			false
		);

		echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Outputs the settings form for the widget.
	 *
	 * @since 1.0.0
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {
		// Merge with defaults.
		$instance = wp_parse_args( (array) $instance, $this->defaults );
		$menus    = wp_get_nav_menus();
		$menu_arr = wp_list_pluck( $menus, 'name', 'term_id' );
		$menu_arr = [ '' => esc_html__( 'None', 'podcast-player' ) ] + $menu_arr;

		$skin_arr = [
			'light' => esc_html__( 'Light', 'podcast-player' ),
			'dark'  => esc_html__( 'Dark', 'podcast-player' ),
		];

		$fetch_method = [
			'feed' => esc_html__( 'From FeedURL', 'podcast-player' ),
			'post' => esc_html__( 'From Posts', 'podcast-player' ),
			'link' => esc_html__( 'From Audio/Video Link', 'podcast-player' )
		];

		$sort_arr = [
			'sort_title_desc' => esc_html__( 'Title Descending', 'podcast-player' ),
			'sort_title_asc'  => esc_html__( 'Title Ascending', 'podcast-player' ),
			'sort_date_desc'  => esc_html__( 'Date Descending', 'podcast-player' ),
			'sort_date_asc'   => esc_html__( 'Date Ascending', 'podcast-player' ),
		];

		$imagecrop = [
			'topleftcrop'      => esc_html__( 'Top Left Cropping', 'podcast-player' ),
			'topcentercrop'    => esc_html__( 'Top Center Cropping', 'podcast-player' ),
			'centercrop'       => esc_html__( 'Center Cropping', 'podcast-player' ),
			'bottomleftcrop'   => esc_html__( 'Bottom Left Cropping', 'podcast-player' ),
			'bottomcentercrop' => esc_html__( 'Bottom Center Cropping', 'podcast-player' ),
		];

		$aspectratio = [
			''       => esc_html__( 'No Cropping', 'podcast-player' ),
			'land1'  => esc_html__( 'Landscape (4:3)', 'podcast-player' ),
			'land2'  => esc_html__( 'Landscape (3:2)', 'podcast-player' ),
			'port1'  => esc_html__( 'Portrait (3:4)', 'podcast-player' ),
			'port2'  => esc_html__( 'Portrait (2:3)', 'podcast-player' ),
			'wdscrn' => esc_html__( 'Widescreen (16:9)', 'podcast-player' ),
			'squr'   => esc_html__( 'Square (1:1)', 'podcast-player' ),
		];

		$display_style = $this->get_display_styles();

		?>

		<p>
			<?php $this->label( 'title', esc_html__( 'Title', 'podcast-player' ) ); ?>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>

		<?php if ( $this->is_premium ) : ?>
		<p>
			<?php
			$this->label( 'pp_fetch_method', esc_html__( 'Podcast Episodes', 'podcast-player' ) );
			$this->select( 'pp_fetch_method', $fetch_method, $instance['pp_fetch_method'] );
			?>
		</p>
		<?php endif; ?>

		<p class="feed-url" <?php echo ( $this->is_premium && 'feed' !== $instance['pp_fetch_method'] ) ? ' style="display:none;"' : ''; ?>>
			<?php $this->label( 'feed_url', esc_html__( 'Podcast Feed URL', 'podcast-player' ) ); ?>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'feed_url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'feed_url' ) ); ?>" type="text" value="<?php echo esc_url( $instance['feed_url'] ); ?>" />
		</p>

		<?php if ( $this->is_premium ) : ?>
		<div class="post-type-fetch" <?php echo ( 'post' !== $instance['pp_fetch_method'] ) ? ' style="display:none;"' : ''; ?>>
			<p>
				<?php
				$post_type = pp_get_post_types();
				$this->label( 'pp_post_type', esc_html__( 'Select Post Type', 'podcast-player' ) );
				$this->select( 'pp_post_type', $post_type, $instance['pp_post_type'] );
				?>
			</p>

			<div class="pp-taxonomies">
				<?php $this->taxonomies_select( $instance['pp_post_type'], $instance['pp_taxonomy'] ); ?>
			</div><!-- .taxonomies -->

			<div class="pp-terms-panel" <?php echo '' === $instance['pp_taxonomy'] ? ' style="display:none;"' : ''; ?>>
				<?php $this->terms_checklist( $instance['pp_taxonomy'], $instance['pp_terms'] ); ?>
			</div><!-- .terms-panel -->
		</div>
		<?php endif; ?>

		<?php if ( $this->is_premium ) : ?>
		<div class="single-audio-fetch" <?php echo ( 'link' !== $instance['pp_fetch_method'] ) ? ' style="display:none;"' : ''; ?>>
			<p class="audio-src">
				<?php $this->label( 'pp_audiosrc', esc_html__( 'Valid Audio/Video File Link (i.e, mp3, ogg, m4a etc.)', 'podcast-player' ) ); ?>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'pp_audiosrc' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'pp_audiosrc' ) ); ?>" type="text" value="<?php echo esc_url( $instance['pp_audiosrc'] ); ?>" />
			</p>
			<p class="audio-title">
				<?php $this->label( 'pp_audiotitle', esc_html__( 'Episode Title', 'podcast-player' ) ); ?>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'pp_audiotitle' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'pp_audiotitle' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['pp_audiotitle'] ); ?>" />
			</p>
			<p class="audio-link">
				<?php $this->label( 'pp_audiolink', esc_html__( 'Podcast episode link for social sharing (optional)', 'podcast-player' ) ); ?>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'pp_audiolink' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'pp_audiolink' ) ); ?>" type="text" value="<?php echo esc_url( $instance['pp_audiolink'] ); ?>" />
			</p>
			<p class="ahide-download">
				<input id="<?php echo esc_attr( $this->get_field_id( 'pp_ahide_download' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'pp_ahide_download' ) ); ?>" type="checkbox" value="yes" <?php checked( $instance['pp_ahide_download'], 'yes' ); ?> />
				<?php $this->label( 'pp_ahide_download', esc_html__( 'Hide Episode Download Link', 'podcast-player' ) ); ?>
			</p>
			<p class="ahide-social">
				<input id="<?php echo esc_attr( $this->get_field_id( 'pp_ahide_social' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'pp_ahide_social' ) ); ?>" type="checkbox" value="yes" <?php checked( $instance['pp_ahide_social'], 'yes' ); ?> />
				<?php $this->label( 'pp_ahide_social', esc_html__( 'Hide Social Share Links', 'podcast-player' ) ); ?>
			</p>
		</div>
		<?php endif; ?>

		<?php if ( ! $this->is_modern ) : ?>
		<p>
			<?php
			$this->label( 'pp_skin', esc_html__( 'Player skin', 'podcast-player' ) );
			$this->select( 'pp_skin', $skin_arr, $instance['pp_skin'] );
			?>
		</p>
		<?php endif; ?>

		<div class="pp-options-wrapper" <?php echo ( $this->is_premium && 'link' === $instance['pp_fetch_method'] ) ? ' style="display:none;"' : ''; ?>>
		<a class="podcast-addinfo-toggle pp-settings-toggle"><span class="is-premium-post" <?php echo ( 'feed' === $instance['pp_fetch_method'] ) ? ' style="display:none;"' : ''; ?>><?php esc_html_e( 'Create Podcast Header Content', 'podcast-player' ); ?></span><span class="is-feed" <?php echo ( 'feed' !== $instance['pp_fetch_method'] ) ? ' style="display:none;"' : ''; ?>><?php esc_html_e( 'Change Podcast Content', 'podcast-player' ); ?></span></a>

		<div class="podcast-additional-info pp-settings-content">

			<?php if ( $this->is_premium ) : ?>
			<p class="podcast-title" <?php echo ( 'feed' === $instance['pp_fetch_method'] ) ? ' style="display:none;"' : ''; ?>>
				<?php $this->label( 'pp_podtitle', esc_html__( 'Podcast Title', 'podcast-player' ) ); ?>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'pp_podtitle' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'pp_podtitle' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['pp_podtitle'] ); ?>" />
			</p>
			<?php endif; ?>

			<p>
				<?php
				$this->label( 'cover_image', esc_html__( 'Podcast Cover Image', 'podcast-player' ) );
				$id   = esc_attr( $this->get_field_id( 'cover_image' ) );
				$name = esc_attr( $this->get_field_name( 'cover_image' ) );
				echo $this->image_upload( $id, $name, $instance['cover_image'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				?>
			</p>

			<p>
				<?php $this->label( 'desc', esc_html__( 'Brief Description', 'podcast-player' ) ); ?>
				<textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'desc' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'desc' ) ); ?>" cols="50" rows="3"><?php echo esc_html( $instance['desc'] ); ?></textarea>
			</p>

			<?php if ( ! $this->is_modern ) : ?>
			<p class="description-excerpt">
				<input id="<?php echo esc_attr( $this->get_field_id( 'pp_no_excerpt' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'pp_no_excerpt' ) ); ?>" type="checkbox" value="yes" <?php checked( $instance['pp_no_excerpt'], 'yes' ); ?> />
				<?php $this->label( 'pp_no_excerpt', esc_html__( 'Show full description in place of excerpt.', 'podcast-player' ) ); ?>
			</p>
			<?php endif; ?>

			<p>
				<?php
				$this->label( 'podcast_menu', esc_html__( 'Podcast Custom Menu', 'podcast-player' ) );
				$this->select( 'podcast_menu', $menu_arr, $instance['podcast_menu'] );
				?>
			</p>

			<p class="number-of-posts">
				<?php $this->label( 'number', esc_html__( 'Number of episodes to show at a time.', 'podcast-player' ) ); ?>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="number" step="1" min="1" value="<?php echo absint( $instance['number'] ); ?>" size="3" />
			</p>

			<?php if ( $this->is_modern ) : ?>
			<p class="excerpt-length" <?php echo $this->is_style_support( $instance['pp_display_style'], 'excerpt' ) ? '' : ' style="display:none;"'; ?>>
				<?php $this->label( 'pp_excerpt_length', esc_html__( 'Excerpt Length', 'podcast-player' ) ); ?>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'pp_excerpt_length' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'pp_excerpt_length' ) ); ?>" type="number" step="1" min="0" max="200" value="<?php echo absint( $instance['pp_excerpt_length'] ); ?>" size="3" />
			</p>
			<?php endif; ?>
		</div>

		<a class="podcast-hide-items pp-settings-toggle"><?php esc_html_e( 'Show/Hide Player Items', 'podcast-player' ); ?></a>

		<div class="podcast-hide-items pp-settings-content">
			<?php if ( $this->is_modern ) : ?>
			<p class="header-default" <?php echo $instance['pp_display_style'] ? 'style="display: none"' : ''; ?>>
				<input id="<?php echo esc_attr( $this->get_field_id( 'pp_header_default' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'pp_header_default' ) ); ?>" type="checkbox" value="yes" <?php checked( $instance['pp_header_default'], 'yes' ); ?> />
				<?php $this->label( 'pp_header_default', esc_html__( 'Show Podcast Header by Default', 'podcast-player' ) ); ?>
			</p>
			<p class="hide_header">
				<input id="<?php echo esc_attr( $this->get_field_id( 'pp_hide_header' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'pp_hide_header' ) ); ?>" type="checkbox" value="yes" <?php checked( $instance['pp_hide_header'], 'yes' ); ?> />
				<?php $this->label( 'pp_hide_header', esc_html__( 'Hide Podcast Header Information', 'podcast-player' ) ); ?>
			</p>
			<?php endif; ?>

			<p class="hide-cover-img" <?php echo $instance['pp_hide_header'] ? ' style="display:none;"' : ''; ?>>
				<input id="<?php echo esc_attr( $this->get_field_id( 'pp_hide_cover' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'pp_hide_cover' ) ); ?>" type="checkbox" value="yes" <?php checked( $instance['pp_hide_cover'], 'yes' ); ?> />
				<?php $this->label( 'pp_hide_cover', esc_html__( 'Hide cover image', 'podcast-player' ) ); ?>
			</p>

			<?php if ( $this->is_modern ) : ?>
			<p class="hide-title" <?php echo $instance['pp_hide_header'] ? ' style="display:none;"' : ''; ?>>
				<input id="<?php echo esc_attr( $this->get_field_id( 'pp_hide_title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'pp_hide_title' ) ); ?>" type="checkbox" value="yes" <?php checked( $instance['pp_hide_title'], 'yes' ); ?> />
				<?php $this->label( 'pp_hide_title', esc_html__( 'Hide Podcast Title', 'podcast-player' ) ); ?>
			</p>
			<?php endif; ?>

			<p class="hide-description" <?php echo $instance['pp_hide_header'] ? ' style="display:none;"' : ''; ?>>
				<input id="<?php echo esc_attr( $this->get_field_id( 'pp_hide_description' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'pp_hide_description' ) ); ?>" type="checkbox" value="yes" <?php checked( $instance['pp_hide_description'], 'yes' ); ?> />
				<?php $this->label( 'pp_hide_description', esc_html__( 'Hide Podcast Description', 'podcast-player' ) ); ?>
			</p>

			<p class="hide-subscribe" <?php echo $instance['pp_hide_header'] ? ' style="display:none;"' : ''; ?>>
				<input id="<?php echo esc_attr( $this->get_field_id( 'pp_hide_subscribe' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'pp_hide_subscribe' ) ); ?>" type="checkbox" value="yes" <?php checked( $instance['pp_hide_subscribe'], 'yes' ); ?> />
				<?php $this->label( 'pp_hide_subscribe', esc_html__( 'Hide Custom menu', 'podcast-player' ) ); ?>
			</p>

			<p class="hide-search">
				<input id="<?php echo esc_attr( $this->get_field_id( 'pp_hide_search' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'pp_hide_search' ) ); ?>" type="checkbox" value="yes" <?php checked( $instance['pp_hide_search'], 'yes' ); ?> />
				<?php $this->label( 'pp_hide_search', esc_html__( 'Hide Podcast Search', 'podcast-player' ) ); ?>
			</p>

			<?php if ( $this->is_modern ) : ?>
			<p class="hide-author">
				<input id="<?php echo esc_attr( $this->get_field_id( 'pp_hide_author' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'pp_hide_author' ) ); ?>" type="checkbox" value="yes" <?php checked( $instance['pp_hide_author'], 'yes' ); ?> />
				<?php $this->label( 'pp_hide_author', esc_html__( 'Hide Episode Author/Podcaster Name', 'podcast-player' ) ); ?>
			</p>
			<?php endif; ?>

			<p class="hide-content" <?php echo ( $this->is_premium && 'feed' !== $instance['pp_fetch_method'] ) ? ' style="display:none;"' : ''; ?>>
				<input id="<?php echo esc_attr( $this->get_field_id( 'pp_hide_content' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'pp_hide_content' ) ); ?>" type="checkbox" value="yes" <?php checked( $instance['pp_hide_content'], 'yes' ); ?> />
				<?php $this->label( 'pp_hide_content', esc_html__( 'Hide Episode Text Content/Transcript', 'podcast-player' ) ); ?>
			</p>

			<p class="hide-loadmore">
				<input id="<?php echo esc_attr( $this->get_field_id( 'pp_hide_loadmore' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'pp_hide_loadmore' ) ); ?>" type="checkbox" value="yes" <?php checked( $instance['pp_hide_loadmore'], 'yes' ); ?> />
				<?php $this->label( 'pp_hide_loadmore', esc_html__( 'Hide Load More Episodes Button', 'podcast-player' ) ); ?>
			</p>

			<p class="hide-download">
				<input id="<?php echo esc_attr( $this->get_field_id( 'pp_hide_download' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'pp_hide_download' ) ); ?>" type="checkbox" value="yes" <?php checked( $instance['pp_hide_download'], 'yes' ); ?> />
				<?php $this->label( 'pp_hide_download', esc_html__( 'Hide Episode Download Link', 'podcast-player' ) ); ?>
			</p>

			<p class="hide-social">
				<input id="<?php echo esc_attr( $this->get_field_id( 'pp_hide_social' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'pp_hide_social' ) ); ?>" type="checkbox" value="yes" <?php checked( $instance['pp_hide_social'], 'yes' ); ?> />
				<?php $this->label( 'pp_hide_social', esc_html__( 'Hide Social Share Links', 'podcast-player' ) ); ?>
			</p>
		</div>

		<?php if ( $this->is_modern ) : ?>
		<a class="podcast-styling pp-settings-toggle"><?php esc_html_e( 'Podcast Player Styling', 'podcast-player' ); ?></a>

		<div class="podcast-styling pp-settings-content">
			<p class="pod-accent-color">
				<?php $this->label( 'pp-accent-color', esc_html__( 'Accent Color', 'podcast-player' ) ); ?>
				<input class="pp-accent-color" id="<?php echo esc_attr( $this->get_field_id( 'pp_accent_color' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'pp_accent_color' ) ); ?>" type="text" value="<?php echo sanitize_hex_color( $instance['pp_accent_color'] ); ?>"/>
			</p>
			<p>
				<?php
				$this->label( 'pp_display_style', esc_html__( 'Podcast Player Display Style', 'podcast-player' ) );
				$this->select( 'pp_display_style', $display_style, $instance['pp_display_style'] );
				?>
			</p>
			<p class="aspect-ratio" <?php echo $this->is_style_support( $instance['pp_display_style'], 'thumbnail' ) ? '' : ' style="display:none;"'; ?>>
				<?php
				$this->label( 'pp_aspect_ratio', esc_html__( 'Thumbnail Cropping', 'podcast-player' ) );
				$this->select( 'pp_aspect_ratio', $aspectratio, $instance['pp_aspect_ratio'] );
				?>
			</p>
			<p class="crop-method" <?php echo ( $this->is_style_support( $instance['pp_display_style'], 'thumbnail' ) && '' !== $instance['pp_aspect_ratio'] ) ? '' : ' style="display:none;"'; ?>>
				<?php
				$this->label( 'pp_crop_method', esc_html__( 'Thumbnail Cropping Position', 'podcast-player' ) );
				$this->select( 'pp_crop_method', $imagecrop, $instance['pp_crop_method'] );
				?>
			</p>
			<p class="grid-columns" <?php echo $this->is_style_support( $instance['pp_display_style'], 'grid' ) ? '' : ' style="display:none;"'; ?>>
				<?php $this->label( 'pp_grid_columns', esc_html__( 'Maximum Grid Columns', 'podcast-player' ) ); ?>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'pp_grid_columns' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'pp_grid_columns' ) ); ?>" type="number" step="1" min="2" max="6" value="<?php echo absint( $instance['pp_grid_columns'] ); ?>" size="1" />
			</p>
		</div>

		<a class="podcast-advanced pp-settings-toggle"><?php esc_html_e( 'Sort & Filter Options', 'podcast-player' ); ?></a>

		<div class="podcast-advanced pp-settings-content">
			<p>
				<?php
				$this->label( 'sortby', esc_html__( 'Sort Podcast Episodes By', 'podcast-player' ) );
				$this->select( 'sortby', $sort_arr, $instance['sortby'] );
				?>
			</p>
			<p class="filter-term">
				<?php $this->label( 'filterby', esc_html__( 'Show episodes only if title contains following', 'podcast-player' ) ); ?>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'filterby' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'filterby' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['filterby'] ); ?>" />
			</p>
		</div>
		<?php endif; ?>
		</div><!-- .pp-options-wrapper -->

		<?php
		if ( 0 < strlen( $instance['error'] ) ) :
			?>
			<div style="color: red; font-weight: bold;"><?php echo esc_html( $instance['error'] ); ?></div>
			<?php
		endif;
	}

	/**
	 * Handles updating the settings for the current widget instance.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Updated settings to save.
	 */
	public function update( $new_instance, $old_instance ) {

		// Merge with defaults.
		$new_instance = wp_parse_args( (array) $new_instance, $this->defaults );

		$instance                = $old_instance;
		$img_url                 = $img_id ? wp_get_attachment_image_src( $img_id ) : false;
		$instance['cover_image'] = $img_url ? $img_id : '';

		$sanitize_int = [
			'number',
			'podcast_menu',
			'cover_image',
			'pp_excerpt_length',
			'pp_grid_columns',
		];
		foreach ( $sanitize_int as $setting ) {
			$instance[ $setting ] = absint( $new_instance[ $setting ] );
		}

		$sanitize_text = [
			'title',
			'pp_skin',
			'desc',
			'sortby',
			'filterby',
			'pp_display_style',
			'pp_aspect_ratio',
			'pp_crop_method',
			'pp_fetch_method',
			'pp_post_type',
			'pp_podtitle',
			'pp_audiotitle',
		];
		foreach ( $sanitize_text as $setting ) {
			$instance[ $setting ] = sanitize_text_field( $new_instance[ $setting ] );
		}

		$sanitize_url = [
			'pp_audiosrc',
			'pp_audiolink',
		];
		foreach ( $sanitize_url as $url ) {
			$instance[ $url ] = esc_url_raw( $new_instance[ $url ] );
		}

		$sanitize_bool = [
			'pp_no_excerpt',
			'pp_hide_title',
			'pp_hide_cover',
			'pp_hide_description',
			'pp_header_default',
			'pp_hide_header',
			'pp_hide_subscribe',
			'pp_hide_search',
			'pp_hide_author',
			'pp_hide_content',
			'pp_hide_loadmore',
			'pp_hide_download',
			'pp_hide_social',
			'pp_ahide_download',
			'pp_ahide_social',
		];
		foreach ( $sanitize_bool as $setting ) {
			$instance[ $setting ] = ( 'yes' === $new_instance[ $setting ] ) ? 'yes' : '';
		}

		$instance['pp_accent_color'] = sanitize_hex_color( $new_instance['pp_accent_color'] );

		if ( $this->is_premium && 'post' !== $instance['pp_fetch_method'] ) {
			$instance['pp_taxonomy'] = '';
			$instance['pp_terms']    = [];
		} else {
			if ( $instance['pp_post_type'] && $new_instance['pp_taxonomy'] ) {
				$instance['pp_taxonomy'] = sanitize_text_field( $new_instance['pp_taxonomy'] );
			} else {
				$instance['pp_taxonomy'] = '';
			}

			if ( $instance['pp_taxonomy'] && $new_instance['pp_terms'] ) {
				$instance['pp_terms'] = array_map( 'sanitize_text_field', $new_instance['pp_terms'] );
			} else {
				$instance['pp_terms'] = [];
			}
		}

		if ( $this->is_premium && 'feed' !== $instance['pp_fetch_method'] ) {
			$instance['feed_url'] = '';
			$instance['error']    = '';
		} else {
			$error   = '';
			$feedurl = '';
			if ( isset( $old_instance['feed_url'] ) && ( $new_instance['feed_url'] === $old_instance['feed_url'] ) ) {
				$feedurl = $old_instance['feed_url'];
			} elseif ( $new_instance['feed_url'] ) {
				$feedurl = esc_url_raw( wp_strip_all_tags( $new_instance['feed_url'] ) );

				// Retrieve feed items for url validation.
				$rss = fetch_feed( $feedurl );
				if ( is_wp_error( $rss ) ) {
					$error .= ' ' . $rss->get_error_message();
				} else {
					$rss->__destruct();
				}
				unset( $rss );
			}

			$instance['feed_url'] = $feedurl;
			$instance['error']    = sanitize_text_field( $error );
		}

		return $instance;
	}

	/**
	 * Prints select list of all taxonomies for a post type.
	 *
	 * @param str   $post_type Selected post type.
	 * @param array $selected  Selected taxonomy in widget form.
	 * @return void
	 */
	public function taxonomies_select( $post_type, $selected = [] ) {

		$options = pp_get_taxonomies();

		// Get HTML classes for select options.
		$taxonomies = get_taxonomies( [], 'objects' );
		$classes    = wp_list_pluck( $taxonomies, 'object_type', 'name' );
		if ( $post_type && 'page' !== $post_type ) {
			foreach ( $classes as $name => $type ) {
				$type = (array) $type;
				if ( ! in_array( $post_type, $type, true ) ) {
					$type[]           = 'podcast-player-hidden';
					$classes[ $name ] = $type;
				}
			}
		}
		$classes[''] = 'always-visible';

		// Taxonomy Select markup.
		$this->label( 'pp_taxonomy', esc_html__( 'Get Episodes by Taxonomy', 'podcast-player' ) );
		$this->select( 'pp_taxonomy', $options, $selected, $classes );
	}

	/**
	 * Prints a checkbox list of all terms for a taxonomy.
	 *
	 * @param str   $taxonomy       Selected Taxonomy.
	 * @param array $selected_terms Selected Terms.
	 * @return void
	 */
	public function terms_checklist( $taxonomy, $selected_terms = [] ) {

		// Get list of all registered terms.
		$terms = get_terms();

		// Get 'checkbox' options as value => label.
		$options = wp_list_pluck( $terms, 'name', 'slug' );

		// Get HTML classes for checkbox options.
		$classes = wp_list_pluck( $terms, 'taxonomy', 'slug' );
		if ( $taxonomy ) {
			foreach ( $classes as $slug => $taxon ) {
				if ( $taxonomy !== $taxon ) {
					$classes[ $slug ] .= ' podcast-player-hidden';
				}
			}
		}

		// Terms Checkbox markup.
		$this->label( 'pp_terms', esc_html__( 'Select Terms', 'podcast-player' ) );
		$this->mu_checkbox( 'pp_terms', $options, $selected_terms, $classes );
	}

	/**
	 * Markup for 'label' for widget input options.
	 *
	 * @param str  $for  Label for which ID.
	 * @param str  $text Label text.
	 * @param bool $echo Display or Return.
	 * @return void|string
	 */
	public function label( $for, $text, $echo = true ) {
		$label = sprintf( '<label for="%s">%s</label>', esc_attr( $this->get_field_id( $for ) ), esc_html( $text ) );
		if ( $echo ) {
			echo $label; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} else {
			return $label;
		}
	}

	/**
	 * Markup for Select dropdown lists for widget options.
	 *
	 * @param str   $for      Select for which ID.
	 * @param array $options  Select options as 'value => label' pair.
	 * @param str   $selected selected option.
	 * @param array $classes  Options HTML classes.
	 * @param bool  $echo     Display or return.
	 * @return void|string
	 */
	public function select( $for, $options, $selected, $classes = [], $echo = true ) {
		$select      = '';
		$final_class = '';
		foreach ( $options as $value => $label ) {
			if ( isset( $classes[ $value ] ) ) {
				$option_classes = (array) $classes[ $value ];
				$option_classes = array_map( 'esc_attr', $option_classes );
				$final_class    = 'class="' . join( ' ', $option_classes ) . '"';
			}
			$select .= sprintf( '<option value="%1$s" %2$s %3$s>%4$s</option>', esc_attr( $value ), $final_class, selected( $value, $selected, false ), esc_html( $label ) );
		}

		$select = sprintf(
			'<select id="%1$s" name="%2$s" class="podcast-player-%3$s widefat">%4$s</select>',
			esc_attr( $this->get_field_id( $for ) ),
			esc_attr( $this->get_field_name( $for ) ),
			esc_attr( str_replace( '_', '-', $for ) ),
			$select
		);

		if ( $echo ) {
			echo $select; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} else {
			return $select;
		}
	}

	/**
	 * Markup for multiple checkbox for widget options.
	 *
	 * @param str   $for      Select for which ID.
	 * @param array $options  Select options as 'value => label' pair.
	 * @param str   $selected selected option.
	 * @param array $classes  Checkbox input HTML classes.
	 * @param bool  $echo     Display or return.
	 * @return void|string
	 */
	public function mu_checkbox( $for, $options, $selected = [], $classes = [], $echo = true ) {

		$final_class = '';

		$mu_checkbox = '<div class="' . esc_attr( $for ) . '-checklist"><ul id="' . esc_attr( $this->get_field_id( $for ) ) . '">';

		$selected    = array_map( 'strval', $selected );
		$rev_options = $options;

		// Moving selected items on top of the array.
		foreach ( $options as $id => $label ) {
			if ( in_array( strval( $id ), $selected, true ) ) {
				$rev_options = [ $id => $label ] + $rev_options;
			}
		}

		foreach ( $rev_options as $id => $label ) {
			if ( isset( $classes[ $id ] ) ) {
				$final_class = ' class="' . esc_attr( $classes[ $id ] ) . '"';
			}
			$mu_checkbox .= "\n<li$final_class>" . '<label class="selectit"><input value="' . esc_attr( $id ) . '" type="checkbox" name="' . esc_attr( $this->get_field_name( $for ) ) . '[]"' . checked( in_array( strval( $id ), $selected, true ), true, false ) . ' /> ' . esc_html( $label ) . "</label></li>\n";
		}
		$mu_checkbox .= "</ul></div>\n";

		if ( $echo ) {
			echo $mu_checkbox; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} else {
			return $mu_checkbox;
		}
	}

	/**
	 * Image upload option markup.
	 *
	 * @since 1.0.0
	 *
	 * @param str $id      Field ID.
	 * @param str $name    Field Name.
	 * @param int $value   Uploaded image id.
	 * @return str Widget form image upload markup.
	 */
	public function image_upload( $id, $name, $value ) {

		$value          = absint( $value );
		$uploader_class = '';
		$class          = 'podcast-player-hidden';

		if ( $value ) {
			$image_src = wp_get_attachment_image_src( $value, 'large' );
			if ( $image_src ) {
				$featured_markup = sprintf( '<img class="custom-widget-thumbnail" src="%s">', esc_url( $image_src[0] ) );
				$class           = '';
				$uploader_class  = 'has-image';
			} else {
				$featured_markup = esc_html__( 'Podcast Cover Image', 'podcast-player' );
			}
		} else {
			$featured_markup = esc_html__( 'Podcast Cover Image', 'podcast-player' );
		}

		$markup  = sprintf( '<a class="podcast-player-widget-img-uploader %s">%s</a>', $uploader_class, $featured_markup );
		$markup .= sprintf( '<span class="podcast-player-widget-img-instruct %s">%s</span>', $class, esc_html__( 'Click the image to edit/update', 'podcast-player' ) );
		$markup .= sprintf( '<a class="podcast-player-widget-img-remover %s">%s</a>', $class, esc_html__( 'Remove Featured Image', 'podcast-player' ) );
		$markup .= sprintf( '<input class="podcast-player-widget-img-id" name="%s" id="%s" value="%s" type="hidden" />', $name, $id, $value );
		return $markup;
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
	 * Check if item is supported by the style.
	 *
	 * @param string $style Current display style.
	 * @param string $item  item to be checked for support.
	 * @return bool
	 */
	public function is_style_support( $style, $item ) {
		if ( ! $style ) {
			return false;
		}

		$sup_arr = $this->style_supported[ $style ];
		return in_array( $item, $sup_arr, true );
	}
}
