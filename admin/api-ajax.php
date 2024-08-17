<?php
    ob_start();
    require_once '../includes/db.php';
    require_once '../includes/init.php';

    /*ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);*/

    @session_start();
    $loggedUserId    = $_SESSION['ID'];
    $userInfo = $user->userInfo($odb,$loggedUserId);

    if(isset($_POST) && $_POST['action']=='api_status'){
        $update = $odb -> prepare("UPDATE `api_management` SET `status` = :status WHERE api_id = :id");
        $update -> execute(array(':status' => $_POST['s'], ':id' => $_POST['id']));
    }elseif(isset($_POST) && $_POST['action']=='api_view'){
        $api = $_POST['api'];
        switch((string)$api) {
            case 'Add The Grade(s)':
                $html = '<div class="row">

                    <div class="col-md-4">
                        <label>API Method</label>
                        <p><button class="btn btn-outline-info  mb-2 me-2" name="viewBtn" value="5" role="button" type="button">Post</button></p>
                    </div>
                    <div class="col-md-8">
                        <label>Header</label>
                        <p><button class="btn btn-outline-info  mb-2 me-2" name="viewBtn" value="5" role="button" type="button">Authorization: Bearer LLfRIFFOfygsMpZ</button></p>
                    </div>
                    <div class="col-md-9">
                        <label>API Url</label>
                        <p><button class="btn btn-outline-info  mb-2 me-2" name="viewBtn" value="5" role="button" type="button">https://rootcapture.com/api/add_grade.php</button></p>
                    </div>
                    <div class="col-md-12 mb-2">
                        <label>Request</label>
                        <p >
                            {"user_id":42,"grade":"k+","grade_value":"k+"}
                        </p>
                    </div>
                    <div class="col-md-12">
                        <label>Response</label>
                        <p >
                            {"type":"success","msg":"Grade added successfully","grade_id":"17"}
                        </p>
                    </div>
                </div>';
                echo $html;
                break;

            case 'Edit The Grade(s)':
                $html = '<div class="row">
                    <div class="col-md-4">
                        <label>API Method</label>
                        <p><button class="btn btn-outline-info  mb-2 me-2" name="viewBtn" value="5" role="button" type="button">Post</button></p>
                    </div>
                    <div class="col-md-8">
                        <label>Header</label>
                        <p><button class="btn btn-outline-info  mb-2 me-2" name="viewBtn" value="5" role="button" type="button">Authorization: Bearer LLfRIFFOfygsMpZ</button></p>
                    </div>

                    <div class="col-md-12">
                        <label>API Url</label>
                        <p><button class="btn btn-outline-info  mb-2 me-2" name="viewBtn" value="5" role="button" type="button">https://rootcapture.com/api/edit_grade.php</button></p>
                    </div>
                    <div class="col-md-12 mb-2">
                        <label>Request</label>
                        <p >
                            {"user_id":42,"grade_id":17,"grade":"k+","grade_value":"k+"}
                        </p>
                    </div>
                    <div class="col-md-12">
                        <label>Response</label>
                        <p>
                            {"type":"success","msg":"Grade update successfully","grade_id":17}
                        </p>
                    </div>
                </div>';
                echo $html;
                break;

            case 'Get The Grade(s)':
                $html = '<div class="row">
                    <div class="col-md-4">
                        <label>API Method</label>
                        <p><button class="btn btn-outline-info  mb-2 me-2" name="viewBtn" value="5" role="button" type="button">Get</button></p>
                    </div>
                    <div class="col-md-8">
                        <label>Header</label>
                        <p><button class="btn btn-outline-info  mb-2 me-2" name="viewBtn" value="5" role="button" type="button">Authorization: Bearer LLfRIFFOfygsMpZ</button></p>
                    </div>

                    <div class="col-md-9">
                        <label>API Url</label>
                        <p><button class="btn btn-outline-info  mb-2 me-2" name="viewBtn" value="5" role="button" type="button">https://rootcapture.com/api/get_grade.php?user_id=42&grade_id=16</button></p>
                    </div>
                    <div class="col-md-12">
                        <label>Response</label>
                        <p>
                            {"type":"success","msg":"Grade available.","data":{"id":16,"grade_value":"k+","grade":"k+"}}
                        </p>
                    </div>
                </div>';
                echo $html;
                break;



            case 'Delete The Grade(s)':
                $html = '<div class="row">
                    <div class="col-md-4">
                        <label>API Method</label>
                        <p><button class="btn btn-outline-info  mb-2 me-2" name="viewBtn" value="5" role="button" type="button">Get</button></p>
                    </div>
                    <div class="col-md-8">
                        <label>Header</label>
                        <p><button class="btn btn-outline-info  mb-2 me-2" name="viewBtn" value="5" role="button" type="button">Authorization: Bearer LLfRIFFOfygsMpZ</button></p>
                    </div>
                    <div class="col-md-12">
                        <label>API Url</label>
                        <p><button class="btn btn-outline-info  mb-2 me-2" name="viewBtn" value="5" role="button" type="button">https://rootcapture.com/api/get_grade.php?user_id=42&grade_id=16</button></p>
                    </div>
                    <div class="col-md-12">
                        <label>Response</label>
                        <p>
                            {"type":"success","msg":"Grade available.","data":{"id":16,"grade_value":"k+","grade":"k+"}}
                        </p>
                    </div>
                </div>';
                echo $html;
                break;

            case 'Get Grading Rubric Criterion':
                $html = '<div class="row">
                    <div class="col-md-4">
                        <label>API Method</label>
                        <p><button class="btn btn-outline-info  mb-2 me-2" name="viewBtn" value="5" role="button" type="button">Get</button></p>
                    </div>
                    <div class="col-md-8">
                        <label>Header</label>
                        <p><button class="btn btn-outline-info  mb-2 me-2" name="viewBtn" value="5" role="button" type="button">Authorization: Bearer LLfRIFFOfygsMpZ</button></p>
                    </div>

                    <div class="col-md-12">
                        <label>API Url</label>
                        <p><button class="btn btn-outline-info  mb-2 me-2" name="viewBtn" value="5" role="button" type="button">https://rootcapture.com/api/get_grading_rubric.php?user_id=42&rubric_id=12</button></p>
                    </div>
                    <div class="col-md-12">
                        <label>Response</label>
                        <p> 
                                {
                                    "type":"success",
                                    "msg":"Grading Rubric Criterion available.",
                                    "data":{
                                        "id":12,
                                        "title":"ASASAS",
                                        "detail":"ASASASSA",
                                        "redteam_grade":"",
                                        "blueteam_grade":"",
                                        "purpleteam_grade":"A",
                                        "assigned_user":[
                                            {
                                                "user_id":40,
                                                "username":"kirtideshwal",
                                                "grade":"B+"
                                            }
                                        ]
                                    }
                                }
                        </p>
                    </div>
                </div>';
                echo $html;
                break;

            case 'Add Grading Rubric Criterion':
                $html = '<div class="row">
                    <div class="col-md-4">
                        <label>API Method</label>
                        <p><button class="btn btn-outline-info  mb-2 me-2" name="viewBtn" value="5" role="button" type="button">Post</button></p>
                    </div>
                    <div class="col-md-8">
                        <label>Header</label>
                        <p><button class="btn btn-outline-info  mb-2 me-2" name="viewBtn" value="5" role="button" type="button">Authorization: Bearer LLfRIFFOfygsMpZ</button></p>
                    </div>
                    <div class="col-md-12">
                        <label>API Url</label>
                        <p><button class="btn btn-outline-info  mb-2 me-2" name="viewBtn" value="5" role="button" type="button">https://rootcapture.com/api/add_grading_rubric.php</button></p>
                    </div>
                    <div class="col-md-12 mb-2">
                        <label>Request</label>
                        <p>
                            {
                                "user_id":42,
                                "title":"Title",
                                "description":"Description",
                                "red_team":"A+",
                                "purple_team":"B+",
                                "blue_team":"A+",
                                "assign_user":[
                                    {
                                        "user_id":"40",
                                        "grade":"A+"
                                    },
                                    {
                                        "user_id":"41",
                                        "grade":"A+"
                                    }
                                ]
                            }
                        </p>
                    </div>
                    <div class="col-md-12">
                        <label>Response</label>
                        <p>
                            {"type":"success","msg":"Grading Rubic added successfully","rubic_id":"21"}
                        </p>
                    </div>
                </div>';
                echo $html;
                break;

            case 'Edit Grading Rubric Criterion':
                $html = '<div class="row">
                    <div class="col-md-4">
                        <label>API Method</label>
                        <p><button class="btn btn-outline-info  mb-2 me-2" name="viewBtn" value="5" role="button" type="button">Post</button></p>
                    </div>
                    <div class="col-md-8">
                        <label>Header</label>
                        <p><button class="btn btn-outline-info  mb-2 me-2" name="viewBtn" value="5" role="button" type="button">Authorization: Bearer LLfRIFFOfygsMpZ</button></p>
                    </div>
                    <div class="col-md-12">
                        <label>API Url</label>
                        <p><button class="btn btn-outline-info  mb-2 me-2" name="viewBtn" value="5" role="button" type="button">https://rootcapture.com/api/add_grading_rubric.php</button></p>
                    </div>
                    <div class="col-md-12 mb-2">
                        <label>Request</label>
                        <p>
                            {
                                "user_id":42,
                                "rubric_id":21,
                                "title":"Title",
                                "description":"Description",
                                "red_team":"A+",
                                "purple_team":"B+",
                                "blue_team":"A+",
                                "assign_user":[
                                    {
                                        "user_id":"40",
                                        "grade":"A+"
                                    }
                                ]
                            }
                        </p>
                    </div>
                    <div class="col-md-12">
                        <label>Response</label>
                        <p>
                            {"type":"success","msg":"Grading Rubic update successfully","rubic_id":"21"}
                        </p>
                    </div>
                </div>';
                echo $html;
                break;

            case 'Delete Grading Rubric Criterion':
                $html = '<div class="row">
                    <div class="col-md-4">
                        <label>API Method</label>
                        <p><button class="btn btn-outline-info  mb-2 me-2" name="viewBtn" value="5" role="button" type="button">Get</button></p>
                    </div>
                    <div class="col-md-8">
                        <label>Header</label>
                        <p><button class="btn btn-outline-info  mb-2 me-2" name="viewBtn" value="5" role="button" type="button">Authorization: Bearer LLfRIFFOfygsMpZ</button></p>
                    </div>
                    <div class="col-md-12">
                        <label>API Url</label>
                        <p><button class="btn btn-outline-info  mb-2 me-2" name="viewBtn" value="5" role="button" type="button">https://rootcapture.com/api/get_grading_rubric.php?user_id=42&rubric_id=12</button></p>
                    </div>
                    <div class="col-md-12">
                        <label>Response</label>
                        <p> 
                                {
                                    "type":"success",
                                    "msg":"Grading Rubric Criterion delete successfully.",
                                    "data":{
                                        "id":12,
                                        "title":"ASASAS",
                                        "detail":"ASASASSA",
                                        "redteam_grade":"",
                                        "blueteam_grade":"",
                                        "purpleteam_grade":"A",
                                        "assigned_user":[
                                            {
                                                "user_id":40,
                                                "username":"kirtideshwal",
                                                "grade":"B+"
                                            }
                                        ]
                                    }
                                }
                        </p>
                    </div>
                </div>';
                echo $html;
                break;

            default:
                $html = '<div class="row">
                    <div class="col-md-12">
                        <p>There is no api links available for this api.</p>
                    </div>
                </div>';
            echo $html;
        }
    }