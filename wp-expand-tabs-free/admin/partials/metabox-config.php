<?php
/**
 * Metabox config file.
 *
 * @link http://shapedplugin.com
 * @since 2.0.0
 *
 * @package wp-expand-tabs-free
 * @subpackage wp-expand-tabs-free/admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access pages directly.

//
// Metabox of the content source settings section.
// Set a unique slug-like ID.
//
$sptpro_content_source_settings = 'sp_tab_source_options';

/**
 * Preview metabox.
 *
 * @param string $prefix The metabox main Key.
 * @return void
 */
SP_WP_TABS::createMetabox(
	'sp_tab_live_preview',
	array(
		'title'        => __( 'Live Preview', 'wp-expand-tabs-free' ),
		'post_type'    => 'sp_wp_tabs',
		'show_restore' => false,
		'context'      => 'normal',
	)
);
SP_WP_TABS::createSection(
	'sp_tab_live_preview',
	array(
		'fields' => array(
			array(
				'type' => 'preview',
			),
		),
	)
);

//
// Create a metabox for content source settings.
//
SP_WP_TABS::createMetabox(
	$sptpro_content_source_settings,
	array(
		'title'     => __( 'Smart Tabs', 'wp-expand-tabs-free' ),
		'post_type' => 'sp_wp_tabs',
		'context'   => 'normal',
	)
);

