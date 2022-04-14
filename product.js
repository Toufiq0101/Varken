document.addEventListener("DOMContentLoaded", function () {
  function slider_ctrl(el_id, arrow) {
    new Splide(`#${el_id}`, {
      padding: { left: 10, right: 10 },
      lazyLoad: "sequential",
      rewind: true,
      arrows: arrow,
      autoWidth: true,
      pagination: false,
    }).mount();
  }
  new Splide("#product-img_container", {
    type: "slide",
    autoHeight: true,
    lazyLoad: "nearby",
  }).mount();
  if (screen.width < 1000) {
    slide_var_rp === 1
      ? slider_ctrl("related-product-overview-slider", false)
      : "";
    slide_var_mfs === 1
      ? slider_ctrl("more-store-product-overview", false)
      : "";
  } else {
    slide_var_rp === 1
      ? slider_ctrl("related-product-overview-slider", true)
      : "";
    slide_var_mfs === 1 ? slider_ctrl("more-store-product-overview", true) : "";
  }
});
function snackbar(message) {
  const alert_el = document.getElementById("order-alert");
  document.getElementById("order-alert").innerHTML = `${message}`;
  alert_el.className = "show-alert";
  setTimeout(function () {
    alert_el.className = alert_el.className.replace("show-alert", "");
  }, 3000);
}
const product_id = window.location.search.split("=");
function wishlist_check() {
  if (decodeURIComponent(document.cookie).includes("u_authentication")) {
    $.ajax({
      url: "./control/control_wishlist.php?check",
      type: "POST",
      data: { p_id_check: product_id[1] },
      success: function (data) {
        if (data == 0) {
          document.getElementById("wishlist-btn-color").setAttribute("fill","#ff0047ed");
        }
      },
    });
  }
};
wishlist_check();
$(document).ready(function () {
  $(document).on("click", ".wishlist-btn-container", function () {
    if (decodeURIComponent(document.cookie).includes("u_authentication")) {
      const el = $(this);
      $.ajax({
        url: "./control/control_wishlist.php?action",
        type: "POST",
        data: { p_id: el.data("p_id") },
        success: function (checks) {
          if (Number(checks) === 1) {
            document.getElementById("wishlist-btn-color").setAttribute("fill","#ff0047ed");
          }
          if (Number(checks) === 2) {
            document.getElementById("wishlist-btn-color").setAttribute("fill","#00000000");
          }
        },
      });
    } else {
      snackbar("Login/Register First");
    }
  });
});
let color = "";
let size = "";
let message = "";
let move_on = true;
document.getElementById("specific-msg-bar").onblur = function () {
  $(this).data("msg", this.value);
  message = $(this).data("msg");
};
$(document).on("click", ".color-option", function () {
  color = $(this).data("color");
  document.querySelectorAll(".color-option").forEach((el) => {
    if ($(el).data("color") === color) {
      el.classList.add("color-option-active");
    } else {
      el.classList.remove("color-option-active");
    }
  });
});
$(document).on("click", ".size-option", function () {
  size = $(this).data("size");
  document.querySelectorAll(".size-option").forEach((el) => {
    if ($(el).data("size") === size) {
      el.classList.add("size-option-active");
    } else {
      el.classList.remove("size-option-active");
    }
  });
});
$(document).on("click", "#add_to_cart", function () {
  if (decodeURIComponent(document.cookie).includes("u_authentication")) {
    const cart_str = $(this).data("add_item");
    move_on = true;
    const product_dtl = { cart_str: cart_str };
    if (document.querySelector(".color-option")) {
      if (color !== "") {
        Object.assign(product_dtl, { p_color: color });
        move_on = true;
      } else {
        move_on = false;
      }
    }
    if (document.querySelector(".size-option")) {
      if (size !== "") {
        Object.assign(product_dtl, { p_size: size });
        move_on = true;
      } else {
        move_on = true;
      }
    }
    Object.assign(product_dtl, { p_msg: message });
    if (move_on) {
      $.ajax({
        url: "./control/add_to_my_cart.php?add",
        type: "POST",
        data: product_dtl,
        success: function (data) {
          console.log(data);
          if (data.includes("qnt")) {
            snackbar("Added to Cart");
          } else {
            snackbar("Unable to add..Refresh");
          }
        },
      });
    } else {
      snackbar("Select Color or Size option");
    }
  } else {
    snackbar("Login/Register First");
  }
});
$(document).on("click", "#order_btn", function () {
  if (decodeURIComponent(document.cookie).includes("u_authentication")) {
    const p_name = $(this).data("p_name");
    const order_str = $(this).data("order_str");
    move_on = true;
    const order_dtl = { order_str: order_str };
    if (document.querySelector(".color-option")) {
      if (color !== "") {
        Object.assign(order_dtl, { p_color: color });
        move_on = true;
      } else {
        move_on = false;
      }
    }
    if (document.querySelector(".size-option")) {
      if (size !== "") {
        Object.assign(order_dtl, { p_size: size });
        move_on = true;
      } else {
        move_on = false;
      }
    }
    Object.assign(order_dtl, { p_msg: message });
    console.log(order_dtl);
    if (move_on) {
      $.ajax({
        url: "./control/orders.php",
        type: "POST",
        data: order_dtl,
        success: function (data) {
          console.log(data)
          if (Number(data) === 1) {
            snackbar("Ordered Successfully..!!");
            function orderedNotification() {
              if (Notification.permission == "granted") {
                const greeting = new Notification(
                  `Your ${p_name} has been Ordered`,
                  {
                    body: "if you didn't ordered it. cancel now.",
                    icon: "./web_files/icon-sqr.png",
                    vibrate: [100, 50, 100],
                    data: {
                      dateOfArrival: Date.now(),
                      primaryKey: 1,
                    },
                    requireInteraction: false,
                  }
                );
                greeting.addEventListener("click", function () {
                  window.open("https://halka.in/?my_orders");
                });
              }
            }
            setTimeout(orderedNotification, 10000);
          } else {
            Number(data) === 9
              ? snackbar("Image size too big or format doesn't support")
              : data.includes("loc404")
                ? snackbar("Location not set")
                : snackbar("Order Unsuccessful..!! Refresh page");
          }
          document.querySelector(
            ".order_reciept-container-modal"
          ).style.display = "none";
        },
      });
    } else {
      snackbar("Select Color or Size");
    }
  } else {
    snackbar("Login/Register First");
  }
});
var modal = document.getElementById("order_reciept-container-modal");
window.onclick = function (event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
};
const root = document.querySelector(":root");
if (
  decodeURIComponent(document.cookie).includes("u_theme") &&
  decodeURIComponent(document.cookie).includes("dark_mode_on")
) {
  root.style.setProperty("--body-background", "#021621");
  root.style.setProperty("--body-text-color", "#f7f7f7");
  root.style.setProperty("--card-background", "#ffffff00");
  root.style.setProperty("--card-text-color", "#f7f7f7");
  root.style.setProperty("--seller-background", "#021621");
  root.style.setProperty("--seller-text-color", "#f7f7f7");
  root.style.setProperty("--order-reciept-background", "#021621");
  root.style.setProperty("--order-reciept-text-color", "#f7f7f7");
  root.style.setProperty("--order-modal-label", "#00f7ff");
  root.style.setProperty("--order-modal-detail", "#e1e4e4");
  document
    .getElementById("wishlist-btn-container")
    .setAttribute("fill", "#00e1ff");
} else {
  root.style.setProperty("--body-background", "white");
  root.style.setProperty("--body-text-color", "#001220");
  root.style.setProperty("--card-background", "#ffffff00");
  root.style.setProperty("--card-text-color", "#001220");
  root.style.setProperty("--seller-background", "#ffffff");
  root.style.setProperty("--seller-text-color", "#001220");
  root.style.setProperty("--order-reciept-background", "#ffffff");
  root.style.setProperty("--order-reciept-text-color", "#001220");
  root.style.setProperty("--order-modal-label", "#00071d");
  root.style.setProperty("--order-modal-detail", "#020202");
}
document.getElementById("search-field").addEventListener("keydown", function (e) {
  if (e.code === 'Enter') {
    window.location.href = `/?search=${document.getElementById("search-field").value}`
  }
});
document.getElementById("search-btn").addEventListener("click", () => {
  window.location.href = `/?search=${document.getElementById("search-field").value}`
});

