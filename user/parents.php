<?php 
// Start the session and initialize database connection
session_start();
require "../config/db.php";
require "../api/checkBind.php";
 $page_name="table.form";
 $validationFile="parentV";

 $table_name="parents";
 $tableID="parents_id";
 
 // ================= ADMIN VIEW CONFIGURATION =================
 if($_SESSION['role']=='admin'){
    $title= "Parents";
    $table=array(
        "Name"=>"Name",
        "Job"=>"Job",
        "relation"=>"Relation",
        "parents_phone"=>"Phone",
        "email"=>"Email",
        "Kids1"=>"Kids 1",
        "Kids2"=>"Kids 2",
        "Action"=>"Action"
     );
    $fields=["Name","Job","relation","parents_phone","email","Kids1","Kids2"];
    
    // Search functionality setup
    $search = isset($_GET['search']) ? "%" . $_GET['search'] . "%" : "";
    $catalogue = $_GET['catalogue'] ?? 'none';
    
    // ================= SEARCH WITH SPECIFIC CATEGORY =================
   if((!empty($_GET['search'])) && $_GET['catalogue'] !=="none"){
    // Adjust search field for name concatenation
    if($catalogue=='name'){
        $catalogue="CONCAT(p.First_Name,' ',p.Last_Name)";
    }
    // Adjust search field for kids name
    if($catalogue=='Kids1'){
        $catalogue = "(SELECT CONCAT(s.First_Name, ' ', s.Last_Name)
                           FROM student_parents sp
                           JOIN students s ON sp.student_id = s.student_id
                           WHERE sp.parents_id = p.parents_id LIMIT 1)";
    }elseif($catalogue==='relation'){
        $catalogue="(SELECT relation FROM student_parents WHERE parents_id = p.parents_id LIMIT 1)";
    }elseif($catalogue==='Kids2'){
        $catalogue="(SELECT CONCAT(s.First_Name, ' ', s.Last_Name)
             FROM student_parents sp
             JOIN students s ON sp.student_id = s.student_id
             WHERE sp.parents_id = p.parents_id LIMIT 1 OFFSET 1)";
    }
        // Query with specific category filter
        $stmt=$conn->prepare("
        SELECT 
            p.parents_id,
            CONCAT(p.First_Name, ' ', p.Last_Name) AS Name,
            p.parents_phone,
            p.Job,
            u.email,
            (SELECT relation FROM student_parents WHERE parents_id = p.parents_id LIMIT 1) AS relation,
            (SELECT CONCAT(s.First_Name, ' ', s.Last_Name)
             FROM student_parents sp
             JOIN students s ON sp.student_id = s.student_id
             WHERE sp.parents_id = p.parents_id LIMIT 1) AS Kids1,
            (SELECT CONCAT(s.First_Name, ' ', s.Last_Name)
             FROM student_parents sp
             JOIN students s ON sp.student_id = s.student_id
             WHERE sp.parents_id = p.parents_id LIMIT 1 OFFSET 1) AS Kids2
        FROM parents p
        LEFT JOIN user as u on u.parents_id=p.parents_id
        where $catalogue like :search
        ");
        $stmt->execute([':search'=>$search]);
        $rows=$stmt->fetchAll(PDO::FETCH_ASSOC);

    // ================= SEARCH ACROSS ALL FIELDS =================
   }elseif((!empty($_GET['search'])) && $_GET['catalogue'] =="none"){
    // Query searching all relevant fields
    $stmt=$conn->prepare("
        SELECT 
            p.parents_id,
            CONCAT(p.First_Name, ' ', p.Last_Name) AS Name,
            p.parents_phone,
            p.Job,
            u.email,
            (SELECT relation FROM student_parents WHERE parents_id = p.parents_id LIMIT 1) AS relation,
            (SELECT CONCAT(s.First_Name, ' ', s.Last_Name)
             FROM student_parents sp
             JOIN students s ON sp.student_id = s.student_id
             WHERE sp.parents_id = p.parents_id LIMIT 1) AS Kids1,
            (SELECT CONCAT(s.First_Name, ' ', s.Last_Name)
             FROM student_parents sp
             JOIN students s ON sp.student_id = s.student_id
             WHERE sp.parents_id = p.parents_id LIMIT 1 OFFSET 1) AS Kids2
        FROM parents p
        LEFT JOIN user as u on u.parents_id=p.parents_id
        where CONCAT(p.First_Name,' ',p.Last_Name) like :search OR
        (SELECT relation FROM student_parents WHERE parents_id = p.parents_id LIMIT 1) like :search OR
        p.Job like :search OR
        u.email like :search OR
        relation like :search OR
        (SELECT CONCAT(s.First_Name, ' ', s.Last_Name)
             FROM student_parents sp
             JOIN students s ON sp.student_id = s.student_id
             WHERE sp.parents_id = p.parents_id LIMIT 1) like :search OR 
        (SELECT CONCAT(s.First_Name, ' ', s.Last_Name)
             FROM student_parents sp
             JOIN students s ON sp.student_id = s.student_id
             WHERE sp.parents_id = p.parents_id LIMIT 1 OFFSET 1) like :search OR
        p.parents_phone like :search
        ");
    $stmt->execute([':search'=>$search]);
    $rows=$stmt->fetchAll(PDO::FETCH_ASSOC);
   }
   // ================= DEFAULT VIEW (NO SEARCH) =================
   else{
    // Combine first and last names for both students and parents
    $stmt = $conn->prepare("
    SELECT
        p.parents_id,
        CONCAT(p.First_Name, ' ', p.Last_Name) AS Name,
        p.parents_phone,
        p.Job,
        u.email,
        (SELECT relation FROM student_parents WHERE parents_id = p.parents_id LIMIT 1) AS relation,
        (SELECT CONCAT(s.First_Name, ' ', s.Last_Name)
         FROM student_parents sp
         JOIN students s ON sp.student_id = s.student_id
         WHERE sp.parents_id = p.parents_id LIMIT 1) AS Kids1,
        (SELECT CONCAT(s.First_Name, ' ', s.Last_Name)
         FROM student_parents sp
         JOIN students s ON sp.student_id = s.student_id
         WHERE sp.parents_id = p.parents_id LIMIT 1 OFFSET 1) AS Kids2
    FROM parents p
    LEFT JOIN user as u on u.parents_id=p.parents_id
");
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
   }
   
    // Handle empty values - show N/A for missing kids
    foreach($rows as $row){
        if(empty($row["Kids2"])){
            $row["Kids2"]="N/A";
        }
        if(empty($row["Kids1"])){
            $row["Kids1"]="N/A";
        }
    }
 
 // ================= STUDENT VIEW CONFIGURATION =================
 }elseif($_SESSION['role']==='student'){
    $title='My Parents';
    $table=array(
        "name"=>"Name",
        "relation"=>"Relation",
        "birthday"=>"Birthday",
        "parents_id"=>"Parent ID"
     );
     $fields2=["Job"=>"Job","email"=>"Email","parents_phone"=>"Phone"];
     $fields=['relation','First_Name','Last_Name','parents_phone','parents_email'];
     
     // Get the student's parents information
     $myID=$_SESSION['roleID'];
     $stmt=$conn->prepare("
    SELECT 
        sp.relation,
        p.parents_id,
        p.parents_phone,
        up.birthday,
        up.email,
        p.Job,
        up.username,
        CONCAT(p.First_Name,' ',p.Last_Name) AS name
    FROM student_parents as sp
    JOIN parents as p on sp.parents_id=p.parents_id
    JOIN user as up on up.parents_id=p.parents_id
    WHERE sp.student_id = :student_id
    ");
     $stmt->execute([':student_id'=>$_SESSION['roleID']]);
     $rows=$stmt->fetchAll(PDO::FETCH_ASSOC);

 // ================= PARENT VIEW CONFIGURATION =================
 }elseif($_SESSION['role']==='parent'){
    $title='Me';
    $table=array(
        "parents_id"=>"My parent ID",
        "name"=>"Name",
        "Gender"=>"Gender",
        "Job"=>"Job",
        "birthday"=>"Birth",
        "email"=>"Email",
        "parents_phone"=>"Phone Contact"
     );
     // Configuration for completeness metrics
     $fields=[
        "id"=>[
            "weight"=>10,
            "title"=>"Setup account"
        ],
        "parents_id"=>[
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
        "parents_phone"=>[
            "weight"=>20,
            "title"=>"Setup phone number"
        ]
     ];
     
     // Get parent's own information
     $stmt=$conn->prepare("
     SELECT 
        u.email,
        u.Gender,
        u.username,
        u.birthday,
        u.id,
        p.parents_id,
        p.parents_phone,
        p.Job,
        CONCAT(p.First_Name,' ',p.Last_Name) AS name
    from user as u
    LEFT JOIN parents as p on p.parents_id=u.parents_id
    where u.id=:id limit 1
     ");
     $stmt->execute([':id'=>$_SESSION['id']]);
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
        // Include footer
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