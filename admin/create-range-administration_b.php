<?php
ob_start();
require_once '../includes/db.php';
require_once '../includes/init.php';

    $getUserDetailIdWise = $user->getUserDetailIdWise($odb); 
    $college_id = $getUserDetailIdWise['college_id']; 

    $SQLGetTeam = $odb -> query("SELECT teams.* FROM `teams` INNER JOIN team_status ON teams.id = team_status.team_id WHERE teams.name != 'Admin' AND teams.name != 'Administrative Assistant' AND team_status.college_id = $college_id AND team_status.status = 1 ORDER BY id ASC")->fetchAll();

    $SQLGetallTeam = $odb -> query("SELECT teams.* FROM `teams` INNER JOIN team_status ON teams.id = team_status.team_id WHERE team_status.college_id = $college_id AND team_status.status = 1 ORDER BY id ASC")->fetchAll();
    

    $resetCyberRangeActive =  false;
    $resetUserListActive =  false; 
    $resetAssetsListActive = false;
    $resetSystemGroupListActive = false;
    $resetTeamAssetsListActive = false;
    $clearDatabaseLoginLogsActive = false;
    $resetLoginLogsActive = false;
    $resetRecentActivityLogsActive = false;
    $resetAllLogsActive = false;
    $getActiveInactiveSession = $user -> getActiveInactiveSession($odb,$college_id); 
    $activeSession = $getActiveInactiveSession['active_session'];
    $inactiveSession = $getActiveInactiveSession['inactive_session']; 
    $get_all_active_user = $getActiveInactiveSession['get_all_active_user']; 

    if (isset($_POST['resetCyberRange']))
    {
        // //Delete all user except AdminDemo
        $SQL = $odb -> prepare("DELETE FROM `users` WHERE `rank` != 1 AND college_id = $college_id");
        $SQL -> execute(array()); // remove all user except the AdminDemo

        $SQL = $odb -> query("DELETE FROM `news` WHERE college_id = $college_id"); // remove announcement

        //remove all grade
            $statement1 = $odb -> query("SELECT id FROM grading_rubric_criteria WHERE `college_id` = $college_id")->fetchAll(PDO::FETCH_ASSOC);
            $idsArr = [];       
            foreach($statement1 as $row){
                $idsArr[] = $row['id'];
            }       

            $SQL = $odb -> query("DELETE FROM `grading_rubric_criteria` WHERE college_id = $college_id");
            $SQL = $odb -> query("DELETE FROM `grading_rubric_criteria_user` WHERE grading_rubric_criteria_id IN (".implode(',',$idsArr).")");

        // end reove grading criteria

        $SQL = $odb -> query("DELETE FROM `asset_group` WHERE college_id = $college_id"); // remove all group
        $SQL = $odb -> query("DELETE FROM `asset` WHERE college_id = $college_id"); // remove all asset
       

        $SQL = $odb -> query("DELETE FROM `tickets` WHERE college_id = $college_id"); // remove all tickets
        $SQL = $odb -> query("DELETE FROM `ticketResponses` WHERE college_id = $college_id"); // remove all tickets

        // $SQL = $odb -> query("TRUNCATE `api_management`"); // remove all APIs

        $user-> addRecentActivities($odb,'reset_all_team_user_list'," The Cyber Range Has Been Reset To Factory Defaults!.");

        $resetCyberRangeActive = true;
    }

    if (isset($_POST['resetUserList']))
    {
        //Delete all user except AdminDemo
        $SQL = $odb -> prepare("DELETE FROM `users` WHERE `rank` != 1 AND college_id = $college_id");
        $SQL -> execute(array()); // remove all user except the AdminDemo
        $user->addRecentActivities($odb,'reset_all_team_assets'," Reset the Platform's User Logs.");
        $resetUserListActive = true;
       
    }    

    if (isset($_POST['resetAssetsList']))
    {
        $SQL = $odb -> query("DELETE FROM `asset` WHERE college_id = $college_id"); // remove all asset
        $user->addRecentActivities($odb,'reset_all_system'," Reset the Platform's System Logs.");
        $resetAssetsListActive = true;
    }

    if (isset($_POST['resetAssetsGroupList']))
    {
        
        $SQL = $odb -> query("DELETE FROM `asset_group` WHERE college_id = $college_id"); // remove all group
        $user->addRecentActivities($odb,'reset_all_system_group'," Reset the Platform's System Group Logs.");
        $resetSystemGroupListActive = true;
    }

  

    if (isset($_POST['resetTeamAssetsList']))
    {
        //Delete all user except AdminDemo
        $teamL = $_POST['multipleSelect']; 
        $resteamname = "";
        foreach($teamL as $teamLV)
        {    
            $teamSql = $odb -> query("SELECT * FROM `teams` WHERE `teams`.`id` = $teamLV")->fetch();
            $teamN = $teamSql['name']; 
            $resteamname .= $teamSql['name'].","; 
           
           $user->addRecentActivities($odb,'reset_asset'," Reset the Platform's ".$teamN." System Logs.");
        }
        
        $resetTeamAssetsListActive = true;
    }  

    if ( isset($_POST['clearDatabaseLoginLogs']) || isset($_POST['resetLoginLogs']) )
    {    
        $userIdArr = [];
        $statement1 = $odb -> query("SELECT ID FROM users WHERE `college_id` = $college_id")->fetchAll(PDO::FETCH_ASSOC);
        foreach($statement1 as $row){
            $userIdArr[] = $row['ID'];
        } 
       
            $commaUserId = implode(',',$userIdArr);
       
      
        $SQL = $odb -> query("DELETE FROM loginip WHERE `UserID` IN (".implode(',',$userIdArr).") ");
        $user->addRecentActivities($odb,'reset_login_logs'," Reset the Platform's Login Logs.");
        $clearDatabaseLoginLogsActive = true;
    }
    
    // if (isset($_POST['resetLoginLogs']))
    // {
    //     $SQL = $odb -> query("TRUNCATE `loginip`");
    //     $user->addRecentActivities($odb,'reset_login_logs'," Reset the Platform's Login Logs.");
    //     $resetLoginLogsActive = true;
    // }

    if (isset($_POST['resetRecentActivityLogs']))
    {
        $SQL = $odb -> query("DELETE FROM `recent_activities` WHERE college_id = $college_id"); // remove all group
        $user->addRecentActivities($odb,'reset_recentactivity_logs'," Reset the Platform's Recent Activity Logs.");
        $resetRecentActivityLogsActive = true;
    }

    if (isset($_POST['resetAllLogs']))
    {
        $userIdArr = [];
        $statement1 = $odb -> query("SELECT ID FROM users WHERE `college_id` = $college_id")->fetchAll(PDO::FETCH_ASSOC);
        foreach($statement1 as $row){
            $userIdArr[] = $row['ID'];
        } 
       
            $commaUserId = implode(',',$userIdArr);
       
      
        $SQL = $odb -> query("DELETE FROM loginip WHERE `UserID` IN (".implode(',',$userIdArr).") ");

        $statement2 = $odb -> query("DELETE FROM `recent_activities` WHERE college_id = $college_id"); // remove all group
        $user->addRecentActivities($odb,'reset_login_recentactivity_logs'," Reset the Platform's Login and Recent Activities Logs.");
        $resetAllLogsActive = true;
    }

    

    //Grapgh Data Queries
        $sqlTotalUsers = $odb -> query("SELECT count(id) as total_user FROM `users` WHERE college_id = $college_id AND status != 2");
        $totalUser = $sqlTotalUsers->fetchColumn();

        $sqlTotalAsset = $odb -> query("SELECT count(asset.id) as total_asset,teams.name,teams.color_code FROM `asset` INNER JOIN `teams` ON `teams`.id = `asset`.`team` WHERE `teams`.`id` NOT IN (1,2)");
        $sqlTotalAsset = $sqlTotalAsset->fetchAll(); 


    

    
    $pageTitle = 'Platform Administration';
    require_once '../header.php';

