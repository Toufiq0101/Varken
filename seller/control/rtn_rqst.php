<?php include "../database_connection.php";
session_start();
if (isset($_SESSION['client_id'])) {
    echo "<div class='tab-heading'>Return Requests</div>";
    $c_id = $_SESSION['client_id'];
    if (isset($_POST['data_num'])) {
        $offset = $_POST['data_num'];
    } else {
        $offset = 0;
    };
    $c_query = "SELECT return_requests FROM client_storage WHERE client_id = $c_id ";
    $send_c_query = mysqli_query($client_connection, $c_query);
    while ($rows = mysqli_fetch_assoc($send_c_query)) {
        $rtn_rqst_str = $rows['return_requests'];
        if (!$rtn_rqst_str) {
            echo "<div class='no_content_err-container'><img src='./css/svg/error.svg' alt='' class='no_content_err-img'><span class='no_content_err-msg'>No Return Requests<span></div>";
            die();
        } else {
            $rtrn_dtl = explode(';', $rtn_rqst_str);
            foreach ($rtrn_dtl as $rtrn_full_dtl) {
                $p_d = explode(':', $rtrn_full_dtl);
                if ($p_d[0]) {
                    $u_id = $p_d[1];
                    $p_id_str = $p_d[2];
                    $u_query = "SELECT * FROM user_storage WHERE user_id = $u_id";
                    $send_u_query = mysqli_query($user_connection, $u_query);
                    while ($rows = mysqli_fetch_assoc($send_u_query)) {
                        $customer_name = $rows['user_name'];
                        $customer_address = $rows['user_address'];
                        $customer_ph_num = $rows['phone_number'];
                        echo "<div id='rtn_customer_detail-line' class='customer_detail' data-rtn_cust_dtl = 'U:$u_id:$p_id_str'><span class='customer_dtl name'>$customer_name</span><span class='customer_dtl address'><span>Add:- </span><span>$customer_address</span></span><span class='customer_dtl ph_no'>Ph: $customer_ph_num</span></div>";
                    };
                };
            };
        };
    };
} else {
    echo "err|Ş`(*⁂‖﹏⁂‖*)′Ş|err%^%401";
};
