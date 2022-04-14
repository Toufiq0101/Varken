<?php
include "./db_connection.php";
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset='UTF-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Varken | Product</title>
    <link rel="stylesheet" href="./css/header.css">
    <link rel='stylesheet' href='./product.css'>
    <link rel='stylesheet' href='../splide-2.4.21/dist/css/splide.min.css'>
    <script src='../splide-2.4.21/dist/js/splide.min.js' defer></script>
    <script src="./product.js" defer></script>
    <script src="../transmitter.js"></script>
    <link rel="shortcut icon" href="./web_files/favicon.ico" type="image/x-icon">
</head>

<body>
    <header class="header">
        <div class="header-1 left-header-1">
            <!-- <img src="./web_files/halka.webp" class="company-logo" draggable="false" /><span style="font-size: x-small; color: rgb(121, 121, 121)" alt="logo">
                beta</span> -->
        </div>
        <a class="right-header-1" href="/?profile">
            <img src="./css/svg/sentimental_profile.svg" class="profile-btn" id="profile-btn" alt="profile" draggable="false" />
        </a>
    </header>
    <div class="fake-div"></div>
    <div class="header-2">
        <div class="search-bar-container">
            <div class="sticky-menu-btn">
                <img class="menu-btn" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAABmJLR0QA/wD/AP+gvaeTAAAAKElEQVRIiWNgGAXDHjAisf/TwmwmKhs6CgYhGE1Fo4ByMJqKRsEQAADWCQMKYvEFtQAAAABJRU5ErkJggg==" onclick="document.getElementById('menu_container').classList.toggle('menu_container-display')" draggable="false" alt="menu" />
            </div>
            <div class="search-bar">
                <input type="text" class="search-field" id="search-field">
                <span style="
              background-image: url(./css/svg/search-icon.svg);
              background-repeat: no-repeat;
              object-fit: contain;
            " class="search-btn" id="search-btn"></span>
            </div>
        </div>
        <div class="menu_container" id="menu_container" onclick="document.getElementById('menu_container').classList.remove('menu_container-display')">
            <nav class="nav-link nav-link-container">
                <a href="/?" id="home-btn" class="nav_link_btn-container">
                    <img src="./css/svg/home.svg" alt="home" class="tab-btn" draggable="false" /><span>Home</span>
                </a>
                <a href="/?market" id="market-btn" class="nav_link_btn-container">
                    <img src="./css/svg/market.svg" alt="market" class="tab-btn" draggable="false" /><span>Market</span>
                </a>
                <a href="/?my_orders" id="my_orders-btn" class="nav_link_btn-container">
                    <img src="./css/svg/my_order.svg" alt="my order" class="tab-btn" draggable="false" /><span>Orders</span>
                </a>
                <a href="/?order_history" id="order_history" class="nav_link_btn-container">
                    <img src="./css/svg/order_history.svg" alt="order history" class="tab-btn" draggable="false" /><span>History</span>
                </a>
                <a href="/?fav_store" id="fav_stores-btn" class="nav_link_btn-container">
                    <img src="./css/svg/fav-store.svg" alt="saved store" class="tab-btn" draggable="false" /><span>My Stores</span>
                </a>
                <a href="/?my_cart" id="my_cart-btn" class="nav_link_btn-container">
                    <img src="./css/svg/my_cart.svg" alt="my cart" class="tab-btn" draggable="false" /><span>Cart</span>
                </a>
                <a href="/?wishlist" id="wishlist-btn" class="nav_link_btn-container">
                    <img src="./css/svg/wishlist.svg" alt="wishlist" class="tab-btn" draggable="false" /><span>Wishlist</span>
                </a>
            </nav>
        </div>
    </div>
    <div id='order-alert'></div>
    <main>
        <?php
        if (isset($_GET['i'])) {
            $product_id = $_GET['i'];
            $query = "SELECT product_name,product_price,product_image,product_size,product_color,product_availability,product_description,product_category,seller_id,store_name,seller_ph_num,store_image,store_location FROM product_storage WHERE product_id = $product_id";
            $send_query = mysqli_query($product_connection, $query);
            if ($send_query->num_rows === 0) {
                echo "<div class='no-page-found-cntnr'><img src='../web_files/arabica-1084.png' alt='no page found' class='no-page-found-cntnr-img'><div class='no-page-found-cntnr-msg'>Go Back to <a href='/'>Home Page</a></div></div>";
            } else {
                while ($rows = mysqli_fetch_assoc($send_query)) {
                    $product_name = $rows['product_name'];
                    $product_price = $rows['product_price'];
                    $formated_price = preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $product_price);
                    $product_image_arr = explode(' ', $rows['product_image']);
                    $product_color = $rows['product_color'];
                    $product_size = $rows['product_size'];
                    $product_availablity = $rows['product_availability'];
                    $product_description_list = explode('%|%', $rows['product_description']);
                    $product_category = $rows['product_category'];
                    $seller_id = $rows['seller_id'];
                };
                $client_sql = mysqli_query($client_connection, "SELECT store_name,store_location,client_image,store_status FROM client_storage WHERE client_id = $seller_id");
                while ($rows = mysqli_fetch_assoc($client_sql)) {
                    $product_publisher_name = $rows['store_name'];
                    $store_img = $rows['client_image'];
                    $store_location = $rows['store_location'];
                    $status = $rows['store_status'];
                };
                echo "
<div class='product-details-container'>
<div id='product-img_container' class='splide'>
<div class='splide__track'>
<div class='splide__list'>";
                foreach ($product_image_arr as $product_image) {
                    if ($product_image) {
                        echo "<div class='splide__slide'>
<img data-splide-lazy='../../uploaded_files/$product_image'>
</div>";
                    }
                }
                echo "
</div>
</div>
<div class='wishlist-btn-container' data-p_id='$product_id'>
<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='#000000'>
    <path d='M0 0h24v24H0V0z' fill='none' />
    <path id='wishlist-btn-color' 
        d='M16.5 5c-1.54 0-3.04.99-3.56 2.36h-1.87C10.54 5.99 9.04 5 7.5 5 5.5 5 4 6.5 4 8.5c0 2.89 3.14 5.74 7.9 10.05l.1.1.1-.1C16.86 14.24 20 11.39 20 8.5c0-2-1.5-3.5-3.5-3.5z'
        opacity='1' fill='none'/>
    <path
        d='M16.5 3c-1.74 0-3.41.81-4.5 2.09C10.91 3.81 9.24 3 7.5 3 4.42 3 2 5.42 2 8.5c0 3.78 3.4 6.86 8.55 11.54L12 21.35l1.45-1.32C18.6 15.36 22 12.28 22 8.5 22 5.42 19.58 3 16.5 3zm-4.4 15.55l-.1.1-.1-.1C7.14 14.24 4 11.39 4 8.5 4 6.5 5.5 5 7.5 5c1.54 0 3.04.99 3.57 2.36h1.87C13.46 5.99 14.96 5 16.5 5c2 0 3.5 1.5 3.5 3.5 0 2.89-3.14 5.74-7.9 10.05z' fill='#ff0047ed'/>
</svg>
</div></div>
<div class='product_info'>
<div class='product-name'>
$product_name
</div>
<div class='product-price'>
Rs.$formated_price
</div>";
                if ($product_size !== '') {
                    echo "<div class='size_option-container'>Sizes : ";
                    foreach (explode(',', $product_size) as $size) {
                        if (isset($size) && $size !== '') {
                            echo "<span class='size-option' data-size='$size'>" . strtoupper("$size") . "</span>";
                        }
                    };
                    echo "</div>";
                }
                if ($product_color !== '') {
                    echo "<div class='color_option-container'>Colors : ";
                    foreach (explode(',', $product_color) as $color) {
                        if (isset($color) && $color !== '') {
                            echo "<span class='color-option' style='background-color:$color;' data-color='$color' ></span>";
                        };
                    };
                    echo "</div>";
                };
                echo "<div class='size_option-container'>Any specification : <input type='text' data-msg='' class='specific_msg' id='specific-msg-bar' placeholder='any specific message to seller'  autocomplete='off'></div>";
                echo "<div class='show_prd_details'>More</div>";
                echo "<div class='product-description'>
Features
<ul>";
                foreach ($product_description_list as $product_description) {
                    if ($product_description) {
                        echo "<li>$product_description</li>";
                    };
                };
                echo "
</ul>
</div>
<div class='seller-section'>
<div>
Seller :-
</div>
<a href='./shop.php?c_id=$seller_id' class='store-info-container'>
<div class='store-img'>
<img src='../uploaded_files/$store_img' alt='img'>
</div>
<div class='store-info'>
<div class='store_name'>
$product_publisher_name
</div>
<div class='store_location'>
$store_location
</div>
</div>
</a></div>";
                if ($status !== "OPEN") {
                    echo "<label style='color:red;padding-bottom:7px;' class='store-detail'>
*Store is CLOSED. Order will be done as soon as it OPENS.
</label>";
                };
                if ($product_availablity == "Available") {
                    echo "
    <div class='product_btn-container'>
    <span class='add-to-cart-btn'id='add_to_cart' data-add_item='$seller_id:$product_id'>Add To Cart</span>
    <span class='order-btn' onclick=document.getElementById('order_reciept-container-modal').style.display='block'>ORDER</span>
    </div>";
                } else {
                    echo "
    <div class='product_btn-container'>
    <span class='not-available-btn'>$product_availablity</span>
    </div>";
                }
                echo "</div></div><script>let slide_var_mfs,slide_var_rp;</script>";
                $mfs_query = "SELECT product_id,product_name,product_price,product_image,product_availability FROM product_storage WHERE product_id != '$product_id' AND seller_id = '$seller_id' AND  product_category != '$product_category'  ORDER BY product_id DESC  LIMIT 0,20";
                $mfs_sql = mysqli_query($product_connection, $mfs_query);
                if ($mfs_sql->num_rows !== 0) {
                    echo "<div class='more-from-store_container'>
<div class='section-heading'>
More From Store
</div>
<div class='splide' id='more-store-product-overview'>
<div class='splide__track'>
<ul class='splide__list'>";
                    while ($rows = mysqli_fetch_assoc($mfs_sql)) {
                        $mfs_product_name = $rows['product_name'];
                        $mfs_product_id = $rows['product_id'];
                        $mfs_product_image = explode(' ', $rows['product_image']);
                        $mfs_product_availablity = $rows['product_availability'];
                        $mfs_product_price = $rows['product_price'];
                        $mfs_formated_price = preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $mfs_product_price);
                        echo "
<li class='splide__slide'><a href='./product.php?i=$mfs_product_id' class='card'><div class='card-img_container'><img data-splide-lazy='../uploaded_files/$mfs_product_image[0]' height='100%' alt='Denim Jeans' style='width:100%;object-fit: contain; '></div><div class='card-name'><p>$mfs_product_name</p></div><div class='card-price'><p class='price'>Rs.$mfs_formated_price</p></div></a></li>";
                    };
                    echo "</ul>
</div>
</div>
</div><script>slide_var_mfs=1;</script>";
                };
                $rlt_query = "SELECT product_id,product_name,product_price,product_image,product_availability
