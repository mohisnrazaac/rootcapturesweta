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

    if (isset($_GET['deleteId']))
    {
        $delete = $_GET['deleteId'];
        //foreach($deletes as $delete)
        //{
            $SQL = $odb -> prepare("DELETE FROM `grading_rubric_criteria` WHERE `id` = :id LIMIT 1");
            $SQL -> execute(array(':id' => $delete));
        //}
    }

    $getUserDetailIdWise = $user->getUserDetailIdWise($odb); 
    $college_id = $getUserDetailIdWise['college_id']; 

    $SQLGetRubric = $odb -> query("SELECT * FROM `grading_rubric_criteria` WHERE college_id = $college_id ORDER BY `ID` DESC");
    $rubricR = $SQLGetRubric -> fetchAll(PDO::FETCH_ASSOC);

    $title = 'Graded Rubric';
    require_once('common/header.php'); 
    
?>

<!-- page constent -->

   
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.3/css/dataTables.bootstrap5.css" />

   
<div class="row special_btn_long_menu">
			<ul class="d-flex flex-sm-row flex-column align-items-center justify-content-center" style="width: 100%;"> 
					<li class=""><a class="top_menu_item_long_menu" href="<?=BASEURL?>dashboard/add-grading-rubric.php">Create Grading Rubric Criterion</a></li> 
			</ul>  	 
	</div>

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
                <th>Criterion</th>
                <th>Description</th>
                <th>Team Assignment</th>
                <th style="text-align: right">Action</th> 
            </tr>
        </thead>
        <tbody> 
		<?php
        if(!empty($rubricR))
        {
            foreach ($rubricR as $key => $getInfo)
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
                <?php if ( $user -> isAdmin($odb) || $user -> isAssist($odb) ) {      ?>  
                    <tr class="gradeA">
                        <td> <?=$title?></td>
                        <td> <?=$detail?></td>
                        <td> <?=$teamassignment?></td>
                        <td>
                            <div class="d-flex flex-row-reverse">
                                <a href="https://rootcapture.com/dashboard/edit-grading-rubric.php?editId=<?=$id?>">
                                <button type="submit" class=" mx-2 rc-btn button-warning">EDIT</button>
                                </a>

                                <a href="https://rootcapture.com/admin/manage-grading-rubric.php?deleteId=<?=$id?>">
                                <button type="submit" class=" mx-2 rc-btn button-danger">DELETE</button>
                                </a>
                            </div>     
                        </td>
                <?php } ?>
            </tr> 
        <?php
            }
         }
         else
         {
            echo '<tr>
                    <td colspan="4">
                        There are currently no asset group created.
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