<!DOCTYPE html>
<html>

    <head>

        <link rel="stylesheet" href="/source/recurring/navBar/navBar.css">

    </head>

    <body>

        <!-- navbar ------------------------------------------------------------------------------------------- -->

        <div id="whole_navbar">

            <nav id="navbar">

                <img src="" alt="personal" id="nav_personal" class="nav_item">
                <a class="nav_item" href="index.php"><img src="" alt="logo"></a> <!-- <-- dass hier muss bei jeder seite verschieden gemacht werden -->
                <form action="" method="POST" id="nav_search" class="nav_item">

                    <input type="text" placeholder="Search.." id="nav_input_text_search">
                    <input type="image" src="" id="nav_input_image_search">

                </form>
                <div id="nav_shopping_cart" class="nav_item">

                    <img src="" alt="shopping Cart" id="nav_shopping_cart_img">
                    <a id="value_shopping_cart">0</a>

                </div>
                
            </nav>

            <!-- toggle menu ------------------------------------------------------------------------------------------- -->

            <!-- toggle menu personal  -->

            <div id="personal_toggle_menu">

                <div id="back_toggle_menu_personal" class="back_toggle_menu">back</div>
                <ul id="personal_toggle_menu_ul" class="toggle_menu_ul">

                    <a href="https://www.wikipedia.de/"><li>test</li></a>
                    <a><li>test</li></a>
                    <a><li>test</li></a>
                    <a><li>test</li></a>
                    <a><li>test</li></a>

                </ul>

            </div>

            <!-- toggle menu shopping cart  -->

            <div id="shopping_cart_toggle_menu">

                <div id="back_toggle_menu_shopping_cart" class="back_toggle_menu">back</div>
                <ul id="shopping_cart_toggle_menu_ul" class="toggle_menu_ul">

                    <a href="https://www.wikipedia.de/"><li>test</li></a>
                    <a><li>test</li></a>
                    <a><li>test</li></a>
                    <a><li>test</li></a>
                    <a><li>test</li></a>

                </ul>
                <form action="" method="Post" id="toggle_menu_shopping_cart_buy">

                    <label>Zum Einkaufswagen</label>
                    <input type="submit">
                    <label>Sofort Kaufen</label>
                    <input type="submit">

                </form>

            </div>

        </div>

    </body>

    <script src="/source/recurring/navBar/navBar.js"></script>

</html>