<?php

require_once('classes/EncryptDecrypt.php');
require_once('classes/ApiController.php');

$api_secret_key = "5257ea386484258b528805a95c5b493055";
$api_salt_iv = "1234567890123456";
$api_encryption_block_mode = "AES-256-CBC";

// for encryption and decryption
$requestResponseEncryption = new EncryptDecrypt($api_secret_key,$api_salt_iv,$api_encryption_block_mode);
 $api = new ApiController();

//API endpoints
$endpoints = [
    'getAllActiveUser' => 'getAllActiveUser',
    'getAllCollegeWiseQuiz' => 'getAllCollegeWiseQuiz',
    'getQuizDetail' => 'getQuizDetail',
    'getAllUserCollegeWise' => 'getAllUserCollegeWise',
    'getAllTeamsCollegeWise' => 'getAllTeamsCollegeWise',
    'getAllAssetGroupCollegeWise' => 'getAllAssetGroupCollegeWise',
    'getTeamName' => 'getTeamName',
    'getGradingRubricCollegeWise' => 'getGradingRubricCollegeWise',
    'getAllSupportCollegeWise' => 'getAllSupportCollegeWise',
    'getAllteamListUserWise' => 'getAllteamListUserWise',
    'createUserCollegeWise' => 'createUserCollegeWise',
    'createTeamCollegeWise' => 'createTeamCollegeWise',
    'getAllSystemAsset' => 'getAllSystemAsset',
    'getAllGradingRubricCollegeWise' => 'getAllGradingRubricCollegeWise',
    'getAllCollegeList' => 'getAllCollegeList',
    'getticketDetail' => 'getticketDetail',
    'getQuizPlayedList' => 'getQuizPlayedList',
    'updateQuizStatus' => 'updateQuizStatus',
    'openTicket' => 'openTicket',
    'closeTicket' => 'closeTicket',
    'ticketResponse' => 'ticketResponse',
    'editUser' => 'editUser',
    'editRubric' => 'editRubric',
    'updateRubric' => 'updateRubric',
    'createRubric' => 'createRubric',
    'updateUser' => 'updateUser',
    'updateTeam' => 'updateTeam',
    'userDeleteById' => 'userDeleteById',
    'ticketDeleteById' => 'ticketDeleteById',
    'deleteTenant' => 'deleteTenant'
];

// API key validation
function validateApiKey($apiKey)
{
    // Replace this with your logic to validate API keys
    $validApiKeys = ['ZVBPNPCHVMBUAQTZYOWPLTXVWXWYERDSGHJHJBBH11'];
    return in_array($apiKey, $validApiKeys);
}

// Get the requested endpoint
$requestUri = $_SERVER['REQUEST_URI']; 
$endpoint = $_GET['endpoint'];

// Check if the endpoint exists
if (array_key_exists($endpoint, $endpoints))
{
    // Validate API key
    $apiKey = $_GET['api_key'] ?? '';
    if (!validateApiKey($apiKey)) {
        header('HTTP/1.1 401 Unauthorized');
        echo json_encode(['error' => 'Invalid API key']);
        exit();
    }

    // Call the corresponding handler function for the endpoint
    $handler = $endpoints[$endpoint];
    $handler($requestResponseEncryption,$api);
}
else
{
    // Endpoint not found
    header('HTTP/1.1 404 Not Found');
    echo json_encode(['error' => 'Endpoint not found']);
}

// Function to handle users endpoint
function getAllActiveUser($requestResponseEncryption,$api)
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        $jsonString = file_get_contents('php://input');
        $data = json_decode($jsonString, true);
        // Check if decoding was successful
        if ($data === null)
        {
            $ret['status'] = false;
            $ret['message'] = "Data missing in request.";
            echo json_encode($ret);
            return $ret;
        } else {
            // Access the encrypted data
            $encryptedData = $data['encrypted_data'];
            
            $decryptedData = $requestResponseEncryption->decrypt(base64_decode(base64_encode($encryptedData)));
            if ($decryptedData === false)
            {
                $ret['status'] = false;
                $ret['message'] = "Data Invalid.";
                echo json_encode($ret);
                return $ret;
            } else {
                if ($decryptedData === null) {
                    $ret['status'] = false;
                    $ret['message'] = "Data Invalid.";
                    echo json_encode($ret);
                    return $ret;
                } else {
                   $decryptedJson = json_decode($decryptedData, true);
                    if( isset($decryptedJson["college_token"])  )
                    {
                        $res = $api->getAllActiveUser($decryptedJson["college_token"]); 
                        if($res && $res['status'])
                        {
                            $ret['status'] = true;
                            $ret['message'] = "Data Fetched.";
                            $ret['data'] = $res['data'];
                            echo json_encode($ret);
                            return $ret;
                        }
                        else
                        {
                            $ret['status'] = false;
                            $ret['message'] = 'Something went wrong.';
                            $ret['error'] = $res['err_msg'];
                            echo json_encode($ret);
                            return $ret;
                        }
                    }
                    else
                    {
                        $ret['status'] = false;
                        $ret['message'] = "Data Missing In Payload.";
                        echo json_encode($ret);
                        return $ret;
                    }
                    
                }
            }
        }
    }
    else
    {
        echo 'Unsupported request method';
    }

    // $users = ['user1', 'user2', 'user3'];
    // echo $requestResponseEncryption->encrypt(json_encode($users));
}

function getAllCollegeWiseQuiz($requestResponseEncryption,$api)
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        $jsonString = file_get_contents('php://input');
        $data = json_decode($jsonString, true);
        // Check if decoding was successful
        if ($data === null)
        {
            $ret['status'] = false;
            $ret['message'] = "Data missing in request.";
            echo json_encode($ret);
            return $ret;
        } else {
            // Access the encrypted data
            $encryptedData = $data['encrypted_data'];
            
            $decryptedData = $requestResponseEncryption->decrypt(base64_decode(base64_encode($encryptedData)));
            if ($decryptedData === false)
            {
                $ret['status'] = false;
                $ret['message'] = "Data Invalid.";
                echo json_encode($ret);
                return $ret;
            } else {
                if ($decryptedData === null) {
                    $ret['status'] = false;
                    $ret['message'] = "Data Invalid.";
                    echo json_encode($ret);
                    return $ret;
                } else {
                   $decryptedJson = json_decode($decryptedData, true);
                    if( isset($decryptedJson["college_token"])  )
                    {
                        $res = $api->getAllCollegeWiseQuiz($decryptedJson["college_token"]); 
                        if($res && $res['status'])
                        {
                            $ret['status'] = true;
                            $ret['message'] = "Data Fetched.";
                            $ret['data'] = $res['data'];
                            echo json_encode($ret);
                            return $ret;
                        }
                        else
                        {
                            $ret['status'] = false;
                            $ret['message'] = 'Something went wrong.';
                            $ret['error'] = $res['err_msg'];
                            echo json_encode($ret);
                            return $ret;
                        }
                    }
                    else
                    {
                        $ret['status'] = false;
                        $ret['message'] = "Data Missing In Payload.";
                        echo json_encode($ret);
                        return $ret;
                    }
                    
                }
            }
        }
    }
    else
    {
        echo 'Unsupported request method';
    }
}

