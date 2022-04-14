<?php
include "../db_connection.php";
session_start();

$u_id = $_SESSION['user_id'];
if (isset($_POST['cart_str'])) {
    $cart_dtl = explode(':', $_POST['cart_str']);
    $c_id = $cart_dtl[0];
    $p_id = $cart_dtl[1];

    $get_query = "SELECT my_cart FROM user_storage WHERE user_id = $u_id";
    $send_get_query = mysqli_query($user_connection, $get_query);
    while ($rows = mysqli_fetch_assoc($send_get_query)) {
        $cart = $rows['my_cart'];
        if (isset($_GET['delete'])) {
            if (str_contains("$cart", "C:$c_id:$p_id=")) {
                $cart_1 = strpos("$cart", "C:$c_id:$p_id=");
                $cart_2 = substr("$cart", $cart_1);
                $cart_3 = strpos($cart_2, "|") - (strpos($cart_2, '=') + 1);
                $cart_4 = substr($cart_2, strpos($cart_2, '=') + 1, $cart_3);
                if ($cart_4 > 1) {
                    $new_qan = $cart_4 - 1;
                    $cart = substr_replace($cart, $new_qan, $cart_1 + strlen("C:$c_id:$p_id="), $cart_3);
                } else {
                    $new_qan = 0;
                    $cart = substr_replace($cart, '', $cart_1, $cart_1 + strpos($cart_2, ';') + 1);
                }
            }
        };
        $upd_query = "UPDATE user_storage SET my_cart = '$cart' WHERE user_id = $u_id";
        $send_upd_query = mysqli_query($user_connection, $upd_query);
        if ($send_upd_query&&isset($new_qan)) {
            echo "qnt:$new_qan";
        } else {
            echo 0;
        }
    }
};
