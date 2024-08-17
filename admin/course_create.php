<?php
ob_start();
require_once '../includes/db.php';
require_once '../includes/init.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
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
$getUserDetailIdWise = $user->getUserDetailIdWise($odb); 
$college_id = $getUserDetailIdWise['college_id']; 

$SQLCSelect = $odb -> query("SELECT * FROM `course_category` WHERE `college_id` = $college_id");
$SQLCSelect -> execute();
$cat =  $SQLCSelect -> fetchAll(PDO::FETCH_ASSOC); 

$level = [1=>"Beginner",2=>"Intermediate",3=>"Expert"];


if (isset($_POST['submitCourse']))
{
// print_r($_POST); exit;	
    $category_id = $_POST['category'];
    $course_title = $_POST['course_title'];
    $level = $_POST['level'];
    $detailAdd = $_POST['detailAdd'];
    $enterprise_id = $college_id.date("YmdHis");
    
	$errors = array();
	if (empty($category_id) || empty($detailAdd) || empty($level) || empty($course_title))
	{
		$errors[] = 'Please verify all fields';
	}
	if (empty($errors))
	{   
        try
        {              
            $sql = "INSERT INTO `course`(`enterprise_id`, `college_id`, `course_title`, `course_category_id`, `course_level`, `content`, `status`,`created_by`, `created_at`, `updated_at`) VALUES (?,?,?,?,?,?,?,?,?,?)";
            $stmt= $odb->prepare($sql);
            $stmt->execute([$enterprise_id,$college_id,$course_title,$category_id,$level, $detailAdd, 1,$_SESSION['ID'],DATETIME,DATETIME ]);

            // $SQLinsert = $odb -> prepare("INSERT INTO `news` (`ID`,`userID`,`college_id`, `title`, `detail`,`created_by`, `date`) VALUES(NULL,:userID,:college_id :title,:detail, :created_by,:date)");
            // $SQLinsert -> execute(array(':userID' => $_SESSION['ID'],':college_id' => $college_id, ':title' => $titleAdd, ':detail' => $detailAdd, ':created_by' => $_SESSION['ID'], ':date' => DATETIME));
        } 
        catch (PDOException $e)
        {
            $errors[] = "DataBase Error: The Course could not be added.<br>".$e->getMessage();
            // $errors[] = $e;
        } catch (Exception $e) 
        {
            $errors[] = "General Error: The Course could not be added.<br>".$e->getMessage();
        }       
	}
	else
	{
		foreach($errors as $error)
		{
			echo '-'.$error.'<br />';
		}
		echo '</div>';
	}
}

$pageTitle = 'Create Course';
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
                                <li class="breadcrumb-item">Course</li>
                                <li class="breadcrumb-item active" aria-current="page"><?php echo $pageTitle ?></li>
                            </ol>
                        </nav>
                    </div>
					<br>
                    <!-- /BREADCRUMB -->
                       <div class="col-lg-12 col-12 layout-spacing">
                         <div class="row mb-3">
                                <div class="col-md-12">
                                    <h2><?php echo $pageTitle ?></h2>
        
                                    <div class="animated-underline-content">
                                        <ul class="nav nav-tabs" id="animateLine" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" id="animated-underline-home-tab" data-bs-toggle="tab" href="#animated-underline-home" role="tab" aria-controls="animated-underline-home" aria-selected="true"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg> <?php echo $pageTitle ?></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-content" id="animateLineContent-4">
                                <div class="tab-pane fade show active" id="animated-underline-home" role="tabpanel" aria-labelledby="animated-underline-home-tab">
		<?php 
		if (isset($_POST['submitCourse']))
		{
			if(empty($errors)) {
				echo '<div class="message" id="message"><p><strong>SUCCESS: The Course has been added! You are now being redirected to the Listing.</strong></div><meta http-equiv="refresh" content="4;url=../admin/course_system.php">';
				
				$titleAdd = '';
				$detailAdd = '';
			} else {
				echo '<div class="error" id="message"><p><strong>ERROR: </strong>';
				foreach($errors as $error) {
					echo ''.$error.'<br />';
				}
				echo '</div>';
			}
			
		}
		
		?>
                                    
                              <div class="row">
                                        <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
                                    <form action="" class="section general-info" onsubmit="javascript: return process();" method="POST">
                                       
                                      <div class="info">
                                                    <div align="Center">
                                                       

                                                        <div class="row">
 <div class="col-lg-11 mx-auto">
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 mt-md-0 mt-4">

                                                            <div class="row">

                                     <div class="col-md-4">
                                        <div class="form-group ">
                                            <label for="category">Title</label>
                                            
                                            <input type="text" name="course_title" class="form-control mb-3" placeholder="Title">
                                            <!-- <input type="text" class="form-control mb-3" placeholder="Write your announcement title here" name="category"> -->
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group ">
                                            <label for="category">Category</label>
                                            <select name="category" id="category" class="form-control mb-3">
                                                <option value="">Please select Category</option>
                                                <?php
                                                    foreach($cat  as $key=>$value){
                                                        echo "<option value='".$value['enterprise_id']."'>".$value['name']."</option>";
                                                    }
                                                ?>
                                            </select>
                                            <!-- <input type="text" class="form-control mb-3" placeholder="Write your announcement title here" name="category"> -->
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group ">
                                            <label for="sub_category">Level</label>
                                            
                                            <select name="level" id="sub_category" class="form-control mb-3">
                                                <option value="">Please select Level</option>
                                                <?php
                                                    foreach($level  as $key=>$value){
                                                        echo "<option value='".$key."'>".$value."</option>";
                                                    }
                                                ?>
                                                
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div  class="form-group customTextAreaTxt">
                                            <label for="blog-description">Content</label>
                                            <div id="blog-description" class="mb-3">
                                                
                                            </div>
                                            <input type="hidden" id="detailAdd" name="detailAdd" />
                                        </div>
                                    </div>
                                       <div class="col-md-12 mt-1">
                                        <div class="form-group text-end">
                                        <input type="submit" name="submitCourse" class="btn btn-outline-success btn-lrg">
                                    </div>
                                    </div>

                                    </div></div></div></div></div></div></div>
                                    </form>
                                   </div>
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
   
    <?php require_once '../footer.php'; ?>

    

    <script>

            function process()
            {
               if(!$('div.ql-blank').length)
               {
                    var content = $('div.ql-editor').html();
                    $('#detailAdd').val(content); 
               }
               else
               {
                $('#detailAdd').val(''); 
               }
                      
               return true;
            }

    </script>


</body>
</html>