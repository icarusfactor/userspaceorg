<?php
/**
 * Podcast player display class.
 *
 * @link       https://www.vedathemes.com
 * @since      1.0.0
 *
 * @package    Podcast_Player
 * @subpackage Podcast_Player/public
 */

namespace Podcast_Player;

/**
 * Display podcast player instance.
 *
 * @package    Podcast_Player
 * @subpackage Podcast_Player/public
 * @author     vedathemes <contact@vedathemes.com>
 */
class Feed extends Display {

	/**
	 * Holds the instance of this class.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    object
	 */
	protected static $instance = null;

	/**
	 * Are we using modern player.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    bool
	 */
	protected $is_modern = true;

	/**
	 * Holds podcast episodes script data for each Podcast instance.
	 *
	 * @since  1.2.0
	 * @access private
	 * @var    array
	 */
	protected $script_data = [];

	/**
	 * Constructor method.
	 *
	 * @since  1.0.0
	 */
	public function __construct() {
		$legacy = get_option( 'pp-legacy-player' );
		if ( 'on' === $legacy ) {
			$this->is_modern = false;
		}
	}

	/**
	 * Register hooked functions.
	 *
	 * @since 1.0.0
	 */
	public static function init() {
		add_filter( 'podcast_player_script_data', [ self::get_instance(), 'scripts_data' ] );
		add_action( 'wp_ajax_pp_fetch_episodes', [ self::get_instance(), 'fetch_episodes' ] );
		add_action( 'wp_ajax_nopriv_pp_fetch_episodes', [ self::get_instance(), 'fetch_episodes' ] );
		add_action( 'wp_ajax_pp_search_episodes', [ self::get_instance(), 'search_episodes' ] );
		add_action( 'wp_ajax_nopriv_pp_search_episodes', [ self::get_instance(), 'search_episodes' ] );
	}