//
// Create a section for content source settings.
//
SP_WP_TABS::createSection(
	$sptpro_content_source_settings,
	array(
		'fields' => array(
			array(
				'type'    => 'heading',
				'image'   => plugin_dir_url( __DIR__ ) . 'img/smart-group-tabs-logo.svg',
				'after'   => '<i class="fa fa-life-ring"></i> Support',
				'link'    => 'https://shapedplugin.com/support/?user=lite',
				'class'   => 'sp-tab__admin-header',
				'version' => WP_TABS_VERSION,
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
					// array(
					// 'id'           => 'tabs_content_icon',
					// 'type'         => 'media',
					// 'class'        => 'tabs-custom-icon-pro',
					// 'library'      => 'image',
					// 'url'          => false,
					// 'desc'         => sprintf(
					// * translators: 1: opening anchor tag with Pro link, 2: closing anchor tag. */
					// __( 'This feature is %1$savailable in Pro!%2$s', 'wp-expand-tabs-free' ),
					// '<a href="' . esc_url( SP_SMART_TABS_PRO_LINK ) . '" target="_blank"><strong>',
					// '</strong></a>'
					// ),
					// 'button_title' => __( '+ Tab Icon', 'wp-expand-tabs-free' ),
					// ),
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
								'image' => WP_TABS_URL . '/admin/img/pro-presets/wp-tab-types.svg',
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

//
// Metabox for the tabs.
// Set a unique slug-like ID.
//
$sptpro_shortcode_settings = 'sp_tab_shortcode_options';

//
// Create a metabox.
//
SP_WP_TABS::createMetabox(
	$sptpro_shortcode_settings,
	array(
		'title'     => __( 'Shortcode Section', 'wp-expand-tabs-free' ),
		'post_type' => 'sp_wp_tabs',
		'context'   => 'normal',
		'theme'     => 'light',
	)
);

//
// Create a section for tabs settings.
//
SP_WP_TABS::createSection(
	$sptpro_shortcode_settings,
	array(
		'title'  => __( 'Tabs Settings', 'wp-expand-tabs-free' ),
		'icon'   => 'fa icon-general-icon',
		'fields' => array(
			array(
				'id'         => 'sptpro_tabs_layout',
				'type'       => 'image_select',
				'class'      => 'sp_wp_tabs_layout pro-preset-style',
				'title'      => __( 'Tabs Layout', 'wp-expand-tabs-free' ),
				'sanitize'   => 'sanitize_text_field',
				'title_help' => sprintf(
					'<div class="wptabspro-info-label">%s</div><div class="wptabspro-short-content">%s</div><a class="wptabspro-open-docs" href="https://wptabs.com/docs/tabs-settings/" target="_blank">%s</a><a class="wptabspro-open-live-demo" href="https://wptabs.com/tabs-layout/" target="_blank">%s</a>',
					__( 'Tabs Layout', 'wp-expand-tabs-free' ),
					__( 'Choose a layout from five individual layout styles to customize how your tabs are displayed in the frontend.', 'wp-expand-tabs-free' ),
					__( 'Open Docs', 'wp-expand-tabs-free' ),
					__( 'Live Demo', 'wp-expand-tabs-free' )
				),
				'options'    => array(
					'horizontal'        => array(
						'image'           => WP_TABS_URL . '/admin/img/tabs-layout/horizontal-top.svg',
						'option_name'     => __( 'Horizontal Top', 'wp-expand-tabs-free' ),
						'class'           => 'free-feature',
						'option_demo_url' => 'https://wptabs.com/horizontal-tabs/',
					),
					'horizontal-bottom' => array(
						'image'           => WP_TABS_URL . '/admin/img/tabs-layout/horizontal-bottom.svg',
						'option_name'     => __( 'Horizontal Bottom', 'wp-expand-tabs-free' ),
						'class'           => 'free-feature',
						'option_demo_url' => 'https://wptabs.com/horizontal-tabs/',
					),
					'vertical-right'    => array(
						'image'           => WP_TABS_URL . '/admin/img/tabs-layout/vertical-right.svg',
						'option_name'     => __( 'Vertical Right', 'wp-expand-tabs-free' ),
						'option_demo_url' => 'https://wptabs.com/vertical-tabs/#vertical-right',
					),
					'vertical'          => array(
						'image'           => WP_TABS_URL . '/admin/img/tabs-layout/vertical-left.svg',
						'option_name'     => __( 'Vertical Left', 'wp-expand-tabs-free' ),
						'class'           => 'pro-feature',
						'option_demo_url' => 'https://demo.wptabs.com/vertical-tabs/',
					),
					'tabs-carousel'     => array(
						'image'           => WP_TABS_URL . '/admin/img/tabs-layout/tabs-carousel.svg',
						'option_name'     => __( 'Tabs Carousel', 'wp-expand-tabs-free' ),
						'class'           => 'pro-feature',
						'option_demo_url' => 'https://wptabs.com/responsive-scrollable-tabs-2/',
					),
				),
				'radio'      => true,
				'default'    => 'horizontal',
			),
			array(
				'id'         => 'sptpro_tabs_horizontal_alignment',
				'type'       => 'image_select',
				'class'      => 'sptpro_tabs_horizontal_alignment',
				'title'      => __( 'Tabs Alignment', 'wp-expand-tabs-free' ),
				'sanitize'   => 'sanitize_text_field',
				'title_help' => sprintf(
					'<div class="wptabspro-info-label">%s</div><div class="wptabspro-short-content">%s</div><a class="wptabspro-open-docs" href="https://wptabs.com/docs/tabs-settings/" target="_blank">%s</a><a class="wptabspro-open-live-demo" href="https://wptabs.com/tabs-position-alignment/" target="_blank">%s</a>',
					__( 'Tabs Alignment', 'wp-expand-tabs-free' ),
					__( 'Choose where you want your tabs to appear – at the top, right, bottom, or left of your content, allowing you to customize their position to best suit your layout and design.', 'wp-expand-tabs-free' ),
					__( 'Open Docs', 'wp-expand-tabs-free' ),
					__( 'Live Demo', 'wp-expand-tabs-free' )
				),
				'options'    => array(
					'tab-horizontal-alignment-left'      => array(
						'image'       => WP_TABS_URL . '/admin/img/tabs-alignment/horizontal-left.svg',
						'option_name' => __( 'Left', 'wp-expand-tabs-free' ),
						'class'       => 'free-feature',
					),
					'tab-horizontal-alignment-right'     => array(
						'image'       => WP_TABS_URL . '/admin/img/tabs-alignment/horizontal-right.svg',
						'option_name' => __( 'Right', 'wp-expand-tabs-free' ),
						'class'       => 'free-feature',
					),
					'tab-horizontal-alignment-center'    => array(
						'image'       => WP_TABS_URL . '/admin/img/tabs-alignment/horizontal-center.svg',
						'option_name' => __( 'Center', 'wp-expand-tabs-free' ),
						'class'       => 'free-feature',
					),
					'tab-horizontal-alignment-justified' => array(
						'image'       => WP_TABS_URL . '/admin/img/tabs-alignment/horizontal-justified.svg',
						'option_name' => __( 'Justified', 'wp-expand-tabs-free' ),
						'class'       => 'free-feature',
					),
				),
				'default'    => 'tab-horizontal-alignment-left',
				'dependency' => array( 'sptpro_tabs_layout', '!=', 'vertical-right', true ),
			),
			array(
				'id'         => 'sptpro_tabs_vertical_alignment',
				'type'       => 'image_select',
				'class'      => 'sptpro_tabs_vertical_alignment',
				'title'      => __( 'Tabs Alignment', 'wp-expand-tabs-free' ),
				'title_help' => sprintf(
					'<div class="wptabspro-info-label">%s</div><div class="wptabspro-short-content">%s</div><a class="wptabspro-open-docs" href="https://wptabs.com/docs/tabs-settings/" target="_blank">%s</a><a class="wptabspro-open-live-demo" href="https://wptabs.com/tabs-position-alignment/" target="_blank">%s</a>',
					__( 'Tabs Alignment', 'wp-expand-tabs-free' ),
					__( 'Choose where you want your tabs to appear – at the top, right, bottom, or left of your content, allowing you to customize their position to best suit your layout and design.', 'wp-expand-tabs-free' ),
					__( 'Open Docs', 'wp-expand-tabs-free' ),
					__( 'Live Demo', 'wp-expand-tabs-free' )
				),
				'options'    => array(
					'tab-vertical-alignment-top'       => array(
						'image'       => WP_TABS_URL . '/admin/img/tabs-alignment/vertical-right/vertical-top.svg',
						'option_name' => __( 'Top', 'wp-expand-tabs-free' ),
					),
					'tab-vertical-alignment-middle'    => array(
						'image'       => WP_TABS_URL . '/admin/img/tabs-alignment/vertical-right/vertical-center.svg',
						'option_name' => __( 'Center', 'wp-expand-tabs-free' ),
					),
					'tab-vertical-alignment-bottom'    => array(
						'image'       => WP_TABS_URL . '/admin/img/tabs-alignment/vertical-right/vertical-bottom.svg',
						'option_name' => __( 'Bottom', 'wp-expand-tabs-free' ),
					),
					'tab-vertical-alignment-justified' => array(
						'image'       => WP_TABS_URL . '/admin/img/tabs-alignment/vertical-right/vertical-justified.svg',
						'option_name' => __( 'Justified', 'wp-expand-tabs-free' ),
					),
				),
				'default'    => 'tab-vertical-alignment-top',
				'dependency' => array( 'sptpro_tabs_layout', '==', 'vertical-right', true ),
			),
			array(
				'id'         => 'sptpro_tabs_activator_event',
				'type'       => 'radio',
				'class'      => 'only_for_pro_event',
				'title'      => __( 'Activator Event', 'wp-expand-tabs-free' ),
				'sanitize'   => 'sanitize_text_field',
				'title_help' => sprintf(
					'<div class="wptabspro-info-label">%s</div><div class="wptabspro-short-content">%s</div><a class="wptabspro-open-docs" href="https://wptabs.com/docs/tabs-settings/" target="_blank">%s</a><a class="wptabspro-open-live-demo" href="https://wptabs.com/activator-events/" target="_blank">%s</a>',
					__( 'Activator Event', 'wp-expand-tabs-free' ),
					__( 'Set an event to switch between tabs with AutoPlay (Pro), On Click, or Mouse Hover.', 'wp-expand-tabs-free' ),
					__( 'Open Docs', 'wp-expand-tabs-free' ),
					__( 'Live Demo', 'wp-expand-tabs-free' )
				),
				'options'    => array(
					'tabs-activator-event-click' => __( 'On Click', 'wp-expand-tabs-free' ),
					'tabs-activator-event-hover' => __( 'Mouseover', 'wp-expand-tabs-free' ),
				),
				'default'    => 'tabs-activator-event-click',
			),
			array(
				'id'              => 'sptpro_margin_between_tabs',
				'type'            => 'spacing',
				'title'           => __( 'Space Between Tabs', 'wp-expand-tabs-free' ),
				'title_help'      => '<div class="wptabspro-img-tag"><img src="' . plugin_dir_url( __DIR__ ) . 'partials/models/assets/images/help-visuals/margin-between-tabs.svg" alt="' . __( 'Margin between Tabs', 'wp-expand-tabs-free' ) . '"></div><div class="wptabspro-info-label">' . __( 'Margin between Tabs', 'wp-expand-tabs-free' ) . '</div>',
				'all'             => true,
				'all_icon'        => '<i class="fa fa-arrows-h"></i>',
				'all_placeholder' => 'margin',
				'sanitize'        => 'wptabspro_sanitize_number_array_field',
				'default'         => array(
					'all' => '10',
				),
				'units'           => array(
					'px',
				),
			),
			array(
				'id'         => 'sptpro_anchor_linking',
				'type'       => 'switcher',
				'title'      => __( 'Anchor Link for Tabs', 'wp-expand-tabs-free' ),
				'text_on'    => __( 'Enabled', 'wp-expand-tabs-free' ),
				'text_off'   => __( 'Disabled', 'wp-expand-tabs-free' ),
				'text_width' => 94,
				'default'    => true,
			),
			array(
				'id'         => 'sptpro_tab_link_type',
				'type'       => 'button_set',
				'title'      => __( 'Link Type ', 'wp-expand-tabs-free' ),
				'title_help' => sprintf(
					'<div class="wptabspro-info-label">%s</div>
					<div class="wptabspro-short-content">%s <br/>%s</div>',
					__( 'Link Type', 'wp-expand-tabs-free' ),
					__( 'Tab ID - Adds the tab\'s unique ID to the URL. i.e. https://example.com/#tab-id', 'wp-expand-tabs-free' ),
					__( 'Tab Title - Adds a URL-friendly version of the tab title (slug) to the URL. i.e. https://example.com/#tab-title-xx', 'wp-expand-tabs-free' ),
				),
				'options'    => array(
					'tab_id'    => __( 'Tab ID', 'wp-expand-tabs-free' ),
					'tab_title' => __( 'Tab Title', 'wp-expand-tabs-free' ),
				),
				'default'    => 'tab_id',
				'dependency' => array( 'sptpro_anchor_linking', '==', 'true', true ),
			),
			array(
				'id'         => 'sptpro_preloader',
				'type'       => 'switcher',
				'title'      => __( 'Preloader', 'wp-expand-tabs-free' ),
				'text_on'    => __( 'Enabled', 'wp-expand-tabs-free' ),
				'text_off'   => __( 'Disabled', 'wp-expand-tabs-free' ),
				'text_width' => 94,
				'default'    => true,
			),

			array(
				'id'         => 'sptpro_fixed_height',
				'type'       => 'button_set',
				'class'      => 'only_pro_fixed_height',
				'ignore_db'  => true,
				'title'      => __( 'Content Height', 'wp-expand-tabs-free' ),
				'title_help' => '<div class="wptabspro-img-tag"><img src="' . plugin_dir_url( __DIR__ ) . 'partials/models/assets/images/help-visuals/content-height.svg" alt="' . __( 'Content Height', 'wp-expand-tabs-free' ) . '"></div><div class="wptabspro-info-label"> ' . __( 'Content Height', 'wp-expand-tabs-free' ) . '</div><a class="wptabspro-open-docs" href="https://wptabs.com/docs/how-to-set-tabs-content-height/" target="_blank">' . __( 'Open Docs', 'wp-expand-tabs-free' ) . '</a><a class="wptabspro-open-live-demo" href="https://wptabs.com/tabs-fixed-content-height/" target="_blank">' . __( 'Live Demo', 'wp-expand-tabs-free' ) . '</a>',
				'options'    => array(
					'auto'   => __( 'Auto', 'wp-expand-tabs-free' ),
					'custom' => __( 'Custom', 'wp-expand-tabs-free' ),
				),
				'default'    => 'auto',
			),
			array(
				'id'         => 'sptpro_tab_opened',
				'type'       => 'spinner',
				'ignore_db'  => true,
				'class'      => 'only_pro_spinner',
				'title'      => __( 'Initial Open Tab', 'wp-expand-tabs-free' ),
				'title_help' => '<div class="wptabspro-img-tag"><img src="' . plugin_dir_url( __DIR__ ) . 'partials/models/assets/images/help-visuals/initial-tab-opened.svg" alt="' . __( 'Initial Open Tab', 'wp-expand-tabs-free' ) . '"></div><div class="wptabspro-info-label">' . __( 'Initial Open Tab', 'wp-expand-tabs-free' ) . '</div>',
				'sanitize'   => 'wptabspro_sanitize_number_field',
				'min'        => 1,
				'default'    => 1,
			),
		), // Fields array end.
	)
); // End of tabs settings.

//
// Carousel settings section begin.
//
SP_WP_TABS::createSection(
	$sptpro_shortcode_settings,
	array(
		'title'  => __( 'Display Settings', 'wp-expand-tabs-free' ),
		'icon'   => 'fa icon-display',
		'fields' => array(
			array(
				'type' => 'tabbed',
				'tabs' => array(
					array(
						'title'  => __( 'Tabs Name & Description', 'wp-expand-tabs-free' ),
						'icon'   => '<i class="fa icon-title_des"></i>',
						'fields' => array(
							array(
								'id'         => 'sptpro_section_title',
								'type'       => 'switcher',
								'title'      => __( 'Tabs Section Title', 'wp-expand-tabs-free' ),
								'default'    => true,
								'text_on'    => __( 'Show', 'wp-expand-tabs-free' ),
								'text_off'   => __( 'Hide', 'wp-expand-tabs-free' ),
								'text_width' => 75,
							),
							array(
								'id'      => 'sptpro_title_heading_tag',
								'type'    => 'select',
								'class'   => 'vertical-gap',
								'title'   => __( 'Tab Name HTML Tag', 'wp-expand-tabs-free' ),
								'options' => array(
									'H1' => 'H1',
									'H2' => 'H2',
									'H3' => 'H3',
									'H4' => 'H4',
									'H5' => 'H5',
									'H6' => 'H6',
								),
								'default' => 'H4',
								'radio'   => true,
							),
							array(
								'id'         => 'sptpro_tabs_bg_color_type',
								'type'       => 'button_set',
								'class'      => 'sptpro_tabs_bg_color_type',
								'title'      => __( 'Tab Name BG Color Type', 'wp-expand-tabs-free' ),
								'title_help' => '<div class="wptabspro-img-tag"><img src="' . plugin_dir_url( __DIR__ ) . 'partials/models/assets/images/help-visuals/title-background-color-type.svg" alt="' . __( 'Title Background Color Type', 'wp-expand-tabs-free' ) . '"></div><div class="wptabspro-info-label">' . __( 'Title Background Color Type', 'wp-expand-tabs-free' ) . '</div><a class="wptabspro-open-docs" href="https://docs.shapedplugin.com/docs/wp-expand-tabs-free/configurations/how-to-set-tabs-title-background-color/" target="_blank">' . __( 'Open Docs', 'wp-expand-tabs-free' ) . '</a><a class="wptabspro-open-live-demo" href="https://wptabs.com/title-background-color/" target="_blank">' . __( 'Live Demo', 'wp-expand-tabs-free' ) . '</a>',
								'options'    => array(
									'solid'    => __( 'Solid', 'wp-expand-tabs-free' ),
									'gradient' => __( 'Gradient', 'wp-expand-tabs-free' ),
								),
								'default'    => 'solid',
							),
							array(
								'id'         => 'sptpro_title_bg_color',
								'type'       => 'color_group',
								'title'      => __( 'Tab Name BG Color', 'wp-expand-tabs-free' ),
								'sanitize'   => 'wptabspro_sanitize_color_group_field',
								'options'    => array(
									'title-bg-color'       => __( 'Background', 'wp-expand-tabs-free' ),
									'title-active-bg-color' => __( 'Active BG', 'wp-expand-tabs-free' ),
									'title-hover-bg-color' => __( 'Hover BG', 'wp-expand-tabs-free' ),
								),
								'default'    => array(
									'title-bg-color'       => '#eee',
									'title-active-bg-color' => '#fff',
									'title-hover-bg-color' => '#fff',
								),
								'dependency' => array( 'sptpro_tabs_bg_color_type', '==', 'solid' ),
							),
							array(
								'id'                    => 'sptpro_tabs_bg_gradient_color',
								'type'                  => 'background',
								'title'                 => __( 'Gradient Color', 'wp-expand-tabs-free' ),
								'background_gradient'   => true,
								'background_color'      => true,
								'background_image'      => false,
								'background_position'   => false,
								'background_repeat'     => false,
								'background_attachment' => false,
								'background_size'       => false,
								'default'               => array(
									'background-color' => '#fff',
									'background-gradient-color' => '#eee',
									'background-gradient-direction' => '135deg',
								),
								'dependency'            => array( 'sptpro_tabs_bg_color_type', '==', 'gradient' ),
							),
							array(
								'id'         => 'sptpro_title_padding',
								'type'       => 'spacing',
								'title'      => __( 'Tab Name Padding', 'wp-expand-tabs-free' ),
								'sanitize'   => 'wptabspro_sanitize_number_array_field',
								'title_help' => '<div class="wptabspro-img-tag"><img src="' . plugin_dir_url( __DIR__ ) . 'partials/models/assets/images/help-visuals/title-padding.svg" alt="' . __( 'Title Padding', 'wp-expand-tabs-free' ) . '"></div><div class="wptabspro-info-label">' . __( 'Title Padding', 'wp-expand-tabs-free' ) . '</div>',
								'units'      => array( 'px' ),
								'default'    => array(
									'left'   => '15',
									'top'    => '15',
									'bottom' => '15',
									'right'  => '15',
								),
							),
							array(
								'id'            => 'sptpro_tabs_border',
								'type'          => 'border',
								'class'         => 'sptpro_tabs_border',
								'title'         => __( 'Border', 'wp-expand-tabs-free' ),
								'all'           => true,
								'border_radius' => true,
								'sanitize'      => 'wptabspro_sanitize_border_field',
								'default'       => array(
									'all'           => 1,
									'style'         => 'solid',
									'color'         => '#cccccc',
									'border_radius' => '2',
								),
							),
							array(
								'id'      => 'sptpro_desc_bg_color',
								'type'    => 'color',
								'title'   => __( 'Description Background Color', 'wp-expand-tabs-free' ),
								'default' => '#ffffff',
							),
							array(
								'id'         => 'sptpro_desc_padding',
								'type'       => 'spacing',
								'title'      => __( 'Description Padding', 'wp-expand-tabs-free' ),
								'title_help' => '<div class="wptabspro-img-tag"><img src="' . plugin_dir_url( __DIR__ ) . 'partials/models/assets/images/help-visuals/description-padding.svg" alt="' . __( 'Description Padding', 'wp-expand-tabs-free' ) . '"></div><div class="wptabspro-info-label"> ' . __( 'Description Padding', 'wp-expand-tabs-free' ) . '</div>',
								'units'      => array( 'px' ),
								'default'    => array(
									'left'   => '30',
									'top'    => '20',
									'bottom' => '20',
									'right'  => '20',
								),
							),
							array(
								'id'      => 'sptpro_desc_border',
								'type'    => 'border',
								'title'   => __( 'Description Border', 'wp-expand-tabs-free' ),
								'all'     => true,
								'style'   => true,
								'default' => array(
									'all'   => 1,
									'style' => 'solid',
									'color' => '#cccccc',
								),
							),
							array(
								'type'    => 'notice',
								'class'   => 'middle_notice smart-tabs-notice',
								'content' => sprintf(
									/* translators: 1: start bold tag 2: close bold tag 3: start link and bold tag, 4: close tag. */
									__( 'Unlock additional settings for Tab Name & Description — %1$sUpgrade to Pro!%2$s', 'wp-expand-tabs-free' ),
									'<a href="' . esc_url( SP_SMART_TABS_PRO_LINK ) . '" target="_blank"><b>',
									'</b></a>'
								),
							),
							array(
								'id'         => 'sptpro_showhide_subtitle',
								'type'       => 'switcher',
								'ignore_db'  => true,
								'class'      => 'only_pro_switcher vertical-gap',
								'title'      => __( 'Tabs Subtitle', 'wp-expand-tabs-free' ),
								'default'    => false,
								'text_on'    => __( 'Show', 'wp-expand-tabs-free' ),
								'text_off'   => __( 'Hide', 'wp-expand-tabs-free' ),
								'text_width' => 75,
							),
							array(
								'id'         => 'sptpro_active_indicator_arrow',
								'type'       => 'switcher',
								'ignore_db'  => true,
								'class'      => 'only_pro_switcher vertical-gap',
								'title'      => __( 'Active Tab Indicator Arrow', 'wp-expand-tabs-free' ),
								'title_help' => '<div class="wptabspro-img-tag"><img src="' . plugin_dir_url( __DIR__ ) . 'partials/models/assets/images/help-visuals/active-tab-indicator-arrow.svg" alt="' . __( 'Active Tab Indicator Arrow (Pro)', 'wp-expand-tabs-free' ) . '"></div><div class="wptabspro-info-label">' . __( 'Active Tab Indicator Arrow (Pro)', 'wp-expand-tabs-free' ) . '</div><a class="wptabspro-open-docs" href="https://wptabs.com/docs/how-to-configure-an-active-tab-indicator-arrow/" target="_blank">' . __( 'Open Docs', 'wp-expand-tabs-free' ) . '</a><a class="wptabspro-open-live-demo" href="https://wptabs.com/active-tab-indicator-arrow/" target="_blank">' . __( 'Live Demo', 'wp-expand-tabs-free' ) . '</a>',
								'default'    => false,
								'text_on'    => __( 'Show', 'wp-expand-tabs-free' ),
								'text_off'   => __( 'Hide', 'wp-expand-tabs-free' ),
								'text_width' => 75,
							),
							array(
								'id'              => 'sptpro_margin_between_tabs_and_desc',
								'type'            => 'spacing',
								'bottom_icon'     => '<i class="fa fa-arrows-v"></i>',
								'ignore_db'       => true,
								'class'           => 'only_pro_spinner',
								'title'           => __( 'Space Between Tabs and Description', 'wp-expand-tabs-free' ),
								'title_help'      => '<div class="wptabspro-img-tag"><img src="' . plugin_dir_url( __DIR__ ) . 'partials/models/assets/images/help-visuals/margin-between-tabs-and-description.svg" alt="' . __( 'Space Between Tabs and Description', 'wp-expand-tabs-free' ) . '"></div>',
								'all_placeholder' => 'margin',
								'bottom_text'     => '',
								'top'             => false,
								'left'            => false,
								'bottom'          => true,
								'right'           => false,
								'default'         => array(
									'bottom' => '0',
								),
								'units'           => array(
									'px',
								),
							),
							array(
								'id'         => 'sptpro_active_tab_style_horizontal',
								'type'       => 'image_select',
								'ignore_db'  => true,
								'class'      => 'only_pro_tabs_icon_section common-size',
								'title'      => __( 'Active Tab Style', 'wp-expand-tabs-free' ),
								'title_help' => sprintf(
									'<div class="wptabspro-info-label">%s</div><div class="wptabspro-short-content">%s</div><a class="wptabspro-open-docs" href="https://wptabs.com/docs/how-to-set-and-customize-active-tab-style/" target="_blank">%s</a><a class="wptabspro-open-live-demo" href="https://wptabs.com/active-tab-style/" target="_blank">%s</a>',
									__( 'Active Tab Style (Pro)', 'wp-expand-tabs-free' ),
									__( 'Choose how the currently selected tab looks. You can add a line to the Top or Bottom of tab\'s title to make it stand out.', 'wp-expand-tabs-free' ),
									__( 'Open Docs', 'wp-expand-tabs-free' ),
									__( 'Live Demo', 'wp-expand-tabs-free' )
								),
								'options'    => array(
									'horizontal-active-tab-normal'      => array(
										'image'       => WP_TABS_URL . '/admin/img/active-tab-style/horizontal-active-tab-normal.svg',
										'option_name' => __( 'Normal', 'wp-expand-tabs-free' ),
									),
									'horizontal-active-tab-top-line'    => array(
										'image'       => WP_TABS_URL . '/admin/img/active-tab-style/horizontal-active-tab-top-line.svg',
										'option_name' => __( 'Top', 'wp-expand-tabs-free' ),
									),
									'horizontal-active-tab-bottom-line' => array(
										'image'       => WP_TABS_URL . '/admin/img/active-tab-style/horizontal-active-tab-bottom-line.svg',
										'option_name' => __( 'Bottom', 'wp-expand-tabs-free' ),
									),
								),
								'radio'      => true,
								'default'    => 'horizontal-active-tab-normal',
							),
							array(
								'id'         => 'sptpro_flat_tab_style_horizontal',
								'type'       => 'image_select',
								'ignore_db'  => true,
								'class'      => 'only_pro_tabs_icon_section common-size',
								'title'      => __( 'Flat Tab Style', 'wp-expand-tabs-free' ),
								'title_help' => sprintf(
									'<div class="wptabspro-info-label">%s</div><div class="wptabspro-short-content">%s</div><a class="wptabspro-open-docs" href="https://wptabs.com/docs/how-to-make-a-tab-flat-style/" target="_blank">%s</a><a class="wptabspro-open-live-demo" href="https://wptabs.com/flat-contained-tabs/" target="_blank">%s</a>',
									__( 'Flat Tab Style (Pro)', 'wp-expand-tabs-free' ),
									__( 'Select the Underline option to enhance your tabs with flat underline positioned below the tab navigation for a clean and modern look.', 'wp-expand-tabs-free' ),
									__( 'Open Docs', 'wp-expand-tabs-free' ),
									__( 'Live Demo', 'wp-expand-tabs-free' )
								),
								'options'    => array(
									'horizontal-flat-tab-normal'    => array(
										'image'       => WP_TABS_URL . '/admin/img/flat-tab-style/horizontal-flat-tab-normal.svg',
										'option_name' => __( 'Normal', 'wp-expand-tabs-free' ),
									),
									'horizontal-flat-tab-underline' => array(
										'image'       => WP_TABS_URL . '/admin/img/flat-tab-style/horizontal-flat-tab-underline.svg',
										'option_name' => __( 'Underline', 'wp-expand-tabs-free' ),
									),
								),
								'radio'      => true,
								'default'    => 'horizontal-flat-tab-normal',
							),
						),
					),
					array(
						'title'  => __( 'Responsive Screen', 'wp-expand-tabs-free' ),
						'icon'   => '<i class="fa icon-responsive"></i>',
						'fields' => array(
							array(
								'id'              => 'sptpro_set_small_screen',
								'type'            => 'spacing',
								'title'           => __( 'When Screen Width is Less Than', 'wp-expand-tabs-free' ),
								'all'             => true,
								'all_icon'        => '<i class="fa fa-arrows-h"></i>',
								'all_placeholder' => 'margin',
								'sanitize'        => 'wptabspro_sanitize_number_array_field',
								'default'         => array(
									'all' => '480',
								),
								'units'           => array(
									'px',
								),
							),
							array(
								'id'         => 'sptpro_tabs_on_small_screen',
								'type'       => 'radio',
								'title'      => __( 'Tabs Mode on Small Screen', 'wp-expand-tabs-free' ),
								'title_help' => sprintf(
									'<div class="wptabspro-info-label">%s</div><div class="wptabspro-short-content">%s</div><a class="wptabspro-open-docs" href="https://wptabs.com/docs/how-to-configure-tabs-mood-on-a-small-screen/" target="_blank">%s</a><a class="wptabspro-open-live-demo" href="https://wptabs.com/docs/style/display-settings/" target="_blank">%s</a>',
									__( 'Tabs Mode on Small Screen', 'wp-expand-tabs-free' ),
									__( 'Choose how your tabs behave on small screens, such as mobile devices. You can select "Full Width" to maintain the current layout or "Accordion" to switch to a collapsible format, ensuring the best user experience on mobile.', 'wp-expand-tabs-free' ),
									__( 'Open Docs', 'wp-expand-tabs-free' ),
									__( 'Live Demo', 'wp-expand-tabs-free' )
								),
								'options'    => array(
									'full_widht'     => __( 'Full Width', 'wp-expand-tabs-free' ),
									'accordion_mode' => __( 'Accordion', 'wp-expand-tabs-free' ),
								),
								'default'    => 'full_widht',
							),
							array(
								'id'         => 'sptpro_expand_and_collapse_icon',
								'type'       => 'switcher',
								'title'      => __( 'Expand and Collapse Icon', 'wp-expand-tabs-free' ),
								'default'    => true,
								'text_on'    => __( 'Show', 'wp-expand-tabs-free' ),
								'text_off'   => __( 'Hide', 'wp-expand-tabs-free' ),
								'text_width' => 75,
								'dependency' => array( 'sptpro_tabs_on_small_screen', '==', 'accordion_mode' ),
							),

						),
					),
					array(
						'title'  => __( 'Description Animation', 'wp-expand-tabs-free' ),
						'icon'   => '<i class="fa icon-animation"></i>',
						'fields' => array(
							array(
								'id'         => 'sptpro_tabs_animation',
								'type'       => 'switcher',
								'title'      => __( 'Animation', 'wp-expand-tabs-free' ),
								'title_help' => sprintf(
									'<div class="wptabspro-info-label">%s</div><div class="wptabspro-short-content">%s</div><a class="wptabspro-open-docs" href="https://wptabs.com/docs/how-to-configure-tab-animation/" target="_blank">%s</a><a class="wptabspro-open-live-demo" href="https://wptabs.com/tabs-animation/" target="_blank">%s</a>',
									__( 'Animation', 'wp-expand-tabs-free' ),
									__( 'You can select animation to enhance your tab with over 50+ fascinating animations to add dynamic and eye-catching effects to your content.', 'wp-expand-tabs-free' ),
									__( 'Open Docs', 'wp-expand-tabs-free' ),
									__( 'Live Demo', 'wp-expand-tabs-free' )
								),
								'text_on'    => __( 'Enabled', 'wp-expand-tabs-free' ),
								'text_off'   => __( 'Disabled', 'wp-expand-tabs-free' ),
								'text_width' => 94,
								'default'    => true,
							),
							array(
								'id'         => 'sptpro_tabs_animation_type',
								'type'       => 'select',
								'class'      => 'sptpro-tabs-pro-select',
								'title'      => __( 'Animation Style', 'wp-expand-tabs-free' ),
								'sanitize'   => 'sanitize_text_field',
								'options'    => array(
									'fadeIn'            => __( 'fadeIn', 'wp-expand-tabs-free' ),
									'fadeInDown'        => __( 'fadeInDown', 'wp-expand-tabs-free' ),
									'fadeInLeft'        => __( 'fadeInLeft (Pro)', 'wp-expand-tabs-free' ),
									'fadeInRight'       => __( 'fadeInRight (Pro)', 'wp-expand-tabs-free' ),
									'fadeInUp'          => __( 'fadeInUp (Pro)', 'wp-expand-tabs-free' ),
									'fadeInDownBig'     => __( 'fadeInDownBig (Pro)', 'wp-expand-tabs-free' ),
									'fadeInLeftBig'     => __( 'fadeInLeftBig (Pro)', 'wp-expand-tabs-free' ),
									'fadeInRightBig'    => __( 'fadeInRightBig (Pro)', 'wp-expand-tabs-free' ),
									'fadeInUpBig'       => __( 'fadeInUpBig (Pro)', 'wp-expand-tabs-free' ),
									'zoomIn'            => __( 'zoomIn (Pro)', 'wp-expand-tabs-free' ),
									'zoomInDown'        => __( 'zoomInDown (Pro)', 'wp-expand-tabs-free' ),
									'zoomInLeft'        => __( 'zoomInLeft (Pro)', 'wp-expand-tabs-free' ),
									'zoomInRight'       => __( 'zoomInRight (Pro)', 'wp-expand-tabs-free' ),
									'zoomInUp'          => __( 'zoomInUp (Pro)', 'wp-expand-tabs-free' ),
									'zoomOut'           => __( 'zoomOut (Pro)', 'wp-expand-tabs-free' ),
									'slideInDown'       => __( 'slideInDown (Pro)', 'wp-expand-tabs-free' ),
									'slideInLeft'       => __( 'slideInLeft (Pro)', 'wp-expand-tabs-free' ),
									'slideInRight'      => __( 'slideInRight (Pro)', 'wp-expand-tabs-free' ),
									'slideInUp'         => __( 'slideInUp (Pro)', 'wp-expand-tabs-free' ),
									'flip'              => __( 'flip (Pro)', 'wp-expand-tabs-free' ),
									'flipInX'           => __( 'flipInX (Pro)', 'wp-expand-tabs-free' ),
									'flipInY'           => __( 'flipInY (Pro)', 'wp-expand-tabs-free' ),
									'bounce'            => __( 'bounce (Pro)', 'wp-expand-tabs-free' ),
									'bounceIn'          => __( 'bounceIn (Pro)', 'wp-expand-tabs-free' ),
									'bounceInLeft'      => __( 'bounceInLeft (Pro)', 'wp-expand-tabs-free' ),
									'bounceInRight'     => __( 'bounceInRight (Pro)', 'wp-expand-tabs-free' ),
									'bounceInUp'        => __( 'bounceInUp (Pro)', 'wp-expand-tabs-free' ),
									'bounceInDown'      => __( 'bounceInDown (Pro)', 'wp-expand-tabs-free' ),
									'rotateIn'          => __( 'rotateIn (Pro)', 'wp-expand-tabs-free' ),
									'rotateInDownLeft'  => __( 'rotateInDownLeft (Pro)', 'wp-expand-tabs-free' ),
									'rotateInDownRight' => __( 'rotateInDownRight (Pro)', 'wp-expand-tabs-free' ),
									'rotateInUpLeft'    => __( 'rotateInUpLeft (Pro)', 'wp-expand-tabs-free' ),
									'rotateInUpRight'   => __( 'rotateInUpRight (Pro)', 'wp-expand-tabs-free' ),
									'backInDown'        => __( 'backInDown (Pro)', 'wp-expand-tabs-free' ),
									'backInLeft'        => __( 'backInLeft (Pro)', 'wp-expand-tabs-free' ),
									'flash'             => __( 'flash (Pro)', 'wp-expand-tabs-free' ),
									'pulse'             => __( 'pulse (Pro)', 'wp-expand-tabs-free' ),
									'shake'             => __( 'shake (Pro)', 'wp-expand-tabs-free' ),
									'swing'             => __( 'swing (Pro)', 'wp-expand-tabs-free' ),
									'tada'              => __( 'tada (Pro)', 'wp-expand-tabs-free' ),
									'wobble'            => __( 'wobble (Pro)', 'wp-expand-tabs-free' ),
									'rubberBand'        => __( 'rubberBand (Pro)', 'wp-expand-tabs-free' ),
									'heartBeat'         => __( 'heartBeat (Pro)', 'wp-expand-tabs-free' ),
									'jello'             => __( 'jello (Pro)', 'wp-expand-tabs-free' ),
									'headShake'         => __( 'headShake (Pro)', 'wp-expand-tabs-free' ),
									'lightSpeedIn'      => __( 'lightSpeedIn (Pro)', 'wp-expand-tabs-free' ),
									'jackInTheBox'      => __( 'jackInTheBox (Pro)', 'wp-expand-tabs-free' ),
									'rollIn'            => __( 'rollIn (Pro)', 'wp-expand-tabs-free' ),
								),
								'default'    => 'fadeIn',
								'dependency' => array( 'sptpro_tabs_animation', '==', 'true' ),
							),
							array(
								'id'         => 'sptpro_animation_time',
								'type'       => 'spinner',
								'title'      => __( 'Transition Delay', 'wp-expand-tabs-free' ),
								'unit'       => 'ms',
								'min'        => 10,
								'max'        => 100000,
								'default'    => 500,
								'dependency' => array( 'sptpro_tabs_animation', '==', 'true' ),
							),
							array(
								'type'    => 'notice',
								'class'   => 'bottom_notice smart-tabs-notice',
								'content' => sprintf(
									/* translators: 1: start strong tag 2: close strong tag 3: start link and bold tag, 4: close tag. */
									__( 'To unlock %1$s50+ elegant Tabs Animations%2$s settings, %3$sUpgrade to Pro!%4$s', 'wp-expand-tabs-free' ),
									'<strong>',
									'</strong>',
									'<a href="' . esc_url( SP_SMART_TABS_PRO_LINK ) . '" target="_blank"><b>',
									'</b></a>'
								),
							),
						),
					),
					array(
						'title'  => __( 'Tabs Icon', 'wp-expand-tabs-free' ),
						'icon'   => '<i class="fa icon-tabs_icon"></i>',
						'fields' => array(
							array(
								'type'      => 'notice',
								'ignore_db' => true,
								'class'     => 'only_pro_notice tab-icon-top-notice smart-tabs-notice',
								'content'   => sprintf(
									/* translators: 1: start link and blod tag, 2: close tag. */
									__( 'To unlock the following essential Tabs Icon options, %1$sUpgrade to Pro!%2$s', 'wp-expand-tabs-free' ),
									'<a href="' . esc_url( SP_SMART_TABS_PRO_LINK ) . '" target="_blank"><b>',
									'</b></a>'
								),
							),
							array(
								'id'         => 'sptpro_tabs_icon',
								'type'       => 'switcher',
								'ignore_db'  => true,
								'class'      => 'only_pro_switcher',
								'title'      => __( 'Tabs Icon', 'wp-expand-tabs-free' ),
								'default'    => true,
								'text_on'    => __( 'Show', 'wp-expand-tabs-free' ),
								'text_off'   => __( 'Hide', 'wp-expand-tabs-free' ),
								'text_width' => 75,
							),
							array(
								'id'              => 'sptpro_tab_icon_size',
								'type'            => 'spacing',
								'ignore_db'       => true,
								'class'           => 'only_pro_spinner',
								'title'           => __( 'Icon Size', 'wp-expand-tabs-free' ),
								'all'             => true,
								'all_text'        => false,
								'all_placeholder' => 'size',
								'sanitize'        => 'wptabspro_sanitize_number_array_field',
								'default'         => array(
									'all' => '16',
								),
								'units'           => array(
									'px',
								),
								'dependency'      => array(
									'sptpro_tabs_icon',
									'==',
									'true',
								),
							),
							array(
								'id'              => 'sptpro_icon_space_title',
								'type'            => 'spacing',
								'ignore_db'       => true,
								'class'           => 'only_pro_spinner',
								'title'           => __( 'Space Between Title and Icon', 'wp-expand-tabs-free' ),
								'title_help'      => '<div class="wptabspro-img-tag"><img src="' . plugin_dir_url( __DIR__ ) . 'partials/models/assets/images/help-visuals/space-between-title-and-icon.svg" alt="' . __( 'Space Between Title and Icon', 'wp-expand-tabs-free' ) . '"></div><div class="wptabspro-info-label">' . __( 'Space Between Title and Icon', 'wp-expand-tabs-free' ) . '</div>',
								'all'             => true,
								'all_text'        => false,
								'all_placeholder' => 'size',
								'sanitize'        => 'wptabspro_sanitize_number_array_field',
								'default'         => array(
									'all' => '10',
								),
								'units'           => array(
									'px',
								),
								'dependency'      => array(
									'sptpro_tabs_icon',
									'==',
									'true',
								),
							),
							array(
								'id'         => 'sptpro_tab_icon_color',
								'type'       => 'color_group',
								'ignore_db'  => true,
								'class'      => 'only_pro_tabs_icon_section tab_icon_position',
								'title'      => __( 'Icon Color', 'wp-expand-tabs-free' ),
								'options'    => array(
									'tab-icon-color'       => __( 'Color', 'wp-expand-tabs-free' ),
									'tab-icon-color-active' => __( 'Active Color', 'wp-expand-tabs-free' ),
									'tab-icon-color-hover' => __( 'Hover Color', 'wp-expand-tabs-free' ),
								),
								'default'    => array(
									'tab-icon-color'       => '#444',
									'tab-icon-color-active' => '#444',
									'tab-icon-color-hover' => '#444',
								),
								'dependency' => array(
									'sptpro_tabs_icon',
									'==',
									'true',
								),
							),
							array(
								'id'         => 'sptpro_tab_icon_position',
								'type'       => 'image_select',
								'ignore_db'  => true,
								'class'      => 'only_pro_tabs_icon_section tab_icon_position',
								'title'      => __( 'Icon Position', 'wp-expand-tabs-free' ),
								'title_help' => sprintf(
									'<div class="wptabspro-info-label">%s</div><div class="wptabspro-short-content">%s</div><a class="wptabspro-open-docs" href="https://wptabs.com/docs/display-settings/tabs-icon/" target="_blank">%s</a><a class="wptabspro-open-live-demo" href="https://wptabs.com/tabs-icon-position/" target="_blank">%s</a>',
									__( 'Icon Position (Pro)', 'wp-expand-tabs-free' ),
									__( 'This option allows you to specify the position of icons within your tab interface. You can place icons to the Top, Right, Bottom and Left of tab\'s title.', 'wp-expand-tabs-free' ),
									__( 'Open Docs', 'wp-expand-tabs-free' ),
									__( 'Live Demo', 'wp-expand-tabs-free' )
								),
								'options'    => array(
									'tab-icon-position-left'  => array(
										'image'       => WP_TABS_URL . '/admin/img/woo-tab-icons-position/left.svg',
										'option_name' => __( 'Left', 'wp-expand-tabs-free' ),
									),
									'tab-icon-position-top'   => array(
										'image'       => WP_TABS_URL . '/admin/img/woo-tab-icons-position/top.svg',
										'option_name' => __( 'Top', 'wp-expand-tabs-free' ),
									),
									'tab-icon-position-right' => array(
										'image'       => WP_TABS_URL . '/admin/img/woo-tab-icons-position/right.svg',
										'option_name' => __( 'Right', 'wp-expand-tabs-free' ),
									),
								),
								'radio'      => true,
								'default'    => 'tab-icon-position-left',
							),
						),
					),

					array(
						'title'  => __( 'Image & Video Gallery', 'wp-expand-tabs-free' ),
						'icon'   => '<i class="fa icon-image-and-video-gallery"></i>',
						'fields' => array(
							array(
								'type'      => 'notice',
								'ignore_db' => true,
								'class'     => 'only_pro_notice tab-icon-top-notice smart-tabs-notice',
								'content'   => sprintf(
									/* translators: 1: start link and blod tag, 2: close tag. */
									__( 'Unlock Image & Video Gallery Tabs with lightbox features & more— %1$sUpgrade to Pro!%2$s', 'wp-expand-tabs-free' ),
									'<a href="' . esc_url( SP_SMART_TABS_PRO_LINK ) . '" target="_blank"><b>',
									'</b></a>'
								),
							),

							array(
								'id'        => 'content_image_layouts',
								'class'     => 'chosen sp-tabs-space-between only_pro_spinner',
								'type'      => 'select',
								'ignore_db' => true,
								'title'     => __( 'Layout Preset', 'wp-expand-tabs-free' ),
								'options'   => array(
									'grid'    => __( 'Grid', 'wp-expand-tabs-free' ),
									'masonry' => __( 'Masonry', 'wp-expand-tabs-free' ),
								),
								'default'   => 'grid',
								// 'dependency' => array( 'tabs_content_type', '==', 'image' ),
							),
							array(
								'id'        => 'content_images_columns',
								'class'     => 'content_images_columns only_pro_spinner',
								'type'      => 'column',
								'ignore_db' => true,
								'title'     => __( 'Columns', 'wp-expand-tabs-free' ),
								'default'   => array(
									'lg_desktop' => '5',
									'desktop'    => '4',
									'laptop'     => '4',
									'tablet'     => '3',
									'mobile'     => '2',
								),
								'min'       => '1',
								// 'dependency' => array( 'tabs_content_type', '==', 'image' ),
							),
							array(
								'id'        => 'space_between_image_and_video',
								'type'      => 'spacing',
								'ignore_db' => true,
								'class'     => 'sp-tabs-space-between only_pro_spinner',
								'title'     => __( 'Space Between', 'wp-expand-tabs-free' ),
								'all'       => true,
								'all_icon'  => '<i class="fa fa-arrows"></i>',
								'default'   => array(
									'all' => '24',
								),
								'units'     => array(
									'px',
								),
							),
							array(
								'id'         => 'content_image_caption',
								'type'       => 'switcher',
								'ignore_db'  => true,
								'class'      => 'sp-tabs-space-between only_pro_switcher',
								'title'      => __( 'Caption', 'wp-expand-tabs-free' ),
								'text_on'    => __( 'Enabled', 'wp-expand-tabs-free' ),
								'text_off'   => __( 'Disabled', 'wp-expand-tabs-free' ),
								'text_width' => 96,
								'default'    => true,
							// 'dependency' => array( 'tabs_content_type', '==', 'image' ),
							),
							array(
								'id'         => 'enable_image_lightbox',
								'type'       => 'switcher',
								'ignore_db'  => true,
								'class'      => 'sp-tabs-space-between only_pro_switcher',
								'title'      => __( 'Image Gallery Lightbox', 'wp-expand-tabs-free' ),
								'text_on'    => __( 'Enabled', 'wp-expand-tabs-free' ),
								'text_off'   => __( 'Disabled', 'wp-expand-tabs-free' ),
								'text_width' => 96,
								'default'    => true,
							),
							array(
								'id'        => 'video_play_mode',
								'type'      => 'button_set',
								'class'     => 'only_pro_spinner',
								'ignore_db' => true,
								'title'     => __( 'Video Play Mode', 'wp-expand-tabs-free' ),
								'options'   => array(
									'inline'   => __( 'Inline', 'wp-expand-tabs-free' ),
									'lightbox' => __( 'Lightbox', 'wp-expand-tabs-free' ),
								),
								'default'   => 'inline',
							),
						),
					),
				),
			),
		),
	)
); // Carousel settings section end.

//
// Typography section begin.
//
SP_WP_TABS::createSection(
	$sptpro_shortcode_settings,
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
					'<a href="' . esc_url( SP_SMART_TABS_PRO_LINK ) . '" target="_blank"><b>',
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

//
// Metabox of the footer section / shortocde section.
// Set a unique slug-like ID.
//
$sptpro_display_shortcode = 'sp_tab_display_shortcode_sidebar';

//
// Create a metabox.
//
SP_WP_TABS::createMetabox(
	$sptpro_display_shortcode,
	array(
		'title'     => __( 'How to Use', 'wp-expand-tabs-free' ),
		'post_type' => 'sp_wp_tabs',
		'context'   => 'side',
	)
);

//
// Create a section.
//
SP_WP_TABS::createSection(
	$sptpro_display_shortcode,
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

//
// Create a metabox.
//
SP_WP_TABS::createMetabox(
	'sp_tab_support_builder_sidebar',
	array(
		'title'     => __( 'Page Builders Ready', 'wp-expand-tabs-free' ),
		'post_type' => 'sp_wp_tabs',
		'context'   => 'side',
	)
);

//
// Create a section.
//
SP_WP_TABS::createSection(
	'sp_tab_support_builder_sidebar',
	array(
		'fields' => array(
			array(
				'type'      => 'shortcode',
				'shortcode' => false,
				'class'     => 'sp-tab__admin-sidebar',
			),
		),
	)
);

//
// Create a metabox.
//
SP_WP_TABS::createMetabox(
	'sp_tab_promotional_notice_sidebar',
	array(
		'title'     => __( 'Page Builders', 'wp-expand-tabs-free' ),
		'post_type' => 'sp_wp_tabs',
		'context'   => 'side',
	)
);

//
// Create a section.
//
SP_WP_TABS::createSection(
	'sp_tab_promotional_notice_sidebar',
	array(
		'fields' => array(
			array(
				'type'      => 'shortcode',
				'shortcode' => 'pro_notice',
				'class'     => 'sp-tab__admin-sidebar',
			),
		),
	)
);

/**
 * Content source settings metabox.
 */
$_content_source_settings = 'sptpro_woo_product_tabs_settings';
// Create a metabox for content source settings.
SP_WP_TABS::createMetabox(
	$_content_source_settings,
	array(
		'title'     => __( 'Smart Tabs', 'wp-expand-tabs-free' ),
		'post_type' => 'sp_products_tabs',
		'context'   => 'normal',
	)
);

// Create a section for content source settings.
SP_WP_TABS::createSection(
	$_content_source_settings,
	array(
		'fields' => array(
			array(
				'id'     => 'product_tab_title_section',
				'type'   => 'fieldset',
				'class'  => 'tabs_title_section',
				'title'  => '',
				'fields' => array(
					array(
						'id'         => 'tab_title',
						'type'       => 'text',
						'class'      => 'tabs_content-title',
						'wrap_class' => 'sp-tab__content_source',
						'title'      => __( 'Tab Name', 'wp-expand-tabs-free' ),
					),
				),
			),
			array(
				'id'          => 'tabs_content_type',
				'class'       => 'sp_product_tabs_content_type full-width pro-preset-style',
				'type'        => 'image_select',
				'title'       => __( 'Tab Content Type', 'wp-expand-tabs-free' ),
				'title_video' => '<div class="wptabspro-img-tag"><video autoplay loop muted playsinline><source src="' . plugin_dir_url( __DIR__ ) . 'partials/models/assets/images/tab-content-type.webm" type="video/webm"></video></div><div class="wcgs-info-label">' . esc_html__( 'Access More Powerful Tab Content Types in', 'wp-expand-tabs-free' ) . '
				<a href="' . esc_url( SP_SMART_TABS_PRO_LINK ) . '" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Pro!', 'wp-expand-tabs-free' ) . '</a></div>',
				'options'     => array(
					'content'   => array(
						'icon'        => 'icon-content',
						'class'       => 'free-feature',
						'option_name' => __( 'Content', 'wp-expand-tabs-free' ),
					),
					'products'  => array(
						'icon'        => 'icon-products',
						'class'       => 'free-feature',
						'option_name' => __( 'Products', 'wp-expand-tabs-free' ),
					),
					'pro-types' => array(
						'image' => WP_TABS_URL . '/admin/img/pro-presets/woo-tab-types.svg',
						'class' => 'pro-feature',
					),
				),
				'default'     => 'content',
			),
			array(
				'id'         => 'tabs_content_description',
				'type'       => 'wp_editor',
				'class'      => 'full-width',
				'wrap_class' => 'sp-tab__content_source',
				'title'      => __( 'Description', 'wp-expand-tabs-free' ),
				'height'     => '150px',
				'sanitize'   => false,
				'dependency' => array( 'tabs_content_type', 'any', 'content,products' ),
			),

			/**
			 * Products Section.
			 */
			array(
				'id'         => 'filter_products',
				'type'       => 'select',
				'title'      => __( 'Filter Products', 'wp-expand-tabs-free' ),
				'class'      => 'chosen sptpro-tabs-pro-select',
				'options'    => array(
					'latest'    => __( 'Latest', 'wp-expand-tabs-free' ),
					'related'   => __( 'Related', 'wp-expand-tabs-free' ),
					'specific'  => __( 'Specific Products (Pro)', 'wp-expand-tabs-free' ),
					'on_sale'   => __( 'On Sale (Pro)', 'wp-expand-tabs-free' ),
					'best_sell' => __( 'Best Selling (Pro)', 'wp-expand-tabs-free' ),
				),
				'desc'       => sprintf(
					// translators: %s is the link to the Woo Product Tabs generator page.
					__( 'Hide WooCommerce default related products %s.', 'wp-expand-tabs-free' ),
					'<a href="' . esc_url(
						admin_url( 'edit.php?post_type=sp_wp_tabs&page=tab_advanced' )
					) . '" target="_blank" rel="noopener noreferrer">' . __( 'here', 'wp-expand-tabs-free' ) . '</a>'
				),
				'default'    => 'latest',
				'dependency' => array( 'tabs_content_type', '==', 'products' ),
			),
			array(
				'id'         => 'products_order_by',
				'type'       => 'select',
				'title'      => __( 'Order By', 'wp-expand-tabs-free' ),
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
				'dependency' => array( 'tabs_content_type', '==', 'products' ),
			),
			array(
				'id'         => 'products_order',
				'type'       => 'select',
				'title'      => __( 'Order', 'wp-expand-tabs-free' ),
				'options'    => array(
					'ASC'  => __( 'Ascending', 'wp-expand-tabs-free' ),
					'DESC' => __( 'Descending', 'wp-expand-tabs-free' ),
				),
				'default'    => 'DESC',
				'dependency' => array( 'tabs_content_type', '==', 'products' ),
			),
			array(
				'id'         => 'number_of_total_products',
				'type'       => 'spinner',
				'title'      => __( 'Limit', 'wp-expand-tabs-free' ),
				'title_help' => __( 'Number of total posts to display. Default value is 8.', 'wp-expand-tabs-free' ),
				'default'    => '8',
				'min'        => 1,
				'max'        => 1000,
				'dependency' => array( 'tabs_content_type', '==', 'products' ),
			),
			/**
			 * Tabs Visibility Settings.
			 */
			array(
				'id'         => 'tabs_show_in',
				'type'       => 'select',
				'class'      => 'chosen sptpro-tabs-pro-select',
				'title'      => __( 'Show Tab in', 'wp-expand-tabs-free' ),
				'options'    => array(
					'all_product'            => __( 'All Products', 'wp-expand-tabs-free' ),
					'brand'                  => __( 'Brands', 'wp-expand-tabs-free' ),
					'categories'             => __( 'Categories (Pro)', 'wp-expand-tabs-free' ),
					'product_tags'           => __( 'Tags (Pro)', 'wp-expand-tabs-free' ),
					'product_sku'            => __( 'SKU (Pro)', 'wp-expand-tabs-free' ),
					'specific_product'       => __( 'Specific Products (Pro)', 'wp-expand-tabs-free' ),
					'product_visibility'     => __( 'Product Visibility (Pro)', 'wp-expand-tabs-free' ),
					'product_shipping_class' => __( 'Product Shipping Classes (Pro)', 'wp-expand-tabs-free' ),
					'product_color'          => __( 'Product Colors (Pro)', 'wp-expand-tabs-free' ),
					'product_size'           => __( 'Product Sizes (Pro)', 'wp-expand-tabs-free' ),
				),
				'default'    => 'latest',
				'dependency' => array( 'tabs_content_type', 'any', 'content,products' ),
			),
			array(
				'id'          => 'tabs_show_by_brands',
				'type'        => 'select',
				'class'       => 'sp_tab_specific_posts',
				'title'       => __( 'Choose Brands', 'wp-expand-tabs-free' ),
				'options'     => 'brands_term',
				'chosen'      => true,
				'sortable'    => true,
				'multiple'    => true,
				// 'ajax'        => true,
				'placeholder' => __( 'Choose Brand(s)', 'wp-expand-tabs-free' ),
				'attributes'  => array(
					'style' => 'min-width: 250px;',
				),
				'dependency'  => array( 'tabs_show_in', '==', 'brand' ),
			),
			array(
				'id'         => 'tabs_exclude',
				'type'       => 'checkbox',
				'wrap_class' => 'sp-tab__content_source',
				'title'      => __( 'Exclude', 'wp-expand-tabs-free' ),
				'default'    => false,
				'dependency' => array( 'tabs_content_type', 'any', 'content,products' ),
			),
			array(
				'id'          => 'exclude_specific',
				'type'        => 'select',
				'title'       => __( 'Specific', 'wp-expand-tabs-free' ),
				'class'       => 'sp_tab_specific_posts',
				'options'     => 'posts',
				'query_args'  => array(
					'post_type'      => 'product',
					'orderby'        => 'post_date',
					'order'          => 'DESC',
					'numberposts'    => 100,
					'posts_per_page' => 100,
					'cache_results'  => false,
					'no_found_rows'  => true,
				),
				'ajax'        => true,
				'chosen'      => true,
				'sortable'    => true,
				'multiple'    => true,
				'placeholder' => __( 'Choose Product(s)', 'wp-expand-tabs-free' ),
				'dependency'  => array( 'tabs_content_type|tabs_exclude', 'any|==', 'content,products|true' ),
			),
			array(
				'id'         => 'override_tab',
				'type'       => 'checkbox',
				'title'      => __( 'Override this tab in each product', 'wp-expand-tabs-free' ),
				'default'    => true,
				'title_help' => sprintf(
					/* translators: 1: start div tag, 2: close div tag, 3: start div tag, 4: close div tag. */
					'<div class="wptabspro-info-label">%s</div><div class="wptabspro-short-content">%s</div>',
					__( 'Override this tab in each product', 'wp-expand-tabs-free' ),
					__( 'Enable this option to override the default behavior and customize this tab’s visibility for specific products.', 'wp-expand-tabs-free' )
				),
				'dependency' => array( 'tabs_content_type', 'any', 'content' ),
			),
			array(
				'id'      => 'product_tabs_notice',
				'type'    => 'notice',
				'style'   => 'normal',
				'class'   => 'smart-tabs-notice',
				'content' => sprintf(
					/* translators: 1: start link and strong tag, 2: close link and strong tag, 3: start strong tag, 4: close strong tag. 5: start link and strong tag, 6: close link and strong tag. */
					__( 'Add unlimited custom tabs with any content to boost engagement and sales — %1$sUpgrade to Pro!%2$s', 'wp-expand-tabs-free' ),
					'<a class="wcgs-open-live-demo" href="' . esc_url( SP_SMART_TABS_PRO_LINK ) . '" target="_blank"><strong>',
					'</strong></a>'
				),
			),
			// ), // End of Content Tabs.
		), // End of fields array.
	)
);
