function add_to_wishlist(el){
    if(el.children[0].children[1].getAttribute("fill")!=="#f00"){
        el.children[0].children[1].setAttribute("fill","#f00");
    }else{
        el.children[0].children[1].setAttribute("fill","#00000000")
    };
};





















// class wholeItem
// {
//     constructor()
//     {
//         // link to item page
//         this.item = document.getElementById("item");
//         this.item_top_img = document.getElementById("item_top_img");
//         this.item_bottom = document.getElementById("item_bottom");
        
//         this.item_top_img.addEventListener("click", this.link_to_item_page);
//         this.item_bottom.addEventListener("click", this.link_to_item_page);

//         // item favourite
//         this.item_favourites_heart = document.getElementById("favourites");
//         this.item_favourites_heart_fill = document.getElementById("cls-1");
//         this.item_favourites_toggle = false;

//         this.item_favourites_heart.addEventListener("click", this.item_favourites_fill_red);

//         // item add show / hide
//         this.quantity = 1;
//         this.item_add = document.getElementById("item_add");
//         this.item_add_pressed = document.getElementById("item_add_pressed");

//         this.item_add.addEventListener("click", this.show_item_add_pressed);

//         // item_quantity
//         this.plus = document.getElementById("item_add_plus");
//         this.minus = document.getElementById("item_add_minus");
//         this.item_add_pressed_label = document.getElementById("item_add_pressed_label");

//         this.plus.addEventListener("click", this.item_quantity_minus);
//         this.minus.addEventListener("click", this.item_quantity_plus);
//     }

//     link_to_item_page()
//     {
//         this.item.style.bottom = "0%";
//         this.item.style.right = "0%";
//         window.location.href = "https://www.ecosia.org/search?method=index&q=test";
//     }

//     item_favourites_fill_red()
//     {
//         if (this.item_favourites_toggle == false)
//         {
//             this.item_favourites_heart_fill.style.transition = "fill 0.2s";
//             this.item_favourites_heart_fill.style.fill = "red";
//             this.item_favourites_toggle = true;
//         }
//         else if (this.item_favourites_toggle == true)
//         {
//             this.item_favourites_heart_fill.style.transition = "fill 0.2s";
//             this.item_favourites_heart_fill.style.fill = "transparent";
//             this.item_favourites_toggle = false;
//         }
//     }

//     show_item_add_pressed()
//     {
//         this.item_add_pressed.style.width = "150px";
//         this.item_add_pressed.style.transition = "width 0.3s";
//         this.item_add_pressed.style.visibility = "visible";
//         this.item_add.style.zIndex = "5";
//         this.item_add_pressed.style.zIndex = "10";
//     }

//     hide_item_add_pressed()
//     {
//         this.item_add_pressed.style.width = "0px";
//         this.item_add_pressed.style.transition = "width 0.3s";
//         this.item_add.style.zIndex = "10";
//         this.item_add_pressed.style.zIndex = "5";
//         window.setTimeout(this.hide_item_add_pressed_visibility, 95); //// change with opacity!!!! <-- try mit zindex aarbeiten!!!
//         this.quantity = 1;
//         this.item_add_pressed_label.innerHTML = quantity;
//     }

//     hide_item_add_pressed_visibility()
//     {
//         this.item_add_pressed.style.visibility = "hidden";
//     }

//     item_quantity_minus()
//     {
//         this.quantity--;
//         this.item_add_pressed_label.innerHTML = quantity;

//         if(this.quantity == 0)
//         {
//             this.hide_item_add_pressed();
//         }
//     }

//     item_quantity_plus()
//     {
        
//         if (this.quantity == 100)
//         {
//             this.quantity = quantity;
//         }
//         else
//         {
//             this.quantity++;
//         }
//         this.item_add_pressed_label.innerHTML = quantity;
//     }
// }

// $wholeitem = new wholeItem;

// OLD --------------------------------------------------------------------------------------------------------------------------------------

/* link to item page */

/* var item = document.getElementById("item");
var item_top_img = document.getElementById("item_top_img");
var item_bottom = document.getElementById("item_bottom"); */

/* function link_to_item_page()
{
    item.style.bottom = "0%";
    item.style.right = "0%";
    window.location.href = "https://www.ecosia.org/search?method=index&q=test";
} */

/* item_top_img.addEventListener("click", link_to_item_page());
item_bottom.addEventListener("click", link_to_item_page()) */

/* item favourite */

/* var item_favourites_heart = document.getElementById("favourites");
var item_favourites_heart_fill = document.getElementById("cls-1");
var item_favourites_toggle = false; */

/* function item_favourites_fill_red()
{
    if (item_favourites_toggle == false)
    {
        item_favourites_heart_fill.style.transition = "fill 0.2s";
        item_favourites_heart_fill.style.fill = "red";
        item_favourites_toggle = true;
    }
    else if (item_favourites_toggle == true)
    {
        item_favourites_heart_fill.style.transition = "fill 0.2s";
        item_favourites_heart_fill.style.fill = "transparent";
        item_favourites_toggle = false;
    }
} */

/* item_favourites_heart.addEventListener("click", item_favourites_fill_red()); */

/* item add show / hide */

/* var quantity = 1;
var item_add = document.getElementById("item_add");
var item_add_pressed = document.getElementById("item_add_pressed"); */

/* function show_item_add_pressed()
{
    item_add_pressed.style.width = "150px";
    item_add_pressed.style.transition = "width 0.3s";
    item_add_pressed.style.visibility = "visible";
    item_add.style.zIndex = "5";
    item_add_pressed.style.zIndex = "10";
}

function hide_item_add_pressed()
{
    item_add_pressed.style.width = "0px";
    item_add_pressed.style.transition = "width 0.3s";
    item_add.style.zIndex = "10";
    item_add_pressed.style.zIndex = "5";
    window.setTimeout(hide_item_add_pressed_visibility, 95); //// change with opacity!!!! <-- try mit zindex aarbeiten!!!
    quantity = 1;
    item_add_pressed_label.innerHTML = quantity;
}

function hide_item_add_pressed_visibility()
{
    item_add_pressed.style.visibility = "hidden";
} */

/* item_add.addEventListener("click", show_item_add_pressed()); */

/* item_quantity */

/* var plus = document.getElementById("item_add_plus");
var minus = document.getElementById("item_add_minus");
var item_add_pressed_label = document.getElementById("item_add_pressed_label"); */

/* function item_quantity_minus()
{
    quantity--;
    item_add_pressed_label.innerHTML = quantity;

    if(quantity == 0)
    {
        hide_item_add_pressed();
    }
}

function item_quantity_plus()
{
    
    if (quantity == 100)
    {
        quantity = quantity;
    }
    else
    {
        quantity++;
    }
    item_add_pressed_label.innerHTML = quantity;
} */

/* plus.addEventListener("click", item_quantity_minus());
minus.addEventListener("click", item_quantity_plus()); */





