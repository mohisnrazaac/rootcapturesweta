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

// if is purple or is red or is blue or is admin or is assistant


$pageTitle = 'User Administration';
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
                                <li class="breadcrumb-item">Cyber Range</li>
                                <li class="breadcrumb-item active" aria-current="page">User Administration</li>
                            </ol>
                            <br>
                            <a class="btn btn-outline-success btn-lrg" href="../admin/addusers.php" role="button">Create A New User</a>

                        </nav>
                    </div>
                    <!-- /BREADCRUMB -->
                    <div class="row layout-top-spacing">
		<!-- <div class="alert alert-arrow-right alert-icon-right alert-light-warning alert-dismissible fade show mb-4" role="alert">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12" y2="16"></line></svg>
                                        <strong><b>Warning!</b></strong> It is important to note that refreshing your Cyber Range will result in the removal of all users except your Administrative User.
                                    </div> -->
      <?php 
		// if (isset($_POST['refreshCyberRange']))
		// {
        //     //Delete all user except AdminDemo
        //     $SQL = $odb -> prepare("DELETE FROM `users` WHERE `username` != :username");
        //     $SQL -> execute(array(':username' => 'AdminDemo'));

		// 	$SQL = $odb -> query("TRUNCATE `blueservers`");
		// 	$SQL = $odb -> query("TRUNCATE `purpleservers`");
		// 	$SQL = $odb -> query("TRUNCATE `redservers`");
		// 	$SQL = $odb -> query("TRUNCATE `loginip`");
		// 	echo '<div class="message" id="message"><p><strong>SUCCESS: </strong>The rootCapture Cyber Range has been refreshed!</p></div>';
		// }
		?>
                        <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                            <div class="widget-content widget-content-area br-8 ">
                                <div class="center-block fix-width scroll-inner">
                                <div class="table-responsive">
                                <table id="zero-config" class="table dt-table-hover tablenoscroll" style="width:100%">
                                    <thead>
                                        <tr>
                <th scope="col" class="col-md-2">Username</th>
                <th class="text-center col-md-3" scope="col">E-Mail Address</th>
                <th class="text-center col-md-2" scope="col">Phone</th>
				<th class="text-center col-md-2" scope="col">User Rank</th>
				<th class="text-center col-md-3" scope="col">User Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
            <?php
                if (isset($_GET['deleteId']))
                {
                    $delete = $_GET['deleteId'];
                    //foreach($deletes as $delete)
                    //{
                        // Start update user placeholdername for announcement on user delete

                        $usersql = $odb -> prepare("SELECT `username` FROM `users` WHERE `ID` = :id");
                        $usersql ->  execute(array(':id' => $delete));
                        $userrow = $usersql -> fetch();
                        $getUsername = '';
                        if(isset($userrow['username']) && $userrow['username']!=''){
                            $getUsername = $userrow['username'];
                            $SQLupdate = $odb -> prepare("UPDATE news SET `created_by` = :created_by WHERE userID = :id");
                            $SQLupdate -> execute(array(':created_by' => $userrow['username'], ':id' => $delete));
                        }

                        // End update user placeholdername for announcement on user delete

                        $SQL = $odb -> prepare("UPDATE `users` SET status = 2 WHERE `ID` = :id");
                        $SQL -> execute(array(':id' => $delete));
                        $user->addRecentActivities($odb,'delete_user',' Deleted the user '.$getUsername.' on the platform.');
                    //}
                    echo '<div class="message" id="message"><p><strong>SUCCESS: </strong>The user has been deleted!</p></div>';
                }
            ?>

			<?php 
             $getUserDetailIdWise = $user->getUserDetailIdWise($odb); 
             $college_id = $getUserDetailIdWise['college_id']; 

			
            try
            {
              
                $SQLGetUsers = $odb -> query("SELECT users.*,teams.name as team_name FROM `users` LEFT JOIN   `teams` ON users.rank = teams.id WHERE users.college_id = $college_id  AND users.ID != ".$_SESSION['ID']." AND users.status != 2 ORDER BY `ID` DESC");
                $SQLGetUsers -> execute();
                $userR =  $SQLGetUsers -> fetchAll(PDO::FETCH_ASSOC);
            }  
            catch (\Exception $e) {                   
                $errors[] = $e; 
            }
           
            if(!empty($userR)  && is_array($userR)){
    			foreach ($userR as $key => $getInfo) 
    			{
    				$id = $getInfo['ID'];
    				$username = $getInfo['username'];
    				$email = $getInfo['email'];
    				$phone = $getInfo['phone'];
    				$team_name = $getInfo['team_name'];
    				
    				echo '<tr class="gradeA"><td>'.$username.'</td><td><center>'.$email.'</center></td><td><center>'.$phone.'</center></td><td><center>'.$team_name.'</center></td>';
                        
                        echo '<td width="70px"><a href="../admin/edituser.php?editId='.$id.'"><button class="btn btn-outline-warning  mb-2 me-4">Edit</button></a> <a href="../admin/adminu.php?deleteId='.$id.'"><button class="btn btn-outline-danger mb-2 me-4" name="deleteBtn" value="'.$id.'" role="button" type="submit">Delete</button></a> </td>';
                    echo  '</tr>';
    			}
            }else{
                echo '<tr class=""><td valign="top" colspan="5" class="dataTables_empty" style="text-align:center;">There is no user currently registered.</td></tr>';
            }
			?>
                                    </tbody>
								</table>
                            </div>
                            </div>
                            
                            </div>
							<br>
							<!-- <center><form action = "" method="post" class="form"><input type="submit" value="Refresh The Cyber Range" name="refreshCyberRange" class="btn btn-outline-danger btn-lrg" /></form></center> -->
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
    <?php  require_once '../footer.php'; ?>
  
    <script>
        $('#zero-config').DataTable({
            "dom": "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l><'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>>>" +
        "<''tr>" +
        "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count  mb-sm-0 mb-3'i><'dt--pagination'p>>",
            "oLanguage": {
                "oPaginate": { "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>', "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>' },
                "sInfo": "Showing page _PAGE_ of _PAGES_",
                "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
                "sSearchPlaceholder": "Search...",
               "sLengthMenu": "Results :  _MENU_",
            },
            "stripeClasses": [],
            "lengthMenu": [7, 10, 20, 50],
            "pageLength": 10 
        });      
       
    </script>
    <!-- END PAGE LEVEL SCRIPTS -->

</body>
</html>