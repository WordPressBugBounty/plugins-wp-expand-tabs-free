<?php
/**
 * Renders popup for the Product Tabs in admin table.
 *
 * Loads and defines the custom post type for this plugin
 * so that it is ready for admin menu under a different post type.
 *
 * @link       https://shapedplugin.com/
 * @since      3.2.1
 *
 * @package    ShapedPlugin\Modules\WPTabs
 * @subpackage Admin
 */

namespace ShapedPlugin\SmartTabsFree\Modules\ProductTabs\Admin\UI;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Popup
 *
 * Handles the rendering of popup UI elements in the admin area.
 */
class Popup {

	/**
	 * Register all hooks related to the tabbed header UI.
	 *
	 * @param Loader $loader The loader that's responsible for maintaining and registering all hooks.
	 * @return void
	 */
	public function register( $loader ) {
		$loader->add_action( 'admin_footer', $this, 'render_popup' );
	}

	/**
	 * Popup HTML for the "+x more" link in the "Show In" column.
	 *
	 * This function outputs the HTML structure and necessary JavaScript
	 * to handle the popup display when clicking on the "+x more" link
	 * in the "Show In" column of the product tabs admin list.
	 */
	public function render_popup() {
		?>
		<!-- Popup HTML Structure -->
		<div id="sp-tabs-popup">
			<div class="sp-tabs-popup-body"></div>
			<span class="sp-tabs-popup-close">&times;</span>
		</div>
		<!-- Popup CSS Styles -->
		<style>
			#sp-tabs-popup {
				position: absolute; /* absolute positioning to appear above button */
				display: none;
				z-index: 9999;
				background: #fff;
				border: 1px solid #ccc;
				padding: 10px;
				border-radius: 4px;
				box-shadow: 0 2px 8px rgba(0,0,0,0.2);
				max-width: 250px;
				width: auto;
				height: auto;
			}
			.sp-tabs-popup-overlay { 
				position:absolute;
				top:0;
				left:0;
				right:0;
				bottom:0; 
				background:rgba(0,0,0,0.4);
			}
			.sp-tabs-popup-content { 
				position: relative; 
				width: 400px; 
				max-width: 90%; 
				background:#fff; 
				margin:100px auto; 
				padding:20px; 
				border-radius:8px; 
				box-shadow:0 5px 20px rgba(0,0,0,0.2);
			}
			.sp-tabs-popup-close { 
				position: absolute;
				top: 0px;
				right: 10px;
				cursor: pointer;
				font-size: 18px;
				display:block;
				text-align:right;
				margin-top:5px;
			}
			.sp-tabs-popup-body {
				line-height: 1.4;
				margin-right: 30px;
			}
		</style>
		<!-- Popup JavaScript -->
		<script>
		jQuery(document).ready(function($) {
			$(document).on('click', '.sp-tabs-more-link', function(e) {
				e.preventDefault();
				var $button = $(this);
				var items   = $button.data('items');   // full list of terms
				var label   = $button.data('label');   // Category / Tags / Brand...
				var $popup  = $('#sp-tabs-popup');

				// Fill content
				$popup.find('.sp-tabs-popup-body').html(
					'<strong style="color: #8B9494;">' + label + '</strong>: ' + items
				);

				// Get button position
				var offset = $button.offset();
				var popupHeight = $popup.outerHeight();
				
				// Position above button
				$popup.css({
					top: offset.top - popupHeight - 50, // 50px above
					left: offset.left - 100,
					display: 'block'
				});
			});

			// Close popup when clicking overlay or close button.
			$(document).on('click', '.sp-tabs-popup-close', function() {
				$('#sp-tabs-popup').fadeOut(100);
			});

			// Click outside to close popup.
			$(document).on('click', function(e) {
				if (!$(e.target).closest('.sp-tabs-more-link, #sp-tabs-popup').length) {
					$('#sp-tabs-popup').fadeOut(100);
				}
			});
		});
		</script>
		<?php
	}
}
