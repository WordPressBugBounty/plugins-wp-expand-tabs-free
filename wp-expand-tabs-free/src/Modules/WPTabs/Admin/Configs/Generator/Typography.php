<?php
/**
 * The Tabs General section.
 *
 * @link       https://shapedplugin.com/
 * @since      3.2.0
 *
 * @package    ShapedPlugin\SmartTabsFree
 * @subpackage Modules\WPTabs\Admin\Configs
 */

namespace ShapedPlugin\SmartTabsFree\Modules\WPTabs\Admin\Configs\Generator;

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.

use ShapedPlugin\SmartTabsFree\Core\Constants;
use ShapedPlugin\SmartTabsFree\Lib\CSFramework\Classes\SP_CSFramework;

/**
 * Class Typography
 */
class Typography {

	/**
	 * Register Typography section.
	 *
	 * @param string $prefix The prefix for the metabox.
	 */
	public static function section( $prefix ) {

		SP_CSFramework::createSection(
			$prefix,
			array(
				'title'           => __( 'Typography', 'wp-expand-tabs-free' ),
				'icon'            => 'fa fa-font',
				'enqueue_webfont' => true,
				'fields'          => array(
					array(
						'type'    => 'notice',
						'class'   => 'only_pro_notice_typo smart-tabs-notice',
						'content' => sprintf(
							/* translators: 1: start link and bold tag, 2: close tag 3: start bold tag 4: close bold tag. */
							__( 'Access 1500+ Google Fonts & Advanced Typography Options — %1$sUpgrade to Pro!%2$s', 'wp-expand-tabs-free' ),
							'<a href="' . esc_url( Constants::PRO_LINK ) . '" target="_blank"><b>',
							'</b></a>',
							'<b class="sptpro-notice-typo-exception">',
							'</b>'
						),
					),
					array(
						'id'        => 'sptpro_section_title_font_load',
						'class'     => 'only_pro_switcher',
						'type'      => 'switcher',
						'ignore_db' => true,
						'title'     => __( 'Load Tabs Section Title Font', 'wp-expand-tabs-free' ),
						'default'   => false,
					),
					array(
						'id'            => 'sptpro_section_title_typo',
						'type'          => 'typography',
						'class'         => 'sptpro_tabs_section_title_typo',
						'title'         => __( 'Tabs Section Title', 'wp-expand-tabs-free' ),
						'margin_bottom' => true,
						'default'       => array(
							'color'          => '#444444',
							'font-family'    => '',
							'font-style'     => '600',
							'font-size'      => '28',
							'line-height'    => '28',
							'letter-spacing' => '0',
							'text-align'     => 'left',
							'text-transform' => 'none',
							'type'           => 'google',
							'unit'           => 'px',
							'margin-bottom'  => '30',
						),
						'preview'       => 'always',
						'preview_text'  => 'Tabs Section Title',
					),
					array(
						'id'        => 'sptpro_tabs_title_font_load',
						'type'      => 'switcher',
						'ignore_db' => true,
						'class'     => 'only_pro_switcher',
						'title'     => __( 'Enable Google Fonts for Tab Name', 'wp-expand-tabs-free' ),
						'default'   => false,
					),
					array(
						'id'           => 'sptpro_tabs_title_typo',
						'type'         => 'typography',
						'title'        => __( 'Tabs Name', 'wp-expand-tabs-free' ),
						'default'      => array(
							'font-family'    => '',
							'font-weight'    => '600',
							'font-style'     => 'normal',
							'font-size'      => '16',
							'line-height'    => '22',
							'letter-spacing' => '0',
							'text-align'     => 'center',
							'text-transform' => 'none',
							'color'          => '#444',
							'hover_color'    => '#444',
							'active_color'   => '#444',
							'type'           => 'google',
						),
						'preview_text' => 'Tabs Name',
						'preview'      => 'always',
						'color'        => true,
						'hover_color'  => true,
						'active_color' => true,
					),
					array(
						'id'        => 'sptpro_desc_font_load',
						'type'      => 'switcher',
						'ignore_db' => true,
						'class'     => 'only_pro_switcher',
						'title'     => __( 'Enable Google Fonts for Tab Description', 'wp-expand-tabs-free' ),
						'default'   => false,
					),
					array(
						'id'           => 'sptpro_desc_typo',
						'type'         => 'typography',
						'title'        => __( 'Description', 'wp-expand-tabs-free' ),
						'default'      => array(
							'color'          => '#444',
							'font-family'    => '',
							'font-style'     => '400',
							'font-size'      => '16',
							'line-height'    => '24',
							'letter-spacing' => '0',
							'text-align'     => 'left',
							'text-transform' => 'none',
							'type'           => 'google',
						),
						'preview'      => 'always',
						'preview_text' => 'Smart Tabs makes your content organized and stylish. Easily create customizable tabs to enhance user experience.',
					),
					array(
						'id'        => 'sptpro_subtitle_font_load',
						'type'      => 'switcher',
						'ignore_db' => true,
						'class'     => 'only_pro_switcher',
						'title'     => __( 'Load Subtitle Font', 'wp-expand-tabs-free' ),
						'default'   => false,
					),
					array(
						'id'           => 'sptpro_subtitle_typo',
						'type'         => 'typography',
						'title'        => __( 'Tabs Subtitle', 'wp-expand-tabs-free' ),
						'class'        => 'disable-typos',
						'ignore_db'    => true,
						'default'      => array(
							'font-family'    => '',
							'font-style'     => '400',
							'font-size'      => '14',
							'line-height'    => '18',
							'letter-spacing' => '0',
							'color'          => '#616161',
							'active_color'   => '#616161',
							'hover_color'    => '#616161',
							'text-align'     => 'center',
							'text-transform' => 'none',
							'type'           => 'google',
						),
						'preview_text' => 'Tabs Sub Title',
						'preview'      => 'always',
						'color'        => true,
						'hover_color'  => true,
						'active_color' => true,
					),
				), // End of fields array.
			)
		); // Style settings section end.

		/**
		 * Shortcode show metabox.
		 *
		 * @param string 'sp_tab_select_shortcode' The metabox main Key.
		 * @return void
		 */
		SP_CSFramework::createMetabox(
			'sp_tab_select_shortcode',
			array(
				'title'     => __( 'How To Use', 'wp-expand-tabs-free' ),
				'post_type' => 'sp_wp_tabs',
				'context'   => 'side',
			)
		);

		SP_CSFramework::createSection(
			'sp_tab_select_shortcode',
			array(
				'fields' => array(
					array(
						'type'      => 'shortcode',
						'shortcode' => 'manage_view',
						'class'     => 'sp-tab__admin-sidebar',
					),
				),
			)
		);
	}
}
