<?php
class RecyclingList
{
	private $link;
	private $variant;
	private $userID;
	private $companyNumber;
	private $productType;
	private $formType;
	private $groupTable = array();
	private $productTable = array();
	private $formHeaders = array();
	private $hasMulti = false;
	private $hasDrug = false;
	
	function __construct($variant, $prtype, $company, $frmtype, $link, $userid=0)
	{
		$this->link = $link;
		$this->productType = $prtype;
		$this->userID = $userid;		
		$this->variant = $variant;
		$this->companyNumber = $company;
		$this->formType = $frmtype;
		$this->groupTable = array('Name'=>array(), 'Count'=>array());
		$this->productTable = array('Name'=>array(), 'ID'=>array());
	}

	private function prepareHeaders()
	{
		if($this->formType == 'single')
		{
			$this->formHeaders = array(
							'Name'=>array('Nazwa produktu', 'Jednorodne'),
							'Width'=>array('auto', '110px')
							);
		}
		elseif($this->formType == 'double')
		{
			$this->formHeaders = array(
							'Name'=>array('Nazwa produktu', 'Kg.', 'Szt.'),
							'Width'=>array('auto', '110px', '110px')
							);
		}
		else
		{
			$this->formHeaders = array(
							'Name'=>array(),
							'Width'=>array()
							);
			return false;
		}
		
		if($this->hasMulti == true)
		{
			$this->formHeaders['Name'][] = 'Wielomateriał';
			$this->formHeaders['Width'][] = '110px';
		}
		if($this->hasDrug == true)
		{
			$this->formHeaders['Name'][] = 'Leki';
			$this->formHeaders['Width'][] = '110px';
		}
		
		return true;
	}
	
	//Default function for creating drop-down product groups
	private function createDropDown($i, $groupName, $rowName, $arrowName)
	{
		return '<script>
			$(document).ready(function()
			{
				$("#'.$groupName.$i.'")
							 .attr("unselectable", "on")
							 .css("user-select", "none")
							 .on("selectstart", false);
				$(".'.$rowName.$i.'").hide();	
				$("#'.$groupName.$i.'").click(function()
				{
					$(".'.$rowName.$i.'").toggle();
					if ($(".'$rowName.$i.'").is(":visible"))
					{ 
						$("#'$arrowName.$i.'").attr("src", "images/arrowdown.png");
					}
					else
					{
						$("#'.$arrowName.$i.'").attr("src", "images/arrow.png");
					}
				});
			});
		</script>';
	}
	
	//Drop-down for II tier product groups
	private function advancedDropDown($i, $k = -1)
	{
		$script = '<script>
			$(document).ready(function()
			{
				$("#expand'.$i.'")
							 .attr("unselectable", "on")
							 .css("user-select", "none")
							 .on("selectstart", false);
				$(".secondaryGroup'.$i.'").hide();
				$("#expand'.$i.'").click(function()
				{
					$(".secondaryGroup'.$i.'").toggle();
					if ($(".secondaryGroup'.$i.'").is(":visible"))
					{ 
						$("#arrow'.$i.'").attr("src", "images/arrowdown.png");
					}
					else
					{
						$("#arrow'.$i.'").attr("src", "images/arrow.png");
						$("#secondaryArrow'.$i.'").attr("src", "images/arrow.png");
						$("#otherSecondaryArrow'.$k.'").attr("src", "images/arrow.png");
						$(".row'.$i.'").hide();
						$(".otherRow'.$k.'").hide();
					}
				});
				
				$("#group'.$i.'")
							.attr("unselectable", "on")
							.css("user-select", "none")
							.on("selectstart", false);
				$(".row'.$i.'").hide();
				$("#group'.$i.'").click(function()
				{
					$(".row'.$i.'").toggle(); 
					if ($(".row'.$i.'").is(":visible"))
					{ 
						$("#secondaryArrow'.$i.'").attr("src", "images/arrowdown.png");
					}
					else
					{
						$("#secondaryArrow'.$i.'").attr("src", "images/arrow.png");
					}
				});';
			
		if($k != -1)
		{	
				
			$script .=	'$("#otherGroup'.$k.'")
							.attr("unselectable", "on")
							.css("user-select", "none")
							.on("selectstart", false);
				$(".otherRow'.$k.'").hide();
				$("#otherGroup'.$k.'").click(function()
				{
					$(".otherRow'.$k.'").toggle(); 
					if ($(".otherRow'.$k.'").is(":visible"))
					{ 
						$("#otherSecondaryArrow'.$k.'").attr("src", "images/arrowdown.png");
					}
					else
					{
						$("#otherSecondaryArrow'.$k.'").attr("src", "images/arrow.png");
					}
				});
				
			});
		</script>';
        }
		else
		{
			$script .= '}); </script>';
		}
		
		return $script;
	}
	
