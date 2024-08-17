

<?php
$getUserDetailIdWise = $user->getUserDetailIdWise($odb); 
$college_id = $getUserDetailIdWise['college_id']; 
$userSessionId = $_SESSION['ID'];
$teamId = $getUserDetailIdWise['rank'];

$color_code = '';
$isUser = 1;
if( $loggedUserTeam == 'Admin' || $loggedUserTeam == 'Administrative Assistant' ){ 
    $color_code_darker = ''; $color_code = '';
    $isUser = 0;
}else if( $loggedUserTeam == 'Red Team' ) 
{ 
    $color_code_darker = $loginInfo['color_code'];
    $color_code = '#ed3b3b';
}
else if( $team_name == 'Blue Team' ) { $color_code_darker = '#092cc1';$color_code = $loginInfo['color_code'];}
else if( $team_name == 'Purple Team' ){ $color_code_darker = '#8e0be1';$color_code = $loginInfo['color_code'];}
else{ $color_code_darker = $user->darken_color($loginInfo['color_code']); $color_code = $loginInfo['color_code'];}
?>
<?php
$currentPage = basename($_SERVER['PHP_SELF']);
$quizListQuery = $odb->query("SELECT * FROM quize where quize.id NOT IN (SELECT quize_id from quiz_submission where quiz_submission.created_by = $userSessionId)  AND  status = 2 AND (FIND_IN_SET($userSessionId,assign_users) OR assign_team = $teamId ) AND is_mandatory = 'yes'");
$quizListQuery->execute();
$quizListData =  $quizListQuery->fetchAll(PDO::FETCH_ASSOC);

