

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="scripts/jquery-1.10.1.min.js"></script>
    <link rel="stylesheet" type="text/css" href="stylesheets/global.css"></link>
    
    <script>
	$(document).ready(function() {
    	$("#test").click(function() {
			$("body").css("cursor", "wait");
			$.ajax({
			url: "classtestsave.php",
			type: "get",
			data: $("#test").val(),
			success: function(result) {
				$("body").css("cursor", "default");
				$("#result").html(result);
			}});
			return false;
		});
    });
	</script>
</head>

<html>

<form>
<input type="submit" id="test" name="products" value="OPAK"></form>

<div id="result" style="background-color:#90F; padding:10%"></div>

</html>