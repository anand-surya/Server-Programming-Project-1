<?php
require_once('database/DB.php');
require_once('functions/validation.php');

class myUtils{

    var $e_message;
    var $r_message;
    function navBar($param){
        $nav = "<html>
                <head>
                <meta http-equiv='content-type' content='text/html; charset=utf-8'/>
                <title>".$param."</title>
                <link rel='STYLESHEET' type='text/css' href='css/style.css'/>      
                </head>";
        $nav .= "<body>
        <div id='header'>
        <h2 id='header'>Event Management Application<h2>
        </div>";
        return $nav;
    }

    function navBarMain($param){
        $nav = "<html>
                <head>
                <meta http-equiv='content-type' content='text/html; charset=utf-8'/>
                <title>".$param."</title>
                <link rel='STYLESHEET' type='text/css' href='css/style.css'/>      
                </head>";
        $nav .= "<body><div id='header'><h2 id='header'>Event Management Application<h2></div>";
        $nav .= "<div class='topnav'>";
        $nav .= "<a href='events.php'>Home</a>";
        $nav .= "<a href='manage.php'>Register Events</a>";
        $nav .= "<a href='logout.php'>Logout</a>
        <p>Welcome ".$_SESSION['username']." !!</p></div>\n";
        return $nav;
    }
    function navBarAdmin($param){
        $nav = "<html>
                <head>
                <meta http-equiv='content-type' content='text/html; charset=utf-8'/>
                <title>".$param."</title>
                <link rel='STYLESHEET' type='text/css' href='css/style.css'/>      
                </head>";
        $nav .= "<body><div id='header'><h2 id='header'>Event Management Application<h2></div>";
        $nav .= "<div class='topnav'>";
        $nav .= "<a href='events.php'>Home</a>";
        $nav .= "<a href='manage.php'>Register Events</a>";
        $nav .= "<a href='admin.php'>Admin</a>";
        $nav .= "<a href='logout.php'>Logout</a>
        <p>Welcome ".$_SESSION['username']." !!</p></div>\n";
        return $nav;
    }

    function navBarSuperAdmin($param){
        $nav = "<html>
                <head>
                <meta http-equiv='content-type' content='text/html; charset=utf-8'/>
                <title>".$param."</title>
                <link rel='STYLESHEET' type='text/css' href='css/style.css'/>      
                </head>";
        $nav .= "<body><div id='header'><h2 id='header'>Event Management Application<h2></div>";
        $nav .= "<div class='topnav'>";
        $nav .= "<a href='events.php'>Home</a>";
        $nav .= "<a href='admin.php'>Admin</a>";
        $nav .= "<a href='logout.php'>Logout</a>
        <p>Welcome ".$_SESSION['username']." !!</p></div>\n";
        return $nav;
    }
    
    function logString(){
        $log = "<div class='column'>
                <p><b>LOGIN</b></p>
                <form action='index.php' method='POST'>
                    <div class='container'>
                        <input type='text' name='username' id='username' placeholder='Username'>
                </div>
                <div class='container'>
                        <input type='password' name='password' id='password' maxlength='20' placeholder='password'>
                </div>
                <div class='container'>      
                        <input type='submit' name='submit' value='Submit'>
                </div>
                </form>
                </div>";

            return $log;
    }

    function regString(){
        $reg = "<div class='column'>
                <p><b>REGISTER</b></p>
                <div>

                </div>
                <form action='index.php' method='POST' id='form2'>
                <div class='ontainer'>
                    <input type='text' name='username' id='username' placeholder='Username'>
                </div>
                <div class='container'>
                    <input type='password' name='password' id='password' maxlength='20' placeholder='password'>
                </div>
                <div class='container'>
                    <input type='submit' name='register' value='Register'>
                </div>
                </form>
                </div>";
        return $reg;
    }

