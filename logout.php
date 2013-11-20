<?php
if(!session_id()) session_start();
include_once 'include/users.php';
require_once 'include/messages.php';

$msg = new Messages();

if($GLOBALS['loggedIn'] == false) {
    die("You are not logged in.");
}
else
{
    $GLOBALS['loggedIn'] = false;
    $msg->add('s', "Logged out.");
    unset($_SESSION['userid']);
    unset($_SESSION['username']);
    header('Location: /index.php');
}