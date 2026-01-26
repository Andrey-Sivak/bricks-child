<?php
/**
 * Internationalization (i18n) functions
 *
 * @package Bricks_Child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Load theme text domain for translations
 *
 * @return void
 */
function bricks_child_load_textdomain() {
	load_child_theme_textdomain(
		'bricks-child',
		BRICKS_CHILD_DIR . '/languages'
	);
}
add_action( 'after_setup_theme', 'bricks_child_load_textdomain' );

/**
 * Get current language code (2-letter)
 *
 * @return string Language code (e.g., 'en', 'cs').
 */
function bricks_child_get_current_language() {
	// Check for Polylang.
	if ( function_exists( 'pll_current_language' ) ) {
		return pll_current_language();
	}

	// Check for WPML.
	if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
		return ICL_LANGUAGE_CODE;
	}

	// Fallback to WordPress locale.
	$locale = get_locale();
	return substr( $locale, 0, 2 );
}

/**
 * Get available languages
 *
 * @return array Array of language codes.
 */
function bricks_child_get_available_languages() {
	// Check for Polylang.
	if ( function_exists( 'pll_languages_list' ) ) {
		return pll_languages_list();
	}

	// Check for WPML.
	if ( function_exists( 'icl_get_languages' ) ) {
		$languages = icl_get_languages( 'skip_missing=0' );
		return array_keys( $languages );
	}

	// Default languages (EN & CZ).
	return array( 'en', 'cs' );
}

/**
 * Get language switcher links
 *
 * @return array Array of language data with 'code', 'name', 'url'.
 */
function bricks_child_get_language_switcher() {
	$languages = array();

	// Check for Polylang.
	if ( function_exists( 'pll_the_languages' ) ) {
		$pll_languages = pll_the_languages( array( 'raw' => 1 ) );
		foreach ( $pll_languages as $lang ) {
			$languages[] = array(
				'code'    => $lang['slug'],
				'name'    => $lang['name'],
				'url'     => $lang['url'],
				'current' => $lang['current_lang'],
			);
		}
		return $languages;
	}

	// Check for WPML.
	if ( function_exists( 'icl_get_languages' ) ) {
		$wpml_languages = icl_get_languages( 'skip_missing=0' );
		foreach ( $wpml_languages as $lang ) {
			$languages[] = array(
				'code'    => $lang['language_code'],
				'name'    => $lang['native_name'],
				'url'     => $lang['url'],
				'current' => $lang['active'],
			);
		}
		return $languages;
	}

	// Fallback: Manual language switcher (customize URLs).
	$current_lang = bricks_child_get_current_language();
	$languages    = array(
		array(
			'code'    => 'en',
			'name'    => 'English',
			'url'     => home_url( '/en/' ),
			'current' => ( 'en' === $current_lang ),
		),
		array(
			'code'    => 'cs',
			'name'    => 'Čeština',
			'url'     => home_url( '/cs/' ),
			'current' => ( 'cs' === $current_lang ),
		),
	);

	return $languages;
}

/**
 * Display language switcher
 *
 * @param string $format Output format: 'list' or 'dropdown'.
 * @return void
 */
function bricks_child_language_switcher( $format = 'list' ) {
	$languages = bricks_child_get_language_switcher();

	if ( empty( $languages ) ) {
		return;
	}

	if ( 'dropdown' === $format ) {
		?>
		<select class="language-switcher" onchange="window.location.href=this.value">
			<?php foreach ( $languages as $lang ) : ?>
				<option
					value="<?php echo esc_url( $lang['url'] ); ?>"
					<?php selected( $lang['current'], true ); ?>
				>
					<?php echo esc_html( $lang['name'] ); ?>
				</option>
			<?php endforeach; ?>
		</select>
		<?php
	} else {
		?>
		<ul class="language-switcher">
			<?php foreach ( $languages as $lang ) : ?>
				<li class="lang-item <?php echo $lang['current'] ? 'current-lang' : ''; ?>">
					<a href="<?php echo esc_url( $lang['url'] ); ?>" hreflang="<?php echo esc_attr( $lang['code'] ); ?>">
						<?php echo esc_html( $lang['code'] ); ?>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
		<?php
	}
}

/**
 * Translate string based on current language
 * Useful for hardcoded strings without Polylang/WPML
 *
 * @param array $translations Array with language codes as keys.
 * @return string Translated string or first value as fallback.
 */
function bricks_child_translate( $translations ) {
	$current_lang = bricks_child_get_current_language();

	if ( isset( $translations[ $current_lang ] ) ) {
		return $translations[ $current_lang ];
	}

	// Return first value as fallback.
	return reset( $translations );
}