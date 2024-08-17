<?php

ob_start();
require_once '../includes/db.php';
require_once '../includes/init.php';
if (!($user -> LoggedIn()))
{
    header('location: ../login.php');
    die();
}
if (!($user -> notBanned($odb)))
{
    header('location: ../login.php');
    die();
}

if ($user -> isAdmin($odb)) {

} else {
	header('location: ../index.php');
	die();
}

$pageTitle = 'Manage Teams';
require_once '../header.php';

if (isset($_POST['setting_save'])){
	print_r($_POST);
}

?>
    


    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container" id="container">

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
                                <li class="breadcrumb-item active" aria-current="page">Settings</li>
                            </ol>
                
                        </nav>
                    </div>
                    <!-- /BREADCRUMB -->
                    <div class="row layout-top-spacing">
                       <div class="row mb-3">
                            <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
			                  	<form class="section general-info setting_form" method="POST">
			                    	<div class="info">
			                      		<div align="Center">
			                        		<h6 class="">Login System</h6>
			                        	</div>
			                        	<div class="row">
				                        	<div class="col-md-6 customBluBg mb-3">
			                                    <div class="headingForRadio">Registration Enable/Disable</div>
		                                        <div class="form-check form-check-primary form-check-inline mt-3">
	                                                <input class="form-check-input" type="radio" name="register_option" id="form-check-radio-default" checked>
	                                                <label class="form-check-label" for="form-check-radio-default">
	                                                	Enable
	                                                </label>
		                                        </div>

		                                        <div class="form-check form-check-primary form-check-inline mt-3">
	                                                <input class="form-check-input" type="radio" name="register_option" id="form-check-radio-default-checked" >
	                                                <label class="form-check-label" for="form-check-radio-default-checked">
	                                                    Disable
	                                                </label>
	                                            </div>
			                                </div>
			                            </div>
			                            <div class="row">
				                        	<div class="col-md-6 mb-3">
				                        	</div>
				                        	<div class="col-md-6 mb-3">
				                        		<div class="form-group text-end">
			                                        <input type="submit" id="setting_save" name="setting_save" class="btn btn-outline-success btn-lrg">
			                                    </div>
				                        	</div>
				                        </div>
			                        </div>
			                    </form>
			                </div>
                        </div>
                    </div>
                </div>

            </div>

            <!--  BEGIN FOOTER  -->
                <?php require_once '../includes/footer-section.php'; ?>
            <!--  END FOOTER  -->
            <!--  END CONTENT AREA  -->
        </div>
        <!--  END CONTENT AREA  -->
    </div>
    <!-- END MAIN CONTAINER -->
    <?php  require_once '../footer.php'; ?>
</body>
</html>