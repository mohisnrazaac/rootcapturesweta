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

$updated_at = date("Y-m-d H:i:s");
$BASE_URL = 'http://66.172.2.18:8080';

// if is purple or is red or is blue or is admin or is assistant

// status = 1 new server , 2 start server, 3 stop server, 4 deleted server , 5 Pause Server

$getUserDetailIdWise = $user->getUserDetailIdWise($odb); 
$college_id = $getUserDetailIdWise['college_id']; 
$pageTitle = 'Server Management';
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
                                <li class="breadcrumb-item active" aria-current="page">Server</li>
                            </ol>
                                                        <br>
                            <a class="btn btn-outline-success btn-lrg" href="../admin/server_create.php" role="button">Create A New Server</a>
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
                                            
                                            <th class="text-center col-md-1"> SNo </th>
                                            <th class="text-center col-md-1"> Server </th>
                                            <th class="text-center col-md-2" scope="col">Snapshot Name </th>
                                            <th class="text-center col-md-1" scope="col">Status </th>
                                            <th class="text-center col-md-2" scope="col">Created Date</th>
                                            <th class="text-center col-md-5" scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
    <?php
        if (isset($_POST['start']))
        {
            $snapshot_name = $_POST['snapshot_name'];
            
            $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => $BASE_URL.'/api/login',
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

                    $curlStart = curl_init();

                    curl_setopt_array($curlStart, array(
                    CURLOPT_URL => $BASE_URL.'/api/antlets/'.$snapshot_name.'/start',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_HTTPHEADER => array(
                        'Accept: application/json',
                        'Authorization: Token '.$getToken['token'],
                        'Content-Type: application/json',
                    ),
                    ));

                    $responseStart = curl_exec($curlStart);
                    $httpcodeStart = curl_getinfo($curlStart, CURLINFO_HTTP_CODE);
                    curl_close($curlStart);
                    // echo $responseStart;
                    if($httpcodeStart == 200){
                        // UPDATE `antlets` SET `status` = '2' WHERE `antlets`.`id` = 1;
                       
                        $SQLupdate = $odb -> prepare("UPDATE `antlets` SET `status`  = :status, updated_at=:updated_at WHERE snapshot_name = :snapshot_name");
				        $SQLupdate -> execute(array(':status' => 2, ':updated_at' => $updated_at ,':snapshot_name' => $snapshot_name));  

                    }else{
                        echo '<div class="error" id="message"><p><strong>ERROR: 4013 Server Not Start</strong></div>';
                    }
            }else{
                echo '<div class="error" id="message"><p><strong>ERROR: 4012 Server Not Start</strong></div>';
            }




            echo '<div class="message" id="message"><p><strong>SUCCESS: </strong>Server Start Successfully!</p></div>';
        }


        //Stop START

        if (isset($_POST['stop']))
        {
            $snapshot_name = $_POST['snapshot_name'];
            
            $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => $BASE_URL.'/api/login',
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

                    $curlStart = curl_init();

                    curl_setopt_array($curlStart, array(
                    CURLOPT_URL => $BASE_URL.'/api/antlets/'.$snapshot_name.'/force-stop',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_HTTPHEADER => array(
                        'Accept: application/json',
                        'Authorization: Token '.$getToken['token'],
                        'Content-Type: application/json',
                    ),
                    ));

                    $responseStart = curl_exec($curlStart);
                    $httpcodeStart = curl_getinfo($curlStart, CURLINFO_HTTP_CODE);
                    curl_close($curlStart);
                    // echo $responseStart;
                    if($httpcodeStart == 200){
                        // UPDATE `antlets` SET `status` = '2' WHERE `antlets`.`id` = 1; 
                        $SQLupdate = $odb -> prepare("UPDATE `antlets` SET `status`  = :status, updated_at=:updated_at WHERE snapshot_name = :snapshot_name");
				        $SQLupdate -> execute(array(':status' => 3, ':updated_at' => $updated_at ,':snapshot_name' => $snapshot_name));     

                    }else{
                        echo '<div class="error" id="message"><p><strong>ERROR: 4013 Server Not Stop</strong></div>';
                    }
            }else{
                echo '<div class="error" id="message"><p><strong>ERROR: 4012 Server Not Stop</strong></div>';
            }




            echo '<div class="message" id="message"><p><strong>SUCCESS: </strong>Server Stop Successfully!</p></div>';
        }

        //Stop END

        // Delete start

        if (isset($_POST['delete']))
        {
            $snapshot_name = $_POST['snapshot_name'];
            
            $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => $BASE_URL.'/api/login',
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

                    $curlStart = curl_init();

                    curl_setopt_array($curlStart, array(
                    CURLOPT_URL => $BASE_URL.'/api/antlets/'.$snapshot_name,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'DELETE',
                    CURLOPT_HTTPHEADER => array(
                        'Accept: application/json',
                        'Authorization: Token '.$getToken['token'],
                        'Content-Type: application/json',
                    ),
                    ));

                    $responseStart = curl_exec($curlStart);
                    $httpcodeStart = curl_getinfo($curlStart, CURLINFO_HTTP_CODE);
                    curl_close($curlStart);
                    // echo $responseStart;
                    if($httpcodeStart == 200){
                      
                        $SQLupdate = $odb -> prepare("UPDATE `antlets` SET `status`  = :status, updated_at=:updated_at WHERE snapshot_name = :snapshot_name");
				        $SQLupdate -> execute(array(':status' => 4, ':updated_at' => $updated_at ,':snapshot_name' => $snapshot_name));    

                    }else{
                        echo '<div class="error" id="message"><p><strong>ERROR: 4013 Server Not Stop</strong></div>';
                    }
            }else{
                echo '<div class="error" id="message"><p><strong>ERROR: 4012 Server Not Stop</strong></div>';
            }




            echo '<div class="message" id="message"><p><strong>SUCCESS: </strong>Server Stop Successfully!</p></div>';
        }


        // Delete END


         //Reboot START

         if (isset($_POST['reboot']))
         {
             $snapshot_name = $_POST['snapshot_name'];
             
             $curl = curl_init();
             curl_setopt_array($curl, array(
             CURLOPT_URL => $BASE_URL.'/api/login',
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
 
                     $curlStart = curl_init();
 
                     curl_setopt_array($curlStart, array(
                     CURLOPT_URL => $BASE_URL.'/api/antlets/'.$snapshot_name.'/reboot',
                     CURLOPT_RETURNTRANSFER => true,
                     CURLOPT_ENCODING => '',
                     CURLOPT_MAXREDIRS => 10,
                     CURLOPT_TIMEOUT => 0,
                     CURLOPT_FOLLOWLOCATION => true,
                     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                     CURLOPT_CUSTOMREQUEST => 'POST',
                     CURLOPT_HTTPHEADER => array(
                         'Accept: application/json',
                         'Authorization: Token '.$getToken['token'],
                         'Content-Type: application/json',
                     ),
                     ));
 
                     $responseStart = curl_exec($curlStart);
                     $httpcodeStart = curl_getinfo($curlStart, CURLINFO_HTTP_CODE);
                     curl_close($curlStart);
                     // echo $responseStart;
                    //  if($httpcodeStart == 200){
                    //      // UPDATE `antlets` SET `status` = '2' WHERE `antlets`.`id` = 1; 
                    //      $SQLupdate = $odb -> prepare("UPDATE `antlets` SET `status`  = :status, updated_at=:updated_at WHERE snapshot_name = :snapshot_name");
                    //      $SQLupdate -> execute(array(':status' => 3, ':updated_at' => $updated_at ,':snapshot_name' => $snapshot_name));     
 
                    //  }else{
                    //      echo '<div class="error" id="message"><p><strong>ERROR: 4013 Server Not Stop</strong></div>';
                    //  }
             }else{
                 echo '<div class="error" id="message"><p><strong>ERROR: 4012 Server Not Stop</strong></div>';
             }
 
 
 
 
             echo '<div class="message" id="message"><p><strong>SUCCESS: </strong>Server Stop Successfully!</p></div>';
         }
 
         //Reboot END

        //  http://10.1.4.12:3000/api/antlets/BackboxAnk/pause POST
        // http://10.1.4.12:3000/api/antlets/BackboxAnk/resume POST replace stop button 

         //Pause START

         if (isset($_POST['pause']))
         {
             $snapshot_name = $_POST['snapshot_name'];
             
             $curl = curl_init();
             curl_setopt_array($curl, array(
             CURLOPT_URL => $BASE_URL.'/api/login',
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
 
                     $curlStart = curl_init();
 
                     curl_setopt_array($curlStart, array(
                     CURLOPT_URL => $BASE_URL.'/api/antlets/'.$snapshot_name.'/pause',
                     CURLOPT_RETURNTRANSFER => true,
                     CURLOPT_ENCODING => '',
                     CURLOPT_MAXREDIRS => 10,
                     CURLOPT_TIMEOUT => 0,
                     CURLOPT_FOLLOWLOCATION => true,
                     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                     CURLOPT_CUSTOMREQUEST => 'POST',
                     CURLOPT_HTTPHEADER => array(
                         'Accept: application/json',
                         'Authorization: Token '.$getToken['token'],
                         'Content-Type: application/json',
                     ),
                     ));
 
                     $responseStart = curl_exec($curlStart);
                     $httpcodeStart = curl_getinfo($curlStart, CURLINFO_HTTP_CODE);
                     curl_close($curlStart);
                     // echo $responseStart;
                     if($httpcodeStart == 200){
                         // UPDATE `antlets` SET `status` = '2' WHERE `antlets`.`id` = 1; 
                         $SQLupdate = $odb -> prepare("UPDATE `antlets` SET `status`  = :status, updated_at=:updated_at WHERE snapshot_name = :snapshot_name");
                         $SQLupdate -> execute(array(':status' => 5, ':updated_at' => $updated_at ,':snapshot_name' => $snapshot_name));     
 
                     }else{
                         echo '<div class="error" id="message"><p><strong>ERROR: 4013 Server Not Stop</strong></div>';
                     }
             }else{
                 echo '<div class="error" id="message"><p><strong>ERROR: 4012 Server Not Stop</strong></div>';
             }
 
 
 
 
             echo '<div class="message" id="message"><p><strong>SUCCESS: </strong>Server has been paused!</p></div>';
         }
 
         //Pause END

        //Resume START

        if (isset($_POST['resume']))
        {
            $snapshot_name = $_POST['snapshot_name'];
            
            $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => $BASE_URL.'/api/login',
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

                    $curlStart = curl_init();

                    curl_setopt_array($curlStart, array(
                    CURLOPT_URL => $BASE_URL.'/api/antlets/'.$snapshot_name.'/resume',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_HTTPHEADER => array(
                        'Accept: application/json',
                        'Authorization: Token '.$getToken['token'],
                        'Content-Type: application/json',
                    ),
                    ));

                    $responseStart = curl_exec($curlStart);
                    $httpcodeStart = curl_getinfo($curlStart, CURLINFO_HTTP_CODE);
                    curl_close($curlStart);
                    // echo $responseStart;
                     if($httpcodeStart == 200){
                         // UPDATE `antlets` SET `status` = '2' WHERE `antlets`.`id` = 1; 
                         $SQLupdate = $odb -> prepare("UPDATE `antlets` SET `status`  = :status, updated_at=:updated_at WHERE snapshot_name = :snapshot_name");
                         $SQLupdate -> execute(array(':status' => 2, ':updated_at' => $updated_at ,':snapshot_name' => $snapshot_name));     

                     }else{
                         echo '<div class="error" id="message"><p><strong>ERROR: 4013 Server Not Stop</strong></div>';
                     }
            }else{
                echo '<div class="error" id="message"><p><strong>ERROR: 4012 Server Not Stop</strong></div>';
            }




            echo '<div class="message" id="message"><p><strong>SUCCESS: </strong>Server resume Successfully!</p></div>';
        }

        //Resume END


 
       
        // SELECT `id`, `src_dname`, `compression`, `dst_dname`, `snapshot_name`, `antlet_num`, `zpool_name`, `status`, `college_id`, `created_at`, `updated_at` FROM `antlets`
                $SQLSelect = $odb -> query("SELECT * FROM `antlets` where college_id = $college_id ORDER BY `id` DESC");
                //    print_r($SQLSelect -> fetch(PDO::FETCH_ASSOC)); exit;
                $i = 0;
                $SQLSelect -> execute();
                $antlets =  $SQLSelect -> fetchAll(PDO::FETCH_ASSOC); 
                if(!empty($antlets)){ 
                    foreach ($antlets as $key => $show) {
                        $src_dname = $show['src_dname'];
                        $snapshot_name = $show['snapshot_name'];
                        $rowID = $show['id'];
                        $status = $show['status'];
                        $statusName = "";
                        switch($status){
                            case 1: $statusName = "New"; break;
                            case 2: $statusName = "Start"; break;
                            case 3: $statusName = "Stop"; break;
                            case 4: $statusName = "Delete"; break;
                            case 5: $statusName = "Pause"; break;
                        }
                                       
                        
                        $date = date_format(date_create($show['created_at']),"m-d-Y, h:i:s");  
                        $i = $key+1;                       
                       echo '<tr>';
                        echo '<td>'.$i.'</td><td>'.$src_dname.'</td><td>'.$snapshot_name.'</td><td><center>'.$statusName.'</center></td><td><center>'.$date.'</center></td>';
                       echo '<td><center><form action="" class = "form" method="POST">';  
                        echo '<input type="hidden" name="snapshot_name" value="'.$snapshot_name.'">';
                       
                       if($status == 2){
                        echo '<button class="btn btn-outline-danger mb-2 me-4" type="submit" name="stop" value="Stop">Stop</button>';
                        echo '<button class="btn btn-outline-primary mb-2 me-4" type="submit" name="reboot" value="reboot">Reboot</button>';
                        echo '<a class="btn btn-outline-warning mb-2 me-4" target="_blank" href="../admin/start-vnc-proxy.php?server_name='.$snapshot_name.'">Enter Server</a>';
                        echo '<button class="btn btn-outline-danger mb-2 me-4" type="submit" name="pause" value="Pause">Pause</button>';

                       }elseif($status == 5){
                        //Pause
                        echo '<button class="btn btn-outline-primary mb-2 me-4" type="submit" name="resume" value="Resume">Resume</button>';
                        echo '<button class="btn btn-outline-danger mb-2 me-4" type="submit" name="delete" value="Delete">Delete</button>';
                        
                       }else{

                        echo '<button class="btn btn-outline-success mb-2 me-4" type="submit" name="start" value="Start">Start</button>';
                        echo '<button class="btn btn-outline-danger mb-2 me-4" type="submit" name="delete" value="Delete">Delete</button>';

                       }
                       
                       
                       echo' </form></center></td>';
                       echo '</tr>';
                
                   }
                }else{
                    echo '<tr class=""><td valign="top" colspan="4" class="dataTables_empty" style="text-align:center;">There are no data to display,</td></tr>';
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