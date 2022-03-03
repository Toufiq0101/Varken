<?php
include "./database_connection.php";
session_start();
?>

<?php
if (isset($_POST['ord_str']) && isset($_GET['dlvr_pick_up'])) {
    $ord_str = explode(':', $_POST['ord_str']);
    $row = mysqli_fetch_assoc(mysqli_query($client_connection, "SELECT pending_orders , on_the_way FROM client_storage WHERE client_id ='$ord_str[0]'"));
    $pending_orders = $row['pending_orders'];
    $on_the_way = $row['on_the_way'];
    $a = "$ord_str[1]:$ord_str[2]:$ord_str[3]:$ord_str[4]:$ord_str[5]";
    $ord_pos = strpos($pending_orders, $a);
    $pending_orders = substr_replace($pending_orders, '', $ord_pos, strlen($a) + 1);
    $on_the_way .= "$a:$_SESSION[courier_id];";
    $new_client_query = "UPDATE client_storage SET pending_orders = '$pending_orders', on_the_way='$on_the_way' WHERE client_id = $ord_str[0]";
    mysqli_query($client_connection, $new_client_query);
    if ($new_client_query) {
        $courier_row = mysqli_fetch_assoc(mysqli_query($user_connection, "SELECT courier_bag FROM courier WHERE courier_id = '$_SESSION[courier_id]'"));
        $courier_bag = $courier_row['courier_bag'];
        $courier_bag .= "$ord_str[0]:$a;";
        mysqli_query($user_connection, "UPDATE courier SET courier_bag = '$courier_bag' WHERE courier_id = '$_SESSION[courier_id]'");
    };
};

if (isset($_POST['ord_str']) && isset($_GET['rtrn_pick_up'])) {
    $ord_str = explode(':', $_POST['ord_str']);
    $row = mysqli_fetch_assoc(mysqli_query($client_connection, "SELECT return_requests , on_the_way FROM client_storage WHERE client_id ='$ord_str[0]'"));
    $return_requests = $row['return_requests'];
    $on_the_way = $row['on_the_way'];
    $a = "$ord_str[1]:$ord_str[2]:$ord_str[3]";
    $ord_pos = strpos($return_requests, $a);
    $return_requests = substr_replace($return_requests, '', $ord_pos, strlen($a) + 1);
    $on_the_way .= "$a::R:$_SESSION[courier_id];";
    $new_client_query = "UPDATE client_storage SET return_requests = '$return_requests', on_the_way='$on_the_way' WHERE client_id = $ord_str[0]";
    mysqli_query($client_connection, $new_client_query);
    if ($new_client_query) {
        $user_row = mysqli_fetch_assoc(mysqli_query($user_connection, "SELECT orders FROM user_storage WHERE user_id = '$ord_str[2]'"));
        $user_orders = substr_replace($user_row['orders'], '', strpos($user_row['orders'], "C:$ord_str[0]:$ord_str[3]:R;"), strlen("C:$ord_str[0]:$ord_str[3]:R;"));
        mysqli_query($user_connection, "UPDATE user_storage SET orders='$user_orders' WHERE user_id = '$ord_str[2]'");
        $courier_row = mysqli_fetch_assoc(mysqli_query($user_connection, "SELECT courier_bag FROM courier WHERE courier_id = '$_SESSION[courier_id]'"));
        $courier_bag = $courier_row['courier_bag'];
        $courier_bag .= "$ord_str[0]:$a:R;";
        mysqli_query($user_connection, "UPDATE courier SET courier_bag = '$courier_bag' WHERE courier_id = '$_SESSION[courier_id]'");
    };
};

if (isset($_POST['ord_str']) && isset($_GET['delivered'])) {
    $ord_str = explode(':', $_POST['ord_str']);
    $row = mysqli_fetch_assoc(mysqli_query($client_connection, "SELECT on_the_way , net_sales FROM client_storage WHERE client_id ='$ord_str[0]'"));
    $net_sales =intval($row['net_sales'])+1;
    $a = "$ord_str[1]:$ord_str[2]:$ord_str[3]:$ord_str[4]:$ord_str[5]:$_SESSION[courier_id];";
    $on_the_way = substr_replace($row['on_the_way'], '', strpos($row['on_the_way'], "$a"), strlen($a));
    $new_client_query = "UPDATE client_storage SET on_the_way='$on_the_way', net_sales = '$net_sales' WHERE client_id = '$ord_str[0]'";
    mysqli_query($client_connection, $new_client_query);
    if ($new_client_query) {
        $courier_row = mysqli_fetch_assoc(mysqli_query($user_connection, "SELECT courier_bag , deliveries FROM courier WHERE courier_id = '$_SESSION[courier_id]'"));
        $courier_bag = substr_replace($courier_row['courier_bag'], '', strpos($courier_row['courier_bag'], "$_POST[ord_str]"), strlen($_POST['ord_str']) + 1);
        $deliveries =intval($courier_row['deliveries'])+1;
        mysqli_query($user_connection, "UPDATE courier SET courier_bag = '$courier_bag' , deliveries = '$deliveries' WHERE courier_id = '$_SESSION[courier_id]'");
        mysqli_query($product_connection, "UPDATE product_storage SET net_sales = 123 WHERE product_id = '$ord_str[3]'");
    }else{
        die();
    };
};

if (isset($_POST['ord_str']) && isset($_GET['returned'])) {
    $ord_str = explode(':', $_POST['ord_str']);
    $row = mysqli_fetch_assoc(mysqli_query($client_connection, "SELECT on_the_way FROM client_storage WHERE client_id ='$ord_str[0]'"));
    $on_the_way = $row['on_the_way'];
    $a = "$ord_str[1]:$ord_str[2]:$ord_str[3]:$ord_str[4]:$ord_str[5];";
    $ord_pos = strpos($on_the_way, $a);
    $on_the_way = substr_replace($on_the_way, '', $ord_pos, strlen($a));
    $new_client_query = "UPDATE client_storage SET on_the_way='$on_the_way' WHERE client_id = $ord_str[0]";
    mysqli_query($client_connection, $new_client_query);
    if ($new_client_query) {
        $courier_row = mysqli_fetch_assoc(mysqli_query($user_connection, "SELECT courier_bag FROM courier WHERE courier_id = '$_SESSION[courier_id]'"));
        $courier_bag = $courier_row['courier_bag'];
        $courier_bag = substr_replace($courier_row['courier_bag'], '', strpos($courier_row['courier_bag'], "$_POST[ord_str]"), strlen($_POST['ord_str']) + 1);
        mysqli_query($user_connection, "UPDATE courier SET courier_bag = '$courier_bag' WHERE courier_id = '$_SESSION[courier_id]'");
    }
}

?>