[js]
<script src="<?php echo URL_WEB . "public/js/product_detail.js" ?>"></script>
[/js]
[css]
<link rel="stylesheet" href="<?php echo URL_WEB?>public/css/ibuy.css" />
[/css]

<div id="fb-root"></div>
  <script>(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.0";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));</script>


    <h1>Product Page</h1>
    <article class="product">

        
        <div>
        <img src="<?php echo URL_WEB . "public/images/".$product['image']?>" style="width:700px; height:300px" alt="product name"><br>
       
       <!-- Thumbnail -->
        <?php if(!empty($product['product_thumbnail']) && is_array($product['product_thumbnail'])){
            echo " <h4>Thumbnail:</h4> <br>";
                foreach($product['product_thumbnail'] as $name){    
        ?>
        <img style="width:100px;height:80px;" src="<?php echo URL_WEB . 'public/images/'.$name?>" alt="">
        <?php }} ?>
        </div>
        
        <section class="details">
        <!-- Name/ -->
            <h2><?php echo $product['name'] ?></h2>
        <!-- Category -->
            <?php if(!empty($product['category_name']) && !is_array($product['category_name']))
                    echo "<h3>" .$product['category_name'] . "</h3>";
            ?>
        <!-- Auction create by  -->
            <p>Auction created by <a href="<?php echo URL_WEB . 'user/showUserProfile?username='.$product['own_product_username'] ?>"><?php echo $product['own_product'] ?></a></p>
           <!-- Current bid -->
           <?php if($product['finish'] == FALSE) {?>
            <p class="price" style="color:green">Current bid: £<?php echo $product['current_bird_price'] ?></p>
            <?php } ?>
            <!-- Minimum price -->
            <?php 
            if((float)$product['bird_minimum_price'] !== 0.00 && $product['finish'] == FALSE){
            ?>
                <p class="price" style="color:blue">Minimum bid: £<?php echo $product['bird_minimum_price'] ?></p>
                <?php if($product['finish'] == FALSE){ ?>
                    <p class="price" style="color:blue">*Please bid more than £<?php echo $product['bird_minimum_price'] ?></p>
                <?php } ?>
            <?php } ?>
            <!-- Hot price -->
            <?php 
            if(!empty($product['hot_price']) && (float)$product['hot_price'] !== 0.00 && $product['finish'] == FALSE){
            ?>
                <p class="price">Hot Price: £<?php echo $product['hot_price'] ?></p>
                <a class="btn1" href="<?php echo URL_WEB. 'product/placeHotBid?id=' . $product['id']?>">Buy With £<?php echo $product['hot_price']?>!</a><br>
            <?php } ?>
            <!-- Time left -->
            <?php if($product['finish'] == TRUE){ ?>
                <h1 style="color:red!important;font-weight:bold;" >This product is end bid!</h1>
            <?php }else{ ?>
            <time>Time left: <?php echo $product['elapsed_time'] ?></time>
            <?php } ?>
            <!-- form Place bid -->
            <?php if($product['finish'] == FALSE){ ?>
            <form action="<?php echo URL_WEB . "product/postPlaceBid"?>" class="bid" method="POST">
                <input name="amount" type="number" min="0.00"  placeholder="Enter bid amount" required/>
                <input name="id" type="hidden" value="<?php echo $product['id']?>"/>
                    <input type="submit" id="placebid" value="Place bid" />
                <?php } ?>
            </form>
            <a href="<?php echo URL_WEB.'product/showBidAuction?id='.$product['id']?>">Show all bid of this auction</a>
            <br>
            Share For More:<br>
            <div style="display:inline-block">
                <div class="fb-share-button" 
                    data-href="<?php echo URL_WEB."product/detail?id=".$product['id']?>" 
                    data-layout="button_count">
                </div>
                <div style="float:left;">
                <a href="https://twitter.com/share?ref_src=twsrc%5Etfw" class="twitter-share-button" data-show-count="false">Tweet</a><script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
                </div>
            </div>
        </section>
        <section class="description">
            <p>
            <?php echo $product['description'] ?>
            </p>

        </section>

        <section class="reviews">
            <h2>Reviews of User.Name </h2>
            <ul id="review-body">
            </ul>

            <form action="<?php echo URL_WEB ."product/postReview"?>" method="POST">
                <label>Add your review</label>
                <input type="hidden" id="getID" name="id" value="<?php echo $product['id']?>">
                <textarea name="review_text"></textarea>

                <input type="submit" value="Add Review" />
            </form>
        </section>
    </article>
