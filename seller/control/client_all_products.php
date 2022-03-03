<?php include "../database_connection.php";
session_start();
if (isset($_SESSION['client_id'])) {
    $add_product_btn_markup = "
<span onclick='document.querySelector(`#upload-product-form-container`).style.display =`block`'><img class='add-product-btn'src='data:image/png;base64,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'/></span>";
    if (isset($_POST['tab']) && $_POST['tab'] !== '') {
        die();
    }
    if (isset($_COOKIE['s_authentication'])) {
        $client_id = $_SESSION['client_id'];
        if (isset($_POST['offset'])) {
            $offset = $_POST['offset'];
        } else {
            $offset = 0;
        };
        $select_all_service_query = "SELECT service_name,service_cost,service_provider_id,service_id,average_time FROM service_storage WHERE service_provider_id = '$client_id' ORDER BY service_id DESC LIMIT $offset,10";
        $send_query = mysqli_query($product_connection, $select_all_service_query);
        if ($offset === 0) {
            echo "<div class='tab-heading'>Services</div>";
        };
        if ($send_query->num_rows === 0) {
            if ($offset === 0) {
                echo "<div class='no_content_err-container'><img src='./css/svg/error.svg' alt='' class='no_content_err-img'><span class='no_content_err-msg'>You Store is Empty<span></div>$add_product_btn_markup";
                die();
            } else {
                echo '';
                die();
            }
        } else {
            echo "<div class='all-service-contianer-grid'>";
            while ($rows = mysqli_fetch_assoc($send_query)) {
                $service_name = $rows['service_name'];
                $service_id = $rows['service_id'];
                $service_time = $rows['average_time'];
                $formated_price = preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $rows['service_cost']);
                echo "<div class='service_container'><div class='column-1'><div class='service_name'>$service_name</div><div class='service_time'><div style='font-size:20px;'>⏱️</div>$service_time min</div></div><div class='column-2'><div class='service_cost'>Rs. $formated_price</div></div><input class='service-id-checkbox' type='checkbox' name='del_p_id' value='$service_id'></div>";
            };
            echo "</div>";
            echo $add_product_btn_markup;
        };
    } else {
        $client_id = $_SESSION['client_id'];
        if (isset($_POST['offset'])) {
            $offset = $_POST['offset'];
        } else {
            $offset = 0;
        };
        $select_all_product_query = "SELECT product_name,product_price,product_availability,product_date,product_image,product_id FROM product_storage WHERE seller_id = '$client_id' ORDER BY product_id DESC LIMIT $offset,10";
        $send_query = mysqli_query($product_connection, $select_all_product_query);
        if ($offset === 0) {
            echo "<div class='tab-heading'>Store</div>";
        };
        if ($send_query->num_rows === 0) {
            if ($offset === 0) {
                echo "<div class='no_content_err-container'><img src='./css/svg/error.svg' alt='' class='no_content_err-img'><span class='no_content_err-msg'>You Store is Empty<span></div>";
                echo "$add_product_btn_markup";
                die();
            } else {
                echo '';
                die();
            };
        } else {
            while ($rows = mysqli_fetch_assoc($send_query)) {
                $product_name = $rows['product_name'];
                $product_price = $rows['product_price'];
                $product_availability = $rows['product_availability'];
                $product_date = $rows['product_date'];
                $product_all_images = explode(' ', $rows['product_image']);
                $product_id = $rows['product_id'];
                $formated_price = preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $product_price);
                echo "<div class='product_overview_container'>";
                if ($product_availability === 'Available') {
                    echo "<div class='product_overview product-availabel'>";
                } elseif ($product_availability == 'Out of Stock') {
                    echo "<div class='product_overview product-unavailabel'>";
                } else {
                    echo "<div class='product_overview product-comming'>";
                }
                echo "<a href='./product.php?edit=$product_id' class='product_image'><img class='product-image' src='../../uploaded_files/$product_all_images[0]'  loading='lazy'></a><a href='./product.php?edit=$product_id' class='product_details '><span class='product-name'>$product_name</span><span class='product-price'>Rs. $formated_price</span><span class='product-date'>Date:-$product_date</span></a><input class='product-checkbox' type='checkbox' name='del_p_id' value='$product_id'></div></div>";
            }
            echo "$add_product_btn_markup";
        };
    };
} else {
    if (isset($_POST['offset'])) {
        $offset = $_POST['offset'];
    } else {
        $offset = 0;
    };
    if ($offset === 0) {
        echo "err|Ş`(*⁂‖﹏⁂‖*)′Ş|err%^%4010";
    }
}
