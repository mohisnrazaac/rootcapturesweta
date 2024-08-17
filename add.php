<?php
ob_start();
require_once 'includes/db.php';
require_once 'includes/init.php';
if (!($user -> LoggedIn()))
{
	header('location: login.php');
	die();
}
if (!($user -> notBanned($odb)))
{
	header('location: login.php');
	die();
}

$pageTitle = 'Graded Rubric';
require_once 'header.php';
$getUserDetailIdWise = $user->getUserDetailIdWise($odb); 
$college_id = $getUserDetailIdWise['college_id']; 
$userType = $user -> isAdmin($odb) || $user -> isAssist($odb);
$userList  = $odb -> query("SELECT * FROM `users` WHERE rank > 2 AND college_id = $college_id")->fetchAll(); 

if(isset($_POST['submitTicket'])) {
	$errors = array();

	$ticketTitle = $_POST['ticketTitle'];
	$ticketContent = $_POST['ticketContent'];
    $assignUser = isset($_POST['assignUser'])?$_POST['assignUser']:null; 
    
	
	//Check if the user has already created 10 or more tickets
	$checkTicketCount = $odb -> prepare("SELECT * FROM `tickets` WHERE `userID` = :userID AND `ticketStatus` = 1 and college_id = $college_id");
	$checkTicketCount -> execute(array(':userID' => $_SESSION['ID']));
	
	if($checkTicketCount -> rowCount() >= 10) {
		$errors[] = 'You have too many tickets open. Please wait until your current tickets are resolved before posting a new one.';
	//Don't bother checking for these errors if the user already has too many tickets open
	} else {
		if(empty($ticketTitle)) {
			$errors[] = 'Please fill out the ticket title';
		} elseif(strlen($ticketTitle) < 5 || strlen($title) > 255) {
			$errors[] = 'Ticket title must be between 5 and 255 characters long';
		}
		if(empty($ticketContent)) {
			$errors[] = 'Please fill out the ticket content';
		} elseif(strlen($ticketContent) < 10 || strlen($ticketContent) > 4096) {
			$errors[] = 'Ticket content must be between 10 and 4096 characters long';
		}

        if(!empty($assignUser)) {
            implode(",", $assignUser);
        }
	}
	
	if(empty($errors)) {  
            // try
            // {  
            //     $odb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //     $odb->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $createTicket = $odb -> prepare("INSERT INTO `tickets` (`college_id`,`userID`, `ticketTitle`, `assign_to`, `timeCreated`, `ticketStatus`) VALUES
                ($college_id,:userID, :ticketTitle, :assign_to, UNIX_TIMESTAMP(), 1)");
            $createTicket -> execute(array(':userID' => $_SESSION['ID'], ':ticketTitle' => $ticketTitle,':assign_to' => $assignUser));

            //Get the ID of the ticket we just inserted so that we can use it in ticketResponses
            $ticketID = $odb -> lastInsertID();
            $createResponse = $odb -> prepare("INSERT INTO `ticketResponses` (`college_id`,`ticketID`, `userID`, `response`, `time`) VALUES
                        ($college_id,:ticketID, :userID, :response, UNIX_TIMESTAMP())");
            $createResponse -> execute(array(':ticketID' => $ticketID, ':userID' => $_SESSION['ID'], ':response' => $ticketContent)); 
            // }
            // catch(Exception $e) {
            //     echo 'to'.$assignUser; exit;
            //         echo 'Exception -> ';
            //         var_dump($e->getMessage()); exit;
            // } 
	}

}

?>


    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container" id="container">

        <div class="overlay"></div>
        <div class="search-overlay"></div>

        <!--  BEGIN SIDEBAR  --> 
<?php include 'sidebar.php'; ?>
        <!--  END SIDEBAR  -->

        <!--  BEGIN CONTENT AREA  -->
        <div id="content" class="main-content">
            <div class="layout-px-spacing">

                <div class="middle-content container-xxl p-0">
                    <!-- BREADCRUMB -->
                    <div class="page-meta">
                        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">My Support Tickets</li>
                                <li class="breadcrumb-item active" aria-current="page">Create A New Ticket</li>
                            </ol>
                        </nav>
                    </div>
					<br>
                    <!-- /BREADCRUMB -->
                       <div class="col-lg-12 col-12 layout-spacing">
                          <div class="row mb-3">
                                <div class="col-md-12">
                                    <h2>Create A New Ticket</h2>
        
                                    <div class="animated-underline-content">
                                        <ul class="nav nav-tabs" id="animateLine" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" id="animated-underline-home-tab" data-bs-toggle="tab" href="#animated-underline-home" role="tab" aria-controls="animated-underline-home" aria-selected="true"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg> Create A New Ticket</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                             <div class="tab-content" id="animateLineContent-4">
                                <div class="tab-pane fade show active" id="animated-underline-home" role="tabpanel" aria-labelledby="animated-underline-home-tab">
                            		<?php 
                            		if (isset($_POST['submitTicket']))
                            		{
                                        $redirectUrl = 'tickets.php';
                                        if($userType)
                                        $redirectUrl = 'admin/manageTickets.php';
                            			if(empty($errors)) {
                            				echo '<div class="message" id="message"><p><strong>SUCCESS: Your ticket has been submitted! Your support staff will respond accordingly! You are now being redirected back to your ticket overview.</strong></div><meta http-equiv="refresh" content="9;url='.$redirectUrl.'">';
                            				
                            				$ticketTitle = '';
                            				$ticketContent = '';
                            			} else {
                            				echo '<div class="error" id="message"><p><strong>ERROR: </strong>';
                            				foreach($errors as $error) {
                            					echo ''.$error.'<br />';
                            				}
                            				echo '</div>';
                            			}
                            			
                            		}
                            		
                            		?>

                               <div class="row">
                                        <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">

                                    <form action="" class="section general-info" onsubmit="javascript: return process();" method="POST">

                                       <div class="info">
                                                    <div align="Center">
                                                         <h6 class="">Create A New Ticket</h6>

                                                          <div class="row">
 <div class="col-lg-11 mx-auto">
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 mt-md-0 mt-4">

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group ">
                                                         <label for="ticketTitle">Ticket Title</label>
                                                        <input type="text" class="form-control" name="ticketTitle" placeholder="Write your ticket title here">
                                                    </div>
                                                </div>  
                                            </div>
                                            <br/>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div  class="form-group customTextAreaTxt">
                                                        <label for="blog-description">Ticket Content</label>
                                                        <div id="blog-description" class="mb-3 newImg">
                                                        
                                                        </div>
                                                        <input type="hidden" id="ticketContent" name="ticketContent" />
                                                    </div>
                                                </div>
                                            </div>

                                     
                                    </div>   

                                    <?php if( $userType ){ ?>
                                     <div class="col-md-12 mrginTopN">
                                              <div  class="form-group customTextAreaTxt">
                                            <label for="multistep" class="disBl">Assign ticket to user</label>
                                            <select id="multipleSelectNu" name="assignUser[]" multiple size="2" >
                                                    <?php 
                                                        foreach($userList as $val)
                                                        {
                                                            echo '<option value="'.$val['ID'].'">'.$val['username'].'</option>';    
                                                        }
                                                    ?>
                                            </select>

                                            <!-- <select id="multipleSelectNu" multiple size="2" >
                                           
                                            </select> -->
                                        </div>                                     
                                    </div> 
                                <?php } ?>

              

                                     <div class="col-md-12 mt-1">
                                        <div class="form-group text-end">
                                        <input type="submit" name="submitTicket" class="btn btn-outline-success btn-lrg">
                                    </div>
                                    </div>
                                        

                                    

                              </div></div></div>
                              </div></div></div></div>


                                    </form>
                                </div>
                            </div>
                        </div>
                </div>

            </div>

          
             <?php require_once 'includes/footer-section.php'; ?>
            <!--  END CONTENT AREA  -->
        </div>
        <!--  END CONTENT AREA  -->
    </div>
    <!-- END MAIN CONTAINER -->
       <?php require_once 'footer.php'; ?>
      <script>
            function process() {
               if(!$('div.ql-blank').length){
                    var content = $('div.ql-editor').html();
                    $('#ticketContent').val(content); 
               } else {
                    $('#ticketContent').val(''); 
               }
                      
               return true;
            }

            let frameworkCMS = new vanillaSelectBox("#multipleSelectNu", {
                "maxHeight": 200,
                "search": true,
                translations: { "all": "All", "items": "Selected" },
                "placeHolder": "Choose..." 
            });
    </script>
  
</body>
</html>