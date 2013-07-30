<?php include "base.php";
include "recycling.php";?>

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
        activate("contact");
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

<!-- CONTACT TABLE -->

                	<h2>Kontakt</h2>  

					
<?php
	$user = unserialize($_SESSION['CurrentUser']);
	$query = "SELECT * FROM users WHERE User_ID=$user->userID";
	if($result = mysqli_query($link, $query))
	{
		if(mysqli_num_rows($result) ==	1)
		{
			echo '<h3>Twój opiekun:</h3><br/>';
			$array = mysqli_fetch_array($result);
			echo $array['User_PHNazwisko'].'<br/>';
			echo '<strong>Tel.:</strong> '.$array['User_PHTelefon'].'<br/>';
			echo '<strong>E-mail:</strong> '.$array['User_PHMail'].'<br/><br/><br/>';
		}
	}

?>
					
                    <table class="contact" style="text-align:left;">
                    <tr><td><strong>BIOSYSTEM SA</strong><br/>
                            Ul. Wodna 4 <br/>
                            30-556 Kraków <br/>
                            Tel. +48 (12) 29 666 25<br/>
                            Fax +48 (12) 29 666 24 <br/>
                            KRS: 0000317409 <br/>
                            NIP 945-20-43-923 <br/>
                            Regon: 120111040 <br/>
                            Numer Rejestrowy GIOŚ: E0002804WZP</td>
                       	<td><strong>Biosystem Elektrorecykling Organizacja Odzysku
                            Sprzętu Elektrycznego i Elektronicznego</strong><br/>
                            Tel. +48 (12) 29 666 25, 65 547 40<br/>
                            Fax +48 (12) 29 666 24<br/>
                            e-mail: biuro@bioelektro.pl</td>
                        <td><strong>Zakład Gospodarki Komunalnej Organizacja Odzysku</strong><br/>
                            Tel. +48 (12) 655-47-40, 655-70-49, 296-66-25<br/>
                            Fax +48 (12) 296-66-24<br/>
                            e-mail: biuro@biosystem.pl</td></tr>
                    </table>

            </div>
            
                 
<!-- FOOTER -->
        <?php include "Footer.php" ?>
    </div>


  </body>
</html>