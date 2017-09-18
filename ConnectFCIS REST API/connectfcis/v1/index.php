    <?php

    require_once '../include/db_handler.php';
    require '.././libs/Slim/Slim.php';

    \Slim\Slim::registerAutoloader();

    $app = new \Slim\Slim();

    //user register
    $app->post('/user/register', function() use ($app) {
                // check for required params
        verifyRequiredParams(array('fName', 'lName', 'email', 'userName', 'password', 'gender', 'dateOfBirth', 'type'));
        
        $response = array();

                // reading post params
        $fName = $app->request->post('fName');
        $lName = $app->request->post('lName');
        $email = $app->request->post('email');
		$userName = $app->request->post('userName');
        $password = $app->request->post('password');
		$gender = $app->request->post('gender');
		$dateOfBirth = $app->request->post('dateOfBirth');
		$type = $app->request->post('type');
       
      
                // validating email address
        validateEmail($email);
		
        $db = new DbHandler();
		//here send to db_handler
        $res = $db->register($fName, $lName, $email, $userName, $password, $gender, $dateOfBirth, $type);

        if ($res == 1) {
            $response["error"] = false;
            $response["message"] = "You are successfully registered";
        } else if ($res == -1) {
            $response["error"] = true;
            $response["message"] = "Oops! An error occurred while registereing";
        } else if ($res == 0) {
            $response["error"] = true;
            $response["message"] = "Sorry, this email already existed";
        }

        echoRespnse(200, $response);
    });

	

    // User login
    $app->post('/user/login', function() use ($app) {
        // check for required params
        verifyRequiredParams(array('email', 'password'));

        // reading post params
        $email = $app->request->post('email');
        $password = $app->request->post('password');
       
        $db = new DbHandler();
        $response = array();

        if ($db->login($email,$password)) {
            $user = $db->getUser($email);
       
	   // return user data when login, to say welcome ahmed for ex.
            $response['error'] = false;
            $response['userID'] = $user['userID'];
            $response['firstName'] = $user['firstName'];
            $response['lastName'] = $user['lastName'];
        } else {
            $response['error'] = true;
            $response['message'] = "Invalid username or password";
        }
        echoRespnse(200, $response);
    });
	
	
	
	
	
	
	
	
	
	
    // get Home page events
    
	$app->get('/user/getEvents', function() use ($app) {

	
        $db = new DbHandler();
	

      $response = array();

  
  ///
		 $response['events'] = array();

        $result = $db->getEvents();
        while($row = $result->fetch_assoc()){
            $temp = array();
            $temp['eventDesc']=$row['eventDesc'];
            $temp['eventImage'] = $row['eventImage'];
            $temp['creatTime'] = $row['creatTime'];
            $temp['startTime'] = $row['startTime'];
            $temp['finishTime'] = $row['finishTime'];
            $temp['eventName'] = $row['eventName'];
			
            array_push($response['events'],$temp);
        }
		 ///
        echoRespnse(200, $response);
    });
	
	
	
	     // getUserID, given email
	 $app->post('/user/getUserID', function() use ($app) {
        // check for required params
        verifyRequiredParams(array('email'));
        $db = new DbHandler();
       
		
        // reading post params
        $email = $app->request->post('email');
		$response = array();
        $result = $db->getUserID($email);

        echoRespnse(200, $result);
    });
	
	
	
	
	
    // get all registered users (for admin to manage)
    $app->get('/user/getUsers', function() use ($app) {
      
	  

        $db = new DbHandler();
		$db->getUsers();
        $response = array();

         
            
         
		 ///
		 $response['users'] = array();

        $result = $db->getUsers();
        while($row = $result->fetch_assoc()){
            $temp = array();
            $temp['firstName']=$row['firstName'];
            $temp['type'] = $row['type']; 
             $temp['userID'] = $row['userID'];
            array_push($response['users'],$temp);
        }
		 ///
        echoRespnse(200, $response);
    });
	
	
	
	// getImage, given email.
	
	 $app->post('/user/getImage', function() use ($app) {
        // check for required params
        verifyRequiredParams(array('email'));

        // reading post params
        $email = $app->request->post('email');
       
	   
        $db = new DbHandler();
        $response = array();

       
            $user = $db->getImage($email);
         
		 $response['image'] = $user['image'];
            
        
        echoRespnse(200, $response);
    });
	
	
	
	
	// edit staff profile
	$app->post('/user/editstaffProfile', function() use ($app) {
                // check for required params
        verifyRequiredParams(array('userID',  'userName', 'password', 'staffDept', 'staffSubjects', 'staffType', 'roomLocation', 'info', 'staffImage', 'availblestatus'));
        $response = array();

                // reading post params
       
		$userName = $app->request->post('userName');
        $password = $app->request->post('password');
        $staffDept = $app->request->post('staffDept');
		$staffSubjects = $app->request->post('staffSubjects');
        $staffType = $app->request->post('staffType');
		$roomLocation = $app->request->post('roomLocation');
		$info = $app->request->post('info');
		$userID=$app->request->post('userID'); 
		$staffImage=$app->request->post('staffImage');
		$availblestatus=$app->request->post('availblestatus');
		
       

                // validating email address
        //validateEmail($email);
		
        $db = new DbHandler();
		//here send to db_handler
        $res = $db->editstaffProfile($userID, $userName, $password,  $staffDept, $staffSubjects, $staffType, $roomLocation, $info, $staffImage, $availblestatus);

        if ($res == 1) {
            $response["error"] = false;
            $response["message"] = "succefuly,updated";
        } else if ($res == -1) {
            $response["error"] = true;
            $response["message"] = "Oops! An error occurred while registereing";
        } else if ($res == 0) {
            $response["error"] = false;
            $response["message"] = "succefuly,updated";
        }

		
echo "Hello world!wait for response";
        echoRespnse(200, $response);
    });

	
	
	
	
		   
	
	//edit student profile
	
