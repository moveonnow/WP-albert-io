<?php
/**
 * Minus child theme related functions
 *
 * @package  Minus Child
 */

include_once('acf.php');

function minus_child_customizer( $wp_customize ) {

	// Child theme settings
	$wp_customize->add_section( 'thrivetheme_child_settings', array(
		'title'    => __( 'Child theme settings', 'thrive' ),
		'priority' => 32,
	) );

	// Explore School Resources button text
	$wp_customize->add_setting( 'school_resources_text', array(
	    'default' => 'School Resources'
	) );

	$wp_customize->add_control( 'school_resources_text', array(
	    'label'       => esc_html__( 'This text will appear on first button in upper right header', 'minus' ),
	    'priority'    => 0,
	    'section'     => 'thrivetheme_child_settings',
	    'type'        => 'text'
	) );

	// Explore School Resources button URL
	$wp_customize->add_setting( 'school_resources_link', array(
	    'default' => 'https://www.albert.io/blog/school-resources/?utm_campaign=blog-header-button&utm_medium=blog&utm_source=blog'
	) );

	$wp_customize->add_control( 'school_resources_link', array(
	    'label'       => esc_html__( 'This URL will be used as link for School Resources button', 'minus' ),
	    'priority'    => 0,
	    'section'     => 'thrivetheme_child_settings',
	    'type'        => 'text'
	) );

	// Explore Albert button text
	$wp_customize->add_setting( 'explore_albert_text', array(
	    'default' => 'Explore Albert'
	) );

	$wp_customize->add_control( 'explore_albert_text', array(
	    'label'       => esc_html__( 'This text will appear on second button in upper right header', 'minus' ),
	    'priority'    => 0,
	    'section'     => 'thrivetheme_child_settings',
	    'type'        => 'text'
	) );

	// Explore Albert button URL
	$wp_customize->add_setting( 'explore_albert_link', array(
	    'default' => 'https://www.albert.io/test-prep/?utm_campaign=blog-header-button&utm_medium=blog&utm_source=blog'
	) );

	$wp_customize->add_control( 'explore_albert_link', array(
	    'label'       => esc_html__( 'This URL will be used as link for Explore Albert button', 'minus' ),
	    'priority'    => 0,
	    'section'     => 'thrivetheme_child_settings',
	    'type'        => 'text'
	) );

}
add_action( 'customize_register', 'minus_child_customizer' );

/**
 * Enqueue parent theme stylesheet
 */
function minus_child_theme_styles() {

	wp_enqueue_style( 'minus-child-google-fonts', '//fonts.googleapis.com/css?family=Lato:300,300i,400,700,900&amp;subset=latin-ext', false );

    wp_enqueue_style( 'minus-parent-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'minus-style', get_stylesheet_directory_uri() . '/style.css', array( 'minus-parent-style', 'thrive-main-style', 'thrive-reset' ) );

    wp_enqueue_script( 'minus-child-script', get_stylesheet_directory_uri() . '/assets/js/common.js', array('jquery-ui-accordion'), false, true  );
}
add_action( 'wp_enqueue_scripts', 'minus_child_theme_styles' );

/**
 * Enqueues our external font awesome stylesheet
 */
function minus_child_font_awesome() {
	wp_enqueue_style( 'font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css');
}
add_action( 'wp_enqueue_scripts','minus_child_font_awesome' );

function register_my_menu() {
  register_nav_menu( 'mobile-menu', 'Mobile Menu', 'theme-slug' );
}
add_action( 'after_setup_theme', 'register_my_menu' );
