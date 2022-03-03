<?php
$error_markup = "
<div class='no_content_err-container'>
<img src='./css/svg/error.svg' alt='' class='no_content_err-img'>
<span class='no_content_err-msg'>Server Connection Lost<br><span style='font-size:small'>Refresh the page or <a href='https://wa.me/qr/5DJPSM4CB47GN1' style='text-decoration: underline;'>Report Now</a></span><span>
</div>";
$client_connection = mysqli_connect('localhost', 'root', '', 'client_storage') or die("$error_markup");
$product_connection = mysqli_connect('localhost', 'root', '', 'product_storage') or die("$error_markup");
$user_connection = mysqli_connect('localhost', 'root', '', 'user_storage') or die("$error_markup");
$unv_product_connection = mysqli_connect('localhost', 'root','','unv_products') or die($error_markup);
?>