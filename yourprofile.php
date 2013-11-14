<?php
if(!session_id()) session_start();
require_once 'include/users.php';

if($loggedIn == false)
{
    header('Location: /index.php');
    die();
}

$userId = $_SESSION['userid'];

header("Location: /profile.php?u=$userId");
die();