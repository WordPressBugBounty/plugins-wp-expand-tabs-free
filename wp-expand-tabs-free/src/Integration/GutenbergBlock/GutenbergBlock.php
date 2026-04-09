<?php
/**
 * The plugin gutenberg block.
 *
 * @link       https://shapedplugin.com/
 * @since      2.1.5
 *
 * @package    WP_Tabs
 * @subpackage WP_Tabs/admin
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

namespace ShapedPlugin\SmartTabsFree\Integration\GutenbergBlock;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'ShapedPlugin\SmartTabsFree\Integration\GutenbergBlock\GutenbergBlock' ) ) {
	/**
	 * Custom Gutenberg Block.
	 */
	class GutenbergBlock {
		/**
		 * Block Initializer.
		 */
		public function __construct() {
			new Gutenberg_Block_Init();
		}
	}
}
