<?php 
    if(!empty($userorderlist)){ 
        foreach($userorderlist as $key=>$value){
            echo '<div class="p-3 fw-bold text-primary">'.strtoupper($key).'</div><ul class="list-unstyled contact-list ">';
            foreach($value as $nkey=>$nvalue){ ?>
                <li class="contact_list" user="<?php echo $nvalue; ?>">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1" onclick="openContactChat(<?php echo $nkey; ?>)" >
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