    function footer() {
        $string = "<div id='footer'>";
        $string .= "<p class='rainbow'>Designed and Developed by Anand Surya Rajasrinivasan</p></div>";
        $string .= "</body></html>";
        return $string;
    }


    function login(){
        $val = new validation();
        $username = $val->sanitizeString($_POST['username']);
        $password = $val->sanitizeString($_POST['password']);
            if($username == ''){
                echo "<script type='text/javascript'>alert('Username is empty')</script>"; 
                return false;
            }
            if (!$val->alphabeticSpace($username)){
                echo "<script type='text/javascript'>alert('Username should be Alphabets')</script>"; 
                return false;
            }
            if($password == ''){
                echo "<script type='text/javascript'>alert('Password is empty')</script>"; 
                return false;
            }
        $db = new DB();
        if(!isset($_SESSION)){ 
            session_start(); }
        if(count($db->login($username,$password))<=0){
            echo "<script type='text/javascript'>alert('Username and Password does not match')</script>"; 
            return false;
        }
        
        $_SESSION['username'] = $username;
        
        return true;
    }
    

    function register(){
        
        $val = new validation();
        $username = $val->sanitizeString($_POST['username']);
        $password = $val->sanitizeString($_POST['password']);
            if($username == ''){
                echo "<script type='text/javascript'>alert('Username is empty')</script>"; 
                return false;
            }
            if (!$val->alphabeticSpace($username)){
                echo "<script type='text/javascript'>alert('Username should be Alphabets')</script>"; 
                return false;
            }
            if($password == ''){
                echo "<script type='text/javascript'>alert('Password is empty')</script>"; 
                return false;
            }
        $db = new DB();
        if(count($db->getAttendee($username)) > 0)
        {
            echo "<script type='text/javascript'>alert('This UserName is already used. Try different username')</script>"; 
        }
        else{
            if(!$db->register($username,$password)){
                
                echo "<script type='text/javascript'>alert('Registration failed. Try again')</script>"; 
                return false;
            }
            else{
                echo "<script type='text/javascript'>alert('Registration Success. Login to access')</script>"; 
            }
        }
            
        }
        

    function isLoggedIn()
    {
         if(!isset($_SESSION)){ 
             session_start(); 
            }
         if(empty($_SESSION['username'])){
            return false;
         }
         return true;
    }

    function isManager($uname){
        $db = new DB();
        if(count($db->isManager($uname)) <= 0){
            return false;
        }
        return true;
    }

    function isAdmin($uname){
        $db = new DB();
        if(count($db->isAdmin($uname)) <= 0){
            return false;
        }
        return true;
    }

    function isSAdmin($uname){
        $db = new DB();
        if(count($db->isSAdmin($uname)) <= 0){
            return false;
        }
        return true;
    }

 

    function deleteSession($idsession,$idevent){
        $db = new DB();
           $db->deleteSession($_SESSION['username'],$idsession);
           $db->deleteEvent($_SESSION['username'],$idevent);
        header("Location: manage.php");
        return true;
    }

    function registerSession($idsession,$idevent){
        $db = new DB();
        if(count($db->isRegister($_SESSION['username'],$idsession)) > 0 and count($db->isRegisterEvent($_SESSION['username'],$idevent)) > 0){
            echo "<script type='text/javascript'>alert('You already registered for this session')</script>";   
            return false;
        } 
        else if(count($db->isRegisterEvent($_SESSION['username'],$idevent)) > 0 and count($db->isRegister($_SESSION['username'],$idsession)) <= 0){
            $db->registerSession($_SESSION['username'],$idsession);
            
        } 
        else if(count($db->isRegisterEvent($_SESSION['username'],$idevent)) <= 0 and count($db->isRegister($_SESSION['username'],$idsession)) <= 0){ 
            $db->registerSession($_SESSION['username'],$idsession);
            $db->registerEvent($_SESSION['username'],$idevent);
        }
      
        header("Location: manage.php");
        return true;
    }

