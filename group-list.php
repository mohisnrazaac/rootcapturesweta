<?php
ob_start();
require_once 'includes/db.php';
require_once 'includes/init.php';
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

$loggedUserId    = $_SESSION['ID'];
if(isset($_GET['group_id']) && $_GET['group_id']>0){
    $SQL = $odb -> prepare("DELETE FROM `chat_group` WHERE `group_id` = :group_id LIMIT 1");
    $SQL -> execute(array(':group_id' => $_GET['group_id']));
}
$grouplist     = $odb -> query("SELECT * FROM `chat_group` WHERE created_by =  $loggedUserId")->fetchAll();


$pageTitle = 'Group List';
require_once 'header.php';

?>
    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container" id="container">

        <div class="overlay"></div>
        <div class="search-overlay"></div>

        <!--  BEGIN SIDEBAR  --> 
        <?php include 'sidebar.php'; ?>
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
                                <li class="breadcrumb-item active" aria-current="page">Group List</li>
                            </ol>
                            <br>
                            <a class="btn btn-outline-success btn-lrg" href="group.php" role="button">Create A New Group</a>

                        </nav>
                    </div>
                    <!-- /BREADCRUMB -->
                    <div class="row layout-top-spacing">
                        <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                            <div class="widget-content widget-content-area br-8 ">
                                <div class="center-block fix-width scroll-inner">
                              
                                <table id="zero-config" class="table dt-table-hover" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th scope="col">Group Name</th>
                                            <th class="text-center" scope="col">Group Members</th>
                            				<th class="text-center" scope="col">User Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(!empty($grouplist)){ ?>
                                            <?php foreach ($grouplist as $key => $value) {
                                                    $group_members = explode(",", $value['group_members']);
                                             ?>
                                                <tr>
                                                    <td><?php echo $value['group_name']; ?></td>
                                                    <td>
                                                        <?php if(!empty($group_members)){ ?>
                                                            <?php for ($i=0; $i < count($group_members) ; $i++) { 
                                                                $member = $user->userInfo($odb,$group_members[$i]);
                                                            ?>
                                                                <span class="badge badge-primary"><?php echo $member['username']; ?></span>                                                                
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </td>
                                                    <td>
                                                        <a href="group.php?group_id=<?php echo $value['group_id']; ?>">
                                                            <button class="btn btn-outline-warning  mb-2 me-4">Edit</button>
                                                        </a> 
                                                        <a href="javascript:void(0)" class="delete_group" group_id="<?php echo $value['group_id']; ?>">
                                                            <button class="btn btn-outline-danger mb-2 me-4" name="deleteBtn" value="'.$id.'" role="button" type="submit">Delete</button>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        <?php } ?>			
                                    </tbody>
								</table>
                            </div>
                            
                            </div>
							<br>
							<!-- <center><form action = "" method="post" class="form"><input type="submit" value="Refresh The Cyber Range" name="refreshCyberRange" class="btn btn-outline-danger btn-lrg" /></form></center> -->
                        </div>
                    </div>
                </div>

            </div>

            <!--  BEGIN FOOTER  --> <?php require_once 'includes/footer-section.php'; ?>
            <!--  END FOOTER  -->
            <!--  END CONTENT AREA  -->
        </div>
        <!--  END CONTENT AREA  -->
    </div>
    <!-- END MAIN CONTAINER -->
    <?php  require_once 'footer.php'; ?>
    
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

        $(document).on("click",".delete_group",function(){
            var group_id = $(this).attr("group_id");
            if (confirm("Confirm to delete this group") == true) {
                window.location.href = "?group_id="+group_id;
            }
        })

    </script>
    <!-- END PAGE LEVEL SCRIPTS -->

</body>
</html>