	/**
	 * Display current podcast player.
	 *
	 * @since  1.0.0
	 *
	 * @param array $args Podcast display args.
	 */
	public function display_podcast( $args ) {

		$feed_url = wp_strip_all_tags( $args['url'] );
		$feed     = $this->fetch_feed( $feed_url );

		if ( false === $feed ) {
			if ( is_wp_error( $feed ) ) {
				if ( is_admin() || current_user_can( 'manage_options' ) ) {
					echo '<p><strong>' . esc_html__( 'RSS Error:', 'podcast-player' ) . '</strong> ' . esc_html( $feed->get_error_message() ) . '</p>';
				}
			}
			return;
		}

		// Define other variables.
		$maxitems    = 0;
		$feed_items  = [];
		$title       = '';
		$description = '';
		$toggle      = '';
		$sortby      = $args['sortby'];
		$filterby    = $args['filterby'];

		$total_episodes = count( $this->get_items( $feed, 0, 0, 'none', $filterby ) );
		$maxitems       = 2 * absint( $args['number'] );
		$maxitems       = ( $total_episodes < $maxitems ) ? $total_episodes : $maxitems;
		$feed_items     = $this->get_items( $feed, 0, $maxitems, $sortby, $filterby );

		if ( empty( $feed_items ) ) {
			printf( 'No epsidoes available for given options.', 'podcast-player' );
			return;
		}

		$inst_class = Instance_Counter::get_instance();
		$number     = $inst_class->get();

		// Get main feed title.
		$feed_title = $feed->get_title();
		$title      = $feed_title ? $feed_title : esc_html__( 'Unknown Feed', 'podcast_player' );

		// Get main feed description.
		if ( $args['description'] ) {
			$description = $args['description'];
		} else {
			$description = $feed->get_description();
		}

		// Get nav-menu and menu toggle markup.
		if ( ! empty( $args['menu'] ) ) {
			$nav_menu = podcast_player_nav_menu(
				[ 'podcast-menu-' . absint( $number ), 'podcast-menu' ],
				esc_html__( 'Podcast Subscription Menu', 'podcast-player' ),
				[
					'menu_class'  => 'pod-menu',
					'menu'        => wp_get_nav_menu_object( $args['menu'] ),
					'depth'       => 1,
					'fallback_cb' => '',
				]
			);
		} else {
			$nav_menu_html = '';
			$link          = wp_strip_all_tags( $feed->get_permalink() );
			while ( stristr( $link, 'http' ) !== $link ) {
				$link = substr( $link, 1 );
			}

			if ( $link ) {
				$nav_menu_html .= '<li class="menu-item"><a href="' . esc_url( $link ) . '">' . esc_html__( 'Visit Website', 'podcast-player' ) . '</a></li>';
			}

			$nav_menu_html .= '<li class="menu-item"><a href="' . esc_url( $feed_url ) . '">' . esc_html__( 'RSS Feed', 'podcast-player' ) . '</a></li>';

			$nav_menu_html = '
			<nav id="podcast-menu-%1$s" class="podcast-menu"><h2 class="ppjs__offscreen">%2$s</h2><ul class="pod-menu">' . $nav_menu_html . '</ul></nav>';

			$nav_menu = sprintf(
				$nav_menu_html,
				absint( $number ),
				esc_html__( 'Podcast Subscription Menu', 'podcast-player' )
			);
		}

		// Toggle social navigation menu.
		if ( ! $args['hide-subscribe'] ) {
			$toggle = sprintf(
				'<button aria-expanded="false" class="pod-header__toggle" >%1$s%2$s</button>',
				esc_html__( 'Follow', 'podcast-player' ),
				podcast_player_get_icon( [ 'icon' => 'caret-down' ] )
			); // WPCS xss ok.
		}

		// Get main feed cover image markup.
		$image_id = ! empty( $args['image'] ) ? absint( $args['image'] ) : '';
		$img_url  = ! empty( $args['img_url'] ) ? wp_strip_all_tags( $args['img_url'] ) : '';
		while ( stristr( $img_url, 'http' ) !== $img_url ) {
			$img_url = substr( $img_url, 1 );
		}
		if ( $image_id ) {
			$img_markup = wp_get_attachment_image( $image_id, 'large', false, [ 'class' => 'podcast-cover-image' ] );
			$img_url    = wp_get_attachment_image_url( $image_id, 'large', false );
		} elseif ( $img_url ) {
			$img_markup = sprintf( '<img class="podcast-cover-image" src="%s">', esc_url( $img_url ) );
		} else {
			$img_markup = '';
			$img_url    = '';
		}

		if ( ! $img_markup ) {
			$podcast_image = wp_strip_all_tags( $feed->get_image_url() );
			while ( stristr( $podcast_image, 'http' ) !== $podcast_image ) {
				$podcast_image = substr( $podcast_image, 1 );
			}
			if ( $podcast_image ) {
				$img_markup = sprintf( '<img class="podcast-cover-image" src="%s">', esc_url( $podcast_image ) );
				$img_url    = $podcast_image;
			}
		}

		// Add image url to podcast player args.
		$args['imgurl'] = $img_url;

		// Prepare feed items for further use.
		$feed_items = $this->prepare_feed_items( $feed_items, $number, 0 );

		// Add script data for current podcast instance.
		$this->add_podcast_script_data( $feed_items, $maxitems, $total_episodes, $feed_url, $args, $number );
		$feed_items = array_values( $feed_items );

		// Re-count feed items to exclude inappropriate feed episodes.
		$maxitems = count( $feed_items );

		$this->podcast_episodes_from_feed_url(
			[
				'title'  => $title,
				'img'    => $img_markup,
				'imgurl' => $img_url,
				'toggle' => $toggle,
				'desc'   => $description,
				'nav'    => $nav_menu,
				'inst'   => $number,
				'max'    => $maxitems,
				'step'   => absint( $args['number'] ),
				'items'  => $feed_items,
				'sets'   => $args,
			]
		);

		$feed->__destruct();
		unset( $feed );
	}

