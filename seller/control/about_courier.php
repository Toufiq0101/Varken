<?php include "../database_connection.php";
session_start();
if (isset($_SESSION['client_id']) && isset($_POST['courier_id'])) {
    $courier_id = $_POST['courier_id'];
    $sql = mysqli_query($user_connection, "SELECT courier_name,courier_image,phone_number,address FROM courier WHERE courier_id = $courier_id");
    if ($sql->num_rows !== 0) {
        while ($row = mysqli_fetch_assoc($sql)) {
            $name = $row['courier_name'];
            $image = $row['courier_image'];
            $ph_num = $row['phone_number'];
            $address = $row['address'];
            echo "<div class='courier_info-container'><span>Courie Boy Info.</span><br><div class='img-container'><img src='../../uploaded_files/$image' alt='img'></div><span class='courier-name'><span style='font-weight:600'>Name : </span>$name</span><span class='courier-ph_num'><span style='font-weight:600'>Ph : </span>$ph_num</span><span class='courier-address'><span style='font-weight:600'>Add : </span>$address</span></div>";
        };
    } else {
        echo "<div class='no_content_err-container'><img src='./css/svg/error.svg' alt='' class='no_content_err-img'><span class='no_content_err-msg'>No Courier Man Found.<span></div>";
    };
};
