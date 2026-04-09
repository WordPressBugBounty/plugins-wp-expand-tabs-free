
(function ($) {
    'use strict';

    /**
     * Initializes SP Smart Tabs.
     * 
     * @return {void}
     */
    function SPSmartTabsInit() {
        const $tabsWrapper = $('.woocommerce-tabs, .et_pb_wc_tabs');
        const tabsPreloader = sp_tabs_ajax.tabs_preloader;

        if ($tabsWrapper.length && tabsPreloader) {
            const preloader = `
                <div class="sptpro-tabs-preloader">
                    <span class="sptpro-spinner"></span>
                </div>`;
            $tabsWrapper.css('position', 'relative');
            $tabsWrapper.prepend(preloader);

            // Wait until the tabs have real content (not just the container)
            const interval = setInterval(function () {
                const hasPanels = $tabsWrapper.find('.panel').length > 0;
                // Check visible panel content.
                let panelToCheck = $tabsWrapper.find('.panel:visible');

                // If no visible tab content found, fallback to the first panel.
                if (!panelToCheck.length) {
                    panelToCheck = $tabsWrapper.find('.panel').first();
                }
                // Check if the panel has content.
                const hasContent = panelToCheck.length > 0 && panelToCheck.text().trim().length > 0;
                const tabsLoaded = hasPanels && hasContent;

                if (tabsLoaded) {
                    $('body').addClass('sptpro-tabs-loaded');
                    clearInterval(interval);
                }
            }, 100);
        }

        // Activate Tabs on Mouseenter.
        if ('tabs-activator-event-hover' === sp_tabs_ajax.activator_event) {
            const $wrapper = $('.woocommerce-tabs, .et_pb_wc_tabs');

            // Check if wrapper exists to avoid errors
            if (!$wrapper.length) return;

            $wrapper.find('.wc-tabs,.et_pb_tabs_controls').on('mouseenter', 'li', function () {
                var $tab = $(this);
                var targetPanel = $tab.find('a').attr('href');
                // Remove active from all tabs and panels
                $tab.siblings().removeClass('active et_pb_tab_active');
                $('.woocommerce-Tabs-panel, .et_pb_tab').removeClass('active et_pb_tab_active').hide();
                // Activate hovered tab and its panel
                $tab.addClass('active et_pb_tab_active');
                $(targetPanel).addClass('active et_pb_tab_active').show();
            });
        }

        /**
        * This function transforms the WooCommerce tabs into an accordion layout.
        * It adds a header element above each panel with the tab title and an icon.
        * Clicking on the header toggles the visibility of the corresponding panel.
        * 
        * @param {object} iconMap - An object containing the default and active states
        *                            of the icon to be used for each type.
        * @param {string} iconType - The type of icon to use. Defaults to 'plus-minus'.
        * 
        * Supported icon types:
        *  - plus-minus-light
        *  - plus-minus
        *  - chevron-bold
        *  - chevron-light
        *  - h-chevron-bold
        *  - h-chevron-light
        *  - v-caret
        *  - h-caret
        */
        function st_tabs_accordion_layout() {
            const $wrapper = $('.woocommerce-tabs, .et_pb_wc_tabs');
            // Define icon types and their corresponding classes.
            const iconMap = {
                'plus-minus-light': { default: 'sp-accordion-tabs-icon-plus', active: 'sp-accordion-tabs-icon-minus' },
                'plus-minus': { default: 'bold sp-accordion-tabs-icon-plus', active: 'bold sp-accordion-tabs-icon-minus' },
                'chevron-bold': { default: 'bold fa-angle-down', active: 'bold fa-angle-up' },
                'chevron-light': { default: 'fa-angle-down', active: 'fa-angle-up' },
                'h-chevron-bold': { default: 'bold fa-angle-right', active: 'bold fa-angle-down' },
                'h-chevron-light': { default: 'fa-angle-right', active: 'fa-angle-down' },
                'v-caret': { default: 'fa-caret-down', active: 'fa-caret-up' },
                'h-caret': { default: 'fa-caret-right', active: 'fa-caret-down' },
            };

            // Define wrapper, tabs, and panels.
            const $tabs = $wrapper.find('.wc-tabs li,.et_pb_tabs_controls li');
            const $panels = $wrapper.find('.woocommerce-Tabs-panel,.et_pb_tab');

            // Check if the accordion is already initialized to avoid duplication.
            if (!$wrapper.hasClass('accordion-enabled')) {
                $wrapper.addClass('accordion-enabled');

                const iconType = sp_tabs_ajax.toggle_icon_type || 'plus-minus';
                const icons = iconMap[iconType] || iconMap['plus-minus'];

                $panels.each(function (index) {
                    const $panel = $(this);
                    const tabTitle = $tabs.eq(index).text().trim();

                    if ($panel.prev('.accordion-header').length === 0) {
                        const $header = $(`
                        <div class="accordion-header">
                            <span class="accordion-title">${tabTitle}</span>
                            <i class="fa icon-responsive sp-accordion-icon" aria-hidden="true"></i>
                            ${sp_tabs_ajax.expand_collapse_icon && 'accordion_mode' === sp_tabs_ajax.small_screen_tab_layout
                                ? `<i class="fa ${icons.default} sp-accordion-icon" aria-hidden="true"></i>`
                                : ''}
                            ${sp_tabs_ajax.expand_collapse_icon && 'tabs-accordion' === sp_tabs_ajax.tabs_layout
                                ? `<i class="fa ${icons.default} sp-accordion-icon" aria-hidden="true"></i>`
                                : ''}
                        </div>
                    `);

                        $panel.before($header);
                    }
                    $panel.hide();

                    setTimeout(() => {
                        if ($panel.hasClass('active')) {
                            $panel.prev('.accordion-header')
                                .addClass('active')
                                .find('.sp-accordion-icon')
                                .removeClass(icons.default)
                                .addClass(icons.active);
                            $panel.show();
                        }
                    }, 10);
                });

                $wrapper.on('click', '.accordion-header', function () {
                    const $header = $(this);
                    const $icon = $header.find('.sp-accordion-icon');
                    const $panel = $header.next('.woocommerce-Tabs-panel,.et_pb_tab');

                    if (!$header.hasClass('active')) {
                        $wrapper.find('.accordion-header').removeClass('active')
                            .find('.sp-accordion-icon')
                            .removeClass(icons.active)
                            .addClass(icons.default);

                        $wrapper.find('.woocommerce-Tabs-panel,.et_pb_tab').stop(true, true).slideUp(200).removeClass('active');
                        $header.addClass('active');
                        $icon.removeClass(icons.default).addClass(icons.active);
                        $panel.stop(true, true).slideDown(200).addClass('active');
                    } else {
                        $header.removeClass('active');
                        $icon.removeClass(icons.active).addClass(icons.default);
                        $panel.stop(true, true).slideUp(200).removeClass('active');
                    }
                });
            }
        }

        /**
         * Tabs on small screen.
         */
        function handleTabsLayout() {
            if ('tabs-accordion' !== sp_tabs_ajax.tabs_layout) {
                const isSmallScreen = $(window).width() <= sp_tabs_ajax.set_min_small_screen_width;
                const isAccordionLayout = 'accordion_mode' === sp_tabs_ajax.small_screen_tab_layout;

                if (isSmallScreen && isAccordionLayout) {
                    // Show accordion, hide tabs
                    $('.woocommerce-tabs, .et_pb_wc_tabs').find('.wc-tabs,.et_pb_tabs_controls').hide();
                    $('.woocommerce-tabs, .et_pb_wc_tabs').find('.wc-accordion').show();
                    $('.woocommerce-tabs, .et_pb_wc_tabs').find('.accordion-header').show();
                    st_tabs_accordion_layout();
                } else {
                    // Show tabs, hide accordion
                    $('.woocommerce-tabs, .et_pb_wc_tabs').find('.wc-tabs,.et_pb_tabs_controls').show();
                    $('.woocommerce-tabs, .et_pb_wc_tabs').find('.wc-accordion').hide();
                    $('.woocommerce-tabs, .et_pb_wc_tabs').find('.accordion-header').hide();
                }

                // Set tabs nav items full width when screen mode "full_width" on small screen.
                if ('full_width' === sp_tabs_ajax.small_screen_tab_layout) {
                    if (isSmallScreen) {
                        $('.woocommerce-tabs, .et_pb_wc_tabs').find('.wc-tabs li,.et_pb_tabs_controls li').css('width', '100%');
                    } else {
                        $('.woocommerce-tabs, .et_pb_wc_tabs').find('.wc-tabs li,.et_pb_tabs_controls li').css('width', '');
                    }
                }
            }
        }

        // Run on page load
        $(document).ready(handleTabsLayout);

        // Run on window resize
        $(window).on('resize', handleTabsLayout);
    }

    // Initialize on document ready.
    $(function () {
        SPSmartTabsInit();
    });

    // Initialize on Elementor preview load.
    $(window).on('elementor/frontend/init', function () {
        SPSmartTabsInit();

        if (isElementorPreview()) {
            // Start polling.
            initTabs();
        }
    });

    // Check if we are in Elementor preview mode.
    function isElementorPreview() {
        return window.elementorFrontend && elementorFrontend.isEditMode();
    }

    // Initialize tabs with preloader support in Elementor preview mode.
    function initTabs() {
        const tabsPreloader = sp_tabs_ajax.tabs_preloader;
        const $tabsWrapper = $('.woocommerce-tabs, .et_pb_wc_tabs');

        if ($tabsWrapper.length === 0) {
            // Retry until tabs exist.
            setTimeout(initTabs, 200);
            return;
        }

        // Preloader
        if (tabsPreloader) {
            const preloader = `<div class="sptpro-tabs-preloader"><span class="sptpro-spinner"></span></div>`;
            $tabsWrapper.css('position', 'relative').prepend(preloader);
        }

        // Tab activator
        const tabIndex = 1;
        const $tabLinks = $tabsWrapper.find('.wc-tabs li, .et_pb_tabs_controls li');
        const $tabPanels = $tabsWrapper.find('.woocommerce-Tabs-panel, .et_pb_tab');

        $tabsWrapper.css({ opacity: 0, position: 'relative' });
        $tabLinks.removeClass('active');
        $tabPanels.removeClass('active').hide();

        if (tabIndex >= 1 && tabIndex <= $tabLinks.length) {
            const index = tabIndex - 1;
            const $targetTab = $tabLinks.eq(index);
            const targetHref = $targetTab.find('a').attr('href');
            $targetTab.addClass('active');
            $(targetHref).addClass('active').show();
        }

        $tabsWrapper.css({ opacity: 1 });
        $('body').addClass('sptpro-tabs-loaded');
    }

})(jQuery);