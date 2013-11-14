<?php

require 'database_access.php';

$loggedIn = false;

if(isset($_SESSION['userid']))
{
	$loggedIn = true;
}

function getUserNameFromId($userId)
{
	if($loggedIn == false)
		return "";
        
    $safeUserid = cleanDB($userid);

	return queryDB("SELECT username FROM users WHERE id = $safeUserid");
}

function saltTheWound($password, $salt)
{
	$retVal = hash('sha256', $password . $salt);
	for($round = 0; $round < 65536; $round++)
		$retVal = hash('sha256', $retVal . $salt);
	return $retVal;
}