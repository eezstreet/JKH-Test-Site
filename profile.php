<?php
if(!session_id()) session_start();
include_once 'include/template.php';
require_once 'include/database_access.php';

function LynchTheUser() {
    header('Location: /notfound.php');
    die();
}

if(!isset($_GET['u']))
    LynchTheUser();
$id = $_GET['u'];
$query = queryDB("SELECT * FROM users WHERE id=$id");
if(count($query) != 1)
    LynchTheUser();
$query = $query[0];
$name = $query['username'];
template_start("JKHub API: Viewing profile: $name");
?>

<br><br>
<div class="container" style="width:60%;">
    <?php template_title(); ?>
    <i style="font-height:14px;"><a href="/dashboard.php">Go to Dashboard</a></i>
    
    <div class="row">
        <div class="col-lg-6">
            <h3>Profile: <?php echo $user; ?></h3>
            <p>Last login: <?php echo $query['lastlogin']; ?>
            <br>Member since: <?php echo $query['membersince']; ?></p>
        </div>
        <div class="col-lg-6">
            <div class="jumbobox">
                <h3>Recent Achievements</h3>
                <p><i style="font-height:14px;">Achievement system not currently functional.</i></p>
            </div>
        </div>
    </div>
    <div class="row">
        <h3>Recently Played</h3>
        <p><i style="font-height:14px;">Link system not currently functional</i></p>
    </div>
    <?php template_copyright(); ?>
</div>

<?php
template_end();