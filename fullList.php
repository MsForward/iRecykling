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

    <?php include "login.php" ?>
  
<!-- CHANGE STYLE FOR ACTIVE MENU CELL -->

	<script>
    window.onload = function()
    {
        activate("dec");
    }
    </script>

<!-- BACKGROUND GRAPHIC-->
	<div class="graphic">
        <img src="images/graphic.png">
    </div>

<!-- MAIN PAGE CONTENT -->
    
    <div class="content">  
      
<!-- MENU -->

    <?php include "memberMenu.php" ?>
    
        <div class="main">
        <h2>Odpady</h2>
  		<div class="column"><p>Aby złożyć deklarację na dodatkowe odpady przejdź do zakładki 'Dodaj deklarację' i wybierz 'Pokaż wszystkie odpady'</p></div><br/><br/>
            
<!--Choose product database radio buttons-->
                <input type="radio" name="products" value="OPAK_LIST" class="company" onChange="changeCompany(this.value)"><strong>Opakowania</strong>&nbsp;
                <input type="radio" name="products" value="BAT_LIST" class="company" onChange="changeCompany(this.value)"><strong>Baterie</strong>&nbsp;
                <input type="radio" name="products" value="OSW_LIST" class="company" onChange="changeCompany(this.value)"><strong>Oświadczenia</strong>&nbsp;
                <input type="radio" name="products" value="SPRZ_LIST" class="company" onChange="changeCompany(this.value)"><strong>Sprzęt elektryczny i elektroniczny</strong>
		
        <div id="productTable">
        </div>
        
         </div>
                 
<!-- FOOTER -->
        <?php include "Footer.php" ?>
    </div>


  </body>
</html>