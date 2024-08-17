<?php 
$group_id = $_POST['group_id']; 
$senderInfo = $user->userInfo($odb,$loggedUserId);

$groupmsg = $odb -> prepare("SELECT * FROM `chat_group_msg` WHERE `group_id` = :group_id");
$groupmsg -> execute(array(':group_id' => $group_id));
$groupchat = $groupmsg -> fetchAll(PDO::FETCH_ASSOC); 

$groupquery = $odb -> prepare("SELECT * FROM `chat_group` WHERE `group_id` = :group_id");
$groupquery -> execute(array(':group_id' => $group_id));
$group = $groupquery -> fetch(PDO::FETCH_ASSOC); 

?>
<div class="w-100 overflow-hidden position-relative">
    <div class="p-3 p-lg-4 border-bottom user-chat-topbar">
        <div class="row align-items-center">
            <div class="col-sm-4 col-8">
                <div class="d-flex align-items-center">
                    <div class="d-block d-lg-none me-2 ms-0">
                        <a href="javascript: void(0);" class="user-chat-remove text-muted font-size-16 p-2"><i class="ri-arrow-left-s-line"></i></a>
                    </div>
                    <div class="avatar-xs">
                        <span class="avatar-title rounded-circle bg-soft-primary text-primary"><?php echo strtoupper(substr($group['group_name'], 0, 1)); ?></span>
                    </div>
                    <div class="flex-grow-1 m-2 overflow-hidden">
                        <h5 class="font-size-16 mb-0 text-truncate"><a href="#" class="text-reset user-profile-show"  onclick="showUserProfile();"><?php echo $group['group_name']; ?></a></h5>
                    </div>
                </div>
            </div>
            <div class="col-sm-8 col-4">
                <ul class="list-inline user-chat-nav text-end mb-0">                                        
                    <li class="list-inline-item">
                        <div class="dropdown">
                            <button class="btn nav-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="ri-search-line"></i>
                            </button>
                            <div class="dropdown-menu p-0 dropdown-menu-end dropdown-menu-md">
                                <div class="search-box p-2">
                                    <input type="text" class="form-control bg-light border-0 chat_search" placeholder="Search..">
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="list-inline-item d-none d-lg-inline-block me-2 ms-0">
                        <button type="button" class="btn nav-btn user-profile-show"  onclick="showUserProfile();">
                            <i class="ri-user-2-line"></i>
                        </button>
                    </li>

                    <!--<li class="list-inline-item">
                        <div class="dropdown">
                            <button class="btn nav-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="ri-more-fill"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="javascript:void(0);" onclick="archiveChat(<?php echo $receiverInfo['ID']; ?>)">Archive <i class="ri-archive-line float-end text-muted"></i></a>
                                <?php if(!empty($mute)){ ?>
                                    <a class="dropdown-item" href="javascript:void(0);" onclick="muteChat(<?php echo $receiverInfo['ID']; ?>,'unmute')">Unmuted <i class="ri-volume-mute-line float-end text-muted"></i></a>
                                <?php }else{ ?>
                                    <a class="dropdown-item" href="javascript:void(0);" onclick="muteChat(<?php echo $receiverInfo['ID']; ?>,'mute')">Muted <i class="ri-volume-mute-line float-end text-muted"></i></a>
                                <?php } ?>
                                <a class="dropdown-item" href="javascript:void(0);" onclick="deleteChat(<?php echo $receiverInfo['ID']; ?>)">Delete <i class="ri-delete-bin-line float-end text-muted"></i></a>
                            </div>
                        </div>
                    </li>-->                                  
                </ul>                                    
            </div>
        </div>
    </div>
    <!-- end chat user head -->
    <!-- start chat conversation -->
    <div class="chat-conversation p-3 p-lg-4" data-simplebar="init">
        <ul class="list-unstyled mb-0 websocket-chat-box">
            <?php if ( !empty( $groupchat ) ){ ?>
                <?php 
                    foreach($groupchat as $chat){ 
                        $chatUser = $user->userInfo($odb,$chat['sender_id']);
                        if($chat['sender_id'] == $loggedUserId) {
                            
                ?>
                            <li class="right chat_list" chat="<?php echo $chat['msg']; ?>">
                                <div class="conversation-list">
                                    <div class="chat-avatar sender-avatar">
                                        <span class="avatar-title rounded-circle bg-soft-primary text-primary"><?php echo strtoupper(substr($senderInfo['username'], 0, 1)); ?></span>
                                    </div>

                                    <div class="user-chat-content">
                                        <div class="ctext-wrap">
                                            <div class="ctext-wrap-content">
                                                <p class="mb-0 msgid">
                                                    <?php echo $chat['msg']; ?>
                                                </p>
                                                <p class="chat-time mb-0"><i class="ri-time-line align-middle"></i> <span class="align-middle"><?php echo date("d-m-Y h:i:s",strtotime($chat['created_on'])); ?></span></p>
                                            </div>
                                                
                                            <div class="dropdown align-self-start">
                                                <a class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="ri-more-2-fill"></i>
                                                </a>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="javascript:void(0);" onclick='copyMessage("<?php echo $chat['msg']; ?>")'>Copy <i class="ri-file-copy-line float-end text-muted"></i></a>
                                                    <a class="dropdown-item" href="javascript:void(0);" onclick='forwardMessage("<?php echo $chat['msg']; ?>")'>Forward <i class="ri-chat-forward-line float-end text-muted"></i></a>
                                                    <a class="dropdown-item" href="javascript:void(0);" onclick='deleteMessage("<?php echo $chat['msg_id']; ?>",<?php echo $group_id; ?>)'>Delete <i class="ri-delete-bin-line float-end text-muted"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="conversation-name m-2"><?php echo $senderInfo['username']; ?></div>
                                    </div>
                                </div>
                            </li>
                <?php   }else{ ?>
                            <li class="chat_list" chat="<?php echo $chat['msg']; ?>">
                                <div class="conversation-list">
                                    <div class="avatar-xs">
                                        <span class="avatar-title rounded-circle bg-soft-primary text-primary"><?php echo strtoupper(substr($chatUser['username'], 0, 1)); ?></span>
                                    </div>

                                    <div class="user-chat-content">
                                        <div class="ctext-wrap">
                                            <div class="ctext-wrap-content">
                                                <p class="mb-0 msgid">
                                                    <?php echo $chat['msg']; ?>
                                                </p>
                                                <p class="chat-time mb-0"><i class="ri-time-line align-middle"></i> 
                                                    <span class="align-middle">
                                                        <?php echo date("d-m-Y h:i:s",strtotime($chat['created_on'])); ?>
                                                    </span>
                                                </p>
                                            </div>
                                            <div class="dropdown align-self-start">
                                                <a class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="ri-more-2-fill"></i>
                                                </a>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="javascript:void(0);" onclick='copyMessage("<?php echo $chat['msg']; ?>")'>Copy <i class="ri-file-copy-line float-end text-muted"></i></a>
                                                    <a class="dropdown-item" href="javascript:void(0);" onclick='forwardMessage("<?php echo $chat['msg']; ?>")'>Forward <i class="ri-chat-forward-line float-end text-muted"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="conversation-name m-2"><?php echo $chatUser['username']; ?></div>
                                    </div>
                                </div>
                            </li>
                <?php   }  
                    }
                }
            ?>
        </ul>
    </div>
    <!-- end chat conversation end -->
    <!-- start chat input section -->
    <div class="chat-input-section p-3 p-lg-4 border-top mb-0">
        <div class="row g-0">
            <div class="col">
                <input type="text" class="form-control form-control-lg bg-light border-light chat-websocket" placeholder="Enter Message...">
                <input type="hidden" name="login_user_id" class="login_user_id" value="<?php echo $senderInfo['ID']; ?>">
                <input type="hidden" name="login_user_name" class="login_user_name" value="<?php echo $senderInfo['username']; ?>">
                <input type="hidden" name="receiver_user_id" class="receiver_user_id" value="0">
                <input type="hidden" name="receiver_user_name" class="receiver_user_name" value="0">
                <input type="hidden" name="chat_group_id" class="chat_group_id" value="<?php echo $group_id; ?>">
                <input type="hidden" name="channel_name" class="channel_name" value="<?php echo $group['channel_name']; ?>">
                
            </div>
            <div class="col-auto">
                <div class="chat-input-links ms-md-2 me-md-0">
                    <ul class="list-inline mb-0">
                        <!-- <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-placement="top" title="Emoji">
                            <button type="button" class="btn btn-link text-decoration-none font-size-16 btn-lg waves-effect">
                                <i class="ri-emotion-happy-line"></i>
                            </button>
                        </li> -->
                        <li class="list-inline-item">
                            <button type="submit" class="btn btn-primary font-size-16 btn-lg chat-send waves-effect waves-light chat-send-websocket">
                                <i class="ri-send-plane-2-fill"></i>
                            </button>
                        </li>
                    </ul>
                </div>
                
            </div>
        </div>
    </div>
