<?php
ob_start();
require_once 'includes/db.php';
require_once 'includes/init.php';
if (!($user -> LoggedIn()))
{
	header('location: login.php');
	die();
}
if (!($user -> notBanned($odb)))
{
	header('location: login.php');
	die();
}

// if is purple or is red or is blue or is admin or is assistant



$pageTitle = 'Asset IP List';
require_once 'header.php';

?>

    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container" id="container">

        <div class="overlay"></div>
        <div class="search-overlay"></div>

        <!--  BEGIN SIDEBAR  --> 
<?php include 'sidebar.php'; ?>
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
                                <li class="breadcrumb-item active" aria-current="page">Asset IP List</li>
                            </ol>
                        </nav>
                    </div>
                    <!-- /BREADCRUMB -->
                    <div class="row layout-top-spacing">
                    
                        <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                            <div class="widget-content widget-content-area br-8">
                                <table id="zero-config" class="table dt-table-hover" style="width:100%">
                                    <thead>
                                        <tr>
											<th scope="col" class="col-md-2"> Name</th>
											<th class="text-center col-md-3" scope="col"> IP</th>
											<th class="text-center col-md-2" scope="col">Operating System</th>
											<th class="text-center col-md-2" scope="col">Team Assignment</th>
											<th class="text-center col-md-3" scope="col">Url </th>
											<th class="text-center col-md-3" scope="col">Asset Group </th>
											<!-- <th class="text-center col-md-3" scope="col">Action </th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php
                if (isset($_GET['deleteId']))
                {
                    $delete = $_GET['deleteId'];
                    $system_name = $odb -> query("SELECT system_name FROM `asset` WHERE id = $delete")->fetchColumn();

                        $SQL = $odb -> prepare("DELETE FROM `asset` WHERE `id` = :id");
                        $SQL -> execute(array(':id' => $delete));

                        $user->addRecentActivities($odb,'delete_sytem'," deleted the System (".$system_name.") on the platform.");
                   
                    echo '<div class="message" id="message"><p><strong>SUCCESS: </strong>The user has been deleted!</p></div>';
                }
            ?>

			<?php 
            $getUserDetailIdWise = $user->getUserDetailIdWise($odb); 
            $college_id = $getUserDetailIdWise['college_id']; 

			$SQLGetAsset = $odb -> query("SELECT asset.*,asset_group.name as assetgroup FROM `asset` INNER JOIN `asset_group` ON `asset_group`.id = `asset`.asset_group WHERE asset_group.college_id = $college_id  ORDER BY `asset`.`id` DESC");
            $assetR = $SQLGetAsset -> fetchAll(PDO::FETCH_ASSOC);
            if(!empty($assetR)){
    			foreach ($assetR as $key => $getInfo) {
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

                    if( $team_name == 'Red Team' ) {
                        $teamassignment = '<span class="badge badge-light-danger mb-2">'.$team_name.'</span>';
                    }
                    else if( $team_name == 'Blue Team' ) {
                        $teamassignment = ' <span class="badge badge-light-info  mb-2">'.$team_name.'</span>';
                    }
                    else if( $team_name == 'Purple Team' ){
                        $teamassignment = ' <span class="badge badge-light-secondary  mb-2">'.$team_name.'</span>';
                    }
                    else
                    {
                        $darker = $user->darken_color($color_code, $darker=2);
                        $teamassignment = '<span class="team-btn" style="background-color: '.$darker.';color:'.$color_code.';">'.$team_name.'</span>';
                    } 
                    
                    

                    if($operating_system == 'windows') {
                        $operating_system = '<span class="badge outline-badge-info mb-2 me-4">Windows</span>';
                    } elseif($operating_system == 'linux') {
                        $operating_system = '<span class="badge outline-badge-success mb-2 me-4">Linux</span>';
                    }
                    elseif($operating_system == 3){
                        $operating_system = '<span class="badge outline-badge-primary mb-2 me-4">MacOS</span>';
                    }
                    elseif($operating_system == 'android'){
                        $operating_system = '<span class="badge outline-badge-warning mb-2 me-4">Android</span>';
                    }
                    elseif($operating_system == 'iphone'){
                        $operating_system = '<span class="badge outline-badge-dark mb-2 me-4">iPhone</span>';
                    }
                    elseif($operating_system == 6) {
                        $operating_system = '<span class="badge outline-badge-success mb-2 me-4">Fedora Linux</span>';
                    }
                    elseif($operating_system == 7) {
                        $operating_system = '<span class="badge outline-badge-success mb-2 me-4">Dracos Linux</span>';
                    }
                    elseif($operating_system == 8) {
                        $operating_system = '<span class="badge outline-badge-success mb-2 me-4">Parrot Linux</span>';
                    }
                    elseif($operating_system == 9) {
                        $operating_system = '<span class="badge outline-badge-success mb-2 me-4">BackBox Linux</span>';
                    }
                    elseif($operating_system == 10) {
                        $operating_system = '<span class="badge outline-badge-success mb-2 me-4">CyborgHawk Linux</span>';
                    }
    				
    				echo '<tr class="gradeA"><td>'.$system_name.'</td><td><center>'.$system_ip.'</center></td><td><center>'.$operating_system.'</center></td><td><center>'.$teamassignment.'</center></td><td><center>'.$url.'</center></td><td><center>'.$asset_group.'</center></td>';if ($user -> isAdmin($odb)) {
                        
                        // echo '<td width="70px"><a href="'.BASEURL.'admin/edit_asset.php?editId='.$id.'"><button class="btn btn-outline-warning  mb-2 me-4">Edit</button></a> <a href="'.BASEURL.'admin/manage_assets.php?deleteId='.$id.'"><button class="btn btn-outline-danger mb-2 me-4" name="deleteBtn" value="'.$id.'" role="button" type="submit">Delete</button></a> </td>';
                    }
                    echo  '</tr>';
    			}
            }else{
                echo '<tr class=""><td valign="top" colspan="7" class="dataTables_empty" style="text-align:center;">There are currently no system created,</td></tr>';
            }
			?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!--  BEGIN FOOTER  -->
            <?php require_once 'includes/footer-section.php'; ?>
            <!--  END FOOTER  -->
            <!--  END CONTENT AREA  -->
        </div>
        <!--  END CONTENT AREA  -->
    </div>
    <!-- END MAIN CONTAINER -->
    <?php  require_once 'footer.php'; ?>
 
    <script>
        $('#zero-config').DataTable({
            "dom": "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l><'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>>>" +
        "<'table-responsive'tr>" +
        "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count  mb-sm-0 mb-3'i><'dt--pagination'p>>",
            "oLanguage": {
                "oPaginate": { "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>', "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>' },
                "sInfo": "Showing page _PAGE_ of _PAGES_",
                "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
                "sSearchPlaceholder": "Search...",
               "sLengthMenu": "Results :  _MENU_",
            },
            "stripeClasses": [],
            "lengthMenu": [7, 10, 20, 50],
            "pageLength": 10 
        });
    </script>
    <!-- END PAGE LEVEL SCRIPTS -->
</body>
</html>