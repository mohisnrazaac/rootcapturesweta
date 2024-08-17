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
        $SQL = $odb -> prepare("DELETE FROM `news` WHERE `ID` = :id LIMIT 1");
        $SQL -> execute(array(':id' => $delete));
    }

    $getUserDetailIdWise = $user->getUserDetailIdWise($odb); 
    $college_id = $getUserDetailIdWise['college_id']; 

    $SQLSelect = $odb -> query("SELECT a.*, u.username FROM `news` as a INNER JOIN users as u ON a.userID = u.id WHERE u.college_id = $college_id ORDER BY `date` DESC");
    $SQLSelect -> execute();
    $newsR =  $SQLSelect -> fetchAll(PDO::FETCH_ASSOC); 
   
   $title = 'Announcements Management';
   require_once('common/header.php'); 
    
?>

<!-- page constent -->

   
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.3/css/dataTables.bootstrap5.css" />

   
	<div class="row special_btn_long_menu">
			<ul class="d-flex flex-sm-row flex-column align-items-center justify-content-center" style="width: 100%;"> 
					<li class=""><a class="top_menu_item_long_menu" href="https://rootcapture.com/admin/addnews.php">Add a Announcement</a></li> 
			</ul>  	 
	</div>

<div class="p-4"></div>

<div class="container px-5"> 
</head>
<body>

<div class="main-container mt-5">
    <span class="cyber_range_heading_bg">Cyber Range / <span class="primary-color">Team Management</span></span>
</div>
	
	
  <div class="main_announcement container ">
     
      <div style="margin-bottom: 1px">
        <label class="cyber_range_bg" for="editor"><i>&nbsp;</i></label> 
      </div>   
	  
  
 
 

	  
	   <table id="rc_table" class="table table-striped nowrap" style="width:100%">
        <thead>
            <tr>
                <th>Title</th>
                <th>Created By</th>
                <th>Date Updated</th>
                <th style="text-align: right">Actions</th> 
            </tr>
        </thead>
        <tbody> 
		<?php
        if(!empty($newsR))
        {
            foreach ($newsR as $key => $show)
            {
                $titleShow = $show['title'];
                $detailShow = $show['detail'];
                $rowID = $show['ID'];
                if(isset($show['username']))
                {
                    $postedby = $show['username'];
                }
                else
                {
                    $postedby = $show['created_by'];
                }                       
                
                $date = date_format(date_create($show['date']),"m-d-Y, h:i:s"); 
        ?>
            <tr>
                <td><?=$titleShow?></td>
                <td><?=$postedby?></td>
                <td><?=$date?></td>
                <td> 

				<div class="d-flex flex-row-reverse">
                    <a href="<?=BASEURL?>admin/editnews.php?id=<?=$rowID?>">
                    <button type="submit" class=" mx-2 rc-btn button-warning">EDIT</button>
                    </a>

                    <a href="<?=BASEURL?>dashboard/news.php?deleteId=<?=$rowID?>">
                    <button type="submit" class=" mx-2 rc-btn button-danger">DELETE</button>
                    </a>
                </div>
				</td> 
            </tr> 
        <?php
            }
         }
         else
         {
            echo '<tr>
                        <td colspan="4">
                            There are no announcements to display
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