<?php
if(!session_id()) session_start();
require_once 'include/users.php';
include_once 'include/template.php';
require_once 'include/messages.php';

$msg = new Messages();

function YourRegistrationSucks($msg, $message)
{
    $msg->add('e', $message);
    header('Location: ' . $_SERVER['PHP_SELF']);
    die();
}

if($GLOBALS['loggedIn'] == true)
{
    $msg->add('s', "Successfully logged in.");
    header('Location: /index.php');
    die();
}
else if(!empty($_POST))
{
    // Registering a new account
    $accessCode = '7aE4AgL8n@5FpIlD'; // DO NOT SHARE WITH --ANYONE--
    if(!isset($_POST['u']))
    {
        YourRegistrationSucks($msg, "Please enter a username.");
    }
    else if(!isset($_POST['p']))
    {
        YourRegistrationSucks($msg, "Please enter a password.");
    }
    else if(!isset($_POST['a']))
    {
        YourRegistrationSucks($msg, "Please enter the 16-digit access code that you were given.");
    }
    else if(strcmp($_POST['a'], $accessCode))
    {
        YourRegistrationSucks($msg, "Invalid access code.");
    }
    else if(strlen($_POST['u']) < 2)
    {
        YourRegistrationSucks($msg, "Username must be at least 2 characters.");
    }
    else if(strlen($_POST['p']) < 4)
    {
        YourRegistrationSucks($msg, "Password must be at least 4 characters.");
    }
    
    $saltyFries = rand(0, 32768);
    $pass = escapeDB($_POST['p']);
    $user = escapeDB($_POST['u']);
    
    // Check and make sure that a user with this username doesn't already exist
    $query = "SELECT id FROM users WHERE username='$user'";
    $userQuery = queryDB("SELECT id FROM users WHERE username='$user'");
    if(count($userQuery) > 0)
        YourRegistrationSucks($msg, "A user by that name already exists.");
    
    $passwordHashed = saltTheWound($pass, $saltyFries);
    
    queryDB("INSERT INTO users (username, password, salt, membersince)
             VALUES ('$user', '$passwordHashed', '$saltyFries', NOW())");
    $msg->add('s', "Successfully registered! You may now login.");
    header('Location: /index.php');
}
else
{
    // Display the page
    template_start("JKHub API - Register");
    
    // TODO: popover
    ?>
    <br>
	<div class="container" style="width:60%;">
        <h1><a href="index.php">JKHub API Test Site</a></h1>
        <?php echo $msg->display(); ?>
        <form action="register.php" method="post">
            <h2>Register</h2>
            <div class="input-group input-group-lg" style="container: 'body';">
                <span class="input-group-addon" style="width:10%;">Username</span>
                <input type="text" class="form-control" placeholder="Username" name="u">
            </div><br>
            <div class="input-group input-group-lg" style="container: 'body';">
                <span class="input-group-addon" style="width:10%;">Password</span>
                <input type="password" class="form-control" placeholder="Password" name="p">
            </div><br>
            <div class="input-group input-group-lg" style="container: 'body';">
                <span class="input-group-addon" style="width:10%;">Email Address</span>
                <input type="text" class="form-control" placeholder="Email Address" name="e">
            </div><br>
            <div class="input-group input-group-lg" style="container: 'body';">
                <span class="input-group-addon" style="width:10%;">Access Code</span>
                <input type="password" class="form-control" name="a">
            </div><br>
            <div class="input-group input-group-lg" style="container: 'body';">
                <input type="submit" class="btn-primary btn-lg" placeholder="Submit">
            </div>
        </form>
        <?php template_copyright(); ?>
    </div>
<?php
}

template_end();

