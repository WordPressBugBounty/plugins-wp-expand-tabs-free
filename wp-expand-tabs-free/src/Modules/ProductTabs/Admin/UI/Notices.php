<?php
/**
 * The admin notices related functionality for Product Tabs module.
 *
 * @link       https://shapedplugin.com/
 * @since      3.2.1
 *
 * @package    SmartTabsFree/ProductTabs
 * @subpackage Admin/UI
 */

namespace ShapedPlugin\SmartTabsFree\Modules\ProductTabs\Admin\UI;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Notices Class.
 *
 * @since 3.2.1
 */
class Notices {
	/**
	 * The loader that's responsible for maintaining and registering all hooks that
	 *
	 * @var $loader
	 */
	protected $loader;

	/**
	 * Initialize the Admin Table Hooks for Product Tabs module.
	 *
	 * @param object $loader The admin loader object.
	 */
	public function __construct( $loader ) {
		$this->loader = $loader;
	}

	/**
	 * Register all hooks related to admin notices.
	 */
	public function register() {
		$this->loader->add_action( 'admin_notices', $this, 'wc_smart_tabs_admin_notice' );
		$this->loader->add_action( 'wp_ajax_dismiss_smart_tabs_wc_notice', $this, 'dismiss_smart_tabs_wc_notice' );
	}

	/**
	 * Show admin notice if WooCommerce is not activated.
	 *
	 * @since 3.2.0
	 */
	public function wc_smart_tabs_admin_notice() {
		// Only show for admins.
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// Show only if WooCommerce is not active and notice not dismissed.
		if ( class_exists( 'WooCommerce' ) || get_option( 'smart_tabs_wc_notice_dismiss_status', false ) ) {
			return;
		}

		add_thickbox();

		$link = esc_url(
			add_query_arg(
				array(
					'tab'       => 'plugin-information',
					'plugin'    => 'woocommerce',
					'TB_iframe' => 'true',
					'width'     => '640',
					'height'    => '500',
				),
				admin_url( 'plugin-install.php' )
			)
		);

		$settings_page_url = admin_url( 'edit.php?post_type=sp_wp_tabs&page=tabs_settings#tab=1' );

		$message = sprintf(
			wp_kses(
				/* translators: %1$s and %2$s are HTML links */
				__(
					'You must install and activate the %1$sWooCommerce%2$s plugin to run the <strong>Product Tabs</strong> smoothly in Smart Tabs. Don’t need it? Just disable the %3$sProduct Tabs%4$s option to hide this notice.',
					'wp-expand-tabs-free'
				),
				array(
					'a'      => array(
						'href'   => array(),
						'class'  => array(),
						'target' => array(),
					),
					'strong' => array(),
				)
			),
			'<a class="thickbox open-plugin-details-modal" href="' . esc_url( $link ) . '"><strong>',
			'</strong></a>',
			'<a href="' . esc_url( $settings_page_url ) . '"><strong>',
			'</strong></a>'
		);

		printf(
			'<div class="notice notice-error is-dismissible wc-smart-tabs-notice"><p>%s</p></div>',
			$message // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Message content escaped via wp_kses above.
		);
		$notice_nonce = wp_create_nonce( 'smart_tabs_wc_notice_nonce' );
		?>
		<script>
		jQuery(document).on('click', '.wc-smart-tabs-notice .notice-dismiss', function() {
			jQuery.post(ajaxurl, { 
				action: 'dismiss_smart_tabs_wc_notice',
				_wpnonce: '<?php echo esc_js( $notice_nonce ); ?>'
			});
		});
		</script>
		<?php
	}

	/**
	 * AJAX callback to persistently dismiss the WooCommerce dependency notice.
	 *
	 * Triggered when an admin clicks the dismiss button on the Smart Tabs notice.
	 * Stores a user meta flag so the notice remains hidden for that user on future page loads.
	 *
	 * @since 3.2.1
	 *
	 * @return void
	 */
	public function dismiss_smart_tabs_wc_notice() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// Verify nonce.
		if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( wp_unslash( $_POST['_wpnonce'] ), 'smart_tabs_wc_notice_nonce' ) ) { // phpcs:ignore -- WordPress.Security.NonceVerification.Recommended
			wp_die( -1 );
		}

		update_option( 'smart_tabs_wc_notice_dismiss_status', 1 );
		wp_send_json_success();
	}
}
