<?php
include "base.php";
include "recycling.php";
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  
</head>
<body>

<?php

$currentUser = unserialize($_SESSION['CurrentUser']);

$result = formFactory($_GET['products'], $link, $currentUser->userID);
echo $result;

?>

</body>
</html>