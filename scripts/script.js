
$(document).ready(function() {
	
	var res = screen.width;
	if(res <= 1152)
	{
		$(".content").css("width", "75%");
		$(".left").css("width", "46%");
	}
	
	if(res <= 1280)
	{
		$("#nav a").css("width", "130px");
		$("#nav ul").css("width", "148px");
	}
	
	$("#year").after('<p class="error" id="year-error-message"></p>');
	$("#year-error-message").hide();
	
	$("#year").keyup(function() {
		var value = $(this).val();
		var currentYear = new Date().getFullYear();
		
		if(value.length > 3 && Number(value) != currentYear && Number(value) != currentYear-1)
		{
			$("#year").css("border-color", "red");
			$("#year-error-message").html('Wpisz rok bieżący lub poprzedni');
			$("#year-error-message").show();
		}
		else
		{
			$("#year").css("border-color", "#CCC");
			$("#year-error-message").hide();
		}
		
		if(value == "")
		{
			$("#year").css("border-color", "red");
			$("#year-error-message").html('Pole wymagane');
			$("#year-error-message").show();
		}
	});
	
	$("#declare").on("keyup", "input[type='text']", (function() {
		var value = $(this).val();
		var comma = value.indexOf(",");
		if(comma >= 0)
		{
			$(this).val(value.replace(",","."));
		}
		
	}));
	
	$("#declare").on("focus", "input[type='text']", (function() {
		if($(this).hasClass("watermark"))
		{
			$(this).removeClass("watermark");
			$(this).val("");
		}
	}));
	
	$("#declare").on("blur", "input[type='text']", (function() {
		var value = $(this).val();
		
		if(value == "")
		{
			var unit = $(this).attr("class");
			$(this).addClass("watermark");
			$(this).val(unit);
		}
		
		var characters = value.split("");
		for(var i=0; i<characters.length; i++)
		{
			if((isNaN(characters[i]) && characters[i] != ".") || value.indexOf(".") == 0)
			{
				$(this).siblings("p").remove();
				$(this).after('<p class="error">To nie jest poprawna wartość.</p>');
				$(this).css("border-color", "red");
			}
			else
			{
				$(this).siblings("p").remove();
				$(this).css("border-color", "#CCC");
			}
		}	
		
		if(value.indexOf(".") > 0)
		{
			if($("#declare input[type='radio']:checked").val() == "OPAK" || $(this).attr("class") == "szt")
			{
				var decimal = value.length - value.indexOf(".") - 1;
				$(this).val(value.substr(0, value.length-decimal-1));
			}
			else
			{
				var decimal = value.length - value.indexOf(".") - 1;
				$(this).val(value.substr(0, value.length-decimal+2));
			}
		}
		
		if($(this).val() != "" && $("#declare input[name='formType']").val() == "double")
		{
			if($(this).siblings().val() == "")
			{
				$(this).siblings().
			}
		}
		
	}));
	
	function showStatus(message)
	{
		$("#dclStatus").slideDown(500);
		var statusTimeout = setTimeout(function() {$("#dclStatus").slideUp(500)}, 20000);
		$("#dclStatus").html(message);
	}
	
	$("#saveButton").click(function() {
	
		$("body").css("cursor", "wait");
		var formData = $("#declare").serialize();
		$.ajax({
			url: "save.php",
			data: formData,
			type: "post",
			dataType: "json",	
			beforeSend: function(xhr, options) {
				var errors = 0;
				
				if($("#year-error-message").is(":visible"))
				{
					showStatus('Wpisz poprawny rok deklaracji');
					errors++;
				}
				
				if($(".monthCheck:checkbox:checked").length == 0)
				{
					showStatus('Zaznacz co najmniej jeden miesiąc.');
					errors++;
				}
				
				if(!$(".declare input[type='radio']").is(":checked"))
				{
					showStatus('Wybierz kategorię odpadów i wypełnij odpowiednie pola.');
					errors++;
				}
				
				if($())
				
				if(errors == 0)
				{
					if(confirm('Czy napewno chcesz wysłać tą deklarację?') != true)
					{
						xhr.abort();
						$("body").css("cursor", "default");
					}
				}
				else
				{
					xhr.abort();
					$("body").css("cursor", "default");
				}
				
			},
			success: function(result)
			{
				if(result.msg == "KOR")
				{
					if(confirm('Istnieje już deklaracja za ten okres. Czy chcesz wysłać jej korektę?') != true)
					{
						xhr.abort();
						$("body").css("cursor", "default");
					}
					else
					{
						var data = "declaration="+result.dec;
						$.ajax({
						url: "correct.php",
						data: data,
						type: "post",
						success: function(result) {
							$("body").css("cursor", "default");
							showStatus(result);
						}});
					}
				}
				else
				{
					$("body").css("cursor", "default");
					showStatus(result.msg);
				}
			},
			error: function()
			{
				showStatus('Wystąpił nieznany błąd serwera');
				$("body").css("cursor", "default");
			}});
	});
	
	$("#dclStatus").click(function() {
		$(this).slideUp(500);
		clearTimeout(statusTimeout);
	});
	
	$("#pdfButton").click(function() {
		var formData = $("#declare").serialize();
		
	});
	
	$("#getFull").click(function() {
		$("body").css("cursor", "wait");
		var name="products=";
		
		if(!$("#declare input[type='radio']").is(":checked"))
		{
			$("#OPAK").prop("checked", true);
		}
		
		var type = $("#declare input[type='radio']:checked").val();
		name += type;
		
		if($("#getFull").is(":checked"))
		{
			name += "_FULL";
		}
		
		$.ajax({
			url: "products.php",
			data: name,
			type: "get",
			success: function(result)
			{
				$("body").css("cursor", "default");
				$("#productTable").html(result);
			}});
	});
});