    function addUForm(){
        $string = "<form  action='admin.php' method='post'>
                <label>Username: </label>
                <input type='text' name='username' id='username' placeholder='Username'/><br/>
                <label>Password: </label>
                <input type='password' name='password' id='password' placeholder='Password'/><br/>
                <label>Role: </label>
                <input type='text' name='role' id='role' placeholder='Role'/><br/>
                <input type='submit' name='addUser' value='Add' /></form>";
        return $string;
    }

    function addUser()
    {
        $val = new validation();
        $uname = $val->sanitizeString($_POST['username']);
        $password = $val->sanitizeString($_POST['password']);
        $role = $val->sanitizeString($_POST['role']);
            if($uname == ''){
                echo "<script type='text/javascript'>alert('Username is empty')</script>"; 
                return false;
            }
            if (!$val->alphabeticSpace($uname)){
                echo "<script type='text/javascript'>alert('Username should be Alphabets')</script>"; 
                return false;
            }
            if($password == ''){
                echo "<script type='text/javascript'>alert('Password is empty')</script>"; 
                return false;
            }
            if($role == ''){
                echo "<script type='text/javascript'>alert('Role is empty')</script>"; 
                return false;
            }
        $db = new DB();
        if(count($db->getAttendee($uname)) > 0)
        {
            echo "<script type='text/javascript'>alert('This UserName is already used. Please try another username')</script>"; 
            return false;
        }
        
        if($db->addUser($uname, $password,$role))
        {
            header("Location: admin.php");

        } else {
            echo "<script type='text/javascript'>alert('Failed to add user. Try again')</script>";
            return false;
        }
        return true;
    
    }
    

    function deleteUser($id){
        $db = new DB();
        
        $db->deleteUser($id);
        header("Location: admin.php");
        
        return true;
    }

    function editUForm(){
        $string = "<form  method='post'>
                <label>Username: </label>
                <input type='text' name='username' id='username' placeholder='Username'/><br/>
                <label>Role: </label>
                <input type='text' name='role' id='role' placeholder='Role ID'/><br/>
                <input type='submit' name='updateUser' value='Update' /></form>";
        return $string;
    }

    function editUser($id){
            $val = new validation();
            $uname = $val->sanitizeString($_POST['username']);
            $role = $val->sanitizeString($_POST['role']);
                if($uname == ''){
                    echo "<script type='text/javascript'>alert('Username is empty')</script>"; 
                    return false;
                }
                if (!$val->alphabeticSpace($uname)){
                    echo "<script type='text/javascript'>alert('Username should be Alphabets')</script>"; 
                    return false;
                }            
                if($role == ''){
                    echo "<script type='text/javascript'>alert('Role is empty')</script>"; 
                    return false;
                }
            $db = new DB();
                if($db->editUser($id,$uname,$role)){
                    header("Location: admin.php");
                } else {
                    echo "<script type='text/javascript'>alert('Failed to edit user. Try again')</script>";
                    return false;
                }    
        return true;
    }

    function deleteEvent($id){
        
            $db = new DB();
            $db->delEvent($id);
            $db->delEventSession($id);
            $db->delEventAttendee($id);
            $db->delEventManager($id);
            $db->updateEventManager($id);
            header("Location: admin.php");
      
        return true;
    }

    function addEForm(){
        $string = "<div><form action='admin.php'  method='post'>
                <label>Event Name: </label>
                <input type='text' name='eventname' placeholder='Event Name'/><br/>
                <label>Start Date and Time: </label>
                <input type='text' name='starttime' placeholder='Format:yyyy-mm-dd hh:mm:ss'/><br/>
                <label>End Date and Time: </label>
                <input type='text' name='endtime' placeholder='Format:yyyy-mm-dd hh:mm:ss'/><br/>
                <label>Number allowed: </label>
                <input type='text' name='numAdd' placeholder='Number Allowed'/><br/>
                <label>Venue: </label>
                <input type='text' name='venueAdd' placeholder='Venue ID'/><br/>
                <input type='submit' name='addEvent' value='Add Event' /></form></div>";
        return $string;
    }

