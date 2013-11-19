<?php

require 'database_access.php';

$loggedIn = false;
$acceptOurIP = false;

if(!isset($_SESSION['ip']))
{ // no IP in this session...this MUST be set. (might cause additional logins required)
    $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
}
else if($_SESSION['ip'] === $_SERVER['REMOTE_ADDR'])
{
    $acceptOurIP = true; // confirmed to not have a spoofed IP
}
else
{
    // spoofed!
    unset($_SESSION['userid']);
    unset($_SESSION['username']);
    die("Spoofed session detected");
}

if(isset($_SESSION['userid']) && $acceptOurIP)
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

function not_authorized()
{
    header("Location: /error.php?e=403&u=/admin.php");
    exit;
}

function adminAuth($value)
{
    if(!isset($_SESSION['userid']))
    not_authorized();
    
    $query = "SELECT rank FROM users WHERE id=" . $_SESSION['userid'];
    $response = queryDB($query);

    if(count($response) != 1)
        not_authorized();
        
    $response = $response[0];

    if($loggedIn == false || $response['rank'] != "1")
        not_authorized();
}