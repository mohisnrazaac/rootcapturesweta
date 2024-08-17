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

    $getUserDetailIdWise = $user->getUserDetailIdWise($odb); 
    $college_id = $getUserDetailIdWise['college_id']; 
    $userIdS =  $_SESSION['ID'];

    $SQLGetQuize = $odb -> query("SELECT * from quize where created_by = $userIdS ORDER BY id ASC");
    $SQLGetQuize -> execute();
    $teamsR = $SQLGetQuize -> fetchAll(PDO::FETCH_ASSOC);


    $title = 'Quiz Management';
    require_once('common/header.php'); 
    
?>

<!-- page constent -->

   
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.3/css/dataTables.bootstrap5.css" />

   
<div class="row special_btn_long_menu">
			<ul class="d-flex flex-sm-row flex-column align-items-center justify-content-center" style="width: 100%;"> 
					<li class=""><a class="top_menu_item_long_menu" href="<?=BASEURL?>dashboard/create_quize.php">Create New Quiz</a></li> 
			</ul>  	 
	</div>

<div class="p-4"></div>

<div class="container px-5"> 
</head>
<body>

<div class="main-container mt-5">
    <span class="cyber_range_heading_bg">Cyber Range / <span class="primary-color">Quiz Management</span></span>
</div>
	
	
  <div class="main_announcement container ">
     
      <div style="margin-bottom: 1px">
        <label class="cyber_range_bg" for="editor"><i>&nbsp;</i></label> 
      </div>   
	  
  
 
 

	  
	   <table id="rc_table" class="table table-striped nowrap" style="width:100%">
        <thead>
            <tr>
                <th>Name</th>
                <th>Is Mandatory</th>
                <th>Assign</th>
                <th>Status</th>
                <th style="text-align: right">Action</th> 
            </tr>
        </thead>
        <tbody> 
		<?php
        if(!empty($teamsR))
        {
            foreach ($teamsR as $key => $getInfo)
            {
                $id = $getInfo['id'];
                $teamname = $getInfo['name'];
                $mamnd = $getInfo['is_mandatory'];
                $team_user = $getInfo['team_user'];
                
                $teamStatus = $getInfo['status'];
                $teamStatusId = $getInfo['id'];
                $statusName = 'INACTIVE';
                if($teamStatus == 1){
                    $statusName = 'ACTIVE';
                }elseif($teamStatus == 2){
                    $statusName = 'PUBLISHED';
                }
        ?>
            <tr>
                <td><?php echo $teamname; ?></td>
                <td><?php echo $mamnd; ?></td>
                <td><?php echo $team_user; ?></td>
                <td><?php echo $statusName; ?></td>
                <td>
                    <div class="d-flex flex-row-reverse">
                    <a href="https://rootcapture.com/dashboard/view_quizes.php?quize=<?=$id?>" ><button class="btn btn-outline-success  mb-2 me-4">View</button></a>

                    <a href="https://rootcapture.com/dashboard/quiz_played.php?quize=<?=base64_encode($id)?>" ><button class="btn btn-outline-success  mb-2 me-4">Played</button></a>

                    <a href="https://rootcapture.com/dashboard/edit_quize.php?quize=<?=base64_encode($id)?>" ><button class="btn btn-outline-warning  mb-2 me-4">Edit</button></a>
                <?php
                    if($teamStatus == 1)
                    {
                        
                        echo '<a href="javascript:void(0)" onClick="activeDeacTeam('.$teamStatusId.',\'active\')" ><button class="btn btn-outline-danger  mb-2 me-4">In Active</button></a>';
                    }
                    elseif($teamStatus == 2){
                       
                        echo '<a href="javascript:void(0)" onClick="activeDeacTeam('.$teamStatusId.',\'inactive\')" ><button class="btn btn-outline-danger  mb-2 me-4">In Active</button></a>'; 
                    }else
                    {
                        echo '<a href="javascript:void(0)" onClick="activeDeacTeam('.$teamStatusId.',\'inactive\')" ><button class="btn btn-outline-success  mb-2 me-4">Active</button></a>'; 
                        
                    }
                ?>
                    </div>     
                </td>
            </tr> 
        <?php
            }
         }
         else
         {
            echo '<tr>
                    <td colspan="4">
                        There are currently no asset group created.
                    </td>
                  </tr>';
         }
        ?>
        </tbody> 
    </table>
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
	    new DataTable('#rc_table');
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
                            url: "<?=BASEURL?>admin/manage_quizes.php",
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
  

<!-- page conctent -->

<?php require_once('common/footer.php') ?>

