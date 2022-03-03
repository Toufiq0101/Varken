<?php
$error_markup = "
<div class='no_content_err-container'>
<img src='./css/svg/error.svg' alt='' class='no_content_err-img'>
<span class='no_content_err-msg'>Server Connection Lost<br><span style='font-size:small'>Refresh the page or <a href='https://wa.me/qr/5DJPSM4CB47GN1' style='text-decoration: underline;'>Report Now</a></span><span>
</div>";
// $client_connection = mysqli_connect('localhost', 'halkaazn_root', '9wI!yA2c8#8@a9#u', 'halkaazn_client_storage','3306') or die("$error_markup");
// $product_connection = mysqli_connect('localhost', 'halkaazn_root', '9wI!yA2c8#8@a9#u', 'halkaazn_product_storage','3306') or die("$error_markup");
// $user_connection = mysqli_connect('localhost', 'halkaazn_root', '9wI!yA2c8#8@a9#u', 'halkaazn_user_storage','3306') or die("$error_markup");
$unv_product_connection = mysqli_connect('localhost', 'halkaazn_root','9wI!yA2c8#8@a9#u','halkaazn_unv_products','3306') or die($error_markup);
?>