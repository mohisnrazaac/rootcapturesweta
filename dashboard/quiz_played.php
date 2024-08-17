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

    $getUserDetailIdWise = $user->getUserDetailIdWise($odb); 
    $college_id = $getUserDetailIdWise['college_id']; 
    $userIdS =  $_SESSION['ID'];
    $quizeId = base64_decode($_GET['quize']);
    $SQLGetQuize = $odb -> query("SELECT sum(is_correct) as correct, avg(is_correct) as avgcorrect,username,email,quiz_submission.created_by as submitted_by, quiz_submission.status as sub_status from quiz_submission inner join quize on quize.id = quiz_submission.quize_id INNER JOIN users on users.id = quiz_submission.created_by where quize.created_by = $userIdS AND quiz_submission.quize_id = $quizeId GROUP BY quiz_submission.created_by");
    $SQLGetQuize -> execute();
    $teamsR = $SQLGetQuize -> fetchAll(PDO::FETCH_ASSOC);

    $title = 'Quiz Played';
    require_once('common/header.php'); 
    
?>

<!-- page constent -->

   
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.3/css/dataTables.bootstrap5.css" />

<div class="p-4"></div>

<div class="container px-5"> 
</head>
<body>

<div class="main-container mt-5">
    <span class="cyber_range_heading_bg">Cyber Range / 
        <span class="primary-color">
            Quiz Played
        </span>
    </span>
</div>
	
	
  <div class="main_announcement container ">
     
      <div style="margin-bottom: 1px">
        <label class="cyber_range_bg" for="editor"><i>&nbsp;</i></label> 
      </div>   
	  
  
 
 

	  
	   <table id="rc_table" class="table table-striped nowrap" style="width:100%">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Correct Answer</th>
                <th>Score</th>
                <th>Status</th>
                <th style="text-align: right">Action</th> 
            </tr>
        </thead>
        <tbody> 
		<?php
        if(!empty($teamsR))
        {
            foreach ($teamsR as $key => $getInfo)
            {
                $id = $getInfo['submitted_by'];
                $correct = $getInfo['correct'];
                $avgcorrect = $getInfo['avgcorrect'];
        ?>
            <tr>
                <td><?php echo $getInfo['username']; ?></td>
                <td><?php echo $getInfo['email']; ?></td>
                <td><?php echo $correct; ?></td>
                <td><?php echo ($avgcorrect*100).'%'; ?></td>
                <?php
                    if($getInfo['sub_status'] == 1){
                            echo '<td>Approved</td>';
                    }else if($getInfo['sub_status'] == 2){
                        echo '<td>Rejected</td>';
                    }else{
                        echo '<td>Pending</td>';
                    }
                ?>
                <td> <button class="btn btn-outline-success  mb-2 me-4">Check</button> </td>
            </tr> 
        <?php
            }
         }
         else
         {
            echo '<tr>
                    <td colspan="6">
                        There are currently no data.
                    </td>
                  </tr>';
         }
        ?>
        </tbody> 
    </table>
  </div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
	    new DataTable('#rc_table');
  </script> 
  

<!-- page conctent -->

<?php require_once('common/footer.php') ?>

