<?php
    // ================= SESSION INITIALIZATION =================
    // Start the session to access session variables
    session_start();
    
    // ================= RESPONSE STRUCTURE SETUP =================
    // Initialize the response array with default values
    $responseUser =[
        "code"=>0,                // 0 indicates success
        "msg"=>'',                // Empty message for successful requests
        "username"=>"",           // Will hold the username if logged in
        "role"=>""                // Will hold the user role if logged in
    ];

    // ================= SESSION VALIDATION =================
    // Check if the user is logged in by verifying the session username exists
    if(!isset($_SESSION["username"])){
        // User is not logged in, update response with error code and message
        $responseUser["code"]=1;  // 1 indicates error
        $responseUser["msg"]="You are not logged in";
        // Return the error response as JSON and exit
        print_r(json_encode($responseUser, JSON_UNESCAPED_UNICODE));
        exit;
    }

    // ================= USER INFORMATION RETRIEVAL =================
    // User is logged in, populate the response with session data
    $responseUser["username"]=$_SESSION["username"];  // Set the username
    $responseUser["role"]=$_SESSION["role"];          // Set the user role

    // ================= RESPONSE OUTPUT =================
    // Return the user information as JSON
    // JSON_UNESCAPED_UNICODE ensures proper encoding of non-ASCII characters
    print_r(json_encode($responseUser, JSON_UNESCAPED_UNICODE));
?>