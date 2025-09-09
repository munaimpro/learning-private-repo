<?php

/**
 * @see http://tgmpluginactivation.com/configuration/ for detailed documentation.
 *
 * @package    TGM-Plugin-Activation
 * @subpackage Example
 * @version    2.6.1 for parent theme Ruby Ecommerce for publication on WordPress.org
 * @author     Thomas Griffin, Gary Jones, Juliette Reinders Folmer
 * @copyright  Copyright (c) 2011, Thomas Griffin
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       https://github.com/TGMPA/TGM-Plugin-Activation
 */

require_once get_template_directory() . '/inc/tgm/class-tgm-plugin-activation.php';

add_action('tgmpa_register', 'ruby_ecommerce_register_required_plugins', 0);
function ruby_ecommerce_register_required_plugins()
{
	$plugins = array(
		array(
			'name'      => 'Superb Addons',
			'slug'      => 'superb-blocks',
			'required'  => false,
		),
		array(
			'name'      => 'WooCommerce',
			'slug'      => 'woocommerce',
			'required'  => false,
		)
	);

	$config = array(
		'id'           => 'ruby-ecommerce',
		'default_path' => '',
		'menu'         => 'tgmpa-install-plugins',
		'has_notices'  => true,
		'dismissable'  => true,
		'dismiss_msg'  => '',
		'is_automatic' => true,
		'message'      => '',
	);

	tgmpa($plugins, $config);
}


function ruby_ecommerce_pattern_styles()
{
	wp_enqueue_style('ruby-ecommerce-patterns', get_template_directory_uri() . '/assets/css/patterns.css', array(), filemtime(get_template_directory() . '/assets/css/patterns.css'));
	if (is_admin()) {
		global $pagenow;
		if ('site-editor.php' === $pagenow) {
			// Do not enqueue editor style in site editor
			return;
		}
		wp_enqueue_style('ruby-ecommerce-editor', get_template_directory_uri() . '/assets/css/editor.css', array(), filemtime(get_template_directory() . '/assets/css/editor.css'));
	}
}
add_action('enqueue_block_assets', 'ruby_ecommerce_pattern_styles');


add_theme_support('wp-block-styles');

// Removes the default wordpress patterns
add_action('init', function () {
	remove_theme_support('core-block-patterns');
});

// Register customer Ruby Ecommerce pattern categories
function ruby_ecommerce_register_block_pattern_categories()
{
	register_block_pattern_category(
		'header',
		array(
			'label'       => __('Header', 'ruby-ecommerce'),
			'description' => __('Header patterns', 'ruby-ecommerce'),
		)
	);
	register_block_pattern_category(
		'call_to_action',
		array(
			'label'       => __('Call To Action', 'ruby-ecommerce'),
			'description' => __('Call to action patterns', 'ruby-ecommerce'),
		)
	);
	register_block_pattern_category(
		'content',
		array(
			'label'       => __('Content', 'ruby-ecommerce'),
			'description' => __('Ruby Ecommerce content patterns', 'ruby-ecommerce'),
		)
	);
	register_block_pattern_category(
		'teams',
		array(
			'label'       => __('Teams', 'ruby-ecommerce'),
			'description' => __('Team patterns', 'ruby-ecommerce'),
		)
	);
	register_block_pattern_category(
		'banners',
		array(
			'label'       => __('Banners', 'ruby-ecommerce'),
			'description' => __('Banner patterns', 'ruby-ecommerce'),
		)
	);
	register_block_pattern_category(
		'contact',
		array(
			'label'       => __('Contact', 'ruby-ecommerce'),
			'description' => __('Contact patterns', 'ruby-ecommerce'),
		)
	);
	register_block_pattern_category(
		'layouts',
		array(
			'label'       => __('Layouts', 'ruby-ecommerce'),
			'description' => __('layout patterns', 'ruby-ecommerce'),
		)
	);
	register_block_pattern_category(
		'testimonials',
		array(
			'label'       => __('Testimonial', 'ruby-ecommerce'),
			'description' => __('Testimonial and review patterns', 'ruby-ecommerce'),
		)
	);

}

add_action('init', 'ruby_ecommerce_register_block_pattern_categories');






// Initialize information content
require_once trailingslashit(get_template_directory()) . 'inc/vendor/autoload.php';

use SuperbThemesThemeInformationContent\ThemeEntryPoint;
add_action("init", function () {
ThemeEntryPoint::init([
    'type' => 'block', // block / classic
    'theme_url' => 'https://superbthemes.com/ruby-ecommerce/',
    'demo_url' => 'https://superbthemes.com/demo/ruby-ecommerce/',
    'features' => array(
    	array(
    		'title' => __("Theme Designer", "ruby-ecommerce"),
    		'icon' => "lego-duotone.webp",
    		'description' => __("Choose from over 300 designs for footers, headers, landing pages & all other theme parts.", "ruby-ecommerce")
    	),
    	   	array(
    		'title' => __("Editor Enhancements", "ruby-ecommerce"),
    		'icon' => "1-1.png",
    		'description' => __("Enhanced editor experience, grid systems, improved block control and much more.", "ruby-ecommerce")
    	),
    	array(
    		'title' => __("Custom CSS", "ruby-ecommerce"),
    		'icon' => "2-1.png",
    		'description' => __("Add custom CSS with syntax highlight, custom display settings, and minified output.", "ruby-ecommerce")
    	),
    	array(
    		'title' => __("Animations", "ruby-ecommerce"),
    		'icon' => "wave-triangle-duotone.webp",
    		'description' => __("Animate any element on your website with one click. Choose from over 50+ animations.", "ruby-ecommerce")
    	),
    	array(
    		'title' => __("WooCommerce Integration", "ruby-ecommerce"),
    		'icon' => "shopping-cart-duotone.webp",
    		'description' => __("Choose from over 100 unique WooCommerce designs for your e-commerce store.", "ruby-ecommerce")
    	),
    	array(
    		'title' => __("Responsive Controls", "ruby-ecommerce"),
    		'icon' => "arrows-out-line-horizontal-duotone.webp",
    		'description' => __("Make any theme mobile-friendly with SuperbThemes responsive controls.", "ruby-ecommerce")
    	)
    )
]);
});