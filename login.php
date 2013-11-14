<?php
session_start();
require_once 'include/users.php';
include_once 'include/template.php';
require_once 'include/messages.php';

$msg = new Messages();

if($loggedIn == true)
{
    $msg->add('s', 'Successfully logged in.');
	header("Location: /index.php");
}
else if(!empty($_POST))
{
	// Most likely logging in.
	$login_ok = false;
    
    $user = escapeDB($_POST['u']);
	
	$row = queryDB("SELECT * FROM users WHERE username = '$user'");
	if(count($row) > 0)
    {
        $row = $row[0];
        
        // We don't need to sanitize the password because we aren't using it in a query
        $checkPass = saltTheWound($_POST['p'], $row['salt']);
        if($checkPass === $row['password'])
        {
            $login_ok = true;
        }
        
        unset($row['salt']);
        unset($row['password']);
    }
	
    unset($_POST);
	if($login_ok == false)
	{ // Page for "Bad credentials"
        $msg->add('e', 'Invalid username/password');
		header('Location: ' . $_SERVER['PHP_SELF']);
	}
	else
	{ // Page for "Everything OK"
		$loggedIn = true;
		$_SESSION['userid'] = $row['id'];
        $_SESSION['username'] = $row['username'];
		$msg->add('s', 'Successfully logged in');
        queryDB("UPDATE users SET lastlogin=NOW() WHERE id=".$row['id']);
        header("Location: /index.php");
	}
    
    die();
}
else
{
	// Just display the login page
    template_start("JKHub API - Login");
	?>
	<br>
	<div class="container" style="width:60%;">
        <h1>JKHub API Test Site</h1>
        <?php echo $msg->display(); ?>
        <form class="form-signin" action="login.php" method="post">
        <div class="row">
            <div class="col-lg-6">
                <h2>Login</h2>
                <div class="jumbotron">
                    <input type="text" class="form-control" placeholder="Username" name="u" autofocus>
                    <input type="password" class="form-control" placeholder="Password" name="p">
                    <button class="btn btn-lg btn-success" type="submit" style="width:50%;">Login</button>
                    <button class="btn btn-lg btn-link"><a href="register.php">Register</a></button>
                </div>
            </div>
            <div class="col-lg-6">
                <h2>What's this?</h2>
                <p>Registration allows access to the developer network, which provides a modified OpenJK executable for testing purposes. The executable will communicate with this test site, where you will find your profile (which has information regarding earned achievements), as well as others'. <b>Registration is invitation-only and requires a password.</b></p>
            </div>
        </div>
        </form>
        <button class="btn btn-lg btn-primary"><a href="index.php" style="color:#FFFFFF;">Return to index</a></button>
	</div>
	<?php
}

template_end();