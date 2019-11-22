<form action="<?php echo getURLWeb()?>admin/postRemoveCate" method="POST" onsubmit="return confirm('Do you want to delete this category?')">
    <input type="text" name="name" value="<?php echo $category['name']?>" readonly>
    <input type="submit" value="Remove">
</form>