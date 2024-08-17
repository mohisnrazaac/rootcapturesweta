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

        $pageTitle = 'Create Quize';
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
                                <li class="breadcrumb-item">Teams</li>
                                <li class="breadcrumb-item active" aria-current="page">Create Quize</li>
                            </ol>
                        </nav>
                    </div>
					<br>
                    <!-- /BREADCRUMB -->
                       <div class="col-lg-12 col-12 layout-spacing">
                         <div class="row mb-3">
                                <div class="col-md-12">
                                    <h2>Create Quize</h2>
        
                                    <!-- <div class="animated-underline-content">
                                        <ul class="nav nav-tabs" id="animateLine" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" id="animated-underline-home-tab" data-bs-toggle="tab" href="#animated-underline-home" role="tab" aria-controls="animated-underline-home" aria-selected="true"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg> Add A Team</a>
                                            </li>
                                        </ul>
                                    </div> -->
                                </div>
                            </div>
                            <div class="tab-content" id="animateLineContent-4">
                                <div class="tab-pane fade show active" id="animated-underline-home" role="tabpanel" aria-labelledby="animated-underline-home-tab">
		<?php 
         $getUserDetailIdWise = $user->getUserDetailIdWise($odb); 
         $college_id = $getUserDetailIdWise['college_id']; 
        $teamList =   $odb -> query("SELECT teams.* FROM `teams` INNER JOIN team_status ON teams.id = team_status.team_id WHERE teams.name != 'Admin' AND teams.name != 'Administrative Assistant' AND team_status.team_id AND team_status.college_id = $college_id AND team_status.status = 1 ORDER BY id ASC")->fetchAll(); 
        
        $userList  = $odb -> query("SELECT * FROM `users` WHERE rank > 2  AND college_id = $college_id")->fetchAll();  
		if (isset($_POST['submitTeam']))
		{
           
            // Array ( [quize] => QUIZE 1 [question] => Array ( [1] => Q1 [2] => Q2 ) [options] => Array ( [1] => 2 [2] => 4 ) [option1] => Array ( [1] => Q1O1 [2] => Q2O1 ) [option2] => Array ( [1] => Q1O2 [2] => Q2O2 ) [correct_option] => Array ( [1] => 1 [2] => 2 ) [option3] => Array ( [2] => Q3O3 ) [option4] => Array ( [2] => Q4O4 ) [submitTeam] => Submit )

            $quize = $_POST['quize'];
            $question = $_POST['question'];
            $created_at = date("Y-m-d h:i:s");
            $option1 = $_POST['option1'];
            $option2 = $_POST['option2'];
            $option3 = $_POST['option3'];
            $option4 = $_POST['option4'];
            $correct_option = $_POST['correct_option'];
            $is_mandatory = $_POST['is_mandatory'];
            $team_user = $_POST['team_user'];
            $assign_team = $_POST['assign_team'] ?? null;
            $assignUser = $_POST['assignUser'] ?? [];
            $assUser = implode(',',$assignUser);
            $errors = [];
            // print_r($_POST); exit;
            if($_POST['team_user'] == 'team' && empty($_POST['assign_team'])){
                $errors[] = 'Please select team';
            }else if($_POST['team_user'] == 'users' && empty($_POST['assignUser'])){

            }else{

                try{
                    $SQLinsert = $odb->prepare("INSERT INTO `quize`(`name`,`is_mandatory`,`team_user`,`assign_team`,`assign_users`, `status`, `created_by`,`college_id`, `created_at`) VALUES(:title,:is_mandatory,:team_user,:assign_team,:assign_users, :statuss, :created_by,:college_id, :created_at)");

                    $SQLinsert->execute(array(':title' => $quize,':is_mandatory' => $is_mandatory,':team_user' => $team_user,':assign_team' => $assign_team,':assign_users' => $assUser,':statuss' => 1, ':created_by' => $_SESSION['ID'],':college_id' => $college_id, ':created_at' => $created_at));
                }catch (\Exception $e) {
                    $errors[] = $e;
                
                }
                $last_id = $odb->lastInsertId(); 
    
                
                foreach($question as $key=>$value){
                    // print_r($correct_option[$key]);
                    $opt1 = $opt2 = $opt3 = $opt4 = $corr = null;
                    if(isset($correct_option[$key])){
                        $corr = $correct_option[$key];
                    }
                    if(isset($option1[$key])){
                        $opt1 = $option1[$key];
                    }
                    if(isset($option2[$key])){
                        $opt2 = $option2[$key];
                    }
                    if(isset($option3[$key])){
                        $opt3 = $option3[$key];
                    }
                    if(isset($option4[$key])){
                        $opt4 = $option4[$key];
                    }
    
                    try{
                        $SQLinserts = $odb->prepare("INSERT INTO `quize_question`(`quize_id`, `question`, `option1`, `option2`, `option3`, `option4`, `correct_answer`, `created_at`) VALUES(:last_id, :question, :option1,:option2,:option3,:option4,:correct_answer, :created_at)");
    
                        $SQLinserts->execute(array(':last_id' => $last_id, ':question' => $value,':option1'=>$opt1,':option2'=>$opt2,':option3'=>$opt3,':option4'=>$opt4,':correct_answer'=>$corr, ':created_at' => $created_at));
                    }catch (\Exception $e) {
                        $errors[] = $e;
                    
                    }
                    
    
                    // print_r($SQLinserts);
                   
                }

            }

            


			if(empty($errors)) {
				echo '<div class="message" id="message"><p><strong>SUCCESS: THE QUIZE HAS BEEN ADDED! YOU ARE NOW BEING REDIRECTED TO THE QUIZE MANAGEMENT PLATFORM.</strong></div><meta http-equiv="refresh" content="4;url='.BASEURL.'admin/manage_quizes.php">';
				
				$team = '';
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
                                        <!-- onsubmit="javascript: return process();" -->
                                    <form action="" class="section general-info"  method="POST">
                                       
                                      <div class="info">
                                                    <div align="Center">
                                                         <!-- <h6 class="">Add A Team</h6> -->

                                                        <div class="row">
 <div class="col-lg-11 mx-auto">
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 mt-md-0 mt-4">

                                                            <div class="row">



                                        <div class="col-md-12">
                                            <div class="form-group ">
                                                <label for="titleAdd">Quize Name</label>
                                                <input type="text" class="form-control mb-3" placeholder="Write your quize name here" name="quize" required>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="row">

                                                <div class="col-md-2">
                                                        <select class="form-control mb-3" name="is_mandatory"  required>
                                                            <option value=""> Is mandatory</option>
                                                            <option value="yes">Yes</option>
                                                            <option value="no">No</option>

                                                        </select>
                                                </div>
                                                <div class="col-md-2">
                                                        <select class="form-control mb-3"  name="team_user" onchange="checkTeamUser(this.value)" required>
                                                            <option value="">Choose Team/Users</option>
                                                            <option value="team">Team</option>
                                                            <option value="users">Users</option>

                                                        </select>
                                                </div>

                                                <div class="col-md-4">
                                                <div class="form-group d-none" id="teamCheck">
                                                    <!-- <label for="assign_team" class="custOption">System Team Assignment</label> -->
                                                    <select class="form-control mb-3" name="assign_team" id="assign_team" >
                                                        <option value=""> Please Select Team</option>
                                                        <?php foreach ($teamList as $teamListV) { 
                                                            echo '<option value="'.$teamListV['id'].'"> '.$teamListV['name'].' </option>'; 
                                                        } ?>
                                                    </select>
                                                </div>
                                           
                                            <div class="form-group d-none" id="userCheck">
                                            <!-- <label for="blueTeam"> Users</label> -->
                                                        <select id="frameworkCMS" name="assignUser[]"  multiple size="2" >
                                                        <!-- <option value=""> Please Select User </option> -->
                                                        <?php foreach ($userList as $userv) { 
                                                            echo '<option onclick="assignUGrade('.$userv['ID'].')"  value="'.$userv['ID'].'">'.$userv['username'].'</option>'; 
                                                        } ?>
                                                        </select>
                                                    </div>
                                            </div>


                                            </div>
                                         </div>
                                       
                                        <div id="dynamic_field">
                                            <div class="row">
                                                <div class="col-md-7">
                                                    <div class="form-group ">
                                                        <label for="titleAdd">Question 1</label>
                                                        <input type="text" class="form-control mb-3" placeholder="Write your question here" name="question[1]">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group ">
                                                        <label for="titleAdd">Option Type</label>
                                                        <select class="form-control mb-3" onchange="getOptions(this.value,1)" name="options[1]"> 
                                                            <option value=''>Please Select</option>
                                                            <option value='2'>2 Options</option>
                                                            <option value='4'>4 Options</option>

                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                <div class="form-group">
                                                    <br/>
                                                    <br/>
                                                <button type="button" name="add" id="add" class="btn btn-success">Add More</button>
                                                </div>
                                                </div>
                                                </div>
                                                <div class="col-md-12" id="options1">
                                                </div>
                                            </div>
                                            <br/>
                                            </div>

                                        

                                        
                                       <div class="col-md-12 mt-1">
                                        <div class="form-group text-end">
                                            <input type="submit" name="submitTeam" class="btn btn-outline-success btn-lrg">
                                        </div>
                                        </div>

                                    </div></div></div></div></div></div></div>
                                    </form>
                                   </div>
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
   
    <?php require_once '../footer.php'; ?>

    

    <script>
        function getOptions(val,inp){
        if(val == 2){
            $('#options'+inp).html(`<div class="row">
                                                <div class="col-md-2">
                                                <input type="text" class="form-control" placeholder="Option 1" name="option1[`+inp+`]">
                                                </div>
                                                <div class="col-md-2">
                                                <input type="text" class="form-control" placeholder="Option 2" name="option2[`+inp+`]">
                                                </div>
                                               
                                                <div class="col-md-2">
                                                <select class="form-control" name="correct_option[`+inp+`]"> 
                                                                <option value=''>Please Select</option>
                                                                <option value='1'>Option 1</option>
                                                                <option value='2'>Option 2</option>
                                                               

                                                            </select>
                                                </div>
                                                </div>`);
        }else{
            $('#options'+inp).html(`<div class="row">
                                                <div class="col-md-2">
                                                <input type="text" class="form-control" placeholder="Option 1" name="option1[`+inp+`]">
                                                </div>
                                                <div class="col-md-2">
                                                <input type="text" class="form-control" placeholder="Option 2" name="option2[`+inp+`]">
                                                </div>
                                                <div class="col-md-2">
                                                <input type="text" class="form-control" placeholder="Option 3" name="option3[`+inp+`]">
                                                </div>
                                                <div class="col-md-2">
                                                <input type="text" class="form-control" placeholder="Option 4" name="option4[`+inp+`]">
                                                </div>
                                                <div class="col-md-2">
                                                <select class="form-control" name="correct_option[`+inp+`]"> 
                                                                <option value=''>Please Select</option>
                                                                <option value='1'>Option 1</option>
                                                                <option value='2'>Option 2</option>
                                                                <option value='3'>Option 3</option>
                                                                <option value='4'>Option 4</option>

                                                            </select>
                                                </div>
                                                </div>`);
        }
            
    }
            function process()
            {
               if(!$('div.ql-blank').length)
               {
                    var content = $('div.ql-editor').html();
                    $('#detailAdd').val(content); 
               }
               else
               {
                $('#detailAdd').val(''); 
               }
                      
               return true;
            }
           

    </script>
    <script>

    $(document).ready(function(){
      var i=1;
      var j=1;
      $('#add').click(function(){
        i++;
        j++;
                                            

        $('#dynamic_field').append(`        
                                            <div class="row mb-2" id="row`+i+`">
                                                <div class="col-md-7 mb-2">
                                                    <div class="form-group ">
                                                        <label for="titleAdd">Question `+j+`</label>
                                                        <input type="text" class="form-control" placeholder="Write your question here" name="question[]">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group ">
                                                        <label for="titleAdd">Option Type</label>
                                                        <select class="form-control" onchange="getOptions(this.value,`+i+`)" name="options[]"> 
                                                            <option value=''>Please Select</option>
                                                            <option value='2'>2 Options</option>
                                                            <option value='4'>4 Options</option>

                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-2"> 
                                                <br/>
                                                <br/>
                                                <button type="button" name="remove" id="`+i+`" class="btn btn-danger btn_remove">X</button></div>
                                                <div class="col-md-12" id="options`+i+`">
                                                </div>
                                            
                                            </div>
                                            `);
      }); 
      
      $(document).on('click', '.btn_remove', function(){
        j--;
        var button_id = $(this).attr("id"); 
        $('#row'+button_id+'').remove();
      });


      



    });



    function checkTeamUser(val){
        $("#teamCheck").attr("class", "form-group d-none");
        $("#userCheck").attr("class", "form-group d-none");
        if(val =='team'){
            $("#teamCheck").attr("class", "form-group");
        }else if(val == 'users'){
            $("#userCheck").attr("class", "form-group");
        }
    }


    let frameworkCMS = new vanillaSelectBox("#frameworkCMS", {
    "maxHeight": 200,
    "search": true,
    translations: { "all": "All", "items": "Selected" },
    "minWidth":481,
    "placeHolder": "Choose Users..." 
});
   
    </script>


</body>
</html>