<?php include "../database_connection.php";
session_start();
if ($_SESSION['client_id']) {
    $cus_dtl_str = explode(':', $_POST['cust_dtl']);
    $u_id = $cus_dtl_str[1];
    $u_query = "SELECT user_name,user_address,phone_number FROM user_storage WHERE user_id = $u_id";
    $send_u_query = mysqli_query($user_connection, $u_query);
    while ($rows = mysqli_fetch_assoc($send_u_query)) {
        $customer_name = $rows['user_name'];
        $customer_address = $rows['user_address'];
        $customer_ph_num = $rows['phone_number'];
        echo "<div class='customer_detail'><span class='customer_dtl name'>$customer_name</span><span class='customer_dtl address'><span>Add:- </span><span>$customer_address</span></span><span class='customer_dtl ph_no'>Ph: $customer_ph_num</span></div>";
    };
    $all_p_id_str = $cus_dtl_str[2];
    $p_id_array = explode(',', $all_p_id_str);
    $total_bill = 0;
    if ($_SESSION['seller_type'] === 'Services') {
        $p_id_str = rtrim($all_p_id_str, ", ");
        $service_sql = mysqli_query($product_connection, "SELECT service_name,service_cost,average_time,service_id FROM service_storage WHERE service_id IN ($p_id_str) ");
        while ($rows = mysqli_fetch_assoc($service_sql)) {
            $service_name = $rows['service_name'];
            $service_price = $rows['service_cost'];
            $service_time = $rows['average_time'];
            $service_id = $rows['service_id'];
            $formated_price = preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $rows['service_cost']);
            echo " <div class='service_container'> <span id='cncl_cstm_ord' class='cncl_cstm_ord-btn' data-cncl_str= '$_SESSION[client_id]:$u_id:$service_id:S'>&times</span> <div class='column-1'> <div class='service_name'>$service_name</div> <div class='service_time'><div style='font-size:18px;'>⏱️</div>$service_time min</div> </div> <div class='column-2'> <div class='service_cost'>Rs. $formated_price</div> </div> </div>";
        };
    } else {
        foreach ($p_id_array as $p_id) {
            if (strpos($p_id, '=')) {
                $p_dtl = explode('=', $p_id);
                $prd_specifics = explode('|', $p_dtl[1]);
                $p_id = $p_dtl[0];
            } else {
                $prd_specifics = '';
            };
            $img_check = false;
            $imageExt = explode('.', $p_id);
            $imageActExt = strtolower(end($imageExt));
            $image_ext_allowed = array('jpg', 'jpeg', 'png', 'webp');
            if (in_array($imageActExt, $image_ext_allowed)) {
                $img_check = true;
            }
            if ($img_check) {
                echo " <div class='product_overview_container'> <div class='product_overview'> <span id='cncl_cstm_ord' class='cncl_cstm_ord-btn' data-cncl_str= '$_SESSION[client_id]:$u_id:$p_id'>&times</span> <img class='product-image zoom-img' id='zoom-img' src='../../uploaded_files/$p_id'></div></div>";
                $total_bill = 0;
            } elseif (!$img_check && $p_id) {
                $p_query = "SELECT product_name,product_image,product_price FROM product_storage WHERE product_id = $p_id";
                $send_p_query = mysqli_query($product_connection, $p_query);
                while ($rows = mysqli_fetch_assoc($send_p_query)) {
                    $product_name = $rows['product_name'];
                    $product_all_images = explode(' ', $rows['product_image']);
                    $product_price = $rows['product_price'];
                    $formated_price = preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $product_price);
                    echo " <div class='product_overview_container'><div class='product_overview'><span id='cncl_cstm_ord' class='cncl_cstm_ord-btn' data-cncl_str= '$_SESSION[client_id]:$u_id:$p_id";
                    if (isset($prd_specifics) && $prd_specifics !== '') {
                        echo "=$prd_specifics[0]|$prd_specifics[1]";
                    };
                    echo "'>&times</span><div class='product_image'> <img class='product-image' src='../../uploaded_files/$product_all_images[0]'></div><div class='product_details product_page_link'> <span class='product-name'>$product_name</span><span class='product-price'>Rs. $formated_price</span>";
                    if (isset($prd_specifics) && $prd_specifics !== '') {
                        if ($prd_specifics[0] !== '') {
                            echo "<span class='product_name'>Qnt: $prd_specifics[0]</span>";
                        }
                        if ($prd_specifics[1] !== '') {
                            echo "<span class='product_name'>Size: $prd_specifics[1]</span>";
                        }
                        if ($prd_specifics[2] !== '') {
                            echo "<span class='product_name'>Color: <input type='color' value='$prd_specifics[2]' disabled></span>";
                        }
                        if ($prd_specifics[3] !== '') {
                            echo "<span class='product_name'>Msg: $prd_specifics[3]</span>";
                        }
                    };
                    echo "</div></div></div>";
                    if (is_numeric($product_price)) {
                        $total_bill = $total_bill + $product_price;
                    };
                };
            };
        };
    };
    if ($_SESSION['seller_type'] !== 'Services') {
        $formated_total_bill = preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $total_bill);
        echo "<div class='parcel_btn-container'><span class='total_bill'>Total : $formated_total_bill</span><span class='parcel_btn' id='parcel_btn' data-parcel_str='$_POST[cust_dtl]:$total_bill:O'>Parcel</span>";
    }
} else {
    echo "err|Ş`(*⁂‖﹏⁂‖*)′Ş|err%^%401";
};
