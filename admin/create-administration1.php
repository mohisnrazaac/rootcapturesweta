<?php
ob_start();
require_once '../includes/db.php';
require_once '../includes/init.php';

    $resetCyberRangeActive =  false;
    $resetUserListActive =  false;
    $resetAssetsListActive = false;
    $resetPurpleAssetsListActive = false;
    $resetRedAssetsListActive = false;
    $clearDatabaseLoginLogsActive = false;

    if (isset($_POST['resetCyberRange']))
    {
        //Delete all user except AdminDemo
        $SQL = $odb -> prepare("DELETE FROM `users` WHERE `username` != :username");
        $SQL -> execute(array(':username' => 'AdminDemo'));

        $SQL = $odb -> query("TRUNCATE `blueservers`");
        $SQL = $odb -> query("TRUNCATE `purpleservers`");
        $SQL = $odb -> query("TRUNCATE `redservers`");
        $SQL = $odb -> query("TRUNCATE `loginip`");
        $resetCyberRangeActive = true;
    }

    if (isset($_POST['resetUserList']))
    {
        //Delete all user except AdminDemo
        $SQL = $odb -> prepare("DELETE FROM `users` WHERE `username` != :username");
        $SQL -> execute(array(':username' => 'AdminDemo'));
        $resetUserListActive = true;
       
    }    

    if (isset($_POST['resetAssetsList']))
    {
        //Delete all user except AdminDemo
        $SQL = $odb -> query("TRUNCATE `blueservers`");
        $SQL = $odb -> query("TRUNCATE `purpleservers`");
        $SQL = $odb -> query("TRUNCATE `redservers`");
        $resetAssetsListActive = true;
    }

    if (isset($_POST['resetPurpleAssetsList']))
    {
        //Delete all user except AdminDemo
        $SQL = $odb -> query("TRUNCATE `purpleservers`");
        $resetPurpleAssetsListActive = true;
    } 
    
    if (isset($_POST['resetRedAssetsList']))
    {
        //Delete all user except AdminDemo
        $SQL = $odb -> query("TRUNCATE `redservers`");
        $resetRedAssetsListActive = true;
    } 

    if (isset($_POST['clearDatabaseLoginLogs']))
    {
        $SQL = $odb -> query("TRUNCATE `loginip`");
        $clearDatabaseLoginLogsActive = true;
    }

    //Grapgh Data Queries
        $sqlTotalUsers = $odb -> query("SELECT count(id) as total_user FROM `users`");
        $totalUser = $sqlTotalUsers->fetchColumn();
        
        $sqlTotalUsersRed = $odb -> query("SELECT count(id) as total_user FROM `users` WHERE rank = 3");
        $totalUserRedTeam = $sqlTotalUsersRed->fetchColumn();

        $sqlTotalUsersBlue = $odb -> query("SELECT count(id) as total_user FROM `users` WHERE rank = 4");
        $totalUserBlueTeam = $sqlTotalUsersBlue->fetchColumn();

        $sqlTotalUsersPurple = $odb -> query("SELECT count(id) as total_user FROM `users` WHERE rank = 5");
        $totalUserPurpleTeam = $sqlTotalUsersPurple->fetchColumn();

        $sqlTotalUsersAdmin = $odb -> query("SELECT count(id) as total_user FROM `users` WHERE rank = 1");
        $totalUserAdminTeam = $sqlTotalUsersAdmin->fetchColumn();

        $sqlTotalUsersAdministrator = $odb -> query("SELECT count(id) as total_user FROM `users` WHERE rank = 2");
        $totalUserAdministratorTeam = $sqlTotalUsersAdministrator->fetchColumn();

    //Queries to get total assets
        $sqlTotalRedServer = $odb -> query("SELECT count(ID) as total FROM `redservers`");
        $sqlTotalRedServer = $sqlTotalRedServer->fetchColumn();

        $sqlTotalPurpleServer = $odb -> query("SELECT count(ID) as total FROM `purpleservers`");
        $sqlTotalPurpleServer = $sqlTotalPurpleServer->fetchColumn();

        $sqlTotalBlueServer = $odb -> query("SELECT count(ID) as total FROM `blueservers`");
        $sqlTotalBlueServer = $sqlTotalBlueServer->fetchColumn();

    //monthly red teams total user query
        $totalUserRedJan = $totalUserRedFeb = $totalUserRedMar = $totalUserRedApr = $totalUserRedMay = $totalUserRedJun = $totalUserRedJul = $totalUserRedAug = $totalUserRedSep = $totalUserRedOct = $totalUserRedNov = $totalUserRedDec = 0;

        $sqlTotalUserRedJan = $odb -> query("SELECT count(id) as month_wise FROM users`datetime` WHERE MONTH(`datetime`) = 1 AND rank = 3");
        $totalUserRedJan = $sqlTotalUserRedJan->fetchColumn(); 

        $sqlTotalUserRedFeb = $odb -> query("SELECT count(id) as month_wise FROM users`datetime` WHERE MONTH(`datetime`) = 2 AND rank = 3");
        $totalUserRedFeb = $sqlTotalUserRedFeb->fetchColumn();
        
        $sqlTotalUserRedMar = $odb -> query("SELECT count(id) as month_wise FROM users`datetime` WHERE MONTH(`datetime`) = 3 AND rank = 3");
        $totalUserRedMar = $sqlTotalUserRedMar->fetchColumn();

        $sqlTotalUserRedApr = $odb -> query("SELECT count(id) as month_wise FROM users`datetime` WHERE MONTH(`datetime`) = 4 AND rank = 3");
        $totalUserRedApr = $sqlTotalUserRedApr->fetchColumn();

        $sqlTotalUserRedMay = $odb -> query("SELECT count(id) as month_wise FROM users`datetime` WHERE MONTH(`datetime`) = 5 AND rank = 3");
        $totalUserRedMay = $sqlTotalUserRedMay->fetchColumn();

        $sqlTotalUserRedJun = $odb -> query("SELECT count(id) as month_wise FROM users`datetime` WHERE MONTH(`datetime`) = 6 AND rank = 3");
        $totalUserRedJun = $sqlTotalUserRedJun->fetchColumn();

        $sqlTotalUserRedJul = $odb -> query("SELECT count(id) as month_wise FROM users`datetime` WHERE MONTH(`datetime`) = 7 AND rank = 3");
        $totalUserRedJul = $sqlTotalUserRedJul->fetchColumn();

        $sqlTotalUserRedAug = $odb -> query("SELECT count(id) as month_wise FROM users`datetime` WHERE MONTH(`datetime`) = 8 AND rank = 3");
        $totalUserRedAug = $sqlTotalUserRedAug->fetchColumn();

        $sqlTotalUserRedSep = $odb -> query("SELECT count(id) as month_wise FROM users`datetime` WHERE MONTH(`datetime`) = 9 AND rank = 3");
        $totalUserRedSep = $sqlTotalUserRedSep->fetchColumn();

        $sqlTotalUserRedOct = $odb -> query("SELECT count(id) as month_wise FROM users`datetime` WHERE MONTH(`datetime`) = 10 AND rank = 3");
        $totalUserRedOct = $sqlTotalUserRedOct->fetchColumn();

        $sqlTotalUserRedNov = $odb -> query("SELECT count(id) as month_wise FROM users`datetime` WHERE MONTH(`datetime`) = 11 AND rank = 3");
        $totalUserRedNov = $sqlTotalUserRedNov->fetchColumn();

        $sqlTotalUserRedDec = $odb -> query("SELECT count(id) as month_wise FROM users`datetime` WHERE MONTH(`datetime`) = 12 AND rank = 3");
        $totalUserRedDec = $sqlTotalUserRedDec->fetchColumn();
    //end
    
    //monthly blue teams total user query
        $totalUserBlueJan = $totalUserBlueFeb = $totalUserBlueMar = $totalUserBlueApr = $totalUserBlueMay = $totalUserBlueJun = $totalUserBlueJul = $totalUserBlueAug = $totalUserBlueSep = $totalUserBlueOct = $totalUserBlueNov = $totalUserBlueDec = 0;

        $sqlTotalUserBlueJan = $odb -> query("SELECT count(id) as month_wise FROM users`datetime` WHERE MONTH(`datetime`) = 1 AND rank = 4");
        $totalUserBlueJan = $sqlTotalUserBlueJan->fetchColumn(); 

        $sqlTotalUserBlueFeb = $odb -> query("SELECT count(id) as month_wise FROM users`datetime` WHERE MONTH(`datetime`) = 2 AND rank = 4");
        $totalUserBlueFeb = $sqlTotalUserBlueFeb->fetchColumn();
        
        $sqlTotalUserBlueMar = $odb -> query("SELECT count(id) as month_wise FROM users`datetime` WHERE MONTH(`datetime`) = 3 AND rank = 4");
        $totalUserBlueMar = $sqlTotalUserBlueMar->fetchColumn();

        $sqlTotalUserBlueApr = $odb -> query("SELECT count(id) as month_wise FROM users`datetime` WHERE MONTH(`datetime`) = 4 AND rank = 4");
        $totalUserBlueApr = $sqlTotalUserBlueApr->fetchColumn();

        $sqlTotalUserBlueMay = $odb -> query("SELECT count(id) as month_wise FROM users`datetime` WHERE MONTH(`datetime`) = 5 AND rank = 4");
        $totalUserBlueMay = $sqlTotalUserBlueMay->fetchColumn();

        $sqlTotalUserBlueJun = $odb -> query("SELECT count(id) as month_wise FROM users`datetime` WHERE MONTH(`datetime`) = 6 AND rank = 4");
        $totalUserBlueJun = $sqlTotalUserBlueJun->fetchColumn();

        $sqlTotalUserBlueJul = $odb -> query("SELECT count(id) as month_wise FROM users`datetime` WHERE MONTH(`datetime`) = 7 AND rank = 4");
        $totalUserBlueJul = $sqlTotalUserBlueJul->fetchColumn();

        $sqlTotalUserBlueAug = $odb -> query("SELECT count(id) as month_wise FROM users`datetime` WHERE MONTH(`datetime`) = 8 AND rank = 4");
        $totalUserBlueAug = $sqlTotalUserBlueAug->fetchColumn();

        $sqlTotalUserBlueSep = $odb -> query("SELECT count(id) as month_wise FROM users`datetime` WHERE MONTH(`datetime`) = 9 AND rank = 4");
        $totalUserBlueSep = $sqlTotalUserBlueSep->fetchColumn();

        $sqlTotalUserBlueOct = $odb -> query("SELECT count(id) as month_wise FROM users`datetime` WHERE MONTH(`datetime`) = 10 AND rank = 4");
        $totalUserBlueOct = $sqlTotalUserBlueOct->fetchColumn();

        $sqlTotalUserBlueNov = $odb -> query("SELECT count(id) as month_wise FROM users`datetime` WHERE MONTH(`datetime`) = 11 AND rank = 4");
        $totalUserBlueNov = $sqlTotalUserBlueNov->fetchColumn();

        $sqlTotalUserBlueDec = $odb -> query("SELECT count(id) as month_wise FROM users`datetime` WHERE MONTH(`datetime`) = 12 AND rank = 4");
        $totalUserBlueDec = $sqlTotalUserBlueDec->fetchColumn();
    // end

    //monthly Purple teams total user query
        $totalUserPurpleJan = $totalUserPurpleFeb = $totalUserPurpleMar = $totalUserPurpleApr = $totalUserPurpleMay = $totalUserPurpleJun = $totalUserPurpleJul = $totalUserPurpleAug = $totalUserPurpleSep = $totalUserPurpleOct = $totalUserPurpleNov = $totalUserPurpleDec = 0;

        $sqlTotalUserPurpleJan = $odb -> query("SELECT count(id) as month_wise FROM users`datetime` WHERE MONTH(`datetime`) = 1 AND rank = 5");
        $totalUserPurpleJan = $sqlTotalUserPurpleJan->fetchColumn(); 

        $sqlTotalUserPurpleFeb = $odb -> query("SELECT count(id) as month_wise FROM users`datetime` WHERE MONTH(`datetime`) = 2 AND rank = 5");
        $totalUserPurpleFeb = $sqlTotalUserPurpleFeb->fetchColumn();
        
        $sqlTotalUserPurpleMar = $odb -> query("SELECT count(id) as month_wise FROM users`datetime` WHERE MONTH(`datetime`) = 3 AND rank = 5");
        $totalUserPurpleMar = $sqlTotalUserPurpleMar->fetchColumn();

        $sqlTotalUserPurpleApr = $odb -> query("SELECT count(id) as month_wise FROM users`datetime` WHERE MONTH(`datetime`) = 4 AND rank = 5");
        $totalUserPurpleApr = $sqlTotalUserPurpleApr->fetchColumn();

        $sqlTotalUserPurpleMay = $odb -> query("SELECT count(id) as month_wise FROM users`datetime` WHERE MONTH(`datetime`) = 5 AND rank = 5");
        $totalUserPurpleMay = $sqlTotalUserPurpleMay->fetchColumn();

        $sqlTotalUserPurpleJun = $odb -> query("SELECT count(id) as month_wise FROM users`datetime` WHERE MONTH(`datetime`) = 6 AND rank = 5");
        $totalUserPurpleJun = $sqlTotalUserPurpleJun->fetchColumn();

        $sqlTotalUserPurpleJul = $odb -> query("SELECT count(id) as month_wise FROM users`datetime` WHERE MONTH(`datetime`) = 7 AND rank = 5");
        $totalUserPurpleJul = $sqlTotalUserPurpleJul->fetchColumn();

        $sqlTotalUserPurpleAug = $odb -> query("SELECT count(id) as month_wise FROM users`datetime` WHERE MONTH(`datetime`) = 8 AND rank = 5");
        $totalUserPurpleAug = $sqlTotalUserPurpleAug->fetchColumn();

        $sqlTotalUserPurpleSep = $odb -> query("SELECT count(id) as month_wise FROM users`datetime` WHERE MONTH(`datetime`) = 9 AND rank = 5");
        $totalUserPurpleSep = $sqlTotalUserPurpleSep->fetchColumn();

        $sqlTotalUserPurpleOct = $odb -> query("SELECT count(id) as month_wise FROM users`datetime` WHERE MONTH(`datetime`) = 10 AND rank = 5");
        $totalUserPurpleOct = $sqlTotalUserPurpleOct->fetchColumn();

        $sqlTotalUserPurpleNov = $odb -> query("SELECT count(id) as month_wise FROM users`datetime` WHERE MONTH(`datetime`) = 11 AND rank = 5");
        $totalUserPurpleNov = $sqlTotalUserPurpleNov->fetchColumn();

        $sqlTotalUserPurpleDec = $odb -> query("SELECT count(id) as month_wise FROM users`datetime` WHERE MONTH(`datetime`) = 12 AND rank = 5");
        $totalUserPurpleDec = $sqlTotalUserPurpleDec->fetchColumn();
    // end

    //monthly Admin teams total user query
        $totalUserAdminJan = $totalUserAdminFeb = $totalUserAdminMar = $totalUserAdminApr = $totalUserAdminMay = $totalUserAdminJun = $totalUserAdminJul = $totalUserAdminAug = $totalUserAdminSep = $totalUserAdminOct = $totalUserAdminNov = $totalUserAdminDec = 0;

        $sqlTotalUserAdminJan = $odb -> query("SELECT count(id) as month_wise FROM users`datetime` WHERE MONTH(`datetime`) = 1 AND rank = 1");
        $totalUserAdminJan = $sqlTotalUserAdminJan->fetchColumn(); 

        $sqlTotalUserAdminFeb = $odb -> query("SELECT count(id) as month_wise FROM users`datetime` WHERE MONTH(`datetime`) = 2 AND rank = 1");
        $totalUserAdminFeb = $sqlTotalUserAdminFeb->fetchColumn();
        
        $sqlTotalUserAdminMar = $odb -> query("SELECT count(id) as month_wise FROM users`datetime` WHERE MONTH(`datetime`) = 3 AND rank = 1");
        $totalUserAdminMar = $sqlTotalUserAdminMar->fetchColumn();

        $sqlTotalUserAdminApr = $odb -> query("SELECT count(id) as month_wise FROM users`datetime` WHERE MONTH(`datetime`) = 4 AND rank = 1");
        $totalUserAdminApr = $sqlTotalUserAdminApr->fetchColumn();

        $sqlTotalUserAdminMay = $odb -> query("SELECT count(id) as month_wise FROM users`datetime` WHERE MONTH(`datetime`) = 5 AND rank = 1");
        $totalUserAdminMay = $sqlTotalUserAdminMay->fetchColumn();

        $sqlTotalUserAdminJun = $odb -> query("SELECT count(id) as month_wise FROM users`datetime` WHERE MONTH(`datetime`) = 6 AND rank = 1");
        $totalUserAdminJun = $sqlTotalUserAdminJun->fetchColumn();

        $sqlTotalUserAdminJul = $odb -> query("SELECT count(id) as month_wise FROM users`datetime` WHERE MONTH(`datetime`) = 7 AND rank = 1");
        $totalUserAdminJul = $sqlTotalUserAdminJul->fetchColumn();

        $sqlTotalUserAdminAug = $odb -> query("SELECT count(id) as month_wise FROM users`datetime` WHERE MONTH(`datetime`) = 8 AND rank = 1");
        $totalUserAdminAug = $sqlTotalUserAdminAug->fetchColumn();

        $sqlTotalUserAdminSep = $odb -> query("SELECT count(id) as month_wise FROM users`datetime` WHERE MONTH(`datetime`) = 9 AND rank = 1");
        $totalUserAdminSep = $sqlTotalUserAdminSep->fetchColumn();

        $sqlTotalUserAdminOct = $odb -> query("SELECT count(id) as month_wise FROM users`datetime` WHERE MONTH(`datetime`) = 10 AND rank = 1");
        $totalUserAdminOct = $sqlTotalUserAdminOct->fetchColumn();

        $sqlTotalUserAdminNov = $odb -> query("SELECT count(id) as month_wise FROM users`datetime` WHERE MONTH(`datetime`) = 11 AND rank = 1");
        $totalUserAdminNov = $sqlTotalUserAdminNov->fetchColumn();

        $sqlTotalUserAdminDec = $odb -> query("SELECT count(id) as month_wise FROM users`datetime` WHERE MONTH(`datetime`) = 12 AND rank = 1");
        $totalUserAdminDec = $sqlTotalUserAdminDec->fetchColumn();
    // end

    //monthly Assistant teams total user query
        $totalUserAssistantJan = $totalUserAssistantFeb = $totalUserAssistantMar = $totalUserAssistantApr = $totalUserAssistantMay = $totalUserAssistantJun = $totalUserAssistantJul = $totalUserAssistantAug = $totalUserAssistantSep = $totalUserAssistantOct = $totalUserAssistantNov = $totalUserAssistantDec = 0;

        $sqlTotalUserAssistantJan = $odb -> query("SELECT count(id) as month_wise FROM users`datetime` WHERE MONTH(`datetime`) = 1 AND rank = 2");
        $totalUserAssistantJan = $sqlTotalUserAssistantJan->fetchColumn(); 

        $sqlTotalUserAssistantFeb = $odb -> query("SELECT count(id) as month_wise FROM users`datetime` WHERE MONTH(`datetime`) = 2 AND rank = 2");
        $totalUserAssistantFeb = $sqlTotalUserAssistantFeb->fetchColumn();
        
        $sqlTotalUserAssistantMar = $odb -> query("SELECT count(id) as month_wise FROM users`datetime` WHERE MONTH(`datetime`) = 3 AND rank = 2");
        $totalUserAssistantMar = $sqlTotalUserAssistantMar->fetchColumn();

        $sqlTotalUserAssistantApr = $odb -> query("SELECT count(id) as month_wise FROM users`datetime` WHERE MONTH(`datetime`) = 4 AND rank = 2");
        $totalUserAssistantApr = $sqlTotalUserAssistantApr->fetchColumn();

        $sqlTotalUserAssistantMay = $odb -> query("SELECT count(id) as month_wise FROM users`datetime` WHERE MONTH(`datetime`) = 5 AND rank = 2");
        $totalUserAssistantMay = $sqlTotalUserAssistantMay->fetchColumn();

        $sqlTotalUserAssistantJun = $odb -> query("SELECT count(id) as month_wise FROM users`datetime` WHERE MONTH(`datetime`) = 6 AND rank = 2");
        $totalUserAssistantJun = $sqlTotalUserAssistantJun->fetchColumn();

        $sqlTotalUserAssistantJul = $odb -> query("SELECT count(id) as month_wise FROM users`datetime` WHERE MONTH(`datetime`) = 7 AND rank = 2");
        $totalUserAssistantJul = $sqlTotalUserAssistantJul->fetchColumn();

        $sqlTotalUserAssistantAug = $odb -> query("SELECT count(id) as month_wise FROM users`datetime` WHERE MONTH(`datetime`) = 8 AND rank = 2");
        $totalUserAssistantAug = $sqlTotalUserAssistantAug->fetchColumn();

        $sqlTotalUserAssistantSep = $odb -> query("SELECT count(id) as month_wise FROM users`datetime` WHERE MONTH(`datetime`) = 9 AND rank = 2");
        $totalUserAssistantSep = $sqlTotalUserAssistantSep->fetchColumn();

        $sqlTotalUserAssistantOct = $odb -> query("SELECT count(id) as month_wise FROM users`datetime` WHERE MONTH(`datetime`) = 10 AND rank = 2");
        $totalUserAssistantOct = $sqlTotalUserAssistantOct->fetchColumn();

        $sqlTotalUserAssistantNov = $odb -> query("SELECT count(id) as month_wise FROM users`datetime` WHERE MONTH(`datetime`) = 11 AND rank = 2");
        $totalUserAssistantNov = $sqlTotalUserAssistantNov->fetchColumn();

        $sqlTotalUserAssistantDec = $odb -> query("SELECT count(id) as month_wise FROM users`datetime` WHERE MONTH(`datetime`) = 12 AND rank = 2");
        $totalUserAssistantDec = $sqlTotalUserAssistantDec->fetchColumn();
    // end



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
        
                                                <div class="widget-content">
                                                    <div id="revenueMonthly"></div>
                                                </div>
                                            </div> 
                                        </div>

                           <div class="col-xl-4 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                                <div class="widget widget-chart-two">
                                    <div class="widget-heading">
                                        <h5 class="">Active / Inactive Sessions</h5>
                                    </div>
                                    <div class="widget-content">
                                        <div id="chart-2" class=""></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-4 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                                <div class="widget widget-chart-two">
                                    <div class="widget-heading">
                                        <h5 class="">Users</h5>
                                    </div>
                                    <div class="widget-content">
                                        <div id="chart-3" class=""></div>
                                    </div>
                                </div>
                            </div>


                            <div class="col-xl-4 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                                <div class="widget widget-chart-two">
                                    <div class="widget-heading">
                                        <h5 class="">Total Number of Assets</h5>
                                    </div>
                                    <div class="widget-content">
                                        <div id="chart-4" class=""></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
        
                                <div class="widget widget-activity-four">
        
                                    <div class="widget-heading">
                                        <h5 class="">Recent Activities</h5>
                                    </div>
        
                                    <div class="widget-content">
        
                                        <div class="mt-container-ra mx-auto">
                                            <div class="timeline-line">
        
                                                <div class="item-timeline timeline-primary">
                                                    <div class="t-dot" data-original-title="" title="">
                                                    </div>
                                                    <div class="t-text">
                                                        <p><span>Updated</span> Server Logs</p>
                                                        <span class="badge">Pending</span>
                                                        <p class="t-time">Just Now</p>
                                                    </div>
                                                </div>
        
                                                <div class="item-timeline timeline-success">
                                                    <div class="t-dot" data-original-title="" title="">
                                                    </div>
                                                    <div class="t-text">
                                                        <p>Send Mail to <a href="javascript:void(0);">HR</a> and <a href="javascript:void(0);">Admin</a></p>
                                                        <span class="badge">Completed</span>
                                                        <p class="t-time">2 min ago</p>
                                                    </div>
                                                </div>
        
                                                <div class="item-timeline  timeline-danger">
                                                    <div class="t-dot" data-original-title="" title="">
                                                    </div>
                                                    <div class="t-text">
                                                        <p>Backup <span>Files EOD</span></p>
                                                        <span class="badge">Pending</span>
                                                        <p class="t-time">14:00</p>
                                                    </div>
                                                </div>
        
                                                <div class="item-timeline  timeline-dark">
                                                    <div class="t-dot" data-original-title="" title="">
                                                    </div>
                                                    <div class="t-text">
                                                        <p>Collect documents from <a href="javascript:void(0);">Sara</a></p>
                                                        <span class="badge">Completed</span>
                                                        <p class="t-time">16:00</p>
                                                    </div>
                                                </div>
        
                                                <div class="item-timeline  timeline-warning">
                                                    <div class="t-dot" data-original-title="" title="">
                                                    </div>
                                                    <div class="t-text">
                                                        <p>Conference call with <a href="javascript:void(0);">Marketing Manager</a>.</p>
                                                        <span class="badge">In progress</span>
                                                        <p class="t-time">17:00</p>
                                                    </div>
                                                </div>
        
                                                <div class="item-timeline  timeline-secondary">
                                                    <div class="t-dot" data-original-title="" title="">
                                                    </div>
                                                    <div class="t-text">
                                                        <p>Rebooted Server</p>
                                                        <span class="badge">Completed</span>
                                                        <p class="t-time">17:00</p>
                                                    </div>
                                                </div>
        
                                                <div class="item-timeline  timeline-warning">
                                                    <div class="t-dot" data-original-title="" title="">
                                                    </div>
                                                    <div class="t-text">
                                                        <p>Send contract details to Freelancer</p>
                                                        <span class="badge">Pending</span>
                                                        <p class="t-time">18:00</p>
                                                    </div>
                                                </div>
        
                                                <div class="item-timeline  timeline-dark">
                                                    <div class="t-dot" data-original-title="" title="">
                                                    </div>
                                                    <div class="t-text">
                                                        <p>Kelly want to increase the time of the project.</p>
                                                        <span class="badge">In Progress</span>
                                                        <p class="t-time">19:00</p>
                                                    </div>
                                                </div>
        
                                                <div class="item-timeline  timeline-success">
                                                    <div class="t-dot" data-original-title="" title="">
                                                    </div>
                                                    <div class="t-text">
                                                        <p>Server down for maintanence</p>
                                                        <span class="badge">Completed</span>
                                                        <p class="t-time">19:00</p>
                                                    </div>
                                                </div>
        
                                                <div class="item-timeline  timeline-secondary">
                                                    <div class="t-dot" data-original-title="" title="">
                                                    </div>
                                                    <div class="t-text">
                                                        <p>Malicious link detected</p>
                                                        <span class="badge">Block</span>
                                                        <p class="t-time">20:00</p>
                                                    </div>
                                                </div>
        
                                                <div class="item-timeline  timeline-warning">
                                                    <div class="t-dot" data-original-title="" title="">
                                                    </div>
                                                    <div class="t-text">
                                                        <p>Rebooted Server</p>
                                                        <span class="badge">Completed</span>
                                                        <p class="t-time">23:00</p>
                                                    </div>
                                                </div>
        
                                                <div class="item-timeline timeline-primary">
                                                    <div class="t-dot" data-original-title="" title="">
                                                    </div>
                                                    <div class="t-text">
                                                        <p><span>Updated</span> Server Logs</p>
                                                        <span class="badge">Pending</span>
                                                        <p class="t-time">Just Now</p>
                                                    </div>
                                                </div>
        
                                                <div class="item-timeline timeline-success">
                                                    <div class="t-dot" data-original-title="" title="">
                                                    </div>
                                                    <div class="t-text">
                                                        <p>Send Mail to <a href="javascript:void(0);">HR</a> and <a href="javascript:void(0);">Admin</a></p>
                                                        <span class="badge">Completed</span>
                                                        <p class="t-time">2 min ago</p>
                                                    </div>
                                                </div>
        
                                                <div class="item-timeline  timeline-danger">
                                                    <div class="t-dot" data-original-title="" title="">
                                                    </div>
                                                    <div class="t-text">
                                                        <p>Backup <span>Files EOD</span></p>
                                                        <span class="badge">Pending</span>
                                                        <p class="t-time">14:00</p>
                                                    </div>
                                                </div>
        
                                                <div class="item-timeline  timeline-dark">
                                                    <div class="t-dot" data-original-title="" title="">
                                                    </div>
                                                    <div class="t-text">
                                                        <p>Collect documents from <a href="javascript:void(0);">Sara</a></p>
                                                        <span class="badge">Completed</span>
                                                        <p class="t-time">16:00</p>
                                                    </div>
                                                </div>
        
                                                <div class="item-timeline  timeline-warning">
                                                    <div class="t-dot" data-original-title="" title="">
                                                    </div>
                                                    <div class="t-text">
                                                        <p>Conference call with <a href="javascript:void(0);">Marketing Manager</a>.</p>
                                                        <span class="badge">In progress</span>
                                                        <p class="t-time">17:00</p>
                                                    </div>
                                                </div>
        
                                            </div>
                                        </div>
        
                                        <div class="tm-action-btn">
                                            <button class="btn"><span>View All</span> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg></button>
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
                                    ?>
    
                                    <form method="POST" >
                                        <div class="row layout-top-spacing">
                                        <div class="col-xl-4 col-lg-4 col-md-4 layout-spacing">
                                            <div class="section general-info">
                                                <div class="info">
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
                                            
                                                <div class="info">
                                                    <h6 class="">Activate Red Team CTF</h6>
                                                    
                                                    <div class="form-group mt-4">
                                                        <div class="switch form-switch-custom switch-inline form-switch-success mt-1">
                                                            <input class="switch-input" <?php if($editstatus == 1){echo 'checked'; } ?> name="user_act_dea" id="user_act_dea" type="checkbox" role="switch" id="socialformprofile-custom-switch-success">
                                                            <input type="hidden" name="checkForm" value="formSubmit" />
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
                                    <form method="POST" >

                                    <div class="row layout-top-spacing">

                                        <div class="col-xl-4 col-lg-4 col-md-4 layout-spacing">
                                            <div class="section general-info">
                                                <div class="info hghtFix">
                                                    <?php if($resetCyberRangeActive) {?>                                 
                                                        <div class="message" style="display:block" id="user_act_succ_message"><p><strong>SUCCESS: All the server and user has been truncated except the admin user!</strong></div> 
                                                    <?php } ?>
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
                                        <div class="section general-info">
                                       <div class="info hghtFix">
                                       <?php if($resetUserListActive) {?>                                 
                                                <div class="message" style="display:block" id="user_act_succ_message"><p><strong>SUCCESS: All the user except the admin user has been truncated successfully!</strong></div> 
                                       <?php } ?>
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
                                        <div class="section general-info">
                                          <div class="info hghtFix">
                                          <?php if($resetAssetsListActive) {?>                                 
                                                <div class="message" style="display:block" id="user_act_succ_message"><p><strong>SUCCESS: All the assets has been truncated successfully!</strong></div> 
                                       <?php } ?>
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
                                        <div class="section general-info">
                                     
                                    <div class="info hghtFix">
                                        <?php if($resetPurpleAssetsListActive) {?>                                 
                                                <div class="message" style="display:block" id="user_act_succ_message"><p><strong>SUCCESS: All the assets has been truncated successfully!</strong></div> 
                                       <?php } ?>
                                       <h6 class="mb-3">Reset Purple Team's Assets</h6>
                                        <div class="alert alert-arrow-right alert-icon-right alert-light-warning alert-dismissible fade show mb-4" role="alert">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12" y2="16"></line></svg>
                                            <strong><b>Warning!</b></strong> Resetting the Purple Team Assets will remove all Purple Team Assets from the Cyber Range, but will keep all other Cyber Range Settings
                                        </div>
                                            <form action="" method="post" class="form">
                                                <input type="submit" value="Reset Purple Team's Assets" name="resetPurpleAssetsList" class="btn btn-outline-danger btn-lrg">
                                            </form>
                                        
                                    </div>
                                </div>
                                       </div>



                                       <div class="col-xl-4 col-lg-4 col-md-4 layout-spacing">
                                        <div class="section general-info">
                                      <div class="info hghtFix">
                                      <?php if($resetRedAssetsListActive) {?>                                 
                                                <div class="message" style="display:block" id="user_act_succ_message"><p><strong>SUCCESS: All the assets has been truncated successfully!</strong></div> 
                                       <?php } ?>
                                       <h6 class="mb-3">Reset Red Team's Assets</h6>
                                        <div class="alert alert-arrow-right alert-icon-right alert-light-warning alert-dismissible fade show mb-4" role="alert">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12" y2="16"></line></svg>
                                            <strong><b>Warning!</b></strong> Resetting the Red Team Assets will remove all Red Team Assets from the Cyber Range, but will keep all other Cyber Range Settings
                                        </div>
                                            <form action="" method="post" class="form">
                                                <input type="submit" value="Reset Red Team's Assets" name="resetRedAssetsList" class="btn btn-outline-danger btn-lrg">
                                            </form>
                                        
                                    </div>
                                </div>

                                       </div>
                                   </div>
                                    
                                    </form>
                                    </div>
                                </div>
                       

                                 <div class="tab-pane fade" id="animated-underline-login-log" role="tabpanel" aria-labelledby="animated-underline-login-log-admin-tab">
                                    
                                   
                                    <div class="row">                                      
                                    <form method="POST" >

                                    <div class="row layout-top-spacing">

                                        <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
                                            <div class="section general-info">
                                                <div class="info ">
                                                                
                                <table id="zero-config" class="table dt-table-hover" style="width:100%">
                                    <thead>
                                        <tr>
                <th scope="col">Username</th>
                <th class="text-center" scope="col">IP Address</th>
                <th class="text-center" scope="col">Login Data</th>
              
                                        </tr>
                                    </thead>
                                    <tbody>
                <?php
                $SQLGetLogs = $odb -> query("SELECT * FROM `loginip` ORDER BY `date` DESC");
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
                                    <center><form action="" method="post" class="form"><input type="submit" value="Clear Database Login Logs" name="clearDatabaseLoginLogs" class="btn btn-outline-danger btn-lrg"></form></center>
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

 
    <?php  require_once '../footer.php'; ?>
 
    <!--  END CUSTOM SCRIPTS FILE  -->
    <script>
         function isNumberKey(evt){
                var charCode = (evt.which) ? evt.which : evt.keyCode
                if (charCode > 31 && (charCode < 48 || charCode > 57))
                    return false;
                return true;
        }

        $(document).ready(function() {
            $("#maintainence_mode").change(function() {
                if(this.checked) {
                    alert("checked");
                }
                else
                {
                    alert("not checked");
                }
            });           
        });

     
    </script>

       <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
   
    <!-- <script src="../src/assets/js/dashboard/dash_2.js"></script> -->
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->

    <?php  require_once 'statistics.php'; ?>

 
    <script>
         $(document).ready( function() {
            <?php if( $resetUserListActive || $resetCyberRangeActive || $resetAssetsListActive || $resetPurpleAssetsListActive || $resetRedAssetsListActive ){?>  
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

   <script type="text/javascript">
        /*
        Template Name: HUD - Responsive Bootstrap 5 Admin Template
        Version: 1.9.0
        Author: Sean Ngu
        Website: http://www.seantheme.com/hud/
        */

        var randomNo = function() {
        return Math.floor(Math.random() * 60) + 30
        };

        var handleRenderChart = function() {
            // global apexchart settings
            Apex = {
                title: {
                    style: {
                        fontSize:  '14px',
                        fontWeight:  'bold',
                        fontFamily:  app.font.family,
                        color:  app.color.white
                    },
                },
                legend: {
                    fontFamily: app.font.family,
                    labels: {
                        colors: '#fff'
                    }
                },
                tooltip: {
                    style: {
                fontSize: '12px',
                fontFamily: app.font.family
            }
                },
                grid: {
                    borderColor: 'rgba('+ app.color.whiteRgb + ', .25)',
                },
                dataLabels: {
                    style: {
                        fontSize: '12px',
                        fontFamily: app.font.family,
                        fontWeight: 'bold',
                        colors: undefined
                }
                },
                xaxis: {
                    axisBorder: {
                        show: true,
                        color: 'rgba('+ app.color.whiteRgb + ', .25)',
                        height: 1,
                        width: '100%',
                        offsetX: 0,
                        offsetY: -1
                    },
                    axisTicks: {
                        show: true,
                        borderType: 'solid',
                        color: 'rgba('+ app.color.whiteRgb + ', .25)',
                        height: 6,
                        offsetX: 0,
                        offsetY: 0
                    },
            labels: {
                        style: {
                            colors: '#fff',
                            fontSize: '12px',
                            fontFamily: app.font.family,
                            fontWeight: 400,
                            cssClass: 'apexcharts-xaxis-label',
                        }
                    }
                },
                yaxis: {
            labels: {
                        style: {
                            colors: '#fff',
                            fontSize: '12px',
                            fontFamily: app.font.family,
                            fontWeight: 400,
                            cssClass: 'apexcharts-xaxis-label',
                        }
                    }
                }
            };
        
        
        // small stat chart
            var x = 0;
            var chart = [];
            
            var elmList = [].slice.call(document.querySelectorAll('[data-render="apexchart"]'));
            elmList.map(function(elm) {
                var chartType = elm.getAttribute('data-type');
                var chartHeight = elm.getAttribute('data-height');
                var chartTitle = elm.getAttribute('data-title');
                var chartColors = [];
                var chartPlotOptions = {};
                var chartData = [];
                var chartStroke = {
                    show: false
                };
                if (chartType == 'bar') {
                    chartColors = [app.color.theme];
                    chartPlotOptions = {
                        bar: {
                            horizontal: false,
                            columnWidth: '65%',
                            endingShape: 'rounded'
                        }
                    };
                    chartData = [{
                        name: chartTitle,
                        data: [randomNo(), randomNo(), randomNo(), randomNo(), randomNo(), randomNo(), randomNo(), randomNo(), randomNo(), randomNo(), randomNo(), randomNo(), randomNo()]
                    }];
                } else if (chartType == 'pie') {
                    chartColors = ['rgba('+ app.color.themeRgb + ', 1)', 'rgba('+ app.color.themeRgb + ', .75)', 'rgba('+ app.color.themeRgb + ', .5)'];
                    chartData = [randomNo(), randomNo(), randomNo()];
                } else if (chartType == 'donut') {
                    chartColors = ['rgba('+ app.color.themeRgb + ', .15)', 'rgba('+ app.color.themeRgb + ', .35)', 'rgba('+ app.color.themeRgb + ', .55)', 'rgba('+ app.color.themeRgb + ', .75)', 'rgba('+ app.color.themeRgb + ', .95)'];
                    chartData = [randomNo(), randomNo(), randomNo(), randomNo(), randomNo()];
                    chartStroke = {
                        show: false,
                        curve: 'smooth',
                        lineCap: 'butt',
                        colors: 'rgba(' + app.color.blackRgb + ', .25)',
                        width: 2,
                        dashArray: 0,    
                    };
                    chartPlotOptions = {
                        pie: {
                            donut: {
                                background: 'transparent',
                            }
                        }
                    };
                } else if (chartType == 'line') {
                    chartColors = [app.color.theme];
                    chartData = [{
                        name: chartTitle,
                        data: [randomNo(), randomNo(), randomNo(), randomNo(), randomNo(), randomNo(), randomNo(), randomNo(), randomNo(), randomNo(), randomNo(), randomNo(), randomNo()]
                    }];
                    chartStroke = {
                        curve: 'straight',
                        width: 2
                    };
                }
            
                var chartOptions = {
                    chart: {
                        height: chartHeight,
                        type: chartType,
                        toolbar: {
                            show: false
                        },
                        sparkline: {
                            enabled: true
                        },
                    },
                    dataLabels: {
                        enabled: false
                    },
                    colors: chartColors,
                    stroke: chartStroke,
                    plotOptions: chartPlotOptions,
                    series: chartData,
                    grid: {
                        show: false
                    },
                    tooltip: {
                        theme: 'dark',
                        x: {
                            show: false
                        },
                        y: {
                            title: {
                                formatter: function (seriesName) {
                                    return ''
                                }
                            },
                            formatter: (value) => { return ''+ value },
                        }
                    },
                    xaxis: {
                        labels: {
                            show: false
                        }
                    },
                    yaxis: {
                        labels: {
                            show: false
                        }
                    }
                };
                chart[x] = new ApexCharts(elm, chartOptions);
                chart[x].render();
                x++;
            });
        
        var serverChartOptions = {
            chart: {
            height: '100%',
            type: 'bar',
            toolbar: {
                show: false
            }
            },
            plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '55%',
                endingShape: 'rounded'  
            },
            },
            dataLabels: {
            enabled: false
            },
            grid: {
                show: true,
                borderColor: 'rgba('+ app.color.whiteRgb +', .15)',
            },
            stroke: {
            show: false
            },
            colors: ['rgba('+ app.color.whiteRgb + ', .25)', app.color.theme],
            series: [{
                name: 'MEMORY USAGE',
            data: [
                randomNo(), randomNo(), randomNo(), randomNo(), randomNo(), randomNo(), randomNo(), randomNo(), randomNo(), randomNo(), randomNo(), randomNo(), randomNo(), randomNo(), randomNo(),
                randomNo(), randomNo(), randomNo(), randomNo(), randomNo(), randomNo(), randomNo(), randomNo(), randomNo()
            ]
            },{
                name: 'CPU USAGE',
            data: [
                randomNo(), randomNo(), randomNo(), randomNo(), randomNo(), randomNo(), randomNo(), randomNo(), randomNo(), randomNo(), randomNo(), randomNo(), randomNo(), randomNo(), randomNo(),
                randomNo(), randomNo(), randomNo(), randomNo(), randomNo(), randomNo(), randomNo(), randomNo(), randomNo()
            ]
            }],
            xaxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            labels: {
                        show: false
                    }
            },
            fill: {
            opacity: .65
            },
            tooltip: {
            y: {
                formatter: function (val) {
                return "$ " + val + " thousands"
                }
            }
            }
        };
        var apexServerChart = new ApexCharts(
            document.querySelector('#chart-server'),
            serverChartOptions
        );
        apexServerChart.render();
        };




   </script>
   <script>
        $('#zero-config').DataTable({
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
  
    
</body>
</html>