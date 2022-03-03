<?php
session_start();
if(isset($_SESSION['user_id'])&&isset($_GET['logout'])){
unset($_SESSION['user_id']);
unset($_SESSION['user_ph_num']);
unset($_SESSION['user_address']);
unset($_SESSION['user_lat']);
unset($_SESSION['user_lng']);
unset($_SESSION['user_name']);
setcookie("u_authentication", "", time() - 3600, '/');
echo 1;
}
?>