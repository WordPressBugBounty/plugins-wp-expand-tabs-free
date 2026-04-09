<?php
/**
 * The admin Tab navigation specific functionality for Product Tabs module.
 *
 * @link       https://shapedplugin.com/
 * @since      1.0.0
 *
 * @package    SmartTabs/ProductTabs
 * @subpackage Admin
 */

namespace ShapedPlugin\SmartTabsFree\Modules\ProductTabs\Admin\UI;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Tab Navigation Class.
 *
 * @since 3.2.1
 */
class TabNavigation {
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
	 * Register all hooks related to Tab Navigation in the admin area.
	 *
	 * @return void
	 */
	public function register() {
		$this->loader->add_action( 'in_admin_header', $this, 'render_tabbed_header' );
		$this->loader->add_filter( 'submenu_file', $this, 'set_active_submenu' );
		$this->loader->add_filter( 'screen_options_show_screen', $this, 'disable_screen_options', 10, 2 );
	}

	/**
	 * Add Woo product tabs admin menu.
	 */
	public function render_tabbed_header() {
		$screen = get_current_screen();

		$target_bases    = array( 'sp_wp_tabs_page_tab_advanced', 'sp_wp_tabs_page_tab_styles', 'edit', 'post' );
		$is_target_base  = in_array( $screen->base, $target_bases, true );
		$is_product_tabs = 'sp_products_tabs' === $screen->post_type;

		// Remove admin notices for the tabs settings page and product tabs post type.
		if ( is_admin() && ( ( $is_target_base && $is_product_tabs ) || ( $is_target_base && 'sp_wp_tabs' === $screen->post_type ) ) ) {
			remove_all_actions( 'admin_notices' );
			remove_all_actions( 'all_admin_notices' );
		}

		$allowed_screens = array( 'sp_wp_tabs_page_tab_advanced', 'sp_wp_tabs_page_tab_styles' );
		if (
			! in_array( $screen->base, $allowed_screens, true ) &&
			( 'sp_products_tabs' !== $screen->post_type || 'edit' !== $screen->base )
		) {
			return;
		}
		?>
		<div class="wrap sp_woo_product_settings_tabbed">
			<h2 class="nav-tab-wrapper">
				<!-- Tabs General -->
				<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=sp_products_tabs&tab=general' ) ); ?>" class="nav-tab <?php echo ( 'sp_products_tabs' === $screen->post_type && 'edit' === $screen->base ) ? 'nav-tab-active' : ''; ?>"><i class="wptabspro-tabbed-icon icon-woo-product-tabs-general"></i><?php echo esc_html_e( 'Product Tabs', 'wp-expand-tabs-free' ); ?></a>
				<!-- Tabs Style -->
				<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=sp_wp_tabs&page=tab_styles' ) ); ?>" class="nav-tab <?php echo ( 'sp_wp_tabs_page_tab_styles' === $screen->base ) ? 'nav-tab-active' : ''; ?>"><i class="wptabspro-tabbed-icon icon-woo-product-tab-style"></i><?php echo esc_html_e( 'Style', 'wp-expand-tabs-free' ); ?></a>
				<!-- Tabs Advanced -->
				<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=sp_wp_tabs&page=tab_advanced' ) ); ?>" class="nav-tab <?php echo ( 'sp_wp_tabs_page_tab_advanced' === $screen->base ) ? 'nav-tab-active' : ''; ?>"><i class="wptabspro-tabbed-icon icon-woo-product-advanced"></i><?php echo esc_html_e( 'Advanced', 'wp-expand-tabs-free' ); ?></a>
			</h2>
		</div>
		<?php
	}

	/**
	 * Set the active submenu for the tabs settings page.
	 *
	 * @param string $submenu_file The submenu file.
	 * @return string
	 */
	public function set_active_submenu( $submenu_file ) {
		global $current_screen;
		if ( 'sp_wp_tabs_page_tab_styles' === $current_screen->id || 'sp_wp_tabs_page_tab_advanced' === $current_screen->id ) {
			$submenu_file = 'edit.php?post_type=sp_products_tabs';
		}

		return $submenu_file;
	}

	/**
	 * Removes 'Screen Option' panel from 'sp_products_tabs' post type.
	 *
	 * @param bool   $show_screen Whether to show the screen options panel.
	 * @param object $screen The current screen object.
	 * @return bool
	 */
	public function disable_screen_options( $show_screen, $screen ) {
		if ( isset( $screen->post_type ) && ( 'sp_products_tabs' === $screen->post_type ) ) {
			return false;
		}
		return $show_screen;
	}
}
