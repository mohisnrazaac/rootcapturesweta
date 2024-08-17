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

if ($user -> isAdmin($odb)) {

} else {
	header('location: ../index.php');
	die();
}

$editId =  $_GET['editId'];

/*$allUser = $user->getAllUserList($odb);
$all = array();
$allSelect = array();
if(!empty($allUser)){
	foreach ($allUser as $key => $value) {
		$all[$value['username']] = $value['ID'];
	}
}*/

if(isset($_POST['api_name'])){
	  $api_name = $_POST['api_name'];
    $api_key = $_POST['api_key'];
    $assign_members = $_POST['assign_members'];
    $api_function = $_POST['api_function'];

    if ( !$api_key ){
        $errors[] = 'Please add api key'; 
    } 

    if ( !$api_name ){
        $errors[] = 'Please add api name'; 
    } 

    if ( !$api_function ){
        $errors[] = 'Please select api function'; 
    } 

    if(empty($assign_members)){
        $errors[] = 'Please assign members'; 
    }

    if (empty($errors)){
    	/*foreach ($assign_members as $key => $value) {
    		$allSelect[] = $all[$value];
    	}*/

      $SQLupdate = $odb -> prepare("UPDATE `api_management` SET `api_name` = :api_name, `api_key` = :api_key, `api_members` = :api_members, `api_function` = :api_function, `timestamp` = :tstamp WHERE api_id = :id");

      $SQLupdate -> execute(array(':api_name' => $api_name, ':api_key' => $api_key, ':api_members' => implode(",", $assign_members), ':api_function' => $api_function, ':tstamp' => time(), ':id' => $editId));

    } 
}

$getUserDetailIdWise = $user->getUserDetailIdWise($odb); 
$college_id = $getUserDetailIdWise['college_id']; 

$teamList = $user->getTeamList($odb,$college_id);

$SQLApi = $odb -> prepare("SELECT * FROM `api_management` WHERE `api_id` = :editId");
$SQLApi -> execute(array(':editId' => $editId));
$api = $SQLApi -> fetch(PDO::FETCH_ASSOC); 
$pageTitle = 'Create Apis';
require_once '../header.php';


?>
<link rel="stylesheet" type="text/css" href="../src/plugins/css/light/vanillaSelectBox/custom-vanillaSelectBox.css">
<style>
  .vsb-main{
    width: 100%;
  }
  .regenerate_key{
    cursor: pointer;
  }
  .prevent-select {
    -webkit-user-select: none; /* Safari */
    -ms-user-select: none; /* IE 10 and IE 11 */
    user-select: none; /* Standard syntax */
  }
