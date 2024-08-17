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
$courseId = $_GET['id'];
$pageTitle = 'Course';
require_once '../header.php';


$SQLSelect = $odb -> query("SELECT * from course_section WHERE course_id = $courseId");
//    print_r($SQLSelect -> fetch(PDO::FETCH_ASSOC)); exit;
$SQLSelect -> execute();
$getData =  $SQLSelect -> fetchAll(PDO::FETCH_ASSOC); 


?>
<style>
.cell-1 {
  border-collapse: separate;
  border-spacing: 0 4em;
  border-bottom: 5px solid transparent;
  /*background-color: gold;*/
  background-clip: padding-box;
  cursor: pointer;
}
.table-elipse {
  cursor: pointer;
}

#demo {
  -webkit-transition: all 0.3s ease-in-out;
  -moz-transition: all 0.3s ease-in-out;
  -o-transition: all 0.3s 0.1s ease-in-out;
  transition: all 0.3s ease-in-out;
}

.row-child {
  background-color: #000;
  color: #fff;
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
                                <li class="breadcrumb-item">Course System</li>
                                <li class="breadcrumb-item active" aria-current="page">Section</li>
                            </ol>
                                                        <br>
                            <!-- <a class="btn btn-outline-success btn-lrg" href="course_create.php" role="button">Create Section</a> -->
                            <!-- MODAL BOX START -->
                            <button type="button" class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#createSection">Create Section</button>

                                <!-- Modal -->
                                <div class="modal fade" id="createSection" role="dialog">
                                <div class="modal-dialog">
                                
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                    <div class="modal-header">
                                    <button type="button" id="closeSection" class="btn btn-sm btn-danger" data-bs-dismiss="modal">X</button>
                                        <h4 class="modal-title">Create Section</h4>
                                    </div>
                                    <form method="POST" action="">
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group ">
                                                        <label for="title">Title</label>
                                                        <input type="text" name="section_title" class="form-control mb-3" placeholder="Title">
                                                        
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group ">
                                                        <label for="description">Description</label>
                                                        <textArea name="description" class="form-control mb-3"></textArea>
                                                        <!-- <input type="text" name="description" class="form-control mb-3" placeholder="Title"> -->
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                                <input type="submit" name="submitSection" class="btn btn-outline-success btn-lrg">
                                        </div>
                                    </form>
                                    </div>
                                    
                                </div>
                                </div>
                            <!-- MODAL BOX END -->
                           
                        </nav>
                    </div>
                    <?php 
                        if (isset($_POST['submitSection']))
                        {
                            // print_r($_POST); exit;
                                $course_id = $_GET['id'];
                                $title=$_POST['section_title'];
                                $description=$_POST['description'];
                                $enterprise_id = $college_id.date("YmdHis");
                                $errors = array();
                                if (empty($description) || empty($title) || empty($course_id))
                                {
                                    $errors[] = 'Please verify all fields';
                                }
                                if (empty($errors))
                                {   
                                    try
                                    {              
                                        $sql = "INSERT INTO `course_section`(`enterprise_id`, `course_id`, `title`, `description`, `created_by`, `created_at`, `updated_at`) VALUES (?,?,?,?,?,?,?)";
                                        $stmt= $odb->prepare($sql);
                                        $stmt->execute([$enterprise_id,$course_id,$title,$description,$_SESSION['ID'],DATETIME,DATETIME ]);
                            
                                    } 
                                    catch (PDOException $e)
                                    {
                                        $errors[] = "DataBase Error: The Course could not be added.<br>".$e->getMessage();
                                        // $errors[] = $e;
                                    } catch (Exception $e) 
                                    {
                                        $errors[] = "General Error: The Course could not be added.<br>".$e->getMessage();
                                    }       
                                }

                            
                                if(empty($errors)) {
                                    echo '<div class="message" id="message"><p><strong>SUCCESS: The Section has been added! You are now being redirected to the Listing.</strong></div><meta http-equiv="refresh" content="2;url=../admin/course_section.php?id='.$course_id.'">';
                                    
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


                            if(isset($_POST['updateSection'])){
                                // print_r($_POST); exit;
                                $section_id = $_POST['section_id'];
                                $title=$_POST['section_title'];
                                $description=$_POST['description'];
                                $errors = array();
                                if (empty($description) || empty($title) || empty($section_id))
                                {
                                    $errors[] = 'Please verify all fields';
                                }
                                if (empty($errors))
                                {   
                                    try
                                    {              
                                        $SQLupdate = $odb -> prepare("UPDATE course_section SET `title` = :title, `description` = :description WHERE id = :id");
				                        $SQLupdate -> execute(array(':title' => $title, ':description' => $description, ':id' => $section_id));    
                            
                                    } 
                                    catch (PDOException $e)
                                    {
                                        $errors[] = "DataBase Error: The Course could not be added.<br>".$e->getMessage();
                                        // $errors[] = $e;
                                    } catch (Exception $e) 
                                    {
                                        $errors[] = "General Error: The Course could not be added.<br>".$e->getMessage();
                                    }       
                                }

                            
                                if(empty($errors)) {
                                    echo '<div class="message" id="message"><p><strong>SUCCESS: The Section has been updated! You are now being redirected to the Listing.</strong></div><meta http-equiv="refresh" content="2;url=../admin/course_section.php?id='.$courseId.'">';
                                    
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
                      
                    <!-- /BREADCRUMB -->
                    <div class="row layout-top-spacing">
                    
                        <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                            <div class="widget-content widget-content-area br-8">
                           
                                <div class="table-responsive">
                                
                                            <!-- TABLE START -->
                                            <table id="zero-config" class="table  dt-table-hover table-bordered table-sm tablenoscroll ">
                                                <thead class="thead-dark">
                                                    <tr>
                                                        <!-- <th class="text-center col-md-1">-</th> -->
                                                        <th class="text-center col-md-4">Section</th>
                                                        <th class="text-center col-md-4">Description</th>
                                                        <th class="text-center col-md-4">Created Date</th>
                                                        <th class="text-center col-md-4">Action</th>
                                                    </tr>
                                                </thead>
                                                <?php
                                                foreach ($getData as $key => $value) {
                                                     $rowID = $value['id'];
                                                    ?>
                                                    <!-- MODAL BOX START -->

                                                        <!-- Modal -->
                                                        <div class="modal fade" id="createEditSection<?=$key?>" role="dialog">
                                                        <div class="modal-dialog">

                                                            <!-- Modal content-->
                                                            <div class="modal-content">
                                                            <div class="modal-header">
                                                            <button type="button" id="closeSection" class="btn btn-sm btn-danger" data-bs-dismiss="modal">X</button>
                                                                <h4 class="modal-title">Edit Section</h4>
                                                            </div>
                                                            <form method="POST" action="">
                                                                <div class="modal-body">
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <div class="form-group ">
                                                                                <label for="title">Title</label>
                                                                                <input type="hidden" name="section_id" value="<?=$value['id']?>" class="form-control mb-3" placeholder="Title">
                                                                                <input type="text" name="section_title" value="<?=$value['title']?>" class="form-control mb-3" placeholder="Title">
                                                                                
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-12">
                                                                            <div class="form-group ">
                                                                                <label for="description">Description</label>
                                                                                <textArea name="description" class="form-control mb-3"><?=$value['description']?></textArea>
                                                                                <!-- <input type="text" name="description" class="form-control mb-3" placeholder="Title"> -->
                                                                                
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                        <input type="submit" name="updateSection" value="Update" class="btn btn-outline-success btn-lrg">
                                                                </div>
                                                            </form>
                                                            </div>
                                                            
                                                        </div>
                                                        </div>
                                                        <!-- MODAL BOX END -->
                                                <tbody>
                                                    <tr>
                                                        <!-- <td>  <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#group-of-rows-<?=$key?>"  aria-controls="group-of-rows-<?=$key?>"> &#8964;</button></td> -->
                                                        <td><?=$value['title']?></td>
                                                        <td><?=$value['description']?></td>
                                                        <td><?=$value['created_at']?></td>
                                                        <td> <button type="button" class="btn btn-outline-warning mb-2" data-bs-toggle="modal" data-bs-target="#createEditSection<?=$key?>">Edit</button>
                                                        <a class="btn btn-outline-success mb-2 me-4" href="../admin/course_chapter.php?id=<?=$rowID?>">Chapter</a> 
                                                    </td>
                                                        
                                                    </tr>
                                                </tbody>
                                                <!-- <tbody id="group-of-rows-<?=$key?>" class="collapse">
                                                    
                                                    <tr >
                                                        <td><i class="fa fa-folder-open"></i> child row</td>
                                                        <td>data 1</td>
                                                        <td>data 1</td>
                                                        <td>data 1</td>
                                                    </tr>
                                                    
                                                </tbody> -->
                                                <?php } ?>
                                            </table>
                                            <!-- TABLE END -->
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

    <?php  require_once '../footer.php'; ?>
    
    <style>
            tbody.collapse.in {
            display: table-row-group;
            }
    </style>
    <script>
    $(document).on('click','#closeSection', function() {
            $('#createSection').modal('hide');
        });
    </script>

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