<div class="navigation-bar">
  <ul>
	<li><a href="<?php echo URL_WEB?>">Home</a></li>
	<?php if (!empty($_SESSION['name'])){ ?>
    <li>
	  <label for="user">Hello, <?php echo $_SESSION['name'] ?> &#9662;</label>
      <ul class="menu-child">
		<?php if (!empty($_SESSION['is_admin'])){ ?>
			<li><a href="<?php echo URL_WEB . 'admin/dashboard'?>">Dashboard</a></li>
			<li><a href="<?php echo URL_WEB . 'admin/addCate'?>">+ Add Category</a></li>
			<li><a href="<?php echo URL_WEB . 'admin/showAuction'?>">Show All Auction</a></li>
		<?php } ?>
        <li><a href="<?php echo URL_WEB . 'user/profile'?>">Profile</a></li>
		<li><a href="<?php echo URL_WEB . 'user/addAuction'?>">+ Add Auction</a></li>
		<li><a href="<?php echo URL_WEB . 'user/logout'?>">Log out</a></li>
	  </ul>
    </li>
	<?php }else { ?>
	<li>
	  <label for="user">Admin &#9662;</label>
      <ul class="menu-child">
        <li><a href="<?php echo URL_WEB . 'admin/login'?>">Login</a></li>
		<li><a href="<?php echo URL_WEB . 'admin/register'?>">Register</a></li>
	  </ul>
	</li>
	<li>
	  <label for="user">User &#9662;</label>
      <ul class="menu-child">
        <li><a href="<?php echo URL_WEB . 'user/login'?>">Login</a></li>
		<li><a href="<?php echo URL_WEB . 'user/register'?>">Register</a></li>
	  </ul>
    </li>
	<?php } ?>
  </ul>
</div>
	
	<header>
			<h1><span class="i">i</span><span class="b">b</span><span class="u">u</span><span class="y">y</span></h1>

			<form action="<?php echo URL_WEB . 'home/search'?>" method="GET">
				<input type="text" name="string" placeholder="Search for anything" />
				<input type="submit" value="Search" />
			</form>
		</header>

		<nav>
			<ul>
			<?php if(!empty($category) && is_array($category)){ ?>
			<?php foreach($category as $value){ ?>
			 <li><a href="<?php  echo URL_WEB . 'home/search?category=' . $value['name'] ?>"><?php echo $value['name'] ?></a></li>
			<?php  }}?>
			</ul>
		</nav>

		