<?php 
  require('model/database.php');
  require('model/RfpList.php');
  require('model/user.php');
  //ini_set('display_errors',1);
  //ini_set('display_startup_errors',1);
  //error_reporting(-1);

  $db = Database::getDB(); 

  $userId = "1";

  //Create a new User object -- Contains all info about this user
  $user = new User($userId);

  //Get all of the Rfps in the database ((((( Need to check date/num responses LATER)))))
  $toRespond = new RfpList();
  
  //Make a table of all available RFPs
  $rfpTable = $toRespond->getRfpTable();

  //Make a table of all RFPs user has responded to
  $responses = $user->getRFPresponses($userId); 
  $responseTable = $user->getResponseTable($responses);


  //FOR POST REQUESTS
  if( isset($_POST["action"]) ){

    if( $_POST["action"] == "home"){

      include('view/vendorHome.php');

    }

    elseif( $_POST["action"] == "viewRfps" ){

      //Show the list of RFPs
      include('view/activeRfps.php');

    }

    elseif($_POST["action"] == "respondToRFP"){

      $title = $_POST["propTitle"]; 
      $cost = $_POST["propCost"]; 
      $sumDesc = $_POST["sumDesc"]; 
      $assRFP = $_POST["associatedRFP"]; 

      $vPartners = []; 
      foreach($_POST as $key => $val){
        if(strpos($key, "vP") !== false){
          array_push($vPartners, $val);
        }
       } 

      $responseArray = array(
                              "responseNum"=>3,
                              "associatedRFP"=>$assRFP,
                              "title"=>$title,
                              "userId"=>$user->getUserId(),
                              "desc"=>$sumDesc,
                              "cost"=>$cost
                            );

      $db->responses->insert($responseArray);

      include('view/vendorHome.php');

    }
  }

  //FOR GET REQUESTS
  elseif( isset($_GET["action"]) ){
    if( $_GET["action"] == "respondToRequest"){
      include('view/responseForm.php');
    } 
  }
  
  //IF NEITHER THEN LOAD VENDOR HOMEPAGE
  else{      

    //Show their home page
    include('view/vendorHome.php');

  }
?>
