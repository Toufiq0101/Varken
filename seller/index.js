if ("serviceWorker" in navigator) {
  navigator.serviceWorker
    .register("./sw.js")
    .then(() => console.log("serviceWorker Registered"))
    .catch(() => console.log("not Register"));
}

let deferredPrompt;
window.addEventListener("beforeinstallprompt", (e) => {
  e.preventDefault();
  deferredPrompt = e;
});
document.querySelector("#download-btn").addEventListener("click", async () => {
  deferredPrompt.prompt();
  const { outcome } = await deferredPrompt.userChoice;
  if (outcome === "dismissed") {
    installSource = null;
  }
  deferredPrompt = null;
});

const header = document.querySelector(".fake-div");
const sticky_header = document.querySelector(".header-2");
const sticky_order_btn = document.querySelector("#hidden-order-btn");
const stickyNav = function (entries) {
  const entry = entries[0];
  if (!entry.isIntersecting) {
    sticky_header.classList.add("sticky-header");
  } else {
    sticky_header.classList.remove("sticky-header");
  }
};
const header_observer = new IntersectionObserver(stickyNav, {
  root: null,
  threshold: 0.1,
});
header_observer.observe(header);
function insert_upload_form() {
  var form_markup = `<div class="modal-upload-form" id="upload-product-form-container"><form class="upload-form" id="product-upload-form"><span onclick="document.getElementById('upload-product-form-container').style.display='none'"class="form-close-btn" title="Close">&times;</span><div class="input-name one-style-input-upload-form"><label for="product_name">Name</label><input type="text" name="product_name" placeholder="Name..." required></div><div class="input-price one-style-input-upload-form"><label for="price">Price</label><input type="text" name="product_price" placeholder="Price..." required></div>`;
  if (decodeURIComponent(document.cookie).includes("c_authentication")) {
    form_markup =
      form_markup +
      `<div class="input-description-container one-style-input-upload-form"><label for="description">Product Description : </label><label for="Description 1">Description 1 :</label><input type="text" name="product_description[]" placeholder="Description..." required><label for="Description 2">Description 2 :</label><input type="text" name="product_description[]" placeholder="Description..." required><label for="Description 3">Description 3 :</label><input type="text" name="product_description[]" placeholder="Description..." required><label for="Description 4">Description 4 :</label><input type="text" name="product_description[]" placeholder="Description..."></div><div class="input-price one-style-input-upload-form"><label for="Sizes">Sizes</label><input type="text" name="product_size" placeholder="Size..." ><span style="font-size: x-small;color: red;">If have Sizes. Ex- S,M,Xl,XXL</span></div><div class="input-color" id='product_color_input-container'><label for="color">color :</label><span id='add_color-btn'>+</span></div><div><label for='product_category'>Category</label><input list='product_category_list' name='product_category' required><datalist id="product_category_list">  <option value='Mobile Phones'></option><option value='Tablets'></option>
<option value='Laptops & PCs'></option><option value='Home Appliance'></option><option value='Electronic Accesories'></option><option value='Groceries'></option><option value='Snacks'></option><option value='Other Essentials'></option><option value='Gifts and Hampers'></option></datalist></div><div class="input-image"><input type="file" name="product_image[]" multiple required></div><div class="input-availability"><label for="availability">Availability</label><select name="availability"><option value="Available">Available</option><option value="Comming Soon">Comming Soon</option><option value="Out of Stock">Out of Stock</option></select></div>`;
  } else {
    form_markup =
      form_markup +
      `<div class="input-time one-style-input-upload-form"><label for="avg_time">Average Time</label><input type="text" name="average_time" placeholder="Average Time" required></div>`;
  }
  form_markup =
    form_markup +
    `<div class="upload-btn"><button type="submit" class="submit-product-form-btn" name="upload_item">Upload Item</button></div></form></div>`;
  document.querySelector("#modal-upload-form-container").innerHTML =
    form_markup;
}
$(document).on("click", "#add_color-btn", function () {
  document
    .querySelector("#product_color_input-container")
    .insertAdjacentHTML(
      "beforeend",
      `<input type="color" name="product_color[]">`
    );
});
insert_upload_form();
window.onclick = function (event) {
  if (
    event.target === document.getElementById("upload-product-form-container")
  ) {
    document.getElementById("upload-product-form-container").style.display =
      "none";
  }
  if (event.target == document.querySelector(".zoomed-img-modal-container")) {
    document.querySelector(".zoomed-img-modal-container").style.display =
      "none";
  }
  if (event.target == document.querySelector(".courier_info-container")) {
    document.querySelector(".courier_info-container").style.display = "none";
  }
};
$(document).on("click", "#add-product-btn", function () {
  document.querySelector("#upload-product-form-container").style.display =
    "block";
});
$(document).on("submit", "#profile-form", function (e) {
  e.preventDefault();
  const formdata = new FormData(this);
  $.ajax({
    url: "./control/login.php?login",
    data: formdata,
    type: "POST",
    contentType: false,
    processData: false,
    success: function (data) {
      console.log(data)
      if (Number(data) === 1) {
        profile();
        document
          .getElementById("my_garage")
          .setAttribute("data-disable", false);
        document
          .getElementById("customer_orders")
          .setAttribute("data-disable", false);
        document
          .getElementById("pending_orders")
          .setAttribute("data-disable", false);
        document
          .getElementById("return_rqsts")
          .setAttribute("data-disable", false);
        document
          .getElementById("on_the_way")
          .setAttribute("data-disable", false);
        document
          .getElementById("unv_products")
          .setAttribute("data-disable", false);
        insert_upload_form();
      } else {
        if (Number(data) === 0) {
          snackbar("Username or Password may be Wrong");
        } else {
          snackbar("Something went Wrong...Refresh");
        }
      }
    },
  });
});
$(document).on("submit", "#register_form", function (e) {
  e.preventDefault();
  const num_str = $("#client_phone_number").val();
  if (
    Number(num_str.length) === 10 &&
    !num_str.startsWith("0") &&
    !num_str.startsWith("1") &&
    !num_str.startsWith("2") &&
    !num_str.startsWith("3") &&
    !num_str.startsWith("4") &&
    !num_str.startsWith("5")
  ) {
    if (
      $(
        '#center-points-list [value="' + $("#center-point-input").val() + '"]'
      ).data("coords") !== undefined
    ) {
      const formdata = new FormData(this);
      $.ajax({
        url: "./control/client_account_ctrl.php?register",
        data: formdata,
        type: "POST",
        contentType: false,
        processData: false,
        success: function (data) {
          console.log(data)
          if (Number(data) === 1) {
            tab();
            snackbar("Registered Successfully..Login Now");
          } else {
            if (Number(data) === 9) {
              snackbar("Image is too big or not supported");
            } else {
              if (Number(data) === 0) {
                snackbar("Phone Number already Registered");
              } else {
                snackbar("Something went Wrong...Refresh the page");
              }
            }
          }
        },
      });
    } else {
      snackbar(
        `Services Unavailable at this location<br><a style="color:blue;" href='https://wa.me/qr/5DJPSM4CB47GN1'>Request Us</a>`
      );
    }
  } else {
    snackbar("Enter a Valid Number");
  }
});
$(document).on("click", "#logout-btn", function () {
  $.ajax({
    url: "./control/logout.php?logout",
    success: function (data) {
      profile();
      document
        .getElementById("customer_orders")
        .setAttribute("data-disable", true);
      document
        .getElementById("pending_orders")
        .setAttribute("data-disable", true);
      document
        .getElementById("return_rqsts")
        .setAttribute("data-disable", true);
      document.getElementById("on_the_way").setAttribute("data-disable", true);
      document
        .getElementById("unv_products")
        .setAttribute("data-disable", true);
    },
  });
});
$(document).ready(function () {
  const root = document.querySelector(":root");
  function night_mode() {
    root.style.setProperty("--body-background", "#002331");
    root.style.setProperty("--body-text", "#e7e7e7");
    root.style.setProperty("--product-container-border", "#66656580");
    root.style.setProperty("--product-overview-background", "#02001d00");
    root.style.setProperty("--product-overview-text", "#e2e2e2");
    root.style.setProperty("--upload-form-background", "#031c27f7");
    root.style.setProperty("--upload-form-border", "#545454");
    root.style.setProperty("--upload-form-text", "#e4e4e4");
    root.style.setProperty("--upload-form-input-border", "#5f5f5f7d");
    root.style.setProperty("--customer-detail-border", "#424242");
    root.style.setProperty("--customer-detail-background", "00111f");
    root.style.setProperty("--customer_detail-address-text", "#00859c");
    root.style.setProperty("--customer_detail-name-text", "#00ffdd");
    root.style.setProperty("--input-field-label-color", "#dedede");
    root.style.setProperty("--input-field-color", "#d4d4d4");
    root.style.setProperty("--input-field-bottom-border-color", "#004967a3");
    root.style.setProperty("--input-field-background", "#ffffff00");
    root.style.setProperty("--copyright-text", ":red");
    root.style.setProperty("--contact-us", ":red");
    root.style.setProperty("--about-us", ":red");
  }
  function light_mode() {
    root.style.setProperty("--body-background", "#ffffff");
    root.style.setProperty("--body-text", "#03001a");
    root.style.setProperty("--product-container-border", "#c8c8c8");
    root.style.setProperty("--product-overview-background", "#ffffff00");
    root.style.setProperty("--product-overview-text", "#011927");
    root.style.setProperty("--upload-form-background", "#ffffff");
    root.style.setProperty("--upload-form-border", "#353535");
    root.style.setProperty("--upload-form-text", "#00141d");
    root.style.setProperty("--upload-form-input-border", "#5f5f5f");
    root.style.setProperty("--customer-detail-border", "#424242");
    root.style.setProperty("--customer-detail-background", "#e3ecff");
    root.style.setProperty("--customer_detail-address-text", "#00262cc2");
    root.style.setProperty("--customer_detail-name-text", "#001414");
    root.style.setProperty("--input-field-label-color", "#000f0f");
    root.style.setProperty("--input-field-color", "#000205");
    root.style.setProperty("--input-field-bottom-border-color", "#868686");
    root.style.setProperty("--input-field-background", "#ffffff00");
    root.style.setProperty("--copyright-tex", ":red");
    root.style.setProperty("--contact-u", ":red");
    root.style.setProperty("--about-u", ":red");
  }
  var dark_theme;
  function theme() {
    let decodedCookie = decodeURIComponent(document.cookie);
    if (
      decodedCookie.includes("cs_theme") &&
      decodedCookie.includes("dark_mode_on")
    ) {
      dark_theme = 1;
    }
  }
  theme();
  if (dark_theme === 1) {
    night_mode();
  } else {
    light_mode();
  }
  $(document).on("change", "#toggle-switch-input", function () {
    if ($(this).prop("checked")) {
      document.cookie =
        "cs_theme=dark_mode_on; expires=Thu, 18 Dec 2023 12:00:00 UTC; path=/";
      night_mode();
    } else {
      document.cookie =
        "cs_theme=dark_mode_on; expires=Thu, 18 Dec 2020 12:00:00 UTC; path=/";
      light_mode();
    }
  });
});
const zoomed_img = document.querySelector(".zoomed-img-modal-container");
$(document).on("click", ".zoom-img", function () {
  const img_src = this.getAttribute("src");
  document.querySelector(".zoomed-img").setAttribute("src", `${img_src}`);
  zoomed_img.style.display = "block";
});
$(document).on("change", ".product-checkbox", function () {
  if ($(this).prop("checked")) {
    document
      .querySelector("#add_to_garage")
      .classList.add("animate-unv_add-btn");
  } else {
    if (document.querySelector(".product-checkbox") === false) {
      document
        .querySelector("#add_to_garage")
        .classList.remove("animate-unv_add-btn");
    }
  }
});
$(document).on("click", ".open_about_courier", function () {
  $.ajax({
    url: "./control/about_courier.php",
    data: { courier_id: $(this).data("courier_boy") },
    type: "POST",
    success: function (data) {
      $("#about-courier-container-modal").html(data);
    },
  });
});
window.onload = function () {
  document.querySelector("#loading-spinner-container").innerHTML =
    "<img class='loading-spinner' src='../web_files/loader.gif' alt=''>";
};
