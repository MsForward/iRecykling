
<?php 

session_start();

$dbhost = "localhost";
$dbname = "irecykling";
$dbuser = "root";
$dbpass = "obroczek65";

$link = new MySQLi($dbhost, $dbuser, $dbpass, $dbname);
$link->set_charset("utf8");

//Check connection
if (mysqli_connect_errno()) 
{
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

?>

