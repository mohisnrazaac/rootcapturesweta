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
    
    $teamList = $user->getTeamList($odb,$college_id);

    if (isset($_GET['deleteId'])){
        $delete = $_GET['deleteId']; 

        $SQL = $odb -> prepare("DELETE FROM `api_management` WHERE `api_id` = :id AND college_id=:college_id LIMIT 1");
        $SQL -> execute(array(':id' => $delete,':college_id'=>$college_id));
        //}
    }

    $SQLApis = $odb -> query("SELECT * FROM `api_management` where college_id=$college_id ORDER BY `api_id` ASC");
    $getApis = $SQLApis -> fetchAll(PDO::FETCH_ASSOC);

    $title = 'API Management';
    require_once('common/header.php'); 
    
?>

<!-- page constent -->

   
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.3/css/dataTables.bootstrap5.css" />

   
<div class="row special_btn_long_menu">
			<ul class="d-flex flex-sm-row flex-column align-items-center justify-content-center" style="width: 100%;"> 
					<li class=""><a class="top_menu_item_long_menu" href="<?=BASEURL?>dashboard/create_apis.php">Create New Api</a></li> 
			</ul>  	 
	</div>

<div class="p-4"></div>

<div class="container px-5"> 
</head>
<body>

<div class="main-container mt-5">
    <span class="cyber_range_heading_bg">Cyber Range / <span class="primary-color">API Management</span></span>
</div>
	
	
  <div class="main_announcement container ">
     
      <div style="margin-bottom: 1px">
        <label class="cyber_range_bg" for="editor"><i>&nbsp;</i></label> 
      </div>   
	  
  
 
 

	  
	   <table id="rc_table" class="table table-striped nowrap" style="width:100%">
        <thead>
            <tr>
                <th>API Name</th>
                <th>API Key</th>
                <th>API Function</th>
                <th>Status</th>
                <th style="text-align: right">Action</th> 
            </tr>
        </thead>
        <tbody> 
		<?php
        if(!empty($getApis))
        {
            foreach ($getApis as $key => $value)
            {
        ?>
            <tr>
                <td><?php echo $value['api_name']; ?></td>
                <td><?php echo $value['api_key']; ?></td>
                <td><?php echo $value['api_function']; ?></td>
                <td>
                    <div class="form-check form-switch">
                        <input type="checkbox" class="form-check-input api_status" value="<?php echo $value['api_id']; ?>" id="swatch<?php echo $key; ?>" <?php if($value['status']==0){ echo "checked"; } ?>>
                        <label class="form-check-label" for="swatch<?php echo $key; ?>"></label>
                    </div>
                </td>

                <td>
                    <div class="d-flex flex-row-reverse">
                        <a href="javascript:void(0);" class="api_view" api="<?php echo $value['api_function'] ; ?>">
                            <button class="btn btn-outline-info  mb-2 me-4" name="viewBtn" value="<?php echo $value['api_id'] ; ?>" role="button" type="button">View API Link</button>
                        </a>

                        <a href="https://rootcapture.com/dashboard/edit_apis.php?editId=<?=$value['api_id']?>">
                        <button type="submit" class=" mx-2 rc-btn button-warning">EDIT</button>
                        </a>

                        <a href="https://rootcapture.com/dashboard/manage_apis.php?deleteId=<?=$value['api_id']?>">
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
                        There are currently no asset group created.
                    </td>
                  </tr>';
         }
        ?>
        </tbody> 
    </table>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Api Link Details</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
              <svg> ... </svg>
            </button>
          </div>
          <form method="post">
            <div class="modal-body api_view_data">
                
            </div>
            <div class="modal-footer">              
                <button type="button" class="btn btn-outline-warning btn-lrg" data-bs-dismiss="modal">
                <i class="flaticon-cancel-12"></i> Close </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Modal Ends -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
	    new DataTable('#rc_table');
        $(document).on("click",".api_view",function(e){
    		var api = $(this).attr("api");
    		$.ajax({
	            type:"post",
	            url:"api-ajax.php",
	            data:{action:"api_view",api:api},
	            success:function(response){
	                $('#exampleModal').modal('show');   
	                $('.api_view_data').html(response);                
	            }
	        })
    		
    	});
  </script> 
  

<!-- page conctent -->

<?php require_once('common/footer.php') ?>

