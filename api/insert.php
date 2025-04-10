<?php
// ================= DATABASE CONNECTION =================
// Connect to the database using the config/db.php file
header('Content-Type: application/json');

// ================= BACKEND VALIDATION =================   
// Include the backValidation.php file for validation functions
require "../config/backValidation.php";

// ================= SESSION START =================
// Start the session to access session variables
session_start();
if (!isset($conn)) {
    echo json_encode(["code" => 1, "msg" => "Database connection failed!"]);
    exit;
}

// ================= TABLE VALIDATION =================
// Define the valid tables and their corresponding ID columns
$validTables=[
    'classes'=>'class_id',
    'students'=>'student_id',
    'teachers'=>'teacher_id',
    'salaries'=>'salaries_id',
    'parents'=>'parents_id',
    'library'=>'book_id',
    'chat_board'=>'chat_id'
];
$table=$_POST['table'] ?? "";


// ================= TABLE VALIDATION =================
// Check if the table is valid, avoid attack
if(!array_key_exists($table,$validTables)){
    echo json_encode(["code" => 1, "msg" => "This table invalid!"]);
    exit;
}
$key=$validTables[$table];

// ================= UPDATE DATA =================
// Get the update data from the POST request
$updateData = $_POST;
// Remove the table and ID column from the update data
unset($updateData['table']);     
unset($updateData[$key]);  
if($updateData['title']!=='My Kids'){
    unset($updateData['title']);
}
$notEmptyData = array_filter($updateData, function($value) {
    return $value !== null && $value !== '' && $value !=="none";
});

// ================= EMPTY DATA VALIDATION =================
// Check if the update data is empty
if(empty($notEmptyData)){
    echo json_encode(["code" => 1, "msg" => "Nothing to insert!"]);
    exit;
}