</style>
<!--  BEGIN CUSTOM STYLE FILE  -->
<!-- <link rel="stylesheet" type="text/css" href="../src/plugins/src/vanillaSelectBox/vanillaSelectBox.css"><link rel="stylesheet" type="text/css" href="../src/plugins/css/light/vanillaSelectBox/custom-vanillaSelectBox.css"><link rel="stylesheet" type="text/css" href="../src/plugins/css/dark/vanillaSelectBox/custom-vanillaSelectBox.css"> -->
<!--  END CUSTOM STYLE FILE  -->
<!--  BEGIN MAIN CONTAINER  -->
<div class="main-container" id="container">
  <div class="overlay"></div>
  <div class="search-overlay"></div>
  <!--  BEGIN SIDEBAR  --> <?php include '../sidebar.php'; ?>
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
              <li class="breadcrumb-item">Manage APIs</li>
              <li class="breadcrumb-item active" aria-current="page">Create API</li>
            </ol>
          </nav>
        </div>
        <br>
        <!-- /BREADCRUMB -->
        <div class="col-lg-12 col-12 layout-spacing">
          <div class="row mb-3">
            <div class="col-md-12">
              <h2>Create API</h2>
              <div class="animated-underline-content">
                <ul class="nav nav-tabs" id="animateLine" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="animated-underline-home-tab" data-bs-toggle="tab" href="#animated-underline-home" role="tab" aria-controls="animated-underline-home" aria-selected="true">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                      </svg> Create API </a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div class="tab-content" id="animateLineContent-4">
            <div class="tab-pane fade show active" id="animated-underline-home" role="tabpanel" aria-labelledby="animated-underline-home-tab">  
            	<?php 
			        if (isset($_POST['api_name'])){
			            if(empty($errors)) {
			                echo '<div class="message" id="message"><p><strong>SUCCESS: The API has been added! You are now being redirected to the API Management Platform.</strong></div><meta http-equiv="refresh" content="4;url='.BASEURL.'/admin/manage_apis.php">';
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
                  <form class="section general-info add_api_form" method="POST">
                    <div class="info">
                      <div align="Center">
                        <h6 class="">Create API</h6>
                        <div class="row">
                          <div class="col-lg-11 mx-auto">
                            <div class="row">
                              <div class="col-xl-10 col-lg-12 col-md-8 mt-md-0 mt-4">
                                <div class="form">
                                  <div class="row">
                                    <div class="col-md-6 prevent-select">
                                      <div class="form-group">
                                        <label for="titleAdd"> Api Name</label>
                                        <input type="text" class="form-control mb-3 api_name required" name="api_name" placeholder="API Name" error="Please enter api name" value="<?php if(isset($api['api_name'])) { echo $api['api_name']; } ?>">
                                      </div>
                                    </div>
                                    <div class="col-md-6 prevent-select">
                                      <div class="form-group">
                                        <label for="descAdd">Api Key
                                            <span class="regenerate_key" title="Generate a Key">
                                              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-clockwise" viewBox="0 0 16 16">
                                                <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z"/>
                                                <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z"/>
                                              </svg>
                                            </span>
                                        </label>
                                        <input type="text" class="form-control mb-3 api_key required" name="api_key" readonly  placeholder="API Key" error="Please enter api key"  value="<?php if(isset($api['api_key'])) { echo $api['api_key']; } ?>">
                                        
                                      </div>
                                    </div>
                                    <?php 
                                    	if(!empty($teamList)){ 
                                    		$options = '';
                                        $api_members = array();
                                        if(isset($api['api_members'])){
                                          $api_members = explode(",", $api['api_members']);
                                        }
                                        
                                    		foreach ($teamList as $key => $value) {
                                    			$users = $user->getATeamMembers($odb,$value['id'],$college_id);
                                    			if(!empty($users)){
                                    				$options .= '<optgroup label="'.$value['name'].'">';
                                    				foreach ($users as $k => $v) {
                                              $sel = '';
                                              if(in_array($v['ID'], $api_members)){
                                                $sel = 'selected';
                                              }
                                    					$options .= '<option '.$sel.' value="'.$v['ID'].'">'.$v['username'].'</option>';
                                    				}
                                    				$options .= '</optgroup>';													  
                                    			}
                                    		}
                                    	}
                                    ?>
                                    <div class="col-md-6">
                                      <div class="form-group  mb-3">
                                        <label for="titleAdd" class="assign_membersOption">Assign Members</label>
            						                <select class="form-control mb-3 assign_members" name="assign_members[]" multiple >
            						                   <?php echo $options; ?>
            						                </select>
                                      </div>
                                    </div>

                                    <div class="col-md-6">
                                      <div class="form-group  mb-3">
                                        <label for="titleAdd" class="functionOption">API Function</label>
            						                <select class="form-control mb-3 api_function" name="api_function">
            						                   <option <?php if(isset($api['api_function']) && $api['api_function']=="Get The Grade(s)") { echo "selected"; } ?>>Get The Grade(s)</option>
            						                   <option <?php if(isset($api['api_function']) && $api['api_function']=="Add The Grade(s)") { echo "selected"; } ?>>Add The Grade(s)</option>
            						                   <option <?php if(isset($api['api_function']) && $api['api_function']=="Edit The Grade(s)") { echo "selected"; } ?>>Edit The Grade(s)</option>
            						                   <option <?php if(isset($api['api_function']) && $api['api_function']=="Delete The Grade(s)") { echo "selected"; } ?>>Delete The Grade(s)</option>
            						                   <option <?php if(isset($api['api_function']) && $api['api_function']=="Get Grading Rubric Criterion") { echo "selected"; } ?>>Get Grading Rubric Criterion</option>
            						                   <option <?php if(isset($api['api_function']) && $api['api_function']=="Add Grading Rubric Criterion") { echo "selected"; } ?>>Add Grading Rubric Criterion</option>
            						                   <option <?php if(isset($api['api_function']) && $api['api_function']=="Edit Grading Rubric Criterion") { echo "selected"; } ?>>Edit Grading Rubric Criterion</option>
            						                   <option <?php if(isset($api['api_function']) && $api['api_function']=="Delete Grading Rubric Criterion") { echo "selected"; } ?>>Delete Grading Rubric Criterion</option>
            						                </select>
                                      </div>
                                    </div>
                                    <div class="col-md-6 mt-4">
                                    	<div class="errorMsg"></div>
                                    </div>
                                    <div class="col-md-6 mt-4">
                                      <div class="form-group text-end">
                                        <input type="hidden" name="api_id" value="<?php echo $_GET['editId']; ?>">
                                        <input type="hidden" name="college_id" value="<?php echo $college_id; ?>">
                                        <input type="submit" id="add_api" name="add_api" class="btn btn-outline-success btn-lrg">
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
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php require_once '../includes/footer-section.php'; ?>
  </div>
  <!--  END CONTENT AREA  -->
</div>
<!-- END MAIN CONTAINER --> <?php  //require_once '../footer.php'; ?>
<!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
<?php require_once '../footer.php'; ?>
<script>
  	$(document).ready(function() {

  		$(document).on("click",".regenerate_key",function(e){
  			var key = makeid(15);
  			$(".api_key").val(key);
  		});

  		$(document).on("click","#add_api",function(e){
  			e.preventDefault();
  			var error = 0;
  			$(".required").each(function(index){
  				if($(this).val()==''){
                    error++;
                    $('.errorMsg').html('<div class="alert alert-danger" role="alert">'+$(this).attr("error")+'</div>');
                    removeErrorMsg();
                }
  			});
  			var cust = $(".assign_members option:selected").map(function () {
                return $(this).val();
            }).get().join(',');

            var cust1 = $(".api_function option:selected").map(function () {
                return $(this).val();
            }).get().join(',');

            if(cust==''){
            	error++;
                $('.errorMsg').html('<div class="alert alert-danger" role="alert">Please assign users to api</div>');
                removeErrorMsg();
            }

            if(cust1==''){
            	error++;
                $('.errorMsg').html('<div class="alert alert-danger" role="alert">Please assign a function to api</div>');
                removeErrorMsg();
            }

            if(error==0){
            	$(".add_api_form").submit();
            }
  		});
  	});

  	let selectBox = new vanillaSelectBox(".assign_members", {
	    "search": true,
	    translations: { "all": "All", "items": "Selected" },
	    "placeHolder": "Choose..." 
  	});

  	let api_function = new vanillaSelectBox(".api_function", {
	    "keepInlineStyles":true,
	    "search": true,
	    "placeHolder": "Choose..." 
	});

	function removeErrorMsg(){
        setTimeout(function(){ $(".errorMsg").html(''); }, 2000);
    }

  	function makeid(length) {
	    var result           = '';
	    var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
	    var charactersLength = characters.length;
	    for ( var i = 0; i < length; i++ ) {
	        result += characters.charAt(Math.floor(Math.random() * charactersLength));
	    }
	    return result;
	}
</script>
</body>
</html>