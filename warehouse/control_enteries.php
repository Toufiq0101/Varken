<?php
if (!isset($_GET['edit'])) {
include "./database_connection.php";
};

if(isset($_GET['edit'])){
$edit_pro_id = $_GET['edit'];
$edit_query = "SELECT * FROM unv_products WHERE product_id = $edit_pro_id";
$sql = mysqli_query($unv_product_connection,$edit_query);
if($sql){
while ($rows = mysqli_fetch_assoc($sql)) {
$product_name = $rows['product_name'];
$product_price = $rows['product_price'];
$product_availability = $rows['product_availability'];
$product_images = $rows['product_image'];
$product_category = $rows['product_category'];
$product_description_arr = explode('%|%',"$rows[product_description]");
$product_color_arr = explode(',',$rows['product_color']);
}}};
if (isset($_GET['delete'])) {
$del_str = explode(',',$_POST['del_str']);
print_r($del_str);
foreach($del_str as $product_id) {
$img_query = "SELECT product_image FROM unv_products WHERE product_id = $product_id";
$del_img_sql = mysqli_query($unv_product_connection, $img_query);
while ($img_rows = mysqli_fetch_assoc($del_img_sql)) {
$product_all_img = explode(' ',$img_rows['product_image']);
$del_check = "";
foreach ($product_all_img as $img_src) {
unlink("../unv_images/$img_src");
}
$del_query = "DELETE FROM unv_products WHERE product_id = $product_id";
$send_del_query = mysqli_query($unv_product_connection,$del_query);
if($send_del_query){
echo "deleted";
}}}};
?>

<!-- Documentry on php backdoor -->