// ================= TITLE VALIDATION =================
// Check if the title is valid
if(isset($notEmptyData['title'])){
    // Check if the title is 'My Kids'      
    if($notEmptyData['title']==='My Kids'){
        unset($notEmptyData['title']);
        // Check if the student_name and relation are set
        if(isset($notEmptyData['student_name']) && isset($notEmptyData['relation'])){
            $stmt=$conn->prepare("SELECT student_id from students where CONCAT(First_Name,' ',Last_Name)=:name");
            $stmt->execute([':name'=>$notEmptyData['student_name']]);
            $result=$stmt->fetch(PDO::FETCH_ASSOC);
            if(!$result){
                echo json_encode(["code" => 1, "msg" => "Student not find!"]);
                exit;
            }

            // ================= INSERT INTO SP TABLE =================
            // Insert into the student_parents table
            $stmt = $conn->prepare("INSERT INTO student_parents (student_id, parents_id,relation) VALUES (:student_id, :parents_id,:relation)");
            $stmt->execute([':student_id'=>$result['student_id'],":parents_id"=>$_SESSION['roleID'],":relation"=>$notEmptyData['relation']]);

        }else{
            echo json_encode(["code" => 1, "msg" => "All fields required!"]);
            exit;
        }
        unset($notEmptyData['student_name']);
        unset($notEmptyData['relation']);
    }

}
//validation for class insert
if($table==='classes'){
    // for admin user who can manage the system
    // get the value from form
    $teacherName = $notEmptyData["Name"] ?? "";
    $className =$notEmptyData["class_name"] ?? "";
    $capacity = $notEmptyData["capacity"]?? "";
    $grade = $notEmptyData["grade"] ?? "";
    // check all inputs are not empty
    if (empty($teacherName) || empty($className) || empty($capacity) || empty($grade) || $grade==="none") {
        echo json_encode(["code" => 1, "msg" => "All fields are required!"]);
        exit;
    }
    // check if teacher's name input both first and last name
    $nameParts = explode(" ", $teacherName);
    if(count($nameParts) <2){
        echo json_encode(["code" => 1, "msg" => "Please input both First and Last name of the teacher"]);
        exit;
    }
    // check the capacity
    if($capacity<20 || $capacity>50){
        echo json_encode(["code"=>1,"msg"=>"The capacity of class should bewteen 20 and 50"]);
        exit;
    }

    // ================= TEACHER NAME VALIDATION =================
    // Check if the teacher's name is valid
    $teacherFirst=trim($nameParts[0]);
    // for multiple last anme
    $teacherLast=trim(implode(" ",array_slice($nameParts,1)));

    // ================= TEACHER EXISTENCE CHECK =================
    // Check if the teacher exists  
    $sql="SELECT teacher_id from teachers where First_name=:first_name AND Last_name=:last_name";
    $stmt=$conn->prepare($sql);
    $stmt->bindParam(":first_name",$teacherFirst);
    $stmt->bindParam(":last_name",$teacherLast);
    $stmt->execute();
    $teacherExist=$stmt->fetch(PDO::FETCH_ASSOC);

    // ================= TEACHER NOT FOUND =================
    // If the teacher doesn't exist, send the error message to front-end
    if(!$teacherExist){
        echo json_encode(["code" => 1, "msg" => "This teacher doesn't exist!"]);
        exit;
    }

    // ================= TEACHER CLASS EXISTENCE CHECK =================
    // Check if the teacher  already has a class
    $sql = "SELECT teacher_id, class_name FROM classes WHERE teacher_id = :teacher_id OR class_name = :class_name";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":teacher_id", $teacherExist["teacher_id"]);
    $stmt->bindParam(":class_name", $className);
    $stmt->execute();
    $existingClass = $stmt->fetch(PDO::FETCH_ASSOC);    

    if ($existingClass) {
        // ================= TEACHER CLASS EXISTENCE CHECK =================
        // Check if the teacher is already assigned to a class
        if (isset($existingClass["teacher_id"]) && $existingClass["teacher_id"] == $teacherExist["teacher_id"]) {
            echo json_encode(["code" => 1, "msg" => "This teacher is already assigned to class: " . $existingClass["class_name"]]);
            exit;
        }

        // ================= CLASS EXISTENCE CHECK =================
        // Check if the class name is already taken
        if (isset($existingClass["class_name"]) && $existingClass["class_name"] == $className) {
            echo json_encode(["code" => 1, "msg" => "This class Name is already taken!"]);
            exit;
        }
    }

    // ================= INSERT CLASS =================
    // Insert the class into the class table
    try {
        $sql = "INSERT INTO Classes (class_name, capacity, teacher_id, Grade) VALUES (:class_name, :capacity, :teacher_id, :Grade)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":class_name", $className);
        $stmt->bindParam(":capacity", $capacity);
        $stmt->bindParam(":teacher_id", $teacherExist["teacher_id"]);
        $stmt->bindParam(":Grade", $grade);

        // ================= INSERT SUCCESS =================
        // If the class is inserted successfully, send the success message to front-end
        if ($stmt->execute()) {
            echo json_encode([
                "code" => 0, 
                "msg" => "Insert Success!",
                "dataForm"=>[
                "year"=>$grade,
                "class_name"=>$className,
                "teacher"=>$teacherName,
                "capacity"=>$capacity
            ]
            ]);
        } else {
            // ================= INSERT FAILED =================
            // If the class is not inserted successfully, send the error message to front-end
            echo json_encode(["code" => 1, "msg" => "Insert Failed!"]);
        }
    } catch (PDOException $e) {
        // ================= DATABASE ERROR =================
        // If the database error occurs, send the error message to front-end
        echo json_encode([
            "code" => 1, 
            "msg" => "Database error: " . $e->getMessage(),
        ]);
    }
    exit;
}

    // ================= STUDENT INSERT =================
