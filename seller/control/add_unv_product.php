<?php
include "../database_connection.php";
require __DIR__ . '\vendor\autoload.php';
session_start();

$aloglia_client = Algolia\AlgoliaSearch\SearchClient::create(
    '2BNKFRXSL7',
    'b0580248a833eb9ca7fcf4df8889ef51'
);

$algolia_product_index = $aloglia_client->initIndex('varken_products');

if ($_SESSION['client_id']) {
    if (isset($_GET['add_p_id'])) {
        if ($_POST['add_p_id']) {
            $product_publisher_id = $_SESSION['client_id'];
            $store_name = $_SESSION['store_name'];
            $seller_ph_num = $_SESSION['phone_number'];
            $store_location = $_SESSION['store_location'];
            $p_id_str = explode(',', $_POST['add_p_id']);
            foreach ($p_id_str as $product_id) {
                $get_query = "SELECT * FROM unv_products WHERE product_id = $product_id";
                $get_sql = mysqli_query($unv_product_connection, $get_query);
                while ($rows = mysqli_fetch_assoc($get_sql)) {
                    $product_name = mysqli_real_escape_string($product_connection, $rows['product_name']);
                    $product_price = $rows['product_price'];
                    $product_description = mysqli_real_escape_string($product_connection, $rows['product_description']);
                    $product_image = $rows['product_image'];
                    $product_color = $rows['product_color'];
                    $product_availability = $rows['product_availability'];
                    $product_keywords = $rows['product_keyword'];
                    $product_size = $rows['product_size'];
                    $category = $rows['product_category'];
                }
                foreach (explode(' ', $product_image) as $image) {
                    if ($image !== '') {
                        copy("../../unv_images/$image", "../../uploaded_files/$image");
                    }
                }
                $add_product_query = "INSERT INTO product_storage( product_name, product_price , product_description, product_color, product_size, product_availability,product_category, product_image, product_date, seller_id, store_name, seller_ph_num, store_location ) VALUES('$product_name' , '$product_price','$product_description' , '$product_color' , '$product_size' , '$product_availability' , '$category', '$product_image',now(), '$product_publisher_id' , '$store_name','$seller_ph_num','$store_location' ) ";
                $send_product = mysqli_query($product_connection, $add_product_query);
                $new_prd_id = mysqli_insert_id($product_connection);
                if ($send_product) {
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
                    echo 1;
                } else {
                    echo 0;
                }
            }
        }
    } else {
        echo 0;
    }
} else {
    echo "err|Ş`(*⁂‖﹏⁂‖*)′Ş|err%^%401";
}
