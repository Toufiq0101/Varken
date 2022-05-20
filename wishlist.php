<?php
include "./db_connection.php";
session_start();

if (isset($_SESSION['user_id'])) {
echo "<div class='tab-heading'>Wishlist</div>";
$u_id = $_SESSION['user_id'];
$query = "SELECT wishlist FROM user_storage WHERE user_id = $u_id";
$query_sql = mysqli_query($user_connection, $query);
while ($row = mysqli_fetch_assoc($query_sql)) {
$wishlist_item_arr = rtrim(($row['wishlist']), ", ");
if ($row['wishlist'] === '') {
echo "
<div class='no_content_err-container'>
<img src='' alt='' class='no_content_err-img'>
<span class='no_content_err-msg'>😏Deep Wishing increases hunger for success<span>
</div>";
die();
}else {
$item_query = "SELECT product_name,product_price,product_image,product_id FROM product_storage WHERE product_id IN ($wishlist_item_arr)";
$item_sql = mysqli_query($product_connection, $item_query);
while ($rows = mysqli_fetch_assoc($item_sql)) {
$product_name = $rows['product_name'];
$product_price = $rows['product_price'];
$product_image =explode(' ', $rows['product_image']);
$product_id = $rows['product_id'];
echo "
<div class='main-overview-container'>
<div class='overview_container'>
<a href='/product.php?i=$product_id' class='overview-img_container'>
<img class='overview-img' src='../uploaded_files/$product_image[0]' loading='lazy'>
</a>
<div class='overview-details-list '>
<a href='/product.php?i=$product_id' class='overview-detail name'>$product_name</a>
<a href='/product.php?i=$product_id' class='overview-detail price'>Rs.$product_price</a>
<div class='btn-container'>
<i style='background-color:#ff000000;' class='btn-1 del-wishlist' data-p_id='$product_id'><img src='./css/svg/remove.png' alt=''></i>
</div>
</div>
</div>
";
};
};
};
}
?>