// Insert the student into the student table
if($table==='students'){
    $FirstName = trim($notEmptyData["First_Name"] ?? "");
    $LastName = trim($notEmptyData["Last_Name"] ?? "");
    $className = trim($notEmptyData["class_name"] ?? "");
    $address = trim($notEmptyData["address"] ?? "");
    $medicalInfor = trim($notEmptyData["medical_information"] ?? "");
    

    if($medicalInfor==="" || $medicalInfor==null){
        $medicalInfor="None";
    }

    // ================= REQUIRED FIELDS VALIDATION =================
    // Check if all required fields are not empty
    if ($FirstName === "" || $LastName === "" || $className === "" || $address === "") {
        echo json_encode(["code" => 1, "msg" => "All fields are required!"]);
        exit;
    }

    // ================= NAME FORMAT VALIDATION =================
    // Check if the name format is correct
    if(nameLength($FirstName) || nameLength($LastName)){
        echo json_encode(["code" => 1, "msg" => "Name format incorrect!"]);
        exit;
    }
    // ================= ADDRESS VALIDATION =================
    // Check if the address is valid
    if(addressValidation($address) || addressValidation($medicalInfor)){
        echo json_encode(["code" => 1, "msg" => "Address or Medical Information format incorrect!"]);
        exit;
    }


    // ================= STUDENT EXISTENCE CHECK =================
    // Check if the student exists
    $stmt = $conn->prepare("SELECT student_id FROM students WHERE first_name = :first_name AND last_name = :last_name");
    $stmt->execute([
        ':first_name' => $FirstName,
        ':last_name'  => $LastName
    ]);

    // ================= STUDENT EXISTENCE CHECK =================
    // Check if the student exists
    $existStudent = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($existStudent) {
        echo json_encode(["code" => 1, "msg" => "This student already exists!"]);
        exit;
    }

    // ================= CLASS EXISTENCE CHECK =================
    // Check if the class exists
    $stmt = $conn->prepare("SELECT class_id FROM classes WHERE class_name = :class_name");
    $stmt->execute([':class_name' => $className]);
    $classRow = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$classRow) {
        echo json_encode(["code" => 1, "msg" => "Class not found!"]);
        exit;
    }
    $classID = $classRow['class_id'];

    // ================= INSERT STUDENT =================
    // Insert the student into the student table
    $stmt=$conn->prepare("INSERT INTO students(First_Name,Last_Name,`address`,medical_information,class_id) VALUES (:first_name,:last_name,:address,:medical_information,:class_id)");
    if($stmt->execute([
        ":first_name"=>$FirstName,
        ":last_name"=>$LastName,
        ":address"=>$address,
        ":medical_information"=>$medicalInfor,
        ":class_id"=>$classID
    ])){
        // ================= INSERT SUCCESS =================
        // If the student is inserted successfully, send the success message to front-end
        echo json_encode(["code" => 0, "msg" => "Insert Success!"]);
    }else {
        // ================= INSERT FAILED =================
        // If the student is not inserted successfully, send the error message to front-end
        echo json_encode(["code" => 1, "msg" => "Insert Failed!"]);
    }
    
}

