
<?php
ob_start();
require 'includes/db.php';
require 'includes/init.php';
if (($user -> LoggedIn())){
    header('location: index.php');
    die;
}
$id = $_GET["id"];
$token = $_GET["token"];

// check if id exist
$stmp = $odb -> prepare("SELECT id, `key`, used FROM `users` WHERE `id` = :id");
$stmp -> execute(array(':id' => $id));
$row = $stmp -> fetch(PDO::FETCH_ASSOC);
$message = '';

if( !$row  )
{
	header("Location: /errorp/tokenexpirlight.html");
	$message = '<strong><div class="error" id="message"><p>ERROR: </strong><br />Invalid Token</p></div>';	
}
else {
	$titleShow = $show['title'];
	$detailShow = $show['detail'];
	$rowID = $show['ID'];
						
	$key = $row['key'];
	$used = $row['used'];
	
	if( $key != $token || empty($token)  )
	{
		header("Location: /errorp/tokenexpirlight.html");
		$message = '<strong><div class="error" id="message"><p>ERROR: </strong><br />Invalid Token</p></div>';	
	}
	else
	{
		if( $used != 0  )
		{
			header("Location: /errorp/tokenexpirlight.html");
			$message = '<strong><div class="error" id="message"><p>ERROR:</strong><br />Token is expired</p></div>';
			exit();
		}
	}
}
	
if(isset($_SESSION['theme_mode']) && $_SESSION['theme_mode']=='light'){ $load_screen = "load_screen_light"; }else{ $load_screen = "load_screen_dark"; }			  

?>


    <!--  END LOADER -->
<div class="container-fluid  py-5">
    <div class="row login-top">
 <div class="col-md-6 ">
     <div class="overlay"></div>
        <div class="search-overlay"></div>

    <div class="auth-container d-flex h-100">

        <div class="container">
    
            <div class="row">
    
           
                    <div class="card mt-3 mb-3">
                        <div class="card-body">
    
                            <div class="row">
                                <div class="col-md-12 mb-3">
                               
                                    <h2>Password Reset</h2>
									
                                </div>
								<div id="message"><?php echo $message; ?></div>
                                <div class="col-md-12" id="input-container-one">
						<p>Enter Your New Password</p>
                                    <div class="mb-4">
                                        <label class="form-label">New Password</label>
                                        <input type="password" id="password" class="form-control">
                                    </div>
                                </div>
								
								<div class="col-md-12" id="input-container-two">
                                    <div class="mb-4">
                                        <label class="form-label">Confirm Password</label>
                                        <input type="password" id="confirm_password" class="form-control">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-4">
                                        <button class="btn btn-secondary w-100" id="btn-r" onclick="resetPassword()">Reset Password</button>
                                    </div>
                                </div>
								
								
                                
                            </div>
                            
                        </div>
                    </div>
         
                
            </div>
            
        </div>

    </div>
    </div>
<div class="col-md-6"><div class="form-right-img">
                        <figure>
                            <img src="assets/img/form-right-img-5.png" alt="">
                        </figure>
                    </div></div>

      </div>
        </div>
    
    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="../src/plugins/src/global/vendors.min.js"></script>
    <script src="../src/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- END GLOBAL MANDATORY SCRIPTS -->
	
	<script>
         function changeThemeMode(mode=''){
            $.ajax({
                url: "<?=BASEURL?>theme-mode-session.php",
                type: "post",
                data: {
                    mode: mode
                },
                success: function(response) { 
                }
            });
        }
    </script>
	
	<script>
		function displayErrorMeessage(msg) {
			document.getElementById("message").innerHTML = `<strong><div class="error" id="message"><p>ERROR: </strong><br />${msg}</p></div>`;
		}
		
		function displayMeessage(msg) {
			document.getElementById("message").innerHTML = `<strong><div class="message" id="message"><p>SUCCESS: </strong><br />${msg}</p></div>`;
			document.getElementById("input-container-one").style.display = 'none';
			document.getElementById("input-container-two").style.display = 'none';
			document.getElementById("btn-r").style.display = 'none';
		}

		var pa = document.getElementById("password");
		var cpa = document.getElementById("confirm_password");

		if(pa){
            pa.addEventListener("keydown", function (e) {
                if (e.code === "Enter") {  //checks whether the pressed key is "Enter"
                    resetPassword()
                }
            });
        }

        if(cpa){
            cpa.addEventListener("keydown", function (e) {
                if (e.code === "Enter") {  //checks whether the pressed key is "Enter"
                    resetPassword()
                }
            });
        }
		
		// call api for sending forgot password
		async function resetPassword() {
			const password = document.getElementById("password").value;
			const confirm_password = document.getElementById("confirm_password").value;
			
			if( password == "" )
			{
				displayErrorMeessage("The New Password field is empty!");
				return;
			}
			
			if( confirm_password == "" )
			{
				displayErrorMeessage("The Confirm Password field is empty!");
				return;
			}
			
			if( password != confirm_password ) {
				displayErrorMeessage("The passwords do not match!");
				return;
			}
			
			var id = "<?php echo $_GET['id']; ?>";
			var token = "<?php echo $_GET['token']; ?>";
			
			var form = new FormData();
			form.append('id', id);
			form.append('token', token);
			form.append('password', password);
			
			const res = await fetch("reset_password_proc.php", {
				body: form,
				method: 'POST'
			});
			
			

			const data = await res.json();
			console.log("data", data);
			
			if( data['code'] == 405 )
			{
				location.href = "/errorp/tokenexpirlight.html";
				return;
			}
			
			if( data['code'] != 200 )
			{
				displayErrorMeessage(data["message"]);
				return;
			}
			
			displayMeessage(data["message"]);
			
			setTimeout(function() {
				location.href = "/login.php";
			}, 7000);
		}
		
	    // $( document ).ready(function() {
        // $('body').addClass('loaderNone')
          
       //});
        $(window).on('load', function () { 
           $('body').removeClass('loaderNone')
          $('#loading').remove();
          
        });
		
	</script>
	<?php require_once 'home-footer.php';  ?>

</body>
</html>