</div>
<!-- end chat input section -->
<!-- start User profile detail sidebar -->
<div class="user-profile-sidebar">
    <div class="px-3 px-lg-4 pt-3 pt-lg-4">
        <div class="user-chat-nav text-end">
            <button type="button" class="btn nav-btn" id="user-profile-hide" onclick="closeSidebar()">
                <i class="ri-close-line"></i>
            </button>
        </div>
    </div>

    <div class="text-center p-4 border-bottom">
        <div class="mb-1 profile-user">
            <a href="javascript:void(0);" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div class="avatar-container">
                    <div class="avatar avatar-lg avatar-indicators avatar-online bg-warning rounded-circle ">
                        <span class=" name_user rounded-circle"><?php echo strtoupper(substr($group['group_name'], 0, 2)); ?></span>
                    </div>
                </div>
            </a>
        </div>

        <h5 class="font-size-16 mb-1 text-truncate"><?php echo $group['group_name']; ?></h5>
        <p class="text-muted text-truncate mb-1"> Active</p>
    </div>
    <!-- End profile user -->

    <!-- Start user-profile-desc -->
    <div class="p-4 user-profile-desc" data-simplebar>
        
        <div class="accordion" id="myprofile">

            <div class="accordion-item card border mb-2">
                <div class="accordion-header" id="about3">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#aboutprofile" aria-expanded="true" aria-controls="aboutprofile">
                        <h5 class="font-size-14 m-0">
                            <i class="ri-user-2-line me-2 ms-0 align-middle d-inline-block"></i> About
                        </h5>
                    </button>
                </div>
                <div id="aboutprofile" class="accordion-collapse collapse show" aria-labelledby="about3" data-bs-parent="#myprofile">
                    <div class="accordion-body">
                        <div>
                            <p class="text-muted mb-1">Group Name</p>
                            <h5 class="font-size-14"><?php echo $group['group_name']; ?></h5>
                        </div>
                    </div>
                </div>
            </div>                                
        <!-- end profile-user-accordion -->
    </div>
    <!-- end user-profile-desc -->
