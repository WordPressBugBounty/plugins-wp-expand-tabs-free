<?php
/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://shapedplugin.com/
 * @since      1.0.0
 *
 * @package wp-expand-tabs-free
 * @subpackage wp-expand-tabs-free/partials
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

$sptpro_post_data_src      = $sptpro_data_upload;
$sptpro_post_type          = ( isset( $sptpro_post_data_src['sptpro_post_type'] ) ? $sptpro_post_data_src['sptpro_post_type'] : 'post' );
$sptpro_display_posts_from = ( isset( $sptpro_post_data_src['sptpro_display_posts_from'] ) ? $sptpro_post_data_src['sptpro_display_posts_from'] : '' );
$sptpro_post_taxonomy      = ( isset( $sptpro_post_data_src['sptpro_post_taxonomy'] ) ? $sptpro_post_data_src['sptpro_post_taxonomy'] : '' );
$sptpro_taxonomy_terms     = ( isset( $sptpro_post_data_src['sptpro_taxonomy_terms'] ) ? $sptpro_post_data_src['sptpro_taxonomy_terms'] : '' );
$sptpro_taxonomy_operator  = ( isset( $sptpro_post_data_src['taxonomy_operator'] ) ? $sptpro_post_data_src['taxonomy_operator'] : '' );
$sptpro_specific_posts     = ( isset( $sptpro_post_data_src['sptpro_specific_posts'] ) ? $sptpro_post_data_src['sptpro_specific_posts'] : '' );
$number_of_total_posts     = isset( $sptpro_post_data_src['number_of_total_posts'] ) ? $sptpro_post_data_src['number_of_total_posts'] : '';
$post_order_by             = ( isset( $sptpro_post_data_src['post_order_by'] ) ? $sptpro_post_data_src['post_order_by'] : 'date' );
$post_order                = ( isset( $sptpro_post_data_src['post_order'] ) ? $sptpro_post_data_src['post_order'] : 'DESC' );

if ( empty( $sptpro_post_type ) ) {
	return;
}
$args = array(
	'post_type'           => $sptpro_post_type,
	'post_status'         => 'publish',
	'ignore_sticky_posts' => 1,
	'posts_per_page'      => $number_of_total_posts,
	'orderby'             => $post_order_by,
	'order'               => $post_order,
);

$post_query = new WP_Query( $args );

echo '<ul class="sp-tab__nav sp-tab__nav-tabs" id="sp-tab__ul" role="tablist">'; // phpcs:ignore

