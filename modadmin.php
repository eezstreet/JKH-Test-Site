<?php
if(!session_id()) session_start();
require_once 'include/users.php';
require_once 'include/template.php';
require_once 'include/messages.php';

$msg = new Messages();

adminAuth(1);

if($_GET['m'] === 'a') {
    // add new mod
    if(!isset($_POST['modname']))
        $placeText = "Mod Name";
    else
        $placeText = escapeDB($_POST['modname']);
    
    template_start("JKHub API - Register Mod");
    
    template_navbar("admin");
    ?>
	<div class="container">
		<br><br><br><br>
		<?php echo $msg->display(); ?>
		<form class="form-horizontal" action="modadmin.php?m=az" method="post">
			<fieldset>

			<!-- Form Name -->
			<legend>Register New Modification</legend>
			<br>
			<!-- Text input-->
			<div class="form-group required-control">
			  <label class="col-md-3 control-label" for="m1">Mod Name</label>
			  <div class="col-md-6">
				<input id="m1" name="m1" type="text" placeholder="Mod Name" class="form-control " required="">
				
			  </div>
			</div>

			<!-- Textarea -->
			<div class="form-group required-control">
			  <label class="col-md-3 control-label" for="m2">Description</label>
			  <div class="col-md-6">
				<textarea id="m2" name="m2" required="" class="form-control ">Write up a good, short description of the mod here.</textarea>
			  </div>
			</div>

			<!-- Button -->
			<div class="form-group">
			  <label class="col-md-3 control-label" for="submit"></label>
			  <div class="col-lg-6">
				<button id="submit" name="submit" class="btn btn-default">Submit</button>
			  </div>
			</div>

			</fieldset>
		</form>
		<?php template_copyright(); ?>
	</div>
    <?php
    template_end();
}
else if($_GET['m'] === 'az') {
	// adding new mod..action time
	if(!isset($_POST['m1']) || !isset($_POST['m2'])) {
		// shouldn't ever display under normal circumstances
		$msg->add('e', "No name/description entered. Please write one.");
		header('Location: /modadmin.php?m=a');
		exit;
	}
	$modName = escapeDB($_POST['m1']);
	$modDescription = escapeDB($_POST['m2']);
	
	queryDB("INSERT INTO mods (name, description) VALUES ('$modName', '$modDescription')");
	//FIXME: ugly. i'm like 90% certain there's a better way to do this
	$row = queryDB("SELECT id FROM mods WHERE name='$modName'");
	$row = $row[0];
	$newModID = $row['id'];
	queryDB("CREATE TABLE modachieve_$newModID ( id int(10) PRIMARY KEY AUTO_INCREMENT, name varchar(128) UNIQUE, description text, number_achieved int(11), type int(11), count int(11) )");
    queryDB("CREATE TABLE modusers_$newModID ( id int(10) )");
    
    // FIXME: mega hack
    $userrow = queryDB("SELECT id FROM users");
    queryDB("INSERT INTO modusers_$newModID (id) SELECT id FROM users");
    
	$msg->add('s', "New mod registered successfully: $modName. <br>Use the Edit feature to add achievements.");
	
	header('Location: /admin.php');
	exit;
}
else if($_GET['m'] === 'e') {
    // editing/deleting a mod
	if(!isset($_GET['m1'])) {
		$msg->add('e', "No mod selected. Error.");
		header('Location: /admin.php');
		exit;
	}
	
	$thisModId = escapeDB($_GET['m1']);
	$row = queryDB("SELECT * FROM mods WHERE id=$thisModId");
	if(count($row) != 1) {
		$msg->add('e', "Unspecified Error");
		header('Location: /admin.php');
		exit;
	}
	
	$row = $row[0];
	$modname = $row['name'];
	
	// grab the achievements
	$acRow = queryDB("SELECT * FROM modachieve_$thisModId");
	
	template_start("JKHub API - Editing Mod $modname");

    template_navbar("admin");
	?>
	<div class="container">
		<br><br><br><br>
		<?php 
		echo $msg->display();
		echo "<h1>Editing Mod Details for $modname</h1>";
		?>
		<!-- nav tabs -->
		<ul class="nav nav-tabs">
			<li class="active"><a href="#n1" data-toggle="tab">Basic Info</a></li>
			<li><a href="#n2" data-toggle="tab">Add Achievement</a></li>
			<li><a href="#n3" data-toggle="tab">Edit Achievement</a></li>
			<li><a href="#n4" data-toggle="tab">Delete Achievement</a></li>
			<li><a href="#n5" data-toggle="tab">Delete Mod</a></li>
		</ul>
		<!-- tab panes -->
		<div class="tab-content">
			<div class="tab-pane fade in active" id="n1">
				<!-- This is the tab that edits basic mod info -->
				<br>
				<form class="form-horizontal" action="modadmin.php?m=ez1" method="post">
					<input type="hidden" name="m0" value="<?php echo $row['id']; ?>">
					<fieldset>

					<!-- Form Name -->
					<legend>Basic Details</legend>
					<br>
					<!-- Text input-->
					<div class="form-group required-control">
					  <label class="col-md-3 control-label" for="m1">Mod Name</label>
					  <div class="col-md-6">
						<input id="m1" name="m1" type="text" class="form-control " value="<?php echo $row['name']; ?>" required="">
						
					  </div>
					</div>

					<!-- Textarea -->
					<div class="form-group required-control">
					  <label class="col-md-3 control-label" for="m2">Description</label>
					  <div class="col-md-6">
						<textarea id="m2" name="m2" required="" class="form-control "><?php echo $row['description']; ?></textarea>
					  </div>
					</div>
					
					<!-- Multiple Checkboxes (inline) -->
					<div class="form-group">
						<label class="col-md-3 control-label" for="m3">Public?</label>
						<div class="col-lg-8">
							<label class="checkbox-inline" for="m3-0">
								<input type="checkbox" name="m3" id="m3-0" value="Yes" <?php if($row['visible'] == 1) echo "checked"; ?>>
								Yes
							</label>
						</div>
					</div>
					
					<!-- Button -->
					<div class="form-group">
					  <label class="col-md-3 control-label" for="submit"></label>
					  <div class="col-lg-6">
						<button id="submit" name="submit" class="btn btn-primary">Save Changes</button>
					  </div>
					</div>

					</fieldset>
				</form>
			</div>
			<div class="tab-pane fade" id="n2">
				<!-- This is the tab that adds a new achievement -->
				<form class="form-horizontal" action="modadmin.php?m=aaz" method="post">
					<input type="hidden" name="l0" value="<?php echo $row['id']; ?>">
                    <br>
					<fieldset>
					<br>
					<!-- Form Name -->
					<legend>New Achievement</legend>
					<br>
					<!-- Text input-->
					<div class="form-group required-control">
					  <label class="col-md-3 control-label" for="l1">Name</label>
					  <div class="col-md-6">
						<input id="l1" name="l1" type="text" placeholder="Name" class="form-control " required="">
						
					  </div>
					</div>

					<!-- Textarea -->
					<div class="form-group required-control">
					  <label class="col-md-3 control-label" for="l2">Description</label>
					  <div class="col-md-6">
						<textarea id="l2" name="l2" required="" class="form-control "></textarea>
					  </div>
					</div>

					<!-- Prepended checkbox -->
					<div class="form-group">
					  <label class="col-md-3 control-label" for="l3">Integral</label>
					  <div class="col-md-6">
						<div class="input-group">
						  <span class="input-group-addon">
							  <input type="checkbox" name="l4" value="Yes">
						  </span>
						  <input id="l3" name="l3" class="form-control " type="text" placeholder="">
						</div>
						<p class="help-block">If checked, the achievement will be integral and report progress. If not checked, the achievement will be boolean (locked/unlocked). Enter amount to unlock in the text area.</p>
					  </div>
					</div>

					<!-- Button -->
					<div class="form-group">
					  <label class="col-md-3 control-label" for="submit"></label>
					  <div class="col-lg-6">
						<button id="submit" name="submit" class="btn btn-primary">Create New</button>
					  </div>
					</div>

					</fieldset>
				</form>
			</div>
			<div class="tab-pane fade" id="n3">
				<!-- This is the tab that edits a current achievement -->
                <form class="form-horizontal" action="modadmin.php?m=eaz" method="post">
                    <br>
                    <fieldset>
                    <br>
                    <legend>Edit Achievement</legend>
					<input type="hidden" name="l0" value="<?php echo $row['id']; ?>">
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="select-achievement-edit">Select Achievement</label>
                        <div class="col-md-6">
                            <select id="select-achievement-edit" name="a1" class="form-control select-achievement-edit">
                                <option value="-1" selected>----------------</option>
                                <?php
                                foreach($acRow as $achievement) {
                                    $acName = $achievement['name'];
                                    $acId = $achievement['id'];
                                    echo "<option value=\"$acId\">$acName</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="container" id="edithide">
                        <p> horse potato </p>
                    </div>
                    </fieldset>
                </form>
			</div>
			<div class="tab-pane fade" id="n4">
				<!-- This is the tab that deletes a current achievement -->
				<form class="form-horizontal" action="modadmin.php?m=daz" method="post">
					<fieldset>
                    <br>
					<input type="hidden" name="k0" value="<?php echo $row['id']; ?>">
					<br><br><br>
					<!-- Form Name -->
					<legend>Delete Achievement</legend>
					<br>
					<!-- Select Basic -->
					<div class="form-group">
					  <label class="col-md-3 control-label" for="k1">Select Achievement</label>
					  <div class="col-md-6">
						<select id="k1" name="k1" class="form-control ">
							<?php
								foreach($acRow as $achievement) {
									$achievementId = $achievement['id'];
									$achievementName = $achievement['name'];
									echo "<option value=\"$achievementId\">$achievementName</option>";
								}
							?>
						</select>
					  </div>
					</div>

					<!-- Button -->
					<div class="form-group">
					  <label class="col-md-3 control-label" for="submit">Are you sure?</label>
					  <div class="col-lg-6">
						<button id="submit" name="submit" class="btn btn-danger">Yes.</button>
					  </div>
					</div>

					</fieldset>
				</form>
			</div>
			<div class="tab-pane fade" id="n5">
				<!-- This is the tab that deletes the whole mod :O -->
				<form class="form-horizontal" action="modaction.php?m=mdz" method="post">
                    <br>
					<fieldset>
					<input type="hidden" name="j0" value="<?php echo $row['id']; ?>">
					<br>
					<!-- Form Name -->
					<legend>Delete Mod</legend>
					<br>
					<!-- Button -->
					<div class="form-group">
					  <label class="col-md-3 control-label" for="submit">...Are you sure?</label>
					  <div class="col-lg-6">
						<button id="submit" name="submit" class="btn btn-danger">Sure as sugar!</button>
					  </div>
					</div>

					</fieldset>
				</form>
			</div>
		</div>
		<?php template_copyright(); ?>
	</div>
	<?php
	template_end_onlyscripts();
	?>
	<script type="text/javascript">
		// for the tabs
		$('#n1 a').click(function (e) {
			e.preventDefault()
			$(this).tab('show')
		})
		$('#n2 a').click(function (e) {
			e.preventDefault()
			$(this).tab('show')
		})
		$('#n3 a').click(function (e) {
			e.preventDefault()
			$(this).tab('show')
		})
		$('#n4 a').click(function (e) {
			e.preventDefault()
			$(this).tab('show')
		})
		$('#n5 a').click(function (e) {
			e.preventDefault()
			$(this).tab('show')
		})
        // for the selection on the edit tab
        function hideEditHide() {
            document.getElementById('edithide').style.visibility = 'hidden';
        }
        function showEditHide() {
            document.getElementById('edithide').style.visibility = 'visible';
        }
        $('.select-achievement-edit').change(function() {
            var v = $( "select.select-achievement-edit option:selected").val();
            
            if(v == -1)
                hideEditHide();
            else
                showEditHide();
        });
        $(document).ready(function() {
            hideEditHide();
        });
	</script>
	<?php
	template_end_noscripts();
}
else if($_GET['m'] === 'ez1') {
	// editing basic info
	if(!isset($_POST['m0']) || !isset($_POST['m1']) || !isset($_POST['m2']))
	{
		$msg->add('e', "Invalid form data.");
		header('Location: /admin.php');
		exit;
	}
	
	$v1 = escapeDB($_POST['m0']);						// mod id
	$v2 = escapeDB($_POST['m1']);						// new name
	$v3 = escapeDB($_POST['m2']);						// new description
	$v4 = (escapeDB($_POST['m3']) === "Yes") ? 1 : 0;	// visible?
	
	queryDB("UPDATE mods SET name='$v2', description='$v3', visible=$v4 WHERE id=$v1");
	$msg->add('s', "Successfully updated mod information.");
	header('Location: /admin.php');
	exit;
}
else if($_GET['m'] === 'aaz') {
	// creating new achievement
    if(!isset($_POST['l0']) || !isset($_POST['l1']) || !isset($_POST['l2'])) {
        $msg->add('e', "Invalid form data.");
        header('Location: /admin.php');
        exit;
    }
    
    $modId = escapeDB($_POST['l0']);
    if($modId < 0) {
        $msg->add('e', "Invalid form data.");
        header('Location: /admin.php');
        exit;
    }
    
    if(!isset($_POST['l4'])) {
        // non-integral type
        $integral = false;
        $numNeed = 1;
    }
    else {
        $integral = true;
        if(!isset($_POST['l3'])) {
            $msg->add('e', "No value given for integral type");
            header("Location: /modadmin.php?m=e&m1=$modId");
            exit;
        }
        $numNeed = escapeDB($_POST['l3']);
    }
    
    $name = escapeDB($_POST['l1']);
    $desc = escapeDB($_POST['l2']);
    
    // make sure that we don't already have an achievement by this name
    $entries = queryDB("SELECT * FROM modachieve_$modId WHERE name='$name'");
    if(count($entries) > 0) {
        $msg->add('e', "An achievement by this name already exists.");
        header("Location: /modadmin.php?m=e&m1=$modId");
        exit;
    }
    
    queryDB("INSERT INTO modachieve_$modId (name, description, number_achieved, type, count) VALUES ('$name', '$desc', '0', '$integral', '$numNeed')");
    // forgive me for the below
    $row = queryDB("SELECT id FROM modachieve_$modId WHERE name='$name'");
    $row = $row[0];
    
    $achieveId = $row['id'];
    // end forgiving
    queryDB("ALTER TABLE modusers_$modId ADD progress_$achieveId int(10) DEFAULT 0");
    queryDB("ALTER TABLE modusers_$modId ADD unlocked_$achieveId date");
    
    $msg->add('s', "Successfully added new achievement: $name");
    header("Location: /modadmin.php?m=e&m1=$modId");
    exit;
}
else if($_GET['m'] === 'eaz') {
	// editing current achievement
}
else if($_GET['m'] === 'daz') {
	// deleting an achievement
	if(!isset($_POST['k1']) || !isset($_POST['k0'])) {
		$msg->add('e', "Invalid form data.");
		header('Location: /admin.php');
		exit;
	}
	
	$id = escapeDB($_POST['k1']);
	$modId = escapeDB($_POST['k0']);
	queryDB("DELETE FROM modachieve_$modId WHERE id=$id");
	
	$msg->add('s', "Successfully deleted achievement.");
	header("Location: /modadmin.php?m=e&m1=$modId");
}
else if($_GET['m'] === 'mdz') {
	// :( deleting
	if(!isset($_POST['j0'])) {
		// there may be hope for us yet! :O
		$msg->add('e', "Invalid form data.");
		header('Location: /admin.php');
		exit;
	}
	
	// yeah, nope.
	$id = escapeDB($_POST['j0']);
	queryDB("DELETE FROM mods WHERE id=$id");
	queryDB("DROP modachieve_$id");
    queryDB("DROP modusers_$id");
	
	$msg->add('s', "Successfully deleted mod.");
	header('Location: /admin.php');
	exit;
}