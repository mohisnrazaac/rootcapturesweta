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

    if (isset($_GET['deleteId']))
    {
        $delete = $_GET['deleteId'];
        $system_name = $odb -> query("SELECT system_name FROM `asset` WHERE id = $delete")->fetchColumn();

            $SQL = $odb -> prepare("DELETE FROM `asset` WHERE `id` = :id");
            $SQL -> execute(array(':id' => $delete));

            $user->addRecentActivities($odb,'delete_sytem'," deleted the System (".$system_name.") on the platform.");
    }

    $getUserDetailIdWise = $user->getUserDetailIdWise($odb); 
    $college_id = $getUserDetailIdWise['college_id']; 

    $SQLGetAsset = $odb -> query("SELECT asset.*,asset_group.name as assetgroup FROM `asset` INNER JOIN `asset_group` ON `asset_group`.id = `asset`.asset_group WHERE asset_group.college_id = $college_id  ORDER BY `asset`.`id` DESC");
    $assetR = $SQLGetAsset -> fetchAll(PDO::FETCH_ASSOC);

    $title = 'Manage Assets';
    require_once('common/header.php'); 
    
?>

<!-- page constent -->

   
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.3/css/dataTables.bootstrap5.css" />

   
<div class="row special_btn_long_menu">
			<ul class="d-flex flex-sm-row flex-column align-items-center justify-content-center" style="width: 100%;"> 
					<li class=""><a class="top_menu_item_long_menu" href="<?=BASEURL?>dashboard/create_group.php">Create New Group</a></li> 
			</ul>  	 
	</div>

<div class="p-4"></div>

<div class="container px-5"> 
</head>
<body>

<div class="main-container mt-5">
    <span class="cyber_range_heading_bg">Cyber Range / <span class="primary-color">Asset Group</span></span>
</div>
	
	
  <div class="main_announcement container ">
     
      <div style="margin-bottom: 1px">
        <label class="cyber_range_bg" for="editor"><i>&nbsp;</i></label> 
      </div>   
	  
  
 
 

	  
	   <table id="rc_table" class="table table-striped nowrap" style="width:100%">
        <thead>
            <tr>
                <th> Name </th>
                <th>IP</th>
                <th>Operating System</th>
                <th>Team Assignment</th>
                <th>Url</th>
                <th>Asset Group</th>
                <th style="text-align: right">Action</th> 
            </tr>
        </thead>
        <tbody> 
		<?php
        if(!empty($assetR))
        {
            foreach ($assetR as $key => $getInfo)
            {
                $id = $getInfo['id'];
                $system_name = $getInfo['system_name'];
                $system_ip = $getInfo['system_ip'];
                $operating_system = $getInfo['operating_system'];
                $team = $getInfo['team'];
                $url = $getInfo['url'];
                $asset_group = $getInfo['assetgroup'];

                $SQL = $odb -> prepare("SELECT name,color_code FROM `teams` WHERE `id` = :id");
                $SQL -> execute(array(':id' => $team));
                $data = $SQL -> fetchAll();	
                $color_code = $data[0]['color_code'];
                $team_name = $data[0]['name'];
                    
                // if( $team_name == 'Red Team' ) {
                //     $teamassignment = '<span class="badge badge-light-danger mb-2">'.$team_name.'</span>';
                // }
                // else if( $team_name == 'Blue Team' ) {
                //     $teamassignment = ' <span class="badge badge-light-info  mb-2">'.$team_name.'</span>';
                // }
                // else if( $team_name == 'Purple Team' ){
                //     $teamassignment = ' <span class="badge badge-light-secondary  mb-2">'.$team_name.'</span>';
                // }
                // else
                // {
                //     $darker = $user->darken_color($color_code, $darker=2);
                //     $teamassignment = '<span class="team-btn" style="background-color: '.$darker.';color:'.$color_code.';">'.$team_name.'</span>';
                // } 

                // if($operating_system == 'windows') {
                //     $operating_system = '<span class="badge outline-badge-info mb-2 me-4">Windows</span>';
                // } elseif($operating_system == 'linux') {
                //     $operating_system = '<span class="badge outline-badge-success mb-2 me-4">Linux</span>';
                // }
                // elseif($operating_system == 3){
                //     $operating_system = '<span class="badge outline-badge-primary mb-2 me-4">MacOS</span>';
                // }
                // elseif($operating_system == 'android'){
                //     $operating_system = '<span class="badge outline-badge-warning mb-2 me-4">Android</span>';
                // }
                // elseif($operating_system == 'iphone'){
                //     $operating_system = '<span class="badge outline-badge-dark mb-2 me-4">iPhone</span>';
                // }
                // elseif($operating_system == 6) {
                //     $operating_system = '<span class="badge outline-badge-success mb-2 me-4">Fedora Linux</span>';
                // }
                // elseif($operating_system == 7) {
                //     $operating_system = '<span class="badge outline-badge-success mb-2 me-4">Dracos Linux</span>';
                // }
                // elseif($operating_system == 8) {
                //     $operating_system = '<span class="badge outline-badge-success mb-2 me-4">Parrot Linux</span>';
                // }
                // elseif($operating_system == 9) {
                //     $operating_system = '<span class="badge outline-badge-success mb-2 me-4">BackBox Linux</span>';
                // }
                // elseif($operating_system == 10) {
                //     $operating_system = '<span class="badge outline-badge-success mb-2 me-4">CyborgHawk Linux</span>';
                // }
        ?>
            <tr>
                <td><?=$system_name?></td>
                <td><?=$system_ip?></td>
                <td><?=$operating_system?></td>
                <td><?=$team_name?></td>
                <td><?=$url?></td>
                <td><?=$asset_group?></td>
                <!-- <td><button type="submit" class=" mx-2 rc-btn button-danger"><?=$team_name?></button></td>
                <td><button type="submit" class=" mx-2 rc-btn button-warning"><?=$getInfo['total_asset']?></button></td> -->
                <?php if ($user -> isAdmin($odb)) {      ?>  
                <td>
                <div class="d-flex flex-row-reverse">
                    <a href="https://rootcapture.com/dashboard/edit_asset.php?editId=<?=$id?>">
                    <button type="submit" class=" mx-2 rc-btn button-warning">EDIT</button>
                    </a>

                    <a href="https://rootcapture.com/admin/manage_assets.php?deleteId=<?=$id?>">
                    <button type="submit" class=" mx-2 rc-btn button-danger">DELETE</button>
                    </a>
                </div>     
                </td>
                <?php } ?>
            </tr> 
        <?php
            }
         }
         else
         {
            echo '<tr>
                        <td colspan="5">
                          There are currently data.
                        </td>
                  </tr>';
         }
        ?>
        </tbody> 
    </table>
 
   
 
 
      
	  <!-- <div class="d-flex flex-row-reverse">
      <button type="submit" class="rc-btn submit-button">Submit</button>
	  </div>   -->
	 
  </div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script> 
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
	    new DataTable('#rc_table');
  </script> 
  

<!-- page conctent -->

<?php require_once('common/footer.php') ?>