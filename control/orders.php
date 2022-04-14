<?php
include "../db_connection.php";
session_start();
?>

<?php
if (isset($_SESSION['user_id'])) {
    if (isset($_SESSION['user_lat']) && $_SESSION['user_lat'] !== '' && isset($_SESSION['user_lng']) && $_SESSION['user_lng'] !== '') {
        if (isset($_POST['order_str']) || isset($_GET['order_img'])) {
            if (isset($_GET['order_img'])) {
                $all_images = '';
                $user_ord_str = '';
                foreach ($_FILES['order_image']['name'] as $key => $value) {
                    $order_image = $_FILES['order_image']['name'][$key];
                    $order_image_tmp = $_FILES['order_image']['tmp_name'][$key];
                    $order_image_size = $_FILES['order_image']['size'][$key];
                    $order_image_error = $_FILES['order_image']['error'][$key];
                    $order_image_type = $_FILES['order_image']['type'][$key];
                    $imageExt = explode('.', $order_image);
                    $imageActExt = strtolower(end($imageExt));
                    $image_ext_allowed = array('jpg', 'jpeg', 'png');
                    if (in_array($imageActExt, $image_ext_allowed)) {
                        if ($order_image_error === 0) {
                            if ($order_image_size < 10000000) {
                                $image_new_name = uniqid('', true) . rand(0, 9) . rand(0, 9) . "." . "$imageActExt";
                                $image_destination = "../uploaded_files/" . "$image_new_name";
                                if (move_uploaded_file($order_image_tmp, $image_destination)) {
                                    if ($key === 0) {
                                        $all_images = substr_replace($all_images, "$image_new_name", -1, 0);
                                    } else {
                                        $all_images = substr_replace($all_images, "$image_new_name,", 0, 0);
                                    };
                                    $user_ord_str .= "C:$_GET[c_id]:$image_new_name:O;";
                                };
                            } else {
                                echo 9;
                                die();
                            }
                        } else {
                            echo 9;
                            die();
                        }
                    } else {
                        echo 9;
                        die();
                    };
                };
                $p_p_id = $_GET['c_id'];
                $p_id = $all_images;
            } else {
                $order_str = explode(':', $_POST['order_str']);
                $p_p_id = $order_str[0];
                $p_id = $order_str[1];
            };
            $ord_var = 0;
            $u_id = $_SESSION['user_id'];
            $c_query = "SELECT seller_type,present_orders FROM client_storage WHERE client_id = '$p_p_id'";
            $u_query = "SELECT orders FROM user_storage WHERE user_id = '$u_id'";
            $p_color = isset($_POST['p_color']) ? $_POST['p_color'] : '';
            $p_size = isset($_POST['p_size']) ? $_POST['p_size'] : '';
            $p_msg = isset($_POST['p_msg']) ? $_POST['p_msg'] : '';
            $order_detail = "U:$u_id:$p_id=|$p_size|$p_color|$p_msg;";
            $send_c_query = mysqli_query($client_connection, $c_query);
            if ($send_c_query) {
                while ($rows = mysqli_fetch_assoc($send_c_query)) {
                    $present_orders = $rows['present_orders'];
                    $seller_type = $rows['seller_type'];
                    // bug
                    $order_check = strpos($present_orders, "U:$u_id");
                    if (!$order_check && $order_check !== 0) {
                        $present_orders .= "$order_detail";
                    } else {
                        $u_id_len = strlen("U:$u_id") + 1;
                        $order_item = "$p_id=|$p_size|$p_color|$p_msg,";
                        $pos = strpos($present_orders, "U:$u_id") + $u_id_len;
                        $present_orders = substr_replace($present_orders, $order_item, $pos, 0);
                    };
                    $upd_c_query = "UPDATE client_storage SET present_orders = '$present_orders' WHERE client_id = $p_p_id";
                    $send_upd_query = mysqli_query($client_connection, $upd_c_query);
                    if (!$send_upd_query) {
                        echo 0;
                        die();
                    } else {
                        $ord_var = 1;
                    };
                };
            };
            $u_send_upd_query = mysqli_query($user_connection, $u_query);
            if ($u_send_upd_query) {
                $row = mysqli_fetch_assoc($u_send_upd_query);
                $my_orders = $row['orders'];
                if (isset($order_str[2]) && $order_str[2] === 'S') {
                    $my_ord_str = (!strpos($my_orders, "C:$p_p_id:") && strpos($my_orders, "C:$p_p_id:") !== 0) ? "C:$p_p_id:$p_id:S;" : "";
                } else {
                    $my_ord_str = "C:$p_p_id:$p_id=|$p_size|$p_color|$p_msg:O;";
                };
                $my_orders .= (isset($_GET['order_img'])) ? "$user_ord_str" : "$my_ord_str";
                $upd_u_query = "UPDATE user_storage SET orders = '$my_orders' WHERE user_id=$u_id";
                $u_send_upd_query = mysqli_query($user_connection, $upd_u_query);
                if (!$u_send_upd_query) {
                    echo 0;
                    die();
                } elseif ($ord_var === 1) {
                    echo 1;
                };
            };
        };
    } else {
        echo "loc404";
    };
} else {
    echo 400;
};
?>