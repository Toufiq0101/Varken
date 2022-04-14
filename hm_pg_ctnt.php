<?php




// EVERY MYSQL QUERY IS MISSING THE WHERE CLAUSE BEACUSE AT TIME OF BUILDING DISTRICCT CODE FEATURES IS NOT AVALIABLE
// SO EVERY QUERY SELECTS THE FIRST COLUMN BY DEFAULT





include './db_connection.php';

$hm_pg_data_sql = mysqli_query($web_db_connection, 'SELECT top_banners,top_sellers,top_products FROM home_page_data LIMIT 1');
$row = mysqli_fetch_assoc($hm_pg_data_sql);
$top_banners = explode(';', $row['top_banners']);
$top_sellers = explode(';', $row['top_sellers']);
$top_products = explode(';', $row['top_products']);

// LEVEL 1
echo "<div id='home-banner-container' class='splide'><div class='splide__track'><div class='splide__list'>";
foreach ($top_banners as $img) {
  if ($img !== '') {
    echo "<div class='splide__slide'><img src='/web_files/$img'></div>";
  }
}
echo '</div></div></div>';

// LEVEL 2
echo "<div class='mrkts_btn-container'>
<div class='prdt_mrkt-btn market-btn'>Market</div>
<div class='srvc_mrkt-btn' id='services-btn'>Services</div>
</div>";

// LEVEL 3
echo "<div class='top_slr-contaiener'>
<div class='top_slr-header'>Sellers of the Week</div>
<div class='splide' id='best_sellers_list-container'>
  <div class='splide__track'>
    <ul class='splide__list'>";
foreach ($top_sellers as $s_card) {
  if ($s_card) {
    $s_card = explode(':', $s_card);
    $s_name = $s_card[1];
    $s_img = $s_card[2];
    echo "<li class='splide__slide'>
    <a href='/shop.php?c_id=$s_card[0]'>
        <div class='slr-card'>
        <img data-splide-lazy='/uploaded_files/$s_img' height='100%' alt='img' class='slr_img' />
        <div class='slr-name'>
        $s_name
        </div>
        </div></a>
        </li>";
  }
}
echo "</ul>
</div>
<div class='splide__progress'>
		<div class='splide__progress__bar'>
		</div>
  </div>
</div>
</div>";

// LEVEL 4
// echo "<div class='top_slr-header'>Products of the Week</div><div class='prd_o_t_wk-container'>
// <div class='splide' id='best_products_list-container'>
//   <div class='splide__track'>
//     <ul class='splide__list'>";
// foreach ($top_products as $p_position => $p_card) {
//   if ($p_card) {
//     $p_card = explode(':', $p_card);
//     $p_img = $p_card[3];
//     $p_position = $p_position + 1;
//     echo "<li class='splide__slide'>
//     <a href='/product.php?i=$p_card[0]'>
//             <div class='prd_card'>
//             <div class='postion_tag-prd_card'>
//             #$p_position
//             </div>
//             <div class='prd_dtl-prd_card'>
//             <img src='/uploaded_files/$p_img' alt='' class='prd_img'>
//             </div></a>
//             </div></li>";
//   }
// }
// echo '</ul></div></div></div>
// <div id="thumbnail-slider" class="splide">
// <div class="splide__track">
// <ul class="splide__list">';
// foreach ($top_products as $p_position => $p_card) {
//   if ($p_card) {
//     $p_card = explode(':', $p_card);
//     $p_img = $p_card[3];
//     $p_position = $p_position + 1;
//     echo "<li class='splide__slide splide_slide-user_custom'>
//         <img src='/uploaded_files/$p_img'>
//       </li>";
//   }
// }
// echo "</ul></div></div>";

echo '<div class="l-4-cntnr">
<div class="l-4-box">
    <span class="l-4-b-label">Custom Order</span>
    <div class="l-4-b-img_cntnr">
        <img src="./220-FG882910.jpg" alt="" class="l-4-b-img">
    </div>
    <span class="l-4-b-example">Stationary, Medicine, Monthly Grocery List etc</span>
</div>
<div class="l-4-box">
    <span class="l-4-b-label">Pick N Drop</span>
    <div class="l-4-b-img_cntnr">
        <img src="./220-SM833448.jpg" alt="" class="l-4-b-img">
    </div>
    <span class="l-4-b-example">Documents, Charger, Clothes etc</span>
</div>
</div>';
// LEVEL 5
date_default_timezone_set('Asia/Kolkata');
$hrs =  explode(':', date('H:i'))[0];
$col_nm_l6 = 'night_products';

echo "<div class='h-p-lvl-6'>";

