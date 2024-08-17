<?php 		//echo $_SERVER['REQUEST_URI']; exit;	

// Default to the dashboard
$title = 'Dashboard'; 
 

// Define page titles and corresponding include files in an associative array
$pageActions = array(
    'asset_list.php' => array(
        'title' => 'Asset List',
        'include_file' => 'asset_list.php',
		'section' => ''
    ),
    'asset_list_add.php' => array(
        'title' => 'Add Asset List',
        'include_file' => 'asset_list_add.php',
		'section' => ''
    ),
    'grading_ruberic.php' => array(
        'title' => 'Grading Ruberic',
        'include_file' => 'grading_ruberic.php',
		'section' => ''
    ), 
    'announcement.php' => array(
        'title' => 'Announcement',
        'include_file' => 'announcement.php',
		'section' => ''
    ),
    'team_management.php' => array(
        'title' => 'Team Management',
        'include_file' => 'team_management.php',
		'section' => ''
    ),
    'manage_team.php' => array(
        'title' => 'Manage Team',
        'include_file' => 'manage_team.php',
		'section' => ''
    ),
    'create_team.php' => array(
        'title' => 'Create Team',
        'include_file' => 'create_team.php',
		'section' => ''
    ),
    'add_a_team.php' => array(
        'title' => 'Add a Team',
        'include_file' => 'add_a_team.php',
		'section' => ''
    ),
    'system_group_management.php' => array(
        'title' => 'System Group Management',
        'include_file' => 'system_group_management.php',
		'section' => ''
    ),
    'system_management.php' => array(
        'title' => 'System Management',
        'include_file' => 'system_management.php',
		'section' => ''
    ),
    'user_management.php' => array(
        'title' => 'User Management',
        'include_file' => 'user_management.php',
		'section' => ''
    ),
    'add_a_user.php' => array(
        'title' => 'Add a User',
        'include_file' => 'add_a_user.php',
		'section' => ''
    ),
    'api_management.php' => array(
        'title' => 'API Management',
        'include_file' => 'api_management.php',
		'section' => ''
    ),
    'ruberic_management.php' => array(
        'title' => 'Ruberic Management',
        'include_file' => 'ruberic_management.php',
		'section' => ''
    ),
    'quiz_management.php' => array(
        'title' => 'Quiz Management',
        'include_file' => 'quiz_management.php',
		'section' => ''
    ),
    'knowledge_base.php' => array(
        'title' => 'Knowledge Base',
        'include_file' => 'knowledge_base.php',
		'section' => ''
    ),
    'ticket_management.php' => array(
        'title' => 'Ticket Management',
        'include_file' => 'ticket_management.php',
		'section' => ''
    ),
    'range_settings.php' => array(
        'title' => 'Range Settings',
        'include_file' => 'range_settings.php',
		'section' => 'dashboard'
    ),
    'range_administration.php' => array(
        'title' => 'Range Administration',
        'include_file' => 'range_administration.php',
		'section' => 'dashboard'
    ),
    'log_administration.php' => array(
        'title' => 'Log Administration',
        'include_file' => 'log_administration.php',
		'section' => 'dashboard'
    ),
    'statistics.php' => array(
        'title' => 'Statistics',
        'include_file' => 'statistics.php',
		'section' => 'dashboard'
    ),
    'login.php' => array(
        'title' => 'Login',
        'include_file' => 'login.php',
		'section' => 'dashboard'
    ),
    'dashboard.php' => array(
        'title' => 'Dashboard',
        'include_file' => 'dashboard.php',
		'section' => 'dashboard'
    ),
    'courses.php' => array(
        'title' => 'Courses',
        'include_file' => 'courses.php',
		'section' => 'courses'
    ),
    'add_course.php' => array(
        'title' => 'Add Courses',
        'include_file' => 'add_course.php',
		'section' => 'courses'
    ),
    'view_course.php' => array(
        'title' => 'View Courses',
        'include_file' => 'view_course.php',
		'section' => 'courses'
    ),
    'course_grid_view.php' => array(
        'title' => 'Course Grid View',
        'include_file' => 'course_grid_view.php',
		'section' => 'courses'
    ),
    'range.php' => array(
        'title' => 'Range',
        'include_file' => 'range.php',
		'section' => 'range'
    ),
    'view_range.php' => array(
        'title' => 'View Range',
        'include_file' => 'view_range.php',
		'section' => 'range'
    ),
    'labs.php' => array(
        'title' => 'Labs',
        'include_file' => 'labs.php',
		'section' => 'labs'
    ),
    'chat.php' => array(
        'title' => 'Chat',
        'include_file' => 'chat.php',
		'section' => 'chat'
    )
);

