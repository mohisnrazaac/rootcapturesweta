<?php
require_once '../includes/db.php';

if(isset($_POST['id'])){
    $id = $_POST['id'];
    $SQLCSelect = $odb -> query("SELECT * FROM `sub_category` where category_id = $id");
    $SQLCSelect -> execute();
    $cat =  $SQLCSelect -> fetchAll(PDO::FETCH_ASSOC); 
    
    echo json_encode($cat);
}else{
    echo json_encode(array());
}


?>