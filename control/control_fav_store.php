<?php include "../db_connection.php";
session_start();
?>

<?php
$get_query = "SELECT fav_store FROM user_storage WHERE user_id = $_SESSION[user_id]";
$get_sql = mysqli_query($user_connection,$get_query);
while ($row = mysqli_fetch_assoc($get_sql)) {
$fav_store_str = $row['fav_store'];
if (isset($_POST['fav_store_check'])) {
// $fav_store_check_pos = strpos($fav_store_str, $_POST['fav_store_check']);
if (in_array($_POST['fav_store_check'],explode(',',$fav_store_str))) {
echo 1;
}else{
echo 0;
};
};
if(isset($_GET['fav_store'])){
$fav_store_id = $_POST['fav_store_id'];
$fav_store_array = explode(',',$fav_store_str);
$fav_store_str_pos = in_array($fav_store_id,$fav_store_array);
if (!$fav_store_str_pos && $fav_store_str_pos !== 0){
$fav_store_str .= "$fav_store_id,";
$upd_query = "UPDATE user_storage SET fav_store='$fav_store_str' WHERE user_id = $_SESSION[user_id]";
$upd_sql = mysqli_query($user_connection, $upd_query);
echo 1;
}elseif(isset($fav_store_str_pos)) {
if(strpos($fav_store_str, "$fav_store_id,")===0){
    $fav_store_array = substr_replace($fav_store_str,'',strpos($fav_store_str, "$fav_store_id,"),strlen($fav_store_id)+1);
}elseif(strpos($fav_store_str,",$fav_store_id,")>0){
    $fav_store_array = substr_replace($fav_store_str,'',strpos($fav_store_str, ",$fav_store_id,"),strlen($fav_store_id)+1);
}
$upd_query = "UPDATE user_storage SET fav_store='$fav_store_array' WHERE user_id = $_SESSION[user_id]";
$upd_sql = mysqli_query($user_connection, $upd_query);
echo 0;
}
}
};
?>