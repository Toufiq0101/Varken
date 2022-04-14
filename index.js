if ("serviceWorker" in navigator) {
  navigator.serviceWorker
    .register("./sw.js")
    .then((err) => console.log("serviceWorker Registered", err))
    .catch(() => console.log("not Register"));
}

window.onload = function before_load() {
  document.querySelector("#loading-spinner-container").innerHTML =
    "<img class='loading-spinner' src='./web_files/loader.gif' alt=''>";
  const header = document.querySelector(".fake-div");
  const sticky_header = document.querySelector(".header-2");
  const stickyNav = function (entries) {
    const entry = entries[0];
    if (!entry.isIntersecting) {
      sticky_header.classList.add("sticky-header");
    } else {
      sticky_header.classList.remove("sticky-header");
    }
  };
  const headerObserver = new IntersectionObserver(stickyNav, {
    root: null,
    threshold: 0.4,
  });
  headerObserver.observe(header);
  function menu_icon() {
    document
      .getElementById("menu_container")
      .classList.toggle("menu_container-display");
  }
};
$(document).ready(function () {
  const root = document.querySelector(":root");
  function night_mode() {
    root.style.setProperty("--body-background", "#002331");
    root.style.setProperty("--body-text-color", "#ffffff");
    root.style.setProperty("--section-name-color", "#ffffff");
    root.style.setProperty("--overview_container-border", "#495b6366");
    root.style.setProperty("--overview_container-background", "#ffffff00");
    root.style.setProperty("--overview-img_container-background", "00000000");
    root.style.setProperty("--overview-details-color", "#ececec");
    root.style.setProperty("--overview-btn-1-background", "#ff6600");
    root.style.setProperty("--overview-btn-2-background", "#ffbc00");
    root.style.setProperty("--profile-form-background", "#ffffff00");
    root.style.setProperty("--profile-form-text-color", "#e0e0e0");
    root.style.setProperty("--input-field-bottom-border-color", "#00598d");
    root.style.setProperty("--input-field-background", "#ffffff00");
    root.style.setProperty("--input-field-color", "#d2d2d2");
    root.style.setProperty("--card-background", "#ffffff00");
    root.style.setProperty("--card-text-color", "#d4fdf9");
    root.style.setProperty("--order-modal-label", "#00f7ff");
    root.style.setProperty("--order-modal-detail", "#00b395");
  }
  function light_mode() {
    root.style.setProperty("--body-background", "#ffffff");
    root.style.setProperty("--body-text-color", "#001118");
    root.style.setProperty("--section-name-color", "#001118");
    root.style.setProperty("--overview_container-border", "#a9a9a9");
    root.style.setProperty("--overview_container-background", "#ffffff00");
    root.style.setProperty("--overview-img_container-background", "#00000000");
    root.style.setProperty("--overview-details-color", "#001118");
    root.style.setProperty("--overview-btn-1-background", "#ff7300");
    root.style.setProperty("--overview-btn-2-background", "#ffbc00");
    root.style.setProperty("--profile-form-background", "#ffffff");
    root.style.setProperty("--profile-form-text-color", "#001118");
    root.style.setProperty("--input-field-bottom-border-color", "#202020");
    root.style.setProperty("--input-field-background", "#eefffe");
    root.style.setProperty("--input-field-color", "#000e20");
    root.style.setProperty("--card-background", "#ffffff00");
    root.style.setProperty("--card-text-color", "#001f1c");
    root.style.setProperty("--order-modal-label", "#00071d");
    root.style.setProperty("--order-modal-detail", "#020202");
  }
  var dark_theme;
  function getCookie() {
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(";");
    ca.forEach((i) => {
      if (i.includes("u_theme") && i.includes("dark_mode_on")) {
        dark_theme = 1;
      }
    });
  }
  getCookie();
  if (dark_theme === 1) {
    night_mode();
  } else {
    light_mode();
  }
  $(document).on("change", "#toggle-switch-input", function () {
    if ($(this).prop("checked")) {
      document.cookie =
        "u_theme=dark_mode_on; expires=Thu, 18 Dec 2023 12:00:00 UTC; path=/";
      night_mode();
    } else {
      document.cookie =
        "u_theme=dark_mode_on; expires=Thu, 18 Dec 2020 12:00:00 UTC; path=/";
      light_mode();
    }
  });
});
function my_coords() {
  navigator.geolocation.getCurrentPosition(get_client_coords.bind(this));
  function get_client_coords(position) {
    const { longitude } = position.coords;
    const { latitude } = position.coords;
    document.getElementById(
      "user_coords"
    ).value = `${latitude.toString()},${longitude.toString()}`;
  }
}
function verify() {
  $.ajax({
    url: "./control/verify.php?verify",
    type: "GET",
    success: function (data) {
      console.log(data);
      if (Number(data) !== 1) {
        document
          .getElementById("my_orders-btn")
          .setAttribute("data-disable", true);
        document
          .getElementById("order_history")
          .setAttribute("data-disable", true);
        document
          .getElementById("wishlist-btn")
          .setAttribute("data-disable", true);
        document
          .getElementById("fav_stores-btn")
          .setAttribute("data-disable", true);
        document
          .getElementById("my_cart-btn")
          .setAttribute("data-disable", true);
      }
    },
  });
}
verify();
$(document).on("submit", "#profile-form", function (e) {
  e.preventDefault();
  document.querySelector(".loadable-btn").classList.add("button--loading");
  document.querySelector(".loadable-btn").setAttribute("disabled", true);
  const formdata = new FormData(this);
  $.ajax({
    url: "./control/login.php?login",
    data: formdata,
    type: "POST",
    contentType: false,
    processData: false,
    success: function (data) {
      document
        .querySelector(".loadable-btn")
        .classList.remove("button--loading");
      document.querySelector(".loadable-btn").removeAttribute("disabled");
      if (Number(data) === 1) {
        profile();
        document
          .getElementById("my_orders-btn")
          .setAttribute("data-disable", false);
        document
          .getElementById("order_history")
          .setAttribute("data-disable", false);
        document
          .getElementById("wishlist-btn")
          .setAttribute("data-disable", false);
        document
          .getElementById("fav_stores-btn")
          .setAttribute("data-disable", false);
        document
          .getElementById("my_cart-btn")
          .setAttribute("data-disable", false);
      } else {
        snackbar("Number or Password may be Wrong");
      }
    },
  });
});
$(document).on("submit", "#register-form", function (e) {
  e.preventDefault();
  document.querySelector("#register-btn").classList.add("button--loading");
  document.querySelector("#register-btn").setAttribute("disabled", true);
  const num_str = $("#user_phone_number").val();
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
        url: "./control/user_account_ctrl.php?register",
        data: formdata,
        type: "POST",
        contentType: false,
        processData: false,
        success: function (data) {
          console.log(data);
          document
            .querySelector("#register-btn")
            .classList.remove("button--loading");
          document.querySelector("#register-btn").removeAttribute("disabled");
          verify();
          if (Number(data) === 000) {
            snackbar("Phone Number already Register");
          } else {
            if (Number(data) === 1) {
              snackbar("Registered successfully...");
              profile();
              document
                .getElementById("my_orders-btn")
                .setAttribute("data-disable", false);
              document
                .getElementById("order_history")
                .setAttribute("data-disable", false);
              document
                .getElementById("wishlist-btn")
                .setAttribute("data-disable", false);
              document
                .getElementById("fav_stores-btn")
                .setAttribute("data-disable", false);
              document
                .getElementById("my_cart-btn")
                .setAttribute("data-disable", false);
            } else {
              snackbar("Something Went Wrong..Refresh");
            }
          }
        },
      });
    } else {
      snackbar("Services Unavailable at this location");
    }
  } else {
    snackbar("Enter a Valid Number");
  }
});
$(document).on("click", "#logout-btn", function () {
  $.ajax({
    url: "./control/logout.php?logout",
    type: "POST",
    success: function (data) {
      if (Number(data) === 1) {
        profile();
        document
          .getElementById("my_orders-btn")
          .setAttribute("data-disable", true);
        document
          .getElementById("order_history")
          .setAttribute("data-disable", true);
        document
          .getElementById("wishlist-btn")
          .setAttribute("data-disable", true);
        document
          .getElementById("fav_stores-btn")
          .setAttribute("data-disable", true);
        document
          .getElementById("my_cart-btn")
          .setAttribute("data-disable", true);
      } else {
        snackbar("Something Went Wrong..Refresh");
      }
    },
  });
});
$(document).on("click", ".profile-login-tab", function () {
  document.querySelector("#login-form-container").style.display = "block";
  document.querySelector("#register-form-container").style.display = "none";
});
$(document).on("click", ".profile-rgst-tab", function () {
  document.querySelector("#register-form-container").style.display = "block";
  document.querySelector("#login-form-container").style.display = "none";
});
const zoomed_img = document.querySelector(".zoomed-img-modal-container");
$(document).on("click", ".zoom-img", function () {
  const img_src = this.getAttribute("src");
  document.querySelector(".zoomed-img").setAttribute("src", `${img_src}`);
  zoomed_img.style.display = "block";
});
window.onclick = function (event) {
  if (event.target == document.querySelector(".zoomed-img")) {
    zoomed_img.style.display = "none";
  }
};
function submit_rating(q) {
  const prd_dtl = $(q).data("ord_dtl");
  const prd_id = $(q).data("p_id");
  $(q).parent("div").children("div").children("input").addClass("rating_input");
  if ($(".ratingForm :radio:checked").length == 0) {
    $('#status').html("nothing checked");
    return false;
  } else {
    const rating_star = $(q).parent("div").children("div").children('input:radio[name=rating]:checked').val();
    $.ajax({
      url: "./control/ctrl_rating.php",
      data: { prd_str: prd_dtl, p_id: prd_id, rating: rating_star },
      type:"POST",
      success:function(){
        my_orders("order_history");
        snackbar("Thanks for Reviews");
      }
    });
  };
};