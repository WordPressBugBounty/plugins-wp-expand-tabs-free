import icons from "./shortcode/blockIcon";
import DynamicShortcodeInput from "./shortcode/dynamicShortcode";
import { escapeAttribute, escapeHTML } from "@wordpress/escape-html";
import { InspectorControls } from '@wordpress/block-editor';

const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;
const { PanelBody, PanelRow } = wp.components;
const { Fragment } = wp.element;
const ServerSideRender = wp.serverSideRender;
const el = wp.element.createElement;

/**
 * Register: WP Tabs Gutenberg Block.
 */
registerBlockType("sp-wp-tabs-pro/shortcode", {
  title: escapeHTML(__("WP Tabs", "wp-expand-tabs-free")),
  description: escapeHTML(__(
    "Use WP Tabs to insert a tabs group in your page",
    "wp-expand-tabs-free"
  )),
  icon: icons.sptabfreeIcon,
  category: "common",
  supports: {
    html: true,
  },
  edit: (props) => {
    const { attributes, setAttributes } = props;
    var shortCodeList = sp_tab_free_gb_block.shortCodeList;

    let scriptLoad = (shortcodeId) => {
      let sptabfreeBlockLoaded = false;
      let sptabfreeBlockLoadedInterval = setInterval(function () {
        let uniqId = jQuery("#sp-wp-tabs-wrapper_" + shortcodeId).parents().attr('id');
        if (document.getElementById(uniqId)) {
          jQuery.getScript(sp_tab_free_gb_block.loadPublic);
          sptabfreeBlockLoaded = true;
          uniqId = '';
        }
        if (sptabfreeBlockLoaded) {
          clearInterval(sptabfreeBlockLoadedInterval);
        }
        if (0 == shortcodeId) {
          clearInterval(sptabfreeBlockLoadedInterval);
        }
      }, 10);
    }

    let updateShortcode = (updateShortcode) => {
      setAttributes({ shortcode: escapeAttribute(updateShortcode.target.value) });
    }

    let shortcodeUpdate = (e) => {
      updateShortcode(e);
      let shortcodeId = escapeAttribute(e.target.value);
      scriptLoad(shortcodeId);
    }

    if (jQuery('.sp-tab__lay-default:not(.sp-tab-loaded)').length > 0 ) {
      let shortcodeId = escapeAttribute( attributes.shortcode );
      scriptLoad(shortcodeId);
    }

    if (attributes.preview) {
      return (
        el('div', { className: 'sptabfree_shortcode_block_preview_image' },
          el('img', { src: escapeAttribute(sp_tab_free_gb_block.url + "admin/GutenbergBlock/assets/wp-tabs-block-preview.svg") })
        )
      )
    }

    if (shortCodeList.length === 0) {
      return (
        <Fragment>
          {
            el('div', { className: 'components-placeholder components-placeholder is-large' },
              el('div', { className: 'components-placeholder__label' },
                el('img', { className: 'block-editor-block-icon', src: escapeAttribute(sp_tab_free_gb_block.url + "admin/GutenbergBlock/assets/wp-tabs-icon.svg") }),
                escapeHTML(__("WP Tabs", "wp-expand-tabs-free"))
              ),
              el('div', { className: 'components-placeholder__instructions' },
                escapeHTML(__("No shortcode found. ", "wp-expand-tabs-free")),
                el('a', { href: escapeAttribute(sp_tab_free_gb_block.link) },
                  escapeHTML(__("Create a shortcode now!", "wp-expand-tabs-free"))
                )
              )
            )
          }
        </Fragment>
      );
    }

    if (!attributes.shortcode || attributes.shortcode == 0) {
      return (
        <Fragment>
          <InspectorControls>
            <PanelBody title="WP Tabs">
              <PanelRow>
                <DynamicShortcodeInput
                  attributes={attributes}
                  shortCodeList={shortCodeList}
                  shortcodeUpdate={shortcodeUpdate}
                />
              </PanelRow>
            </PanelBody>
          </InspectorControls>
          {
            el('div', { className: 'components-placeholder components-placeholder is-large' },
              el('div', { className: 'components-placeholder__label' },
                el('img', { className: 'block-editor-block-icon', src: escapeAttribute(sp_tab_free_gb_block.url + "admin/GutenbergBlock/assets/wp-tabs-icon.svg") }),
                escapeHTML(__("WP Tabs", "wp-expand-tabs-free"))
              ),
              el('div', { className: 'components-placeholder__instructions' }, escapeHTML(__("Select a tabs group", "wp-expand-tabs-free"))),
              <DynamicShortcodeInput
                attributes={attributes}
                shortCodeList={shortCodeList}
                shortcodeUpdate={shortcodeUpdate}
              />
            )
          }
        </Fragment>
      );
    }

    return (
      <Fragment>
        <InspectorControls>
          <PanelBody title="WP Tabs">
            <PanelRow>
              <DynamicShortcodeInput
                attributes={attributes}
                shortCodeList={shortCodeList}
                shortcodeUpdate={shortcodeUpdate}
              />
            </PanelRow>
          </PanelBody>
        </InspectorControls>
        <ServerSideRender block="sp-wp-tabs-pro/shortcode" attributes={attributes} />
      </Fragment>
    );
  },
  save() {
    // Rendering in PHP
    return null;
  },
});
