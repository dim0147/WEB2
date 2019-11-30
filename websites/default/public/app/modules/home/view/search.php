[css]
<link rel="stylesheet" href="<?php echo URL_WEB?>public/css/ibuy.css" />
[/css]
    <img src="<?php echo URL_WEB?>public/randombanner.php" alt="Banner" />

    <main>
        <h1><?php echo $searchStr ?></h1>
        <?php if(!empty($products && is_array($products))){ ?>
        <ul class="productList">
            <?php foreach($products as $prod){ ?>
            <li>
                <img src="<?php echo URL_WEB . "public/images/" . $prod['image']?>" alt="product name">
                <article>
                    <h2><?php echo $prod['name']; ?></h2>
                    <h3><?php echo $prod['category_name'];?></h3>
                    <p><?php echo $prod['description']; ?></p>

                    <p class="price">Current bid: £<?php echo $prod['current_bird_price'];?></p>
                    <a href="<?php echo URL_WEB . "product/detail?id=".$prod['id']?>" class="more">More &gt;&gt;</a>
                </article>
            </li> 
        <?php }?>
           
        </ul>
            <?php }else{ ?>
        
        <h1>There is no result to display!</h1>
    <?php } ?>

        <hr />
         