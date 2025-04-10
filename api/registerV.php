<?php 
// ================= DATABASE CONNECTION =================
// Connect to the database using the config/db.php file
require "../config/db.php";

// ================= BACKEND VALIDATION =================
// Include the backValidation.php file for validation functions
require "../config/backValidation.php";

// ================= RESPONSE DATA =================
// Initialize the response data array
$responseData = [
    "code" => 0,
    "msg" => "",
    "data" => []
];

// ================= ACTION =================
// Get the action from the POST data
$action = $_POST["action"] ?? "";

// ================= REGISTRATION =================
// Handle the registration action
if($action==="registration"){
    $message_error= '';
    $password1= $_POST['password'] ?? '';
    $password2= $_POST['re_password'] ?? '';
    $email= $_POST['email'] ?? '';
    $username= $_POST['username'] ?? '';
    $role = $_POST['role'] ?? 'student';
    // ================= INPUT VALIDATION =================
    // Validate all input fields
    foreach(["username","email","password","re_password"] as $v){
            if(isEmpty($_POST[$v])){
                $message_error .= $v." can't be empty!";
                break;
            }
            if(!isEnough($_POST[$v]) && $v!='username'){
                $message_error .=$v . " should be at least 6 characters!";
                break;
            }
    }

    // ================= USERNAME VALIDATION =================
    // Check if the username contains only letters, numbers, '-' and '_' (5-50 characters)
    if (!preg_match('/^[a-zA-Z0-9_-]{5,50}$/', $username)) {
        $message_error .= "Username should only contain letters, numbers, '-' and '_' (5-50 characters).";
    }

    // ================= EMAIL VALIDATION =================
    // Check if the email follows the correct format
    if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
        $message_error .= "Incorrect email format!";
    }

    // ================= EXISTENCE CHECK =================
    // Check if the username or email already exists in the database    
    $stmt=$conn->prepare("SELECT username,email from user where username=:u OR email=:e");
    $stmt->execute([":u"=>$username,":e"=>$email]);
    $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
    if($result){
        $message_error .= "Username or Email already be taken";
    }


    // ================= PASSWORD VALIDATION =================
    // Check if the passwords match and are not empty
    if($password1!==$password2 && $password1 && $password2){
        $message_error .= "password should be the same!";
    }



    // ================= ERROR RESPONSE =================
    // If not pass the backend validation, send the error to front-end
    if (!empty($message_error)) {
        $responseData["code"] = 1;
        $responseData["msg"] = $message_error;
        // send json response
        print_r(json_encode($responseData, JSON_UNESCAPED_UNICODE));
        exit;
    }
    
    // ================= ROLE VALIDATION =================
    // Validate the role input
    $valid_roles = ['student', 'teacher', 'parent', 'admin'];
    if (!in_array($role, $valid_roles)){
         $role = 'student';
        }

    // ================= EXISTENCE CHECK =================
    // Check if the username or email already exists in the database
    $sql="SELECT * FROM user WHERE username = :username OR email = :email";
    $stmt=$conn->prepare($sql);
    $stmt->bindParam(":username", $username, PDO::PARAM_STR);
    $stmt->bindParam(":email", $email, PDO::PARAM_STR);
    //excute search
        $stmt->execute();
        $exist=$stmt->fetch(PDO::FETCH_ASSOC); 

    // ================= ERROR RESPONSE =================
    // If the username and email already exist, send the error to front-end
    if($exist){
        $responseData["code"] = 1;
        $responseData["msg"] = "The Username or Email is already existed";
        print_r(json_encode($responseData, JSON_UNESCAPED_UNICODE));
        exit;
    }

    // ================= INSERTION =================
    // If the username and email do not exist, insert the data into the database
    $sql ="INSERT INTO user(username,email,`password`,`role`) VALUES (:username,:email,:user_password,:user_role)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":username",$username,PDO::PARAM_STR);
    $stmt->bindParam(":email",$email,PDO::PARAM_STR);
        $stmt->bindParam(":user_password",$password1,PDO::PARAM_STR);
        $stmt->bindParam(":user_role",$role,PDO::PARAM_STR);
        $registResult=$stmt->execute();
        if($registResult){
            $responseData["msg"] = "Registration successful!";
        }else {
            $responseData["code"] = 1;
            $responseData["msg"] = "Database error. Please try again.";
        }

        // ================= RESPONSE =================
        // Call the front-end with the JSON response
        echo (json_encode($responseData, JSON_UNESCAPED_UNICODE));
    }

    
?>