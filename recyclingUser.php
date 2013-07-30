<?php
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
?>