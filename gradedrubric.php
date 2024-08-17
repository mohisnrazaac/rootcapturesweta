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

if ($user -> isBlueTeam($odb) || $user -> isRedTeam($odb) || $user -> isPurpleTeam($odb) || $user -> isAssist($odb) || $user -> isAdmin($odb)) {

} else {
	header('location: index.php');
	die();
}

$pageTitle = 'Graded Rubric';
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
                                <li class="breadcrumb-item active" aria-current="page">Graded Rubric</li>
                            </ol>
                           
                        </nav>
                    </div>
                    <!-- /BREADCRUMB -->
                    <div class="row layout-top-spacing">
		
 
                        <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                            <div class="widget-content widget-content-area br-8">
                                <div class="table-responsive">
                                <table id="zero-grading" class="table dt-table-hover tablenoscroll" style="width:100%">
                                    <thead>
                                        <tr>
                <th scope="col" class="col-md-3">Title</th>
                <th class="text-center col-md-4" scope="col">Description</th>
                <th class="text-center col-md-5" scope="col">Team Assignment</th>
                                        </tr>
                                    </thead>
                                    <tbody>
               <?php 
               $loggedUserId = $user -> loggedUserDetail()['id'];
               if ($user -> isAdmin($odb) || $user -> isAssist($odb)) {
                  $getDataSql = "SELECT * FROM `grading_rubric_criteria` ORDER BY `ID` DESC";
               }
               else if( $user -> isBlueTeam($odb)){ 
                  $getDataSql = "SELECT * FROM `grading_rubric_criteria` WHERE (`blueteam_grade` IS NOT NULL AND `blueteam_grade` != '') OR FIND_IN_SET ($loggedUserId,`assigned_user`) ORDER BY `ID` DESC";
                }
               else if( $user -> isRedTeam($odb) ){ 
                  $getDataSql = "SELECT * FROM `grading_rubric_criteria` WHERE (`redteam_grade` IS NOT NULL AND `redteam_grade` != '') OR FIND_IN_SET ($loggedUserId,`assigned_user`) ORDER BY `ID` DESC";
                }
                else if( $user -> isPurpleTeam($odb) ){ 
                    $getDataSql = "SELECT * FROM `grading_rubric_criteria` WHERE (`purpleteam_grade` IS NOT NULL AND `purpleteam_grade` != '') OR FIND_IN_SET ($loggedUserId,`assigned_user`) ORDER BY `ID` DESC";
                }
              

			$SQLGetRubric = $odb -> query($getDataSql);
            $SQLGetRubric -> execute();
            $gradeR =  $SQLGetRubric -> fetchAll(PDO::FETCH_ASSOC);
            if(!empty($gradeR)){
    			foreach ($gradeR as $key => $getInfo) {
    				$id = $getInfo['id'];
    				$title = $getInfo['title'];
    				$detail = $getInfo['detail'];
                    $teamassignment = '';
                    if( $getInfo['redteam_grade'] ) {
                        $teamassignment .= '<span class="badge badge-light-danger">Red Team ('.$getInfo['redteam_grade'].')</span>';
                    }
                    if($getInfo['blueteam_grade']) {
                        $teamassignment .= ' <span class="badge badge-light-info">Blue Team ('.$getInfo['blueteam_grade'].')</span>';
                    }
                    if($getInfo['purpleteam_grade']){
                        $teamassignment .= ' <span class="badge badge-light-secondary">Purple Team ('.$getInfo['purpleteam_grade'].')</span>';
                    }
    				// $redteam_grade = ($getInfo['redteam_grade'])?$getInfo['redteam_grade']:'N/A';
    				// $blueteam_grade = ($getInfo['blueteam_grade'])?$getInfo['blueteam_grade']:'N/A';
    				// $purpleteam_grade = ($getInfo['purpleteam_grade'])?$getInfo['purpleteam_grade']:'N/A';
                  
    				
    				echo '<tr class="gradeA"><td>'.$title.'</td><td><center>'.$detail.'</center></td><td><center>'.$teamassignment.'</center></td></tr>';
    			}
            }else{
                echo '<tr class=""><td valign="top" colspan="4" class="dataTables_empty" style="text-align:center;">There is no grade rubic currently created.</td></tr>';
            }
			?>
			
                                    </tbody>
								</table>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!--  BEGIN FOOTER  -->
            <?php  require_once 'includes/footer-section.php'; ?>
            <!--  END FOOTER  -->
            <!--  END CONTENT AREA  -->
        </div>
        <!--  END CONTENT AREA  -->
    </div>
    <!-- END MAIN CONTAINER -->

    <?php  require_once 'footer.php'; ?>
     <script>
        $('#zero-grading').DataTable({
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
  
</body>
</html>