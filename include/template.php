<?php 

function template_start($titleVar)
{
	?>
	<html lang="en">
		<head>
			<meta charset="utf-8">
			<meta name="author" content="eezstreet">
			<!-- TODO: favicon -->
			<link href="../style/bootstrap.css" rel="stylesheet">
			<link href="../style/boostrap-theme.css" rel="stylesheet">
            <link href="../style/bootstrap-custom.css" rel="stylesheet">
			<title><?php echo $titleVar ?></title>
		</head>
		<body>
	<?php
}

function template_end()
{
	?>
		<script src="../scripts/bootstrap.js"></script>
		<script src="../scripts/jquery.js"></script>
		</body>
	</html>
	<?php
}

function template_copyright()
{
    ?>
    <div class="footer">
		<p>&copy; JKHub/eezstreet 2013</p>
	</div>
    <?php
}

function template_title()
{
    ?>
    <h1><a href="/index.php">JKHub API</a></h1>
    <?php
}

function __doLI($active, $check)
{
    if($active == $check)
        echo 'class="active"';
}

function template_navbar($active)
{
    ?>
    <div class="navbar navbar-default navbar-fixed-top" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <a class="navbar-brand" href="/index.php">JKHub API</a>
            </div>
            <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li <?php __doLI($active, "dashboard"); ?>><a href="/dashboard.php">Dashboard</a></li>
                    <li <?php __doLI($active, "profile"); ?>><a href="/yourprofile.php">Profile</a></li>
                    <li <?php __doLI($active, "mods"); ?>><a href="/mods.php">Mods</a></li>
                    <li <?php __doLI($active, "admin"); ?>><a href="/admin.php">Admin</a></li>
                </ul>
            </div>
        </div>
    </div>
    <?php
}
?>