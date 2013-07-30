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
        activate("acc");
    }
    </script>

<!-- BACKGROUND GRAPHIC-->
	<div class="graphic">
        <img src="images/graphic.png">
    </div>
    
<!-- MAIN PAGE CONTENT -->
    
    <div class="content"> 
       
<!-- MENU -->

    <?php include "memberMenu.php"?>

        <div class="main">
            
<!-- PASSWORD FORM -->
            <h2>ZMIANA HASŁA</h2>
            	<div class="left">
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent imperdiet justo vel ipsum ullamcorper, eu feugiat sapien rutrum. Cras laoreet lacus quis nisl adipiscing lacinia. Praesent vitae pulvinar ligula, nec consequat quam. Morbi ac fermentum neque, ut cursus nunc. Aenean vestibulum egestas libero et congue. Morbi in est ut sapien feugiat dignissim. Nunc volutpat augue vel dictum vulputate. Praesent tristique est in erat laoreet egestas. Nullam ut arcu erat. In velit nisl, convallis in egestas sed, commodo non ante. Nulla vitae elit nisi. Nam porttitor, urna at cursus dapibus, nibh libero commodo lacus, vel consequat risus purus in turpis. Suspendisse dignissim elit eu arcu dignissim consectetur. Fusce sed facilisis augue, et tincidunt neque. Cras et nisl a velit accumsan semper at et enim.<br/><br/></p>

                </div>
            
            <div class="right">
            <div class="form">
                	
                    <form action="" onSubmit="">
                    <table>
                        <tr><td>Stare hasło: </td><td><input type="password" name:"oldPassword" autofocus required size="15"></td></tr>
                        <tr><td>Nowe hasło: </td><td><input type="password" name="newPassword" required size="15"></td></tr>
                        <tr><td>Powtórz hasło: </td><td><input type="password" name="repeatPassword" required size="15"></td></tr>
                        <tr><td></td><td><input class="button" type="submit" value="Zmień"></td></tr>
                    </table>
                    </form>
                </div>
            </div>
            

            <div style="clear: both;"></div>
         </div>
                 
<!-- FOOTER -->
        <?php include "Footer.php" ?>
    </div>


  </body>
</html>