<?PHP
require_once("functions/myUtils.php");

$myUtils = new myUtils();



if(isset($_POST['submit'])){
   if($myUtils->login()){
    header("Location: events.php");
   }
}
if(isset($_POST['register'])){
   $myUtils->register();
}

$nav = $myUtils->navBar('Login');
echo $nav;
?>

<div class="row">

<?php
$l = $myUtils->logString();
echo $l;
$r = $myUtils->regString();
echo $r;
?>

</div>

<?php
$footer = $myUtils->footer();
echo $footer;
?>


