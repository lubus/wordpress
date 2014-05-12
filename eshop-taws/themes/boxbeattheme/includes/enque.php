<?php
/**
 *my custom style sheets and script
 */
function my_scripts_method() {
 wp_enqueue_style( 'phiverivers-theme-options', get_template_directory_uri() . '/style_add.css', false, '2011-04-28' );
 wp_enqueue_style( 'phiverivers-theme-option1', get_template_directory_uri() . '/css/eshopbox.css', false, '2011-04-28' );

}
add_action( 'wp_enqueue_scripts', 'my_scripts_method' );

function phiverivers_customize_preview_js_new() {
	//wp_enqueue_script( 'phiverivers-customizer', get_template_directory_uri() . '/js/theme-customizer.js', array( 'customize-preview' ), '20120827', true );
}
add_action( 'customize_preview_init', 'phiverivers_customize_preview_js_new' );
?>