$app->post('/user/editStdProfile', function() use ($app) {
                // check for required params
        verifyRequiredParams(array('userID',  'userName', 'password', 'stdDept', 'stdAcadYear', 'studImage', 'availableStatus', 'info'));

        $response = array();

                // reading post params
       
		$userName = $app->request->post('userName');
        $password = $app->request->post('password');
        $stdDept = $app->request->post('stdDept');
		$stdAcadYear = $app->request->post('stdAcadYear');
        $studImage = $app->request->post('studImage');
		$availableStatus = $app->request->post('availableStatus');
		$info = $app->request->post('info');
		$userID=$app->request->post('userID');
       
	   
echo "Hello world!1";
                // validating email address
        //validateEmail($email);
		
        $db = new DbHandler();
		//here send to db_handler
        $res = $db->editStdProfile($userID, $userName, $password,  $stdDept, $stdAcadYear, $studImage, $availableStatus, $info);
echo "Hello world!after go to db";
        if ($res == 1) {
            $response["error"] = false;
            $response["message"] = "You are successfully registered";
        } else if ($res == -1) {
            $response["error"] = true;
            $response["message"] = "Oops! An error occurred while registereing";
        } else if ($res == 0) {
            $response["error"] = false;
            $response["message"] = "succefuly,updated";
        }
//77
echo "Hello world!wait for response";
        echoRespnse(200, $response);
    });

	
	////add event
	
	    $app->post('/user/addEvent', function() use ($app) {
                // check for required params
        verifyRequiredParams(array('eventDesc', 'eventImage', 'creatTime', 'startTime', 'finishTime', 'eventName'));
echo "Hello world!0";
        $response = array();

                // reading post params
        $eventDesc = $app->request->post('eventDesc');
        $eventImage = $app->request->post('eventImage');
        $creatTime = $app->request->post('creatTime');
		$startTime = $app->request->post('startTime');
        $finishTime = $app->request->post('finishTime');
		$eventName = $app->request->post('eventName');
		
echo "Hello world!1";
        
        $db = new DbHandler();
		//here send to db_handler
        $res = $db->addEvent($eventDesc, $eventImage, $creatTime, $startTime, $finishTime, $eventName);
echo "Hello world!after go to db";
        if ($res == 1) {
            $response["error"] = false;
            $response["message"] = "successfully created";
        } else if ($res == -1) {
            $response["error"] = true;
            $response["message"] = "Oops! An error occurred while Creating";
        } else if ($res == 0) {
            $response["error"] = true;
            $response["message"] = "Sorry, this event already existed";
        }
//77
echo "Hello world!wait for response";
        echoRespnse(200, $response);
    });
	
	
	
	
	///delete event  
	
	
	$app->post('/user/deleteEvent', function() use ($app) {
                // check for required params
        verifyRequiredParams(array('eventName'));
       // echo "Hello world!0";
        $response = array();
	 $eventName = $app->request->post('eventName');
	  $db = new DbHandler();
		//here send to db_handler
        $res = $db->deleteEvent($eventName);
//echo "Hello world!after go to db";
        
		if ($res == 1) {
			
            $response["error"] = false;
		$response["message"] = "successfully deleted";
// echo "Hello 1";
  
		}
		 // return here
		
		else if ($res == 0) {
            $response["error"] = false;
		$response["message"] = "successfully deleted";
  //echo "Hello 2";
		}
		
		else if ($res == -1) {
            $response["error"] = true;
		$response["message"] = "Oops! An error occurred while updating";
// echo "Hello 3";
		}

		

		echoRespnse(200, $response);

        
	 
       });
	
	
	////
	
	
	//edit event 
	
	//editEvent($eventID, $eventDesc, $eventImage, $creatTime, $startTime, $finishTime, $eventName)
	
	
	$app->post('/user/editEvent', function() use ($app) {
                // check for required params
        verifyRequiredParams(array('eventDesc', 'eventImage', 'creatTime', 'startTime', 'finishTime', 'eventName'));
       echo "Hello world!0";
        $response = array();

                // reading post params
	    
		$eventDesc = $app->request->post('eventDesc');
        $eventImage = $app->request->post('eventImage');
        $creatTime = $app->request->post('creatTime');
		$startTime = $app->request->post('startTime');
        $finishTime = $app->request->post('finishTime');
		$eventName = $app->request->post('eventName');
		
echo "Hello world!1";
        
        $db = new DbHandler();
		//here send to db_handler
        $res = $db->editEvent($eventDesc, $eventImage, $creatTime, $startTime, $finishTime, $eventName);
echo "Hello world!after go to db";
        if ($res == 1) {
            $response["error"] = false;
            $response["message"] = "successfully updated";
        } else if ($res == -1) {
            $response["error"] = true;
            $response["message"] = "Oops! An error occurred while updating";
        }
		else if ($res == 0) {
            $response["error"] = false;
            $response["message"] = "successfully updated";

        }
		
//77
echo "Hello world!wait for response";
        echoRespnse(200, $response);
    });
	
	
	//
	//add comment 

	
	
	$app->post('/user/addComment', function() use ($app) {
                // check for required params
        verifyRequiredParams(array('commContent', 'createdTime', 'EventName', 'createdUser'));
       echo "Hello world!0";
        $response = array();

                // reading post params
	    $commContent=$app->request->post('commContent');
        $createdTime = $app->request->post('createdTime');
        $EventName = $app->request->post('EventName');
        $createdUser = $app->request->post('createdUser');
		
		
echo "Hello world!1";
        
        $db = new DbHandler();
		//here send to db_handler
        $res = $db->addComment($commContent, $createdTime, $EventName, $createdUser);
echo "Hello world!after go to db";
        if ($res == 1) {
            $response["error"] = false;
            $response["message"] = "successfully commenting";
        } else if ($res == -1) {
            $response["error"] = true;
            $response["message"] = "Oops! An error occurred while commenting";
        }
		else if ($res == 0) {
            $response["error"] = false;
            $response["message"] = "successfully updated";

        }
		
//77
echo "Hello world!wait for response";
        echoRespnse(200, $response);
    });
	
	
	//  deleteUser
	
		
	$app->post('/user/deleteUser', function() use ($app) {
                // check for required params
        verifyRequiredParams(array('userID', 'type'));
       // echo "Hello world!0";
        $response = array();
	 $userID = $app->request->post('userID');
	 $type = $app->request->post('type');
	 
	 
	  $db = new DbHandler();
		//here send to db_handler
        $res = $db->deleteUser($userID, $type);
//echo "Hello world!after go to db";
        
		if ($res == 1) {
			
            $response["error"] = false;
		$response["message"] = "successfully deleted";
// echo "Hello 1";
  
		}
		 // return here
		
		else if ($res == 0) {
            $response["error"] = false;
		$response["message"] = "successfully deleted";
  //echo "Hello 2";
		}
		
		else if ($res == -1) {
            $response["error"] = true;
		$response["message"] = "Oops! An error occurred while deleting";
// echo "Hello 3";
		}

		

		echoRespnse(200, $response);

        
	 
       });
	
	
	//delete comment  deleteComment($commID)
	
	
	
	$app->post('/user/deleteComment', function() use ($app) {
                // check for required params
        verifyRequiredParams(array('commID'));
       echo "Hello world!0";
        $response = array();

                // reading post params
	    $commID=$app->request->post('commID');
        
		
echo "Hello world!1";
        
        $db = new DbHandler();
		//here send to db_handler
        $res = $db->deleteComment($commID);
echo "Hello world!after go to db";
        if ($res == 1) {
            $response["error"] = false;
            $response["message"] = "successfully deleted";
        } else if ($res == -1) {
            $response["error"] = true;
            $response["message"] = "Oops! An error occurred while deleting";
        }
		else if ($res == 0) {
            $response["error"] = false;
            $response["message"] = "successfully deleting";

        }
		
//77
echo "Hello world!wait for response";
        echoRespnse(200, $response);
    });
	
	
	
	
	///get event comments
	
	
	$app->post('/user/getEventComments', function() use ($app) {
                // check for required params
        verifyRequiredParams(array('EventName'));
        
		$EventName=$app->request->post('EventName');
        
		$response = array();

		
        $db = new DbHandler();
	    
         
            
         
		 ///
		 $response['eventComments'] = array();

        $result = $db->getEventComments($EventName);
        while($row = $result->fetch_assoc()){
            $temp = array();
            $temp['commID']=$row['commID'];
            $temp['commContent'] = $row['commContent'];
            $temp['createdTime'] = $row['createdTime'];
            $temp['createdUser'] = $row['createdUser'];
           
		    array_push($response['eventComments'],$temp);
        }
		 ///
        echoRespnse(200, $response);
    });
	
	//idOfMail($email)
	
	
	
	
	$app->post('/user/idOfMail', function() use ($app) {
                // check for required params
        verifyRequiredParams(array('email'));
        
		$email=$app->request->post('email');
        
		$response = array();

		
        $db = new DbHandler();
	     $response['id'] = $db->idOfMail($email)->fetch_assoc();
         $response['error'] = "false";

				
         
            
       // echoRespnse(200, $response);
		 echoRespnse(200, $response);
    });
	
	
	
