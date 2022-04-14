<?php
include "../db_connection.php";
session_start();

if(isset($_POST['rating'])){
    $rating = $_POST['rating'];
    $p_id = $_POST['p_id'];
    $prd_str = $_POST['prd_str'];
    $prd_data = mysqli_fetch_assoc(mysqli_query($product_connection,"SELECT new_ratings , all_ratings FROM product_storage WHERE product_id = '$p_id'"));
    $usr_data = mysqli_fetch_assoc(mysqli_query($user_connection,"SELECT order_history FROM user_storage WHERE user_id = '$_SESSION[user_id]'"));
    $order_history = $usr_data['order_history'];
    $new_order_history = substr_replace($order_history,'',strpos($order_history,"$prd_str;"),strlen("$prd_str;"));
    $new_rating = $prd_data['new_ratings'];
    $all_ratings  = $prd_data['all_ratings'];
    $upd_new_ratings = "$rating;".$new_rating;
    $new_ratings_arr = explode(';',$upd_new_ratings);
    if (count($new_ratings_arr)>50) {
        $new_ratings_arr = array_pop($new_ratings_arr);
        $new_rating = implode("",array($new_ratings_arr));
    }
    $upd_all_ratings = "$_SESSION[user_id]:$rating;".$all_ratings;
    mysqli_query($product_connection,"UPDATE product_storage SET new_ratings = '$upd_new_ratings' , all_ratings = '$upd_all_ratings' WHERE product_id = '$p_id'");
    mysqli_query($user_connection,"UPDATE user_storage SET order_history = '$new_order_history' WHERE user_id = '$_SESSION[user_id]'");
};