</div>
<!-- end User profile detail sidebar -->
<div class="medatacontent"  style="display:none">
    <li class="right chat_list" chat="{{MESSAGE}}">
        <div class="conversation-list">
            <div class="chat-avatar sender-avatar">
                <span class="avatar-title rounded-circle bg-soft-primary text-primary">{{SHORTNAME}}</span>
            </div>

            <div class="user-chat-content">
                <div class="ctext-wrap">
                    <div class="ctext-wrap-content">
                        <p class="mb-0 msgid">
                            {{MESSAGE}}
                        </p>
                        <p class="chat-time mb-0"><i class="ri-time-line align-middle"></i> <span class="align-middle">{{DATE}}</span></p>
                    </div>
                        
                    <div class="dropdown align-self-start">
                        <a class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="ri-more-2-fill"></i>
                        </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="javascript:void(0);" onclick='copyMessage("{{MESSAGE}}")'>Copy <i class="ri-file-copy-line float-end text-muted"></i></a>
                            <a class="dropdown-item" href="javascript:void(0);" onclick='forwardMessage("{{MESSAGE}}")'>Forward <i class="ri-chat-forward-line float-end text-muted"></i></a>
                            <a class="dropdown-item" href="javascript:void(0);" onclick='deleteMessage("{{MESSAGE}}","{{MESSAGEID}}")'>Delete <i class="ri-delete-bin-line float-end text-muted"></i></a>
                        </div>
                    </div>
                </div>
                
                <div class="conversation-name m-2">{{USERNAME}}</div>
            </div>
        </div>
    </li>
</div>

<div class="otherdatacontent" style="display:none">
    <li class="chat_list" chat="{{MESSAGE}}">
        <div class="conversation-list">
            <div class="avatar-xs">
                <span class="avatar-title rounded-circle bg-soft-primary text-primary">{{SHORTNAME}}</span>
            </div>

            <div class="user-chat-content">
                <div class="ctext-wrap">
                    <div class="ctext-wrap-content">
                        <p class="mb-0 msgid">
                            {{MESSAGE}}
                        </p>
                        <p class="chat-time mb-0"><i class="ri-time-line align-middle"></i> <span class="align-middle">{{DATE}}</span></p>
                    </div>
                    <div class="dropdown align-self-start">
                        <a class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="ri-more-2-fill"></i>
                        </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="javascript:void(0);" onclick='copyMessage("{{MESSAGE}}")'>Copy <i class="ri-file-copy-line float-end text-muted"></i></a>
                            <a class="dropdown-item" href="javascript:void(0);" onclick='forwardMessage("{{MESSAGE}}")'>Forward <i class="ri-chat-forward-line float-end text-muted"></i></a>
                        </div>
                    </div>
                </div>
                <div class="conversation-name m-2">{{USERNAME}}</div>
            </div>
        </div>
    </li>
</div>