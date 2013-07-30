<?php include "base.php"; ?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Logout</title>
</head>

<body>

<?php

	session_destroy();
	header( 'Location: index.php' );
?>

</body>
</html>