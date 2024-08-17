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

if (isset($_POST['submitTeam']))
{
   
    $team = $_POST['team'];
    $favcolor = $_POST['favcolor']; 
    $getUserDetailIdWise = $user->getUserDetailIdWise($odb); 
    $college_id = $getUserDetailIdWise['college_id']; 

    if (empty($team))
    {
        header('location: ../errordocs.php');
    } 
    else
    {
        $sqlTeamAlreadyExists = $odb -> query("SELECT COUNT(teams.id) FROM `teams` INNER JOIN `team_status` ON teams.id = team_status.team_id WHERE `name` LIKE '$team' && team_status.college_id = $college_id");
        

        if($sqlTeamAlreadyExists->fetchColumn())
        {   
            $errors = 'This team is already in list';
        }
        else
        {
        
            $team_code = $user->random_strings(20); 
        

            $sqlTeamCodeExists = $odb -> query("SELECT COUNT(id) FROM `teams` WHERE `team_code` LIKE '$team_code'");
        
            if($sqlTeamCodeExists->fetchColumn())
            {   
                $team_code = $user->random_strings(20);
            }
            
        try {
            
            $SQLinsert = $odb -> prepare("INSERT INTO `teams` (`id`,`user_id`,`team_type`,`name`,`team_code`,`color_code`, `created_at`, `updated_at`) VALUES(NULL,:user_id ,:team_type,:team_name, :team_code,:color_code, :created_at,:updated_at)");

            $statement2 = $odb -> prepare("INSERT INTO `team_status` (`id`,`team_id`,`team_code`,`college_id`,`status`) VALUES(NULL,:team_id,:team_code ,$college_id,1)");
            

            $odb->beginTransaction();

            $SQLinsert -> execute(array(':user_id'=>$_SESSION['ID'],':team_type'=>3,':team_name' => $team, ':team_code' => $team_code,':color_code' => $favcolor,'created_at' => DATETIME, 'updated_at' => DATETIME));

            $last_insert_id = $odb->lastInsertId();
            

            $statement2 -> execute(array(':team_id'=>$last_insert_id,':team_code'=>$team_code));


            $odb->commit();
        } 
        catch (\Exception $e) {                   
            if ($odb->inTransaction()) {
                $odb->rollback();
                // If we got here our two data updates are not in the database
            } 

            $errors  = $e->getMessage();
        }

            $user->addRecentActivities($odb,'add_team',' Created a New Team ('.$team.') on the Platform.');
        }
    } 
}

$pageTitle = 'Add A Team';
require('common/header.php') 
?>
 
	
	
<div class="row special_btn_long_menu">

<ul class="d-flex flex-sm-row flex-column align-items-center justify-content-center" style="width: 100%;"> 

        <li class=""><a class="top_menu_item_long_menu" href="?page=add_a_team.php">Add a Team</a></li> 
    </ul>  	
    
      
</div>




<div class="container centralize_container pt-5 " >
<ul class="nav nav-pills   justify-content-center" id="pills-tab" role="tablist">
<li class="nav-item" role="presentation">
<button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true"> 
    <?php if( isset($errors)){ echo $errors; } else{ echo 'Add an Team'; } ?>
    </button>
</li>

</ul>
<div class="tab-content" id="pills-tabContent">
<div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">

<form id="form" onsubmit="javascript: return process();" method="POST">

        <div class="p-5 row">
            <div class="col-6 px-5">



                <div class="mb-3">
                    <label for="team" class="form-label">Team Name <span id="teamErr" style="color:red" ></span> </label>
                    <input type="text" class="form-control" id="team" name="team" placeholder="Write your team name here">
                </div>
                
                
                
            </div>
            <div class="col-6 px-5">
                <div class="mb-3">
                    <label for="favcolor" class="form-label">Pick Color <span id="favcolorErr" color="red" ></span> </label>
                    <input type="color" class="form-control" id="favcolor" name="favcolor" >
                </div>
            </div>
        </div>

        <div class="tab_footer d-flex flex-row align-items-center justify-content-center  ">
            <button type="submit" name="submitTeam" class="rc-btn submit-button">Create Team</button>
        </div>
</form>
                
</div> 
</div>
</div>

  <?php require('common/footer.php') ?>
  <script>
    function process()
    {
       var team = $("#team").val();
       var favcolor = $("#favcolor").val();
       if(team == '')
       {
            $("#teamErr").text(" (Required)");
            $("#team").focus();
            return false;
       }
       else
       {
        if(favcolor == '')
        {
            $("#favcolorErr").text(" (Required)");
            $("#favcolor").focus();
            return false;
        }
        else
        {
            $('#form').submit();
        }
       }
    }
  </script>