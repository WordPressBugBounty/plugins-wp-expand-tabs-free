
(function ($) {
    'use strict';
    $(function () {
        const $tabsWrapper = $('.woocommerce-tabs');
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
            const $wrapper = $('.woocommerce-tabs');

            // Check if wrapper exists to avoid errors
            if (!$wrapper.length) return;

            $wrapper.find('.wc-tabs').on('mouseenter', 'li', function () {
                var $tab = $(this);
                var targetPanel = $tab.find('a').attr('href');
                // Remove active from all tabs and panels
                $tab.siblings().removeClass('active');
                $('.woocommerce-Tabs-panel').removeClass('active').hide();
                // Activate hovered tab and its panel
                $tab.addClass('active');
                $(targetPanel).addClass('active').show();
            });
        }
    });
})(jQuery);