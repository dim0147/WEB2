<div class="outer">
<form action="<?php echo URL_WEB?>user/postAddAuction" method="POST" enctype="multipart/form-data">
Name: <input type="text" name="name" placeholder="Name product" required><br>
Description: <textarea type="text" name="description" placeholder="Product description" required></textarea><br>
Minimum price: <input name ="minimum_price" type="number" min="0.00" max="10000.00" step="0.01" placeholder="Minimum price"><br>
Maximum price: <input name ="maximum_price" type="number" min="0.00" max="10000.00" step="0.01" placeholder="Maximum price"><br>
HOT PRICE: <input name ="hot_price" type="text" min="0.00" max="10000.00"  placeholder="Hot Price"><br>
End Date: <input name ="end_date" type="datetime-local"   placeholder="End Time" required><br>
Status: <select name="status">
  <option value="Open">Open</option>
  <option value="Close">Close</option>
</select><br>
Image: <input name="header" type="file" accept="image/*" required><br>
Thumbnail: <input name="thumbnail[]" type="file" accept="image/*" multiple><br>
<?php  
    if(!empty($category)){
        foreach($category as $value){
?>
    <input type="checkbox" class='form' value="<?php echo $value['id'];?>" name="category[]" checked/><?php echo $value['name'];?><br>
<?php 
        }}
?>

<button type="submit">Add new auction</button>
</form>
</div>