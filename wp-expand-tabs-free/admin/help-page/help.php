<?php
/**
 * The help page for the Smart Tabs Free
 *
 * @package Smart Tabs Free
 * @subpackage wp-tabs-free/admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access.

/**
 * The help class for the Smart Tabs Free
 */
class WP_Tabs_Help_Page {

	/**
	 * Single instance of the class
	 *
	 * @var null
	 */
	protected static $_instance = null;

	/**
	 * Plugins Path variable.
	 *
	 * @var array
	 */
	protected static $plugins = array(
		'woo-product-slider'             => 'main.php',
		'gallery-slider-for-woocommerce' => 'woo-gallery-slider.php',
		'post-carousel'                  => 'main.php',
		'easy-accordion-free'            => 'plugin-main.php',
		'logo-carousel-free'             => 'main.php',
		'location-weather'               => 'main.php',
		'woo-quickview'                  => 'woo-quick-view.php',
		'wp-expand-tabs-free'            => 'plugin-main.php',
	);

	/**
	 * Welcome pages
	 *
	 * @var array
	 */
	public $pages = array(
		'tabs_help',
	);


	/**
	 * Not show this plugin list.
	 *
	 * @var array
	 */
	protected static $not_show_plugin_list = array( 'aitasi-coming-soon', 'latest-posts', 'widget-post-slider', 'easy-lightbox-wp' );

	/**
	 * Help page construct function.
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'help_admin_menu' ), 80 );

        $page   = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : '';// @codingStandardsIgnoreLine
		if ( 'tabs_help' !== $page ) {
			return;
		}
		add_action( 'admin_print_scripts', array( $this, 'disable_admin_notices' ) );
		add_action( 'wptabspro_enqueue', array( $this, 'help_page_enqueue_scripts' ) );
	}

	/**
	 * Main Help page Instance
	 *
	 * @static
	 * @return self Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Help_page_enqueue_scripts function.
	 *
	 * @return void
	 */
	public function help_page_enqueue_scripts() {
		wp_enqueue_style( 'sp-wp-tabs-help', WP_TABS_URL . 'admin/help-page/css/help-page.min.css', array(), WP_TABS_VERSION );
		wp_enqueue_style( 'sp-wp-tabs-help-fontello', WP_TABS_URL . 'admin/help-page/css/fontello.min.css', array(), WP_TABS_VERSION );

		wp_enqueue_script( 'sp-wp-tabs-help', WP_TABS_URL . 'admin/help-page/js/help-page.min.js', array(), WP_TABS_VERSION, true );
	}

	/**
	 * Add admin menu.
	 *
	 * @return void
	 */
	public function help_admin_menu() {
		add_submenu_page(
			'edit.php?post_type=sp_wp_tabs',
			esc_html__( 'Smart Tabs', 'wp-expand-tabs-free' ),
			esc_html__( 'Recommended', 'wp-expand-tabs-free' ),
			'manage_options',
			'edit.php?post_type=sp_wp_tabs&page=tabs_help#recommended'
		);
		add_submenu_page(
			'edit.php?post_type=sp_wp_tabs',
			esc_html__( 'Smart Tabs', 'wp-expand-tabs-free' ),
			esc_html__( 'Lite vs Pro', 'wp-expand-tabs-free' ),
			'manage_options',
			'edit.php?post_type=sp_wp_tabs&page=tabs_help#lite-to-pro'
		);
		add_submenu_page(
			'edit.php?post_type=sp_wp_tabs',
			esc_html__( 'Smart Tabs Help', 'wp-expand-tabs-free' ),
			esc_html__( 'Get Help', 'wp-expand-tabs-free' ),
			'manage_options',
			'tabs_help',
			array(
				$this,
				'help_page_callback',
			)
		);
	}

	/**
	 * Sptabs_ajax_help_page function.
	 *
	 * @return void
	 */
	public function sptabs_plugins_info_api_help_page() {
		$plugins_arr = get_transient( 'sptabs_plugins' );
		if ( false === $plugins_arr ) {
			$args    = (object) array(
				'author'   => 'shapedplugin',
				'per_page' => '120',
				'page'     => '1',
				'fields'   => array(
					'slug',
					'name',
					'version',
					'downloaded',
					'active_installs',
					'last_updated',
					'rating',
					'num_ratings',
					'short_description',
					'author',
					'icons',
				),
			);
			$request = array(
				'action'  => 'query_plugins',
				'timeout' => 30,
				'request' => serialize( $args ),
			);
			// https://codex.wordpress.org/WordPress.org_API.
			$url      = 'http://api.wordpress.org/plugins/info/1.0/';
			$response = wp_remote_post( $url, array( 'body' => $request ) );

			if ( ! is_wp_error( $response ) ) {

				$plugins_arr = array();
				$plugins     = unserialize( $response['body'] );

				if ( isset( $plugins->plugins ) && ( count( $plugins->plugins ) > 0 ) ) {
					foreach ( $plugins->plugins as $pl ) {
						if ( ! in_array( $pl->slug, self::$not_show_plugin_list, true ) ) {
							$plugins_arr[] = array(
								'slug'              => $pl->slug,
								'name'              => $pl->name,
								'version'           => $pl->version,
								'downloaded'        => $pl->downloaded,
								'active_installs'   => $pl->active_installs,
								'last_updated'      => strtotime( $pl->last_updated ),
								'rating'            => $pl->rating,
								'num_ratings'       => $pl->num_ratings,
								'short_description' => $pl->short_description,
								'icons'             => $pl->icons['2x'],
							);
						}
					}
				}

				set_transient( 'sptabs_plugins', $plugins_arr, 24 * HOUR_IN_SECONDS );
			}
		}

		if ( is_array( $plugins_arr ) && ( count( $plugins_arr ) > 0 ) ) {
			array_multisort( array_column( $plugins_arr, 'active_installs' ), SORT_DESC, $plugins_arr );

			foreach ( $plugins_arr as $plugin ) {
				$plugin_slug = $plugin['slug'];
				$plugin_icon = $plugin['icons'];
				// Skip WP Expand Tabs Free plugin.
				if ( 'wp-expand-tabs-free' === $plugin_slug ) {
					continue;
				}
				if ( isset( self::$plugins[ $plugin_slug ] ) ) {
					$plugin_file = self::$plugins[ $plugin_slug ];
				} else {
					$plugin_file = $plugin_slug . '.php';
				}

				$details_link = network_admin_url( 'plugin-install.php?tab=plugin-information&amp;plugin=' . $plugin['slug'] . '&amp;TB_iframe=true&amp;width=600&amp;height=550' );
				?>
				<div class="plugin-card <?php echo esc_attr( $plugin_slug ); ?>" id="<?php echo esc_attr( $plugin_slug ); ?>">
					<div class="plugin-card-top">
						<div class="name column-name">
							<h3>
								<a class="thickbox" title="<?php echo esc_attr( $plugin['name'] ); ?>" href="<?php echo esc_url( $details_link ); ?>">
						<?php echo esc_html( $plugin['name'] ); ?>
									<img src="<?php echo esc_url( $plugin_icon ); ?>" class="plugin-icon"/>
								</a>
							</h3>
						</div>
						<div class="action-links">
							<ul class="plugin-action-buttons">
								<li>
						<?php
						if ( $this->is_plugin_installed( $plugin_slug, $plugin_file ) ) {
							if ( $this->is_plugin_active( $plugin_slug, $plugin_file ) ) {
								?>
										<button type="button" class="button button-disabled" disabled="disabled">Active</button>
									<?php
							} else {
								?>
											<a href="<?php echo esc_url( $this->activate_plugin_link( $plugin_slug, $plugin_file ) ); ?>" class="button button-primary activate-now">
									<?php esc_html_e( 'Activate', 'wp-expand-tabs-free' ); ?>
											</a>
									<?php
							}
						} else {
							?>
										<a href="<?php echo esc_url( $this->install_plugin_link( $plugin_slug ) ); ?>" class="button install-now">
								<?php esc_html_e( 'Install Now', 'wp-expand-tabs-free' ); ?>
										</a>
								<?php } ?>
								</li>
								<li>
									<a href="<?php echo esc_url( $details_link ); ?>" class="thickbox open-plugin-details-modal" aria-label="<?php echo esc_attr( 'More information about ' . $plugin['name'] ); ?>" title="<?php echo esc_attr( $plugin['name'] ); ?>">
								<?php esc_html_e( 'More Details', 'wp-expand-tabs-free' ); ?>
									</a>
								</li>
							</ul>
						</div>
						<div class="desc column-description">
							<p><?php echo esc_html( isset( $plugin['short_description'] ) ? $plugin['short_description'] : '' ); ?></p>
							<p class="authors"> <cite>By <a href="https://shapedplugin.com/">ShapedPlugin LLC</a></cite></p>
						</div>
					</div>
					<?php
					echo '<div class="plugin-card-bottom">';

					if ( isset( $plugin['rating'], $plugin['num_ratings'] ) ) {
						?>
						<div class="vers column-rating">
							<?php
							wp_star_rating(
								array(
									'rating' => $plugin['rating'],
									'type'   => 'percent',
									'number' => $plugin['num_ratings'],
								)
							);
							?>
							<span class="num-ratings">(<?php echo esc_html( number_format_i18n( $plugin['num_ratings'] ) ); ?>)</span>
						</div>
						<?php
					}
					if ( isset( $plugin['version'] ) ) {
						?>
						<div class="column-updated">
							<strong><?php esc_html_e( 'Version:', 'wp-expand-tabs-free' ); ?></strong>
							<span><?php echo esc_html( $plugin['version'] ); ?></span>
						</div>
							<?php
					}

					if ( isset( $plugin['active_installs'] ) ) {
						?>
						<div class="column-downloaded">
						<?php echo esc_html( number_format_i18n( $plugin['active_installs'] ) ) . esc_html__( '+ Active Installations', 'wp-expand-tabs-free' ); ?>
						</div>
									<?php
					}

					if ( isset( $plugin['last_updated'] ) ) {
						?>
						<div class="column-compatibility">
							<strong><?php esc_html_e( 'Last Updated:', 'wp-expand-tabs-free' ); ?></strong>
							<span><?php echo esc_html( human_time_diff( $plugin['last_updated'] ) ) . ' ' . esc_html__( 'ago', 'wp-expand-tabs-free' ); ?></span>
						</div>
									<?php
					}

					echo '</div>';
					?>
				</div>
				<?php
			}
		}
	}

