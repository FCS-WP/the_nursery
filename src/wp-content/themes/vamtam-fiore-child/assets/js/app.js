import { ajax } from "jquery";
import "../lib/slick/slick.js";

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

jQuery(document).ready(function ($) {
  const plantPrice = parseFloat($("#plant-price").text()) || 0;
  const plantID = $("#plant-combo-builder").data("plant-id");

  let selectedPlanter = null;

  const $planterSlider = $(".planter-preview");

  $planterSlider.on("init", function () {
    const $firstItem = $(".combo-item[data-type='planter'][data-index='0']");
    $firstItem.addClass("active");

    selectedPlanter = {
      name: $firstItem.data("name"),
      price: parseFloat($firstItem.data("price")) || 0,
      potheight: parseInt($firstItem.data("potheight")) || 0,
      product_id: $firstItem.data("product_id"),
      index: 0,
    };

    updateComboTotal();
  });

  $planterSlider.slick({
    infinite: true,
    arrows: true,
    slidesToShow: 1,
    slidesToScroll: 1,
  });

  // $(".combo-item[data-type='planter']").on("click", function () {
  //   $(".combo-item[data-type='planter']").removeClass("active");
  //   $(this).addClass("active");

  //   selectedPlanter = {
  //     name: $(this).data("name"),
  //     price: parseFloat($(this).data("price")) || 0,
  //     potheight: parseInt($(this).data("potheight")) || 0,
  //     product_id: $(this).data("product_id"),
  //     index: $(this).data("index"),
  //   };

  //   // Sync with slider
  //   $planterSlider.slick("slickGoTo", selectedPlanter.index);

  //   updateComboTotal();
  // });

  $planterSlider.on("afterChange", function (event, slick, currentSlide) {
    $(".combo-item[data-type='planter']").removeClass("active");
    const $item = $(
      `.combo-item[data-type='planter'][data-index='${currentSlide}']`
    ).addClass("active");

    selectedPlanter = {
      name: $item.data("name"),
      price: parseFloat($item.data("price")) || 0,
      potheight: parseInt($item.data("potheight")) || 0,
      product_id: $item.data("product_id"),
      index: currentSlide,
    };

    updateComboTotal();
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
  }

  $("#add-to-cart-combo").on("click", function (e) {
    e.preventDefault();

    if (!selectedPlanter) {
      $(".combo-options.planters").addClass("shake");
      setTimeout(() => $(".combo-options.planters").removeClass("shake"), 500);
      return;
    }

    const $btn = $(this);
    $btn.prop("disabled", true).text("Adding...");

    $.ajax({
      url: "/wp-admin/admin-ajax.php",
      type: "POST",
      data: {
        action: "add_to_cart_combo",
        plant_id: plantID,
        planter_id: selectedPlanter.product_id,
      },
      success: function (response) {
        if (response.success) {
          $(".message-added-to-cart").fadeIn();
          setTimeout(() => {
            $(".message-added-to-cart").fadeOut();
          }, 3000);

          updateMiniCartQtyIncrementally(2);
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

  $("#planter-select").on("change", function () {
    const selectedID = $(this).val();
    if (!selectedID) return;

    const $matchedItem = $(
      `.combo-item[data-type='planter'][data-product_id='${selectedID}']`
    );

    if ($matchedItem.length) {
      const slickIndex = $matchedItem
        .closest(".slick-slide")
        .data("slick-index");
      $planterSlider.slick("slickGoTo", slickIndex);

      $(".combo-item[data-type='planter']").removeClass("active");
      $matchedItem.addClass("active");

      selectedPlanter = {
        name: $matchedItem.data("name"),
        price: parseFloat($matchedItem.data("price")) || 0,
        potheight: parseInt($matchedItem.data("potheight")) || 0,
        product_id: $matchedItem.data("product_id"),
        index: slickIndex,
      };

      updateComboTotal();
    }
  });
});
