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

    if ($user -> isBlueTeam($odb) || $user -> isRedTeam($odb) || $user -> isPurpleTeam($odb) || $user -> isAssist($odb) || $user -> isAdmin($odb)) {

    } else {
        header('location: index.php');
        die();
    }

    $title = 'Graded Rubric';
    require_once('common/header.php'); 
    
?>

<!-- page constent -->

   
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.3/css/dataTables.bootstrap5.css" />

   
<!-- <div class="row special_btn_long_menu">
			<ul class="d-flex flex-sm-row flex-column align-items-center justify-content-center" style="width: 100%;"> 
					<li class=""><a class="top_menu_item_long_menu" href="<?=BASEURL?>dashboard/create_group.php">Create New Group</a></li> 
			</ul>  	 
	</div> -->

<div class="p-4"></div>

<div class="container px-5"> 
</head>
<body>

<div class="main-container mt-5">
    <span class="cyber_range_heading_bg">Cyber Range / <span class="primary-color">Graded Rubric</span></span>
</div>
	
	
  <div class="main_announcement container ">
     
      <div style="margin-bottom: 1px">
        <label class="cyber_range_bg" for="editor"><i>&nbsp;</i></label> 
      </div>   
	  
  
 
 

	  
	   <table id="rc_table" class="table table-striped nowrap" style="width:100%">
        <thead>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Team Assignment</th>
            </tr>
        </thead>
        <tbody> 
		<?php
         $loggedUserId = $user -> loggedUserDetail()['id'];
         if ($user -> isAdmin($odb) || $user -> isAssist($odb)) {
            $getDataSql = "SELECT * FROM `grading_rubric_criteria` ORDER BY `ID` DESC";
         }
         else if( $user -> isBlueTeam($odb)){ 
            $getDataSql = "SELECT * FROM `grading_rubric_criteria` WHERE (`blueteam_grade` IS NOT NULL AND `blueteam_grade` != '') OR FIND_IN_SET ($loggedUserId,`assigned_user`) ORDER BY `ID` DESC";
          }
         else if( $user -> isRedTeam($odb) ){ 
            $getDataSql = "SELECT * FROM `grading_rubric_criteria` WHERE (`redteam_grade` IS NOT NULL AND `redteam_grade` != '') OR FIND_IN_SET ($loggedUserId,`assigned_user`) ORDER BY `ID` DESC";
          }
          else if( $user -> isPurpleTeam($odb) ){ 
              $getDataSql = "SELECT * FROM `grading_rubric_criteria` WHERE (`purpleteam_grade` IS NOT NULL AND `purpleteam_grade` != '') OR FIND_IN_SET ($loggedUserId,`assigned_user`) ORDER BY `ID` DESC";
          }
        

      $SQLGetRubric = $odb -> query($getDataSql);
      $SQLGetRubric -> execute();
      $gradeR =  $SQLGetRubric -> fetchAll(PDO::FETCH_ASSOC);
        if(!empty($gradeR))
        {
            foreach ($gradeR as $key => $getInfo)
            {
                $id = $getInfo['id'];
                $title = $getInfo['title'];
                $detail = $getInfo['detail'];
                $teamassignment = '';
                if( $getInfo['redteam_grade'] ) {
                    $teamassignment .= 'Red Team ('.$getInfo['redteam_grade'].')';
                }
                if($getInfo['blueteam_grade']) {
                    $teamassignment .= ' Blue Team ('.$getInfo['blueteam_grade'].')';
                }
                if($getInfo['purpleteam_grade']){
                    $teamassignment .= ' Purple Team ('.$getInfo['purpleteam_grade'].')';
                }
        ?>
            <tr>
                <td><?=$title?></td>
                <td><?=$detail?></td>
                <td><?=$teamassignment?></td>
            </tr> 
        <?php
            }
         }
         else
         {
            echo '<tr>
                        <td colspan="3">
                          There are currently data.
                        </td>
                  </tr>';
         }
        ?>
        </tbody> 
    </table>
 
   
 
 
      
	  <!-- <div class="d-flex flex-row-reverse">
      <button type="submit" class="rc-btn submit-button">Submit</button>
	  </div>   -->
	 
  </div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script> 
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
	    new DataTable('#rc_table');
  </script> 
  

<!-- page conctent -->

<?php require_once('common/footer.php') ?>