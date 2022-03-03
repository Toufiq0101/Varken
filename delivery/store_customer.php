<?php
include "./database_connection.php";
session_start();
?>

<?php
if (isset($_POST['c_id']) && isset($_GET['deliver_ord'])) {
    $query = "SELECT pending_orders FROM client_storage WHERE client_id = $_POST[c_id]";
    $sql = mysqli_query($client_connection, $query);
    while ($rows = mysqli_fetch_assoc($sql)) {
        $pending_order = explode(';', $rows['pending_orders']);
        foreach ($pending_order as $orders_str) {
            $ord_dtl = explode(':', $orders_str);
            if ($ord_dtl[0]) {
                $user_query = "SELECT user_name,user_address,phone_number FROM user_storage WHERE user_id = $ord_dtl[1]";
                $user_sql = mysqli_query($user_connection, $user_query);
                $user_row = mysqli_fetch_assoc($user_sql);
                $user_name = $user_row['user_name'];
                $user_adrs = $user_row['user_address'];
                $user_ph_no = $user_row['phone_number'];

                echo "
<div id='delivery_order'>
<span>$user_name</span><br>
<span>Ph : $user_ph_no</span><br>
<span>Addr : $user_adrs</span><br>
<span>Total Bill : $ord_dtl[2]</span>
</div>";
                echo "
$orders_str;
<button data-ord_str='$_POST[c_id]:$orders_str' id='dlvr_pick_up'>PICK UP</button>
";
            };
        };
    };
};
?>

<?php
if (isset($_POST['c_id']) && isset($_GET['rtrn_rqst'])) {
    $query = "SELECT return_requests FROM client_storage WHERE client_id = $_POST[c_id]";
    $sql = mysqli_query($client_connection, $query);
    while ($rows = mysqli_fetch_assoc($sql)) {
        $pending_order = explode(';', $rows['return_requests']);
        foreach ($pending_order as $orders_str) {
            $ord_dtl = explode(':', $orders_str);
            if ($ord_dtl[0]) {
                $user_query = "SELECT * FROM user_storage WHERE user_id = $ord_dtl[1]";
                $user_sql = mysqli_query($user_connection, $user_query);
                $user_row = mysqli_fetch_assoc($user_sql);
                $user_name = $user_row['user_name'];
                $user_adrs = $user_row['user_address'];
                $user_ph_no = $user_row['phone_number'];

                $product_query = "SELECT * FROM product_storage WHERE product_id = '$ord_dtl[2]'";
                $product_row = mysqli_fetch_assoc(mysqli_query($product_connection, $product_query));
                $product_name = $product_row['product_name'];
                $product_img = explode(' ', $product_row['product_image']);
                $product_price = $product_row['product_price'];

                echo "
<div id='return_request'>
<span>$user_name</span><br>
<span>Ph : $user_ph_no</span><br>
<span>Addr : $user_adrs</span><br>
<span>Refund : As on Payment Bill</span>
<div><img width='100px' src='../uploaded_files/$product_img[0]'>
<span>$product_name</span></div>
</div>";
                echo "
$_POST[c_id]:$orders_str
<button data-ord_str='$_POST[c_id]:$orders_str' id='rtrn_pick_up'>PICKsss UP</button>
";
            };
        };
    };
};
?>