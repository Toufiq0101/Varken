<style>
.hidden_form {
display: none;
}
</style>
<div>Start</div>

<?php
session_start();
if (isset($_SESSION['courier_id'])) {
include "./database_connection.php";
$query = "SELECT courier_name,password,phone_number,courier_image,address FROM courier WHERE courier_id = '$_SESSION[courier_id]'";
while($row = mysqli_fetch_assoc(mysqli_query($user_connection, $query))){
$courier_name = $row["courier_name"];
$password = $row['password'];
$phone_number = $row['phone_number'];
$courier_img = $row['courier_image'];
$address = $row['address'];
};
}else{
echo "<h1>Login or Register to Start your Wishmaster Job now </h1>
<button onclick='login()'>LOGIN</button>
<button onclick='register()'>REGISTER</button>";
};
?>
<form action="./ctrl_rgst_login.php" method="post" id="register_form">
<?php
if(isset($_SESSION['courier_id'])){
    echo "<img src='../uploaded_files/$courier_img' alt=''>";
}
?>
<label for="user_name">User Name</label>
<input type="text" name="user_name" value="<?php echo (isset($_SESSION['courier_id']))?$row['courier_name']:""; ?>" reequired>

<label for="phone_number">Phone Number</label>
<input type="number" name="phone_number" value="<?php echo (isset($_SESSION['courier_id']))?$row['phone_number']:""; ?>" required>

<label for="password">Password</label>
<input type="password" name="password" value="<?php echo (isset($_SESSION['courier_id']))?$row['password']:""; ?>" required>

<label for="Address">Address</label>
<input type="text" name="address" value="<?php echo (isset($_SESSION['courier_id']))?$row['address']:""; ?>">

<label for="image">Profile Image</label>
<input type="file" name="profile_img" <?php echo (!isset($_SESSION['courier_id']))?"required":""; ?> >

<div class="input_field-container">
<label for="d_user_coords">Your Coord</label>
<input type="text" id="center-point-input" list='center-points-list' placeholder='Your Area' autocomplete="off" required>
<input type="text" id="d_user_coords" name="d_user_coords" required autocomplete="off" required>
<datalist id='center-points-list'>
<option data-coords="23.788117809722195,86.41868225381391" value='Bank More'>
</datalist>
</div>
<button type="submit">SUBMIT</button>
</form>

<form action="./ctrl_rgst_login.php" id="login_form" class="hidden_form">
<label for="phone_number">Phone Number</label>
<input type="number" name="phone_number" autocomplete="on">

<label for="password">Password</label>
<input type="password" name="password"><button type="submit">LOGIN</button>
</form>

<button id="logout">Logout</button>
<script>
function login() {
document.querySelector("#login_form").classList.remove("hidden_form");
document.querySelector("#register_form").classList.add("hidden_form");
}
function register() {
document.querySelector("#login_form").classList.add("hidden_form");
document.querySelector("#register_form").classList.remove("hidden_form");
}
$("#center-point-input").on("input", function () {
var coords = $('#center-points-list [value="' + $(this).val() + '"]').data('coords');
document.querySelector("#d_user_coords").value = coords;
});
$("#register_form").on("submit", function (e) {
e.preventDefault();
if ($("#d_user_coords").val !== undefined) {
const formdata = new FormData(this);
$.ajax({
url: "./d_account_ctrl.php?register",
data: formdata,
type: "POST",
contentType: false,
processData: false,
success: function (data) {
(Number(data)===0)?alert("User already registered"):
(Number(data)===9)?alert("Image too big or not supported"):
alert("Registered Successfully");
}
});
};
});
$("#login_form").on("submit", function (e) {
e.preventDefault();
const formdata = new FormData(this);
$.ajax({
url: "./d_account_ctrl.php?login",
data: formdata,
type: "POST",
contentType: false,
processData: false,
success: function (data) {
console.log(data);
if (Number(data) === 1) {
// window.location.replace("./index.php");
}else{
const err_markup = (Number(data)===0)?"User not Registered with thisb Phone Number":
(Number(data)===405)?"Username or Password may be incorrect":"Something went Wrong..Refresh Page";
};
}
});
});
$(document).on("click","#logout",function(){
$.ajax({
url:"./d_account_ctrl.php?logout",
type:"get",
success:function(data){
console.log(data);
}
})
})
</script>