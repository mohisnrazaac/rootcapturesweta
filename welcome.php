<?php
ob_start();
require_once 'includes/db.php';
require_once 'includes/init.php';
@session_start();
if (!($user -> LoggedIn()))
{
	if(isset($_GET['r'])){
		$_SESSION['refer'] = preg_replace("/[^A-Za-z0-9-]/","", $_GET['r']);
		header('Location: login.php');
		die();
	}
	header('location: login.php');
	die();
}
if (!($user -> notBanned($odb)))
{
	header('location: login.php');
	die();
}

        $getUserDetailIdWise = $user->getUserDetailIdWise($odb); 
        $college_id = $getUserDetailIdWise['college_id']; 
    // check user preference
    $loggedUserId = $_SESSION['ID'];
    $usersql = $odb -> prepare("SELECT `otp_verification_preference` FROM `users` WHERE `ID` = :id");
    $usersql -> execute(array(":id" => $loggedUserId));
    $row = $usersql -> fetch(); 
    $otp_verification_preference = $row['otp_verification_preference']; 

    if( isset($_POST['banned_form']) )
    {
        $fa_preference = $_POST['2fa_preference']; 
        if( isset($fa_preference) &&  $fa_preference == 'on')
        {
            $fa_preference = 1;
        }
        else
        {
            $fa_preference = 2;
        }

        $updateSql = $odb -> prepare("UPDATE users SET `otp_verification_preference` = :otp_verification_preference WHERE id = :id");
        $updateSql -> execute(array(':otp_verification_preference' => $fa_preference, ':id' => $loggedUserId));
        $otp_verification_preference = $fa_preference;
    }

    $pageTitle = 'Cyber Range';
    require_once 'header.php';

?>

    <!--  BEGIN MAIN CONTAINER  -->
<div class="main-container welcome-page" id="container">
  <div class="overlay"></div>
  <div class="search-overlay"></div>
  <!--  BEGIN SIDEBAR  --> <?php include 'sidebar.php'; ?>
  <!--  END SIDEBAR  -->
  <!--  BEGIN CONTENT AREA  -->
  <div id="content" class="main-content">
    <div class="container">
    <div class="layout-px-spacing">
      <div class="middle-content container-xxl p-0">
        <br>
        <div align="Center">
            <h1 class="">
                <font>Welcome to rootcapture</font>
            </h1>
            <div class="right-traing-img">
                        <figure>
                            <img src="assets/img/traing-img.png" alt="">
                        </figure>
                    </div>
        </div>
        <div class="row justify-content-center"> 
        
            
                   <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12 layout-spacing col-xl-offset-4 col-md-offset-5">
                    <div class="widget widget-card-one para-margin-none">
                        <div class="widget-content text-center">
                         <div class="bigText text-center white-text">Continue with Rootcapture </div>
                        <div class="imgSvg text-center"><svg xmlns="http://www.w3.org/2000/svg" width="54" height="54" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg></div>
                        <div class="textPat text-center">Lorem ipsum is placeholder text commonly used in the graphic</div>
                          <a class="btn btn-primary cutClickBut" href="https://rootcapture.com/#contact_us" role="button">Click here</a>
                        </div>
                    </div>
                </div>
                   <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12 layout-spacing col-xl-offset-4 col-md-offset-5">
                    <div class="widget widget-card-one para-margin-none">
                        <div class="widget-content text-center">
                         <div class="bigText text-center white-text">Continue with Course </div>
                        <div class="imgSvg text-center"><svg xmlns="http://www.w3.org/2000/svg" width="54" height="54" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-dollar-sign"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg></div>
                         <div class="textPat text-center">Lorem ipsum is placeholder text commonly used in the graphic</div>
                          <a class="btn btn-primary cutClickBut" href="https://rootcapture.com/#contact_us" role="button">Click here</a>
                        </div>
                    </div>
                </div>
            
          
        </div>
        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">2 FA verification preference</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                  <svg> ... </svg>
                </button>
              </div>
              <form method="post">
                <div class="modal-body">
                  <p class="modal-text">2 FA verification preference </p>
                  <p>
                  <div class="form-group mt-4">
                    <div class="switch form-switch-custom switch-inline form-switch-primary form-switch-custom inner-label-toggle show mb-sm-0 mb-3">
                      <div class="input-checkbox">
                        <span class="switch-chk-label label-left">SMS</span>
                        <input name="2fa_preference" class="switch-input" type="checkbox" role="switch" id="form-custom-switch-inner-label" onchange="this.checked ? this.closest('.inner-label-toggle').classList.add('show') : this.closest('.inner-label-toggle').classList.remove('show')" checked>
                        <span class="switch-chk-label label-right">Email</span>
                      </div>
                    </div>
                  </div>
                  </p>
                </div>
                <div class="modal-footer">
                  <button class="btn" data-bs-dismiss="modal">
                    <i class="flaticon-cancel-12"></i> Discard </button>
                  <button type="submit" name="banned_form" class="btn btn-primary">Save</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
  </div>
    </div>
    <!--  BEGIN FOOTER  --> <?php require_once 'includes/footer-section.php'; ?>
    <!--  END FOOTER  -->
  </div>
  <!--  END CONTENT AREA  -->
</div>
<!-- END MAIN CONTAINER -->

<?php  require_once 'footer.php'; ?>

<script type="text/javascript">
    var otp_verification_preference = '<?php echo $otp_verification_preference; ?>';
    if(otp_verification_preference == null || otp_verification_preference == '')
    {
        $(window).on('load', function() {
          $('#exampleModal').modal('show');
       });
    }  

       $( document ).ready(function() {
          $('body').addClass('loaderNone')
      });
        $(window).on('load', function () {
          $('#loading').remove();
           $('body').addClass('loaderNone')
        });  
</script>

</body>
</html>