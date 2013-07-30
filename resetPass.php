<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">

  <head>
    <meta charset="iso-8859-2">
    <meta name="generator" content="">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <title></title>
    
	<link rel="stylesheet" type="text/css" href="stylesheets/global.css"></link>
	<script src="scripts/script.js"></script>
	
    <!--[if IE]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
  </head>
  <body>
  
  <div class="menu">
    <ul>
		<li id="menuChange"><a href="index.php">Zaloguj</a></li>
		<li id="logged" onmouseover="expandMenu('account')" >Konto</li>
		<li id="logged" onmouseover="expandMenu('order')" >Zamówienia</li>	
		<li><a href="Terms.php">Regulamin</a></li>
		<li><a href="Contact.php">Kontakt</a></li>
	</ul>	
			<ul class="secondLevel" id="account" style="margin-left:170px;">
				<li><a href="ChangePassword.php">Zmień hasło</a></li>
			</ul>
			<ul class="secondLevel" id="order" style="margin-left:340px;">
				<li><a href="List.php">Dodaj Zamówienie</a></li>
				<li><a href="orderList.php">Lista Zamówień</a></li>
				<li><a href="fullList.php">Pełna lista produktów</a></li>
			</ul>
  </div>

  </body>
</html>