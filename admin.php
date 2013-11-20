<?php
if(!session_id()) session_start();
require_once 'include/users.php';
require_once 'include/template.php';
require_once 'include/messages.php';

adminAuth(1);

$msg = new Messages();
    
// Good to go, so let's add a few options.
// For right now we have a mod creation panel, though I will want to add an impersonation system at some point
$modList = queryDB("SELECT name, id FROM mods");

template_start("JKHub API - Admin Panel");
?>
<div class="container">
    <?php template_navbar("admin"); ?>
    <div class="container">
        <br><br>
        <div class="row">
            <p>
                <h1>Administration Panel</h1>
                <i style="font-height:20%;">If you are able to see this panel, notify me right away --eezstreet</i>
            </p>
        </div>
		<div class="row">
			<?php echo $msg->display(); ?>
		</div>
        <br>
        <div class="row">
            <div class="col-lg-6">
                <div class="jumbotron">
                    <h2>Register Mod</h2>
                    <form action="modadmin.php?m=a" method="post">
                        <div class="input-group">
                            <input type="text" placeholder="Type Mod Name Here" class="form-control" name="modname" size="45">
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
				<form action="modadmin.php" method="get" class="form-horizontal">
					<input type="hidden" name="m" value="e">
					<fieldset>

					<!-- Form Name -->
					<legend>Edit Mod</legend>
					<br><br><br><br>
					<!-- Select Basic -->
					<div class="form-group">
					  <label class="col-md-3 control-label" for="m1">Select Mod</label>
					  <div class="col-md-6">
						<select id="m1" name="m1" class="form-control ">
						<?php
							foreach($modList as $mod) {
								echo "<option value=\"" . $mod['id'] . "\">" . $mod['name'] . "</option>";
							}
						?>
						</select>
					  </div>
					</div>

					<!-- Button -->
					<div class="form-group">
					  <label class="col-md-3 control-label" for="submit"></label>
					  <div class="col-lg-6">
						<button id="submit" class="btn btn-primary">Edit</button>
					  </div>
					</div>

					</fieldset>
				</form>
            </div>
        </div>
        
        <?php template_copyright(); ?>
    </div>
</div>

<?php
template_end();