// Check if the 'page' parameter is set in the URL
if (isset($_GET['page'])) {
    // Get the value of the 'page' parameter
    $page = $_GET['page'];

    // Check if the page key exists in the array
    if (array_key_exists($page, $pageActions)) { 
        $title = $pageActions[$page]['title'];
		$section  = $pageActions[$page]['section'];
		$include_file  = $pageActions[$page]['include_file'];
    } else {
        echo "Page not found in pageActions array.<br>";
        $title = '404'; 
    }
} elseif (basename($_SERVER['PHP_SELF']) === 'index.php') {
 
	$page = 'dashboard.php';
        $title = 'Dashboard';
		$section  = 'dashboard';
		$include_file  = 'index.php';
}else { 
        $title = 'Dashboard';
		$section  = 'dashboard';
		$include_file  = 'index.php';
}

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="generator" content="RootCapture">
    <title><?php echo $pageTitle; ?> - RootCapture CyberRange </title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- Custom styles for this template -->
    <link href="https://rootcapture.com/dashboard/assets/css/style.css?v=0.0.1" rel="stylesheet">
    <style>
        .dp_cont {
    position: absolute;
    width: 128px;
    right: 0px;
    background: #171f4d;
    padding: 7px 20px 13px;
    font-size: 14px;
    display: flex;
    flex-direction: column;
    gap: 9px;
    top: 22px;
    border-radius: 8px;
    border: 1px solid #154279;
    visibility: hidden;
    opacity: 0;
transition:all 0.5s;
}
.drp_menu {
    position: relative;
}
.drp_menu:hover .dp_cont {
   visibility: visible;
    opacity: 1;
}
.drp_menu:hover .dp_cont a:hover {
color:#03cadf;
}
    </style>
</head>

