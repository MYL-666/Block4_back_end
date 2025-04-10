<?php 
session_start();

// ================= BASIC VALIDATION FUNCTIONS =================
// Check if value is empty
function isEmpty($str){
    return $str===Null || empty($str);
}

// Check if string length is between 6-30 characters
function isEnough($str){
    return strlen($str) >=6 && strlen($str)<=30;
}

// Check if name length follows SQL rules (1-50 characters)
function nameLength($str){
    return strlen($str) <1 && strlen($str)>50;
}

// ================= CONTACT INFORMATION VALIDATION =================
// Validate UK phone number format (mobile numbers)
function phoneValidation($str){
    $str = trim($str);
    if(!preg_match('/^(\+44\s?7\d{9}|07\d{9})$/',$str)){
        return false;
    }
    return true;
}

// Validate email format
function emailValidation($str){
    if(!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',$str)){
        return false;
    }
    return true;
}

// ================= CONTENT VALIDATION FUNCTIONS =================
// Validate address length (1-500 characters)
function addressValidation($str){
    return strlen($str)<1 && strlen($str)>500;
}

// Validate decimal amount format (for currency)
function validateAmount($value) {
    return preg_match('/^\d+(\.\d{1,2})?$/', $value);
}

// Validate class capacity (must be between 20-50)
function validCapacity($str){
    return is_numeric($str) && $str >= 20 && $str <= 50;
}

// Validate date format (YYYY-MM-DD)
function dateValid($date){
    $dateObj=DateTime::createFromFormat('Y-m-d',$date);
    if(!$dateObj || $dateObj->format('Y-m-d') !==$date){
        return false;
    }
    return true;
}

// Validate job title length (1-50 characters)
function checkJob($str){
    if(strlen($str)>0 && strlen($str)<50){
        return true;
    }
    return false;
}

// ================= DATABASE VALIDATION FUNCTIONS =================
// Check phone number for duplicates before updating
function checkPhone($str,$column,$user_ID){
    global $conn;
    global $table;
    global $error;
        // Check if this number has been taken by another user
        $stmt=$conn->prepare("SELECT $user_ID from $table where $column=:phone AND $user_ID!=:user_id");
        $stmt->execute([':phone'=>$str,":user_id"=>$_SESSION['roleID']]);
        if($stmt->fetch(PDO::FETCH_ASSOC)){
            $error[]="This phone number already been taken!";
            return false;
        }
        // Update phone number in database
        $stmt=$conn->prepare("UPDATE $table SET $column=:phone where $user_ID=:user_id");
        $stmt->execute([':phone'=>$str,":user_id"=>$_SESSION['roleID']]); 
}