	public function outputForm()
	{	
		$i = 0;
		$count = 0;
		$form = '<table class="productForm">';
		
		foreach($this->groupTable['Name'] as $index => $groupName)
		{
			$form .= '<tr><td colspan="10" class="group" id="group'.$index.'">
				<img id="arrow'.$index.'" src="images/arrow.png" width="20px" style="float:left; margin:5px 10px 5px; cursor:pointer;">'
				.mb_strtoupper($groupName, 'utf-8').'</td></tr>';
					
			foreach($this->formHeaders['Name'] as $headerIndex => $name)
			{
				$form .= '<th class="row'.$index.'" width="'.$this->formHeaders['Width'][$headerIndex].'">'.$name.'</th>';
			}
			
			$form .= $this->createDropDown($index, "group", "row", "arrow");
						
			$count += $this->groupTable['Count'][$index];
			for(; $i<$count; $i++)
			{
				$form .= '<tr class="row'.$index.'"><td>'.ucfirst($this->productTable['Name'][$i]).'</td>';
				
				if($this->formType == 'single')
				{
					$form .= '<td style="width:70px"><input type="text" class="kg. watermark" value="kg." name="amount_'.$this->productTable['ID'][$i].'" size="10"></td>';
				}
				elseif($this->formType == 'double')
				{
					$form .= '<fieldset><td style="width:70px"><input type="text" class="kg. watermark"  value="kg." name="amount_'.$this->productTable['ID'][$i].'KG" size="10"></td>
						<td style="width:70px"><input type="text" class="szt. watermark" value="szt." name="amount_'.$this->productTable['ID'][$i].'SZT" size="10"></td></fieldset>';
				}
				
				if($this->hasMulti)
				{
					$form .= '<td style="width:70px"><input type="text" size="10"></td>';
				}
				if($this->hasDrug)
				{
					$form .= '<td style="width:70px"><input type="text" size="10"></td>';
				}
				$form .= '</tr>';
			}
		}
		
		$form .= '</table>';
		$form .= '<input type="hidden" name="formType" value="'.$this->formType.'">
			<input type="hidden" name="company" value="'.$this->companyNumber.'">';
		
		return $form;
	}
	
	public function outputJoinedForm($object)
	{
		$groupOne = substr($this->productType, strpos($this->productType, '_')+1);
		$groupTwo = substr($object->productType, strpos($object->productType, '_')+1);
		
		$i = 0;
		$k = 0;
		$countOne = 0;
		$countTwo = 0;
		$form = '<table class="productForm">';
		
		foreach($this->groupTable['Name'] as $indexOne => $groupName)
		{	
			$form .= '<tr><td colspan="3" class="group" id="expand'.$indexOne.'">
				<img id="arrow'.$indexOne.'" src="images/arrow.png" width="20px" style="float:left; margin:5px 10px 5px; cursor:pointer;">'
				.mb_strtoupper($groupName, 'utf-8').'</td></tr>';
				
			$form .= '<tr><td colspan="3" class="secondaryGroup'.$indexOne.' group" id="group'.$indexOne.'">
				<img id="secondaryArrow'.$indexOne.'" src="images/arrow.png" width="20px" style="float:left; margin:5px 10px 5px; margin-left:30px; cursor:pointer;">'
				.$groupOne.'</td></tr>';	
					
			foreach($this->formHeaders['Name'] as $headerIndex => $name)
			{
				$form .= '<th class="row'.$indexOne.'" width="'.$this->formHeaders['Width'][$headerIndex].'">'.$name.'</th>';
			}

			$countOne += $this->groupTable['Count'][$indexOne];
			
			for(; $i<$countOne; $i++)
			{
				$form .= '<tr class="row'.$indexOne.'"><td>'.ucfirst($this->productTable['Name'][$i]).'</td>';
				
				if($this->formType == 'single')
				{
					$form .= '<td style="width:70px"><input type="text" class="kg. watermark" value="kg." name="amount_'.$this->productTable['ID'][$i].'" size="10"></td></tr>';
				}
				elseif($this->formType == 'double')
				{
					$form .= '<fieldset><td style="width:70px"><input type="text" class="kg. watermark" value="kg." name="amount_'.$this->productTable['ID'][$i].'KG" size="10"></td>
						<td style="width:70px"><input type="text" class="szt. watermark" value="szt." name="amount_'.$this->productTable['ID'][$i].'SZT" size="10"></td></fieldset></tr>';
				}
			}
			
			if(($indexTwo = array_search($this->groupTable['Name'][$indexOne], $object->groupTable['Name'])) !== false)
			{
				
				$form .= '<tr><td colspan="3" class="secondaryGroup'.$indexOne.' group" id="otherGroup'.$indexTwo.'">
				<img id="otherSecondaryArrow'.$indexTwo.'" src="images/arrow.png" width="20px" style="float:left; margin:5px 10px 5px; margin-left:30px; cursor:pointer;">'
				.$groupTwo.'</td></tr>';
				
					foreach($this->formHeaders['Name'] as $headerIndex => $name)
				{
					$form .= '<th class="otherRow'.$indexOne.'" width="'.$this->formHeaders['Width'][$headerIndex].'">'.$name.'</th>';
				}
			
				$countTwo += $object->groupTable['Count'][$indexTwo];
				
				for(; $k<$countTwo; $k++)
				{
					$form .= '<tr class="otherRow'.$indexTwo.'"><td>'.$object->productTable['Name'][$k].'</td>';
					
					if($object->formType == 'single')
					{
						$form .= '<td style="width:70px"><input type="text" class="kg. watermark" value="kg." name="amount_'.$object->productTable['ID'][$k].'" size="10"></td>
							<td>kg.</td></tr>';
					}
					elseif($object->formType == 'double')
					{
						$form .= '<fieldset><td style="width:70px"><input type="text" class="kg. watermark" value="kg." name="amount_'.$object->productTable['ID'][$k].'KG" size="10"></td>
							<td style="width:70px"><input type="text" class="szt. watermark" value="szt." name="amount_'.$object->productTable['ID'][$k].'SZT" size="10"></td></fieldset></tr>';
					}
				}
				
				$form .= $this->advancedDropDown($indexOne, $indexTwo);
			}
			else
			{
				$form .= $this->advancedDropDown($indexOne);
			}
			
		}
		
		$form .= '</table>';
		$form .= '<input type="hidden" name="formType" value="'.$this->formType.'">
		<input type="hidden" name="company" value="'.$this->companyNumber.'">';
		
		return $form;
	}
	