function getQuizDetail($requestResponseEncryption,$api)
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        $jsonString = file_get_contents('php://input');
        $data = json_decode($jsonString, true);
        // Check if decoding was successful
        if ($data === null)
        {
            $ret['status'] = false;
            $ret['message'] = "Data missing in request.";
            echo json_encode($ret);
            return $ret;
        } else {
            // Access the encrypted data
            $encryptedData = $data['encrypted_data'];
            
            $decryptedData = $requestResponseEncryption->decrypt(base64_decode(base64_encode($encryptedData)));
            if ($decryptedData === false)
            {
                $ret['status'] = false;
                $ret['message'] = "Data Invalid.";
                echo json_encode($ret);
                return $ret;
            } else {
                if ($decryptedData === null) {
                    $ret['status'] = false;
                    $ret['message'] = "Data Invalid.";
                    echo json_encode($ret);
                    return $ret;
                } else {
                   $decryptedJson = json_decode($decryptedData, true);
                    if( isset($decryptedJson["college_token"]) && isset($decryptedJson["id"])  )
                    {
                        $res = $api->getQuizDetail($decryptedJson["college_token"],$decryptedJson["id"]); 
                        if($res && $res['status'])
                        {
                            $ret['status'] = true;
                            $ret['message'] = "Data Fetched.";
                            $ret['data'] = $res['data'];
                            echo json_encode($ret);
                            return $ret;
                        }
                        else
                        {
                            $ret['status'] = false;
                            $ret['message'] = 'Something went wrong.';
                            $ret['error'] = $res['err_msg'];
                            echo json_encode($ret);
                            return $ret;
                        }
                    }
                    else
                    {
                        $ret['status'] = false;
                        $ret['message'] = "Data Missing In Payload.";
                        echo json_encode($ret);
                        return $ret;
                    }
                    
                }
            }
        }
    }
    else
    {
        echo 'Unsupported request method';
    }
}

function getAllUserCollegeWise($requestResponseEncryption,$api)
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        $jsonString = file_get_contents('php://input');
        $data = json_decode($jsonString, true);
        // Check if decoding was successful
        if ($data === null)
        {
            $ret['status'] = false;
            $ret['message'] = "Data missing in request.";
            echo json_encode($ret);
            return $ret;
        } else {
            // Access the encrypted data
            $encryptedData = $data['encrypted_data'];
            
            $decryptedData = $requestResponseEncryption->decrypt(base64_decode(base64_encode($encryptedData)));
            if ($decryptedData === false)
            {
                $ret['status'] = false;
                $ret['message'] = "Data Invalid.";
                echo json_encode($ret);
                return $ret;
            } else {
                if ($decryptedData === null) {
                    $ret['status'] = false;
                    $ret['message'] = "Data Invalid.";
                    echo json_encode($ret);
                    return $ret;
                } else {
                   $decryptedJson = json_decode($decryptedData, true);
                    if( isset($decryptedJson["college_token"]) )
                    {
                        $res = $api->getAllUserCollegeWise($decryptedJson["college_token"]); 
                        if($res && $res['status'])
                        {
                            $ret['status'] = true;
                            $ret['message'] = "Data Fetched.";
                            $ret['data'] = $res['data'];
                            echo json_encode($ret);
                            return $ret;
                        }
                        else
                        {
                            $ret['status'] = false;
                            $ret['message'] = 'Something went wrong.';
                            $ret['error'] = $res['err_msg'];
                            echo json_encode($ret);
                            return $ret;
                        }
                    }
                    else
                    {
                        $ret['status'] = false;
                        $ret['message'] = "Data Missing In Payload.";
                        echo json_encode($ret);
                        return $ret;
                    }
                    
                }
            }
        }
    }
    else
    {
        echo 'Unsupported request method';
    }
}

function getAllTeamsCollegeWise($requestResponseEncryption,$api)
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        $jsonString = file_get_contents('php://input');
        $data = json_decode($jsonString, true);
        // Check if decoding was successful
        if ($data === null)
        {
            $ret['status'] = false;
            $ret['message'] = "Data missing in request.";
            echo json_encode($ret);
            return $ret;
        } else {
            // Access the encrypted data
            $encryptedData = $data['encrypted_data'];
            
            $decryptedData = $requestResponseEncryption->decrypt(base64_decode(base64_encode($encryptedData)));
            if ($decryptedData === false)
            {
                $ret['status'] = false;
                $ret['message'] = "Data Invalid.";
                echo json_encode($ret);
                return $ret;
            } else {
                if ($decryptedData === null) {
                    $ret['status'] = false;
                    $ret['message'] = "Data Invalid.";
                    echo json_encode($ret);
                    return $ret;
                } else {
                   $decryptedJson = json_decode($decryptedData, true);
                    if( isset($decryptedJson["college_token"]) && isset($decryptedJson["team_id"]) )
                    {
                        $res = $api->getAllTeamsCollegeWise($decryptedJson["college_token"], $decryptedJson["team_id"]); 
                        if($res && $res['status'])
                        {
                            $ret['status'] = true;
                            $ret['message'] = "Data Fetched.";
                            $ret['data'] = $res['data'];
                            echo json_encode($ret);
                            return $ret;
                        }
                        else
                        {
                            $ret['status'] = false;
                            $ret['message'] = 'Something went wrong.';
                            $ret['error'] = $res['err_msg'];
                            echo json_encode($ret);
                            return $ret;
                        }
                    }
                    else
                    {
                        $ret['status'] = false;
                        $ret['message'] = "Data Missing In Payload.";
                        echo json_encode($ret);
                        return $ret;
                    }
                    
                }
            }
        }
    }
    else
    {
        echo 'Unsupported request method';
    }
}

function getAllAssetGroupCollegeWise($requestResponseEncryption,$api)
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        $jsonString = file_get_contents('php://input');
        $data = json_decode($jsonString, true);
        // Check if decoding was successful
        if ($data === null)
        {
            $ret['status'] = false;
            $ret['message'] = "Data missing in request.";
            echo json_encode($ret);
            return $ret;
        } else {
            // Access the encrypted data
            $encryptedData = $data['encrypted_data'];
            
            $decryptedData = $requestResponseEncryption->decrypt(base64_decode(base64_encode($encryptedData)));
            if ($decryptedData === false)
            {
                $ret['status'] = false;
                $ret['message'] = "Data Invalid.";
                echo json_encode($ret);
                return $ret;
            } else {
                if ($decryptedData === null) {
                    $ret['status'] = false;
                    $ret['message'] = "Data Invalid.";
                    echo json_encode($ret);
                    return $ret;
                } else {
                   $decryptedJson = json_decode($decryptedData, true);
                    if( isset($decryptedJson["college_token"]) )
                    {
                        $res = $api->getAllAssetGroupCollegeWise($decryptedJson["college_token"]); 
                        if($res && $res['status'])
                        {
                            $ret['status'] = true;
                            $ret['message'] = "Data Fetched.";
                            $ret['data'] = $res['data'];
                            echo json_encode($ret);
                            return $ret;
                        }
                        else
                        {
                            $ret['status'] = false;
                            $ret['message'] = 'Something went wrong.';
                            $ret['error'] = $res['err_msg'];
                            echo json_encode($ret);
                            return $ret;
                        }
                    }
                    else
                    {
                        $ret['status'] = false;
                        $ret['message'] = "Data Missing In Payload.";
                        echo json_encode($ret);
                        return $ret;
                    }
                    
                }
            }
        }
    }
    else
    {
        echo 'Unsupported request method';
    }
}

