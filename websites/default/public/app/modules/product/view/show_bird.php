[css]
<link rel="stylesheet" href="<?php echo URL_WEB ?>public/css/ibuy.css">
<link rel="stylesheet" href="<?php echo URL_WEB ?>public/css/user/profile.css">
[/css]

<div class="container">


    <div class="bird">
        <h1 class="title bird_title">All Bird of <?php echo $product['name']?></h1>
        <img style="margin-left: 44%;margin-bottom: 20px;" src="<?php echo URL_WEB?>/public/images/<?php echo $product['image']?>" height="90" width="120">
        <?php if(!empty($winnerBird)){ ?>
            <h1 class="title" style="color:red">The bid winner!!! :</h1>
            <table>
                <tr>
                    <th>Name</th>
                    <th>Bird</th>
                    <th>Bird_at</th>
                </tr>
                <tr>
                    <td style="text-align:center;"><a class ="link_product" href="<?php echo URL_WEB . 'user/showUserProfile?username=' .$winnerBird['username'] ?>"><p class="tb_field_name"><?php echo $winnerBird['name'] ?></p></a></td>
                    <td style="text-align:center;"><p class="tb_field_bird_current"><?php echo '£' .$winnerBird['price'] ?></p></td>
                    <td style="text-align:center;"><p class="tb_field_category"><?php echo $winnerBird['created_at'] ?></p></td>
                </tr>
            </table><br><br><br>
        <?php } ?>
        <?php if(!empty($product['current_bird_price']) && empty($winnerBird)) {?>
        <h1 class="title" style="color:red">Current bird: £<?php echo $product['current_bird_price']?></h1>
        <?php } ?>
        <?php if(!empty($birds) && is_array($birds)){ ?>
            <table>
                <tr>
                    <th>Name</th>
                    <th>Bird</th>
                    <th>Bird_at</th>
                    <th>Hot Price</th>
                </tr>
            <?php foreach($birds as $bird){ ?>
                <tr>
                    <td style="text-align:center;"><a class ="link_product" href="<?php echo URL_WEB . 'user/showUserProfile?username=' .$bird['username'] ?>"><p class="tb_field_name"><?php echo $bird['name'] ?></p></a></td>
                    <td style="text-align:center;"><p class="tb_field_bird_current"><?php echo '£' .$bird['price'] ?></p></td>
                    <td style="text-align:center;"><p class="tb_field_category"><?php echo $bird['created_at'] ?></p></td>
                    <td style="text-align:center;">
                    <?php if($bird['isHot'] == TRUE){ ?>
                        <p style="color:green">Yes</p>
                    <?php }else{ ?>
                        <p style="color:black">No</p>
                    <?php } ?>
                    </td>
                </tr>
        <?php } ?>
            </table>
            <?php }else{ ?>
            <p class="no_bird">This auction don't have any bird!</p>
        <?php } ?>
    </div>

    


</div>