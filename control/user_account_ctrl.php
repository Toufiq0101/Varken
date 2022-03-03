<?php
include "../db_connection.php";
session_start();
?>

<?php
if (isset($_GET['register']) || isset($_GET['edit'])) {
    $user_phone_number = $_POST['user_phone_number'];
    $user_row_test = mysqli_query($user_connection, "SELECT user_name FROM user_storage WHERE phone_number = '$user_phone_number'");
    if (!isset($_GET['edit']) && $user_row_test->num_rows !== 0) {
        echo 000;
        die();
    };
    $user_name = $_POST['user_name'];
    $user_password = $_POST['user_password'];
    $user_loc = explode(',', $_POST['user_coords']);
    $user_lat = $user_loc[0];
    $user_lng = $user_loc[1];
    $user_dist = $user_loc[2];
    $user_address = $_POST['user_address'];
    print_r($user_loc);
    if (isset($_GET['edit'])) {
        $send_user_query = "UPDATE user_storage SET user_name='$user_name', password='$user_password', phone_number='$user_phone_number'
 , latitude = '$user_lat',longitude = '$user_lng', user_address='$user_address' , loc_district = '$user_dist' WHERE user_id = '$_SESSION[user_id]'";
    } else {
        $send_user_query = "INSERT INTO user_storage(user_name, password , phone_number , latitude , longitude , user_address , loc_district) ";
        $send_user_query .= "VALUES('$user_name' , '$user_password' , '$user_phone_number', '$user_lat','$user_lng', '$user_address', '$user_dist' )";
    }
    if (mysqli_query($user_connection, $send_user_query)) {
        $user_row = mysqli_query($user_connection, "SELECT phone_number,user_address,latitude,longitude,user_id,user_name,loc_district FROM user_storage WHERE phone_number = '$user_phone_number'");
        while ($rows = mysqli_fetch_assoc($user_row)) {
            $_SESSION['user_ph_num'] = $rows['phone_number'];
            $_SESSION['user_address'] = $rows['user_address'];
            $_SESSION['user_lat'] = $rows['latitude'];
            $_SESSION['user_lng'] = $rows['longitude'];
            $_SESSION['user_id'] = $rows['user_id'];
            $_SESSION['user_name'] = $rows['user_name'];
            $_SESSION['loc_district'] = $rows['loc_district'];
            setcookie('test', "test", time() + 60 * 60 * 24 * 30 * 2, '/');
            if (isset($rows['user_id'])) {
                $auth_no = md5($rows['user_id']);
                setcookie('u_authentication', $auth_no, time() + 60 * 60 * 24 * 30 * 2, '/');
                mysqli_query($user_connection, "UPDATE user_storage SET user_auth_no = '$auth_no' WHERE user_id=$rows[user_id]");
                echo 1;
            };
        };
    } else {
        echo 0;
    };
} else {
    header("Location:../index.html");
}
?>