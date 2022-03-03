<?php
include "./database_connection.php";
if ($_GET['u_p_id']) {
$query = "SELECT * FROM unv_products WHERE product_id = '$_GET[u_p_id]'";
$sql = mysqli_query($unv_product_connection, $query);
if ($sql) {
while ($rows = mysqli_fetch_assoc($sql)) {
$product_name = $rows['product_name'];
$product_price = $rows['product_price'];
$product_category = $rows['product_category'];
$product_availability = $rows['product_availability'];
$product_all_img = explode(' ', $rows['product_image']);
$product_description_arr = explode('%|%', "$rows[product_description]");
$product_color_arr = explode('%|%', $rows['product_color']);
};
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel='stylesheet' href='../splide-2.4.21/dist/css/splide.min.css'>
<link rel="stylesheet" href="./product.css">
    <link rel="shortcut icon" href="../web_files/favicon.ico" type="image/x-icon">
<script src='../splide-2.4.21/dist/js/splide.min.js' defer></script>
<title>Varken | Warehouse Product</title>
</head>
<body>
<div class='edit-product-container'>
<div id='img-container-edit' class='img-container-edit splide'>
<div class='splide__track'>
<div class='splide__list'>
<?php
foreach ($product_all_img as $image) {
if ($image) {
echo "<div class='splide__slide'>
<img data-splide-lazy='../unv_images/$image'>
</div>";
}
}
?>
</div>
</div>
</div>
<div class='product_info-container-edit'>
<form class='edit-form' id='product-upload-form'>
<div class='input-name edit-input-field-container'>
<label for='product_name'>Name</label><br>
<input type='text' name='product_name' placeholder='Name...'
value='<?php echo "$product_name"; ?>' required disabled>
</div>
<div class='input-price edit-input-field-container'>
<label for='price'>Price</label><br>
<input type='text' name='product_price' placeholder='Price...' required value='<?php echo "$product_price"; ?>' disabled>
</div>
<div class='input-description-container edit-input-field-container'>
<label for='description'>Product Description</label>
<div class='edit-discription-list'>
<?php
foreach ($product_description_arr as $key => $description) {
    if($description){
    $key = $key+1;
echo "<label for='Description $key'>Description $key :</label>
<span class='unv_product_desctiption' onfocus='textAreaAdjust(this)' type='text' name='product_description$key'
placeholder='Description...' required>$description</span>";
};}
?>
</div>
</div>
<div class='edit-input-field-container'>
<label for='product_category'>Category : </label>
<input class='datalist_input' list='product_category_list' name='product_category' placeholder='Choose...' value="<?php echo $product_category;?>" disabled>
<datalist id='product_category_list'>
<option value='Mobile Phones'></option>
<option value='Tablets'></option>
<option value='computers'></option>
<option value='Accesories'></option>
<option value='Groceries'></option>
<option value='Snacks'></option>
</datalist>
</div>
<?php
foreach ($product_color_arr as $color) {
    if($color!==''){
echo "
<div class='input-color edit-input-field-container'>
<label for='color'>color :</label>
<input type='color' name='product_color' value='$color' disabled>
</div>";
    }
}
?>
<div class='input-availability edit-input-field-container'>
<label for='availability'>Availability</label>
<select class='unv_select' name='availability' disabled>
<option value='Available' <?php echo $product_availability==='Available'?'Selected':'' ?>>Available</option>
<option value='Comming Soon' <?php echo $product_availability==='Comming Soon'?'Selected':'' ?>>Comming Soon</option>
<option value='Out of Stock' <?php echo $product_availability==='Out of Stock'?'Selected':'' ?>>Out of Stock</option>
</select>
</div>
<div class='upload-btn'>
<button type='submit' class='submit-product-form-btn' name='upload_item'>Upload Item</button>
</div>
</form>
</div>
</div>
</body>
<script>
document.addEventListener('DOMContentLoaded', function () {
function slider_ctrl(el_id, arrow) {
new Splide(`#${el_id}`, {
padding: {
left: 10,
right: 10,
},
lazyLoad: 'sequential',
rewind: true,
arrows: arrow,
autoWidth: true,
pagination: false,
}).mount();
};
new Splide('#img-container-edit', {
type: "loop",
autoHeight: true,
lazyLoad: 'nearby',
}).mount();
});
</script>
</html>
<?php
};};
?>