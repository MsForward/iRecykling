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
		<li id="logged" onmouseover="expandMenu('order')" >Zam�wienia</li>	
		<li><a href="Terms.php">Regulamin</a></li>
		<li><a href="Contact.php">Kontakt</a></li>
	</ul>	
			<ul class="secondLevel" id="account" style="margin-left:170px;">
				<li><a href="ChangePassword.php">Zmie� has�o</a></li>
			</ul>
			<ul class="secondLevel" id="order" style="margin-left:340px;">
				<li><a href="List.php">Dodaj Zam�wienie</a></li>
				<li><a href="orderList.php">Lista Zam�wie�</a></li>
				<li><a href="fullList.php">Pe�na lista produkt�w</a></li>
			</ul>
  </div>

  </body>
</html>