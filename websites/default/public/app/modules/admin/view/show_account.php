<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="<?php echo URL_WEB . 'public/css/admin/dashboard.css'?>">
    <link rel="stylesheet" href="<?php echo URL_WEB . 'public/css/admin/show_category.css'?>">
    <title>Edit Account</title>
</head>
<body>

<div class="container">

    <!-- Menu -->
    <nav class="navigation">
    <ul class="main_menu">
        <li><a href="<?php echo URL_WEB.'admin/dashboard'?>">Dashboard</a></li>
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
        <h1 style="text-align:center;"> Account</h1>
        <?php if(!empty($accounts) && is_array($accounts)){ ?>
        <table class="Tb">
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Name</th>
                <th>Type</th>
                <th>Create at</th>
                <th>Action</th>
            </tr>
        <?php foreach($accounts as $account){ ?>
            <tr>
                <td><p><?php echo $account['id'] ?></p></td>
                <td><p><?php echo $account['username'] ?></p></td>
                <td><p><?php echo $account['name'] ?></p></td>
                <td><p><?php echo $account['type'] ?></p></td>
                <td><p><?php echo $account['created_at'] ?></p></td>
                <td>
                    <a onclick="return confirm('Are you really want to remove <?php echo $account['username'] ?>?')" style="color:red;" class="action" href="<?php echo URL_WEB . 'admin/removeUser?id='.$account['id']?>">Remove</a>
                </td>
            </tr>
        <?php } ?>
        </table>
        <?php }else{ ?>
            <p class="none">No User Available!</p>
        <?php } ?>
    </div>
</div>