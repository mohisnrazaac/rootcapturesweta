<!doctype html>
<html lang="en">
    <?php
        ob_start();
        require_once '../includes/db.php';
        require_once '../includes/init.php';
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
        @session_start();
        $loggedUserId    = $_SESSION['ID'];
        $getUserDetailIdWise = $user->getUserDetailIdWise($odb); 
        $college_id = $getUserDetailIdWise['college_id'];         
        $userInfo = $user->userInfo($odb,$loggedUserId);
        
        if($userInfo['restrict_chat']==1){
            header('location: /index.php');
            die();
        }

        $userlist = $user->getUserList($odb,$loggedUserId,$college_id);
        $contactlist = $user->getContactList($odb,$loggedUserId);
        $userorderlist = array();
        $existContact = array();
        if(!empty($contactlist)){
            foreach($contactlist as $key=>$value){
                $contactInfo = $user->userInfo($odb,$value['receiver_id']);
                $existContact[] = $value['receiver_id'];
                $userorderlist[$contactInfo['username'][0]][$contactInfo['ID']] = $contactInfo['username'];
            }
        }
        $usergrouplist = $user->getUserGroups($odb,$loggedUserId);
        $chatuserlist = $user->getChatUserList($odb,$loggedUserId);
    ?>
    <head>
        
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
        <title>rootCapture - <?=$pageTitle?> </title>
        <link rel="icon" type="image/x-icon" href="<?=BASEURL?>src/assets/img/favicon-dark.png"/>

        <!-- magnific-popup css -->
        <link href="<?=BASEURL?>assets/chat/libs/magnific-popup/magnific-popup.css" rel="stylesheet" type="text/css" />

        <!-- owl.carousel css -->
        <link rel="stylesheet" href="<?=BASEURL?>assets/chat/libs/owl.carousel/assets/owl.carousel.min.css">

        <link rel="stylesheet" href="<?=BASEURL?>assets/chat/libs/owl.carousel/assets/owl.theme.default.min.css">

        <!-- Bootstrap Css -->
        <link href="<?=BASEURL?>assets/chat/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="<?=BASEURL?>assets/chat/css/icons.min.css" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="<?=BASEURL?>assets/chat/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="<?=BASEURL?>assets/chat/css/app.min.css">
        <link rel="stylesheet" href="<?=BASEURL?>assets/chat/css/livechat.css">

    </head>
    
    <body data-layout-mode="dark">
        
        <input type="hidden" name="loggedUserId" class="loggedUserId" value="<?php echo $loggedUserId; ?>">
        <div class="layout-wrapper d-lg-flex">

            <!-- Start left sidebar-menu -->
            <div class="side-menu flex-lg-column me-lg-1 ms-lg-0">
                <!-- LOGO -->
                <div class="navbar-brand-box">
                    <a href="index.php" class="logo logo-dark">
                        <span class="logo-sm">
                            <img src="<?=BASEURL?>/assets/chat/images/logo.svg" alt="" height="30">
                        </span>
                    </a>

                    <a href="index.html" class="logo logo-light">
                        <span class="logo-sm">
                            <img src="<?=BASEURL?>/assets/chat/images/logo.svg" alt="" height="30">
                        </span>
                    </a>
                </div>
                <!-- end navbar-brand-box -->

                <!-- Start side-menu nav -->
                <div class="flex-lg-column my-auto">
                    <ul class="nav nav-pills side-menu-nav justify-content-center" role="tablist">
                        <li class="nav-item" data-bs-toggle="tooltip" data-bs-placement="top" title="Profile">
                            <a class="nav-link" id="pills-user-tab" data-bs-toggle="pill" href="#pills-user" role="tab">
                                <i class="ri-user-2-line"></i>
                            </a>
                        </li>
                        <li class="nav-item" data-bs-toggle="tooltip" data-bs-placement="top" title="Chats">
                            <a class="nav-link active" id="pills-chat-tab" data-bs-toggle="pill" href="#pills-chat" role="tab">
                                <i class="ri-message-3-line"></i>
                            </a>
                        </li>
                        <li class="nav-item" data-bs-toggle="tooltip" data-bs-placement="top" title="Groups">
                            <a class="nav-link" id="pills-groups-tab" data-bs-toggle="pill" href="#pills-groups" role="tab">
                                <i class="ri-group-line"></i>
                            </a>
                        </li>
                        <li class="nav-item" data-bs-toggle="tooltip" data-bs-placement="top" title="Contacts">
                            <a class="nav-link" id="pills-contacts-tab" data-bs-toggle="pill" href="#pills-contacts" role="tab">
                                <i class="ri-contacts-line"></i>
                            </a>
                        </li>
                        <li class="nav-item" data-bs-toggle="tooltip" data-bs-placement="top" title="Settings">
                            <a class="nav-link" id="pills-setting-tab" data-bs-toggle="pill" href="#pills-setting" role="tab">
                                <i class="ri-settings-2-line"></i>
                            </a>
                        </li>
                        <li class="nav-item dropdown profile-user-dropdown d-inline-block d-lg-none">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img src="<?=BASEURL?>/assets/chat/images/users/avatar-1.jpg" alt="" class="profile-user rounded-circle">
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="#">Profile <i class="ri-profile-line float-end text-muted"></i></a>
                                <a class="dropdown-item" href="#">Setting <i class="ri-settings-3-line float-end text-muted"></i></a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#">Log out <i class="ri-logout-circle-r-line float-end text-muted"></i></a>
                            </div>
                        </li>
                    </ul>
                </div>
                <!-- end side-menu nav -->

                <div class="flex-lg-column d-none d-lg-block">
                    <ul class="nav side-menu-nav justify-content-center">
                        <li class="nav-item">
                            <a class="nav-link light-dark-mode" href="#" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="right" title="Dark / Light Mode">
                                <i class='ri-sun-line theme-mode-icon'></i>
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- Side menu user -->
            </div>
            <!-- end left sidebar-menu -->

            <!-- start chat-leftsidebar -->
            <div class="chat-leftsidebar me-lg-1 ms-lg-0">

                <div class="tab-content">
                    <!-- Start Profile tab-pane -->
                    <div class="tab-pane" id="pills-user" role="tabpanel" aria-labelledby="pills-user-tab">
                        <!-- Start profile content -->
                        <div>
                            <div class="px-4 pt-4">
                                <h4 class="mb-0">My Profile</h4>
                            </div>

                            <div class="text-center p-4 border-bottom">
                                <div class="mb-1 profile-user">
                                    <a href="javascript:void(0);" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <div class="avatar-container">
                                            <div class="avatar avatar-lg avatar-indicators avatar-online bg-warning rounded-circle ">
                                                <span class=" name_user rounded-circle"><?php echo strtoupper(substr($_SESSION['username'], 0, 2)); ?></span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <h5 class="font-size-16 mb-1 text-truncate"><?php echo $userInfo['username']; ?></h5>
                                
                                <div class="dropdown d-inline-block mb-1">
                                    <a class="text-muted dropdown-toggle pb-1 d-block update_user_status" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="ri-record-circle-fill font-size-10 text-success me-1 ms-0 d-inline-block"></i> Online <i class="mdi mdi-chevron-down"></i>
                                    </a>
          
                                    <div class="dropdown-menu select_user_status" style="">
                                      <a class="dropdown-item" href="javascript:void(0)">Online</a>
                                      <a class="dropdown-item" href="javascript:void(0)">Busy</a>
                                      <a class="dropdown-item" href="javascript:void(0)">Away</a>
                                      <a class="dropdown-item" href="javascript:void(0)">Offline</a>
                                    </div>
                                </div>
                            </div>
                            <!-- End profile user -->

                            <!-- Start user-profile-desc -->
                            <div class="p-4 user-profile-desc" data-simplebar>
                                <div id="tabprofile" class="accordion">
                                    <div class="accordion-item card border mb-2">
                                        <div class="accordion-header" id="about2">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#about" aria-expanded="true" aria-controls="about">
                                                <h5 class="font-size-14 m-0">
                                                    <i class="ri-user-2-line me-2 ms-0 ms-0 align-middle d-inline-block"></i> About
                                                </h5>
                                            </button>
                                        </div>
                                        <div id="about" class="accordion-collapse collapse show" aria-labelledby="about2" data-bs-parent="#tabprofile">
                                            <div class="accordion-body">
                                                <div>
                                                    <p class="text-muted mb-1">Name</p>
                                                    <h5 class="font-size-14"><?php echo $userInfo['username']; ?></h5>
                                                </div>

                                                <div class="mt-4">
                                                    <p class="text-muted mb-1">Email</p>
                                                    <h5 class="font-size-14"><?php echo $userInfo['email']; ?></h5>
                                                </div>

                                                <div class="mt-4">
                                                    <p class="text-muted mb-1">Time</p>
                                                    <h5 class="font-size-14"><?php echo date('h:i A'); ?></h5>
                                                </div>

                                                <div class="mt-4">
                                                    <p class="text-muted mb-1">Location</p>
                                                    <h5 class="font-size-14 mb-0">California, USA</h5>
                                                </div>

                                                <div class="mt-4">
                                                    <p class="text-muted mb-1">Assign Team</p>
                                                    <h5 class="font-size-14 mb-0">
                                                        <?php 
                                                            if ($userInfo['rank'] == "1"){
                                                                echo "Administrative Member";
                                                            }elseif ($row['rank'] == "2"){
                                                                echo "Staff Member";
                                                            }elseif ($row['rank'] == "3"){
                                                                echo "Red Team Member";
                                                            }elseif ($row['rank'] == "4"){
                                                                echo "Blue Team Member";
                                                            }elseif ($row['rank'] == "5"){
                                                                echo "Purple Team Member";
                                                            }else {
                                                                echo "No Team Assigned";
                                                            }
                                                        ?>
                                                    </h5>
                                                </div>

                                                <div class="mt-4">
                                                    <?php 
                                                        $members = $user->getTeamMembers($odb,$userInfo['ID'],$userInfo['rank']);
                                                    ?>
                                                    <p class="text-muted mb-1">Team Members</p>
                                                    <?php if(empty($members)){ ?>
                                                        <h5 class="font-size-14 mb-0">No Member</h5>
                                                    <?php }else{ ?>
                                                        <?php foreach ($members as $key => $value) { ?>
                                                            <span class="badge badge-soft-light  mb-2"><?php echo $value['username']; ?></span>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                   
                                </div>
                                <!-- end profile-user-accordion -->

                            </div>
                            <!-- end user-profile-desc -->
                        </div>
                        <!-- End profile content -->
                    </div>
                    <!-- End Profile tab-pane -->

                    <!-- Start chats tab-pane -->
                    <div class="tab-pane fade show active" id="pills-chat" role="tabpanel" aria-labelledby="pills-chat-tab">
                        <!-- Start chats content -->
                        <div>
                            <div class="px-4 pt-4">
                                <h4 class="mb-4">Chats</h4>
                                <div class="search-box chat-search-box">            
                                    <div class="input-group mb-3 rounded-3">
                                        <span class="input-group-text text-muted bg-light pe-1 ps-3" id="basic-addon1">
                                            <i class="ri-search-line search-icon font-size-18"></i>
                                        </span>
                                        <input type="text" class="form-control bg-light user_list_search" placeholder="Search messages or users" aria-label="Search messages or users" aria-describedby="basic-addon1">
                                    </div> 
                                </div> <!-- Search Box-->
                            </div> <!-- .p-4 -->
    
                            <!-- Start user status -->
                            <div class="px-4 pb-4" dir="ltr">

                                <div class="owl-carousel owl-theme" id="user-status-carousel">
                                    <?php if(!empty($chatuserlist)){ ?>
                                        <?php foreach ($chatuserlist as $key => $value) { 
                                            if($value['partner1']==$loggedUserId){
                                                $partnerid = $value['partner2'];
                                            }else{
                                                $partnerid = $value['partner1'];
                                            }
                                            $partnerInfo = $user->userInfo($odb,$partnerid);

                                        ?>
                                                <div class="item" onclick="openContactChat(<?php echo $partnerid.','.$loggedUserId; ?>)">
                                                    <a href="#" class="user-status-box">
                                                        <div class="avatar-xs mx-auto d-block chat-user-img online">
                                                            <span class="avatar-title rounded-circle bg-soft-primary text-primary">
                                                                <?php echo strtoupper(substr($partnerInfo['username'], 0, 1)); ?>
                                                            </span>
                                                            <span class="user-status"></span>
                                                        </div>
                
                                                        <h5 class="font-size-13 text-truncate mt-3 mb-1"><?php echo $partnerInfo['username']; ?></h5>
                                                    </a>
                                                </div>
                                        <?php }
                                        }
                                    ?>
                                </div>
                                <!-- end user status carousel -->
                            </div>
                            <!-- end user status -->

                            <!-- Start chat-message-list -->
                            <div class="px-2">
                                <h5 class="mb-3 px-3 font-size-16">Recent</h5>

                                <div class="chat-message-list px-2" data-simplebar>
            
                                    <ul class="list-unstyled chat-list chat-user-list">
                                        <?php if(!empty($chatuserlist)){ ?>
                                            <?php foreach ($chatuserlist as $key => $value) { 
                                                if($value['partner1']==$loggedUserId){
                                                    $partnerid = $value['partner2'];
                                                }else{
                                                    $partnerid = $value['partner1'];
                                                }
                                                $partnerInfo = $user->userInfo($odb,$partnerid);
                                                $lastchat = $user->getLastMessage($odb,$partnerid,$loggedUserId);

                                            ?>
                                               <li  class="user_list" user="<?php echo $partnerInfo['username']; ?>" onclick="openContactChat(<?php echo $partnerid.','.$loggedUserId; ?>)">

                                                    <a href="javascript:void(0);">
                                                        <div class="d-flex">                            
                                                            <div class="chat-user-img align-self-center me-3 ms-0">
                                                                <div class="avatar-xs">
                                                                    <span class="avatar-title rounded-circle bg-soft-primary text-primary">
                                                                        <?php echo strtoupper(substr($partnerInfo['username'], 0, 1)); ?>
                                                                    </span>
                                                                    <span class="user-status"></span>
                                                                </div>
                                                            </div>
                                    
                                                            <div class="flex-grow-1 overflow-hidden">
                                                                <h5 class="text-truncate font-size-15 mb-1"><?php echo $partnerInfo['username']; ?></h5>
                                                                <p class="chat-user-message text-truncate mb-0"><?php echo $lastchat['message']; ?></p>
                                                            </div>
                                                            <div class="font-size-11">05 min</div>
                                                        </div>
                                                    </a>
                                                </li>
                                            <?php } ?>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>
                            <!-- End chat-message-list -->
                        </div>
                        <!-- Start chats content -->
                    </div>
                    <!-- End chats tab-pane -->
                    
                    <!-- Start groups tab-pane -->
                    <div class="tab-pane" id="pills-groups" role="tabpanel" aria-labelledby="pills-groups-tab">
                        <!-- Start Groups content -->
                        <div>
                            <div class="p-4">
                                <div class="user-chat-nav float-end">
                                    <div  data-bs-toggle="tooltip" data-bs-placement="bottom" title="Create group">
                                        <!-- Button trigger modal -->
                                        <button type="button" class="btn btn-link text-decoration-none text-muted font-size-18 py-0" data-bs-toggle="modal" data-bs-target="#addgroup-exampleModal">
                                            <i class="ri-group-line me-1 ms-0"></i>
                                        </button>
                                    </div>
            
                                </div>
                                <h4 class="mb-4">Groups</h4>
        
                                <!-- Start add group Modal -->
                                <div class="modal fade" id="addgroup-exampleModal" tabindex="-1" role="dialog" aria-labelledby="addgroup-exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title font-size-16" id="addgroup-exampleModalLabel">Create New Group</h5>
                                                <button type="button" class="btn-close addgroup_close" data-bs-dismiss="modal" aria-label="Close">
                                                </button>
                                                </div>
                                            <div class="modal-body p-4">
                                                <form class="create_group_form">
                                                    <div class="mb-4">
                                                        <label for="addgroupname-input" class="form-label">Group Name</label>
                                                        <input type="text" name="group_name" class="form-control group_name" id="addgroupname-input" placeholder="Enter Group Name">
                                                    </div>
                                                    <div class="mb-4">
                                                        <label class="form-label">Group Members</label>
                                                        <div class="mb-3">
                                                            <button class="btn btn-light btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#groupmembercollapse" aria-expanded="false" aria-controls="groupmembercollapse">
                                                                Select Members
                                                            </button>
                                                        </div>

                                                        <div class="collapse" id="groupmembercollapse">
                                                            <div class="card border">
                                                                <div class="card-header">
                                                                    <h5 class="font-size-15 mb-0">Contacts</h5>
                                                                </div>
                                                                <div class="card-body p-2">
                                                                    <div data-simplebar style="max-height: 150px;">
                                                                        <div>
                                                                            <?php 
                                                                                if(!empty($userorderlist)){ 
                                                                                    foreach($userorderlist as $key=>$value){
                                                                                        echo '<div class="p-3 fw-bold text-primary">'.strtoupper($key).'</div><ul class="list-unstyled contact-list">';
                                                                                        foreach($value as $nkey=>$nvalue){ ?>
                                                                                            <li>
                                                                                                <div class="form-check">
                                                                                                    <input type="checkbox" name="group_member[]" class="form-check-input group_member" id="memberCheck<?php echo $nkey; ?>" value="<?php echo $nkey; ?>">
                                                                                                    <label class="form-check-label" for="memberCheck<?php echo $nkey; ?>"><?php echo $nvalue; ?></label>
                                                                                                </div>
                                                                                            </li>
                                                                                        <?php 
                                                                                        } 
                                                                                        echo '</ul>';
                                                                                    }  
                                                                                } ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                        
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="group_id">
                                                    <input type="hidden" name="action" value="create_group">
                                                </form>
                                            </div>

                                            <div class="modal-footer">
                                                <div class="message_section">
                                                    
                                                </div>
                                                <button type="button" class="btn btn-link" data-bs-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-primary create_group">Create Groups</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End add group Modal -->

                                <div class="search-box chat-search-box">            
                                    <div class="input-group rounded-3">
                                        <span class="input-group-text text-muted bg-light pe-1 ps-3" id="basic-addon1">
                                            <i class="ri-search-line search-icon font-size-18"></i>
                                        </span>
                                        <input type="text" class="form-control bg-light search_group_list" placeholder="Search groups..." aria-label="Search groups..." aria-describedby="basic-addon1">
                                    </div> 
                                </div> <!-- Search Box-->
                            </div>

                            <!-- Start chat-group-list -->
                            <div class="p-4 chat-message-list chat-group-list" data-simplebar>
        

                                <ul class="list-unstyled chat-list group_chat_list">
                                    <?php if(!empty($usergrouplist)){ ?>
                                        <?php foreach($usergrouplist as $key=>$value){ ?>
                                            <li class="group_list" group="<?php echo strtoupper($value['group_name']); ?>" onclick="openGroupChat(<?php echo $value['group_id'].','.$loggedUserId; ?>)">
                                                <a href="javascript:void(0)">
                                                    <div class="d-flex align-items-center">
                                                        <div class="chat-user-img me-3 ms-0">
                                                            <div class="avatar-xs">
                                                                <span class="avatar-title rounded-circle bg-soft-primary text-primary">
                                                                    <?php echo strtoupper($value['group_name'][0]); ?>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 overflow-hidden">
                                                            <h5 class="text-truncate font-size-14 mb-0">#<?php echo strtoupper($value['group_name']); ?></h5>
                                                        </div>
                                                    </div>
                                                </a>
                                            </li>
                                        <?php } ?>
                                    <?php } ?>
                                </ul>
                            </div>
                            <!-- End chat-group-list -->
                        </div>
                        <!-- End Groups content -->
                    </div>
                    <!-- End groups tab-pane -->

                    <!-- Start contacts tab-pane -->
                    <div class="tab-pane" id="pills-contacts" role="tabpanel" aria-labelledby="pills-contacts-tab">
                        <!-- Start Contact content -->
                        <div>
                            <div class="p-4">
                                <div class="user-chat-nav float-end">
                                    <div data-bs-toggle="tooltip" data-bs-placement="bottom" title="Add Contact">
                                        <!-- Button trigger modal -->
                                        <button type="button" class="btn btn-link text-decoration-none text-muted font-size-18 py-0" data-bs-toggle="modal" data-bs-target="#addContact-exampleModal">
                                            <i class="ri-user-add-line"></i>
                                        </button>
                                    </div>
                                </div>
                                <h4 class="mb-4">Contacts</h4>

                                <!-- Start Add contact Modal -->
                                <div class="modal fade" id="addContact-exampleModal" tabindex="-1" role="dialog" aria-labelledby="addContact-exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title font-size-16" id="addContact-exampleModalLabel">Add Contact</h5>
                                                <button type="button" class="btn-close addcontact_close" data-bs-dismiss="modal" aria-label="Close">
                                                </button>
                                            </div>
                                            <div class="modal-body p-4">
                                                <form class="contactsearchform">
                                                    <div class="mb-3">
                                                        <label for="addcontactemail-input" class="form-label">Users</label>
                                                        <input type="text" class="form-control addcontactsearch" id="addcontactsearch-input" placeholder="Search By Email, Username or Phone Number">
                                                        <ul class="list-unstyled contact-list">
                                                            <?php foreach($userlist as $nkey=>$nvalue){ ?>
                                                                <?php if(!in_array($nvalue['ID'], $existContact)){ ?>
                                                                    <li class="search-contact-list" username="<?php echo $nvalue['username']; ?>" email="<?php echo $nvalue['email']; ?>" phone="<?php echo $nvalue['phone']; ?>">
                                                                        <div class="form-check">
                                                                            <input type="checkbox" name="add_contacts[]" class="form-check-input add_contacts" id="memberCheckc<?php echo $nvalue['ID']; ?>" value="<?php echo $nvalue['ID']; ?>">
                                                                            <label class="form-check-label" for="memberCheckc<?php echo $nvalue['ID']; ?>"><?php echo $nvalue['username']; ?></label>
                                                                        </div>
                                                                    </li>
                                                                <?php } ?>
                                                            <?php } ?>
                                                        </ul>
                                                    </div>
                                                    <input type="hidden" name="action" value="add_contacts">
                                                </form>
                                            </div>
                                            <div class="modal-footer">
                                                <div class="message_section">
                                                    
                                                </div>
                                                <button type="button" class="btn btn-link" data-bs-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-primary add_new_contact">Add Contact</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Add contact Modal -->

                                <!-- Start Add contact Modal -->
                                <div class="modal fade customModel" id="shareContact-exampleModal" tabindex="-1" role="dialog" aria-labelledby="addContact-exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title font-size-16" id="shareContact-exampleModalLabel">Share Contact(<span class="sharename"></span>)</h5>
                                                <button type="button" class="btn-close action_close" data-bs-dismiss="modal" aria-label="Close">
                                                </button>
                                            </div>
                                            <div class="modal-body p-4">
                                                <form class="contactshareform">
                                                    <div class="mb-3">
                                                        <label for="sharecontactsearch-input" class="form-label">Contacts</label>
                                                        <input type="text" class="form-control sharecontactsearch" id="sharecontactsearch-input" placeholder="Search By Email, Username or Phone Number">
                                                        <ul class="list-unstyled contact-list">
                                                            <?php foreach($userlist as $nkey=>$nvalue){ ?>
                                                                <?php if(in_array($nvalue['ID'], $existContact)){ ?>
                                                                    <li class="search-share-list " username="<?php echo $nvalue['username']; ?>" email="<?php echo $nvalue['email']; ?>" phone="<?php echo $nvalue['phone']; ?>">
                                                                        <div class="form-check share-user share-user<?php echo $nvalue['ID']; ?>">
                                                                            <input type="checkbox" name="share_contacts[]" class="form-check-input share_contacts" id="memberChecks<?php echo $nvalue['ID']; ?>" value="<?php echo $nvalue['ID']; ?>">
                                                                            <label class="form-check-label" for="memberChecks<?php echo $nvalue['ID']; ?>"><?php echo $nvalue['username']; ?></label>
                                                                        </div>
                                                                    </li>
                                                            <?php } } ?>
                                                        </ul>
                                                    </div>
                                                    <input type="hidden" name="action" value="share_contacts">
                                                    <input type="hidden" class="shared_user" name="shared_user" value="">
                                                </form>
                                            </div>
                                            <div class="modal-footer">
                                                <div class="message_section">
                                                    
                                                </div>
                                                <button type="button" class="btn btn-link action_close" data-bs-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-primary share_new_contact">Share Contact</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Add contact Modal -->


                                <!-- Start Remove contact Modal -->
                                <div class="modal fade customModel" id="actionContact-exampleModal" tabindex="-1" role="dialog" aria-labelledby="addContact-exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title font-size-16" id="Contact-exampleModalLabel">Contact</h5>
                                                <button type="button" class="btn-close action_close" data-bs-dismiss="modal" aria-label="Close">
                                                </button>
                                            </div>
                                            <div class="modal-body p-4">
                                                <form class="actioncontactform">
                                                    <div class="mb-3">
                                                        <p class="confirm_message"></p>
                                                    </div>
                                                    <input type="hidden" class="action_type" name="action" value="">
                                                    <input type="hidden" class="action_id" name="action_id" value="">
                                                </form>
                                            </div>
                                            <div class="modal-footer">
                                                <div class="message_section">
                                                    
                                                </div>
                                                <button type="button" class="btn btn-link action_close" data-bs-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-primary action_remove">Remove</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Remove contact Modal -->

                                <div class="search-box chat-search-box">
                                    <div class="input-group bg-light  input-group-lg rounded-3">
                                        <div class="input-group-prepend">
                                            <button class="btn btn-link text-decoration-none text-muted pe-1 ps-3" type="button">
                                                <i class="ri-search-line search-icon font-size-18"></i>
                                            </button>
                                        </div>
                                        <input type="text" class="form-control bg-light" placeholder="Search users..">
                                    </div>
                                </div>
                                <!-- End search-box -->
                            </div>
                            <!-- end p-4 -->

                            <!-- Start contact lists -->
                            <div class="p-4 chat-message-list chat-group-list" data-simplebar>
        
                                <div class="userexistcontact">
                                    <?php 
                                        if(!empty($userorderlist)){ 
                                            foreach($userorderlist as $key=>$value){
                                                echo '<div class="p-3 fw-bold text-primary">'.strtoupper($key).'</div><ul class="list-unstyled contact-list ">';
                                                foreach($value as $nkey=>$nvalue){ ?>
                                                    <li class="contact_list" user="<?php echo $nvalue; ?>">
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-grow-1" onclick="openContactChat(<?php echo $nkey.','.$loggedUserId; ?>)" >
                                                                <h5 class="font-size-14 m-0"><?php echo $nvalue; ?></h5>
                                                            </div>
                                                            <div class="dropdown">
                                                                <a href="javascript:void(0)" class="text-muted dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    <i class="ri-more-2-fill"></i>
                                                                </a>
                                                                <div class="dropdown-menu dropdown-menu-end">
                                                                    <a class="dropdown-item share_my_contact" onclick="shareContact('<?php echo $nvalue; ?>',<?php echo $nkey; ?>)" href="javascript:void(0)" contact_id="<?php echo $nkey; ?>">Share <i class="ri-share-line float-end text-muted"></i></a>
                                                                    <a class="dropdown-item block_my_contact" href="javascript:void(0)"  onclick="actionContact('block_contact',<?php echo $nkey; ?>)">Block <i class="ri-forbid-line float-end text-muted"></i></a>
                                                                    <a class="dropdown-item remove_my_contact" href="javascript:void(0)"  onclick="actionContact('remove_contact',<?php echo $nkey; ?>)">Remove <i class="ri-delete-bin-line float-end text-muted"></i></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                <?php 
                                                }
                                                echo '</ul>';
                                            }
                                        }
                                    ?>

                                    
                                </div>
                            </div>
                            <!-- end contact lists -->
                        </div>
                        <!-- Start Contact content -->
                    </div>
                    <!-- End contacts tab-pane -->
                    
                    <!-- Start settings tab-pane -->
                    <div class="tab-pane" id="pills-setting" role="tabpanel" aria-labelledby="pills-setting-tab">
                        <!-- Start Settings content -->
                        <div>
                            <div class="px-4 pt-4">
                                <h4 class="mb-0">Settings</h4>
                            </div>

                            <div class="text-center border-bottom p-4">
                                <div class="mb-1 profile-user">
                                    <a href="javascript:void(0);" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <div class="avatar-container">
                                            <div class="avatar avatar-lg avatar-indicators avatar-online bg-warning rounded-circle">
                                                <span class=" name_user rounded-circle"><?php echo strtoupper(substr($_SESSION['username'], 0, 2)); ?></span>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                <h5 class="font-size-16 mb-1 text-truncate"><?php echo $_SESSION['username']; ?></h5>
                                <span class="badge badge-soft-light  mb-2">
                                    <?php 
                                        if ($userInfo['rank'] == "1"){
                                            echo "Administrative Member";
                                        }elseif ($row['rank'] == "2"){
                                            echo "Staff Member";
                                        }elseif ($row['rank'] == "3"){
                                            echo "Red Team Member";
                                        }elseif ($row['rank'] == "4"){
                                            echo "Blue Team Member";
                                        }elseif ($row['rank'] == "5"){
                                            echo "Purple Team Member";
                                        }else {
                                            echo "No Team Assigned";
                                        }
                                    ?>
                                </span>
                                <br/>
                                <div class="dropdown d-inline-block mb-1">
                                    <a class="text-muted dropdown-toggle pb-1 d-block update_user_status" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="ri-record-circle-fill font-size-10 text-success me-1 ms-0 d-inline-block"></i> Online <i class="mdi mdi-chevron-down"></i>
                                    </a>
          
                                    <div class="dropdown-menu select_user_status" style="">
                                      <a class="dropdown-item" href="javascript:void(0)">Online</a>
                                      <a class="dropdown-item" href="javascript:void(0)">Busy</a>
                                      <a class="dropdown-item" href="javascript:void(0)">Away</a>
                                      <a class="dropdown-item" href="javascript:void(0)">Offline</a>
                                    </div>
                                </div>
                            </div>
                            <!-- End profile user -->

                            <!-- Start User profile description -->
                            <div class="p-4 user-profile-desc" data-simplebar>        
                                <div id="settingprofile" class="accordion">
                                    <div class="accordion-item card border mb-2">
                                        <div class="accordion-header" id="personalinfo1">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#personalinfo" aria-expanded="true" aria-controls="personalinfo">
                                                <h5 class="font-size-14 m-0">Personal Info</h5>
                                            </button>
                                        </div>
                                        <div id="personalinfo" class="accordion-collapse collapse show" aria-labelledby="personalinfo1" data-bs-parent="#settingprofile">
                                            <div class="accordion-body">
                                                <div>
                                                    <p class="text-muted mb-1">Name</p>
                                                    <h5 class="font-size-14"><?php echo $userInfo['username']; ?></h5>
                                                </div>

                                                <div class="mt-4">
                                                    <p class="text-muted mb-1">Email</p>
                                                    <h5 class="font-size-14"><?php echo $userInfo['email']; ?></h5>
                                                </div>

                                                <div class="mt-4">
                                                    <p class="text-muted mb-1">Time</p>
                                                    <h5 class="font-size-14"><?php echo date('h:i A'); ?></h5>
                                                </div>

                                                <div class="mt-4">
                                                    <p class="text-muted mb-1">Location</p>
                                                    <h5 class="font-size-14 mb-0">California, USA</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end personal info card -->

                                    <div class="accordion-item card border mb-2">
                                        <div class="accordion-header" id="privacy1">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#privacy" aria-expanded="false" aria-controls="privacy">
                                                <h5 class="font-size-14 m-0">Privacy</h5>
                                            </button>
                                        </div>
                                        <div id="privacy" class="accordion-collapse collapse" aria-labelledby="privacy1" data-bs-parent="#settingprofile">
                                            <div class="accordion-body">
                                                <div class="py-3 border-top">
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-grow-1 overflow-hidden">
                                                            <h5 class="font-size-13 mb-0 text-truncate">Last seen</h5>

                                                        </div>
                                                        <div class="ms-2 me-0">
                                                            <div class="form-check form-switch">
                                                                <input type="checkbox" class="form-check-input" id="privacy-lastseenSwitch" checked>
                                                                <label class="form-check-label" for="privacy-lastseenSwitch"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="py-3 border-top">
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-grow-1 overflow-hidden">
                                                            <h5 class="font-size-13 mb-0 text-truncate">Status</h5>
                                                        </div>
                                                        <div class="dropdown ms-2 me-0">
                                                            <button class="btn btn-light btn-sm dropdown-toggle w-sm" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                Everyone <i class="mdi mdi-chevron-down"></i>
                                                            </button>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a class="dropdown-item" href="#">Everyone</a>
                                                                <a class="dropdown-item" href="#">selected</a>
                                                                <a class="dropdown-item" href="#">Nobody</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="py-3 border-top">
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-grow-1 overflow-hidden">
                                                            <h5 class="font-size-13 mb-0 text-truncate">Read receipts</h5>
                                                        </div>
                                                        <div class="ms-2 me-0">
                                                            <div class="form-check form-switch">
                                                                <input type="checkbox" class="form-check-input" id="privacy-readreceiptSwitch" checked>
                                                                <label class="form-check-label" for="privacy-readreceiptSwitch"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                        
                                                <div class="py-3 border-top">
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-grow-1 overflow-hidden">
                                                            <h5 class="font-size-13 mb-0 text-truncate">Groups</h5>

                                                        </div>
                                                        <div class="dropdown ms-2 me-0">
                                                            <button class="btn btn-light btn-sm dropdown-toggle w-sm" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                Everyone <i class="mdi mdi-chevron-down"></i>
                                                            </button>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a class="dropdown-item" href="#">Everyone</a>
                                                                <a class="dropdown-item" href="#">selected</a>
                                                                <a class="dropdown-item" href="#">Nobody</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end profile-setting-accordion -->
                            </div>
                            <!-- End User profile description -->
                        </div>
                        <!-- Start Settings content -->
                    </div>
                    <!-- End settings tab-pane -->
                </div>
                <!-- end tab content -->

            </div>
            <!-- end chat-leftsidebar -->

            <!-- Start User chat -->
            <div class="user-chat w-100 overflow-hidden">
                <div class="d-lg-flex livechat_container" >

                    
                </div>
            </div>
            <!-- End User chat -->
        </div>
        <!-- end  layout wrapper -->

        <!-- Start Remove chat confirm Modal -->
        <div class="modal fade customModel" id="confirm-exampleModal" tabindex="-1" role="dialog" aria-labelledby="confirm-exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title font-size-16" id="Contact-exampleModalLabel">Contact</h5>
                        <button type="button" class="btn-close action_close" data-bs-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body p-4">
                        <form class="confirm_form">
                            <div class="mb-3">
                                <p class="confirm_message"></p>
                            </div>
                            <input type="hidden" class="action_type" name="action" value="">
                            <input type="hidden" class="conatct_user_id" name="contact_id" value="">
                        </form>
                    </div>
                    <div class="modal-footer">
                        <div class="message_section">
                            
                        </div>
                        <button type="button" class="btn btn-link action_close" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary confirm_remove">Remove</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Remove chat confirm Modal -->

        <!-- Start forward message Modal -->
        <div class="modal fade customModel" id="forwardmsg-exampleModal" tabindex="-1" role="dialog" aria-labelledby="forwardmsg-exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title font-size-16" id="forwardmsg-exampleModalLabel">Message ( <span class="forwardmsg"></span> )</h5>
                        <button type="button" class="btn-close action_close" data-bs-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body p-4">
                        <form class="forwardmsgform">
                            <div class="mb-3">
                                <label for="forwardsearch-input" class="form-label">Contacts</label>
                                <input type="text" class="form-control forwardsearch" id="forwardsearch-input" placeholder="Search By Email, Username or Phone Number">
                                <ul class="list-unstyled contact-list">
                                    <?php foreach($userlist as $nkey=>$nvalue){ ?>
                                        <?php if(in_array($nvalue['ID'], $existContact)){ ?>
                                            <li class="search-forward-list " username="<?php echo $nvalue['username']; ?>" email="<?php echo $nvalue['email']; ?>" phone="<?php echo $nvalue['phone']; ?>">
                                                <div class="form-check share-user share-user<?php echo $nvalue['ID']; ?>">
                                                    <input type="checkbox" name="contacts[]" class="form-check-input forward_contacts" id="memberChecks<?php echo $nvalue['ID']; ?>" value="<?php echo $nvalue['ID']; ?>">
                                                    <label class="form-check-label" for="memberChecks<?php echo $nvalue['ID']; ?>"><?php echo $nvalue['username']; ?></label>
                                                </div>
                                            </li>
                                    <?php } } ?>
                                </ul>
                            </div>
                            <input type="hidden" name="action" value="forward_message">
                            <input type="hidden" class="forward_message" name="message" value="">
                        </form>
                    </div>
                    <div class="modal-footer">
                        <div class="message_section">
                            
                        </div>
                        <button type="button" class="btn btn-link action_close" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary forward_msg_button">Send Message</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End forward message Modal  -->

        <!-- JAVASCRIPT -->
        <script src="<?=BASEURL?>assets/chat/libs/jquery/jquery.min.js"></script>
        <script src="<?=BASEURL?>assets/chat/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="<?=BASEURL?>assets/chat/libs/simplebar/simplebar.min.js"></script>
        <script src="<?=BASEURL?>assets/chat/libs/node-waves/waves.min.js"></script>

        <!-- Magnific Popup-->
        <script src="<?=BASEURL?>assets/chat/libs/magnific-popup/jquery.magnific-popup.min.js"></script>

        <!-- owl.carousel js -->
        <script src="<?=BASEURL?>assets/chat/libs/owl.carousel/owl.carousel.min.js"></script>

        <!-- page init -->
        <script src="<?=BASEURL?>assets/chat/js/pages/index.init.js"></script>

        <script src="<?=BASEURL?>assets/chat/js/app.js"></script>
        <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
        <script>
            // Enable pusher logging - don't include this in production
            Pusher.logToConsole = true;
        </script>      
        <script src="<?=BASEURL?>livechat/livechat.js"></script>
    </body>
</html>
