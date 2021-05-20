<?php

require_once("functions/myUtils.php");
require_once("database/DB.php");

$myUtils = new myUtils();

if(!$myUtils->isLoggedIn()){
    header("Location: index.php");
}

else {
    if($myUtils->isManager($_SESSION['username']) or $myUtils->isAdmin($_SESSION['username'])){
    $nav = $myUtils->navBarAdmin('Manage Events');
    echo $nav;
    } else{
    $nav = $myUtils->navBarMain('Manage Events');
    echo $nav; 
    }
    
    if (isset($_GET['adds']) and $_GET['adde']!=0 ){
        $myUtils->registerSession($_GET['adds'],$_GET['adde']);
    }
    if (isset($_GET['deletes']) and $_GET['deletee']!=0 ){
        $myUtils->deleteSession($_GET['deletes'],$_GET['deletee']);
    }

    echo "<h3 id='Header'><b>Register Events</b></h3>";
    $db = new DB();
    $table1 = $db->addEventsAsTable($_SESSION['username']);
    echo $table1; 
    
    echo "<h3 id='Header'><b>Delete Events</b></h3>";
    $db = new DB();
    $table = $db->registeredEventsAsTable($_SESSION['username']);
    echo $table;    
   
}

$footer = $myUtils->footer();
echo $footer;
?>