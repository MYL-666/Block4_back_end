<?php
// ================= INITIALIZATION =================
// Require database connection and validation functions
require "../config/db.php";
require "../config/backValidation.php";
// Set header to indicate JSON response
header('Content-Type: application/json');

// ================= CONFIGURATION =================
// Define valid tables and their primary key column names
$validTables=[
    'classes'=>'class_id',
    'students'=>'student_id',
    'teachers'=>'teacher_id',
    'salaries'=>'salaries_id',
    'parents'=>'parents_id',
    'library'=>'Book_id',
    'chat_board'=>'chat_id',    
    'borrowed_book'=>'borrow_id'
];
// Get the target table name from the POST request
$table=$_POST['table'] ?? "";

// ================= TABLE NAME VALIDATION =================
// Check if the provided table name is valid and allowed for editing
if(!array_key_exists($table,$validTables)){
    echo json_encode(["code" => 1, "msg" => "This table invalid!"]);
    exit;
}
// Get the primary key column name for the validated table
$key=$validTables[$table];

// ================= INPUT PROCESSING =================
// Get all POST data
$updateData = $_POST;
// Remove the 'table' key as it's not part of the data to be updated
unset($updateData['table']);     

// Handle specific logic for profile edits (when title is 'Me') vs general edits
if($_POST['title']!=='Me'){
    // For general edits, remove the primary key and title from update data
    unset($updateData[$key]);  
    unset($updateData['title']); 
    // Get the primary key value (ID of the record to edit)
    $value=$_POST[$key];
    // Ensure the primary key value is provided for general edits
    if(!$value){
        echo json_encode(["code" => 1, "msg" => "Invalid ID!"]); // Changed error message
        exit;
    } 
}

// Filter out empty, null, or 'none' values from the update data
$notEmptyData = array_filter($updateData, function($value) {
    return $value !== null && $value !== '' && $value !=='none';
});

// Debugging log (can be removed in production)
error_log("NotEmptyData after filter: " . print_r($notEmptyData, true));

// Check if there is any data left to update after filtering
if(empty($notEmptyData)){
    echo json_encode(["code" => 1, "msg" => "Nothing to update!"]);
    exit;
}
// Get the keys of the original POST data (might not be needed after filtering)
$keys = array_keys($_POST);

// ================= VALIDATION SETUP =================
// Initialize an array to store validation errors
$error=[];
// Helper function to validate date format
function CheckDates($Date) {
    global $error;
    if (!isset($Date) || !dateValid($Date)) {
        $error[] = "Incorrect Date format of $Date!";
        return false;
    }
    return true;
}

// ====================== SPECIFIC TABLE VALIDATIONS ======================

