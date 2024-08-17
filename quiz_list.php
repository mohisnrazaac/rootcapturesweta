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

// if is purple or is red or is blue or is admin or is assistant



$pageTitle = 'Quiz List';
require_once 'header.php';


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
                                <li class="breadcrumb-item">Cyber Range</li>
                                <li class="breadcrumb-item active" aria-current="page">Quiz List</li>
                            </ol>
                        </nav>
                    </div>
                    <!-- /BREADCRUMB -->
                    <div class="row layout-top-spacing">
                    
                        <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                            <div class="widget-content widget-content-area br-8">
                                <table id="zero-config" class="table dt-table-hover" style="width:100%">
                                    <thead>
                                        <tr>
                <th scope="col">Topic</th>
                <th class="text-center" scope="col">Mandatory</th>
				<th class="text-center" scope="col">Post Date</th>
				<th class="text-center" scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
				<?php

                $userIdS =  $_SESSION['ID'];
                $getUserDetailIdWise = $user->getUserDetailIdWise($odb); 
                $college_id = $getUserDetailIdWise['college_id']; 
                $team = $getUserDetailIdWise['rank'];

				$SQLGetAssets = $odb -> query("SELECT * FROM quize where (FIND_IN_SET($userIdS,assign_users) OR assign_team = $team ) and status = 2");
				$SQLGetAssets -> execute();
				$quizData =  $SQLGetAssets -> fetchAll(PDO::FETCH_ASSOC);
				if(!empty($quizData)){
					foreach ($quizData as $key => $getInfo)
					{

                        $qID = $getInfo['id'];
                        $checkQuizSubmission = $odb -> query("SELECT sum(is_correct) as correct, avg(is_correct) as avgcorrect, quiz_submission.status as sub_status FROM quiz_submission where created_by = $userIdS AND quize_id = $qID");
                        $checkQuizSubmission -> execute();
                        $quizSubmission =  $checkQuizSubmission -> fetchAll(PDO::FETCH_ASSOC);
                        $correctAns = null;
                       
						
						$id = base64_encode($getInfo['id']);
						$is_mandatory = $getInfo['is_mandatory'] ?? 'No';
						$created_at = date("d-m-Y", strtotime($getInfo['created_at']));
						$name = $getInfo['name'];
						echo '<tr><td>' . $name  . '</td><td><center>' . $is_mandatory . '</center></td><td><center>' . $created_at .'</center></td>';
                        if(isset($quizSubmission[0]['correct'])){
                            $correctAns = $quizSubmission[0]['correct'];
                            $avgcorrect = $quizSubmission[0]['avgcorrect'] *100;

                            $sub_status = $quizSubmission[0]['sub_status'];

                            if($sub_status == 0){
                                echo '<td>Pending</td>';
                            }elseif($sub_status == 2){
                                echo '<td>Rejected</td>';
                            }else{
                                echo '<td>'.$avgcorrect.'%  Score('.$correctAns.')</td>';
                            }
                           
                        }else{
                            echo '<td><a href="quiz.php?quize='.$id.'" ><button class="btn btn-outline-success  mb-2 me-4">Start Quiz</button></a> '.$correctAns.'</td>';
                        }
                        
                        echo '</tr>';
					}
				}else{
					echo '<tr class=""><td valign="top" colspan="4" class="dataTables_empty" style="text-align:center;">There is no quiz available in list.</td></tr>';
				}
					
				?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!--  BEGIN FOOTER  -->
            <?php require_once 'includes/footer-section.php'; ?>
            <!--  END FOOTER  -->
            <!--  END CONTENT AREA  -->
        </div>
        <!--  END CONTENT AREA  -->
    </div>
    <!-- END MAIN CONTAINER -->
    <?php  require_once 'footer.php'; ?>
 
    <script>
        $('#zero-config').DataTable({
            "dom": "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l><'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>>>" +
        "<'table-responsive'tr>" +
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