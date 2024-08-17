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

    
    $gradeList = $user->gradeList($odb); //print_r($gradeList); exit;
    $defaultTeam = $odb -> query("SELECT teams.name FROM `teams` INNER JOIN team_status ON teams.id = team_status.team_id WHERE team_status.college_id = $college_id AND team_status.status = 1 AND (teams.name = 'Red Team' OR teams.name = 'Blue Team' OR teams.name = 'Purple team')")->fetchAll();
   
    $userList  = $odb -> query("SELECT * FROM `users` WHERE rank > 2  AND college_id = $college_id")->fetchAll();

    if (isset($_POST['title']))
    {
        $assignGradeUser = array();
        $title = $_POST['title'];
        $detail = $_POST['detail'];
        $redTeam = $_POST['redTeam'];
        $purpleTeam = $_POST['purpleTeam'];
        $blueTeam = $_POST['blueTeam'];
        $assignUser = $_POST['assignUser']; 
        $assignGradeUser = $_POST['assignGradeUser'];

        $errors = array();
        if ( empty($title) || empty($detail) )
        {
            $errors[] = 'Please verify all fields'; 
        } 

        $grade = false;
        if( $redTeam != '' ){ $grade = true;} 
        else if( $purpleTeam != '' ) { $grade = true; }
        else if( $blueTeam != '' ) { $grade = true; }

        if ( !$grade )
        {
            $errors[] = 'Please select grades'; 
        } 

        if(empty($assignUser) && !$grade){
            $errors[] = 'Please assign users'; 
        }

        if(!empty($assignUser)){
            $et = 0;
            for ($i=0; $i <count($assignUser) ; $i++) { 
                if(!isset($assignGradeUser[$assignUser[$i]]) && $assignGradeUser[$assignUser[$i]]==''){
                    $et++; 
                }
            }
            if($et>0){
                $errors[] = 'Please select grades to all users'; 
            }
        }


        if (empty($errors))
        { 
            try{
            $SQLinsert = $odb -> prepare("INSERT INTO `grading_rubric_criteria` (college_id,title, detail, redteam_grade, blueteam_grade, purpleteam_grade,assigned_user)  VALUES($college_id,:title, :detail, :redteam_grade, :blueteam_grade, :purpleteam_grade,:assigned_user)");
            $SQLinsert -> execute(array(':title' => $title, ':detail' => $detail, ':redteam_grade' => $redTeam, ':blueteam_grade' => $blueTeam, ':purpleteam_grade' => $purpleTeam,':assigned_user'=>implode(",", $assignUser)));
            }
            catch (\Exception $e) {
                $errors[] = $e;
                // echo $e; exit;
            }
            
            $last_id = $odb->lastInsertId(); 

            foreach($assignGradeUser as $key => $assignGradeUserV)
            {
                $SQLinsert = $odb -> prepare("INSERT INTO `grading_rubric_criteria_user` (grading_rubric_criteria_id, user_id, grade)  VALUES(:last_id, :user_id, :grade)");
                $SQLinsert -> execute(array(':last_id' => $last_id, ':user_id' => $key, ':grade' => $assignGradeUserV));
            }

        }
       
    }

    $pageTitle = 'Add Rubric Grade';
    require_once '../header.php';