function getTeamName($requestResponseEncryption,$api)
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        $jsonString = file_get_contents('php://input');
        $data = json_decode($jsonString, true);
        // Check if decoding was successful
        if ($data === null)
        {
            $ret['status'] = false;
            $ret['message'] = "Data missing in request.";
            echo json_encode($ret);
            return $ret;
        } else {
            // Access the encrypted data
            $encryptedData = $data['encrypted_data'];
            
            $decryptedData = $requestResponseEncryption->decrypt(base64_decode(base64_encode($encryptedData)));
            if ($decryptedData === false)
            {
                $ret['status'] = false;
                $ret['message'] = "Data Invalid.";
                echo json_encode($ret);
                return $ret;
            } else {
                if ($decryptedData === null) {
                    $ret['status'] = false;
                    $ret['message'] = "Data Invalid.";
                    echo json_encode($ret);
                    return $ret;
                } else {
                   $decryptedJson = json_decode($decryptedData, true);
                    if( isset($decryptedJson["team_id"]) )
                    {
                        $res = $api->getTeamName($decryptedJson["team_id"]); 
                        if($res && $res['status'])
                        {
                            $ret['status'] = true;
                            $ret['message'] = "Data Fetched.";
                            $ret['data'] = $res['data'];
                            echo json_encode($ret);
                            return $ret;
                        }
                        else
                        {
                            $ret['status'] = false;
                            $ret['message'] = 'Something went wrong.';
                            $ret['error'] = $res['err_msg'];
                            echo json_encode($ret);
                            return $ret;
                        }
                    }
                    else
                    {
                        $ret['status'] = false;
                        $ret['message'] = "Data Missing In Payload.";
                        echo json_encode($ret);
                        return $ret;
                    }
                    
                }
            }
        }
    }
    else
    {
        echo 'Unsupported request method';
    }
}

function getGradingRubricCollegeWise($requestResponseEncryption,$api)
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        $jsonString = file_get_contents('php://input');
        $data = json_decode($jsonString, true);
        // Check if decoding was successful
        if ($data === null)
        {
            $ret['status'] = false;
            $ret['message'] = "Data missing in request.";
            echo json_encode($ret);
            return $ret;
        } else {
            // Access the encrypted data
            $encryptedData = $data['encrypted_data'];
            
            $decryptedData = $requestResponseEncryption->decrypt(base64_decode(base64_encode($encryptedData)));
            if ($decryptedData === false)
            {
                $ret['status'] = false;
                $ret['message'] = "Data Invalid.";
                echo json_encode($ret);
                return $ret;
            } else {
                if ($decryptedData === null) {
                    $ret['status'] = false;
                    $ret['message'] = "Data Invalid.";
                    echo json_encode($ret);
                    return $ret;
                } else {
                   $decryptedJson = json_decode($decryptedData, true);
                    if( isset($decryptedJson["college_token"]) )
                    {
                        $res = $api->getGradingRubricCollegeWise($decryptedJson["college_token"]); 
                        if($res && $res['status'])
                        {
                            $ret['status'] = true;
                            $ret['message'] = "Data Fetched.";
                            $ret['data'] = $res['data'];
                            echo json_encode($ret);
                            return $ret;
                        }
                        else
                        {
                            $ret['status'] = false;
                            $ret['message'] = 'Something went wrong.';
                            $ret['error'] = $res['err_msg'];
                            echo json_encode($ret);
                            return $ret;
                        }
                    }
                    else
                    {
                        $ret['status'] = false;
                        $ret['message'] = "Data Missing In Payload.";
                        echo json_encode($ret);
                        return $ret;
                    }
                    
                }
            }
        }
    }
    else
    {
        echo 'Unsupported request method';
    }
}

function getAllSupportCollegeWise($requestResponseEncryption,$api)
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        $jsonString = file_get_contents('php://input');
        $data = json_decode($jsonString, true);
        // Check if decoding was successful
        if ($data === null)
        {
            $ret['status'] = false;
            $ret['message'] = "Data missing in request.";
            echo json_encode($ret);
            return $ret;
        } else {
            // Access the encrypted data
            $encryptedData = $data['encrypted_data'];
            
            $decryptedData = $requestResponseEncryption->decrypt(base64_decode(base64_encode($encryptedData)));
            if ($decryptedData === false)
            {
                $ret['status'] = false;
                $ret['message'] = "Data Invalid.";
                echo json_encode($ret);
                return $ret;
            } else {
                if ($decryptedData === null) {
                    $ret['status'] = false;
                    $ret['message'] = "Data Invalid.";
                    echo json_encode($ret);
                    return $ret;
                } else {
                   $decryptedJson = json_decode($decryptedData, true);
                    if( isset($decryptedJson["college_token"]) )
                    {
                        $res = $api->getAllSupportCollegeWise($decryptedJson["college_token"]); 
                        if($res && $res['status'])
                        {
                            $ret['status'] = true;
                            $ret['message'] = "Data Fetched.";
                            $ret['data'] = $res['data'];
                            echo json_encode($ret);
                            return $ret;
                        }
                        else
                        {
                            $ret['status'] = false;
                            $ret['message'] = 'Something went wrong.';
                            $ret['error'] = $res['err_msg'];
                            echo json_encode($ret);
                            return $ret;
                        }
                    }
                    else
                    {
                        $ret['status'] = false;
                        $ret['message'] = "Data Missing In Payload.";
                        echo json_encode($ret);
                        return $ret;
                    }
                    
                }
            }
        }
    }
    else
    {
        echo 'Unsupported request method';
    }
}

function getAllteamListUserWise($requestResponseEncryption,$api)
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        $jsonString = file_get_contents('php://input');
        $data = json_decode($jsonString, true);
        // Check if decoding was successful
        if ($data === null)
        {
            $ret['status'] = false;
            $ret['message'] = "Data missing in request.";
            echo json_encode($ret);
            return $ret;
        } else {
            // Access the encrypted data
            $encryptedData = $data['encrypted_data'];
            
            $decryptedData = $requestResponseEncryption->decrypt(base64_decode(base64_encode($encryptedData)));
            if ($decryptedData === false)
            {
                $ret['status'] = false;
                $ret['message'] = "Data Invalid.";
                echo json_encode($ret);
                return $ret;
            } else {
                if ($decryptedData === null) {
                    $ret['status'] = false;
                    $ret['message'] = "Data Invalid.";
                    echo json_encode($ret);
                    return $ret;
                } else {
                   $decryptedJson = json_decode($decryptedData, true);
                    if( isset($decryptedJson["college_token"]) )
                    {
                        $res = $api->getAllteamListUserWise($decryptedJson["college_token"]); 
                        if($res && $res['status'])
                        {
                            $ret['status'] = true;
                            $ret['message'] = "Data Fetched.";
                            $ret['data'] = $res['data'];
                            echo json_encode($ret);
                            return $ret;
                        }
                        else
                        {
                            $ret['status'] = false;
                            $ret['message'] = 'Something went wrong.';
                            $ret['error'] = $res['err_msg'];
                            echo json_encode($ret);
                            return $ret;
                        }
                    }
                    else
                    {
                        $ret['status'] = false;
                        $ret['message'] = "Data Missing In Payload.";
                        echo json_encode($ret);
                        return $ret;
                    }
                    
                }
            }
        }
    }
    else
    {
        echo 'Unsupported request method';
    }
}

