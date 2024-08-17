document.addEventListener("DOMContentLoaded", function () {
    setTimeout(function () {
        var gearIcon = document.querySelector(".gear_icon");
        gearIcon.style.display = "block"; 
    }, 2100);
	
	 
});
 
 


var passwordInputs = document.querySelectorAll('.password-input input');
passwordInputs.forEach(function(input, index, inputs) {
    input.addEventListener('input', function() {
        if (input.value.length === 1 && index < inputs.length - 1) {
            inputs[index + 1].focus();
        }
});
input.addEventListener('keydown', function(e) {
    if (e.key === 'Backspace' && index > 0) {
        if (input.value.length === 0) {
            e.preventDefault();
            inputs[index - 1].focus();
        }
    }
});
});

function showRespNav(){
    document.querySelector('.resp_nav').style.setProperty("display", "flex");
    document.querySelector('.cross').style.setProperty("display", "block");
    document.body.style.overflow = 'hidden';
}
function closeNav(){
    document.querySelector('.resp_nav').style.setProperty("display", "none");
    document.querySelector('.resp2_nav').style.setProperty("display", "flex");
    document.body.style.overflow = 'auto';
}
function handleResize() {
  try {
    if (window.innerWidth > 768) {
      document.querySelector('.cross').style.setProperty("display", "none");
      document.querySelector('.resp_nav').style.setProperty("display", "flex");
      document.querySelector('.resp2_nav').style.setProperty("display", "none");
    }else{
        document.querySelector('.cross').style.setProperty("display", "block");
        document.querySelector('.resp_nav').style.setProperty("display", "none");
        document.querySelector('.resp2_nav').style.setProperty("display", "flex");
    }
  } catch (error) {
  }
  }
  window.addEventListener('resize',handleResize);

  const star = "x";
  let text = "";
  let isVisible = false;
  
  function toggle(id) {
    const button = document.getElementById(id);
    const input = document.querySelectorAll("input");
    
    switch (isVisible) {
      case false:
        button.innerText = "Hide Password";
        input.value = text;
        isVisible = true;
        break;
      case true:
        button.innerText = "Show Password";
        input.value = star.repeat(text.length);
        isVisible = false;
    }
    
    console.log(`Text When Button Clicked: ${text}`);
  }
  
function maskPassword(inputField) {
// Get the value from the input field 
let inputValue = inputField.value;

// Replace each character with 'X'
let maskedValue = 'x'.repeat(inputValue.length);

// Update the value in the input field
inputField.value = maskedValue;
}
  /*
  function formatInput(id) {
     
    const elem = document.getElementById(id);
    const keyPressed = event.key;
   
    
    if (keyPressed == "Backspace") {
      text = text.substring(0, text.length - 1);
      elem.value = elem.value.substring(0, elem.value.length);
      console.log(`Text at Backspace: ${text}`)
      return;
    }
    
    if (keyPressed.length == 1) {
      text = text + keyPressed;
      elem.value = text;
    }
    
    
    switch (isVisible) {
      case false:
        elem.value = star.repeat(text.length - 1)
        console.log(`Text When Password = Hidden: ${text}`)
              break;
      case true:
        elem.value = text;
         elem.value = elem.value.substring(0, text.length - 1)
        console.log(`Text When Password = Visible: ${text}`)
    }
  }
*/


  function updatePadding() {
    try {
    const limitingWidth = 1420;
    const container = document.querySelector('.cont_3');
    const basePadding = 12; // Initial padding percentage
    const paddingIncrease = 1; // Padding increase per 50px
    const widthIncreaseInterval = 50; // Width interval for padding increase

    const currentWidth = container.offsetWidth;
    const dynamicPadding = basePadding + Math.floor((currentWidth - limitingWidth) / widthIncreaseInterval) * paddingIncrease;

    if (currentWidth > limitingWidth) {
      container.style.padding = `0 ${dynamicPadding}%`;
    } 
    else if( currentWidth < limitingWidth && currentWidth > 451){
      container.style.padding = `0 21%`;
    }
    else if(currentWidth < 451){
      container.style.padding = `0 20%`;
    }
  } catch (error) {}
  }

  // Call the function on page load and window resize
  window.addEventListener('load', updatePadding);
  window.addEventListener('resize', updatePadding);
  
  
    let popup = false;

      function showPop(){
        if(!popup){
          document.querySelector('.popup').style.display = 'flex';
          document.querySelector('.gear_icon').style.color = 'red';
          document.querySelector('.gear_icon').classList.add('rotated');
          popup = true;
        }
        else{
          document.querySelector('.popup').style.display = 'none';
          document.querySelector('.gear_icon').style.color = '#04fefe';
          document.querySelector('.gear_icon').classList.remove('rotated');
          popup = false;
        }
      }


    let isAnimationOff = false;
    function toggleAnimation()
    {
        const video = document.getElementById("myVideo"); 
        const toggleText = document.getElementById("toggleText1");
        const slider = document.querySelector(".slider1");

        if($('#toggleText1').text() != "OFF")
        {
            video.pause();
            deleteCookie('user_cookie_animation');
            setCookie('user_cookie_animation', 0, 365);
            toggleText.textContent = "OFF";
            slider.style.transform = "translateX(0)";
            toggleText.style.transform = "translate(0,-50%)";
        }
        else
        {
            video.play();
            deleteCookie('user_cookie_animation');
            setCookie('user_cookie_animation', 1, 365);
            toggleText.textContent = "ON";
            slider.style.transform = "translateX(100%)";
            toggleText.style.transform = "translate(-50%,-50%)";
        }
    }

      let isSoundOff = false;
    function toggleSound() {
      const toggleText = document.getElementById("toggleText2");
      const slider = document.querySelector(".slider2");
      isSoundOff = !isSoundOff;
      if (isSoundOff) {
        toggleText.textContent = "ON";
        slider.style.transform = "translateX(100%)";
        toggleText.style.transform = "translate(-50%,-50%)";
      } else {
        toggleText.textContent = "OFF";
        slider.style.transform = "translateX(0)";
        toggleText.style.transform = "translate(0,-50%)";
      }
    }
	
	
	        $(document).ready(function () {
            // Initialize input mask for phone number
            $('#phoneNumber').inputmask('(999) 999-9999', {
                onKeyValidation: function (result) {
                    if (!result) {
                        // Show tooltip if alpha character is entered
                        $('#phoneNumber').tooltip('show');
                    } else {
                        // Hide tooltip if valid character is entered
                        $('#phoneNumber').tooltip('hide');
                    }
                }
            });

            // Enable Bootstrap tooltips
            $('[data-toggle="tooltip"]').tooltip();
        });

        function sleep(ms) {
          return new Promise(resolve => setTimeout(resolve, ms));
      }
	