// ================= PARENTS INSERT =================
// Insert the parents into the parents table
if($table==='parents'){
    $parent_name = $notEmptyData["Name"] ?? "";
    $phone      = $notEmptyData["parents_phone"] ?? "";
    // $email      = $notEmptyData["parents_email"] ?? "";
    $child1     = $notEmptyData["Kids1"] ?? "";
    $child2     = $notEmptyData["Kids2"] ?? "";
    $relation   = $notEmptyData["relation"] ?? "";
    $job        = $notEmptyData['Job'] ?? "";

        // ================= REQUIRED FIELDS VALIDATION =================
    // Check if all required fields are not empty
    if (empty($parent_name) || empty($phone) || empty($child1) || empty($relation) || empty($job)) {
        echo json_encode(["code" => 1, "msg" => "All fields are required with at least 1 kid!"]);
        exit;
    }


    // ================= NAME DIVISION =================
    // Divide the name into first and last part
    $nameParts = explode(" ", $parent_name);
    $first_name = trim($nameParts[0]);
    $last_name = isset($nameParts[1]) ? trim(implode(" ", array_slice($nameParts, 1))) : "";

    // ================= NAME VALIDATION =================
    // Check if both contain first and last name
    if (empty($first_name) || empty($last_name)) {
        echo json_encode(["code" => 1, "msg" => "Please input both first and last name of the parent"]);
        exit;
    }

    // ================= KID'S NAME VALIDATION =================
    // Check if the kid's name input both first and last name
    $nameParts1 = explode(" ", $child1);
    if(count($nameParts1) <2){
        echo json_encode(["code" => 1, "msg" => "Please input both First and Last name of the child"]);
        exit;
    }

    $kid1First=$nameParts1[0];
    $kid1Last=trim(implode(" ",array_slice($nameParts1,1)));

    // ================= NAME LENGTH VALIDATION =================
    // Check if the name length is correct
    if (nameLength($first_name) || nameLength($last_name)) {
        echo json_encode(["code" => 1, "msg" => "The name length should be less than 50 characters!"]);
        exit;
    }

    // ================= PHONE FORMAT VALIDATION =================
    // Check if the phone format is correct
    if (!phoneValidation($phone)) {
        echo json_encode(["code" => 1, "msg" => "Incorrect phone format!"]);
        exit;
    }


    // ================= PHONE NUMBER EXISTENCE CHECK =================
    // Check if the phone number already exists
    $stmt=$conn->prepare("SELECT parents_phone from parents where parents_phone=:phone");
    $stmt->execute([
        ":phone"=>$phone
    ]);
    $existPE=$stmt->fetch(PDO::FETCH_ASSOC);
    if($existPE){
        // ================= PHONE NUMBER EXISTENCE CHECK =================
        // Check if the phone number already exists
        if ($existPE["parents_phone"] === $phone) {
            echo json_encode(["code" => 1, "msg" => "Phone number already existed!"]);
            exit;
        }

    }

    // ================= STUDENT ID SEARCH =================
    // Search the student id with that name
    $stmt=$conn->prepare("SELECT student_id from students where First_Name=:first_name AND Last_Name=:last_name");
    $stmt->execute([
        ":first_name"=>$kid1First,
        ":last_name"=>$kid1Last
    ]);
    // ================= STUDENT EXISTENCE CHECK =================
    // Check if the student exists
    $student_id=$stmt->fetch(PDO::FETCH_ASSOC);
    if (!$student_id) {
        echo json_encode(["code" => 1, "msg" => "Student not found!"]);
        exit;
    }

    // ================= PARENT COUNT CHECK =================
    // Check if the student already has two parents
    $stmt = $conn->prepare("SELECT COUNT(*) AS parent_count FROM student_parents WHERE student_id = :student_id");
    $stmt->execute([":student_id" => $student_id["student_id"]]);
    $countResult = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($countResult && $countResult['parent_count'] >= 2) {
        echo json_encode(["code" => 1, "msg" => "This student already has two parents!"]);
        exit;
    }
    // ================= INSERT PARENTS =================
    // Insert into the parent table
    $stmt=$conn->prepare("INSERT INTO parents(Last_Name,First_Name,parents_phone,Job) Values (:last_name,:first_name,:phone,:job)");
    $stmt->execute([
        ":last_name"=>$last_name,
        ":first_name"=>$first_name,
        ":phone"=>$phone,
        ":job"=>$job
    ]);
    // ================= GET PARENT ID =================
    // Get the parent id
    $parent_id = $conn->lastInsertId();

    // ================= INSERT RELATION =================
    // Insert their relation into sutdent_parent table
    $stmt=$conn->prepare("INSERT INTO student_parents (student_id,parents_id,relation) values (:students_id,:parents_id,:relation)");
    $stmt->execute([
        ":students_id"=>$student_id["student_id"],
        ":parents_id"=>$parent_id,
        ":relation"=>$relation
    ]);

    // ================= SECOND KID CHECK =================
    // Check if there is a second kid
    if (!empty($child2)) {
        // ================= SECOND KID FORMAT VALIDATION =================
        // Check if the second kid format is correct
        $nameParts2 = explode(" ", $child2);
        if(count($nameParts2) < 2){
            echo json_encode(["code" => 1, "msg" => "Please input both First and Last name of the second child!"]);
            exit;
        }
        $kid2First = $nameParts2[0];
        $kid2Last = trim(implode(" ", array_slice($nameParts2, 1)));
        // ================= STUDENT EXISTENCE CHECK =================
        // Check if the second kid exists
        $stmt = $conn->prepare("SELECT student_id FROM students WHERE First_Name = :first_name AND Last_Name = :last_name");
        $stmt->execute([
            ":first_name" => $kid2First,
            ":last_name"=> $kid2Last
        ]);
        $student_id2 = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$student_id2) {
            echo json_encode(["code" => 1, "msg" => "Second student not found!"]);
            exit;
        }

        // ================= PARENT COUNT CHECK =================
        // Check if the second kid already has two parents
        $stmt = $conn->prepare("SELECT COUNT(*) AS parent_count FROM student_parents WHERE student_id = :student_id");
        $stmt->execute([":student_id" => $student_id2["student_id"]]);
        $countResult2 = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($countResult2 && $countResult2['parent_count'] >= 2) {
            echo json_encode(["code" => 1, "msg" => "The second student already has two parents!"]);
            exit;
        }

        // ================= INSERT SECOND KID =================
        // Insert the second kid into the student_parents table
        $stmt = $conn->prepare("INSERT INTO student_parents (student_id, parents_id, relation) VALUES (:students_id, :parents_id, :relation)");
        $stmt->execute([
            ":students_id" => $student_id2["student_id"],
            ":parents_id"=> $parent_id,
            ":relation"=> $relation 
        ]);
    }

    echo json_encode(["code" => 0, "msg" => "Success!"]);
    exit;
}

