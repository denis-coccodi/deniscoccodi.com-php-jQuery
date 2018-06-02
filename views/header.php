<!DOCTYPE html>
<html lang="en" class="no-js">
    <head>
        <!-- Required meta tags -->
        <meta name="viewport" content="width=device-width" />
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1.0, user-scalable=no">
		<meta name="keywords" content="Denis Coccodi">
        
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous"> 
        
        <!--Font Awesome-->
        <link rel="stylesheet" href="./font-awesome-4.7.0/css/font-awesome.min.css">
        <link rel="icon" type="image/png" sizes="32x32" href="images/favicon.png">
        
        <title>Denis Coccodi</title>
        <script type="text/javascript" src="modernizr/modernizr.js"></script><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/9.9.0/css/bootstrap-slider.min.css">
		<link rel="stylesheet" media="all" href="css/preload.css">
        <link rel="stylesheet" media="all" href="./css/styles.css">
    </head>
    <body><meta content="Denis Coccodi">
		
	<div id="loading">
		<img id="denisLogo" src="./images/DenisLogoB.png">
		<div id="loader"></div>
	</div>
    <div id="page" class="mx-auto p-0 m-0">
		<nav class="navbar navbar-toggleable-md navbar-light fixed-top p-0">
			<div class="d-flex flex-row pl-2 justify-content-between m-0 px-0 py-2 topBar">               
				<div class="m-0 p-0">
				  <a class="navbar-brand text-white p-0 m-0" id="brand" href="./index.php"><img src="images/DenisLogo.png"></a>
				</div>

				<div class="m-0 p-0">
					<button class="navbar-toggler-right m-0 px-3 py-1" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
						<i class="fa fa-lg fa-bars" aria-hidden="true"></i>
					</button>
				</div>
			 </div>  


			  <div class="collapse navbar-collapse" id="navbarSupportedContent">
				<ul class="navbar-nav navItems mx-auto my-auto py-0 text-truncate">

				  <li class="nav-item  py-0 my-auto d-flex justify-content-center">
					<a class="nav-link py-0  my-auto" href="?page=curriculum"><h4 class="p-2 my-auto text-truncate">

					<?php if($_SESSION["lang"] == 'eng'){ ?>

						RESUME

					<?php } else { ?>

						CURRICULUM

					<?php } ?>
					</h4></a>
				  </li>

				  <li class="nav-item py-0 my-auto d-flex justify-content-center">
					  <a class="nav-link py-0  my-auto" href="?page=timeline">
						  <h4 class="p-2 my-auto text-truncate">
							<?php if($_SESSION["lang"] == 'eng'){ ?>

								TIMELINE

							<?php } else { ?>

								STORICO

							<?php } ?>
						  </h4></a>
				  </li>

				  <li class="nav-item py-0 my-auto d-flex justify-content-center">
					<a class="nav-link text-uppercase py-0  my-auto" href="?page=exercises"><h4 class="p-2 my-auto text-truncate">

					<?php if($_SESSION["lang"] == 'eng'){ ?>

						Exercises

					<?php } else { ?>

						Esercizi

					<?php } ?>
					</h4></a>
				  </li>

				  <li class="nav-item py-0 my-auto d-flex justify-content-center">
					  <a class="nav-link py-0  my-auto" href="#" data-toggle="modal"  data-caller="info" data-target="#picsModal" id="info"><h4 class="p-2 my-auto text-truncate">

					<?php if($_SESSION["lang"] == 'eng'){ ?>

						ABOUT

					<?php } else { ?>

						INFO

					<?php } ?>
					</h4></a>
				  </li>

				</ul>

				<ul class="navbar-nav navItems py-0 my-auto langList ml-auto">
					<li class="nav-item navLang langSelection py-0 d-flex mx-auto">

						<a href="#" class="text-uppercase p-2 my-auto nav-link"<? echo (($_SESSION["lang"] == 'eng')?' data-lang="ita"><img src="./images/ita-xs.jpg" width="50" height="33" alt="Denis Coccodi">':' data-lang="eng"><img src="./images/uk-xs-ie.jpg"  width="50" alt="Denis Coccodi">') ?>
						</a>

					</li>
				</ul>

			  </div>


			</nav>
			
			<div id="bg" class="p-0 m-0 mx-auto">