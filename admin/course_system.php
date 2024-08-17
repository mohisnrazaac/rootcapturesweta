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
$pageTitle = 'Course';
$LoginUser = $_SESSION['ID'];
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
if(isset($tsSetting[0]['course_freeze'])){
    $checkFreeze = $tsSetting[0]['course_freeze'];
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
                                <li class="breadcrumb-item">Course System</li>
                                <li class="breadcrumb-item active" aria-current="page">Course</li>
                            </ol>
                                                        <br>
                            <a class="btn btn-outline-success btn-lrg" href="course_create.php" role="button">Create  Course</a>
                            <?php
                            if($checkFreeze == 0){
                                ?>
                            <form action="" style="width: fit-content;display: contents;" method="POST">
                            <button type="submit" class="btn btn-outline-primary btn-lrg" name="import" onclick="" role="button">Import Course</button>
                            </form>
                            <?php } ?>
                        </nav>
                    </div>

                    <?php
                            if(isset($_POST['import'])){
                                try{

                                    $odb->beginTransaction();
                                    // echo $college_id; exit;
                                    // $SQLSelect = $odbenterprise -> query("SELECT * from college WHERE id = $college_id");
                                    $SQLSelect = $odbenterprise -> query("SELECT * from college WHERE access_code = '$accessToken'");
                                    //    print_r($SQLSelect -> fetch(PDO::FETCH_ASSOC)); exit;
                                    $SQLSelect -> execute();
                                    $checkCollegeVersion =  $SQLSelect -> fetchAll(PDO::FETCH_ASSOC); 

                                    if(isset($checkCollegeVersion[0]['version'])){
                                        // print_r($checkCollegeVersion); exit;
                                        $version = $checkCollegeVersion[0]['version'];
                                        $SQLKSelect = $odbenterprise -> query("SELECT * FROM `course` WHERE `version` = $version");
                                        $SQLKSelect -> execute();
                                        $knowledge =  $SQLKSelect -> fetchAll(PDO::FETCH_ASSOC); 
                                        
                                        $category = $subCategory = $courseId = [];
                                        // Knowledge check and insert
                                        if(!empty($knowledge)){ 
                                            foreach ($knowledge as $key => $value) {
                                                $category[] = $value['course_category_id'];
                                                $courseId[] = $value['id'];
                                                $cID = $value['course_category_id'];
                                                $kID = $value['id'];
                                                $vID = $value['version'];
                                                $content = $value['content'];
                                                $course_level = $value['course_level'];
                                                $course_title = $value['course_title'];
                                                $status = $value['status'];

                                                $SQLCKSelect = $odb -> query("SELECT * FROM `course` WHERE `enterprise_id` = $kID");
                                                $SQLCKSelect -> execute();
                                                $Cknowledge =  $SQLCKSelect -> fetchAll(PDO::FETCH_ASSOC); 
                                                
                                                if(isset($Cknowledge[0]['id'])){
                                                   
                                                }else{
                                                    
                                                    $statement1 = $odb -> prepare("INSERT INTO `course`(`enterprise_id`, `college_id`, `course_title`, `course_category_id`, `course_level`, `version`, `content`, `status`, `created_by`, `created_at`, `updated_at`) VALUES (:enterprise_id,:college_id,:course_title,:category_id, :course_level, :version ,:content,:status,:created_by, :created_at, :updated_at)");
                                            
                                                    $statement1 -> execute(array(':enterprise_id' => $kID,':college_id' => $college_id,':course_title' => $course_title,':category_id' => $cID,':course_level' => $course_level,':version' => $vID,':content' => $content, ':status' => $status,':created_by'=> $LoginUser,':created_at' => DATETIME, ':updated_at' => DATETIME));

                                                    $lastID = $odb -> lastInsertID();
                                                    //Section & chapter
                                                    if($lastID){
                                                        $SQLSSelect = $odbenterprise -> query("SELECT * FROM `course_section` WHERE `course_id` = $kID");
                                                        $SQLSSelect -> execute();
                                                        $sectionData =  $SQLSSelect -> fetchAll(PDO::FETCH_ASSOC); 
                                                        foreach($sectionData as $k=>$v){

                                                            $secId = $v['id'];
                                                            $sectiont = $v['section'];
                                                            $description = $v['description'];

                                                            $statement3 = $odb -> prepare("INSERT INTO `course_section`(`enterprise_id`, `course_id`, `title`, `description`, `created_by`, `created_at`, `updated_at`) VALUES (:enterprise_id, :course_id,:title,:description,:created_by, :created_at, :updated_at)");
                                            
                                                            $statement3 -> execute(array(':enterprise_id' => $secId,':course_id' => $lastID, ':title' => $sectiont,':description' => $description,':created_by'=> $LoginUser, ':created_at' => DATETIME, ':updated_at' => DATETIME));

                                                            $lastSecID = $odb -> lastInsertID();
                                                            if($lastSecID){
                                                                echo "chapter";
                                                                $SQLChSelect = $odbenterprise -> query("SELECT * FROM `course_section_chapter` WHERE `section_id` = $secId");
                                                                $SQLChSelect -> execute();
                                                                $chapterData =  $SQLChSelect -> fetchAll(PDO::FETCH_ASSOC); 
                                                                foreach($chapterData as $ck=>$cv){
                                                                    $chapId = $cv['id'];
                                                                    $section_id = $cv['section_id'];
                                                                    $ctitle = $cv['title'];
                                                                    $cdescription = $cv['description'];
                                                                    
                                                                    
                                                                    $statement4 = $odb -> prepare("INSERT INTO `course_chapter`(`enterprise_id`, `section_id`, `title`, `description`, `status`, `created_by`, `created_at`, `updated_at`) VALUES (:enterprise_id, :section_id,:title,:description,:status,:created_by, :created_at, :updated_at)");
                                            
                                                                    $statement4 -> execute(array(':enterprise_id' => $chapId,':section_id' => $lastSecID, ':title' => $ctitle,':description' => $cdescription,':status'=>1,':created_by'=> $LoginUser, ':created_at' => DATETIME, ':updated_at' => DATETIME));
                                                                }
                                                            }

                                                            
                                                        }
                                                        
                                                    }
                                                }

                                               

                                            }
                                            
                                            // print_r($subCategory); exit;
                                            // Category Insert
                                            if(!empty($category)){
                                            $category_ids = implode(",",$category);
                                            
                                                $SQLCSelect = $odbenterprise -> query("SELECT * FROM `course_category` WHERE `id` in ($category_ids)");
                                                $SQLCSelect -> execute();
                                                $cat =  $SQLCSelect -> fetchAll(PDO::FETCH_ASSOC); 

                                                foreach($cat as $key=>$value){

                                                    $cId = $value['id'];
                                                    $name = $value['name'];
                                                    $status = $value['status'];

                                                    $SQLCCSelect = $odb -> query("SELECT * FROM `course_category` WHERE `enterprise_id` = $cId");
                                                    $SQLCCSelect -> execute();
                                                    $Ccat =  $SQLCCSelect -> fetchAll(PDO::FETCH_ASSOC); 

                                                    if(isset($Ccat[0]['id'])){

                                                    }else{
                                                    $statement2 = $odb -> prepare("INSERT INTO `course_category`(`enterprise_id`, `name`, `college_id`, `status`, `created_at`, `updated_at`) VALUES (:enterprise_id, :name,:college_id,:status, :created_at, :updated_at)");
                                            
                                                    $statement2 -> execute(array(':enterprise_id' => $cID,':name' => $name, ':status' => $status,':college_id' => $college_id, ':created_at' => DATETIME, ':updated_at' => DATETIME));
                                                    }

                                                }



                                            }

                                            

                                        }
                                        
                                    }

                                    $sucMsg = 'Course system imported successfully';
                                    $odb->commit();
                                    echo '<div class="message" id="message"><p><strong>SUCCESS: </strong>'.$sucMsg.'</p></div><meta http-equiv="refresh" content="3;url=course_system.php">';

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
                                        <th class="text-center col-md-4">Title</th>
                                            <th class="text-center col-md-4"> Content</th>
                                            <th class="text-center col-md-4"> Category</th>
                                            <th class="text-center col-md-4"> Level</th>
                                            <th class="text-center col-md-2" scope="col">Created By</th>
                                            <th class="text-center col-md-3" scope="col">Date</th>
                                            <th class="text-center col-md-3" scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
    <?php
        
       
                $SQLSelect = $odb -> query("SELECT course.*, course_category.name as category_name from course
                 join course_category on course_category.enterprise_id = course.course_category_id WHERE course.college_id = $college_id ORDER BY course.created_at DESC");
                //    print_r($SQLSelect -> fetch(PDO::FETCH_ASSOC)); exit;
                $i = 0;
                $SQLSelect -> execute();
                $courseSystem =  $SQLSelect -> fetchAll(PDO::FETCH_ASSOC); 
                if(!empty($courseSystem)){ 
                    foreach ($courseSystem as $key => $show) {
                        // print_r($show); exit;
                        $title = $show['course_title'];
                        $contents = $show['content'];
                        $category_name = $show['category_name'];
                        $course_level = $show['course_level'];
                        if($course_level ==1){
                            $level = "Beginner";
                        }elseif($course_level == 2){
                            $level = "Intermediate";
                        }else{
                            $level = "Expert";
                        }
                        
                        $rowID = $show['id'];
                        if(isset($show['created_by'])){
                            $postedby = 'College';
                        }else{
                            
                            $postedby = 'Root Capture';
                        }                       
                        
                        $date = date_format(date_create($show['date']),"m-d-Y, h:i:s");                         
                       
                        echo '<tr><td>'.$title.'</td><td>'.$contents.'</td><td>'.$category_name.'</td><td>'.$level.'</td><td>'.$postedby.'</td><td><center>'.$date.'</center></td>';
                        
                        if($postedby == 'College'){
                            echo'<td><center> <a class="btn btn-outline-warning mb-2 me-4" href="../admin/course_edit.php?id='.$rowID.'">Edit</a> 
                            <a class="btn btn-outline-success mb-2 me-4" href="../admin/course_section.php?id='.$rowID.'">Section</a> 
                            </center></td>';
                            
                        }else{
                            echo'<td></td>';
                        }
                        
                        echo'</tr>';
                
                   }
                }else{
                    echo '<tr class=""><td valign="top" colspan="4" class="dataTables_empty" style="text-align:center;">There are no Course to display,</td></tr>';
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