function createUserCollegeWise($requestResponseEncryption,$api)
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        $jsonString = file_get_contents('php://input');
        $data = json_decode($jsonString, true);
        // Check if decoding was successful
        if ($data === null)
        {
            $ret['status'] = false;
            $ret['message'] = "Data missing in request.";
            echo json_encode($ret);
            return $ret;
        } else {
            // Access the encrypted data
            $encryptedData = $data['encrypted_data'];
            
            $decryptedData = $requestResponseEncryption->decrypt(base64_decode(base64_encode($encryptedData)));
            if ($decryptedData === false)
            {
                $ret['status'] = false;
                $ret['message'] = "Data Invalid.";
                echo json_encode($ret);
                return $ret;
            } else {
                if ($decryptedData === null) {
                    $ret['status'] = false;
                    $ret['message'] = "Data Invalid.";
                    echo json_encode($ret);
                    return $ret;
                } else {
                   $decryptedJson = json_decode($decryptedData, true);
                    if( isset($decryptedJson["college_token"]) )
                    {
                        $college_token = $decryptedJson["college_token"];
                        $username = $decryptedJson["username"];
                        $phone = $decryptedJson["phone"];
                        $email = $decryptedJson["email"];
                        $password = $decryptedJson["password"];
                        $role = $decryptedJson["role"];
                        $fa_preference = $decryptedJson["fa_preference"];
                        $restrict = $decryptedJson["restrict"];
                        $assistant_permissions = $decryptedJson["assistant_permissions"];
                        
                        $res = $api->createUserCollegeWise($college_token,$username,$phone,$email,$password,$role,$fa_preference,$restrict,$assistant_permissions); 

                        if($res && $res['status'])
                        {
                            $ret['status'] = true;
                            $ret['message'] = "Data Fetched.";
                            $ret['data'] = $res['data'];
                            echo json_encode($ret);
                            return $ret;
                        }
                        else
                        {
                            $ret['status'] = false;
                            $ret['message'] = 'Something went wrong.';
                            $ret['error'] = $res['err_msg'];
                            echo json_encode($ret);
                            return $ret;
                        }
                    }
                    else
                    {
                        $ret['status'] = false;
                        $ret['message'] = "Data Missing In Payload.";
                        echo json_encode($ret);
                        return $ret;
                    }
                    
                }
            }
        }
    }
    else
    {
        echo 'Unsupported request method';
    }
}

function createTeamCollegeWise($requestResponseEncryption,$api)
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        $jsonString = file_get_contents('php://input');
        $data = json_decode($jsonString, true);
        // Check if decoding was successful
        if ($data === null)
        {
            $ret['status'] = false;
            $ret['message'] = "Data missing in request.";
            echo json_encode($ret);
            return $ret;
        } else {
            // Access the encrypted data
            $encryptedData = $data['encrypted_data'];
            
            $decryptedData = $requestResponseEncryption->decrypt(base64_decode(base64_encode($encryptedData)));
            if ($decryptedData === false)
            {
                $ret['status'] = false;
                $ret['message'] = "Data Invalid.";
                echo json_encode($ret);
                return $ret;
            } else {
                if ($decryptedData === null) {
                    $ret['status'] = false;
                    $ret['message'] = "Data Invalid.";
                    echo json_encode($ret);
                    return $ret;
                } else {
                   $decryptedJson = json_decode($decryptedData, true);
                    if( isset($decryptedJson["college_token"]) )
                    {
                        $college_token = $decryptedJson["college_token"];
                        $name = $decryptedJson["name"];
                        $color = $decryptedJson["color"];
                        $str_result = $decryptedJson["str_result"];
                        $unique_key = $decryptedJson["unique_key"];
                        
                        $res = $api->createTeamCollegeWise($college_token,$name,$color); 

                        if($res && $res['status'])
                        {
                            $ret['status'] = true;
                            $ret['message'] = "Data Fetched.";
                            $ret['data'] = $res['data'];
                            echo json_encode($ret);
                            return $ret;
                        }
                        else
                        {
                            $ret['status'] = false;
                            $ret['message'] = 'Something went wrong.';
                            $ret['error'] = $res['err_msg'];
                            echo json_encode($ret);
                            return $ret;
                        }
                    }
                    else
                    {
                        $ret['status'] = false;
                        $ret['message'] = "Data Missing In Payload.";
                        echo json_encode($ret);
                        return $ret;
                    }
                    
                }
            }
        }
    }
    else
    {
        echo 'Unsupported request method';
    }
}

function getAllSystemAsset($requestResponseEncryption,$api)
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        $jsonString = file_get_contents('php://input');
        $data = json_decode($jsonString, true);
        // Check if decoding was successful
        if ($data === null)
        {
            $ret['status'] = false;
            $ret['message'] = "Data missing in request.";
            echo json_encode($ret);
            return $ret;
        } else {
            // Access the encrypted data
            $encryptedData = $data['encrypted_data'];
            
            $decryptedData = $requestResponseEncryption->decrypt(base64_decode(base64_encode($encryptedData)));
            if ($decryptedData === false)
            {
                $ret['status'] = false;
                $ret['message'] = "Data Invalid.";
                echo json_encode($ret);
                return $ret;
            } else {
                if ($decryptedData === null) {
                    $ret['status'] = false;
                    $ret['message'] = "Data Invalid.";
                    echo json_encode($ret);
                    return $ret;
                } else {
                   $decryptedJson = json_decode($decryptedData, true);
                    if( isset($decryptedJson["college_token"]) )
                    {
                        $res = $api->getAllSystemAsset($decryptedJson["college_token"]); 
                        if($res && $res['status'])
                        {
                            $ret['status'] = true;
                            $ret['message'] = "Data Fetched.";
                            $ret['data'] = $res['data'];
                            echo json_encode($ret);
                            return $ret;
                        }
                        else
                        {
                            $ret['status'] = false;
                            $ret['message'] = 'Something went wrong.';
                            $ret['error'] = $res['err_msg'];
                            echo json_encode($ret);
                            return $ret;
                        }
                    }
                    else
                    {
                        $ret['status'] = false;
                        $ret['message'] = "Data Missing In Payload.";
                        echo json_encode($ret);
                        return $ret;
                    }
                    
                }
            }
        }
    }
    else
    {
        echo 'Unsupported request method';
    }
}

function getAllGradingRubricCollegeWise($requestResponseEncryption,$api)
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        $jsonString = file_get_contents('php://input');
        $data = json_decode($jsonString, true);
        // Check if decoding was successful
        if ($data === null)
        {
            $ret['status'] = false;
            $ret['message'] = "Data missing in request.";
            echo json_encode($ret);
            return $ret;
        } else {
            // Access the encrypted data
            $encryptedData = $data['encrypted_data'];
            
            $decryptedData = $requestResponseEncryption->decrypt(base64_decode(base64_encode($encryptedData)));
            if ($decryptedData === false)
            {
                $ret['status'] = false;
                $ret['message'] = "Data Invalid.";
                echo json_encode($ret);
                return $ret;
            } else {
                if ($decryptedData === null) {
                    $ret['status'] = false;
                    $ret['message'] = "Data Invalid.";
                    echo json_encode($ret);
                    return $ret;
                } else {
                   $decryptedJson = json_decode($decryptedData, true);
                    if( isset($decryptedJson["college_token"]) )
                    {
                        $res = $api->getAllGradingRubricCollegeWise($decryptedJson["college_token"]); 
                        if($res && $res['status'])
                        {
                            $ret['status'] = true;
                            $ret['message'] = "Data Fetched.";
                            $ret['data'] = $res['data'];
                            echo json_encode($ret);
                            return $ret;
                        }
                        else
                        {
                            $ret['status'] = false;
                            $ret['message'] = 'Something went wrong.';
                            $ret['error'] = $res['err_msg'];
                            echo json_encode($ret);
                            return $ret;
                        }
                    }
                    else
                    {
                        $ret['status'] = false;
                        $ret['message'] = "Data Missing In Payload.";
                        echo json_encode($ret);
                        return $ret;
                    }
                    
                }
            }
        }
    }
    else
    {
        echo 'Unsupported request method';
    }
}

