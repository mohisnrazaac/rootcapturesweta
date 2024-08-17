<?php
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

if ($user -> isAdmin($odb)) {

} else {
	header('location: ../index.php');
	die();
}

    $gradeList = $user->gradeList($odb); 
    $userList  = $odb -> query("SELECT * FROM `users` WHERE rank > 2")->fetchAll();

    $editId =  $_GET['editId'];

    if (isset($_POST['title']))
    {    
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
            $SQLupdate = $odb -> prepare("UPDATE grading_rubric_criteria SET `title` = :title, `detail` = :detail, `redteam_grade` = :redteam_grade, `blueteam_grade` = :blueteam_grade, `purpleteam_grade` = :purpleteam_grade, `assigned_user` = :assigned_user WHERE id = :id");
            $SQLupdate -> execute(array(':title' => $title, ':detail' => $detail, ':redteam_grade' => $redTeam, ':blueteam_grade' => $blueTeam, ':purpleteam_grade' => $purpleTeam, ':assigned_user' => implode(',',$assignUser), ':id' => $editId));

            $SQL = $odb -> prepare("DELETE FROM `grading_rubric_criteria_user` WHERE `grading_rubric_criteria_id` = :id");
            $SQL -> execute(array(':id' => $editId));

            foreach($assignGradeUser as $key => $assignGradeUserV)
            {
                $SQLinsert = $odb -> prepare("INSERT INTO `grading_rubric_criteria_user` (grading_rubric_criteria_id, user_id, grade)  VALUES(:last_id, :user_id, :grade)");
                $SQLinsert -> execute(array(':last_id' => $editId, ':user_id' => $key, ':grade' => $assignGradeUserV));
            }

        }
       
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>rootCapture - Edit Grading Rubric </title>
      <link rel="icon" type="image/x-icon" href="../src/assets/img/favicon.ico"/>
    <link href="../layouts/vertical-dark-menu/css/light/loader.css" rel="stylesheet" type="text/css" />
    <link href="../layouts/vertical-dark-menu/css/dark/loader.css" rel="stylesheet" type="text/css" />
    <script src="../layouts/vertical-dark-menu/loader.js"></script>
    <link href="../css/alter.css" rel="stylesheet" type="text/css" />
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="../src/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="../layouts/vertical-dark-menu/css/light/plugins.css" rel="stylesheet" type="text/css" />
    <link href="../layouts/vertical-dark-menu/css/dark/plugins.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->

    <!-- BEGIN PAGE LEVEL STYLES -->
    <link rel="stylesheet" type="text/css" href="../src/plugins/src/table/datatable/datatables.css">
    <link rel="stylesheet" type="text/css" href="../src/plugins/src/vanillaSelectBox/vanillaSelectBox.css">
<link rel="stylesheet" type="text/css" href="../src/plugins/css/dark/vanillaSelectBox/custom-vanillaSelectBox.css">
 <link rel="stylesheet" type="text/css" href="../src/assets/css/dark/forms/switches.css">
 <link rel="stylesheet" type="text/css" href="../src/assets/css/light/forms/switches.css">
  <link href="../src/assets/css/dark/components/tabs.css" rel="stylesheet" type="text/css">
  <link href="../src/assets/css/light/components/tabs.css" rel="stylesheet" type="text/css">
    <link href="../src/assets/css/dark/users/account-setting.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="../src/plugins/src/sweetalerts2/sweetalerts2.css">
     <link rel="stylesheet" type="text/css" href="../src/assets/css/light/elements/alert.css">
      <link rel="stylesheet" type="text/css" href="../src/assets/css/dark/elements/alert.css">
        <link href="../src/plugins/css/dark/sweetalerts2/custom-sweetalert.css" rel="stylesheet" type="text/css" />
        <link href="../src/assets/css/light/users/account-setting.css" rel="stylesheet" type="text/css" />
    
    <link href="../src/plugins/css/light/sweetalerts2/custom-sweetalert.css" rel="stylesheet" type="text/css" />
    
    <link rel="stylesheet" type="text/css" href="../src/plugins/css/light/table/datatable/dt-global_style.css">
    <link rel="stylesheet" type="text/css" href="../src/plugins/css/dark/table/datatable/dt-global_style.css">
     <!--  BEGIN CUSTOM STYLE FILE  -->
   
    <link rel="stylesheet" type="text/css" href="../src/plugins/css/light/vanillaSelectBox/custom-vanillaSelectBox.css">

    
    <!--  END CUSTOM STYLE FILE  -->
    <!-- END PAGE LEVEL STYLES -->

