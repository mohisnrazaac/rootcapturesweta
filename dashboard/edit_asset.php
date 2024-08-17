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

    $assetGroup = $odb -> query("SELECT * FROM `asset_group` WHERE college_id = $college_id")->fetchAll();
    $editId =  $_GET['editId']; 

    $SQLget = $odb -> prepare("SELECT * FROM `asset` WHERE `id` = :editId");
    $SQLget -> execute(array(':editId' => $editId));
    $Info = $SQLget -> fetch(PDO::FETCH_ASSOC); 
    $datas = '';
    if(!empty($Info))
    {
        $editsystem_name = $Info['system_name'];
        $editsystem_ip = $Info['system_ip'];
        $editoperating_system = $Info['operating_system'];
        $editteam = $Info['team'];
        $editurl = $Info['url'];
        $editasset_group = $Info['asset_group'];
    }
    else
    {
        $editsystem_name = '';
        $editsystem_ip = '';
        $editoperating_system = '';
        $editteam = '';
        $editurl = '';
        $editasset_group = '';
    }

    if (isset($_POST['updateAsset']))
    {
        $system_name = $_POST['system_name'];
        $system_ip = $_POST['system_ip'];
        $operating_system = $_POST['operating_system'];
        $assign_team = $_POST['assign_team'];
        $url = $_POST['url'];
        $asset_group = $_POST['asset_group'];

        if ( empty($system_name) || empty($system_ip) || empty($operating_system) || empty($assign_team) || empty($url) || empty($asset_group) )
        {
            $errors = 'Please fill all fields'; 
        }
        else
        {
        

            // try
            // {
            //     $odb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //     $odb->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

                //Check for dublicate username
            
                $checkAssetExists = $odb -> prepare("SELECT * FROM `asset` WHERE `system_name` = :system_name AND `id` != :id");
                $checkAssetExists -> execute(array(':system_name' => $system_name,':id' => $editId));

                if($checkAssetExists -> rowCount() >= 1) {
                    
                    $errors[] = 'The asset is already in use, please use a different asset.';  
                }
                else
                {   
                    $SQLupdate = $odb -> prepare("UPDATE `asset` SET `system_name` = :system_name, `system_ip` = :system_ip , operating_system = :operating_system, `team` = :team , `url` = :url , `asset_group` = :asset_group  WHERE id = :id");                       
                    $SQLupdate -> execute(array(':system_name' => $system_name, ':system_ip' => $system_ip, ':operating_system' => $operating_system, ':team' => $assign_team, ':url' => $url, ':asset_group' => $asset_group, ':id' => $editId));

                    $user->addRecentActivities($odb,'edit_sytem'," modified System (".$system_name.") on the platform.");
                }

                header('location: https://rootcapture.com/dashboard/manage_assets.php');
                die();
            

            // }
            // catch(Exception $e) {
            //     $errors = 'Exception -> ';
            //     var_dump($e->getMessage());
            // }
        } 
    }

    $pageTitle = 'Edit An Asset';
require('common/header.php') 
?>
 
	
	
<div class="row special_btn_long_menu">

<ul class="d-flex flex-sm-row flex-column align-items-center justify-content-center" style="width: 100%;"> 

        <li class=""><a class="top_menu_item_long_menu">Edit An Asset</a></li> 
    </ul>  	
    
      
</div>




<div class="container centralize_container pt-5 " >
<ul class="nav nav-pills   justify-content-center" id="pills-tab" role="tablist">
<li class="nav-item" role="presentation">
<button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true"> 
    <?php if( isset($errors)){ echo $errors; } else{ echo 'Edit An Asset'; } ?>
    </button>
</li>

</ul>
<div class="tab-content" id="pills-tabContent">
<div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">

<form id="form" onsubmit="javascript: return process();" method="POST">

        <div class="p-5 row">
            <div class="col-6 px-5">



                <div class="mb-3">
                    <label for="system_name" class="form-label">System Name <span id="groupNameErr" style="color:red" ></span> </label>
                    <input type="text" class="form-control" id="system_name" value="<?=$editsystem_name?>" name="system_name" placeholder="Write your system name here">
                </div>
                
                
                
            </div>

            <div class="col-6 px-5">
                <div class="mb-3">
                    <label for="system_ip" class="form-label">System IP <span id="groupNameErr" style="color:red" ></span> </label>
                    <input type="text" class="form-control" id="system_ip" value="<?=$editsystem_ip?>" name="system_ip" placeholder="Write your ip here">
                </div>
            </div>

            <div class="col-6 px-5">
                <div class="mb-3">
					<label for="selectCust" class="form-label">Operating System</label>
					<select  id="selectCust" name="operating_system" class="form-select">
                        <option value=""> Please Select </option>
                        <option value="windows" <?php if($editoperating_system == 'windows'){echo 'selected';} ?>> Windows </option>
                        <option value="linux" <?php if($editoperating_system == 'linux'){echo 'selected';} ?>> Linux </option>
                        <option value="android" <?php if($editoperating_system == 'android'){echo 'selected';} ?>> Android </option>
                        <option value="iphone" <?php if($editoperating_system == 'iphone'){echo 'selected';} ?>> iPhone </option>
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
                            echo '<option value="'.$teamListV['id'].'" '.$select.'> '.$teamListV['name'].' </option>'; 
                        } ?>
					</select>

				</div>
            </div>

            <div class="col-6 px-5">
                <div class="mb-3">
                    <label for="url" class="form-label"> Url <span id="groupNameErr" style="color:red" ></span> </label>
                    <input type="text" class="form-control" id="url" value="<?=$editurl?>" name="url" placeholder="Write your ip here">
                </div>
            </div>

            <div class="col-6 px-5">
                <div class="mb-3">
					<label for="asset_group" class="form-label">Asset group</label>
					<select  id="asset_group" name="asset_group" class="form-select">
                        <option value=""> Please Select </option>
                        <?php foreach ($assetGroup as $assetGroupV) { 
                            if($editasset_group == $assetGroupV['id']){ $select = 'selected';}else $select = '';
                            echo '<option value="'.$assetGroupV['id'].'" '.$select.'> '.$assetGroupV['name'].' </option>'; 
                        } ?>
					</select>

				</div>
            </div>

        </div>

        <div class="tab_footer d-flex flex-row align-items-center justify-content-center  ">
            <button type="submit" name="updateAsset" class="rc-btn submit-button">Update Asset</button>
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