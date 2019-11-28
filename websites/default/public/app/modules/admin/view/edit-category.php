[css]
<link rel="stylesheet" href="<?php echo URL_WEB . 'public/css/admin/edit_category.css'?>">
[/css]
<div class="header-form">   
    <form class="form-input" action="<?php getUrlWeb()?>admin/postEditCate" method="POST">
    <input type="hidden" name="oldName" value="<?php echo $category['name']?>">
        <div class="element">
            <label  class="name_category" for="name">Name:</label >
            <input class="input_name" type="text" name="name" value="<?php echo $category['name']?>" placeholder="Edit new name">
        </div>
        <br>
        <div class="hold_button">
            <button type="submit" class="btn">Edit Category </button>
        </div>
    </form>
</div>
