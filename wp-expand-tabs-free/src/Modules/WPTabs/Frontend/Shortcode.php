<?php
/**
 * Define the shortcode functionality
 *
 * Loads and defines the shortcode for this plugin
 * so that it is ready for shortcode base view system.
 *
 * @link       https://shapedplugin.com/
 * @since      2.0.0
 *
 * @package    WP_Tabs_Pro
 * @subpackage WP_Tabs_Pro/includes
 */

namespace ShapedPlugin\SmartTabsFree\Modules\WPTabs\Frontend;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use ShapedPlugin\SmartTabsFree\Core\Constants;

/**
 * Define class for the shortcode functionality.
 */
class Shortcode {
	/**
	 * To check already rendered shortcode in the page.
	 *
	 * @var array
	 */
	protected static $rendered_tabs = array();

	/**
	 * Full html show.
	 *
	 * @param array $post_id Shortcode ID.
	 * @param array $sptpro_data_upload get all layout options.
	 * @param array $sptpro_shortcode_options get all meta options.
	 * @param array $main_section_title shows section title.
	 */
	public static function render_tab_html( $post_id, $sptpro_data_upload, $sptpro_shortcode_options, $main_section_title ) {
		// Tabs configurations.
		include Constants::src_path() . 'Modules/WPTabs/Frontend/TabsConfig.php';

		switch ( $sptpro_tabs_on_small_screen ) {
			case 'full_widht':
				$title_data_attr = 'aria-controls=%s aria-selected=true tabindex=0';
				break;
			case 'accordion_mode':
				wp_enqueue_script( 'sptpro-collapse' );
				$wrapper_class .= ' sp-tab__default-accordion';
				break;
		}

		wp_enqueue_script( 'sptpro-tab' );
		wp_enqueue_script( 'sptpro-script' );
		include Constants::src_path() . 'Modules/WPTabs/Frontend/partials/section-title.php';
		?>
		<div id="sp-wp-tabs-wrapper_<?php echo esc_attr( $post_id ); ?>" class="<?php echo esc_attr( $wrapper_class . $sptpro_tabs_position_bottom ); ?>" data-preloader="<?php echo esc_attr( $sptpro_preloader ); ?>" data-activemode="<?php echo esc_attr( $sptpro_tabs_activator_event ); ?>" data-anchor_linking="<?php echo esc_attr( $sptpro_anchor_linking ); ?>">
		<?php
		include Constants::src_path() . 'Modules/WPTabs/Frontend/preloader.php';
		if ( 'content-tabs' === $sptpro_tab_type ) {
			include Constants::src_path() . 'Modules/WPTabs/Frontend/partials/tab-custom/content.php';
		} else {
			include Constants::src_path() . 'Modules/WPTabs/Frontend/partials/tab-post/tabs-post.php';
		}
		?>
		</div>
		<?php
	}

	/**
	 * ShortCode of the Plugin.
	 *
	 * @since 2.0.0
	 * @param array $attributes The Attribute of the ShortCode.
	 * @param null  $content Param.
	 */
	public function execute_tabs_shortcode( $attributes, $content = null ) {
		static $rendered_tabs = array();

		if ( empty( $attributes['id'] ) || 'sp_wp_tabs' !== get_post_type( $attributes['id'] ) || ( get_post_status( $attributes['id'] ) === 'trash' ) ) {
			return;
		}
		$post_id                  = esc_attr( intval( $attributes['id'] ) );
		$sptpro_data_src          = get_post_meta( $post_id, 'sp_tab_source_options', true );
		$sptpro_shortcode_options = get_post_meta( $post_id, 'sp_tab_shortcode_options', true );
		$main_section_title       = get_the_title( $post_id );

		// Get the existing shortcode id from the current page.
		$get_page_data = Helper::get_page_data();

		ob_start();
		// Check if shortcode and page ids are not exist in the current page then enqueue the stylesheet.
		if ( empty( $rendered_tabs[ $post_id ] ) ) {
			// Load dynamic style for the existing shortcode.
			$dynamic_style  = Helper::load_dynamic_style( $post_id, $sptpro_shortcode_options );
			$accordion_mode = $dynamic_style['accordion'];
			if ( $accordion_mode ) {
				wp_enqueue_style( 'sptpro-accordion-style' );
			}
			wp_enqueue_style( 'sptpro-style' );
			echo '<style id="sp_tab_dynamic_style' . $post_id . '">' . $dynamic_style['dynamic_css'] . '</style>'; // phpcs:ignore
			$rendered_tabs[ $post_id ] = true;
		}

		// persistence.
		Helper::tabs_update_options( $post_id, $get_page_data );
		// Update all option If the option does not exist in the current page.
		// Helper::tabs_update_options( $post_id, $get_page_data );
		// Render output.
		self::render_tab_html( $post_id, $sptpro_data_src, $sptpro_shortcode_options, $main_section_title );
		return ob_get_clean();
	}
}
