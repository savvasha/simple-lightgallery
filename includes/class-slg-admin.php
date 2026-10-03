<?php
/**
 * Admin functionality.
 *
 * @package Simple_lightGallery
 * @subpackage Admin
 * @author Savvas
 */

/**
 * Simple lightGallery admin settings page.
 */
class simplelightGallery_Admin {

	/**
	 * The plugin options.
	 *
	 * @var array
	 */
	public static $options;

	/**
	 * Constructor
	 */
	public function __construct() {

		self::$options = get_option( 'simplelightGallery_settings' );

		// Hooks.
		add_action( 'admin_menu', array( $this, 'simplelightGallery_add_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'simplelightGallery_settings_init' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'select2_enqueue_assets' ), -99 );
		add_action( 'admin_head', array( $this, 'simplelightGallery_action_javascript' ) );

		add_filter( 'plugin_action_links_' . SIMPLELIGHTGALLERY_PLUGIN_BASE, array( $this, 'simplelightGallery_plugin_settings_link' ) );
	}

	/**
	 * Register the plugin options page under the Settings menu.
	 */
	public function simplelightGallery_add_admin_menu() {
		add_options_page( 'Simple lightGallery', 'Simple lightGallery', 'manage_options', 'simple_lightgallery', array( $this, 'simplelightGallery_options_page' ) );
	}

	/**
	 * Render the options page form.
	 */
	public function simplelightGallery_options_page() {
		?>
		<form action='options.php' method='post'>
			<h2>Simple lightGallery</h2>
			<?php
			settings_fields( 'pluginPage' );
			do_settings_sections( 'pluginPage' );
			submit_button();
			?>
		</form>
		<?php
	}

	/**
	 * Add a "Settings" link to the plugin row on the Plugins screen.
	 *
	 * @param array $links Existing plugin action links.
	 * @return array Filtered plugin action links.
	 */
	public function simplelightGallery_plugin_settings_link( $links ) {
		$plink = array(
			'<a href="' . admin_url( 'options-general.php?page=simple_lightgallery' ) . '">Settings</a>',
		);

		$links = array_merge( $links, $plink );

		return $links;
	}

	/**
	 * Register the plugin setting, section and fields.
	 */
	public function simplelightGallery_settings_init() {
		register_setting( 'pluginPage', 'simplelightGallery_settings' );

		add_settings_section(
			'simplelightGallery_pluginPage_section',
			sprintf(
				/* translators: 1: lightGallery website URL, 2: anchor target attribute. */
				__( 'An integration of <a href="%1$s" target="%2$s">Lightgallery</a> javascript to WordPress', 'simplelightGallery' ),
				'https://www.lightgalleryjs.com/',
				'_blank'
			),
			null,
			'pluginPage'
		);

		add_settings_field(
			'simplelightGallery_version',
			__( 'Which lightGallery version to use?', 'simplelightGallery' ),
			array( $this, 'simplelightGallery_version_render' ),
			'pluginPage',
			'simplelightGallery_pluginPage_section'
		);

		add_settings_field(
			'simplelightGallery_inline',
			__( 'Auto add inline js code? (Only v1 compatible!)', 'simplelightGallery' ),
			array( $this, 'simplelightGallery_inline_render' ),
			'pluginPage',
			'simplelightGallery_pluginPage_section',
			array( 'class' => 'simplelightGallery_inline' )
		);

		add_settings_field(
			'simplelightGallery_selectors',
			__( 'Which selector(s) to use?', 'simplelightGallery' ),
			array( $this, 'simplelightGallery_selector_render' ),
			'pluginPage',
			'simplelightGallery_pluginPage_section',
			array( 'class' => 'simplelightGallery_selectors' )
		);

		add_settings_field(
			'simplelightGallery_post_types',
			__( 'In which Post Type pages to enable this?', 'simplelightGallery' ),
			array( $this, 'simplelightGallery_post_types_render' ),
			'pluginPage',
			'simplelightGallery_pluginPage_section'
		);

		add_settings_field(
			'simplelightGallery_taxonomies',
			__( 'In which Taxonomy pages to enable this?', 'simplelightGallery' ),
			array( $this, 'simplelightGallery_taxonomies_render' ),
			'pluginPage',
			'simplelightGallery_pluginPage_section'
		);

		add_settings_field(
			'simplelightGallery_plugins',
			__( 'Which lightGallery v2 plugins should be enabled?', 'simplelightGallery' ),
			array( $this, 'simplelightGallery_plugins_render' ),
			'pluginPage',
			'simplelightGallery_pluginPage_section',
			array( 'class' => 'simplelightGallery_plugins' )
		);

		add_settings_field(
			'simplelightGallery_wpgallery',
			__( 'Use lightGallery with WordPress native gallery shortcode [gallery]', 'simplelightGallery' ),
			array( $this, 'simplelightGallery_wpgallery_render' ),
			'pluginPage',
			'simplelightGallery_pluginPage_section'
		);
	}

	/**
	 * Render the lightGallery version radio buttons.
	 */
	public function simplelightGallery_version_render() {

		if ( isset( self::$options['version'] ) ) {
			$version = self::$options['version'];
		} else {
			$version = 1;
		}
		?>
		<input type="radio" name="simplelightGallery_settings[version]" class="version" value="1" <?php checked( 1, $version, true ); ?>>v1.10.0 (jQuery dependency)
		<input type="radio" name="simplelightGallery_settings[version]" class="version" value="2" <?php checked( 2, $version, true ); ?>>v2.7.2
		<?php
	}

	/**
	 * Render the "auto add inline js" checkbox.
	 */
	public function simplelightGallery_inline_render() {
		?>
			<input type="checkbox" id="simplelightGallery_inline" class="simplelightGallery_inline" name="simplelightGallery_settings[inline]" value="1"<?php checked( isset( self::$options['inline'] ) ); ?> />
		<?php
	}

	/**
	 * Render the selectors multi-select field.
	 */
	public function simplelightGallery_selector_render() {

		if ( isset( self::$options['lightgallery_selectors'] ) ) {
			$selectors = (array) self::$options['lightgallery_selectors'];
		} else {
			$selectors = array( '#lightgallery' );
		}
		?>
		<select name="simplelightGallery_settings[lightgallery_selectors][]" id="lightgallery_selectors" multiple>
		<?php
		foreach ( $selectors as $selector ) {
			?>
			<option value="<?php echo esc_attr( $selector ); ?>" selected="selected"><?php echo esc_attr( $selector ); ?></option>
		<?php } ?>
		</select>
		<?php
	}


	/**
	 * Render the post types multi-select field.
	 */
	public function simplelightGallery_post_types_render() {
		$post_types = get_post_types();

		if ( isset( self::$options['lightgallery_post_types'] ) ) {
			$selected_post_types = (array) self::$options['lightgallery_post_types'];
		} else {
			$selected_post_types = array();
		}
		?>
		<select name="simplelightGallery_settings[lightgallery_post_types][]" id="lightgallery_post_types" multiple>
		<?php
		foreach ( $post_types as $key => $name ) {
				$selected = in_array( $name, $selected_post_types, true ) ? ' selected="selected" ' : '';
			?>
			<option value="<?php echo esc_attr( $key ); ?>" <?php echo esc_attr( $selected ); ?>><?php echo esc_attr( $name ); ?></option>
		<?php } ?>
		</select>
		<?php
	}


	/**
	 * Render the taxonomies multi-select field.
	 */
	public function simplelightGallery_taxonomies_render() {
		$taxonomies = get_taxonomies();

		if ( isset( self::$options['lightgallery_taxonomies'] ) ) {
			$selected_taxonomies = (array) self::$options['lightgallery_taxonomies'];
		} else {
			$selected_taxonomies = array();
		}
		?>
		<select name="simplelightGallery_settings[lightgallery_taxonomies][]" id="lightgallery_taxonomies" multiple>
		<?php
		foreach ( $taxonomies as $key => $name ) {
				$selected = in_array( $name, $selected_taxonomies, true ) ? ' selected="selected" ' : '';
			?>
			<option value="<?php echo esc_attr( $key ); ?>" <?php echo esc_attr( $selected ); ?>><?php echo esc_attr( $name ); ?></option>
		<?php } ?>
		</select>
		<?php
	}

	/**
	 * Render the lightGallery v2 plugins checkboxes.
	 */
	public function simplelightGallery_plugins_render() {
		$plugins = array( 'autoplay', 'comment', 'fullscreen', 'hash', 'mediumZoom', 'pager', 'relativeCaption', 'rotate', 'share', 'thumbnail', 'video', 'zoom' );

		?>
		<?php foreach ( $plugins as $plugin ) { ?>
			<input type="checkbox" id="<?php echo esc_attr( $plugin ); ?>" class="simplelightGallery_plugin" name="simplelightGallery_settings[plugins][<?php echo esc_attr( $plugin ); ?>]" value="1"<?php checked( isset( self::$options['plugins'][ $plugin ] ) ); ?> />
			<label for="<?php echo esc_attr( $plugin ); ?>"> <?php echo esc_attr( $plugin ); ?></label><br>
		<?php } ?>
		<?php
	}

	/**
	 * Render the WordPress native gallery shortcode checkbox.
	 */
	public function simplelightGallery_wpgallery_render() {
		?>
			<input type="checkbox" id="simplelightGallery_wpgallery" class="simplelightGallery_wpgallery" name="simplelightGallery_settings[wpgallery]" value="1"<?php checked( isset( self::$options['wpgallery'] ) ); ?> />
		<?php
	}


	/**
	 * Enqueue the Select2 assets on the plugin settings screen.
	 *
	 * @param string $hook_suffix The current admin page hook suffix.
	 */
	public function select2_enqueue_assets( $hook_suffix ) {
		if ( 'settings_page_simple_lightgallery' === $hook_suffix ) {
			wp_enqueue_script( 'select2', plugin_dir_url( __DIR__ ) . 'assets/select2/js/select2.min.js', array(), '4.0.13', false );
			wp_enqueue_script( 'select2-lightgallery', plugin_dir_url( __DIR__ ) . 'assets/select2/simple.lightgallery.select2.js', array( 'select2' ), '1.5.0', false );
			wp_enqueue_style( 'select2', plugin_dir_url( __DIR__ ) . 'assets/select2/css/select2.min.css', array(), '4.0.13' );
		}
	}

	/**
	 * Print the admin-head script that toggles fields by selected version.
	 */
	public function simplelightGallery_action_javascript() {

		?>
		<script type="text/javascript" >
		jQuery(document).ready(function($) {

			var version = $('input[type=radio][class=version]:checked').val();

			if ( version == '1' ){
				$('tr.simplelightGallery_plugins').hide();
				$('tr.simplelightGallery_inline').show();
				$('tr.simplelightGallery_selectors').show();
			}
			else if ( version == '2' ){
				$('tr.simplelightGallery_plugins').show();
				$('tr.simplelightGallery_inline').hide();
				$('tr.simplelightGallery_selectors').hide();
			}
			$('input[type=radio][class=version]').change(function() {
				if (this.value == '1') {
					$('tr.simplelightGallery_plugins').hide();
					$('tr.simplelightGallery_inline').show();
					$('tr.simplelightGallery_selectors').show();
				}
				else if (this.value == '2') {
					$('tr.simplelightGallery_plugins').show();
					$('tr.simplelightGallery_inline').hide();
					$('tr.simplelightGallery_selectors').hide();
				}
			});
		});
		</script>
		<?php
	}
}

new simplelightGallery_Admin();