    function addEvent()
    {   
            $val = new validation();
            $eventName = $val->sanitizeString($_POST['eventname']);
            $startTime = $val->sanitizeString($_POST['starttime']);
            $endTime = $val->sanitizeString($_POST['endtime']);
            $numAdd = $val->sanitizeString($_POST['numAdd']);
            $venueAdd = $val->sanitizeString($_POST['venueAdd']);
            if($eventName == ''){
                echo "<script type='text/javascript'>alert('Event name is empty')</script>"; 
                return false;
            }
            if (!$val->alphabeticNumeric($eventName)){
                echo "<script type='text/javascript'>alert('Event name is not Alpha Numeric')</script>"; 
                return false;
            }
            if($startTime == ''){
                echo "<script type='text/javascript'>alert('Start Time is empty')</script>"; 
                return false;
            }
            if($endTime == ''){
                echo "<script type='text/javascript'>alert('End Time is empty')</script>"; 
                return false;
            }
            if (!$val->date($startTime) || !$val->date($endTime)){
                echo "<script type='text/javascript'>alert('Time must be in this format yyyy-mm-dd hh:mm:ss')</script>"; 
                return false;
            }
            if($numAdd == ''){
                echo "<script type='text/javascript'>alert('Number allowed is empty')</script>"; 
                return false;
            }
            if($venueAdd == ''){
                echo "<script type='text/javascript'>alert('Venue ID is empty')</script>"; 
                return false;
            }

            $db = new DB();

            if($db->addEvent($eventName,$startTime,$endTime,$numAdd,$venueAdd)){
                if($this->isManager($_SESSION['username'])){
                    $db->addEventManager($_SESSION['username'],$eventName);
                   header("Location: admin.php");

                } 
                else{
                    header("Location: admin.php");
                }
              
            } else {
                echo "<script type='text/javascript'>alert('Failed to Add event. Try again')</script>"; 
            }
        
        
        return true;
    }

    function editEForm(){
        $string = "<div><form method='post'>
                <label>Event Name: </label>
                <input type='text' name='eventName' id='eventName' placeholder='Event Name'/><br/>
                <label>Start Date and Time: </label>
                <input type='text' name='startTime' id='startTime' placeholder='Format:yyyy-mm-dd hh:mm:ss'/><br/>
                <label>End Date and Time: </label>
                <input type='text' name='endTime' id='endTime' placeholder='Format:yyyy-mm-dd hh:mm:ss'/><br/>
                <label>Number allowed: </label>
                <input type='text' name='num' id='num' placeholder='Number Allowed'/><br/>
                <label>Venue: </label>
                <input type='text' name='venue' id='venue' placeholder='Venue ID'/><br/>
                <input type='submit' name='updateEvent' value='Edit Event' /></form></div>";
        return $string;
    }

    function editEvent($id){
            $val = new validation();
            $eventName = $val->sanitizeString($_POST['eventName']);
            $startTime = $val->sanitizeString($_POST['startTime']);
            $endTime = $val->sanitizeString($_POST['endTime']);
            $num = $val->sanitizeString($_POST['num']);
            $venue = $val->sanitizeString($_POST['venue']);
            if($eventName == ''){
                echo "<script type='text/javascript'>alert('Event name is empty')</script>"; 
                return false;
            }
            if (!$val->alphabeticNumeric($eventName)){
                echo "<script type='text/javascript'>alert('Event name is not Alpha Numeric')</script>"; 
                return false;
            }
            if($startTime == ''){
                echo "<script type='text/javascript'>alert('Start Time is empty')</script>"; 
                return false;
            }
            if($endTime == ''){
                echo "<script type='text/javascript'>alert('End Time is empty')</script>"; 
                return false;
            }
            if (!$val->date($startTime) || !$val->date($endTime)){
                echo "<script type='text/javascript'>alert('Time must be in this format yyyy-mm-dd hh:mm:ss')</script>"; 
                return false;
            }
            if($num == ''){
                echo "<script type='text/javascript'>alert('Number allowed is empty')</script>"; 
                return false;
            }
            if($venue == ''){
                echo "<script type='text/javascript'>alert('Venue ID is empty')</script>"; 
                return false;
            }
            
            $db = new DB();
            if($db->editEvent($id,$eventName,$startTime,$endTime,$num,$venue)){
                header("Location: admin.php");
            } 
            else {
                echo "<script type='text/javascript'>alert('Failed to Update. Try again')</script>"; 
            }

        
        return true;
    }

