(function ($, window) {
  "use strict";

  var WooLentorBlocks = {
    /**
     * [init]
     * @return {[void]} Initial Function
     */
    init: function () {
      this.TabsMenu($(".ht-tab-menus"), ".ht-tab-pane");
      this.TabsMenu($(".woolentor-product-video-tabs"), ".video-cus-tab-pane");
      if ($("[class*='woolentorblock-'] .ht-product-image-slider").length > 0) {
        this.productImageThumbnailsSlider($(".ht-product-image-slider"));
      }
      this.thumbnailsimagescontroller();
      this.ThumbNailsTabs(
        ".woolentor-thumbanis-image",
        ".woolentor-advance-product-image-area"
      );
    },

    /**
     * [TabsMenu] Active first menu item
     */
    TabsMenu: function ($tabmenus, $tabpane) {
      $tabmenus.on("click", "a", function (e) {
        e.preventDefault();
        var $this = $(this),
          $target = $this.attr("href");
        $this
          .addClass("htactive")
          .parent()
          .siblings()
          .children("a")
          .removeClass("htactive");
        $($tabpane + $target)
          .addClass("htactive")
          .siblings()
          .removeClass("htactive");

        // slick refresh
        if ($(".slick-slider").length > 0) {
          var $id = $this.attr("href");
          $($id).find(".slick-slider").slick("refresh");
        }
      });
    },

    /**
     *
     * @param {TabMen area selector} $tabmenu
     * @param {Image Area} $area
     */
    ThumbNailsTabs: function ($tabmenu, $area) {
      $($tabmenu).on("click", "li", function (e) {
        e.preventDefault();
        var $image = $(this).data("wlimage");
        if ($image) {
          $($area)
            .find(".woocommerce-product-gallery__image .wp-post-image")
            .attr("src", $image);
          $($area)
            .find(".woocommerce-product-gallery__image .wp-post-image")
            .attr("srcset", $image);
        }
      });
    },

    /**
     * Slick Slider
     */
    initSlickSlider: function ($block) {
      $($block).css("display", "block");
      var settings = WooLentorBlocks.sanitizeObject($($block).data("settings"));
      if (settings) {
        var arrows = settings["arrows"];
        var dots = settings["dots"];
        var autoplay = settings["autoplay"];
        var rtl = settings["rtl"];
        var autoplay_speed = parseInt(settings["autoplay_speed"]) || 3000;
        var animation_speed = parseInt(settings["animation_speed"]) || 300;
        var fade = false;
        var pause_on_hover = settings["pause_on_hover"];
        var display_columns = parseInt(settings["product_items"]) || 4;
        var scroll_columns = parseInt(settings["scroll_columns"]) || 4;
        var tablet_width = parseInt(settings["tablet_width"]) || 800;
        var tablet_display_columns =
          parseInt(settings["tablet_display_columns"]) || 2;
        var tablet_scroll_columns =
          parseInt(settings["tablet_scroll_columns"]) || 2;
        var mobile_width = parseInt(settings["mobile_width"]) || 480;
        var mobile_display_columns =
          parseInt(settings["mobile_display_columns"]) || 1;
        var mobile_scroll_columns =
          parseInt(settings["mobile_scroll_columns"]) || 1;

        $($block)
          .not(".slick-initialized")
          .slick({
            arrows: arrows,
            prevArrow:
              '<button type="button" class="slick-prev"><i class="fa fa-angle-left"></i></button>',
            nextArrow:
              '<button type="button" class="slick-next"><i class="fa fa-angle-right"></i></button>',
            dots: dots,
            infinite: true,
            autoplay: autoplay,
            autoplaySpeed: autoplay_speed,
            speed: animation_speed,
            fade: fade,
            pauseOnHover: pause_on_hover,
            slidesToShow: display_columns,
            slidesToScroll: scroll_columns,
            rtl: rtl,
            responsive: [
              {
                breakpoint: tablet_width,
                settings: {
                  slidesToShow: tablet_display_columns,
                  slidesToScroll: tablet_scroll_columns,
                },
              },
              {
                breakpoint: mobile_width,
                settings: {
                  slidesToShow: mobile_display_columns,
                  slidesToScroll: mobile_scroll_columns,
                },
              },
            ],
          });
      }
    },

    /**
     * Slick Nav For As Slider Initial
     * @param {*} $sliderwrap
     */
    initSlickNavForAsSlider: function ($sliderwrap) {
      $($sliderwrap).find(".woolentor-learg-img").css("display", "block");
      $($sliderwrap).find(".woolentor-thumbnails").css("display", "block");
      var settings = WooLentorBlocks.sanitizeObject($($sliderwrap).data("settings"));

      if (settings) {
        $($sliderwrap)
          .find(".woolentor-learg-img")
          .not(".slick-initialized")
          .slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            dots: settings["mainslider"].dots,
            arrows: settings["mainslider"].arrows,
            fade: false,
            asNavFor: ".woolentor-thumbnails",
            prevArrow:
              '<button class="woolentor-slick-large-prev"><i class="sli sli-arrow-left"></i></button>',
            nextArrow:
              '<button class="woolentor-slick-large-next"><i class="sli sli-arrow-right"></i></button>',
          });
        $($sliderwrap)
          .find(".woolentor-thumbnails")
          .not(".slick-initialized")
          .slick({
            slidesToShow: settings["thumbnailslider"].slider_items,
            slidesToScroll: 1,
            asNavFor: ".woolentor-learg-img",
            centerMode: false,
            dots: false,
            arrows: settings["thumbnailslider"].arrows,
            vertical: settings["thumbnailslider"].slidertype,
            focusOnSelect: true,
            prevArrow:
              '<button class="woolentor-slick-prev"><i class="sli sli-arrow-left"></i></button>',
            nextArrow:
              '<button class="woolentor-slick-next"><i class="sli sli-arrow-right"></i></button>',
          });
      }
    },

    /**
     * Accordion
     */
    initAccordion: function ($block) {
      var settings = $($block).data("settings");
      if ($block.length > 0) {
        var $id = $block.attr("id");
        new Accordion("#" + $id, {
          duration: 500,
          showItem: settings.showitem,
          elementClass: "htwoolentor-faq-card",
          questionClass: "htwoolentor-faq-head",
          answerClass: "htwoolentor-faq-body",
        });
      }
    },

    /**
     * Senitize HTML
     */
    sanitizeHTML: function (str) {
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
    },

    /**
     * Object Sanitize
     */
    sanitizeObject: function (inputObj) {
      const sanitizedObj = {};

      for (let key in inputObj) {
          if (inputObj.hasOwnProperty(key)) {
              let value = inputObj[key];
  
              // Sanitize based on the value type
              if (typeof value === 'string') {
                  // Sanitize strings to prevent injection
                  sanitizedObj[key] = WooLentorBlocks.sanitizeHTML(value);
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
  },

    /*
     * Tool Tip
     */
    woolentorToolTips: function (element, content) {
      if (content == "html") {
        var tipText = element.text();
      } else {
        var tipText = element.attr("title");
      }
      element.on("mouseover", function () {
        if ($(".woolentor-tip").length == 0) {
          element.before('<span class="woolentor-tip">' + WooLentorBlocks.sanitizeHTML(tipText) + "</span>");
          $(".woolentor-tip").css("transition", "all 0.5s ease 0s");
          $(".woolentor-tip").css("margin-left", 0);
        }
      });
      element.on("mouseleave", function () {
        $(".woolentor-tip").remove();
      });
    },

    woolentorToolTipHandler: function () {
      $("a.woolentor-compare").each(function () {
        WooLentorBlocks.woolentorToolTips($(this), "title");
      });
      $(
        ".woolentor-cart a.add_to_cart_button,.woolentor-cart a.added_to_cart,.woolentor-cart a.button"
      ).each(function () {
        WooLentorBlocks.woolentorToolTips($(this), "html");
      });
    },

    /*
     * Universal product
     */
    productImageThumbnailsSlider: function ($slider) {
      $slider.slick({
        dots: true,
        arrows: true,
        prevArrow:
          '<button class="slick-prev"><i class="sli sli-arrow-left"></i></button>',
        nextArrow:
          '<button class="slick-next"><i class="sli sli-arrow-right"></i></button>',
      });
    },

    thumbnailsimagescontroller: function () {
      this.TabsMenu($(".ht-product-cus-tab-links"), ".ht-product-cus-tab-pane");
      this.TabsMenu($(".ht-tab-menus"), ".ht-tab-pane");

      // Countdown
      $(".ht-product-countdown").each(function () {
        var $this = $(this),
          finalDate = $(this).data("countdown");
        var customlavel = $(this).data("customlavel");
        $this.countdown(finalDate, function (event) {
          $this.html(
            event.strftime(
              '<div class="cd-single"><div class="cd-single-inner"><h3>%D</h3><p>' +
              WooLentorBlocks.sanitizeHTML(customlavel.daytxt) +
                '</p></div></div><div class="cd-single"><div class="cd-single-inner"><h3>%H</h3><p>' +
                WooLentorBlocks.sanitizeHTML(customlavel.hourtxt) +
                '</p></div></div><div class="cd-single"><div class="cd-single-inner"><h3>%M</h3><p>' +
                WooLentorBlocks.sanitizeHTML(customlavel.minutestxt) +
                '</p></div></div><div class="cd-single"><div class="cd-single-inner"><h3>%S</h3><p>' +
                WooLentorBlocks.sanitizeHTML(customlavel.secondstxt) +
                "</p></div></div>"
            )
          );
        });
      });
    },

    /**
     * Single Product Quantity Increase/decrease manager
     */
    quantityIncreaseDescrease: function ($area) {
      $area
        .find("form.cart")
        .on(
          "click",
          "span.wl-qunatity-plus, span.wl-qunatity-minus",
          function () {
            const poductType = $area.data("producttype");
            // Get current quantity values
            if ("grouped" != poductType) {
              var qty = $(this).closest("form.cart").find(".qty:visible");
              var val = parseFloat(qty.val());
              var min_val = 1;
            } else {
              var qty = $(this)
                .closest(".wl-quantity-grouped-cal")
                .find(".qty:visible");
              var val = !qty.val() ? 0 : parseFloat(qty.val());
              var min_val = 0;
            }

            var max = parseFloat(qty.attr("max"));
            var min = parseFloat(qty.attr("min"));
            var step = parseFloat(qty.attr("step"));

            // Change the value if plus or minus
            if ($(this).is(".wl-qunatity-plus")) {
              if (max && max <= val) {
                qty.val(max);
              } else {
                qty.val(val + step);
              }
            } else {
              if (min && min >= val) {
                qty.val(min);
              } else if (val > min_val) {
                qty.val(val - step);
              }
            }
          }
        );
    },

    CartTableHandler: function () {
      // Product Details Slide Toggle
      $('body').on("click", '.woolentor-cart-product-details-toggle', function (e) {
          e.preventDefault();
          
          const $target = $(this).data('target');

          if($(`[data-id="${$target}"]`).is(':hidden')) {
              $(`[data-id="${$target}"]`).slideDown();
          } else {
              $(`[data-id="${$target}"]`).slideUp();
          }
      });
  }
  };

  $(document).ready(function () {
    WooLentorBlocks.init();
    WooLentorBlocks.CartTableHandler();

    $("[class*='woolentorblock-'] .product-slider").each(function () {
      WooLentorBlocks.initSlickSlider($(this));
    });

    $("[class*='woolentorblock-'].woolentor-block-slider-navforas").each(
      function () {
        WooLentorBlocks.initSlickNavForAsSlider($(this));
      }
    );

    $("[class*='woolentorblock-'] .htwoolentor-faq").each(function () {
      WooLentorBlocks.initAccordion($(this));
    });

    $("[class*='woolentorblock-'].woolentor-product-addtocart").each(
      function () {
        WooLentorBlocks.quantityIncreaseDescrease($(this));
      }
    );

    /**
     * Tooltip Manager
     */
    WooLentorBlocks.woolentorToolTipHandler();
  });

  // For Editor Mode Slider
  document.addEventListener(
    "WoolentorEditorModeSlick",
    function (event) {
      let editorMainArea = $(".block-editor__container"),
        editorIframe = $("iframe.edit-site-visual-editor__editor-canvas"),
        productSliderDiv =
          editorIframe.length > 0
            ? editorIframe
                .contents()
                .find("body.block-editor-iframe__body")
                .find(
                  `.woolentorblock-editor-${event.detail.uniqid} .product-slider`
                )
            : editorMainArea.find(
                `.woolentorblock-editor-${event.detail.uniqid} .product-slider`
              );
      window.setTimeout(
        WooLentorBlocks.initSlickSlider(productSliderDiv),
        1000
      );
    },
    false
  );

  // For Editor Mode Nav For As Slider
  document.addEventListener(
    "WoolentorEditorModeNavForSlick",
    function (event) {
      let editorMainArea = $(".block-editor__container"),
        editorIframe = $("iframe.edit-site-visual-editor__editor-canvas"),
        navForAsSliderDiv =
          editorIframe.length > 0
            ? editorIframe
                .contents()
                .find("body.block-editor-iframe__body")
                .find(
                  `.woolentorblock-editor-${event.detail.uniqid} .woolentor-block-slider-navforas`
                )
            : editorMainArea.find(
                `.woolentorblock-editor-${event.detail.uniqid} .woolentor-block-slider-navforas`
              );
      window.setTimeout(
        WooLentorBlocks.initSlickNavForAsSlider(navForAsSliderDiv),
        1000
      );
    },
    false
  );
})(jQuery, window);
