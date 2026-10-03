<?php
/**
 * Frontend functionality.
 *
 * @package Simple_lightGallery
 * @subpackage Front
 * @author Savvas
 */

/**
 * Simple lightGallery frontend handler.
 */
class simplelightGallery_Front {

	/**
	 * The selected version.
	 *
	 * @var int
	 */
	public static $version;

	/**
	 * Apply or not an inline js script.
	 *
	 * @var boolean
	 */
	public static $inline;

	/**
	 * The selected selectors.
	 *
	 * @var array
	 */
	public static $selected_selectors;

	/**
	 * The selected post types.
	 *
	 * @var array
	 */
	public static $selected_post_types;

	/**
	 * The selected taxonomies.
	 *
	 * @var array
	 */
	public static $selected_taxonomies;

	/**
	 * The selected plugins.
	 *
	 * @var array
	 */
	public static $selected_plugins;

	/**
	 * How to map selected plugin names with script's names.
	 *
	 * @var array
	 */
	public static $selected_plugins_names;

	/**
	 * Apply to wp gallery shortcode.
	 *
	 * @var int
	 */
	public static $wpgallery;

	/**
	 * Constructor
	 */
	public function __construct() {

		$options = get_option( 'simplelightGallery_settings' );

		if ( isset( $options['lightgallery_post_types'] ) ) {
			self::$selected_post_types = (array) $options['lightgallery_post_types'];
		} else {
			self::$selected_post_types = array();
		}

		if ( isset( $options['lightgallery_taxonomies'] ) ) {
			self::$selected_taxonomies = (array) $options['lightgallery_taxonomies'];
		} else {
			self::$selected_taxonomies = array();
		}

		if ( isset( $options['plugins'] ) ) {
			self::$selected_plugins = (array) $options['plugins'];
		} else {
			self::$selected_plugins = array();
		}

		if ( isset( $options['version'] ) ) {
			self::$version = $options['version'];
		} else {
			self::$version = 1;
		}

		if ( isset( $options['inline'] ) ) {
			self::$inline = true;
		} else {
			self::$inline = false;
		}

		if ( isset( $options['lightgallery_selectors'] ) ) {
			self::$selected_selectors = (array) $options['lightgallery_selectors'];
		} else {
			self::$selected_selectors = array( '#lightgallery' );
		}

		if ( isset( $options['lightgallery_selectors'] ) ) {
			self::$selected_selectors = (array) $options['lightgallery_selectors'];
		} else {
			self::$selected_selectors = array( '#lightgallery' );
		}

		if ( isset( $options['wpgallery'] ) ) {
			self::$wpgallery = $options['wpgallery'];
		} else {
			self::$wpgallery = 0;
		}

		self::$selected_plugins_names = array(
			'autoplay'        => 'lgAutoplay',
			'comment'         => 'lgComment',
			'fullscreen'      => 'lgFullscreen',
			'hash'            => 'lgHash',
			'mediumZoom'      => 'lgMediumZoom',
			'pager'           => 'lgPager',
			'relativeCaption' => 'lgRelativeCaption',
			'rotate'          => 'lgRotate',
			'share'           => 'lgShare',
			'thumbnail'       => 'lgThumbnail',
			'video'           => 'lgVideo',
			'zoom'            => 'lgZoom',
		);

		// Hooks.
		add_action( 'wp_enqueue_scripts', array( $this, 'simplelightGallery_enqueue_properties_scripts' ) );
		if ( self::$wpgallery ) {
			add_filter( 'the_content', array( $this, 'simplelightGallery_inline_js' ), 1 );
		}
	}

