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

        <div class="field">
            <h3 class="field_t">Date Joined:</h3>
            <span class="field_v"><?php echo $user['created_at'] ?></span>
        </div> 

        <a href="<?php echo URL_WEB ?>user/updateProfile" class="update">Update</a>    
    </div>

    <div class="bird">
        <h1 class="title bird_title">Your Bid</h1>
        <?php if(!empty($birds) && is_array($birds)){ ?>
            <table>
                <tr>
                    <th style="width:50%">Auction Name</th>
                    <th>Image</th>
                    <th>Category</th>
                    <th>Minimum Price</th>
                    <th>Maximum Price</th>
                    <th>Hot Price</th>
                    <th>Current_bid</th>
                    <th>Your_bid</th>
                    <th>Status</th>
                    <th>Time left</th>
                    <th>Time place bid</th>
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
                    <td><p class="tb_field_bird_user"><?php echo '£' . $bird['user_price'] ?></p></td>
                    <td><p class="tb_field_status"><?php echo $bird['status'] ?></p></td>
                    <td>
                    <?php if($bird['finish'] == TRUE){ ?>
                        <p style="color:red;">End bid!</p>
                    <?php }else{ ?>
                        <p><?php echo $bird['elapsed_time']?></p>
                    <?php } ?>
                    </td>
                    <td><p><?php echo $bird['time_bird'] ?></p></td>
                    
                </tr>
        <?php } ?>
            </table>
            <?php }else{ ?>
            <p class="no_bird">You don't place any bid!</p>
        <?php } ?>
    </div>

    <div class="auction">
        <h1 class="title auction_title">Your Auction</h1>
        <a href="<?php echo URL_WEB . 'user/addAuction'?>" class="update">Add</a>
        <?php if(!empty($auctions) && is_array($auctions)){ ?>
            <table>
                <tr>
                    <th>Auction Name</th>
                    <th>Image</th>
                    <th>Category</th>
                    <th>Minimum Price</th>
                    <th>Hot Price</th>
                    <th>Current_bird</th>
                    <th>Status</th>
                    <th>Bid Win</th>
                    <th>Time left</th>
                    <th>Approve</th>
                    <th style="width:15%">Action</th>
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
                    <td>
                    <?php if(empty($auction['bid_winner_id'])) { ?>
                        <p class="tb_field_max" style="color: red"> No </p>
                    <?php }else{ ?>
                        <p class="tb_field_max" style="color: green"> Have </p>
                    <?php } ?>
                    </td>
                    <td>
                    <?php if($auction['finish'] == TRUE){ ?>
                        <p style="color:red;">End bid!</p>
                    <?php }else{ ?>
                        <p><?php echo $auction['elapsed_time'] ?></p>
                    <?php } ?>
                    </td>
                    <td>
                    <?php if ($auction['approve'] == FALSE || $auction['approve'] == 0){ ?>
                    <p style="color:red">Need Approve</p>
                    <?php }else{ ?>
                    <p style="color:green">Approved</p>
                    <?php } ?>
                    </td>
                    <td>
                    <?php if($auction['finish'] == FALSE){ ?>
                    <a class ="link_product action" href="<?php echo URL_WEB . 'user/editAuction?id='.$auction['id']?>">Edit</a> | 
                    <?php } ?>
                    <a class ="link_product action" href="<?php echo URL_WEB . 'product/detail?id='.$auction['id']?>">View</a>| <a class ="link_product action" href="<?php echo URL_WEB . 'product/showBidAuction?id='.$auction['id']?>">See Bids</a></td>
                </tr>
        <?php } ?>
            </table>
            <?php }else{ ?>
            <p class="no_bird">You don't create any auction!</p>
        <?php } ?>
    </div>

    <div class="review">
        <h1 class="title review_title">Your Review</h1>
        <?php if(!empty($reviews) && is_array($reviews)){ ?>
            <table>
                <tr>
                    <th style="width:50%">Auction Name</th>
                    <th>Image</th>
                    <th>Comment</th>
                    <th>Time</th>
                </tr>
            <?php foreach($reviews as $review){ ?>
                <tr>
                    <td><a class ="link_product" href="<?php echo URL_WEB . 'product/detail?id=' .$review['product_id'] ?>"><p class="tb_field_name"><?php echo $review['name'] ?></p></a></td>
                    <td><img src="<?php echo URL_WEB?>/public/images/<?php echo $review['image']?>" height="42" width="42"></td>
                    <td><p class="tb_field_category"><?php echo $review['comment'] ?></p></td>
                    <td><p class="tb_field_hot"><?php echo $review['created_at'] ?></p></td>
                </tr>
        <?php } ?>
            </table>
            <?php }else{ ?>
            <p class="no_bird">You don't review any auction yet!</p>
        <?php } ?>
    </div>


</div>