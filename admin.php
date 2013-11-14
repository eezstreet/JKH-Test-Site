<?php
if(!session_id()) session_start();
require_once 'include/users.php';
require_once 'include/template.php';

function not_authorized()
{
    header("Location: /error.php?e=403&r=(none)&a=(unavailable)&u=/admin.php&h=apitest.jkhub.org");
    exit;
}

if(!isset($_SESSION['userid']))
    not_authorized();
    
$query = "SELECT rank FROM users WHERE id=" . $_SESSION['userid'];
$response = queryDB($query);

if(count($response) != 1)
    not_authorized();
    
$response = $response[0];

if($loggedIn == false || $response['rank'] != "1")
    not_authorized();