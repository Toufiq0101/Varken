<?php
// require_once "./db_connection.php";
// session_start();
?>
<!-- 23.83021, 86.522255 -->
<?php
// if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_lat']) || $_SESSION['user_lat']==='' || $_SESSION['user_long']==='' ) {
//     $_SESSION['user_lat'] =23.83021;
//     $_SESSION['user_long'] = 86.522255;
//     $user_latitude =floatval(23.83021);
//     $user_longitude = floatval(86.522255);
//     echo 0;
// }else{
//     $user_latitude =floatval($_SESSION['user_lat']);
//     $user_longitude = floatval($_SESSION['user_long']);
//     echo $user_latitude,$user_longitude;
// };
// $market = array();
// $services = array();
// //Must be in KiloMetre unit
// $working_radius = 1;
// $client_query = "SELECT client_id,latitude,longitude,seller_type FROM client_storage WHERE 0>(POW((latitude - $user_latitude),2) + POW((longitude - $user_longitude) , 2) - $working_radius)";
// $client_sql = mysqli_query($client_connection,$client_query);
// while ($rows=mysqli_fetch_assoc($client_sql)) {
//     $client_id = $rows['client_id'];
//     ($rows['seller_type'])==='Services'?array_push($services,$client_id):array_push($market,$client_id);
// };
// $_SESSION['market'] = $market;
// $_SESSION['services'] = $services;
?>