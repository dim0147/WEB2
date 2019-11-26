	<header>
			<h1><span class="i">i</span><span class="b">b</span><span class="u">u</span><span class="y">y</span></h1>

			<form action="#">
				<input type="text" name="search" placeholder="Search for anything" />
				<input type="submit" name="submit" value="Search" />
			</form>
		</header>

		<nav>
			<ul>
			<?php if(!empty($category)){ ?>
			<?php foreach($category as $value){ ?>
			<?php  echo "<li><a href='#'>" . $value['name'] . "</a></li>";?>
			<?php  }}?>
			</ul>
		</nav>

		