	/**
	 * Fetch podcast feed from feed url.
	 *
	 * @since 1.0.0
	 *
	 * @param str $feed_url Feed Url.
	 * @return Obj|false
	 */
	public function fetch_feed( $feed_url ) {
		while ( stristr( $feed_url, 'http' ) !== $feed_url ) {
			$feed_url = substr( $feed_url, 1 );
		}

		if ( ! $feed_url ) {
			return false;
		}

		$feed = fetch_feed( $feed_url );

		if ( is_wp_error( $feed ) ) {
			return false;
		}

		$feed->enable_order_by_date( false );

		return $feed;
	}

	/**
	 * Get all the items from the feed after properly sorting.
	 *
	 * @since 1.6.0
	 *
	 * @param Obj $feed Feed object.
	 * @param int $start Index to start at.
	 * @param int $end Number of items to return. 0 for all items after `$start`.
	 * @param str $sort Sorting rule.
	 * @param str $filterby Filter text.
	 * @return array|null List of {@see SimplePie_Item} objects
	 */
	public function get_items( $feed, $start = 0, $end = 0, $sort = 'none', $filterby = '' ) {
		$items = $feed->get_items();

		if ( ! $items || empty( $items ) ) {
			return $items;
		}

		if ( $filterby ) {
			$items = $this->filter_items( $items, $filterby );
		}
		$items = $this->sort_items( $items, $sort );

		// Slice the data as desired.
		if ( 0 === $end ) {
			return array_slice( $items, $start );
		} else {
			return array_slice( $items, $start, $end );
		}

		return $items;
	}

	/**
	 * Filter episodes by a filter text string in episode title.
	 *
	 * @since 1.6.0
	 *
	 * @param array $items Feed items to be sorted.
	 * @param str   $filterby Filtering Rule.
	 * @return array|null Sorted list of {@see SimplePie_Item} objects
	 */
	public function filter_items( $items, $filterby ) {
		$filterby = strtolower( $filterby );
		foreach ( $items as $key => $item ) {
			$item_title = strtolower( $item->get_title() );
			if ( false === strpos( $item_title, $filterby ) ) {
				unset( $items[ $key ] );
			}
		}

		return $items;
	}

	/**
	 * Sort feed items.
	 *
	 * @since 1.6.0
	 *
	 * @param array $items Feed items to be sorted.
	 * @param str   $sort Sorting Rule.
	 * @return array|null Sorted list of {@see SimplePie_Item} objects
	 */
	public function sort_items( $items, $sort ) {
		$do_sort = true;

		if ( false !== strpos( $sort, 'date' ) ) {
			foreach ( $items as $item ) {
				if ( ! $item->get_date( 'U' ) ) {
					$do_sort = false;
					break;
				}
			}
		}

		if ( $do_sort ) {
			switch ( $sort ) {
				case 'sort_title_desc':
					usort( $items, array( get_class( $this ), 'sort_title_desc' ) );
					break;
				case 'sort_title_asc':
					usort( $items, array( get_class( $this ), 'sort_title_asc' ) );
					break;
				case 'sort_date_asc':
					usort( $items, array( get_class( $this ), 'sort_date_asc' ) );
					break;
				case 'sort_date_desc':
					usort( $items, array( get_class( $this ), 'sort_date_desc' ) );
					break;
				default:
					break;
			}
		}

		return $items;
	}

	/**
	 * Sorting callback for items title descending.
	 *
	 * @since 1.6.0
	 *
	 * @param SimplePie $a The SimplePieItem.
	 * @param SimplePie $b The SimplePieItem.
	 * @return boolean
	 */
	public static function sort_title_desc( $a, $b ) {
		return $a->get_title() <= $b->get_title();
	}

	/**
	 * Sorting callback for items title ascending.
	 *
	 * @since 1.6.0
	 *
	 * @param SimplePie $a The SimplePieItem.
	 * @param SimplePie $b The SimplePieItem.
	 * @return boolean
	 */
	public static function sort_title_asc( $a, $b ) {
		return $a->get_title() > $b->get_title();
	}

