<?php
ob_start();
require_once '../includes/db.php';
require_once '../includes/init.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
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

$nextQuery = $odb -> query("SELECT * FROM `antlets` order by antlet_num");
$nextQuery -> execute();
$nextData =  $nextQuery -> fetchAll(PDO::FETCH_ASSOC); 


if (isset($_POST['submitServer']))
{
// print_r($_POST); exit;	
    $src_server = $_POST['src_server'];
    $server_name = $_POST['server_name'];
    
	$errors = array();
	if (empty($src_server) || empty($server_name))
	{
		$errors[] = 'Please verify all fields';
	}
	if (empty($errors))
	{   
        try
        {   
            // print_r($nextData);
            if(!empty($nextData) && isset($nextData[0]['antlet_num'])){
                $antlet_num = $nextData[0]['antlet_num']+1;
            }else{
                $antlet_num = 25;
            }
            $postData['src-antlet']['dname'] = $src_server;
            $postData['src-antlet']['snapshot-name'] = $server_name;

            $postData['dst-antlet']['dname'] = $server_name;
            $postData['dst-antlet']['compression'] = "inherit";
            $postData['dst-antlet']['zpool-name'] = "antlets";
            $postData['dst-antlet']['antlet-num'] = $antlet_num;


            // CURL START
                $curl = curl_init();
                curl_setopt_array($curl, array(
                CURLOPT_URL => 'http://66.172.2.18:8080/api/login',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>'{
                    "username": "root",
                    "password": "antsle"
                }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
                ));
                $response = curl_exec($curl);
                if(curl_errno($curl))
                    echo 'Curl error: '.curl_error($curl);
                $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                // echo $httpcode;
                curl_close($curl);

                if($httpcode == 200 ){
                    $getToken = json_decode($response,true);

                    $curlClone = curl_init();

                    curl_setopt_array($curlClone, array(
                    CURLOPT_URL => 'http://66.172.2.18:8080/api/antlets/clone',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS =>json_encode($postData),
                    CURLOPT_HTTPHEADER => array(
                        'Accept: application/json',
                        'Authorization: Token '.$getToken['token'],
                        'Content-Type: application/json',
                    ),
                    ));

                    $responseClone = curl_exec($curlClone);

                    curl_close($curlClone);
                   

                    $responseArray = json_decode($responseClone,true);

                   

                    if(isset($responseArray['success']) && $responseArray['success']){

                        $sql = "INSERT INTO `antlets`(`src_dname`, `compression`, `dst_dname`, `snapshot_name`, `antlet_num`, `zpool_name`, `status`, `college_id`, `created_at`, `updated_at`) VALUES(?,?,?,?,?,?,?,?,?,?)";
                        $stmt= $odb->prepare($sql);
                        $stmt->execute([$src_server,"inherit",$server_name, $server_name, $antlet_num, "antlets",1,$college_id,DATETIME,DATETIME ]);
            

                    }else{
                        $errors[] = $responseArray['message'];
                        $errors[] = "Not Saved";
                    }


                }

            // CURL END


       
        } 
        catch (PDOException $e)
        {
            $errors[] = "DataBase Error: The news could not be added.<br>".$e->getMessage();
            // $errors[] = $e;
        } catch (Exception $e) 
        {
            $errors[] = "General Error: The news could not be added.<br>".$e->getMessage();
        }       
	}
	else
	{
		foreach($errors as $error)
		{
			echo '-'.$error.'<br />';
		}
		echo '</div>';
	}
}

