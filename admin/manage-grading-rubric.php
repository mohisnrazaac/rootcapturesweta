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

$pageTitle = 'Cyber Range';
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
                                <li class="breadcrumb-item active" aria-current="page">Graded Rubric</li>
                            </ol>
                            <br>
                            <a class="btn btn-outline-success btn-lrg" href="https://rootcapture.com/admin/add-grading-rubric.php" role="button">Create Grading Rubric Criterion</a>

                        </nav>
                    </div>
                    <!-- /BREADCRUMB -->
                    <div class="row layout-top-spacing">
		
 
                        <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                            <div class="widget-content widget-content-area br-8">
                                <div class="table-responsive">
                                <table id="zero-config" class="table dt-table-hover tablenoscroll" style="width:100%">
                                    <thead>
                                        <tr>
                <th scope="col" class="col-md-2">Criterion</th>
                <th class="text-center col-md-4" scope="col">Description</th>
                <th class="text-center col-md-4" scope="col">Team Assignment</th>
                <th class="text-center col-md-2" scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
               <?php 
               $getUserDetailIdWise = $user->getUserDetailIdWise($odb); 
               $college_id = $getUserDetailIdWise['college_id']; 

                if (isset($_GET['deleteId']))
                {
                    $delete = $_GET['deleteId'];
                    //foreach($deletes as $delete)
                    //{
                        $SQL = $odb -> prepare("DELETE FROM `grading_rubric_criteria` WHERE `id` = :id LIMIT 1");
                        $SQL -> execute(array(':id' => $delete));
                    //}
                    echo '<div class="message" id="message"><p><strong>SUCCESS: </strong>The criteria has been deleted!</p></div>';
                }

			$SQLGetRubric = $odb -> query("SELECT * FROM `grading_rubric_criteria` WHERE college_id = $college_id ORDER BY `ID` DESC");
            $rubricR = $SQLGetRubric -> fetchAll(PDO::FETCH_ASSOC);
            if(!empty($rubricR)){
                foreach ($rubricR as $key => $getInfo) {
    				$id = $getInfo['id'];
    				$title = $getInfo['title'];
    				$detail = $getInfo['detail'];
                    $teamassignment = '';
                    if( $getInfo['redteam_grade'] ) {
                        $teamassignment .= '<span class="badge badge-light-danger mb-2">Red Team ('.$getInfo['redteam_grade'].')</span>';
                    }
                    if($getInfo['blueteam_grade']) {
                        $teamassignment .= ' <span class="badge badge-light-info  mb-2">Blue Team ('.$getInfo['blueteam_grade'].')</span>';
                    }
                    if($getInfo['purpleteam_grade']){
                        $teamassignment .= ' <span class="badge badge-light-secondary  mb-2">Purple Team ('.$getInfo['purpleteam_grade'].')</span>';
                    }

    				// $redteam_grade = ()?$getInfo['redteam_grade']:'N/A';
    				// $blueteam_grade = ()?$getInfo['blueteam_grade']:'N/A';
    				// $purpleteam_grade = ()?$getInfo['purpleteam_grade']:'N/A';
                  
    				if ( $user -> isAdmin($odb) || $user -> isAssist($odb) ) {
    				echo '<tr class="gradeA"><td>'.$title.'</td></td><td><center>'.$detail.'</center></td>
                    <td><center>'.$teamassignment.'</center></td><td width="70px">';
                        echo '<a href="https://rootcapture.com/admin/edit-grading-rubric.php?editId='.$id.'"><button class="btn btn-outline-success mb-2 me-4">Edit</button></a> <a href="https://rootcapture.com/admin/manage-grading-rubric.php?deleteId='.$id.'"><button class="btn btn-outline-danger mb-2 me-4" name="deleteBtn" value="'.$id.'" role="button" type="submit">Delete</button></a>';
                    }
                    echo '</td></tr>';
    			}
            }else{
                echo '<tr class=""><td valign="top" colspan="4" class="dataTables_empty" style="text-align:center;">There are currently no grading rubric created,</td></tr>';
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