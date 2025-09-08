<?php
/**
 * Functions.php for Woostify Child Theme
 */

// ===============================
// 1. Parent theme style load করা
// ===============================
function woostify_child_enqueue_styles() {
    wp_enqueue_style( 'woostify-parent-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'woostify-child-style', get_stylesheet_directory_uri() . '/style.css', array('woostify-parent-style') );
}
add_action( 'wp_enqueue_scripts', 'woostify_child_enqueue_styles' );

// ===============================
// 2. Elementor Banner Widget load করা
// ===============================
function woostify_child_register_elementor_widgets( $widgets_manager ) {
    require_once( get_stylesheet_directory() . '/elementor-widgets/banner-block.php' );
    $widgets_manager->register( new \Elementor_Banner_Block() );
}
add_action( 'elementor/widgets/register', 'woostify_child_register_elementor_widgets' );