FROM product_storage WHERE product_category = '$product_category' ORDER BY product_id DESC LIMIT 0,20";
                $rlt_sql = mysqli_query($product_connection, $rlt_query);
                if ($rlt_sql->num_rows !== 0) {
                    echo "
<div class='related-product_container'>
 <div class='section-heading'>
 From This Category
 </div>
 <div class='splide' id='related-product-overview-slider'>
 <div class='splide__track'>
 <ul class='splide__list'>";
                    while ($rows = mysqli_fetch_assoc($rlt_sql)) {
                        $rlt_product_name = $rows['product_name'];
                        $rlt_product_id = $rows['product_id'];
                        $rlt_product_image = explode(' ', $rows['product_image']);
                        $rlt_product_availablity = $rows['product_availability'];
                        $rlt_product_price = $rows['product_price'];
                        $rlt_formated_price = preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $rlt_product_price);
                        echo "
 <li class='splide__slide'>
 <a href='./product.php?i=$rlt_product_id' class='card'>
 <div class='card-img_container'>
 <img data-splide-lazy='../uploaded_files/$rlt_product_image[0]' height='100%' alt='img' style='width:100%;object-fit: contain; '>
 </div>
 <div class='card-name'>
 <p>$rlt_product_name</p>
 </div>
 <div class='card-price'>
 <p class='price'>Rs.$rlt_formated_price</p>
 </div>
 </a>
 </li>";
                    };
                    echo "
 </ul>
 </div>
 </div>
 </div><script>slide_var_rp=1;</script>";
                };
                echo "
