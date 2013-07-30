<?php
	include "base.php";
?>	 
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="stylesheets/global.css"></link>
    <script src="scripts/script.js"></script>
       <link href='http://fonts.googleapis.com/css?family=Marmelad&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
	<script src="http://code.jquery.com/jquery-1.10.0.min.js"></script>

</head>
<body>

<?php
	//Get client products array from database
	include "products.php";	
	
	//Create variable containing product table
	$table = '<table class="products">';
	
	//Display headers for chosen product database
	$headers = array(
		array(
			"names"=>array("Nazwa produktu", "Ilość", "Jednostka"),
			"widths"=>array("auto", "70", "100")
			),
		array(
			"names"=>array("Nazwa produktu", "Kg.", "Szt."),
			"widths"=>array("auto", "70", "70")
			)
		);
	
	switch($_GET['products'])
	{
	Case '89857': 
		$c = 0;
		break;
	Case '89858': 
		$c = 1; 
		break;			
	}
	
	for($i = 0; $i<count($headers[$c]['names']); $i++)
	{
		$table .= '<th width="'.$headers[$c]['widths'][$i].'">'.$headers[$c]['names'][$i].'</th>';
	}
	
	//Output product table
	for($k = 0; $k<count($userProducts[$c]); $k++)
	{
		//Display group name
		$table .= '<tr><td colspan="3" style="background-color:#FFF">
					<img id="arrow'.$k.'" src="images/arrow.png" width="20px" style="float:left; margin:5px 10px 5px; cursor:pointer;">
					<h4>'.mb_strtoupper($userProducts[$c][$k]['Twr_Grupa'], "utf-8").'</h4>
					</td></tr>';
					
		//Save script for expanding group products			
		echo '<script>
		$(document).ready(function()
		{
			$(".row'.$k.'").hide();
			$("#arrow'.$k.'").click(function()
			{
				$(".row'.$k.'").toggle(); 
				if ($(".row'.$k.'").is(":visible"))
				{ 
					$("#arrow'.$k.'").attr("src", "images/arrowdown.png");
				}
				else
				{
					$("#arrow'.$k.'").attr("src", "images/arrow.png");
				}
			});
		});
		</script>';
		
		//Output each product in a group
		for($m = 0; $m<count($userProducts[$c][$k]['Twr']); $m++)
		{
			$table .= '<tr id="row'.$k.'_'.$m.'" class="row'.$k.'"><td>'.ucfirst($userProducts[$c][$k]['Twr'][$m]['Twr_Nazwa']).'</td>
						<form id="amount'.$k.'_'.$m.'"><td style="width:70px"><input type="text" size="5"></td>';
			
			//Output additional text input
			switch($_GET['products'])
			{
			Case '89857': 
				$table .= '<td style="width:100px;">'.$userProducts[$c][$k]['Twr'][$m]['Twr_Jm'].'</td></tr>';
				break;
			Case '89858': 
				$table .= '<td style="width:70px;"><input type="text" size="5"></td>';
				break;	
			}
		}
	}			
	
	//Print product table
	$table .= '</table>';
	echo $table;
		
?>

</body>
</html>