</head>
<body class="layout-boxed">
    <!-- BEGIN LOADER -->
    <div id="load_screen"> <div class="loader"> <div class="loader-content">
        <div class="spinner-grow align-self-center"></div>
    </div></div></div>
    <!--  END LOADER -->

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
                                <li class="breadcrumb-item active" aria-current="page">Edit Graded Criterion</li>
                            </ol>
                        </nav>
                    </div>
                    <br>
                    <!-- /BREADCRUMB -->
                       <div class="col-lg-12 col-12 layout-spacing">

                        <div class="row mb-3">
                                <div class="col-md-12">
                                    <h2>Edit Graded Criterion</h2>
        
                                    <div class="animated-underline-content">
                                        <ul class="nav nav-tabs" id="animateLine" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" id="animated-underline-home-tab" data-bs-toggle="tab" href="#animated-underline-home" role="tab" aria-controls="animated-underline-home" aria-selected="true"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg> Edit Graded Criterion</a>
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
                echo '<div class="message" id="message"><p><strong>SUCCESS: THE GRADING CRITERION HAS BEEN ADDED! YOU ARE NOW BEING REDIRECTED TO THE RUBRIC MANAGEMENT PLATFORM.</strong></div><meta http-equiv="refresh" content="4;url=https://rootcapture.com/admin/manage-grading-rubric.php">';
                
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

        // Get Rubric Data id wise
            
        $SQLgetRubric = $odb -> prepare("SELECT * FROM `grading_rubric_criteria` WHERE `id` = :editId");
        $SQLgetRubric -> execute(array(':editId' => $editId));
        $rubricInfo = $SQLgetRubric -> fetch(PDO::FETCH_ASSOC); 

        $SQLuserGrade = $odb -> prepare("SELECT * FROM `grading_rubric_criteria_user` WHERE `grading_rubric_criteria_id` = :editId");
        $SQLuserGrade -> execute(array(':editId' => $editId));
        $userGrade = $SQLuserGrade -> fetchAll(PDO::FETCH_ASSOC);
        
        $editTitle = '';
        $editdetail = '';
        $editredteam_grade = '';
        $editblueteam_grade = '';
        $editpurpleteam_grade = '';
        $edit_assigned_user = '';

        if(!empty($rubricInfo))
        {    
            $editTitle = $rubricInfo['title'];
            $editdetail = $rubricInfo['detail'];
            $editredteam_grade = $rubricInfo['redteam_grade'];
            $editblueteam_grade = $rubricInfo['blueteam_grade'];
            $editpurpleteam_grade = $rubricInfo['purpleteam_grade'];
            $edit_assigned_user = $rubricInfo['assigned_user'];
            
        }
        else
        {
            echo '<div class="error" id="message"><p><strong>ERROR: </strong>Something went wrong</p></div>';
        }
        
        ?>

