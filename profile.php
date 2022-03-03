<?php
include "./db_connection.php";
session_start();
?>
<div class='dark_mode-container'>
    <div class='dark_mode-label'>Dark Mode</div>
    <div class='dark_mode-switch'>
        <label class="toggle-switch">
            <input type="checkbox" id="toggle-switch-input">
            <span class="toogle_slider round-switch"></span>
        </label>
    </div>
</div>
<?php
if (isset($_SESSION['user_id'])) {
    $sql = mysqli_query($user_connection, "SELECT user_name,phone_number,user_address,latitude,longitude FROM user_storage WHERE user_id=$_SESSION[user_id]");
    if ($sql->num_rows !== 0) {
        while ($row = mysqli_fetch_assoc($sql)) {
            $user_name = $row['user_name'];
            $user_address = $row['user_address'];
            $user_lat = $row['latitude'];
            $user_lng = $row['longitude'];
            $user_ph_no = $row['phone_number'];
        }; ?>
        <div class="profile-form_container" id="login-form-container">
            <div style="font-size: 17px;font-weight:bold;">Profile</div>
            <form id="profile_form" class="profile-form">
                <div class="input-field_container">
                    <label for="user_name">Username</label>
                    <input type="text" placeholder="Your Name..." value="<?php echo $user_name; ?>" name="user_name" required>
                </div>
                <div class="input-field_container">
                    <label for="phone_number">Mobile Number</label>
                    <input type="number" placeholder="Your Phone Number" name="user_phone_number" value="<?php echo $user_ph_no; ?>" id="user_phone_number" required>
                </div>
                <div class="input-field_container">
                    <label for="address">Address</label>
                    <input name="user_address" type="text" value="<?php echo $user_address; ?>" placeholder="Address..." required>
                </div>
                <div class="input-field_container">
                    <label for="password">Password</label>
                    <input type="password" placeholder="Choose a strong password" name="user_password" required>
                </div>
                <div class="input-field_container">
                    <label for="user_coords">District</label>
                    <input id="center-point-input" list='center-points-list' placeholder='Nearest Area' autocomplete="off" required>
                    <datalist id='center-points-list'>
                        <option data-coords="23.788117809722195,86.41868225381391" value='Dhanbad'>
                        <option data-coords="23.788117809722195,86.41868225381391" value='Dhumka'>
                    </datalist>
                    <input style="display:none;" type="text" name="user_coords" id="user_coords" value="<?php echo "$user_lat,$user_lng"; ?>" autocomplete="off" required>
                </div>
                <button class="profile-submit-btn loadable-btn" type="submit" name="submit_user_data">Save</button>
                <button class="logout-btn" id="logout-btn">Logout</button>
            </form>
        </div>
    <?php
    }
} else {
    ?>
    <span style="font-size: 18px;font-weight: bold;">You Haven't Loggedin Yet..!</span>
    <div class='login_rgst_btn-container'>
        <span class='profile-login-tab'>LOGIN</span>
        <span class='profile-rgst-tab'>REGISTER</span>
    </div>
    <div class="profile-form_container" id="login-form-container">
        <div style="font-size: 20px; padding: 10px; text-align: center;">Login</div>
        <form method="post" id="profile-form" class='profile-form'>
            <div class="input-field_container">
                <label for="phone_number">Phone Number</label>
                <input type="number" name="phone_number" placeholder='Phone Number' required>
            </div>
            <div class="input-field_container">
                <label for="password">Password</label>
                <input type="password" name="password" placeholder='Password' required>
            </div>
            <button class="profile-form-btn loadable-btn" type="submit" name="login">LOGIN</button>
        </form>
    </div>
    <div class="profile-form_container hidden-form" id="register-form-container">
        <div style="font-size: 20px; padding: 10px; text-align: center;">Register</div>
        <form method="post" id="register-form" class='profile-form'>
            <div class="input-field_container">
                <label for="user_name">Username</label>
                <input type="text" placeholder="Your Name..." name="user_name" required>
            </div>
            <div class="input-field_container">
                <label for="phone_number">Mobile Number</label>
                <input type="number" placeholder="Your Phone Number" name="user_phone_number" id="user_phone_number" required autocomplete="off">
            </div>
            <div class="input-field_container">
                <label for="address">Address</label>
                <input name="user_address" type="text" placeholder="Address..." required>
            </div>
            <div class="input-field_container">
                <label for="password">Password</label>
                <input type="password" placeholder="Choose a strong password" name="user_password" required autocomplete="off">
            </div>
            <div class="input-field_container">
                <label for="user_coords">District</label>
                <input id="center-point-input" list='center-points-list' placeholder='Your Area' autocomplete="off" required>
                <datalist id='center-points-list'>
                    <option data-coords="23.788117809722195,86.41868225381391" value='Dhanbad'>
                    <option data-coords="23.788117809722195,86.41868225381391" value='Dumka'>
                </datalist>
                <input style="display:none;" type="text" name="user_coords" id="user_coords" required autocomplete="off">
            </div>
            <button id='register-btn' class="profile-form-btn loadable-btn" type="submit" name="submit_user_data">REGISTER</button>
        </form>
    </div>
<?php
}
?>
<script>
    if (decodeURIComponent(document.cookie).includes('u_theme') && decodeURIComponent(document.cookie).includes('dark_mode_on')) {
        document.getElementById('toggle-switch-input').setAttribute('checked', true);
    };
    $("#center-point-input").on("input", function() {
        var coords = $('#center-points-list [value="' + $(this).val() + '"]').data('coords');
        var dist = $('#center-point-input').val();
        document.querySelector("#user_coords").value = `${coords},${dist}`;
    });
</script>