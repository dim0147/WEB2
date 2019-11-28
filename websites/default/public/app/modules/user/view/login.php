[css]
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="<?php echo URL_WEB . 'public/css/user/user_style.css'?>">
[/css]


<div class="header">
    <div class="header-main">
        <h1> User Log in </h1>
        <div class="header-body">
            <form action="<?php echo URL_WEB . 'user/postLogin'?>" method="POST">
                <div class="user_div">
                    <i class="fa fa-user icon"></i>
                    <input type="text" class="input_hive" name="username" placeholder="Username">
                </div>
                <div class="user_div">
                    <i class="fa fa-lock"></i>
                    <input type="text" class="input_hive" name="password" placeholder="Password">
                </div>
                <a class="suggest" href="<?php echo URL_WEB.'user/register'?>"> Don't have account? Register here!</a><br>
                <button type="submit" class="btn">Log in</button>
            </form>

        </div>
    </div>

</div>