<?php
include "../db_connection.php";
session_start();
if(isset($_SESSION['user_id'])&&isset($_GET['return'])){
$rtn_str = explode(':', $_POST['return_order_str']);
$client_query = "SELECT return_requests FROM client_storage WHERE client_id = $rtn_str[0] ";
$user_query = "SELECT orders,order_history FROM user_storage WHERE user_id = '$rtn_str[1]'";
$client_sql = mysqli_query($client_connection, $client_query);
$user_sql = mysqli_query($user_connection, $user_query);
$row = mysqli_fetch_assoc($client_sql);
$u_row = mysqli_fetch_assoc($user_sql);
$return_requests = $row['return_requests'];
$return_requests .= "U:$rtn_str[1]:$rtn_str[2];";
$upd_client_query = "UPDATE client_storage SET return_requests ='$return_requests' WHERE client_id = '$rtn_str[0]' ";
$upd_client_sql = mysqli_query($client_connection, $upd_client_query);
if ($upd_client_sql) {
$order_history = $u_row['order_history'];
$orders = $u_row['orders'];
$rtn_str_pos = strpos($order_history, "C:$rtn_str[0]:$rtn_str[2];");
$rtn_str_len = strlen("C:$rtn_str[0]:$rtn_str[2];");
$new_order_history = substr_replace($order_history, '', $rtn_str_pos, $rtn_str_len);
$orders .= "C:$rtn_str[0]:$rtn_str[2]:R;";
$new_user_query = "UPDATE user_storage SET orders = '$orders' , order_history = '$new_order_history' WHERE user_id = '$_SESSION[user_id]'";
if (mysqli_query($user_connection, $new_user_query)) {
echo 1;
} else {
echo 0;
};
}
}
?>