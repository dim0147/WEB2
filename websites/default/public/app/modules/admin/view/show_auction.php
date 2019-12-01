<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="<?php echo URL_WEB . 'public/css/admin/dashboard.css'?>">
    <link rel="stylesheet" href="<?php echo URL_WEB . 'public/css/admin/show_category.css'?>">
    <title>Show Auctions</title>
</head>
<body>

<div class="container">

    <!-- Menu -->
    <nav class="navigation">
    <ul class="main_menu">
        <li><a href="<?php echo URL_WEB.'admin/dashboard'?>">Dashboard</a></li>
        <li><a href="<?php echo URL_WEB.'admin/showAuction'?>">Auction<span style="font-size: 15px;float:right">&#709;</span></a>
            <ul class="sub_menu">
                <li><a class="active"  href="<?php echo URL_WEB.'admin/showAuction'?>">+ Show</a></li>
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
            <li><a href="<?php echo URL_WEB.'admin/showAccount'?>">+ Edit</a></li>
            <li><a href="<?php echo URL_WEB.'user/logout'?>">+ Logout</a></li>
        </ul>
        </li>
    </ul>
    </nav>
    <!-- END MENU -->

    <div class="content">
        <h1 style="text-align:center;"> Auctions</h1>
        <?php if(!empty($auctions) && is_array($auctions)){ ?>
        <table class="Tb">
            <tr>
                <th>Name</th>
                <th>Image</th>
                <th>Minimum Price</th>
                <th>Max Price</th>
                <th>Hot Price</th>
                <th>Username</th>
                <th>Create At</th>
                <th>End At</th>
                <th>Time Left</th>
                <th>Current bird</th>
                <th>Status</th>
            </tr>
        <?php foreach($auctions as $auction){ ?>
            <tr>
                <td><a class ="link_product" href="<?php echo URL_WEB . 'product/detail?id=' .$auction['id'] ?>"><?php echo $auction['name'] ?></a></td>
                <td><img src="<?php echo URL_WEB?>/public/images/<?php echo $auction['image']?>" height="42" width="42"></td>
                <td><p><?php echo $auction['bird_minimum_price'] ?></p></td>
                <td><p><?php echo $auction['bird_max_price'] ?></p></td>
                <td><p><?php echo $auction['hot_price'] ?></p></td>
                <td><a class ="link_user" href="<?php echo URL_WEB . 'user/showUserProfile?username=' .$auction['username'] ?>"><?php echo $auction['username'] ?></a></td>
                <td><p><?php echo $auction['created_at'] ?></p></td>
                <td><p><?php echo $auction['end_at'] ?></p></td>
                <td><p style="color:red"><?php echo $auction['elapsed_time'] ?></p></td>
                <td><p style="color:brown"><?php echo $auction['current_bird_price'] ?></p></td>
                <td><p><?php echo $auction['status'] ?></p></td>
            
            </tr>
        <?php } ?>
        </table>
        <?php }else{ ?>
            <p class="none">No Auction Available!</p>
        <?php } ?>
    </div>
</div>