function changeCompany(name)
{
	$("body").css("cursor", "wait");
	var isFull = $("#getFull").prop("checked");
	if(isFull == true)
	{
		name += "_FULL";
	}
	
	name = "products=" + name;
	$.ajax({
			url: "products.php",
			data: name,
			type: "get",
			success: function(result)
			{
				$("body").css("cursor", "default");
				$("#productTable").html(result);
			}});
}


//MENU ACTIVE CELL MARKER
function activate(id)
{
	$("#"+id).css("background", "#049CDB");
	$("#"+id).css("color", "#FFF");
	$("#"+id).removeAttr("href");
}	

$(document).ready(function() {
	$("#sidebar1").hover(function() {
		$(this).stop().animate(
		{
			right: "0"
		});
	},
	function() {
		$(this).stop().animate(
		{
			right: "-200px"
		}, "slow");		
	});
});

$(document).ready(function() {
	
	$("#q1").click(function() {
		var checked = $("#q1").prop("checked");
		for(var i=0; i<3; i++)
		{
			$("#month"+i).prop("checked", checked);
		}
	});
	$("#q2").click(function() {
		var checked = $("#q2").prop("checked");
		for(var i=3; i<6; i++)
		{
			$("#month"+i).prop("checked", checked);
		}
	});
	$("#q3").click(function() {
		var checked = $("#q3").prop("checked");
		for(var i=6; i<9; i++)
		{
			$("#month"+i).prop("checked", checked);
		}
	});
	$("#q4").click(function() {
		var checked = $("#q4").prop("checked");
		for(var i=9; i<12; i++)
		{
			$("#month"+i).prop("checked", checked);
		}
	});
	$("#p1").click(function() {
		var checked = $("#p1").prop("checked");
		for(var i=0; i<6; i++)
		{
			$("#month"+i).prop("checked", checked);
		}
	});
	$("#p2").click(function() {
		var checked = $("#p2").prop("checked");
		for(var i=6; i<12; i++)
		{
			$("#month"+i).prop("checked", checked);
		}
	});

	$("#reset").click(function() {
		$("#month0,#month1,#month2,#month3,#month4,#month5,#month6,#month7,#month8,#month9,#month10,#month11,#q1,#q2,#q3,#q4,#p1,#p2,#reset").prop("checked", false);
	});
	
	$("#sidebarChange").change(function() {
		var num = $("#sidebarChange input[type='radio']:checked").val();
		$("#sidebar1").hide();
		$("#sidebar2").hide();
		$("#sidebar3").hide();
	
		$("#sidebar"+num).show();
	});
});
	