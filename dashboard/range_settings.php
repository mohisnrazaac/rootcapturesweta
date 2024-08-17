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

    $title = 'API Management';
    require_once('common/header.php'); 
    
?>

<div class="container centralize_container pt-5 ">

	<div class="row pt-5">
		<div class="col-4">
			<div class="rc_card_heading">
				Maintenance Mode
			</div>
			<div class="p-4 rc_card_body d-flex justify-content-center">
				<button style="margin: 0 auto;" type="submit" class="rc-btn enable-button">Enable</button>
			</div>
		</div>



		<div class="col-4">
			<div class="rc_card_heading">
				Platform Registration
			</div>
			<div class="p-4 rc_card_body d-flex justify-content-center">
				<button style="margin: 0 auto;" type="submit" class="rc-btn disable-button">Disable</button>
			</div>
		</div>



		<div class="col-4">
			<div class="rc_card_heading">
				Active Red Team CFT
			</div>
			<div class="p-4 rc_card_body d-flex justify-content-center">

				<button style="margin: 0 auto;" type="submit" class="rc-btn enable-button">Enable</button> </div>
		</div>



	</div>



</div>

<?php require_once('common/footer.php') ?>