function getAllCollegeList($requestResponseEncryption,$api)
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        $jsonString = file_get_contents('php://input');
        $data = json_decode($jsonString, true);
        // Check if decoding was successful
        if ($data === null)
        {
            $ret['status'] = false;
            $ret['message'] = "Data missing in request.";
            echo json_encode($ret);
            return $ret;
        }
        else
        {
            $res = $api->getAllCollegeList(); 
            if($res && $res['status'])
            {
                $ret['status'] = true;
                $ret['message'] = "Data Fetched.";
                $ret['data'] = $res['data'];
                echo json_encode($ret);
                return $ret;
            }
            else
            {
                $ret['status'] = false;
                $ret['message'] = 'Something went wrong.';
                $ret['error'] = $res['err_msg'];
                echo json_encode($ret);
                return $ret;
            }
        }
    }
    else
    {
        echo 'Unsupported request method';
    }
}

function getticketDetail($requestResponseEncryption,$api)
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        $jsonString = file_get_contents('php://input');
        $data = json_decode($jsonString, true);
        // Check if decoding was successful
        if ($data === null)
        {
            $ret['status'] = false;
            $ret['message'] = "Data missing in request.";
            echo json_encode($ret);
            return $ret;
        } else {
            // Access the encrypted data
            $encryptedData = $data['encrypted_data'];
            
            $decryptedData = $requestResponseEncryption->decrypt(base64_decode(base64_encode($encryptedData)));
            if ($decryptedData === false)
            {
                $ret['status'] = false;
                $ret['message'] = "Data Invalid.";
                echo json_encode($ret);
                return $ret;
            } else {
                if ($decryptedData === null) {
                    $ret['status'] = false;
                    $ret['message'] = "Data Invalid.";
                    echo json_encode($ret);
                    return $ret;
                } else {
                   $decryptedJson = json_decode($decryptedData, true);
                    if( isset($decryptedJson["ticketId"]) )
                    {
                        $res = $api->getticketDetail($decryptedJson["ticketId"]); 
                        if($res && $res['status'])
                        {
                            $ret['status'] = true;
                            $ret['message'] = "Data Fetched.";
                            $ret['data'] = $res['data'];
                            $ret['ticketInfo'] = $res['ticketInfo'];
                            echo json_encode($ret);
                            return $ret;
                        }
                        else
                        {
                            $ret['status'] = false;
                            $ret['message'] = 'Something went wrong.';
                            $ret['error'] = $res['err_msg'];
                            echo json_encode($ret);
                            return $ret;
                        }
                    }
                    else
                    {
                        $ret['status'] = false;
                        $ret['message'] = "Data Missing In Payload.";
                        echo json_encode($ret);
                        return $ret;
                    }
                    
                }
            }
        }
    }
    else
    {
        echo 'Unsupported request method';
    }
}

function getQuizPlayedList($requestResponseEncryption,$api)
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        $jsonString = file_get_contents('php://input');
        $data = json_decode($jsonString, true);
        // Check if decoding was successful
        if ($data === null)
        {
            $ret['status'] = false;
            $ret['message'] = "Data missing in request.";
            echo json_encode($ret);
            return $ret;
        } else {
            // Access the encrypted data
            $encryptedData = $data['encrypted_data'];
            
            $decryptedData = $requestResponseEncryption->decrypt(base64_decode(base64_encode($encryptedData)));
            if ($decryptedData === false)
            {
                $ret['status'] = false;
                $ret['message'] = "Data Invalid.";
                echo json_encode($ret);
                return $ret;
            } else {
                if ($decryptedData === null) {
                    $ret['status'] = false;
                    $ret['message'] = "Data Invalid.";
                    echo json_encode($ret);
                    return $ret;
                } else {
                   $decryptedJson = json_decode($decryptedData, true);
                    if( isset($decryptedJson["quizeId"]) )
                    {
                        $res = $api->getQuizPlayedList($decryptedJson["quizeId"]); 
                        if($res && $res['status'])
                        {
                            $ret['status'] = true;
                            $ret['message'] = "Data Fetched.";
                            $ret['data'] = $res['data'];
                            echo json_encode($ret);
                            return $ret;
                        }
                        else
                        {
                            $ret['status'] = false;
                            $ret['message'] = 'Something went wrong.';
                            $ret['error'] = $res['err_msg'];
                            echo json_encode($ret);
                            return $ret;
                        }
                    }
                    else
                    {
                        $ret['status'] = false;
                        $ret['message'] = "Data Missing In Payload.";
                        echo json_encode($ret);
                        return $ret;
                    }
                    
                }
            }
        }
    }
    else
    {
        echo 'Unsupported request method';
    }
}

function updateQuizStatus($requestResponseEncryption,$api)
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        $jsonString = file_get_contents('php://input');
        $data = json_decode($jsonString, true);
        // Check if decoding was successful
        if ($data === null)
        {
            $ret['status'] = false;
            $ret['message'] = "Data missing in request.";
            echo json_encode($ret);
            return $ret;
        } else {
            // Access the encrypted data
            $encryptedData = $data['encrypted_data'];
            
            $decryptedData = $requestResponseEncryption->decrypt(base64_decode(base64_encode($encryptedData)));
            if ($decryptedData === false)
            {
                $ret['status'] = false;
                $ret['message'] = "Data Invalid.";
                echo json_encode($ret);
                return $ret;
            } else {
                if ($decryptedData === null) {
                    $ret['status'] = false;
                    $ret['message'] = "Data Invalid.";
                    echo json_encode($ret);
                    return $ret;
                } else {
                   $decryptedJson = json_decode($decryptedData, true);
                    if( isset($decryptedJson["college_token"]) && isset($decryptedJson["quiz_id"]) )
                    {
                        $res = $api->updateQuizStatus( $decryptedJson["college_token"], $decryptedJson["quiz_id"]); 
                        if($res && $res['status'])
                        {
                            $ret['status'] = true;
                            $ret['message'] = "Data Fetched.";
                            $ret['data'] = $res['data'];
                            echo json_encode($ret);
                            return $ret;
                        }
                        else
                        {
                            $ret['status'] = false;
                            $ret['message'] = 'Something went wrong.';
                            $ret['error'] = $res['err_msg'];
                            echo json_encode($ret);
                            return $ret;
                        }
                    }
                    else
                    {
                        $ret['status'] = false;
                        $ret['message'] = "Data Missing In Payload.";
                        echo json_encode($ret);
                        return $ret;
                    }
                    
                }
            }
        }
    }
    else
    {
        echo 'Unsupported request method';
    }
}

function openTicket($requestResponseEncryption,$api)
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        $jsonString = file_get_contents('php://input');
        $data = json_decode($jsonString, true);
        // Check if decoding was successful
        if ($data === null)
        {
            $ret['status'] = false;
            $ret['message'] = "Data missing in request.";
            echo json_encode($ret);
            return $ret;
        } else {
            // Access the encrypted data
            $encryptedData = $data['encrypted_data'];
            
            $decryptedData = $requestResponseEncryption->decrypt(base64_decode(base64_encode($encryptedData)));
            if ($decryptedData === false)
            {
                $ret['status'] = false;
                $ret['message'] = "Data Invalid.";
                echo json_encode($ret);
                return $ret;
            } else {
                if ($decryptedData === null) {
                    $ret['status'] = false;
                    $ret['message'] = "Data Invalid.";
                    echo json_encode($ret);
                    return $ret;
                } else {
                   $decryptedJson = json_decode($decryptedData, true);
                    if(isset($decryptedJson["ticketId"]) )
                    {
                        $res = $api->openTicket( $decryptedJson["ticketId"]); 
                        if($res && $res['status'])
                        {
                            $ret['status'] = true;
                            $ret['message'] = "Data Fetched.";
                            $ret['data'] = $res['data'];
                            echo json_encode($ret);
                            return $ret;
                        }
                        else
                        {
                            $ret['status'] = false;
                            $ret['message'] = 'Something went wrong.';
                            $ret['error'] = $res['err_msg'];
                            echo json_encode($ret);
                            return $ret;
                        }
                    }
                    else
                    {
                        $ret['status'] = false;
                        $ret['message'] = "Data Missing In Payload.";
                        echo json_encode($ret);
                        return $ret;
                    }
                    
                }
            }
        }
    }
    else
    {
        echo 'Unsupported request method';
    }
}

