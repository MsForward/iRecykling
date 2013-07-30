<?php
function formFactory($info, $link, $userid)
{

//Check for complex product type

	if(($pos = strpos($info, '_')) !== false)
	{
		//Get variant full or list
		$variant = strtolower(substr($info, $pos+1));
		//Get product type
		$type = substr($info, 0, $pos);
		//Choose product company and form type
		switch ($type)
		{
			case 'OPAK':
				$company = 89857;
				$frmtype = 'single';
				break;
			case 'BAT':
				$company = 89857;
				$frmtype = 'double';
				break;
			case 'SPRZ':
				$type = 'SPRZ_B2C';
				$company = 89858;
				$frmtype = 'double';
				break;
			default:
				return 'Exception: Failed to match complex product type.';
		}
		if($variant == 'full')
		{
			//Create new recycling list object
			$currentList = new RecyclingList($variant, $type, $company, $frmtype, $link, $userid);
			if($currentList->init())
			{
				//Check for special case product type
				if($type == 'SPRZ_B2C')
				{
					$type = 'SPRZ_B2B';
					
					$secondList = new RecyclingList($variant, $type, $company, $frmtype, $link, $userid);
					if($secondList->init())
					{
						return $currentList->outputJoinedForm($secondList);
					}
					else
					{
						return 'Exception: Failed to initialize second object from double group';
					}
				}
				else
				{
					$table = $currentList->outputForm();
					return $table;
				}
			}
			else
			{
				return 'Exception: Failed to initialize full form object.';
			}
		}
		elseif($variant == 'list')
		{
			$variant = 'full';
			$currentList = new RecyclingList($variant, $type, $company, $frmtype, $link, $userid);
			if($currentList->init())
			{
				$table = $currentList->outputList();
				return $table;
			}
			else
			{
				return 'Exception: Failed to initialize full list object.';
			}
		}
		else
		{
			return 'Exception: Failed to match complex product type variant.';
		}
	}
	else //Didn't find complex type
	{	
		$type = $info;
		$variant = 'user';
		//Choose product company and form type
		switch ($type)
		{
			case 'OPAK':
				$company = 89857;
				$frmtype = 'single';
				break;
			case 'BAT':
				$company = 89857;
				$frmtype = 'double';
				break;
			case 'SPRZ':
				$type = 'SPRZ_B2C';
				$company = 89858;
				$frmtype = 'double';
				break;
			default:
				return 'Exception: Failed to match product type.';
		}
		
		$currentList = new RecyclingList($variant, $type, $company, $frmtype, $link, $userid);
		if($currentList->init())
		{
			//Check for special case product type
			if($type == 'SPRZ_B2C')
			{
				$type = 'SPRZ_B2B';
				
				$secondList = new RecyclingList($variant, $type, $company, $frmtype, $link, $userid);
				if($secondList->init())
				{
					$table = $currentList->outputJoinedForm($secondList);
					return $table;
				}
				else
				{
					return 'Exception: Failed to initialize second object from double group.';
				}
			}
			else
			{
				$table = $currentList->outputForm();
				return $table;
			}
		}
		else
		{
			return 'Exception: Failed to initialize user object.';
		}
	}
		
}