    function addSForm(){
        $string = "<div><form action='admin.php' method='post'>
                <label>Event ID: </label>
                <input type='text' name='eventid' placeholder='Event ID'/><br/>
                <label>Session Name: </label>
                <input type='text' name='sname' placeholder='Session Name'/><br/>
                <label>Number allowed: </label>
                <input type='text' name='capacity' placeholder='Number Allowed'/><br/>
                <label>Start Date and Time: </label>
                <input type='text' name='sstart' placeholder='Format:yyyy-mm-dd hh:mm:ss'/><br/>
                <label>End Date and Time: </label>
                <input type='text' name='send' placeholder='Format:yyyy-mm-dd hh:mm:ss'/><br/>
                <input type='submit' name='addSes' value='Add Session' /></form></div>";
        return $string;
    }

    function addSession(){   
            $val = new validation();
            $eventid = $val->sanitizeString($_POST['eventid']);
            $sessionName = $val->sanitizeString($_POST['sname']);
            $capacity = $val->sanitizeString($_POST['capacity']);
            $sessionStart = $val->sanitizeString($_POST['sstart']);
            $sessionEnd = $val->sanitizeString($_POST['send']);
            if($eventid == ''){
                echo "<script type='text/javascript'>alert('Event Id is empty')</script>"; 
                return false;
            }
            if($sessionName == ''){
                echo "<script type='text/javascript'>alert('Session Name is empty')</script>"; 
                return false;
            }
            if (!$val->alphabeticNumeric($sessionName)){
                echo "<script type='text/javascript'>alert('Session name should be Alpha Numeric')</script>"; 
                return false;
            }
            if($sessionStart == ''){
                echo "<script type='text/javascript'>alert('Start Time is empty')</script>"; 
                return false;
            }
            if($sessionEnd == ''){
                echo "<script type='text/javascript'>alert('End Time is empty')</script>"; 
                return false;
            }
            if (!$val->date($sessionStart) || !$val->date($sessionEnd)){
                echo "<script type='text/javascript'>alert('Time must be in this format yyyy-mm-dd hh:mm:ss')</script>"; 
                return false;
            }
            if($capacity == ''){
                echo "<script type='text/javascript'>alert('Number allowed is empty')</script>"; 
                return false;
            }
        
        $db = new DB();

        if($db->addSession($sessionName, $sessionStart,$sessionEnd,$capacity,$eventid)){
            
            header("Location: admin.php");   
        } else {
            echo "<script type='text/javascript'>alert('Failed to Add. Try again)</script>";
        }
        return true;
    }

    function delSession($id){

            $db = new DB();
            $db->delSession($id);
            $db->delSesAttendee($id);
            header("Location: admin.php");

        return true;
    }

    function editSForm(){
        $string = "<div><form  method='post'>
                <label>Session Name: </label>
                <input type='text' name='sessionname' placeholder='Session Name'/><br/>
                <label>Number allowed: </label>
                <input type='text' name='capacity' placeholder='Number Allowed'/><br/>
                <label>Start Date and Time: </label>
                <input type='text' name='sessionstart' placeholder='Format:yyyy-mm-dd hh:mm:ss'/><br/>
                <label>End Date and Time: </label>
                <input type='text' name='sessionend' placeholder='Format:yyyy-mm-dd hh:mm:ss'/><br/>
                <input type='submit' name='updateSes' value='Edit Session' /></form></div>";
        return $string;
    }

