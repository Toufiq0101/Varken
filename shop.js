function snackbar(message) {
  const alert_el = document.getElementById("order-alert");
  document.getElementById("order-alert").innerHTML = `${message}`;
  alert_el.className = "show-alert";
  setTimeout(function () {
    alert_el.className = alert_el.className.replace("show-alert", "");
  }, 3000);
}
const store_id = window.location.search.split("=");
function fav_store_check() {
  if (decodeURIComponent(document.cookie).includes("u_authentication")) {
    $.ajax({
      url: "./control/control_fav_store.php",
      type: "POST",
      data: { fav_store_check: store_id[1] },
      success: function (data) {
        if (Number(data) === 1) {
          document
            .querySelector("#fav-store-btn")
            .setAttribute("fill", "#ff0040");
        } else {
          // document
          //   .querySelector("#fav-store-btn")
          //   .setAttribute("fill", "#ffffff");
        }
      },
    });
  }
}
fav_store_check();
$(document).ready(function () {
  $(document).on("click", "#fav-store-btn-container", function () {
    const fav_btn = document.querySelector("#fav-store-btn");
    if (decodeURIComponent(document.cookie).includes("u_authentication")) {
      $.ajax({
        url: "./control/control_fav_store.php?fav_store",
        type: "POST",
        data: { fav_store_id: $("#fav-store-btn-container").data("c_id") },
        success: function (data) {
          console.log(decodeURIComponent(document.cookie));
          Number(data) === 1
            ? fav_btn.setAttribute("fill", "#ff0040")
            : fav_btn.setAttribute("fill", "#ffffff");
        },
      });
    } else {
      snackbar("Login/Register First");
    }
  });
});
$(document).on("submit", "#img_order-form", function (e) {
  e.preventDefault();
  let formData = new FormData(this);
  if (decodeURIComponent(document.cookie).includes("u_authentication")) {
    $.ajax({
      url: `./control/orders.php?c_id=${store_id[1]}&order_img`,
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,
      success: function (data) {
        console.log(data);
        if (Number(data) === 1) {
          snackbar("Ordered Sucessfully..!");
        } else {
          Number(data) === 9
            ? snackbar("Image too big or not supported")
            : data.includes("loc404")
              ? snackbar("Location Not Set")
              : snackbar("Order Failed");
        }
      },
    });
  } else {
    snackbar("Login/Register First");
  }
});
$(document).on("click", "#hire_me-btn", function () {
  if (decodeURIComponent(document.cookie).includes("u_authentication")) {
    var s_id_array = [];
    $.each($("input[name='ord_service_id']:checked"), function () {
      s_id_array.push($(this).val());
    });
    const service_ord_str = `${$(this).data("c_id")}:${s_id_array.join(",")}:S`;
    if (service_ord_str !== "") {
      $.ajax({
        url: "./control/orders.php",
        type: "POST",
        data: { order_str: service_ord_str },
        success: function (data) {
          console.log(data);
          if (data.includes("err|Ş`(*⁂‖﹏⁂‖*)′Ş|err")) {
            error_handler(data.split("%^%")[1]);
          } else {
            if (Number(data) === 1) {
              snackbar("Hired Sucessfully");
            } else {
              data.includes("loc404")
                ? snackbar("Location Not Set")
                : snackbar("Request Failed");
            }
          }
        },
      });
    }
  } else {
    snackbar("Login/Register First");
  }
});


// 

function search(query) {
  // let searched_prd_markup = ``;
  algoliaClient_shop.initIndex('varken_products').search(query, {
    aroundLatLng: '23.381809993197447, 85.32847040217709',
    fitler: ''
  }).then(({ hits }) => {
    // document.querySelector('main').innerHTML = searched_prd_markup;
    hits.forEach(prd_dtl => {
      console.log(prd_dtl)
    })
  })
}

// 

