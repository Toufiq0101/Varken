<?php include "./db_connection.php"; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="./web_files/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/shop_body.css">
    <script type="text/javascript" src="../transmitter.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/algoliasearch@4/dist/algoliasearch-lite.umd.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/instantsearch.js@4" defer></script>
    <script src="./shop.js" defer></script>
    <script src="./quick_modal.js" defer></script>
    <script src="./quickii_order.js" defer></script>
    <!-- <script src="./search_engine.js" defer></script> -->
    <title>Varken | Shop</title>
</head>

<body>
    <header class="header">
        <div class="header-1 left-header-1">
            <!-- <img src="./web_files/halka.webp" class="company-logo" draggable="false" /><span style="font-size: x-small; color: rgb(121, 121, 121)" alt="logo">
                beta</span> -->
        </div>
        <a class="right-header-1" href="/?profile">
            <img src="./css/svg/profile.svg" class="profile-btn" id="profile-btn" alt="profile" draggable="false" />
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
                    <img src="./css/svg/wishlist.svg" alt= "wishlist" class="tab-btn" draggable="false" /><span>Wishlist</span>
                </a>
            </nav>
        </div>
    </div>
    <div id='order-alert'></div>
    <div class="quckii-modal-modal-container">
        <div id="quickii_modal-container"></div>
    </div>
    <main>
        <?php
        if (isset($_GET['c_id'])) {
            $c_id = $_GET['c_id'];
            $store_query = "SELECT store_name,owner_name,store_image,client_image,store_location,store_description,phone_number,seller_type,store_status FROM client_storage WHERE client_id = $c_id";
            $store_sql = mysqli_query($client_connection, $store_query);
            if ($store_sql->num_rows) {
                while ($row = mysqli_fetch_assoc($store_sql)) {
                    $store_name = ucwords($row['store_name']);
                    $owner_name = ucwords($row['owner_name']);
                    $store_image = $row['store_image'];
                    $client_image = $row['client_image'];
                    // Must replace with image of seller not seller's store
                    $store_location = $row['store_location'];
                    $store_description = $row['store_description'];
                    $store_contact = $row['phone_number'];
                    $seller_type = $row['seller_type'];
                    $status = $row['store_status'];
                    echo "
                <div class='store_info-container'>
                <div class='store_img-container'>
                <img class='store-image' src='./uploaded_files/$store_image' alt=''>
                <img class='seller-image' src='./uploaded_files/$client_image' alt=''>
                <div class='str_special_tags'><span class='verified_shop-sign'>✔verified</span></div>
                </div>
                <div class='store_details-container'>
                <div class='store_detail-level1'>
                <div class='store_name store-detail'>$store_name</div><div class='store_dtl-content owner_name'>$owner_name</div></div>
                <div class='store_detail-level2'>";
                    // "<div class='fav-store-btn' data-c_id='$c_id' id='fav-store-btn-container'>
                    // <svg xmlns='http://www.w3.org/2000/svg' x='0px' y='0px' viewBox='0 0 172 172'
                    // style=' fill:#000000;'>
                    // <g fill='none' fill-rule='nonzero' stroke='none' stroke-width='1' stroke-linecap='butt' stroke-linejoin='miter'
                    // stroke-miterlimit='10' stroke-dasharray=' stroke-dashoffset='0' font-family='none' font-weight='none'
                    // font-size='none' text-anchor='none' style='mix-blend-mode: normal'>
                    // <path d='M0,172v-172h172v172z' fill='none'></path>
                    // <path id='fav-store-btn-circle' fill='#1c1c1c'
                    // d='M86,172c-47.49649,0 -86,-38.50351 -86,-86v0c0,-47.49649 38.50351,-86 86,-86v0c47.49649,0 86,38.50351 86,86v0c0,47.49649 -38.50351,86 -86,86z'>
                    // </path>
                    // <g id='fav-store-btn' fill='#ffffff'>
                    // <path
                    // d='M86,146.89069l-1.70067,-1.41031c-3.22505,-2.69618 -7.5908,-5.62051 -12.65133,-9.00111c-19.71326,-13.20093 -46.70622,-31.26537 -46.70622,-62.0641c0,-19.02884 15.48232,-34.51117 34.51117,-34.51117c10.33883,0 20.02436,4.60425 26.54705,12.47504c6.52269,-7.87079 16.20822,-12.47504 26.54705,-12.47504c19.02884,0 34.51117,15.48232 34.51117,34.51117c0,30.79873 -26.99296,48.86316 -46.70622,62.0641c-5.06053,3.3806 -9.42628,6.30492 -12.65133,9.00111z'>
                    // </path>
                    // </g>
                    // </g>
                    // </svg>
                    // </div>";
                    if ($seller_type === "Services") {
                        echo "<span id='hire_me-btn' class='hire-me-btn' data-c_id='$c_id'>HIRE ME</span>";
                    } else {
                        echo "<div class='image_order_form-label'>
                    Pictorial Order
                    </div><form id='img_order-form' method='post'>";
                        if ($status !== 'OPEN') {
                            echo "<label class='store-detail' style='color:red'>
                        *Store is CLOSED.Order will be done as soon as its Opens
                        </label>";
                        }
                        echo "
                    <div class='image_order-container'>
                    <input type='file' name='order_image[]' id='img_order-btn' multiple>
                    <img class='file-upload-btn' src='./css/svg/camera.svg' alt=''>
                    <input type='submit' class='img-upload-submit-btn' value='ORDER'>
                    </div>
                    <div style='color:#616060;font-size:smaller;'><span>*upload list of items</span><span id='more_details-btn' class='more_details-btn'>±more</span></div>
                    </form>
                    </div>";
                    };
                    echo "<div class='store_detail-level3 some_more_store_dtls no_display'>
                    <div class='store_ftl3-title'>Description</div>
                    <div class='store_dtl3-block1'>
                        <div class='store_dtl3-flex-1'><span class='dtl3-flex-content'>4.5/5</span><br><span class='dtl3-flex-label'>User Rating</span></div>
                        <div class='store_dtl3-flex-1'><span class='dtl3-flex-content'>4562</span><br><span class='dtl3-flex-label'>Customers Served</span></div>
                        <div class='store_dtl3-flex-1'><span class='dtl3-flex-content'>Jan 1</span><br><span class='dtl3-flex-label'>2015</span></div>
                    </div>
                            <div class='store_dtl3-label'>About</div><div class='store_dtl3-content'>$store_description</div>
                            <div class='store_dtl3-label'>Contact</div><div class='store_dtl3-content'>$store_contact</div>
                            <div class='store_dtl3-label'>Address</div><div class='store_dtl3-content'>$store_location</div>
                            </div>";
                    echo "</div></div>";
                };
                if ($seller_type === 'Services') {
                    $service_query = "SELECT service_name,service_cost,service_id,average_time FROM service_storage WHERE service_provider_id=$c_id ORDER BY service_id DESC";
                    $service_sql = mysqli_query($product_connection, $service_query);
                    if ($service_sql->num_rows === 0) {
                        echo "<div>We will open the Services soon</div>";
                        die();
                    } else {
                        echo "
<div class='store_product-container-title'>Services</div>
<div class='store-services-container'>";
                        while ($rows = mysqli_fetch_assoc($service_sql)) {
                            $service_name = $rows['service_name'];
                            $service_cost = $rows['service_cost'];
                            $service_time = $rows['average_time'];
                            $service_id = $rows['service_id'];
                            $formated_price = preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $rows['service_cost']);
                            echo "
                        <label class='service_container service_container-checkbox'>
                        <div class='column-1'>
                        <div class='service_name'>$service_name </div>
                        <div class='service_time'><div style='font-size:20px;'>⏱️</div>$service_time min</div>
                        </div>
                        <div class='column-2'>
                        <div class='service_cost'>Rs. $formated_price</div>
                        </div>
                        <input type='checkbox' name='ord_service_id' value='$service_id'>
                        <span class='service_checkmark'></span>
                        </label>";
                        };
                        echo "</div>";
                    };
                } else {
                    $product_query = "SELECT product_name,product_price,product_image,product_id FROM product_storage WHERE seller_id = $c_id ORDER BY product_id  DESC";
                    $product_sql = mysqli_query($product_connection, $product_query);
                    echo "
<div class='store_product-container'>
<div class='str_prd_header-container'>
    <div class='str_prd_header-title-left'>Inside Store<div class='str_prd_num'>$product_sql->num_rows Products</div></div>
    <div class='str_prd_header-right'>
    <input class='inside_store-srch-bar' id='inside_store-srch-bar' type='text' placeholder='search inside store..'/>
    <img class='r-icon' id='inside_store-srch-btn' src='./css/svg/search-icon.svg' alt=''>
    </div>
    </div>";
                    // <img class='r-icon' src='./css/svg/filter.svg' alt=''>
                    if ($product_sql->num_rows === 0) {
                        echo "<div class='no_content_err-container'>
<img src='./css/svg/error.svg' alt='' class='no_content_err-img' loading='lazy'>
<span class='no_content_err-msg'>This Shop is Empty.<a href='https://wa.me/qr/5DJPSM4CB47GN1' style='text-decoration: underline;'>Let us Know</a></span>
</div></div>";
                        die();
                    } else {
                        echo "
<div class='l6-p-cntnr'>";
                        while ($rows = mysqli_fetch_assoc($product_sql)) {
                            $product_name = $rows['product_name'];
                            $product_price = $rows['product_price'];
                            $product_image = explode(' ', $rows['product_image']);
                            $product_id = $rows['product_id'];
                            $formated_price = preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $product_price);
                            echo "<div class='l6-p-card-cntnr'>
                            <a class='l6-p-card-p_link' href='/product.php?i=$product_id'>
                            <div class='l6-p-c-img'>
                            <img
                            src='/uploaded_files/$product_image[0]'
                            alt=''
                            />
                            <span class='prd-rating'>4.5</span>
                            </div>
                            <div class='l6-p-c-name'>$product_name</div>
                            <div class='l6-p-c-price'>Rs. $product_price</div>
                            </a>
                            <div class='l6-p-c-btn-cntnt'>
  <div class='l6-p-c-cart-btn'><span class='del_cart_item_btn' data-cart_str='$c_id:$product_id' >–</span><span class='prd_qnt'>0</span><span class='quicki_add_to_cart-btn' data-add_item='$c_id:$product_id'>+</span></div>
  <div class='l6-p-c-order-btn' onclick='open_quickii_modal(`$product_image[0]`,`$product_name`,`$product_price`,``,``,`$c_id`,`$product_id`)'><img src='/css/svg/quick_order_btn.svg'></div>
</div>
                            </div>";
                        };
                    };
                };
            };
        };
        echo "</div></div>";
        ?>
    </main>
</body>

</html>