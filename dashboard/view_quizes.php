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
    $quize_id = $_GET['quize'];

    $SQLGetQuize = $odb -> query("SELECT * from quize where id = $quize_id ORDER BY id ASC");
    $SQLGetQuize -> execute();
    $teamsR = $SQLGetQuize -> fetchAll(PDO::FETCH_ASSOC);

    $getQuizeDetail = $odb -> query("SELECT * from quize_question where quize_id = $quize_id ORDER BY id ASC");
    $getQuizeDetail -> execute();
    $quizeDetail = $getQuizeDetail -> fetchAll(PDO::FETCH_ASSOC);

    if( isset($_GET['publish']) && $_GET['publish']==1 ){
        $quiz_i = $quize_id;
        $status = 2;
        $updateTeamStatusSql = $odb->prepare("UPDATE quize SET `status` = :status WHERE id = :id");
        $updateTeamStatusSql->execute(array(':status' => $status, ':id' => $quiz_i));
        
    }

    $title = 'View Quizes';
    require_once('common/header.php'); 
    
?>

<!-- page constent -->

   
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.3/css/dataTables.bootstrap5.css" />

<div class="row special_btn_long_menu">
			<ul class="d-flex flex-sm-row flex-column align-items-center justify-content-center" style="width: 100%;"> 
					<li class="">
                    <?php
                        if(isset($teamsR[0]['status']) && $teamsR[0]['status'] == 1){
                        echo '<a class="top_menu_item_long_menu" href="'.BASEURL.'dashboard/view_quizes.php?quize='.$quize_id.'&publish=1">Publish</a>';
                        }else if (isset($teamsR[0]['status']) && $teamsR[0]['status'] == 2){
                            echo '<a class="top_menu_item_long_menu" href="javascript:void(0)">Published</a>';

                        }else{
                            echo '<a class="top_menu_item_long_menu" href="javascript:void(0)">Inactive</a>';

                        }
                    ?>
                    </li> 
			</ul>  	 
	</div>



<div class="p-4"></div>

<div class="container px-5"> 
</head>
<body>

<div class="main-container mt-5">
    <span class="cyber_range_heading_bg">Cyber Range / 
        <span class="primary-color">
            View Quiz 
            <?php
            if(isset($teamsR[0]['name'])){
                echo '('.$teamsR[0]['name'].')';
            }
            ?>
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
                <th>Question</th>
                <th>Option 1</th>
                <th>Option 2</th>
                <th>Option 3</th>
                <th>Option 4</th>
                <th>Correct Option</th>
            </tr>
        </thead>
        <tbody> 
		<?php
        if(!empty($quizeDetail))
        {
            foreach ($quizeDetail as $key => $getInfo)
            {
                $id = $getInfo['id'];
                $question = $getInfo['question'];
                $option1 = $getInfo['option1'];
                $option2 = $getInfo['option2'];
                $option3 = $getInfo['option3'];
                $option4 = $getInfo['option4'];
                
                $correct = $getInfo['correct_answer'];
        ?>
            <tr>
                <td><?php echo $question; ?></td>
                <td><?php echo $option1; ?></td>
                <td><?php echo $option2; ?></td>
                <td><?php echo $option3; ?></td>
                <td><?php echo $option4; ?></td>
                <td><?php echo $correct; ?></td>
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