// ================= TEACHER INSERT =================
// Insert the teacher into the teacher table
if($table==='teachers'){
    $FirstName=$notEmptyData["First_Name"] ?? "";
    $LastName=$notEmptyData["Last_Name"] ?? "";
    $phone=$notEmptyData["phone"] ?? "";


    if (isset($notEmptyData["backgroundCheck"]) && $notEmptyData["backgroundCheck"] === "yes") {
        $bcc = 1;
    } 
    if(isset($notEmptyData["backgroundCheck"]) && $notEmptyData["backgroundCheck"] === "no") {
        $bcc = 0;
    }

    // ================= EMPTY INPUT CHECK =================
    // Check if there are empty input box
    if (empty($FirstName) || empty($LastName) || empty($phone)) {
        echo json_encode(["code" => 1, "msg" => "All fields are required!"]);
        exit;
    }
    // ================= NAME LENGTH VALIDATION =================
    // Check if the name length is correct
    if(nameLength($FirstName) && nameLength($LastName)){
        echo json_encode(["code" => 1, "msg" => "First Name or Last Name incorrect format!"]);
        exit;
    }
    // ================= PHONE NUMBER VALIDATION =================
    // Check if the phone number follows the UK number format
    if(!phoneValidation($phone)){
        echo json_encode(["code" => 1, "msg" => "Incorrect UK phone-number format!"]);
        exit;
    }
    // ================= TEACHER EXISTENCE CHECK =================
    // Check if the teacher or phone number already exists
    $stmt=$conn->prepare("SELECT teacher_id FROM teachers WHERE (First_Name=:first_name AND Last_Name=:last_name) OR phone=:phone");
    $stmt->execute([
        ":first_name"=>$FirstName,
        ":last_name"=>$LastName,
        ":phone"=>$phone
    ]);
    $result=$stmt->fetch(PDO::FETCH_ASSOC);
    if (isset($result["First_Name"]) && isset($result["Last_Name"])) {
        echo json_encode(["code" => 1, "msg" => "This teacher already exists!"]);
        exit;
    }
    if (isset($result["phone"])) {
        echo json_encode(["code" => 1, "msg" => "Phone number already exists! Please try another one!"]);
        exit;
    }

    // ================= INSERT TEACHER =================
    // Insert the teacher into the teacher table
    try {
        $stmt=$conn->prepare("INSERT INTO teachers (Last_name,First_Name,phone,backgroundCheck) VALUES (:last_name, :first_name, :phone, :bcc)");
        $stmt->execute([
            ":last_name"=>$LastName,
            ":first_name"=>$FirstName,
            ":phone"=>$phone,
            ":bcc"=>$bcc
        ]);
    
        
        echo json_encode(["code" => 0, "msg" => "Insert Success!"]);
        exit;

    } catch (PDOException $e) {
        echo json_encode(["code" => 1, "msg" => "Database error: " . $e->getMessage()]);
    }
    exit;
}

