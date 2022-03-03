<?php
include "./database_connection.php";
session_start();
echo "$_SESSION[courier_id]//////";
$courier_bag = "SELECT courier_bag FROM courier WHERE $_SESSION[courier_id]";
$courier_rows = mysqli_fetch_assoc(mysqli_query($user_connection, $courier_bag));
$bag_item =explode(';', $courier_rows['courier_bag']);
foreach ($bag_item as $order_dtl) {
if ($order_dtl) {
$a = explode(':', $order_dtl);
$user_query = "SELECT user_name,phone_number,user_address,phone_number FROM user_storage WHERE user_id = $a[2]";
$user_rows = mysqli_fetch_assoc(mysqli_query($user_connection, $user_query));
$user_name = $user_rows['user_name'];
$user_ph_no = $user_rows['phone_number'];
$user_address = $user_rows['user_address'];
if (strpos($order_dtl, 'PO')) {
echo "
<div class='order_delivery'>
<span>$user_name</span><br>
<span>Ph : $user_ph_no</span><br>
<span>Add : $user_address</span><br>
<span>Bill : $a[3]</span>
<button id='order_delivered' data-ord_str='$order_dtl:$_SESSION[courier_id]' >Delivered</button>
</div>
";
};
if (strpos($order_dtl, 'R')) {
echo "
<div class='order_rtrn'>
<span>$user_name</span><br>
<span>Ph : $user_ph_no</span><br>
<span>Add : $user_address</span><br>
<span>Bill : As on Bill</span>
";
$product_query = "SELECT * FROM product_storage WHERE product_id = '$a[3]'";
$product_row = mysqli_fetch_assoc(mysqli_query($product_connection,$product_query));
$product_name = $product_row['product_name'];
$product_img = explode(' ',$product_row['product_image']);
$product_price = $product_row['product_price'];
echo "
<div><img width='100px' src='../uploaded_files/$product_img[0]'>
<span>$product_name</span></div>
<button id='order_rtrn' data-ord_str='$order_dtl:$_SESSION[courier_id]' >RETURNED</button>
</div>
";
}
}
};
?>