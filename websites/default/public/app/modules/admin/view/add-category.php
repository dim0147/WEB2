[css]
<link rel="stylesheet" href="<?php echo URL_WEB . 'public/css/admin/add_category.css'?>">
[/css]
<div class="header-form">   
    <form class="form-input" action="<?php getUrlWeb()?>admin/postAddCate" method="POST">
        <div class="element">
            <label  class="name_category" for="name">Name:</label >
            <input class="input_name" type="text" name="name" placeholder="Add new category">
        </div>
        <br>
        <div class="hold_button">
            <button type="submit" class="btn">Add Category </button>
        </div>
    </form>
</div>
