document.addEventListener('DOMContentLoaded', function () {
    new Splide('#img-container-edit', {
        type: "loop",
        autoHeight: true,
        lazyLoad: 'nearby',
    }).mount();
});
function textAreaAdjust(element) {
    element.style.height = "1px";
    element.style.height = (20 + element.scrollHeight) + "px";
};
$(document).on("submit", "#upload_item", function (e) {
    const p_id = window.location.search.split("=")[1];
    let url_str = `./unv_product_upload.php?edit=${p_id}&upload_item`;
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
            if (Number(data)===1){
                window.location.reload();
            }else{
                (Number(data)===9)?snacbar("Image too big or not supported"):snackbar("Somethin went wrong..Refresh Page");
            }
        }
    });
});
var decodedCookie = decodeURIComponent(document.cookie);
const root = document.querySelector(":root");
// if (decodedCookie.includes('cs_theme') && decodedCookie.includes('dark_mode_on')) {
//     root.style.setProperty('--body-background', '#00121a');
//     root.style.setProperty('--body-text-color', '#eafffc');
//     root.style.setProperty('--product-container-background', '#e7e7e700');
//     root.style.setProperty('--label-text-color', '#ffffff');
//     root.style.setProperty('--input-text-color', '#d2d9f6');
//     root.style.setProperty('--textarea-color', '#d5defa');
//     root.style.setProperty('--btn-background', '#0077ff');
// } else {
//     root.style.setProperty('--body-background', '#ffffff');
//     root.style.setProperty('--body-text-color', '#001e22');
//     root.style.setProperty('--product-container-background', '#e7e7e7');
//     root.style.setProperty('--label-text-color', '#002733');
//     root.style.setProperty('--input-text-color', '#000f0e');
//     root.style.setProperty('--textarea-color', '#00103');
//     root.style.setProperty('--btn-background', '#6890ff');
// };
$(document).on("click", "#add_color-btn", function () {
    console.log('sadd');
    document.querySelector('#product_color_input-container').insertAdjacentHTML("beforeend", `<input type='color' name='product_color[]'>`)
});
$(document).on("click", "#remove_color-btn", function () {
    document.querySelector('#product_color_input-container').lastChild.remove();
});