<?php
/**
 * Typography settings of product tabs.
 *
 * @link       https://shapedplugin.com/
 * @since      3.2.0
 *
 * @package    ShapedPlugin\SmartTabsFree
 * @subpackage Modules\ProductTabs\Admin\Configs\TabStyle
 */

namespace ShapedPlugin\SmartTabsFree\Modules\ProductTabs\Admin\Configs\TabStyle;

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
	 * Section.
	 *
	 * @param mixed $prefix Prefix.
	 */
	public static function section( $prefix ) {
		//
		// Custom CSS Fields.
		//
		SP_CSFramework::createSection(
			$prefix,
			array(
				'title'  => __( 'Typography', 'wp-expand-tabs-free' ),
				'icon'   => 'fa icon-typography',
				'fields' => array(
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
						'id'        => 'product_tabs_title_font_load',
						'type'      => 'switcher',
						'ignore_db' => true,
						'class'     => 'only_pro_switcher',
						'title'     => __( 'Enable Google Fonts for Tab Name', 'wp-expand-tabs-free' ),
						'default'   => false,
					),
					array(
						'id'           => 'product_tabs_title_typo',
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
							'type'           => 'google',
						),
						'preview_text' => 'Tabs Name',
						'preview'      => 'always',
						'color'        => false,
					),
					array(
						'id'        => 'product_desc_font_load',
						'type'      => 'switcher',
						'ignore_db' => true,
						'class'     => 'only_pro_switcher',
						'title'     => __( 'Enable Google Fonts for Tab Description', 'wp-expand-tabs-free' ),
						'default'   => false,
					),
					array(
						'id'           => 'product_desc_typo',
						'type'         => 'typography',
						'title'        => __( 'Description', 'wp-expand-tabs-free' ),
						'color'        => false,
						'default'      => array(
							'font-family'    => '',
							'font-weight'    => '400',
							'font-style'     => 'normal',
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
				),
			)
		);
	}
}
