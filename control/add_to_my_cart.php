<?php
include "../db_connection.php";
session_start();

$u_id = $_SESSION['user_id'];
if (isset($_POST['cart_str'])) {
    $cart_dtl = explode(':', $_POST['cart_str']);
    $p_color = isset($_POST['p_color']) ? $_POST['p_color'] : '';
    $p_size = isset($_POST['p_size']) ? $_POST['p_size'] : '';
    $p_msg = isset($_POST['p_msg']) ? $_POST['p_msg'] : '';
    echo $p_msg;
    $c_id = $cart_dtl[0];
    $p_id = $cart_dtl[1];
    $get_query = "SELECT my_cart FROM user_storage WHERE user_id = $u_id";
    $send_get_query = mysqli_query($user_connection, $get_query);
    while ($rows = mysqli_fetch_assoc($send_get_query)) {
        $cart = $rows['my_cart'];
        $cart_1 = strpos("$cart", "C:$c_id:$p_id=");
        if (str_contains("$cart", "C:$c_id:$p_id=")) {
            $cart_2 = substr("$cart", $cart_1);
            $cart_3 = strpos($cart_2, "|") - (strpos($cart_2, '=') + 1);
            $cart_4 = substr($cart_2, strpos($cart_2, '=') + 1, $cart_3);
            $new_qan = $cart_4 + 1;
            $cart = substr_replace($cart, $new_qan, $cart_1 + strlen("C:$c_id:$p_id="), $cart_3);
        } else {
            $new_qan =1;
            $cart .= "C:$c_id:$p_id=1|$p_size|$p_color|$p_msg;";
        }
        $upd_query = "UPDATE user_storage SET my_cart = '$cart' WHERE user_id = $u_id";
        $send_upd_query = mysqli_query($user_connection, $upd_query);
        if ($send_upd_query) {
            echo "qnt:$new_qan";
        } else {
            echo 0;
        }
    }
}