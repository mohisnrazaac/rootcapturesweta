<?php
ob_start();
require_once '../includes/db.php';
require_once '../includes/db-enterprise.php';
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

$getUserDetailIdWise = $user->getUserDetailIdWise($odb); 
$college_id = $getUserDetailIdWise['college_id']; 
$pageTitle = 'KnowledgeBase';
require_once '../header.php';
$tokenQuery = $odb -> query("SELECT * FROM `college` WHERE `id` = $college_id");
$tokenQuery -> execute();
$tokenSelect =  $tokenQuery -> fetchAll(PDO::FETCH_ASSOC); 
$accessToken = null;
if(isset($tokenSelect[0]['access_token'])){
    $accessToken = $tokenSelect[0]['access_token'];
}
// echo $accessToken; exit;
$tsSelect = $odbenterprise -> query("SELECT * from tenant_setting WHERE tenant_token = '$accessToken'");
$tsSelect -> execute();
$tsSetting =  $tsSelect -> fetchAll(PDO::FETCH_ASSOC); 
$checkFreeze = 0;
if(isset($tsSetting[0]['kb_freeze'])){
    $checkFreeze = $tsSetting[0]['kb_freeze'];
}

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
                                <li class="breadcrumb-item">Cyber Range</li>
                                <li class="breadcrumb-item active" aria-current="page">KnowledgeBase</li>
                            </ol>
                                                        <br>
                            <a class="btn btn-outline-success btn-lrg" href="create_knowledgebase.php" role="button">Create  KnowledgeBase</a>
                            <?php
                            if($checkFreeze == 0){
                                ?>
                            <form action="" style="width: fit-content;display: contents;" method="POST">
                            <button type="submit" class="btn btn-outline-primary btn-lrg" name="import" onclick="" role="button">Import KnowledgeBase</button>
                            </form>
                            <?php } ?>
                        </nav>
                    </div>

                    <?php
                            if(isset($_POST['import'])){
                                try{

                                    $odb->beginTransaction();
                                    
                                    $SQLSelect = $odbenterprise -> query("SELECT * from college WHERE access_code = '$accessToken'");
                                    //    print_r($SQLSelect -> fetch(PDO::FETCH_ASSOC)); exit;
                                    $SQLSelect -> execute();
                                    $checkCollegeVersion =  $SQLSelect -> fetchAll(PDO::FETCH_ASSOC); 

                                    if(isset($checkCollegeVersion[0]['version'])){
                                        // print_r($checkCollegeVersion); exit;
                                        $version = $checkCollegeVersion[0]['version'];
                                        $SQLKSelect = $odbenterprise -> query("SELECT * FROM `knowledge_base` WHERE `version` = $version");
                                        $SQLKSelect -> execute();
                                        $knowledge =  $SQLKSelect -> fetchAll(PDO::FETCH_ASSOC); 
                                        
                                        $category = $subCategory = [];
                                        // Knowledge check and insert
                                        if(!empty($knowledge)){ 
                                            foreach ($knowledge as $key => $value) {
                                                $category[] = $value['category_id'];
                                                $subCategory[] = $value['sub_category_id'];
                                                $kID = $value['id'];
                                                $cID = $value['category_id'];
                                                $scID = $value['sub_category_id'];
                                                $vID = $value['version'];
                                                $content = $value['content'];
                                                $status = $value['status'];

                                                $SQLCKSelect = $odb -> query("SELECT * FROM `knowledge_base` WHERE `enterprise_id` = $kID");
                                                $SQLCKSelect -> execute();
                                                $Cknowledge =  $SQLCKSelect -> fetchAll(PDO::FETCH_ASSOC); 
                                                
                                                if(isset($Cknowledge[0]['id'])){
                                                   
                                                }else{
                                                    
                                                    $statement1 = $odb -> prepare("INSERT INTO `knowledge_base`(`enterprise_id`, `category_id`, `sub_category_id`, `version`, `college_id`, `content`, `status`, `created_at`, `updated_at`) VALUES (:enterprise_id, :category_id, :sub_category_id, :version ,:college_id,:content,:status, :created_at, :updated_at)");
                                            
                                                    $statement1 -> execute(array(':enterprise_id' => $kID,':category_id' => $cID,':sub_category_id' => $scID,':version' => $vID,':college_id' => $college_id,':content' => $content, ':status' => $status, ':created_at' => DATETIME, ':updated_at' => DATETIME));
                                                }

                                               

                                            }
                                            
                                            // print_r($subCategory); exit;
                                            // Category Insert
                                            if(!empty($category)){
                                            $category_ids = implode(",",$category);
                                            
                                                $SQLCSelect = $odbenterprise -> query("SELECT * FROM `category` WHERE `id` in ($category_ids)");
                                                $SQLCSelect -> execute();
                                                $cat =  $SQLCSelect -> fetchAll(PDO::FETCH_ASSOC); 

                                                foreach($cat as $key=>$value){

                                                    $cId = $value['id'];
                                                    $name = $value['name'];
                                                    $status = $value['status'];

                                                    $SQLCCSelect = $odb -> query("SELECT * FROM `category` WHERE `enterprise_id` = $cId");
                                                    $SQLCCSelect -> execute();
                                                    $Ccat =  $SQLCCSelect -> fetchAll(PDO::FETCH_ASSOC); 

                                                    if(isset($Ccat[0]['id'])){

                                                    }else{
                                                    $statement2 = $odb -> prepare("INSERT INTO `category`(`enterprise_id`, `name`, `status`, `college_id`, `created_at`, `updated_at`) VALUES (:enterprise_id, :name,:status,:college_id, :created_at, :updated_at)");
                                            
                                                    $statement2 -> execute(array(':enterprise_id' => $cID,':name' => $name, ':status' => $status,':college_id' => $college_id, ':created_at' => DATETIME, ':updated_at' => DATETIME));
                                                    }

                                                }



                                            }

                                            // Sub Category Insert
                                            if(!empty($subCategory)){
                                            $sub_category_ids = implode(",",$subCategory);
                                            $SQLSCSelect = $odbenterprise -> query("SELECT * FROM `sub_category` WHERE `id` in ($sub_category_ids)");
                                                $SQLSCSelect -> execute();
                                                $subCat =  $SQLSCSelect -> fetchAll(PDO::FETCH_ASSOC); 
                                                
                                                foreach($subCat as $key=>$value){
                                                    $scId = $value['id'];
                                                    $category_id = $value['category_id'];
                                                    $name = $value['name'];
                                                    $status = $value['status'];

                                                    $SQLCSCSelect = $odb -> query("SELECT * FROM `sub_category` WHERE `enterprise_id` = $scId");
                                                    $SQLCSCSelect -> execute();
                                                    $CScat =  $SQLCSCSelect -> fetchAll(PDO::FETCH_ASSOC); 

                                                    if(isset($CScat[0]['id'])){

                                                    }else{

                                                    $statement3 = $odb -> prepare("INSERT INTO `sub_category`(`enterprise_id`, `name`, `category_id`, `status`, `college_id`, `created_at`, `updated_at`) VALUES (:enterprise_id, :name,:category_id,:status,:college_id, :created_at, :updated_at)");
                                            
                                                    $statement3 -> execute(array(':enterprise_id' => $scId,':name' => $name,':category_id' => $category_id, ':status' => $status,':college_id' => $college_id, ':created_at' => DATETIME, ':updated_at' => DATETIME));
                                                    }

                                                }
                                                

                                            }
                                        }
                                        
                                    }

                                    $sucMsg = 'Knowledgebase imported successfully';
                                    $odb->commit();
                                    echo '<div class="message" id="message"><p><strong>SUCCESS: </strong>'.$sucMsg.'</p></div><meta http-equiv="refresh" content="3;url=knowledgebase.php">';

                                    // $odb->rollback();
                                }catch (\Exception $e) {
                                    if ($odb->inTransaction()) {
                                        $odb->rollback();
                                        // If we got here our two data updates are not in the database
                                    } 
                                    print_r($e->getMessage());
                                    echo '<div class="error" id="message"><p><strong>ERROR: </strong>Something went wrong</p></div>';
                                }

                            }

                    ?>
                    <!-- /BREADCRUMB -->
                    <div class="row layout-top-spacing">
                    
                        <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                            <div class="widget-content widget-content-area br-8">
                            <form action="" class = "form" method="GET">
                                <div class="table-responsive">
                               <table id="zero-config" class="table dt-table-hover tablenoscroll" style="width:100%">
                                    <thead>
                                        <tr>
                                            
                                            <th class="text-center col-md-4"> Content</th>
                                            <th class="text-center col-md-4"> Category</th>
                                            <th class="text-center col-md-4">Sub Category</th>
                                            <!-- <th class="text-center col-md-4"> Version</th> -->
                                            <th class="text-center col-md-2" scope="col">Created By</th>
                                            <th class="text-center col-md-3" scope="col">Date</th>
                                            <th class="text-center col-md-3" scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
    <?php
        if (isset($_GET['deleteId']))
        {
            $delete = $_GET['deleteId'];
            //foreach($deletes as $delete)
            //{
                $SQL = $odb -> prepare("DELETE FROM `news` WHERE `ID` = :id LIMIT 1");
                $SQL -> execute(array(':id' => $delete));
            //}
            echo '<div class="message" id="message"><p><strong>SUCCESS: </strong>The announcement has been deleted!</p></div>';
        }
       
                $SQLSelect = $odb -> query("SELECT knowledge_base.*, category.name as category_name,sub_category.name as sub_category_name from knowledge_base join sub_category on sub_category.enterprise_id = knowledge_base.sub_category_id
                 join category on category.enterprise_id = knowledge_base.category_id WHERE knowledge_base.college_id = $college_id ORDER BY `created_at` DESC");
                //    print_r($SQLSelect -> fetch(PDO::FETCH_ASSOC)); exit;
                $i = 0;
                $SQLSelect -> execute();
                $knowledgebase =  $SQLSelect -> fetchAll(PDO::FETCH_ASSOC); 
                if(!empty($knowledgebase)){ 
                    foreach ($knowledgebase as $key => $show) {
                        $titleShow = $show['content'];
                        $category_name = $show['category_name'];
                        $sub_category_name = $show['sub_category_name'];
                        $version = $show['version'];
                        
                        $rowID = $show['id'];
                        if(isset($show['created_by'])){
                            $postedby = 'College';
                        }else{
                            
                            $postedby = 'Root Capture';
                        }                       
                        
                        $date = date_format(date_create($show['date']),"m-d-Y, h:i:s");                         
                       
                        echo '<tr><td>'.$titleShow.'</td><td>'.$category_name.'</td><td>'.$sub_category_name.'</td><td>'.$postedby.'</td><td><center>'.$date.'</center></td>';
                        
                        if($postedby == 'College'){
                            echo'<td><center> <a class="btn btn-outline-warning mb-2 me-4" href="../admin/edit_knowledgebase.php?id='.$rowID.'">Edit</a> 
                            </center></td>';
                        }else{
                            echo'<td></td>';
                        }
                        
                        echo'</tr>';
                
                   }
                }else{
                    echo '<tr class=""><td valign="top" colspan="4" class="dataTables_empty" style="text-align:center;">There are no knowledgebase to display,</td></tr>';
                }
    ?>
                                    </tbody>
                                </table>
                            </div>
                            </div>
                            </form>
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
        
   
       
    </script>



    <!-- END PAGE LEVEL SCRIPTS -->
</body>
</html>