?>


    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container pieChartSvg" id="container">

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
                                <li class="breadcrumb-item active" aria-current="page">Platform Administration</li>
                            </ol>
                        </nav>
                    </div>
                    <!-- /BREADCRUMB -->
                        
                    <div class="account-settings-container layout-top-spacing">
    
                        <div class="account-content">
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <h2>Settings</h2>
        
                                    <div class="animated-underline-content">
                                        <ul class="nav nav-tabs" id="animateLine" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active dashboard-tabs" id="animated-underline-dashboard-tab" data-bs-toggle="tab" href="#animated-underline-dashboard" role="tab" aria-controls="animated-underline-dashboard-tab" aria-selected="true"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="$_SESSION['username']round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>Dashboard</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link statistics-tabs" id="animated-underline-statistics-tab" data-bs-toggle="tab" href="#animated-underline-statistics" role="tab" aria-controls="animated-underline-statistics-tab" aria-selected="false"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>Statistics</a>
                                            </li>
                                               <li class="nav-item">
                                                <a class="nav-link" id="animated-underline-range-setting-tab" data-bs-toggle="tab" href="#animated-underline-range-setting" role="tab" aria-controls="animated-underline-range-setting-tab" aria-selected="false"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>Range Settings</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="animated-underline-range-admin-tab" data-bs-toggle="tab" href="#animated-underline-range-admin" role="tab" aria-controls="animated-underline-range-admin-tab" aria-selected="false"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>Range Administration</a>
                                            </li>

                                            <li class="nav-item">
                                                <a class="nav-link" id="animated-underline-login-log-admin-tab" data-bs-toggle="tab" href="#animated-underline-login-log" role="tab" aria-controls="animated-underline-login-log-admin-tab" aria-selected="false"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>Login  Log Administration</a>
                                            </li>
                                           
                                        </ul>
                                    </div>
                                </div>
                            </div>

 
                            <div class="tab-content" id="animateLineContent-4">
                                <div class="tab-pane fade show active" id="animated-underline-dashboard" role="tabpanel" aria-labelledby="animated-underline-dashboard-tab">
                                    <div class="row">
                                      <form method="POST" >
                                        <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing app" id="app">
                                         
                                                <!-- BEGIN card -->
                                                <div class="card mb-3">
                                                    <!-- BEGIN card-body -->
                                                    <div class="card-body">
                                                        <!-- BEGIN title -->
                                                        <div class="d-flex fw-bold small mb-3">
                                                            <span class="flex-grow-1">TRAFFIC ANALYTICS</span>
                                                            <a href="#" data-toggle="card-expand" class="text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                                                        </div>
                                                        <!-- END title -->
                                                        <!-- BEGIN map -->
                                                        <div class="ratio ratio-21x9 mb-3">
                                                            <div id="world-map" class="jvectormap-without-padding"></div>
                                                        </div>
                                                        <!-- END map -->
                                                        <!-- BEGIN row -->
                                                        <div class="row gx-4">
                                                            <!-- BEGIN col-6 -->
                                                            <div class="col-lg-6 mb-3 mb-lg-0">
                                                                <table class="w-100 small mb-0 text-truncate  text-opacity-60">
                                                                    <thead>
                                                                        <tr class="text-white text-opacity-75">
                                                                            <th class="w-50">COUNTRY</th>
                                                                            <th class="w-25 text-end">VISITS</th>
                                                                            <th class="w-25 text-end">PCT%</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td>FRANCE</td>
                                                                            <td class="text-end">13,849</td>
                                                                            <td class="text-end">40.79%</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>SPAIN</td>
                                                                            <td class="text-end">3,216</td>
                                                                            <td class="text-end">9.79%</td>
                                                                        </tr>
                                                                        <tr class="text-theme fw-bold">
                                                                            <td>MEXICO</td>
                                                                            <td class="text-end">1,398</td>
                                                                            <td class="text-end">4.26%</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>UNITED STATES</td>
                                                                            <td class="text-end">1,090</td>
                                                                            <td class="text-end">3.32%</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>BELGIUM</td>
                                                                            <td class="text-end">1,045</td>
                                                                            <td class="text-end">3.18%</td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <!-- END col-6 -->
                                                            <!-- BEGIN col-6 -->
                                                            <div class="col-lg-6">
                                                                <!-- BEGIN card -->
                                                                <div class="card">
                                                                    <!-- BEGIN card-body -->
                                                                    <div class="card-body py-2">
                                                                        <div class="d-flex align-items-center">
                                                                            <div class="w-70px">
                                                                                <div data-render="apexchart" data-type="donut" data-height="70"></div>
                                                                            </div>
                                                                            <div class="flex-1 ps-2">
                                                                                <table class="w-100 small mb-0  text-opacity-60">
                                                                                    <tbody>
                                                                                        <tr>
                                                                                            <td>
                                                                                                <div class="d-flex align-items-center">
                                                                                                    <div class="w-6px h-6px rounded-pill me-2 bg-theme bg-opacity-95"></div> FEED
                                                                                                </div>
                                                                                            </td>
                                                                                            <td class="text-end">25.70%</td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td>
                                                                                                <div class="d-flex align-items-center">
                                                                                                    <div class="w-6px h-6px rounded-pill me-2 bg-theme bg-opacity-75"></div> ORGANIC
                                                                                                </div>
                                                                                            </td>
                                                                                            <td class="text-end">24.30%</td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td>
                                                                                                <div class="d-flex align-items-center">
                                                                                                    <div class="w-6px h-6px rounded-pill me-2 bg-theme bg-opacity-55"></div> REFERRAL
                                                                                                </div>
                                                                                            </td>
                                                                                            <td class="text-end">23.05%</td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td>
                                                                                                <div class="d-flex align-items-center">
                                                                                                    <div class="w-6px h-6px rounded-pill me-2 bg-theme bg-opacity-35"></div> DIRECT
                                                                                                </div>
                                                                                            </td>
                                                                                            <td class="text-end">14.85%</td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td>
                                                                                                <div class="d-flex align-items-center">
                                                                                                    <div class="w-6px h-6px rounded-pill me-2 bg-theme bg-opacity-15"></div> EMAIL
                                                                                                </div>
                                                                                            </td>
                                                                                            <td class="text-end">7.35%</td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!-- END card-body -->
                                                                    
                                                                    <!-- BEGIN card-arrow -->
                                                                    <div class="card-arrow">
                                                                        <div class="card-arrow-top-left"></div>
                                                                        <div class="card-arrow-top-right"></div>
                                                                        <div class="card-arrow-bottom-left"></div>
                                                                        <div class="card-arrow-bottom-right"></div>
                                                                    </div>
                                                                    <!-- END card-arrow -->
                                                                </div>
                                                                <!-- END card -->
                                                            </div>
                                                            <!-- END col-6 -->
                                                        </div>
                                                        <!-- END row -->
                                                    </div>
                                                    <!-- END card-body -->
                                                    
                                                    <!-- BEGIN card-arrow -->
                                                    <div class="card-arrow">
                                                        <div class="card-arrow-top-left"></div>
                                                        <div class="card-arrow-top-right"></div>
                                                        <div class="card-arrow-bottom-left"></div>
                                                        <div class="card-arrow-bottom-right"></div>
                                                    </div>
                                                    <!-- END card-arrow -->
                                                </div>
                                                <!-- END card -->
                                            
                                        </div>
                                    
                                      </form>
                                        
                                    </div>
                                </div> 




                                 <div class="tab-pane fade" id="animated-underline-statistics" role="tabpanel" aria-labelledby="animated-underline-statistics-tab">                                    
                                  
                                    <div class="row">                                    
    
                                    <form method="POST" >
                                        <div class="row layout-top-spacing">
                                        <div class="col-xl-8 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                                           <div class="widget widget-chart-one">
                                                <div class="widget-heading">
                                                    <h5 class="">Users</h5>
                                                    <div class="task-action">
                                                        <div class="dropdown">
                                                            <a class="dropdown-toggle" href="#" role="button" id="renvenue" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                                                            </a>
                                                            <div class="dropdown-menu left" aria-labelledby="renvenue" style="will-change: transform;">
                                                                <a class="dropdown-item" href="javascript:void(0);">Weekly</a>
                                                                <a class="dropdown-item" href="javascript:void(0);">Monthly</a>
                                                                <a class="dropdown-item" href="javascript:void(0);">Yearly</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
        
                                                <div class="widget-content ">
                                                    <div id="revenueMonthly"></div>
                                                </div>
                                            </div> 
                                        </div>

                           <div class="col-xl-4 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing  heightForMap">
                                <div class="widget widget-chart-two">
                                    <div class="widget-heading">
                                        <h5 class="">Active / Inactive Sessions</h5>
                                    </div>
                                    <div class="widget-content">
                                        <div id="chart-2" class=""></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-4 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing  heightForMap">
                                <div class="widget widget-chart-two ">
                                    <div class="widget-heading">
                                        <h5 class="">Users</h5>
                                    </div>
                                    <div class="widget-content">
                                        <div id="chart-3" class=""></div>
                                    </div>
                                </div>
                            </div>


                            <div class="col-xl-4 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing  heightForMap">
                                <div class="widget widget-chart-two">
                                    <div class="widget-heading">
                                        <h5 class="">Total Number of Assets</h5>
                                    </div>
                                    <div class="widget-content ">
                                        <div id="chart-4" class=""></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing ">
        
                                <div class="widget widget-activity-four">
        
                                    <div class="widget-heading">
                                        <h5 class="">Recent Activities</h5>
                                    </div>
                                  
                                    <div class="widget-content">
        
                                        <div class="mt-container-ra mx-auto">
                                            <div class="timeline-line">
        
                                            
                                            </div>
                                        </div>
        
                                        <div class="tm-action-btn">

                                            <button type="button" class="btn" onClick="openModal()"><span>View All</span> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg></button>
                                        </div>
                                    </div>
                                </div>
                            </div>




                    </div>


                                    
                                    </form>
                                    </div>
                                </div>

                                   <div class="tab-pane fade" id="animated-underline-range-setting" role="tabpanel" aria-labelledby="animated-underline-range-setting-tab">    
                                    <div class="row">                                      
                                        
                                    <?php
                                        $isMaintanenceMode = $user -> isMaintanenceMode($odb); 
                                        $isRegistrationMode = $user -> isRegistrationMode($odb,$college_id);  
                                        $isRedTeamCTFactive = $user -> isRedTeamCTFactive($odb,$college_id);                                         
                                    ?>

    
                                    <form method="POST" >
                                        <div class="row layout-top-spacing">
                                        <div class="col-xl-4 col-lg-4 col-md-4 layout-spacing">
                                            <div class="section general-info">
                                                <div class="info text-center">                                                
                                                    <h6 class="">Maintenance Mode</h6>
                                                   
                                                    <div class="form-group mt-4">
                                                        <div class="switch form-switch-custom switch-inline form-switch-success mt-1">
                                                            <input class="switch-input" <?php if($isMaintanenceMode == 1){echo 'checked'; } ?> name="maintainence_mode" id="maintainence_mode" type="checkbox" role="switch" id="socialformprofile-custom-switch-success">
                                                        </div>                                                      
                                                    </div>
                                                </div>                                      
                                            </div>
                                        </div>

                                        <div class="col-xl-4 col-lg-4 col-md-4 layout-spacing">
                                            <div class="section general-info">
                                                <div class="info text-center">                                                
                                                    <h6 class="">Platform Registration</h6>
                                                   
                                                    <div class="form-group mt-4">
                                                        <div class="switch form-switch-custom switch-inline form-switch-success mt-1">
                                                            <input class="switch-input" <?php if($isRegistrationMode == 1){echo 'checked'; } ?> name="registration_mode" id="registration_mode" type="checkbox" role="switch" id="socialformprofile-custom-switch-success">
                                                        </div>                                                      
                                                    </div>
                                                </div>                                      
                                            </div>
                                        </div>

                                        <div class="col-xl-4 col-lg-4 col-md-4 layout-spacing">
                                                <div class="section general-info">
                                                
                                                    <div class="info text-center">
                                                        <h6 class="">Activate Red Team CTF</h6>
                                                        
                                                        <div class="form-group mt-4">
                                                            <div class="switch form-switch-custom switch-inline form-switch-success mt-1">
                                                                <input class="switch-input" <?php if($isRedTeamCTFactive == 1){echo 'checked'; } ?> name="redTeamCTFactive" id="redTeamCTFactive" type="checkbox" role="switch" id="socialformprofile-custom-switch-success">
                                                            
                                                            </div>
                                                        
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </form>
                                    </div>
                                </div> 

                        

                                

                                 <div class="tab-pane fade" id="animated-underline-range-admin" role="tabpanel" aria-labelledby="animated-underline-range-admin-tab">
                                    
                                   
                                    <div class="row">

                                    <?php if($resetCyberRangeActive) {?>                                 
                                        <div class="message" style="display:block" ><p><strong>SUCCESS: THE CYBER RANGE HAS BEEN RESET TO FACTORY DEFAULTS!!</strong></div> 
                                    <?php }  elseif($resetUserListActive) {?>                                 
                                            <div class="message" style="display:block" ><p><strong>SUCCESS: ALL OF THE PLATFORM'S USERS WERE SUCCESSFULLY RESET!</strong></div> 
                                    <?php }  elseif($resetAssetsListActive) {?>                                 
                                        <div class="message" style="display:block" ><p><strong>SUCCESS: ALL OF THE PLATFORM'S ASSETS WERE SUCCESSFULLY RESET!</strong></div> 
                                    <?php } elseif($resetSystemGroupListActive) {?>                                 
                                        <div class="message" style="display:block" ><p><strong>SUCCESS: ALL OF THE PLATFORM'S ASSET GROUP WERE SUCCESSFULLY RESET!</strong></div> 
                                    <?php } elseif($resetTeamAssetsListActive) {?>                                 
                                            <div class="message" style="display:block" ><p><strong>SUCCESS: ALL OF <?=substr($resteamname, 0, -1)?> ASSETS WERE SUCCESSFULLY RESET!</strong></div> 
                                    <?php } elseif($resetLoginLogsActive) {?>                                 
                                            <div class="message" style="display:block" ><p><strong>SUCCESS: ALL OF THE PLATFORM'S LOGS WERE SUCCESSFULLY RESET!</strong></div> 
                                    <?php } elseif($resetRecentActivityLogsActive) {?>                                 
                                            <div class="message" style="display:block" ><p><strong>SUCCESS: ALL OF THE PLATFORM'S RECENT ACTIVITY WERE SUCCESSFULLY RESET!</strong></div> 
                                    <?php } elseif($resetAllLogsActive) {?>                                 
                                            <div class="message" style="display:block" ><p><strong>SUCCESS: ALL OF THE PLATFORM'S LOGS AND RECENT ACTIVITY WERE SUCCESSFULLY RESET!</strong></div> 
                                    <?php } ?>
                                    
                                                                          
                                            <form method="POST" >

                                    <div class="row layout-top-spacing">

                                        <div class="col-xl-4 col-lg-4 col-md-4 layout-spacing">
                                            <div class="section general-info section text-center">
                                                <div class="info hghtFix">                                                    
                                                    <h6 class="mb-3">Factory Reset The Cyber Range</h6>
                                                    <div class="alert alert-arrow-right alert-icon-right alert-light-warning alert-dismissible fade show mb-4" role="alert">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12" y2="16"></line></svg>
                                                        <strong><b>Warning!</b></strong> Resetting the Cyber Range will result in factory defaulting the range.
                                                    </div>
                                                    <input type="submit" value="Factory Reset The Cyber Range" name="resetCyberRange" class="btn btn-outline-danger btn-lrg"> 
                                                </div>
                                            </div>
                                        </div>

                                       <div class="col-xl-4 col-lg-4 col-md-4 layout-spacing">
                                        <div class="section general-info text-center">
                                       <div class="info hghtFix">                                      
                                       <h6 class="mb-3">Reset The User's List</h6>
                                                     <div class="alert alert-arrow-right alert-icon-right alert-light-warning alert-dismissible fade show mb-4" role="alert">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12" y2="16"></line></svg>
                                        <strong><b>Warning!</b></strong> Resetting the User's List will remove all users except the Administrative User, but will keep all other Cyber Range Settings
                                       
                                    </div>
                                    
                                            <form action="" method="post" class="form">
                                                <input type="submit" value="Reset The User's List" name="resetUserList" class="btn btn-outline-danger btn-lrg">
                                            </form> 
                                      
                                    </div>
                                       </div>
                                   </div>



                                       <div class="col-xl-4 col-lg-4 col-md-4 layout-spacing">
                                            <div class="section general-info text-center">
                                            <div class="info hghtFix">
                                                
                                                <h6 class="mb-3">Reset The Assets List</h6>
                                                <div class="alert alert-arrow-right alert-icon-right alert-light-warning alert-dismissible fade show mb-4" role="alert">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12" y2="16"></line></svg>
                                                    <strong><b>Warning!</b></strong> Resetting the Asset's List will remove all assets from the Cyber Range, but will keep all other Cyber Range Settings
                                                </div>

                                                <form action="" method="post" class="form">
                                                    <input type="submit" value="Reset The Assets List" name="resetAssetsList" class="btn btn-outline-danger btn-lrg">
                                                </form>
                                            
                                            </div>
                                            </div>
                                       </div>

                                       <div class="col-xl-4 col-lg-4 col-md-4 layout-spacing">
                                            <div class="section general-info text-center">
                                            <div class="info hghtFix">                                                
                                                <h6 class="mb-3">Reset The Asset Groups</h6>
                                                <div class="alert alert-arrow-right alert-icon-right alert-light-warning alert-dismissible fade show mb-4" role="alert">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12" y2="16"></line></svg>
                                                    <strong><b>Warning!</b></strong> Resetting the Asset Groups will remove all asset groups from the Cyber Range, but will keep all other Cyber Range Settings
                                                </div>

                                                <form action="" method="post" class="form">
                                                    <input type="submit" value="Reset The Asset Groups" name="resetAssetsGroupList" class="btn btn-outline-danger btn-lrg">
                                                </form>
                                            
                                            </div>
                                            </div>
                                       </div>


                                       <div class="col-xl-4 col-lg-4 col-md-4 layout-spacing">
                                            <div class="section general-info text-center">
                                                <div class="info hghtFix">                                                    
                                                    <h6 class="mb-3">Remove Team Specific Assets</h6>
                                                    <div class="alert alert-arrow-right alert-icon-right alert-light-warning alert-dismissible fade show mb-4" role="alert">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12" y2="16"></line></svg>
                                                        <strong><b>Warning!</b></strong> Resetting the selected Team(s)' Assets will remove all of their respective Assets from the Cyber Range, but will keep all other Cyber Range Settings
                                                    </div>
                                                    <form action="" method="post" class="form">
                                                    <select id="multipleSelect" name="multipleSelect[]" multiple size="3">
                                                        
                                                        <?php foreach($SQLGetTeam as $SQLGetTeamV){
                                                            echo '<option value="'.$SQLGetTeamV['id'].'">'.$SQLGetTeamV['name'].'</option>';
                                                        } ?>
                                                    </select>

                                                    <input type="submit" value="Reset" name="resetTeamAssetsList" class="btn btn-outline-danger btn-lrg">
                                                        
                                                    </form>
                                                    
                                                </div>
                                            </div>
                                       </div>

                                       <!-- Reset The Login Logs -->
                                        <div class="col-xl-4 col-lg-4 col-md-4 layout-spacing">
                                                <div class="section general-info text-center">
                                                    <div class="info hghtFix">                                                        
                                                        <h6 class="mb-3">Reset The Login Logs</h6>
                                                        <div class="alert alert-arrow-right alert-icon-right alert-light-warning alert-dismissible fade show mb-4" role="alert">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12" y2="16"></line></svg>
                                                            <strong><b>Warning!</b></strong> Resetting the Login Logs will remove all Login Logs from the Cyber Range Database, but will keep all other Cyber Range Settings
                                                        </div>
                                                        <form action="" method="post" class="form">
                                                            <input type="submit" value="Reset The Login Logs" name="resetLoginLogs" class="btn btn-outline-danger btn-lrg">
                                                        </form>
                                                        
                                                    </div>
                                                </div>
                                        </div>
                                       <!-- End Reset The Login Logs -->

                                       <!-- Reset The Activity Logs -->
                                            <div class="col-xl-4 col-lg-4 col-md-4 layout-spacing">
                                                <div class="section general-info text-center">
                                                    <div class="info hghtFix">                                                       
                                                        <h6 class="mb-3">Reset The Activity Logs</h6>
                                                        <div class="alert alert-arrow-right alert-icon-right alert-light-warning alert-dismissible fade show mb-4" role="alert">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12" y2="16"></line></svg>
                                                            <strong><b>Warning!</b></strong> Resetting the Activity Logs will remove all Activity Logs from the Cyber Range Database, but will keep all other Cyber Range Settings
                                                        </div>
                                                        <form action="" method="post" class="form">
                                                            <input type="submit" value="Reset The Activity Logs" name="resetRecentActivityLogs" class="btn btn-outline-danger btn-lrg">
                                                        </form>
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                       <!-- End Reset The Activity Logs -->

                                       <!-- Reset All Logs -->
                                        <div class="col-xl-4 col-lg-4 col-md-4 layout-spacing">
                                                <div class="section general-info text-center">
                                                    <div class="info hghtFix">                                                        
                                                        <h6 class="mb-3">Reset All Logs</h6>
                                                        <div class="alert alert-arrow-right alert-icon-right alert-light-warning alert-dismissible fade show mb-4" role="alert">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12" y2="16"></line></svg>
                                                            <strong><b>Warning!</b></strong> Resetting all of the Cyber Range Logs will remove all Logs from the Cyber Range Database, but will keep all other Cyber Range Settings
                                                        </div>
                                                        <form action="" method="post" class="form">
                                                            <input type="submit" value="Reset All Logs" name="resetAllLogs" class="btn btn-outline-danger btn-lrg">
                                                        </form>
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                       <!-- End Reset All Logs -->
                                   </div>
                                    
                                    </form>
                                    </div>
                                </div>
                       

                                 <div class="tab-pane fade" id="animated-underline-login-log" role="tabpanel" aria-labelledby="animated-underline-login-log-admin-tab">
                                    
                                   
                                    <div class="row">       
                                    <?php if($clearDatabaseLoginLogsActive) {?>                                 
                                        <div class="message" style="display:block" ><p><strong>SUCCESS: ALL OF THE PLATFORM'S LOGS WERE SUCCESSFULLY RESET!</strong></div> 
                                    <?php } ?>                               
                                    <form method="POST" >

                                    <div class="row layout-top-spacing">

                                        <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
                                            <div class="section general-info">
                                                <div class="info style-2 table-responsive">
                                                                
                                <table id="zero-config1" class="table dt-table-hover" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th scope="col">Username</th>
                                            <th class="text-center" scope="col">IP Address</th>
                                            <th class="text-center" scope="col">Login Data</th>
              
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $SQLGetLogs = $odb -> query("SELECT loginip.* FROM `loginip` INNER JOIN users ON users.ID = loginip.userID WHERE users.college_id = $college_id  ORDER BY `date` DESC");
                                        while($getInfo = $SQLGetLogs -> fetch(PDO::FETCH_ASSOC))
                                        {
                                            $username = $getInfo['username'];
                                            $logged = $getInfo['logged'];
                                            date_default_timezone_set('MST');
                                            $date = date("m-d-Y, h:i:s a" ,$getInfo['date']);
                                            echo '<tr><td>'.$username.'</td><td><center>'.$logged.'</center></td><td><center>'.$date.'</center></td></tr>';
                                        }
                                            
                                        ?>
                                    </tbody>
                                </table>
                                                </div>
                                            </div>
                                        </div>

                            
                                   </div>

                                    
                                    </form>
                                    <center><form action="" method="post" class="form"><input type="submit" value="Reset The Login Logs" name="clearDatabaseLoginLogs" class="btn btn-outline-danger btn-lrg"></form></center>
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
            
        </div>
        <!--  END CONTENT AREA  -->
    </div>
    <!-- END MAIN CONTAINER -->


     <!-- Modal -->
 <div class="modal fade customNuw" id="accessCodeModal" tabindex="-1" role="dialog" aria-labelledby="accessCodeModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="accessCodeModalLabel">Access Code</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
              </div>
              <form method="post">
                <div class="modal-body" id="access_code_list">
                
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn" data-bs-dismiss="modal">
                    <i class="flaticon-cancel-12"></i> Discard </button>
                </div>
              </form>
            </div>
          </div>
  </div>

