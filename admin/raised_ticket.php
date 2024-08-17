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
	header('location: login.php');
	die();
}
if (!($user -> isAdmin($odb)) && !($user -> isAssist($odb)) )
{
	die('You are not admin');
}

$pageTitle = 'My Support Tickets';
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
                                <li class="breadcrumb-item">Support Tickets</li>
                                <li class="breadcrumb-item active" aria-current="page">Raised Tickets</li>
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
                        <td class="col-md-3">Raised By</td>
                        <td class="col-md-6">Ticket Title</td>						
						<td class="col-md-3">Ticket status</td>
                    </tr>
                </thead>
                                    <tbody>
				<?php
				$SQLGetTickets = $odb -> prepare("SELECT users.username, 
														tickets.ticketID, 
														tickets.ticketTitle, 
														tickets.ticketStatus
													FROM `tickets`
													INNER JOIN `users` ON tickets.userID = users.ID
													WHERE 
														tickets.ticketStatus = 1 AND userID != 1 AND userID != 2");
				$SQLGetTickets -> execute();
				while($getInfo = $SQLGetTickets -> fetch(PDO::FETCH_ASSOC))
				{
					$username = $getInfo['username'];
					$ticketID = $getInfo['ticketID'];
					$title = htmlspecialchars($getInfo['ticketTitle']);
					// $lastResponseTime = $getInfo['lastResponseTime'];
					// $responseDate = date('d/m/y H:i', $lastResponseTime);
                    $status = $getInfo['ticketStatus'];

                    if($status == 1) {
						$ticketStatus = '<span class="badge badge-light-success">Open; In Review</span>';
					} else {
						$ticketStatus = '<span class="badge badge-light-danger">Closed</span>';
					}
					
					echo '<tr><td>' . $username . '</td><td> <a href="../viewTicket.php?id=' . $ticketID . '">' . $title . '</a></td><td> <center> '.$ticketStatus.' </center> </td></tr>';
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

            <!--  BEGIN FOOTER  --> <?php require_once '../includes/footer-section.php'; ?> <!--  END FOOTER  -->
            <!--  END CONTENT AREA  -->
        </div>
        <!--  END CONTENT AREA  -->
    </div>
    <!-- END MAIN CONTAINER -->
    
    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <?php  require_once '../footer.php'; ?>
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