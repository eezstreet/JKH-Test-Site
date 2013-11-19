<?php
if(!session_id()) session_start();
require_once 'include/template.php';
require_once 'include/httpcode.php';
require_once 'include/users.php';

if(!isset($_GET['e']))
{
    header('Location: /index.php');
    die();
}
if(isset($_GET['u']))
    $redirectURI = true;
else
    $redirectURI = false;
$errorCode = $_GET['e'];
$friendly = code2friendly($errorCode);
template_start("$errorCode $friendly - JKHub API");
?>
<div class="container" style="width:60%;">
    <h1><?php echo "$errorCode $friendly"; ?></h1>
    <i style="font-height:10px;">That&apos;s an error.</i>
    
    <p><a class="btn btn-lg btn-primary" href="/index.php">Return to Index</a>
    <?php
    if($loggedIn) {
    ?>
    <a class="btn btn-lg btn-primary" href="/dashboard.php">...or the Dashboard</a></p>
    <?php
    }
    ?>
    
    <div class="jumbobox">
        <h3>Additional Information</h3>
        <br>Your IP: <?php echo $_SERVER['REMOTE_ADDR']; ?>
        <br>Your Browser: <?php echo $_SERVER['HTTP_USER_AGENT']; ?>
        <br>Host: <?php echo $_SERVER['HTTP_HOST']; ?>
        <?php if($redirectURI === true) { ?>
        <br>Redirect URI: <?php echo $_GET['u']; ?>
        <?php } ?>
    </div>
</div>
<?php
template_end();