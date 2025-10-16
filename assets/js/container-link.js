/**
 * Pluglio Container Link - Simple click handler
 */

(function($) {
    'use strict';
    
    function initContainerLinks() {
        $('.pluglio-container-link').each(function() {
            var $container = $(this);
            var url = $container.data('pluglio-link');
            var target = $container.data('pluglio-target') || '_self';
            
            if (!url) return;
            
            // Remove existing handlers to prevent duplicates
            $container.off('click.pluglio');
            
            // Add click handler
            $container.on('click.pluglio', function(e) {
                // Don't trigger if clicking on actual links, buttons, or form elements
                if ($(e.target).closest('a, button, input, select, textarea, .elementor-element-editable').length) {
                    return;
                }
                
                e.preventDefault();
                
                // Handle Ctrl/Cmd + Click for new tab
                if (e.ctrlKey || e.metaKey) {
                    window.open(url, '_blank');
                } else {
                    window.open(url, target);
                }
            });
            
            // Add cursor pointer
            $container.css('cursor', 'pointer');
        });
    }
    
    // Initialize on document ready
    $(document).ready(function() {
        initContainerLinks();
    });
    
    // Reinitialize in Elementor editor
    $(window).on('elementor/frontend/init', function() {
        if (window.elementorFrontend) {
            elementorFrontend.hooks.addAction('frontend/element_ready/container', function($scope) {
                setTimeout(initContainerLinks, 100);
            });
        }
    });
    
})(jQuery);