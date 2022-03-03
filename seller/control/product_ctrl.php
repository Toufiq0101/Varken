<?php

include "../database_connection.php";
require __DIR__ . '\vendor\autoload.php';

$aloglia_client = Algolia\AlgoliaSearch\SearchClient::create(
    '2BNKFRXSL7',
    'b0580248a833eb9ca7fcf4df8889ef51'
);

$algolia_product_index = $aloglia_client->initIndex('varken_products');

session_start();
if (isset($_SESSION['client_id'])) {
    if (isset($_GET['upload_item']) || isset($_POST['upload_item'])) {
        $product_name = mysqli_real_escape_string($product_connection, $_POST['product_name']);
        $product_price = $_POST['product_price'];
        $product_size = isset($_POST['product_size']) ? $_POST['product_size'] : "";
        $product_availability = $_POST["availability"];
        $product_description_str = "";
        foreach ($_POST['product_description'] as $description) {
            if ($description !== '') {
                $product_description_str .= "$description%|%";
            };
        };
        $product_description = mysqli_real_escape_string($product_connection, $product_description_str);
        $product_color_str = "";
        if (isset($_POST['product_color'])) {
            foreach ($_POST['product_color'] as $color) {
                $product_color_str .= "$color,";
            };
        };
        $product_category = $_POST['product_category'];
        $seller_id = $_SESSION['client_id'];
        $store_name = $_SESSION['store_name'];
        $store_image = $_SESSION['store_image'];
        $seller_ph_num = $_SESSION['phone_number'];
        $product_store_location = $_SESSION['store_location'];
        $_geoloc = $_SESSION['_geoloc'];
        $all_images = "";
        foreach ($_FILES['product_image']['name'] as $key => $value) {
            if ($_FILES['product_image']['size'][$key] !== 0) {
                $product_image = $_FILES['product_image']['name'][$key];
                $product_image_tmp = $_FILES['product_image']['tmp_name'][$key];
                $product_image_size = $_FILES['product_image']['size'][$key];
                $product_image_error = $_FILES['product_image']['error'][$key];
                $product_image_type = $_FILES['product_image']['type'][$key];
                $imageExt = explode('.', $product_image);
                $imageActExt = strtolower(end($imageExt));
                $image_ext_allowed = array('jpg', 'jpeg', 'png', 'webp');
                if (in_array($imageActExt, $image_ext_allowed)) {
                    if ($product_image_error === 0) {
                        if ($product_image_size < 500000) {
                            $image = imagecreatefromstring(file_get_contents($product_image_tmp));
                            ob_start();
                            imagejpeg($image, null, 80);
                            $cont = ob_get_contents();
                            ob_end_clean();
                            $content = imagecreatefromstring($cont);
                            $image_new_name = uniqid('', true) . rand(0, 9) . rand(0, 9);
                            $image_destination = "../../uploaded_files/" . "$image_new_name.webp";
                            if ($product_image_size > 250000) {
                                imagewebp($content, $image_destination, 10);
                            } elseif ($product_image_size > 100000) {
                                imagewebp($content, $image_destination, 40);
                            } elseif ($product_image_size < 100000) {
                                imagewebp($content, $image_destination, 80);
                            } else {
                                imagewebp($content, $image_destination, 10);
                            }
                            imagedestroy($content);
                            $all_images .= "$image_new_name.webp ";
                        } else {
                            die("Image size is too big..!!");
                        };
                    } else {
                        die("image format does not support..!!");
                    };
                } else {
                    die("file format doesn't not support");
                };
            };
        };
        if (isset($_GET['edit'])) {
            if ($_FILES['product_image']['size'][0] == 0) {
                $all_images .= "$_POST[product_old_image]";
            } else {
                $product_old_img = explode(' ', $_POST['product_old_image']);
                foreach ($product_old_img as $product_img) {
                    unlink("../../uploaded_files/$product_img");
                };
            };
        };
        if (isset($_GET['edit'])) {
            $edit_pro_id = $_GET['edit'];
            $send_product_query = "UPDATE product_storage SET product_name = '$product_name', product_price='$product_price' ,product_category='$product_category', product_description='$product_description',product_color='$product_color_str',product_size='$product_size',product_availability='$product_availability', product_image = '$all_images', product_date=now() ,seller_id = '$seller_id', store_name='$store_name', seller_ph_num='$seller_ph_num',store_image='$store_image', store_location = '$product_store_location',_geoloc = '$_geoloc' WHERE product_id = $edit_pro_id ";
            $send_product = mysqli_query($product_connection, $send_product_query);
            $new_prd_id = $edit_pro_id;
        } else {
            print_r($_geoloc);
            $send_product_query = "INSERT INTO product_storage(product_name, product_price ,product_category, product_description,product_size,product_color, product_availability, product_image, product_date, seller_id, store_name, seller_ph_num,store_image, store_location, _geoloc) ";
            $send_product_query .= "VALUES('$product_name' , '$product_price' ,'$product_category', '$product_description','$product_size' , '$product_color_str' , '$product_availability', '$all_images',now(), '$seller_id' , '$store_name','$seller_ph_num','$store_image','$product_store_location' ,'$_geoloc' ) ";
            $send_product = mysqli_query($product_connection, $send_product_query);
            $new_prd_id = mysqli_insert_id($product_connection);
        };
        $geoloc_lat_lng = json_decode("$_geoloc", true);
        $records = [[
            "product_name" => "$product_name",
            "product_price" => "$product_price",
            "product_description" => "$product_description",
            "product_color" => "$product_color_str",
            "product_size" => "$product_size",
            "product_availability" => "$product_availability",
            "product_image" => "$all_images",
            "product_id" => "$new_prd_id",
            "seller_id" => "$seller_id",
            "store_name" => "$store_name",
            "seller_ph_num" => "$seller_ph_num",
            "store_image" => "$store_image",
            "store_location" => "$product_store_location",
            "product_date" => '"' . date('y-m-d') . '"',
            "product_category" => "$product_category",
            "product_ratings" => "",
            "net_sales" => "0",
            "_geoloc" => [["lat" => $geoloc_lat_lng['lat'], "lng" => $geoloc_lat_lng['lng']]],
            "objectID" => "$new_prd_id"
        ]];
        $algolia_product_index->saveObjects($records, ['autoGenerateObjectIDIfNotExist' => true]);
        if ($send_product) {
            echo 1;
        } else {
            echo 0;
        };
    };
} else {
    echo "err|Ş`(*⁂‖﹏⁂‖*)′Ş|err%^%401";
};

?>