	/**
	 * Sorting callback for items date ascending.
	 *
	 * @since 1.6.0
	 *
	 * @param SimplePie $a The SimplePieItem.
	 * @param SimplePie $b The SimplePieItem.
	 * @return boolean
	 */
	public static function sort_date_asc( $a, $b ) {
		return $a->get_date( 'U' ) > $b->get_date( 'U' );
	}

	/**
	 * Sorting callback for items date descending.
	 *
	 * @since 1.6.0
	 *
	 * @param SimplePie $a The SimplePieItem.
	 * @param SimplePie $b The SimplePieItem.
	 * @return boolean
	 */
	public static function sort_date_desc( $a, $b ) {
		return $a->get_date( 'U' ) <= $b->get_date( 'U' );
	}

	/**
	 * Prepare feed episodes for current podcast player instance.
	 *
	 * @since 1.0.0
	 *
	 * @param array $items       Array of podcast episodes objects.
	 * @param int   $counter     Current podcast player instance number.
	 * @param int   $items_count Item number counter.
	 * @return array
	 */
	public function prepare_feed_items( $items, $counter, $items_count ) {
		$feed_items = [];
		$audio_ext  = wp_get_audio_extensions();
		$video_ext  = wp_get_video_extensions();
		$mime_types = wp_get_mime_types();

		foreach ( $items as $key => $item ) {
			$has_media  = false;
			$enclosure  = false;
			$featured   = null;
			$media_type = false;
			$enclosures = $item->get_enclosures();
			foreach ( $enclosures as $encl ) {
				$type = $encl->get_type();
				if ( false !== strpos( $type, 'audio' ) ) {
					$enclosure  = $encl;
					$media_type = 'audio';
					break;
				} elseif ( false !== strpos( $type, 'video' ) ) {
					$enclosure  = $encl;
					$media_type = 'video';
					break;
				}
			}

			if ( ! $enclosure ) {
				$enclosure = $item->get_enclosure();
			}

			if ( $enclosure ) {
				$media = $enclosure->get_link();
				// Strip querystring variables since WordPress Audio Player doesn't handle them.
				$media = preg_replace( '/\?.*/', '', $media );
				while ( stristr( $media, 'http' ) !== $media ) {
					$media = substr( $media, 1 );
				}

				if ( $media ) {
					$type = wp_check_filetype( $media, $mime_types );
					if ( in_array( strtolower( $type['ext'] ), $audio_ext, true ) ) {
						$has_media  = true;
						$media_type = 'audio';
					} elseif ( in_array( strtolower( $type['ext'] ), $video_ext, true ) ) {
						$has_media  = true;
						$media_type = 'video';
					}
				}
			}

			if ( false === $has_media ) {
				unset( $items[ $key ] );
				continue;
			}

			foreach ( $enclosures as $encl ) {
				$type = $encl->get_medium();
				if ( 'image' === $type ) {
					$featured = $encl->get_link();
					break;
				}
			}

			$author = $item->get_author();
			if ( is_object( $author ) ) {
				$author = $author->get_name();
				$author = wp_strip_all_tags( $author );
			}

			$title       = wp_strip_all_tags( $item->get_title() );
			$description = podcast_player_esc_desc( $item->get_content() );
			$link        = wp_strip_all_tags( $item->get_link() );
			while ( stristr( $link, 'http' ) !== $link ) {
				$link = substr( $link, 1 );
			}

			$date = $item->get_date( 'M j, Y' );
			$items_count++;
			$id       = $counter . '-' . $items_count;
			$link     = esc_attr( esc_url( $link ) );
			$media    = esc_attr( esc_url( $media ) );
			$featured = esc_attr( esc_url( $featured ) );

			$feed_items[ 'ppe-' . $id ]['title']       = $title ? esc_html( $title ) : '';
			$feed_items[ 'ppe-' . $id ]['description'] = $description;
			$feed_items[ 'ppe-' . $id ]['author']      = $author ? esc_html( $author ) : '';
			$feed_items[ 'ppe-' . $id ]['date']        = $date ? esc_html( $date ) : '';
			$feed_items[ 'ppe-' . $id ]['link']        = $link ? $link : $media ? $media : '';
			$feed_items[ 'ppe-' . $id ]['src']         = $media ? $media : '';
			$feed_items[ 'ppe-' . $id ]['featured']    = $featured ? $featured : '';
			$feed_items[ 'ppe-' . $id ]['mediatype']   = $media_type ? $media_type : '';
		}

		return $feed_items;
	}

