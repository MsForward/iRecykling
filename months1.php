<!doctype html>
<html>
<head>
<meta charset="iso-8859-2">
<title>Untitled Document</title>

    <link rel="stylesheet" type="text/css" href="stylesheets/global.css"></link>
</head>

<body style="background:none">

<label>Deklarowane miesi�ce:<br/></label>


            <div style="width:130px; height:auto; float:left;"> 
			
			<?php 
			
				//Display months with checkboxes
				$months = array("Stycze�", "Luty", "Marzec", "Kwiecie�", "Maj", "Czerwiec", "Lipiec", "Sierpie�", "Wrzesie�", "Pa�dziernik", "Listopad", "Grudzie�");
				for($i = 0; $i < 6; $i++) 
				{
					echo '<input type="checkbox" id="month'.$i.'" name="month'.$i.'" value="0'.($i+1).'">'.$months[$i].'</input><br/>';
				} 
				echo '</div><div style="width:130px; height:auto; float:left;">';
				for($i = 6; $i < 12; $i++) 
				{
					echo '<input type="checkbox" id="month'.$i.'" name="month'.$i.'" value="'.($i+1).'">'.$months[$i].'</input><br/>';
				}
				echo '</div>'; 
			?>
            
            <div class="form" style="height:auto; float:left; margin-left:3%; padding:1%;">
            <label>Zaznacz:<br/></label> 
            <div style="width:130px; float:left"> 
            <input id="q1" type="checkbox">Kwarta� I</input><br/>
            <input id="q2" type="checkbox">Kwarta� II</input><br/>
            <input id="q3" type="checkbox">Kwarta� III</input><br/>
            <input id="q4" type="checkbox">Kwarta� IV</input><br/>
            </div>
            <div style="width:130px; float:left">
            <input id="p1" type="checkbox">P�rocze I</input><br/>
            <input id="p2" type="checkbox">P�rocze II</input><br/>
            </div>
            <div style="width:100px; float:left">
            <input id="reset" type="checkbox">Resetuj zaznaczenie</input><br/>
            </div>
            </div>
            <div style="clear:both;"></div>

</body>
</html>