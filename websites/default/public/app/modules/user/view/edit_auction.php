[js]
<script src="<?php echo URL_WEB."public/js/edit_auction.js"?>"></script>
[/js]
<div class="outer">
<form id="form" action="<?php echo URL_WEB?>user/postEditAuction" method="POST" enctype="multipart/form-data">
<input type="hidden" id="prodID" name="id" value="<?php echo $product['id']?>" ><br>
Name: <input type="text" name="name" value="<?php echo $product['name']?>" ><br>
Description: <textarea type="text" name="description" value="<?php echo $product['description']?>" ><?php echo $product['description']?></textarea><br>
Minimum price: <input name ="minimum_price" type="number" min="0.00" max="10000.00" step="0.01" value="<?php echo $product['bird_minimum_price']?>"><br>
Maximum price: <input name ="maximum_price" type="number" min="0.00" max="10000.00" step="0.01" value="<?php echo $product['bird_max_price']?>"><br>
HOT PRICE: <input name ="hot_price" type="number" min="0.00" max="10000.00" step="0.01" value="<?php echo $product['hot_price']?>"><br>
End Date: <input name ="end_date" type="datetime-local"   value="<?php echo date('Y-m-d\TH:i', strtotime($product['end_at']))?>"><br>
Status: <select name="status">
  <option value="Open">Open</option>
  <option value="Close">Close</option>
</select><br>
Image: <input name="header" type="file" accept="image/*"><br>
<img style="width:50px; height:40px;" src="<?php echo URL_WEB. 'public/images/' . $product['image']?>" alt=""><br>
Thumbnail: <input name="thumbnail[]" type="file" accept="image/*" multiple><br>
<?php 
//   Loop though product_thumbnail if not empty
if(!empty($product['product_thumbnail'])){
    foreach($product['product_thumbnail'] as $id => $name){
?>
<img style="width:50px; height:40px;" src="<?php echo URL_WEB . "public/images/" . $name?>" class="thumb_img" imgID="<?php echo $id?>" alt="">
<?php 
}}
//   END PRODUCT_THUMBNAIL
?>
<br>

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

<br>
<button type="submit">Save</button>
<button id="Del">Remove</button>
</form>
</div>