// ============================= CLASSES VALIDATION ============================
if($table=='classes'){
    // Check if Grade is valid
    if(isset($notEmptyData['Grade'])){
        if(!in_array($notEmptyData['Grade'],['Reception Year','Year One','Year Two','Year Three','Year Four','Year Five','Year Six'])){
            $error[] ='Invalid grade selected!';
        }
    }
    // Check if capacity is within the allowed range
    if(isset($notEmptyData['capacity']) && !validCapacity($notEmptyData['capacity'])){
        $error[] ='Capacity should be between 20 and 50!';
    }

    // Check if the new capacity is less than the number of existing students
    if(isset($notEmptyData['capacity'])){
        $stmt=$conn->prepare("
        SELECT 
            COUNT(s.student_id) AS student_number 
            from classes as c 
            JOIN students as s on c.class_id=s.class_id
            where c.class_id=:class
            ");
        $stmt->execute([':class'=>$value]);
        $result=$stmt->fetch(PDO::FETCH_ASSOC);
        if($result && $result['student_number'] > $notEmptyData['capacity']){
            $error[] = "Invalid capacity: You cannot set the class capacity to {$notEmptyData['capacity']} while {$result['student_number']} students are already enrolled.";
        }
    }

    // Check teacher assignment
    if(isset($notEmptyData['Name']) ){
        $teacherName = trim($notEmptyData['Name']);
        if ($teacherName === '') {
            $error[] = 'Teacher name cannot be empty'; // Changed error message
        } else {
            // Check if the specified teacher exists
            $sql = "SELECT teacher_id 
                    FROM teachers 
                    WHERE CONCAT(First_Name,' ', Last_Name) = :teacher_name
                    LIMIT 1";
    
            $stmt = $conn->prepare($sql);
            $stmt->execute([':teacher_name' => $teacherName]);
            $teacherRow = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if (!$teacherRow) {
                $error[] = "Teacher '{$teacherName}' not found!"; // Changed error message
            } else {
                // Check if the teacher is already assigned to another class (excluding the current one being edited)
                $teacher_id = $teacherRow['teacher_id'];
                $stmt=$conn->prepare("SELECT class_id from classes where teacher_id=:teacher_id AND class_id !=:class_id");
                $stmt->execute([
                    ":teacher_id"=>$teacher_id,
                    ":class_id"=>$value // $value holds the class_id being edited
                ]);
                if($stmt->fetch(PDO::FETCH_ASSOC)){
                    $error[]="This teacher is already assigned to another class!"; // Changed error message
                }else{
                    // If valid, replace 'Name' with 'teacher_id' for the update query
                    $notEmptyData['teacher_id'] = $teacher_id;
                    unset($notEmptyData['Name']);
                }
                
            }
        }
    }
    // Check if class name is valid and unique
    if(isset($notEmptyData['class_name'])){
        if(nameLength($notEmptyData['class_name'])){
            $error[] ='Invalid class name length! Must be between 1 and 50 characters.'; // Added length info
        }
        // Check if the class name is already taken by another class
        $stmt=$conn->prepare("
            SELECT 
                class_id
                from classes
                where class_name=:class_name AND class_id != :class_id
        "); // Added check to exclude the current class
        $stmt->execute([
            ":class_name"=>$notEmptyData['class_name'],
            ":class_id"=>$value // $value holds the class_id being edited
            ]);
        if($stmt->fetch(PDO::FETCH_ASSOC)){
            $error[]="This class name is already taken!"; // Changed error message
        }
    }
} // End Classes Validation

// ============================ STUDENTS VALIDATION ============================
if($table==='students'){
    // Call the common editUser function for profile-related fields (Name, Gender, birthday, email, student_id binding)
    editUser("student_id");
    
    // Check if address format is valid
    if(isset($notEmptyData['address']) && addressValidation($notEmptyData['address']) ){
        $error[]="Address should be less than 500 character";
    }
    // Check if medical information format is valid
    if(isset($notEmptyData["medical_information"]) && addressValidation($notEmptyData["medical_information"])){
        $error[]="Medical information should be less than 500 character";
    }
    // Check if the selected class exists and get its ID
    if(isset($notEmptyData['class_name'])){
        $stmt=$conn->prepare("
        SELECT 
        c.class_id 
        from classes as c
        where c.class_name=:class_name");
        $stmt->execute([":class_name"=>$notEmptyData['class_name']]);
        $result=$stmt->fetch(PDO::FETCH_ASSOC);
        if(!$result){
            $error[]="The selected class name does not exist!"; // Changed error message
        } else {
            // Replace 'class_name' with 'class_id' for the update query
            $notEmptyData['class_id']=$result['class_id'];
            unset($notEmptyData["class_name"]);
        }
    }
} // End Students Validation

// ============================ PARENTS VALIDATION =============================
if($table==='parents'){
    // Call the common editUser function for profile-related fields
    editUser("parents_id");

    // Validate phone number format and uniqueness
    if(isset($notEmptyData['parents_phone'])){
        if(!phoneValidation($notEmptyData['parents_phone'])){
            $error[]="Invalid UK Phone format!";
        }
        // Check if this phone number already exists for another parent
        $stmt=$conn->prepare("SELECT parents_id from parents where parents_phone=:phone AND parents_id != :parent_id"); // Exclude current parent
        $stmt->execute([
            ':phone'=>$notEmptyData['parents_phone'],
            ':parent_id'=>$value // $value holds the parents_id being edited
            ]);
        if($stmt->fetch(PDO::FETCH_ASSOC)){
            $error[]="This phone number is already associated with another parent!"; // Changed error message
        }
    }

    // Split 'Name' into 'First_Name' and 'Last_Name'
    if(isset($notEmptyData['Name'])){
        $nameParts = explode(' ', trim($notEmptyData['Name']), 2);
        $notEmptyData['First_Name']=$nameParts[0];
        if(isset($nameParts[1])){
            $notEmptyData['Last_Name']=$nameParts[1];
        }else{
            $error[]="Please include the last name!"; // Changed error message
        }
        unset($notEmptyData['Name']); // Remove the original 'Name' key
    }

    // Update relationship in the student_parents table
    if(isset($notEmptyData['relation'])){
        $parents_id = $value; // Use the $value which holds the parents_id being edited
        if (!$parents_id) {
            echo json_encode(["code" => 1, "msg" => "Invalid parents ID for relationship update"]);
            exit;
        }
        
        try {
            // Update the relation for all associated students of this parent
            $stmt = $conn->prepare("UPDATE student_parents SET relation=:relation where parents_id=:parents_id");
            $stmt->execute([
                ':relation' => $notEmptyData['relation'],
                ':parents_id' => $parents_id
            ]);
            
            unset($notEmptyData['relation']); // Remove relation from main update array
        } catch (PDOException $e) {
            // Log the detailed error instead of showing it to the user
            error_log("Database error updating relationship: " . $e->getMessage());
            echo json_encode(["code" => 1, "msg" => "Failed to update relationship."]);
            exit;
        }
    }

    // Validate Job title length
    if(isset($notEmptyData['Job'])){
        if(!checkJob($notEmptyData['Job'])){
            $error[] = "Job title length must be between 1 and 50 characters."; // Changed error message
        }
    }

    // Validate and update linked Kids (Kids1, Kids2)
    if(isset($notEmptyData['Kids1']) || isset($notEmptyData['Kids2'])){
        $kid_names=[];
        if(isset($notEmptyData['Kids1'])){
            $kid_names[]=$notEmptyData['Kids1'];
        }
        if(isset($notEmptyData['Kids2'])){
            $kid_names[]=$notEmptyData['Kids2'];
        }
        
        // Get the current student IDs linked to this parent
        $stmt=$conn->prepare("
            SELECT
                (SELECT s.student_id
                from student_parents as sp
                JOIN students as s on s.student_id=sp.student_id
                where sp.parents_id=p.parents_id LIMIT 1) as kid1,
                (SELECT s.student_id
                FROM student_parents sp
                JOIN students s ON sp.student_id = s.Student_id
                WHERE sp.parents_id = p.parents_id
                LIMIT 1 OFFSET 1) AS Kids2
            From parents as p
            LEFT JOIN student_parents as sp on sp.parents_id=p.parents_id
            where p.parents_id=:parents_id
        ");
        $stmt->execute([':parents_id'=>$value]);
        $oldKids = $stmt->fetchAll(PDO::FETCH_COLUMN); // Fetch an array of student IDs

        $index=0;
        $newKidsToLink = []; // Store new valid kid IDs

        // Check each provided kid name
        foreach($kid_names as $kid_name){
            // Check if this student exists by name
            $stmt=$conn->prepare("SELECT student_id from students where CONCAT(First_Name,' ',Last_Name)=:kid_name");
            $stmt->execute([':kid_name'=>$kid_name]);
            $resultS=$stmt->fetch(PDO::FETCH_ASSOC);
            if(!$resultS){
                $error[]="Student '{$kid_name}' does not exist!";
                continue; // Skip to the next kid if this one doesn't exist
            }
            $newKidId = $resultS['student_id'];

            // Check how many parents are already linked to this student (excluding the current parent being edited)
            $stmt=$conn->prepare("
                SELECT
                    count(sp.parents_id) AS parent_count
                    from student_parents as sp
                    JOIN students as s on s.student_id=sp.student_id
                    where CONCAT(s.First_Name,' ',s.Last_Name)=:kid_name
            ");
            $stmt->execute([':kid_name'=>$kid_name]);
            $result=$stmt->fetch(PDO::FETCH_ASSOC);

            // A student can have at most 2 parents/guardians
            if($result["parent_count"]>=2){
                $error[]="This kid already had 2 parents/gardients!";
                continue;
            }
                //if only one kid then insert the new relationship
                if(!isset($oldKids[$index])){
                    $stmt = $conn->prepare("
                    INSERT INTO student_parents (student_id, parents_id) VALUES (:student_id, :parents_id)
                    ");
                    try{
                        // execute the query
                        $stmt->execute([
                            ':student_id' => $resultS['student_id'],
                            ':parents_id' => $value
                        ]);
                    }catch(PDOException $e){
                        // log the error
                        $error[]="DB Error: ".$e->getMessage();
                    }

                }else{
                    // update the student id
                    $stmt=$conn->prepare("
                        UPDATE student_parents SET student_id=:student_id where parents_id=:parents_id AND student_id=:oldstudent_id
                    ");
                    try{
                        // execute the query
                        $stmt->execute([
                            ":student_id"=>$resultS['student_id'],
                            ":parents_id"=>$value,
                            ":oldstudent_id"=>$oldKids[$index]
                        ]);
                    }catch(PDOException $e){
                        $error[]="DB Error: ".$e->getMessage();
                    }

                }

            $index++;
        }
    }
    unset($notEmptyData['Kids1']);
    unset($notEmptyData['Kids2']);
} // End Parents Validation

// ============================ TEACHERS VALIDATION ============================
if($table==='teachers'){
    // Validate phone number format and uniqueness
    if(isset($notEmptyData['phone'])){
        if(!phoneValidation($notEmptyData['phone'])){
            $error[]="Invalid UK phone number!";
        }
        // Check if this phone number is already assigned to another teacher
        $stmt=$conn->prepare("SELECT teacher_id FROM teachers where phone=:phone AND teacher_id != :teacher");
        $stmt->execute([':phone'=>$notEmptyData['phone'], ":teacher"=>$value]); // $value holds the teacher_id being edited
        if($stmt->fetch(PDO::FETCH_ASSOC)){
            $error[]="This phone number is already assigned to another teacher!"; // Changed error message
        }
    }

    // Convert backgroundCheck string ('yes'/'no') to boolean (1/0)
    if(isset($notEmptyData['backgroundCheck'])){
        if($notEmptyData['backgroundCheck']==='no'){
            $notEmptyData['backgroundCheck']=0;
        } elseif ($notEmptyData['backgroundCheck']==='yes'){ // Use elseif for clarity
            $notEmptyData['backgroundCheck']=1;
        } else {
            // Handle invalid values if necessary, e.g., unset or set to default
            unset($notEmptyData['backgroundCheck']); 
        }
    }
    // Call the common editUser function for profile-related fields
    editUser("teacher_id");
} // End Teachers Validation

// ============================ SALARIES VALIDATION ============================
if($table==='salaries'){
    // Validate teacher assignment for the salary record
    if(isset($notEmptyData['Name'])){
        $stmt=$conn->prepare("
            SELECT 
                t.teacher_id,
                s.salaries_id
                from teachers as t
                JOIN salaries as s on s.teacher_id=t.teacher_id
                where CONCAT(t.First_Name,' ',t.Last_Name)=:teacher_name
        ");
        $stmt->execute([":teacher_name"=>$notEmptyData['Name']]);
        $result=$stmt->fetch(PDO::FETCH_ASSOC);
        if(!$result){
            $error[]="This teacher doesnt exist!";
        }else{
            if($result["salaries_id"]){
                $error[]="This teacher already binded one salary!";
            }else{
                $notEmptyData['teacher_id'] = $result["teacher_id"];
            }
        }        
        unset($notEmptyData['Name']); // Remove 'Name' after processing
    }

    //when changing both expected and penalty money
    if(isset($notEmptyData['expected_amount']) && isset($notEmptyData['penalty_amount'])){
        if(!validateAmount($notEmptyData['expected_amount']) || !validateAmount($notEmptyData['penalty_amount'])){
            $error[]="Please input decimal number for expected amount! (e.g., 0.00)";
        }
        //directly use the change one
        $expectedAmount = (float)$notEmptyData['expected_amount'];
        $penaltyAmount = (float)$notEmptyData['penalty_amount'];
    
        $actualAmount = $expectedAmount - $penaltyAmount;
        //change actual money as well
        $notEmptyData['actual_amount'] = number_format($actualAmount, 2, '.', '');
    }elseif(isset($notEmptyData['expected_amount']) && !isset($notEmptyData['penalty_amount'])){
        //when only change expected amount while penalty not change
        if(!validateAmount($notEmptyData['expected_amount'])){
            $error[]="Please input decimal number for expected amount! (e.g., 0.00)";
        }
        //search the orginal penalty money 
        $expectedAmount = (float)$notEmptyData['expected_amount'];
        $stmt=$conn->prepare("SELECT penalty_amount from salaries where salaries_id=:salaries_id");
        $stmt->execute([":salaries_id"=>$value]);
        $result=$stmt->fetch(PDO::FETCH_ASSOC);
        if(isset($result)){
            //find the penalty money and calculate the actual money
            $penaltyAmount=(float)$result['penalty_amount'];
            $actualAmount = $expectedAmount - $penaltyAmount;
            
            $notEmptyData['actual_amount'] = number_format($actualAmount, 2, '.',   '');
        }
    }elseif(isset($notEmptyData['penalty_amount']) && !isset($notEmptyData['expected_amount'])){
        //when only penalty money change while expect amount stay same
        if(!validateAmount($notEmptyData['penalty_amount'])){
            $error[]="Please input decimal number for penalty amount! (e.g., 0.00)";
        }
        //search the expect amount from database
        $penaltyAmount = (float)$notEmptyData['penalty_amount'];
        $stmt=$conn->prepare("SELECT expected_amount from salaries where salaries_id=:salaries_id");
        $stmt->execute([":salaries_id"=>$value]);
        $result=$stmt->fetch(PDO::FETCH_ASSOC);
        if(isset($result)){
            $expectedAmount=(float)$result['expected_amount'];
            $actualAmount = $expectedAmount - $penaltyAmount;
            // calculate the actual amount and add it to be changed
            $notEmptyData['actual_amount'] = number_format($actualAmount, 2, '.',   '');
        }
    }
    
    // Validate salary month uniqueness for the specific teacher
    if(isset($notEmptyData['salary_month'])){
        $salary_month = $notEmptyData['salary_month'] . '-01';
        $stmt=$conn->prepare("SELECT * from salaries where salaries_id=:s");
        $stmt->execute([':s'=>$value]);
        $result=$stmt->fetch(PDO::FETCH_ASSOC);
        // beside this teacher if still has other record
        $stmt = $conn->prepare("
        SELECT COUNT(*) FROM salaries 
        WHERE teacher_id =:t AND salary_month =:s AND salaries_id !=:s_id
        ");
        $stmt->execute([":t"=>$result['teacher_id'],":s"=> $salary_month,"s_id"=> $value]);

        if ($stmt->fetchColumn() > 0) {
            $error[]="This teacher already has other record in {$notEmptyData['salary_month']}";
        }
    }

    // Convert 'if_paid' status string to boolean (1/0)
    if(isset($notEmptyData['if_paid'])){
        if($notEmptyData['if_paid']=="no"){
            $notEmptyData['if_paid']=0;
        } elseif($notEmptyData['if_paid']=="yes"){ // Use elseif
            $notEmptyData['if_paid']=1;
        } else {
            unset($notEmptyData['if_paid']); // Remove if invalid value
        }
    }
} // End Salaries Validation

// ============================ LIBRARY VALIDATION =============================
if($table==='library'){
    // Handle changes to book title or author - requires fetching new cover
    if(isset($notEmptyData['book_title']) || isset($notEmptyData['Author'])){
        // Get the current title and author
        $stmt = $conn->prepare("SELECT title, Author FROM library WHERE Book_id = :book_id");
        $stmt->execute([':book_id' => $value]);
        $old = $stmt->fetch(PDO::FETCH_ASSOC);

        $newTitle=$newAuthor='';
        isset($notEmptyData['book_title']) ? $newTitle=$notEmptyData['book_title'] : $newTitle=$old['title'];
        isset($notEmptyData['Author']) ? $newAuthor=$notEmptyData['Author'] : $newAuthor=$old['Author'];
        // check if change has made
        if ($old['title'] !== $newTitle || $old['Author'] !== $newAuthor) {
            // check if book already exist
            $stmt=$conn->prepare("SELECT Book_id from library where title=:title AND Author=:author");
            $stmt->execute([":title"=>$newTitle,":author"=>$newAuthor]);
            if($stmt->fetch(PDO::FETCH_ASSOC)){
                $error[]="Another book with this title and author already exists!";
            }else{
                // If unique, fetch new cover from Google Books API
                $notEmptyData['cover'] = getBookCoverFromGoogle($newTitle, $newAuthor);
                // Ensure title and author are updated in the array
                $notEmptyData['title'] = $newTitle;
                $notEmptyData['Author'] = $newAuthor;
            }
            
        }else{
            $error[]="This Book does't change!";
        }

        unset($notEmptyData['book_title']);
    }

    // Validate publish date format and ensure it's not in the future
    if(isset($notEmptyData['publishDate']) && CheckDates($notEmptyData['publishDate'])){
        $dateNow= new DateTime();
        $dateObj=DateTime::createFromFormat('Y-m-d',$notEmptyData['publishDate']);
        // Allow comparing date objects directly
        if($dateObj > $dateNow){
            $error[]="Publish date cannot be in the future!";
        }
    }
    
    // Validate if the assigned student exists (if student_id is being set/changed)
    if(isset($notEmptyData['student_id'])){
        $stmt = $conn->prepare("SELECT student_id FROM students WHERE student_id = :student_id");
        $stmt->execute([':student_id' => $notEmptyData['student_id']]);
        if(!$stmt->fetch(PDO::FETCH_ASSOC)){
            $error[] = "The selected student ID does not exist!";
        }
    }
    //check borrowed date valid, if borrowed student should also be selected
    if((isset($notEmptyData['borrowDate']) && CheckDates($notEmptyData['borrowDate'])) || (isset($notEmptyData['DueDate']) && CheckDates($notEmptyData['DueDate']))){
        $stmt=$conn->prepare("
            SELECT 
                borrowDate,DueDate
                from borrowed_book 
                where book_id=:book_id
        ");
        $stmt->execute([":book_id"=>$value]);
        $result=$stmt->fetch(PDO::FETCH_ASSOC);
        // No borrowing record, indicating no one has borrowed
        // Determine whether the user wants to borrow (fill in borrowDate)
        if (!$result) {
            if (isset($notEmptyData['borrowDate'])) {
                if (!isset($notEmptyData['DueDate']) || !isset($notEmptyData['student_id'])) {
                    $error[] = "Please select both Due Date and Student to borrow this book.";
                }else{
                    // insert the borrow record
                    $stmt = $conn->prepare("
                     INSERT INTO borrowed_book (book_id, borrowDate, DueDate, student_id)
                     VALUES (:book_id, :borrowDate, :DueDate, :student_id)
                     ");
                     try{
                        // execute the query
                        $stmt->execute([
                            ':book_id' => $value,
                            ':borrowDate' => $notEmptyData['borrowDate'],
                            ':DueDate' => $notEmptyData['DueDate'],
                            ':student_id' => $notEmptyData['student_id']
                        ]);
                     }catch(PDOException $e){
                        // log the error
                        $error[]="DB Error: ".$e->getMessage();
                     }
                     
                     $notEmptyData["status"] = "Borrowed";

                    }
            }
        }
        else{

            $currentBorrowDate = DateTime::createFromFormat('Y-m-d', $result['borrowDate']);
            $currentDueDate = DateTime::createFromFormat('Y-m-d', $result['DueDate']);
            // check if the borrow date or de date is going to be updated
            $newBorrowDate = isset($notEmptyData['borrowDate']) ? DateTime::createFromFormat('Y-m-d', $notEmptyData['borrowDate']) : $currentBorrowDate;
            $newDueDate = isset($notEmptyData['DueDate']) ? DateTime::createFromFormat('Y-m-d', $notEmptyData['DueDate']) : $currentDueDate;
            $dateNow=new DateTime();
            if($newBorrowDate > $dateNow){
                $error[]='Borrow date should not be future time!';
            }
            //check if borrowed date earlier than duedate
            if ($newBorrowDate > $newDueDate) {
                $error[] = "Borrow date should not be later than due date!";
            } else {
                $updateFields = [];
                $updateValues = [':book_id' => $value];
                        if (isset($notEmptyData['borrowDate'])) {
                    $updateFields[] = "borrowDate = :borrowDate";
                    $updateValues[':borrowDate'] = $newBorrowDate->format('Y-m-d');
                    unset($notEmptyData['borrowDate']);
                }
                        if (isset($notEmptyData['DueDate'])) {
                    $updateFields[] = "DueDate = :DueDate";
                    $updateValues[':DueDate'] = $newDueDate->format('Y-m-d');
                    unset($notEmptyData['DueDate']);
                }
                        // update borrowed book table
                if (!empty($updateFields)) {
                    $sql = "UPDATE borrowed_book SET " . implode(', ', $updateFields) . " WHERE book_id = :book_id";
                    $stmt = $conn->prepare($sql);
                    try{
                        // execute the query
                        $stmt->execute($updateValues);
                    }catch(PDOException $e){
                        // log the error
                        $error[]="DB Error: ".$e->getMessage();
                    }
                }
                // Set library status to Borrowed
                $notEmptyData["status"] = "Borrowed"; 
            }
        } // End validation for Borrowing
    } // End check for borrowing field updates

    // Clean up fields handled by borrow logic
    unset($notEmptyData['borrowDate']);
    unset($notEmptyData['DueDate']);
    unset($notEmptyData['student_id']);
} // End Library Validation

// ============================ CHAT BOARD VALIDATION ==========================
if($table==='chat_board'){
    // check if title is valid
    if(isset($notEmptyData['chat-title']) && strlen($notEmptyData['chat-title'])<30 && strlen($notEmptyData['chat-title'])>0){
        $notEmptyData['title']=$notEmptyData['chat-title'];
        unset($notEmptyData['chat-title']);
    }   
    // check if content is valid
    if(isset($notEmptyData['content']) && (strlen($notEmptyData['content'])>500 || strlen($notEmptyData['content'])<0)){
        $error[]="Content should be less than 500 characters!";
    }
}

// ========================= BORROWED BOOKS VALIDATION =========================
if($table==='borrowed_book'){
    // Validate return date
    if(isset($notEmptyData['returnDate']) && CheckDates($notEmptyData['returnDate'])){
        // Get the borrow date for this record
        $stmt=$conn->prepare("SELECT borrowDate FROM borrowed_book WHERE borrow_id=:borrow_id");
        $stmt->execute([":borrow_id"=>$value]); // $value is borrow_id
        $result=$stmt->fetch(PDO::FETCH_ASSOC);
        
        if($result && $result['borrowDate']){ // Check if borrowDate exists
            $borrowDate=DateTime::createFromFormat('Y-m-d',$result['borrowDate']);
            $returnDate=DateTime::createFromFormat('Y-m-d',$notEmptyData['returnDate']);
            if($returnDate < $borrowDate){
                $error[]="Return date cannot be earlier than borrow date!";
            }
        }
        // if there is no borrow record
        else{
            $error[]="Borrow record not found! Please borrow the book first!";
        }
    }  

    // Validate borrow date
    if(isset($notEmptyData['borrowDate']) && CheckDates($notEmptyData['borrowDate'])){
        $dateNow= new DateTime();
        $dateObj=DateTime::createFromFormat('Y-m-d',$notEmptyData['borrowDate']);
        if($dateObj > $dateNow){
            $error[]="Borrow date cannot be future!";
        }

        // check if borrow date is later than return date
        $stmt=$conn->prepare("SELECT returnDate FROM borrowed_book WHERE borrow_id=:borrow_id");
        $stmt->execute([":borrow_id"=>$value]);
        $result=$stmt->fetch(PDO::FETCH_ASSOC);
        // if there is a return record
        if($result){
            $returnDate=DateTime::createFromFormat('Y-m-d',$result['returnDate']);
            if($dateObj > $returnDate){
                $error[]="Borrow date cannot be later than return date!";
            }
        } 
    }
    //check if student exist
    if(isset($notEmptyData['Name'])){
        // Check if student exists by name
        $stmt = $conn->prepare("SELECT student_id FROM students WHERE CONCAT(First_Name,' ',Last_Name) = :student_name");
        $stmt->execute([':student_name' => $notEmptyData['Name']]);
        $result=$stmt->fetch(PDO::FETCH_ASSOC);
        if(!$result){
            $error[] = "Student '{$notEmptyData['Name']}' does not exist!";
        }else{
            // update the student id
            $stmt=$conn->prepare("UPDATE borrowed_book SET student_id=:student_id WHERE borrow_id=:borrow_id");
            $stmt->execute([
                ':student_id'=>$result['student_id'],
                ':borrow_id'=>$value
            ]);
        }
        unset($notEmptyData['Name']); // Remove 'Name' key
    }

    // Re-check date consistency if both are set (handles cases missed by individual checks)
    if(isset($notEmptyData['borrowDate']) && isset($notEmptyData['returnDate'])){
        $borrowDate=DateTime::createFromFormat('Y-m-d',$notEmptyData['borrowDate']);
        $returnDate=DateTime::createFromFormat('Y-m-d',$notEmptyData['returnDate']);
        if($borrowDate > $returnDate){
            $error[]="Borrow date cannot be later than return date!";
        }
    }
}

// ================= FINAL ERROR CHECK =================
// Check if any validation errors occurred
if (!empty($error)) {
    // Send JSON response with errors
    echo json_encode(["code" => 1, "msg" => $error]);
    exit;
} else {
    // ================= DATABASE UPDATE EXECUTION =================
    // Proceed only if there's data to update and no errors occurred
    if(!empty($notEmptyData)){
        // Dynamically build the SET part of the UPDATE query
        $updateColumn=[];
        $updateValue=[];
        foreach($notEmptyData as $k=>$v){
            $updateColumn[]="`$k`=:$k"; // Use backticks for column names
            $updateValue[":$k"]=$v;
        }
        // Add the primary key value for the WHERE clause
        $updateValue[":primaryKey"]=$value; // $value holds the ID for general edits
        
        // Construct the final SQL UPDATE statement
        $sql = "UPDATE `$table` SET " . implode(", ", $updateColumn) . " WHERE  `$key` = :primaryKey";
        
        try{
            // Prepare and execute the statement
            $stmt=$conn->prepare($sql);
            $stmt->execute($updateValue);
            // Send success response
            echo json_encode(["code" => 0, "msg" => "Update Success!!"]); // Changed message slightly
        }catch(PDOException $e){
            echo json_encode(["code" => 1, "msg" => "DB Error: " . $e->getMessage   ()]);
        }
        exit; // Stop script after update attempt
    } else {
        // If $notEmptyData became empty after validation cleanups but no errors occurred
        // (e.g., only relation was updated directly), send success.
        echo json_encode(["code" => 0, "msg" => "Update Success!!"]);
        exit;
    }
}

// Final exit (should not be reached)
?>