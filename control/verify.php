<?php

include "../db_connection.php";
session_start();

if (isset($_GET['verify'])) {
if (isset($_SESSION['user_id'])&&isset($_SESSION['user_ph_num']) && $_SESSION['user_ph_num']!=='') {
if (!isset($_COOKIE['u_authentication'])) {
    $auth_no = md5($_SESSION['user_id']);
setcookie('u_authentication', $auth_no, time()+60*60*24*30*2,'/');
echo 1;
if ($_COOKIE['u_authentication']) {echo 6;};
}else{
    echo 1;
}
}elseif (isset($_COOKIE['u_authentication'])) {
$auth_no = $_COOKIE['u_authentication'];
$login_query ="SELECT user_id,phone_number,latitude,longitude,user_name,user_address FROM user_storage WHERE user_auth_no = '$auth_no'";
// }else{
//     $login_query ="SELECT user_id,phone_number,latitude,longitude,user_name,user_address FROM user_storage WHERE user_id = '$_SESSION[user_id]'";
// }
$query_result = mysqli_query($user_connection, $login_query);
if (!$query_result) {
setcookie("u_authentication", "", time() - 3600, '/');
echo 0;
die();
};
while ($rows = mysqli_fetch_assoc($query_result)) {
$_SESSION['user_id'] = $rows['user_id'];
$_SESSION['user_ph_num']= $rows['phone_number'];
if ($rows['latitude']!=='' ||$rows['longitude']!=='') {
$_SESSION['user_lat'] = $rows['latitude'];
$_SESSION['user_lng'] = $rows['longitude'];
} else {
$_SESSION['user_lat'] =25.0960742;
$_SESSION['user_lng'] = 85.31311939999999;
}
$_SESSION['user_address'] = $rows['user_address'];
$_SESSION['user_name'] = $rows['user_name'];
$auth_no = md5($rows['user_id']);
setcookie('u_authentication', $auth_no, time()+60*60*24*30*2,'/');
echo 1;
};
};};
?>