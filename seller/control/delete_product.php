<?php

require __DIR__ . '\vendor\autoload.php';
include "../database_connection.php";
session_start();

$aloglia_client = Algolia\AlgoliaSearch\SearchClient::create(
    '2BNKFRXSL7',
    'b0580248a833eb9ca7fcf4df8889ef51'
);

$algolia_product_index = $aloglia_client->initIndex('varken_products');

if (isset($_GET['delete'])) {
    if ($_POST['del_str']) {
        $img_query = "SELECT product_image FROM product_storage WHERE product_id IN ($_POST[del_str])";
        $del_img_sql = mysqli_query($product_connection, $img_query);
        while ($img_rows = mysqli_fetch_assoc($del_img_sql)) {
            $product_all_img = explode(' ', $img_rows['product_image']);
            $del_check = "";
            foreach ($product_all_img as $img_src) {
                if ($img_src) {
                    unlink("../../uploaded_files/$img_src");
                };
            };
        };
        $del_query = "DELETE  FROM product_storage WHERE product_id IN ($_POST[del_str])";
        $send_del_query = mysqli_query($product_connection, $del_query);
        print_r($_POST['del_str']);
        $algolia_product_index->deleteObjects([$_POST['del_str']]);
        if ($send_del_query) {
            echo 1;
        }
    }
}