if ( is_array( $sptpro_post_data_src ) ) {
	$sptpro_data_count = 1;

	// First loop.
	if ( $post_query->have_posts() ) {

		while ( $post_query->have_posts() ) {
			$post_query->the_post();
			$sptpro_active_class       = $sptpro_tab_opened == $sptpro_data_count ? 'sp-tab__active' : ''; // phpcs:ignore
			$sp_tab_item_title   = apply_filters( 'sp_tabs_post_title', get_the_title( get_the_ID() ), get_the_ID(), $post_id );
			$tabs_content_title  = '<' . $sptpro_title_heading_tag . ' class="sp-tab__tab_title">' . $sp_tab_item_title . '</' . $sptpro_title_heading_tag . '>';

			// Tab title slug.
			$tabs_aria_controls_for_id = 'tab-' . $post_id . $sptpro_data_count;
			$name_url                  = get_post_field( 'post_name', get_the_ID() );
			$tab_name_url              = apply_filters( 'sp_tabs_name_slug', $name_url );
			$tabs_aria_controls_for_id = isset( $tab_name_url ) && 'tab_title' === $sptpro_tab_link_type ? $tab_name_url : $tabs_aria_controls_for_id;

			include WP_TABS_PATH . '/public/partials/tab-post/post-meta.php';

			echo '<li class="sp-tab__nav-item" role="tab" >';
			echo '<label class="sp-tab__nav-link ' . esc_attr( $sptpro_active_class ) . '" data-sptoggle="tab" for="#' . esc_attr( $tabs_aria_controls_for_id ) . '"  aria-controls="' . esc_attr( $tabs_aria_controls_for_id ) . '" aria-selected="true" tabindex="0">' . wp_kses_post( $tabs_content_title ) . $post_meta_after_title . '</label>'; // phpcs:ignore

			echo '</li>';

			++$sptpro_data_count;
		}
	}

	$accordion_content = 'accordion_mode' === $sptpro_tabs_on_small_screen ? 'id="wpt-accordion-' . esc_attr( $post_id ) . '"' : '';
	echo '</ul>
		<div class="sp-tab__tab-content" ' . $accordion_content . '>'; // phpcs:ignore -- This is escaped in the variable.

	// Second loop.
	$sptpro_cont_count = 1;
	if ( $post_query->have_posts() ) {
		while ( $post_query->have_posts() ) {
			global $wp_embed;
			$post_query->the_post();
			$sptpro_active_class   = $sptpro_tab_opened == $sptpro_cont_count ? 'sp-tab__show sp-tab__active' : ''; // phpcs:ignore

			// Tab title slug.
			$tabs_pane_variable_id = 'tab-' . $post_id . $sptpro_cont_count;
			$name_url              = get_post_field( 'post_name', get_the_ID() );
			$tab_name_url          = apply_filters( 'sp_tabs_name_slug', $name_url );
			$tabs_pane_variable_id = isset( $tab_name_url ) && 'tab_title' === $sptpro_tab_link_type ? $tab_name_url : $tabs_pane_variable_id;

			$sptpro_content = do_shortcode( $wp_embed->autoembed( get_the_content() ) );
			if ( function_exists( 'do_blocks' ) ) {
				$sptpro_content = do_blocks( $sptpro_content );
			}
			$sptpro_content_ptag = wpautop( trim( preg_replace( '/<!--(.|s)*?-->/', '', $sptpro_content ) ) );
			$animation_name      = $sptpro_tabs_animation ? 'animated ' . $sptpro_tabs_animation_type : '';
			$smooth_scrollbar    = '';
			include WP_TABS_PATH . '/public/partials/tab-post/post-meta.php';
			include WP_TABS_PATH . '/public/partials/tab-post/feature-image.php';

			switch ( $sptpro_tabs_on_small_screen ) {
				case 'full_widht':
					echo '';
					printf(
						'<div class="sp-tab__tab-pane %1$s" id="%2$s" role="tabpanel" aria-labelledby="%2$s" aria-hidden="false" %5$s>
						<div class="sp-tab__tab-body %4$s">%6$s <div class="sp-tab__tab-content">%7$s %3$s</div></div>
						</div>',
						esc_attr( $sptpro_active_class ),
						esc_attr( $tabs_pane_variable_id ),
						$sptpro_content_ptag, // phpcs:ignore
						esc_attr( $animation_name ),
						esc_attr( $smooth_scrollbar ),
						wp_kses( $post_thumb, $allow_meta_tag ),
						$post_meta_before_descriptions // phpcs:ignore
					);
					break;
				case 'accordion_mode':
					$sptpro_show_class           = $sptpro_tab_opened === $sptpro_cont_count ? 'sp-tab__show' : '';
					$tabs_pane_variable_controls = 'collapse-' . $post_id . $sptpro_cont_count;
					$tabs_pane_variable_heading  = 'heading-' . $post_id . $sptpro_cont_count;
					$tabs_content_title          = get_the_title( get_the_ID() );
					$aria_expanded               = $sptpro_show_class ? 'true' : 'false';

					echo '<div id="' . esc_attr( $tabs_pane_variable_id ) . '" class="sp-tab__card sp-tab__tab-pane ' . esc_attr( $sptpro_active_class ) . '" aria-labelledby="' . esc_attr( $tabs_pane_variable_heading ) . '" role="tabpanel">
					<label data-sptoggle="collapse" for="#' . esc_attr( $tabs_pane_variable_controls ) . '" aria-expanded="' . esc_attr( $aria_expanded ) . '" aria-controls="' . esc_attr( $tabs_pane_variable_controls ) . '">
					<div class="sp-tab__card-header"  id="' . esc_attr( $tabs_pane_variable_heading ) . '">' . wp_kses_post( $tabs_content_title ) . $post_meta_after_title . '</div></label>'; // phpcs:ignore -- This is escaped in the variable.

					echo '<div id="' . esc_attr( $tabs_pane_variable_controls ) . '" class="sp-tab__collapse ' . esc_attr( $sptpro_show_class ) . '" data-parent="#wpt-accordion-' . esc_attr( $post_id ) . '" aria-labelledby="' . esc_attr( $tabs_pane_variable_heading ) . '">
						<div class="sp-tab__card-body">
							<div class="sp-tabs-accordion-body ' . esc_attr( $animation_name ) . '">' . wp_kses( $post_thumb, $allow_meta_tag ) . '<div class="sp-tab__tab-content">' . $post_meta_before_descriptions . $sptpro_content_ptag . '</div></div>'; // phpcs:ignore
							echo '</div>
						</div>
					</div>';
					break;
			}
			++$sptpro_cont_count;
		}
		wp_reset_postdata();
	}
}

echo '</div>';