// ================= PROFILE EDIT FUNCTION =================
// Edit user profile information - handles various field updates
function editUser($user_ID){
    global $notEmptyData;
    global $conn;
    global $error;
    global $table;
    
    if($_POST['title']==='Me'){
        unset($notEmptyData['title']);

        // Check if there's anything to update
        if(empty($notEmptyData)){
            echo json_encode(["code" => 1, "msg" => "Nothing to update!"]);
            exit;
        }

        // ================= HANDLE USER ID UPDATES =================
        if(isset($notEmptyData[$user_ID])){
            // Check if this ID exists in the system
            $stmt=$conn->prepare("SELECT $user_ID from $table where $user_ID=:id");
            $stmt->execute([':id'=>$notEmptyData[$user_ID]]);
            if (!$stmt->fetch(PDO::FETCH_ASSOC)) {
                $error[] = "$user_ID " . $notEmptyData[$user_ID] . " does not exist!";
                return false;
            }

            // Check if this ID is already bound to another account
            $stmt=$conn->prepare("SELECT id from user where $user_ID=:value AND id!=:user_id");
            $stmt->execute([":value"=>$notEmptyData[$user_ID],":user_id"=>$_SESSION['id']]);
            if($stmt->fetch(PDO::FETCH_ASSOC)){
                $error[]=$user_ID." ".$notEmptyData[$user_ID]." already binded an account!";
                return false;
            }else{
                // Update the user table with new ID
                $stmt=$conn->prepare("UPDATE user SET $user_ID=:newID where id=:user_id");
                $stmt->execute([":newID"=>$notEmptyData[$user_ID],':user_id'=>$_SESSION['id']]);
                $_SESSION['roleID']=$notEmptyData[$user_ID];
            }
        }

        // ================= HANDLE NAME UPDATES =================
        if(isset($notEmptyData['name'])){
            // Ensure user has a bound ID before updating name
            $stmt=$conn->prepare("SELECT $user_ID from user where id=:user_id");
            $stmt->execute([':user_id'=>$_SESSION['id']]);
            $result=$stmt->fetch(PDO::FETCH_ASSOC);
            $roleID = $result[$user_ID] ?? $_SESSION['roleID'] ?? null;
            if(empty($result[$user_ID])){
                $error[]="Please bind $user_ID first!";
                return false;
            }
            
            // Split full name into first and last name
            $nameParts = explode(' ', $notEmptyData['name'], 2);
            $firstName = $nameParts[0];
            $lastName = isset($nameParts[1]) ? $nameParts[1] : '';
            $nameError = [];

            // Validate first name
            if (empty($firstName)) {
                $nameError[] = "First name is empty";
            } elseif (nameLength($firstName)) {
                $nameError[] = "First name length is invalid";
            }           

            // Validate last name
            if (empty($lastName)) {
                $nameError[] = "Last name is empty";
            } elseif (nameLength($lastName)) {
                $nameError[] = "Last name length is invalid";
            }           

            // Handle name validation errors
            if (!empty($nameError)) {
                $error[] = implode("; ", $nameError);
                return false;
            }
            
            // Update name in database
            $stmt = $conn->prepare("UPDATE $table SET First_Name = :first, Last_Name = :last WHERE $user_ID = :roleID");
            $stmt->execute([
                ':first' => $firstName,
                ':last' => $lastName,
                ':roleID' => $roleID
            ]);
        }

        // ================= HANDLE OTHER PROFILE UPDATES =================
        // Update gender
        if(isset($notEmptyData['Gender'])){
            $stmt=$conn->prepare("UPDATE user SET Gender=:gender where id=:user_id");
            $stmt->execute([':gender'=>$notEmptyData['Gender'],":user_id"=>$_SESSION['id']]);
        }

        // Update birthday
        if(isset($notEmptyData['birthday'])){
            $stmt=$conn->prepare("UPDATE user SET birthday=:birthday where id=:user_id");
            $stmt->execute([':birthday'=>$notEmptyData['birthday'],":user_id"=>$_SESSION['id']]);
        }

        // Update email with validation
        if(isset($notEmptyData['email'])){
            if(!emailValidation($notEmptyData['email'])){
                $error[]="Invalid email format!";
                return false;
            }else{
                // Check if email is already taken
                $stmt=$conn->prepare("SELECT id FROM user where email=:email AND id!=:user_id ");
                $stmt->execute([':email'=>$notEmptyData['email'],":user_id"=>$_SESSION['id']]);
                if($stmt->fetch(PDO::FETCH_ASSOC)){
                    $error[]="This email already be taken!";
                    return false;
                }

                // Update email if no errors
                $stmt=$conn->prepare("UPDATE user SET email=:email where id=:user_id");
                $stmt->execute([':email'=>$notEmptyData['email'],":user_id"=>$_SESSION['id']]); 
            }
        }

        // Update phone number
        if(isset($notEmptyData['phone'])){
            checkPhone($notEmptyData['phone'],"phone","teacher_id");
            unset($notEmptyData['phone']);
        }

        // Update parent phone number
        if(isset($notEmptyData['parents_phone'])){
            checkPhone($notEmptyData['parents_phone'],"parents_phone","parents_id");
            unset($notEmptyData['parents_phone']);
        }

        // Clean up processed fields
        unset($notEmptyData[$user_ID]);
        unset($notEmptyData["name"]);
        unset($notEmptyData["Gender"]);
        unset($notEmptyData["birthday"]);
        unset($notEmptyData["email"]);
    }
}

// ================= EXTERNAL API FUNCTION =================
// Get book cover image from Google Books API
function getBookCoverFromGoogle($title, $author = '') {
    $apiKey = 'Your Api here'; 
    // Build search query with title and optional author
    $query = urlencode("intitle:$title" . ($author ? "+inauthor:$author" : ""));
    $url = "https://www.googleapis.com/books/v1/volumes?q=$query&key=$apiKey";

    // Fetch and parse API response
    $json = file_get_contents($url);
    $data = json_decode($json, true);

    // Return thumbnail URL if available, otherwise return default image
    if (!empty($data['items'][0]['volumeInfo']['imageLinks']['thumbnail'])) {
        return $data['items'][0]['volumeInfo']['imageLinks']['thumbnail'];
    }

    return '../public/img/no-cover.png';
}
?>