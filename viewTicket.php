<?php
   ob_start();
   require_once "includes/db.php";
   require_once "includes/init.php";
   $assist = $user->isAssist($odb);
   $admin = $user->isAdmin($odb);
  
   if (!$user->LoggedIn()) {
       header("location: login.php");
       die();
   }
   if (!$user->notBanned($odb)) {
       header("location: login.php");
       die();
   }
   
   if (!isset($_GET["id"])) {
       header("location: tickets.php");
       die();
   }
   
   //Check if the ticket exists
   $ticketInfo = $odb->prepare(
       "SELECT * FROM `tickets` WHERE `ticketID` = :ticketID"
   );
   $ticketInfo->execute([":ticketID" => $_GET["id"]]);
   
   $ticketExists = $ticketInfo->rowCount() > 0;
   if ($ticketExists) {
       $ticket = $ticketInfo->fetch(PDO::FETCH_ASSOC);
       //Check that the user is staff or higher or otherwise the owner of the ticket
       //(Don't want users viewing other user's tickets)
       if (!$user->isAssist($odb) && !$user->isAdmin($odb)) {
           $userID = $ticket["userID"];
            $assign_to = array();
            if($ticket["assign_to"]!=''){
                $assign_to = explode(",", $ticket["assign_to"]);
            }

           if ($userID != $_SESSION["ID"] && !in_array($_SESSION["ID"], $assign_to)) {
               header("location: tickets.php");
               die();
           }
       }
   
       $ticketTitle = ucfirst(htmlspecialchars($ticket["ticketTitle"]));
       $timeCreated = $ticket["timeCreated"];
       $ticketStatus = $ticket["ticketStatus"];
       $ticketOpen = $ticketStatus;
   
       $dateCreated = date("d/m/y H:i", $timeCreated);
   } else {
       header("location: tickets.php");
       die();
   }
   
    if (isset($_POST["changeTicket"])) {
       $change = $_POST["changeTicket"];
   
       //Initialise just in case
       $newTicketStatus = 0;
       $changeTicketStatus = $odb->prepare(
           "UPDATE `tickets` SET `ticketStatus` = :status WHERE `ticketID` = :ticketID"
       );
   
       if ($change == "Close Ticket") {
           $newTicketStatus = 0;
       } elseif (
           $change == "Open Ticket" &&
           ($user->isAssist($odb) || $user->isAdmin($odb))
       ) {
           $newTicketStatus = 1;
       }
   
       $changeTicketStatus->execute([
           ":status" => $newTicketStatus,
           ":ticketID" => $_GET["id"],
       ]);
   
       //Refresh this page so that the status is updated
       header("Location: " . $_SERVER["REQUEST_URI"]);
    }

    if (isset($_POST["escalateTicket"])) {
       $escalate = $_POST["escalateTicket"];
       $changeTicketStatus = $odb->prepare(
           "UPDATE `tickets` SET `ticketStatus` = :status WHERE `ticketID` = :ticketID"
       );

       $changeTicketStatus->execute([
           ":status" => 2,
           ":ticketID" => $_GET["id"],
       ]);

       header("Location: " . $_SERVER["REQUEST_URI"]);
    }

    $pageTitle = 'View Ticket';
    require_once 'header.php';
   ?>
   <!--  BEGIN CUSTOM STYLE FILE  -->
    <link href="../src/assets/css/light/components/timeline.css" rel="stylesheet" type="text/css" />

    <link href="../src/assets/css/dark/components/timeline.css" rel="stylesheet" type="text/css" />
    <!--  END CUSTOM STYLE FILE  -->

    <style>
        .toggle-code-snippet { margin-bottom: 0px; }
        body.dark .toggle-code-snippet { margin-bottom: 0px; }
		.comment {
			overflow-wrap: anywhere;
		}
    </style>
      <div class="main-container" id="container">
         <div class="overlay"></div>
         <div class="search-overlay"></div>
         <!--  BEGIN SIDEBAR  --> 
         <?php include "sidebar.php"; ?>
         <!--  END SIDEBAR  -->
         <!--  BEGIN CONTENT AREA  -->
         <div id="content" class="main-content">
            <div class="layout-px-spacing">
               <div class="middle-content container-xxl p-0">
                  <br>
                  <!-- BREADCRUMB -->
                  <div class="page-meta">
                     <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                           <li class="breadcrumb-item">My Support Tickets</li>
                           <li class="breadcrumb-item active" aria-current="page">Viewing Ticket Titled: <?php echo $ticketTitle; ?></li>
                        </ol>
                     </nav>
                  </div>
                  <br>
                  <!-- /BREADCRUMB -->
                  <div class="row layout-top-spacing">
                     <div id="timelineBasic" class="col-lg-12 layout-spacing">
                        <div class="statbox widget box box-shadow">
                           <div class="widget-header">
                              <div class="row" align="Center">
                                 <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                    <h4> Ticket Title: <?php echo $ticketTitle; ?>
                                       <br>
                                       <br>
                                       Ticket Status: 
                                       <?php 
                                            if($ticketOpen==0){
                                                echo '<font color="red">Closed Ticket</font>'; 
                                            }elseif($ticketOpen==1){
                                                echo '<font color="#00ab55">Open Ticket</font>';
                                            }elseif($ticketOpen==2){
                                                echo '<font color="red">Admin Assistance Requested</font>'; 
                                            }
                                         
                                        ?>
                                    </h4>
                                 </div>
                                 <?php if (isset($_POST["leaveResponse"]) && $ticketOpen) {
                                    $errors = [];
                                    $response = $_POST["response"];
                                    
                                    if (empty($response)) {
                                        $errors[] = "You did not enter a response";
                                    } elseif (strlen($response) < 4 || strlen($response) > 4096) {
                                        $errors[] =
                                            "Response length must be between 4 and 4096 characters long.";
                                    }
                                    
                                    if (empty($errors)) {
                                        $createResponse = $odb->prepare("INSERT INTO `ticketResponses` (`college_id`,`ticketID`, `userID`, `response`, `time`) VALUES
                                    		($college_id,:ticketID, :userID, :response, UNIX_TIMESTAMP())");
                                        $createResponse->execute([
                                            ":ticketID" => $_GET["id"],
                                            ":userID" => $_SESSION["ID"],
                                            ":response" => $response,
                                        ]);
                                        echo '<div class="message" id="message"><p><strong>SUCCESS: </strong>Your response has been added to the ticket.</p></div></strong>';
                                    
                                        $response = "";
                                    } else {
                                        echo '<div class="error" id="message"><p><strong>ERROR: </strong>';
                                        foreach ($errors as $error) {
                                            echo "" . $error . "<br />";
                                        }
                                        echo "</div>";
                                    }
                                    } ?>
                              </div>
                           </div>
                           <div class="widget-content widget-content-area pb-1">
                              <ol class="timeline">
                                 <li class="timeline-item extra-space">
                                    <div class="timeline-item-wrapper">
                                       <div class="timeline-item-description"><i></i>
                                       </div>
                                       <?php
                                          $responses = $odb->prepare(
                                              "SELECT (SELECT `username` FROM `users` WHERE `ID` = `userID`) AS username, `response`, `time` FROM `ticketResponses` WHERE `ticketID` = :id ORDER BY `time` ASC"
                                          );
                                          $responses->execute([":id" => $_GET["id"]]);
                                          while ($getResponses = $responses->fetch(PDO::FETCH_ASSOC)) {
                                              $userloop = ($getResponses["username"])?$getResponses["username"]:'Super Admin';
                                              $userResponse =$getResponses["response"];
                                              $responseTime = $getResponses["time"];
                                              $date = date("m/d/y", $responseTime);
                                              $time = date("H:i", $responseTime);
                                              date_default_timezone_set("MST");
                                              if ($userloop != $_SESSION["username"]) {
                                                  $style = ' style="background-color:#D6FFEF"';
                                              } else {
                                                  $style = "";
                                              }
                                          
                                              echo '<div class="message_flex"><span class="timeline-item-icon filled-icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-circle"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg></span><span class="align-self-center response_user mx-3">' .
                                                  $userloop .
                                                  "</span><span class='response_time'><sub>" .
                                                  $date .
                                                  " at " .
                                                  $time .
                                                  "</sub></span></div>";
                                              echo '<div class="comment">' . $userResponse . "</div>";
                                          }
                                          ?>
                                 </li>
                                 <li class="timeline-item">
                                 <div class="new-comment">
                                 <!-- Form -->
                                 <?php 
                                    if ($ticketOpen==1) {
                                        echo '<form action="" class="form"  onsubmit="javascript: return process();" method="POST">
                                                 <div class="widget">
                                                         <div class="form-group mb-4">
    						                            <div id="blog-description" class="mb-3">
    						                                
    						                            </div>
    						                            <input type="hidden" id="comment_data" name="response" /></div>
                                        <div class="clear"></div>
                                        </div>
                                        <input type="submit" value="Leave Response" name="leaveResponse" class="btn btn-outline-success btn-lrg" />
                                        <input type="submit" value="Close Ticket" name="changeTicket" class="btn btn-outline-danger btn-lrg  mx-2" />';

                                        if($assist){
                                            echo '<input type="submit" value="Escalate Ticket" name="escalateTicket" class="btn btn-outline-primary btn-lrg" />';
                                        }

                                        echo '</form>';

                                    } elseif ($assist || $admin) {
                                        echo '<input type="submit" value="Open Ticket" name="changeTicket" class="btn btn-outline-success btn-lrg" />';                                        
                                    } 
                                ?>
                                 </div>
                                 </div>
                           </div>
                           </li>
                           </ol>
                        </div>
                     </div>
                  </div>
               </div>
               <?php require_once 'includes/footer-section.php'; ?>
            </div>           
         </div>
         <!--  END CONTENT AREA  -->
      	</div>
      	<?php require_once 'footer.php'; ?>
      	<script>
            function process(){
               if(!$('div.ql-blank').length){
                    var content = $('div.ql-editor').html();
                    $('#comment_data').val(content); 
               } else {
                    $('#comment_data').val(''); 
               }
                      
               return true;
            }

    	</script>
      	<script src="../src/plugins/src/highlight/highlight.pack.js"></script>
      	<!-- END GLOBAL MANDATORY STYLES -->
   </body>
</html>