<?php
include "./db_connection.php";
?>

<?php
if (isset($_GET['cat'])) {
    if (isset($_POST['offset'])) {
        $offset = $_POST['offset'];
    } else {
        echo "<div class='tab-heading'>$_GET[cat]</div>";
        $offset = 0;
    };
    $product_query = "SELECT product_name,product_price,product_image,product_color,product_size,seller_id,product_id FROM product_storage WHERE product_category = '$_GET[cat]' ORDER BY product_id DESC LIMIT $offset,10";
    $product_sql = mysqli_query($product_connection, $product_query);
    if ($product_sql->num_rows === 0 && $offset === 0) {
        echo "
<div class='no_content_err-container'>
<img src='./css/svg/error.svg' alt='' class='no_content_err-img'>
<span class='no_content_err-msg'>We will add here Something soon<span>
</div>";
        die();
    } else {
        while ($rows = mysqli_fetch_assoc($product_sql)) {
            $product_name = $rows['product_name'];
            $product_price = $rows['product_price'];
            $product_all_images = explode(" ", $rows['product_image']);
            $product_id = $rows['product_id'];
            $product_size = $rows['product_size'];
            $product_color = $rows['product_color'];
            $product_publisher_id = $rows['seller_id'];
            $formated_price = preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $product_price);
            echo "
<div class='main-overview-container'>
<div class='overview_container'>
<a href='./product.php?i=$product_id' class='overview-img_container'>
<img class='overview-img' src='../../uploaded_files/$product_all_images[0]' alt='img'loading='lazy'>
</a>
</a>
<span  class='overview-details-list '>
<a href='./product.php?i=$product_id' class='overview-detail name repeated_markup-check' data-name='$product_name' >$product_name</a>
<a href='./product.php?i=$product_id' class='overview-detail price'>Rs. $formated_price</a>
<div class='btn-container'>
<span class='overview-detail btn-1' onclick='open_quickii_modal(`$product_all_images[0]`,`$product_name`,`$product_price`,`$product_color`,`$product_size`,`$product_publisher_id`,`$product_id`)'>Quickii..</span>
</div>
</span>
</div>
</div>";
        }
    }
} else {
    echo 9;
}
?>