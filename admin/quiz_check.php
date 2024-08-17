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


$pageTitle = 'View Quizes';
require_once '../header.php';



    if(isset($_POST['ok'])){
        
        $quiz_id = base64_decode($_GET['quize']);
        $user = base64_decode($_GET['user']);
        $status = 1;
        $updateStatus = $odb->prepare("UPDATE quiz_submission SET `status` = :status WHERE quize_id = :quize_id AND created_by = :created_by");
        $updateStatus->execute(array(':status' => $status, ':quize_id' => $quiz_id, 'created_by' => $user));
        header("Refresh:0");

    }

    if(isset($_POST['Reject'])){
       
        $quiz_id = base64_decode($_GET['quize']);
        $user = base64_decode($_GET['user']);
        $status = 2;
        $updateStatus = $odb->prepare("UPDATE quiz_submission SET `status` = :status WHERE quize_id = :quize_id AND created_by = :created_by");
        $updateStatus->execute(array(':status' => $status, ':quize_id' => $quiz_id, 'created_by' => $user));
        header("Refresh:0");

    }

?>
    


    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container" id="container">

        <div class="overlay"></div>
        <div class="search-overlay"></div>

        <!--  BEGIN SIDEBAR  --> 
<?php include '../sidebar.php'; ?>
        <!--  END SIDEBAR  -->

        <?php

            $getUserDetailIdWise = $user->getUserDetailIdWise($odb); 
            $college_id = $getUserDetailIdWise['college_id']; 
            $userIdS =  $_SESSION['ID'];
            $quize_id = base64_decode($_GET['quize']);
            $userSubmit = base64_decode($_GET['user']);

            $SQLGetQuize = $odb -> query("SELECT * from quize where id = $quize_id ORDER BY id ASC");
            $SQLGetQuize -> execute();
            $teamsR = $SQLGetQuize -> fetchAll(PDO::FETCH_ASSOC);

            $getVideoQuery = $odb -> query("SELECT * from quiz_submit_video where quize_id = $quize_id AND  `user_id` = $userSubmit ORDER BY id ASC");
            $getVideoQuery -> execute();
            $getVideo = $getVideoQuery -> fetchAll(PDO::FETCH_ASSOC);

            

        ?>

        <!--  BEGIN CONTENT AREA  -->
        <div id="content" class="main-content">
            <div class="layout-px-spacing">

                <div class="middle-content container-xxl p-0">

                    <!-- BREADCRUMB -->
                    <div class="page-meta">
                        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">Manage Quize</li>
                                <li class="breadcrumb-item active" aria-current="page">View Quize</li>
                            </ol>
                            <br>
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>Title : <?php 
                                        if(isset($teamsR[0]['name'])){
                                            echo $teamsR[0]['name'];
                                        }
                                        
                                    ?></h4>
                                </div>
                                <div class="col-md-6">
                                    <button type="button" style="float:right" id="videoOpen" class="btn btn-secondary" data-dismiss="modal">Check Video</button>
                                </div>
                            </div>
                           
                             
                        </nav>
                        
                        <div class="modal" tabindex="-1" role="dialog" id="videoModal">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Recording</h5>
                                </div>
                                <div class="modal-body">
                                    <?php
                                        if(isset($getVideo[0]['video_link'])){
                                            $video = $getVideo[0]['video_link'];
                                            echo '<video width="400" controls>
                                            <source src="../'.$video.'" >
                                            No video
                                        </video>';
                                        }
                                    ?>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" id="videoClose" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                                </div>
                            </div>
                        </div>

                        

                    </div>
                    <!-- /BREADCRUMB -->
                    <div class="row layout-top-spacing">
		<!-- <div class="alert alert-arrow-right alert-icon-right alert-light-warning alert-dismissible fade show mb-4" role="alert">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12" y2="16"></line></svg>
                                        <strong><b>Warning!</b></strong> It is important to note that refreshing your Cyber Range will result in the removal of all users except your Administrative User.
                                    </div> -->
     
                        <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                            <div class="widget-content widget-content-area br-8 ">
                                <div class="center-block fix-width scroll-inner">
                                <div class="table-responsive">
                                <table id="zero-config" class="table dt-table-hover tablenoscroll" style="width:100%">
                                    <thead>
                                        <tr>
                <th scope="col" class="col-md-2">Question</th>
                <th scope="col" class="col-md-2">Option 1</th>
                <th scope="col" class="col-md-2">Option 2</th>
                <th scope="col" class="col-md-2">Option 3</th>
                <th scope="col" class="col-md-2">Option 4</th>
                <th scope="col" class="col-md-2">Correct Option</th>
                <th scope="col" class="col-md-2">Selected Option</th>
                                        </tr>
                                    </thead>
                                    <tbody>
            <?php
             

          
            $getQuizeDetail = $odb -> query("SELECT quize_question.*,quiz_submission.choose_option,is_correct,quiz_submission.status as sub_status from quize_question INNER JOIN quiz_submission on quiz_submission.question_id = quize_question.id where quiz_submission.quize_id = $quize_id AND  quiz_submission.created_by = $userSubmit ORDER BY id ASC");
            $getQuizeDetail -> execute();
            $quizeDetail = $getQuizeDetail -> fetchAll(PDO::FETCH_ASSOC);

			
            if(!empty($quizeDetail)){
                foreach ($quizeDetail as $key => $getInfo) {
    				$id = $getInfo['id'];
    				$question = $getInfo['question'];
    				$option1 = $getInfo['option1'];
    				$option2 = $getInfo['option2'];
    				$option3 = $getInfo['option3'];
    				$option4 = $getInfo['option4'];
    				
    				$correct = $getInfo['correct_answer'];
    				$choose_option = $getInfo['choose_option'];
                    $is_correct = $getInfo['is_correct'];
                    
                   
    				     
    				echo '<tr class="gradeA"><td>'.$question.'</td><td>'.$option1.'</td>';
    				echo '<td>'.$option2.'</td><td>'.$option3.'</td>';
    				echo '<td>'.$option4.'</td><td>'.$correct.'</td> ';
                    if($is_correct == 1){
                        echo '<td> <span class="label label-success"> '.$choose_option.' </span></td>';
                    }else{
                        echo '<td> <span class="label label-danger"> '.$choose_option.' </span></td>';
                    }
                   
                    echo  '</tr>';
                
			     }
                 if($getInfo['sub_status'] == 0){
                    echo '<tr class=""><td valign="top" colspan="6" class="dataTables_empty" style="text-align:center;">
                    <form method="POST" action="">
                    <input type="submit" class="btn btn-success" name="ok" value="ok"> &nbsp
                    <input type="submit" class="btn btn-danger" name="Reject" value="Reject">
                    </form>
                    </td></tr>';
                 }else if($getInfo['sub_status'] == 1){
                    echo '<tr class=""><td valign="top" colspan="6" class="dataTables_empty" style="text-align:center;">
                        Approved
                    </td></tr>';
                 }else if($getInfo['sub_status'] == 2){
                    echo '<tr class=""><td valign="top" colspan="6" class="dataTables_empty" style="text-align:center;">
                        Rejected
                    </td></tr>';
                 }
                    

                 
                 
             }else{
                echo '<tr class=""><td valign="top" colspan="6" class="dataTables_empty" style="text-align:center;">There are currently no Question</td></tr>';
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

<script type="text/javascript">
                            $(window).on('load', function() {
                                $('#videoModal').modal('hide');
                            });
                            $(document).on('click','#videoOpen', function() {
                                $('#videoModal').modal('show');
                            });
                            $(document).on('click','#videoClose', function() {
                                $('#videoModal').modal('hide');
                            });
                            
                        </script>
    <!-- END PAGE LEVEL SCRIPTS -->

</body>
</html>