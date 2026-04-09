<?php
/**
 * The admin-specific functionality for the Shortcode-based Tabs.
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
 * Class TabsSource
 */
class TabsSource {
	/**
	 * Register Tabs Source metabox
	 *
	 * @param string $prefix Prefix.
	 */
	public static function section( $prefix ) {
		SP_CSFramework::createSection(
			$prefix,
			array(
				'fields' => array(
					array(
						'type'    => 'heading',
						'image'   => Constants::wp_tabs_url() . 'Admin/Assets/img/smart-group-tabs-logo.svg',
						'after'   => '<i class="fa fa-life-ring"></i> Support',
						'link'    => 'https://shapedplugin.com/support/?user=lite',
						'class'   => 'sp-tab__admin-header',
						'version' => Constants::VERSION,
					),
					array(
						'id'      => 'sptpro_tab_type',
						'type'    => 'button_set',
						'title'   => __( 'Tabs Source Type', 'wp-expand-tabs-free' ),
						'class'   => 'sp_wp_tab_type',
						'options' => array(
							'content-tabs' => __( 'Custom', 'wp-expand-tabs-free' ),
							'post-tabs'    => __( 'Post', 'wp-expand-tabs-free' ),
						),
						'default' => 'content-tabs',
					),
					// Content Tabs.
					array(
						'id'                     => 'sptpro_content_source',
						'type'                   => 'group',
						'title'                  => __( 'Tabs Content', 'wp-expand-tabs-free' ),
						'button_title'           => __( '+ Add New Tab', 'wp-expand-tabs-free' ),
						'class'                  => 'sp-tab__content_wrapper',
						'accordion_title_prefix' => __( 'Item :', 'wp-expand-tabs-free' ),
						'accordion_title_number' => true,
						'accordion_title_auto'   => true,
						'sanitize'               => 'wptabspro_sanitize_tab_title_content',
						'fields'                 => array(
							array(
								'id'         => 'tabs_content_title',
								'class'      => 'tabs_content-title',
								'type'       => 'text',
								'wrap_class' => 'sp-tab__content_source',
								'title'      => __( 'Tab Name', 'wp-expand-tabs-free' ),
							),
							array(
								'id'         => 'tabs_linking',
								'type'       => 'checkbox',
								'wrap_class' => 'sp-tab__content_source',
								'title'      => __( 'Make it Deep-Linking', 'wp-expand-tabs-free' ),
								'title_help' => sprintf(
									'<div class="wptabspro-info-label">%s</div><div class="wptabspro-short-content">%s</div><a class="wptabspro-open-docs" href="https://wptabs.com/docs/how-to-make-a-tab-deep-link/" target="_blank">%s</a><a class="wptabspro-open-live-demo" href="https://wptabs.com/deep-linking/" target="_blank">%s</a>',
									__( 'Make it Deep-Linking (Pro)', 'wp-expand-tabs-free' ),
									__( 'Check to enable the ability to associate a direct link or URL with a specific tab', 'wp-expand-tabs-free' ),
									__( 'Open Docs', 'wp-expand-tabs-free' ),
									__( 'Live Demo', 'wp-expand-tabs-free' )
								),
								'default'    => false,
								'class'      => 'sp-tab__tab-linking',
							),
							array(
								'id'      => 'tabs_content_type',
								'class'   => 'sp_tabs_content_type pro-preset-style',
								'type'    => 'image_select',
								'title'   => __( 'Tab Content Type', 'wp-expand-tabs-free' ),
								'options' => array(
									'text'       => array(
										'icon'        => 'icon-content',
										'option_name' => __( 'Content', 'wp-expand-tabs-free' ),
										'class'       => 'free-feature',
									),
									'horizontal' => array(
										'image' => Constants::product_tabs_url() . 'Admin/Assets/img/pro-presets/wp-tab-types.svg',
										'class' => 'pro-feature',
									),
								),
								'default' => 'text',
							),
							array(
								'id'         => 'tabs_content_description',
								'type'       => 'wp_editor',
								'wrap_class' => 'sp-tab__content_source',
								'title'      => __( 'Description', 'wp-expand-tabs-free' ),
								'height'     => '150px',
							),
						),
						'dependency'             => array( 'sptpro_tab_type', '==', 'content-tabs' ),
					), // End of Content Tabs.
					// Post Tabs.
					array(
						'id'         => 'sptpro_post_type',
						'type'       => 'select',
						'title'      => __( 'Post Type', 'wp-expand-tabs-free' ),
						'options'    => 'post_type',
						'class'      => 'sp-tab__post_type',
						'attributes' => array(
							'placeholder' => __( 'Select Post Type', 'wp-expand-tabs-free' ),
							'style'       => 'min-width: 150px;',
						),
						'default'    => 'post',
						'dependency' => array( 'sptpro_tab_type', '==', 'post-tabs' ),
					),
					array(
						'id'         => 'sptpro_display_posts_from',
						'type'       => 'select',
						'title'      => __( 'Filter Posts', 'wp-expand-tabs-free' ),
						'options'    => array(
							'latest'        => __( 'Latest', 'wp-expand-tabs-free' ),
							'taxonomy'      => __( 'Taxonomy (Pro)', 'wp-expand-tabs-free' ),
							'specific_post' => __( 'Specific Posts (Pro)', 'wp-expand-tabs-free' ),
						),
						'default'    => 'latest',
						'class'      => 'chosen sptpro-tabs-pro-select',
						'dependency' => array( 'sptpro_tab_type', '==', 'post-tabs' ),
					),
					array(
						'id'         => 'post_order_by',
						'type'       => 'select',
						'title'      => __( 'Order by', 'wp-expand-tabs-free' ),
						'options'    => array(
							'ID'         => __( 'ID', 'wp-expand-tabs-free' ),
							'date'       => __( 'Date', 'wp-expand-tabs-free' ),
							'rand'       => __( 'Random', 'wp-expand-tabs-free' ),
							'title'      => __( 'Title', 'wp-expand-tabs-free' ),
							'modified'   => __( 'Modified', 'wp-expand-tabs-free' ),
							'menu_order' => __( 'Menu Order', 'wp-expand-tabs-free' ),
							'post__in'   => __( 'Drag & Drop', 'wp-expand-tabs-free' ),
						),
						'default'    => 'date',
						'dependency' => array( 'sptpro_tab_type', '==', 'post-tabs' ),
					),
					array(
						'id'         => 'post_order',
						'type'       => 'select',
						'title'      => __( 'Order', 'wp-expand-tabs-free' ),
						'options'    => array(
							'ASC'  => __( 'Ascending', 'wp-expand-tabs-free' ),
							'DESC' => __( 'Descending', 'wp-expand-tabs-free' ),
						),
						'default'    => 'DESC',
						'dependency' => array( 'sptpro_tab_type', '==', 'post-tabs' ),
					),
					array(
						'id'         => 'number_of_total_posts',
						'type'       => 'spinner',
						'title'      => __( 'Limit', 'wp-expand-tabs-free' ),
						'class'      => 'number-of-total-posts',
						'title_help' => __( 'Number of total posts to display. Default value is 6.', 'wp-expand-tabs-free' ),
						'default'    => '6',
						'min'        => 1,
						'max'        => 1000,
						'dependency' => array( 'sptpro_tab_type', '==', 'post-tabs' ),
					),
				), // End of fields array.
			)
		);
	}
}
