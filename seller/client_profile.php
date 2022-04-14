<?php include "./database_connection.php";
session_start(); ?><div class='dark_mode-container'>
    <div class='dark_mode-label'>Dark Mode</div>
    <div class='dark_mode-switch'><label class="toggle-switch"><input type="checkbox" id="toggle-switch-input"><span class="toogle_slider round-switch "></span></label></div>
</div><?php if (isset($_SESSION['client_id']) && $_SESSION['client_id'] !== '' && isset($_GET['edit'])) {
            $u_query = "SELECT owner_name,store_name,phone_number,client_image,store_image,store_location,store_description,seller_type,_geoloc,store_status FROM client_storage WHERE client_id = $_SESSION[client_id]";
            $send_u_query = mysqli_query($client_connection, $u_query);
            while ($rows = mysqli_fetch_assoc($send_u_query)) {
                $owner_name = $rows['owner_name'];
                $store_name = $rows['store_name'];
                $phone_number = $rows['phone_number'];
                $client_image = $rows['client_image'];
                $store_image = $rows['store_image'];
                $store_location = $rows['store_location'];
                $store_description = $rows['store_description'];
                $seller_type = $rows['seller_type'];
                $_geoloc = $rows['_geoloc'];
                $status = strtoupper($rows['store_status']);
            };
        };
        if (isset($_SESSION['client_id']) && $_SESSION['client_id'] !== '' && isset($_GET['edit']) && isset($owner_name)) { ?><div class='dark_mode-container'>
        <div class='dark_mode-label'>Store Status</div>
        <div class='dark_mode-switch'><label class="toggle-switch"><input type="checkbox" id="toggle-store_status"><span class="toogle_slider round-switch store_status-open"></span></label></div>
    </div>
    <div class='dark_mode-container' id="notification_status-container"></div>
    <script>
        if (Notification.permission == 'granted') {
            document.querySelector("#notification_status-container").innerHTML = `<span style="color:#848484;font-size:smaller">**Don't Close the app.Just minimize or press Home Button to get NOTIFIED on new orders.</span>`;
        } else {
            if (Notification.permission == 'denied') {
                document.querySelector("#notification_status-container").innerHTML = `<span style="color:red;font-size:smaller">**You denied the notification permision<br>click 🔒 icon on url bar(at the top in browsers) > permissions > allow or reset.</span>`;
            } else {
                document.querySelector("#notification_status-container").innerHTML = `<div class='dark_mode-label'>Notify Me about Orders</div>
            <div class='dark_mode-switch'><label class="toggle-switch"><input type="checkbox" id="notify_me"><span
            class="toogle_slider round-switch "></span></label></div>`;
            }
        }
    </script>
    <div class="profile-title">Profile</div>
    <div class='profile-client-img'><?php if (file_exists("../uploaded_files/$client_image")) {
                                        echo "<img src='../uploaded_files/$client_image' alt=''>";
                                    } else {
                                        echo "<img src='../web_files/profile.webp' alt='' draggable='false'>";
                                    } ?></div>
    <div class='profile-client-img'><?php if (file_exists("../uploaded_files/$store_image")) {
                                        echo "<img src='../uploaded_files/$store_image' alt=''>";
                                    } else {
                                        echo "<img src='../web_files/profile.webp' alt='' draggable='false'>";
                                    } ?></div>
    <div class="profile-form-container">
        <form id="profile_form" class='profile-form'>
            <div class="input_field-container"><label for="store_name">Store Name</label><input type="text" placeholder="Your Store Name..." name="store_name" value="<?php echo "$store_name"; ?>" required></div>
            <div class="input_field-container"><label for="store_owner_name">Owner Name</label><input type="text" placeholder="Your Name..." name="store_owner_name" value="<?php echo "$owner_name"; ?>" required></div>
            <div class="input_field-container"><label for="mobile_number">Mobile Number</label><input type="number" placeholder="Phone Number..." name="phone_number" id="client_phone_number" value="<?php echo "$phone_number"; ?>" required></div>
            <div class="input_field-container"><label for="client_password">Password</label><input type="password" name="client_password" placeholder="choose strong password..." required autocomplete="current-password"></div>
            <div class="input_field-container"><label for="store_description">Store Description</label><input type="text" placeholder="Your Store Description..." name="store_description" value="<?php echo "$store_description"; ?>" required></div>
            <div class="input_field-container" style="padding: 15px 0px 15px 0px;"><label for="seller_type">What you Sells</label><select name="seller_type" required>
                    <option value="Products" <?php echo $seller_type === 'Products' ? 'selected' : ''; ?>>Products</option>
                    <option value="Services" <?php echo $seller_type === 'Services' ? 'selected' : ''; ?>>Services</option>
                </select></div>
            <div class="input_field-container"><label for="store_location">Location</label><input type="text" placeholder="Location.." name="store_location" value="<?php echo "$store_location"; ?>" required></div>
            <div class="input_field-container">
                <label for="client_coords">Area</label><input style="display: none;" type="text" id="client_coords" name="client_coords" required value="23.788117809722195,86.41868225381391" autocomplete="off"><input id="center-point-input" list='center-points-list' placeholder='Your Area' autocomplete="off"><datalist id='center-points-list'>
                    <option data-coords="23.37995486566156, 85.3339206574689" value='Dhanbad'>
                    <option data-coords="23.38243652052875, 85.33495062571942" value='Dumka'>
                </datalist>
            </div>
            <div class="input_field-container"><label for="profile_img">Photo</label><input type="file" name="profile_img"></div>
            <div class="input_field-container"><label for="profile_img">Upload Your Store's Picture</label><input type="file" name="store_img"></div>
            <div class="submit-container"><button class="profile-submit-btn" type="submit" name="submit_primary_data">Save</button></div>
        </form><button class="profile-submit-btn logout-btn" id="logout-btn">LOGOUT🛑</button>
    </div><?php if ($status === 'OPEN') {
                echo "<script>document.getElementById('toggle-store_status').setAttribute('checked', true);console.log('$status');</script>";
            }
        } else { ?><span style="font-size: 18px;font-weight: bold;">You Haven't Loggedin Yet..!</span>
    <div class='login_rgst_btn-container'><span class='profile-login-tab'>LOGIN</span><span class='profile-rgst-tab'>REGISTER</span></div>
    <div class="profile-form-container" id='login-form-container'>
        <div class="profile-title">Login</div>
        <form id="profile-form" class='profile-form'>
            <div class="input_field-container"><label for="phone_number">Phone Number</label><input type="number" name="phone_number" placeholder="Phone Number" required></div>
            <div class="input_field-container"><label for="password">Password</label><input type="password" name="password" placeholder="Password..." autocomplete="current-password" required></div><button type="submit" name="client_login" class="login-btn">LOGIN</button>
        </form>
    </div>
    <div class="profile-form-container" id='register-form-container'>
        <div class='profile-title'>Register</div>
        <form method="post" id="register_form" class='profile-form'>
            <div class="input_field-container"><label for="store_owner_name">Owner Name</label><input type="text" placeholder="Your Name..." name="store_owner_name" required></div>
            <div class="input_field-container"><label for="mobile_number">Mobile Number</label><input type="number" placeholder="Phone Number..." name="phone_number" id="client_phone_number" required></div>
            <div class="input_field-container"><label for="client_password">Password</label><input type="password" name="client_password" placeholder="choose strong password..." required autocomplete="off"></div>
            <div class="input_field-container"><label for="store_name">Store Name</label><input type="text" placeholder="Store Name..." name="store_name" required></div>
            <div class="input_field-container"><label for="store_description">Store Description</label><input type="text" placeholder="Store Description..." name="store_description" required></div>
            <div class="input_field-container"><label for="seller_type">What you Sells</label><select name="seller_type" required>
                    <option value="Products">Products</option>
                    <option value="Services">Services</option>
                </select></div>
            <div class="input_field-container"><label for="store_location">Address</label><input type="text" placeholder="Location..." name="store_location" required></div>
            <div class="input_field-container"><label for="profile_img">Upload Your Picture</label><input type="file" name="profile_img"></div>
            <div class="input_field-container"><label for="profile_img">Upload Your Store's Picture</label><input type="file" name="store_img"></div>
            <div class="input_field-container"><label for="client_coords">District</label><input style="display: none;" type="text" id="client_coords" name="client_coords" value="23.788117809722195,86.41868225381391" autocomplete="off"><input id="center-point-input" list='center-points-list' placeholder='Your Area' autocomplete="off"><datalist id='center-points-list'>
                    <option data-coords="53.788117809722195,56.41868225381391" value='Dhanbad'>
                    <option data-coords="23.788117809722195,86.41868225381391" value='Dumka'>
                </datalist></div><button class="profile-submit-btn" type="submit" name="submit_primary_data">Submit</button>
        </form>
    </div>
    <script>
        document.querySelector('.profile-login-tab').addEventListener("click", function() {
            document.querySelector('#login-form-container').style.display = 'block';
            document.querySelector('#register-form-container').style.display = 'none';
        });
        document.querySelector('.profile-rgst-tab').addEventListener('click', function() {
            document.querySelector('#register-form-container').style.display = 'block';
            document.querySelector('#login-form-container').style.display = 'none';
        });
    </script><?php } ?>
<script>
    if (decodeURIComponent(document.cookie).includes('cs_theme') && decodeURIComponent(document.cookie).includes('dark_mode_on')) {
        document.getElementById('toggle-switch-input').setAttribute('checked', true);
    };
    $("#center-point-input").on("input", function() {
        var coords = $('#center-points-list [value="' + $(this).val() + '"]').data('coords');
        var dist = $('#center-point-input').val();
        document.querySelector("#client_coords").value = `${coords},${dist}`;
        console.log(document.querySelector("#client_coords"));
    });
    $(document).on("change", "#toggle-store_status", function() {
        if ($(this).prop("checked")) {
            $.ajax({
                url: "./control/store_status.php?open",
                type: "GET",
                success: function(data) {
                    console.log(data);
                }
            })
        } else {
            $.ajax({
                url: "./control/store_status.php?close",
                type: "GET",
                success: function(data) {
                    console.log(data);
                }
            })
        };
    });
</script>