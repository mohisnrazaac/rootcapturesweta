              <div class="footer section-gap-sm position-relative">
          <div
            class="position-absolute bottom-0 start-50 z-n1 translate-middle-x w-100"
          >
            <img src="frontend-assets/img/blur-bottom.png" alt="" class="w-100" />
          </div>
          <div class="container">
            <div class="row justify-content-center">
              <div class="col-lg-8 col-xl-6 col-xxl-4">
                <div
                  class="d-flex justify-content-center flex-column text-center"
                >
                <a href="mailto:contact@rootcapture.com" class="mb-4 d-inline-block"
                  >contact@rootcapture.com</a
                >
                  <div class="footer-image mb-1">
                    <a href="#">
                      <img src="frontend-assets/img/site-logo.svg" alt="" class="img-fluid" />
                    </a>
                  </div>
                  <!-- <p class="mt-5 mb-5">
                    Our Cyber Range can be customized to fit unique
                    organizational demands, wants, and necessities. through each
                    and every phase
                  </p> -->

                  <p class="mt-4 text-white">Copyright © <script>document.write(new Date().getFullYear())</script> rootCapture®</p>
                </div>
              </div>
            </div>
          </div>
        </div>
         <!-- ./ Footer -->

    <script src="frontend-assets/js/bootstrap.bundle.min.js"></script>
    <!-- <script src="frontend-assets/js/aos.js"></script> -->
    <script src="frontend-assets/js/script.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script>

    // $( document ).ready(function() {
    //       // $('body').addClass('loaderNone')
          
    //   });
        $(window).on('load', function () { 
           $('body').removeClass('loaderNone')
          $('#loading').remove();
          
        });

        function validate()
        {
          var name = $('#name').val(); 
          var email = $('#email').val();           
          var subject = $('#subject').val();
          var message = $('#message').val();          
          var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;

          if(name == '')
          {    
            $('#name').addClass('inputError');
            $('#name').attr('placeholder','Please enter your name.').focus(); return false;
          }
          else
          {
            $('#name').removeClass('inputError');
            $('#name').attr('placeholder','');
          }

           if(email == '')
          {
            $('#email').addClass('inputError');
            $('#email').val('').attr('placeholder','Please enter your email.').focus(); return false;
          }
          else if(!regex.test(email))
          {
            $('#email').addClass('inputError');
            $('#email').val('').attr('placeholder','Invalid email address.').focus(); return false;
          }
          else
          {
            $('#email').removeClass('inputError');
            $('#email').attr('placeholder','');
          }

          if(subject == '')
          {    
            $('#subject').addClass('inputError');
            $('#subject').attr('placeholder','Please enter subject here.').focus(); return false;
          }
          else
          {
            $('#subject').removeClass('inputError');
            $('#subject').attr('placeholder','');
          }

          if(message == '')
          { 
            $('#message').addClass('inputError');   
            $('#message').attr('placeholder','Please enter message here.').focus(); return false;
          }
          else
          {
            $('#message').removeClass('inputError');
            $('#message').attr('placeholder','');
          } 

          $('#submit-form').attr('disabled',true);

          $.ajax({
                url: "https://rootcapture.com/includes/ajax-nosession.php",
                type: "post",
                data: {
                    function_name: 'submit_contact_form',
                    name: name,
                    email: email,
                    subject: subject,
                    message: message
                },
                async: false,
                success: function(response) { 
                    res = JSON.parse(response);
                    if (res.status) {
                        // $('#ignismyModal').modal('show');
                        $('#contactus').html('');
                        $('.successMsg').css('display','block');

                    } else {
                        
                    }


                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                }
            });

          return false;
        }

        $(document).ready(function()
        {
          $('#post_comment').click(function()
          {
              var username = $('#username').val();
              var email = $('#email').val();
              var reply = $('#reply').val();
              var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
              var blogid = $(this).attr('data-id');

              if(username == '')
              {    
                $('#username').addClass('inputError');
                $('#username').attr('placeholder','Please enter your username.').focus(); return false;
              }
              else
              {
                $('#username').removeClass('inputError');
                $('#username').attr('placeholder','');
              }

              if(email == '')
              {
                $('#email').addClass('inputError');
                $('#email').val('').attr('placeholder','Please enter your email.').focus(); return false;
              }
              else if(!regex.test(email))
              {
                $('#email').addClass('inputError');
                $('#email').val('').attr('placeholder','Invalid email address.').focus(); return false;
              }
              else
              {
                $('#email').removeClass('inputError');
                $('#email').attr('placeholder','');
              }

              if(reply == '')
              { 
                $('#reply').addClass('inputError');   
                $('#reply').attr('placeholder','Please enter reply here.').focus(); return false;
              }
              else
              {
                $('#reply').removeClass('inputError');
                $('#reply').attr('placeholder','');
              } 

              $('#post_comment').attr('disabled',true);

              $.ajax({
                    url: "https://rootcapture.com/includes/ajax-nosession.php",
                    type: "post",
                    data: {
                        function_name: 'submit_blog_reply',
                        name: username,
                        email: email,
                        reply: reply,
                        blogid: blogid
                    },
                    async: false,
                    success: function(response) { 
                        res = JSON.parse(response);
                        if (res.status) {
                            // $('#ignismyModal').modal('show');
                            $('#post_comment').text('Reply has been sent!');

                        } else {
                            
                        }


                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(textStatus, errorThrown);
                    }
                });

              return false;

          });

          $('#searchhere').keyup( function(){  
             if( $(this).val().length >= 3 )
             {
                var length = $(this).val().length; 
                var term = $(this).val();
                $( ".accordion-button" ).each(function(index) {
                    var text = $(this).html(); 
                    // console.log(term.toLowerCase()+'-------------'+text.substring(0,length+1).toLowerCase()); return false;
                    if( term.toLowerCase() == text.substring(0,length+1).toLowerCase().trim()  )
                    {
                      $(this).parent('h2').css('background-color','#87c409');
                      // $(this).parent().css('background-color','yellow');
                    }
                    else
                    {
                      // console.log($(this).parent('h2').text());
                      // console.log($(this));
                      $(this).parent('h2').css('background-color','');
                    }
                });
              }
              else
              {
                $( ".accordion-button" ).each(function(index) {
                      $(this).parent('h2').css('background-color','');
                });
              }
          } );
        })

              

        // $('#modalClose').click(function(){
        //   $('#ignismyModal').modal('hide');
        // })

        
    </script>
  
