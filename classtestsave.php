<?php

include "base.php";
include "recycling.php";



$testUser = new RecyclingUser(1, $link);
$testProductsList = new RecyclingList('user', 'OPAK', 89857, $link, $testUser->userID);

echo $testProductsList->outputForm();

?>