<?php
include "./database_connection.php";
session_start();
?>

<?php
if(isset($_GET['register'])){
$user_name = $_POST['user_name'];
$phone_number = $_POST['phone_number'];
$password = $_POST['password'];
$user_query = "INSERT INTO data_enteric(user_name , phone_number , password ) VALUES ('$user_name' , '$phone_number' , '$password' ) ";
$user_sql = mysqli_query($unv_product_connection,$user_query);
if($user_sql){
echo 1;
}else{
echo 2;
}
}elseif(isset($_GET['login'])){
$login_ph_no = $_POST['phone_number'];
$login_password = $_POST['password'];
$user_query = "SELECT phone_number,enteric_id,password FROM data_enteric WHERE phone_number = $login_ph_no";
$user_sql = mysqli_query($unv_product_connection, $user_query);
$rows = mysqli_fetch_assoc($user_sql);
if(!$rows){
echo 0;
die();
}
$user_password= $rows['password'];
if ($user_password !== $login_password) {
echo 101;
die();
}
$_SESSION['enteric_ph_no'] = $rows['phone_number'];
$_SESSION['enteric_id'] = $rows['enteric_id'];
if (isset($_SESSION['enteric_id'])) {
echo 1;
}else{
echo 0;
}
}
?>