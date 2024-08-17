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
if (isset($_POST['updateTeam']))
{
      $editteam = $_POST['editteam'];
      $edit_id = $_POST['edit_id'];
      $favcolor = $_POST['favcolor'];
   $errors = array();
   if (empty($editteam) && empty($edit_id) && empty($favcolor))
   {
      $errors[] = 'Please fill required field.';
   } 
   if (empty($errors))
   {   
      $sqlTeamAlreadyExists = $odb -> query("SELECT COUNT(id) FROM `teams` WHERE `name` LIKE '$editteam' AND `id` != $edit_id");
      if($sqlTeamAlreadyExists->fetchColumn())
      {   
            $errors[] = 'This team is already in list';
      }
      else
      {  
            
            $SQLupdate = $odb -> prepare("UPDATE teams SET `name` = :team_name,`color_code` = :color_code, `updated_at` = :updated_at WHERE id = :id");
            $SQLupdate -> execute(array(':team_name' => $editteam, ':color_code' => $favcolor, ':updated_at' => DATETIME, ':id' => $edit_id));

            $user->addRecentActivities($odb,'edit_team',' Modified the team ('.$editteam.') on the platform.');
      }
   } 
}

if( isset($_POST['change_status']) )
{
   $change_status = $_POST['change_status'];
   $id = $_POST['id'];
   $status = 1;
   if($change_status == 'active')
   {
      $status = 0;
   }

   $updateTeamStatusSql = $odb->prepare("UPDATE team_status SET `status` = :status WHERE id = :id");
   $updateTeamStatusSql->execute(array(':status' => $status, ':id' => $id));
}
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
				   <div class="btn_new_user">
					   <button type="button" class="use_btn">Create New User</button>
					   </div>
                  <div class="table-reponsive box">
                     <table id="example" class="table table-striped table-bordered">
                        <thead>
                           <tr>
                           <th>Teams</th>
                           <th>Color</th>
                           <th>code</th>
                           <th>Actions</th>
                           </tr>
                        </thead>
                        <tbody>
			<?php 
             $getUserDetailIdWise = $user->getUserDetailIdWise($odb); 
             $college_id = $getUserDetailIdWise['college_id']; 

			
            try
            {
               $SQLGetTeams = $odb -> query("SELECT teams.*,team_status.team_code,team_status.status as teamStatus,team_status.id as teamStatusId FROM `teams` INNER JOIN team_status ON teams.id = team_status.team_id WHERE teams.name != 'Admin' AND teams.name != 'Administrative Assistant' AND  team_status.college_id = $college_id ORDER BY id ASC");
               $SQLGetTeams -> execute();
               $teamsR = $SQLGetTeams -> fetchAll(PDO::FETCH_ASSOC);  
            }  
            catch (\Exception $e) {                   
                $errors[] = $e; 
            }
           
            if(!empty($teamsR)){
               foreach ($teamsR as $key => $getInfo) {
               $id = $getInfo['id'];
               $teamname = $getInfo['name'];
               $color_code = $getInfo['color_code'];
               $team_code = $getInfo['team_code'];
               $teamStatus = $getInfo['teamStatus'];
               $teamStatusId = $getInfo['teamStatusId'];
                              
               echo '<tr class="gradeA"><td>'.$teamname.'</td><td><input type="color" disabled  value="'.$color_code.'"></td><td>'.$team_code.'</td>';

                   echo '<td width="70px">';
                   if($teamStatus)
                   {
                       echo '<a href="javascript:void(0)" onClick="activeDeacTeam('.$teamStatusId.',\'active\')" ><button class="btn btn-outline-success  mb-2 me-4">Active</button></a>';
                   }
                   else
                   {
                       echo '<a href="javascript:void(0)" onClick="activeDeacTeam('.$teamStatusId.',\'inactive\')" ><button class="btn btn-outline-danger  mb-2 me-4">In Active</button></a>';
                   }
                   if ($user -> isAdmin($odb) && ($teamname != 'Red Team' && $teamname != 'Blue Team' && $teamname != 'Purple Team') ) {
                       
                       echo '<a href="javascript:void(0)" onClick="editTeam(\''.$teamname.'\','.$id.',\''.$color_code.'\')"><button class="btn btn-outline-warning  mb-2 me-4">Edit</button></a>';
                   }
                   echo  '</td></tr>';
             }
            }else{
               echo '<tr class=""><td valign="top" colspan="3" class="dataTables_empty" style="text-align:center;">There are currently no custom teams created,</td></tr>';
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

       <!-- start modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Team</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                  <svg> ... </svg>
                </button>
              </div>
              <form method="post">
                <div class="modal-body">
                    <div class="col-md-12">
                        <div class="form-group">
                        <label for="editteam"> Team</label>
                        <input type="text" class="form-control mb-3 title_criteria" id="editteam" name="editteam">
                        <input type="hidden" id="edit_id" name="edit_id"/>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                        <label for="editteam"> Pick Color</label>
                        <input type="color" class="form-control mb-3 title_criteria" id="favcolor" name="favcolor">
                    </div>
                </div>
                <div class="modal-footer">
                    
                  <button type="submit" name="updateTeam" class="btn btn-outline-success btn-lrg">
                    <i class="flaticon-cancel-12"></i> Save </button>

                    <button type="button" class="btn btn-outline-warning btn-lrg cancel_assign" data-bs-dismiss="modal">
                    <i class="flaticon-cancel-12"></i> Cancel </button>
                </div>
              </form>
            </div>
          </div>
    </div>
    <!-- end modal -->
   </body>
   <?php require_once("./common/footer_script.php"); ?>
   <script>
       function editTeam(name,id,color)
        {   
            $('#editteam').val(name);
            $('#edit_id').val(id);
            $('#favcolor').val(color);
            $('#exampleModal').modal('show');
        }

        function activeDeacTeam(id,action)
        {
            var status;
            if(action == 'active')
            {
                status = "you want to deactivate this team"
            }
            else
            {
                status = "you want to activate this team"
            }

            Swal.fire({
            title: 'Are you sure?',
            text: status,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes!'
          }          
        )
        .then((result) => {
            if(result.isConfirmed){
              // function () {
                  // setTimeout(function () {
                        $.ajax({
                            url: "<?=BASEURL?>admin/manage_team.php",
                            type: "post",
                            data: {
                                change_status: action,
                                id : id
                            },
                            success: function (data) {
                            //   // var data = JSON.parse(data);
                            //   if(data.status)
                            //   {
                                Swal.fire(
                                    'Status Changed!'
                                  ).then((res)=>{
                                    if(res.isConfirmed)
                                    {
                                      location.reload();
                                    }
                                  })
                            //   }
                            }
                        });
                    // });
                // }
            }
        });

    }
   </script>
</html>