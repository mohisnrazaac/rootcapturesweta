function Login()
{
  let username = $('#username').val();
  let password_input = $('#password-input').val();
  let actualPassword = $('#actualPassword').val();
    if( username === '' )
    {
      $('#username').focus();
      $('#username').attr('placeholder','Please enter username or email');
    }
    else
    {    
      if( password_input === '' )
      {
      $('#password-input').focus();
      $('#password-input').attr('placeholder','Please enter password');
      }
      else
      {
        document.getElementById('login_screen-3').style.display = 'block';
        setTimeout(function() {
          $.ajax({
            url: "",
            type: "post",
            dataType : 'json',
            data: {
              username: username,
              password: actualPassword
            },
            async: false,
            success: function(response) { 
              var txt = response.message;
              // typewriter function start
              if (typeof typeWriter !== 'function') {
                var j = 0;
                var speed = 50;
                function typeWriter() {
                  if (j < txt.length) {
                    // Check if the current character is '<', then add the following characters until '>'
                    if (txt.charAt(j) === '<') {
                      var tagEndIndex = txt.indexOf('>', j);
                      document.getElementById("demo").innerHTML += txt.substring(j, tagEndIndex + 1);
                      j = tagEndIndex + 1;
                    } else {
                      document.getElementById("demo").innerHTML += txt.charAt(j);
                    j++;
                    }
                    setTimeout(typeWriter, speed);
                    if(j === txt.length){
                      setTimeout(function() {
                        window.location.href = "errordocs.php";
                    }, 1000);
                    }
                  }
                }
              }
              // typewriter function ends
                if (response.status) {
                  // if response true 
                  document.getElementById('login_screen-3').style.display = 'none';
                  document.getElementById('login_screen-5').style.display = 'block';
                  setTimeout(function() {
                    window.location.replace("verification.php");
                }, 2100);

                } else {
                   // if response false 
                  document.getElementById('login_screen-3').style.display = 'none';
                  document.getElementById('login_screen-4').style.display = 'block';
                  $('#demo').html('');
                  setTimeout(function () { 
                    typeWriter();
                  }, 2000); 
                  const video = document.getElementById('myVideo3');
                  video.autoplay = true;
                  video.muted = true;
              
                  video.addEventListener("ended", handleVideoEnded);
              
                  function handleVideoEnded() {
                      video.pause();
                      video.currentTime = 5;
                      video.play();
                  }
                  document.querySelector('.login_error_h').style.display = 'block';
                    
                }


            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
            }
          });
        }, 3100);
      }

    }
}

