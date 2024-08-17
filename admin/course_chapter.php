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
$sectionId = $_GET['id'];
$pageTitle = 'Course';
require_once '../header.php';


$SQLSelect = $odb -> query("SELECT * from course_chapter WHERE section_id = $sectionId");
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
                                <li class="breadcrumb-item active" aria-current="page">Chapter</li>
                            </ol>
                                                        <br>
                            <!-- <a class="btn btn-outline-success btn-lrg" href="course_create.php" role="button">Create Section</a> -->
                            <a class="btn btn-outline-success mb-2 me-4" href="../admin/course_chapter_add.php?id=<?=$sectionId?>">Add Chapter</a> 
                            
                           
                        </nav>
                    </div>
                    
                      
                    <!-- /BREADCRUMB -->
                    <div class="row layout-top-spacing">
                    
                        <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                            <div class="widget-content widget-content-area br-8">
                           
                                <div class="table-responsive">
                                
                                            <!-- TABLE START -->
                                            <table class="table  dt-table-hover table-bordered table-sm ">
                                                <thead class="thead-dark">
                                                    <tr>
                                                        
                                                        <th class="text-center col-md-4">Title</th>
                                                        <th class="text-center col-md-4">Description</th>
                                                        <th class="text-center col-md-4">Created Date</th>
                                                        <th class="text-center col-md-4">Action</th>
                                                    </tr>
                                                </thead>
                                                <?php
                                                foreach ($getData as $key => $value) {
                                                     $rowID = $value['id'];
                                                    ?>
                                                    
                                                <tbody>
                                                    <tr>
                                                       
                                                        <td><?=$value['title']?></td>
                                                        <td><?=$value['description']?></td>
                                                        <td><?=$value['created_at']?></td>
                                                        <td> <a class="btn btn-outline-success mb-2 me-4" href="../admin/course_chapter_edit.php?id=<?=$rowID?>&section_id=<?=$sectionId?>">Edit</a> 
                                                        
                                                    </td>
                                                        
                                                    </tr>
                                                </tbody>
                                              
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