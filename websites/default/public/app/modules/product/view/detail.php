[js]
<script src="<?php echo URL_WEB . "public/js/product_detail.js" ?>"></script>
[/js]
[css]
<link rel="stylesheet" href="<?php echo URL_WEB?>public/css/ibuy.css" />
[/css]
    <h1>Product Page</h1>
    <article class="product">

        
        <div>
        <img src="<?php echo URL_WEB . "public/images/".$product['image']?>" style="width:700px; height:300px" alt="product name"><br>
       
        <?php if(!empty($product['product_thumbnail']) && is_array($product['product_thumbnail'])){
            echo " <h4>Thumbnail:</h4> <br>";
                foreach($product['product_thumbnail'] as $name){    
        ?>
        <img style="width:100px;height:80px;" src="<?php echo URL_WEB . 'public/images/'.$name?>" alt="">
        <?php }} ?>
        </div>
        
        <section class="details">
            <h2><?php echo $product['name'] ?></h2>
            <?php if(!empty($product['category_name']) && !is_array($product['category_name']))
                    echo "<h3>" .$product['category_name'] . "</h3>";
            ?>
            <p>Auction created by <a href="<?php echo URL_WEB . 'user/showUserProfile?username='.$product['own_product_username'] ?>"><?php echo $product['own_product'] ?></a></p>
            <p class="price">Current bid: £<?php echo $product['current_bird_price'] ?></p>
            <?php 
            if((float)$product['bird_minimum_price'] !== 0.00){
            ?>
                <p class="price">Minimum bid: £<?php echo $product['bird_minimum_price'] ?></p>
                <?php if($product['elapsed_time'] !== FALSE){ ?>
                    <p class="price">*Please bird more than £<?php echo $product['bird_minimum_price'] ?></p>
                <?php } ?>
            <?php } ?>

            <?php 
            if((float)$product['bird_max_price'] !== 0.00){
            ?>
                <p class="price">Maximum bid: £<?php echo $product['bird_max_price'] ?></p>
            <?php } ?>
            
            <?php if($product['elapsed_time'] === FALSE){ ?>
                <h1 style="color:red!important;font-weight:bold;" >This product is end bird!</h1>
            <?php }else{ ?>
            <time>Time left: <?php echo $product['elapsed_time'] ?></time>
            <?php } ?>
            <?php if($product['elapsed_time'] !== FALSE){ ?>
            <form action="<?php echo URL_WEB . "product/postPlaceBid"?>" class="bid" method="POST">
                <input name="amount" type="number" min="0.00"  placeholder="Enter bid amount" required/>
                <input name="id" type="hidden" value="<?php echo $product['id']?>"/>
                    <input type="submit" id="placebid" value="Place bid" />
                <?php } ?>
            </form>
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
