<?php include "../database_connection.php";
session_start();
if (isset($_SESSION['client_id'])) {
    echo "<div class='tab-heading'>Pending Orders</div>";
    $c_id = $_SESSION['client_id'];
    if (isset($_POST['data_num'])) {
        $offset = $_POST['data_num'];
    } else {
        $offset = 0;
    };
    $c_query = "SELECT pending_orders FROM client_storage WHERE client_id = '$c_id'";
    $send_c_query = mysqli_query($client_connection, $c_query);
    while ($rows = mysqli_fetch_assoc($send_c_query)) {
        $all_pending_order_str = $rows['pending_orders'];
        if (!$all_pending_order_str) {
            echo "<div class='no_content_err-container'><img src='./css/svg/error.svg' alt='' class='no_content_err-img'><span class='no_content_err-msg'>No Pending Orders<span></div>";
            die();
        } else {
            $order_dtl = explode(';', $all_pending_order_str);
            foreach ($order_dtl as $ordr_full_dtl) {
                $p_d = explode(':', $ordr_full_dtl);
                if ($p_d[0]) {
                    $u_id = $p_d[1];
                    $u_query = "SELECT * FROM user_storage WHERE user_id = $u_id";
                    $send_u_query = mysqli_query($user_connection, $u_query);
                    while ($rows = mysqli_fetch_assoc($send_u_query)) {
                        $customer_name = $rows['user_name'];
                        $customer_address = $rows['user_address'];
                        $customer_ph_num = $rows['phone_number'];
                        echo "<div id='pending-orders customer_detail-pending' class='customer_detail'><span class='customer_dtl name'>$customer_name</span><span class='customer_dtl address'><span>Add:- </span><span>$customer_address</span></span><span class='customer_dtl ph_no'>Ph: $customer_ph_num</span><span class='customer_dtl price'>Total Bill : $p_d[3]</span></div>";
                    };
                };
            };
        };
    };
} else {
    echo "err|Ş`(*⁂‖﹏⁂‖*)′Ş|err%^%401";
}
