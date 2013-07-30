<?php
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
?>