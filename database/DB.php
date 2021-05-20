<?php
class DB{
    private $dbh;

    function __construct(){
        try{
            $this->dbh = new PDO("mysql:host={$_SERVER['DB_SERVER']}; dbname={$_SERVER['DB']}",  $_SERVER['DB_USER'],  $_SERVER['DB_PASSWORD']);
        } catch (PDOException $e){ 
            echo $e->getMessage();
            die();
        }
    }  
    function login($uname,$password) {
        $hashPass = hash("sha256",$password);
        try{
            $stmt = $this->dbh->prepare("select * from attendee where name = :uname and password = :pass");
            $stmt->bindParam(":uname",$uname,PDO::PARAM_STR);
            $stmt->bindParam(":pass",$hashPass,PDO::PARAM_STR);
            $stmt->execute();
            $data = array();
            $data = $stmt->fetchAll();
            return $data;
        
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }
    function register($uname, $password) {
        $hashPass = hash("sha256",$password);
        try{
            $stmt = $this->dbh->prepare("insert into attendee (name, password, role) values (:uname, :password, 3)");
            $stmt->bindParam(":uname",$uname,PDO::PARAM_STR);
            $stmt->bindParam(":password",$hashPass,PDO::PARAM_STR);
            $stmt->execute();
            return $this->dbh->lastInsertId();
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }
    function getAttendee($name) {
        try{
            
            $stmt = $this->dbh->prepare("select * from attendee where name = :name");
            $stmt->bindParam(":name",$name,PDO::PARAM_STR);
            $stmt->execute();
            $data = array();
            $data = $stmt->fetchAll();
            return $data;
        
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

    function getAttendeeById($id) {
        try{
            
            $stmt = $this->dbh->prepare("select * from attendee where idattendee = :id");
            $stmt->bindParam(":id",$id,PDO::PARAM_STR);
            $stmt->execute();
            $data = array();
            $data = $stmt->fetchAll();
            return $data;
        
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

    function getEvents() {
        try{
            $stmt = $this->dbh->prepare("select e.idevent as idevent, e.name as ename, e.datestart as estart,e.dateend as eend , e.numberallowed as capacity, v.name as vname, s.numberallowed as snum, s.idsession as idsession, s.name as sessionname, s.startdate as sstartdate, s.enddate as senddate 
            from event e left join session as s on e.idevent = s.event left join venue as v on e.venue = v.idvenue order by e.idevent, s.idsession");
            $stmt->execute();
            $data = array();
            while ($row = $stmt->fetch()){
                $data[] = $row;
            }
            return $data;
        
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }
    function getAEvents() {
        try{
            $stmt = $this->dbh->prepare("select idevent as idevent, name as ename, datestart as estart,dateend as eend , numberallowed as capacity, venue from event");
            $stmt->execute();
            $data = array();
            while ($row = $stmt->fetch()){
                $data[] = $row;
            }
            return $data;
        
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

    function getEventsAsTable() {
        $data = $this->getEvents();
        if (count($data) > 0) {
            $bigString = "<div><table>\n
                            <tr><th>ID</th>
                            <th>Name</th>
                            <th>Venue</th>
                            <th>Capacity</th>
                            <th>Session ID</th>
                            <th>Session Name</th>
                            <th>Session Start Time</th>
                            <th>Session End Time</th></tr>\n";
            foreach ($data as $row) {
                $bigString .="<tr><td>{$row['idevent']}</td>
                                  <td>{$row['ename']}</td>
                                  <td>{$row['vname']}</td>
                                  <td>{$row['snum']}</td>
                                  <td>{$row['idsession']}</td>
                                  <td>{$row['sessionname']}</td>
                                  <td>{$row['sstartdate']}</td>
                                  <td>{$row['senddate']}</td></tr>\n";
            }
            $bigString .="</table></div>\n";
        } else {
            $bigString = "<h2>No event exists</h2>"; 
        }
        return $bigString;
    }

    function isManager($name) {
        try{
            
            $stmt = $this->dbh->prepare("select * from attendee where role = 2 and name=:name");
            $stmt->bindParam(":name",$name,PDO::PARAM_STR);
            $stmt->execute();
            $data = array();
            $data = $stmt->fetchAll();
            return $data;
        
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }
    
    function isAdmin($name) {
        try{
            
            $stmt = $this->dbh->prepare("select * from attendee where role = 1 and name=:name");
            $stmt->bindParam(":name",$name,PDO::PARAM_STR);
            $stmt->execute();
            $data = array();
            $data = $stmt->fetchAll();
            return $data;
        
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

    function isSAdmin($name) {
        try{
            
            $stmt = $this->dbh->prepare("select * from attendee where role = 0 and name=:name");
            $stmt->bindParam(":name",$name,PDO::PARAM_STR);
            $stmt->execute();
            $data = array();
            $data = $stmt->fetchAll();
            return $data;
        
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

  

    function registeredEvents($name){
        try{
            
            $stmt = $this->dbh->prepare("select e.idevent as idevent, e.name as ename, e.datestart as estart, v.name as vname, s.numberallowed as snum, s.idsession as idsession, s.name as sessionname, s.startdate as sstartdate, s.enddate as senddate 
            from session as s left join event as e on s.event = e.idevent left join attendee_session as ats on  s.idsession = ats.session left join venue v 
            on e.venue = v.idvenue where ats.attendee in (select idattendee FROM attendee a WHERE a.name = :name ) order by e.idevent, s.idsession");
            $stmt->bindParam(":name",$name,PDO::PARAM_STR);
            $stmt->execute();
            $data = array();
            while ($row = $stmt->fetch()){
                $data[] = $row;
            }
            return $data;
        
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

    function registeredEventsAsTable($name) {
        $data = $this->registeredEvents($name);
        if (count($data) > 0) {
            $bigString = "<div><table>\n
                            <tr>
                            <th>Name</th>
                            <th>Start Date&Time</th>
                            <th>Venue</th>
                            <th>Capacity</th>
                            <th>Session Name</th>
                            <th>Session Start Time</th>
                            <th>Session End Time</th>
                            <th>Delete</th></tr>\n";
            foreach ($data as $row) {
                $bigString .="<tr>
                                  <td>{$row['ename']}</td>
                                  <td>{$row['estart']}</td>
                                  <td>{$row['vname']}</td>
                                  <td>{$row['snum']}</td>
                                  <td>{$row['sessionname']}</td>
                                  <td>{$row['sstartdate']}</td>
                                  <td>{$row['senddate']}</td>
                                  <td><a href='manage.php?deletes={$row['idsession']}&deletee={$row['idevent']}'><img src='assets/minus1.jpg' style='width:35px;height:30px;'></a></td></tr>\n";
            }
            $bigString .="</table></div>\n";
        } else {
            $bigString = "<h2>No Registered events</h2>"; 
        }
        return $bigString;
    }

    function registeredSessionsAsTable($name) {
        $data = $this->registeredEvents($name);
        if (count($data) > 0) {
            $bigString = "<div><table>\n
                            <th>Event Name</th>
                            <th>Session Name</th>
                            <th>Venue</th>
                            <th>Session Start Time</th>
                            <th>Session End Time</th></tr>\n";
            foreach ($data as $row) {
                $bigString .="<tr>
                                  <td>{$row['ename']}</td>
                                  <td>{$row['sessionname']}</td>
                                  <td>{$row['vname']}</td>
                                  <td>{$row['sstartdate']}</td>
                                  <td>{$row['senddate']}</td></tr>\n";
            }
            $bigString .="</table></div>\n";
        } else {
            $bigString = "<h2>No event exists</h2>"; 
        }
        return $bigString;
    }

    function addEventsAsTable() {
        $data = $this->getEvents();
        if (count($data) > 0) {
            $bigString = "<div><table>\n
                            <tr>
                            <th>Name</th>
                            <th>Start DateTime</th>
                            <th>Venue</th>
                            <th>Capacity</th>
                            <th>Session Name</th>
                            <th>Session Start Time</th>
                            <th>Session End Time</th>
                            <th>Add</th></tr>\n";
            foreach ($data as $row) {
                $bigString .="<tr>
                                
                                  <td>{$row['ename']}</td>
                                  <td>{$row['estart']}</td>
                                  <td>{$row['vname']}</td>
                                  <td>{$row['snum']}</td>
                                  <td>{$row['sessionname']}</td>
                                  <td>{$row['sstartdate']}</td>
                                  <td>{$row['senddate']}</td>
                                  <td><a href='manage.php?adds={$row['idsession']}&adde={$row['idevent']}'><img src='assets/plus.png' style='width:30px;height:30px;'></a></td></tr>\n";
            }
            $bigString .="</table></div>\n";
        } else {
            $bigString = "<h2>No event exists</h2>"; 
        }
        return $bigString;
    }

    function deleteSession($name,$idsession){
        try{
            $stmt = $this->dbh->prepare("delete from attendee_session where session = :idsession and attendee in (select idattendee from attendee where name = :name);");
            $stmt->bindParam(":name",$name,PDO::PARAM_STR);
            $stmt->bindParam(":idsession",$idsession,PDO::PARAM_STR);
            $stmt->execute();
            $deleted = $stmt->rowCount();
            return $deleted;
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }
    function deleteEvent($name,$idevent){
        try{
            $stmt = $this->dbh->prepare("delete from attendee_event where event = :idevent and attendee in (select idattendee from attendee where name = :name);");
            $stmt->bindParam(":name",$name,PDO::PARAM_STR);
            $stmt->bindParam(":idevent",$idevent,PDO::PARAM_STR);
            $stmt->execute();
            $deleted = $stmt->rowCount();
            return $deleted;
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

    function isRegister($name,$idsession){
        try{
            $data = array();
            $stmt = $this->dbh->prepare("select * from attendee_session as ats join attendee as a on ats.attendee = a.idattendee where ats.session = :idsession and a.name = :name");
            $stmt->bindParam(":name",$name,PDO::PARAM_STR);
            $stmt->bindParam(":idsession",$idsession,PDO::PARAM_INT);
            $stmt->execute();
            $data = $stmt->fetchAll();
            return $data;
        
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }
    function isRegisterEvent($name,$idevent){
        try{
            $data = array();
            $stmt = $this->dbh->prepare("select * from attendee_event as ae join attendee as a on ae.attendee = a.idattendee where ae.event = :idevent and a.name = :name");
            $stmt->bindParam(":idevent",$idevent,PDO::PARAM_INT);
            $stmt->bindParam(":name",$name,PDO::PARAM_STR);
            $stmt->execute();
            $data = $stmt->fetchAll();
            return $data;
        
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }
    
    function registerSession($name,$idsession){
        try{
            $stmt = $this->dbh->prepare("insert into attendee_session (session,attendee) SELECT :idsession,idattendee FROM attendee a WHERE a.name = :name;");
            $stmt->bindParam(":name",$name,PDO::PARAM_STR);
            $stmt->bindParam(":idsession",$idsession,PDO::PARAM_INT);
            $stmt->execute();
            return $this->dbh->lastInsertId();
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }
    function registerEvent($name,$idevent){
        try{
            $stmt = $this->dbh->prepare("insert into attendee_event (event,attendee) SELECT :idevent,idattendee FROM attendee a WHERE a.name = :name;");
            $stmt->bindParam(":name",$name,PDO::PARAM_STR);
            $stmt->bindParam(":idevent",$idevent,PDO::PARAM_STR);
            $stmt->execute();
            return $this->dbh->lastInsertId();
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

    function getUsers(){
        try{
            $stmt = $this->dbh->prepare("select a.idattendee, a.name, a.role, r.name as rolename, r.idrole as idrole from attendee as a inner join role as r on r.idrole = a.role order by a.idattendee");
            $stmt->execute();
            $data = array();
            while ($row = $stmt->fetch()){
                $data[] = $row;
            }
            return $data;
        
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }


    function getUsersAsTable() {
        $data = $this->getUsers();
        if (count($data) > 0) {
            $bigString = "<div><table>\n";
            $bigString .= "<tr><th>User ID</th>
                            <th>User Name</th>
                            <th>User Role</th>
                            <th>Role Id</th>
                            <th>Delete</th>
                            <th>Edit</th></tr>\n";
            foreach ($data as $row) {
                $bigString .="<tr>
                                  <td>{$row['idattendee']}</td>
                                  <td>{$row['name']}</td>
                                  <td>{$row['rolename']}</td>
                                  <td>{$row['idrole']}</td>
                                  <td><a href='admin.php?deleteUser={$row['idattendee']}'><img src='assets/minus1.jpg' style='width:30px;height:30px;'></a></td>
                                  <td><a href='admin.php?editUser={$row['idattendee']}'><img src='assets/edit.ico' style='width:20px;height:20px;'></a></td></tr>\n";
            }
            $bigString .="</table></div>\n";
        } else {
            $bigString = "<h2>No user exists</h2>"; 
        }
        return $bigString;
    }

    function addUser($name, $pass,$role) {
        $hashPass = hash("sha256",$password);
        try{
            $stmt = $this->dbh->prepare("insert into attendee (name, password, role) values (:name, :password, :role);");
            $stmt->bindParam(":name",$name,PDO::PARAM_STR);
            $stmt->bindParam(":password",$hashPass,PDO::PARAM_STR);
            $stmt->bindParam(":role",$role,PDO::PARAM_INT);
            $stmt->execute();
            return $this->dbh->lastInsertId();
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

    function deleteUser($id){
        try{
            $stmt = $this->dbh->prepare("delete from attendee where idattendee = :id;"); 
            $stmt->bindParam(":id",$id,PDO::PARAM_INT);
            $stmt->execute();
            $deleted = $stmt->rowCount();
            return $deleted;
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

    function editUser($id,$name,$role){
        try{
            $stmt = $this->dbh->prepare("update attendee set name = :name, role = :role where idattendee = :id;");
            $stmt->bindParam(":name",$name,PDO::PARAM_STR);
            $stmt->bindParam(":id",$id,PDO::PARAM_INT);
            $stmt->bindParam(":role",$role,PDO::PARAM_INT);
            $stmt->execute();
            $updated = $stmt->rowCount();
            return $updated;
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

    function addEventsAsTableAlt() {
        $data = $this->getAEvents();
        if (count($data) > 0) {
            $bigString = "<div><table>\n
                            <tr>
                            <th>Event ID</th>
                            <th>Name</th>
                            <th>Capacity</th>
                            <th>Start DateTime</th>
                            <th>End DateTime</th>
                            <th>Venue</th>
                            <th>Delete</th>
                            <th>Edit</th></tr>\n";
            foreach ($data as $row) {
                $bigString .="<tr>
                                <td>{$row['idevent']}</td>
                                  <td>{$row['ename']}</td>
                                  <td>{$row['capacity']}</td>
                                  <td>{$row['estart']}</td>
                                  <td>{$row['eend']}</td>
                                  <td>{$row['venue']}</td>
                                  <td><a href='admin.php?deleteEvent={$row['idevent']}'><img src='assets/minus1.jpg' style='width:30px;height:30px;'></a></td>
                                  <td><a href='admin.php?eEvent={$row['idevent']}'><img src='assets/edit.ico' style='width:20px;height:20px;'></a></td></tr>\n";
            }
            $bigString .="</table></div>\n";
        } else {
            $bigString = "<h2>No event exists</h2>"; 
        }
        return $bigString;
    }

    function delEvent($id){
        try{
            $stmt = $this->dbh->prepare("delete from event where idevent = :id;");
            $stmt->bindParam(":id",$id,PDO::PARAM_INT);
            $stmt->execute();
            $deleted = $stmt->rowCount();
            return $deleted;
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

    function delEventSession($id){
        try{
            $stmt = $this->dbh->prepare("delete from session where event = :id;");
            $stmt->bindParam(":id",$id,PDO::PARAM_INT);
            $stmt->execute();
            $deleted = $stmt->rowCount();
            return $deleted;
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

    function delEventAttendee($id){
        try{
            $stmt = $this->dbh->prepare("delete from attendee_event where event = :id;");
            $stmt->bindParam(":id",$id,PDO::PARAM_INT);
            $stmt->execute();
            $deleted = $stmt->rowCount();
            return $deleted;
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

    function delEventManager($id){
        try{
            $stmt = $this->dbh->prepare("delete from manager_event where event = :id;");
            $stmt->bindParam(":id",$id,PDO::PARAM_INT);
            $stmt->execute();
            $deleted = $stmt->rowCount();
            return $deleted;
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

    function updateEventManager($id){
        try{
            $stmt = $this->dbh->prepare("update attendee set role='3' where idattendee in (select manager from manager_event where event = :id);");
            $stmt->bindParam(":id",$id,PDO::PARAM_INT);
            $stmt->execute();
            $updated = $stmt->rowCount();
            return $updated;
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

    function editEvent($id,$name,$sdate,$edate,$num,$venue){
        try{
            $stmt = $this->dbh->prepare("update event set name = :name, datestart = :sdate, dateend = :edate, numberallowed = :num, venue = :venue where idevent = :id;");
            $stmt->bindParam(":id",$id,PDO::PARAM_INT);
            $stmt->bindParam(":name",$name,PDO::PARAM_STR);
            $stmt->bindParam(":sdate",$sdate,PDO::PARAM_STR);
            $stmt->bindParam(":edate",$edate,PDO::PARAM_STR);
            $stmt->bindParam(":num",$num,PDO::PARAM_INT);
            $stmt->bindParam(":venue",$venue,PDO::PARAM_INT);
            $stmt->execute();
            $updated = $stmt->rowCount();
            return $updated;
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

    function addEvent($name, $sdate,$edate,$num,$venue) {
        try{
            $stmt = $this->dbh->prepare("insert into event (name, datestart, dateend, numberallowed, venue) values (:name, :sdate, :edate, :num, :venue);");
            $stmt->bindParam(":name",$name,PDO::PARAM_STR);
            $stmt->bindParam(":sdate",$sdate,PDO::PARAM_STR);
            $stmt->bindParam(":edate",$edate,PDO::PARAM_STR);
            $stmt->bindParam(":num",$num,PDO::PARAM_INT);
            $stmt->bindParam(":venue",$venue,PDO::PARAM_INT);
            $stmt->execute();
            return $this->dbh->lastInsertId();
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

    function addEManager($name,$ename) {
        try{
            $stmt = $this->dbh->prepare("insert into manager_event (event,manager) select e.idevent, a.idattendee from event as e join attendee as a where a.name = :name and e.name = :ename;");
            $stmt->bindParam(":name",$name,PDO::PARAM_STR);
            $stmt->bindParam(":ename",$ename,PDO::PARAM_STR);
            $stmt->execute();
            return $this->dbh->lastInsertId();
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }
    
    function getSessions() {
        try{
            $stmt = $this->dbh->prepare("select * from session");
            $stmt->execute();
            $data = array();
            $data = $stmt->fetchAll();
            return $data;
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

    function addSessionAsTableAlt() {
        $data = $this->getSessions();
        if (count($data) > 0) {
            $bigString = "<div><table>\n
                            <tr>
                            <th>Session ID</th>
                            <th>Session Name</th>
                            <th>Capacity</th>
                            <th>Start DateTime</th>
                            <th>End DateTime</th>
                            <th>Event Name</th>
                            <th>Delete</th>
                            <th>Edit</th></tr>\n";
            foreach ($data as $row) {
                $bigString .="<tr>
                                  <td>{$row['idsession']}</td>
                                  <td>{$row['name']}</td>
                                  <td>{$row['numberallowed']}</td>
                                  <td>{$row['startdate']}</td>
                                  <td>{$row['enddate']}</td>
                                  <td>{$row['event']}</td>
                                  <td><a href='admin.php?deleteSes={$row['idsession']}'><img src='assets/minus1.jpg' style='width:30px;height:30px;'></a></td>
                                  <td><a href='admin.php?editSes={$row['idsession']}'><img src='assets/edit.ico' style='width:20px;height:20px;'></a></td></tr>\n";
            }
            $bigString .="</table></div>\n";
        } else {
            $bigString = "<h2>No session exists</h2>"; 
        }
        return $bigString;
    }

    function addSession($name, $sdate,$edate,$capacity,$eventid) {
        try{
            $stmt = $this->dbh->prepare("insert into session (name, startdate, enddate, numberallowed, event) values (:name, :sdate, :edate, :capacity, :id) ;");
            $stmt->bindParam(":name",$name,PDO::PARAM_STR);
            $stmt->bindParam(":sdate",$sdate,PDO::PARAM_STR);
            $stmt->bindParam(":edate",$edate,PDO::PARAM_STR);
            $stmt->bindParam(":capacity",$capacity,PDO::PARAM_INT);
            $stmt->bindParam(":id",$eventid,PDO::PARAM_INT);
            $stmt->execute();
            return $this->dbh->lastInsertId();
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

    function delSession($id){
        try{
            $stmt = $this->dbh->prepare("delete from session where idsession = :id;");
            $stmt->bindParam(":id",$id,PDO::PARAM_INT);
            $stmt->execute();
            $deleted = $stmt->rowCount();
            return $deleted;
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }
    function delSesAttendee($id){
        try{
            $stmt = $this->dbh->prepare("delete from attendee_session where session = :id;");
            $stmt->bindParam(":id",$id,PDO::PARAM_INT);
            $stmt->execute();
            $deleted = $stmt->rowCount();
            return $deleted;
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

    function editSession($id,$name,$sdate,$edate,$capacity){
        try{
            $stmt = $this->dbh->prepare("update session set name = :name, startdate = :sdate, enddate = :edate, numberallowed = :capacity where idsession = :id;");
            $stmt->bindParam(":name",$name,PDO::PARAM_STR);
            $stmt->bindParam(":sdate",$sdate,PDO::PARAM_STR);
            $stmt->bindParam(":edate",$edate,PDO::PARAM_STR);
            $stmt->bindParam(":capacity",$capacity,PDO::PARAM_INT);
            $stmt->bindParam(":id",$id,PDO::PARAM_INT);
            $stmt->execute();
            $updated = $stmt->rowCount();
            return $updated;
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

    function getAllAttendee(){
        try{
           $stmt = $this->dbh->prepare("select a.idattendee as idatt, a.name as aname, ats.attendee, ats.session as atses, s.name as sname, s.event as sevent from attendee as a join attendee_session as ats on a.idattendee = ats.attendee join session as s on ats.session = s.idsession order by idatt ;");
           $stmt->execute();
           $data = array();
           $data = $stmt->fetchAll();
           return $data;
       } catch (PDOException $e) {
           echo $e->getMessage();
           die();
       }
   }

   function getAA(){
    try{
       $stmt = $this->dbh->prepare("select * from attendee where role != 0;");
       $stmt->execute();
       $data = array();
       $data = $stmt->fetchAll();
       return $data;
   } catch (PDOException $e) {
       echo $e->getMessage();
       die();
   }
 }

   function addAttendeeAsTable() {
        $data = $this->getAllAttendee();
        if (count($data) > 0) {
            $bigString = "<div><table>\n";
            $bigString .= "";
            $bigString .= "<tr><th>Attendee ID</th>
                            <th>Attendee Name</th>
                            <th>Session Name</th>
                            <th>Session ID</th>
                            <th>Event ID</th>
                            <th>Delete</th></tr>\n";
            foreach ($data as $row) {
                $bigString .="<tr>
                              <td>{$row['idatt']}</td>
                              <td>{$row['aname']}</td>
                              <td>{$row['sname']}</td>
                              <td>{$row['atses']}</td>
                              <td>{$row['sevent']}</td>
                              <td><a href='admin.php?eventID={$row['sevent']}&&attID={$row['idatt']}&&sesID={$row['atses']}'><img src='assets/minus1.jpg' style='width:30px;height:30px;'></a></td>
                             </tr>\n";
            }
            $bigString .="</table></div>\n";
        } else {
            $bigString = "<h2>No Attendee exists</h2>"; 
        }
        return $bigString;
    }


    function deleteAttendeeS($id,$sesid){
        try{
            $stmt = $this->dbh->prepare("delete from attendee_session where attendee = :id and session=:sid;");
            $stmt->bindParam(":id",$id,PDO::PARAM_INT);
            $stmt->bindParam(":sid",$sesid,PDO::PARAM_INT);
            $stmt->execute();
            $deleted = $stmt->rowCount();
            return $deleted;
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

    function deleteAttendeeE($id,$eid){
        try{
            $stmt = $this->dbh->prepare("delete from attendee_event where attendee = :id and event = :eid;");
            $stmt->bindParam(":id",$id,PDO::PARAM_INT);
            $stmt->bindParam(":eid",$eid,PDO::PARAM_INT);
            $stmt->execute();
            $deleted = $stmt->rowCount();
            return $deleted;
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

    function getVenue(){
        try{
           $stmt = $this->dbh->prepare("select * from venue;");
           $stmt->execute();
           $data = array();
           $data = $stmt->fetchAll();
           return $data;
       } catch (PDOException $e) {
           echo $e->getMessage();
           die();
       }
   }

    function addVenueAsTable() {
        $data = $this->getVenue();
        if (count($data) > 0) {
            $bigString = "<div><table>\n";
            $bigString .= "";
            $bigString .= "<tr><th>Venue ID</th>
                            <th>Venue Name</th>
                            <th>Capacity</th>
                            <th>Delete</th>
                            <th>Edit</th></tr>\n";
            foreach ($data as $row) {
                $bigString .="<tr>
                              <td>{$row['idvenue']}</td>
                              <td>{$row['name']}</td>
                              <td>{$row['capacity']}</td>
                              <td><a href='admin.php?deleteV={$row['idvenue']}'><img src='assets/minus1.jpg' style='width:30px;height:30px;'></a></td>
                                  <td><a href='admin.php?editV={$row['idvenue']}'><img src='assets/edit.ico' style='width:20px;height:20px;'></a></td></tr>\n";
            }
            $bigString .="</table></div>\n";
        } else {
            $bigString = "<h2>No venue exists</h2>"; 
        }
        return $bigString;
    }

    function addVenue($name, $capacity) {
        try{
            $stmt = $this->dbh->prepare("insert into venue (name, capacity) values (:name, :capacity);");
            $stmt->bindParam(":name",$name,PDO::PARAM_STR);
            $stmt->bindParam(":capacity",$capacity,PDO::PARAM_INT);
            $stmt->execute();
            return $this->dbh->lastInsertId();
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

    function deleteVenue($id){
        try{
            $stmt = $this->dbh->prepare("delete from venue where idvenue = :idvenue;");
            $stmt->bindParam(":idvenue", $id, PDO::PARAM_INT);
            $stmt->execute();
            $deleted = $stmt->rowCount();
            return $deleted;
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

    function editVenue($id,$name,$capacity){
        try{
            $data = array();
            $stmt = $this->dbh->prepare("update venue set name = :name, capacity = :capacity where idvenue = :id;");
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->bindParam(":name", $name, PDO::PARAM_STR);
            $stmt->bindParam(":capacity", $capacity, PDO::PARAM_INT);
            $stmt->execute();
            $updated = $stmt->rowCount();
            return $updated;
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

    function getManagerEvent(){
        try{
           $stmt = $this->dbh->prepare("select e.* from event e join manager_event on e.idevent = manager_event.event join attendee on manager_event.manager = attendee.idattendee where attendee.name=:name");
           $stmt->bindParam(":name",$_SESSION['username'],PDO::PARAM_STR);
           $stmt->execute();
           $data = array();
           $data = $stmt->fetchAll();
           return $data;
       } catch (PDOException $e) {
           echo $e->getMessage();
           die();
       }
   }

   function managerEventsAsTable() {
       $data = $this->getManagerEvent();
       if (count($data) > 0) {
           $bigString = "<div><table>\n";
           $bigString .= "";
           $bigString .= "<tr><th>ID</th>
                           <th>Name</th><th>Start Date&Time</th>
                           <th>End Date&Time</th>
                           <th>Number Allowed</th>
                           <th>Venue</th>
                           <th>Delete</th><th>Edit</th></tr>\n";
           foreach ($data as $row) {
               $bigString .="<tr>
                                   <td>{$row['idevent']}</td>
                                 <td>{$row['name']}</td>
                                 <td>{$row['datestart']}</td>
                                 <td>{$row['dateend']}</td>
                                 <td>{$row['numberallowed']}</td>
                                 <td>{$row['venue']}</td>
                                 <td><a href='admin.php?delEvent={$row['idevent']}'><img src='assets/minus1.jpg' style='width:30px;height:30px;'></a></td>
                                  <td><a href='admin.php?eEvent={$row['idevent']}'><img src='assets/edit.ico' style='width:20px;height:20px;'></a></td></tr>\n";
           }
           $bigString .="</table></div>\n";
       } else {
           $bigString = "<h2>No event exists</h2>"; 
       }
       return $bigString;
   }

   function addEventManager($name,$ename) {
    try{  
        $stmt = $this->dbh->prepare("insert into manager_event (event,manager) select e.idevent, a.idattendee from event as e join attendee as a where a.name = :name and e.name = :ename;");
        $stmt->bindParam(":name",$name,PDO::PARAM_STR);
        $stmt->bindParam(":ename",$ename,PDO::PARAM_STR);
        $stmt->execute();
        return $this->dbh->lastInsertId();
    } catch (PDOException $e) {
        echo $e->getMessage();
        die();
    }

  }

  function getManagerSession(){
    try{
       $stmt = $this->dbh->prepare("select s.* from session as s join event on s.event = event.idevent where idevent in (select event from manager_event join attendee on manager_event.manager = attendee.idattendee where attendee.name = :name)");
       $stmt->bindParam(":name",$_SESSION['username'],PDO::PARAM_STR);
       $stmt->execute();
       $data = array();
       $data = $stmt->fetchAll();
       return $data;
   } catch (PDOException $e) {
       echo $e->getMessage();
       die();
   }
}

function managerSessionAsTable() {
   $data = $this->getManagerSession();
   if (count($data) > 0) {
       $bigString = "<div><table>\n";
       $bigString .= "";
       $bigString .= "<tr><th>Event ID</th>
                        <th>Session ID</th>
                        <th>Name</th>
                        <th>Number Allowed</th>
                        <th>Start Date&Time</th>
                        <th>End Date&Time</th>
                        <th>Delete</th>
                        <th>Edit</th></tr>\n";
       foreach ($data as $row) {
           $bigString .="<tr><td>{$row['event']}</td>
                       <td>{$row['idsession']}</td>
                       <td>{$row['name']}</td>
                       <td>{$row['numberallowed']}</td>
                       <td>{$row['startdate']}</td>
                       <td>{$row['enddate']}</td>
                       <td><a href='admin.php?delSes={$row['idsession']}'><img src='assets/minus1.jpg' style='width:30px;height:30px;'></a></td>
                       <td><a href='admin.php?eSes={$row['idsession']}'><img src='assets/edit.ico' style='width:20px;height:20px;'></a></td></tr>\n";

       }
       $bigString .="</table></div>\n";
   } else {
       $bigString = "No sessions exits"; 
   }
   return $bigString;
 }

 function getManagerAttendee(){
    try{
       $stmt = $this->dbh->prepare("select a.idattendee as idatt, a.name aname, a.role, ats.attendee, ats.session as atses, s.name as sname, s.event as sevent from attendee as a join attendee_session as ats on a.idattendee = ats.attendee join session as s on ats.session = s.idsession  where s.event in (select event from manager_event as me join attendee as a on me.manager = a.idattendee where a.name = :name);");
       $stmt->bindParam(":name",$_SESSION['username'],PDO::PARAM_STR);
       $stmt->execute();
       $data = array();
       $data = $stmt->fetchAll();
       return $data;
   
   } catch (PDOException $e) {
       echo $e->getMessage();
       die();
   }
}

function managerAttendeeAsTable() {
   $data = $this->getManagerAttendee();
   if (count($data) > 0) {
       $bigString = "<div><table>\n";
       $bigString .= "";
       $bigString .= "<tr><th>Attendee ID</th>
                        <th>Attendee Name</th>
                        <th>Session Name</th>
                        <th>Session ID</th>
                        <th>Event ID</th>
                        <th>Delete</th></tr>\n";
       foreach ($data as $row) {
           $bigString .="<tr>
           <td>{$row['idatt']}</td>
           <td>{$row['aname']}</td>
           <td>{$row['sname']}</td>
           <td>{$row['atses']}</td>
           <td>{$row['sevent']}</td>
           <td><a href='admin.php?eventID={$row['sevent']}&&attID={$row['idatt']}&&sesID={$row['atses']}'><img src='assets/minus1.jpg' style='width:30px;height:30px;'></a></td></tr>\n";
       }
       $bigString .="</table></div>\n";
   } else {
       $bigString = "No Attendee exists"; 
   }
   return $bigString;
  }

}
?>