if($isUser ==1 && $currentPage != 'quiz_list.php' && $currentPage != 'quiz.php' && !empty($quizListData)){
    ?>

    <div class="modal" tabindex="-1" role="dialog" id="myModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Pending Quiz</h5>
        </div>
        <div class="modal-body">
            <p>Play mandatory quiz !!!</p>
        </div>
        <div class="modal-footer">
            <a href="quiz_list.php" class="btn btn-primary">Quiz List</a>
            <button type="button" id="closeQuiz" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
        </div>
    </div>
    </div>

<?php
}
?>

  <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
  <script src="<?=BASEURL?>src/plugins/src/global/vendors.min.js"></script>
    <script src="<?=BASEURL?>src/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?=BASEURL?>src/plugins/src/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="<?=BASEURL?>src/plugins/src/mousetrap/mousetrap.min.js"></script>
    <script src="<?=BASEURL?>layouts/vertical-dark-menu/app.js"></script>
    <script src="<?=BASEURL?>src/assets/js/custom.js"></script>
    <script src="<?=BASEURL?>src/plugins/src/vanillaSelectBox/vanillaSelectBox.js"></script>
    <script src="<?=BASEURL?>src/plugins/src/vanillaSelectBox/custom-vanillaSelectBox.js"></script>
    <script src="<?=BASEURL?>src/plugins/src/sweetalerts2/sweetalerts2.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-migrate-1.4.1.min.js"></script>
    <script src="<?=BASEURL?>src/plugins/src/apex/apexcharts.min.js"></script>
    <script src="<?=BASEURL?>layouts/vertical-dark-menu/jquery-jvectormap.min.js"></script> 
    <script src="<?=BASEURL?>layouts/vertical-dark-menu/world-mill.js"></script>
    <script src="<?=BASEURL?>src/plugins/src/filepond/filepond.min.js"></script>
    <script src="<?=BASEURL?>src/plugins/src/filepond/FilePondPluginFileValidateType.min.js"></script>
    <script src="<?=BASEURL?>src/plugins/src/filepond/FilePondPluginImageExifOrientation.min.js"></script>
    <script src="<?=BASEURL?>src/plugins/src/filepond/FilePondPluginImagePreview.min.js"></script>
    <script src="<?=BASEURL?>src/plugins/src/filepond/FilePondPluginImageCrop.min.js"></script>
    <script src="<?=BASEURL?>src/plugins/src/filepond/FilePondPluginImageResize.min.js"></script>
    <script src="<?=BASEURL?>src/plugins/src/filepond/FilePondPluginImageTransform.min.js"></script>
    <script src="<?=BASEURL?>src/plugins/src/filepond/filepondPluginFileValidateSize.min.js"></script>
    <script src="<?=BASEURL?>src/plugins/src/notification/snackbar/snackbar.min.js"></script>
    <script src="<?=BASEURL?>src/assets/js/users/account-settings.js"></script>
    <script src="<?=BASEURL?>src/plugins/src/editors/quill/quill.js"></script> 
    <script src="<?=BASEURL?>src/plugins/src/tagify/tagify.min.js"></script>
    <script src="<?=BASEURL?>src/assets/js/apps/blog-create.js"></script>
    <!-- END GLOBAL MANDATORY SCRIPTS -->

    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="<?=BASEURL?>src/plugins/src/table/datatable/datatables.js"></script>
    <script type="text/javascript">
        $(window).on('load', function() {
            $('#myModal').modal('show');
        });
        $(document).on('click','#closeQuiz', function() {
            $('#myModal').modal('hide');
        });
    </script>
    <script>
        

        var college_id = "<?php echo $college_id; ?>";

        // $(window).on('load', function () { 
        // //    $('body').removeClass('loaderNone')
        // //   $('#loading').remove();
        // setTimeout(function(){
        //     $('body,html').removeClass('loaderNone')
        //     $('#load_screen').remove();
        // },2000);
        // });
       
         var link = document.querySelector("link[rel~='icon']");
            if (!link) {
                link = document.createElement('link');
                link.rel = 'icon';
                document.getElementsByTagName('head')[0].appendChild(link);
            }

         var teamBgColor = '<?php echo $color_code; ?>';
         var color_code_darker = '<?php echo $color_code_darker; ?>';
         
         var ip_address = '<?php echo $_SERVER['REMOTE_ADDR']; ?>';

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
         function changeTheme()
            {   
                if( $('body').hasClass('dark') )
                {
                    $('body').css({
                        'background':'',
                        'background-image':''
                    });

                    $('.footer-wrapper').css({
                        'background':''                       
                    });
                     
                    if(teamBgColor != ''){  
                        $('.footer-wrapper p,.footer-wrapper a').css({
                        'color':''                       
                        }); 
                    }

                    link.href = '<?=BASEURL?>src/assets/img/favicon-dark.ico';
                    $('.navbar-logo').attr('src', '<?=BASEURL?>assets/img/rootcapture-whitelogo.png');
                }
                else
                {   
                    // $('body').addClass(teamBgColor);
                    $('body').css({
                        'background':teamBgColor,
                        'background-image':'linear-gradient(263deg, #ffffff  0%, '+teamBgColor+' 76%'
                    });
                    $('.footer-wrapper').css({
                        'background':color_code_darker                       
                    });

                    if(teamBgColor != ''){  
                        $('.footer-wrapper p,.footer-wrapper a').css({
                        'color':'#fff'                       
                        }); 
                    }
                    

                    link.href = '<?=BASEURL?>src/assets/img/favicon.ico';
                    $('.navbar-logo').attr('src', '<?=BASEURL?>assets/img/RootCapture0.png');
                }
            }

            $(window).on('load', function() {  
                if( $('body').hasClass('dark') )
                {  
                    $('body').css({
                        'background':'',
                        'background-image':''
                    });
                    $('.footer-wrapper').css({
                        'background':''                       
                    });
                     
                    if(teamBgColor != ''){  
                        $('.footer-wrapper p,.footer-wrapper a').css({
                        'color':''                       
                        }); 
                    }

                    link.href = '<?=BASEURL?>src/assets/img/favicon-dark.ico';
                    $('.navbar-logo').attr('src', '<?=BASEURL?>frontend-assets/img/rootcapture-whitelogo.png');
                }
                else
                {  
                    $('body').css({
                        'background':teamBgColor,
                        'background-image':'linear-gradient(263deg, #ffffff  0%, '+teamBgColor+' 76%'
                    });
                    $('.footer-wrapper').css({
                        'background':color_code_darker                       
                    });
                    if(teamBgColor != ''){  
                        $('.footer-wrapper p,.footer-wrapper a').css({
                        'color':'#fff'                       
                        }); 
                    }

                    link.href = '<?=BASEURL?>src/assets/img/favicon.ico';
                    // document.querySelector('.navbar-logo').src = '<?=BASEURL?>frontend-assets/img/rootcapture-whitelogo.png';
                    $('.navbar-logo').attr('src', '<?=BASEURL?>assets/img/RootCapture0.png');
                
                }
            })

        function maintainSession()
        {
            $.ajax({
                url: "<?=BASEURL?>includes/ajax.php",
                type: "post",
                data: {
                    function_name: 'maintain_active_inactive_session',
                    college_id: college_id,
                    ip_address: ip_address
                },
                async: false,
                success: function(response) { console.log(response);
                    // res = JSON.parse(response);
                    // if (res.status) {
                        
                    // } else {
                        
                    // }


                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                }
            });

             setTimeout(function() {
                maintainSession();
            }, 60 * 5000);
        }
        $(document).ready(function(){
            // setTimeout(function() {
                maintainSession();
            // }, 60 * 1000);
        })


      
    </script>
   
       <!--<script>
$(document).ready(function(){
  $("#btn-group-selectCust .caret").click(function(){
    $(".vsb-menu.show").slideToggle(600);
  });
});
</script>-->
    <!-- END PAGE LEVEL SCRIPTS -->



    