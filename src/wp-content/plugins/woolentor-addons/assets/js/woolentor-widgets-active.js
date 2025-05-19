;(function($){
"use strict";

    /**
     * Senitize HTML
     */
    var woolentorSanitizeHTML = function (str) {
        if( str ){
            return str.replace(/[&<>"']/g, function (c) {
                switch (c) {
                    case '&': return '&amp;';
                    case '<': return '&lt;';
                    case '>': return '&gt;';
                    case '"': return '&quot;';
                    case "'": return '&#39;';
                    default: return c;
                }
            });
        }else{
            return '';
        }
    }

    /**
     * Sanitize Object
     */
    var woolentorSanitizeObject = function (inputObj) {
        const sanitizedObj = {};
    
        for (let key in inputObj) {
            if (inputObj.hasOwnProperty(key)) {
                let value = inputObj[key];
    
                // Sanitize based on the value type
                if (typeof value === 'string') {
                    // Sanitize strings to prevent injection
                    sanitizedObj[key] = woolentorSanitizeHTML(value);
                } else if (typeof value === 'number') {
                    // Ensure numbers are valid (you could also set limits if needed)
                    sanitizedObj[key] = Number.isFinite(value) ? value : 0;
                } else if (typeof value === 'boolean') {
                    // Keep boolean values as they are
                    sanitizedObj[key] = value;
                } else {
                    // Handle other types if needed (e.g., arrays, objects)
                    sanitizedObj[key] = value;
                }
            }
        }
    
        return sanitizedObj;
    }

   /* 
    * Product Slider 
    */
    var WidgetProductSliderHandler = function ($scope, $) {

        var slider_elem = $scope.find('.product-slider').eq(0);

        if (slider_elem.length > 0) {

            slider_elem[0].style.display='block';

            var settings = woolentorSanitizeObject(slider_elem.data('settings'));
            var arrows = settings['arrows'];
            var dots = settings['dots'];
            var autoplay = settings['autoplay'];
            var infinite = settings.hasOwnProperty('infinite') ? settings['infinite'] : true;
            var rtl = settings['rtl'];
            var autoplay_speed = parseInt(settings['autoplay_speed']) || 3000;
            var animation_speed = parseInt(settings['animation_speed']) || 300;
            var fade = settings['fade'];
            var pause_on_hover = settings['pause_on_hover'];
            var display_columns = parseInt(settings['product_items']) || 4;
            var scroll_columns = parseInt(settings['scroll_columns']) || 4;
            var tablet_width = parseInt(settings['tablet_width']) || 800;
            var tablet_display_columns = parseInt(settings['tablet_display_columns']) || 2;
            var tablet_scroll_columns = parseInt(settings['tablet_scroll_columns']) || 2;
            var mobile_width = parseInt(settings['mobile_width']) || 480;
            var mobile_display_columns = parseInt(settings['mobile_display_columns']) || 1;
            var mobile_scroll_columns = parseInt(settings['mobile_scroll_columns']) || 1;

            slider_elem.not('.slick-initialized').slick({
                arrows: arrows,
                prevArrow: '<button type="button" class="slick-prev"><i class="fa fa-angle-left"></i></button>',
                nextArrow: '<button type="button" class="slick-next"><i class="fa fa-angle-right"></i></button>',
                dots: dots,
                infinite: infinite,
                autoplay: autoplay,
                autoplaySpeed: autoplay_speed,
                speed: animation_speed,
                fade: false,
                pauseOnHover: pause_on_hover,
                slidesToShow: display_columns,
                slidesToScroll: scroll_columns,
                rtl: rtl,
                responsive: [
                    {
                        breakpoint: tablet_width,
                        settings: {
                            slidesToShow: tablet_display_columns,
                            slidesToScroll: tablet_scroll_columns
                        }
                    },
                    {
                        breakpoint: mobile_width,
                        settings: {
                            slidesToShow: mobile_display_columns,
                            slidesToScroll: mobile_scroll_columns
                        }
                    }
                ]
            });
        };
    };

    /*
    * Custom Tab
    */
    function woolentor_tabs( $tabmenus, $tabpane ){
        $tabmenus.on('click', 'a', function(e){
            e.preventDefault();
            var $this = $(this),
                $target = $this.attr('href');
            $this.addClass('htactive').parent().siblings().children('a').removeClass('htactive');
            $( $tabpane + $target ).addClass('htactive').siblings().removeClass('htactive');

            // slick refresh
            if( $('.slick-slider').length > 0 ){
                var $id = $this.attr('href');
                $( $id ).find('.slick-slider').slick('refresh');
            }

        });
    }

    /* 
    * Universal product 
    */
    function productImageThumbnailsSlider( $slider ){
        $slider.slick({
            dots: true,
            arrows: true,
            prevArrow: '<button class="slick-prev"><i class="sli sli-arrow-left"></i></button>',
            nextArrow: '<button class="slick-next"><i class="sli sli-arrow-right"></i></button>',
        });
    }
    if( $(".ht-product-image-slider").length > 0 ) {
        productImageThumbnailsSlider( $(".ht-product-image-slider") );
    }

    var WidgetThumbnaisImagesHandler = function thumbnailsimagescontroller(){
        woolentor_tabs( $(".ht-product-cus-tab-links"), '.ht-product-cus-tab-pane' );
        woolentor_tabs( $(".ht-tab-menus"), '.ht-tab-pane' );

        // Countdown
        var finalTime, daysTime, hours, minutes, second;
        $('.ht-product-countdown').each(function() {
            var $this = $(this), finalDate = $(this).data('countdown');
            var customlavel = $(this).data('customlavel');
            $this.countdown(finalDate, function(event) {
                $this.html(event.strftime('<div class="cd-single"><div class="cd-single-inner"><h3>%D</h3><p>'+woolentorSanitizeHTML(customlavel.daytxt)+'</p></div></div><div class="cd-single"><div class="cd-single-inner"><h3>%H</h3><p>'+woolentorSanitizeHTML(customlavel.hourtxt)+'</p></div></div><div class="cd-single"><div class="cd-single-inner"><h3>%M</h3><p>'+woolentorSanitizeHTML(customlavel.minutestxt)+'</p></div></div><div class="cd-single"><div class="cd-single-inner"><h3>%S</h3><p>'+woolentorSanitizeHTML(customlavel.secondstxt)+'</p></div></div>'));
            });
        });

    }

    /*
    * Tool Tip
    */
    function woolentor_tool_tips(element, content) {
        if ( content == 'html' ) {
            var tipText = element.text();
        } else {
            var tipText = element.attr('title');
        }
        element.on('mouseover', function() {
            if ( $('.woolentor-tip').length == 0 ) {
                element.before('<span class="woolentor-tip">' + woolentorSanitizeHTML(tipText) + '</span>');
                $('.woolentor-tip').css('transition', 'all 0.5s ease 0s');
                $('.woolentor-tip').css('margin-left', 0);
            }
        });
        element.on('mouseleave', function() {
            $('.woolentor-tip').remove();
        });
    }

    /*
    * Tooltip Render
    */
    var WidgetWoolentorTooltipHandler = function woolentor_tool_tip(){
        $('a.woolentor-compare').each(function() {
            woolentor_tool_tips( $(this), 'title' );
        });
        $('.woolentor-cart a.add_to_cart_button,.woolentor-cart a.added_to_cart,.woolentor-cart a.button').each(function() {
            woolentor_tool_tips( $(this), 'html');
        });
        $('a.woolentor-quick-checkout-button').each(function() {
            woolentor_tool_tips( $(this), 'title' );
        });
    }

    /*
    * Product Tab
    */
    var  WidgetProducttabsHandler = woolentor_tabs( $(".ht-tab-menus"),'.ht-tab-pane' );

    /*
    * Single Product Video Gallery tab
    */
    var WidgetProductVideoGallery = function thumbnailsvideogallery(){
        woolentor_tabs( $(".woolentor-product-video-tabs"), '.video-cus-tab-pane' );
    }

    /**
     * WoolentorAccordion
     */
    var WoolentorAccordion = function ( $scope, $ ){
        var accordion_elem = $scope.find('.htwoolentor-faq').eq(0);

        var data_opt = accordion_elem.data('settings');

        if ( accordion_elem.length > 0 ) {
            var $id = accordion_elem.attr('id');
            new Accordion('#' + $id, {
                duration: 500,
                showItem: data_opt.showitem,
                elementClass: 'htwoolentor-faq-card',
                questionClass: 'htwoolentor-faq-head',
                answerClass: 'htwoolentor-faq-body',
            });
        }
        
    };


    /**
     * WoolentorOnePageSlider
     */
    var WoolentorOnePageSlider = function ( $scope, $ ){

        var slider_elem = $scope.find('.ht-full-slider-area').eq(0);

        if ( slider_elem.length > 0 ) {

            /* Jarallax active  */
            $('.ht-parallax-active').jarallax({
                speed: 0.4,
            });
            
            $('#ht-nav').onePageNav({
                currentClass: 'current',
                changeHash: false,
                scrollSpeed: 750,
                scrollThreshold: 0.5,
                filter: '',
                easing: 'swing',
            });
            
            /*------ Wow Active ----*/
            new WOW().init();

            /*---------------------
            Video popup
            --------------------- */
            $('.ht-video-popup').magnificPopup({
                type: 'iframe',
                mainClass: 'mfp-fade',
                removalDelay: 160,
                preloader: false,
                zoom: {
                    enabled: true,
                }
            });
    
        }

    };

    /*
    * Run this code under Elementor.
    */
    $(window).on('elementor/frontend/init', function () {

        elementorFrontend.hooks.addAction( 'frontend/element_ready/woolentor-product-tab.default', WidgetProductSliderHandler);
        elementorFrontend.hooks.addAction( 'frontend/element_ready/woolentor-product-tab.default', WidgetProducttabsHandler);

        elementorFrontend.hooks.addAction( 'frontend/element_ready/woolentor-universal-product.default', WidgetProductSliderHandler);
        elementorFrontend.hooks.addAction( 'frontend/element_ready/woolentor-universal-product.default', WidgetWoolentorTooltipHandler);
        elementorFrontend.hooks.addAction( 'frontend/element_ready/woolentor-universal-product.default', WidgetThumbnaisImagesHandler);

        elementorFrontend.hooks.addAction( 'frontend/element_ready/woolentor-cross-sell-product-custom.default', WidgetProductSliderHandler);
        elementorFrontend.hooks.addAction( 'frontend/element_ready/woolentor-cross-sell-product-custom.default', WidgetWoolentorTooltipHandler);
        elementorFrontend.hooks.addAction( 'frontend/element_ready/woolentor-cross-sell-product-custom.default', WidgetThumbnaisImagesHandler);

        elementorFrontend.hooks.addAction( 'frontend/element_ready/woolentor-upsell-product-custom.default', WidgetProductSliderHandler);
        elementorFrontend.hooks.addAction( 'frontend/element_ready/woolentor-upsell-product-custom.default', WidgetWoolentorTooltipHandler);
        elementorFrontend.hooks.addAction( 'frontend/element_ready/woolentor-upsell-product-custom.default', WidgetThumbnaisImagesHandler);

        elementorFrontend.hooks.addAction( 'frontend/element_ready/woolentor-related-product-custom.default', WidgetProductSliderHandler);
        elementorFrontend.hooks.addAction( 'frontend/element_ready/woolentor-related-product-custom.default', WidgetWoolentorTooltipHandler);
        elementorFrontend.hooks.addAction( 'frontend/element_ready/woolentor-related-product-custom.default', WidgetThumbnaisImagesHandler);

        elementorFrontend.hooks.addAction( 'frontend/element_ready/wl-product-video-gallery.default', WidgetProductVideoGallery );
        
        elementorFrontend.hooks.addAction( 'frontend/element_ready/wl-brand-logo.default', WidgetProductSliderHandler );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/wl-faq.default', WoolentorAccordion );
        
        elementorFrontend.hooks.addAction( 'frontend/element_ready/wl-category-grid.default', WidgetProductSliderHandler );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/wl-testimonial.default', WidgetProductSliderHandler );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/wl-product-grid.default', WidgetProductSliderHandler );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/wl-recently-viewed-products.default', WidgetProductSliderHandler );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/wl-onepage-slider.default', WoolentorOnePageSlider );

        elementorFrontend.hooks.addAction( 'frontend/element_ready/wl-customer-veview.default', WidgetProductSliderHandler );

    });


})(jQuery);