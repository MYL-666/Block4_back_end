<?php 
// Start the session and initialize database connection
session_start();
require "../config/db.php";
require "../api/checkBind.php";
 $page_name="table.form";
 $validationFile="teacherV";
 $table_name="teachers";
 $tableID="teacher_id";
 $formIDs=['first_name','last_name','phone'];
 
 // ================= ADMIN VIEW CONFIGURATION =================
 if($_SESSION['role']==='admin'){
    $title= "Teachers";
    $table=array(
        "First_Name"=>"First Name",
        "Last_Name"=>"Last Name",
        "phone"=>"Phone",
        "email"=>"Email",
        "backgroundCheck"=>"BackgroundCheck",
        "Action"=>"Action"
     );
    $fields=["First_Name","Last_Name","phone","email","backgroundCheck"];
    
    // Search functionality setup
    $search = isset($_GET['search']) ? "%" . $_GET['search'] . "%" : "";
    $catalogue = $_GET['catalogue'] ?? 'none';

    // ================= SEARCH WITH SPECIFIC CATEGORY =================
    if(!empty($_GET['search']) && $_GET['catalogue']!=="none"){
        // Special handling for backgroundCheck field (exact match)
        if($catalogue=='backgroundCheck'){
            $stmt = $conn->prepare("SELECT                 
                t.*,u.email
                from teachers as t 
                LEFT JOIN user as u on u.teacher_id=t.teacher_id WHERE t.backgroundCheck = :search");
            $stmt->execute([':search' => (int)($_GET['search'])]);
        }else{
            // Standard search with like operator
            $stmt=$conn->prepare("
            SELECT
                t.*,u.email
                from teachers as t 
                JOIN user as u on u.teacher_id=t.teacher_id
                WHERE $catalogue like :search
        ");
        $stmt->execute([':search'=>$search]);
        }
        
        $rows=$stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // ================= SEARCH ACROSS ALL FIELDS =================
    elseif(!empty($_GET['search']) && $_GET['catalogue']==="none"){
            $stmt=$conn->prepare("
            SELECT
                t.*,u.email
                from teachers as t 
                JOIN user as u on u.teacher_id=t.teacher_id
                WHERE t.First_Name like :search OR
                    t.Last_Name like :search OR
                    t.phone like :search OR
                    u.email like :search OR
                    t.backgroundCheck like :search
        ");
        $stmt->execute([':search'=>$search]);
        $rows=$stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // ================= DEFAULT VIEW (NO SEARCH) =================
    else{
        // Get all teacher records with email
        $stmt=$conn->prepare("SELECT t.*,u.email from teachers as t LEFT JOIN user as u on u.teacher_id=t.teacher_id");
        $stmt->execute();
        $rows=$stmt->fetchAll(PDO::FETCH_ASSOC);
    }
 
 // ================= STUDENT VIEW CONFIGURATION =================
 }elseif($_SESSION['role']==='student'){
    $title= "My Teacher";
    $table=array(
        "teacher_id"=>"Teacher's ID",
        "name"=>"Name",
        "Gender"=>"Gender",
        "birthday"=>"Birth",
        "email"=>"Email",
        "phone"=>"Phone Contact"
     );
     $fields2=["username","class_infor"];
     $fields=["class_name","First_Name","Last_Name","phone","backgroundCheck"];
     
     // Get the student's teacher information
     $stmt=$conn->prepare("
        SELECT 
            *,c.class_name,
            CONCAT(t.First_Name,' ',t.Last_Name) as name,
            CONCAT(c.Grade,'-',c.class_name) as class_infor
            from classes as c
            left join students as s on s.class_id=c.class_id
            left join  teachers as t on c.teacher_id=t.teacher_id
            JOIN user as u on u.teacher_id=t.teacher_id
            where s.student_id=:student
     ");
     $stmt->execute([':student'=>$_SESSION['roleID']]);
     $rows=$stmt->fetchAll(PDO::FETCH_ASSOC);
 
 // ================= TEACHER VIEW CONFIGURATION =================
 }elseif($_SESSION['role']==='teacher'){
    $title='Me';
    $table=array(
        "teacher_id"=>"My teacher ID",
        "name"=>"Name",
        "Gender"=>"Gender",
        "birthday"=>"Birth",
        "email"=>"Email",
        "phone"=>"Phone Contact"
     );
     
     // Configuration for profile completeness metrics
     $fields=[
        "id"=>[
            "weight"=>10,
            "title"=>"Setup account"
        ],
        "teacher_id"=>[
           "weight" =>30,
           "title"=>"Link with school ID"
        ],
        "Gender"=>[
            "weight"=>10,
            "title"=>"Select your gender"
        ],
        "birthday"=>[
            "weight"=>10,
            "title"=>"Setup your birth"
        ],
        "email"=>[
            "weight"=>20,
            "title"=>"Setup your email"
        ],
        "phone"=>[
            "weight"=>20,
            "title"=>"Setup phone number"
        ]
     ];
     
     // Get teacher's own information
     $stmt=$conn->prepare("
     SELECT 
        *,
        CONCAT(t.First_Name,' ',t.Last_Name) AS name
        from teachers as t
        Join classes as c on c.teacher_id=t.teacher_id
        JOIN user as u on u.teacher_id=t.teacher_id
        where t.teacher_id=:teacher_id
     ");
     $stmt->execute([':teacher_id'=>$_SESSION['roleID']]);
     $rows=$stmt->fetch(PDO::FETCH_ASSOC);
 }

// ================= FALLBACK FOR EMPTY RESULTS =================
// If no data found, provide default placeholder values
if(empty($rows)){
    $rows = [array_merge([$tableID => "0"], array_fill_keys($fields, "N/A"))];
}
?>

<!DOCTYPE html>
<html lang="en">
<?php 
    // ================= PAGE STRUCTURE =================
    // Include header with CSS and meta information
    require "./common/head.php"
?>
<body>
    <main>
        <?php
            // Include sidebar navigation
            require "./common/slidebar.php";
        ?>
        <div class="container">
            <?php 
                // Include the main table component
                require "./common/table.php";
            ?>
        </div>
    </main>
    <?php   
        // ================= INCLUDE SCRIPTS AND FUNCTIONALITY =================
        // Include footer and functionality scripts
        require "./common/footer.php";
        
        // Import JavaScript functionality
        // Import insert functionality
        require "../api/fetch.insert.php";

        // Import edit functionality
        require "../api/fetch.edit.php";
        
        // Import delete functionality
        require "../api/fetch.delete.php";

        // Import profile completion chart
        require "../api/profile.doughnut.php";
    ?>

</body>
</html>
