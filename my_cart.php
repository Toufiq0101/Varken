<?php
include "./db_connection.php";
session_start();
?>

<?php
if (isset($_SESSION['user_id'])) {
    echo "<div class='tab-heading'>My Cart</div>";
    $cart_query = "SELECT my_cart FROM user_storage WHERE user_id = $_SESSION[user_id]";
    $cart_sql = mysqli_query($user_connection, $cart_query);
    while ($row = mysqli_fetch_assoc($cart_sql)) {
        $cart = $row['my_cart'];
    };
    $cart_per_pack_arr = explode(';', $cart);
    if (isset($cart) && $cart === '') {
        echo "
<div class='no_content_err-container'>
<img src='./css/svg/empty_cart.svg' alt='' class='no_content_err-img' loading='lazy'>
<span class='no_content_err-msg'>Add to cart anything to Buy altogther<span>
</div>";
        die();
    } elseif (count($cart_per_pack_arr) > 0) {
        $total_price = 0;
        $cart_p_id_str = '';
        foreach ($cart_per_pack_arr as $cart_per_pack) {
            $cart_dtl = explode(':', $cart_per_pack);
            if ($cart_dtl[0]) {
                $a = explode('=', $cart_dtl[2]);
                $p_dtl = explode('|', $a[1]);
                $p_qnty = $p_dtl[0];
                $p_size = $p_dtl[1];
                $p_color = $p_dtl[2];
                $p_id = $a[0];
                $get_product_query = "SELECT product_name,product_price,product_image FROM product_storage WHERE product_id =$p_id";
                $get_product_sql = mysqli_query($product_connection, $get_product_query);
                while ($p_rows = mysqli_fetch_assoc($get_product_sql)) {
                    $product_name = $p_rows['product_name'];
                    $product_price = preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $p_rows['product_price']);
                    $product_images = explode(' ', $p_rows['product_image']);
                    $total_price = $total_price + $p_rows['product_price'];
                    echo "
<div class='main-overview-container'>
<div class='overview_container'>
<a class='overview-img_container'>
<img src='../uploaded_files/$product_images[0]' class='overview-img' alt='img' loading='lazy'>
</a>
<div class='overview-details-list '>
<a href = './product.php?i=$p_id' class='overview-detail name'>$product_name</a>
<a href = './product.php?i=$p_id' class='overview-detail price'>Rs. $product_price</a>";
                    if (isset($p_size) && $p_size !== '') {
                        echo "<a href = './product.php?i=$p_id' class='overview-detail price'>Size : " . strtoupper($p_size) . "</a>";
                    };
                    if (isset($p_color) && $p_color !== '') {
                        echo "<a href = './product.php?i=$p_id' class='overview-detail price'>Color : <span class='product_color' style='background-color:#000000;' data-color='#000000'></span></a>";
                    };
                    echo "<div class='btn-container'>Qnt : 
                    <div class='l6-p-c-cart-btn'><span class='del_cart_item_btn'
                    data-cart_str='$cart_dtl[1]:$p_id' onclick='my_cart()'>–</span><span>$p_qnty</span><span class='quicki_add_to_cart-btn' data-add_item='$cart_dtl[1]:$p_id'>+</span>
                </div>
                </div>
                </div></div></div>";
                    // <img class='delete-icon del_cart_item_btn' data-cart_str='$cart_dtl[1]:$p_id' src='./css/svg/remove_cart.svg'>
                };
            };
        };
        $total_price = preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $total_price);
        echo "
<div class='cart_order-detail'>
<span class='cart_total-price'>Total Cost :- $total_price</span>
<span onclick='document.querySelector(`.order_reciept-container-modal`).style.display=`block`' class='order_cart-btn' >Order Cart</span>
</div>
";
    };
    echo "<div onclick='document.querySelector(`.order_reciept-container-modal`).style.display=`none`' id='order_reciept-container-modal' class='order_reciept-container-modal'>
<div class='order_reciept-container animate_reciept-container'>
<div class='reciept-heading'>Order Confirmation</div>
<span onclick='document.getElementById(order_reciept-container-modal).style.display=none'
class='close_order_reciept' title='Close'>&times;</span>
<div class='user_details-container'>
<span>Your Details :-</span>
<ul>
<li>$_SESSION[user_name]</li>
<li>Ph :- $_SESSION[user_ph_num]</li>
<li>$_SESSION[user_address]</li>
</ul>
</div>
<div class='highlights'>
<span>
Highlights :-
</span>
<ul>
<li>Within 60 minitues delivery.</li>
<li>Cash on Delivery only</li>
<li>Easy Returns</li>
<li>On spot Check on Food items</li>
</ul>
<div class='delivery-price'>Delivery Cost :- Any Tip as Your Wish.</div>
</div>
<div class='order-decision-btn-container'>
<div class='total-price'>
<span>
Total :-
</span>
<span>
Rs.$total_price
</span>
</div>
<div id='order_cart-btn' class='reciept-order-btn'>
ORDER
</div>
</div>
</div>
</div>";
}
?>