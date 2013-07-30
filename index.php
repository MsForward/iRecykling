<?php include "base.php";?>
<!DOCTYPE html>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>BIOSYSTEM iRecykling - Elektroniczne Deklaracje dla Firm</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap..min.css" />
	<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.no-icons.min.css" rel="stylesheet" />
	<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet" />
	<link rel="stylesheet" href="stylesheets/main.css" />
	<link rel="shortcut icon" href="favicon.ico" />
    
</head>
<body>

	<!-- Change style for active menu cell -->
	<script>
    window.onload = function()
    {
    	document.getElementById("logIn").style.clear;
		$("#logIn").removeAttr("href");
		$("#logIn").addClass("active");
    }
    </script>
	
	<?php include "login.php";
			$sessionEnd = "Z powodu nieaktywności zakończyła się sesja użytkownika. Prosimy o ponowne zalogowanie się do systemu.";
			$loginError = "Wpisano niepoprawny login lub hasło";
	?>
	
	<!-- Pop-up login form-->
	
	<div class="coverUp">
		<div class="logInWrapper">
			<form class="form-signin" action="orderList.php" method="post">
				<p class="closeWindow"><i class="icon-remove"></i></p>
				<h2 class="form-signin-heading">Logowanie</h2>
				<input type="text" name="username" class="input-block-level" placeholder="Login" autofocus required>
				<input type="password" name="password" class="input-block-level" placeholder="Hasło" autofocus required>
				<input class="btn btn-large btn-primary" type="submit" value="Zaloguj się" />
				<br/><br/><a href="">Zapomniałeś hasła?</a>
			</form><br/>
			<aside>
				<h2>Nie masz konta?</h2>
				<p>Skontaktuj się z nami w celu podpisania umowy z firmą Biosystem.</p><br/>
				<p>Tel. +48 (12) 29 666 25</p><br/>
				<a href="contact.php"><button class="btn btn-large btn-info" type="button">Kontakt</button></a>
			</aside>
		</div>
	</div>

	<div class="wrapper">

		<!-- Logo and navigation bar-->
		<header>
		
			<img src="images/homeLogo.png">	
				<!-- Menu -->
			<?php
			//Check if user session is still valid and display appropriate menu
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
		</header>

		<div class="content">
			<div id="welcome">
				
				<?php 	
					if(isset($_GET['Message']))
					{
						switch ($_GET['Message'])
						{
							Case 'loginError':
								$message = $loginError;
								break;
							Case 'sessionEnd':
								$message = $sessionEnd;
								break;
						}
						echo '<p class="message"><img src="images/red-warning-sign.png" width="20px" align="left" style="padding:0px 5px;">'.$message.'</p>';
					}
					else
					{
						echo '<h1>BIOSYSTEM iRecykling</h1><p>Witamy na stronie BIOSYSTEM iRecykling.</p>';
					}

					?>

					<br/><button class="btn btn-large btn-primary" type="button" id="addDecButton">Dodaj deklarację!</button>
			</div>
			<aside class="left" id="homeInfo">
				<h2>About iRecykling</h2>
				<p>Some info about this page</p>
			</aside>
		</div>
	</div>
	
	<footer>
		
		<?php include "footer.php" ?>
	</footer>
	
	<!--Load scripts-->
	<script src="scripts/jquery-1.10.1.min.js"></script>
	<script src ="bootstrap/js/bootstrap.min.js"></script>
    <script src="scripts/script.js"></script>
	<script>
	$(document).ready(function() {
		$("#addDecButton").click(function() {
			$(".coverUp").show();
		});
		
		$(".closeWindow").click(function() {
			$(".coverUp").hide();
		});
		
		$("footer img").mouseenter(function() {
			var src = $(this).attr("src");
			var newSrc = src.replace(".png", "Over.png");
			$(this).attr("src", newSrc);
		});
		
		$("footer img").mouseleave(function() {
			var src = $(this).attr("src");
			var newSrc = src.replace("Over.png", ".png");
			$(this).attr("src", newSrc);
		});
		
	});
	</script>
    

</body>
</html>