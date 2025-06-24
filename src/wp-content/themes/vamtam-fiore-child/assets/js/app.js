import { ajax } from "jquery";
import "../lib/slick/slick.js";
import { Fancybox } from "@fancyapps/ui";
import flatpickr from "flatpickr";

document.addEventListener("DOMContentLoaded", function () {
  const banners = document.querySelectorAll(".custom-title-baner-home");
  if (banners.length <= 1) return;
  let index = 0;
  function showNextBanner() {
    banners.forEach((b) => b.classList.remove("active"));
    banners[index].classList.add("active");
    index = (index + 1) % banners.length;
  }

  showNextBanner();
  setInterval(showNextBanner, 4000);
});

Fancybox.bind('[data-fancybox="gallery"]', {
  Thumbs: {
    autoStart: false,
  },
  infinite: true,
});

jQuery(document).ready(function ($) {
  const plantPrice = parseFloat($("#plant-price").text()) || 0;
  const plantID = $("#plant-combo-builder").data("plant-id");

  let selectedPlanter = null;

  const $planterSlider = $(".planter-preview");
  const $select = $("#planter-select");

  $planterSlider.on("init", function () {
    $select.val("");
    updateSelectClass();
    updateComboTotal();
  });

  $planterSlider.slick({
    infinite: true,
    arrows: true,
    slidesToShow: 1,
    slidesToScroll: 1,
  });

  $planterSlider.on("afterChange", function (event, slick, currentSlide) {
    $(".combo-item").removeClass("active");

    const $currentItem = $(
      ".slick-slide[data-slick-index='" + currentSlide + "']"
    ).find(".combo-item");
    $currentItem.addClass("active");

    const isDefault = $currentItem.data("index") == -1;

    selectedPlanter = {
      name: $currentItem.data("name"),
      price: parseFloat($currentItem.data("price")) || 0,
      potheight: parseInt($currentItem.data("potheight")) || 0,
      product_id: $currentItem.data("product_id"),
      index: $currentItem.data("index"),
    };

    $select.val(isDefault ? "" : selectedPlanter.product_id);
    updateSelectClass();
    updateComboTotal();
  });

  $select.on("change", function () {
    const selectedID = $(this).val();

    if (!selectedID) {
      const $defaultItem = $(".combo-item[data-index='-1']");
      const slickIndex = $defaultItem
        .closest(".slick-slide")
        .data("slick-index");
      $planterSlider.slick("slickGoTo", slickIndex);
      return;
    }

    const $matchedItem = $(`.combo-item[data-product_id='${selectedID}']`);
    if ($matchedItem.length) {
      const slickIndex = $matchedItem
        .closest(".slick-slide")
        .data("slick-index");
      $planterSlider.slick("slickGoTo", slickIndex);
    }
  });

  function updateComboTotal() {
    if (selectedPlanter) {
      const total = plantPrice + selectedPlanter.price;
      $("#combo-total").text(total.toFixed(2));
    }
  }

  function updateMiniCartQtyIncrementally(addedQty = 1) {
    const $badge = $(".elementor-button-icon-qty");
    let currentQty = parseInt($badge.attr("data-counter"), 10);
    if (isNaN(currentQty)) currentQty = 0;
    const newQty = currentQty + addedQty;
    $badge.attr("data-counter", newQty).text(newQty);

    const $itemCount = $(".item-count");
    $itemCount.text(`(${newQty})`);
  }

  function updateSelectClass() {
    $select.find("option").removeClass("selected");
    $select.find("option:selected").addClass("selected");
  }

  $(".accordion-header").on("click", function () {
    var $header = $(this);
    var $content = $header.next(".accordion-content");
    var $icon = $header.find(".accordion-icon");

    $content.slideToggle(200);

    if ($icon.text() === "+") {
      $icon.text("-");
      $icon.addClass("minus").removeClass("plus");
    } else {
      $icon.text("+");
      $icon.addClass("plus").removeClass("minus");
    }
  });

  $("#add-to-cart-combo").on("click", function (e) {
    e.preventDefault();

    if (!selectedPlanter || selectedPlanter.product_id === "") {
      $(".combo-options.planters").addClass("shake");
      setTimeout(() => $(".combo-options.planters").removeClass("shake"), 500);
      return;
    }

    const $btn = $(this);
    const giftId = $("#gift-select").val();
    const quantity = parseInt($("input.qty").val(), 10) || 1;

    $btn.prop("disabled", true).text("Adding...");

    $.ajax({
      url: "/wp-admin/admin-ajax.php",
      type: "POST",
      data: {
        action: "add_to_cart_combo",
        plant_id: plantID,
        planter_id: selectedPlanter.product_id,
        gift_id: giftId || 0,
        quantity: quantity,
      },
      success: function (response) {
        if (response.success) {
          $(".message-added-to-cart").fadeIn();
          setTimeout(() => {
            $(".message-added-to-cart").fadeOut();
          }, 3000);

          const qty = quantity * (giftId ? 3 : 2);
          updateMiniCartQtyIncrementally(qty);

          $.post("/?wc-ajax=get_refreshed_fragments", function (data) {
            if (data && data.fragments) {
              $.each(data.fragments, function (key, value) {
                $(key).replaceWith(value);
              });

              $(document.body).trigger("wc_fragments_refreshed");
            }
          });
        } else {
          alert(response.data || "Something went wrong.");
        }
      },

      error: function () {
        alert("AJAX error, please try again.");
      },
      complete: function () {
        $btn.prop("disabled", false).text("Add To Cart");
      },
    });
  });
});
