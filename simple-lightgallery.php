<?php
/**
 * Plugin Name: Simple lightGallery
 * Description: An integration of lightGallery JavaScript v1 and v2 to WordPress
 * Author: Savvas
 * Author URI: https://profiles.wordpress.org/savvasha/
 * Version: 1.7.3
 * Requires at least: 5.3
 * Requires PHP: 7.4
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl.html
 *
 * @package Simple_lightGallery
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Simple_lightGallery' ) ) :

	/**
	 * Main Simple lightGallery Class
	 *
	 * @class Simple_lightGallery
	 * @version 1.7.3
	 */
	class Simple_lightGallery {

		/**
		 * Constructor
		 */
		public function __construct() {
			// Define constants.
			$this->define_constants();

			// Include required files.
			$this->includes();
		}

		/**
		 * Define constants
		 */
		private function define_constants() {
			if ( ! defined( 'SIMPLELIGHTGALLERY_PLUGIN_BASE' ) ) {
				define( 'SIMPLELIGHTGALLERY_PLUGIN_BASE', plugin_basename( __FILE__ ) );
			}
		}

		/**
		 * Include required files
		 */
		private function includes() {
			// Load the needed frontend files.
			include __DIR__ . '/includes/class-slg-front.php';
			// Load the admin settings page.
			include __DIR__ . '/includes/class-slg-admin.php';
		}
	}

endif;

new Simple_lightGallery();
