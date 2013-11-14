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
$errorCode = $_GET['e'];
$friendly = code2friendly($errorCode);
$referrer = $_GET['r'];
$ip = $_GET['a'];
$request_uri = $_GET['u'];
$host = $_GET['h'];
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
        <br>Referring Address: <?php echo $referrer; ?>
        <br>Your IP: <?php echo $ip; ?>
        <br>Request URI: <?php echo $request_uri; ?>
        <br>Host: <?php echo $host; ?>
        <br>Your Browser: <!--#echo var="HTTP_USER_AGENT" -->
    </div>
</div>
<?php
template_end();