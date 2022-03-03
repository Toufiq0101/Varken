<?php
session_start();
$_SESSION['test']='test1';
echo $_SESSION['test'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>DELIVERY</title>
<script src="../transmitter.js" ></script>
<link rel="stylesheet" href="./index.css">
</head>
<body>
<script>
$.ajax({
url: "./d_account_ctrl.php",
type: "POST",
success: function (data) {
console.log(data);
if (Number(data) === 1) {
}
}
});
</script>

<header>
<button id="orders">Orders</button>
<button id="rtrn_rqst">Return Requests</button>
<button id="courier_bag">Bag</button>
<button id="profile">profile</button>
</header>
<main>
<div id="dynamic_content"></div>
</main>
<script>
function orders(){
$.ajax({
url:"./orders.php?store&deliver_ord",
type:"GET",
success:function(data){
$("#dynamic_content").html(data);
}
})
}
$(document).on("click","#orders",function(){
orders();
})
orders();
$(document).on("click","#rtrn_rqst",function(){
$.ajax({
url:"./orders.php?store&rtrn_rqst",
type:"GET",
success:function(data){
$("#dynamic_content").html(data);
}
})
})
$(document).on("click","#store_deliver_order",function(){
const c_id = $(this).data("c_id");
$.ajax({
url:"./store_customer.php?deliver_ord",
data:{c_id:c_id},
type:"POST",
success:function(data){
$("#dynamic_content").html(data);
}
})
});
$(document).on("click","#store_rtrn_rqst",function(){
const c_id = $(this).data("c_id");
$.ajax({
url:"./store_customer.php?rtrn_rqst",
data:{c_id:c_id},
type:"POST",
success:function(data){
$("#dynamic_content").html(data);
}
})
});
//DELIVERy PICK UP
$(document).on("click","#dlvr_pick_up",function(){
const ord_str = $(this).data("ord_str");
$.ajax({
url:"./courier_func.php?dlvr_pick_up",
data:{ord_str:ord_str},
type:"POST",
success:function(data){
console.log(data);
}
})
})
//DELIVERED
$(document).on("click","#order_delivered",function(){
const ord_str = $(this).data("ord_str");
$.ajax({
url:"./courier_func.php?delivered",
data:{ord_str:ord_str},
type:"POST",
success:function(data){
console.log(data);
}
})
})
//RETURNED
$(document).on("click","#order_rtrn",function(){
const ord_str = $(this).data("ord_str");
$.ajax({
url:"./courier_func.php?returned",
data:{ord_str:ord_str},
type:"POST",
success:function(data){
console.log(data);
}
})
})
$(document).on("click","#rtrn_pick_up",function(){
const ord_str = $(this).data("ord_str");
$.ajax({
url:"./courier_func.php?rtrn_pick_up",
data:{ord_str:ord_str},
type:"POST",
success:function(data){
console.log(data);
}
})
})
//COURIER BAG
$(document).on("click","#courier_bag",function(){
$.ajax({
url:"./courier_bag.php",
type:"GETT",
success:function(data){
$("#dynamic_content").html(data);
}
})
});
$(document).on("click","#profile",function(){
$.ajax({
url:"./user.php",
type:"GET",
success:function(data){
$("#dynamic_content").html(data);
}
});
});
</script>
</body>
</html>