    function editSes($id){
            $val = new validation();
            $sessionName = $val->sanitizeString($_POST['sessionname']);
            $capacity = $val->sanitizeString($_POST['capacity']);
            $sessionStart = $val->sanitizeString($_POST['sessionstart']);
            $sessionEnd = $val->sanitizeString($_POST['sessionend']);
            if($sessionName == ''){
                echo "<script type='text/javascript'>alert('Session Name is empty')</script>"; 
                return false;
            }
            if (!$val->alphabeticNumeric($sessionName)){
                echo "<script type='text/javascript'>alert('Session name should be Alpha Numeric')</script>"; 
                return false;
            }
            if($sessionStart == ''){
                echo "<script type='text/javascript'>alert('Start Time is empty')</script>"; 
                return false;
            }
            if($sessionEnd == ''){
                echo "<script type='text/javascript'>alert('End Time is empty')</script>"; 
                return false;
            }
            if (!$val->date($sessionStart) || !$val->date($sessionEnd)){
                echo "<script type='text/javascript'>alert('Time must be in this format yyyy-mm-dd hh:mm:ss')</script>"; 
                return false;
            }
            if($capacity == ''){
                echo "<script type='text/javascript'>alert('Number allowed is empty')</script>"; 
                return false;
            }
            
            $db = new DB();
            if($db->editSession($id,$sessionName,$sessionStart,$sessionEnd,$capacity)){
                
                header("Location: admin.php");
                
            } 
            else {
                echo "<script type='text/javascript'>alert('Failed to Edit. Try again)</script>";
            }
        
        
        return true;
    }

    function delAttendee($attid,$eventid,$sesid){
        
            $db = new DB();
            if($db->deleteAttendeeS($attid,$sesid)){  
                $db->deleteAttendeeE($attid,$eventid);
                header("Location: admin.php");

            } 
            else {
                echo "<script type='text/javascript'>alert('Unable to Delete)</script>";
            }
        
        return true;
    }


    function addAForm(){
        $db = new DB();
        $aid = $db->getAA();
        $sid = $db->getSessions();
        $eid = $db->getAEvents();
        $string = "<div><form method='post'>
                 <span>select matching event id and session id to successfully add the attendee</span><br><br>
                <label>Attendee Name: </label>
                <select id='aId' name='aId'>";
            foreach($aid as $val){
                $string .= "<option value='{$val['name']}'>{$val['name']}</option>";
            }
            $string .= "</select><br><label>Event ID: </label><select id='eId' name='eId'>";
            foreach($eid as $val1){
                $string .= "<option value='{$val1['idevent']}'>{$val1['idevent']}</option>";
            }
            $string .= "</select><br><label>Session ID: </label><select id='sId' name='sId'>";
            foreach($sid as $val1){
                $string .= "<option value='{$val1['idsession']}'>{$val1['idsession']}</option>";
            }
                $string .= "</select><input type='submit' name='addAtt' value='Add Attendee' /></form></div>";
        return $string;
    }

    function registerSes($uname,$idevent,$idsession){
        $db = new DB();
        if(count($db->isRegister($uname,$idsession)) > 0 and count($db->isRegisterEvent($uname,$idevent)) > 0)
        {
            echo "<script type='text/javascript'>alert('You already registered for this session')</script>";   
            return false;
        } 
        else if(count($db->isRegisterEvent($uname,$idevent)) > 0 and count($db->isRegister($uname,$idsession)) <= 0)
        {
            $db->registerSession($uname,$idsession);
            
        } 
        else if(count($db->isRegisterEvent($uname,$idevent)) <= 0 and count($db->isRegister($uname,$idsession)) <= 0)
        { 
            $db->registerSession($uname,$idsession);
            $db->registerEvent($uname,$idevent);
        }
      
        header("Location: admin.php");
        return true;
    }

