<form action="<?php echo URL_WEB . 'user/postRegister'?>" method="POST">
            <label>Your name</label>
            <input type="text" name="name" require>
            <label>Username</label>
            <input type="text" name="username" require>
            <label>Password</label>
            <input type="password" name="password" require>
            <input type="submit" value="Register">

</form>