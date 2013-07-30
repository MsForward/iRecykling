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
?>