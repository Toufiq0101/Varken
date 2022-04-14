function open_quickii_modal(p_img = '', p_name = '', p_price = '', p_color = '', p_size = '', p_p_id = '', p_id) {
    console.log(p_name);
    let quickii_modal_markup = `<div class='quick_order-modal'>
    <span id='close_quickii-modal'>&times</span>
    <div class='main-overview-container quickii_p_dtl'>
        <div class='overview_container'>
            <span class='overview-img_container'>
                <img class='overview-img' src='../../uploaded_files/${p_img}' alt='img'
                    loading='lazy'>
            </span>
            <span class='overview-details-list '>
                <span class='overview-detail name'>${p_name}</span>
                <span class='overview-detail price'>Rs. ${p_price}</span>
            </span>
        </div>
    </div>`;
    if (p_color !== '' && p_color != 'undefined') {
        quickii_modal_markup += `<span class='color_option-container'>Colors : `;
        p_color.split(',').forEach(color => {
            if (color !== '') {
                quickii_modal_markup += `<span class='color-option' style='background-color:${color};' data-color='${color}'></span>`;
            }
        });
        quickii_modal_markup += `</span>`;
    };
    if (p_size !== '') {
        quickii_modal_markup += `<span class='size_option-container'>Sizes :`;
        p_size.split(',').forEach(size => {
            if (size !== '') {
                quickii_modal_markup += `<span class='size-option' data-size='${size}'>${size}</span>`;
            }
        });
        quickii_modal_markup += `</span>`;
    }
    quickii_modal_markup += `<div class='size_option-container'>Any specification : <input type='text' data-msg='' class='specific_msg' id='specific-msg-bar' placeholder='any specific message to seller'></div>`;
    quickii_modal_markup += `<div class='product_btn-container'>
    <span class='add-to-cart-btn quicki_add_to_cart-btn' data-add_item='${p_p_id}:${p_id}'>Add To
        Cart</span>
    <span class='order-btn' id='order_btn' data-p_name='${p_name}' data-order_str='${p_p_id}:${p_id}'>ORDER</span>
</div></div>`;
    document.querySelector('#quickii_modal-container').innerHTML = quickii_modal_markup;
    document.querySelector('.quckii-modal-modal-container').style.display = 'block';
    document.querySelector("#close_quickii-modal").addEventListener("click", function () {
        document.querySelector(".quick_order-modal").style.display = 'none';
        document.querySelector('.quckii-modal-modal-container').style.display = 'none';
    });
};