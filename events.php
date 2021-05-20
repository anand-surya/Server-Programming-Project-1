<?php

require_once("functions/myUtils.php");
require_once("database/DB.php");

$myUtils = new myUtils();

if(!$myUtils->isLoggedIn()){
    header("Location: index.php");
}
else {
    if($myUtils->isManager($_SESSION['username']) or $myUtils->isAdmin($_SESSION['username'])){
    $nav = $myUtils->navBarAdmin('Home');
    echo $nav;
    echo "<h3 id='Header'><b>Your Upcoming Sessions</b></h3>";
    $db = new DB();
    $table = $db->registeredSessionsAsTable($_SESSION['username']);
    echo $table;
    echo "<h3 id='Header'><b>All Events/Sessions</b></h3>";
    $table1 = $db->getEventsAsTable();
    echo $table1;
    }

    else if($myUtils->isSAdmin($_SESSION['username'])){
        $nav = $myUtils->navBarSuperAdmin('Home');
        echo $nav;
        $string = "<h2> You are Super Admin</h2>";
        $string .= "<img id='sadmin' src='assets/superadmin.png'></div>";
        echo $string;

    }

    else{
    $nav = $myUtils->navBarMain('Home');
    echo $nav; 
    echo "<h3 id='Header'><b>Your Upcoming Sessions</b></h3>";
    $db = new DB();
    $table = $db->registeredSessionsAsTable($_SESSION['username']);
    echo $table;
    echo "<h3 id='Header'><b>All Events/Sessions</b></h3>";
    $table1 = $db->getEventsAsTable();
    echo $table1;
    }
    
}

$footer = $myUtils->footer();
echo $footer;
?>