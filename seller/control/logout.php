<?php 
session_start();
echo $_SESION['client_id'];if(isset($_GET['logout'])&&isset($_SESSION['client_id'])){setcookie("c_authentication","",time()-3600,'/');setcookie("s_authentication","",time()-3600,'/');unset($_SESSION['client_id']);unset($_SESSION['phone_number']);unset($_SESSION['owner_name']);unset($_SESSION['store_name']);unset($_SESSION['store_location']);unset($_SESSION['seller_type']);}?>