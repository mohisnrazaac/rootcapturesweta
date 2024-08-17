<?php
ob_start();
require_once 'includes/db.php';
require_once 'includes/init.php';
if (!($user -> LoggedIn()))
{
	header('location: login.php');
	die();
}
if (!($user -> notBanned($odb)))
{
	header('location: login.php');
	die();
}

// if is purple or is red or is blue or is admin or is assistant



$pageTitle = 'Quiz';
require_once 'header.php';


       


?>

    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container" id="container">

        <div class="overlay"></div>
        <div class="search-overlay"></div>

        <!--  BEGIN SIDEBAR  --> 
<?php include 'sidebar.php'; ?>
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
                                <li class="breadcrumb-item active" aria-current="page">Quiz</li>
                                <li style="width:40%"><video style="float:right" id="videoElement" autoplay width="150px"></video> </li>
                            </ol>
                            
                        </nav>
                        
                    </div>
                    <!-- /BREADCRUMB -->
                    <div class="row layout-top-spacing">
                    
                        <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">

                            <?php

                                if (isset($_POST['submitQuiz']))
                                {
                                    $errors = [];
                                    $question = $_POST['question'];
                                    $quize_id = $_POST['quize_id'];
                                    $correct_option = $_POST['correct_option'];
                                    foreach($question as $key=>$val){
                                        $quize = $quize_id[$key];
                                        $option = $correct_option[$key];

                                        $is_correct = 0;

                                        $findCorrect = $odb -> query("SELECT quize_question.correct_answer FROM quize_question where id = $val");
                                        $findCorrect -> execute();
                                        $quizData =  $findCorrect -> fetchAll(PDO::FETCH_ASSOC);
                                        if(isset($quizData[0]['correct_answer'] )){
                                            $correct_answer = $quizData[0]['correct_answer'];
                                            if($correct_answer == $option){
                                                $is_correct = 1;
                                            }
                                        }
                                    $created_at = date('Y-m-d H:i:s');
                                        

                                        $SQLinsert = $odb->prepare("INSERT INTO `quiz_submission`(`quize_id`, `question_id`, `choose_option`, `created_by`, `is_correct`, `created_at`, `updated_at`) VALUES (:quize_id,:question_id,:choose_option,:created_by,:is_correct, :created_at, :updated_at)");

                                        $SQLinsert->execute(array(':quize_id' => $quize,':question_id' => $val,':choose_option' => $option,':created_by' => $_SESSION['ID'],':is_correct' => $is_correct, ':created_at' => $created_at, ':updated_at' => $created_at));
                                    }

                                    if(empty($errors)) {
                                        // echo '<div class="message" id="message"><p><strong>SUCCESS: THE QUIZE HAS BEEN ADDED! YOU ARE NOW BEING REDIRECTED TO THE QUIZE MANAGEMENT PLATFORM.</strong></div><meta http-equiv="refresh" content="4;url='.BASEURL.'quiz_list.php">';
                                        // echo '<meta http-equiv="refresh" content="4;url='.BASEURL.'quiz_list.php">';
                                        header('location: quiz_list.php');
                                        exit;
                                        $team = '';
                                    } else {
                                        echo '<div class="error" id="message"><p><strong>ERROR: </strong>';
                                        foreach($errors as $error) {
                                            echo ''.$error.'<br />';
                                        }
                                        echo '</div>';
                                    }
                                    // quiz_submission
                                
                                }
                            ?>


                            <div class="widget-content widget-content-area br-8">
                           
                                <div style="text-align:center;" id="start-recording-div">
                                <br/>
                                    <button id="start-recording" style="margin-bottom: 18px;"  class="btn btn-primary" onclick="startRecording()">Start Quiz</button>
                                    <br/>
                                </div>
                                <table id="zero-config" class="table dt-table-hover" style="width:100%">
                                    <thead>
                                        <tr>
                <th scope="col">No</th>
                <th scope="col">Question</th>
                <th class="text-center" scope="col">Option 1</th>
				<th class="text-center" scope="col">Option 2</th>
				<th class="text-center" scope="col">Option 3</th>
				<th class="text-center" scope="col">Option 4</th>
				<th class="text-center" scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
				<?php

                $userIdS =  $_SESSION['ID'];
                $getUserDetailIdWise = $user->getUserDetailIdWise($odb); 
                $college_id = $getUserDetailIdWise['college_id']; 
                $team = $getUserDetailIdWise['rank'];
                $quize_id = base64_decode($_GET['quize']);

				$SQLGetAssets = $odb -> query("SELECT quize_question.* FROM quize INNER JOIN quize_question on quize_question.quize_id = quize.id where quize_id = $quize_id AND  FIND_IN_SET($userIdS,assign_users) OR assign_team = $team");
				$SQLGetAssets -> execute();
				$quizData =  $SQLGetAssets -> fetchAll(PDO::FETCH_ASSOC);
				if(!empty($quizData)){
                    echo '<form action="" class="section general-info" id="quizForm"  method="POST">';
					foreach ($quizData as $key => $getInfo)
					{
						
						$id = $getInfo['id'];
                        $question = $getInfo['question'];
                        $option1 = $getInfo['option1'];
                        $option2 = $getInfo['option2'];
                        $option3 = $getInfo['option3'];
                        $option4 = $getInfo['option4'];
                        $quize_id = $getInfo['quize_id'];

                        $ddData = 2;
                        if(!empty ($option4)){
                            $ddData = 4;
                        }

						echo '<tr class="gradeA"><td>'.($key+1).'</td><td>'.$question.'
                        <input type="hidden" name="question['.$key.']" value="'.$id.'">
                        <input type="hidden" name="quize_id['.$key.']" value="'.$quize_id.'">
                        </td><td>'.$option1.'</td>';
                        echo '<td>'.$option2.'</td><td>'.$option3.'</td>';
                        echo '<td>'.$option4.'</td>';
                        if($ddData == 2){
                            echo "<td>
                            <select class='form-control' name='correct_option[]'> 
                                <option value=''>Choose Answer Q".($key+1)."</option>
                                <option value='1'>Option 1</option>
                                <option value='2'>Option 2</option>

                            </select>
                        </td>";
                        }else{
                            echo "<td>
                            <select class='form-control' name='correct_option[".$key."]'> 
                                <option value=''>Choose Answer Q".($key+1)."</option>
                                <option value='1'>Option 1</option>
                                <option value='2'>Option 2</option>
                                <option value='3'>Option 3</option>
                                <option value='4'>Option 4</option>

                            </select>
                        </td>";
                        }
                        
                            echo '</tr>';
					}
                    echo ' <tr><td valign="top" colspan="7" class="dataTables_empty" style="text-align:center;">
                        <input type="submit" name="submitQuiz" class="btn btn-outline-success btn-lrg">
                    </td>
                    </tr>';
                    echo "</form>";
				}else{
					echo '<tr class=""><td valign="top" colspan="4" class="dataTables_empty" style="text-align:center;">There is no Asset IP available in list.</td></tr>';
				}
					
				?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <canvas id="canvas"></canvas>
            <!--  BEGIN FOOTER  -->
            <?php require_once 'includes/footer-section.php'; ?>
            <!--  END FOOTER  -->
            <!--  END CONTENT AREA  -->
        </div>
        <!--  END CONTENT AREA  -->
    </div>
    <!-- END MAIN CONTAINER -->
    <?php  require_once 'footer.php'; ?>
 
    <script>
        $('#zero-config').DataTable({
            "dom": "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l><'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>>>" +
        "<'table-responsive'tr>" +
        "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count  mb-sm-0 mb-3'i><'dt--pagination'p>>",
            "oLanguage": {
                "oPaginate": { "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>', "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>' },
                "sInfo": "Showing page _PAGE_ of _PAGES_",
                "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
                "sSearchPlaceholder": "Search...",
               "sLengthMenu": "Results :  _MENU_",
            },
            "stripeClasses": [],
            "lengthMenu": [100, 150, 200, 250],
            "pageLength": 100 
        });
    </script>

<script>
$("#zero-config").hide();
let stream, recorder, chunks, videostream = [];
var canvas = document.getElementById('canvas');
  var context = canvas.getContext('2d');
// Start the screen recording
function startRecording() {
    $('#start-recording-div').hide();
    $("#zero-config").show();
    navigator.mediaDevices.getUserMedia({ video: true })
        .then(function(videostream) {
            var video = document.getElementById('videoElement');
            video.srcObject = videostream;
        })
        .catch(function(error) {
          console.error('Error accessing the camera: ', error);
        });
navigator.mediaDevices.getDisplayMedia({ video: true })
  .then(function(stream) {
    // Store the stream
    // stream = screenStream;
   
    // Create a MediaRecorder
    recorder = new MediaRecorder(stream);

    // Listen for data available event
    recorder.ondataavailable = function(event) {
      chunks.push(event.data);
    };

    recorder.onstop = function() {
        var blob = new Blob(chunks, { type: 'video/webm' });
        chunks = [];

        // Save the recorded video to a file
        var videoURL = URL.createObjectURL(blob);
        var a = document.createElement('a');
        a.href = videoURL;
        a.download = 'screen_recording.webm';
        document.body.appendChild(a);
        
        callAPI(blob);
        // a.click();
        // document.body.removeChild(a);
        
      };
    // Start recording
    recorder.start();
  })
  .catch(function(error) {
    console.error('Error starting screen recording:', error);
  });
}


  // Stop the screen recording
function stopRecording() {
   
  // Stop the recorder
  recorder.stop();

  // Stop the screen stream
  stream.getTracks().forEach(function(track) {
    track.stop();
  });
// console.log(chunks);
  // Create a Blob from the recorded chunks
//   const recordedBlob = new Blob(chunks, { type: chunks[0].type });
// console.log(recordedBlob);
  // Clear the chunks array
//   chunks = [];

  // Call your API with the recorded file

}


// $('#quizForm').onclick(function(event) {
//     // event.preventDefault(); // Prevent form from submitting
//         // Stop the recorder
//         recorder.stop();
       
       
        
//       });

$('#quizForm').submit(function(event) {
  
  // Stop the recorder
  recorder.stop();

});

// Call the API with the recorded file
function callAPI(file) {
  // Create a FormData object
  var quizId = '<?php echo $_GET['quize'] ?>';
  console.log(quizId);
  console.log(file);
  var formData = new FormData();

  // Append the recorded file to the FormData object
  formData.append('quizId',quizId);
  formData.append('recordedFile', file, 'screen_recording.webm');

  // Send the AJAX request to upload the file
  $.ajax({
    url: 'quiz_screen_save.php', // Replace with your API endpoint
    type: 'POST',
    data: formData,
    processData: false,
    contentType: false,
    success: function(response) {
      // Handle the API response
      console.log('API response:', response);
      var load = setTimeout(function() {
            $("#quizForm").submit();
        }, 10000);
      // Submit the form after the alert is closed
      
    },
    error: function(xhr, status, error) {
      // Handle errors
      console.error('Error calling API:', error);
    }
  });
}

function saveForm(){
    
    // $("#quizForm").unbind('submit').submit();
}
</script>
<script>
// Check if the Page Visibility API is supported by the browser
if (typeof document.hidden !== "undefined") {
  // Add event listener for visibility change
  document.addEventListener("visibilitychange", handleVisibilityChange);
}

// Function to handle visibility change
function handleVisibilityChange() {
  if (document.hidden) {
    // Refresh the page when switching to another window or tab
    location.reload();
  }
}
</script>

    <!-- END PAGE LEVEL SCRIPTS -->
</body>
</html>