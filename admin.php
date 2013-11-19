<?php
if(!session_id()) session_start();
require_once 'include/users.php';
require_once 'include/template.php';

adminAuth(1);
    
// Good to go, so let's add a few options.
// For right now we have a mod creation panel, though I will want to add an impersonation system at some point
$modList = queryDB("SELECT name, id FROM mods");

template_start("JKHub API - Admin Panel");
?>
<div class="container">
    <?php template_navbar("admin"); ?>
    <div class="container" style="width:80%;">
        <br><br>
        <div class="row">
            <p>
                <h1>Administration Panel</h1>
                <i style="font-height:20%;">If you are able to see this panel, notify me right away --eezstreet</i>
            </p>
        </div>
        <br>
        <div class="row">
            <div class="col-lg-6">
                <div class="jumbotron">
                    <h2>Register Mod</h2>
                    <form class="form-search" action="modadmin.php?m=a" method="post">
                        <div class="input-group">
                            <input type="text" placeholder="Type Mod Name Here" class="form-control" name="modname">
                        </div>
                        <div class="input-control">
                            <button type="submit" class="btn btn-default" type="button">
                                New Mod
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="jumbotron">
                    <h2>Edit Mod</h2>
                    <form action="modadmin.php" method="get">
                        <input type="hidden" name="m" value="e">
                        <div class="input-group">
                            <select name="mod" placeholder="Select Mod">
                                <?php
                                foreach($modList as $mod) {
                                    echo "<option value=\"" . $mod['id'] . "\">" . $mod['name'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="input-control">
                            <button type="submit" class="btn btn-primary" type="button">
                                Edit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <?php template_copyright(); ?>
    </div>
</div>

<?php
template_end();