?>

    <link rel="stylesheet" type="text/css" href="../src/plugins/css/light/vanillaSelectBox/custom-vanillaSelectBox.css">


        <!--  BEGIN CUSTOM STYLE FILE  -->
    <!-- <link rel="stylesheet" type="text/css" href="../src/plugins/src/vanillaSelectBox/vanillaSelectBox.css">
    
   
    <link rel="stylesheet" type="text/css" href="../src/plugins/css/light/vanillaSelectBox/custom-vanillaSelectBox.css">

    
    <link rel="stylesheet" type="text/css" href="../src/plugins/css/dark/vanillaSelectBox/custom-vanillaSelectBox.css"> -->
    <!--  END CUSTOM STYLE FILE  -->
 

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
                                <li class="breadcrumb-item">Manage Grading Rubric</li>
                                <li class="breadcrumb-item active" aria-current="page">Add Graded Criterion</li>
                            </ol>
                        </nav>
                    </div>
                    <br>
                    <!-- /BREADCRUMB -->
                       <div class="col-lg-12 col-12 layout-spacing">

                        <div class="row mb-3">
                                <div class="col-md-12">
                                    <h2>Add Graded Criterion</h2>
        
                                    <div class="animated-underline-content">
                                        <ul class="nav nav-tabs" id="animateLine" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" id="animated-underline-home-tab" data-bs-toggle="tab" href="#animated-underline-home" role="tab" aria-controls="animated-underline-home" aria-selected="true"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg> Add Graded Criterion</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-content" id="animateLineContent-4">
                                <div class="tab-pane fade show active" id="animated-underline-home" role="tabpanel" aria-labelledby="animated-underline-home-tab">
        <?php 
        if (isset($_POST['title']))
        {
            if(empty($errors)) {
                echo '<div class="message" id="message"><p><strong>SUCCESS: The grading criteria has been added! You are now being redirected to the Rubric Management Platform.</strong></div><meta http-equiv="refresh" content="4;url=https://rootcapture.com/admin/manage-grading-rubric.php">';
                
                $title = '';
                $detail = '';
                $redTeam = '';
                $blueTeam = '';
                $purpleTeam = '';
            } else {
                echo '<div class="error" id="message"><p><strong>ERROR: </strong>';
                foreach($errors as $error) {
                    echo ''.$error.'<br />';
                }
                echo '</div>';
            }
            
        }
        
        ?>

<div class="row">
<div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
<form class="section general-info grading_rubric_form" method="POST">
<div class="info"> 
    <div align="Center">
        <h6 class="">Add Graded Criterion</h6>
<div class="row">
 <div class="col-lg-11 mx-auto">
    <div class="row">
<div class="col-xl-10 col-lg-12 col-md-8 mt-md-0 mt-4">

<div class="form">
<div class="row">



   
        <div class="col-md-6">
            <div class="form-group">
            <label for="titleAdd"> Title of Criteria</label>
            <input type="text" class="form-control mb-3 title_criteria" name="title">
        </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
             <label for="descAdd">Title of Criteria Description</label>
         <textarea class="form-control mb-3 description" name="detail" rows="2"></textarea>
        </div>
        </div>
        
        <?php foreach ($defaultTeam as $defaultTeamV) {
            if( $defaultTeamV['name'] == 'Red Team' ){ ?>
        <div class="col-md-6">
            <div class="form-group  mb-3">
                <label for="redTeam">Red Team</label>
                <select class="form-control" name="redTeam" id="selectCust" >
                    <option value=""> Please Select Grade </option>
                <?php foreach ($gradeList as $grade) { 
                        echo '<option value="'.$grade['grade'].'"> '.$grade['grade'].' </option>'; 
                    } ?>
                </select>
            </div>
        </div>
        <?php  } 
        else if($defaultTeamV['name'] == 'Purple Team'){ ?>
        <div class="col-md-6">
            <div class="form-group  mb-3">
                <label for="purpleTeam">Purple Team</label>
                <select class="form-control " name="purpleTeam" id="selectCust1" >
                    <option value=""> Please Select Grade </option>
                    <?php foreach ($gradeList as $grade) { 
                        echo '<option value="'.$grade['grade'].'"> '.$grade['grade'].' </option>'; 
                    } ?>
                </select>
            </div>
        </div>
        <?php } 
        else if( $defaultTeamV['name'] == 'Blue Team' ){ ?>
        <div class="col-md-6">
            <div class="form-group  mb-3">
            <label for="blueTeam">Blue Team</label>
            <select class="form-control" name="blueTeam" id="selectCust2" >
                <option value=""> Please Select Grade </option>
                <?php foreach ($gradeList as $grade) { 
                    echo '<option value="'.$grade['grade'].'"> '.$grade['grade'].' </option>'; 
                } ?>
            </select>
            </div>
        </div>
         <?php }} ?>       

        <div class="col-md-6">            
            <div id="withGroups" class="optGroup">

                         <div class="form-group  mb-3">
            <label for="blueTeam">Assigned User</label>
                        <select id="frameworkCMS" name="assignUser[]"  multiple size="2" >
                        <!-- <option value=""> Please Select User </option> -->
                        <?php foreach ($userList as $userv) { 
                            echo '<option onclick="assignUGrade('.$userv['ID'].')"  value="'.$userv['ID'].'">'.$userv['username'].'</option>'; 
                        } ?>
                        </select>
                    </div>
              
              
            </div>
            
        </div>

        <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Assign Grades To User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                  <svg> ... </svg>
                </button>
              </div>
              <form method="post">
                <div class="modal-body body-assign-ugrade">
                    
                </div>
                <div class="modal-footer">
                    
                  <button type="button" class="btn btn-outline-success btn-lrg" data-bs-dismiss="modal">
                    <i class="flaticon-cancel-12"></i> Save </button>

                    <button type="button" class="btn btn-outline-warning btn-lrg cancel_assign" data-bs-dismiss="modal">
                    <i class="flaticon-cancel-12"></i> Cancel </button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <!-- Modal Ends -->
   
    <div class="col-md-6 mt-4">
        <div class="errorMsg">
            
        </div>
    </div>
    <div class="col-md-6 mt-4">
        <div class="form-group text-end">          
          <!-- <input type="button" id="assignGradeUser" value="Assign grade to User" class="btn btn-outline-warning btn-lrg"> -->
          <input type="hidden" id="contenttype" value="click">
          <input type="submit" id="gradeing_rubic_submit" name="submitRubric" class="btn btn-outline-success btn-lrg">
        </div>
    </div>



