<?php
/**
 * This is is responsible to generate admin notices.
 *
 * @since        2.0.1
 *
 * @package    ShapedPlugin\SmartTabsFree
 * @subpackage Core\Notices
 * @author     ShapedPlugin<support@shapedplugin.com>
 */

namespace ShapedPlugin\SmartTabsFree\Core\Notices;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use ShapedPlugin\SmartTabsFree\Core\Constants;

/**
 * Review notice class.
 *
 * @since    2.0.0
 */
class Review {

	/**
	 * Register all of the hooks related to the import export functionality
	 *
	 * @since    3.2.0
	 * @param loader $loader The loader that's responsible for maintaining and registering all hooks.
	 */
	public function register_hooks( $loader ): void {
		$loader->add_action( 'admin_notices', $this, 'display_admin_notice' );
		$loader->add_action( 'wp_ajax_sp-wptabs-never-show-review-notice', $this, 'dismiss_review_notice' );
	}

	/**
	 * Display admin notice.
	 *
	 * @return void
	 */
	public function display_admin_notice() {
		// Show only to Admins.
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// Variable default value.
		$review = get_option( 'sp_wptabs_review_notice_dismiss' );
		$time   = time();
		$load   = false;

		if ( ! $review ) {
			$review = array(
				'time'      => $time,
				'dismissed' => false,
			);
			add_option( 'sp_wptabs_review_notice_dismiss', $review );
		} else {
			// Check if it has been dismissed or not.
			if ( ( isset( $review['dismissed'] ) && ! $review['dismissed'] ) && ( isset( $review['time'] ) && ( ( $review['time'] + ( DAY_IN_SECONDS * 3 ) ) <= $time ) ) ) {
				$load = true;
			}
		}

		// If we cannot load, return early.
		if ( ! $load ) {
			return;
		}
		?>
		<div id="sp-wptabs-review-notice" class="sp-wptabs-review-notice">
			<div class="sp-wptabs-plugin-icon">
				<img src="<?php echo esc_url( Constants::url() . 'src/Core/Notices/Images/sp-smart-tabs.gif' ); ?>" alt="Smart Tabs">
			</div>
			<div class="sp-wptabs-notice-text">
				<h3>Enjoying <strong>Smart Tabs</strong>?</h3>
				<p>We hope you had a wonderful experience using <strong>Smart Tabs</strong>. Please take a moment to leave a review on <a href="https://wordpress.org/support/plugin/wp-expand-tabs-free/reviews/" target="_blank"><strong>WordPress.org</strong></a>.
				Your positive review will help us improve. Thank you! 😊</p>

				<p class="sp-wptabs-review-actions">
					<a href="https://wordpress.org/support/plugin/wp-expand-tabs-free/reviews/" target="_blank" class="button button-primary notice-dismissed rate-wp-tabs">Ok, you deserve ★★★★★</a>
					<a href="#" class="notice-dismissed remind-me-later"><span class="dashicons dashicons-clock"></span>Nope, maybe later
					</a>
					<a href="#" class="notice-dismissed never-show-again"><span class="dashicons dashicons-dismiss"></span>Never show again</a>
				</p>
			</div>
		</div>

		<script type='text/javascript'>

			jQuery(document).ready( function($) {
				$(document).on('click', '#sp-wptabs-review-notice.sp-wptabs-review-notice .notice-dismissed', function( event ) {
					if ( $(this).hasClass('rate-wp-tabs') ) {
						var notice_dismissed_value = "1";
					}
					if ( $(this).hasClass('remind-me-later') ) {
						var notice_dismissed_value =  "2";
						event.preventDefault();
					}
					if ( $(this).hasClass('never-show-again') ) {
						var notice_dismissed_value =  "3";
						event.preventDefault();
					}

					$.post( ajaxurl, {
						action: 'sp-wptabs-never-show-review-notice',
						notice_dismissed_data : notice_dismissed_value,
						nonce: '<?php echo esc_attr( wp_create_nonce( 'sp_wptabs_review_notice' ) ); ?>'
					});

					$('#sp-wptabs-review-notice.sp-wptabs-review-notice').hide();
				});
			});

		</script>
		<?php
	}

	/**
	 * Dismiss review notice
	 *
	 * @since  2.0.1
	 *
	 * @return void
	 **/
	public function dismiss_review_notice() {
		// Check if the user has the required capability.
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$post_data = wp_unslash( $_POST );
		$nonce     = ( ! empty( $_POST['nonce'] ) ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';

		if ( ! wp_verify_nonce( $nonce, 'sp_wptabs_review_notice' ) ) {
			return;
		}
		$review = get_option( 'sp_wptabs_review_notice_dismiss' );

		if ( ! $review ) {
			$review = array();
		}
		switch ( isset( $post_data['notice_dismissed_data'] ) ? $post_data['notice_dismissed_data'] : '' ) {
			case '1':
				$review['time']      = time();
				$review['dismissed'] = false;
				break;
			case '2':
				$review['time']      = time();
				$review['dismissed'] = false;
				break;
			case '3':
				$review['time']      = time();
				$review['dismissed'] = true;
				break;
		}
		update_option( 'sp_wptabs_review_notice_dismiss', $review );
		die;
	}
}
