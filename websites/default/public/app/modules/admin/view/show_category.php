<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="<?php echo URL_WEB . 'public/css/admin/dashboard.css'?>">
    <link rel="stylesheet" href="<?php echo URL_WEB . 'public/css/admin/show_category.css'?>">
    <title>Edit Category</title>
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
            <li><a class="active" href="<?php echo URL_WEB.'admin/showCategory'?>">+ Edit</a></li>
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
        <h1 style="text-align:center;"> Category</h1>
        <a href="<?php echo URL_WEB.'admin/addCate' ?>" class="btn">+ Add New</a>
        <?php if(!empty($categorys) && is_array($categorys)){ ?>
        <table class="Tb">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Action</th>
            </tr>
        <?php foreach($categorys as $category){ ?>
            <tr>
                <td><p><?php echo $category['id'] ?></p></td>
                <td><p style="color:green"><?php echo $category['name'] ?></p></td>
                <td>
                    <a style="color:#19bdda;" class="action" href="<?php echo URL_WEB . 'admin/editCate?name='.$category['name']?>">Edit</a> /
                    <a style="color:red;" class="action" href="<?php echo URL_WEB . 'admin/removeCate?name='.$category['name']?>">Remove</a>
                </td>
            </tr>
        <?php } ?>
        </table>
        <?php }else{ ?>
            <p class="none">No Category Available!</p>
        <?php } ?>
    </div>
</div>