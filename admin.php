<?php

require_once("functions/myUtils.php");
require_once("database/DB.php");

$db = new DB();
$myUtils = new myUtils();

if(!$myUtils->isLoggedIn()){
    header("Location: index.php");
} 
else {
    if($myUtils->isSAdmin($_SESSION['username'])){
        $nav = $myUtils->navBarSuperAdmin('Admin');
        echo $nav;
    } else {
        $nav = $myUtils->navBarAdmin('Admin');
    echo $nav;
    }

    if($myUtils->isAdmin($_SESSION['username']) || $myUtils->isSAdmin($_SESSION['username']) ){
        echo "<h3 id='Header'><b>Admin Dashboard</b></h3>";
        

        $userTable ="<h4><b>Manage Users </b><a href='admin.php?add=true'><button><img src='assets/plus.png' style='width:20px;height:20px;'></button></a></h4>";
        $userTable .="";
        $userTable .= $db->getUsersAsTable();
        
        if(isset($_GET['add'])){
             $userTable .=  $myUtils->addUform();
             
        }
        if(isset($_POST['addUser'])){
                $myUtils->addUser();
        }
        if(isset($_GET['deleteUser'])){
            $myUtils->deleteUser($_GET['deleteUser']);
        }
        if(isset($_GET['editUser'])){
            $userTable .= $myUtils->editUForm();
        }
        if(isset($_POST['updateUser'])){
            $myUtils->editUser($_GET['editUser']);  
        } 

        $vTable ="<h4><b>Manage Venue </b><a href='admin.php?venue=true'><button><img src='assets/plus.png' style='width:20px;height:20px;'></button></a></h4>";
        $vTable .="";
        $vTable .= $db->addVenueAsTable();
        if(isset($_GET['venue'])){
            $vTable .=  $myUtils->addVForm();
            
       }
       if(isset($_POST['addVenue'])){
               $myUtils->addVenue();
       }
       if(isset($_GET['deleteV'])){
           $myUtils->deleteVenue($_GET['deleteV']);
       }
       if(isset($_GET['editV'])){
           $vTable .= $myUtils->editVForm();
       }
       if(isset($_POST['updateV'])){
           $myUtils->editVenue($_GET['editV']);  
       } 
        


        $eventTable ="<h4><b>Manage Events </b><a href='admin.php?event=true'><button><img src='assets/plus.png' style='width:20px;height:20px;'></button></a></h4>";
        $eventTable .="";
        $eventTable .= $db->addEventsAsTableAlt();
        if(isset($_GET['event'])){
             $eventTable .=  $myUtils->addEform();
             
        }
        if(isset($_POST['addEvent'])){
                $myUtils->addEvent();
        }
        if(isset($_GET['deleteEvent'])){
            $myUtils->deleteEvent($_GET['deleteEvent']);
        }
        if(isset($_GET['eEvent'])){
            $eventTable .= $myUtils->editEForm();
        }
        if(isset($_POST['updateEvent'])){
            $myUtils->editEvent($_GET['eEvent']);  
        } 
       
        

        $sessionTable ="<h4><b>Manage Event Sessions </b><a href='admin.php?ses=true'><button><img src='assets/plus.png' style='width:20px;height:20px;'></button></a></h4>";
        $sessionTable .="";
        $sessionTable .= $db->addSessionAsTableAlt();
        if(isset($_GET['ses'])){
             $sessionTable .=  $myUtils->addSform();
             
        }
        if(isset($_POST['addSes'])){
                $myUtils->addSession();
        }
        if(isset($_GET['deleteSes'])){
            $myUtils->delSession($_GET['deleteSes']);
        }
        if(isset($_GET['editSes'])){
            $sessionTable .= $myUtils->editSForm();
        }
        if(isset($_POST['updateSes'])){
            $myUtils->editSes($_GET['editSes']);  
        } 

       
        $attTable ="<h4><b>Manage Event Attendee </b><a href='admin.php?att=true'><button><img src='assets/plus.png' style='width:20px;height:20px;'></button></a></h4>";
        $attTable .="";
        $attTable .= $db->addAttendeeAsTable();
        if(isset($_GET['att'])){
             $attTable .=  $myUtils->addAform();
             
        }
        if(isset($_POST['addAtt'])){
                $myUtils->registerSes($_POST['aId'],$_POST['eId'],$_POST['sId']);
                
        }
        if(isset($_GET['eventID']) && isset($_GET['sesID']) && isset($_GET['attID'])){
            $myUtils->delAttendee($_GET['attID'],$_GET['eventID'],$_GET['sesID']);
        } 

        
        echo $userTable;
        echo $vTable;
        echo $eventTable;
        echo $sessionTable;
        echo $attTable;
    }
   
    else if($myUtils->isManager($_SESSION['username'])){

        $meTable ="<h4><b>Manage Events </b><a href='admin.php?mEvent=true'><button><img src='assets/plus.png' style='width:20px;height:20px;'></button></a></h4>";
        $meTable .="";
        $meTable .= $db->managerEventsAsTable();
        if(isset($_GET['mEvent'])){
             $meTable .=  $myUtils->addEform();
             
        }
        if(isset($_POST['addEvent'])){
                $myUtils->addEvent();
        }
        if(isset($_GET['delEvent'])){
            $myUtils->deleteEvent($_GET['delEvent']);
        }
        if(isset($_GET['eEvent'])){
            $meTable .= $myUtils->editEForm();
        }
        if(isset($_POST['updateEvent'])){
            $myUtils->editEvent($_GET['eEvent']);  
        } 

        $msTable ="<h4><b>Manage Sessions </b><a href='admin.php?mSes=true'><button><img src='assets/plus.png' style='width:20px;height:20px;'></button></a></h4>";
        $msTable .="";
        $msTable .= $db->managerSessionAsTable();
        if(isset($_GET['mSes'])){
             $msTable .=  $myUtils->addSform();
             
        }
        if(isset($_POST['addSes'])){
                $myUtils->addSession();
        }
        if(isset($_GET['delSes'])){
            $myUtils->delSession($_GET['delSes']);
        }
        if(isset($_GET['eSes'])){
            $msTable .= $myUtils->editSForm();
        }
        if(isset($_POST['updateSes'])){
            $myUtils->editSes($_GET['eSes']);  
        } 

        $mattTable ="<h4><b>Manage Event Attendee </b><a href='admin.php?mAtt=true'><button><img src='assets/plus.png' style='width:20px;height:20px;'></button></a></h4>";
        $mattTable .="";
        $mattTable .= $db->managerAttendeeAsTable();
        if(isset($_GET['mAtt'])){
             $mattTable .=  $myUtils->addMAform();
             
        }
        if(isset($_POST['addAtt'])){
                $myUtils->registerSes($_POST['aId'],$_POST['eId'],$_POST['sId']);
                
        }
        if(isset($_GET['eventID']) && isset($_GET['sesID']) && isset($_GET['attID'])){
            $myUtils->delAttendee($_GET['attID'],$_GET['eventID'],$_GET['sesID']);
        } 

        echo $meTable;
        echo $msTable;
        echo $mattTable;


    }

}

$footer = $myUtils->footer();
echo $footer;
?>