function closeTicket($requestResponseEncryption,$api)
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        $jsonString = file_get_contents('php://input');
        $data = json_decode($jsonString, true);
        // Check if decoding was successful
        if ($data === null)
        {
            $ret['status'] = false;
            $ret['message'] = "Data missing in request.";
            echo json_encode($ret);
            return $ret;
        } else {
            // Access the encrypted data
            $encryptedData = $data['encrypted_data'];
            
            $decryptedData = $requestResponseEncryption->decrypt(base64_decode(base64_encode($encryptedData)));
            if ($decryptedData === false)
            {
                $ret['status'] = false;
                $ret['message'] = "Data Invalid.";
                echo json_encode($ret);
                return $ret;
            } else {
                if ($decryptedData === null) {
                    $ret['status'] = false;
                    $ret['message'] = "Data Invalid.";
                    echo json_encode($ret);
                    return $ret;
                } else {
                   $decryptedJson = json_decode($decryptedData, true);
                    if(isset($decryptedJson["ticketId"]) )
                    {
                        $res = $api->closeTicket( $decryptedJson["ticketId"]); 
                        if($res && $res['status'])
                        {
                            $ret['status'] = true;
                            $ret['message'] = "Data Fetched.";
                            $ret['data'] = $res['data'];
                            echo json_encode($ret);
                            return $ret;
                        }
                        else
                        {
                            $ret['status'] = false;
                            $ret['message'] = 'Something went wrong.';
                            $ret['error'] = $res['err_msg'];
                            echo json_encode($ret);
                            return $ret;
                        }
                    }
                    else
                    {
                        $ret['status'] = false;
                        $ret['message'] = "Data Missing In Payload.";
                        echo json_encode($ret);
                        return $ret;
                    }
                    
                }
            }
        }
    }
    else
    {
        echo 'Unsupported request method';
    }
}

function ticketResponse($requestResponseEncryption,$api)
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        $jsonString = file_get_contents('php://input');
        $data = json_decode($jsonString, true);
        // Check if decoding was successful
        if ($data === null)
        {
            $ret['status'] = false;
            $ret['message'] = "Data missing in request.";
            echo json_encode($ret);
            return $ret;
        } else {
            // Access the encrypted data
            $encryptedData = $data['encrypted_data'];
            
            $decryptedData = $requestResponseEncryption->decrypt(base64_decode(base64_encode($encryptedData)));
            if ($decryptedData === false)
            {
                $ret['status'] = false;
                $ret['message'] = "Data Invalid.";
                echo json_encode($ret);
                return $ret;
            } else {
                if ($decryptedData === null) {
                    $ret['status'] = false;
                    $ret['message'] = "Data Invalid.";
                    echo json_encode($ret);
                    return $ret;
                } else {
                   $decryptedJson = json_decode($decryptedData, true);
                    if( isset($decryptedJson["college_token"]) )
                    {
                        $college_token = $decryptedJson["college_token"];
                        $ticketID = $decryptedJson["ticketID"];
                        $userID = $decryptedJson["userID"];
                        $response = $decryptedJson["response"];
                        $time = $decryptedJson["time"];
                        
                        $res = $api->ticketResponse($college_token, $ticketID, $userID, $response, $time); 

                        if($res && $res['status'])
                        {
                            $ret['status'] = true;
                            $ret['message'] = "Data Fetched.";
                            $ret['data'] = $res['data'];
                            echo json_encode($ret);
                            return $ret;
                        }
                        else
                        {
                            $ret['status'] = false;
                            $ret['message'] = 'Something went wrong.';
                            $ret['error'] = $res['err_msg'];
                            echo json_encode($ret);
                            return $ret;
                        }
                    }
                    else
                    {
                        $ret['status'] = false;
                        $ret['message'] = "Data Missing In Payload.";
                        echo json_encode($ret);
                        return $ret;
                    }
                    
                }
            }
        }
    }
    else
    {
        echo 'Unsupported request method';
    }
}

function editUser($requestResponseEncryption,$api)
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        $jsonString = file_get_contents('php://input');
        $data = json_decode($jsonString, true);
        // Check if decoding was successful
        if ($data === null)
        {
            $ret['status'] = false;
            $ret['message'] = "Data missing in request.";
            echo json_encode($ret);
            return $ret;
        } else {
            // Access the encrypted data
            $encryptedData = $data['encrypted_data'];
            
            $decryptedData = $requestResponseEncryption->decrypt(base64_decode(base64_encode($encryptedData)));
            if ($decryptedData === false)
            {
                $ret['status'] = false;
                $ret['message'] = "Data Invalid.";
                echo json_encode($ret);
                return $ret;
            } else {
                if ($decryptedData === null) {
                    $ret['status'] = false;
                    $ret['message'] = "Data Invalid.";
                    echo json_encode($ret);
                    return $ret;
                } else {
                   $decryptedJson = json_decode($decryptedData, true);
                    if( isset($decryptedJson["college_token"]) && isset($decryptedJson["edit"]) )
                    {
                        $res = $api->editUser( $decryptedJson["college_token"], $decryptedJson["edit"] ); 
                        if($res && $res['status'])
                        {
                            $ret['status'] = true;
                            $ret['message'] = "Data Fetched.";
                            $ret['data'] = $res['data'];
                            $ret['teamList'] = $res['teamList'];
                            $ret['assistant_data'] = $res['assistant_data'];
                            $ret['user_id'] = $res['user_id'];
                            echo json_encode($ret);
                            return $ret;
                        }
                        else
                        {
                            $ret['status'] = false;
                            $ret['message'] = 'Something went wrong.';
                            $ret['error'] = $res['err_msg'];
                            echo json_encode($ret);
                            return $ret;
                        }
                    }
                    else
                    {
                        $ret['status'] = false;
                        $ret['message'] = "Data Missing In Payload.";
                        echo json_encode($ret);
                        return $ret;
                    }
                    
                }
            }
        }
    }
    else
    {
        echo 'Unsupported request method';
    }
}

function editRubric($requestResponseEncryption,$api)
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        $jsonString = file_get_contents('php://input');
        $data = json_decode($jsonString, true);
        // Check if decoding was successful
        if ($data === null)
        {
            $ret['status'] = false;
            $ret['message'] = "Data missing in request.";
            echo json_encode($ret);
            return $ret;
        } else {
            // Access the encrypted data
            $encryptedData = $data['encrypted_data'];
            
            $decryptedData = $requestResponseEncryption->decrypt(base64_decode(base64_encode($encryptedData)));
            if ($decryptedData === false)
            {
                $ret['status'] = false;
                $ret['message'] = "Data Invalid.";
                echo json_encode($ret);
                return $ret;
            } else {
                if ($decryptedData === null) {
                    $ret['status'] = false;
                    $ret['message'] = "Data Invalid.";
                    echo json_encode($ret);
                    return $ret;
                } else {
                   $decryptedJson = json_decode($decryptedData, true);
                    if( isset($decryptedJson["college_token"]) && isset($decryptedJson["edit"]) )
                    {
                        $res = $api->editRubric( $decryptedJson["college_token"], $decryptedJson["edit"] ); 
                        if($res && $res['status'])
                        {
                            $ret['status'] = true;
                            $ret['message'] = "Data Fetched.";
                            $ret['data'] = $res['data'];
                            $ret['collegeList'] = $res['collegeList'];
                            echo json_encode($ret);
                            return $ret;
                        }
                        else
                        {
                            $ret['status'] = false;
                            $ret['message'] = 'Something went wrong.';
                            $ret['error'] = $res['err_msg'];
                            echo json_encode($ret);
                            return $ret;
                        }
                    }
                    else
                    {
                        $ret['status'] = false;
                        $ret['message'] = "Data Missing In Payload.";
                        echo json_encode($ret);
                        return $ret;
                    }
                    
                }
            }
        }
    }
    else
    {
        echo 'Unsupported request method';
    }
}

