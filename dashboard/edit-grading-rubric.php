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

$teamList =   $odb -> query("SELECT teams.* FROM `teams` INNER JOIN team_status ON teams.id = team_status.team_id WHERE teams.name != 'Admin' AND teams.name != 'Administrative Assistant' AND team_status.team_id AND team_status.college_id = $college_id AND team_status.status = 1 ORDER BY id ASC")->fetchAll();

$editId =  $_GET['editId']; 
$SQLget = $odb -> prepare("SELECT * FROM `asset_group` WHERE `id` = :editId");
$SQLget -> execute(array(':editId' => $editId));
$Info = $SQLget -> fetch(PDO::FETCH_ASSOC); 

if(!empty($Info))
{
    $editname = $Info['name'];  
    $editoperating_system = $Info['operating_system'];
    $editteam = $Info['team'];
}
else
{
    header('location: https://rootcapture.com/errordocs.php');
	die();
}

if (isset($_POST['updateAssetGroup']))
{
   
    $group_name = $_POST['group_name'];
    $operating_system = $_POST['operating_system'];
    $assign_team = $_POST['assign_team'];

    if (empty($group_name) || empty($operating_system) || empty($assign_team))
    {
        $errors = 'Please fill all fields'; 
    }
    else
    {
        $checkAssetGroupExists = $odb -> prepare("SELECT * FROM `asset_group` WHERE `name` = :name AND id != :id");
        $checkAssetGroupExists -> execute(array(':name' => $group_name, ':id' => $editId));

        if($checkAssetGroupExists -> rowCount() >= 1) {
                        
            $errors = 'The group name is already in use.';  
        }                  
        else
        {
            $SQLupdate = $odb -> prepare("UPDATE `asset_group` SET `name` = :group_name, `operating_system` = :operating_system , team = :team WHERE id = :id");
            $SQLupdate -> execute(array(':group_name' => $group_name, ':operating_system' => $operating_system, ':team' => $assign_team, ':id' => $editId));   
            $user->addRecentActivities($odb,'edit_sytem_group'," modified System Group (".$group_name.") on the platform.");

            header('location: https://rootcapture.com/dashboard/asset_group.php');
	        die();
        }
    } 
}

$pageTitle = 'Edit A Group';
require('common/header.php') 
?>
 
	
	
<div class="row special_btn_long_menu">

<ul class="d-flex flex-sm-row flex-column align-items-center justify-content-center" style="width: 100%;"> 

        <li class=""><a class="top_menu_item_long_menu">Edit a Group</a></li> 
    </ul>  	
    
      
</div>




<div class="container centralize_container pt-5 " >
<ul class="nav nav-pills   justify-content-center" id="pills-tab" role="tablist">
<li class="nav-item" role="presentation">
<button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true"> 
    <?php if( isset($errors)){ echo $errors; } else{ echo 'Edit A Group'; } ?>
    </button>
</li>

</ul>
<div class="tab-content" id="pills-tabContent">
<div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">

<form id="form" onsubmit="javascript: return process();" method="POST">

        <div class="p-5 row">
            <div class="col-6 px-5">



                <div class="mb-3">
                    <label for="group_name" class="form-label">Group Name <span id="groupNameErr" style="color:red" ></span> </label>
                    <input type="text" class="form-control" id="group_name" value="<?=$editname?>" name="group_name" placeholder="Write your group name here">
                </div>
                
                
                
            </div>
            <div class="col-6 px-5">
                <div class="mb-3">
					<label for="selectCust" class="form-label">Operating System</label>
					<select  id="selectCust" name="operating_system" class="form-select">
                        <option value=""> Please Select </option>
                        <option value="windows" <?php if($editoperating_system == 'windows'){echo 'selected';}?> > Windows </option>
                        <option value="linux" <?php if($editoperating_system == 'linux'){echo 'selected';}?> > Linux </option>
                        <option value="android" <?php if($editoperating_system == 'android'){echo 'selected';}?>> Android </option>
                        <option value="iphone" <?php if($editoperating_system == 'iphone'){echo 'selected';}?>> iPhone </option>
					</select>

				</div>
            </div>

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
            <button type="submit" name="updateAssetGroup" class="rc-btn submit-button">Update Group</button>
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