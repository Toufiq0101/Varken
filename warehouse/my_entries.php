<?php
include "./database_connection.php";
session_start();
?>

<?php
if (isset($_SESSION['enteric_id'])) {
if(isset($_POST['offset'])){
    $offset = $_POST['offset'];
}else{
    $offset = 0;
};
    if($offset===0){
    echo "<div id='default_tab'>
    <input type='search' id='search_input' placeholder='search...Your Enteries'>
    <i id='search-btn' class='fi-br-search'>search</i>
</div>";}
    $product_enteric_id = $_SESSION['enteric_id'];
    $select_all_product_query = "SELECT product_name,product_price,product_image,product_id FROM unv_products WHERE data_enteric = $product_enteric_id ORDER BY product_id DESC LIMIT $offset,10 ";
    $send_query = mysqli_query($unv_product_connection, $select_all_product_query);
    if ($send_query ->num_rows == 0) {
        if($offset===0){
        echo "
<img class='empty__garage' src='' alt='Empty Error'>
<h1> 😱😱Oops..!! Looks like your Garage is 🤯Empty🤯 </h1>";
        include "./data_entry_form.html";}
        die();
    }
    while ($rows = mysqli_fetch_assoc($send_query)) {
        $product_name = $rows['product_name'];
        $product_price = $rows['product_price'];
        $product_all_images = explode(' ', $rows['product_image']);
        $product_id = $rows['product_id'];
        $formated_price=preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $product_price);
        echo " 
        <div class='product_overview_container'>
        <div class='product_overview product-availabel'>
        <a href='./product.php?edit=$product_id' class='product_image'><img class='product-image'
        src='../unv_images/$product_all_images[0]' loading='lazy'></a><a
    href='./product.php?edit=$product_id' class='product_details '><span
        class='product-name'>$product_name</span><span class='product-price'>Rs.
        $formated_price</span></a><label class='custom_checkbox-container'>
        <input type='checkbox' name='del_p_id' value='$product_id'>
        <span class='custom_checkmark'></span>
    </label>
        </div></div>";
    };
}
// <input class='product-checkbox' type='checkbox' name='del_p_id' value='$product_id'>
?>
<?php
include "./data_entry_form.html";
?>