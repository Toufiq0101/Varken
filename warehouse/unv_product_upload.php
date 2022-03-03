<?php
include "./database_connection.php";
session_start();
?>

<?php
if (isset($_SESSION['enteric_id'])) {
    if (isset($_GET['upload_item']) || isset($_POST['upload_item'])) {
        $product_name = mysqli_real_escape_string($unv_product_connection,$_POST['product_name']);
        $product_price = $_POST['product_price'];
        $product_availability = $_POST["availability"];
        $product_description_str = "";
        foreach ($_POST['product_description'] as $description) {
            $product_description_str .= "$description%|%";
        };
        $product_category = $_POST['product_category'];
        $product_size = isset($_POST['product_size'])? mysqli_real_escape_string($unv_product_connection,$_POST['product_size']):'';
        $product_color = "";
        foreach ($_POST['product_color'] as $color) {
            $product_color .= "$color,";
        };
        $search_key_str = "";
        $search_key_array = explode(' ', "$product_name");
        foreach ($search_key_array as $word) {
            $key = metaphone($word);
            $search_key_str .= " $key";
        };
        $all_images = "";
        $product_description = mysqli_real_escape_string($unv_product_connection,$product_description_str);
        foreach ($_FILES['product_image']['name'] as $key => $value) {
            if ($_FILES['product_image']['size'][$key]!==0) {
                $product_image = $_FILES['product_image']['name'][$key];
                $product_image_tmp = $_FILES['product_image']['tmp_name'][$key];
                $product_image_size = $_FILES['product_image']['size'][$key];
                $product_image_error = $_FILES['product_image']['error'][$key];
                $product_image_type= $_FILES['product_image']['type'][$key];
                $imageExt = explode('.', $product_image);
                $imageActExt = strtolower(end($imageExt));
                $image_ext_allowed = array('jpg','jpeg','png','webp');
                if (in_array($imageActExt, $image_ext_allowed)) {
                    if ($product_image_error===0) {
                        if ($product_image_size<500000) {
                            $image = imagecreatefromstring(file_get_contents($product_image_tmp));
                            ob_start();
                            imagejpeg($image, null, 80);
                            $cont = ob_get_contents();
                            ob_end_clean();
                            $content=imagecreatefromstring($cont);
                            $image_new_name = uniqid('', true).rand(0, 9).rand(0, 9);
                            $image_destination = "../unv_images/"."UNV_IMG$image_new_name.webp";
                            if ($product_image_size>250000) {
                                imagewebp($content, $image_destination, 10);
                            } elseif ($product_image_size>100000) {
                                imagewebp($content, $image_destination, 40);
                            } elseif ($product_image_size<100000) {
                                imagewebp($content, $image_destination, 70);
                            } else {
                                imagewebp($content, $image_destination, 10);
                            }
                            imagedestroy($content);
                            $all_images .= "UNV_IMG$image_new_name.webp ";
                        }  else {
                            die(9);
                        }
                    } else {
                        die(9);
                    }
                } else {
                    die(9);
                }
            }
        }
        if (isset($_GET['edit'])) {
            if($_FILES['product_image']['size'][0]==0){
                $all_images .= "$_POST[product_old_image]";
            }else{
                $product_old_img = explode(' ',$_POST['product_old_image']);
                foreach ($product_old_img as $product_img) {
                    if($product_img){
                        unlink("../unv_images/$product_img");
                    }
                };
            };
        };
        if (isset($_GET['edit'])) {
            $edit_pro_id = $_GET['edit'];
            $send_product_query = "UPDATE unv_products SET product_name = '$product_name', product_price='$product_price' , product_description='$product_description',
product_color='$product_color',product_availability='$product_availability',product_category='$product_category', product_image = '$all_images', product_keyword='$search_key_str' ,
data_enteric='$_SESSION[enteric_id]' WHERE product_id = $edit_pro_id ";
        } else {
            $send_product_query = "INSERT INTO unv_products(
product_name, product_price , product_description,product_color,product_size,product_category,product_availability, product_image, product_keyword ,data_enteric) ";
            $send_product_query .= "VALUES('$product_name' , '$product_price' , '$product_description' , '$product_color' ,'$product_size','$product_category', 
'$product_availability', '$all_images','$search_key_str','$_SESSION[enteric_id]' ) ";
        }
        if($send_product = mysqli_query($unv_product_connection, $send_product_query)){
            echo 1;
        }else{
            echo 2;
        };
    }
}
?>