</div>
    </div>
    </div>
    </div>
    </div>
</div>
</div>
</div>
</form>
</div></div>




                               
                                </div>
                            </div>
                        </div>
                </div>

            </div>

           <?php require_once '../includes/footer-section.php'; ?>
        </div>
        <!--  END CONTENT AREA  -->
    </div>
    <!-- END MAIN CONTAINER -->
    
    <?php  //require_once '../footer.php'; ?>
    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script>
        var currentevent = '';
    </script>  
    <script src="../src/plugins/src/global/vendors.min.js"></script>
    <script src="../src/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../src/plugins/src/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="../src/plugins/src/mousetrap/mousetrap.min.js"></script>
    <script src="../layouts/vertical-dark-menu/app.js"></script>
    <script src="../src/plugins/src/vanillaSelectBox/vanillaSelectBox.js"></script>
    <script src="../src/plugins/src/vanillaSelectBox/custom-vanillaSelectBox.js"></script>
    <script src="../src/assets/js/custom.js"></script>
     <script src="../src/plugins/src/sweetalerts2/sweetalerts2.min.js"></script>
    <script src="../src/assets/js/custom.js"></script>
    <script>
        //setTimeout(function(){ alert('dddd'); $(".multi li").trigger("click"); }, 2000);
        
        <?php foreach ($gradeList as $grade) { 
            $gradeOptions .= '<option value="'.$grade['grade'].'"> '.$grade['grade'].' </option>'; 
        } ?>

        var gradeOptions = '<?=$gradeOptions?>';

        function isNumberKey(evt){
                var charCode = (evt.which) ? evt.which : evt.keyCode
                if (charCode > 31 && (charCode < 48 || charCode > 57))
                    return false;
                return true;
        }

        $(document).ready(function() {
            $('#phone').blur( function(){
                var phone = $('#phone').val(); 
                if (!(phone.length >= 10 && phone.length <= 14) ) { 
                    $('#phone').val('');
                    $('#phone').attr('placeholder','Phone length cannot be less than 10 or greater than 14 digit.');
                }
            } );

            $('#gradeing_rubic_submit').click(function(e){ 
                e.preventDefault();
                var error =0;
                var cust = $("#selectCust option:selected").map(function () {
                    return $(this).val();
                }).get().join(',');

                var cust1 = $("#selectCust1 option:selected").map(function () {
                    return $(this).val();
                }).get().join(',');

                var cust2 = $("#selectCust2 option:selected").map(function () {
                    return $(this).val();
                }).get().join(',');

                var option_all = $("#frameworkCMS option:selected").map(function () {
                    return $(this).text();
                }).get().join(',');

                if(cust=='' && cust1=='' && cust2=='' && option_all==''){
                    error++;
                    $('.errorMsg').html('<div class="alert alert-danger" role="alert">Please select a team or users option.</div>');
                    removeErrorMsg();
                }


                if(option_all!=''){
                    if($(".assign_grade_user").length==0){
                        error++;
                        $('.errorMsg').html('<div class="alert alert-danger" role="alert">Please Assign grade to all users.</div>');
                        removeErrorMsg();
                    }
                }

                $(".assign_grade_user").each(function(){
                    if($(this).val()==''){
                        error++;
                        $('.errorMsg').html('<div class="alert alert-danger" role="alert">Please Assign grade to all users.</div>');
                        removeErrorMsg();
                    }
                });

                if($(".description").val()==''){
                    error++;
                    $('.errorMsg').html('<div class="alert alert-danger" role="alert">Please enter description.</div>');
                    removeErrorMsg();
                }

                if($(".title_criteria").val()==''){
                    error++;
                    $('.errorMsg').html('<div class="alert alert-danger" role="alert">Please enter title of criteria.</div>');
                    removeErrorMsg();
                }


                if(error==0){
                    $(".grading_rubric_form").submit();
                }
            });


            $('#assignGradeUser').click(function(){ 
                var option_all = $("#frameworkCMS option:selected").map(function () {
                    return $(this).text();
                }).get().join(',');
                if(option_all==''){
                    $('.errorMsg').html('<div class="alert alert-danger" role="alert">Please select user to assign grade.</div>');
                    removeErrorMsg();
                }else{
                    var selected = option_all.split(",");
                    var selectedIds = $('#frameworkCMS').val();

                    var htmlVal = '';
                    $.each(selected,function(i){
                        if($('.gradeuserwrapper'+selectedIds[i]).length==0){
                            htmlVal += '<div class="gradeuserwrapper'+selectedIds[i]+'"><span>'+selected[i]+'</span> <select class="form-control assign_grade_user" name="assignGradeUser['+selectedIds[i]+']"> <option value=""> Please Select Grade </option>'+gradeOptions+'</select></div>'; 
                        }
                    });

                    $('.modal-body').append(htmlVal);
                    $('#exampleModal').modal('show');
                }               
            });

            $('.cancel_assign').click(function(e){
                cancelAssign();
            });

        });

        function removeErrorMsg(){
            setTimeout(function(){ $(".errorMsg").html(''); }, 2000);
        }

