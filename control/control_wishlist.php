<?php include "../db_connection.php";
session_start();
?>

<?php
if (isset($_SESSION['user_id'])) {
    $get_query = "SELECT wishlist FROM user_storage WHERE user_id = $_SESSION[user_id]";
    $get_sql = mysqli_query($user_connection, $get_query);
    while ($row = mysqli_fetch_assoc($get_sql)) {
        $wishlist_str = $row['wishlist'];
        if (isset($_POST['p_id_check']) && isset($_GET['check'])) {
            $p_id_check = $_POST['p_id_check'];
            if (strpos($wishlist_str, $p_id_check) || strpos($wishlist_str, $p_id_check) === 0) {
                echo 0;
            } else {
                echo 1;
            };
        } elseif (isset($_POST['p_id']) && (isset($_GET['delete']) || isset($_GET['action']))) {
            $p_id = $_POST['p_id'];
            $p_id_pos = strpos($wishlist_str, $p_id);
            if (!$p_id_pos && $p_id_pos !== 0) {
                $wishlist_str .= "$p_id,";
                $upd_query = "UPDATE user_storage SET wishlist='$wishlist_str' WHERE user_id = $_SESSION[user_id]";
                $upd_sql = mysqli_query($user_connection, $upd_query);
                echo 1;
            } elseif (isset($_GET['delete']) || $p_id_pos || $p_id_pos === 0) {
                $p_len = strpos($wishlist_str, $p_id);
                $wishlist_str = substr_replace($wishlist_str, '', $p_len, strlen($p_id) + 1);
                $upd_query = "UPDATE user_storage SET wishlist='$wishlist_str' WHERE user_id = $_SESSION[user_id]";
                $upd_sql = mysqli_query($user_connection, $upd_query);
                echo 2;
            };
        };
    };
} else {
    echo "<div class='unlogged'>
You should login first..!!
</div>";
};
?>