// ================= SALARIES INSERT =================
// Insert the salaries into the salaries table
if($table==='salaries'){
    $teacher_name=$notEmptyData['Name'] ?? "";
    $expected_amount = (float) ($notEmptyData["expected_amount"] ?? 0);
    $penalty_amount = (float) ($notEmptyData["penalty_amount"] ?? 0);
    $actual_amount = $expected_amount - $penalty_amount;    
    $if_paid=$notEmptyData["if_paid"] ?? "";
    $salary_month = $notEmptyData['salary_month'] . '-01';

    // ================= EMPTY INPUT CHECK =================
    // Check if there are empty input box
    if(empty($teacher_name) || empty($expected_amount) ||  empty($penalty_amount) || empty($salary_month)){
        echo json_encode(["code" => 1, "msg" => "All fields are required!"]);
        exit;
    }

    // ================= NAME DIVISION CHECK =================
    // Check if the name could divided into first and last name
    if (str_word_count($teacher_name) < 2 || strpos($teacher_name, ' ') === false) {
        echo json_encode(["code" => 1, "msg" => "Please enter both first name and last name (e.g., John Smith)."]);
        exit;
    }
    

    // ================= DECIMAL NUMBER VALIDATION =================
    // Check if the input is a decimal number
    if(!validateAmount($expected_amount) || !validateAmount($penalty_amount)){
        echo json_encode(["code" => 1, "msg" => "Please input 2 decimal number!"]);
        exit;
    }

    // ================= TEACHER EXISTENCE CHECK =================
    // Check if the teacher exists
    $stmt=$conn->prepare("SELECT teacher_id from teachers where CONCAT(First_Name,' ',Last_Name)=:teacher_name");
    $stmt->execute([
        ":teacher_name"=>$teacher_name
    ]);
    $existTeacher=$stmt->fetch(PDO::FETCH_ASSOC);
    if(!$existTeacher){
        echo json_encode(["code" => 1, "msg" => "Teacher not found!"]);
        exit;
    }

    // ================= SALARY EXISTENCE CHECK =================
    // Check if the teacher already has salary in this month
    $stmt=$conn->prepare("SELECT count(*) from salaries where teacher_id=:teacher_id AND salary_month=:s_m");
    $stmt->execute([':teacher_id'=>$existTeacher['teacher_id'],":s_m"=>$salary_month]);
    if($stmt->fetchColumn()>0){
        echo json_encode(["code" => 1, "msg" => "This teacher's salaries of {$salary_month} already exist!"]);
        exit;
    }

    // ================= INSERT SALARY =================
    // Insert the salary into the salaries table
    $stmt=$conn->prepare("INSERT INTO salaries (teacher_id,expected_amount,penalty_amount,actual_amount,if_paid,salary_month) VALUES (:teacher_id,:expected_amount,:penalty_amount,:actual_amount,:if_paid,:salary_month)");
    $stmt->execute([
        ":teacher_id"=>$existTeacher["teacher_id"],
        ":expected_amount"=>$expected_amount,
        ":penalty_amount"=>$penalty_amount,
        ":actual_amount"=>$actual_amount,
        ":if_paid"=>$if_paid,
        ":salary_month"=>$salary_month
    ]);

    echo json_encode(["code" => 0, "msg" => "Insert success!"]);
    exit;
}

