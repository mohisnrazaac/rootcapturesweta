<?php
    ob_start();
    require '../includes/db.php';
    require '../includes/init.php';
    require_once '../home-header.php';
    if (($user -> LoggedIn())){
        header('location: index.php');
    }
    if(isset($_SESSION['theme_mode']) && $_SESSION['theme_mode']=='light'){ $load_screen = "load_screen_light"; }else{ $load_screen = "load_screen_dark"; }
?>

  
<div class="container-fluid  py-5">
    <div class="row login-top">
        <div class="col-md-6 ">
    <div class=" " id="container">

        <div class="overlay"></div>
        <div class="search-overlay"></div>

      

        <!--  BEGIN CONTENT AREA  -->
       <div class="auth-container d-flex h-100">

        <div class="container ">
    
            <div class="row">
    
           
                    <div class="card mt-5 mb-3">
                       
                        <div class="card-body">
    
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                   
                                   <h2>Password Reset</h2>
                                  
                                 
                                    <div id="message"></div>
                                </div>
                                <div class="col-md-12" id="input-container">
                              <p>Enter your email to recover your account</p>
                                    <div class="mb-4">
                                        <label class="form-label">Email</label>
                                        <input type="email" id="email" class="form-control">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-4" id="btn-container">
                                        <button class="btn btn-secondary w-100" id="recovery" onclick="sendForgotPassword()">RECOVER</button>
                                    </div>
                                    <div class="mb-4" id="btn-go-back" style="display:none">
                                        <button class="btn btn-secondary w-100" onclick="goBack()">Go Back</button>
                                    </div>
                                </div>

                            </div>
                            
                        </div>
                    </div>
           
                
            </div>

        </div>
        <!--  END CONTENT AREA  -->
    </div>
    <!-- END MAIN CONTAINER -->
</div>
</div>

  <div class="col-md-6"><div class="form-right-img">
                        <figure>
                            <img src="assets/img/form-right-img-3.png" alt="">
                        </figure>
                    </div></div>



</div>
</div>
    
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <script src="../src/plugins/src/global/vendors.min.js"></script>
    <script src="../src/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../src/plugins/src/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="../src/plugins/src/mousetrap/mousetrap.min.js"></script>
    <script src="../layouts/vertical-light-menu/app.js"></script>
    <!-- END GLOBAL MANDATORY STYLES -->
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
            document.getElementById("input-container").style.display = 'block';
        }
        
        function displayMeessage(msg) {
            document.getElementById("message").innerHTML = `<strong><div class="message" id="message"><p>SUCCESS: </strong><br />${msg}</p></div>`;
            document.getElementById("input-container").style.display = 'none';
            document.getElementById("btn-container").style.display = 'none';
            document.getElementById("btn-go-back").style.display = 'block';
        }
        
        function goBack() {
            location.href = "/login.php";
        }

        var em = document.getElementById("email");

        if(em){
            em.addEventListener("keydown", function (e) {
                if (e.code === "Enter") {  //checks whether the pressed key is "Enter"
                    sendForgotPassword()
                }
            });
        }
            
        
        // call api for sending forgot password
        async function sendForgotPassword() {
            const email = document.getElementById("email").value;
            
            // check email is empty
            if( email == "") {
                displayErrorMeessage("The Email field is empty!");
                return;
            }
                
            const res = await fetch("forgot_password_proc.php?email="+email);
            const data = await res.json();
            console.log("data", data);
            
            if( data['code'] != 200 )
            {
                displayErrorMeessage(data["message"]);
                return;
            }
            
            displayMeessage(data["message"]);
            
            
                
        };
         // $( document ).ready(function() {
        // $('body').addClass('loaderNone')
          
       //});
        $(window).on('load', function () { 
           $('body').removeClass('loaderNone')
          $('#loading').remove();
          
        });
        
    </script>

    <!-- END PAGE LEVEL SCRIPTS -->
    <?php require_once 'home-footer.php';  ?>
</body>
</html>