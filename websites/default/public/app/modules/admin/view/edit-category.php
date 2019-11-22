<form action="<?php getUrlWeb()?>admin/postEditCate" method="POST">
    <input type="hidden" name="oldName" value="<?php echo $category['name']?>">
    <label for="name">Name</label><input type="text" name="name" value="<?php echo $category['name'] ?>"><br>
    <input type="submit" value="Edit">
</form>