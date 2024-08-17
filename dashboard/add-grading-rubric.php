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
    header('location: https://rootcapture.com/index.php');
	die();
}

$getUserDetailIdWise = $user->getUserDetailIdWise($odb); 
$college_id = $getUserDetailIdWise['college_id']; 


$gradeList = $user->gradeList($odb); //print_r($gradeList); exit;
$defaultTeam = $odb -> query("SELECT teams.name FROM `teams` INNER JOIN team_status ON teams.id = team_status.team_id WHERE team_status.college_id = $college_id AND team_status.status = 1 AND (teams.name = 'Red Team' OR teams.name = 'Blue Team' OR teams.name = 'Purple team')")->fetchAll();

$userList  = $odb -> query("SELECT * FROM `users` WHERE rank > 2  AND college_id = $college_id")->fetchAll();



$pageTitle = 'Add Rubric Grade';
require('common/header.php') 
?>
 
	
	
<div class="row special_btn_long_menu">

<ul class="d-flex flex-sm-row flex-column align-items-center justify-content-center" style="width: 100%;"> 

        <li class=""><a class="top_menu_item_long_menu">Add Rubric Grade</a></li> 
    </ul>  	
    
      
</div>




<div class="container centralize_container pt-5 " >
<ul class="nav nav-pills   justify-content-center" id="pills-tab" role="tablist">
<li class="nav-item" role="presentation">
<button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true"> 
    <?php if( isset($errors)){ echo $errors; } else{ echo 'Add Rubric Grade'; } ?>
    </button>
</li>

</ul>
<div class="tab-content" id="pills-tabContent">
<div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">

<form id="form" onsubmit="javascript: return process();" method="POST">

        <div class="p-5 row">
            <div class="col-6 px-5">
                <div class="mb-3">
                    <label for="title" class="form-label">Title of Criteria <span id="groupNameErr" style="color:red" ></span> </label>
                    <input type="text" class="form-control" id="title" value="<?=$editname?>" name="title" placeholder="Write your title here">
                </div>
            </div>

            <div class="col-6 px-5">
                <div class="mb-3">
                    <label for="detail" class="form-label">Title of Criteria Description <span id="groupNameErr" style="color:red" ></span> </label>
                    <textarea type="text" class="form-control" id="detail" value="<?=$editname?>" name="detail"> </textarea>
                </div>
            </div>

            <?php foreach ($defaultTeam as $defaultTeamV) { 
                if( $defaultTeamV['name'] == 'Red Team' ){ ?>
                <div class="col-6 px-5">
                    <div class="mb-3">
                        <label for="redTeam" class="form-label">Red Team</label>
                        <select  id="redTeam" name="redTeam" class="form-select">
                        <option value=""> Please Select Grade </option>
                        <?php foreach ($gradeList as $grade) { 
                            echo '<option value="'.$grade['grade'].'"> '.$grade['grade'].' </option>'; 
                        } ?>
                        </select>
                    </div>
                </div>
                <?php } 
                else if( $defaultTeamV['name'] == 'Blue Team' ){ ?>
                     <div class="col-6 px-5">
                        <div class="mb-3">
                            <label for="blueTeam" class="form-label">Blue Team</label>
                            <select  id="blueTeam" name="blueTeam" class="form-select">
                            <option value=""> Please Select Grade </option>
                            <?php foreach ($gradeList as $grade) { 
                                echo '<option value="'.$grade['grade'].'"> '.$grade['grade'].' </option>'; 
                            } ?>
                            </select>
                        </div>
                    </div>
                    <?php  } 
                else if($defaultTeamV['name'] == 'Purple Team'){ ?>
                 <div class="col-6 px-5">
                        <div class="mb-3">
                            <label for="purpleTeam" class="form-label">Purple Team</label>
                            <select  id="purpleTeam" name="purpleTeam" class="form-select">
                                <option value=""> Please Select Grade </option>
                                <?php foreach ($gradeList as $grade) { 
                                    echo '<option value="'.$grade['grade'].'"> '.$grade['grade'].' </option>'; 
                                } ?>
                            </select>
                        </div>
                    </div>
            <?php }} ?>  

            <div class="col-6 px-5">
                <div class="mb-3">
					<label for="assign_team" class="form-label">System Team Assignment</label>
					<select  id="assign_team" name="assign_team" class="form-select">
                        <option value=""> Please Select </option>
                        <?php foreach ($teamList as $teamListV) { 
                         if($editteam == $teamListV['id']){ $select = 'selected';}else{$select = '';}
                        echo '<option value="'.$teamListV['id'].'" '.$select.' > '.$teamListV['name'].' </option>'; 
                    } ?>
					</select>

				</div>
            </div>
        </div>

        <div class="tab_footer d-flex flex-row align-items-center justify-content-center  ">
            <button type="submit" name="updateAssetGroup" class="rc-btn submit-button">Create</button>
        </div>
</form>
                
</div> 
</div>
</div>

  <?php require('common/footer.php') ?>
  <script>
    function process()
    {
    //    var team = $("#team").val();
    //    var favcolor = $("#favcolor").val();
    //    if(team == '')
    //    {
    //         $("#teamErr").text(" (Required)");
    //         $("#team").focus();
    //         return false;
    //    }
    //    else
    //    {
    //     if(favcolor == '')
    //     {
    //         $("#favcolorErr").text(" (Required)");
    //         $("#favcolor").focus();
    //         return false;
    //     }
    //     else
    //     {
    //         $('#form').submit();
    //     }
    //    }

    $('#form').submit();
    }
  </script>