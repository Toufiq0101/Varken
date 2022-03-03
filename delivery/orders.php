<?php
include "./database_connection.php";
session_start();
?>

<?php
echo $_SESSION['courier_id'];
if (isset($_SESSION['courier_id'])) {
    if (isset($_GET['store'])) {
        $store_query = "SELECT owner_name,store_name,phone_number,store_location,client_image,client_id,";
        $store_query .= (isset($_GET['rtrn_rqst']))?"return_requests FROM client_storage WHERE return_requests != '' ":"pending_orders FROM client_storage WHERE pending_orders != '' ";
        $store_sql = mysqli_query($client_connection, $store_query);
        echo $store_sql->num_rows;
        while ($rows = mysqli_fetch_assoc($store_sql)) {
            $store_name = $rows['store_name'];
            $owner_name = $rows['owner_name'];
            $ph_num = $rows['phone_number'];
            $store_location = $rows['store_location'];
            $store_image = $rows['client_image'];
            $client_id = $rows['client_id'];
            echo isset($_GET['rtrn_rqst'])?"<div data-c_id='$client_id' id='store_rtrn_rqst'>":"<div data-c_id='$client_id' id='store_deliver_order'>";
            if (isset($client_id)) {
                echo"
                <div class='product_overview_container'>
                <div class='product_overview'>
                <div class='product_image'>
                    <img class='product-image' src='../uploaded_files/$store_image' alt='$store_name' loading='lazy'>
                </div>
                <div  class='product_details'>
                    <span class='product-name'>$store_name</span>
                    <span class='product-price'>Ph: $ph_num</span>
                    <span class='product-date'>Addr : $store_location</span>
                    <span>by:- $owner_name</span>
                </div></div></div>";
            };
        };
    };
}else{
    echo "LOGIN FIRST";
};
?>