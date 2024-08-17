<?php
   
    ob_start();
    require_once 'includes/db.php';
    require_once 'includes/init.php';
    $loggedUserId    = $_SESSION['ID'];
    $loginInfo = $user->userInfo($odb,$loggedUserId); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>rootCapture - Error</title>
    <link rel="icon" type="image/x-icon" href="../src/assets/img/favicon.ico"/>
    <link href="https://rootcapture.com/layouts/vertical-dark-menu/css/light/loader.css" rel="stylesheet" type="text/css" />
    <link href="https://rootcapture.com/layouts/vertical-dark-menu/css/dark/loader.css" rel="stylesheet" type="text/css" />
    <script src="https://rootcapture.com/layouts/vertical-dark-menu/loader.js"></script>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="https://rootcapture.com/src/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

    <link href="https://rootcapture.com/layouts/vertical-dark-menu/css/light/plugins.css" rel="stylesheet" type="text/css" />
    <link href="https://rootcapture.com/src/assets/css/light/pages/error/error.css" rel="stylesheet" type="text/css" />

    <link href="https://rootcapture.com/layouts/vertical-dark-menu/css/dark/plugins.css" rel="stylesheet" type="text/css" />
    <link href="https://rootcapture.com/src/assets/css/dark/pages/error/error.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->

    <style>
              body.loaderNone {
    overflow: hidden;
}
            .loader {
          position: fixed;
          display: block;
          width: 100%;
          height: 100%;
          overflow: hidden;
          margin: auto;
          top: 0;
          left: 0;
          bottom: 0;
          right: 0;
          text-align: center;
          opacity: 1;
          background-color: #1c1b40;
          z-index: 9999;
      }
   .dark_preloader {
    position: absolute;
    top: 11%;
    left: 30%;
    z-index: 100;
    width: 40%;
    max-height: inherit;
}

.spinner-grow-new.align-self-center {
    width: 100%;
    min-height: inherit;
    height: 100%;
}
        body.dark .theme-logo.dark-element {
            display: inline-block;
        }
        .theme-logo.dark-element {
            display: none;
        }
        body.dark .theme-logo.light-element {
            display: none;
        }
        .theme-logo.light-element {
            display: inline-block;
        }
        .outerPagesHeader {
    position: relative;
}

.outerPagesHeader .nav-item {
    position: absolute;
    right: 15px;
    top: 15px;
}
body.dark.error .darkModeLogo {
    display: block;
}
body.dark.error .lightModeLogo {
    display: none;
}

body.error .lightModeLogo {
    display: block;
}
body.error .darkModeLogo {
    display: none;
}

body.dark.error .error-img,.error .error-img{ width: 100%; }
    </style>
    
</head>
<body class="error text-center loaderNone">

    <!-- BEGIN LOADER -->
     <div id="load_screen"> 
        <div class="loader"> 
            <div class="loader-content">
                <div class="spinner-grow-new align-self-center">
                    <img  src="https://rootcapture.com/assets/img/Loading-Animation-dark.gif" class="dark_preloader ">
                 
                </div>
            </div>
        </div>
    </div>
    <!--  END LOADER -->

     <!--  BEGIN NAVBAR  -->
    <div class="outerPagesHeader">
        <div class="header navbar navbar-expand-sm expand-header">

      
         

            <ul class="navbar-item flex-row ms-lg-auto ms-0">

           

                <li class="nav-item theme-toggle-item">
                    <a href="javascript:void(0);" class="nav-link theme-toggle">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-moon dark-mode"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-sun light-mode"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
                    </a>
                </li>

            
            </ul>
        </div>
    </div>
    <!--  END NAVBAR  -->
    
    <div class="container-fluid" id="container">
        <div class="overlay"></div>
        <div class="search-overlay"></div>
        <div class="row">
            <div class="col-md-4 mr-auto mt-5 text-md-left text-center">
                </a>
            </div>
        </div>
    </div>
    <div class="container-fluid error-content">
           <div class="">
            <p class="mini-text">Hello!</p>
            <p class="error-text mb-5 mt-1">rootCapture is currently going maintenance. <br /> Please try again later!</p>
            <img src="https://rootcapture.com/frontend-assets/img/site-logo.svg" class="error-img darkModeLogo">
<img src="../assets/img/RootCapture0.png" class="error-img lightModeLogo">
            <div class="btn-toolbar">
            <a href="#" onclick="javascript:history.go(-1)" class="btn btn-dark mt-5">Go Back</a>
            <a href="#" onClick="window.location.reload();return false;" class="btn btn-dark mt-5">Try Again</a>
            <a href="../index.php" class="btn btn-dark mt-5">Go Home</a>
        </div>
        </div>
    </div>    
    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
     <script src="../src/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../src/plugins/src/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="../src/plugins/src/mousetrap/mousetrap.min.js"></script>
    <script src="../layouts/vertical-light-menu/app.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <!-- END GLOBAL MANDATORY SCRIPTS -->
    <script type="text/javascript">
        var color_code = '<?=$loginInfo['color_code']?>';
            $('body').click(function(){
              
                if( $('body').hasClass('dark') )
                {
                    $('body').css({
                        'background':'',
                        'background-image':''
                    });
                }
                else
                {   
                    
                    $('body').css({
                        'background': color_code,
                        'background-image':'linear-gradient(360deg, #ffffff  0%, '+color_code+' 76%'
                    });
                }
           
            })
            

            $(window).on('load', function() {  
                if( $('body').hasClass('dark') )
                {  
                    $('body').css({
                        'background':'',
                        'background-image':''
                    });  
                }
                else
                {  
                    $('body').css({
                        'background':color_code,
                        'background-image':'linear-gradient(360deg, #ffffff  0%, '+color_code+' 76%'
                    });
                }
            })

        // $( document ).ready(function() {
        // $('body').addClass('loaderNone')
          
       //});
        $(window).on('load', function () { 
           $('body').removeClass('loaderNone')
          $('#loading').remove();
          
        });
    </script>
</body>
</html>