function updateRubric($requestResponseEncryption,$api)
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        $jsonString = file_get_contents('php://input');
        $data = json_decode($jsonString, true);


        // Check if decoding was successful
        if ($data === null)
        {
            $ret['status'] = false;
            $ret['message'] = "Data missing in request.";
            echo json_encode($ret);
            return $ret;
        } else {
            // Access the encrypted data
            $encryptedData = $data['encrypted_data'];
            
            $decryptedData = $requestResponseEncryption->decrypt(base64_decode(base64_encode($encryptedData)));


            if ($decryptedData === false)
            {
                $ret['status'] = false;
                $ret['message'] = "Data Invalid.";
                echo json_encode($ret);
                return $ret;
            } else {
                if ($decryptedData === null) {
                    $ret['status'] = false;
                    $ret['message'] = "Data Invalid.";
                    echo json_encode($ret);
                    return $ret;
                } else {

                   $decryptedJson = json_decode($decryptedData, true);

                    if( isset($decryptedJson["college_token"]) )
                    {
                        $update =  $decryptedJson["update"];
                        $title =  $decryptedJson["title"];
                        $detail =  $decryptedJson["detail"]; 
                        $purpleteam_grade =  $decryptedJson["purpleteam_grade"]; 
                        $redteam_grade =  $decryptedJson["redteam_grade"]; 
                        $blueteam_grade =  $decryptedJson["blueteam_grade"];  
                        $assigned_user =  $decryptedJson["assigned_user"];

                        $res = $api->updateRubric( $decryptedJson["college_token"], $update, $title, $detail, $purpleteam_grade, $redteam_grade, $blueteam_grade, $assigned_user ); 
                        if($res && $res['status'])
                        {
                            $ret['status'] = true;
                            $ret['message'] = "Data Fetched.";
                            $ret['data'] = $res['data'];
                            echo json_encode($ret);
                            return $ret;
                        }
                        else
                        {
                            $ret['status'] = false;
                            $ret['message'] = 'Something went wrong.';
                            $ret['error'] = $res['err_msg'];
                            echo json_encode($ret);
                            return $ret;
                        }
                    }
                    else
                    {
                        $ret['status'] = false;
                        $ret['message'] = "Data Missing In Payload.";
                        echo json_encode($ret);
                        return $ret;
                    }
                    
                }
            }
        }
    }
    else
    {
        echo 'Unsupported request method';
    }
}

function createRubric($requestResponseEncryption,$api)
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        $jsonString = file_get_contents('php://input');
        $data = json_decode($jsonString, true);
        // Check if decoding was successful
        if ($data === null)
        {
            $ret['status'] = false;
            $ret['message'] = "Data missing in request.";
            echo json_encode($ret);
            return $ret;
        } else {
            // Access the encrypted data
            $encryptedData = $data['encrypted_data'];
            
            $decryptedData = $requestResponseEncryption->decrypt(base64_decode(base64_encode($encryptedData)));
            if ($decryptedData === false)
            {
                $ret['status'] = false;
                $ret['message'] = "Data Invalid.";
                echo json_encode($ret);
                return $ret;
            } else {
                if ($decryptedData === null) {
                    $ret['status'] = false;
                    $ret['message'] = "Data Invalid.";
                    echo json_encode($ret);
                    return $ret;
                } else {
                   $decryptedJson = json_decode($decryptedData, true);
                    if( isset($decryptedJson["college_token"]) )
                    {
                        $college_token = $decryptedJson["college_token"];
                        $title = $decryptedJson["title"];
                        $detail = $decryptedJson["detail"];
                        $purpleteam_grade = $decryptedJson["purpleteam_grade"];
                        $redteam_grade = $decryptedJson["redteam_grade"];
                        $blueteam_grade = $decryptedJson["blueteam_grade"];
                        $assigned_user = $decryptedJson["assigned_user"];
                        
                        $res = $api->createRubric($college_token,$title,$detail,$purpleteam_grade,$redteam_grade,$blueteam_grade,$assigned_user); 

                        if($res && $res['status'])
                        {
                            $ret['status'] = true;
                            $ret['message'] = "Data Fetched.";
                            $ret['data'] = $res['data'];
                            echo json_encode($ret);
                            return $ret;
                        }
                        else
                        {
                            $ret['status'] = false;
                            $ret['message'] = 'Something went wrong.';
                            $ret['error'] = $res['err_msg'];
                            echo json_encode($ret);
                            return $ret;
                        }
                    }
                    else
                    {
                        $ret['status'] = false;
                        $ret['message'] = "Data Missing In Payload.";
                        echo json_encode($ret);
                        return $ret;
                    }
                    
                }
            }
        }
    }
    else
    {
        echo 'Unsupported request method';
    }
}

function updateUser($requestResponseEncryption,$api)
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        $jsonString = file_get_contents('php://input');
        $data = json_decode($jsonString, true);
        // Check if decoding was successful
        if ($data === null)
        {
            $ret['status'] = false;
            $ret['message'] = "Data missing in request.";
            echo json_encode($ret);
            return $ret;
        } else {
            // Access the encrypted data
            $encryptedData = $data['encrypted_data'];
            
            $decryptedData = $requestResponseEncryption->decrypt(base64_decode(base64_encode($encryptedData)));
            if ($decryptedData === false)
            {
                $ret['status'] = false;
                $ret['message'] = "Data Invalid.";
                echo json_encode($ret);
                return $ret;
            } else {
                if ($decryptedData === null) {
                    $ret['status'] = false;
                    $ret['message'] = "Data Invalid.";
                    echo json_encode($ret);
                    return $ret;
                } else {
                   $decryptedJson = json_decode($decryptedData, true);
                    if( isset($decryptedJson["college_token"]) )
                    {
                        $college_token = $decryptedJson["college_token"];
                        $username = $decryptedJson["username"];
                        $phone = $decryptedJson["phone"];
                        $email = $decryptedJson["email"];
                        $password = $decryptedJson["password"];
                        $role = $decryptedJson["role"];
                        $fa_preference = $decryptedJson["fa_preference"];
                        $restrict = $decryptedJson["restrict"];
                        $assistant_permissions = $decryptedJson["assistant_permissions"];
                        $update_id = $decryptedJson["update_id"];
                        
                        $res = $api->updateUser($college_token,$username,$phone,$email,$password,$role,$fa_preference,$restrict,$assistant_permissions, $update_id); 

                        if($res && $res['status'])
                        {
                            $ret['status'] = true;
                            $ret['message'] = "Data Fetched.";
                            $ret['data'] = $res['data'];
                            echo json_encode($ret);
                            return $ret;
                        }
                        else
                        {
                            $ret['status'] = false;
                            $ret['message'] = 'Something went wrong.';
                            $ret['error'] = $res['err_msg'];
                            echo json_encode($ret);
                            return $ret;
                        }
                    }
                    else
                    {
                        $ret['status'] = false;
                        $ret['message'] = "Data Missing In Payload.";
                        echo json_encode($ret);
                        return $ret;
                    }
                    
                }
            }
        }
    }
    else
    {
        echo 'Unsupported request method';
    }
}

