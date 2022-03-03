<?php
include "./db_connection.php";
require __DIR__ . '/vendor/autoload.php';

session_start();
if (isset($_POST['offset'])) {
    $offset = $_POST['offset'];
} else {
    $offset = 0;
};
if (isset($_SESSION['user_id'])) {
    $user_latitude = 23.83021;
    $user_longitude =  86.522255;
} else {
    $user_latitude = 23.83021;
    $user_longitude =  86.522255;
    if (!isset($_GET['fav_store'])) {
        echo $offset === 0 ? "<script>snackbar('Using Default Location');</script>" : "";
    }
};
$working_radius = 5;
if (isset($_GET['fav_store'])) {
    echo "<div class='tab-heading'>Favrouit Store</div>";
    $query = "SELECT fav_store FROM user_storage WHERE user_id = '$_SESSION[user_id]'";
    $sql = mysqli_query($user_connection, $query);
    while ($row = mysqli_fetch_assoc($sql)) {
        $fav_store_arr = explode(',', $row['fav_store']);
        if ($row['fav_store'] === '') {
            echo "
<div class='no_content_err-container'>
<img src='./css/svg/error.svg' alt='' class='no_content_err-img' loading='lazy'>
<span class='no_content_err-msg'>You haven't marked any store..!!<br><span style='font-size:x-small'>Do you have any disappointment from any store..<a href='https://wa.me/qr/5DJPSM4CB47GN1' style='text-decoration: underline;'>Report now</a></span><span>
</div>";
            die();
        };
        foreach ($fav_store_arr as $c_id) {
            if ($c_id) {
                $query_c_id = "SELECT store_name,client_image,store_location,store_description FROM client_storage WHERE client_id = $c_id";
                $sql_c_id = mysqli_query($client_connection, $query_c_id);
                if ($sql_c_id) {
                    while ($rows = mysqli_fetch_assoc($sql_c_id)) {
                        $store_name = $rows['store_name'];
                        $store_image = $rows['client_image'];
                        $store_location = $rows['store_location'];
                        $store_description = $rows['store_description'];
                        echo "
<div class='main-overview-container'>
<a href='./shop.php?c_id=$c_id' class='overview_container' data-client_id='$c_id'>
<div class='overview-img_container'>
<img class='overview-img' src='../uploaded_files/$store_image' loading='lazy'>
</div>
<div  class='overview-details-list'>
<span class='overview-detail name'>$store_name</span>
<span class='overview-detail location'>$store_location</span>
<span class='overview-detail description'>$store_description</span>
</div>
</a>
</div>";
                    };
                };
            };
        };
    };
} elseif (isset($_GET['service_market'])) {
    if ($offset === 0) {
        echo "<div class='tab-heading'>Service Providers</div>";
    }
    $query = "SELECT client_image , store_name , store_location , store_description ,client_id FROM client_storage WHERE seller_type='Services' LIMIT $offset,10";
    $sql = mysqli_query($client_connection, $query);
    if ($sql->num_rows === 0 && $offset === 0) {
        echo "
<div class='no_content_err-container'>
<img src='./css/svg/error.svg' alt='' class='no_content_err-img'>
<span class='no_content_err-msg'>No Service Provider Found near you.<a href='https://wa.me/qr/5DJPSM4CB47GN1' style='text-decoration: underline;'>Let Us Know</a></span><span>
</div>";
        die();
    } else {
        while ($rows = mysqli_fetch_assoc($sql)) {
            $store_name = $rows['store_name'];
            $store_image = $rows['client_image'];
            $store_location = $rows['store_location'];
            $store_description = $rows['store_description'];
            $client_id = $rows['client_id'];
            echo "
<div class='main-overview-container'>
<a href='./shop.php?c_id=$client_id' class='overview_container' data-client_id='$client_id'>
<div class='overview-img_container'>
<img class='overview-img' src='../uploaded_files/$store_image' loading='lazy'>
</div>
<div  class='overview-details-list'>
<span class='overview-detail name'>$store_name</span>
<span class='overview-detail location'>$store_location</span>
<span class='overview-detail description'>$store_description</span>
</div>
</a>
</div>";
        };
    };
} else {
    if ($offset === 0) {
        echo "<div class='tab-heading'>Market</div>";
    }
    $client = Algolia\AlgoliaSearch\SearchClient::create(
        '2BNKFRXSL7',
        '3f16cfd9712309205322ece7ae637084'
    );

    $index = $client->initIndex('varken_seller');
    $hits = $index->search('', [
        'filters' => 'loc_district:Dumka',
        'aroundLatLng' => '23.374802095551612, 85.33998759005773'
    ]);
    foreach ($hits['hits'] as $value) {
        echo "
        <div class='main-overview-container'>
    <a href='./shop.php?c_id=$value[client_id]' class='overview_container' data-client_id='$value[client_id]'>
    <div class='overview-img_container'>
    <img class='overview-img' src='../uploaded_files/$value[store_image]' loading='lazy'>
    </div>
    <div  class='overview-details-list'>
    <span class='overview-detail name'>".ucwords($value['store_name'])."</span>
    <span class='overview-detail location'>$value[store_location]</span>
    <span class='overview-detail description'>" . ((sqrt(pow($value['_geoloc']['lat'] - $_SESSION['user_lat'], 2) + pow($value['_geoloc']['lng'] - $_SESSION['user_lng'], 2)))) . "Hrs Apprx.</span>
    </div>
    </a>
    </div>";
    };
    // echo $results;
    //     $query = "SELECT client_image , store_name , store_location , store_description,client_id FROM client_storage WHERE 0>=(POW((latitude - $user_latitude),2) + POW((longitude - $user_longitude) , 2) - $working_radius) AND seller_type='Products' LIMIT $offset,10";
    //     $sql = mysqli_query($client_connection, $query);
    //     if ($sql->num_rows === 0 && $offset === 0) {
    //         echo "
    // <div class='no_content_err-container'>
    // <img src='./css/svg/error.svg' alt='' class='no_content_err-img'>
    // <span class='no_content_err-msg'>No Store Found near you...<a href='https://wa.me/qr/5DJPSM4CB47GN1' style='text-decoration: underline;'>Let us Know</a></span><span>
    // </div>";
    //         die();
    //     } else {
    //         while ($rows = mysqli_fetch_assoc($sql)) {
    //             $store_name = $rows['store_name'];
    //             $store_image = $rows['client_image'];
    //             $store_location = $rows['store_location'];
    //             $store_description = $rows['store_description'];
    //             $client_id = $rows['client_id'];
    //             echo "
    // <div class='main-overview-container'>
    // <a href='./shop.php?c_id=$client_id' class='overview_container' data-client_id='$client_id'>
    // <div class='overview-img_container'>
    // <img class='overview-img' src='../uploaded_files/$store_image' loading='lazy'>
    // </div>
    // <div  class='overview-details-list'>
    // <span class='overview-detail name'>$store_name</span>
    // <span class='overview-detail location'>$store_location</span>
    // <span class='overview-detail description'>$store_description</span>
    // </div>
    // </a>
    // </div>";
    //         };
    //     }
};