;(function($){
    "use strict";

    var WooLentorCurrencySwitcher = {

        init: function(){
            $(document).on( 'click','.woolentor-currency-switcher ul li', WooLentorCurrencySwitcher.saveCurrency );
            $(document).on( 'click','.woolentor-selected-currency-wrap', WooLentorCurrencySwitcher.currencySwitcherDropdown );
            $('body').on('click', WooLentorCurrencySwitcher.manageOutSideClick );
        },

        // Save Currency
        saveCurrency: function( event ){
            event.preventDefault();

            const selectedCurrency = $(this).attr('data-value');
            $('.woolentor-currency-switcher').addClass('woolentor-loading');

            $.ajax({
                url: wlsl_currency_switcher.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'woolentor_save_current_currency',
                    data: selectedCurrency,
                    wpnonce: wlsl_currency_switcher.nonce
                },
                success: function( response ) {
                    $('.woolentor-currency-switcher').removeClass('woolentor-loading');
                    location.reload();
                },
                error: function( error ) {
                    $('.woolentor-currency-switcher').removeClass('woolentor-loading');
                },
            });

        },

        // Currency Switcher Dropdown
        currencySwitcherDropdown: function( event ){
            event.preventDefault();
            let dowpdown = $(this).siblings('.woolentor-currency-dropdown:not(.list-style)');
            WooLentorCurrencySwitcher.swticherDropdownToggle( $(this), dowpdown );
        },

        // Hide Dropdown
        swticherDropdownToggle: function( switcherArea, selectBox ){
            selectBox.slideToggle( "slow");
            const isActive = switcherArea.hasClass("active");
            if ( isActive ) {
                switcherArea.removeClass("active");
                selectBox.removeClass("active");
            } else {
                switcherArea.addClass("active");
                selectBox.addClass("active");
            }
        },

        // Hide Dropdown if click on out-side
        manageOutSideClick: function ( event ) {
            const target        = event.target;
            const switcherWrap  = $('.woolentor-selected-currency-wrap');
            const dropdown      = $('.woolentor-currency-switcher .woolentor-currency-dropdown:not(.list-style)');
            if ( !$( target ).is( switcherWrap ) && !$( target ).is( switcherWrap.children() ) && dropdown.is('.active') ) {
                const isActive = dropdown.hasClass("active");
                if( isActive ){
                    switcherWrap.removeClass('active');
                    dropdown.removeClass('active');
                    dropdown.slideUp( "slow");
                }
            }

        },


    };

    WooLentorCurrencySwitcher.init();

})(jQuery);