	/**
	 * Add episodes data of current podcast instance to script data array.
	 *
	 * @since 1.0.0
	 *
	 * @param array $script_data Podcast data.
	 * @param int   $loaded Total episodes fetched from the feed.
	 * @param int   $number Maximum number of episodes to be displayed.
	 * @param str   $url    Podcast feed url.
	 * @param int   $args   Podcast settings.
	 * @param int   $counter Podcast player instance.
	 */
	public function add_podcast_script_data( $script_data = [], $loaded, $number, $url, $args, $counter ) {
		$ajax_args = [
			'imgurl' => esc_html( $args['imgurl'] ),
			'hddesc' => $args['hide-content'] ? 1 : 0,
		];

		$ajax_info = [
			'load_info' => [
				'loaded'    => absint( $loaded ),
				'displayed' => absint( $args['number'] ), // Initial count.
				'maxItems'  => absint( $number ),
				'src'       => esc_url( $url ),
				'step'      => absint( $args['number'] ),
				'sortby'    => esc_html( $args['sortby'] ),
				'filterby'  => esc_html( $args['filterby'] ),
				'args'      => $ajax_args,
			],
		];

		$render_info = [
			'rdata' => [
				'from' => 'feedurl',
				'elen' => absint( $args['excerpt-length'] ),
			],
		];

		$this->script_data[ 'pp-podcast-' . $counter ] = array_merge( $ajax_info, $script_data, $render_info );
	}

	/**
	 * Display podcast episodes.
	 *
	 * @since 1.0.0
	 *
	 * @param array $props Podcast player display props.
	 */
	public function podcast_episodes_from_feed_url( $props ) {
		if ( $this->is_modern ) {
			$this->render( $props );
		} else {
			include PODCAST_PLAYER_DIR . 'frontend/legacy/podcast-player-public-display.php';
		}
	}

	/**
	 * Populate podcast player cdata.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data Podcast data.
	 * @return array
	 */
	public function scripts_data( $data = [] ) {
		if ( ! isset( $data['ajax_info'] ) ) {
			$data['ajax_info'] = [
				'ajaxurl'  => admin_url( 'admin-ajax.php' ),
				'security' => wp_create_nonce( 'podcast-player-ajax-nonce' ),
			];
		}

		$data = array_merge( $data, $this->script_data );
		return $data;
	}

