<?php
include "./database_connection.php";
session_start();
?>

<?php
$user_search_terms = $_POST['srch_key_str'];
$search_keywords = "";
if (isset($_POST['offset'])) {
    $offset =$_POST['offset'];
} else {
    $offset =0;
};
    foreach (explode(' ', $user_search_terms) as $key) {
        $search_keywords .= metaphone($key)." ";
        $search_keywords .= $key." ";
    };
    $search_query = "SELECT product_name,product_price,product_image,product_id FROM unv_products ";
    $search_query .= " WHERE MATCH(product_name,product_keyword,product_price) AGAINST('$search_keywords') ";
    $search_query .= (isset($_GET['unv_srch']))?"":"AND data_enteric = $_SESSION[enteric_id]";
    $search_sql = mysqli_query($unv_product_connection, $search_query);
    if ($search_sql->num_rows===0) {
        echo ($offset===0)? "
        <div class='no_content_err-container'>
        <img src='./error.svg' alt='' class='no_content_err-img'>
        <span class='no_content_err-msg'>No Product Found..Check the spelling<span>
        </div>":"";
        die();
    } else {
        while($rows = mysqli_fetch_assoc($search_sql)) {
            $product_name = $rows['product_name'];
            $product_price = $rows['product_price'];
            $product_all_images = explode(' ', $rows['product_image']);
            $product_id = $rows['product_id'];
            $formated_price = preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $product_price);
            echo " 
            <div class='product_overview_container'>
            <div class='product_overview product-availabel'>
            <a href='./product.php?edit=$product_id' class='product_image'><img class='product-image'
            src='../uploaded_files/$product_all_images[0]' loading='lazy'></a><a
        href='./product.php?edit=$product_id' class='product_details '><span
            class='product-name'>$product_name</span><span class='product-price'>Rs.
            $formated_price</span></a><input class='product-checkbox' type='checkbox' name='del_p_id' value='$product_id'>
            </div></div>";
        }
    }
?>