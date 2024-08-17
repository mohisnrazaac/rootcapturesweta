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
if (!($user -> isAdmin($odb)) && !$user->isAssist($odb))
{
	die('You are not admin or admin assistant');
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
                                <li class="breadcrumb-item active" aria-current="page">My Support Tickets</li>
                            </ol>
							<br>
							<a class="btn btn-outline-success btn-lrg" href="../add.php" role="button">Create A New Ticket</a>
                            <div class="inlineCls textLeft">
                            <form action="" id="searchform" method="GET">
                            <div class="inlineCls">
                                <select name="filterby" id="filterby" class="form-control selctboxCu">
                                    <option value="all" <?php if($_GET['filterby'] == 'all'){ echo 'selected'; }?>>All</option>
                                    <option value="open" <?php if($_GET['filterby'] == 'open'){ echo 'selected'; }?>>Open</option>
                                    <option value="closed" <?php if($_GET['filterby'] == 'closed'){ echo 'selected'; }?>>Closed</option>
                                </select>
                            </div>
                            <!-- <div class="inlineCls">
                                <input type="submit"  value="Filter" name="filter" class="btn btn-outline-success btn-lrg">
                            </div> -->
                            </form>
                            </div>
                        </nav>
                    </div>
                    <!-- /BREADCRUMB -->
                    <div class="row layout-top-spacing">
                    
                        <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                            <div class="widget-content widget-content-area br-8">
                                <div class="table-responsive">
                                <table id="zero-config" class="table dt-table-hover custom-btn customWidthTable  tablenoscroll" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="col-md-3">Owner</th>
                                            <th class="col-md-6">Ticket Title</th>						
                    						<th class="col-md-3">Ticket status</th>
                                            <th class="col-md-3">Last Response</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                            				<?php
                            				$SQLGetTickets = $odb -> prepare("SELECT users.username, 
                            														tickets.ticketID, 
                            														tickets.ticketTitle, 
                            														tickets.ticketStatus, 
                            														(SELECT ticketResponses.time FROM `ticketResponses` WHERE ticketResponses.ticketID = tickets.ticketID ORDER BY ticketResponses.time DESC LIMIT 1) AS lastResponseTime
                            													FROM `tickets`
                            													INNER JOIN `users` ON tickets.userID = users.ID AND users.college_id = $college_id");
                                            if( isset($_GET['filterby']) ) {

                                                $filterby = $_GET['filterby'];
                                                if($filterby == 'open'){
                                                    $SQLGetTickets = $odb -> prepare("SELECT users.username, 
                                                                                    tickets.ticketID, 
                                                                                    tickets.ticketTitle, 
                                                                                    tickets.ticketStatus, 
                                                                                    (SELECT ticketResponses.time FROM `ticketResponses` WHERE ticketResponses.ticketID = tickets.ticketID ORDER BY ticketResponses.time DESC LIMIT 1) AS lastResponseTime
                                                                                FROM `tickets`
                                                                                INNER JOIN `users` ON tickets.userID = users.ID WHERE `ticketStatus` = 1 AND users.college_id = $college_id");
                                                } else if($filterby == 'closed') {
                                                    $SQLGetTickets = $odb -> prepare("SELECT users.username, 
                                                                                    tickets.ticketID, 
                                                                                    tickets.ticketTitle, 
                                                                                    tickets.ticketStatus, 
                                                                                    (SELECT ticketResponses.time FROM `ticketResponses` WHERE ticketResponses.ticketID = tickets.ticketID ORDER BY ticketResponses.time DESC LIMIT 1) AS lastResponseTime
                                                                                FROM `tickets`
                                                                                INNER JOIN `users` ON tickets.userID = users.ID WHERE `ticketStatus` = 0 AND users.college_id = $college_id");
                                                } 
                                            }
                                            
                            				$SQLGetTickets -> execute();
                                            $ticketR = $SQLGetTickets -> fetchAll(PDO::FETCH_ASSOC);
                            				if(!empty($ticketR)){
                                                foreach ($ticketR as $key => $getInfo) {
                                					$username = $getInfo['username'];
                                					$ticketID = $getInfo['ticketID'];
                                					$title = htmlspecialchars($getInfo['ticketTitle']);
                                					$lastResponseTime = $getInfo['lastResponseTime'];
                                					$responseDate = date('d/m/y H:i', $lastResponseTime);
                                                    $status = $getInfo['ticketStatus'];

                                                    if($status == 1) {
                                						$ticketStatus = '<span class="badge badge-light-success">Open; In Review</span>';
                                					}else if($status == 2) { 
                                                        $ticketStatus = '<span class="badge badge-light-danger">Admin Assistance Requested</span>';
                                                    } else {
                                						$ticketStatus = '<span class="badge badge-light-danger">Closed</span>';
                                					}
                                					
                                					echo '<tr><td>' . $username . '</td><td><a href="../viewTicket.php?id=' . $ticketID . '">' . $title . '</a></td><td> <center>  </center> '.$ticketStatus.' </td> <td><center>' . $responseDate . '</center></td></tr>';
                                                }
                            				}else{
                                                echo '<tr class=""><td valign="top" colspan="4" class="dataTables_empty" style="text-align:center;">There are no currently support tickets to display,</td></tr>';
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

        $('#filterby').change( function(){
            $('#searchform').submit();
        } );
    </script>
    <!-- END PAGE LEVEL SCRIPTS -->
</body>
</html>