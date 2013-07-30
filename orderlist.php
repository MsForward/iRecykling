<?php include "base.php" ?>

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
            <h2>Twoje deklaracje</h2>
			<?php
			$currentUser = unserialize($_SESSION['CurrentUser']);
			
			$query = "SELECT Dkl_Data, Dkl_Rok, Dkl_Ost FROM dklkarty AS dkl JOIN kntkarty AS knt ON dkl.Dkl_KntID = knt.Knt_ID AND dkl.Dkl_Firma = knt.Knt_Firma WHERE Knt_UserID ='".$currentUser->userID."'";
			if($result = mysqli_query($link, $query))
			{
				$rows = mysqli_num_rows($result);
				if($rows != 0)
				{
					$table = '<table class="productList"><th>Data dodania</th><th>Rok deklaracji</th><th>Deklarowane miesiące</th><th></th>';
					
					while ($array = mysqli_fetch_array($result)) 
					{
						$table .= '<tr><td>'.$array['Dkl_Data'].'</td><td>'.$array['Dkl_Rok'].'</td></tr>';
					}
					
					$table .= '</table>';
					
					print($table);
					mysqli_free_result($result);
				}
				else
				{
					echo '<p>Nie wypełniłeś jeszcze żadnych deklaracji.<br/><br/> <form action="list.php" method="post"><input class="button" type="submit" value="Dodaj deklarację"></form>';
				}
			}
			else
			{
				echo 'Błąd połączenia z bazą danych';
			}
			?>
        </div>
                 
<!-- FOOTER -->
        <?php include "Footer.php" ?>
    </div>


  </body>
</html>