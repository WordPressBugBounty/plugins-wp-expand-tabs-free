import icons from "./shortcode/blockIcon";
import DynamicShortcodeInput from "./shortcode/dynamicShortcode";
import { escapeAttribute, escapeHTML } from "@wordpress/escape-html";
import { __ } from "@wordpress/i18n";
import { registerBlockType } from "@wordpress/blocks";
import { PanelBody, PanelRow } from "@wordpress/components";
import { createElement, useEffect, useRef } from "@wordpress/element";
import { InspectorControls, useBlockProps } from "@wordpress/block-editor";
import ServerSideRender from "@wordpress/server-side-render";

const el = createElement;

/**
 * `useBlockProps` was introduced with Block API v2 in WordPress 5.6. Older
 * releases still wrap the block themselves, so fall back to empty props there.
 */
const useBlockPropsCompat = "function" === typeof useBlockProps ? useBlockProps : () => ({});

/**
 * Register: Smart Tabs Gutenberg Block.
 */
registerBlockType("sp-wp-tabs-pro/shortcode", {
  // Block API v3 marks the block as compatible with the iframed editor canvas,
  // which WordPress 7.1 uses for every editor.
  apiVersion: 3,
  title: escapeHTML(__("Smart Tabs", "wp-expand-tabs-free")),
  description: escapeHTML(__(
    "Use Smart Tabs to insert a tabs group in your page",
    "wp-expand-tabs-free"
  )),
  icon: icons.sptabfreeIcon,
  category: "common",
  supports: {
    html: true,
  },
  edit: (props) => {
    const { attributes, setAttributes } = props;
    const shortCodeList = sp_tab_free_gb_block.shortCodeList;
    const isPreview = !! attributes.preview;
    const hasSelection = !! attributes.shortcode && 0 != attributes.shortcode;
    const previewRef = useRef(null);
    const blockProps = useBlockPropsCompat(
      isPreview ? { className: "sptabfree_shortcode_block_preview_image" } : {}
    );

    /**
     * Initialize the tabs group that `ServerSideRender` just rendered.
     *
     * Since WordPress 6.3 the block canvas is an iframe, so the rendered markup,
     * jQuery and the tabs scripts all live in `ownerDocument.defaultView` and not
     * in the editor window. The canvas document and the server-side render both
     * settle asynchronously, so wait for this block's own wrapper and for the
     * canvas scripts, then hand over to the front-end initializer.
     */
    useEffect(() => {
      if (isPreview || ! hasSelection) {
        return;
      }

      // `ServerSideRender` keeps the previous markup on screen while it reloads,
      // so match this block's own wrapper instead of any tabs group.
      const wrapperSelector = "#sp-wp-tabs-wrapper_" + String(attributes.shortcode).replace(/[^0-9]/g, "");
      const interval = 150;
      let attempts = 0;
      let waitedForScripts = 0;

      const timer = setInterval(() => {
        attempts++;

        // Bail out rather than polling forever when the render never arrives.
        if (attempts > 400) {
          clearInterval(timer);
          return;
        }

        const node = previewRef.current;
        const view = node && node.ownerDocument ? node.ownerDocument.defaultView : null;

        if (! node || ! view || ! view.jQuery || ! node.querySelector(wrapperSelector)) {
          return;
        }

        // The canvas scripts are parsed while the blocks mount, so make sure they
        // arrived before initializing. Stop waiting after five seconds and run
        // whatever is available in case a site filtered one of the handles out.
        if ("function" !== typeof view.SP_Tabs_Init) {
          waitedForScripts += interval;

          if (waitedForScripts < 5000) {
            return;
          }
        }

        clearInterval(timer);

        if ("function" === typeof view.SP_Tabs_Init) {
          view.SP_Tabs_Init();
        }
      }, interval);

      return () => clearInterval(timer);
    }, [isPreview, hasSelection, attributes.shortcode]);

    let updateShortcode = (updateShortcode) => {
      setAttributes({ shortcode: escapeAttribute(updateShortcode.target.value) });
    }

    let shortcodeUpdate = (e) => {
      updateShortcode(e);
    }

    if (isPreview) {
      return (
        el("div", blockProps,
          el("img", { src: escapeAttribute(sp_tab_free_gb_block.url + "assets/wp-tabs-block-preview.svg") })
        )
      )
    }

    if (shortCodeList.length === 0) {
      return (
        el("div", blockProps,
          el("div", { className: "components-placeholder components-placeholder is-large" },
            el("div", { className: "components-placeholder__label" },
              el("img", { className: "block-editor-block-icon", src: escapeAttribute(sp_tab_free_gb_block.url + "assets/wp-tabs-icon.svg") }),
              escapeHTML(__("Smart Tabs", "wp-expand-tabs-free"))
            ),
            el("div", { className: "components-placeholder__instructions" },
              escapeHTML(__("No shortcode found. ", "wp-expand-tabs-free")),
              el("a", { href: escapeAttribute(sp_tab_free_gb_block.link) },
                escapeHTML(__("Create a shortcode now!", "wp-expand-tabs-free"))
              )
            )
          )
        )
      );
    }

    if (! hasSelection) {
      return (
        el("div", blockProps,
          <InspectorControls>
            <PanelBody title="Smart Tabs">
              <PanelRow>
                <DynamicShortcodeInput
                  attributes={attributes}
                  shortCodeList={shortCodeList}
                  shortcodeUpdate={shortcodeUpdate}
                />
              </PanelRow>
            </PanelBody>
          </InspectorControls>,
          el("div", { className: "components-placeholder components-placeholder is-large" },
            el("div", { className: "components-placeholder__label" },
              el("img", { className: "block-editor-block-icon", src: escapeAttribute(sp_tab_free_gb_block.url + "assets/wp-tabs-icon.svg") }),
              escapeHTML(__("Smart Tabs", "wp-expand-tabs-free"))
            ),
            el("div", { className: "components-placeholder__instructions" }, escapeHTML(__("Select a tabs group", "wp-expand-tabs-free"))),
            <DynamicShortcodeInput
              attributes={attributes}
              shortCodeList={shortCodeList}
              shortcodeUpdate={shortcodeUpdate}
            />
          )
        )
      );
    }

    return (
      el("div", blockProps,
        <InspectorControls>
          <PanelBody title="Smart Tabs">
            <PanelRow>
              <DynamicShortcodeInput
                attributes={attributes}
                shortCodeList={shortCodeList}
                shortcodeUpdate={shortcodeUpdate}
              />
            </PanelRow>
          </PanelBody>
        </InspectorControls>,
        el("div", { ref: previewRef, className: "sptabfree-block-preview" },
          <ServerSideRender block="sp-wp-tabs-pro/shortcode" attributes={attributes} />
        )
      )
    );
  },
  save() {
    // Rendering in PHP
    return null;
  },
});