$pageTitle = 'Create Server';
require_once '../header.php';
?>

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
                                <li class="breadcrumb-item">Server</li>
                                <li class="breadcrumb-item active" aria-current="page"><?php echo $pageTitle ?></li>
                            </ol>
                        </nav>
                    </div>
					<br>
                    <!-- /BREADCRUMB -->
                       <div class="col-lg-12 col-12 layout-spacing">
                         <div class="row mb-3">
                                <div class="col-md-12">
                                    <h2><?php echo $pageTitle ?></h2>
        
                                    <div class="animated-underline-content">
                                        <ul class="nav nav-tabs" id="animateLine" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" id="animated-underline-home-tab" data-bs-toggle="tab" href="#animated-underline-home" role="tab" aria-controls="animated-underline-home" aria-selected="true"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg> <?php echo $pageTitle ?></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-content" id="animateLineContent-4">
                                <div class="tab-pane fade show active" id="animated-underline-home" role="tabpanel" aria-labelledby="animated-underline-home-tab">
		<?php 
		if (isset($_POST['submitServer']))
		{
			if(empty($errors)) {
				echo '<div class="message" id="message"><p><strong>SUCCESS:New Server has been created. You are now being redirected to the Listing.</strong></div>
                ';
				// <meta http-equiv="refresh" content="4;url=../admin/knowledgebase.php">
				$titleAdd = '';
				$detailAdd = '';
			} else {
				echo '<div class="error" id="message"><p><strong>ERROR: </strong>';
				foreach($errors as $error) {
					echo ''.$error.'<br />';
				}
				echo '</div>';
			}
			
		}
       
		
		?>
                                    
                              <div class="row">
                                        <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
                                    <form action="" class="section general-info" onsubmit="javascript: return process();" method="POST">
                                       
                                      <div class="info">
                                                    <div align="Center">
                                                       

                                                        <div class="row">
 <div class="col-lg-11 mx-auto">
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 mt-md-0 mt-4">

                                                            <div class="row">


                                    <div class="col-md-6">
                                        <div class="form-group ">
                                            <label for="src_server">Server Name</label>
                                            
                                            <select name="src_server" id="src_server" class="form-control mb-3">
                                                <option value="">Please select Source Server</option>
                                                <option value="Backbox">Backbox</option>
                                                <option value="Fedora">Fedora</option>
                                                <option value="KaliLinux">KaliLinux</option>
                                                <option value="Parrot">Parrot</option>
                                                <option value="Win10">Win10</option>
                                                <option value="Ubuntu20">Ubuntu20</option>
                                                
                                                
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group ">
                                            <label for="category">Snapshot Name</label>
                                           
                                            <input type="text" class="form-control mb-3" placeholder="Server Name" name="server_name">
                                            Please use only letters, numbers, dots (.) and dashes (-)
                                        </div>
                                    </div>
                                    
                                  
                                     <div class="col-md-12 mt-1">
                                        <div class="form-group text-end">
                                        <input type="submit" name="submitServer" class="btn btn-outline-success btn-lrg">
                                    </div>
                                    </div>

                                    </div></div></div></div></div></div></div>
                                    </form>
                                   </div>
                               </div>


                                </div>
                            </div>
                        </div>
                </div>

            </div>

            <!--  BEGIN FOOTER  -->
            <?php require_once '../includes/footer-section.php'; ?>
            <!--  END FOOTER  -->
            <!--  END CONTENT AREA  -->
        </div>
        <!--  END CONTENT AREA  -->
    </div>
    <!-- END MAIN CONTAINER -->
   
    <?php require_once '../footer.php'; ?>

    

    <script>

        // SUBCAT
$(document).ready(function(){  
      
      $('#category').change(function(){
            var cat = $(this).val();
            var subCatUrl = "subCategoryFetch.php"
            $.ajax({
                    type: "POST",
                    url: subCatUrl,
                    data: {
                      "id": cat,                          
                    },
                    success: function (data) {
                      var subCat = JSON.parse(data);
                    //   console.log(data);
                      var subCatHtml = '<option value="">Please Select Sub Category</option>';
                      if(subCat)
                      {
                        $.each(subCat, function( index, value ) {
                          subCatHtml += "<option value='"+value.enterprise_id+"'>"+value.name+"</option>";
                        });
                      }
    
                      $('#sub_category').html(subCatHtml);
                    }
                });
    
          });
          });

            function process()
            {
               if(!$('div.ql-blank').length)
               {
                    var content = $('div.ql-editor').html();
                    $('#detailAdd').val(content); 
               }
               else
               {
                $('#detailAdd').val(''); 
               }
                      
               return true;
            }

    </script>


</body>
</html>