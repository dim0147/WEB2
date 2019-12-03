[js]
<script src="<?php echo URL_WEB."public/js/edit_auction.js"?>"></script>
[/js]
[css]
<link rel="stylesheet" href="<?php echo URL_WEB ?>public/css/ibuy.css">
<link rel="stylesheet" href="<?php echo URL_WEB ?>public/css/user/profile.css">
[/css]





<div class="container">
<form id="form" action="<?php echo URL_WEB?>user/postEditAuction" method="POST" enctype="multipart/form-data">
<input type="hidden" id="prodID" name="id" value="<?php echo $product['id']?>" ><br>
    <div class="profile">
        <h1 class="title">Edit Auction</h1>

        <div class="field">
            <h3 class="field_t">Name:</h3>
            <input type="text" name="name" value="<?php echo $product['name']?>" class="field_v" required>
        </div>

        <div class="field">
            <h3 class="field_t">Description:</h3>
            <textarea type="text" name="description" value="<?php echo $product['description']?>" required><?php echo $product['description']?></textarea>
        </div>

        <div class="field">
            <h3 class="field_t">Minimum price:</h3>
            <input class="field_v" name ="minimum_price" type="number" min="0.00" max="10000.00" step="0.01" value="<?php echo $product['bird_minimum_price']?>">
        </div>

        <input type="hidden" value="0.00" name ="maximum_price">

        <div class="field">
            <h3 class="field_t">HOT PRICE:</h3>
            <input class="field_v"name ="hot_price" type="number" min="0.00" max="10000.00" step="0.01" value="<?php echo $product['hot_price']?>">
        </div>

        <div class="field">
            <h3 class="field_t">End Date:</h3>
            <input class="field_v" name ="end_date" type="datetime-local"   value="<?php echo date('Y-m-d\TH:i', strtotime($product['end_at']))?>" required>
        </div>

        <div class="field">
        <h3 class="field_t">Status:</h3>
          <select name="status">
            <option value="Open">Open</option>
            <option value="Close">Close</option>
          </select>
        </div>

        <div class="field">
            <h3 class="field_t">Image:</h3>
            <input class="field_v" name="header" type="file" accept="image/*">
        </div>
        <div class="field">
            <img style="width:50px; height:40px;" src="<?php echo URL_WEB. 'public/images/' . $product['image']?>" alt="">
        </div>

        <div class="field">
            <h3 class="field_t">Thumbnail:</h3>
            <input class="field_v" name="thumbnail[]" type="file" accept="image/*" multiple>
        </div>
        <div class="field">
            <?php 
                //   Loop though product_thumbnail if not empty
                if(!empty($product['product_thumbnail']) && is_array($product['product_thumbnail'])){
                    foreach($product['product_thumbnail'] as $id => $name){
            ?>
                <img style="width:50px; height:40px;" src="<?php echo URL_WEB . "public/images/" . $name?>" class="thumb_img" imgID="<?php echo $id?>" alt="">
            <?php 
                }}
                //   END PRODUCT_THUMBNAIL
            ?>
        </div>

        <div class="field">
            <?php  
            //  If not empty category
                if(!empty($product['category_name']) && is_array($product['category_name'])){
                    foreach($product['category_name'] as $id => $value){
            ?>
                <input type="checkbox" class='form' value="<?php echo $id;?>" name="category[]" checked/><?php echo $value;?><br>
            <?php 
            //   END category
                    }}
            ?>


            <?php  
                if(!empty($cateOutProduct) && is_array($cateOutProduct)){
                    foreach($cateOutProduct as $id=>$value){
            ?>
                <input type="checkbox" class='form' value="<?php echo $id;?>" name="category[]"/><?php echo $value;?><br>
            <?php 
                    }}
            ?>
        </div>

    <button type="submit" class="update">Update</button>
    <button id="Del" class="update" style="background:red!important"> Remove</button>
    </div>
    </form>
  </div>




