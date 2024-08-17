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
?>
<!DOCTYPE html>
<html lang="en">
   <?php require_once("./common/head.php") ?>
   <body>
      <div class="main d-flex flex-row">
         <?php require_once("./common/sidebar.php") ?>

         <div class="remaining_bar d-flex flex-column">

             <?php require_once("./common/header.php") ?>
            
            <div class="remain_container d-flex flex-column align-items-center justify-content-between">
               <!-- BODY DESIGN -->
               <div class="dta_table">
                  <div class="table-reponsive box">
                     <table id="example" class="table table-striped table-bordered">
                        <thead>
                           <tr>
                              <th>Username</th>
                              <th>E-Mail Address</th>
                              <th>Phone</th>
                              <th>User Rank</th>
                              <th>User Actions</th>
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
    				
    				echo '<tr>
                      <td>'.$username.'</td>
                      <td>'.$email.'</td>
                      <td>'.$phone.'</td>
                      <td>'.$team_name.'</td>';
               echo '<td>
                        <a href="../admin/edituser.php?editId='.$id.'">
                        <button class="btn btn-outline-warning  mb-2 me-4">Edit</button></a> 
                        <a href="../admin/adminu.php?deleteId='.$id.'">
                        <button class="btn btn-outline-danger mb-2 me-4" name="deleteBtn" value="'.$id.'" role="button" type="submit">Delete</button>
                        </a>
                     </td>';
               echo  '</tr>';
    			}
            }else{
                echo '<tr class="">
                        <td valign="top" colspan="5" class="dataTables_empty" style="text-align:center;">
                           There is no user currently registered.
                        </td>
                     </tr>';
            }
			?>            
                        </tbody>
                     </table>
                  </div>
               </div>
               <!-- end BODY DESIGN -->
               <?php require_once("./common/footer.php"); ?>
            </div>
         </div>
      </div>
   </body>
   <?php require_once("./common/footer_script.php"); ?>
</html>