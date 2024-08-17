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

$getUserDetailIdWise = $user->getUserDetailIdWise($odb); 
$college_id = $getUserDetailIdWise['college_id']; 
$pageTitle = 'Category';
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
                                <li class="breadcrumb-item">Cyber Range</li>
                                <li class="breadcrumb-item active" aria-current="page">Category</li>
                            </ol>
                                                        <br>
                            <!-- <a class="btn btn-outline-success btn-lrg" href="#" role="button">Create Category</a> -->
                        </nav>
                    </div>
                    <!-- /BREADCRUMB -->
                    <div class="row layout-top-spacing">
                    
                        <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                            <div class="widget-content widget-content-area br-8">
                            <form action="" class = "form" method="GET">
                                <div class="table-responsive">
                               <table id="zero-config" class="table dt-table-hover tablenoscroll" style="width:100%">
                                    <thead>
                                        <tr>
                                            
                                            <th class="text-center col-md-4"> Name</th>
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
       
                $SQLSelect = $odb -> query("SELECT * from category WHERE college_id = $college_id ORDER BY `created_at` DESC");
                //    print_r($SQLSelect -> fetch(PDO::FETCH_ASSOC)); exit;
                $i = 0;
                $SQLSelect -> execute();
                $category =  $SQLSelect -> fetchAll(PDO::FETCH_ASSOC); 
                if(!empty($category)){ 
                    foreach ($category as $key => $show) {
                        $titleShow = $show['name'];
                        
                        $rowID = $show['ID'];
                        if(isset($show['enterprise_id'])){
                            $postedby = 'Root Capture';
                        }else{
                            $postedby = 'College';
                        }                       
                        
                        $date = date_format(date_create($show['date']),"m-d-Y, h:i:s");                         
                       
                        echo '<tr><td>'.$titleShow.'</td><td>'.$postedby.'</td><td><center>'.$date.'</center></td>';
                        echo'<td></td>';
                        // echo'<td><center> <a class="btn btn-outline-warning mb-2 me-4" href="../admin/editnews.php?id='.$rowID.'">Edit</a> <a class="btn btn-outline-danger mb-2 me-4" href="../admin/news.php?deleteId='.$rowID.'" >Delete</a> </center></td>';
                        echo'</tr>';
                
                   }
                }else{
                    echo '<tr class=""><td valign="top" colspan="4" class="dataTables_empty" style="text-align:center;">There are no category to display,</td></tr>';
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