	/**
	 * Check plugins installed function.
	 *
	 * @param string $plugin_slug Plugin slug.
	 * @param string $plugin_file Plugin file.
	 * @return boolean
	 */
	public function is_plugin_installed( $plugin_slug, $plugin_file ) {
		return file_exists( WP_PLUGIN_DIR . '/' . $plugin_slug . '/' . $plugin_file );
	}

	/**
	 * Check active plugin function
	 *
	 * @param string $plugin_slug Plugin slug.
	 * @param string $plugin_file Plugin file.
	 * @return boolean
	 */
	public function is_plugin_active( $plugin_slug, $plugin_file ) {
		return is_plugin_active( $plugin_slug . '/' . $plugin_file );
	}

	/**
	 * Install plugin link.
	 *
	 * @param string $plugin_slug Plugin slug.
	 * @return string
	 */
	public function install_plugin_link( $plugin_slug ) {
		return wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=' . $plugin_slug ), 'install-plugin_' . $plugin_slug );
	}

	/**
	 * Active Plugin Link function
	 *
	 * @param string $plugin_slug Plugin slug.
	 * @param string $plugin_file Plugin file.
	 * @return string
	 */
	public function activate_plugin_link( $plugin_slug, $plugin_file ) {
		return wp_nonce_url( admin_url( 'edit.php?post_type=sp_wp_tabs&page=tabs_help&action=activate&plugin=' . $plugin_slug . '/' . $plugin_file . '#recommended' ), 'activate-plugin_' . $plugin_slug . '/' . $plugin_file );
	}

	/**
	 * Making page as clean as possible
	 */
	public function disable_admin_notices() {

		global $wp_filter;

		if ( isset( $_GET['post_type'] ) && isset( $_GET['page'] ) && 'sp_wp_tabs' === wp_unslash( $_GET['post_type'] ) && in_array( wp_unslash( $_GET['page'] ), $this->pages ) ) { // @codingStandardsIgnoreLine

			if ( isset( $wp_filter['user_admin_notices'] ) ) {
				unset( $wp_filter['user_admin_notices'] );
			}
			if ( isset( $wp_filter['admin_notices'] ) ) {
				unset( $wp_filter['admin_notices'] );
			}
			if ( isset( $wp_filter['all_admin_notices'] ) ) {
				unset( $wp_filter['all_admin_notices'] );
			}
		}
	}

