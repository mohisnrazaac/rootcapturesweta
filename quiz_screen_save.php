<?php
require_once 'includes/db.php';
require_once 'includes/init.php';

// Check if a file was uploaded successfully

if (isset($_FILES['recordedFile']) && $_FILES['recordedFile']['error'] ==0) {
  $tempFilePath = $_FILES['recordedFile']['tmp_name'];
  $originalFileName = date("YmdHis").$_FILES['recordedFile']['name'];
echo $originalFileName;
  // Move the temporary file to the desired location
  $destinationPath = 'quiz/'; // Replace with the path where you want to save the file
  $destinationFile = $destinationPath . $originalFileName;

  if (move_uploaded_file($tempFilePath, $destinationFile)) {
    $quize = base64_decode($_POST['quizId']);
    // File was successfully moved and saved
   echo $quize."--".$_SESSION['ID']."---";
             
                    $odb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $odb->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

                    $SQLinsert = $odb->prepare("INSERT INTO `quiz_submit_video`(`quize_id`,`user_id`, `video_link`, `created_at`) VALUES (:quize_id,:created_by,:video_link, :created_at)");
                    $created_at = date("Y-m-d H:i:s");
                    echo $destinationFile;
                    $SQLinsert->execute(array(':quize_id' => $quize,':created_by' => $_SESSION['ID'],':video_link' => $destinationFile, ':created_at' => $created_at));
                    echo 'File uploaded and saved successfully!';
                 
  } else {
    // Error moving the file
    echo 'Error moving the file to the destination path.';
  }
} else {
  // Error uploading the file
  echo 'Error uploading the file.';
}
?>