<?php
include "base.php";
include "recycling.php";

//Check form data before submit
if(!isset($_POST['month']))
{
	$return['msg'] = 'Nie zaznaczono żadnego miesiąca lub wystąpił błąd podczas pobierania danych.';
	echo json_encode($return);
	return;
}

if(!isset($_POST['formType']) || !isset($_POST['company']))
{
	$return['msg'] = 'Wybierz jedną z kategorii produktów i wypełnij odpowiednie pola.';
	echo json_encode($return);
	return;
}

//Get current user's ID
$currentUser = unserialize($_SESSION['CurrentUser']);

//Create new declaration object
$currentDeclaration = new RecyclingDeclaration($link);
$result = $currentDeclaration->init($currentUser->userID, $_POST);
if($result === true)
{	
	//Check if exists declaration for the same period
	$isKOR = $currentDeclaration->checkIfKOR();
	
	if($isKOR === false)
	{
		if(($result = $currentDeclaration->insertIntoDatabase()) === true)
		{
			$return['msg'] = 'Deklaracja zapisana z powodzeniem';
			echo json_encode($return);
		}
		else
		{
			$return['msg'] = $result;
			echo json_encode($return);
		}
	}
	else if($isKOR === true)		//Send back question if user wants to submit correction
	{
		$return['dec'] = serialize($currentDeclaration);
		$return['msg'] = 'KOR';
		echo json_encode($return);
	}
	else							//Display message about duplicate months
	{
		$return['msg'] = 'Została już złożona deklaracja za miesiące: '.$isKOR.'. Nie jest możliwa deklaracja za zaznaczone przez ciebie miesiące.';
		echo json_encode($return);
	}
}
else
{
	echo $result;
}
?>