<!-- Modal Ends -->

 
    <?php  require_once './recent_activity.php'; ?>
    <?php  require_once '../footer.php'; ?>
 
    <!--  END CUSTOM SCRIPTS FILE  -->
    <script>
        
         function isNumberKey(evt){
                var charCode = (evt.which) ? evt.which : evt.keyCode
                if (charCode > 31 && (charCode < 48 || charCode > 57))
                    return false;
                return true;
        }

        function getLiveRecentActivities()
        {
            $.ajax({
                url: "<?=BASEURL?>includes/ajax.php",
                type: "post",
                data: {
                    function_name: 'get_live_recent_activities'
                },
                async: false,
                success: function(response) { 
                   
                    res = JSON.parse(response);
                    if (res.status) {
                        $('.timeline-line').html('');
                        $('.timeline-line').html(res.content);
                    } else {
                        
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                }
            });
             setTimeout(function() {
                getLiveRecentActivities();
            }, 5000);
        }

        $(document).ready(function() {
            $("#maintainence_mode").change(function() {
                if(this.checked) { var status = 1;}else{var status = 0;}
                $.ajax({
                        url: "<?=BASEURL?>includes/ajax.php",
                        type: "post",
                        data: {
                            function_name: 'maintainence_mode_change',
                            status:status
                        },
                        async: false,
                        success: function(response) { console.log(response);
                            // res = JSON.parse(response);
                            // if (res.status) {
                               
                            // } else {
                               
                            // }


                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log(textStatus, errorThrown);
                        }
                    });
                
            });  
            
            $("#registration_mode").change(function() {  
                if(this.checked) { var status = 1;}else{var status = 0;}
                $.ajax({
                        url: "<?=BASEURL?>includes/ajax.php",
                        type: "post",
                        data: {   
                            function_name: 'registration_mode_change',
                            college_id:college_id,
                            status:status
                        },
                        async: false,
                        success: function(response) { 
                            res = JSON.parse(response);
                            var teams = res.team_code;
                            var length = res.team_code.length;
                            if (status) 
                            {
                                var accCode = '';
                                $('#access_code_list').text('');
                                for (var i = 0; i < length; i++) 
                                {
                                     accCode += `<p><span class="teamText">`+teams[i]['name']+`</span><span class="teamCode `+teams[i]['team_code']+`">`+teams[i]['team_code']+`</span> <span class="regenerate_key" data-id="`+teams[i]['team_code']+`" data-code="`+teams[i]['team_code']+`" title="Generate a Key">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-clockwise" viewBox="0 0 16 16">
                      <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z"/>
                      <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z"/>
                    </svg>
                  </span></p></p>`;
                                    $('#access_code_list').html(accCode);
                                    
                                }
                                $('#accessCodeModal').modal('show');
                            } else {
                               
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log(textStatus, errorThrown);
                        }
                    });
                
            });  
            
            $("#redTeamCTFactive").change(function() {
                if(this.checked) { var status = 1;}else{var status = 0;}
                $.ajax({
                        url: "<?=BASEURL?>includes/ajax.php",
                        type: "post",
                        data: {
                            function_name: 'redTeamCTFactive',
                            college_id: college_id,
                            status:status
                        },
                        async: false,
                        success: function(response) { console.log(response);
                            // res = JSON.parse(response);
                            // if (res.status) {
                               
                            // } else {
                               
                            // }


                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log(textStatus, errorThrown);
                        }
                    });
                
            });

            getLiveRecentActivities();
        });

       
        $(document).on("click", ".regenerate_key", function() { 
            var code = $(this).attr('data-code');
            var element = $(this).attr('data-id');
            var thiss = $(this);
            $.ajax({
                        url: "<?=BASEURL?>includes/ajax.php",
                        type: "post",
                        data: {
                            function_name: 'regenerate_access_teamkey',
                            college_id: college_id,
                            code:code
                        },
                        async: false,
                        success: function(response) { 
                            res = JSON.parse(response);
                            var team_code = res.team_code;
                            thiss.attr('data-code',team_code);
                            if (res.status) 
                            {
                               $('.'+element).text('').text(team_code);
                            }
                            else{  }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log(textStatus, errorThrown);
                        }
                    });
        });
        

     
    </script>

       <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
   
    <!-- <script src="../src/assets/js/dashboard/dash_2.js"></script> -->
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->

    <?php  require_once 'statistics.php'; ?>
    <?php  require_once 'map-js.php'; ?>

 
    <script>
        function openModal()
        {
            $('#exampleModal').modal('show');
        }
         $(document).ready( function() {
            <?php if( $resetUserListActive || $resetCyberRangeActive || $resetAssetsListActive || $resetSystemGroupListActive ||  $resetTeamAssetsListActive || $resetLoginLogsActive || $resetRecentActivityLogsActive || $resetAllLogsActive ){?>  
                $('#animated-underline-dashboard-tab').removeClass("active");          
                $('#animated-underline-dashboard').removeClass("active").removeClass("show");
                $('#animated-underline-range-admin-tab').addClass('active');
                $('#animated-underline-range-admin').addClass("active").addClass("show");
            <?php }?>  

            <?php if( $clearDatabaseLoginLogsActive ){?>  
                $('#animated-underline-dashboard-tab').removeClass("active");          
                $('#animated-underline-dashboard').removeClass("active").removeClass("show");
                $('#animated-underline-login-log-admin-tab').addClass('active');
                $('#animated-underline-login-log').addClass("active").addClass("show");
            <?php }?> 


            
           
        });
    </script>

 
   <script>
        $('#zero-config1, #zero-config2').DataTable({
            "dom": "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l><'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>>>" +
         "<''tr>" +
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
    <script type="text/javascript">

selectBox3 = new vanillaSelectBox("#multipleSelect", {
    "minWidth":178,
    "maxHeight": 200,
    "placeHolder": "Choose..." ,
     "keepInlineStyles":true,
    "search": true,
    "stayOpen":false
});
    </script>
  
    
</body>
</html>