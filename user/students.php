<?php 
/**
 * Student Information Management Page
 * Displays different student information views based on user roles (admin, student, teacher, parent)
 */

// Start session and connect to database
session_start();
require "../config/db.php";
require "../api/checkBind.php";
// Basic page configuration
$page_name = "table.form";
$validationFile = "studentV";
$table_name = "students";
$tableID = "student_id";

// Default field definitions
$fields = ["First_Name", "Last_Name", "address", "medical_information", "class_name"];

// ================= ROLE-BASED VIEW CONFIGURATION =================

// ================= ADMIN VIEW CONFIGURATION =================
if($_SESSION['role'] === 'admin') {
    $table = array(
        "First_Name" => "First Name",
        "Last_Name" => "Last Name",
        "address" => "Address",
        "medical_information" => "Medical_Information",
        "class_name" => "Class_Name",
        "Action" => "Action"
    );
    $title = "Students";
    $fields = ["First_Name", "Last_Name", "address", "medical_information", "class_name"];
    
    // Search functionality setup
    $search = isset($_GET['search']) ? "%" . $_GET['search'] . "%" : "";
    $catalogue = $_GET['catalogue'] ?? 'none';

    // ================= SEARCH WITH SPECIFIC CATEGORY =================
    if(!empty($_GET['search']) && $_GET['catalogue']!=="none"){
        $sql="
            SELECT 
                * From students as s
                JOIN classes as c on c.class_id=s.class_id
                where $catalogue like :search
        ";
        $stmt=$conn->prepare($sql);
        $stmt->execute([':search'=>$search]);
        $rows=$stmt->fetchAll(PDO::FETCH_ASSOC);

    // ================= SEARCH ACROSS ALL FIELDS =================
    }elseif(!empty($_GET['search']) && $_GET['catalogue']=="none"){
        $sql = "
        SELECT 
            * FROM students AS s
            JOIN classes AS c ON c.class_id = s.class_id
            WHERE 
                First_Name LIKE :search OR
                Last_Name LIKE :search OR
                address LIKE :search OR
                medical_information LIKE :search OR
                class_name LIKE :search
    ";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':search' => $search]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // ================= DEFAULT VIEW (NO SEARCH) =================
    else{
        // Admin view: Display all student information
    
        // Query all student information
        $stmt = $conn->prepare("SELECT * from students Left JOIN classes on classes.class_id=students.class_id");
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }  

// ================= STUDENT VIEW CONFIGURATION =================
} elseif ($_SESSION['role'] == 'student') { 
    $title = "Me"; 
    // Student view: Display only their own information
    $table=array(
        "student_id"=>"My Student ID",
        "name"=>"Name",
        "Gender"=>"Gender",
        "birthday"=>"Birth",
        "email"=>"Email"
     );
     
     // Configuration for profile completeness metrics
     $fields=[
        "id"=>[
            "weight"=>10,
            "title"=>"Setup account"
        ],
        "student_id"=>[
           "weight" =>30,
           "title"=>"Link with school ID"
        ],
        "Gender"=>[
            "weight"=>10,
            "title"=>"Select your gender"
        ],
        "birthday"=>[
            "weight"=>20,
            "title"=>"Setup your birth"
        ],
        "email"=>[
            "weight"=>30,
            "title"=>"Setup your email"
        ]
     ];

     // Get student's own information
     $stmt=$conn->prepare("
            SELECT 
                s.student_id,
                CONCAT(s.First_Name, ' ', s.Last_Name) AS name,
                u.Gender,
                u.birthday,
                u.email,
                u.id
            FROM students AS s
            JOIN user AS u ON u.student_id = s.student_id
            LEFT JOIN classes AS c ON c.class_id = s.class_id
            WHERE s.student_id = :roleID
     ");
    $stmt->execute([':roleID' => $_SESSION['roleID']]);
    $rows = $stmt->fetch(PDO::FETCH_ASSOC);

// ================= TEACHER VIEW CONFIGURATION =================
} elseif($_SESSION['role'] === 'teacher') {
    // Teacher view: Display all students in their classes
    $title = 'My Student';
    $table = array(
        "student_name" => "Name",
        "gender" => "Gender",
        "birthday" => "Birth",
        "address" => "Address",
        'medical_information' => 'Medical Infor'
    );
    
    // Parent information display field configuration
    $fields2 = [
        "parent_name" => "Name",
        "relation" => 'relation',
        "parents_phone" => "Phone",
        "email" => 'Email'
    ];
    
    // ================= QUERY STUDENTS IN TEACHER'S CLASSES =================
    // First query to get unique students
    $stmt = $conn->prepare("
    SELECT DISTINCT
        s.student_id,
        CONCAT(s.First_Name,' ',s.Last_Name) as student_name,
        us.gender, us.birthday,
        s.address, s.medical_information
    FROM teachers as t
    LEFT JOIN classes as c ON t.teacher_id = c.teacher_id
    LEFT JOIN students as s ON s.class_id = c.class_id
    LEFT JOIN user as us ON us.student_id = s.student_id
    LEFT JOIN student_parents as sp on sp.student_id=s.student_id
    LEFT JOIN user as up ON up.parents_id=sp.parents_id
    WHERE c.class_id IN (SELECT class_id FROM classes WHERE t.teacher_id = :teacher_id)
    AND s.student_id IS NOT NULL
    ");
    $stmt->execute([':teacher_id' => $_SESSION['roleID']]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ================= QUERY PARENT INFORMATION =================
    // Create a lookup array for parents
    $studentParents = [];
    if (!empty($rows)) {
        $studentIds = array_column($rows, 'student_id');
        $parentStmt = $conn->prepare("
            SELECT 
                sp.student_id,
                CONCAT(p.First_Name,' ',p.Last_Name) as parent_name,
                sp.relation,
                p.parents_phone,
                u.email
            FROM student_parents sp
            JOIN parents p ON p.parents_id = sp.parents_id
            JOIN user as u on u.parents_id=p.parents_id
            WHERE sp.student_id IN (" . str_repeat('?,', count($studentIds) - 1) . "?)
        ");
        $parentStmt->execute($studentIds);
        $parents = $parentStmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Organize parents by student ID
        foreach ($parents as $parent) {
            $studentParents[$parent['student_id']][] = [
                'parent_name' => $parent['parent_name'],
                'relation' => $parent['relation'],
                'parents_phone' => $parent['parents_phone'],
                'email' => $parent['email']
            ];
        }
    }

    // Add parents data to each student row
    foreach ($rows as $key => $row) {
        $rows[$key]['parents'] = isset($studentParents[$row['student_id']]) 
            ? $studentParents[$row['student_id']] 
            : [];
    }

// ================= PARENT VIEW CONFIGURATION =================
} elseif($_SESSION['role'] === 'parent') {
    // Parent view: Display their children's information
    $title = 'My Kids';
    $table = array(
        "student_name" => "Name",
        "Gender" => "Gender",
        "birthday" => "Birth",
        "address" => "Address",
        'medical_information' => 'Medical Infor'
    );
    $fields2 = [
        "teacher_name" => "Name",
        "class_infor" => 'Class',
        "phone" => "Phone",
        "email" => 'Email'
    ];
    
    // ================= QUERY CHILDREN AND THEIR TEACHERS =================
    // Query children's class and teacher information
    $stmt = $conn->prepare("
    SELECT
        CONCAT(s.First_Name,' ',s.Last_Name) as student_name,
        CONCAT(t.First_Name,' ',t.Last_Name) as teacher_name,
        CONCAT(c.Grade,'-',c.class_name) as class_infor,
        s.student_id, s.address, s.medical_information, t.phone,
        ut.email,us.Gender,us.birthday,
        COUNT(*) OVER() AS kid_number
        FROM parents as p
    JOIN student_parents as sp ON sp.parents_id = p.parents_id
    JOIN students as s ON sp.student_id = s.student_id
    JOIN user as us ON us.student_id=s.student_id
    JOIN classes as c ON s.class_id = c.class_id
    JOIN teachers as t ON c.teacher_id = t.teacher_id
    JOIN user as ut ON ut.teacher_id=t.teacher_id
    WHERE p.parents_id = :parent_id
    ");
    $stmt->execute([':parent_id' => $_SESSION['roleID']]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// ================= FALLBACK FOR EMPTY RESULTS =================
// Handle empty result set
if(empty($rows)) {
    $rows = [array_merge([$tableID => "0"], array_fill_keys($fields, "N/A"))];
}
?>

<!DOCTYPE html>
<html lang="en">
<?php 
    // ================= PAGE STRUCTURE =================
    // Include page header
    require "./common/head.php";
?>
<body>
    <main>
        <?php
            // Include sidebar
            require "./common/slidebar.php";
        ?>
        <div class="container">
            <?php 
                // Include main content table
                require "./common/table.php";
            ?>
        </div>
    </main>
    <?php   
        // ================= INCLUDE SCRIPTS AND FUNCTIONALITY =================
        // Include footer and functionality scripts
        require "./common/footer.php";
        require "../api/fetch.insert.php";  // Data insertion functionality
        require "../api/fetch.edit.php";    // Data editing functionality
        require "../api/fetch.delete.php";  // Data deletion functionality
        require "../api/profile.doughnut.php"; // Profile completion chart
    ?>
</body>
</html>