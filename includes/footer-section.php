<!--  BEGIN FOOTER  -->
<div class="footer-wrapper">
    <div class="footer-section f-section-1">				
        <p class="">Copyright © <span class="dynamic-year"><?=date('Y')?></span> <a target="_blank" href="<?=BASEURL?>">rootCapture</a>, All rights reserved.</p>
    </div>

    <!-- <div class="footer-section f-section-2">
                    <p class="">Coded with <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-heart"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg></p>
    </div> -->
</div>

<?php
    $college_idd = $getUserDetailIdWise['college_id'] ? $getUserDetailIdWise['college_id'] : 0;
    $isMaintanenceMode = $user->isMaintanenceMode($odb,$college_idd);
    $getUserDetailIdWise = $user->getUserDetailIdWise($odb); 
    if( ($isMaintanenceMode) && ($getUserDetailIdWise['team_name'] == 'Red Team' || $getUserDetailIdWise['team_name'] == 'Blue Team' || $getUserDetailIdWise['team_name'] == 'Purple Team'))
    {
        header("Location: https://rootcapture.com/maintenance.php");  exit;
    }
?>

<!--  END FOOTER  -->
