/* navbar bottom shadow on scroll */

function nav_bottom_show_shadow_onscroll()
{
    if (document.body.scrollTop > 50 || document.documentElement.scrollTop > 50)
    {
        /* var navbar = getElementById("navbar"); */
        navbar.style.boxShadow = "rgba(0, 0, 0, 0.15) 0px 2px 1px, rgba(0, 0, 0, 0.09) 0px 4px 2px, rgba(0, 0, 0, 0.09) 0px 8px 4px, rgba(0, 0, 0, 0.09) 0px 16px 8px, rgba(0, 0, 0, 0.09) 0px 32px 16px";
        navbar.style.transition = "box-shadow 0.5s";
    }
    else
    {
        navbar.style.transition = "box-shadow 0.5s";
        navbar.style.boxShadow = "";
    }
}

window.onscroll = function()
{
    nav_bottom_show_shadow_onscroll()
};

/* toggle menu */

let toggle_personal_style = document.getElementById("personal_toggle_menu");
let toggle_shopping_cart_style = document.getElementById("shopping_cart_toggle_menu");

let nav_personal = document.getElementById("nav_personal");
let nav_shopping_cart = document.getElementById("nav_shopping_cart");

let nav_personal_back = document.getElementById("back_toggle_menu_personal");
let nav_shopping_cart_back = document.getElementById("back_toggle_menu_shopping_cart");

var nav_toggle_status_personal = true;
var nav_toggle_status_shopping_cart = true;

/* toggle menu show */

function nav_toggle_personal()
{
    if (nav_toggle_status_personal = true)
    {
        toggle_personal_style.style.width = "15%";    //290px
        toggle_personal_style.style.transition = "width 0.5s";
    }
}

function nav_toggle_shopping_cart()
{
    if (nav_toggle_status_shopping_cart = true)
    {
        toggle_shopping_cart_style.style.width = "15%";   //290px
        toggle_shopping_cart_style.style.transition = "width 0.5s";
    }
}

nav_personal.addEventListener("click", nav_toggle_personal);
nav_shopping_cart.addEventListener("click", nav_toggle_shopping_cart);

/* toggle menu back */

function nav_toggle_personal_back()
{
    if (nav_toggle_status_personal = true)
    {
        toggle_personal_style.style.width = "0%";
        toggle_personal_style.style.transition = "width 0.5s";
    }
}

function nav_toggle_shopping_cart_back()
{
    if (nav_toggle_status_shopping_cart = true)
    {
        toggle_shopping_cart_style.style.width = "0%";
        toggle_shopping_cart_style.style.transition = "width 0.5s";
    }
}

nav_personal_back.addEventListener("click", nav_toggle_personal_back);
nav_shopping_cart_back.addEventListener("click", nav_toggle_shopping_cart_back);