///

    /**
     * Verifying required params posted or not
     */
    function verifyRequiredParams($required_fields) {
        $error = false;
        $error_fields = "";
        $request_params = array();
        $request_params = $_REQUEST;
        // Handling PUT request params
        if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
            $app = \Slim\Slim::getInstance();
            parse_str($app->request()->getBody(), $request_params);
        }
        foreach ($required_fields as $field) {
            if (!isset($request_params[$field]) || strlen(trim($request_params[$field])) <= 0) {
                $error = true;
                $error_fields .= $field . ', ';
            }
        }

        if ($error) {
            // Required field(s) are missing or empty
            // echo error json and stop the app
            $response = array();
            $app = \Slim\Slim::getInstance();
            $response["error"] = true;
            $response["message"] = 'Required field(s) ' . substr($error_fields, 0, -2) . ' is missing or empty';
            echoRespnse(400, $response);
            $app->stop();
        }
    }

    /**
     * Validating email address
     */
    function validateEmail($email) {
        $app = \Slim\Slim::getInstance();
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $response["error"] = true;
            $response["message"] = 'Email address is not valid';
            echoRespnse(400, $response);
            $app->stop();
        }
    }

	
    /**
     * Echoing json response to client
     * @param String $status_code Http response code
     * @param Int $response Json response
     */
    function echoRespnse($status_code, $response) {
        $app = \Slim\Slim::getInstance();
        // Http response code
        $app->status($status_code);

        // setting response content type to json
        $app->contentType('application/json');

        echo json_encode($response);
    }

    $app->run();
    ?>