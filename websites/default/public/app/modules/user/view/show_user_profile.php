[css]
<link rel="stylesheet" href="<?php echo URL_WEB ?>public/css/ibuy.css">
<link rel="stylesheet" href="<?php echo URL_WEB ?>public/css/user/profile.css">
[/css]

<div class="container">

<div class="profile">
        <h1 class="title"><?php echo $user['name'] ?> Profile</h1>

        <div class="field">
            <h3 class="field_t">Name:</h3>
            <span class="field_v"><?php echo $user['name'] ?></span>
        </div>

        <div class="field">
            <h3 class="field_t">Username:</h3>
            <span class="field_v"><?php echo $user['username'] ?></span>
        </div> 

        <div class="field">
            <h3 class="field_t">Date Joined:</h3>
            <span class="field_v"><?php echo $user['created_at'] ?></span>
        </div> 
    </div>


<div class="auction">
        <h1 class="title auction_title"><?php echo $user['name'] ?>'s Auction</h1>
        <?php if(!empty($auctions) && is_array($auctions)){ ?>
            <table>
                <tr>
                    <th >Auction Name</th>
                    <th>Image</th>
                    <th>Category</th>
                    <th>Minimum Price</th>
                    <th>Hot Price</th>
                    <th>Current_bid</th>
                    <th>Status</th>
                    <th style="width: 15%">Start Date</th>
                    <th>Time left</th>
                </tr>
            <?php foreach($auctions as $auction){ ?>
                <tr>
                    <td><a class ="link_product" href="<?php echo URL_WEB . 'product/detail?id=' .$auction['id'] ?>"><p class="tb_field_name"><?php echo $auction['name'] ?></p></a></td>
                    <td><img src="<?php echo URL_WEB?>/public/images/<?php echo $auction['image']?>" height="42" width="42"></td>
                    <td><p class="tb_field_category"><?php echo $auction['product_category'] ?></p></td>
                    <td><p class="tb_field_mini"><?php echo $auction['bird_minimum_price'] ?></p></td>
                    <td><p class="tb_field_hot"><?php echo $auction['hot_price'] ?></p></td>
                    <td><p class="tb_field_bird_current"><?php echo '£' . $auction['current_bird_price'] ?></p></td>
                    <td>
                    <?php if($auction['status'] === 'Open'){ ?>
                    <p class="tb_field_status">Open</p>
                     <?php }else{ ?>
                    <p class="tb_field_status" style="color:red"><?php echo $auction['status'] ?></p>
                     <?php } ?>
                    </td>
                    </td>
                    <td><p class="tb_field_maximum"><?php echo $auction['created_at'] ?></p></td>
                    <?php if($auction['finish'] == FALSE){ ?>
                        <td><p><?php echo $auction['elapsed_time'] ?></p></td>
                    <?php }else{ ?>
                        <td><p style="color: red;">Bid End!</p></td>
                    <?php } ?>
                </tr>
        <?php } ?>
            </table>
            <?php }else{ ?>
            <p class="no_bird">This user don't have any auction!</p>
        <?php } ?>
    </div>
</div>