	public function outputList()
	{		
		$i = 0;
		$count = 0;
		$list = '<table class="productList">';
		$list .= '<th>Nazwa produktu</th>';
		
		foreach($this->groupTable['Name'] as $index => $groupName)
		{
			$list .= '<tr><td class="group" id="group'.$index.'">
				<img id="arrow'.$index.'" src="images/arrow.png" width="20px" style="float:left; margin:5px 10px 5px; cursor:pointer;">'
				.mb_strtoupper($groupName, 'utf-8').'</td></tr>';
			
			$list .= $this->createDropDown($index);
						
			$count += $this->groupTable['Count'][$index];
			for(; $i<$count; $i++)
			{
				$list .= '<tr class="row'.$index.'"><td>'.ucfirst($this->productTable['Name'][$i]).'</td></tr>';
			}
		}
		
		$list .= '</table>';
		
		return $list;
	}
	
	public function init()
	{	
		if($this->variant == 'full' && $this->productType == 'OPAK')
		{
			$this->hasMulti = true;
			$this->hasDrug = true;
		}
		
		if($this->prepareHeaders() == false)
		{
			return false;
		}
		
		if($this->variant == 'user')
		{
			$query = "SELECT Twr_ID, Twr_Nazwa, Twr_Grupa
				FROM twrkarty 
				JOIN prmkarty ON Twr_ID=Prm_TwrID AND Twr_Firma=Prm_Firma 
				JOIN kntkarty ON Prm_KntID=Knt_ID AND Prm_Firma=Knt_Firma 
				WHERE Twr_Rodzaj='$this->productType' 
				AND Twr_Firma='$this->companyNumber' 
				AND Knt_UserID='$this->userID' 
				AND Twr_Kod NOT LIKE '%WIEL%'
				AND Twr_Kod NOT LIKE '%LEKI%'
				ORDER BY Twr_GrupaLp";
		}
		elseif($this->variant == 'full')
		{
			$query = "SELECT Twr_ID, Twr_Nazwa, Twr_Grupa
				FROM twrkarty 
				WHERE Twr_Rodzaj='$this->productType' 
				AND Twr_Firma='$this->companyNumber'
				AND Twr_Kod NOT LIKE '%WIEL%'
				AND Twr_Kod NOT LIKE '%LEKI%'
				ORDER BY Twr_GrupaLp";
		}
		else
		{
			return false;
		}
			
		$i = 0;
				
		if($result = mysqli_query($this->link, $query))
		{
			if(mysqli_num_rows($result) == 0)
			{
				echo '<br/>Nie masz podpisanej umowy na odpady z tej kategorii, skontaktuj się z nami w celu jej podpisania lub zaznacz "Pełna lista odpadów"';
			}
			else
			{
				$array = mysqli_fetch_array($result);
				$currentGroup = $this->groupTable['Name'][$i] = $array['Twr_Grupa'];
				$this->productTable['Name'][] = $array['Twr_Nazwa'];
				$this->productTable['ID'][] = $array['Twr_ID'];
				$this->groupTable['Count'][$i] = 1;
	
				while($array = mysqli_fetch_array($result))
				{
					if($array['Twr_Grupa'] <> $currentGroup)
					{
						$i++;
						$currentGroup = $this->groupTable['Name'][$i] = $array['Twr_Grupa'];
						$this->groupTable['Count'][$i] = 0;
					}
					$this->productTable['Name'][] = $array['Twr_Nazwa'];
					$this->productTable['ID'][] = $array['Twr_ID'];
					$this->groupTable['Count'][$i]++;
				}
				mysqli_free_result($result);
			}
		}
		else
		{
			return false;
		}
		
		return true;
	}
}
?>