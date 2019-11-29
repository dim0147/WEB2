[css]
<link rel="stylesheet" href="<?php echo URL_WEB?>public/css/ibuy.css">
<link rel="stylesheet" href="<?php echo URL_WEB ?>public/css/user/profile.css">
[/css]

<div class="container">
    <form class="profile" action="<?php echo URL_WEB?>user/postUpdateProfile" method="POST">
        <h1 class="title">Update Profile</h1>

        <div class="field">
            <h3 class="field_t">Your name:</h3>
            <input name="name" class="field_v" value="<?php echo $user['name'] ?>">
        </div>

        <button type="submit" class="update">Update</button>
        
    </form>
</div>