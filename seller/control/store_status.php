<?php include "../database_connection.php";session_start();if(isset($_SESSION['client_id'])){if(isset($_GET['close'])){echo 'close';mysqli_query($client_connection, "UPDATE client_storage SET store_status='CLOSE' WHERE client_id = $_SESSION[client_id]");}elseif(isset($_GET['open'])){ mysqli_query($client_connection, "UPDATE client_storage SET store_status='OPEN' WHERE client_id = $_SESSION[client_id]");echo 'open';}else{echo 901;};};?>