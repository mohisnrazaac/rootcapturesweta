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

if ($user -> isAdmin($odb)) {

} else {
    header('location: https://rootcapture.com/index.php');
	die();
}

$getUserDetailIdWise = $user->getUserDetailIdWise($odb); 
$college_id = $getUserDetailIdWise['college_id']; 
$teamList = $user->getTeamList($odb,$college_id);
if(!empty($teamList)){ 
    $options = '';
    $options .= '<option value="">Please select</option>';
    foreach ($teamList as $key => $value) {
        $users = $user->getATeamMembers($odb,$value['id'],$college_id);
        if(!empty($users)){
            foreach ($users as $k => $v) {
                $options .= '<option value="'.$v['ID'].'">'.$v['username'].'</option>';
            }													  
        }
    }
}


if (isset($_POST['createApiSub']))
{
    echo '<pre>'; print_r($_POST); exit;
    $api_name = $_POST['api_name'];
    $api_key = $_POST['api_key'];
    $assign_members = $_POST['assign_members'];
    $api_function = $_POST['api_function'];
    $college_id = $_POST['college_id'];

    if ( empty($api_name) || empty($api_key) || empty($assign_members) || empty($api_function) || empty($college_id) )
    {
        $errors = 'Please fill all fields'; 
    }
    else
    {
       

        try
        {
            $odb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $odb->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

             $SQLinsert = $odb -> prepare("INSERT INTO `api_management` (college_id,api_name, api_key, api_members, api_function, status,timestamp)  VALUES(:college_id,:api_name, :api_key, :api_members, :api_function, :status,:tstamp)");

             $SQLinsert -> execute(array(':college_id'=>$college_id,':api_name' => $api_name, ':api_key' => $api_key, ':api_members' => implode(",", $assign_members), ':api_function' => $api_function, ':status' => 0,':tstamp'=>time()));

             header('location: https://rootcapture.com/dashboard/manage_apis.php');
             die();

        }
        catch(Exception $e) {
            $errors = 'Exception -> ';
            var_dump($e->getMessage());
        }
    } 
}



$pageTitle = 'Create API';
require('common/header.php') 
?>
 
	
	
<div class="row special_btn_long_menu">

<ul class="d-flex flex-sm-row flex-column align-items-center justify-content-center" style="width: 100%;"> 

        <li class=""><a class="top_menu_item_long_menu">Create API</a></li> 
    </ul>  	
    
      
</div>




<div class="container centralize_container pt-5 " >
<ul class="nav nav-pills   justify-content-center" id="pills-tab" role="tablist">
<li class="nav-item" role="presentation">
<button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true"> 
    <?php if( isset($errors)){ echo $errors; } else{ echo 'Create API'; } ?>
    </button>
</li>

</ul>
<div class="tab-content" id="pills-tabContent">
<div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">

<form id="form" class="add_api_form" method="POST">

        <div class="p-5 row">
            <div class="col-6 px-5">
                <div class="mb-3">
                    <label for="api_name" class="form-label">API Name <span id="groupNameErr" style="color:red" ></span> </label>
                    <input type="text" class="form-control" id="api_name" name="api_name" placeholder="Write your api name here">
                </div>
            </div>

            <div class="col-6 px-5">
                <div class="mb-3">
                    <label for="api_key" class="form-label">Api Key
                    <span class="regenerate_key" title="Generate a Key">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-clockwise" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z"/>
                        <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z"/>
                        </svg>
                    </span>
                        <span id="groupNameErr" style="color:red" ></span> </label>
                        <input type="text" class="form-control" id="api_key" name="api_key" readonly placeholder="Generate your api key here">
                </div>
            </div>

            <div class="col-6 px-5">
                <div class="mb-3">
                    <label for="assign_members" class="form-label">Assign Members</label>
                    <select  id="assign_members" multiple name="assign_members[]" class="form-select assign_members">
                       <?php echo $options; ?>
                    </select>
                </div>
            </div>

            <div class="col-6 px-5">
                <div class="mb-3">
                    <label for="api_function" class="form-label">Assign Members</label>
                    <select  id="api_function" name="api_function" class="form-select api_function">
                        <option>Get The Grade(s)</option>
                        <option>Add The Grade(s)</option>
                        <option>Edit The Grade(s)</option>
                        <option>Delete The Grade(s)</option>
                        <option>Get Grading Rubric Criterion</option>
                        <option>Add Grading Rubric Criterion</option>
                        <option>Edit Grading Rubric Criterion</option>
                        <option>Delete Grading Rubric Criterion</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="tab_footer d-flex flex-row align-items-center justify-content-center  ">
        <input type="hidden" name="college_id" value="<?php echo $college_id; ?>">
            <button type="submit" name="createApiSub" id="add_api" class="rc-btn submit-button">Create</button>
        </div>
</form>
                
</div> 
</div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <?php require('common/footer.php') ?>
  <script>

    $(document).ready(function() {

    var key = makeid(15);
    $("#api_key").val(key);

    $(document).on("click",".regenerate_key",function(e){
        var key = makeid(15);
        $("#api_key").val(key);
  
    });

    $(document).on("click","#add_api",function(e){
        e.preventDefault();
        // alert("hello"); return false;
        var error = 0;
        // $(".required").each(function(index){
        //     if($(this).val()==''){
        //       error++;
        //       $('.errorMsg').html('<div class="alert alert-danger" role="alert">'+$(this).attr("error")+'</div>');
        //       removeErrorMsg();
        //   }
        // });
        var cust = $(".assign_members option:selected").map(function () {
          return $(this).val();
      }).get().join(',');

      var cust1 = $(".api_function option:selected").map(function () {
          return $(this).val();
      }).get().join(',');

      if(cust==''){
         alert('Please assign users to api');
         return false;
      }

      if(cust1==''){
         alert('Please assign a function to api');
         return false;
      }
      $(".add_api_form").submit();
    });
});



function makeid(length) {
  var result           = '';
  var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
  var charactersLength = characters.length;
  for ( var i = 0; i < length; i++ ) {
      result += characters.charAt(Math.floor(Math.random() * charactersLength));
  }
  return result;
}

  </script>