	/**
	 * The Smart Tabs Help Callback.
	 *
	 * @return void
	 */
	public function help_page_callback() {
		add_thickbox();

		$action   = isset( $_GET['action'] ) ? sanitize_text_field( wp_unslash( $_GET['action'] ) ) : '';
		$plugin   = isset( $_GET['plugin'] ) ? sanitize_text_field( wp_unslash( $_GET['plugin'] ) ) : '';
		$_wpnonce = isset( $_GET['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ) : '';

		if ( isset( $action, $plugin ) && ( 'activate' === $action ) && wp_verify_nonce( $_wpnonce, 'activate-plugin_' . $plugin ) ) {
			activate_plugin( $plugin, '', false, true );
		}

		if ( isset( $action, $plugin ) && ( 'deactivate' === $action ) && wp_verify_nonce( $_wpnonce, 'deactivate-plugin_' . $plugin ) ) {
			deactivate_plugins( $plugin, '', false, true );
		}

		?>
		<div class="sp-wp-tabs-help">
			<!-- Header section start -->
			<section class="sptabs__help header">
				<div class="sptabs-header-area-top">
				<p>
				<?php
					printf(
						/* translators: %1$s is the plugin name in bold, %2$s is the upgrade link in bold */
						esc_html__( 'You’re currently using %1$s. To access additional features, consider %2$s', 'wp-expand-tabs-free' ),
						'<b>' . esc_html__( 'Smart Tabs Lite', 'wp-expand-tabs-free' ) . '</b>',
						'<a target="_blank" href="' . esc_url( SP_SMART_TABS_PRO_LINK ) . '" ><b>' . esc_html__( 'upgrading to Pro!', 'wp-expand-tabs-free' ) . '</b></a>'
					);
				?>
				🚀</p>
				</div>
				<div class="sptabs-header-area">
					<div class="sptabs-container">
						<div class="sptabs-header-logo">
							<img src="<?php echo esc_url( WP_TABS_URL . 'admin/img/smart-tabs-logo-help-page.svg' ); ?>" alt="Smart Tabs logo for help page">
							<span><?php echo esc_html( WP_TABS_VERSION ); ?></span>
						</div>
					</div>
					<div class="sptabs-header-logo-shape">

						<img src="<?php echo esc_url( WP_TABS_URL . 'admin/img/smart-tabs-logo.svg' ); ?>" alt="">
					</div>
				</div>
				<div class="sptabs-header-nav">
					<div class="sptabs-container">
						<div class="sptabs-header-nav-menu">
							<ul>
								<li><a class="active" data-id="get-start-tab"  href="<?php echo esc_url( home_url( '' ) . '/wp-admin/edit.php?post_type=sp_wp_tabs&page=tabs_help#get-start' ); ?>"><i class="sptabs-icon-play"></i> <?php esc_html_e( 'Get Started', 'wp-expand-tabs-free' ); ?></a></li>
								<li><a href="<?php echo esc_url( home_url( '' ) . '/wp-admin/edit.php?post_type=sp_wp_tabs&page=tabs_help#recommended' ); ?>" data-id="recommended-tab"><i class="sptabs-icon-recommended"></i> <?php esc_html_e( 'Recommended', 'wp-expand-tabs-free' ); ?></a></li>
								<li><a href="<?php echo esc_url( home_url( '' ) . '/wp-admin/edit.php?post_type=sp_wp_tabs&page=tabs_help#lite-to-pro' ); ?>" data-id="lite-to-pro-tab"><i class="sptabs-icon-lite-to-pro-icon"></i> <?php esc_html_e( 'Lite Vs Pro', 'wp-expand-tabs-free' ); ?></a></li>
								<li><a href="<?php echo esc_url( home_url( '' ) . '/wp-admin/edit.php?post_type=sp_wp_tabs&page=tabs_help#about-us' ); ?>" data-id="about-us-tab"><i class="sptabs-icon-info-circled-alt"></i> <?php esc_html_e( 'About Us', 'wp-expand-tabs-free' ); ?></a></li>
							</ul>
						</div>
					</div>
				</div>
			</section>
			<!-- Header section end -->

			<!-- Start Page -->
			<section class="sptabs__help start-page" id="get-start-tab">
				<div class="sptabs-container">
					<div class="sptabs-start-page-wrap">
						<div class="sptabs-video-area">
							<h2 class='sptabs-section-title'><?php esc_html_e( 'Welcome to Smart Tabs!', 'wp-expand-tabs-free' ); ?></h2>
							<span class='sptabs-normal-paragraph'><?php esc_html_e( 'Thank you for installing Smart Tabs! This video will help you get started with the plugin. Enjoy!', 'wp-expand-tabs-free' ); ?></span>
							<iframe width="724" height="405" src="https://www.youtube.com/embed/LuZbygSTuDc" title="YouTube video player" frameborder="0" allowfullscreen></iframe>
							<ul>
								<li><a class='sptabs-medium-btn' href="<?php echo esc_url( home_url( '/' ) . 'wp-admin/edit.php?post_type=sp_products_tabs' ); ?>">Add Product Tabs</a></li>
								<b class="sptabs-separator-or">Or</b>
								<li class='sptabs-create-tab-group'><a class='sptabs-medium-btn' href="<?php echo esc_url( home_url( '/' ) . 'wp-admin/edit.php?post_type=sp_wp_tabs' ); ?>">Create Tab Group</a></li>
								<li><a target="_blank" class='sptabs-medium-btn arrow-btn' href="https://wptabs.com/"><?php esc_html_e( 'Explore Smart Tabs', 'wp-expand-tabs-free' ); ?> <i class="sptabs-icon-button-arrow-icon"></i></a></li>
							</ul>
						</div>
						<div class="sptabs-start-page-sidebar">
							<div class="sptabs-start-page-sidebar-info-box">
								<div class="sptabs-info-box-title">
									<h4><i class="sptabs-icon-doc-icon"></i> <?php esc_html_e( 'Documentation', 'wp-expand-tabs-free' ); ?></h4>
								</div>
								<span class='sptabs-normal-paragraph'><?php esc_html_e( 'Explore Smart Tabs plugin capabilities in our enriched documentation.', 'wp-expand-tabs-free' ); ?></span>
								<a target="_blank" class='sptabs-small-btn' href="https://wptabs.com/docs/">Browse Now</a>
							</div>
							<div class="sptabs-start-page-sidebar-info-box">
								<div class="sptabs-info-box-title">
									<h4><i class="sptabs-icon-support"></i> <?php esc_html_e( 'Technical Support', 'wp-expand-tabs-free' ); ?></h4>
								</div>
								<span class='sptabs-normal-paragraph'><?php esc_html_e( 'For personalized assistance, reach out to our skilled support team for prompt help.', 'wp-expand-tabs-free' ); ?></span>
								<a target="_blank" class='sptabs-small-btn' href="https://shapedplugin.com/create-new-ticket/"><?php esc_html_e( 'Ask Now', 'wp-expand-tabs-free' ); ?></a>
							</div>
							<div class="sptabs-start-page-sidebar-info-box">
								<div class="sptabs-info-box-title">
									<h4><i class="sptabs-icon-team-icon"></i> <?php esc_html_e( 'Join The Community', 'wp-expand-tabs-free' ); ?></h4>
								</div>
								<span class='sptabs-normal-paragraph'><?php esc_attr_e( 'Join the official ShapedPlugin Facebook group to share your experiences, thoughts, and ideas.', 'wp-expand-tabs-free' ); ?></span>
								<a target="_blank" class='sptabs-small-btn' href="https://www.facebook.com/groups/ShapedPlugin/"><?php esc_html_e( 'Join Now', 'wp-expand-tabs-free' ); ?></a>
							</div>
						</div>
					</div>
				</div>
			</section>

			<!-- Lite to Pro Page -->
			<section class="sptabs__help lite-to-pro-page" id="lite-to-pro-tab">
				<div class="sptabs-container">
					<div class="sptabs-call-to-action-top">
						<h2 class="sptabs-section-title"><?php esc_html_e( 'Lite vs Pro Comparison', 'wp-expand-tabs-free' ); ?></h2>
						<a target="_blank" href="<?php echo esc_url( SP_SMART_TABS_PRO_LINK ); ?>" class='sptabs-big-btn'><?php esc_html_e( 'Upgrade to Pro Now!', 'wp-expand-tabs-free' ); ?></a>
					</div>
					<div class="sptabs-lite-to-pro-wrap">
						<div class="sptabs-features">
							<ul>
								<li class='sptabs-header'>
									<span class='sptabs-title'><?php esc_html_e( 'FEATURES', 'wp-expand-tabs-free' ); ?></span>
									<span class='sptabs-free'><?php esc_html_e( 'Lite', 'wp-expand-tabs-free' ); ?></span>
									<span class='sptabs-pro'><i class='sptabs-icon-pro'></i> <?php esc_html_e( 'PRO', 'wp-expand-tabs-free' ); ?></span>
								</li>
								<li class='sptabs-body sptabs-wp-tabs-pro'>
									<span class='sptabs-title'><?php esc_html_e( 'WooCommerce Product Tabs', 'wp-expand-tabs-free' ); ?> </span>
									<span class='sptabs-free'></span>
									<span class='sptabs-pro'></span>
								</li>
								<li class='sptabs-body'>
									<span class='sptabs-title'><b><?php esc_html_e( 'Product Tab Layouts & Templates', 'wp-expand-tabs-free' ); ?></b></span>
									<span class='sptabs-free'><b>2</b></span>
									<span class='sptabs-pro'><b>6+</b></span>
								</li>

								<li class='sptabs-body'>
									<span class='sptabs-title'><?php esc_html_e( 'Vertical Tabs', 'wp-expand-tabs-free' ); ?></span>
									<span class='sptabs-free sptabs-close-icon'></span>
									<span class='sptabs-pro sptabs-check-icon'></span>
								</li>

								<li class='sptabs-body'>
									<span class='sptabs-title'><?php esc_html_e( 'Accordion Layout 🔥', 'wp-expand-tabs-free' ); ?> </span>
									<span class='sptabs-free sptabs-close-icon'></span>
									<span class='sptabs-pro sptabs-check-icon'></span>
								</li>
								<li class='sptabs-body'>
									<span class='sptabs-title'>
										<?php
										printf(
											/* translators: 1: opening strong tag, 2: closing strong tag, 3: 'more' link */
											esc_html__( '%1$sCustom Product Tab Types%2$s (Size Chart, Shipping, Image Gallery, Videos, FAQs, Forms, Downloads, Map/Location, and much more)', 'wp-expand-tabs-free' ),
											'<strong>',
											'</strong>'
										);
										?>
									</span>
									<span class='sptabs-free'><b>2</b></span>
									<span class='sptabs-pro'><b>8+</b></span>
								</li>
								<li class='sptabs-body'>
									<span class='sptabs-title'><?php esc_html_e( 'Display a Tab for All Products', 'wp-expand-tabs-free' ); ?></span>
									<span class='sptabs-pro sptabs-check-icon'></span>
									<span class='sptabs-pro sptabs-check-icon'></span>
								</li>
								<li class='sptabs-body'>
								<span class='sptabs-title'>
									<?php
									printf(
										/* translators: 1: opening strong tag, 2: closing strong tag */
										esc_html__( 'Display a Tab for %1$sSpecific Products, Categories%2$s, or %3$sBrands%4$s 🔥', 'wp-expand-tabs-free' ),
										'<strong>',
										'</strong>',
										'<strong>',
										'</strong>'
									);
									?>
								</span>
									<span class='sptabs-free sptabs-close-icon'></span>
									<span class='sptabs-pro sptabs-check-icon'></span>
								</li>

								<li class='sptabs-body'>
									<span class='sptabs-title'><?php esc_html_e( 'Exclude a Product Tab from Specific Products', 'wp-expand-tabs-free' ); ?></span>
									<span class='sptabs-free sptabs-check-icon'></span>
									<span class='sptabs-pro sptabs-check-icon'></span>
								</li>
								<li class='sptabs-body'>
									<span class='sptabs-title'>
									<?php
									printf(
										/* translators: 1: opening strong tag, 2: closing strong tag */
										esc_html__( '%1$sOverride/Edit%2$s Tab Content Per Product 🔥', 'wp-expand-tabs-free' ),
										'<strong>',
										'</strong>'
									);
									?>
									</span>
									<span class='sptabs-free sptabs-check-icon'></span>
									<span class='sptabs-pro sptabs-check-icon'></span>
								</li>

								<li class='sptabs-body'>
									<span class='sptabs-title'><?php esc_html_e( 'Custom Icons for Tab Names', 'wp-expand-tabs-free' ); ?></span>
									<span class='sptabs-free sptabs-close-icon'></span>
									<span class='sptabs-pro sptabs-check-icon'></span>
								</li>
								<li class='sptabs-body'>
									<span class='sptabs-title'>
									<?php
									printf(
										/* translators: 1: opening strong tag, 2: closing strong tag */
										esc_html__( 'Tab Position: %1$sBelow Product Summary 🔥%2$s', 'wp-expand-tabs-free' ),
										'<strong>',
										'</strong>'
									);
									?>
								</span>
									<span class='sptabs-free sptabs-close-icon'></span>
									<span class='sptabs-pro sptabs-check-icon'></span>
								</li>

								<li class='sptabs-body'>
									<span class='sptabs-title'><?php esc_html_e( 'Tab Navigation Trigger (Click or Mouseover)', 'wp-expand-tabs-free' ); ?></span>
									<span class='sptabs-free sptabs-check-icon'></span>
									<span class='sptabs-pro sptabs-check-icon'></span>
								</li>
								<li class='sptabs-body'>
									<span class='sptabs-title'><?php esc_html_e( 'Custom Spacing Between Tabs', 'wp-expand-tabs-free' ); ?> </span>
									<span class='sptabs-free sptabs-close-icon'></span>
									<span class='sptabs-pro sptabs-check-icon'></span>
								</li>
								<li class='sptabs-body'>
									<span class='sptabs-title'><?php esc_html_e( 'Custom Tab Open by Default (Initial Tab Open) 🔥', 'wp-expand-tabs-free' ); ?></span>
									<span class='sptabs-free sptabs-close-icon'></span>
									<span class='sptabs-pro sptabs-check-icon'></span>
								</li>
								<li class='sptabs-body'>
									<span class='sptabs-title'><?php esc_html_e( 'Custom Height for Tab Content Area', 'wp-expand-tabs-free' ); ?></span>
									<span class='sptabs-free sptabs-close-icon'></span>
									<span class='sptabs-pro sptabs-check-icon'></span>
								</li>
								<li class='sptabs-body'>
									<span class='sptabs-title'><?php esc_html_e( 'Multiple Tab Line Positions, Styles & Backgrounds', 'wp-expand-tabs-free' ); ?></span>
									<span class='sptabs-free sptabs-check-icon'></span>
									<span class='sptabs-pro sptabs-check-icon'></span>
								</li>
								<li class='sptabs-body'>
									<span class='sptabs-title'><?php esc_html_e( 'Custom Space Between Tabs & Description', 'wp-expand-tabs-free' ); ?></span>
									<span class='sptabs-free sptabs-check-icon'></span>
									<span class='sptabs-pro sptabs-check-icon'></span>
								</li>

								<li class='sptabs-body'>
									<span class='sptabs-title'>
									<?php esc_html_e( 'Horizontal Tabs Styles', 'wp-expand-tabs-free' ); ?>
									</span>
									<span class='sptabs-free'><b>2</b></span>
									<span class='sptabs-pro'><b>5</b></span>
								</li>
								<li class='sptabs-body'>
									<span class='sptabs-title'>
									<?php esc_html_e( 'Vertical Tabs Styles', 'wp-expand-tabs-free' ); ?>
									</span>
									<span class='sptabs-free'><b>1</b></span>
									<span class='sptabs-pro'><b>4</b></span>
								</li>

								<li class='sptabs-body'>
									<span class='sptabs-title'>
									<?php esc_html_e( 'Add Image Gallery Tabs with Lightbox', 'wp-expand-tabs-free' ); ?></span>
									<span class='sptabs-free sptabs-close-icon'></span>
									<span class='sptabs-pro sptabs-check-icon'></span>
								</li>
								<li class='sptabs-body'>
									<span class='sptabs-title'>
									<?php esc_html_e( 'Add Video Gallery Tabs with Lightbox (Self-Hosted, YouTube, etc.)', 'wp-expand-tabs-free' ); ?></span>
									<span class='sptabs-free sptabs-close-icon'></span>
									<span class='sptabs-pro sptabs-check-icon'></span>
								</li>
								<li class='sptabs-body'>
									<span class='sptabs-title'><?php esc_html_e( 'Full Styling Controls – Highly Customizable', 'wp-expand-tabs-free' ); ?> </span>
									<span class='sptabs-free sptabs-close-icon'></span>
									<span class='sptabs-pro sptabs-check-icon'></span>
								</li>
								<li class='sptabs-body'>
									<span class='sptabs-title'><?php esc_html_e( 'Advanced Typography (1500+ Google Fonts)', 'wp-expand-tabs-free' ); ?> </span>
									<span class='sptabs-free sptabs-close-icon'></span>
									<span class='sptabs-pro sptabs-check-icon'></span>
								</li>
								<li class='sptabs-body'>
									<span class='sptabs-title'><?php esc_html_e( 'Auto Switch Tabs to Accordion on Mobile 🔥', 'wp-expand-tabs-free' ); ?></span>
									<span class='sptabs-free sptabs-close-icon'></span>
									<span class='sptabs-pro sptabs-check-icon'></span>
								</li>
								<li class='sptabs-body'>
									<span class='sptabs-title'><?php esc_html_e( 'Custom Email Address for Contact Form Submissions', 'wp-expand-tabs-free' ); ?> </span>
									<span class='sptabs-free sptabs-close-icon'></span>
									<span class='sptabs-pro sptabs-check-icon'></span>
								</li>
								<li class='sptabs-body'>
									<span class='sptabs-title'><?php esc_html_e( 'Disable Default Tab Styles', 'wp-expand-tabs-free' ); ?> </span>
									<span class='sptabs-free sptabs-check-icon'></span>
									<span class='sptabs-pro sptabs-check-icon'></span>
								</li>
								<li class='sptabs-body'>
									<span class='sptabs-title'><?php esc_html_e( 'Rename Default WooCommerce Tabs (Description, Additional Info, Reviews).', 'wp-expand-tabs-free' ); ?> </span>
									<span class='sptabs-free sptabs-close-icon'></span>
									<span class='sptabs-pro sptabs-check-icon'></span>
								</li>
								<li class='sptabs-body'>
									<span class='sptabs-title'><?php esc_html_e( 'Replace Default Related Products with Custom, Specific, Best-Selling, or On-Sale Products.', 'wp-expand-tabs-free' ); ?> </span>
									<span class='sptabs-free sptabs-close-icon'></span>
									<span class='sptabs-pro sptabs-check-icon'></span>
								</li>
								<!-- WordPress Tab Group -->
								<li class='sptabs-body sptabs-wp-tabs-pro'>
									<span class='sptabs-title'><?php esc_html_e( 'WordPress Tabs', 'wp-expand-tabs-free' ); ?> </span>
									<span class='sptabs-free'></span>
									<span class='sptabs-pro'></span>
								</li>
								<li class='sptabs-body'>
									<span class='sptabs-title'><?php esc_html_e( 'Beautiful, Responsive Tab Layouts', 'wp-expand-tabs-free' ); ?> </span>
									<span class='sptabs-free'><b>8</b></span>
									<span class='sptabs-pro'><b>20+</b></span>
								</li>
								<li class='sptabs-body'>
									<span class='sptabs-title'><?php esc_html_e( 'Tabs Carousel (Scrollable, Variable Width & Infinite Loop)', 'wp-expand-tabs-free' ); ?></span>
									<span class='sptabs-free sptabs-close-icon'></span>
									<span class='sptabs-pro sptabs-check-icon'></span>
								</li>
								<li class='sptabs-body'>
									<span class='sptabs-title'><?php esc_html_e( 'Flexible Content Types (Text, Image & Video Gallery)', 'wp-expand-tabs-free' ); ?></span>
									<span class='sptabs-free'><b>Text Only</b></span>
									<span class='sptabs-pro sptabs-check-icon'></span>
								</li>
								<li class='sptabs-body'>
									<span class='sptabs-title'><?php esc_html_e( 'Supports All Content (HTML, Shortcodes, Images, Videos, Audio, etc.)', 'wp-expand-tabs-free' ); ?> </span>
									<span class='sptabs-free sptabs-check-icon'></span>
									<span class='sptabs-pro sptabs-check-icon'></span>
								</li>
								<li class='sptabs-body'>
									<span class='sptabs-title'><?php esc_html_e( 'Create Tabs from Any Post Type, Page, or Taxonomy', 'wp-expand-tabs-free' ); ?></span>
									<span class='sptabs-free sptabs-check-icon'></span>
									<span class='sptabs-pro sptabs-check-icon'></span>
								</li>
								<li class='sptabs-body'>
									<span class='sptabs-title'><?php esc_html_e( 'Unlimited Multi-level/Nested Tabs', 'wp-expand-tabs-free' ); ?></span>
									<span class='sptabs-free sptabs-check-icon'></span>
									<span class='sptabs-pro sptabs-check-icon'></span>
								</li>
								<li class='sptabs-body'>
									<span class='sptabs-title'><?php esc_html_e( 'Font Awesome Tab Icons Library or Image Icons', 'wp-expand-tabs-free' ); ?></span>
									<span class='sptabs-free sptabs-close-icon'></span>
									<span class='sptabs-pro sptabs-check-icon'></span>
								</li>
								<li class='sptabs-body'>
									<span class='sptabs-title'><?php esc_html_e( 'Tab Icon Styling (Size, Color, Position)', 'wp-expand-tabs-free' ); ?></span>
									<span class='sptabs-free sptabs-close-icon'></span>
									<span class='sptabs-pro sptabs-check-icon'></span>
								</li>
								<li class='sptabs-body'>
									<span class='sptabs-title'><?php esc_html_e( 'Add Subtitles to Tabs', 'wp-expand-tabs-free' ); ?></span>
									<span class='sptabs-free sptabs-close-icon'></span>
									<span class='sptabs-pro sptabs-check-icon'></span>
								</li>
								<li class='sptabs-body'>
									<span class='sptabs-title'><?php esc_html_e( 'Deep Linking / Custom Linking', 'wp-expand-tabs-free' ); ?></span>
									<span class='sptabs-free sptabs-close-icon'></span>
									<span class='sptabs-pro sptabs-check-icon'></span>
								</li>
								<li class='sptabs-body'>
									<span class='sptabs-title'><?php esc_html_e( 'Rich Text Editor', 'wp-expand-tabs-free' ); ?></span>
									<span class='sptabs-free sptabs-check-icon'></span>
									<span class='sptabs-pro sptabs-check-icon'></span>
								</li>
								<li class='sptabs-body'>
									<span class='sptabs-title'><?php esc_html_e( 'Make Specific Tabs Inactive', 'wp-expand-tabs-free' ); ?></span>
									<span class='sptabs-free sptabs-close-icon'></span>
									<span class='sptabs-pro sptabs-check-icon'></span>
								</li>

								<li class='sptabs-body'>
									<span class='sptabs-title'><?php esc_html_e( 'Tab Triggers (Click, Mouseover, AutoPlay)', 'wp-expand-tabs-free' ); ?></span>
									<span class='sptabs-free'><b>2 types</b></span>
									<span class='sptabs-pro'><b>3 types</b></span>
								</li>
								<li class='sptabs-body'>
									<span class='sptabs-title'><?php esc_html_e( 'Custom Tab Open by Default 🔥', 'wp-expand-tabs-free' ); ?></span>
									<span class='sptabs-free sptabs-close-icon'></span>
									<span class='sptabs-pro sptabs-check-icon'></span>
								</li>
								<li class='sptabs-body'>
									<span class='sptabs-title'><?php esc_html_e( 'Spacing Between Tabs & Description, and Padding', 'wp-expand-tabs-free' ); ?></span>
									<span class='sptabs-free sptabs-check-icon'></span>
									<span class='sptabs-pro sptabs-check-icon'></span>
								</li>
								<li class='sptabs-body'>
									<span class='sptabs-title'><?php esc_html_e( 'Custom Height for Tab Content Area', 'wp-expand-tabs-free' ); ?></span>
									<span class='sptabs-free sptabs-close-icon'></span>
									<span class='sptabs-pro sptabs-check-icon'></span>
								</li>
								<li class='sptabs-body'>
									<span class='sptabs-title'><?php esc_html_e( 'Custom HTML Tag Support for Tab Titles', 'wp-expand-tabs-free' ); ?></span>
									<span class='sptabs-free sptabs-check-icon'></span>
									<span class='sptabs-pro sptabs-check-icon'></span>
								</li>
								<li class='sptabs-body'>
									<span class='sptabs-title'><?php esc_html_e( 'Active Tab Arrow to Content', 'wp-expand-tabs-free' ); ?></span>
									<span class='sptabs-free sptabs-close-icon'></span>
									<span class='sptabs-pro sptabs-check-icon'></span>
								</li>
								<li class='sptabs-body'>
									<span class='sptabs-title'><?php esc_html_e( 'Active Tab Indicator Styles', 'wp-expand-tabs-free' ); ?></span>
									<span class='sptabs-free'><b>1</b></span>
									<span class='sptabs-pro'><b>3</b></span>
								</li>
								<li class='sptabs-body'>
									<span class='sptabs-title'><?php esc_html_e( 'Hide Tabs Hash (#) from Browser URL', 'wp-expand-tabs-free' ); ?></span>
									<span class='sptabs-free sptabs-close-icon'></span>
									<span class='sptabs-pro sptabs-check-icon'></span>
								</li>
								<li class='sptabs-body'>
									<span class='sptabs-title'><?php esc_html_e( 'Tab Animations', 'wp-expand-tabs-free' ); ?></span>
									<span class='sptabs-free'><b>2</b></span>
									<span class='sptabs-pro'><b>50+</b></span>
								</li>
								<li class='sptabs-body'>
									<span class='sptabs-title'><?php esc_html_e( 'Full Styling + Advanced Typography (1500+ Google Fonts)', 'wp-expand-tabs-free' ); ?></span>
									<span class='sptabs-free sptabs-close-icon'></span>
									<span class='sptabs-pro sptabs-check-icon'></span>
								</li>
								<li class='sptabs-body'>
									<span class='sptabs-title'><?php esc_html_e( 'Import/Export Tab Groups', 'wp-expand-tabs-free' ); ?></span>
									<span class='sptabs-free sptabs-check-icon'></span>
									<span class='sptabs-pro sptabs-check-icon'></span>
								</li>
								<li class='sptabs-body'>
									<span class='sptabs-title'><?php esc_html_e( 'All Premium Features & Enhancements', 'wp-expand-tabs-free' ); ?></span>
									<span class='sptabs-free sptabs-close-icon'></span>
									<span class='sptabs-pro sptabs-check-icon'></span>
								</li>
								<li class='sptabs-body'>
									<span class='sptabs-title'><?php esc_html_e( 'Priority Human Support', 'wp-expand-tabs-free' ); ?></span>
									<span class='sptabs-free sptabs-close-icon'></span>
									<span class='sptabs-pro sptabs-check-icon'></span>
								</li>
							</ul>
						</div>
						<div class="sptabs-upgrade-to-pro">
							<h2 class='sptabs-section-title'><?php esc_html_e( 'Upgrade to PRO & Enjoy Advanced Features!', 'wp-expand-tabs-free' ); ?></h2>
							<span class='sptabs-section-subtitle'>
								<?php
								printf(
									/* translators: %1$s Opening <b> tag, %2$s Closing <b> tag */
									esc_html__( 'Already, %1$s15000+%2$s people are using Smart Tabs on their websites to create beautiful showcase, why won\'t you!', 'wp-expand-tabs-free' ),
									'<b>',
									'</b>'
								);
								?>
							</span>
							<div class="sptabs-upgrade-to-pro-btn">
								<div class="sptabs-action-btn">
									<a target="_blank" href="<?php echo esc_url( SP_SMART_TABS_PRO_LINK ); ?>" class='sptabs-big-btn'><?php esc_html_e( 'Upgrade to Pro Now!', 'wp-expand-tabs-free' ); ?></a>
									<span class='sptabs-small-paragraph'><?php esc_html_e( '14-Day No-Questions-Asked', 'wp-expand-tabs-free' ); ?> <a target="_blank" href="https://shapedplugin.com/refund-policy/"><?php esc_html_e( 'Refund Policy', 'wp-expand-tabs-free' ); ?></a></span>
								</div>
								<a target="_blank" href="https://wptabs.com" class='sptabs-big-btn-border'><?php esc_html_e( 'See All Features', 'wp-expand-tabs-free' ); ?></a>
								<a target="_blank" href="https://wptabs.com/horizontal-tabs/" class='sptabs-big-btn-border sptabs-pro-live-demo'><?php esc_html_e( 'Pro Live Demo', 'wp-expand-tabs-free' ); ?></a>
							</div>
						</div>
					</div>
					<div class="sptabs-testimonial">
						<div class="sptabs-testimonial-title-section">
							<span class='sptabs-testimonial-subtitle'>
								<?php echo esc_html__( 'NO NEED TO TAKE OUR WORD FOR IT', 'wp-expand-tabs-free' ); ?>
							</span>
							<h2 class="sptabs-section-title">
								<?php echo esc_html__( 'Our Users Love Smart Tabs Pro!', 'wp-expand-tabs-free' ); ?>
							</h2>
						</div>
						<div class="sptabs-testimonial-wrap">
							<div class="sptabs-testimonial-area">
								<div class="sptabs-testimonial-content">
									<p><?php echo esc_html__( 'I’ve tried 3 other Gallery / Carousel plugins and this one is by far the easiest, lightweight and does exactly what I want! I had a minor glitch and support was very quick to fix it. Very happy and highly rec...', 'wp-expand-tabs-free' ); ?></p>
								</div>
								<div class="sptabs-testimonial-info">
									<div class="sptabs-img">
										<img src="<?php echo esc_url( WP_TABS_URL . 'admin/help-page/img/joyce.png' ); ?>" alt="">
									</div>
									<div class="sptabs-info">
										<h3><?php echo esc_html__( 'Joyce van den Berg', 'wp-expand-tabs-free' ); ?></h3>
										<div class="sptabs-star">
											<i><?php echo esc_html__( '★★★★★', 'wp-expand-tabs-free' ); ?></i>
										</div>
									</div>
								</div>
							</div>
							<div class="sptabs-testimonial-area">
								<div class="sptabs-testimonial-content">
									<p><?php echo esc_html__( 'The plugin works great and is a great addition to my site. The support has been tremendously! Fast and easy! They even helped with JS errors from other plugins. THANKS!...', 'wp-expand-tabs-free' ); ?></p>
								</div>
								<div class="sptabs-testimonial-info">
									<div class="sptabs-img">
										<img src="<?php echo esc_url( WP_TABS_URL . 'admin/help-page/img/sksposcho.png' ); ?>" alt="">
									</div>
									<div class="sptabs-info">
										<h3><?php echo esc_html__( 'Sksposcho', 'wp-expand-tabs-free' ); ?></h3>
										<div class="sptabs-star">
											<i><?php echo esc_html__( '★★★★★', 'wp-expand-tabs-free' ); ?></i>
										</div>
									</div>
								</div>
							</div>
							<div class="sptabs-testimonial-area">
								<div class="sptabs-testimonial-content">
									<p><?php echo esc_html__( 'The new WP-Tabs is a tab menu for people who love more features. First of all you have to be playful when creating the tabs. If – then the Pro Version includes the things that make creating them reall...', 'wp-expand-tabs-free' ); ?></p>
								</div>
								<div class="sptabs-testimonial-info">
									<div class="sptabs-img">
										<img src="<?php echo esc_url( WP_TABS_URL . 'admin/help-page/img/wegerl.png' ); ?>" alt="">
									</div>
									<div class="sptabs-info">
										<h3><?php echo esc_html__( 'Wegerl', 'wp-expand-tabs-free' ); ?></h3>
										<div class="sptabs-star">
											<i><?php echo esc_html__( '★★★★★', 'wp-expand-tabs-free' ); ?></i>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>

			<!-- Recommended Page -->
			<section id="recommended-tab" class="sptabs-recommended-page">
				<div class="sptabs-container">
					<h2 class="sptabs-section-title"><?php esc_html_e( 'Enhance your Website with our Free Robust Plugins', 'wp-expand-tabs-free' ); ?></h2>
					<div class="sptabs-wp-list-table plugin-install-php">
						<div class="sptabs-recommended-plugins" id="the-list">
							<?php
								$this->sptabs_plugins_info_api_help_page();
							?>
						</div>
					</div>
				</div>
			</section>

			<!-- About Page -->
			<section id="about-us-tab" class="sptabs__help about-page">
				<div class="sptabs-container">
					<div class="sptabs-about-box">
						<div class="sptabs-about-info">
							<h3><?php esc_html_e( 'A Highly Customizable WordPress Tabs plugin from the Smart Tabs Team, ShapedPlugin, LLC', 'wp-expand-tabs-free' ); ?></h3>
							<?php
								printf(
									/* translators: %1$s Opening <p> tag, %2$s Opening <b> tag, %3$s Closing <b> tag, %4$s Closing <p> tag */
									esc_html__( '%1$sAt %2$sShapedPlugin LLC%3$s, we have been looking for the best way to display WordPress content in a clean, organized tabbed navigation. Unfortunately, we couldn\'t find any suitable plugin that met our needs. Hence, we set a simple goal: to develop a responsive and drag & drop tabs builder plugin for WordPress with many customization options.%4$s', 'wp-expand-tabs-free' ),
									'<p>',
									'<b>',
									'</b>',
									'</p>'
								);
							?>

							<p><?php esc_html_e( 'The Smart Tabs plugin provides a convenient way to create visually appealing Tabs sections and WooCommerce custom tabs. Check it out now and experience the difference!', 'wp-expand-tabs-free' ); ?></p>
							<div class="sptabs-about-btn">
								<a target="_blank" href="https://wptabs.com" class='sptabs-medium-btn'><?php esc_html_e( 'Explore Smart Tabs', 'wp-expand-tabs-free' ); ?></a>
								<a target="_blank" href="https://shapedplugin.com/about-us/" class='sptabs-medium-btn sptabs-arrow-btn'><?php esc_html_e( 'More About Us', 'wp-expand-tabs-free' ); ?> <i class="sptabs-icon-button-arrow-icon"></i></a>
							</div>
						</div>
						<div class="sptabs-about-img">
							<img src="<?php echo esc_url( WP_TABS_URL . 'admin/help-page/img/shapedplugin-team.jpg' ); ?>" alt="ShapedPlugin Team">
							<span><?php esc_html_e( 'Team ShapedPlugin LLC at WordCamp Sylhet', 'wp-expand-tabs-free' ); ?></span>
						</div>
					</div>
					<?php
					$plugins_arr = get_transient( 'sptabs_plugins' );
					$plugin_icon = array();
					if ( is_array( $plugins_arr ) && ( count( $plugins_arr ) > 0 ) ) {
						foreach ( $plugins_arr as $plugin ) {
							$plugin_icon[ $plugin['slug'] ] = $plugin['icons'];
						}
					}
					?>
					<div class="sptabs-our-plugin-list">
						<h3 class="sptabs-section-title"><?php esc_html_e( 'Upgrade your Website with our High-quality Plugins!', 'wp-expand-tabs-free' ); ?></h3>
						<div class="sptabs-our-plugin-list-wrap">
							<a target="_blank" class="sptabs-our-plugin-list-box" href="https://wpcarousel.io/?ref=1">
								<i class="sptabs-icon-button-arrow-icon"></i>
								<img src="<?php echo esc_url( $plugin_icon['wp-carousel-free'] ); ?>" alt="WP Carousel">
								<h4><?php esc_html_e( 'WP Carousel', 'wp-expand-tabs-free' ); ?></h4>
								<p><?php esc_html_e( 'The most powerful and user-friendly multi-purpose carousel, slider, & gallery plugin for WordPress.', 'wp-expand-tabs-free' ); ?></p>
							</a>
							<a target="_blank" class="sptabs-our-plugin-list-box" href="https://realtestimonials.io/?ref=1">
								<i class="sptabs-icon-button-arrow-icon"></i>
								<img src="<?php echo esc_url( $plugin_icon['testimonial-free'] ); ?>" alt="Real Testimonials">
								<h4><?php esc_html_e( 'Real Testimonials', 'wp-expand-tabs-free' ); ?></h4>
								<p><?php esc_html_e( 'Simply collect, manage, and display Testimonials on your website and boost conversions.', 'wp-expand-tabs-free' ); ?></p>
							</a>
							<a target="_blank" class="sptabs-our-plugin-list-box" href="https://wpsmartpost.com/?ref=1">
								<i class="sptabs-icon-button-arrow-icon"></i>
								<img src="<?php echo esc_url( $plugin_icon['post-carousel'] ); ?>" alt="Smart Post">
								<h4><?php esc_html_e( 'Smart Post', 'wp-expand-tabs-free' ); ?></h4>
								<p><?php esc_html_e( 'Filter and display posts (any post types), pages, taxonomy, custom taxonomy, and custom field, in beautiful layouts.', 'wp-expand-tabs-free' ); ?></p>
							</a>
							<a target="_blank" href="https://wooproductslider.io/?ref=1" class="sptabs-our-plugin-list-box">
								<i class="sptabs-icon-button-arrow-icon"></i>
								<img src="<?php echo esc_url( $plugin_icon['woo-product-slider'] ); ?>" alt="Product Slider for WooCommerce">
								<h4><?php esc_html_e( 'Product Slider for WooCommerce', 'wp-expand-tabs-free' ); ?></h4>
								<p><?php esc_html_e( 'Boost sales by interactive product Slider, Grid, and Table in your WooCommerce website or store.', 'wp-expand-tabs-free' ); ?></p>
							</a>
							<a target="_blank" class="sptabs-our-plugin-list-box" href="https://woogallery.io/?ref=1">
								<i class="sptabs-icon-button-arrow-icon"></i>
								<img src="<?php echo esc_url( $plugin_icon['gallery-slider-for-woocommerce'] ); ?>" alt="WooGallery">
								<h4><?php esc_html_e( 'WooGallery', 'wp-expand-tabs-free' ); ?></h4>
								<p><?php esc_html_e( 'Product gallery slider and additional variation images gallery for WooCommerce and boost your sales.', 'wp-expand-tabs-free' ); ?></p>
							</a>
							<a target="_blank" class="sptabs-our-plugin-list-box" href="https://getwpteam.com/?ref=1">
								<i class="sptabs-icon-button-arrow-icon"></i>
								<img src="<?php echo esc_url( $plugin_icon['team-free'] ); ?>" alt="Smart Team">
								<h4><?php esc_html_e( 'Smart Team', 'wp-expand-tabs-free' ); ?></h4>
								<p><?php esc_html_e( 'Display your team members smartly who are at the heart of your company or organization!', 'wp-expand-tabs-free' ); ?></p>
							</a>
							<a target="_blank" class="sptabs-our-plugin-list-box" href="https://logocarousel.com/?ref=1">
								<i class="sptabs-icon-button-arrow-icon"></i>
								<img src="<?php echo esc_url( $plugin_icon['logo-carousel-free'] ); ?>" alt="<?php echo esc_attr__( 'Logo Carousel', 'wp-expand-tabs-free' ); ?>">
								<h4><?php echo esc_html__( 'Logo Carousel', 'wp-expand-tabs-free' ); ?></h4>
								<p><?php echo esc_html__( 'Showcase a group of logo images with Title, Description, Tooltips, Links, and Popup as a grid or in a carousel.', 'wp-expand-tabs-free' ); ?></p>
							</a>
							<a target="_blank" class="sptabs-our-plugin-list-box" href="https://easyaccordion.io/?ref=1">
								<i class="sptabs-icon-button-arrow-icon"></i>
								<img src="<?php echo esc_url( $plugin_icon['easy-accordion-free'] ); ?>" alt="<?php echo esc_attr__( 'Easy Accordion', 'wp-expand-tabs-free' ); ?>">
								<h4><?php echo esc_html__( 'Easy Accordion', 'wp-expand-tabs-free' ); ?></h4>
								<p><?php echo esc_html__( 'Minimize customer support by offering comprehensive FAQs and increasing conversions.', 'wp-expand-tabs-free' ); ?></p>
							</a>
							<a target="_blank" class="sptabs-our-plugin-list-box" href="https://shapedplugin.com/woocategory/?ref=1">
								<i class="sptabs-icon-button-arrow-icon"></i>
								<img src="<?php echo esc_url( $plugin_icon['woo-category-slider-grid'] ); ?>" alt="<?php echo esc_attr__( 'WooCategory', 'wp-expand-tabs-free' ); ?>">
								<h4><?php echo esc_html__( 'WooCategory', 'wp-expand-tabs-free' ); ?></h4>
								<p><?php echo esc_html__( 'Display by filtering the list of categories aesthetically and boosting sales.', 'wp-expand-tabs-free' ); ?></p>
							</a>
							<a target="_blank" class="sptabs-our-plugin-list-box" href="https://wptabs.com/?ref=1">
								<i class="sptabs-icon-button-arrow-icon"></i>
								<img src="<?php echo esc_url( $plugin_icon['wp-expand-tabs-free'] ); ?>" alt="<?php echo esc_attr__( 'Smart Tabs', 'wp-expand-tabs-free' ); ?>">
								<h4><?php echo esc_html__( 'Smart Tabs', 'wp-expand-tabs-free' ); ?></h4>
								<p><?php echo esc_html__( 'Display tabbed content smartly & quickly on your WordPress site without coding skills.', 'wp-expand-tabs-free' ); ?></p>
							</a>
							<a target="_blank" class="sptabs-our-plugin-list-box" href="https://locationweather.io/?ref=1">
								<i class="sptabs-icon-button-arrow-icon"></i>
								<img src="<?php echo esc_url( $plugin_icon['location-weather'] ); ?>" alt="<?php echo esc_attr__( 'Location Weather', 'wp-expand-tabs-free' ); ?>">
								<h4><?php echo esc_html__( 'Location Weather', 'wp-expand-tabs-free' ); ?></h4>
								<p><?php echo esc_html__( 'Best WordPress Weather Forecast plugin to Show Current Temperature & Weather, Daily & Hourly, Detailed Forecast up to 16 Days, and Weather Map.', 'wp-expand-tabs-free' ); ?></p>
							</a>
							<a target="_blank" class="sptabs-our-plugin-list-box" href="https://shapedplugin.com/plugin/smart-brands-for-woocommerce/?ref=1">
								<i class="sptabs-icon-button-arrow-icon"></i>
								<img src="<?php echo esc_url( $plugin_icon['smart-brands-for-woocommerce'] ); ?>" alt="<?php echo esc_attr__( 'Smart Brands for WooCommerce', 'wp-expand-tabs-free' ); ?>">
								<h4><?php echo esc_html__( 'Smart Brands for WooCommerce', 'wp-expand-tabs-free' ); ?></h4>
								<p><?php echo esc_html__( 'Smart Brands for WooCommerce Pro helps you display product brands in an attractive way on your online store.', 'wp-expand-tabs-free' ); ?></p>
							</a>
						</div>
					</div>
				</div>
			</section>

			<!-- Footer Section -->
			<section class="sptabs-footer">
				<div class="sptabs-footer-top">
					<?php
						/** Translators: %1$s Opening <span> tag, %2$s Closing <span> tag */
						printf(
							'<p><span>%1$s <i class="sptabs-icon-heart"></i></span> %2$s</p>',
							esc_html__( 'Made With', 'wp-expand-tabs-free' ),
							sprintf(
								/* translators: %1$s Opening <a> tag, %2$s Closing <a> tag */
								esc_html__( 'By the %1$sShapedPlugin LLC%2$s Team', 'wp-expand-tabs-free' ),
								'<a target="_blank" href="https://shapedplugin.com/">',
								'</a>'
							)
						);
					?>
					<p><?php echo esc_html__( 'Get connected with', 'wp-expand-tabs-free' ); ?></p>
					<ul>
						<li><a target="_blank" href="https://www.facebook.com/ShapedPlugin/"><i class="sptabs-icon-fb"></i></a></li>
						<li><a target="_blank" href="https://x.com/intent/follow?screen_name=ShapedPlugin"><i class="sptabs-icon-x"></i></a></li>
						<li><a target="_blank" href="https://profiles.wordpress.org/shapedplugin/#content-plugins"><i class="sptabs-icon-wp-icon"></i></a></li>
						<li><a target="_blank" href="https://youtube.com/@ShapedPlugin?sub_confirmation=1"><i class="sptabs-icon-youtube-play"></i></a></li>
					</ul>
				</div>
			</section>
		</div>
		<?php
	}
}

WP_Tabs_Help_Page::instance();
