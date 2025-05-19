(function($){
    $(document).ready(function () {

        // Copy URL Coupon
        $("p.form-field.woolentor_coupon_url_field").on("click", function(event){
            let urlBox = $("p.form-field.woolentor_coupon_url_field input");

            let $tempInputBox = $("<input>");
            $("body").append($tempInputBox);
            $tempInputBox.val(urlBox.val()).select();
            urlBox.select();
            document.execCommand("copy");
            $tempInputBox.remove();

            $(this).addClass("copied");
            setTimeout(function () {
                $("p.form-field.woolentor_coupon_url_field").removeClass("copied");
            }, 1000);

        });


        let BulkGenButton = ( woolentor_advanced_local_obj.hasOwnProperty( 'bulk_generate_button' ) ? woolentor_advanced_local_obj.bulk_generate_button : '' );

        /**
         * Add bulk coupon generate button.
         */
        ( function () {
            if ( ( 'string' === typeof BulkGenButton ) && ( 0 < BulkGenButton.length ) ) {
                $( document ).find( '#wpbody-content .wrap .wp-header-end' ).before( BulkGenButton );
            }


            let url = new URL( window.location );
            let bulkEnable = url.searchParams.get('wlgeneratebulk') ? url.searchParams.get('wlgeneratebulk') : '';
            if( bulkEnable === 'yes'){
                $("#titlewrap").css("display","none");
                $("#woolentor_bulk_coupon").css("display","block");
                $(".wp-heading-inline").html(woolentor_advanced_local_obj.bulk_title);
            }

            $(".woolentor-bulk-coupon-btn").on('click',function(e){
                e.preventDefault();

                if( $(this).hasClass('back-to-default') ){
                    url.searchParams.delete( 'wlgeneratebulk' );

                    let url_history = decodeURIComponent(url.href);
                    window.history.pushState( {}, '', url_history );

                    $("#titlewrap").css("display","block");
                    $(".wp-heading-inline").html(woolentor_advanced_local_obj.single_title);
                    $("#woolentor_bulk_coupon").css("display","none");
                    $("#wlgeneratebulk").val("no");

                    $(this).html(woolentor_advanced_local_obj.bulk_generate_btn_text);
                    $(this).removeClass('back-to-default');

                }else{

                    url.searchParams.set( 'wlgeneratebulk', 'yes' );

                    $(this).html(woolentor_advanced_local_obj.back_btn_text);
                    $(this).addClass('back-to-default');

                    let url_history = decodeURIComponent(url.href);
                    window.history.pushState( {}, '', url_history );

                    $("#titlewrap").css("display","none");
                    $(".wp-heading-inline").html(woolentor_advanced_local_obj.bulk_title);
                    $("#woolentor_bulk_coupon").css("display","block");
                    $("#wlgeneratebulk").val("yes");

                }

            });

        } )();


    });
})(jQuery);