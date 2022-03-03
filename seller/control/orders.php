<?php include "../database_connection.php";
session_start();
if (isset($_SESSION['client_id'])) {
    $c_id = $_SESSION['client_id'];
    $c_query = "SELECT present_orders,pending_orders FROM client_storage WHERE client_id = $c_id";
    $send_c_query = mysqli_query($client_connection, $c_query);
    while ($rows = mysqli_fetch_assoc($send_c_query)) {
        $all_order_str = $rows['present_orders'];
        $all_pending_order_str = $rows['pending_orders'];
        if (isset($_GET['send_order'])) {
            $parcel_str = explode(':', $_POST['parcel_str']);
            $a = "$parcel_str[0]:$parcel_str[1]:$parcel_str[2]";
            $parcel_str_pos = strpos($all_order_str, $a);
            $prcl_str_len = strlen("$a");
            $all_order_str = substr_replace($all_order_str, '', $parcel_str_pos, $prcl_str_len + 1);
            $all_pending_order_str .= "$parcel_str[0]:$parcel_str[1]:$parcel_str[2]:$parcel_str[3]:PO;";
            $upd_orders_query = "UPDATE client_storage SET present_orders='$all_order_str' , pending_orders='$all_pending_order_str' WHERE client_id = $c_id";
            $upd_orders_sql = mysqli_query($client_connection, $upd_orders_query);
            if ($upd_orders_sql) {
                $user_query = "SELECT orders,order_history FROM user_storage WHERE user_id = $parcel_str[1]";
                foreach (explode(',', $parcel_str[2]) as $p_id) {
                    $user_sql = mysqli_query($user_connection, $user_query);
                    $user_rows = mysqli_fetch_assoc($user_sql);
                    $user_orders = $user_rows['orders'];
                    $user_his = $user_rows['order_history'];
                    $ord_str = "C:$c_id:$p_id";
                    $new_u_ord = substr_replace($user_orders, '', strpos($user_orders, "$ord_str:O"), strlen("$ord_str:O;"));
                    $new_ord_his = substr_replace($user_his, "$ord_str;", 0, 0);
                    $new_user_query = "UPDATE user_storage SET orders = '$new_u_ord', order_history='$new_ord_his' WHERE user_id = $parcel_str[1] ";
                    $new_user_sql = mysqli_query($user_connection, $new_user_query);
                };
            };
        } elseif (isset($_GET['cncl_cstm_ord'])) {
            $cncl_str = explode(':', $_POST['cncl_cstm_ord']);
            $client_query = "SELECT seller_type,present_orders,return_requests,completed_orders FROM client_storage WHERE client_id = '$_SESSION[client_id]' ";
            $user_query = "SELECT orders,order_history FROM user_storage WHERE user_id = '$cncl_str[1]' ";
            $client_sql = mysqli_query($client_connection, $client_query);
            while ($rows = mysqli_fetch_assoc($client_sql)) {
                $seller_type = $rows['seller_type'];
                $order_str = $rows['present_orders'];
                $new_order_str = '';
                foreach (explode(';', $order_str) as $value) {
                    if ($value) {
                        $ord_str = explode(':', $value);
                        if ("U:$ord_str[1]:" === "U:$cncl_str[1]:") {
                            if (strpos($ord_str[2], ',') && $seller_type !== "Services") {
                                if (strpos($ord_str[2], "$cncl_str[2]") === 0) {
                                    $b = strpos($ord_str[2], "$cncl_str[2],");
                                    $c = substr_replace($ord_str[2], '', $b, strlen($cncl_str[2]) + 1);
                                } else {
                                    $b = strpos($ord_str[2], ",$cncl_str[2]");
                                    $c = substr_replace($ord_str[2], '', $b, strlen($cncl_str[2]) + 1);
                                };
                                $new_order_str .= "U:$cncl_str[1]:$c;";
                            };
                        } else {
                            $new_order_str .= "$value;";
                        };
                    };
                };
                $upd_client_query = "UPDATE client_storage SET present_orders='$new_order_str'";
                $upd_client_query .= (isset($cncl_str[4]) && $cncl_str[4] === 'D') ? ",completed_orders='$completed_orders'" : "";
                $upd_client_query .= " WHERE client_id = '$cncl_str[0]' ";
                $upd_client_sql = mysqli_query($client_connection, $upd_client_query);
                if ($upd_client_sql) {
                    $user_sql = mysqli_query($user_connection, $user_query);
                    $g = mysqli_fetch_assoc($user_sql);
                    $user_orders = $g['orders'];
                    $user_hstry_orders = "$g[order_history]";
                    $user_hstry_orders .= (isset($cncl_str[3]) && $cncl_str[3] === 'S') ? "C:$cncl_str[0]:SW;" : "C:$cncl_str[0]:$cncl_str[2]:C;";
                    $user_new_orders = '';
                    if (isset($cncl_str[3]) && $cncl_str[3] === 'S') {
                        echo strpos("abc", 'a');
                        foreach (explode(';', $user_orders) as $value) {
                            if ($value !== '') {
                                echo strpos($value, "C:$cncl_str[0]:$cncl_str[2]:");
                                $user_new_orders .= (strpos($value, "C:$cncl_str[0]:") || strpos($value, "C:$cncl_str[0]") === 0) ? '' : "$value;";
                            }
                        };
                    } else {
                        $user_new_orders = substr_replace($user_orders, '', strpos($user_orders, "C:$cncl_str[0]:$cncl_str[2]:"), strlen("C:$cncl_str[0]:$cncl_str[2]:O;"));
                    };
                    $user_new_query = "UPDATE user_storage SET orders='$user_new_orders',order_history='$user_hstry_orders' WHERE user_id = '$cncl_str[1]' ";
                    $user_new_sql = mysqli_query($user_connection, $user_new_query);
                    if ($user_new_sql) {
                        echo 1;
                    }
                } else {
                    echo 0;
                }
            };
        } else {
            echo "<div class='tab-heading'>Orders</div>";
            if ($all_order_str === '') {
                echo "<div class='no_content_err-container'><img src='./css/svg/error.svg' alt='' class='no_content_err-img'><span class='no_content_err-msg'>No Orders Yet<span></div>";
                die();
            } else {
                $order_dtl = explode(';', $all_order_str);
                foreach ($order_dtl as $ordr_full_dtl) {
                    $p_d = explode(':', $ordr_full_dtl);
                    if ($p_d[0]) {
                        $u_id = $p_d[1];
                        $p_id_str = $p_d[2];
                        $u_query = "SELECT user_name,user_address,phone_number FROM user_storage WHERE user_id = $u_id";
                        $send_u_query = mysqli_query($user_connection, $u_query);
                        while ($rows = mysqli_fetch_assoc($send_u_query)) {
                            $customer_name = $rows['user_name'];
                            $customer_address = $rows['user_address'];
                            $customer_ph_num = $rows['phone_number'];
                            echo "<div id='customer_detail-line' class='customer_detail' data-cust_dtl='U:$u_id:$p_id_str'><span class='customer_dtl name'>$customer_name</span><span class='customer_dtl address'><span>Add:- </span><span>$customer_address</span></span><span class='customer_dtl ph_no'>Ph: $customer_ph_num</span></div>";
                        };
                    };
                };
            };
        };
    };
} else {
    echo "err|Ş`(*⁂‖﹏⁂‖*)′Ş|err%^%401";
}