<body>

    <div class="row flex-nowrap">
        <div class="col-auto px-0">


            <div id="sidebar" class="collapse collapse-horizontal show  ">

                <div class="  d-flex flex-row  justify-content-center align-items-center    logo">
                    <img width="220px" src="https://rootcapture.com/dashboard/assets/img/logo_tr.png" class=" p-2 img-fluid">
                </div>
 

                <div id="sidebar-nav" class="list-group border-0 rounded-0 text-sm-start min-vh-100">
                    <a href="" class="special_btn <?php if (strpos($_SERVER['REQUEST_URI'], 'index.php') !== FALSE && !isset($_GET['page'])) { echo ' active-btn '; } ?>" data-bs-parent="#sidebar">
                        <i class="bi bi-bootstrap"></i> <span>Home</span>
                    </a>
                    <a href="https://rootcapture.com/dashboard/assetip.php" class="special_btn" data-bs-parent="#sidebar">
                        <i class="bi bi-bootstrap"></i> <span>Asset List</span>
                    </a>
                    <a href="https://rootcapture.com/dashboard/gradedrubric.php" class="mb-3 special_btn" data-bs-parent="#sidebar">
                        <i class="bi bi-bootstrap"></i> <span>Grading Ruberic</span>
                    </a>
                    <a href="#" class="list-group-label" data-bs-parent="#sidebar">
                        <i class="bi bi-bootstrap"></i> <span>Administrative Functions</span>
                    </a> 

                    <a href="#announSubmenu" class="list-group-item border-end-0 d-inline-block <?php if (isset($_GET['page']) && $_GET['page'] == 'team_management.php') { echo ' active-menu '; } ?>" data-bs-toggle="collapse" aria-expanded="false" aria-controls="teamSubmenu">
                        <i class="bi bi-bootstrap"></i> <span>Announcement</span> <i class="fa fa-caret-down float-end"></i>
                    </a>
                    <div class="collapse" id="announSubmenu">
                        <a href="https://rootcapture.com/dashboard/news.php" class="ms-3 list-group-item border-end-0 d-inline-block <?php if (isset($_GET['page']) && $_GET['page'] == 'manage_team.php') { echo ' active-menu '; } ?>" data-bs-parent="#sidebar-nav">
                            <i class="bi bi-bootstrap"></i><i class="fa-solid translate-middle fa-circle" style="font-size:8px"></i> <span>Manage Announcement</span>
                        </a>
                        <a href="index.php?page=create_team.php" class="ms-3 list-group-item border-end-0 d-inline-block <?php if (isset($_GET['page']) && $_GET['page'] == 'create_team.php') { echo ' active-menu '; } ?>" data-bs-parent="#sidebar-nav">
                            <i class="bi bi-bootstrap"></i><i class="fa-solid translate-middle fa-circle" style="font-size:8px"></i> <span>Create Announcement</span>
                        </a>
                    </div>

                     <a href="#teamSubmenu" class="list-group-item border-end-0 d-inline-block <?php if (isset($_GET['page']) && $_GET['page'] == 'team_management.php') { echo ' active-menu '; } ?>" data-bs-toggle="collapse" aria-expanded="false" aria-controls="teamSubmenu">
                        <i class="bi bi-bootstrap"></i> <span>Team Management</span> <i class="fa fa-caret-down float-end"></i>
                    </a>
                    <div class="collapse" id="teamSubmenu">
                        <a href="https://rootcapture.com/dashboard/manage-team.php" class="ms-3 list-group-item border-end-0 d-inline-block <?php if (isset($_GET['page']) && $_GET['page'] == 'manage_team.php') { echo ' active-menu '; } ?>" data-bs-parent="#sidebar-nav">
                            <i class="bi bi-bootstrap"></i><i class="fa-solid translate-middle fa-circle" style="font-size:8px"></i> <span>Manage Teams</span>
                        </a>
                        <a href="https://rootcapture.com/dashboard/create_team.php" class="ms-3 list-group-item border-end-0 d-inline-block" data-bs-parent="#sidebar-nav">
                            <i class="bi bi-bootstrap"></i><i class="fa-solid translate-middle fa-circle" style="font-size:8px"></i> <span>Create Team</span>
                        </a>
                    </div>

                    <a href="#UserSubmenu" class="list-group-item border-end-0 d-inline-block <?php if (isset($_GET['page']) && $_GET['page'] == 'team_management.php') { echo ' active-menu '; } ?>" data-bs-toggle="collapse" aria-expanded="false" aria-controls="teamSubmenu">
                        <i class="bi bi-bootstrap"></i> <span>User Management</span> <i class="fa fa-caret-down float-end"></i>
                    </a>
                    <div class="collapse" id="UserSubmenu">
                        <a href="https://rootcapture.com/dashboard/users.php" class="ms-3 list-group-item border-end-0 d-inline-block <?php if (isset($_GET['page']) && $_GET['page'] == 'manage_team.php') { echo ' active-menu '; } ?>" data-bs-parent="#sidebar-nav">
                            <i class="bi bi-bootstrap"></i><i class="fa-solid translate-middle fa-circle" style="font-size:8px"></i> <span>Manage Users</span>
                        </a>
                        <a href="https://rootcapture.com/dashboard/create_user.php" class="ms-3 list-group-item border-end-0 d-inline-block" data-bs-parent="#sidebar-nav">
                            <i class="bi bi-bootstrap"></i><i class="fa-solid translate-middle fa-circle" style="font-size:8px"></i> <span>Create Users</span>
                        </a>
                    </div>

                    <a href="#SysGrpSubmenu" class="list-group-item border-end-0 d-inline-block <?php if (isset($_GET['page']) && $_GET['page'] == 'team_management.php') { echo ' active-menu '; } ?>" data-bs-toggle="collapse" aria-expanded="false" aria-controls="teamSubmenu">
                        <i class="bi bi-bootstrap"></i> <span>System Group Management</span> <i class="fa fa-caret-down float-end"></i>
                    </a>
                    <div class="collapse" id="SysGrpSubmenu">
                        <a href="https://rootcapture.com/dashboard/asset_group.php" class="ms-3 list-group-item border-end-0 d-inline-block <?php if (isset($_GET['page']) && $_GET['page'] == 'manage_team.php') { echo ' active-menu '; } ?>" data-bs-parent="#sidebar-nav">
                            <i class="bi bi-bootstrap"></i><i class="fa-solid translate-middle fa-circle" style="font-size:8px"></i> <span>Manage System Group</span>
                        </a>
                        <a href="https://rootcapture.com/dashboard/create_group.php" class="ms-3 list-group-item border-end-0 d-inline-block <?php if (isset($_GET['page']) && $_GET['page'] == 'create_team.php') { echo ' active-menu '; } ?>" data-bs-parent="#sidebar-nav">
                            <i class="bi bi-bootstrap"></i><i class="fa-solid translate-middle fa-circle" style="font-size:8px"></i> <span>Create System Group</span>
                        </a>
                    </div>

                    <a href="#SysSubmenu" class="list-group-item border-end-0 d-inline-block <?php if (isset($_GET['page']) && $_GET['page'] == 'team_management.php') { echo ' active-menu '; } ?>" data-bs-toggle="collapse" aria-expanded="false" aria-controls="teamSubmenu">
                        <i class="bi bi-bootstrap"></i> <span>System Management</span> <i class="fa fa-caret-down float-end"></i>
                    </a>
                    <div class="collapse" id="SysSubmenu">
                        <a href="https://rootcapture.com/dashboard/manage_assets.php" class="ms-3 list-group-item border-end-0 d-inline-block <?php if (isset($_GET['page']) && $_GET['page'] == 'manage_team.php') { echo ' active-menu '; } ?>" data-bs-parent="#sidebar-nav">
                            <i class="bi bi-bootstrap"></i><i class="fa-solid translate-middle fa-circle" style="font-size:8px"></i> <span>Manage System</span>
                        </a>
                        <a href="https://rootcapture.com/dashboard/create_asset.php" class="ms-3 list-group-item border-end-0 d-inline-block <?php if (isset($_GET['page']) && $_GET['page'] == 'create_team.php') { echo ' active-menu '; } ?>" data-bs-parent="#sidebar-nav">
                            <i class="bi bi-bootstrap"></i><i class="fa-solid translate-middle fa-circle" style="font-size:8px"></i> <span>Create System</span>
                        </a>
                    </div>

                    <a href="#rubManage" class="list-group-item border-end-0 d-inline-block <?php if (isset($_GET['page']) && $_GET['page'] == 'team_management.php') { echo ' active-menu '; } ?>" data-bs-toggle="collapse" aria-expanded="false" aria-controls="teamSubmenu">
                        <i class="bi bi-bootstrap"></i> <span>Rubric Management</span> <i class="fa fa-caret-down float-end"></i>
                    </a>
                    <div class="collapse" id="rubManage">
                        <a href="https://rootcapture.com/dashboard/manage-grading-rubric.php" class="ms-3 list-group-item border-end-0 d-inline-block <?php if (isset($_GET['page']) && $_GET['page'] == 'manage_team.php') { echo ' active-menu '; } ?>" data-bs-parent="#sidebar-nav">
                            <i class="bi bi-bootstrap"></i><i class="fa-solid translate-middle fa-circle" style="font-size:8px"></i> <span>Manage Grades</span>
                        </a>
                        <a href="https://rootcapture.com/dashboard/add-grading-rubric.php" class="ms-3 list-group-item border-end-0 d-inline-block <?php if (isset($_GET['page']) && $_GET['page'] == 'create_team.php') { echo ' active-menu '; } ?>" data-bs-parent="#sidebar-nav">
                            <i class="bi bi-bootstrap"></i><i class="fa-solid translate-middle fa-circle" style="font-size:8px"></i> <span>Create New Criteria</span>
                        </a>
                    </div>

                    <a href="#apiManage" class="list-group-item border-end-0 d-inline-block <?php if (isset($_GET['page']) && $_GET['page'] == 'team_management.php') { echo ' active-menu '; } ?>" data-bs-toggle="collapse" aria-expanded="false" aria-controls="teamSubmenu">
                        <i class="bi bi-bootstrap"></i> <span>API Management</span> <i class="fa fa-caret-down float-end"></i>
                    </a>
                    <div class="collapse" id="apiManage">
                        <a href="https://rootcapture.com/dashboard/manage_apis.php" class="ms-3 list-group-item border-end-0 d-inline-block <?php if (isset($_GET['page']) && $_GET['page'] == 'manage_team.php') { echo ' active-menu '; } ?>" data-bs-parent="#sidebar-nav">
                            <i class="bi bi-bootstrap"></i><i class="fa-solid translate-middle fa-circle" style="font-size:8px"></i> <span>Manage API's</span>
                        </a>
                        <a href="https://rootcapture.com/dashboard/create_apis.php" class="ms-3 list-group-item border-end-0 d-inline-block <?php if (isset($_GET['page']) && $_GET['page'] == 'create_team.php') { echo ' active-menu '; } ?>" data-bs-parent="#sidebar-nav">
                            <i class="bi bi-bootstrap"></i><i class="fa-solid translate-middle fa-circle" style="font-size:8px"></i> <span>Create API's</span>
                        </a>
                    </div>

                    <a href="#quizManage" class="list-group-item border-end-0 d-inline-block <?php if (isset($_GET['page']) && $_GET['page'] == 'team_management.php') { echo ' active-menu '; } ?>" data-bs-toggle="collapse" aria-expanded="false" aria-controls="teamSubmenu">
                        <i class="bi bi-bootstrap"></i> <span>Quiz Management</span> <i class="fa fa-caret-down float-end"></i>
                    </a>
                    <div class="collapse" id="quizManage">
                        <a href="https://rootcapture.com/dashboard/manage_quizes.php" class="ms-3 list-group-item border-end-0 d-inline-block <?php if (isset($_GET['page']) && $_GET['page'] == 'manage_team.php') { echo ' active-menu '; } ?>" data-bs-parent="#sidebar-nav">
                            <i class="bi bi-bootstrap"></i><i class="fa-solid translate-middle fa-circle" style="font-size:8px"></i> <span>Manage Quiz</span>
                        </a>
                        <a href="https://rootcapture.com/dashboard/create_quize.php" class="ms-3 list-group-item border-end-0 d-inline-block <?php if (isset($_GET['page']) && $_GET['page'] == 'create_team.php') { echo ' active-menu '; } ?>" data-bs-parent="#sidebar-nav">
                            <i class="bi bi-bootstrap"></i><i class="fa-solid translate-middle fa-circle" style="font-size:8px"></i> <span>Create Quiz</span>
                        </a>
                    </div>








                    <!-- <a href="index.php?page=announcement.php" class="list-group-item border-end-0 d-inline-block <?php if (isset($_GET['page']) && $_GET['page'] == 'announcement.php') { echo ' active-menu '; } ?>" data-bs-parent="#sidebar">
                        <i class="bi bi-bootstrap"></i> <span>Announcement</span>
                    </a> -->
                   
                    <!-- <a href="index.php?page=add_a_team.php" class="list-group-item border-end-0 d-inline-block <?php if (isset($_GET['page']) && $_GET['page'] == 'add_a_team.php') { echo ' active-menu '; } ?>" data-bs-parent="#sidebar">
                        <i class="bi bi-bootstrap"></i> <span>Add a Team</span>
                    </a>
                    <a href="index.php?page=system_group_management.php" class="list-group-item border-end-0 d-inline-block <?php if (isset($_GET['page']) && $_GET['page'] == 'system_group_management.php') { echo ' active-menu '; } ?>" data-bs-parent="#sidebar">
                        <i class="bi bi-bootstrap"></i> <span>System Group Management</span>
                    </a> -->
                    <!-- <a href="index.php?page=system_management.php" class="list-group-item border-end-0 d-inline-block <?php if (isset($_GET['page']) && $_GET['page'] == 'system_management.php') { echo ' active-menu '; } ?>" data-bs-parent="#sidebar">
                        <i class="bi bi-bootstrap"></i> <span>System Management</span>
                    </a>
                    <a href="index.php?page=user_management.php" class="list-group-item border-end-0 d-inline-block <?php if (isset($_GET['page']) && $_GET['page'] == 'user_management.php') { echo ' active-menu '; } ?>" data-bs-parent="#sidebar">
                        <i class="bi bi-bootstrap"></i> <span>User Management</span>
                    </a>
                    <a href="index.php?page=api_management.php" class="list-group-item border-end-0 d-inline-block <?php if (isset($_GET['page']) && $_GET['page'] == 'api_management.php') { echo ' active-menu '; } ?>" data-bs-parent="#sidebar">
                        <i class="bi bi-bootstrap"></i> <span>API Management</span>
                    </a>
                    <a href="index.php?page=ruberic_management.php" class="list-group-item border-end-0 d-inline-block <?php if (isset($_GET['page']) && $_GET['page'] == 'ruberic_management.php') { echo ' active-menu '; } ?>" data-bs-parent="#sidebar">
                        <i class="bi bi-bootstrap"></i> <span>Ruberic Management</span>
                    </a>
                    <a href="index.php?page=quiz_management.php" class="list-group-item border-end-0 d-inline-block <?php if (isset($_GET['page']) && $_GET['page'] == 'quiz_management.php') { echo ' active-menu '; } ?>" data-bs-parent="#sidebar">
                        <i class="bi bi-bootstrap"></i> <span>Quiz Management</span>
                    </a>
                    <a href="index.php?page=knowledge_base.php" class="list-group-item border-end-0 d-inline-block <?php if (isset($_GET['page']) && $_GET['page'] == 'knowledge_base.php') { echo ' active-menu '; } ?>" data-bs-parent="#sidebar">
                        <i class="bi bi-bootstrap"></i> <span>Knowledge Base</span>
                    </a>
                    <a href="index.php?page=ticket_management.php" class="list-group-item border-end-0 d-inline-block <?php if (isset($_GET['page']) && $_GET['page'] == 'ticket_management.php') { echo ' active-menu '; } ?>" data-bs-parent="#sidebar">
                        <i class="bi bi-bootstrap"></i> <span>Ticket Management</span>
                    </a> -->
                </div>

            </div>
        </div>
 


        <main class="col">

            <div class="row top-header">

                <div class="col ">
                    <ul class="d-flex flex-sm-row flex-column align-items-center justify-content-center"
                        style="width: 100%;">
                        <li class="">	    
							<a class="top_menu_item 
							<?php if (isset($section) && $section == 'dashboard') { echo ' top_menu_item_active '; } ?>
							 
							" href="index.php">Admin Dashboard</a>
						</li>
                        <li class="ms-sm-5">
							<a class="top_menu_item
							<?php if (isset($section) && $section == 'labs') { echo ' top_menu_item_active '; } ?>
							  
							" href="index.php?page=labs.php">Labs</a>
						</li>
                        <li class="ms-sm-5">
							<a class="top_menu_item 
							<?php if (isset($section) && $section == 'courses') { echo ' top_menu_item_active '; } ?>
							  
							" href="index.php?page=courses.php">Courses</a>
						</li>
						
                        <li class="ms-sm-5">
							<a class="top_menu_item
							<?php if (isset($section) && $section == 'range') { echo ' top_menu_item_active '; } ?> 
							" href="index.php?page=range.php">Range</a>
						</li>
						 
                        <li class="ms-sm-5">
							<a class="top_menu_item
							<?php if (isset($section) && $section == 'chat') { echo ' top_menu_item_active '; } ?>
							  
							" href="index.php?page=chat.php">Chat</a>
						</li>
                    </ul>
                </div>

                <div class="col-2 d-flex flex-sm-row flex-column align-items-center justify-content-center">
                    <a href="#" class="top_menu_item px-2"> <i class="fa fa-fw" aria-hidden="true"
                            title="Copy to use user"></i></a>
                    <div class="drp_menu">
                        <a href="#" data-bs-target="#sidebar" data-bs-toggle="collapse" class="drop_dn  px-2 text-decoration-none">
                            <i class="fa-solid fa-bars"></i>
                            <div class="dp_cont">
                                <a href="#">My Account</a>
                                <a href="#">Logout</a>
                            </div>
					    </a>
                    </div>
                   

                </div>

            </div>  
			
		

 

		
