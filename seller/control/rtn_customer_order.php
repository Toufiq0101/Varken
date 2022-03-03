<?php include "../database_connection.php";
session_start();
if (isset($_SESSION['client_id']) && isset($_POST['cust_dtl'])) {
    $cus_dtl_str = explode(':', $_POST['cust_dtl']);
    $u_id = $cus_dtl_str[1];
    $u_query = "SELECT user_name,user_address,phone_number FROM user_storage WHERE user_id = $u_id";
    $send_u_query = mysqli_query($user_connection, $u_query);
    while ($rows = mysqli_fetch_assoc($send_u_query)) {
        $customer_name = $rows['user_name'];
        $customer_address = $rows['user_address'];
        $customer_ph_num = $rows['phone_number'];
        echo "<div class='customer_detail ggg'><span class='customer_dtl name'>$customer_name</span><span class='customer_dtl address'><span>Add:- </span><span>$customer_address</span></span><span class='customer_dtl ph_no'>Ph: $customer_ph_num</span></div>";
    };
    $p_id = $cus_dtl_str[2];
    $total_bill = 0;
    $img_ext_arr = array('jpg', 'jpeg', 'png', 'webp');
    $img_check = false;
    foreach ($img_ext_arr as $img_ext) {
        if (strpos($p_id, $img_ext)) {
            $img_check = true;
        }
    };
    if ($img_check) {
        echo "<div class='product_overview_container'><div class='product_overview'><img class='product-image zoom-img' id='zoom-img' src='../../uploaded_files/$p_id' ></div></div>";
        $total_bill = 0;
    } elseif (!$img_check && $p_id) {
        $p_id = explode('=',$p_id)[0];
        $p_query = "SELECT product_name,product_image,product_price FROM product_storage WHERE product_id = $p_id";
        $send_p_query = mysqli_query($product_connection, $p_query);
        while ($rows = mysqli_fetch_assoc($send_p_query)) {
            $product_name = $rows['product_name'];
            $product_all_images = explode(' ', $rows['product_image']);
            $product_price = $rows['product_price'];
            $formated_price = preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $product_price);
            echo "<div class='product_overview_container'><div class='product_overview'><div class='product_image'><img class='product-image' src='../../uploaded_files/$product_all_images[0]'></div><div class='product_details product_page_link'><span class='product-name'>$product_name</span><span class='product-price'>Rs. $formated_price</span></div></div></div>";
            if (is_numeric($product_price)) {
                $total_bill = $total_bill + $product_price;
            };
        };
    };
    $formated_total_bill = preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $total_bill);
    echo "<div class='parcel_btn-container'><span class='total_bill' style='margin-left: auto;margin-right: auto;'>Total : $formated_total_bill</span></div>";
} else {
    echo "err|Ş`(*⁂‖﹏⁂‖*)′Ş|err%^%401";
};