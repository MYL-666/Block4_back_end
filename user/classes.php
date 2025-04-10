
<!-- /**
 * classes.php
 * This file contains the class management interface for the website.
 * It includes class information display, student list, and teacher assignments.
 * The interface features different views based on user roles (admin, teacher, student).
 */ -->

<?php 
// =========== INITIALIZATION SECTION ===========
session_start();
require "../config/db.php";
$table_name="classes";
$page_name="table.form";
$validationFile="classV";
$tableID="class_id";

// =========== ADMIN VIEW SECTION ===========
if($_SESSION['role']==='admin'){
    $title= "Classes";
    // Define table columns for admin view
    $table=array(
        "Grade"=>"Grade", 
        "class_name"=>" Class Name",
        "Name"=>"Teacher",
        "capacity"=>"Capacity",
        "Action"=>"Action"
     );
    
    $fields=["Grade","class_name","Name","capacity"];

    // Search and filter functionality
    $search = isset($_GET['search']) ? "%" . $_GET['search'] . "%" : "";
    $catalogue = $_GET['catalogue'] ?? 'none';

    // Search with specific catalogue
    if(!empty($_GET['search']) && $_GET['catalogue'] !=="none"){
      if($catalogue=='Name'){
        $catalogue="CONCAT(teachers.First_Name,' ' ,teachers.Last_Name)";
      }
      $stmt=$conn->prepare("
      SELECT 
          Grade,class_name,capacity,class_id, 
          CONCAT(teachers.First_Name,' ' ,teachers.Last_Name) AS `Name` 
          from classes 
          left join teachers on classes.teacher_id=teachers.teacher_id
          WHERE $catalogue like :search
          "
      );
      $stmt->execute([":search"=>$search]);
      $rows=$stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // Search without specific catalogue
    elseif(!empty($_GET['search']) && $_GET['catalogue'] =="none"){
      $stmt=$conn->prepare("
      SELECT 
          c.Grade,c.class_name,c.capacity,c.class_id, 
          CONCAT(teachers.First_Name,' ' ,teachers.Last_Name) AS `Name` 
          from classes as c
          left join teachers on c.teacher_id=teachers.teacher_id
          WHERE c.Grade like :search OR
            c.class_name like :search OR
            c.capacity like :search OR
            CONCAT(teachers.First_Name,' ' ,teachers.Last_Name) like :search
          ");
      $stmt->execute([":search"=>$search]);
      $rows=$stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // Default view without search
    else{
      $stmt=$conn->prepare("
      SELECT 
          Grade,class_name,capacity,class_id, 
          CONCAT(teachers.First_Name,' ' ,teachers.Last_Name) AS `Name` 
          from classes 
          left join teachers on classes.teacher_id=teachers.teacher_id"
      );
      $stmt->execute();
      $rows=$stmt->fetchAll(PDO::FETCH_ASSOC);
    }

// =========== STUDENT VIEW SECTION ===========
}elseif($_SESSION['role']=='student'){
    $title= "My Class & Classmates";
    $fields=["student_name","class_infor","address","medical_information","Gender","email"];
    // Define table columns for student view
    $table=array(
        "student_name"=>"Student Name",
        "class_infor"=>"Class Infor",
        "address"=>"Address",
        "medical_information"=>"Medical Infor",
        "Gender"=>'Gender',
        "email"=>"Email",
        "Action"=>"Action"
    );
    
    // Fetch student's class information
    $stmt=$conn->prepare("
     SELECT
        CONCAT(s.First_Name,' ',s.Last_Name) as student_name,
        CONCAT(c.Grade,'-',c.class_name) as class_infor,
        s.medical_information,
        s.address, c.class_id,
        u.Gender,
        u.birthday,
        u.email
        from students as s
        JOIN classes as c on s.class_id=c.class_id
        JOIN user as u on u.student_id=s.student_id
        WHERE s.class_id = (
    SELECT class_id FROM students WHERE student_id=:student_id
    )
     ");
     $stmt->execute([':student_id'=>$_SESSION['roleID']]);
     $rows=$stmt->fetchAll(PDO::FETCH_ASSOC);

// =========== TEACHER VIEW SECTION ===========
}elseif($_SESSION['role']==='teacher'){
    $title='My Class';
    $fields=["Grade","class_name","capacity","numbers"];
    // Define table columns for teacher view
    $table=array(
        "Grade"=>"Grade",
        "Class Name"=>"Class Name",
        "Max-Capacity"=>"Max-Capacity",
        "Number of Students"=>'Number of Students',
        "Action"=>"Action"
    );
    // Fetch teacher's class information with student count
    $stmt=$conn->prepare("
        SELECT 
            c.Grade,c.class_name,c.capacity,c.class_id,
            count(s.student_id) as numbers
            from teachers as t
            Join classes as c on t.teacher_id=c.teacher_id
            LEFT JOIN students as s on c.class_id=s.class_id
            where t.teacher_id=:teacher_id
            GROUP BY c.class_id, c.Grade, c.class_name, c.capacity
    ");
    $stmt->execute([':teacher_id'=>$_SESSION['roleID']]);
    $rows=$stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Handle empty results
if(empty($rows)){
    $rows = [array_merge([$tableID => "0"], array_fill_keys($fields, "N/A"))];
 }
?>

<!DOCTYPE html>
<html lang="en">
<?php 
    // Include head.php
    require "./common/head.php";
?>
<body>
    <main>
        <?php
            // Include slidebar.php
            require "./common/slidebar.php";
        ?>
        <div class="container">
            <?php
                // Include table.php
                require "./common/table.php";
            ?>
        </div>
    </main>
    <?php   
        // Include footer.php
        require "./common/footer.php";
        // Import required API files
        require "../api/fetch.insert.php";
        require "../api/fetch.edit.php";

        //import delete file
        require "../api/fetch.delete.php";

        if($_SESSION['role']==='teacher'){
    ?>

<script>
    // =========== GENDER CHART SECTION ===========
    fetch("../api/chartDrawing.php")
      .then(res => res.json())
      .then(res => {
        const { male, female } = res.studentStats;
        const ctx = document.getElementById("genderChart").getContext("2d");
        new Chart(ctx, {
          type: "pie",
          data: {
            labels: ["Male", "Female"],
            datasets: [{
              data: [male, female],
              backgroundColor: [
                "#4B9CD3", 
                "#F9A8D4"  
              ],
              borderWidth: 1
            }]
          },
          options: {
            responsive: true,
            plugins: {
              title: {
                display: true,
                text: "Gender Distribution"
              },
              legend: {
                position: "bottom"
              }
            }
          }
        });
      });

    // =========== CAPACITY CHART SECTION ===========
    fetch("../api/chartDrawing.php")
      .then(res => res.json())
      .then(res => {
        const { occupid:occupid, remain:remain } = res.capacityStats;
        const ctx = document.getElementById("capacityChart").getContext("2d");
        new Chart(ctx, {
          type: "pie",
          data: {
            labels: ["Occupid", "Remain"],
            datasets: [{
              data: [occupid, remain],
              backgroundColor: [
                "#4B9CD3", 
                "#F9A8D4"  
              ],
              borderWidth: 1
            }]
          },
          options: {
            responsive: true,
            plugins: {
              title: {
                display: true,
                text: "Class capcaity"
              },
              legend: {
                position: "bottom"
              }
            }
          }
        });
      });

    // =========== BIRTHDAY CHART SECTION ===========
    fetch("../api/chartDrawing.php")
      .then(res => res.json())
      .then(res => {
        const birthdayData = res.birthdayData;
        const labels = birthdayData.map(item => item.year);
        const data = birthdayData.map(item => item.count);
        const ctx = document.getElementById("birthdayChart").getContext("2d");
        new Chart(ctx, {
            type: "bar",
            data: {
              labels: labels,
              datasets: [{
                label: "Number of Students",
                data: data,
                backgroundColor: "#7FB77E"
              }]
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              plugins: {
                title: {
                  display: true,
                  text: "Birth Year Distribution of My Students"
                }
              },
              scales: {
                y: {
                  beginAtZero: true
                }
              }
            }
          });
      });
</script>
<?php } ?>
</body>
</html>