function updateTeam($requestResponseEncryption,$api)
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        $jsonString = file_get_contents('php://input');
        $data = json_decode($jsonString, true);
        // Check if decoding was successful
        if ($data === null)
        {
            $ret['status'] = false;
            $ret['message'] = "Data missing in request.";
            echo json_encode($ret);
            return $ret;
        } else {
            // Access the encrypted data
            $encryptedData = $data['encrypted_data'];
            
            $decryptedData = $requestResponseEncryption->decrypt(base64_decode(base64_encode($encryptedData)));
            if ($decryptedData === false)
            {
                $ret['status'] = false;
                $ret['message'] = "Data Invalid.";
                echo json_encode($ret);
                return $ret;
            } else {
                if ($decryptedData === null) {
                    $ret['status'] = false;
                    $ret['message'] = "Data Invalid.";
                    echo json_encode($ret);
                    return $ret;
                } else {
                   $decryptedJson = json_decode($decryptedData, true);
                    if( isset($decryptedJson["college_token"]) )
                    {
                        $college_token = $decryptedJson["college_token"];
                        $update = $decryptedJson["update"];
                        $name = $decryptedJson["name"];
                        $color = $decryptedJson["color"];
                        
                        $res = $api->updateTeam($college_token,$name,$color,$update); 

                        if($res && $res['status'])
                        {
                            $ret['status'] = true;
                            $ret['message'] = "Data Updated Successfully.";
                            $ret['data'] = $res['data'];
                            echo json_encode($ret);
                            return $ret;
                        }
                        else
                        {
                            $ret['status'] = false;
                            $ret['message'] = 'Something went wrong.';
                            $ret['error'] = $res['err_msg'];
                            echo json_encode($ret);
                            return $ret;
                        }
                    }
                    else
                    {
                        $ret['status'] = false;
                        $ret['message'] = "Data Missing In Payload.";
                        echo json_encode($ret);
                        return $ret;
                    }
                    
                }
            }
        }
    }
    else
    {
        echo 'Unsupported request method';
    }
}

function userDeleteById($requestResponseEncryption,$api)
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        $jsonString = file_get_contents('php://input');
        $data = json_decode($jsonString, true);
        // Check if decoding was successful
        if ($data === null)
        {
            $ret['status'] = false;
            $ret['message'] = "Data missing in request.";
            echo json_encode($ret);
            return $ret;
        } else {
            // Access the encrypted data
            $encryptedData = $data['encrypted_data'];
            
            $decryptedData = $requestResponseEncryption->decrypt(base64_decode(base64_encode($encryptedData)));
            if ($decryptedData === false)
            {
                $ret['status'] = false;
                $ret['message'] = "Data Invalid.";
                echo json_encode($ret);
                return $ret;
            } else {
                if ($decryptedData === null) {
                    $ret['status'] = false;
                    $ret['message'] = "Data Invalid.";
                    echo json_encode($ret);
                    return $ret;
                } else {
                   $decryptedJson = json_decode($decryptedData, true);
                    if( isset($decryptedJson["user_id"]) )
                    {
                        $user_id = $decryptedJson["user_id"];
                        $res = $api->userDeleteById($user_id); 

                        if($res && $res['status'])
                        {
                            $ret['status'] = true;
                            $ret['message'] = "Data Deleted Successfully.";
                            $ret['data'] = $res['data'];
                            echo json_encode($ret);
                            return $ret;
                        }
                        else
                        {
                            $ret['status'] = false;
                            $ret['message'] = 'Something went wrong.';
                            $ret['error'] = $res['err_msg'];
                            echo json_encode($ret);
                            return $ret;
                        }
                    }
                    else
                    {
                        $ret['status'] = false;
                        $ret['message'] = "Data Missing In Payload.";
                        echo json_encode($ret);
                        return $ret;
                    }
                    
                }
            }
        }
    }
    else
    {
        echo 'Unsupported request method';
    }
}

function ticketDeleteById($requestResponseEncryption,$api)
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        $jsonString = file_get_contents('php://input');
        $data = json_decode($jsonString, true);
        // Check if decoding was successful
        if ($data === null)
        {
            $ret['status'] = false;
            $ret['message'] = "Data missing in request.";
            echo json_encode($ret);
            return $ret;
        } else {
            // Access the encrypted data
            $encryptedData = $data['encrypted_data'];
            
            $decryptedData = $requestResponseEncryption->decrypt(base64_decode(base64_encode($encryptedData)));
            if ($decryptedData === false)
            {
                $ret['status'] = false;
                $ret['message'] = "Data Invalid.";
                echo json_encode($ret);
                return $ret;
            } else {
                if ($decryptedData === null) {
                    $ret['status'] = false;
                    $ret['message'] = "Data Invalid.";
                    echo json_encode($ret);
                    return $ret;
                } else {
                   $decryptedJson = json_decode($decryptedData, true);
                    if( isset($decryptedJson["user_id"]) )
                    {
                        $ticketID = $decryptedJson["ticketID"];
                        $res = $api->ticketDeleteById($ticketID); 

                        if($res && $res['status'])
                        {
                            $ret['status'] = true;
                            $ret['message'] = "Data Deleted Successfully.";
                            $ret['data'] = $res['data'];
                            echo json_encode($ret);
                            return $ret;
                        }
                        else
                        {
                            $ret['status'] = false;
                            $ret['message'] = 'Something went wrong.';
                            $ret['error'] = $res['err_msg'];
                            echo json_encode($ret);
                            return $ret;
                        }
                    }
                    else
                    {
                        $ret['status'] = false;
                        $ret['message'] = "Data Missing In Payload.";
                        echo json_encode($ret);
                        return $ret;
                    }
                    
                }
            }
        }
    }
    else
    {
        echo 'Unsupported request method';
    }
}

function deleteTenant($requestResponseEncryption,$api)
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        $jsonString = file_get_contents('php://input');
        $data = json_decode($jsonString, true);
        // Check if decoding was successful
        if ($data === null)
        {
            $ret['status'] = false;
            $ret['message'] = "Data missing in request.";
            echo json_encode($ret);
            return $ret;
        } else {
            // Access the encrypted data
            $encryptedData = $data['encrypted_data'];
            
            $decryptedData = $requestResponseEncryption->decrypt(base64_decode(base64_encode($encryptedData)));
            if ($decryptedData === false)
            {
                $ret['status'] = false;
                $ret['message'] = "Data Invalid.";
                echo json_encode($ret);
                return $ret;
            } else {
                if ($decryptedData === null) {
                    $ret['status'] = false;
                    $ret['message'] = "Data Invalid.";
                    echo json_encode($ret);
                    return $ret;
                } else {
                   $decryptedJson = json_decode($decryptedData, true);
                    if( isset($decryptedJson["user_id"]) )
                    {
                        $token = $decryptedJson["token"];
                        $res = $api->deleteTenant($token); 

                        if($res && $res['status'])
                        {
                            $ret['status'] = true;
                            $ret['message'] = "Data Deleted Successfully.";
                            $ret['data'] = $res['data'];
                            echo json_encode($ret);
                            return $ret;
                        }
                        else
                        {
                            $ret['status'] = false;
                            $ret['message'] = 'Something went wrong.';
                            $ret['error'] = $res['err_msg'];
                            echo json_encode($ret);
                            return $ret;
                        }
                    }
                    else
                    {
                        $ret['status'] = false;
                        $ret['message'] = "Data Missing In Payload.";
                        echo json_encode($ret);
                        return $ret;
                    }
                    
                }
            }
        }
    }
    else
    {
        echo 'Unsupported request method';
    }
}
    
?>
