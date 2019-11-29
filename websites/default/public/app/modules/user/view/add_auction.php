[css]
<link rel="stylesheet" href="<?php echo URL_WEB ?>public/css/ibuy.css">
<link rel="stylesheet" href="<?php echo URL_WEB ?>public/css/user/profile.css">
[/css]

<div class="container">
<form action="<?php echo URL_WEB?>user/postAddAuction" method="POST" enctype="multipart/form-data">
    <div class="profile">
        <h1 class="title">Add Auction</h1>

        <div class="field">
            <h3 class="field_t">Name:</h3>
            <input name="name" placeholder="Name product"class="field_v" required>
        </div>

        <div class="field">
            <h3 class="field_t">Description:</h3>
            <textarea name="description" placeholder="Product description" required></textarea>
        </div>

        <div class="field">
            <h3 class="field_t">Minimum price:</h3>
            <input class="field_v" name ="minimum_price" type="number" min="0.00" max="10000.00" step="0.01" placeholder="Minimum price">
        </div>

        <div class="field">
            <h3 class="field_t">Maximum price:</h3>
            <input class="field_v" name ="maximum_price" type="number" min="0.00" max="10000.00" step="0.01" placeholder="Maximum price">
        </div>

        <div class="field">
            <h3 class="field_t">HOT PRICE:</h3>
            <input class="field_v" name="hot_price" type="number" min="0.00" max="10000.00"  placeholder="Hot Price">
        </div>

        <div class="field">
            <h3 class="field_t">End Date:</h3>
            <input class="field_v" name ="end_date" type="datetime-local"   placeholder="End Time" required>
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
            <input class="field_v" name="header" type="file" accept="image/*" required>
        </div>

        <div class="field">
            <h3 class="field_t">Thumbnail:</h3>
            <input class="field_v" name="thumbnail[]" type="file" accept="image/*" multiple>
        </div>

        <?php  
    if(!empty($category) && is_array($category)){
      echo '<h1 class="tb_field_hot" >NOTE: PLEASE CHOOSE AT LEAST 1 CATEGORY! </h1>';
              foreach($category as $value){
      ?>
          <input type="checkbox" class='form' value="<?php echo $value['id'];?>" name="category[]"><?php echo $value['name'];?><br>
      <?php 
              }}
      ?>

    <button type="submit" class="update">Add new auction</button>
    </div>
    </form>
  </div>