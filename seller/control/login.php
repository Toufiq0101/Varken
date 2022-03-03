<?php include "../database_connection.php";
session_start();
if (isset($_GET['login'])) {
    $login_ph_num = $_POST['phone_number'];
    $login_password = $_POST['password'];
    $login_query = "SELECT store_name,owner_name,phone_number,client_password,store_image,store_location,loc_district,seller_type,client_id,_geoloc FROM client_storage WHERE phone_number = $login_ph_num";
    $query_result = mysqli_query($client_connection, $login_query);
    if ($query_result->num_rows == 0) {
        echo 0;
        die();
    } else {
        while ($rows = mysqli_fetch_assoc($query_result)) {
            $check_ph_num = $rows['phone_number'];
            $check_pasword = $rows['client_password'];
            if ($check_pasword !== $login_password) {
                echo 0;
                die();
            } else {
                $_SESSION['client_id'] = $rows['client_id'];
                $_SESSION['phone_number'] = $rows['phone_number'];
                $_SESSION['owner_name'] = $rows['owner_name'];
                $_SESSION['store_name'] = $rows['store_name'];
                $_SESSION['store_location'] = $rows['store_location'];
                $_SESSION['store_image'] = $rows['store_image'];
                $_SESSION['_geoloc'] = $rows['_geoloc'];
                $_SESSION['seller_type'] = $rows['seller_type'];
                if ($rows['seller_type'] === 'Services') {
                    $cookie_name = 's_authentication';
                    (isset($_COOKIE['c_authentication'])) ? setcookie("c_authentication", "", time() - 3600, '/') : '';
                } else {
                    $cookie_name = 'c_authentication';
                    (isset($_COOKIE['s_authentication'])) ? setcookie("s_authentication", "", time() - 3600, '/') : '';
                };
                if (isset($rows['client_id'])) {
                    $authent_no = md5($rows['client_id']);
                    if (isset($_COOKIE["$cookie_name"]) && $_COOKIE["$cookie_name"] !== $auth_no) {
                        setcookie("$cookie_name", $authent_no, time() + 60 * 60 * 24 * 30 * 12, '/');
                        mysqli_query($client_connection, "UPDATE client_storage SET auth_no = '$authent_no' WHERE client_id=$rows[client_id]");
                    } elseif (!isset($_COOKIE["$cookie_name"])) {
                        setcookie("$cookie_name", $authent_no, time() + 60 * 60 * 24 * 30 * 12, '/');
                        mysqli_query($client_connection, "UPDATE client_storage SET auth_no = '$authent_no' WHERE client_id=$rows[client_id]");
                    };
                    echo 1;
                };
            };
        };
    };
};
