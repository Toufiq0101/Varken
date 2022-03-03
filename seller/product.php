<!DOCTYPE html>
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
    <title>Product</title>
</head>

<body>
    <?php
    include "./database_connection.php";
    session_start();
    if (isset($_GET['edit'])) {
        $product_query = "SELECT product_name,product_price,product_description,product_size,product_color,product_availability,product_category,product_image FROM product_storage WHERE product_id=$_GET[edit]";
        $product_sql = mysqli_query($product_connection, $product_query);
        if ($product_sql->num_rows === 1) {
            while ($row = mysqli_fetch_assoc($product_sql)) {
                $product_name = $row['product_name'];
                $product_price = $row['product_price'];
                $product_description = explode('%|%', $row['product_description']);
                $product_colors_str = $row['product_color'];
                $product_availability = $row['product_availability'];
                $product_category = $row['product_category'];
                $product_size = $row['product_size'];
                $product_images = $row['product_image'];
            }
    ?>
            <div class='edit-product-container'>
                <div id='img-container-edit' class='img-container-edit splide'>
                    <div class='splide__track'>
                        <div class='splide__list'>
                            <?php
                            foreach (explode(' ', $product_images) as $image) {
                                if ($image) {
                                    echo "<div class='splide__slide'>
<img data-splide-lazy='../uploaded_files/$image'>
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
                            <input type='text' name='product_name' placeholder='Name...' value='<?php echo "$product_name"; ?>' required>
                        </div>
                        <div class='input-price edit-input-field-container'>
                            <label for='price'>Price</label><br>
                            <input type='number' name='product_price' placeholder='Price...' required value='<?php echo "$product_price"; ?>'>
                        </div>
                        <div class='input-description-container edit-input-field-container'>
                            <label for='description'>Product Description</label>
                            <div class='edit-discription-list'>
                                <?php
                                foreach ($product_description as $key => $description) {
                                    echo "<label for='Description $key'>Description " . ($key + 1) . " :</label>
<textarea onfocus='textAreaAdjust(this)' type='text' name='product_description[]'
placeholder='Description...'>$description</textarea>";
                                }
                                ?>
                            </div>
                        </div>
                        <div class='edit-input-field-container'>
                            <label for='product_category'>Category : </label>
                            <input class='datalist_input' list='product_category_list' name='product_category' placeholder='Choose...' value='<?php echo "$product_category"; ?>'>
                            <datalist id='product_category_list'>
                                <option value='Mobile Phones'></option>
                                <option value='Tablets'></option>
                                <option value='Laptops & PCs'></option>
                                <option value='Home Appliance'></option>
                                <option value='Electronic Accesories'></option>
                                <option value='Groceries'></option>
                                <option value='Snacks'></option>
                                <option value='Other Essentials'></option>
                                <option value='Gifts and Hampers'></option>
                            </datalist>
                        </div>
                        <div class='input-color edit-input-field-container'>
                            <label for='Size'>Size :</label>
                            <input type="text" name="product_size" <?php echo ($product_size !== '') ? "value='$product_size'" : "placeholder='Size Not Setted...'"; ?>>
                        </div>
                        <div class='input-color edit-input-field-container' id="product_color_input-container">
                            <label for='color'>Color :</label>
                            <span id='add_color-btn'>+</span>
                            <span id='remove_color-btn'>-</span>
                            <?php
                            foreach (explode(',', $product_colors_str) as $product_color) {
                                if ($product_color) {
                                    echo "<input type='color' name='product_color[]' value='$product_color'>";
                                }
                            };
                            ?>
                        </div>
                        <div class='input-image edit-input-field-container'>
                            <input style="display:none;" type="text" name="product_old_image" value="<?php echo $product_images; ?>">
                            <input type='file' name='product_image[]' multiple>
                        </div>
                        <div class='input-availability edit-input-field-container'>
                            <label for='availability'>Availability</label>
                            <select name='availability'>
                                <option value='Available' <?php echo $product_availability === 'Available' ? 'Selected' : '' ?>>Available</option>
                                <option value='Comming Soon' <?php echo $product_availability === 'Comming Soon' ? 'Selected' : '' ?>>Comming Soon</option>
                                <option value='Out of Stock' <?php echo $product_availability === 'Out of Stock' ? 'Selected' : '' ?>>Out of Stock</option>
                            </select>
                        </div>
                        <div class='upload-btn'>
                            <button type='submit' class='submit-product-form-btn' name='upload_item'>Upload Item</button>
                        </div>
                    </form>
                </div>
            </div>
    <?php
        } else {
            echo "";
        }
    }
    ?>
</body>

</html>