<div id='order_reciept-container-modal' class='order_reciept-container-modal'>
<div class='order_reciept-container animate_reciept-container'>
<div class='reciept-heading'>Order Confirmation</div>
<div class='reciept_product-container'>
<div class='reciept-product-img_container'>
<img class='reciept_product_img' src='../../uploaded_files/$product_image_arr[0]'
loading='lazy'>
</div>
<div class='reciept_product-details-list '>
<p class='reciept_product-detail'>$product_name</p>
<p class='reciept_product-detail price'>Rs.$formated_price</p>
</div>
</div>
<div class='seller-info'>
<span>
Your Details :-
</span>
<ul>
";
                if (isset($_SESSION['user_id'])) {
                    echo "<li style='list-style-type: none;'>$_SESSION[user_name]</li>
<li style='list-style-type: none;'>$_SESSION[user_address]</li>";
                } else {
                    echo "<li style='list-style-type: none;'><a href='https://halka.in?profile'>Login/Register</a> First to Order it.</li>";
                }
                echo "
</ul>
</div>
<div class='seller-info'>
<span>
Buying From :-
</span>
<ul>
<li style='list-style-type: none;'>$product_publisher_name</li>
<li style='list-style-type: none;'>$store_location</li>
</ul></div>
<div class='highlights'>
<span>
Highlights :-
</span>
<ul>
<li>Return within the same Day of Order Delivered</li>
<li>Cash on Delivery only</li>
<li>Easy Returns</li>
</ul>
<div class='delivery-price'>";
                if ($product_price > 200) {
                    echo "Delivery Cost :- Only Tip as much you like.";
                } else {
                    echo "Delivery Cost :- Rs.10";
                };
                echo "</div></div>
<div class='order-decision-btn-container'>
<div class='total-price'>
<span>
Total :- 
</span>
<span>
Rs.$formated_price";
                if ($product_price < 200) {
                    echo "+ Rs.10";
                };
                echo "
</span>
</div>
<div id='order_btn' class='reciept-order-btn' data-p_name='${product_name}' data-order_str='$seller_id:$product_id'>
ORDER
</div>
</div>
</div>
</div>";
            };
        };
        ?>
    </main>
</body>

</html>