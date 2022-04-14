function manage_markups() {
  var arr = Array.from(document.querySelectorAll(".repeated_markup-check"));
  var elements = "";
  function check_html() {
    Array.from(document.querySelectorAll(".repeated_markup-check")).forEach(
      (e) => {
        elements = elements + `${e.getAttribute("data-name")}%|%`;
      }
    );
  }
  check_html();
  arr.forEach((e) => {
    const aab = elements.split("%|%");
    const counts = aab.reduce(
      (acc, value) => ({
        ...acc,
        [value]: (acc[value] || 0) + 1,
      }),
      {}
    );
    if (counts[`${e.getAttribute("data-name")}`] > 1) {
      e.closest(".main-overview-container").remove();
      elements = "";
      check_html();
    }
  });
}
var alert_el = document.getElementById("alert");
function snackbar(message) {
  document.getElementById("alert").innerHTML = `${message}`;
  alert_el.className = "show-alert";
  setTimeout(function () {
    alert_el.className = alert_el.className.replace("show-alert", "");
  }, 3000);
}
// function loading_spinner() {
//   document.getElementById("loading-spinner-container").style.display = "flex";
// }
// function remove_spinner() {
//   document.getElementById("loading-spinner-container").style.display = "none";
// }
let offset;
let load_more_ctrl_var;
const footer = document.querySelector("#footer");
const load_data = function (entries) {
  const search_var = window.location.search.split("=");
  const entry = entries[0];
  if (entry.isIntersecting) {
    function load_more_cntr(url) {
      $.ajax({
        url: `${url}`,
        type: "POST",
        data: { search_str: search_var[1], offset: offset },
        success: function (data) {
          if (Number(data) === 0) {
            load_more_ctrl_var = 0;
          } else {
            $("#main-content").append(data);
            // manage_markups();
            offset = offset + 10;
          }
        },
      });
    }
    if (load_more_ctrl_var === 1) {
      search_var[0].includes("cat")
        ? load_more_cntr(`./sections.php?cat=${search_var[1]}`)
        : search_var[0].includes("services")
          ? load_more_cntr(`./market.php?service_market`)
          : search_var[0].includes("market")
            ? load_more_cntr("./market.php")
            : "";
    }
  }
};
const footer_observer = new IntersectionObserver(load_data, {
  root: null,
  rootMargin: "500px",
  threshold: 0.1,
});
footer_observer.observe(footer);
let deferredPrompt;
let installSource;
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
function profile() {
  $.ajax({
    url: "./profile.php",
    type: "GET",
    success: function (data) {
      $("#main-content").html(data);
      // remove_spinner();
      if (window.location.search !== "?profile") {
        history.pushState("", "", "?profile");
      }
    },
  });
}
$(document).on("click", ".del-wishlist", function () {
  const p_id = $(this).data("p_id");
  $.ajax({
    url: "./control/control_wishlist.php?delete",
    type: "POST",
    data: { p_id: p_id },
    success: function (data) {
      wishlist();
      // remove_spinner();
    },
  });
});
function my_orders(order_type) {
  $.ajax({
    url: `./my_orders.php?${order_type}`,
    type: "POST",
    success: function (data) {
      // remove_spinner();
      $("#main-content").html(data);
      if (window.location.search !== `?${order_type}`) {
        history.pushState("", "", `?${order_type}`);
      }
    },
  });
}
$(document).on("click", "#cancel-order", function () {
  const element_data = $(this).data("cancel_my_order_str");
  $.ajax({
    url: "./control/control_cncl_rtn.php?cancel",
    type: "POST",
    data: { cancel_order_str: element_data },
    success: function (check) {
      console.log(check)
      // remove_spinner();
      my_orders("my_orders");
      if (Number(check) === 1) {
        snackbar("Cancelled..!");
      } else {
        snackbar("Order Cancelation Failed..!");
      }
    },
  });
});
$(document).on("click", "#return-order", function () {
  const element_data = $(this).data("return_my_order_str");
  $.ajax({
    url: "./control/ctrl_rtrn.php?return",
    type: "POST",
    data: { return_order_str: element_data },
    success: function (check) {
      // remove_spinner();
      my_orders("order_history");
      if (Number(check) === 1) {
        snackbar("Return Requested Successfully");
      } else {
        snackbar("Return Request Failed...Refresh");
      }
    },
  });
});
$(document).on("submit", "#profile_form", function (e) {
  e.preventDefault();
  document.querySelector(".loadable-btn").classList.add("button--loading");
  document.querySelector(".loadable-btn").setAttribute("disabled", true);
  var aaa = $(
    '#center-points-list [value="' + $("#center-point-input").val() + '"]'
  ).data("coords");
  if (
    $(
      '#center-points-list [value="' + $("#center-point-input").val() + '"]'
    ).data("coords") !== undefined
  ) {
    const formdata = new FormData(this);
    $.ajax({
      url: "./control/user_account_ctrl.php?edit",
      data: formdata,
      type: "POST",
      contentType: false,
      processData: false,
      success: function (data) {
        console.log(data)
        // remove_spinner();
        document
          .querySelector(".loadable-btn")
          .classList.remove("button--loading");
        document.querySelector(".loadable-btn").removeAttribute("disabled");
        if (Number(data) === 1) {
          snackbar("Saved..!!");
        }
      },
    });
  } else {
    snackbar("Services Unavailable..Choose something from options");
  }
});
$(document).on("click", "#order_cart-btn", function () {
  $.ajax({
    url: "./control/order_cart.php?order_cart",
    type: "POST",
    success: function (data) {
      // remove_spinner();
      if (Number(data) === 1) {
        my_cart();
        snackbar("Cart Ordered Successfully..!");
      } else {
        snackbar("Order Failed..Refresh");
      }
    },
  });
});
function wishlist() {
  $.ajax({
    url: "./wishlist.php",
    type: "POST",
    success: function (data) {
      // remove_spinner();
      $("#main-content").html(data);
      if (window.location.search !== "?wishlist") {
        history.pushState("", "", "?wishlist");
      }
    },
  });
}
function my_cart() {
  $.ajax({
    url: "./my_cart.php",
    type: "GET",
    success: function (data) {
      // remove_spinner();
      $("#main-content").html(data);
      if (window.location.search !== "?my_cart") {
        history.pushState("", "", "?my_cart");
      }
    },
  });
}
function market() {
  offset = 0;
  $.ajax({
    url: "./market.php",
    type: "GET",
    success: function (data) {
      // remove_spinner();
      $("#main-content").html(data);
      if (window.location.search !== "?market") {
        history.pushState("", "", "?market");
      }
      offset = 10;
      load_more_ctrl_var = 1;
    },
  });
}
function fav_store() {
  $.ajax({
    url: "./market.php?fav_store",
    type: "GET",
    success: function (data) {
      // remove_spinner();
      $("#main-content").html(data);
      if (window.location.search !== "?fav_store") {
        history.pushState("", "", "?fav_store");
      }
    },
  });
}
function sections(search) {
  offset = 0;
  $.ajax({
    url: `./sections.php?cat=${search}`,
    type: "GET",
    success: function (data) {
      // remove_spinner();
      window.scrollTo(0, 0);
      $("#main-content").html(data);
      // manage_markups();
      if (window.location.search !== `?cat=${search}`) {
        history.pushState("", "", `?cat=${search}`);
      }
      offset = 10;
      load_more_ctrl_var = 1;
    },
  });
}
function services_tab() {
  offset = 0;
  $.ajax({
    type: "GET",
    url: "./market.php?service_market",
    success: function (data) {
      // remove_spinner();
      $("#main-content").html(data);
      if (window.location.search !== `?services`) {
        history.pushState("", "", `?services`);
      }
      offset = 10;
      load_more_ctrl_var = 1;
    },
  });
}
function home_content() {
  $.ajax({
    type: "GET",
    url: "./hm_pg_ctnt.php",
    data: { width: screen.width, height: screen.height },
    success: function (data) {
      $("#main-content").html(data);
      new Splide("#home-banner-container", {
        type: "loop",
        padding: '2rem',
        lazyLoad: "nearby",
        autoplay: true,
        interval: 3000,
        arrows: false,
      }).mount();
      new Splide(`#best_sellers_list-container`, {
        padding: { left: 10, right: 10 },
        lazyLoad: "sequential",
        rewind: true,
        arrows: false,
        autoWidth: true,
        pagination: false,
        gap: 10,
      }).mount();
      // var main = new Splide(`#best_products_list-container`, {
      //   type: 'fade',
      //   rewind: true,
      //   pagination: false,
      //   arrows: false,
      //   gap: 10,
      // });
      // var thumbnails = new Splide('#thumbnail-slider', {
      //   fixedWidth: 100,
      //   fixedHeight: 60,
      //   gap: 10,
      //   rewind: true,
      //   pagination: false,
      //   arrows: false,
      //   cover: true,
      //   focus: 'center',
      //   isNavigation: true,
      //   // start:1,
      //   breakpoints: {
      //     600: {
      //       fixedWidth: 70,
      //       fixedHeight: 50,
      //     },
      //   },
      //   autoplay: true,
      //   interval: 2000,
      // });
      // main.sync(thumbnails);
      // main.mount();
      // thumbnails.mount();
    },
  });
  // remove_spinner();
  if (window.location.search !== "") {
    history.pushState("", "", "/");
  }
}
$(document).on("click", "#my_orders-btn", function () {
  if ($(this).data("disable") !== true) {
    // loading_spinner();
    my_orders("my_orders");
  } else {
    snackbar("Login/Register First..!");
  }
});
$(document).on("click", "#order_history", function () {
  if ($(this).data("disable") != true) {
    // loading_spinner();
    my_orders("order_history");
  } else {
    snackbar("Login/Register First..!");
  }
});
$(document).on("click", "#wishlist-btn", function () {
  if ($(this).data("disable") !== true) {
    // loading_spinner();
    wishlist();
  } else {
    snackbar("Login/Register First..!");
  }
});
$(document).on("click", ".market-btn", function () {
  // loading_spinner();
  market();
});
$(document).on("click", "#fav_stores-btn", function () {
  if ($(this).data("disable") !== true) {
    // loading_spinner();
    fav_store();
  } else {
    snackbar("Login/Register First..!");
  }
});
$(document).on("click", "#my_cart-btn", function () {
  if ($(this).data("disable") !== true) {
    // loading_spinner();
    my_cart();
  } else {
    snackbar("Login/Register First..!");
  }
});
$(document).on("click", "#home-btn", function () {
  // loading_spinner();
  home_content();
});
$(document).on("click", "#services-btn", function () {
  services_tab();
});
$(document).on("click", "#profile-btn", function () {
  // loading_spinner();
  profile();
});
function load_tab() {
  if (window.location.search.includes("?cat")) {
    const search = window.location.search.split("=");
    sections(search[1]);
  } else {
    switch (window.location.search) {
      case "?wishlist":
        wishlist();
        break;
      case "?market":
        market();
        break;
      case "?fav_store":
        fav_store();
      case "?my_orders":
        my_orders("my_orders");
        break;
      case "?order_history":
        my_orders("order_history");
        break;
      case "?my_cart":
        my_cart();
        break;
      case "?profile":
        profile();
        break;
      case "?services":
        services_tab();
        break;
      case "":
        home_content();
        break;
    }
  }
}
load_tab();
SpeechRecognitionAlternative();
load_tab();
window.addEventListener("popstate", function () {
  load_tab();
  document
    .getElementById("menu_container")
    .classList.remove("menu_container-display");
  // remove_spinner();
});
window.addEventListener("online", () => {
  alert_el.className = alert_el.className.replace("show-alert", "");
  // remove_spinner();
});
window.addEventListener("offline", () => {
  // loading_spinner();
  document.getElementById("alert").innerHTML = `No Internet..❗❗`;
  alert_el.className = "show-alert";
});