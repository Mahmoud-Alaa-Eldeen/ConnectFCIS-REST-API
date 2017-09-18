    <?php
	
      
	 
    class DbHandler {

        private $conn;

        function __construct() {
            require_once dirname(__FILE__) . '/db_connect.php';
            // opening db connection
            $db = new DbConnect();
            $this->conn = $db->connect();
        }
			
		
		
		
		// add comment  
		
		public function addComment($commContent, $createdTime, $EventName, $createdUser) {

             // hold message (error or not) 
     		$response = array();

		
		   
			
                // insert query
                $stmt = $this->conn->prepare("INSERT INTO comments(commContent, createdTime, EventName, createdUser) values(?, ?, ?, ?)");
                $stmt->bind_param("ssss", $commContent, $createdTime, $EventName, $createdUser);

                $result = $stmt->execute();
			
			

                $stmt->close();

                // Check for successful insertion
           
       		   if ($result) {
                    // User successfully inserted
                    return 1;
                } else {
                    // Failed to create user
                    return -1;
                }
             

           return $response;
        }
		
		
		
		
		//delete comment
		
		
		
		
		public function deleteComment($commID) {


     		$response = array();

		
		   
			
                // insert query
                $stmt = $this->conn->prepare("DELETE FROM comments WHERE commID=?");
                $stmt->bind_param("i", $commID);

                $result = $stmt->execute();
			
			

                $stmt->close();

                // Check for successful deletion
           
       		   if ($result) {
                    // comment successfully deleted
                    return 1;
                } else {
                    // Failed to delete comment
                    return -1;
                }
             

            return $response;
        }
		
		
		
		
		//getid of user 
		
		
		public function idOfMail($email) {


     		$response = array();

		
		   
			
                // insert query
                $stmt = $this->conn->prepare("SELECT userID FROM user WHERE email=?");
                $stmt->bind_param("s", $email);

                $result = $stmt->execute();
			
			 $id = $stmt->get_result();
        $stmt->close();
        return $id;

		}
		
		
		
		
		
		//get event comments   getEventComments($EventName)
		
		
		
public function getEventComments($EventName) {

        $stmt = $this->conn->prepare("select * from comments where EventName=?");
        $stmt->bind_param("s", $EventName);

        $stmt->execute();  
        $eventComments = $stmt->get_result();
        $stmt->close();
        return $eventComments;

}
		
		
		
        // creating new user if not existed
        public function register($fName, $lName, $email, $userName, $password, $gender, $dateOfBirth, $type) {
            $response = array();

            // First check if user already existed in db
            if (!$this->isUserExists($email)) {

                // insert query
                $stmt = $this->conn->prepare("INSERT INTO user(firstName, lastName, email, userName, password, gender, dateOfBirth, type) values(?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssssss", $fName, $lName, $email, $userName, $password, $gender, $dateOfBirth, $type);

                $result = $stmt->execute();
			

                $stmt->close();

                // Check for successful insertion
                if ($result) {
                    // User successfully inserted
                    return 1;
                } else {
                    // Failed to create user
                    return -1;
                }
            } else {
                // User with same email already existed in the db
                return 0;
            }

            return $response;
        }

		


//  deleteUser($userID, $type)


	   public function deleteUser($userID, $type){
      
	 if($type=="Student"){
	
	$sql ="DELETE FROM student WHERE stdID='$userID';";

       //$sql .= "UPDATE  user SET firstName='g', lastName='g', 	 userName='g', password='g', gender='g', dateOfBirth='g' ,	type='g' ,  WHERE userID='$userID' ;";
	$sql .="DELETE FROM user WHERE userID='$userID';";

		 


           // return $response;
		 
		 	$stmt = $this->conn->multi_query($sql);

	 }
	 else if  ($type=="Staff"){
		 
		 
		 $sql ="DELETE FROM staff WHERE staffID='$userID';";

		 
		 	
$sql .= "DELETE FROM user WHERE userID='$userID';";

	
	
$stmt = $this->conn->multi_query($sql);

	

           // return $response;
	 
	 }


        }
		



// getUsers();

public function getUsers(){

        $stmt = $this->conn->prepare("select userID, firstName, type from user");

        $stmt->execute();  
        $users = $stmt->get_result();
        $stmt->close();
        return $users;

}



public function getEvents(){

        $stmt = $this->conn->prepare("select * from event");

$stmt->execute();  
        $users = $stmt->get_result();
        $stmt->close();
        return $users;

}
//////l


        // user login
        public function login($email,$password){
            $stmt = $this->conn->prepare("SELECT * FROM user WHERE email=? and password =?");
            $stmt->bind_param("ss",$email,$password);
            $stmt->execute();
            $stmt->store_result();
            $num_rows = $stmt->num_rows;
            $stmt->close();
            return $num_rows>0;
        }

		
                // fetching single user by email
        public function getUser($email) {
            $stmt = $this->conn->prepare("SELECT * FROM user WHERE email = ?");
            $stmt->bind_param("s", $email);
            if ($stmt->execute()) { 

                $user = $stmt->get_result()->fetch_assoc();
                $stmt->close();
                return $user;
            } else {
                return NULL;
            }
        }

		 

		
      // fetching single user by id
        public function getUserById($user_id) {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE user_id = ?");
            $stmt->bind_param("s", $user_id);
            if ($stmt->execute()) { 

                $user = $stmt->get_result()->fetch_assoc();
                $stmt->close();
                return $user;
            } else {
                return NULL;
            }
       } 
	     
	  
	  
  public function getProfile($email, $type)
{
      
	 
	 if($type=="Student"){
	
	$sql ="SELECT FROM student WHERE stdID='$userID';";

       //$sql .= "UPDATE  user SET firstName='g', lastName='g', 	 userName='g', password='g', gender='g', dateOfBirth='g' ,	type='g' ,  WHERE userID='$userID' ;";
	$sql .="SELECT FROM user WHERE userID='$userID';";

		 


           // return $response;
		 
		 	$stmt = $this->conn->multi_query($sql);

	 }

	 
        }
	



	 public function editStdProfile($userID, $userName, $password,  $stdDept, $stdAcadYear, $studImage, $availableStatus, $info){
      
	 

$sql = "UPDATE user SET userName='$userName', password='$password' WHERE userID='$userID';";

$sql .="UPDATE  student SET stdDept='$stdDept', stdAcadYear='$stdAcadYear', studImage='$studImage', availableStatus='$availableStatus', info='$info'  WHERE stdID='$userID';";

$sql .="INSERT into  student SET stdDept='$stdDept', stdAcadYear='$stdAcadYear', studImage='$studImage', availableStatus='$availableStatus', info='$info', stdID='$userID';";


	
	

	
	$stmt = $this->conn->multi_query($sql);

           // return $response;
        }
		
	  
	  
	  
	  
	   ////////edit staff profile
	   
	   
	   
	   public function editstaffProfile($userID, $userName, $password,  $staffDept, $staffSubjects, $staffType, $roomLocation, $info, $staffImage, $availblestatus){
      
	 

$sql = "UPDATE user SET userName='$userName', password='$password' WHERE userID='$userID';";

$sql .="UPDATE  staff SET staffDept='$staffDept', staffSubjects='$staffSubjects', staffType='$staffType', roomLocation='$roomLocation', info='$info', staffImage='$staffImage', availblestatus='$availblestatus'  WHERE staffID='$userID';";

$sql .="INSERT into  staff SET staffDept='$staffDept', staffSubjects='$staffSubjects', staffType='$staffType', roomLocation='$roomLocation', info='$info', staffImage='$staffImage', availblestatus='$availblestatus', staffID='$userID';";
	
	$stmt = $this->conn->multi_query($sql);

           // return $response;
        }
		
	
	   
	   
	   
	   //add event   
	     public function addEvent($eventDesc, $eventImage, $creatTime, $startTime, $finishTime, $eventName) {
            $response = array();

            // First check if user already existed in db
            if (!$this->isEventExists($eventName)) {

                // insert query
                $stmt = $this->conn->prepare("INSERT INTO event(eventDesc, eventImage, creatTime, startTime, finishTime, eventName) values(?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssss", $eventDesc, $eventImage, $creatTime, $startTime, $finishTime, $eventName);

                $result = $stmt->execute();
			

                $stmt->close();

                // Check for successful insertion
                if ($result) {
                    // event successfully inserted
                    return 1;
                } else {
                    // Failed to create event
                    return -1;
                }
            } else {
                // event with same Event Name already existed in the db
                return 0;
            }

            return $response;
        }
	              
	   
	   
	   
//delete event

			
			public function deleteEvent($eventName){
      
	 


	
	// delete quary
  $stmt = $this->conn->prepare("DELETE FROM event WHERE eventName=?");
                $stmt->bind_param("s", $eventName);

	
	$result = $stmt->execute();
		

          
        }



//edit event 

 public function editEvent($eventDesc, $eventImage, $creatTime, $startTime, $finishTime, $eventName)
 {
	 
	 $sql = "UPDATE event SET eventDesc='$eventDesc', eventImage='$eventImage', creatTime='$creatTime', startTime='$startTime', finishTime='$finishTime', eventName='$eventName' WHERE eventName='$eventName';";
       $stmt = $this->conn->multi_query($sql);
    
	 
 }




        /**
         * Checking for duplicate user by email address
         * @param String $email email to check in db
         * @return boolean
         */
        private function isUserExists($email) {
            $stmt = $this->conn->prepare("SELECT userID from user WHERE email=?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();
            $num_rows = $stmt->num_rows;
            $stmt->close();
            return $num_rows > 0;
        }
		
		
		 private function isEventExists($eventName) {
            $stmt = $this->conn->prepare("SELECT eventID from event WHERE eventName=?");
            $stmt->bind_param("s", $eventName);
            $stmt->execute();
            $stmt->store_result();
            $num_rows = $stmt->num_rows;
            $stmt->close();
            return $num_rows > 0;
        }
    }

    ?>
