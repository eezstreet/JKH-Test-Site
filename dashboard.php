<?php
if(!session_id()) session_start();
require_once 'include/users.php';
require_once 'include/template.php';

if($GLOBALS['loggedIn'] == false) {
    header('Location: /index.php');
    die();
}

template_start("Dashboard - JKHub API");
?>

<div class="container">
    <?php template_navbar("dashboard"); ?>
    <div class="container" style="width:60%;">
        <br><br> <!-- goddamn bootstrap navbars/Google Chrome -->
        <h1>Dashboard</h1>
        <form class="form-search" action="search.php?t=u" method="post">
            <div class="input-group">
                <input type="text" placeholder="Find Friends" class="form-control">
                <span class="input-group-btn">
                    <button type="submit" class="btn btn-default" type="button">
                        Search
                    </button>
                </span>
            </div>
        </form>
        <p> <?php template_copyright(); ?> </p>
    </div>
</div>

<?php
template_end();