// ================= LIBRARY INSERT =================
// Insert the library into the library table
if($table==='library'){
    if($_SESSION["role"]==="admin"){
        $book_title=$notEmptyData["book_title"] ?? "";
        $author=$notEmptyData["Author"] ?? "";
        $publishDate=$notEmptyData["publishDate"] ?? "";

        // ================= EMPTY INPUT CHECK =================
        // Check if all fields are not empty
        if(empty($book_title) || empty($author) || empty($publishDate)){
            echo json_encode(["code" => 1, "msg" => "Title, Author and PublishDate are required!"]);
            exit;
        }
    
        // ================= BOOK TITLE VALIDATION =================
        // Check if the book title is valid
        if(nameLength($book_title) || nameLength($author)){
            echo json_encode(["code" => 1, "msg" => "Name or Title format incorrect!"]);
            exit;
        }
    
        // ================= DATE VALIDATION =================
        // Check if the date is valid
        $dateObj = DateTime::createFromFormat('Y-m-d', $publishDate);
        if (!$dateObj || $dateObj->format('Y-m-d') !== $publishDate) {
        echo json_encode(["code" => 1, "msg" => "Invalid date format!"]);
        exit;
        }
        // ================= DATE VALIDATION =================
        // Check if the date is valid
        $dateNow=new DateTime();
        if ($dateObj > $dateNow) {
            echo json_encode(["code" => 1, "msg" => "Publish date cannot be in the future!"]);
            exit;
        }
    
        // ================= BOOK EXISTENCE CHECK =================
        // Check if the book already exists
        $stmt=$conn->prepare("SELECT Book_id from library where title=:title AND Author=:author");
        $stmt->execute([
            ":title"=>$book_title,
            ":author"=>$author
        ]);
        if($stmt->fetch(PDO::FETCH_ASSOC)){
            echo json_encode(["code" => 1, "msg" => "Book already exist!"]);
            exit;
        }
        $notEmptyData['cover'] = getBookCoverFromGoogle($book_title, $author);
        // ================= INSERT LIBRARY =================
        // Insert the library into the library table
        $stmt=$conn->prepare("INSERT INTO library (cover,title,Author,publishDate) VALUES (:cover,:title,:author,:publishDate)");
        $stmt->execute([
            ":cover"=>$notEmptyData['cover'],
            ":title"=>$book_title,
            ":author"=>$author,
            ":publishDate"=>$publishDate
        ]);
        echo json_encode(["code" => 0, "msg" => "Success!"]);
        exit;
    
    
    }
    
    // ================= STUDENT INSERT =================
    // Insert the student into the student table    
    if($_SESSION["role"]==="student"){
        $book_title=$notEmptyData["book_title"] ?? "";
        $author=$notEmptyData["author"] ?? "";
        $publishDate=$notEmptyData["publishDate"] ?? "";
        $borrowDate="";
        $dueDate=$notEmptyData["dueDate"] ?? "";
        $student_id=$notEmptyData["myid"] ?? "";

        // ================= EMPTY INPUT CHECK =================
        // Check if all fields are not empty
        if(empty($book_title)|| empty($author) || empty($publishDate) || empty($dueDate) || empty($dueDate) || empty($student_id)){
            echo json_encode(["code" => 1, "msg" => "All fields are required!"]);
            exit;
        }
    
        // ================= BOOK TITLE VALIDATION =================
        // Check if the book title is valid
        if(nameLength($book_title) || nameLength($author)){
            echo json_encode(["code" => 1, "msg" => "Name or Title format incorrect!"]);
            exit;
        }
        // ================= DUE DATE VALIDATION =================
        // Check if the due date is valid
        $dateObj = DateTime::createFromFormat('Y-m-d', $dueDate);
        if (!$dateObj || $dateObj->format('Y-m-d') !== $dueDate) {
        echo json_encode(["code" => 1, "msg" => "Invalid date format!"]);
        exit;
        }
        $dateNow=new DateTime();
        if ($dateObj <= $dateNow) {
            echo json_encode(["code" => 1, "msg" => "Return date cant be today or past day!"]);
            exit;
        }
    
        // ================= BOOK EXISTENCE CHECK =================
        // Check if the book exists
        $stmt=$conn->prepare("SELECT Book_id from library where title=:title AND Author=:author AND publishDate=:publishDate");
        $stmt->execute([
            ":title"=>$book_title,
            ":author"=>$author,
            ":publishDate"=>$publishDate
        ]);
        $resultT=$stmt->fetch(PDO::FETCH_ASSOC);
        if(!$resultT){
            echo json_encode(["code" => 1, "msg" => "This book not found!"]);
            exit;
        }
        // ================= STUDENT EXISTENCE CHECK =================
        // Check if the student exists
        $stmt=$conn->prepare("SELECT * from students where student_id=:student_id");
        $stmt->execute([":student_id"=>$student_id]);
        if(!$stmt->fetch(PDO::FETCH_ASSOC)){
            echo json_encode(["code" => 1, "msg" => "This student not found!"]);
            exit;
        }
        // ================= INSERT BORROWED BOOK =================
        // Insert the borrowed book into the borrowed_book table
        $stmt=$conn->prepare("INSERT INTO borrowed_book(Student_id,Book_id,borrowDate,DueDate) VALUES (:student_id,:book_id,:borrowedDate,:dueDate)");
        $stmt->execute([
            ":student_id"=>$student_id,
            ":book_id"=>$resultT["Book_id"],
            ":borrowedDate"=>$dateNow->format('Y-m-d'),
            ":dueDate"=>$dueDate
        ]);
        echo json_encode(["code" => 0, "msg" => "Success!"]);
        exit;
    }
}

