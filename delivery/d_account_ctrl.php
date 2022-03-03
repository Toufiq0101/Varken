<?php
include "./database_connection.php";
session_start();
?>

<?php
if (isset($_GET['register'])) {
    $img_row = mysqli_query($user_connection, "SELECT courier_image FROM courier WHERE phone_number=$_POST[phone_number]");
    if ($img_row->num_rows !== 0) {
        if (!isset($_GET['edit'])) {
            echo 0;
            die();
        } else {
            $courier_old_image = $img_row['courier_image'];
        }
    };
    $user_name = $_POST['user_name'];
    $phone_number = $_POST['phone_number'];
    $password = $_POST['password'];
    $address = $_POST['address'];
    $coords = explode(',', $_POST['d_user_coords']);
    $courier_image = "";
    if (($_FILES['profile_img']['size'] !== 0)) {
        $profile_img = $_FILES['profile_img']['name'];
        $profile_img_tmp = $_FILES['profile_img']['tmp_name'];
        $profile_img_size = $_FILES['profile_img']['size'];
        $profile_img_error = $_FILES['profile_img']['error'];
        $profile_img_type = $_FILES['profile_img']['type'];
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
                    $final_image = "../uploaded_files/" . "$image_new_name.webp";
                    imagewebp($uploaded_img_content, $final_image, 10);
                    imagedestroy($uploaded_img_content);
                    $courier_image .= "$image_new_name.webp";
                    (isset($_GET['edit']) && $_FILES['profile_img']['size'] !== 0) ?
                        unlink("../uploaded_files/$img_row[$courier_image]") : '';
                } else {
                    echo 9;
                    die();
                };
            } else {
                echo 9;
                die();
            };
        } else {
            echo 9;
            die();
        };
    } elseif (isset($_GET['edit']) && $_FILES['profile_img']['size'] === 0) {
        $client_image = $img_row['client_image'];
    };
    if (isset($_GET['edit'])) {
        $user_query = "UPDATE courier SET courier_name='$user_name',password='$password',phone_number='$phone_number',courier_image='$courier_image',address='$address' WHERE courier_id =$_SESSION[courier_id]";
    } else {
        $user_query = "INSERT INTO courier(courier_name,phone_number,password,latitude,longitude,courier_image,address) VALUES ('$user_name' , '$phone_number' , '$password','$coords[0]','$coords[1]','$courier_image','$address') ";
    }
    $user_sql = mysqli_query($user_connection, $user_query);
    if ($user_sql) {
        echo 1;
        die();
    } else {
        echo 0;
        die();
    };
} elseif (isset($_GET['login']) || isset($_COOKIE['courier_authentication'])) {
    $user_query = "SELECT courier_name,password,phone_number,latitude,longitude,courier_id,courier_auth_no FROM courier WHERE ";
    if (isset($_GET['login'])) {
        $login_ph_no = $_POST['phone_number'];
        $login_password = $_POST['password'];
        $user_query .= "phone_number = '$login_ph_no'";
    } else {
        $user_query .= "courier_auth_no = '$_COOKIE[courier_authentication]' ";
    };
    $user_sql = mysqli_query($user_connection, $user_query);

    $rows = mysqli_fetch_assoc($user_sql);
    if ($user_sql->num_rows === 0) {
        echo 0;
        die();
    } else {
        $user_password = $rows['password'];
        if (isset($_GET['login']) && $user_password !== $login_password) {
            echo 405;
            die();
        } else {
            $_SESSION['courier_ph_no'] = $rows['phone_number'];
            $_SESSION['courier_name'] = $rows['courier_name'];
            $_SESSION['courier_id'] = $rows['courier_id'];
            $_SESSION['d_latitude'] = $rows['latitude'];
            $_SESSION['d_longitude'] = $rows['longitude'];
            if (isset($_SESSION['courier_id']) && isset($rows['phone_number'])) {
                $auth_no = md5($rows['courier_id']);
                if (isset($rows['courier_auth_no'])) {
                    if (!isset($_COOKIE['courier_authentication'])) {
                        if ($_COOKIE['courier_authentication'] !== $rows['courier_auth_no']) {
                            mysqli_query($user_connection, "UPDATE courier SET courier_auth_no = '$auth_no' WHERE courier_id = $rows[courier_id]");
                        }
                        setcookie("courier_authentication", "$auth_no", time() + 60 * 60 * 24 * 30 * 2, "/");
                    };
                    echo 1;
                    die();
                } else {
                    echo 0;
                    die();
                };
            };
        }
    }
} elseif ($_GET['logout']) {
    unset($_SESSION['courier_id']);
    unset($_SESSION['courier_name']);
    unset($_SESSION['courier_ph_no']);
    unset($_SESSION['d_latitude']);
    unset($_SESSION['d_longitude']);
    setcookie("courier_authentication", "", time() - 3600, "/");
};
?>