	/**
	 * Register and enqueue the lightGallery scripts and styles on matching pages.
	 */
	public function simplelightGallery_enqueue_properties_scripts() {

		if ( in_array( get_post_type(), self::$selected_post_types, true ) || is_tax( self::$selected_taxonomies ) ) {
			if ( wp_script_is( 'lightgallery-min', 'enqueued' ) ) {
				return;

			} else {
				switch ( self::$version ) :
					case 1:
						wp_register_script( 'lightgallery-min', plugin_dir_url( __DIR__ ) . 'assets/lightgallery/v1/js/lightgallery.min.js', array( 'jquery' ), '1.10.0', true );
						wp_enqueue_script( 'lightgallery-min' );
						wp_enqueue_style( 'lightgallery', plugin_dir_url( __DIR__ ) . 'assets/lightgallery/v1/css/lightgallery.min.css', array(), '1.10.0', 'all' );

						if ( self::$inline ) {
							wp_add_inline_script(
								'lightgallery-min',
								'
																		jQuery(document).ready(function() {
																			jQuery("' . implode( ', ', self::$selected_selectors ) . '").lightGallery(); 
																		});'
							);
						}
						break;
					case 2:
						wp_register_script( 'lightgallery-min', plugin_dir_url( __DIR__ ) . 'assets/lightgallery/v2/lightgallery.min.js', array(), '2.7.2', false );
						wp_enqueue_script( 'lightgallery-min' );
						$last_plugin_script = 'lightgallery-min';
						wp_enqueue_style( 'lightgallery', plugin_dir_url( __DIR__ ) . 'assets/lightgallery/v2/css/lightgallery-bundle.min.css', array(), '2.7.2', 'all' );
						$inline_plugins = array();

						foreach ( self::$selected_plugins as $selected_plugin => $value ) {
							$inline_plugins[] = self::$selected_plugins_names[ $selected_plugin ];
							switch ( $selected_plugin ) :
								case 'autoplay':
									wp_register_script( 'lightgallery-lg-autoplay-min', plugin_dir_url( __DIR__ ) . 'assets/lightgallery/v2/plugins/autoplay/lg-autoplay.min.js', array( 'lightgallery-min' ), '2.7.2', false );
									wp_enqueue_script( 'lightgallery-lg-autoplay-min' );
									$last_plugin_script = 'lightgallery-lg-autoplay-min';
									break;
								case 'comment':
									wp_register_script( 'lightgallery-lg-comment-min', plugin_dir_url( __DIR__ ) . 'assets/lightgallery/v2/plugins/comment/lg-comment.min.js', array( 'lightgallery-min' ), '2.7.2', false );
									wp_enqueue_script( 'lightgallery-lg-comment-min' );
									$last_plugin_script = 'lightgallery-lg-comment-min';
									break;
								case 'fullscreen':
									wp_register_script( 'lightgallery-lg-fullscreen-min', plugin_dir_url( __DIR__ ) . 'assets/lightgallery/v2/plugins/fullscreen/lg-fullscreen.min.js', array( 'lightgallery-min' ), '2.7.2', false );
									wp_enqueue_script( 'lightgallery-lg-fullscreen-min' );
									$last_plugin_script = 'lightgallery-lg-fullscreen-min';
									break;
								case 'hash':
									wp_register_script( 'lightgallery-lg-hash-min', plugin_dir_url( __DIR__ ) . 'assets/lightgallery/v2/plugins/hash/lg-hash.min.js', array( 'lightgallery-min' ), '2.7.2', false );
									wp_enqueue_script( 'lightgallery-lg-hash-min' );
									$last_plugin_script = 'lightgallery-lg-hash-min';
									break;
								case 'mediumZoom':
									wp_register_script( 'lightgallery-lg-mediumZoom-min', plugin_dir_url( __DIR__ ) . 'assets/lightgallery/v2/plugins/mediumZoom/lg-medium-zoom.min.js', array( 'lightgallery-min' ), '2.7.2', false );
									wp_enqueue_script( 'lightgallery-lg-mediumZoom-min' );
									$last_plugin_script = 'lightgallery-lg-mediumZoom-min';
									break;
								case 'pager':
									wp_register_script( 'lightgallery-lg-pager-min', plugin_dir_url( __DIR__ ) . 'assets/lightgallery/v2/plugins/pager/lg-pager.min.js', array( 'lightgallery-min' ), '2.7.2', false );
									wp_enqueue_script( 'lightgallery-lg-pager-min' );
									$last_plugin_script = 'lightgallery-lg-pager-min';
									break;
								case 'relativeCaption':
									wp_register_script( 'lightgallery-lg-relativeCaption-min', plugin_dir_url( __DIR__ ) . 'assets/lightgallery/v2/plugins/relativeCaption/lg-relative-caption.min.js', array( 'lightgallery-min' ), '2.7.2', false );
									wp_enqueue_script( 'lightgallery-lg-relativeCaption-min' );
									$last_plugin_script = 'lightgallery-lg-relativeCaption-min';
									break;
								case 'rotate':
									wp_register_script( 'lightgallery-lg-rotate-min', plugin_dir_url( __DIR__ ) . 'assets/lightgallery/v2/plugins/rotate/lg-rotate.min.js', array( 'lightgallery-min' ), '2.7.2', false );
									wp_enqueue_script( 'lightgallery-lg-rotate-min' );
									$last_plugin_script = 'lightgallery-lg-rotate-min';
									break;
								case 'share':
									wp_register_script( 'lightgallery-lg-share-min', plugin_dir_url( __DIR__ ) . 'assets/lightgallery/v2/plugins/share/lg-share.min.js', array( 'lightgallery-min' ), '2.7.2', false );
									wp_enqueue_script( 'lightgallery-lg-share-min' );
									$last_plugin_script = 'lightgallery-lg-share-min';
									break;
								case 'thumbnail':
									wp_register_script( 'lightgallery-lg-thumbnail-min', plugin_dir_url( __DIR__ ) . 'assets/lightgallery/v2/plugins/thumbnail/lg-thumbnail.min.js', array( 'lightgallery-min' ), '2.7.2', false );
									wp_enqueue_script( 'lightgallery-lg-thumbnail-min' );
									$last_plugin_script = 'lightgallery-lg-thumbnail-min';
									break;
								case 'video':
									wp_register_script( 'lightgallery-lg-video-min', plugin_dir_url( __DIR__ ) . 'assets/lightgallery/v2/plugins/video/lg-video.min.js', array( 'lightgallery-min' ), '2.7.2', false );
									wp_enqueue_script( 'lightgallery-lg-video-min' );
									$last_plugin_script = 'lightgallery-lg-video-min';
									break;
								case 'zoom':
									wp_register_script( 'lightgallery-lg-zoom-min', plugin_dir_url( __DIR__ ) . 'assets/lightgallery/v2/plugins/zoom/lg-zoom.min.js', array( 'lightgallery-min' ), '2.7.2', false );
									wp_enqueue_script( 'lightgallery-lg-zoom-min' );
									$last_plugin_script = 'lightgallery-lg-zoom-min';
									break;
							endswitch;
						}
						// Which plugins to use.
						$inline_plugins_string = implode( ', ', $inline_plugins );
						wp_add_inline_script(
							$last_plugin_script,
							'
																	lightGallery(document.getElementById("' . implode( ', ', self::$selected_selectors ) . '"), {
																		plugins: [' . $inline_plugins_string . '],
																		
																	});'
						);
						break;
				endswitch;
			}
		}
	}

