<!DOCTYPE html>
<html lang="en">
	<?php
        require_once "config.php";
        session_start();
        ?>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title>Movie Database</title>
		//CSS
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
		<link rel="stylesheet" id="stylesheet" href="css/stylesheet.css">
		<script src="custom.js"></script>
		
	</head>

	<header>
		//Navbar
		<input type="checkbox" id="menuButton" />
		<label for="menuButton" class="menu-button-label">
			<div class="white-bar"></div>
			<div class="white-bar"></div>
			<div class="white-bar"></div>
			<div class="white-bar"></div>
		</label>

		// add search bar in here
	</header>
</html>