// const root = document.querySelector(":root");
// if (
//   decodeURIComponent(document.cookie).includes("u_theme") &&
//   decodeURIComponent(document.cookie).includes("dark_mode_on")
// ) {
//   root.style.setProperty("--body-background", "#00111d");
//   root.style.setProperty("--body-text-color", "#fffff");
//   root.style.setProperty("--store-info-container-background", "#ffffff00");
//   root.style.setProperty("--store-product-title", "#e2e1e1");
//   root.style.setProperty("--store-product-container-background", "#ffffff00");
//   root.style.setProperty("--store-detail-text", "#e2e1e1");
//   root.style.setProperty("--product-card-background", "#00141d");
//   root.style.setProperty("--card-text", "#e2e1e1");
// } else {
//   root.style.setProperty("--body-background", "#ffffff");
//   root.style.setProperty("--body-text-color", "#000a0f");
//   root.style.setProperty("--store-info-container-background", "#ffffff00");
//   root.style.setProperty("--store-product-title", "#000311");
//   root.style.setProperty("--store-product-container-background", "#ffffff00");
//   root.style.setProperty("--store-detail-text", "#001118");
//   root.style.setProperty("--product-card-background", "#ffffff");
//   root.style.setProperty("--card-text", "#000e1f");
// }
document.querySelector('#more_details-btn').addEventListener("click", function () {
  document.querySelector('.some_more_store_dtls').classList.toggle('no_display')
})



// 


const algoliaClient_shop = algoliasearch(
  '2BNKFRXSL7',
  '3f16cfd9712309205322ece7ae637084'
);

console.log(window.location.search.split("=")[1])
function search(query) {
  console.log(query)
  let searched_prd_markup = ``;
  algoliaClient_shop.initIndex('varken_products').search(query, {
    aroundLatLng: '23.381809993197447, 85.32847040217709',
    // filters: `seller_id:${window.location.search.split("=")[1]}`,
  }).then(({ hits }) => {
    console.log(hits);
    document.querySelector('.l6-p-cntnr').innerHTML = searched_prd_markup;
    hits.forEach(prd_dtl => {
      if ($(`[data-markup_check="${prd_dtl.product_name}:${prd_dtl.product_price}"]`)[0]) {
      } else {
        searched_prd_markup = `<div class='l6-p-card-cntnr' data-markup_check='${prd_dtl.product_name}:${prd_dtl.product_price}'>
                            <a class='l6-p-card-p_link' href='/product.php?i=${prd_dtl.product_id}'>
                            <div class='l6-p-c-img'>
                            <img src='/uploaded_files/${(prd_dtl.product_image).split(" ")[0]}' alt=''/>
                            <span class='prd-rating'>4.5</span>
                            </div>
                            <div class='l6-p-c-name'>${prd_dtl.product_name}</div>
                            <div class='l6-p-c-price'>Rs. ${prd_dtl.product_price}</div>
                            </a>
                            <div class='l6-p-c-btn-cntnt'>
                            <div class='l6-p-c-cart-btn'><img src='/css/svg/shopping-cart.svg'></div>
                            <div class='l6-p-c-order-btn'><img src='/css/svg/quick_order_btn.svg'></div>
                            </div>
                            </div>`;
        document.querySelector('.l6-p-cntnr').insertAdjacentHTML("beforeend", searched_prd_markup)
      }
    });
    // if (!window.location.search.includes(`?search=${query}`)) {
    //   history.pushState("", "", `?search=${query}`);
    // };
  })
};
document.getElementById("inside_store-srch-bar").addEventListener("keydown", function (e) {
  if (e.code === 'Enter') {
    search(this.value)
  }
});
document.getElementById("inside_store-srch-btn").addEventListener("click", () => {
  search(document.getElementById("inside_store-srch-bar").value);
});
document.getElementById("search-field").addEventListener("keydown", function (e) {
  if (e.code === 'Enter') {
    window.location.href = `/?search=${document.getElementById("search-field").value}`
  }
});
document.getElementById("search-btn").addEventListener("click", () => {
  window.location.href = `/?search=${document.getElementById("search-field").value}`
});