    function addVForm(){
        $string = "<div><form method='post'>
                <label>Venue Name: </label>
                <input type='text' name='vname' placeholder='Venue Name'/><br/>
                <label>Capacity: </label>
                <input type='text' name='capacity' placeholder='Capacity'/><br/>
                <input type='submit' name='addVenue' value='Add Venue' /></form></div>";
        return $string;
    }

    function addVenue(){
            $val = new validation();
            $venuename = $val->sanitizeString($_POST['vname']);
            $capacity = $val->sanitizeString($_POST['capacity']);
            if($venuename == ''){
                echo "<script type='text/javascript'>alert('Venue Name is empty')</script>"; 
                return false;
            }
            if (!$val->alphabeticNumeric($venuename)){
                echo "<script type='text/javascript'>alert('Venue name should be Alpha Numeric')</script>"; 
                return false;
            }
            if($capacity == ''){
                echo "<script type='text/javascript'>alert('Capacity is empty')</script>"; 
                return false;
            }

            $db = new DB();
            if($db->addVenue($venuename,$capacity)){
                
                header("Location: admin.php");

            } 
            else {
                echo "<script type='text/javascript'>alert('Failed to add Venue. Try again')</script>"; 
            }
    
        return true;
    }

    function deleteVenue($id){
            $db = new DB();
            $db->deleteVenue($id);
                header("Location: admin.php");
        return true;
    }

    function editVForm(){
        $string = "<div><form method='post'>
                <label>Venue Name: </label>
                <input type='text' name='vname' placeholder='Venue Name'/><br/>
                <label>Capacity: </label>
                <input type='text' name='capacity' placeholder='Capacity'/><br/>
                <input type='submit' name='updateV' value='Edit Venue' /></form></div>";
        return $string;
    }

    function editVenue($id){          
            $val = new validation();
            $venuename = $val->sanitizeString($_POST['vname']);
            $capacity = $val->sanitizeString($_POST['capacity']);
            if($venuename == ''){
                echo "<script type='text/javascript'>alert('Venue Name is empty')</script>"; 
                return false;
            }
            if (!$val->alphabeticNumeric($venuename)){
                echo "<script type='text/javascript'>alert('Venue name should be Alpha Numeric')</script>"; 
                return false;
            }
            if($capacity == ''){
                echo "<script type='text/javascript'>alert('Capacity is empty')</script>"; 
                return false;
            }

            $db = new DB();
            if($db->editVenue($id,$venuename,$capacity)){
                
                header("Location: admin.php");

            } else {
                echo "<script type='text/javascript'>alert('Failed to edit Venue. Try again')</script>"; 
            }
            return true;
        }

        function addMAForm(){
            $db = new DB();
            $aid = $db->getAA();
            $sid = $db->getManagerSession();
            $eid = $db->getManagerEvent();
            $string = "<div><form method='post'>
                     <span>select matching event id and session id to successfully add the attendee</span><br><br>
                    <label>Attendee Name: </label>
                    <select id='aId' name='aId'>";
                foreach($aid as $val){
                    $string .= "<option value='{$val['name']}'>{$val['name']}</option>";
                }
                $string .= "</select><br><label>Event ID: </label><select id='eId' name='eId'>";
                foreach($eid as $val1){
                    $string .= "<option value='{$val1['idevent']}'>{$val1['idevent']}</option>";
                }
                $string .= "</select><br><label>Session ID: </label><select id='sId' name='sId'>";
                foreach($sid as $val1){
                    $string .= "<option value='{$val1['idsession']}'>{$val1['idsession']}</option>";
                }
                    $string .= "</select><input type='submit' name='addAtt' value='Add Attendee' /></form></div>";
            return $string;
        }
}


?>