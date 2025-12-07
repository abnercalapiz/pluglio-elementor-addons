(function($) {
    'use strict';
    
    var resizeTimer; // Properly scoped resize timer
    
    var PluglioToggleSearch = function($scope, $) {
        var $wrapper = $scope.find('.pluglio-toggle-search-wrapper');
        var $button = $wrapper.find('.pluglio-toggle-search-button');
        var $searchIcon = $wrapper.find('.pluglio-search-icon');
        var $closeIcon = $wrapper.find('.pluglio-close-icon');
        var $container = $wrapper.find('.pluglio-search-form-container');
        var $input = $wrapper.find('.pluglio-search-input');
        var isOpen = false;
        
        function openSearch() {
            isOpen = true;
            $container.show().delay(10).queue(function(next) {
                $(this).addClass('active');
                next();
            });
            $searchIcon.hide();
            $closeIcon.show();
            $button.attr('aria-expanded', 'true');
            
            // Add body class for mobile
            if ($(window).width() <= 767) {
                $('body').addClass('pluglio-search-open');
            }
            
            // Focus on input after animation
            setTimeout(function() {
                $input.focus();
            }, 300);
        }
        
        function closeSearch() {
            isOpen = false;
            $container.removeClass('active');
            setTimeout(function() {
                $container.hide();
            }, 300);
            $searchIcon.show();
            $closeIcon.hide();
            $button.attr('aria-expanded', 'false');
            
            // Remove body class
            $('body').removeClass('pluglio-search-open');
        }
        
        function toggleSearch() {
            if (isOpen) {
                closeSearch();
            } else {
                openSearch();
            }
        }
        
        // Toggle button click
        $button.on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            toggleSearch();
        });
        
        // Close on clicking outside
        $(document).on('click.pluglioSearch' + $scope.data('id'), function(e) {
            if (isOpen && !$(e.target).closest('.pluglio-toggle-search-wrapper').length) {
                closeSearch();
            }
        });
        
        // Close on ESC key
        $(document).on('keydown.pluglioSearch' + $scope.data('id'), function(e) {
            if (isOpen && e.keyCode === 27) {
                closeSearch();
                $button.focus();
            }
        });
        
        // Prevent form container clicks from closing
        $container.on('click', function(e) {
            e.stopPropagation();
        });
        
        // Handle form submission
        $wrapper.find('.pluglio-search-form').on('submit', function(e) {
            var searchValue = $input.val().trim();
            if (searchValue === '') {
                e.preventDefault();
                $input.focus();
            }
        });
        
        // Handle window resize
        $(window).on('resize.pluglioSearch' + $scope.data('id'), function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                if ($(window).width() > 767 && isOpen) {
                    $('body').removeClass('pluglio-search-open');
                }
            }, 250);
        });
        
        // Cleanup on widget removal
        $scope.on('remove', function() {
            $(document).off('.pluglioSearch' + $scope.data('id'));
            $(window).off('.pluglioSearch' + $scope.data('id'));
        });
    };
    
    // Initialize on Elementor frontend ready
    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/pluglio-toggle-search.default', PluglioToggleSearch);
    });
    
})(jQuery);

// Add CSS for body class on mobile
jQuery(document).ready(function($) {
    if ($('style#pluglio-toggle-search-mobile').length === 0) {
        $('head').append('<style id="pluglio-toggle-search-mobile">body.pluglio-search-open { overflow: hidden; }</style>');
    }
});