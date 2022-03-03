<?php

require "./db_connection.php";


// $send_product_query = "INSERT INTO product_storage(product_name, product_price ,product_category, product_description,product_size,product_color, product_availability, product_image, product_date, seller_id, store_name, seller_ph_num,store_image, store_location, _geoloc) ";
// $send_product_query .= "VALUES('product_name' , 'product_price' ,'product_category', 'product_description','product_size' , 'product_color_str' , 'product_availability', 'all_images',now(), 'seller_id' , 'store_name','seller_ph_num','store_image','product_store_location' ,'{ \"lat\" : 23.38243652052875 , \"lng\" :  85.33495062571942 }' ) ";
// $send_product = mysqli_query($product_connection, $send_product_query) && mysqli_insert_id($product_connection);
// $link = mysqli_connect('127.0.0.1', 'my_user', 'my_pass', 'my_db');
// mysqli_query($link, "INSERT INTO mytable (1, 2, 3, 'blah')");

$cart_str = "C:24:56=5|2|3|#f00|spcf_msg;C:34:66=6|2|3|#f00|spcf_msg;C:14:46=3|12|3|#f00|spcf_msg;";
// $cart_str = "C:34:350=0|||;";
$cart_1 = strpos("$cart_str", "C:34:66=rgrgrg");
// $cart_2 = substr("$cart_str", $cart_1);
// $cart_3 = strpos($cart_2, "|") - (strpos($cart_2, '=') + 1);
// $cart_4 = substr($cart_2, strpos($cart_2, '=') + 1, $cart_3);
// $new_qan = $cart_4-1;
// $d = substr_replace($cart_str,$new_qan,$cart_1+strlen("C:14:46="),$cart_3);
// $d = substr_replace($cart_str, '', $cart_1, $cart_1 + strpos($cart_2, ';') + 1);
echo $cart_1. "----";