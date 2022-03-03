let offset;
let load_more_ctrl_var;
const footer = document.querySelector("#footer");
const load_data = function (entries) {
    const search_var = window.location.search.split('=');
    const entry = entries[0];
    if (entry.isIntersecting) {
        function load_more_cntr(url) {
            $.ajax({
                url: `${url}`,
                type: "POST",
                data: { offset: offset, srch_key_str: search_var[1], tab: search_var[0] },
                success: function (data) {
                    if (Number(data) === 0) {
                        load_more_ctrl_var = 0;
                    } else {
                        $("#main-content").append(data);
                        offset = offset + 10;
                    };
                }
            });
        };
        if (load_more_ctrl_var === 1) {
           load_more_cntr(`./my_entries.php`);
        };
    };
};
const footer_observer = new IntersectionObserver(load_data, {
    root: null,
    rootMargin: "500px",
    threshold: 0.1,
});
footer_observer.observe(footer);
// Search Script
function search_key(search_str) {
    let srch_key_str = $("#search-input").val();
    if (srch_key_str !== '') {
        $.ajax({
            url: `./search_engine.php`,
            type: "POST",
            data: { srch_key_str: srch_key_str },
            success: function (data) {
                inp_val = '';
                $("#main-content").html(data);
                const search = window.location.search.split('=');
                if (search[0] !== '?search' || search[1] !== srch_key_str) {
                    history.pushState('', '', `?search=${srch_key_str}`);
                }
                offset = 10;
                load_more_ctrl_var = 1;
            }
        });
    };
};
$(document).on("click", "#search-btn", function () {
    const search_str = window.location.search.split('=');
    search_key('search_input', search_str)
})
function profile() {
    $.ajax({
        url: `./new_user.php`,
        type: "GET",
        success: function (data) {
            $("#main-content").html(data);
        }
    });
};
// MY GARAGE
function my_entries() {
    $.ajax({
        url: "./my_entries.php",
        type: "GET",
        success: function (data) {
            $("#main-content").html(data);
            offset = 10;
            load_more_ctrl_var = 1;
            if (window.location.search !== '') {
                history.pushState('', '', '/warehouse/index.html');
            };
        }
    })
};
my_entries();
$(document).on("click","#my_entries", function () {
    my_entries();
});
// DELETE ITEM
$(document).on("click", "#delete_item", function () {
    var del_p_id = [];
    $.each($("input[name='del_p_id']:checked"), function () {
        del_p_id.push($(this).val());
    });
    const del_p_id_str = `${del_p_id.join(",")}`;
    if (del_p_id_str !== '') {
        $.ajax({
            url: "./control_enteries.php?delete",
            type: "POST",
            data: { del_str: del_p_id_str },
            success: function (data) {
                my_entries();
            }
        })
    }
});
// UPLOAD ITEM
$(document).on("submit", "#product-upload-form", function (e) {
    e.preventDefault();
    let formData = new FormData(this);
    $.ajax({
        url: "./unv_product_upload.php?upload_item",
        type: "post",
        data: formData,
        contentType: false,
        processData: false,
        success: function (data) {
            my_entries();
        },
    })
});
//PROFILE
$(document).on("click", "#profile-btn", function () {
    $.ajax({
        url: "./client_profile.php?edit",
        type: "GET",
        success: function (data) {
            $("#main-content").html(data);
        }
    })
});
$(document).on("submit", "#profile_form", function (e) {
    e.preventDefault();
    const formdata = new FormData(this);
    $.ajax({
        url: "./control/upload_register_form.php?edit",
        data: formdata,
        type: "POST",
        contentType: false,
        processData: false,
        success: function () {
            my_entries();
        }
    })
});
$(document).on("click", "#unv_product_tab-btn", function () {
    document.querySelector("#main-content").innerHTML = `<div class="nav-top-item">
    <input type="search" id="unv_search_input" placeholder="search...Warehouse">
    <i id="search_unv-btn" class="fi-br-search">search</i>
</div>
<div id="unv_products"></div>`
});
$(document).on("click","#search_unv-btn",function () {
    let srch_key_str = $("#unv_search_input").val()
    if (srch_key_str !== '') {
        $.ajax({
            url: `./search_engine.php?unv_srch`,
            type: "POST",
            data: { srch_key_str: srch_key_str },
            success: function (data) {
                inp_val = '';
                $("#unv_products").html(data);
                if (window.location.search !== '?unv') {
                    history.pushState('', '', '?unv');
                };
            }
        });
    };
});
$("#register_form").on("submit", function (e) {
    e.preventDefault();
    const formdata = new FormData(this);
    $.ajax({
        url: "./ctrl_rgst_login.php?register",
        data: formdata,
        type: "POST",
        contentType: false,
        processData: false,
        success: function (data) {
        }
    });
});
$("#login_form").on("submit", function (e) {
    e.preventDefault();
    const formdata = new FormData(this);
    $.ajax({
        url: "./ctrl_rgst_login.php?login",
        data: formdata,
        type: "POST",
        contentType: false,
        processData: false,
        success: function (data) {
            // if (Number(data) == 1) {
            //     $("#err_log").html("Internal Server Problem Please Refresh the Page & then Try Again");
            //     window.location.reload();
            // } else {
            //     if (Number(data) == 0 || data == 101) {
            //         $("#err_log").html("Username or Password may be incorrect");
            //         console.log(data);
            //     };
            // };
        }
    });
});