<?php
if(!session_id()) session_start();
require_once 'include/users.php';
require_once 'include/template.php';

adminAuth(1);

if($_GET['m'] === 'a') {
    // add new mod
    if(!isset($_POST['modname']))
        $placeText = "Mod Name";
    else
        $placeText = escapeDB($_POST['modname']);
    
    template_start("JKHub API - Register Mod");
    
    echo "<div class=\"container\">";
    template_navbar("admin");
    ?>
        <br><br>
        <div class="row">
            <h1>Register New Modification</h1>
        </div>
        <div class="row">
            <div class="col-lg-6">
            </div>
            <div class="col=lg-6">
                <div class="jumbobox">
                    <h2>Basic Information</h2>
                    <div class="form-group">
                        <label>Mod Name</label>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea>Write a brief description of the mod here.</textarea>
                    </div>
                </div>
            </div>
        </div>
    <?php
    echo "</div>";
    
    template_end();
}
else if($_GET['m'] === 'e') {
    // editing/deleting a mod
}