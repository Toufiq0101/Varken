let color = "";
let size = "";
let message = "";
let move_on = true;

$(document).on("click", ".color-option", function () {
  color = $(this).data("color");
  document.querySelectorAll(".color-option").forEach((el) => {
    if ($(el).data("color") === color) {
      el.classList.add("color-option-active");
    } else {
      el.classList.remove("color-option-active");
    }
  });
  console.log(color);
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
$(document).on("click", ".quicki_add_to_cart-btn", function () {
  const el = this;
  if(document.querySelector("#specific-msg-bar")){
    message = document.querySelector("#specific-msg-bar").value;
  }
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
    console.log(product_dtl);

    if (move_on) {
      $.ajax({
        url: "./control/add_to_my_cart.php?add",
        type: "POST",
        data: product_dtl,
        success: function (data) {
          if (data.includes("qnt:")) {
            const p_qnt = data.split(":")[1];
            // $(el).closest(".l6-p-c-cart-btn").children(".prd_qnt").text(`${p_qnt}`);
            if (window.location.search.includes('my_cart')) {
              my_cart();
            }
            snackbar("Added to Cart");
            if (document.querySelector(".quick_order-modal")) {
              document.querySelector(".quick_order-modal").style.display = "none";
            }
            document.querySelector(
              ".quckii-modal-modal-container"
            ).style.display = "none";
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
$(document).on("click", ".del_cart_item_btn", function () {
  const el = this;
  const cart_str = $(this).data("cart_str");
  $.ajax({
    url: "./control/delete_from_cart.php?delete",
    type: "POST",
    data: { cart_str: cart_str },
    success: function (data) {
      const p_qnt = data.split(":")[1];
      console.log(p_qnt);
      if (window.location.search.includes('my_cart')) {
        my_cart();
      }
      // snackbar("Removed from cart");
    },
  });
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
        move_on = true;
      }
    }
    if (document.querySelector(".size-option")) {
      if (size !== "") {
        Object.assign(order_dtl, { p_size: size });
        move_on = true;
      } else {
        move_on = true;
      }
    }
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
                const notify = new Notification(
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
                notify.addEventListener("click", function () {
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
          document.querySelector(".quick_order-modal").style.display = "none";
        },
      });
    } else {
      snackbar("Select Color or Size");
    }
    document.querySelector(".quick_order-modal").style.display = "none";
    document.querySelector(".quckii-modal-modal-container").style.display =
      "none";
  } else {
    snackbar("Login/Register First");
  }
});