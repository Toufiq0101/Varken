<?php
include "../db_connection.php";
session_start();
if (isset($_GET['login'])) {
$input_phone_number = $_POST['phone_number'];
$input_password = $_POST['password'];
$search_ph_num = "SELECT * FROM user_storage WHERE phone_number = $input_phone_number";
$query =mysqli_query($user_connection, $search_ph_num);
if ($query -> num_rows === 0) {
echo 0;
die();
}
while ($rows = mysqli_fetch_assoc($query)) {
if ($rows['password'] !== $input_password ) {
echo 0;
die();
}
$_SESSION['user_ph_num']= $rows['phone_number'];
$_SESSION['user_address'] = $rows['user_address'];
$_SESSION['user_lat'] = $rows['latitude'];
$_SESSION['user_lng'] = $rows['longitude'];
$_SESSION['user_id'] = $rows['user_id'];
$_SESSION['user_name'] = $rows['user_name'];
if (isset($rows['user_id'])) {
$auth_no = md5($rows['user_id']);
if(isset($_COOKIE['u_authentication'])&& $_COOKIE['u_authentication'] !== $auth_no){
setcookie('u_authentication', "$auth_no", time()+60*60*24*30*2,'/');
mysqli_query($user_connection,"UPDATE user_storage SET user_auth_no = '$auth_no' WHERE user_id=$rows[user_id]");
}elseif(!isset($_COOKIE['u_authentication'])){
setcookie('u_authentication', "$auth_no", time()+60*60*24*30*2,'/');
mysqli_query($user_connection,"UPDATE user_storage SET user_auth_no = '$auth_no' WHERE user_id=$rows[user_id]");
};
echo 1;
};
};
};
?>