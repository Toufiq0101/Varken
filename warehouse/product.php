<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="../transmitter.js" defer></script>
<script src='../splide-2.4.21/dist/js/splide.min.js' defer></script>
<script src="./product.js" defer></script>
<link rel='stylesheet' href='../splide-2.4.21/dist/css/splide.min.css'>
<link rel="stylesheet" href="./product.css">
    <title>Document</title>
</head>
<body>

<?php
include "./database_connection.php";
include "./control_enteries.php";

if(isset($_GET['edit']) && isset($product_name)){
?>
<div class='edit-product-container'>
<div id='img-container-edit' class='img-container-edit splide'>
<div class='splide__track'>
<div class='splide__list'>
<?php
foreach (explode(' ',$product_images) as $image) {
if ($image) {
echo "<div class='splide__slide'>
<img data-splide-lazy='../unv_images/$image'>
</div>";
}
}
?>
</div>
</div>

<form method="post" enctype="multipart/form-data" class="upload_item" id="upload_item" >
<div>
<?php
if (isset($_GET['edit'])) {
echo "<input type='text' style='display: none;' name='product_old_image' value='$product_images'>";
}
?>
</div>
<div class='edit-input-field-container'>
<label for="product_name">Product Name</label>
<input type="text"name = "product_name" value="<?php echo $product_name; ?>" placeholder="Product name..." required><br>
</div>
<div class='edit-input-field-container'>
<label for="price">Product Price</label>
<input type="text" name="product_price" value="<?php echo $product_price; ?>" placeholder="Product Price..." required><br>
</div>
<div class='input-description-container edit-input-field-container'>
<label for="description">Product Description : </label>
<div class='edit-discription-list'>
<?php
foreach ($product_description_arr as $key => $product_description) {
    if($product_description!==''){
    $key = $key+1;
echo "
<label for='Description $key'>Description $key :</label>
<textarea onfocus='textAreaAdjust(this)' type='text' name='product_description[]'
placeholder='Description...' >$product_description</textarea>";
}
}
?>
</div></div>
<div class='edit-input-field-container'>
<label for='product_category'>Category : </label>
<input class='datalist_input' list='product_category_list' name='product_category' placeholder='Choose...' value='<?php echo "$product_category";?>' autocomplete='off'>
<datalist id='product_category_list'>
         <option value='Mobile Phones'></option>
                <option value='Tablets'></option>
                <option value='Laptops & PCs'></option>
                <option value='Home Appliance'></option>
                <option value='Electronic Accesories'></option>
                <option value='Groceries'></option>
                <option value='Snacks'></option>
                <option value='Other Essentials'></option>
                <option value='Gifts & Hampers'></option>
</datalist>
</div>
<div class='input-color edit-input-field-container' id="product_color_input-container">
<label for='color'>Color :</label>
<span id='add_color-btn'>+</span>
<span id='remove_color-btn'>-</span>
<?php
foreach ($product_color_arr as $key => $product_color) {
    if($product_color){
        echo "
        <input type='color' name='product_color[]' value = '$product_color'>
        ";
    }
}
?>
</div>
<div class='edit-input-field-container'>
<input type="file" name="product_image[]" multiple >
</div>
<div class='edit-input-field-container'>
<select name="availability" >
<option value="Available">Available</option>
<option value="Comming Soon">Comming Soon</option>
<option value="Out of Stock">Out of Stock</option>
</select>
</div>
<button type="submit" name="upload_item">Upload Item</button>
</form>

<?php
}
?>
    
    </body>
</html>