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

if ($user -> isBlueTeam($odb) || $user -> isAssist($odb) || $user -> isAdmin($odb)) {

} else {
	header('location: ../index.php');
	die();
}

$pageTitle = 'Asset IP List';
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
<br>
<br>
<br>
                    <!-- BREADCRUMB -->
                    <div class="page-meta">
                        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">Cyber Range</li>
                                <li class="breadcrumb-item active" aria-current="page">Asset IP List</li>
                            </ol>
                        </nav>
                    </div>
                    <!-- /BREADCRUMB -->
                    <div class="row layout-top-spacing">
                    
                        <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                            <div class="widget-content widget-content-area br-8">
                            	<div class="table-responsive">
                                <table id="zero-config" class="table dt-table-hover tablenoscroll" style="width:100%">
                                    <thead>
                                        <tr>
                <th scope="col" class="col-md-2">System Name</th>
                <th class="text-center col-md-2" scope="col">System IP</th>
				<th class="text-center col-md-2" scope="col">Operating System</th>
				<th class="text-center col-md-3" scope="col">System Team Assignment</th>
				<th class="text-center col-md-3" scope="col">System Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
						<?php
		if (isset($_GET['deleteId']))
		{
			$delete = $_GET['deleteId'];
			//foreach($deletes as $delete)
			//{
				$SQL = $odb -> prepare("DELETE FROM `bluepho` WHERE `ID` = :id LIMIT 1");
				$SQL -> execute(array(':id' => $delete));
			//}
			echo '<div class="message" id="message"><p><strong>SUCCESS: </strong>The asset has been deleted!</p></div>';
		}
		?>
				<?php
				$SQLGetAssets = $odb -> query("SELECT ID,name,ip,os,team,url FROM `bluepho`");
				$vulnenR = $SQLGetAssets -> fetchAll(PDO::FETCH_ASSOC);
	            if(!empty($vulnenR)){
		                foreach ($vulnenR as $key => $getInfo) {					
						$name = htmlspecialchars($getInfo['name']);
						$ip = $getInfo['ip'];
						$os = $getInfo['os'];
						$teamassignment =  $getInfo['team'];
						$url = $getInfo['url'];
						$id = $getInfo['ID'];
						
						if($teamassignment == 1) {
							$teamassignment = '<span class="badge badge-light-danger">Red Team</span>';
						} elseif($teamassignment == 2) {
							$teamassignment = '<span class="badge badge-light-info">Blue Team</span>';
						}
						elseif($teamassignment == 3){
							$teamassignment = '<span class="badge badge-light-secondary">Purple Team</span>';
						}
						
						if($os == 1) {
							$os = '<span class="badge outline-badge-info mb-2 me-4">Windows</span>';
						} elseif($os == 2) {
							$os = '<span class="badge outline-badge-success mb-2 me-4">Kali Linux</span>';
						}
						elseif($os == 3){
							$os = '<span class="badge outline-badge-primary mb-2 me-4">MacOS</span>';
						}
						elseif($os == 4){
							$os = '<span class="badge outline-badge-warning mb-2 me-4">Android</span>';
						}
						elseif($os == 5){
							$os = '<span class="badge outline-badge-dark mb-2 me-4">iPhone</span>';
						}
						elseif($os == 6) {
							$os = '<span class="badge outline-badge-success mb-2 me-4">Fedora Linux</span>';
						}
						elseif($os == 7) {
							$os = '<span class="badge outline-badge-success mb-2 me-4">Dracos Linux</span>';
						}
						elseif($os == 8) {
							$os = '<span class="badge outline-badge-success mb-2 me-4">Parrot Linux</span>';
						}
						elseif($os == 9) {
							$os = '<span class="badge outline-badge-success mb-2 me-4">BackBox Linux</span>';
						}
						elseif($os == 10) {
							$os = '<span class="badge outline-badge-success mb-2 me-4">CyborgHawk Linux</span>';
						}
						echo '<tr><td>' . $name  . '</td><td><center>' . $ip . '</center></td><td><center>' . $os .'</center></td><td><center>' . $teamassignment . '</center></td><td><center><a class="btn btn-outline-success mb-2 me-4" href="'.$url.'" role="button">Enter System</a></center>'; 
						if ($user -> isAdmin($odb)) {

						echo '<center><a class="btn btn-outline-warning mb-2 me-4" href="../admin/editsys.php?id='.$id.'" role="button">Edit System</a><a class="btn btn-outline-danger mb-2 me-4" href="../blue/vulphon.php?deleteId='.$id.'" name="deleteBtn" value="'.$id.'" role="button" type="submit">Delete</a></center></td></tr>'; } else { echo '</td></tr>';
						}
					}
				}else{
					echo '<tr class=""><td valign="top" colspan="5" class="dataTables_empty" style="text-align:center;">There are currently no vulnerable phones  in list,</td></tr>';
				}
				?>
                                    </tbody>
                                </table>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!--  BEGIN FOOTER  -->  <?php require_once '../includes/footer-section.php'; ?> <!--  END FOOTER  -->
            <!--  END CONTENT AREA  -->
        </div>
        <!--  END CONTENT AREA  -->
    </div>
    <!-- END MAIN CONTAINER --> <?php  require_once '../footer.php'; ?>
    <script>
        $('#zero-config').DataTable({
            "dom": "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l><'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>>>" +
        "<'table-responsive'tr>" +
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