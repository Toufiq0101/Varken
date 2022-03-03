<?php
include "./db_connection.php";
session_start();
?>

<?php
if (isset($_SESSION['user_id'])) {
    if (isset($_GET['my_orders'])) {
        echo "<div class='tab-heading'>My Orders</div>";
        $get_orders_query = "SELECT orders FROM user_storage WHERE user_id = $_SESSION[user_id]";
        $send_query = mysqli_query($user_connection, $get_orders_query);

        while ($rows = mysqli_fetch_assoc($send_query)) {
            $order_str = explode(';', $rows['orders']);
            if (count($order_str) <= 1) {
                echo "
<div class='no_content_err-container'>
<span class='no_content_err-msg'>Look around there's must be something for you..😜<span>
</div>";
                die();
            };
        };
    } elseif (isset($_GET['order_history'])) {
        echo "<div class='tab-heading'>Order History</div>";
        $get_orders_query = "SELECT order_history FROM user_storage WHERE user_id = $_SESSION[user_id]";
        $send_query = mysqli_query($user_connection, $get_orders_query);

        while ($rows = mysqli_fetch_assoc($send_query)) {
            $order_str = explode(';', $rows['order_history']);
            if ($rows['order_history'] === '') {
                echo "
<div class='no_content_err-container'>
<img src='./css/svg/error.svg' alt='' class='no_content_err-img' loading='lazy'>
<span class='no_content_err-msg'>History Box is empty<span>
</div>";
                die();
            };
        };
    };

    if (isset($order_str)) {
        foreach ($order_str as $p_dtl) {
            $ord_dtl = explode(':', $p_dtl);
            if ($ord_dtl[0]) {
                $img_check_state = false;
                $imageExt = explode('.', $ord_dtl[2]);
                $imageActExt = strtolower(end($imageExt));
                $img_ext_arr = array('jpg', 'jpeg', 'png', 'webp');
                if (in_array($imageActExt, $img_ext_arr)) {
                    $img_check_state = true;
                };
                if ($img_check_state) {
                    $img_name_arr = explode(',', $ord_dtl[2]);
                    $client_sql = mysqli_query($client_connection, "SELECT store_name FROM client_storage WHERE client_id=$ord_dtl[1]");
                    $client_name_row = mysqli_fetch_assoc($client_sql);
                    $client_name = $client_name_row['store_name'];
                    foreach ($img_name_arr as $ordered_img) {
                        if ($ordered_img) {
                            echo "
<div class='overview_container'>
<div class='overview-img_container'>
<img class='overview-img zoom-img' src='../uploaded_files/$ordered_img' loading='lazy'>
</div>
<div class='overview-details-list'>
<div class='overview-detail'>Ordered from $client_name</div>";
                            if (isset($ord_dtl[3]) && $ord_dtl[3] === 'R') {
                                echo "<div class='overview-detail'>Will be Recived soon</div>";
                            };
                            echo "<div class='btn-container'>
";
                            if (isset($_GET['my_orders'])) {
                                echo "<span class='btn-1' id='cancel-order' data-cancel_my_order_str='$ord_dtl[1]:$_SESSION[user_id]:$ord_dtl[2]:$ord_dtl[3]'>CANCEL</span>";
                            } elseif (isset($_GET['order_history'])) {
                                echo "<span class='btn-2' id='return-order' data-return_my_order_str='$ord_dtl[1]:$_SESSION[user_id]:$ord_dtl[2]'>RETURN</span>";
                            };
                            echo "</div>
</div>
</div>";
                        };
                    };
                } else {
                    if (isset($ord_dtl[3]) && $ord_dtl[3] === 'S' || (isset($_GET['order_history']) && $ord_dtl[2] === "SW")) {
                        $s_client_sql = mysqli_query($client_connection, "SELECT client_image,store_name,owner_name,store_location,phone_number FROM client_storage WHERE client_id = $ord_dtl[1]");
                        while ($row = mysqli_fetch_assoc($s_client_sql)) {
                            $client_name = $row['owner_name'];
                            $store_name = $row['store_name'];
                            $client_img = $row['client_image'];
                            $ph_num = $row['phone_number'];
                            $store_location = $row['store_location'];
                            echo "
<div class='overview_container'>
<div class='overview-img_container'>
<img class='overview-img zoom-img' src='../uploaded_files/$client_img' loading='lazy'>
</div>
<div class='overview-details-list'>
<div class='overview-detail'>Hired <span class='name'>$client_name</span> from <span class='name'>$store_name</span></div>
<div class='overview-detail location'>$store_location</div>";
                            echo "<div class='btn-container'>";
                            if (isset($_GET['my_orders'])) {
                                echo "<span class='btn-2' id='cancel-order' data-cancel_my_order_str='$ord_dtl[1]:$_SESSION[user_id]:$ord_dtl[2]:$ord_dtl[3]:D'>DONE</span>
<span class='btn-1' id='cancel-order' data-cancel_my_order_str='$ord_dtl[1]:$_SESSION[user_id]:$ord_dtl[2]:$ord_dtl[3]'>CANCEL</span>";
                            } elseif (isset($_GET['order_history']) && $ord_dtl[2] === "SW") {
                                echo "
<div class='overview-detail'><span class='store-location'>Service Provider is Sorring for not Comming</span></div>";
                            };
                            echo "</div>
</div>
</div>";
                        };
                    } else {
                        $get_p_dtl_query = "SELECT product_name,product_price,product_id,product_image,seller_id FROM product_storage WHERE product_id = '$ord_dtl[2]' ";
                        $p_send_query = mysqli_query($product_connection, $get_p_dtl_query);
                        while ($rows = mysqli_fetch_assoc($p_send_query)) {
                            $product_name = $rows['product_name'];
                            $product_price = preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $rows['product_price']);
                            $product_id = $rows['product_id'];
                            $product_image = explode(' ', $rows['product_image']);
                            $seller_id = $rows['seller_id'];
                            $client_present_order = "SELECT present_orders FROM client_storage WHERE client_id = $seller_id";
                            $client_storage_sql = mysqli_query($client_connection, $client_present_order);
                            while ($a = mysqli_fetch_assoc($client_storage_sql)) {
                                $present_order_str = $a['present_orders'];
                            };
                            echo "
<div class='main-overview-container'>
<div class='overview_container'>
<a href='./product.php?i=$product_id' class='overview-img_container'>
<img class='overview-img' src='../uploaded_files/$product_image[0]' loading='lazy'>
</a>
<div class='overview-details-list '>
<a href= './product.php?i=$product_id' class='overview-detail name'>$product_name</a>
<a href= './product.php?i=$product_id' class='overview-detail price'>Rs.$product_price</a>";
                            if (isset($ord_dtl[3])) {
                                if ($ord_dtl[3] === 'R') {
                                    echo "<a href= './product.php?i=$product_id' class='overview-detail'>Will be Recived soon</a>";
                                } elseif ($ord_dtl[3] === 'C') {
                                    echo "<a href= './product.php?i=$product_id' class='overview-detail'>Its just gone Out of Stoke...Order unable to fulfill</a>";
                                };
                            };
                            echo "<div class='btn-container'>";
                            if (isset($_GET['my_orders'])) {
                                echo "<span class='btn-1' id='cancel-order' data-cancel_my_order_str='$seller_id:$_SESSION[user_id]:$ord_dtl[2]:$ord_dtl[3]'>CANCEL</span>";
                            } elseif (isset($_GET['order_history']) && !isset($ord_dtl[3]) || $ord_dtl[3] !== 'C') {
                                echo "<span class='btn-2' id='return-order' data-return_my_order_str='$seller_id:$_SESSION[user_id]:$ord_dtl[2]'>RETURN</span>";
                            };
                            echo "</div></div></div></div>";
                        };
                    };
                };
            };
        };
    };
};
?>
