$.ajax({
  url: "./control/verify.php?verify",
  type: "GET",
  success: function (data) {
    let data_disable;
    if (Number(data) === 1) {
      tab();
      data_disable = false;
    } else {
      data_disable = true;
    }
    document
      .getElementById("customer_orders")
      .setAttribute("data-disable", data_disable);
    document
      .getElementById("pending_orders")
      .setAttribute("data-disable", data_disable);
    document
      .getElementById("return_rqsts")
      .setAttribute("data-disable", data_disable);
    document
      .getElementById("on_the_way")
      .setAttribute("data-disable", data_disable);
    document
      .getElementById("unv_products")
      .setAttribute("data-disable", data_disable);
  },
});
function loading_spinner() {
  document.getElementById("loading-spinner-container").style.display = "flex";
}
function remove_spinner() {
  document.getElementById("loading-spinner-container").style.display = "none";
}
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
        data: {
          offset: offset,
          srch_key_str: search_var[1],
          tab: search_var[0],
        },
        success: function (data) {
          if (Number(data) === 0) {
            load_more_ctrl_var = 0;
          } else {
            $("#main-content").append(data);
            offset = offset + 10;
          }
        },
      });
    }
    if (load_more_ctrl_var === 1) {
      search_var[0].includes("unv")
        ? load_more_cntr(`./control/all_unv_products.php`)
        : search_var[0].includes("search")
        ? load_more_cntr(`./control/search_engine.php?${search_var[1]}`)
        : search_var[0].includes("")
        ? load_more_cntr(`./control/client_all_products.php`)
        : "";
    }
  }
};
const footer_observer = new IntersectionObserver(load_data, {
  root: null,
  rootMargin: "300px",
  threshold: 0.1,
});
footer_observer.observe(footer);
function snackbar(message) {
  const alert_el = document.getElementById("alert");
  document.getElementById("alert").innerHTML = `${message}`;
  alert_el.className = "show-alert";
  setTimeout(function () {
    alert_el.className = alert_el.className.replace("show-alert", "");
  }, 3000);
}
function error_handler(err_code) {
  const code = Number(err_code);
  let markup;
  switch (code) {
    case 401:
      snackbar("Login/Register First..!");
      break;
    case 4010:
      markup = `<div class='login_err_markup-container'>Create a Shop and Lets <span class='login_err_start-btn'>Start</span></div>`;
      break;
    default:
      snackbar("Something went Wrong..Refresh");
      break;
  }
  if (markup) {
    document.querySelector("#main-content").innerHTML = markup;
  }
}
var alert_el = document.getElementById("alert");
//search_tab_var is 'unv' as default that should be fixed
//changes at line 4 and in bottom at popstate event
let search_tab_var = "home";
function search_product(search_str) {
  offset = 0;
  $.ajax({
    url: "./control/search_engine.php",
    type: "POST",
    data: { srch_key_str: search_str, tab: "unv" },
    success: function (data) {
      remove_spinner();
      $("#main-content").html(data);
      const search = window.location.search.split("=");
      if (search[0] !== "?search" || search[1] !== search_str) {
        history.pushState("", "", `?search=${search_str}`);
      }
      offset = 10;
      load_more_ctrl_var = 1;
    },
  });
}
function my_garage(data_num) {
  offset = 0;
  $.ajax({
    url: "./control/client_all_products.php",
    type: "POST",
    data: { data_num: data_num },
    success: function (data) {
      remove_spinner();
      if (data.includes("err|Ş`(*⁂‖﹏⁂‖*)′Ş|err")) {
        error_handler(data.split("%^%")[1]);
      } else {
        $("#main-content").html(data);
        offset = 10;
        load_more_ctrl_var = 1;
      }
    },
  });
}
function unv_prdt() {
  offset = 0;
  $.ajax({
    url: "./control/all_unv_products.php",
    type: "GET",
    success: function (data) {
      remove_spinner();
      if (data.includes("err|Ş`(*⁂‖﹏⁂‖*)′Ş|err")) {
        error_handler(data.split("%^%")[1]);
      } else {
        $("#main-content").html(data);
        offset = 10;
        load_more_ctrl_var = 1;
        if (window.location.search !== "?unv") {
          history.pushState("", "", "?unv");
        }
      }
    },
  });
}
$(document).on("click", "#add_to_garage", function () {
  var add_p_id = [];
  $.each($("input[name='add_p_id']:checked"), function () {
    add_p_id.push($(this).val());
  });
  const add_p_id_str = `${add_p_id.join(",")}`;
  if (add_p_id_str !== "") {
    $.ajax({
      url: "./control/add_unv_product.php?add_p_id",
      type: "POST",
      data: { add_p_id: add_p_id_str },
      success: function (data) {
        remove_spinner();
        if (data.includes("err|Ş`(*⁂‖﹏⁂‖*)′Ş|err")) {
          error_handler(data.split("%^%")[1]);
        } else {
          if(data==1){
            snackbar("Products Added to Your Shop")
          }
          unv_prdt();
        }
      },
    });
  }
});
$(document).on("click", "#delete_item", function () {
  var del_p_id = [];
  $.each($("input[name='del_p_id']:checked"), function () {
    del_p_id.push($(this).val());
  });
  const del_p_id_str = `${del_p_id.join(",")}`;
  if (del_p_id_str !== "") {
    $.ajax({
      url: decodeURIComponent(document.cookie).includes("c_authentication")
        ? "./control/delete_product.php?delete"
        : "./control/service_ctrl.php?delete",
      type: "POST",
      data: { del_str: del_p_id_str },
      success: function (data) {
        console.log(data);
        remove_spinner();
        if (data.includes("err|Ş`(*⁂‖﹏⁂‖*)′Ş|err")) {
          error_handler(data.split("%^%")[1]);
        } else {
          if (Number(data) === 1) {
            my_garage();
            snackbar("Deleted");
          } else {
            snackbar("Something went Wrong..Refresh");
          }
        }
      },
    });
  }
});
function orders(url_t_f = false) {
  $.ajax({
    url: "./control/orders.php",
    type: "POST",
    success: function (data) {
      remove_spinner();
      if (data.includes("err|Ş`(*⁂‖﹏⁂‖*)′Ş|err")) {
        error_handler(data.split("%^%")[1]);
      } else {
        $("#main-content").html(data);
        if (window.location.search !== "?cstm") {
          url_t_f ? window.history.go(-1) : history.pushState("", "", "?cstm");
        }
      }
    },
  });
}
$(document).on("click", "#customer_detail-line", function () {
  const cust_dtl_str = $(this).data("cust_dtl");
  $.ajax({
    url: "./control/customer_order.php",
    data: { cust_dtl: cust_dtl_str },
    type: "POST",
    success: function (data) {
      remove_spinner();
      if (data.includes("err|Ş`(*⁂‖﹏⁂‖*)′Ş|err")) {
        error_handler(data.split("%^%")[1]);
      } else {
        $("#main-content").html(data);
        if (window.location.search !== "?c_o") {
          history.pushState("", "", "?c_o");
        }
      }
    },
  });
});
$(document).on("click", "#parcel_btn", function () {
  const parcel_str = $(this).data("parcel_str");
  $.ajax({
    url: "./control/orders.php?send_order",
    type: "POST",
    data: { parcel_str: parcel_str },
    success: function (data) {
      remove_spinner();
      if (data.includes("err|Ş`(*⁂‖﹏⁂‖*)′Ş|err")) {
        error_handler(data.split("%^%")[1]);
      } else {
        orders();
      }
    },
  });
});
function pending_ord() {
  $.ajax({
    url: "./control/pending_orders.php",
    type: "GET",
    success: function (data) {
      remove_spinner();
      if (data.includes("err|Ş`(*⁂‖﹏⁂‖*)′Ş|err")) {
        error_handler(data.split("%^%")[1]);
      } else {
        $("#main-content").html(data);
        if (window.location.search !== "?p_o") {
          history.pushState("", "", "?p_o");
        }
      }
    },
  });
}
function rtrn_rqst(url_t_f = false) {
  $.ajax({
    url: "./control/rtn_rqst.php",
    type: "GET",
    success: function (data) {
      remove_spinner();
      if (data.includes("err|Ş`(*⁂‖﹏⁂‖*)′Ş|err")) {
        error_handler(data.split("%^%")[1]);
      } else {
        $("#main-content").html(data);
        if (window.location.search !== "?rtrn") {
          url_t_f ? window.history.go(-1) : history.pushState("", "", "?rtrn");
        }
      }
    },
  });
}
$(document).on("click", "#rtn_customer_detail-line", function () {
  const cust_dtl_str = $(this).data("rtn_cust_dtl");
  $.ajax({
    url: "./control/rtn_customer_order.php",
    data: { cust_dtl: cust_dtl_str },
    type: "POST",
    success: function (data) {
      remove_spinner();
      if (data.includes("err|Ş`(*⁂‖﹏⁂‖*)′Ş|err")) {
        error_handler(data.split("%^%")[1]);
      } else {
        $("#main-content").html(data);
        if (window.location.search !== "?r_o") {
          history.pushState("", "", "?r_o");
        }
      }
    },
  });
});
$(document).on("click", "#cncl_cstm_ord", function () {
  $.ajax({
    url: "./control/orders.php?cncl_cstm_ord",
    data: { cncl_cstm_ord: $(this).data("cncl_str") },
    type: "POST",
    success: function (data) {
      remove_spinner();
      if (Number(data) === 1) {
        orders();
      }
    },
  });
});
function profile() {
  $.ajax({
    url: "./client_profile.php?edit",
    type: "GET",
    success: function (data) {
      remove_spinner();
      if (data.includes("err|Ş`(*⁂‖﹏⁂‖*)′Ş|err")) {
        error_handler(data.split("%^%")[1]);
      } else {
        $("#main-content").html(data);
        if (window.location.search !== "?profile") {
          history.pushState("", "", "?profile");
        }
      }
    },
  });
}
$(document).on("submit", "#profile_form", function (e) {
  e.preventDefault();
  // if ($('#center-points-list [value="' + $("#center-point-input").val() + '"]').data('coords') !== undefined) {
  const formdata = new FormData(this);
  $.ajax({
    url: "./control/client_account_ctrl.php?edit",
    data: formdata,
    type: "POST",
    contentType: false,
    processData: false,
    success: function (data) {
      console.log(data);
      remove_spinner();
      if (data.includes("err|Ş`(*⁂‖﹏⁂‖*)′Ş|err")) {
        error_handler(data.split("%^%")[1]);
      } else {
        profile();
        if (Number(data) === 1) {
          snackbar("Saved");
        } else {
          Number(data) === 9
            ? snackbar("Image too big or unsupported")
            : snackbar("Something went Wrong..Refresh");
        }
      }
    },
  });
  // } else {
  // snackbar(`Services Unavailable at this location<br><a style="color:blue;" href='https://wa.me/qr/5DJPSM4CB47GN1'>Contact Us</a>`);
  // };
});
function on_the_way() {
  $.ajax({
    url: "./control/on_the_way.php?courier",
    type: "GET",
    success: function (data) {
      remove_spinner();
      if (data.includes("err|Ş`(*⁂‖﹏⁂‖*)′Ş|err")) {
        error_handler(data.split("%^%")[1]);
      } else {
        $("#main-content").html(data);
        if (window.location.search !== "?otw") {
          history.pushState("", "", "?otw");
        }
      }
    },
  });
}
$(document).on("submit", "#product-upload-form", function (e) {
  let url_str = decodeURIComponent(document.cookie).includes("c_authentication")
    ? "./control/product_ctrl.php?upload_item"
    : "./control/service_ctrl.php?upload_item";
  e.preventDefault();
  let formData = new FormData(this);
  $.ajax({
    url: url_str,
    type: "post",
    data: formData,
    contentType: false,
    processData: false,
    success: function (data) {
      console.log(data);
      remove_spinner();
      if (data.includes("err|Ş`(*⁂‖﹏⁂‖*)′Ş|err")) {
        error_handler(data.split("%^%")[1]);
      } else {
        my_garage();
        document.getElementById("product-upload-form").reset();
        document.getElementById("upload-product-form-container").style.display =
          "none";
      }
    },
  });
});
$(document).on("click", "#search-btn", function () {
  const search_str = $("#search-input").val();
  if (search_str !== "" && search_str) {
    loading_spinner();
    search_product(search_str);
  }
});
$("#my_garage").on("click", function () {
  if ($(this).data("disable") !== true) {
    loading_spinner();
    my_garage(0);
    if (window.location.search !== "") {
      history.pushState("", "", "/seller/");
    }
  } else {
    snackbar("Login/Register First..!");
  }
});
$(document).on("click", "#unv_products", function () {
  if ($(this).data("disable") !== true) {
    loading_spinner();
    unv_prdt();
  } else {
    snackbar("Login/Register First..!");
  }
});
$("#customer_orders").on("click", function () {
  if ($(this).data("disable") !== true) {
    loading_spinner();
    orders();
  } else {
    snackbar("Login/Register First..!");
  }
});
$("#pending_orders").on("click", function () {
  if ($(this).data("disable") !== true) {
    loading_spinner();
    pending_ord();
  } else {
    snackbar("Login/Register First..!");
  }
});
$("#return_rqsts").on("click", function () {
  if ($(this).data("disable") !== true) {
    loading_spinner();
    rtrn_rqst();
  } else {
    snackbar("Login/Register First..!");
  }
});
$(document).on("click", "#on_the_way", function () {
  if ($(this).data("disable") !== true) {
    loading_spinner();
    on_the_way();
  } else {
    snackbar("Login/Register First..!");
  }
});
$(document).on("click", "#profile-btn", function () {
  loading_spinner();
  profile();
});
$(document).on("click", ".login_err_start-btn", function () {
  loading_spinner();
  profile();
});
function tab() {
  if (window.location.search.includes("?search")) {
    search_product(window.location.search.split("=")[1]);
  } else {
    switch (window.location.search) {
      case "?cstm":
        orders();
        break;
      case "?rtrn":
        rtrn_rqst();
        break;
      case "?otw":
        on_the_way();
        break;
      case "?p_o":
        pending_ord();
        break;
      case "?unv":
        search_tab_var = "unv";
        unv_prdt();
        break;
      case "?c_o":
        orders(true);
        break;
      case "?r_o":
        rtrn_rqst(true);
        break;
      case "?profile":
        profile();
        break;
      case "":
        my_garage();
        break;
    }
  }
}
tab();
window.addEventListener("popstate", function () {
  tab();
  document
    .getElementById("menu_container")
    .classList.remove("menu_container-display");
  if (
    !window.location.search.includes("?search") &&
    search_tab_var === "unv" &&
    window.location.search !== "?unv"
  ) {
    search_tab_var = "home";
  }
});
window.addEventListener("online", () => {
  alert_el.className = alert_el.className.replace("show-alert", "");
  remove_spinner();
});
window.addEventListener("offline", () => {
  loading_spinner();
  document.getElementById("alert").innerHTML = `No Internet..❗❗`;
  alert_el.className = "show-alert";
});
