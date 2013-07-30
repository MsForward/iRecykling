<?php include "base.php"?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <title>Main</title>
    
    <link rel="stylesheet" type="text/css" href="stylesheets/global.css"></link>
    <script src="scripts/script.js"></script>
    <script src="scripts/jquery-1.10.1.min.js"></script>
    
</head>
<body>
  
<!-- CHANGE STYLE FOR ACTIVE MENU CELL -->

	<script>
    window.onload = function()
    {
        activate("terms");
    }
    </script>

<!-- BACKGROUND GRAPHIC-->
	<div class="graphic">
        <img src="images/graphic.png">
    </div>
    
<!-- MAIN PAGE CONTENT -->
    
    <div class="content">  
      
<!-- MENU -->

    <?php
	$now = time();
	if((isset($_SESSION['LoggedIn'])) && ($now <= $_SESSION['Timeout']))
	{
		include "memberMenu.php";
    }
    else
    {
    	include "menu.php";
    }
	?>
        <div class="main">
        <h2>Regulamin</h2>
        <div class="column">    
		<?php include 'loremipsum.txt'?>
	</div>
    </div>
                 
<!-- FOOTER -->
        <?php include "Footer.php" ?>
    </div>


  </body>
</html>