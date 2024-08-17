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
$teamList =   $odb -> query("SELECT teams.* FROM `teams` INNER JOIN team_status ON teams.id = team_status.team_id WHERE teams.name != 'Admin' AND teams.name != 'Administrative Assistant' AND team_status.team_id AND team_status.college_id = $college_id AND team_status.status = 1 ORDER BY id ASC")->fetchAll(); 

$userList  = $odb -> query("SELECT * FROM `users` WHERE rank > 2  AND college_id = $college_id")->fetchAll(); 

$pageTitle = 'Add A Quiz';
require('common/header.php') 
?>
 
	
	
<div class="row special_btn_long_menu">

<ul class="d-flex flex-sm-row flex-column align-items-center justify-content-center" style="width: 100%;"> 

        <li class=""><a class="top_menu_item_long_menu">Add a Quiz</a></li> 
    </ul>  	
    
      
</div>




<div class="container centralize_container pt-5 " >
<ul class="nav nav-pills   justify-content-center" id="pills-tab" role="tablist">
<li class="nav-item" role="presentation">
<button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true"> 
    <?php if( isset($errors)){ echo $errors; } else{ echo 'Add A Quiz'; } ?>
    </button>
</li>

</ul>
<div class="tab-content" id="pills-tabContent">
<div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">

<form id="form" onsubmit="javascript: return process();" method="POST">

        <div class="p-5 row">
            <div class="col-6 px-5">
                <div class="mb-3">
                    <label for="quize" class="form-label">Quize Name <span id="groupNameErr" style="color:red" ></span> </label>
                    <input type="text" class="form-control" id="quize" name="quize" placeholder="Write your quize name here">
                </div>
            </div>
            <div class="col-6 px-5">
                <div class="mb-3">
					<label for="is_mandatory" class="form-label"> Is mandatory </label>
					<select  id="is_mandatory" name="is_mandatory" class="form-select">
                        <option value=""> Is mandatory</option>
                        <option value="yes">Yes</option>
                        <option value="no">No</option>
					</select>

				</div>
            </div>

            <div class="col-6 px-5">
                <div class="mb-3">
					<label for="team_user" class="form-label">Team/User</label>
					<select  id="team_user" onchange="checkTeamUser(this.value)" name="team_user" class="form-select">
                        <option value=""> Please Select </option>
                            <option value="">Choose Team/Users</option>
                            <option value="team" selected >Team</option>
                            <option value="users">Users</option>
					</select>

				</div>
            </div>

            <div class="col-6 px-5" id="teamCheck" style="display:block" >
                <div class="mb-3">
					<label for="assign_team" class="form-label"> Assigned Team </label>
					<select  id="assign_team" name="assign_team" class="form-select">
                        <option value=""> Please Select Team</option>
                        <?php foreach ($teamList as $teamListV) { 
                            echo '<option value="'.$teamListV['id'].'"> '.$teamListV['name'].' </option>'; 
                        } ?>
					</select>

				</div>
            </div>

            <div class="col-6 px-5" id="userCheck" style="display:none">
                <div class="mb-3">
					<label for="assignUser" class="form-label">Team/User</label>
					<select  id="assignUser"  name="assignUser[]" multiple size="2" class="form-select">
                        <?php foreach ($userList as $userv) { 
                            echo '<option onclick="assignUGrade('.$userv['ID'].')"  value="'.$userv['ID'].'">'.$userv['username'].'</option>'; 
                        } ?>
					</select>

				</div>
            </div>
        </div>
        <div class="p-5 row" id="dynamic_field">
            <div class="row">
                <div class="col-6 px-5">
                    <div class="mb-3">
                        <label for="questions" class="form-label">Question 1 <span id="groupNameErr" style="color:red" ></span> </label>
                        <input type="text" class="form-control"  name="question[1]" placeholder="Write your question here">
                    </div>
                </div>

                <div class="col-3 px-5">
                    <div class="mb-3">
                        <label  class="form-label">Option Type</label>
                        <select  onchange="getOptions(this.value,1)" name="options[1]" class="form-select">
                            <option value=""> Please Select </option>
                                <option value="">Choose Team/Users</option>
                                <option value="team">Team</option>
                                <option value="users">Users</option>
                        </select>
                    </div>
                </div>

                <div class="col-3 px-5">
                    <div class="mb-3">
                        <label  class="form-label"></label>
                        <button type="button" name="add" id="add" class="rc-btn submit-button">Add More</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab_footer d-flex flex-row align-items-center justify-content-center  ">
            <button type="submit" name="submitAssetGroup" class="rc-btn submit-button">Create Quiz</button>
        </div>
</form>
                
</div> 
</div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script> 
  <?php require('common/footer.php') ?>
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

    function process()
    {
        $('#form').submit();
    }

    function checkTeamUser(val)
    {
        if( val == 'team' ){
            console.log("a");
            $("#userCheck").css("display", "none");
            $("#teamCheck").css("display", "block");
        }else if(val == 'users'){
            console.log("bz");
            $("#teamCheck").css("display", "none");
            $("#userCheck").css("display", "block");
        }
        else
        {
            $("#userCheck").css("display", "none");
            $("#teamCheck").css("display", "none");
        }
    }
  </script>