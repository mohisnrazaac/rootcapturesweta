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
        $teamList =   $odb -> query("SELECT teams.* FROM `teams` INNER JOIN team_status ON teams.id = team_status.team_id WHERE teams.name != 'Admin' AND teams.name != 'Administrative Assistant' AND team_status.college_id = $college_id AND team_status.status = 1 ORDER BY id ASC")->fetchAll();

        $assetGroup = $odb -> query("SELECT * FROM `asset_group` WHERE college_id = $college_id")->fetchAll();
		if (isset($_POST['submitAsset']))
		{
			$system_name = $_POST['system_name'];
			$system_ip = $_POST['system_ip'];
			$operating_system = $_POST['operating_system'];
			$assign_team = $_POST['assign_team'];
			$url = $_POST['url'];
			$asset_group = $_POST['asset_group'];
           
           
			$errors = array();
			if (empty($system_name) || empty($system_ip) || empty($operating_system) || empty($assign_team) || empty($url) || empty($asset_group))
			{
				$errors[] = 'Please verify all fields'; 
			} 

			if (empty($errors))
			{  
                try
                {
                    $odb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $odb->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

                    //Check for dublicate username
                   
                    $checkAssetExists = $odb -> prepare("SELECT * FROM `asset` WHERE `system_name` = :system_name AND college_id = $college_id");
	                $checkAssetExists -> execute(array(':system_name' => $system_name));

                    if($checkAssetExists -> rowCount() >= 1) {
                        
                        $errors[] = 'The asset is already in use, please use a different asset.';  
                    }
                    
                        $SQLinsert = $odb -> prepare("INSERT INTO `asset`(`id`, `college_id`,`system_name`, `system_ip`, `operating_system`, `team`, `url`, `asset_group`) VALUES(NULL,$college_id, :system_name, :system_ip, :operating_system, :team, :url, :asset_group)");
                        $SQLinsert -> execute(array(':system_name' => $system_name, ':system_ip' => $system_ip, ':operating_system' => $operating_system, ':team' => $assign_team, ':url' => $url, ':asset_group' => $asset_group));

                        $user->addRecentActivities($odb,'add_sytem'," created new System (".$system_name.") on the platform.");
                 

                }
                catch(Exception $e) {
                    $errors = 'Exception -> ';
                    var_dump($e->getMessage());
                }
                    
			}
			else
			{
				foreach($errors as $error)
				{
					echo '-'.$error.'<br />';
				}
				echo '</div>';
			}
		}

        $pageTitle = 'Add An Asset';
        require_once '../header.php'
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
                                <li class="breadcrumb-item active" aria-current="page">Add An Asset</li>
                            </ol>
                        </nav>
                    </div>
					<br>
                    <!-- /BREADCRUMB -->
                       <div class="col-lg-12 col-12 layout-spacing">

                        <div class="row mb-3">
                                <div class="col-md-12">
                                    <h2>Add An Asset</h2>
        
                                    <div class="animated-underline-content">
                                        <ul class="nav nav-tabs" id="animateLine" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" id="animated-underline-home-tab" data-bs-toggle="tab" href="#animated-underline-home" role="tab" aria-controls="animated-underline-home" aria-selected="true"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg> Add An Asset</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-content" id="animateLineContent-4">
                                <div class="tab-pane fade show active" id="animated-underline-home" role="tabpanel" aria-labelledby="animated-underline-home-tab">
		<?php 
		if (isset($_POST['submitAsset']))
		{
			if(empty($errors)) {
				echo '<div class="message" id="message"><p><strong>SUCCESS: The asset has been added! You are now being redirected to the asset management Platform.</strong></div><meta http-equiv="refresh" content="4;url='.BASEURL.'admin/manage_assets.php">';
				
				$username = '';
				$email = '';
				$password = '';
				$repassword = '';
				$role = '';
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
<form class=" section general-info" method="POST">
<div class="info"> 
    <div align="Center">
        <h6 class="">Add An Asset</h6>
<div class="row">
 <div class="col-lg-11 mx-auto">
    <div class="row">
<div class="col-xl-10 col-lg-12 col-md-8 mt-md-0 mt-4">

<div class="form">
<div class="row">



   
        <div class="col-md-6">
            <div class="form-group">
            <label for="system_name">System Name</label>
            <input type="text" id="system_name" class="form-control mb-3" name="system_name">
        </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
             <label for="system_ip">System IP</label>
            <input type="text" id="system_ip" class="form-control mb-3" name="system_ip" >
        </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="selectCust" class="custOption">Operating System</label>
                <select class="form-control mb-3" name="operating_system" id="selectCust" >
                    <option value=""> Please Select </option>
                    <option value="windows"> Windows </option>
                    <option value="linux"> Linux </option>
                    <option value="android"> Android </option>
                    <option value="iphone"> iPhone </option>
                </select>
            </div>
        </div> 

        <div class="col-md-6">
            <div class="form-group">
                <label for="assign_team" class="custOption">System Team Assignment</label>
                <select class="form-control mb-3" name="assign_team" id="assign_team" >
                    <option value=""> Please Select </option>
                    <?php foreach ($teamList as $teamListV) { 
                        echo '<option value="'.$teamListV['id'].'"> '.$teamListV['name'].' </option>'; 
                    } ?>
                </select>
            </div>
        </div>
    
     
        <div class="col-md-12">
            <div class="form-group mt-3">
            <label for="url">Url</label>
            <input type="text" class="form-control mb-3" id="url" name="url"/>
        </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="asset_group" class="custOption">Asset group</label>
                <select class="form-control mb-3" name="asset_group" id="asset_group" >
                    <option value=""> Please Select </option>
                    <?php foreach ($assetGroup as $assetGroupV) { 
                        echo '<option value="'.$assetGroupV['id'].'"> '.$assetGroupV['name'].' </option>'; 
                    } ?>
                </select>
            </div>
        </div>
   
   
   <div class="col-md-12 mt-4">
    <div class="form-group text-end">
    <input type="submit" name="submitAsset" class="btn btn-outline-success btn-lrg">
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
        function isNumberKey(evt){
                var charCode = (evt.which) ? evt.which : evt.keyCode
                if (charCode > 31 && (charCode < 48 || charCode > 57))
                    return false;
                return true;
        }

       

        selectBox = new vanillaSelectBox("#selectCust", {
            "keepInlineStyles":true,
            "maxHeight": 200,
            "minWidth":481,
            "search": true,
            "placeHolder": "Choose..." 
        });

        assign_team = new vanillaSelectBox("#assign_team", {
            "keepInlineStyles":true,
            "maxHeight": 200,
            "minWidth":481,
            "search": true,
            "placeHolder": "Choose..." 
        });

        asset_group = new vanillaSelectBox("#asset_group", {
            "keepInlineStyles":true,
            "maxHeight": 200,
            "minWidth":481,
            "search": true,
            "placeHolder": "Choose..." 
        });

    </script>
   
</body>
</html>