=== Simple lightGallery ===
Contributors: savvasha
License: GPLv2 or later
Tags: lightGallery, gallery, photography, responsive, jQuery
Requires at least: 5.3
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 1.7.3

An integration of lightGallery JavaScript v1 and v2 to WordPress

== Description ==
An easy way to integrate either v1 or v2 of lightGallery JavaScript to WordPress. lightGallery is a lightweight, modular, JavaScript library for creating beautiful image & video galleries for the web and the mobile

#### Features

In **Settings > Simple lightGallery** you can find several options to control in which parts of your site the lightGallery will be loaded.

Some of lightGallery core features ([taken from lightGallery officially site](https://www.lightgalleryjs.com/docs/getting-started/))

* Fully responsive.
* Modular architecture with built in plugins.
* Highly optimized for touch devices.
* Mouse drag supports for desktops.
* Double-click/Double-tap to see actual size of the image.
* Animated thumbnails.
* Social sharing.
* And many more.

#### Usage

1.  Select in which post_types and taxonomies you want the lightGallery to be loaded.
1.  Make sure you are using the correct [markup](https://www.lightgalleryjs.com/docs/getting-started/#the-markup) and the appropriate [initialization](https://www.lightgalleryjs.com/docs/getting-started/#initialize-lightgallery) for your galleries.
1.  If you want to use lightGallery with WP native `[gallery]` shortcode make sure to use it with the option `link="file"`.

* For more usage tips, go to [lightGallery officially site](https://www.lightgalleryjs.com/)

== Installation ==

1.  Install and activate Simple lightGallery plugin
2.  Either use your own markup and code to initialize it on the frontend or enable the support for the WP `[gallery]` shortcode.

= Credits =

* [Chosen](https://github.com/jjj/chosen/)
* [lightGallery](https://www.lightgalleryjs.com/)

== Screenshots ==

1.  lightGallery Customization Options using v1 of the script
1.  lightGallery Customization Options using v2 of the script

== Changelog ==

= 1.7.3 =

* Tested up to WordPress 7.1
* Fix: Hide the selectors option when lightGallery v2 is selected
* DEV: Codebase brought in line with WordPress Coding Standards; no functional changes

= 1.7.2 =

* Fix: Remove jQuery dependency from v2 code

= 1.7.0 =

* Update lightGallery to version 2.7.2

= 1.6.1 =

* Fix: Hash plugin is not working on multiple galleries.
* Fix: Image captions are not shown at slideshow.

= 1.6.0 =

* Fix: lightGallery v2 Plugins are not working.

= 1.5.1 =

* Code cleaning...

= 1.5.0 =

* Replace jQuery Chosen library with Select2
* Add the option to auto-include the needed inline js code (for V1 only!)

= 1.4.0 =

* Update lightGallery to version 2.7.0

= 1.3.0 =

* Update lightGallery to version 2.3.0

= 1.2.0 =

* Add the option to use lightGallery with WP native `[gallery]` shortcode

= 1.0.1 =

* Cosmetic fixes

= 1.0.0 =

* First Release!