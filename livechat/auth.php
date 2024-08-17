
<?php
 require __DIR__ . '/vendor/autoload.php';
 session_start();

 if($_POST['id'] == $_SESSION['ID'])
 {
     $options = array(
        'cluster' => 'ap2',
        'useTLS' => true
     );

    //  $custom_client = new GuzzleHttp\Client();

     $pusher = new Pusher\Pusher(
        'afc846898302db567944',
        'ade7a55c67e46e56239b',
        '1625867',
        $options
     );
     
     echo $pusher->authorizeChannel($_POST['channel_name'],$_POST['socket_id']);
 }
 else
 {
    header('',true,403);
    echo 'Forbidden';
 }

?>