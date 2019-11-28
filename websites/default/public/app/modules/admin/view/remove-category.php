[css]
<link rel="stylesheet" href="<?php echo URL_WEB . 'public/css/admin/add_category.css'?>">
[/css]
<div class="header-form">   
    <form class="form-input" action="<?php getUrlWeb()?>admin/postRemoveCate" method="POST" onsubmit="return confirm('Do you want to delete this category?')">
        <div class="element">
            <label  class="name_category" for="name">Name:</label >
            <input class="input_name" type="text" name="name" value="<?php echo $category['name']?>" readonly>
        </div>
        <br>
        <div class="hold_button">
            <button type="submit" class="btn">Remove</button>
        </div>
    </form>
</div>
