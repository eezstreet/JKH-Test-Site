<?php
if(!session_id()) session_start();
include_once 'include/template.php';
include_once 'include/users.php';
require_once 'include/messages.php';

template_start("JKHub API Testing Grounds");
$msg = new Messages();
?>

<br><br>
<div class="container" style="width:60%;">
    <?php echo $msg->display(); ?>
	<div class="jumbotron">
		<h1>JKHub API</h1>
		<p class="lead">The next big thing to hit Jedi Academy. Join the revolution. </p>
        <?php
        if($GLOBALS['loggedIn']) {
            $displayUsername = "<a href=\"profile.php?u=" . $_SESSION['userid'] ."\">" . $_SESSION['username'] . "</a>";
            echo "<p><i style=\"font-size:14px;\">Welcome, $displayUsername.</i>";
        ?>
        
        <br><a class="btn btn-lg btn-success" href="dashboard.php">Continue to Dashboard</a>
          <a class="btn btn-lg btn-information" href="logout.php">Log out</a></p>
        
        <?php
        } else { ?>
		<p><a class="btn btn-lg btn-success" href="login.php">Login</a>
			<a class="btn btn-lg btn-information" href="register.php">Register</a></p>
        <?php } ?>
	</div>
	
	<h3>Major Features</h3>
	
	<div class="row">
		<div class="col-lg-6">
			<h4>Achievements</h4>
			<p>Impress others with your awesome feats.</p>
		</div>
		
		<div class="col-lg-6">
			<h4>OpenJK Protocol</h4>
			<p>Launch the game from a simple link. <i style="font-size:8px;">(Requires OpenJK)</i></p>
		</div>
	</div>
	
	<hr>
	
	<?php template_copyright(); ?>
</div>


<?php
template_end();
