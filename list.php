<?php include "base.php"?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <title>Main</title>

<!--INCLUDE SCRIPTS AND CSS-->  
    <!--Global stylesheet-->
    <link rel="stylesheet" type="text/css" href="stylesheets/global.css"></link>
    <!--Global script-->
    <script type="text/javascript" src="scripts/script.js"></script>
    <!--JQuery--> 
    <script type="text/javascript" src="scripts/jquery-1.10.1.min.js"></script>
    <!--Form validation plugin-->
    <script type="text/javascript" src="scripts/jquery.validate/lib/jquery.form.js"></script>
    <script type="text/javascript" src="scripts/jquery.validate/localization/messages_pl.js"></script>
    
</head>
<body>

<!--CHECK LOGIN INFORMATION-->
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

<!--SIDE FIXED-POSITIONED SUBMIT BUTTONS-->
    <div id="sidebar1">
    <ul>
<!--View declaration as PDF-->
    <li><button type="submit" id="pdfButton"><img id="pdf" src="images/fullpdf.png" height="50px"></button></li>
<!--Save declaration to database-->
    <li><button type="submit" id="saveButton"><img id="save" src="images/fullsave.png" height="50px"></button></li>
    </ul>
    </div>

<p id="dclStatus"></p>    


<!-- MAIN PAGE CONTENT -->    
    <div class="content">
        
<!-- MENU -->
    <?php include "memberMenu.php" ?>

<!--MAIN CONTENT BLOCK-->   
        <div class="main">
        <h2>Deklaracja</h2>
        
<!--Declaration content-->
        <div class="declare">
        

<!-- Declaration form-->
			<form method="post" action="save.php" class="declare" id="declare" name="declare">
 

<!--Choose product database radio buttons-->

			<label class="firstLevel">Kategoria</label><br/>

                <input type="radio" name="products" value="OPAK" id="OPAK" onChange="changeCompany(this.value)"><label for="OPAK"><strong>Opakowania</strong></label>&nbsp;
                <input type="radio" name="products" value="BAT" id="BAT" onChange="changeCompany(this.value)"><label for="BAT"><strong>Baterie</strong></label>&nbsp;
                <input type="radio" name="products" value="SPRZ" id="SPRZ" onChange="changeCompany(this.value)"><label for="SPRZ"><strong>Sprzęt elektryczny i elektroniczny</strong></label>&nbsp;
                <input type="radio" name="products" value="OSW" id="OSW" onChange="changeCompany(this.value)"><label for="OSW"><strong>Kampanie EDU</strong></label>

                <br/><br/>
				
 
            <label class="firstLevel" for="year">Rok deklaracji:</label><br/>
            <input type="text" name="year" id="year" value="2012" required number minlength = "4" maxlength="4"/><br/><label for="year" class="error"></label><br/>
            
<!--Declare months form-->			

<label class="firstLevel">Deklarowane miesiące:<br/></label>
            <div id="monthCheck" style="width:130px; height:auto; float:left;"> 
			
			<?php 
			
				//Display months with checkboxes
				$months = array("Styczeń", "Luty", "Marzec", "Kwiecień", "Maj", "Czerwiec", "Lipiec", "Sierpień", "Wrzesień", "Październik", "Listopad", "Grudzień");
				for($i = 0; $i < 9; $i++) 
				{
					echo '<input type="checkbox" class="monthCheck" id="month'.$i.'" name="month[]" value="0'.($i+1).'"/><label for="month[]">'.$months[$i].'</label><br/>';
					if($i == 5)
					{
						echo '</div><div style="width:130px; height:auto; float:left;">';
					}
				}
				for($i = 9; $i < 12; $i++) 
				{
					echo '<input type="checkbox" class="monthCheck" id="month'.$i.'" name="month[]" value="'.($i+1).'"/><label for="month[]">'.$months[$i].'</label><br/>';
				}
				echo '</div>'; 
			?>
            
            <div class="form" style="height:auto; float:left; margin-left:3%; padding:1%;">
            <label class="firstLevel">Zaznacz:<br/></label> 
            <div style="width:130px; float:left"> 
            <input id="q1" type="checkbox"/><label for="q1">Kwartał I</label><br/>
            <input id="q2" type="checkbox"/><label for="q2">Kwartał II</label><br/>
            <input id="q3" type="checkbox"/><label for="q3">Kwartał III</label><br/>
            <input id="q4" type="checkbox"/><label for="q4">Kwartał IV</label><br/>
            </div>
            <div style="width:130px; float:left">
            <input id="p1" type="checkbox"/><label for="p1">Półrocze I</label><br/>
            <input id="p2" type="checkbox"/><label for="p2">Półrocze II</label><br/>
            </div>
            <div style="width:100px; float:left">
            <input id="reset" type="checkbox"/><label for="reset">Resetuj zaznaczenie</label><br/>
            </div>
            </div>
            <div style="clear:both;"></div>
            <br/><br/>

			
			 <div class="form" style="float:right; padding:1% 2% ; height:20px;"><input type="checkbox" class="company" id="getFull"><label class="firstLevel" for="getFull"><strong>Pełna lista odpadów</strong></label></div>
			 <div style="clear:both;"></div>
		
<!--Container for product table-->
            <div id="productTable">
            </div>
		    </form>
            <script>
			$("#declare").validate();
			</script>
        </div>    
        </div>
                 
<!-- FOOTER -->
        <?php include "footer.php" ?>
    </div>

  </body>
</html>