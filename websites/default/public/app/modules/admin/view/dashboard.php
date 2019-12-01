<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="<?php echo URL_WEB . 'public/css/admin/dashboard.css'?>">
    <title>Admin Dashboard</title>
</head>
<body>

<div class="container">

    <!-- Menu -->
    <nav class="navigation">
    <ul class="main_menu">
        <li><a class="active" href="<?php echo URL_WEB.'admin/dashboard'?>">Dashboard</a></li>
        <li><a href="<?php echo URL_WEB.'admin/showAuction'?>">Auction<span style="font-size: 15px;float:right">&#709;</span></a>
            <ul class="sub_menu">
                <li><a href="<?php echo URL_WEB.'admin/showAuction'?>">+ Show</a></li>
            </ul>
        </li>
        <li><a href="<?php echo URL_WEB.'admin/showCategory'?>">Category<span style="font-size: 15px;float:right">&#709;</span></a>
        <ul class="sub_menu">
            <li><a href="<?php echo URL_WEB.'admin/showCategory'?>">+ Edit</a></li>
            <li><a href="<?php echo URL_WEB.'admin/addCate'?>">+ Add</a></li>
        </ul>
        </li>
        <li><a href="<?php echo URL_WEB.'admin/showAccount'?>">Account<span style="font-size: 15px;float:right">&#709;</span></a>
        <ul class="sub_menu">
            <li><a class="active" href="<?php echo URL_WEB.'admin/showAccount'?>">+ Edit</a></li>
            <li><a href="<?php echo URL_WEB.'user/logout'?>">+ Logout</a></li>
        </ul>
        </li>
    </ul>
    </nav>
    <!-- END MENU -->

    <div class="content">
        <h1 class="db_title">Dashboard</h1>
        <div class="db_notification">
            <h1 style="font-weight:bold; margin-left: 10px;">Recently</h1>
            <a href="<?php echo URL_WEB. 'admin/showAuction'?>">
                <div class="db_nt_auction">
                    <img src="<?php echo URL_WEB . 'public/images/auction.jpg'?>" alt="Lights" style="width:100%;height:100%;vertical-align: middle;border-style: none;position:absolute;">
                    <div class="db_nt_title">10 Auction Added ></div>
                </div>
            </a>

        </div>

        <div class="chart">
            <h1 style="font-weight:bold; margin-left: 10px;">Chart</h1>
            <img style="width:100%;height:100%;vertical-align: middle;border-style: none;position:absolute;" src="<?php echo URL_WEB . 'public/images/chart.png'?>" alt="">
        </div>

        <div>
            <p></p>
        </div>
    </div>



</div>


</body>
</html>