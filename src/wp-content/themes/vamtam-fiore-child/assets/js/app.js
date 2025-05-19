import { ajax } from "jquery";
import "../lib/slick/slick.js";

document.addEventListener("DOMContentLoaded", function () {
  const banners = document.querySelectorAll(".custom-title-baner-home");
  let index = 0;

  function showNextBanner() {
    banners.forEach((b) => b.classList.remove("active"));
    banners[index].classList.add("active");
    index = (index + 1) % banners.length;
  }

  showNextBanner();
  setInterval(showNextBanner, 4000);
});
document.addEventListener("DOMContentLoaded", function () {
  const showMoreButtons = document.querySelectorAll(".show-more");

  showMoreButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const targetClass = this.dataset.target;
      const items = document.querySelectorAll(
        `.combo-options.${targetClass} .combo-item.hidden`
      );
      let shown = 0;

      items.forEach((item) => {
        if (shown < 4) {
          item.classList.remove("hidden");
          shown++;
        }
      });

      if (
        document.querySelectorAll(
          `.combo-options.${targetClass} .combo-item.hidden`
        ).length === 0
      ) {
        this.style.display = "none";
      }
    });
  });
});

jQuery(document).ready(function ($) {
  const $plantSlider = $(".plant-preview");
  const $planterSlider = $(".planter-preview");

  $plantSlider.slick({
    infinite: true,
    arrows: true,
    slidesToShow: 1,
    slidesToScroll: 1,
  });

  $planterSlider.slick({
    infinite: true,
    arrows: true,
    slidesToShow: 1,
    slidesToScroll: 1,
  });

  let selectedPlant = null,
    selectedPlanter = null;

  $(".combo-item").on("click", function () {
    const type = $(this).data("type");
    const index = $(this).data("index");

    $('.combo-item[data-type="' + type + '"]').removeClass("active");
    $(this).addClass("active");

    if (type === "plant") {
      selectedPlant = {
        name: $(this).data("name"),
        price: parseFloat($(this).data("price")),
        height: parseInt($(this).data("height")) || 0,
      };
      $plantSlider.slick("slickGoTo", index);
    } else {
      selectedPlanter = {
        name: $(this).data("name"),
        price: parseFloat($(this).data("price")),
        potheight: parseInt($(this).data("potheight")) || 0,
      };
      $planterSlider.slick("slickGoTo", index);
    }

    updatePreview();
  });

  $plantSlider.on("afterChange", function (event, slick, currentSlide) {
    $('.combo-item[data-type="plant"]').removeClass("active");
    const $item = $(
      '.combo-item[data-type="plant"][data-index="' + currentSlide + '"]'
    ).addClass("active");

    selectedPlant = {
      name: $item.data("name"),
      price: parseFloat($item.data("price")),
      height: parseInt($item.data("height")) || 0,
    };
    updatePreview();
  });

  $planterSlider.on("afterChange", function (event, slick, currentSlide) {
    $('.combo-item[data-type="planter"]').removeClass("active");
    const $item = $(
      '.combo-item[data-type="planter"][data-index="' + currentSlide + '"]'
    ).addClass("active");

    selectedPlanter = {
      name: $item.data("name"),
      price: parseFloat($item.data("price")),
      potheight: parseInt($item.data("potheight")) || 0,
    };
    updatePreview();
  });

  function updatePreview() {
    if (selectedPlant && selectedPlanter) {
      const totalPrice = selectedPlant.price + selectedPlanter.price;
      const totalHeight = selectedPlant.height + selectedPlanter.potheight;

      $("#combo-total").text(totalPrice.toFixed(2));
    }
  }

  $("#add-to-cart").on("click", function (e) {
    e.preventDefault();
    if (!selectedPlant || !selectedPlanter) {
      alert("Please select a plant and a planter.");
      return;
    }

    const plantID = $('.combo-item[data-type="plant"].active').data(
      "product_id"
    );
    const planterID = $('.combo-item[data-type="planter"].active').data(
      "product_id"
    );

    $.ajax({
      url: "/wp-admin/admin-ajax.php",
      type: "POST",
      data: {
        action: "add_to_cart_combo",
        plant_id: plantID,
        planter_id: planterID,
      },
      success: function (response) {
        if (response.success) {
          alert("Combo added to cart!");
          window.location.href = "/cart";
        } else {
          alert(response.data || "Something went wrong.");
        }
      },
    });
  });
});
