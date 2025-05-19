;(function($){
    'use strict';

    var WoolentorCrossSellPopup = {
        init: function(){
            this.popup = $('#woolentor-cross-sell-popup');
            this.isProcessing = false; // Add processing state
            this.bindEvents();
            this.initCustomStyles();
        },

        initCustomStyles: function(){
            var style = document.createElement('style');
            var css = '';

            // Add custom styles from settings
            if(typeof WoolentorCrossSell !== 'undefined'){
                if(WoolentorCrossSell?.settings?.popup_width){
                    css += '.woolentor-popup-wrapper { max-width: ' + WoolentorCrossSell.settings.popup_width + '; }';
                }
                if(WoolentorCrossSell?.settings?.button_color){
                    css += '.woolentor-add-to-cart { --button-color: ' + WoolentorCrossSell.settings.button_color + '; }';
                }
                if(WoolentorCrossSell?.settings?.button_hover_color){
                    css += '.woolentor-add-to-cart { --button-hover-color: ' + WoolentorCrossSell.settings.button_hover_color + '; }';
                }
            }

            style.appendChild(document.createTextNode(css));
            document.head.appendChild(style);
        },

        bindEvents: function(){
            var self = this;

            // Improve close popup handling
            $(document).on('keyup', function(e) {
                if (e.key === "Escape" && self.popup.is(':visible')) {
                    self.closePopup();
                }
            });

            // Close popup
            this.popup.on('click', '.woolentor-popup-close, .woolentor-continue-shopping', function(e){
                e.preventDefault();
                self.closePopup();
            });

            // Close on outside click
            $(window).click(function(event) {
                if (event.target == self.popup[0]) {
                    self.closePopup();
                }
            });

            // Add loading state for popup
            $(document).on('added_to_cart', function(e, fragments, cart_hash, button){

                if(self.isProcessing) return; // Prevent multiple calls
                
                self.isProcessing = true;
                var product_id = button.data('product_id');

                if( typeof product_id === 'undefined'){
                    var cartForm = button.closest('form.cart');
                    var variation_id    = cartForm.find('input[name=variation_id]').val() || 0;
                    product_id = cartForm.find('input[name=product_id]').val() || button.val();
                }
                
                // Show loading state
                self.popup.addClass('loading');
                
                $.ajax({
                    url: WoolentorCrossSell.ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'woolentor_get_cross_sell_product',
                        nonce: WoolentorCrossSell.nonce
                        // product_id: product_id,
                    },
                    success: function(response){
                        if(response.success && response.data.html.length > 0){
                            $('.woolentor-popup-wrapper').html(response.data.html);
                            self.popup.fadeIn().removeClass('loading');
                        }
                    },
                    complete: function() {
                        self.isProcessing = false;
                    }
                });
            });

            // Improve add to cart handling
            this.popup.on('click', '.woolentor-add-to-cart', function(e){
                e.preventDefault();
                if(self.isProcessing) return;
                
                var $button = $(this);
                self.addToCart($button);
            });
        },

        addToCart: function($button){
            var self = this;
            if(this.isProcessing) return;
            
            this.isProcessing = true;
            $button.addClass('loading');

            $.ajax({
                url: WoolentorCrossSell.ajaxurl,
                type: 'POST',
                data: {
                    action: 'woolentor_cross_sell_add_to_cart',
                    nonce: WoolentorCrossSell.nonce,
                    product_id: $button.data('product-id')
                },
                success: function(response){
                    if(response.success){
                        $button.addClass('added').text('Added');
                        $(document.body).trigger('wc_fragment_refresh');
                        
                        // Show success message
                        self.showMessage('Product added successfully');
                    } else {
                        self.showMessage(response.data.message || 'Failed to add product', 'error');
                    }
                },
                error: function() {
                    self.showMessage('Something went wrong', 'error');
                },
                complete: function() {
                    self.isProcessing = false;
                    $button.removeClass('loading');
                }
            });
        },

        showMessage: function(message, type = 'success') {
            // Add message display logic
            const messageEl = $('<div>', {
                class: `woolentor-message woolentor-message-${type}`,
                text: message
            }).appendTo(this.popup);

            setTimeout(() => messageEl.remove(), 3000);
        },

        closePopup: function(){
            this.popup.fadeOut();
            // Clear content after animation
            setTimeout(() => {
                $('.woolentor-cross-sell-popup-content').empty();
            }, 400);
        },

        requestPopup: function(productId){

            // Show loading state
            WoolentorCrossSellPopup.popup.addClass('loading');

            $.ajax({
                url: WoolentorCrossSell.ajaxurl,
                type: 'POST',
                data: {
                    action: 'woolentor_get_cross_sell_product',
                    product_id: productId,
                    nonce: WoolentorCrossSell.nonce
                },
                success: function(response){
                    if(response.success && response.data.html.length > 0){
                        $('.woolentor-popup-wrapper').html(response.data.html);
                        WoolentorCrossSellPopup.popup.fadeIn().removeClass('loading');
                    }
                }
            });

        }

    };

    window.WoolentorCrossSellPopup = WoolentorCrossSellPopup;

    $(document).ready(function(){
        WoolentorCrossSellPopup.init();
    });

})(jQuery);