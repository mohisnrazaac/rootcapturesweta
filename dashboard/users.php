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
    // Get College Id
    $getUserDetailIdWise = $user->getUserDetailIdWise($odb); 
    $college_id = $getUserDetailIdWise['college_id'];
    // Delete USer
    if (isset($_GET['deleteId']))
    {
        $delete = $_GET['deleteId'];

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
    }
    try
    {
      
        $SQLGetUsers = $odb -> query("SELECT users.*,teams.name as team_name FROM `users` LEFT JOIN   `teams` ON users.rank = teams.id WHERE users.college_id = $college_id  AND users.ID != ".$_SESSION['ID']." AND users.status != 2 ORDER BY `ID` DESC");
        $SQLGetUsers -> execute();
        $userR =  $SQLGetUsers -> fetchAll(PDO::FETCH_ASSOC);
    }  
    catch (\Exception $e) {                   
        $errors[] = $e; 
    }

    $title = 'User Management';
    require_once('common/header.php'); 
    
?>

<!-- page constent -->

   
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.3/css/dataTables.bootstrap5.css" />

   
	<div class="row special_btn_long_menu">
			<ul class="d-flex flex-sm-row flex-column align-items-center justify-content-center" style="width: 100%;"> 
					<li class=""><a class="top_menu_item_long_menu" href="https://rootcapture.com/admin/addusers.php">Add a User</a></li> 
			</ul>  	 
	</div>

<div class="p-4"></div>

<div class="container px-5"> 
</head>
<body>

<div class="main-container mt-5">
    <span class="cyber_range_heading_bg">Cyber Range / <span class="primary-color">User Management</span></span>
</div>
	
	
  <div class="main_announcement container ">
     
      <div style="margin-bottom: 1px">
        <label class="cyber_range_bg" for="editor"><i>&nbsp;</i></label> 
      </div>   
	  
  
 
 

	  
	   <table id="rc_table" class="table table-striped nowrap" style="width:100%">
        <thead>
            <tr>
                <th>Username</th>
                <th>E-Mail Address</th>
                <th>Phone</th> 
                <th>User Rank</th> 
                <th style="text-align: right">User Actions</th> 
            </tr>
        </thead>
        <tbody> 
		<?php
        if(!empty($userR)  && is_array($userR))
        {
            
            foreach ($userR as $key => $getInfo) 
            {
                $id = $getInfo['ID'];
                $username = $getInfo['username'];
                $email = $getInfo['email'];
                $phone = $getInfo['phone'];
                $team_name = $getInfo['team_name'];
        ?>
            <tr>
                <td><?=$username?></td>
                <td><?=$email?></td>
                <td><?=$phone?></td>
                <td><?=$team_name?></td>
                <td> 

				<div class="d-flex flex-row-reverse">
                    <!-- <a href="https://rootcapture.com/admin/edituser.php?editId=">
					    <button type="submit" class=" mx-2  rc-btn button-primary">VIEW</button>
                    </a> -->
					<a href="https://rootcapture.com/admin/edituser.php?editId=<?=$id?>">
                        <button type="submit" class=" mx-2 rc-btn button-warning">EDIT</button>
                    </a>
                    <a href="https://rootcapture.com/admin/edituser.php?deleteId=<?=$id?>">
					    <button type="submit" class=" mx-2  rc-btn button-danger">DELETE</button>
                    </a>
				</div>


				</td> 
            </tr> 
        <?php
            }
         }
         else
         {
            echo '<tr>
                        <td colspan="5">
                           There is no user currently registered.
                        </td>
                     </tr>';
         }
        ?>
        </tbody> 
    </table>
 
   
 
 
      
	  <!-- <div class="d-flex flex-row-reverse">
      <button type="submit" class="rc-btn submit-button">Submit</button>
	  </div>   -->
	 
  </div>


 
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script> 
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
  <script>
 
	  new DataTable('#rc_table');
  </script> 
  
  
  
  <?php 
  
  function generatePhoneNumber() {
    $phoneNumber = '+1'; // Assuming US country code
    for ($i = 0; $i < 10; $i++) {
        $phoneNumber .= mt_rand(0, 9); // Append random digits
        if ($i == 2 || $i == 5) {
            $phoneNumber .= '-'; // Add dashes at appropriate positions
        }
    }
    return $phoneNumber;
}

?>

<!-- page conctent -->

<?php require_once('common/footer.php') ?>