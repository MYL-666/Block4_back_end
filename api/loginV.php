<?php 
    // ================= INITIALIZATION =================
    // Start the session to store user information after successful login
    session_start();
    // Include database connection
    require "../config/db.php";
    
    // Helper function to check if a string is empty or null
    function isEmpty($str){
        return $str===Null || empty($str);
    }

    // ================= REQUEST VALIDATION =================
    // Check if this is a login request by examining the 'action' parameter
    $action_login= $_POST["action"] ?? "";
    if($action_login==="login"){
        // Initialize error message and credentials
        $message_error_login="";
        $login_email="";
        $login_password="";

        // ================= INPUT VALIDATION =================
        // Check if required fields are provided
        foreach(["login_email","login_password"] as $v){
            // Validate that field is not empty
            if(isEmpty($_POST[$v])){
                $message_error_login .= $v." can't be empty!";
            }

            // Capture email and password for authentication
            if($v==='login_email'){
                $login_email=$_POST[$v];
            }

            if($v=="login_password"){
                $login_password=$_POST[$v];
            }
        }
        
        // ================= USER AUTHENTICATION =================
        // Check if the user exists with the provided email
        $sql="SELECT id,username,`password`,`role`,student_id,teacher_id,parents_id FROM user where email = :email ";
        $stmt=$conn->prepare($sql);
        $stmt->bindParam(":email",$login_email,PDO::PARAM_STR);
        $stmt->execute();
        $exist_user=$stmt->fetch(PDO::FETCH_ASSOC);
        
        // If user does not exist, return error
        if(!$exist_user){
            $responseData["code"]=1;
            $responseData["msg"]="The user doesn't exist!";
            print_r(json_encode($responseData,JSON_UNESCAPED_UNICODE));
            exit;
        }
        
        // ================= PASSWORD VERIFICATION =================
        // Different password verification for admin vs other users
        if ($exist_user["role"] === "admin") {
            // Admin passwords are hashed, verify using password_verify
            if (!password_verify($login_password, $exist_user["password"])) {
                $responseData["code"] = 1;
                $responseData["msg"] = "The password is incorrect!";
                print_r(json_encode($responseData, JSON_UNESCAPED_UNICODE));
                exit;
            }
        } else {
            // Non-admin users (students, parents, teachers) have plain text passwords
            if ($login_password !== $exist_user["password"]) {
                $responseData["code"] = 1;
                $responseData["msg"] = "The password is incorrect!";
                print_r(json_encode($responseData, JSON_UNESCAPED_UNICODE));
                exit;
            }
        }

        // ================= ROLE-SPECIFIC PROCESSING =================
        // Determine the role-specific ID field name ('parent_id', 'student_id', etc.)
        if($exist_user['role']=='parent'){
            $role_field=$exist_user['role'].'s_id'; // 'parents_id' for parent role
        } else {
            $role_field = $exist_user['role'].'_id'; // 'student_id', 'teacher_id', 'admin_id'
        }
        
        // Get the role-specific ID value, if exists
        $role_id = $exist_user[$role_field] ?? null;  
        // sotore into session about username and role
        $_SESSION["username"] = $exist_user["username"]; // Username for display
        $_SESSION["role"] = $exist_user["role"];         // Role for access control
        $_SESSION["id"] = $exist_user["id"];             // User ID from user table
        $_SESSION['identity'] = $exist_user['role'] . "s"; // Pluralized role (e.g., 'students')
        $_SESSION["identityID"] = $role_field;           // Name of role ID field   
        $_SESSION["roleID"] = $role_id;                  // Actual role-specific ID value
        
        // ================= RESPONSE GENERATION =================
        // Prepare success response
        $responseData["code"] = 0; // 0 indicates success
        $responseData["msg"] = "Login successful! {$_SESSION['roleID']}"; // Success message with roleID

        // Return JSON response (outside the if but still part of the login process)
        print_r(json_encode($responseData,JSON_UNESCAPED_UNICODE));
    }
?>