if ($hrs >= 3 && $hrs < 11) {
  $col_nm_l6 = 'morning_products';
  echo "<div class='top_slr-header'>Products You Might Need<br>this Morning</div>";
} elseif ($hrs >= 11 && $hrs < 16) {
  $col_nm_l6 = 'mid_day_products';
  echo "<div class='top_slr-header'>Products You Might Need<br>this Afternoon</div>";
} elseif ($hrs >= 16 & $hrs < 19) {
  $col_nm_l6 = 'evening_products';
  echo "<div class='top_slr-header'>Products You Might Need<br>this Evening</div>";
} else {
  $col_nm_l6 = 'night_products';
  echo "<div class='top_slr-header'>Products You Might Need<br>this Evening</div>";
}
echo "<div class='l6-p-cntnr'>";

$l6_query = mysqli_query($web_db_connection, "SELECT $col_nm_l6 FROM home_page_data LIMIT 1");
$l6_prd_row = mysqli_fetch_assoc($l6_query);
$prd_arr = explode(';', $l6_prd_row["$col_nm_l6"]);
foreach ($prd_arr as $l6_prd) {
  if ($l6_prd) {
    $l6_prd = explode(':', $l6_prd);
    $p_seller_id = $l6_prd[1];
    $p_id = $l6_prd[2];
    $p_name = $l6_prd[3];
    $p_price = $l6_prd[4];
    $p_img = $l6_prd[5];
    echo "
    <div class='l6-p-card-cntnr'>
    <a class='l6-p-card-p_link' href='/product.php?i=$p_id'>
    <div class='l6-p-c-img'>
    <img
    src='/uploaded_files/$p_img'
    alt=''
    />
    </div>
    <div class='l6-p-c-name'>
    $p_name
    </div>
    <div class='l6-p-c-price'>Rs. $p_price</div></a>
    <div class='l6-p-c-btn-cntnt'>
  <div class='l6-p-c-cart-btn' data-add_item='34:$p_id'><span class='del_cart_item_btn' data-cart_str='$p_seller_id:$p_id' >–</span><span class='prd_qnt'>0</span><span class='quicki_add_to_cart-btn' data-add_item='$p_seller_id:$p_id'>+</span></div>
  <div class='l6-p-c-order-btn' onclick='open_quickii_modal(`$p_img`,`$p_name`,`$p_price`,``,``,`$p_seller_id`,`$p_id`)'><img src='/css/svg/quick_order_btn.svg'></div>
</div>
    </div>";
  }
}

// <div class='l6-p-c-cart-btn quicki_add_to_cart-btn'  data-add_item='34:$p_id'><img src='/css/svg/shopping-cart.svg'></div>

echo "</div></div>";

// LEVEL 5
echo "<div class='top_slr-header'>Shop by Categories</div>
<div class='fooding-ctg'>
  <div class='f-ctg-box1' onclick='sections(`Snacks`)'><div>SNACKS</div></div>
  <div class='f-ctg-flex-row-1'>
    <div class='f-ctg-box2' onclick='sections(`Groceries`)'><div>GROCERIES</div></div>
    <div class='f-ctg-flex-row-2'>
      <div class='f-ctg-box3' onclick='sections(`Vegitables`)'><div>VEGIES</div></div>
      <div class='f-ctg-box4' onclick='sections(`Fruits`)'><div>FRUITS</div></div>
    </div>
  </div>
</div>";

// LEVEL 6

// CONFUSED, WE SHOULD LIST THESE OR NOT

// if ($_GET['width'] < 500) {
//   echo "<div class='top_slr-header'>Cool Electronics</div>
//     <div class='lvl-6-cntnr'>
//     <div class='l1'>
//       <div class='cpu'></div>
//       <div class='mntr'></div>
//     </div>
//     <div class='l2'>
//       <div class='mobl'></div>
//       <div class='l2-l1'>
//         <div class='er_phn'></div>
//         <div class='spkr'></div>
//       </div>
//     </div>
//     <div class='l3'>
//       <div class='lapi'></div>
//       <div class='tblt'></div>
//     </div>
//   </div>";
// } else {
//   echo "<div class='top_slr-header'>Cool Electronics</div>
//   <div class='lvl-6-cntnr_plus_500'>
//   <div class='l-6-flex-1'>
//     <div class='l-6-c-card mobl'></div>
//     <div class='l-6-c-card spkr'></div>
//     <div class='l-6-c-card er_phn'></div>
//   </div>
//   <div class='l-6-flex-2'>
//     <div class='l-6-c-card lapi'></div>
//     <div class='l-6-c-card tblt'></div>
//     <div class='l-6-c-card mntr-cpu'></div>
//   </div>
// </div>";
// }
