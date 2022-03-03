<?php include "../database_connection.php";
session_start();
if (isset($_GET['verify'])) {
    if (isset($_SESSION['client_id'])) {
        echo 1;
    } elseif (isset($_COOKIE['c_authentication']) || isset($_COOKIE['s_authentication'])) {
        $auth_no = (isset($_COOKIE['c_authentication'])) ? $_COOKIE['c_authentication'] : $_COOKIE['s_authentication'];
        $login_query = "SELECT store_name,owner_name,phone_number,client_password,store_image,store_location,loc_district,seller_type,client_id,_geoloc FROM client_storage WHERE auth_no = '$auth_no'";
        $query_result = mysqli_query($client_connection, $login_query);
        if ($query_result->num_rows === 0) {
            setcookie("c_authentication", "", time() - 3600, '/');
            echo 0;
            die();
        } else {
            while ($rows = mysqli_fetch_assoc($query_result)) {
                $_SESSION['client_id'] = $rows['client_id'];
                $_SESSION['phone_number'] = $rows['phone_number'];
                $_SESSION['owner_name'] = $rows['owner_name'];
                $_SESSION['store_name'] = $rows['store_name'];
                $_SESSION['store_location'] = $rows['store_location'];
                $_SESSION['store_image'] = $rows['store_image'];
                $_SESSION['_geoloc'] = $rows['_geoloc'];
                $_SESSION['seller_type'] = $rows['seller_type'];
                $seller_type = $rows['seller_type'];
            };
            echo 1;
            if ($seller_type === 'Services') {
                $cookie_name = 's_authentication';
                (isset($_COOKIE['c_authentication'])) ? setcookie("c_authentication", "", time() - 3600, '/') : '';
            } else {
                $cookie_name = 'c_authentication';
                (isset($_COOKIE['s_authentication'])) ? setcookie("s_authentication", "", time() - 3600, '/') : '';
            };
        };
    } else {
        echo 0;
    };
} elseif ($_COOKIE['c_authentication']) {
    $auth_no = $_COOKIE['c_authentication'];
    $login_query = "SELECT store_name,owner_name,phone_number,client_password,store_image,store_location,loc_district,seller_type,client_id,_geoloc FROM client_storage WHERE auth_no = '$auth_no'";
    $query_result = mysqli_query($client_connection, $login_query);
    if ($query_result->num_rows == 0) {
        setcookie("c_authentication", "", time() - 3600, '/');
        die();
    } else {
        while ($rows = mysqli_fetch_assoc($query_result)) {
            $_SESSION['client_id'] = $rows['client_id'];
                $_SESSION['phone_number'] = $rows['phone_number'];
                $_SESSION['owner_name'] = $rows['owner_name'];
                $_SESSION['store_name'] = $rows['store_name'];
                $_SESSION['store_location'] = $rows['store_location'];
                $_SESSION['store_image'] = $rows['store_image'];
                $_SESSION['_geoloc'] = $rows['_geoloc'];
                $_SESSION['seller_type'] = $rows['seller_type'];
        };
        die();
    };
};
