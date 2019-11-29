[css]
<link rel="stylesheet" href="<?php echo URL_WEB?>public/css/ibuy.css">
<link rel="stylesheet" href="<?php echo URL_WEB ?>public/css/user/profile.css">
[/css]

<div class="container">
    <form class="profile" action="<?php echo URL_WEB?>user/postChangePassword" method="POST">
        <h1 class="title">Change Password</h1>

        <div class="field">
            <h3 class="field_t">Old Password:</h3>
            <input name="old_password" class="field_v" type="password">
        </div>

        <div class="field">
            <h3 class="field_t">New Password:</h3>
            <input name="new_password" class="field_v" type="password">
        </div>

        <div class="field">
            <h3 class="field_t">Confirm Password:</h3>
            <input name="confirm_password" class="field_v" type="password">
        </div>

        <button type="submit" class="update">Update</button>
        
    </form>
</div>