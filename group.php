<?php
ob_start();
require_once 'includes/db.php';
require_once 'includes/init.php';
 /*ini_set('display_errors', 1);
 ini_set('display_startup_errors', 1);
 error_reporting(E_ALL);*/
@session_start();

		
$loggedUserId    = $_SESSION['ID'];
$getuserList     = $odb -> query("SELECT * FROM `users` WHERE ID !=  $loggedUserId AND restrict_chat=0")->fetchAll();


if(isset($_POST['group_member']) && !empty($_POST['group_member'])){
    if(isset($_POST['group_id']) && $_POST['group_id']>0){
        $updategroup = $odb -> prepare("UPDATE `chat_group` SET `group_name` = :group_name, `group_members` = :group_members WHERE group_id = :group_id");
        $updategroup -> execute(array(':group_name' => $_POST['group_name'], ':group_members' => implode(",", $_POST['group_member']), ':group_id' => $_POST['group_id']));
    }else{
        $insertgroup = $odb -> prepare("INSERT INTO `chat_group` (`group_name`, `group_members`, `created_by`) VALUES(:group_name, :group_members, :created_by)");
        $insertgroup -> execute(array(':group_name' => $_POST['group_name'], ':group_members' => implode(",", $_POST['group_member']), ':created_by' => $loggedUserId));
    }
}
$groupdata = array();
if(isset($_GET['group_id']) && $_GET['group_id']>0){
    $group_id = $_GET['group_id'];
    $groupdata = $odb -> query("SELECT * FROM `chat_group` WHERE group_id = $group_id")->fetch();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>rootCapture - Chat Room </title>
    <link rel="icon" type="image/x-icon" href="../src/assets/img/favicon.ico"/>
    <link href="../layouts/vertical-dark-menu/css/light/loader.css" rel="stylesheet" type="text/css" />
    <link href="../layouts/vertical-dark-menu/css/dark/loader.css" rel="stylesheet" type="text/css" />
    <script src="../layouts/vertical-dark-menu/loader.js"></script>
	<link href="../css/alter.css" rel="stylesheet" type="text/css" />
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="../src/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="../layouts/vertical-dark-menu/css/light/plugins.css" rel="stylesheet" type="text/css" />
    <link href="../layouts/vertical-dark-menu/css/dark/plugins.css" rel="stylesheet" type="text/css" />
    <link href="../css/chat.css" rel="stylesheet" type="text/css" />

    <!-- END GLOBAL MANDATORY STYLES -->

    <!-- BEGIN PAGE LEVEL STYLES -->
    <link rel="stylesheet" type="text/css" href="../src/plugins/src/table/datatable/datatables.css">
     <link rel="stylesheet" type="text/css" href="../src/plugins/src/vanillaSelectBox/vanillaSelectBox.css">
    <link rel="stylesheet" type="text/css" href="../src/plugins/css/dark/vanillaSelectBox/custom-vanillaSelectBox.css">

    
    <link href="../src/assets/css/light/apps/contacts.css" rel="stylesheet" type="text/css" />
    <link href="../src/assets/css/dark/apps/contacts.css" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" type="text/css" href="../src/plugins/css/light/table/datatable/dt-global_style.css">
    <link rel="stylesheet" type="text/css" href="../src/plugins/css/dark/table/datatable/dt-global_style.css">
    <!-- END PAGE LEVEL STYLES -->

</head>
<body class="layout-boxed">
    <!-- BEGIN LOADER -->
    <div id="load_screen"> <div class="loader"> <div class="loader-content">
        <div class="spinner-grow align-self-center"></div>
    </div></div></div>
    <!--  END LOADER -->

    <!--  BEGIN NAVBAR  -->

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
                    <div class="page-meta">
                        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">Group</li>
                                <?php if(isset($_GET['group_id']) && $_GET['group_id']>0){ ?>
                                    <li class="breadcrumb-item active" aria-current="page">Update Group</li>
                                <?php }else{ ?>
                                    <li class="breadcrumb-item active" aria-current="page">Create New Group</li>
                                <?php } ?>
                            </ol>
                        </nav>
                    </div>
                    <div class="row layout-spacing layout-top-spacing" id="cancel-row">
                        <div class="col-lg-12">
                            <div class="widget-content searchable-container list">
                                <div class="row">
                                    <div class="col-xl-4 col-lg-5 col-md-5 col-sm-7 filtered-list-search layout-spacing align-self-center">
                                        <form class="form-inline my-2 my-lg-0">
                                            <div class="">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                                                <input type="text" class="form-control product-search" id="input-search" placeholder="Search Contacts...">
                                            </div>
                                        </form>
                                    </div>                                    
                                </div>
    
                                <form method="post">
                                    <?php if(!empty($getuserList)){ 
                                        $group_members = array();
                                        if(isset($groupdata['group_members'])){
                                             $group_members = explode(",", $groupdata['group_members']);
                                        }
                                     ?>
                                        <div class="searchable-items grid" method="post">
                                            <?php foreach ($getuserList as $key => $value) { ?>
                                                    <div class="items">
                                                        <div class="item-content">
                                                            <div class="user-profile">
                                                                <div class="align-self-center text-center">
                                                                    <div class="form-check form-check-primary me-0 mb-0">
                                                                        <input class="form-check-input inbox-chkbox contact-chkbox" <?php if(isset($group_members) && in_array($value['ID'], $group_members)){ echo "checked"; } ?> value="<?php echo $value['ID']; ?>" name="group_member[]" type="checkbox">
                                                                    </div>
                                                                </div>
                                                                <div class="user-meta-info">
                                                                    <p class="user-name" data-name="Alan Green"><?php echo $value['username']; ?></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                            <?php } ?>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-md-6">
                                                <div class="group-name">
                                                    <input type="text" id="group_name" value="<?php if(isset($groupdata['group_name'])){ echo $groupdata['group_name']; } ?>" name="group_name" class="form-control" placeholder="Group Name" required>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="group_id" class="group_id" value="<?php if(isset($_GET['group_id'])){ echo $_GET['group_id']; } ?>">
                                        <?php if(isset($_GET['group_id']) && $_GET['group_id']>0){ ?>
                                            <button id="btn-add" type="submit" class="btn btn-primary">Update Group</button>
                                        <?php }else{ ?>
                                            <button id="btn-add" type="submit" class="btn btn-primary">Create Group</button>
                                        <?php } ?>
                                    <?php } ?>
                                </form>
    
                            </div>
                        </div>
                    </div>

                </div>
                
            </div>
            <!--  BEGIN FOOTER  -->
            <div class="footer-wrapper mt-0">
                <div class="footer-section f-section-1">
                    <p class="">Copyright © <span class="dynamic-year">2022</span> <a target="_blank" href="https://designreset.com/cork-admin/">DesignReset</a>, All rights reserved.</p>
                </div>
                <div class="footer-section f-section-2">
                    <p class="">Coded with <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-heart"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg></p>
                </div>
            </div>
            <!--  END FOOTER  -->
        </div>
        <!--  END CONTENT AREA  -->
    </div>
    <!-- END MAIN CONTAINER -->
    
    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="../src/plugins/src/global/vendors.min.js"></script>
    <script src="../src/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../src/plugins/src/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="../src/plugins/src/mousetrap/mousetrap.min.js"></script>
    <script src="../layouts/vertical-dark-menu/app.js"></script>
    <script src="../src/plugins/src/vanillaSelectBox/vanillaSelectBox.js"></script>
    <script src="../src/plugins/src/vanillaSelectBox/custom-vanillaSelectBox.js"></script>
    <script src="../src/assets/js/custom.js"></script>
    <script src="../src/assets/js/apps/contact.js"></script>
    <!-- END GLOBAL MANDATORY SCRIPTS -->
    
</body>
</html>