$("#forgot_password").click(function(){
  let email = $('#email').val();
  var validRegex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
  if( email === '' )
  {
    $('#email').focus();
    $('#email').attr('placeholder','Please enter email');
  }
  else if(!email.toLowerCase().match(
    /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|.(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
  ))
  {
    $('#email').val('');
    $('#email').focus();
    $('#email').attr('placeholder','Please enter valid email address.');
  }
  else
  {
    
    document.getElementById('forgot_password1').style.display = 'none';
    document.getElementById('authenticate_video').style.display = 'block';
    setTimeout(function() {
      $.ajax({
        url: "",
        type: "post",
        dataType : 'json',
        data: {
          email: email
        },
        async: false,
        success: function(response) {
          var txt = response.message;
          // typewriter function start
          if (typeof typeWriter !== 'function') {
            var j = 0;
            var speed = 50;
            function typeWriter() {
              if (j < txt.length) {
                // Check if the current character is '<', then add the following characters until '>'
                if (txt.charAt(j) === '<') {
                  var tagEndIndex = txt.indexOf('>', j);
                  document.getElementById("demo").innerHTML += txt.substring(j, tagEndIndex + 1);
                  j = tagEndIndex + 1;
                } else {
                  document.getElementById("demo").innerHTML += txt.charAt(j);
                j++;
                }
                setTimeout(typeWriter, speed);
                if(j === txt.length){
                  setTimeout(function() {
                    $('#error_video').css('display','none');
                    $('#forgot_password1').css('display','block');
                }, 1000);
                  
                }
              }
            }
          }
          // typewriter function ends
            if (response.status)
            {
              // if response true 
              document.getElementById('authenticate_video').style.display = 'none';
              document.getElementById('success_video').style.display = 'block';
              setTimeout(function() {
                window.location.replace("forgot-password-verify.php?email="+response.email);
            }, 2100);

            }
            else
            {
               // if response false 
              document.getElementById('authenticate_video').style.display = 'none';
              document.getElementById('error_video').style.display = 'block';
              $('#demo').html('');
              setTimeout(function () { 
                typeWriter();
              }, 2000); 
              const video = document.getElementById('myVideo3');
              video.autoplay = true;
              video.muted = true;
          
              video.addEventListener("ended", handleVideoEnded);
          
              function handleVideoEnded() {
                  video.pause();
                  video.currentTime = 5;
                  video.play();
              }
              document.querySelector('.login_error_h').style.display = 'block';
                
            }


        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
        }
      });
    }, 3100);
  }
});

$('#forgot-verify').click(function(){
  const v1 = $('#v1').val();
  const v2 = $('#v2').val();
  const v3 = $('#v3').val();
  const v4 = $('#v4').val();
  const v5 = $('#v5').val();
  const v6 = $('#v6').val();

  if( v1 === '' )
  {
    $('#v1').focus();
  }
  else
  {
    if( v2 === '' )
    {
      $('#v2').focus();
    }
    else
    {
      if( v3 === '' )
      {
        $('#v3').focus();
      }
      else
      {
        if( v4 === '' )
        {
          $('#v4').focus();
        }
        else
        {
          if( v5 === '' )
          {
            $('#v5').focus();
          }
          else
          {
            if( v6 === '' )
            {
              $('#v6').focus();
            }
            else
            {
              document.getElementById('forgot_password_verify').style.display = 'none';
              document.getElementById('authenticate_video').style.display = 'block';
             
              setTimeout(function() {
                $.ajax({
                  url: "",
                  type: "post",
                  dataType : 'json',
                  data: {
                    v1: v1,
                    v2: v2,
                    v3: v3,
                    v4: v4,
                    v5: v5,
                    v6: v6,
                  },
                  async: false,
                  success: function(response) {
                    var txt = response.message;
                    // typewriter function start
                    if (typeof typeWriter !== 'function') {
                      var j = 0;
                      var speed = 50;
                      function typeWriter() {
                        if (j < txt.length) {
                          // Check if the current character is '<', then add the following characters until '>'
                          if (txt.charAt(j) === '<') {
                            var tagEndIndex = txt.indexOf('>', j);
                            document.getElementById("demo").innerHTML += txt.substring(j, tagEndIndex + 1);
                            j = tagEndIndex + 1;
                          } else {
                            document.getElementById("demo").innerHTML += txt.charAt(j);
                          j++;
                          }
                          setTimeout(typeWriter, speed);
                          if(j === txt.length){
                            setTimeout(function() {
                              $('#error_video').css('display','none');
                                $('#v1').val('');
                                $('#v2').val('');
                                $('#v3').val('');
                                $('#v4').val('');
                                $('#v5').val('');
                                $('#v6').val('');
                              $('#forgot_password_verify').css('display','block');
                          }, 1000);
                          }
                        }
                      }
                    }
                    // typewriter function ends
                      if (response.status)
                      {
                        // if response true 
                        document.getElementById('authenticate_video').style.display = 'none';
                        document.getElementById('success_video').style.display = 'block';
                        setTimeout(function() {
                          document.getElementById('success_video').style.display = 'none';
                          $('#v1').val('');
                          $('#v2').val('');
                          $('#v3').val('');
                          $('#v4').val('');
                          $('#v5').val('');
                          $('#v6').val('');
                          $('#mesShow').html('');
                          $('#mesShow').html('<span style="color: #04fefe;">'+response.message+'</span>');
                          document.getElementById('forgot_password_verify').style.display = 'block';
                      }, 2100);
          
                      }
                      else
                      {
                         // if response false 
                        document.getElementById('authenticate_video').style.display = 'none';
                        document.getElementById('error_video').style.display = 'block';
                        $('#demo').html('');
                        setTimeout(function () { 
                          typeWriter();
                        }, 2000); 
                        const video = document.getElementById('myVideo3');
                        video.autoplay = true;
                        video.muted = true;
                    
                        video.addEventListener("ended", handleVideoEnded);
                    
                        function handleVideoEnded() {
                            video.pause();
                            video.currentTime = 5;
                            video.play();
                        }
                        document.querySelector('.login_error_h').style.display = 'block';
                          
                      }
          
          
                  },
                  error: function(jqXHR, textStatus, errorThrown) {
                      console.log(textStatus, errorThrown);
                  }
                });
              }, 2100);
            }
          }
        }
      }
    }
  }

});

$('#set_new_pass_btn').click(function(){
  let password_input = $('#password-input').val();
  let password_input2 = $('#password-input2').val();

  if( password_input == '' )
  {
    $('#password-input').focus();
    $('#password-input').attr('placeholder','Please enter password');
  }
  else
  {
    if( password_input2 === '' )
    {
      $('#password-input2').focus();
      $('#password-input2').attr('placeholder','Please enter confirm password');
    }
    else
    {
      if( password_input2 !== password_input )
      {
        $('#password-input2').val('');
        $('#password-input2').focus();
        $('#password-input2').attr('placeholder','Confirm password does not match.');
      }
      else
      {
        document.getElementById('resetpassword').style.display = 'none';
        document.getElementById('authenticate_video').style.display = 'block';
        setTimeout(function() {
          $.ajax({
            url: "",
            type: "post",
            dataType : 'json',
            data: {
              password_input: password_input,
              password_input2: password_input2
            },
            async: false,
            success: function(response) {
              var txt = response.message;
              // typewriter function start
              if (typeof typeWriter !== 'function') {
                var j = 0;
                var speed = 50;
                function typeWriter() {
                  if (j < txt.length) {
                    // Check if the current character is '<', then add the following characters until '>'
                    if (txt.charAt(j) === '<') {
                      var tagEndIndex = txt.indexOf('>', j);
                      document.getElementById("demo").innerHTML += txt.substring(j, tagEndIndex + 1);
                      j = tagEndIndex + 1;
                    } else {
                      document.getElementById("demo").innerHTML += txt.charAt(j);
                    j++;
                    }
                    setTimeout(typeWriter, speed);
                    if(j === txt.length){
                      setTimeout(function() {
                        $('#error_video').css('display','none');
                        $('#resetpassword').css('display','block');
                    }, 1000);
                    }
                  }
                }
              }
              // typewriter function ends
                if (response.status)
                {
                  // if response true 
                  document.getElementById('authenticate_video').style.display = 'none';
                  document.getElementById('success_video').style.display = 'block';
                  setTimeout(function() {
                    window.location.replace("login.php");
                }, 2100);
    
                }
                else
                {
                   // if response false 
                  document.getElementById('authenticate_video').style.display = 'none';
                  document.getElementById('error_video').style.display = 'block';
                  $('#demo').html('');
                  setTimeout(function () { 
                    typeWriter();
                  }, 2000); 
                  const video = document.getElementById('myVideo3');
                  video.autoplay = true;
                  video.muted = true;
              
                  video.addEventListener("ended", handleVideoEnded);
              
                  function handleVideoEnded() {
                      video.pause();
                      video.currentTime = 5;
                      video.play();
                  }
                  document.querySelector('.login_error_h').style.display = 'block';
                    
                }
    
    
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
            }
          });
        }, 3100);
      }
    }
  }
});

$("#resend_otp_phone").click( async function(){
  let phoneNumber = $('#phoneNumber').val();
  if( phoneNumber !== '' || phoneNumber != '' )
  {
      var form = new FormData();
      form.append('function_name', 'resend_veri_mobile_reg');
      form.append('phoneNumber', phoneNumber);
      
      const res = await fetch("https://rootcapture.com/includes/ajax-nosession.php", {
          body: form,
          method: 'POST'
      });
      const data = await res.json();
      if(data.status)
      {
          // Start the timer
          seconds = 60;
          var intervalId = setInterval(updateTimer, 1000);
      }
      else
      {
          alert("Something went wrong"); return false;
      }
  }
});

$("#resend_otp_email").click( async function(){
  let email = $('#email').val();
  if( email !== '' || email != '' )
  {
      var form = new FormData();
      form.append('function_name', 'resend_veri_email_reg');
      form.append('email', email);
      
      const res = await fetch("https://rootcapture.com/includes/ajax-nosession.php", {
          body: form,
          method: 'POST'
      });
      const data = await res.json();
      if(data.status)
      {
          // Start the timer
          seconds = 60;
          var intervalId = setInterval(updateTimer, 1000);
      }
      else
      {
          alert("Something went wrong"); return false;
      }
  }

});

$("#reg_verification").click(async function(){
  const v1 = $('#v1').val();
  const v2 = $('#v2').val();
  const v3 = $('#v3').val();
  const v4 = $('#v4').val();
  const v5 = $('#v5').val();
  const v6 = $('#v6').val();
  const email = $('#email').val();
  const phoneNumber = $('#phoneNumber').val();

  if( v1 === '' )
  {
    $('#v1').focus();
  }
  else
  {
    if( v2 === '' )
    {
      $('#v2').focus();
    }
    else
    {
      if( v3 === '' )
      {
        $('#v3').focus();
      }
      else
      {
        if( v4 === '' )
        {
          $('#v4').focus();
        }
        else
        {
          if( v5 === '' )
          {
            $('#v5').focus();
          }
          else
          {
            if( v6 === '' )
            {
              $('#v6').focus();
            }
            else
            {
              var form = new FormData();
              form.append('function_name', 'otp_vry_register');
              form.append('v1', v1);
              form.append('v2', v2);
              form.append('v3', v3);
              form.append('v4', v4);
              form.append('v5', v5);
              form.append('v6', v6);
              form.append('email', email);
              form.append('phoneNumber', phoneNumber);
              
              const res = await fetch("https://rootcapture.com/includes/ajax-nosession.php", {
                  body: form,
                  method: 'POST'
              });
              const data = await res.json();


              if(data.status)
              {
                otp_verified = true;
                $('#otpverimsg').text(data.message);
                setTimeout(function() {
                  closeTextPop();
                }, 1000);
              }
              else
              {
                $('#otpverimsg').text(data.message);
              }
            }
          }
        }
      }
    }
  }
});

$("#register_student").click(async function(){

  let username = $('#username').val();
  let email = $('#email').val();
  let password = $('#password').val();
  let cnfPassword = $('#cnfPassword').val();
  let phoneNumber = $('#phoneNumber').val();
  let code = $('#code').val();
  var twoFaOption = document.getElementsByName('2faOption');
  var twoFaValid = false;
  var fa_preference = '';

  if( username === '' )
  {
    $('#username').focus();
    $('#username').attr('placeholder','Please enter username.');
  }
  else
  {
    if( password === '' )
    {
      $('#password').focus();
      $('#password').attr('placeholder','Please enter password.');
    }
    else
    {
        if( cnfPassword === '' )
        {
          $('#cnfPassword').focus();
          $('#cnfPassword').attr('placeholder','Please enter confirm password.');
        }
        else
        {
            if( password !== cnfPassword )
            {
              $('#cnfPassword').val('');
              $('#cnfPassword').focus();
              $('#cnfPassword').attr('placeholder','Confirm password does not match.');
            }
            else
            {
              
              for (var i = 0; i < twoFaOption.length; i++) {
                if (twoFaOption[i].checked) {
                  if(twoFaOption[i].value == 1)
                  {
                    fa_preference = 1;
                    if( phoneNumber === '' )
                    {
                      $('#phoneNumber').focus();
                      $('#phoneNumber').attr('placeholder','Please enter phone number.');
                      return false;
                    }
                  }
                  else if (twoFaOption[i].value == 2)
                  {
                    fa_preference = 2;
                    if( email === '' )
                    {
                      $('#email').focus();
                      $('#email').attr('placeholder','Please enter email address');
                      return false;
                    }
                    else if(!email.toLowerCase().match(
                      /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|.(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
                    ))
                    {
                      $('#email').val('');
                      $('#email').focus();
                      $('#email').attr('placeholder','Please enter valid email address.');
                      return false;
                    }
                  }
                  else
                  {
                    alert("Not determined");
                  }

                  twoFaValid = true;
                  break; // Exit loop if a radio button is checked
                }
              }

              if (!twoFaValid) {
                $('#2faValidation').text('Required');
                return false;
              }
              else
              {
                if(!otp_verified)
                {
                  $('#2faValidation').text('Please verify');
                  return false;
                }
                else
                {
                  if( code === '' )
                  {
                    $('#code').focus();
                    $('#code').attr('placeholder','Please enter code.');
                    return false;
                  }
                  else
                  {
                    document.getElementById('register_student_v').style.display = 'none';
                    document.getElementById('authenticate_video').style.display = 'block';
          
                      var form = new FormData();
                      form.append('username', username);
                      form.append('email', email);
                      form.append('password', password);
                      form.append('cnfPassword', cnfPassword);
                      form.append('phoneNumber', phoneNumber);
                      form.append('code', code);
                      form.append('fa_preference', fa_preference);
                      
                      setTimeout(async function() {
                        const res = await fetch("https://rootcapture.com/register.php", {
                            body: form,
                            method: 'POST',
                            headers: {
                              'X-Requested-With': 'XMLHttpRequest'
                            },
                        });
                      const data = await res.json();
                      if(!data.status)
                      {
                        var txt = data.message;
                        // typewriter function start
                        if (typeof typeWriter !== 'function') {
                          var j = 0;
                          var speed = 50;
                          function typeWriter() {
                            if (j < txt.length) {
                              // Check if the current character is '<', then add the following characters until '>'
                              if (txt.charAt(j) === '<') {
                                var tagEndIndex = txt.indexOf('>', j);
                                document.getElementById("demo").innerHTML += txt.substring(j, tagEndIndex + 1);
                                j = tagEndIndex + 1;
                              } else {
                                document.getElementById("demo").innerHTML += txt.charAt(j);
                              j++;
                              }
                              setTimeout(typeWriter, speed);
                              if(j === txt.length){
                                setTimeout(function() {
                                  window.location.href = "errordocs.php";
                              }, 1000);
                              }
                            }
                          }
                        }
  
                        document.getElementById('authenticate_video').style.display = 'none';
                        document.getElementById('error_video').style.display = 'block';
                        $('#demo').html('');
                        setTimeout(function () { 
                          typeWriter(data.message);
                        }, 2000); 
                        const video = document.getElementById('myVideo3');
                        video.autoplay = true;
                        video.muted = true;
                    
                        video.addEventListener("ended", handleVideoEnded);
                    
                        function handleVideoEnded() {
                            video.pause();
                            video.currentTime = 5;
                            video.play();
                        }
                        document.querySelector('.login_error_h').style.display = 'block';
                      }
                      else
                      {
                        document.getElementById('authenticate_video').style.display = 'none';
                        document.getElementById('success_video').style.display = 'block';
                        setTimeout(function() {
                          window.location.replace("verification.php");
                      }, 2100);
                      }
                    }, 3100);
                  }
                }
              }
             
            }
        }
      
    }
  }
})

$('#send_auth_code').click( async function(){
    var form = new FormData();
    form.append('function_name', 'send_auth_code_verification');
    
    const res = await fetch("https://rootcapture.com/includes/ajax-nosession.php", {
        body: form,
        method: 'POST'
    });
    const data = await res.json();
    if(data.status)
    {
           var imageButton = document.querySelector(".auth-image-button");
          
            intervalId = setInterval(updateTimer, 1000);
          
            imageButton.style.display = "none";
          
             verifyDiv.style.display = "block";
    }
    else
    {
      window.location.href = "errordocs.php";
     
      // $('#code-expired-text').text(data.message);
      // let verifyDiv = document.getElementById("verifyDiv");
      // let expiredBanner = document.getElementById("expired-banner");
      // verifyDiv.style.display = 'none';
      // expiredBanner.style.display = 'block';
      // setTimeout(function() {
      //   window.location.href = "errordocs.php";
      // }, 1000);
      
    }
} );

$('#resend_auth_code').click( async function(){
  var form = new FormData();
  form.append('function_name', 'send_auth_code_verification');
  
  const res = await fetch("https://rootcapture.com/includes/ajax-nosession.php", {
      body: form,
      method: 'POST'
  });
  const data = await res.json();
  if(data.status)
  {
     toggleButtonAndShowDivResendCode();
  }
  else
  {
    window.location.href = "errordocs.php";
   
    // $('#code-expired-text').text(data.message);
    // let verifyDiv = document.getElementById("verifyDiv");
    // let expiredBanner = document.getElementById("expired-banner");
    // verifyDiv.style.display = 'none';
    // expiredBanner.style.display = 'block';
    // setTimeout(function() {
    //   window.location.href = "errordocs.php";
    // }, 1000);
    
  }
} );

$("#resend_otp_email_verification").click( async function(){
    var form = new FormData();
    form.append('function_name', 'resend_otp_email_verification');
    
    const res = await fetch("https://rootcapture.com/includes/ajax-nosession.php", {
        body: form,
        method: 'POST'
    });
    const data = await res.json();
    if(data.status)
    {
      $('#succ_message').text('Otp Sent');
      seconds = 60;
      updateTimer()
    }
    else
    {
      window.location.href = "errordocs.php";
    }
});   

$("#resend_otp_phone_verification").click( async function(){
  var form = new FormData();
  form.append('function_name', 'resend_otp_phone_verification');
  
  const res = await fetch("https://rootcapture.com/includes/ajax-nosession.php", {
      body: form,
      method: 'POST'
  });
  const data = await res.json();
  if(data.status)
  {
    $('#succ_message').text('Otp Sent');
    seconds = 60;
    updateTimer()
  }
  else
  {
    window.location.href = "errordocs.php";
  }
});