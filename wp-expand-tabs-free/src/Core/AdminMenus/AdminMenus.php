<?php
/**
 * Allows to create Admin Submenus of the Plugin.
 *
 * @link       https://shapedplugin.com/
 * @since      3.2.0
 *
 * @package    ShapedPlugin\SmartTabsFree
 * @subpackage Core\AdminMenus
 */

namespace ShapedPlugin\SmartTabsFree\Core\AdminMenus;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access.

use ShapedPlugin\SmartTabsFree\Core\Constants;

/**
 * The help class for the Smart Tabs Free
 */
class AdminMenus {
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
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the ShortCode-based Tabs module.
	 *
	 * @var Loader
	 */
	protected $loader;

	/**
	 * Not show this plugin list.
	 *
	 * @var array
	 */
	protected static $not_show_plugin_list = array( 'aitasi-coming-soon', 'latest-posts', 'widget-post-slider', 'easy-lightbox-wp' );

	/**
	 * Constructor for the AdminMenus class.
	 *
	 * Initializes the class and registers all hooks using the provided loader instance.
	 *
	 * @param Loader $loader The loader to register all hooks.
	 */
	public function __construct( $loader ) {
		$this->loader = $loader;

		$this->loader->add_action( 'admin_menu', $this, 'help_admin_menus', 80 );
		$this->loader->add_filter( 'admin_footer_text', $this, 'sptpro_review_text', 10, 2 );
		$this->loader->add_filter( 'plugin_action_links', $this, 'sptpro_plugin_action_links', 10, 2 );
		$this->loader->add_filter( 'update_footer', $this, 'sptpro_version_text', 11 );

        $page   = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : '';// @codingStandardsIgnoreLine
		if ( 'tabs_help' !== $page ) {
			return;
		}

		$this->loader->add_action( 'admin_print_scripts', $this, 'disable_admin_notices' );
		$this->loader->add_action( 'wptabspro_enqueue', $this, 'help_page_enqueue_scripts' );
	}

	/**
	 * Help_page_enqueue_scripts function.
	 *
	 * @return void
	 */
	public function help_page_enqueue_scripts() {
		wp_enqueue_style( 'sp-wp-tabs-help', Constants::help_page_assets() . 'css/help-page.min.css', array(), Constants::VERSION );
		wp_enqueue_style( 'sp-wp-tabs-help-fontello', Constants::help_page_assets() . 'css/fontello.min.css', array(), Constants::VERSION );

		wp_enqueue_script( 'sp-wp-tabs-help', Constants::help_page_assets() . 'js/help-page.min.js', array(), Constants::VERSION, true );
	}

	/**
	 * Add admin menu.
	 *
	 * @return void
	 */
	public function help_admin_menus() {
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
		add_submenu_page(
			'edit.php?post_type=sp_wp_tabs',
			__( 'Upgrade to Pro', 'wp-expand-tabs-free' ),
			'<a class="sp-wp-tabs-upgrade-btn-wrapper" href="https://wptabs.com/pricing/?ref=1" target="_blank">
        		<span class="sp-wp-tabs-upgrade-btn">
            		<span class="sp-wp-tabs-go-pro-icon"></span> Upgrade to Pro
        		</span>
    		</a>',
			'manage_options',
			'tabs_help',
			'__return_null',
			80
		);
	}

	/**
	 * Sptabs_ajax_help_page function.
	 *
	 * @return void
	 */
	public function sptabs_plugins_info_api_help_page() {
		$plugins_arr = get_transient( 'sptabs_plugins' );
		if ( false === $plugins_arr || true ) {
			require_once ABSPATH . 'wp-admin/includes/plugin-install.php'; // For plugins_api().

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
			$api_response = plugins_api( 'query_plugins', $args );

			if ( ! is_wp_error( $api_response ) && isset( $api_response->plugins ) ) {
				$plugins_arr = array();

				if ( ! isset( $api_response->plugins ) && ! ( count( $plugins->plugins ) > 0 ) ) {
					return;
				}

				foreach ( $api_response->plugins as $pl ) {

					if ( ! in_array( $pl['slug'], self::$not_show_plugin_list, true ) ) {
						$slug = isset( $pl['slug'] ) ? $pl['slug'] : '';

						$plugins_arr[] = array(
							'slug'              => $slug,
							'name'              => isset( $pl['name'] ) ? $pl['name'] : '',
							'version'           => isset( $pl['version'] ) ? $pl['version'] : '',
							'downloaded'        => isset( $pl['downloaded'] ) ? $pl['downloaded'] : 0,
							'active_installs'   => isset( $pl['active_installs'] ) ? $pl['active_installs'] : 0,
							'last_updated'      => isset( $pl['last_updated'] ) ? strtotime( $pl['last_updated'] ) : 0,
							'rating'            => isset( $pl['rating'] ) ? $pl['rating'] : 0,
							'num_ratings'       => isset( $pl['num_ratings'] ) ? $pl['num_ratings'] : 0,
							'short_description' => isset( $pl['short_description'] ) ? $pl['short_description'] : '',
							// Safe icon extraction.
							'icons'             => isset( $pl['icons']['2x'] ) ? $pl['icons']['2x'] : ( isset( $pl['icons']['1x'] ) ? $pl['icons']['1x'] : '' ),
						);
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

		include_once plugin_dir_path( __FILE__ ) . '/help-page.php';
	}

	/**
	 * Add plugin action menu
	 *
	 * @param array $links The action link.
	 * @param array $file The file link.
	 *
	 * @return array
	 */
	public function sptpro_plugin_action_links( $links, $file ) {
		if ( Constants::basename() === $file ) {
			$post_type = class_exists( 'WooCommerce' ) ? 'sp_products_tabs' : 'sp_wp_tabs';
			$new_links = array(
				sprintf( '<a href="%s">%s</a>', admin_url( 'edit.php?post_type=' . $post_type ), __( 'Settings', 'wp-expand-tabs-free' ) ),
			);
			$links[]   = '<a href="' . esc_url( Constants::PRO_LINK ) . '" style="color: #35b747; font-weight: 700;">' . __( 'Go Pro!', 'wp-expand-tabs-free' ) . '</a>';

			return array_merge( $new_links, $links );
		}

		return $links;
	}

	/**
	 * Bottom review notice.
	 *
	 * @param string $text The review notice.
	 * @return string
	 */
	public function sptpro_review_text( $text ) {
		$screen = get_current_screen();
		if ( 'sp_wp_tabs' === $screen->post_type || 'sp_products_tabs' === $screen->post_type ) {
			$url  = 'https://wordpress.org/support/plugin/wp-expand-tabs-free/reviews/';
			$text = sprintf(
				/* translators: 1: start strong tag, 2: close strong tag, 3: start a tag, 4: close a tag. */
				__( 'Enjoying %1$sSmart Tabs?%2$s Please rate us %3$sWordPress.org%4$s. Your positive feedback will help us grow more. Thank you! 😊', 'wp-expand-tabs-free' ),
				'<strong>',
				'</strong>',
				'<span class="sptabs-footer-text-star">★★★★★</span> <a href="' . esc_url( $url ) . '" target="_blank">',
				'</a>'
			);
		}

		return $text;
	}

	/**
	 * Bottom version notice.
	 *
	 * @param string $text Version notice.
	 * @return string
	 */
	public function sptpro_version_text( $text ) {
		$screen = get_current_screen();
		if ( 'sp_wp_tabs' === $screen->post_type || 'sp_products_tabs' === $screen->post_type ) {
			$text = 'Smart Tabs ' . Constants::VERSION;
		}
		return $text;
	}
}
