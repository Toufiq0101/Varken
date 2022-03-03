<?php include "../database_connection.php";
ob_start();
session_start();
if (isset($_GET['register']) || isset($_GET['edit'])) {
    $client_old_img_sql = mysqli_query($client_connection, "SELECT client_image,store_image FROM client_storage WHERE phone_number = '$_POST[phone_number]'");
    if (isset($_GET['register']) && $client_old_img_sql->num_rows !== 0) {
        echo 2;
        die();
    } else {
        $store_name = strtolower($_POST['store_name']);
        $client_name = strtolower($_POST['store_owner_name']);
        $client_phone_number = $_POST['phone_number'];
        $client_password = $_POST['client_password'];
        $seller_type = strtolower($_POST['seller_type']);
        $client_loc_data = explode(',', $_POST['client_coords']);
        print_r($client_loc_data);
        $client_store_description = strtolower($_POST['store_description']);
        $store_location = strtolower($_POST['store_location']);
        if (isset($client_loc_data[1]) && isset($client_loc_data[0])) {
            $client_lat = $client_loc_data[0];
            $client_long = $client_loc_data[1];
            $client_district = $client_loc_data[2];
        } else {
            $client_lat = '';
            $client_long = '';
            $client_district = 'Dumka';
        };
        $client_image = '';
        $store_image = '';

        $img_row = mysqli_fetch_assoc($GLOBALS['client_old_img_sql']);
        function img_manager($img_name, $img_var)
        {
            if (($_FILES["$img_name"]['size'] !== 0)) {
                $profile_img = $_FILES["$img_name"]['name'];
                $profile_img_tmp = $_FILES["$img_name"]['tmp_name'];
                $profile_img_size = $_FILES["$img_name"]['size'];
                $profile_img_error = $_FILES["$img_name"]['error'];
                $imageExt = explode('.', $profile_img);
                $imageActExt = strtolower(end($imageExt));
                $image_ext_allowed = array('jpg', 'jpeg', 'png', 'webp');
                if (in_array($imageActExt, $image_ext_allowed)) {
                    if ($profile_img_error === 0) {
                        if ($profile_img_size < 10000000) {
                            $image = imagecreatefromstring(file_get_contents($profile_img_tmp));
                            ob_start();
                            imagejpeg($image, null, 100);
                            $cont = ob_get_contents();
                            ob_end_clean();
                            $uploaded_img_content = imagecreatefromstring($cont);
                            $image_new_name = uniqid('', true) . rand(0, 9) . rand(0, 9);
                            $final_image = "../../uploaded_files/" . "$image_new_name.webp";
                            imagewebp($uploaded_img_content, $final_image, 10);
                            imagedestroy($uploaded_img_content);
                            $GLOBALS["$img_var"] .= "$image_new_name.webp";
                            (isset($_GET['edit']) && $_FILES["$img_name"]['size'] !== 0) ? unlink("../../uploaded_files/$GLOBALS[img_row][$img_var]") : '';
                        } else {
                            echo 9;
                            die();
                        };
                    } else {
                        9;
                        die();
                    };
                } else {
                    echo 9;
                    die();
                };
            } elseif (isset($_GET['edit']) && $_FILES["$img_name"]['size'] === 0) {
                $GLOBALS["$img_var"] = $GLOBALS["img_row"]["$img_var"];
            };
        };
        img_manager("profile_img", "client_image");
        img_manager("store_img", "store_image");
        if (isset($_GET['edit'])) {
            $send_client_query = "UPDATE client_storage SET store_name='$store_name', owner_name='$client_name' ,seller_type='$seller_type', store_description='$client_store_description', phone_number='$client_phone_number',client_password='$client_password', client_image ='$client_image', store_image='$store_image', store_location = '$store_location', loc_district = '$client_district', _geoloc = '{ \"lat\" : $client_lat , \"lng\" : $client_long }' WHERE client_id = $_SESSION[client_id]";
            $update_client_products = "UPDATE product_storage SET store_name = '$store_name' , seller_ph_num = '$client_phone_number' , store_image = '$store_image' , store_location = '$store_location', _geoloc = JSON_OBJECT('lat', $client_lat, 'lng', $client_long) WHERE seller_id = $_SESSION[client_id]";
        } else {
            $send_client_query = "INSERT INTO client_storage(store_name, owner_name , store_description,seller_type,phone_number,client_password, client_image , store_image , store_location ,loc_district, _geoloc ) VALUES('$store_name' , '$client_name' , '$client_store_description' ,'$seller_type', '$client_phone_number' ,'$client_password', '$client_image', '$store_image' , '$store_location','$client_district ','{ \"lat\" : $client_lat , \"lng\" : $client_long }')";
        };
        $send_client = mysqli_query($client_connection, $send_client_query);
        if ($send_client) {
            mysqli_query($product_connection, $update_client_products);
            echo 1;
            if (isset($_GET['edit'])) {
                $_SESSION['phone_number'] = $_POST['phone_number'];
                $_SESSION['owner_name'] = $_POST['store_owner_name'];
                $_SESSION['store_name'] = $_POST['store_name'];
                $_SESSION['store_location'] = $_POST['store_location'];
                $_SESSION['seller_type'] = $_POST['seller_type'];
                $cookie_name = ($_POST['seller_type'] === 'Services') ? 's_authentication' : 'c_authentication';
                $exit_cookie_name = ($_POST['seller_type'] === 'Services') ? 'c_authentication' : 's_authentication';
                if (!isset($_COOKIE[$cookie_name])) {
                    $authent_no = md5($_SESSION['client_id']);
                    setcookie("$cookie_name", $authent_no, time() + 60 * 60 * 24 * 30 * 12, '/');
                };
                if (isset($_COOKIE[$exit_cookie_name])) {
                    setcookie("$exit_cookie_name", "", time() - 3600, '/');
                };
            };
            die();
        } else {
            echo 0;
            die();
        };
    };
} else {
    die();
};