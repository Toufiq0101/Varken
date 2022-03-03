<?php include "../database_connection.php";
session_start();
if (isset($_SESSION['client_id'])) {
    echo "<div class='tab-heading'>On The Way</div>";
    if (isset($_GET['courier'])) {
        if (isset($_POST['data_num'])) {
            $offset = $_POST['data_num'];
        } else {
            $offset = 0;
        }
        $sql = mysqli_query($client_connection, "SELECT on_the_way FROM client_storage WHERE client_id= $_SESSION[client_id] LIMIT $offset,1");
        $row = mysqli_fetch_assoc($sql);
        $on_the_way = $row['on_the_way'];
        if ($on_the_way === '') {
            echo "<div class='no_content_err-container'><img src='./css/svg/error.svg' alt='' class='no_content_err-img'><span class='no_content_err-msg'>No Orders is on the Way<span></div>";
            die();
        } else {
            $each_product_arr = explode(';', $on_the_way);
            foreach ($each_product_arr as $each_product) {
                $order_dtl = explode(':', $each_product);
                if (isset($order_dtl[1])) {
                    $user_query = "SELECT user_name,user_address,phone_number FROM user_storage WHERE user_id = '$order_dtl[1]' ";
                    $rows = mysqli_fetch_assoc(mysqli_query($user_connection, $user_query));
                    $customer_name = $rows['user_name'];
                    $customer_address = $rows['user_address'];
                    $customer_ph_num = $rows['phone_number'];
                    echo "<div class='customer_detail open_about_courier' data-courier_boy='$order_dtl[5]' id='on_the_way-courier_dtl'><span class='customer_dtl name'>$customer_name</span><span class='customer_dtl address'>Add: $customer_address</span><span class='customer_dtl ph_no'>Ph: $customer_ph_num</span></div>";
                };
            };
            echo "<div id='about-courier-container-modal'></div>";
        };
    };
} else {
    echo "err|Ş`(*⁂‖﹏⁂‖*)′Ş|err%^%401";
};