<?php //Show this sub-menu if the dashboard part is open
 
if( end(explode("/", $_SERVER['REQUEST_URI'])) == 'dashboard' || end(explode("/", $_SERVER['REQUEST_URI'])) == 'login-log.php' || end(explode("/", $_SERVER['REQUEST_URI'])) == 'range_settings.php' ) {
$query_string = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';
?>
<div class="row special_btn_long_menu">
  <ul class="d-flex flex-sm-row flex-column align-items-center justify-content-center" style="width: 100%;">
    <li class=""><a class="top_menu_item_long_menu <?php if ($query_string == 'page=dashboard.php'){ echo 'top_menu_item_long_menu_active'; }?>" href="?page=dashboard.php">Dashboard</a></li>
    
    <li class="ms-sm-5"><a class="top_menu_item_long_menu" href="?page=statistics.php">Statistics</a></li>

    <li class="ms-sm-5"><a class="top_menu_item_long_menu" href="https://rootcapture.com/dashboard/range_settings.php">Range Settings</a></li>

    <li class="ms-sm-5"><a class="top_menu_item_long_menu" href="#">Range Administration</a></li>

    <li class="ms-sm-5"><a class="top_menu_item_long_menu <?php if ($query_string == 'page=login.php'){ echo 'top_menu_item_long_menu_active'; }?>" href="?page=login.php">Login</a></li>

    <li class="ms-sm-5">
        <a class="top_menu_item_long_menu" href="https://rootcapture.com/dashboard/login-log.php">Log Administration
        </a>
    </li>
  </ul>
</div>


<?php } ?> 






		
<?php //Show this sub-menu if the COURSES part is open
if($section=='courses') {
$query_string = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';
?>
<div class="row special_btn_long_menu">
  <ul class="d-flex flex-sm-row flex-column align-items-center justify-content-center" style="width: 100%;">
    <li class=""><a class="top_menu_item_long_menu <?php if ($query_string == 'page=courses.php'){ echo 'top_menu_item_long_menu_active'; }?>" href="index.php?page=courses.php">Courses</a></li>
    <li class="ms-sm-5"><a class="top_menu_item_long_menu <?php if ($query_string == 'page=add_course.php'){ echo 'top_menu_item_long_menu_active'; }?>" href="index.php?page=add_course.php">Add Course</a></li> 
    <li class="ms-sm-5"><a class="top_menu_item_long_menu <?php if ($query_string == 'page=view_course.php'){ echo 'top_menu_item_long_menu_active'; }?>" href="index.php?page=view_course.php">View Course</a></li> 
    <li class="ms-sm-5"><a class="top_menu_item_long_menu <?php if ($query_string == 'page=course_grid_view.php'){ echo 'top_menu_item_long_menu_active'; }?>" href="index.php?page=course_grid_view.php">Course Grid View</a></li> 
  </ul>
</div>


<?php } ?> 
