[css]
<link rel="stylesheet" href="<?php echo URL_WEB ?>public/css/ibuy.css">
<link rel="stylesheet" href="<?php echo URL_WEB ?>public/css/user/profile.css">
[/css]

<div class="container">


    <div class="bird">
        <h1 class="title bird_title">All Bird of <?php echo $product['name']?></h1>
        <img style="margin-left: 44%;margin-bottom: 20px;" src="<?php echo URL_WEB?>/public/images/<?php echo $product['image']?>" height="90" width="120">
        <?php if(!empty($product['current_bird_price'])) {?>
        <h1 class="title" style="color:red">Current bird: £<?php echo $product['current_bird_price']?></h1>
        <?php } ?>
        <?php if(!empty($birds) && is_array($birds)){ ?>
            <table>
                <tr>
                    <th>Name</th>
                    <th>Bird</th>
                    <th>Bird_at</th>
                </tr>
            <?php foreach($birds as $bird){ ?>
                <tr>
                    <td style="text-align:center;"><a class ="link_product" href="<?php echo URL_WEB . 'user/showUserProfile?username=' .$bird['username'] ?>"><p class="tb_field_name"><?php echo $bird['name'] ?></p></a></td>
                    <td style="text-align:center;"><p class="tb_field_bird_current"><?php echo '£' .$bird['price'] ?></p></td>
                    <td style="text-align:center;"><p class="tb_field_category"><?php echo $bird['created_at'] ?></p></td>
                </tr>
        <?php } ?>
            </table>
            <?php }else{ ?>
            <p class="no_bird">This auction don't have any bird!</p>
        <?php } ?>
    </div>

    


</div>