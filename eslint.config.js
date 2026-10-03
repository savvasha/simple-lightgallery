/**
 * ESLint flat config - WordPress coding standards for JavaScript.
 *
 * Only the plugin's own Select2 init script is linted; the bundled
 * lightGallery and Select2 libraries under assets/ are third-party code.
 *
 * @package Simple_lightGallery
 */

const wordpress = require( '@wordpress/eslint-plugin' );

module.exports = [
	{
		ignores: [
			'assets/lightgallery/**',
			'assets/select2/js/**',
			'assets/select2/css/**',
			'node_modules/**',
			'**/*.min.js',
		],
	},
	...wordpress.configs[ 'recommended-with-formatting' ],
];
