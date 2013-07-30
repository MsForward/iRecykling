<?php
include "base.php";
include "recycling.php";

if(isset($_POST['declaration']))
{
	$currentDeclaration = unserialize($_POST['declaration']);
	
	$currentDeclaration->changeLink($link);
	if(($result = $currentDeclaration->insertIntoDatabase()) === true)
		{
			echo 'Deklaracja zapisana z powodzeniem';
		}
		else
		{
			echo $result;
		}
}
?>