<html>

<head>
	<title><?php echo $title?></title>
	<meta charset="UTF-8" />
	<link rel="stylesheet" href="<?php echo URL_WEB?>public/css/ibuy.css" />
	<@yield css>
</head>
<body>

<div class="header">
	<?php 
	if(!empty($_SESSION['name'])){
	?>
	<h4 style="float:right;display"><?php echo "Hello, ".$_SESSION['name'] ?></h4>
	<?php } ?>

</div>