	/**
	 * Fetch podcast episodes for Ajax calls.
	 *
	 * @since 1.0.0
	 */
	public function fetch_episodes() {
		check_ajax_referer( 'podcast-player-ajax-nonce', 'security' );

		// Get variable values from Ajax request.
		$items_already_loaded  = isset( $_POST['loaded'] ) ? absint( wp_unslash( $_POST['loaded'] ) ) : '';
		$max_items_tobe_loaded = isset( $_POST['maxItems'] ) ? absint( wp_unslash( $_POST['maxItems'] ) ) : '';
		$feed_url              = isset( $_POST['feedUrl'] ) ? esc_url_raw( wp_unslash( $_POST['feedUrl'] ) ) : '';
		$player_instance       = isset( $_POST['instance'] ) ? absint( wp_unslash( $_POST['instance'] ) ) : '';
		$lot_size              = isset( $_POST['step'] ) ? absint( wp_unslash( $_POST['step'] ) ) : '';
		$sortby                = isset( $_POST['sortby'] ) ? sanitize_text_field( wp_unslash( $_POST['sortby'] ) ) : 'sort_date_desc';
		$filterby              = isset( $_POST['filterby'] ) ? sanitize_text_field( wp_unslash( $_POST['filterby'] ) ) : '';

		// Get Podcast feed object.
		$feed = $this->fetch_feed( $feed_url );

		// Return empty array if feed not available.
		if ( false === $feed ) {
			echo wp_json_encode( [] );
			wp_die();
		}

		// Get remaining episodes which are not yet loaded to front-end.
		$remaining_items_tobe_loaded = $max_items_tobe_loaded - $items_already_loaded;

		$maxitems   = $feed->get_item_quantity( min( $remaining_items_tobe_loaded, $lot_size ) );
		$feed_items = $this->get_items( $feed, $items_already_loaded, $items_already_loaded + $maxitems, $sortby, $filterby );

		// Prepare feed items for further use.
		$feed_items = $this->prepare_feed_items( $feed_items, $player_instance, $items_already_loaded );

		// Ajax output to be returened.
		$output = [
			'loaded'   => $maxitems + $items_already_loaded,
			'episodes' => $feed_items,
		];
		echo wp_json_encode( $output );

		wp_die();
	}

	/**
	 * Fetch podcast episodes for Ajax calls.
	 *
	 * @since 1.0.0
	 */
	public function search_episodes() {
		check_ajax_referer( 'podcast-player-ajax-nonce', 'security' );

		// Get variable values from Ajax request.
		$items_already_loaded  = isset( $_POST['loaded'] ) ? absint( wp_unslash( $_POST['loaded'] ) ) : '';
		$max_items_tobe_loaded = isset( $_POST['maxItems'] ) ? absint( wp_unslash( $_POST['maxItems'] ) ) : '';
		$feed_url              = isset( $_POST['feedUrl'] ) ? esc_url_raw( wp_unslash( $_POST['feedUrl'] ) ) : '';
		$player_instance       = isset( $_POST['instance'] ) ? sanitize_text_field( wp_unslash( $_POST['instance'] ) ) : '';
		$search_term           = isset( $_POST['search'] ) ? sanitize_text_field( wp_unslash( $_POST['search'] ) ) : false;
		$sortby                = isset( $_POST['sortby'] ) ? sanitize_text_field( wp_unslash( $_POST['sortby'] ) ) : 'sort_date_desc';
		$filterby              = isset( $_POST['filterby'] ) ? sanitize_text_field( wp_unslash( $_POST['filterby'] ) ) : '';

		if ( ! $search_term || ! ( str_replace( ' ', '', $search_term ) ) ) {
			echo wp_json_encode( [] );
			wp_die();
		}

		// Get Podcast feed object.
		$feed = $this->fetch_feed( $feed_url );

		// Return empty array if feed not available.
		if ( false === $feed ) {
			echo wp_json_encode( [] );
			wp_die();
		}

		$feed_items = $this->get_items( $feed, $items_already_loaded, $max_items_tobe_loaded, $sortby, $filterby );

		// Prepare feed items for further use.
		$feed_items = $this->prepare_feed_items( $feed_items, $player_instance, $items_already_loaded );

		// Filter episodes which are having the search term.
		$search_term = strtolower( $search_term );
		foreach ( $feed_items as $key => $item ) {
			$item_title = strtolower( $item['title'] );
			if ( false === strpos( $item_title, $search_term ) ) {
				unset( $feed_items[ $key ] );
			}
		}

		$count = count( $feed_items );
		if ( $count ) {
			// Ajax output to be returened.
			$output = [
				'loaded'   => $count,
				'episodes' => $feed_items,
			];
			echo wp_json_encode( $output );
		} else {
			echo wp_json_encode( [] );
		}

		wp_die();
	}

	/**
	 * Returns the instance of this class.
	 *
	 * @since  1.0.0
	 *
	 * @return object Instance of this class.
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}


}
Feed::init();
