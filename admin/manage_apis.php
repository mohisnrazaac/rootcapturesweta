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

// if is purple or is red or is blue or is admin or is assistant

$pageTitle = 'API Management';
require_once '../header.php';

$getUserDetailIdWise = $user->getUserDetailIdWise($odb); 
$college_id = $getUserDetailIdWise['college_id']; 

$teamList = $user->getTeamList($odb,$college_id);
?>
    
<style>
	.modal-dialog{
		max-width: 700px!important;
	}
</style>

    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container" id="container">

        <div class="overlay"></div>
        <div class="search-overlay"></div>

        <!--  BEGIN SIDEBAR  --> 
		<?php include '../sidebar.php'; ?>
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
                                <li class="breadcrumb-item active" aria-current="page">API Management</li>
                            </ol>
                            <br>
                            <a class="btn btn-outline-success btn-lrg" href="<?=BASEURL?>admin/create_apis.php" role="button">Create New API</a>
                        </nav>
                    </div>
                    <!-- /BREADCRUMB -->
                    <div class="row layout-top-spacing">
                        <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                            <div class="widget-content widget-content-area br-8 ">
                                <div class="center-block fix-width scroll-inner">
	                                <div class="table-responsive">
		                                <table id="zero-config" class="table dt-table-hover tablenoscroll" style="width:100%">
		                                    <thead>
		                                        <tr>
									                <th scope="col" class="col-md-2">API Name</th>
									                <th class="text-center col-md-2" scope="col">API Key</th>
									                <th class="text-center col-md-2" scope="col">API Function</th>
													<th class="text-center col-md-1" scope="col">Status</th>
													<th class="text-center col-md-5" scope="col">User Actions</th>
		                                        </tr>
		                                    </thead>
		                                    <tbody>
		                                    	<?php
									                if (isset($_GET['deleteId'])){
									                    $delete = $_GET['deleteId'];

								                        $SQL = $odb -> prepare("DELETE FROM `api_management` WHERE `api_id` = :id AND college_id=:college_id LIMIT 1");
								                        $SQL -> execute(array(':id' => $delete,':college_id'=>$college_id));
									                    //}
									                    echo '<div class="message" id="message"><p><strong>SUCCESS: </strong>The api has been deleted!</p></div>';
									                }
            
		            								$SQLApis = $odb -> query("SELECT * FROM `api_management` where college_id=$college_id ORDER BY `api_id` ASC");
		            								$getApis = $SQLApis -> fetchAll(PDO::FETCH_ASSOC);
		            								if(!empty($getApis)){
		            									foreach ($getApis as $key => $value) {
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
		            												<a href="javascript:void(0);" class="api_view" api="<?php echo $value['api_function'] ; ?>">
		            													<button class="btn btn-outline-info  mb-2 me-4" name="viewBtn" value="<?php echo $value['api_id'] ; ?>" role="button" type="button">View API Link</button>
		            												</a>
		            												<a href="<?=BASEURL?>/admin/edit_apis.php?editId=<?php echo $value['api_id'] ; ?>">
		            													<button class="btn btn-outline-warning  mb-2 me-4">Edit</button>
		            												</a>
		            												<a href="<?=BASEURL?>/admin/manage_apis.php?deleteId=<?php echo $value['api_id'] ; ?>" class="api_delete">
		            													<button class="btn btn-outline-danger mb-2 me-4" name="deleteBtn" value="<?php echo $value['api_id'] ; ?>" role="button" type="submit">Delete</button>
		            												</a>
		            											</td>
		            										</tr>
		            									<?php
		            									}
		            								}else{
		            									echo '<tr class=""><td valign="top" colspan="5" class="dataTables_empty" style="text-align:center;">There are currently no Api created,</td></tr>';
		            								}
		            							?>
		                                    </tbody>
										</table>
			                        </div>
                            	</div>
								<br>
                        	</div>
                    	</div>
               		</div>

            	</div>
            </div>
            <!--  BEGIN FOOTER  -->
            <?php require_once '../includes/footer-section.php'; ?>
            <!--  END FOOTER  -->
        </div>
        <!--  END CONTENT AREA  -->
    </div>
    <!-- END MAIN CONTAINER -->

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

    <?php  require_once '../footer.php'; ?>
  
    <script>
        $('#zero-config').DataTable({
            "dom": "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l><'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>>>" +
        "<''tr>" +
        "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count  mb-sm-0 mb-3'i><'dt--pagination'p>>",
            "oLanguage": {
                "oPaginate": { "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>', "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>' },
                "sInfo": "Showing page _PAGE_ of _PAGES_",
                "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
                "sSearchPlaceholder": "Search...",
               "sLengthMenu": "Results :  _MENU_",
            },
            "stripeClasses": [],
            "lengthMenu": [7, 10, 20, 50],
            "pageLength": 10 
        });      
       
 
    	$(document).on("change",".api_status",function(e){
    		var s=0;
    		if(this.checked) {
		        s=0;
		    }else{
		    	s=1;
		    }
		    var id = $(this).val();
		    $.ajax({
	            type:"post",
	            url:"api-ajax.php",
	            data:{action:"api_status",s:s,id:id},
	            success:function(response){
	                	                
	            }
	        })
    	});

    	$(document).on("click",".api_delete",function(e){
    		e.preventDefault();
    		if (confirm("Do you really want to delete this api?") == true) {
			    var l = $(this).attr("href");
			    window.location.href = l;
			}
    	});

    	$(document).on("click",".api_view",function(e){
    		var api = jQuery(this).attr("api");
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
</body>
</html>