<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
    <form class=" section general-info grading_rubric_form" method="POST">
    <div class="info"> 
        <div align="Center">
            <h6 class="">Edit Grading Rubric</h6>
    <div class="row">
    <div class="col-lg-11 mx-auto">
        <div class="row">
    <div class="col-xl-10 col-lg-12 col-md-8 mt-md-0 mt-4">

    <div class="form">
    <div class="row">



   
        <div class="col-md-6">
            <div class="form-group">
            <label for="titleAdd"> Title of Criteria</label>
            <input type="text" class="form-control mb-3" value="<?=$editTitle?>" name="title">
        </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
             <label for="descAdd">Title of Criteria Description</label>
         <textarea class="form-control mb-3" name="detail" rows="2"><?=$editdetail?></textarea>
        </div>
        </div>
    
     
        <div class="col-md-6">
            <div class="form-group  mb-3">
            <label for="redTeam">Red Team</label>
            <select class="form-control" name="redTeam" id="selectCust" >
                <option value=""> Please Select Grade </option>
               <?php foreach ($gradeList as $grade) { ?>
                   <option value="<?=$grade['grade']?>" <?php if( $grade['grade']== $editredteam_grade ) echo 'selected'; ?> > <?=$grade['grade']?> </option>
                <?php } ?>
            </select>
        </div>
        </div>
        <div class="col-md-6">
            <div class="form-group  mb-3">
            <label for="purpleTeam">Purple Team</label>
             <select class="form-control " name="purpleTeam" id="selectCust1" >
                <option value=""> Please Select Grade </option>
                <?php foreach ($gradeList as $grade) { ?>
                    <option value="<?=$grade['grade']?>" <?php if( $grade['grade']== $editpurpleteam_grade ) echo 'selected'; ?>> <?=$grade['grade']?> </option>
                <?php } ?>
            </select>
        </div>
        </div>
   
     
        <div class="col-md-6">
            <div class="form-group  mb-3">
            <label for="blueTeam">Blue Team</label>
            <select class="form-control" name="blueTeam" id="selectCust2" >
                <option value=""> Please Select Grade </option>
                <?php foreach ($gradeList as $grade) { ?>
                    <option value="<?=$grade['grade']?>" <?php if( $grade['grade']== $editpurpleteam_grade ) echo 'selected'; ?>> <?=$grade['grade']?> </option> 
                <?php } ?>
            </select>
        </div>
    </div>

    <div class="col-md-6">            
    <div id="withGroups" class="col-lg-12 layout-spacing optGroup">
                            <div class="statbox widget box box-shadow">
                                <div class="widget-header">
                                    <div class="row">
                                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                            <h4>Assign User</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="widget-content widget-content-area">
                                    <select id="frameworkCMS" name="assignUser[]" multiple size="2" >
                                    <?php foreach ($userList as $userv) { ?>
                                        <option onclick="assignUGrade(<?php echo $userv['ID']; ?>)" value="<?=$userv['ID']?>" <?php if( in_array($userv['ID'],explode(',',$edit_assigned_user)) ){echo 'selected'; } ?>> <?=$userv['username']?></option>
                                    <?php } ?>
                                    </select>
                                    <?php if(!empty($userGrade)){ ?>
                                        <?php foreach($userGrade as $ukey=>$uvalue){ ?>
                                            <input type="hidden" class="oldassignuser" user_id="<?php echo $uvalue['user_id']; ?>" value="<?php echo $uvalue['grade']; ?>">
                                        <?php } ?>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
    </div>


        <div class="col-md-6">
            <div class="form-group">
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

        


</div>
<div class="row">
    <!-- Modal Ends -->
        <div class="col-md-6 mt-4">
            <div class="errorMsg">
                
            </div>
        </div>
        <div class="col-md-6 mt-4">
            <div class="form-group text-end">          
              <!-- <input type="button" id="assignGradeUser" value="Assign grade to User" class="btn btn-outline-warning btn-lrg"> -->
              <input type="hidden" id="contenttype" value="load">
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
            <!--  END CONTENT AREA  -->
        </div>
        <!--  END CONTENT AREA  -->
    </div>
    <!-- END MAIN CONTAINER -->
<script>
    var currentevent = '';
</script>  
<!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
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
<!-- END GLOBAL MANDATORY SCRIPTS -->
<script>

    

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
            assignSelectGrade();               
        });

        $('.cancel_assign').click(function(e){
            cancelAssign();
        });
    });

    function removeErrorMsg(){
        setTimeout(function(){ $(".errorMsg").html(''); }, 2000);
    }

    selectBox = new vanillaSelectBox("#selectCust", {
        "keepInlineStyles":true,
        "maxHeight": 200,
        "minWidth":481,
        "search": true,
        "placeHolder": "Choose..." 
    });
                    selectBox = new vanillaSelectBox("#selectCust2", {
        "keepInlineStyles":true,
        "maxHeight": 200,
        "minWidth":481,
        "search": true,
        "placeHolder": "Choose..." 
    });
                    selectBox = new vanillaSelectBox("#selectCust1", {
        "keepInlineStyles":true,
        "maxHeight": 200,
        "minWidth":481,
        "search": true,
        "placeHolder": "Choose..." 
    });

    let frameworkCMS = new vanillaSelectBox("#frameworkCMS", {
        "maxHeight": 200,
        "search": true,
        translations: { "all": "All", "items": "Selected" },
        "minWidth":481,
        "placeHolder": "Choose..." 
    });
    assignSelectGrade('load');
    function assignSelectGrade(status=''){
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
                    htmlVal += '<div class="gradeuserwrapper'+selectedIds[i]+'"><span>'+selected[i]+'</span> <select class="form-control assign_grade_user" id="assigngd'+selectedIds[i]+'" name="assignGradeUser['+selectedIds[i]+']"> <option value=""> Please Select Grade </option>'+gradeOptions+'</select></div>'; 
                }
            });

            $('.modal-body').append(htmlVal);
            assignSelectGradeUser();
            if(status!='load'){
               $('#exampleModal').modal('show'); 
            }
        }
    }
    function assignSelectGradeUser(){
        $(".oldassignuser").each(function(e){
            var user_id = $(this).attr("user_id");
            var gd = $(this).val();
            $("#assigngd"+user_id).val(gd);
            $("#assigngd"+user_id).attr("dddddd",'sassassa');
        });
    }

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
    
    setTimeout(function(){ 
        $("#contenttype").val('click');
    }, 500);

</script>
</body>
</html>