// ================= CHAT BOARD INSERT =================
// Insert the chat board into the chat_board table
if($table==='chat_board'){
    if($_SESSION['role']!='admin'){
        // ================= TYPE CHECK =================
        // Check if the type is valid   
        $type='message';
        if(empty($notEmptyData['chat-title']) && empty($notEmptyData['content'])){
            echo json_encode(["code" => 1, "msg" => "All fileds required!"]);
            exit;
        }
    }else{
        // ================= TYPE CHECK =================
        // Check if the type is valid
        $type=$notEmptyData['type'] ?? 'message';
        if(empty($notEmptyData['type']) || empty($notEmptyData['chat-title']) || empty($notEmptyData['content'])){
            echo json_encode(["code" => 1, "msg" => "All fileds required!"]);
            exit;
        }
    }

    // ================= CURRENT DATE =================
    // Get the current date
    $now = date('Y-m-d H:i:s');
    $stmt=$conn->prepare("INSERT into chat_board (user_id,`type`,title,content,created_date) VALUES (:id,:type,:title,:content,:created_date)");
    $stmt->execute([
        ":id"=>$_SESSION['id'],
        ":type"=>$type,
        ":title"=>$notEmptyData['chat-title'],
        ":content"=>$notEmptyData['content'],
        ":created_date"=>$now
    ]);
    echo json_encode(["code" => 0, "msg" => "Post success!"]);
    exit;
}
?>