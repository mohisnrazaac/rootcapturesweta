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

    if ($user -> isBlueTeam($odb) || $user -> isRedTeam($odb) || $user -> isPurpleTeam($odb) || $user -> isAssist($odb) || $user -> isAdmin($odb)) {

    } else {
        header('location: index.php');
        die();
    }

    $title = 'Log Administration';
    require_once('common/header.php'); 
?>
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.3/css/dataTables.bootstrap5.css" />
<div class="p-4"></div>

<div class="container px-5"> 
</head>
<body>

<div class="main_announcement container ">
     
     <div style="margin-bottom: 1px">
       <label class="cyber_range_bg" for="editor"><i>&nbsp;</i></label> 
     </div>   
     <table id="rc_table" class="table table-striped nowrap" style="width:100%">
        <thead>
            <tr>
                <th>Username</th>
                <th>IP Address</th>
                <th>Login Data</th> 
            </tr>
        </thead>
        <tbody> 
        <?php
            $SQLGetLogs = $odb -> query("SELECT * FROM `loginip` ORDER BY `date` DESC");
            while($getInfo = $SQLGetLogs -> fetch(PDO::FETCH_ASSOC))
            {
                $username = $getInfo['username'];
                $logged = $getInfo['logged'];
                date_default_timezone_set('MST');
                $date = date("m-d-Y, h:i:s a" ,$getInfo['date']);
        ?>
                
            <tr>
                <td><?=$username?></td>
                <td><?=$logged?></td>
                <td><?=$date?></td>
            </tr>
        <?php
            }
        ?>
        </tbody> 
    </table>

    <div class="d-flex justify-content-center">
      <button type="submit" class="button-danger-reset">Reset the Login Logs</button>
	  </div>  
	 
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script> 
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script>
    new DataTable('#rc_table');
</script> 
<?php require_once('common/footer.php') ?>