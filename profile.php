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
$id = escapeDB($_GET['u']);
$query = queryDB("SELECT * FROM users WHERE id=$id");
if(count($query) != 1)
    LynchTheUser();
$query = $query[0];
$name = $query['username'];
template_start("JKHub API: Viewing profile: $name");
?>

<br><br>
<div class="container" style="width:60%;">
    <?php template_navbar("profile"); ?>
    <br><br><br>
    <div class="col-lg-6">
        <div class="row">
            <h3>Profile: <?php echo $name; ?></h3>
            <p>Last login: <?php echo $query['lastlogin']; ?>
            <br>Member since: <?php echo $query['membersince']; ?></p>
        </div>
        <div class="row">
            <h3>Recently Played</h3>
            <p><i style="font-height:14px;">Link system not currently functional</i></p>
        </div>
    </div>
    <div class="col-lg-6">
            <h3>Recent Achievements</h3>
            <?php
            $recentAchieves = queryDB("SELECT extraint,text FROM status WHERE user='$id' AND type='1' ORDER BY timestamp ASC LIMIT 5");
            if(count($recentAchieves) == 0) {
                echo "<i>This user has not earned any achievements.</i>";
            }
            else {
                foreach($recentAchieves as $achieveId) {
                    $modName = $achieveId['text'];
                    $achieveId = $achieveId['extraint'];
                    $userAchieve = queryDB("SELECT unlocked_$achieveId FROM modusers_$modName WHERE id='$id'");
                    $modAchieve = queryDB("SELECT * FROM modachieve_$modName WHERE id='$achieveId'");
                    $realModName = queryDB("SELECT name FROM mods WHERE id='$modName'");
                    $realModName = $realModName[0];
                    ?>
                    <div class="row">
                        <h5><?php echo $realModName; ?> -
                            <i data-toggle="tooltip" data-placement="top" title data-original-title="<?php echo $modAchieve['description']; ?>">
                                <?php echo $modAchieve['name']; ?>
                        </h5>
                    </div>
                    <?php
                }
            }
            ?>
    </div>
</div>
<center>
    <div class="container" style="width:60%;">
        <div class="row">
            <?php template_copyright(); ?>
        </div>
    </div>
</center>

<?php
template_end();