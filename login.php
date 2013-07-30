<!-- CHECK LOGIN -->

<?php 

include "recycling.php";

	if(isset($_SESSION['LoggedIn']) && isset($_SESSION['Username']))
	{
		//Check if session not expired

		$now = time();
		if($now > $_SESSION['Timeout'])
		{
			session_destroy();
			$messageID = "sessionEnd";
			header("Location: index.php?Message=".$messageID);
		}
		else
		{
			$_SESSION['Timeout'] = time() + (100*60);
		}
	}
	elseif(isset($_POST['username']) && isset($_POST['password']))
	{
		//Let user login
		$username = mysqli_real_escape_string($link, $_POST['username']);
		$password = mysqli_real_escape_string($link, $_POST['password']);
		
		$checklogin = "SELECT * FROM users WHERE Username='".$username."' AND Password='".$password."'";

			if($result = mysqli_query($link, $checklogin))
			{
				if(mysqli_num_rows($result) == 1)
				{
					$row = mysqli_fetch_array($result);
					$email = $row['EmailAddress'];
					$id = $row['User_ID'];
					
					$currentUser = new RecyclingUser($id, $link);
					$currentUser->init();
					$_SESSION['CurrentUser'] = serialize($currentUser);
					$_SESSION['Username'] = $username;
					$_SESSION['EmailAddress'] = $email;
					$_SESSION['LoggedIn'] = 1;
					$_SESSION['Timeout'] = time() + (100*60);
					
					echo '<script type="text/javascript"> window.location = "http://192.168.1.35:8888/iRecykling/orderlist.php"; return false;</script>';
				}
				else
				{
					$messageID = "loginError";
					header("Location: index.php?Message=".$messageID);
				}
				
				/* close result set */
    			mysqli_free_result($result);
				
			}
			else
			{
				echo 'B³±d po³±czenia z baz± danych';
			}
			
			
	}
	else
	{
		//Display login form
		$file = basename($_SERVER['SCRIPT_NAME']);
		if($file != 'index.php')
		{
			header( 'Location: index.php' );
		}
	}
?>