//RECYCLING LISTS AND FORMS CLASS
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
	private function createDropDown($i, $groupName=0, $rowName=0, $arrowName=0)
	{
		return '<script>
			$(document).ready(function()
			{
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
						$("#arrow'.$i.'").attr("src", "images/arrowdown.png");
					}
					else
					{
						$("#arrow'.$i.'").attr("src", "images/arrow.png");
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
			
			$form .= $this->createDropDown($index);
						
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

//USER CLASS
class RecyclingUser
{
	private $link;
	private $productTypes = array();
	
	public $userID;
	
	function __construct($ID, $link)
	{
		$this->link = $link;
		$this->userID = $ID;
	}
	
	private function getProductTypes()
	{
		$query = "SELECT DISTINCT Twr_Rodzaj 
			FROM twrkarty AS twr 
			JOIN prmkarty AS prm 
			ON twr.Twr_ID=prm.Prm_TwrID AND twr.Twr_Firma=prm.Prm_Firma
			JOIN kntkarty AS knt
			ON prm.Prm_KntID=knt.Knt_ID AND prm.Prm_Firma=knt.Knt_Firma
			WHERE knt.Knt_UserID=$this->userID";
		
		if($result = mysqli_query($this->link, $query))
		{
			while($row = mysqli_fetch_row($result))
			{
				$this->productTypes[] = $row[0];	
			}
			mysqli_free_result($result);
		}
		else
		{
			return false;
		}
		
		return true;
	}
	
	public function checkIfUserTypePresent($type)
	{
		$found = false;
		foreach($this->productTypes as $userType)
		{
			if($type == $userType)
			{
				$found = true;
			}
		}
		
		return $found;
	}	
	
	public function init()
	{
		if($this->getProductTypes())
		{
			foreach($this->productTypes as $type)
			{
				$this->userProducts[$type] = array();
			}
		}
		else
		{
			return false;
		}
		
		return true;
	}
}

//DECLARATION CLASS
class RecyclingDeclaration
{
	private $ID = 0;
	private $link;
	private $year;
	private $lastDay;
	private $duration;
	private $date;
	private $companyNumber;
	private $clientID;
	private $dataType;
	private $products = array();
	
	public  $isKOR = false;
	
	function __construct($link)
	{
		$this->link = $link;
		$this->products = array('ID'=>array(), 'KG'=>array(), 'SZT'=>array());
		$this->date = date('Y-m-d H:i:s');
	}
	
	private function convertFormData($data)
	{
		unset($data['products']);
		$this->dataType = $data['formType'];
		unset($data['formType']);
		$this->year = $data['year'];
		unset($data['year']);
		$this->lastDay = $this->getLastDay($data['month']);
		$this->duration = $this->createMonthString($data['month']);
		unset($data['month']);
		$this->companyNumber = $data['company'];
		unset($data['company']);
		
		if($this->dataType == 'single')
		{
			foreach($data as $key => $value)
			{
				if($value != '')
				{
					$this->products['ID'][] = substr($key, 7);
					$this->products['KG'][] = $value;
					$this->products['SZT'][] = 0;
				}
				else
				{
					continue;
				}
			}
		}
		elseif($this->dataType == 'double')
		{
			$i = 0;
			foreach($data as $key => $value)
			{
				if($i % 2 == 0)
				{
					if($value != '')
					{
						$keyEnd = strpos($key, 'KG') - 7;
						$this->products['ID'][] = substr($key, 7, $keyEnd);
						$this->products['KG'][] = $value;
						$i++;
					}
					else
					{
						$i += 2;
					}
				}
				else
				{
					$this->products['SZT'][] = $value;
					$i++;
				}
			}
		}
		else
		{
			return 'Exception: Failed to match data type.';
		}
		
		return true;
	}
	
	private function getLastDay(& $data)
	{
		$this->length = count($data);		
		$temp = $this->year.'-'.end($data).'-'.'01';
		$day = date('Y-m-t H:i:s', strtotime($temp));	
		
		return $day;
	}
	
	private function createMonthString(& $data)
	{
		$string = '';
		
		foreach ($data as $monthNumber)
		{
			$string .= $monthNumber.'-';
		}
		
		$string = substr($string, 0, -1);
		
		return $string;
	}
	
	public function init($id, $formData)
	{
		if(($result = $this->convertFormData($formData)) == false)
		{
			return 'Exception: Failed to fetch form data';
		}
		
		$query = "SELECT Knt_ID
			FROM kntkarty
			WHERE Knt_Firma='$this->companyNumber' AND Knt_UserID='$id'";
			
		if($result = mysqli_query($this->link, $query))
		{
			if(mysqli_num_rows($result) == 1)
			{
				$row = mysqli_fetch_row($result);
				$this->clientID = $row[0];
			}
			mysqli_free_result($result);
		}
		else
		{
			return 'Exception: Failed to fetch client ID from server';
		}
		
		return true;
	}
	
	public function checkIfKOR()
	{
		$query = "SELECT Dkl_Okr FROM dklkarty WHERE Dkl_KntID='$this->clientID' AND Dkl_Rok=$this->year AND Dkl_Firma=$this->companyNumber";
		
		if($result = mysqli_query($this->link, $query))
		{
			while($row = mysqli_fetch_row($result))
			{
				if($row[0] == $this->duration)
				{
					$this->isKOR = true;
					return true;
				}
				else
				{
					$current = explode('-', $this->duration);
					$previous = explode('-', $row[0]);
					
					$repeat = "";
					
					foreach ($current as $curMonth)
					{
						foreach ($previous as $prevMonth)
						{
							if($curMonth == $prevMonth)
							{
								$repeat = implode(", ", $previous);
								break;
							}
						}
					}
					
					if($repeat != "")
					{
						return $repeat;
					}
					else
					{
						continue;
					}
				}
			}
			mysqli_free_result($result);
			return false;
		}
		else
		{
			return 'Exception: Failed to fetch client ID from server';
		}
		
	}
		
	public function changeLink($link)
	{
		$this->link = $link;
	}
	
	public function insertIntoDatabase()
	{
		switch ($this->isKOR)
		{
			case true:
				$kor = 1;
				break;
			case false:
				$kor = 0;
				break;
		}
		
		$query = "INSERT INTO dklkarty VALUES (0, $this->companyNumber, $this->clientID, '$this->date', $this->year, '$this->lastDay', '$this->duration', $kor)";
		if(mysqli_query($this->link, $query)) 
		{
			$this->ID = mysqli_insert_id($this->link);
			
		}
		else
		{
			return 'Exception: Failed to insert declaration info';
		}
		
		foreach ($this->products['ID'] as $index => $id)
		{
			$KG = $this->products['KG'][$index];
			$SZT = $this->products['SZT'][$index];
			$query = "INSERT INTO zmwkarty VALUES($this->ID, $id, $KG, $SZT)";
			if(!mysqli_query($this->link, $query)) 
			{
				$query = "DELETE FROM dklkarty WHERE Dkl_ID = $this->ID";
				mysqli_query($this->link, $query);
				return 'Exception: Failed to insert declaration<br/>ID:'.$id.'<br/>KG:'.$KG.'<br/>SZT:'.$SZT	;
			}
		}
		
		return true;
	}
	
	public function outputPDF()
	{
		
	}
}

require('fpdf/fpdf.php');

class PDF extends FPDF
{
	// Page header
	function Header()
	{
		// Logo
		$this->Image('images/biosystemOSE.png',115,10,80);
		// Arial bold 15
		$this->SetFont('Arial','B',15);
		// Move to the right
		$this->Cell(80);
		// Line break
		$this->Ln(25);
	}
	
	// Page footer
	function Footer()
	{
		// Position at 1.5 cm from bottom
		$this->SetY(-15);
		// Arial italic 8
		$this->SetFont('Arial','I',8);
		// Page number
		$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}
	
	function Table($header, $data)
	{
		// Column widths
		$w = array(40, 35, 40, 45);
		// Header
		for($i=0;$i<count($header);$i++)
			$this->Cell($w[$i],7,$header[$i],1,0,'C');
		$this->Ln();
		// Data
		foreach($data as $row)
		{
			$this->Cell($w[0],6,$row[0],'LR');
			$this->Cell($w[1],6,$row[1],'LR');
			$this->Cell($w[2],6,number_format($row[2]),'LR',0,'R');
			$this->Cell($w[3],6,number_format($row[3]),'LR',0,'R');
			$this->Ln();
		}
		// Closing line
		$this->Cell(array_sum($w),0,'','T');
	}
}