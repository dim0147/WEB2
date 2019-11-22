<form action="<?php getUrlWeb()?>admin/postEditCate" method="POST">
    <input type="hidden" name="oldName" value="<?php echo $data['name']?>">
    <label for="name">Name</label><input type="text" name="name" value="<?php echo $data['name'] ?>"><br>
    <input type="submit" value="Edit">
</form>