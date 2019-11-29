[css]
<link rel="stylesheet" href="<?php echo URL_WEB ?>public/css/ibuy.css">
<link rel="stylesheet" href="<?php echo URL_WEB ?>public/css/user/profile.css">
[/css]

<div class="container">
    <div class="profile">
        <h1 class="title">User Profile</h1>

        <div class="field">
            <h3 class="field_t">Your name:</h3>
            <span class="field_v"><?php echo $user['name'] ?></span>
        </div>

        <div class="field">
            <h3 class="field_t">Username:</h3>
            <span class="field_v"><?php echo $user['username'] ?></span>
        </div>

        <div class="field">
            <h3 class="field_t">Password:</h3>
            <span class="field_v"><a href="<?php echo URL_WEB?>user/changePassword">Change your password</a></span>
        </div>

        <a href="<?php echo URL_WEB ?>user/updateProfile" class="update">Update</a>    
    </div>

    <div class="bird">
        <h1 class="title bird_title">Your Bird</h1>
        <?php if(!empty($birds) && is_array($birds)){ ?>
            <table>
                <tr>
                    <th style="width:50%">Auction Name</th>
                    <th>Image</th>
                    <th>Category</th>
                    <th>Minimum Price</th>
                    <th>Maximum Price</th>
                    <th>Hot Price</th>
                    <th>Current_bird</th>
                    <th>Status</th>
                    <th>Time left</th>
                    <th>Time place bird</th>
                </tr>
            <?php foreach($birds as $bird){ ?>
                <tr>
                    <td><a class ="link_product" href="<?php echo URL_WEB . 'product/detail?id=' .$bird['product_id'] ?>"><p class="tb_field_name"><?php echo $bird['name'] ?></p></a></td>
                    <td><img src="<?php echo URL_WEB?>/public/images/<?php echo $bird['image']?>" height="42" width="42"></td>
                    <td><p class="tb_field_category"><?php echo $bird['product_category'] ?></p></td>
                    <td><p class="tb_field_mini"><?php echo $bird['bird_minimum_price'] ?></p></td>
                    <td><p class="tb_field_max"><?php echo $bird['bird_max_price'] ?></p></td>
                    <td><p class="tb_field_hot"><?php echo $bird['hot_price'] ?></p></td>
                    <td><p class="tb_field_bird_current"><?php echo '£' . $bird['current_bird_price'] ?></p></td>
                    <td><p class="tb_field_status"><?php echo $bird['status'] ?></p></td>
                    <td><p><?php echo $bird['elapsed_time'] ?></p></td>
                    <td><p><?php echo $bird['time_bird'] ?></p></td>
                    
                </tr>
        <?php } ?>
            </table>
            <?php }else{ ?>
            <p class="no_bird">You don't have place any bird!</p>
        <?php } ?>
    </div>

    <div class="auction">
        <h1 class="title auction_title">Your Auction</h1>
        <?php if(!empty($auctions) && is_array($auctions)){ ?>
            <table>
                <tr>
                    <th style="width:50%">Auction Name</th>
                    <th>Image</th>
                    <th>Category</th>
                    <th>Minimum Price</th>
                    <th>Maximum Price</th>
                    <th>Hot Price</th>
                    <th>Current_bird</th>
                    <th>Status</th>
                    <th>Time left</th>
                </tr>
            <?php foreach($auctions as $auction){ ?>
                <tr>
                    <td><a class ="link_product" href="<?php echo URL_WEB . 'user/editAuction?id=' .$auction['id'] ?>"><p class="tb_field_name"><?php echo $auction['name'] ?></p></a></td>
                    <td><img src="<?php echo URL_WEB?>/public/images/<?php echo $auction['image']?>" height="42" width="42"></td>
                    <td><p class="tb_field_category"><?php echo $auction['product_category'] ?></p></td>
                    <td><p class="tb_field_mini"><?php echo $auction['bird_minimum_price'] ?></p></td>
                    <td><p class="tb_field_max"><?php echo $auction['bird_max_price'] ?></p></td>
                    <td><p class="tb_field_hot"><?php echo $auction['hot_price'] ?></p></td>
                    <td><p class="tb_field_bird_current"><?php echo '£' . $auction['current_bird_price'] ?></p></td>
                    <td><p class="tb_field_status"><?php echo $auction['status'] ?></p></td>
                    <td><p><?php echo $auction['elapsed_time'] ?></p></td>
                </tr>
        <?php } ?>
            </table>
            <?php }else{ ?>
            <p class="no_bird">You don't have create any auction!</p>
        <?php } ?>
    </div>


</div>