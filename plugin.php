<?php
/**
 * Plugin name: Webfonts API Example Plugin
 */

/**
 * An array of font-families for our example plugin.
 *
 * @return array
 */
function webfonts_api_example_get_font_families() {
	return array(
		'',
		'Roboto',
		'Fruktur',
		'Zen Antique',
		'Open Sans',
		'Lato',
		'Montserrat',
		'Zen Old Mincho',
	);
}

/**
 * Add customizer section & control to select a font-family.
 *
 * @param WP_Customize_Manager $wp_customize
 * @return void
 */
function webfonts_api_example_add_customizer_controls( $wp_customize ) {
	$wp_customize->add_section( 'webfonts_api_example_section', array(
		'title' => 'Webfonts API Example',
		'priority' => 30,
	) );

	$wp_customize->add_setting( 'webfonts_api_example_font_family', array(
		'default' => '',
	) );

	$wp_customize->add_control( 'webfonts_api_example_font_family', array(
		'label'       => 'Font Family',
		'description' => 'Select a font family from the list below. Please note that all font-families are loaded from Google. Leave empty to use the theme\'s defaults.',
		'section'     => 'webfonts_api_example_section',
		'type'        => 'select',
		'choices'     => array_combine( webfonts_api_example_get_font_families(), webfonts_api_example_get_font_families() ),
	) );
}
add_action( 'customize_register', 'webfonts_api_example_add_customizer_controls' );

/**
 * Add font-family CSS to the <head> tag.
 *
 * @return void
 */
function webfonts_api_example_register_font_family() {
	
	if ( ! function_exists( 'wp_register_webfonts' ) ) {
		return;
	}
	
	$font_family = get_theme_mod( 'webfonts_api_example_font_family', '' );
	if ( ! $font_family ) {
		return;
	}

	wp_register_webfonts(
		array(
			array(
				'fontFamily' => $font_family,
				'fontStyle'  => 'normal',
				'fontWeight' => '400',
				'provider'   => 'google',
			),
			array(
				'fontFamily' => $font_family,
				'fontStyle'  => 'normal',
				'fontWeight' => '700',
				'provider'   => 'google',
			),
			array(
				'fontFamily' => $font_family,
				'fontStyle'  => 'italic',
				'fontWeight' => '400',
				'provider'   => 'google',
			),
			array(
				'fontFamily' => $font_family,
				'fontStyle'  => 'italic',
				'fontWeight' => '700',
				'provider'   => 'google',
			),
		)
	);
}

function webfonts_api_example_add_font_family_css() {
	$font_family = get_theme_mod( 'webfonts_api_example_font_family', '' );
	if ( empty( $font_family ) ) {
		return;
	}

	// Enqueue the webfonts.
	webfonts_api_example_register_font_family();

	// Print some CSS to apply the font-family.
	$css = ".site *,.edit-post-visual-editor *{font-family:{$font_family} !important;}";
	wp_add_inline_style( 'wp-block-library', $css );
}
add_action( 'wp_loaded', 'webfonts_api_example_add_font_family_css' );
