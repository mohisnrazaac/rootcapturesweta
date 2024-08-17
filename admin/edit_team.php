<?php
ob_start();
require_once '../includes/db.php';
require_once '../includes/init.php';
if (!($user -> LoggedIn()))
{
	header('location: ../login.php');
	die();
}
if (!($user -> notBanned($odb)))
{
	header('location: ../login.php');
	die();
}

if ($user -> isAdmin($odb)) {

} else {
	header('location: ../index.php');
	die();
}
        $editId =  $_GET['editId']; 
		if (isset($_POST['updateTeam']))
		{
			 $team = $_POST['team'];
			$errors = array();
			if (empty($team))
			{
				$errors[] = 'Please your team name';
			} 
			if (empty($errors))
			{   
                $sqlTeamAlreadyExists = $odb -> query("SELECT COUNT(id) FROM `teams` WHERE `name` LIKE '$team' AND `id` != $editId");
                if($sqlTeamAlreadyExists->fetchColumn())
                {   
                    $errors[] = 'This team is already in list';
                }
                else
                {  
                   
                    $SQLupdate = $odb -> prepare("UPDATE teams SET `name` = :team_name, `updated_at` = :updated_at WHERE id = :id");
				    $SQLupdate -> execute(array(':team_name' => $team, ':updated_at' => DATETIME, ':id' => $editId));
                    $user->addRecentActivities($odb,'edit_team',' Edited a New Team ('.$team.') on the Platform.');
                }
			} 
			// else
			// {
			// 	foreach($errors as $error)
			// 	{
			// 		echo '-'.$error.'<br />';
			// 	}
			// 	echo '</div>';
			// }
		}

        $pageTitle = 'Edit A Team';
        require_once '../header.php';
?>

    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container" id="container">

        <div class="overlay"></div>
        <div class="search-overlay"></div>

        <!--  BEGIN SIDEBAR  --> 
<?php include '../sidebar.php'; ?>
        <!--  END SIDEBAR  -->

        <!--  BEGIN CONTENT AREA  -->
        <div id="content" class="main-content">
            <div class="layout-px-spacing">

                <div class="middle-content container-xxl p-0">
   
                    <!-- BREADCRUMB -->
                    <div class="page-meta">
                        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">Teams</li>
                                <li class="breadcrumb-item active" aria-current="page">Edit A Team</li>
                            </ol>
                        </nav>
                    </div>
					<br>
                    <!-- /BREADCRUMB -->
                       <div class="col-lg-12 col-12 layout-spacing">
                         <div class="row mb-3">
                                <div class="col-md-12">
                                    <h2>Edit A Team</h2>
        
                                    <div class="animated-underline-content">
                                        <ul class="nav nav-tabs" id="animateLine" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" id="animated-underline-home-tab" data-bs-toggle="tab" href="#animated-underline-home" role="tab" aria-controls="animated-underline-home" aria-selected="true"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg> Edit A Team</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-content" id="animateLineContent-4">
                                <div class="tab-pane fade show active" id="animated-underline-home" role="tabpanel" aria-labelledby="animated-underline-home-tab">
		<?php 
            if (isset($_POST['updateTeam']))
            {
                if(empty($errors)) {
                    echo '<div class="message" id="message"><p><strong>SUCCESS: The team has been updated! You are now being redirected to the manage team.</strong></div><meta http-equiv="refresh" content="4;url='.BASEURL.'admin/manage_team.php">';
                    
                    $team = '';
                } else {
                    echo '<div class="error" id="message"><p><strong>ERROR: </strong>';
                    foreach($errors as $error) {
                        echo ''.$error.'<br />';
                    }
                    echo '</div>';
                }
                
            }	
            
            // Get Team Data id wise
           
            $SQLgetTeam = $odb -> prepare("SELECT * FROM `teams` WHERE `id` = :editId");
			$SQLgetTeam -> execute(array(':editId' => $editId));
			$teamInfo = $SQLgetTeam -> fetch(PDO::FETCH_ASSOC); 
            $team = '';
			if(!empty($teamInfo))
			{
				$team = $teamInfo['name'];
			}
			else
			{
				echo '<div class="error" id="message"><p><strong>ERROR: </strong>Something went wrong</p></div>';
			}
		?>
                                    
                              <div class="row">
                                        <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
                                    <form action="" class="section general-info" method="POST">
                                       
                                      <div class="info">
                                                    <div align="Center">
                                                         <h6 class="">Edit A Team</h6>

                                                        <div class="row">
 <div class="col-lg-11 mx-auto">
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 mt-md-0 mt-4">

                                                            <div class="row">



                                        <div class="col-md-12">
                                        <div class="form-group ">
                                            <label for="titleAdd">Team</label>
                                            <input type="text" value="<?=$team?>" class="form-control mb-3" placeholder="Write your team name here" name="team">
                                        </div>
                                    </div>
                                    
                                       <div class="col-md-12 mt-1">
                                        <div class="form-group text-end">
                                            <input type="submit" name="updateTeam" class="btn btn-outline-success btn-lrg">
                                        </div>
                                        </div>

                                    </div></div></div></div></div></div></div>
                                    </form>
                                   </div>
                               </div>


                                </div>
                            </div>
                        </div>
                </div>

            </div>

            <!--  BEGIN FOOTER  -->
            <?php require_once '../includes/footer-section.php'; ?>
            <!--  END FOOTER  -->
            <!--  END CONTENT AREA  -->
        </div>
        <!--  END CONTENT AREA  -->
    </div>
    <!-- END MAIN CONTAINER -->
   
    <?php require_once '../footer.php'; ?>

    

    <script>
            function process()
            {
               if(!$('div.ql-blank').length)
               {
                    var content = $('div.ql-editor').html();
                    $('#detailAdd').val(content); 
               }
               else
               {
                $('#detailAdd').val(''); 
               }
                      
               return true;
            }

    </script>


</body>
</html>