//         selectBox = new vanillaSelectBox("#selectCust", {
//     "keepInlineStyles":true,
//     "maxHeight": 200,
//     "minWidth":481,
//     "search": true,
//     "placeHolder": "Choose..." 
// });
//                 selectBox = new vanillaSelectBox("#selectCust2", {
//     "keepInlineStyles":true,
//     "maxHeight": 200,
//     "minWidth":481,
//     "search": true,
//     "placeHolder": "Choose..." 
// });
//                 selectBox = new vanillaSelectBox("#selectCust1", {
//     "keepInlineStyles":true,
//     "maxHeight": 200,
//     "minWidth":481,
//     "search": true,
//     "placeHolder": "Choose..." 
// });
    let frameworkCMS = new vanillaSelectBox("#frameworkCMS", {
    "maxHeight": 200,
    "search": true,
    translations: { "all": "All", "items": "Selected" },
    "minWidth":481,
    "placeHolder": "Choose..." 
});

    function assignUGrade(user_id=0){
        assignSelectGradeNew('click',user_id);
        currentevent = user_id;
    }

    function cancelAssign(){
        $('.multi li[data-value="'+currentevent+'"]').trigger("click");
    }

    function assignSelectGradeNew(status='',user_id=0){
        setTimeout(function(){ 
            var option_all = $("#frameworkCMS option:selected").map(function () {
                return $(this).text();
            }).get().join(',');

            var selected = option_all.split(",");
            var selectedIds = $('#frameworkCMS').val();
            if ($('.gradeuserwrapper'+user_id).length>0){
               $('.gradeuserwrapper'+user_id).remove();
            }else{
                if(option_all!=''){
                    var htmlVal = '';
                    $.each(selected,function(i){
                        if(selectedIds[i]==user_id){
                            htmlVal += '<div class="gradeuserwrapper'+selectedIds[i]+'"><span>'+selected[i]+'</span> <select class="form-control assign_grade_user" id="assigngd'+selectedIds[i]+'" name="assignGradeUser['+selectedIds[i]+']"> <option value=""> Please Select Grade </option>'+gradeOptions+'</select></div>'; 
                        }
                    });

                    $('.modal-body').append(htmlVal);
                    if(status!='load'){
                       $('#exampleModal').modal('show'); 
                    }
                }
                
            }
        }, 100);
    }

    </script>
        <!-- <script src="../src/plugins/src/vanillaSelectBox/vanillaSelectBox.js"></script>
    <script src="../src/plugins/src/vanillaSelectBox/custom-vanillaSelectBox.js"></script> -->
</body>
</html>