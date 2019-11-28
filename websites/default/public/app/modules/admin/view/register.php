
[css]
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="<?php echo URL_WEB . 'public/css/admin/admin_style.css'?>">
[/css]


<div class="header">
    <div class="header-main">
        <h1 class="danger"> ADMIN REGISTER </h1>
        <div class="header-body">
        <form action="<?php echo URL_WEB . 'admin/postRegister'?>" method="POST">
                <div class="user_div">
                    <i class="fa fa-address-book"></i>
                    <input type="text" class="input_hive" name="name" placeholder="Your name">
                </div>
                <div class="user_div">
                    <i class="fa fa-user icon"></i>
                    <input type="text" class="input_hive" name="username" placeholder="Username">
                </div>
                <div class="user_div">
                    <i class="fa fa-lock"></i>
                    <input type="text" class="input_hive" name="password" placeholder="Password">
                </div>
                <button type="submit" class="btn">Register</button>
            </form>

        </div>
    </div>

</div>