	/**
	 * Append the lightGallery init script when the [gallery] shortcode is used.
	 *
	 * @param string $content The post content.
	 * @return string The content, with the init script appended when applicable.
	 */
	public function simplelightGallery_inline_js( $content ) {
		// Check if gallery shortcode is used.
		if ( ! has_shortcode( $content, 'gallery' ) ) {
			return $content;
		}
		// Which plugins to use.
		$inline_plugins = array();
		foreach ( self::$selected_plugins as $selected_plugin => $value ) {
			$inline_plugins[] = self::$selected_plugins_names[ $selected_plugin ];
		}
		$inline_plugins_string = implode( ', ', $inline_plugins );
		// Check if we're inside the main loop in a single Post.
		if ( is_singular() && in_the_loop() && is_main_query() ) {
			if ( in_array( get_post_type(), self::$selected_post_types, true ) || is_tax( self::$selected_taxonomies ) ) {
				$id = get_the_ID();
				switch ( self::$version ) :
					case 1:
						$inline_script = "<script type=\"text/javascript\">
													jQuery(document).ready(function($) {
														$('.galleryid-$id').each(function(i, obj) {
															$('#'+$(this).prop('id')).lightGallery({
																selector: 'a',
															});
														});
													});
										</script>";
						break;
					case 2:
						$inline_script = "<script type=\"text/javascript\">
											document.addEventListener('DOMContentLoaded', function() {
												// Select all divs with an ID starting with 'gallery-'
												document.querySelectorAll('div[id^=\"gallery-\"]').forEach(function(galleryDiv) {
													// For each gallery item within this gallery div
													galleryDiv.querySelectorAll('.gallery-item').forEach(function(galleryItem) {
														// Retrieve the text inside the figcaption element.
														var caption = galleryItem.querySelector('figcaption')?.textContent || '';
														// Add the data-sub-html attribute to the corresponding <a> element
														galleryItem.querySelector('a').setAttribute('data-sub-html', caption);
													});
													// Get the ID of the current div
													var id = galleryDiv.getAttribute('id');
													// Use a regular expression to extract the number from the ID
													var number = id.match(/\\d+/)[0];
													// Initialize lightGallery
													lightGallery(galleryDiv, {
														plugins: [$inline_plugins_string],
														selector: 'a',
														galleryId: number,
													});
												});
											});
										</script>";

						break;
				endswitch;
				return $content . $inline_script;
			}
			return $content;
		}
		return $content;
	}
}

new simplelightGallery_Front();
