/**
 * Resources:
 * - 
 * - 
 */
(function($, window, document, undefined) {
    'use strict';

    var CONST = {
        
    }

    /* Main index functionality */
    var main = {
        initVariables: function() {
            
        },

        cacheElements: function() {

        },

        init: function() {
            this.initVariables();
            this.cacheElements();
            this.bindEvents();
            this.setAffix();
            this.initScrollReveal();
        },

        bindEvents: function() {            
            // Highlight the top nav as scrolling occurs
            $('body').scrollspy({
                target: '.navbar-fixed-top',
                offset: 51
            });
            // jQuery for page scrolling feature - requires jQuery Easing plugin
            $('a.page-scroll').bind('click', function(event) {
                var $anchor = $(this);
                $('html, body').stop().animate({
                    scrollTop: ($($anchor.attr('href')).offset().top - 50)
                }, 1250, 'easeInOutExpo');
                event.preventDefault();
            });
            // Closes the Responsive Menu on Menu Item Click
            $('.navbar-collapse ul li a').click(function() {
                $('.navbar-toggle:visible').click();
            });

        },
        // setAffix on navbar
        setAffix: function() {
            // Offset for Main Navigation
            $('#mainNav').affix({
                offset: {
                    top: 100
                }
            });
        },
        // Initialize and Configure Scroll Reveal Animation
        initScrollReveal: function() {
            window.sr = ScrollReveal();
            sr.reveal('.sr-contact', {
                duration: 600,
                scale: 0.3,
                distance: '0px'
            }, 300);
        }
        
    }

    $( document ).ready(function() {
        main.init();
    });

})(jQuery, window, document);
