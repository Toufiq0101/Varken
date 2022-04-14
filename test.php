<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=\, initial-scale=1.0">
    <title>Document</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .l-4-cntnr {
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: space-evenly;
        }
        .l-4-box {
            width: 49%;
            border-radius: 10px;
            background-color: white;
            /* position: relative; */
            /* aspect-ratio: 4/3; */
            /* height: 10rem; */
            display: flex;
            flex-direction: column;
            border: 1px solid black;
        }
        .l-4-b-label {
            width: 100%;
            height: 1.5rem;
            padding: .3rem;
            /* position: absolute;
            top: 1%; */
            font-size: medium;
            font-weight: 600;
            text-align: center;
        }
        .l-4-b-example {
            height: 20%;
            font-size: 0.9rem;
            font-weight: lighter;
            text-align: center;
            word-break: break-all;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
        }
        .l-4-b-img_cntnr{
            aspect-ratio: 10/4;
        }
        .l-4-b-img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            aspect-ratio: 12/4;
        }
    </style>
</head>

<body>
    <div class="l-4-cntnr">
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
    </div>
</body>

</html>