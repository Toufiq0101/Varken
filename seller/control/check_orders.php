<?php
session_start();
include_once "../database_connection.php";
if(isset($_SESSION['client_id'])){
    $sql = mysqli_query($client_connection, "SELECT present_orders FROM client_storage WHERE client_id = $_SESSION[client_id]");
    if($sql->num_rows!==0){
        $row = mysqli_fetch_assoc($sql);
        echo count(explode(';',$row['present_orders']));
    }else{
        echo 401;
    };
};

?>