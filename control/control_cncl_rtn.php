<?php
include "../db_connection.php";
session_start();
?>

<?php
if (isset($_SESSION['user_id'])) {
    if (isset($_GET['cancel']) && isset($_POST['cancel_order_str'])) {
        $cncl_str = explode(':', $_POST['cancel_order_str']);
        $client_query = "SELECT seller_type,present_orders,return_requests,completed_orders FROM client_storage WHERE client_id = '$cncl_str[0]' ";
        $user_query = "SELECT orders,order_history FROM user_storage WHERE user_id = '$_SESSION[user_id]' ";
        $client_sql = mysqli_query($client_connection, $client_query);
        while ($rows = mysqli_fetch_assoc($client_sql)) {
            $seller_type = $rows['seller_type'];
            if ($cncl_str[3] === 'R') {
                $order_str = $rows['return_requests'];
            } else {
                $order_str = $rows['present_orders'];
            };
            $new_order_str = '';
            if ($cncl_str[3] === 'R') {
                $new_order_str .= substr_replace($order_str, '', strpos($order_str, "U:$cncl_str[1]:$cncl_str[2];"), strlen("U:$cncl_str[1]:$cncl_str[2];"));
            } else {
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
                                }
                                $new_order_str .= "U:$cncl_str[1]:$c;";
                            };
                            if (isset($cncl_str[4]) && $cncl_str[4] === 'D') {
                                $completed_orders = $rows['completed_orders'] . "U:$ord_str[1]:$ord_str[2];";
                            };
                        } else {
                            $new_order_str .= "$value;";
                        };
                    };
                };
            };
            if ($cncl_str[3] === 'R') {
                $upd_client_query = "UPDATE client_storage SET return_requests='$new_order_str' WHERE client_id = '$cncl_str[0]' ";
            } else {
                $upd_client_query = "UPDATE client_storage SET present_orders='$new_order_str' ";
                $upd_client_query .= (isset($cncl_str[4]) && $cncl_str[4] === 'D') ? ",completed_orders='$completed_orders'" : "";
                $upd_client_query .= " WHERE client_id = '$cncl_str[0]' ";
            };
            $upd_client_sql = mysqli_query($client_connection, $upd_client_query);
            if ($upd_client_sql) {
                $user_sql = mysqli_query($user_connection, $user_query);
                $g = mysqli_fetch_assoc($user_sql);
                $user_orders = $g['orders'];
                $user_hstry_orders = $g['order_history'];
                if ($cncl_str[3] === 'R') {
                    $user_new_orders = substr_replace($user_orders, '', strpos($user_orders, "C:$cncl_str[0]:$cncl_str[2]:R"), strlen("C:$cncl_str[0]:$cncl_str[2]:O;"));
                    $ord_his = substr_replace($user_hstry_orders, "C:$cncl_str[0]:$cncl_str[2];", 0, 0);
                    $user_new_query = "UPDATE user_storage SET orders='$user_new_orders' , order_history='$ord_his' WHERE user_id = '$cncl_str[1]' ";
                } else {
                    $user_new_orders = substr_replace($user_orders, '', strpos($user_orders, "C:$cncl_str[0]:$cncl_str[2]:"), strlen("C:$cncl_str[0]:$cncl_str[2]:O;"));
                    $user_new_query = "UPDATE user_storage SET orders='$user_new_orders' WHERE user_id = '$cncl_str[1]' ";
                };
                $user_new_sql = mysqli_query($user_connection, $user_new_query);
                if ($user_new_sql) {
                    echo 1;
                };
            } else {
                echo 0;
            };
        };
    };
}
?>