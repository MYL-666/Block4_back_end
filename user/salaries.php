<?php 
// Start the session and initialize database connection
session_start();
require "../config/db.php";
require "../api/checkBind.php";
$page_name="table.form";
$validationFile="salariesV";
$table_name="salaries";
$tableID="salaries_id";

// ================= ADMIN VIEW CONFIGURATION =================
if($_SESSION['role']==='admin'){
    $title= "Salaries";
    $table=array(
        "Name"=>"Teachers",
        "expected_amount"=>"Expected Amount($)",
        "penalty_amount"=>"Penalty Amount($)",
        "actual_amount"=>"Actual Amount($)",
        "salary_month"=>"Month of Salary",
        "if_paid"=>"Status",
        "Action"=>"Action"
     );
     $fields=["Name","expected_amount","penalty_amount","actual_amount","salary_month","if_paid"];
     
     // Search functionality setup
     $search = isset($_GET['search']) ? "%" . $_GET['search'] . "%" : "";
     $catalogue = $_GET['catalogue'] ?? 'none';

     // ================= SEARCH WITH SPECIFIC CATEGORY =================
     if(!empty($_GET['search']) && $_GET['catalogue'] !=="none"){
        // Adjust search field for date formatting
        if($catalogue=='salary_month'){
          $catalogue="DATE_FORMAT(salary_month, '%Y-%m')";
        }
        // Adjust search field for name concatenation
        if($catalogue==='Name'){
          $catalogue="Concat(teachers.First_Name,' ',teachers.Last_Name)";
        }
          // Query with specific category filter
          $stmt=$conn->prepare("
          SELECT 
            salaries.*,
            salaries.teacher_id,
            DATE_FORMAT(salary_month, '%Y-%m') AS salary_month,
            Concat(teachers.First_Name,' ',teachers.Last_Name) AS `Name` 
          from salaries 
          Left JOIN teachers  
          ON teachers.teacher_id=salaries.teacher_id 
          where $catalogue like :search
          ORDER by teacher_id ASC
          ");
    $stmt->execute([":search"=>$search]);
    $rows=$stmt->fetchAll(PDO::FETCH_ASSOC);
     }
     // ================= SEARCH ACROSS ALL FIELDS =================
     elseif(!empty($_GET['search']) && $_GET['catalogue'] =="none"){
          // Query searching all relevant fields
          $stmt=$conn->prepare("
          SELECT 
            salaries.*,
            salaries.teacher_id,
            DATE_FORMAT(salary_month, '%Y-%m') AS salary_month,
            Concat(teachers.First_Name,' ',teachers.Last_Name) AS `Name` 
          from salaries 
          Left JOIN teachers  
          ON teachers.teacher_id=salaries.teacher_id 
          where salaries.expected_amount like :search OR
                penalty_amount like :search OR
                actual_amount like :search OR
                DATE_FORMAT(salary_month, '%Y-%m') like :search OR
                Concat(teachers.First_Name,' ',teachers.Last_Name) like :search OR
                salaries.if_paid like :search
          ORDER by teacher_id ASC
          ");
      $stmt->execute([":search"=>$search]);
      $rows=$stmt->fetchAll(PDO::FETCH_ASSOC);
     }
     // ================= DEFAULT VIEW (NO SEARCH) =================
     else{
      // Get all salaries data with teacher information
      $stmt=$conn->prepare("
        SELECT 
          salaries.*,
          salaries.teacher_id,
          DATE_FORMAT(salary_month, '%Y-%m') AS salary_month,
          Concat(teachers.First_Name,' ',teachers.Last_Name) AS `Name` 
        from salaries 
        Left JOIN teachers  
        ON teachers.teacher_id=salaries.teacher_id 
        ORDER by teacher_id ASC
        ");
      $stmt->execute();
      $rows=$stmt->fetchAll(PDO::FETCH_ASSOC);
     }

// ================= TEACHER VIEW CONFIGURATION =================
}elseif($_SESSION['role']==='teacher'){
    $title='My Salaries';
    $table=array(
        "expected_amount"=>"Expected Amount($)",
        "penalty amount"=>"Penalty Amount($)",
        "actual_amount"=>"Actual Amount($)",
        "salary_month"=>"Month of Salary",
        "paid"=>"Status",
        "Action"=>"Action"
     );
     $fields=["expected_amount","penalty_amount","actual_amount","salary_month","if_paid"];
     
     // Get the teacher's own salary information
     $stmt=$conn->prepare("SELECT salaries.*,DATE_FORMAT(salary_month, '%Y-%m') AS salary_month from salaries Left JOIN teachers  ON teachers.teacher_id=salaries.teacher_id where teachers.teacher_id=:teacher_id");
     $stmt->execute([':teacher_id'=>$_SESSION['roleID']]);
     $rows=$stmt->fetchAll(PDO::FETCH_ASSOC);
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
    ?>
    <!-- ================= JAVASCRIPT FUNCTIONALITY ================= -->
    <script>
// Toggle expandable rows functionality
document.querySelectorAll('.toggle-more').forEach(button => {
    button.addEventListener('click', () => {
        const targetClass = button.dataset.target;
        const rows = document.querySelectorAll(`.rows.${targetClass}`);
        const expanded = button.classList.contains('expanded');

        rows.forEach(row => {
            row.style.display = expanded ? 'none' : 'table-row';
        });

        button.classList.toggle('expanded');
        button.classList.toggle('icon-rotate');
    });
});


    </script>
</body>
</html>

<!-- ================= TEACHER SALARY CHART ================= -->
<!-- Chart only shown when teacher logs in -->
<?php if($_SESSION['role']=='teacher'){ ?>
<script>
// Chart.js implementation for salary trends
// Source: https://www.chartjs.org/docs/latest/samples/line/line.html

// Function to render salary chart with teacher's data
async function renderSalaryChart(teacherId) {
  // Fetch salary data from API
  const res = await fetch(`../api/chartDrawing.php?teacher_id=${teacherId}`);
  const result = await res.json();
  if (result.code !== 0) {
    alert(result.msg);
    return;
  }

  // Extract data for chart
  const labels = result.data.map(row => row.month);
  const expected = result.data.map(row => parseFloat(row.expected_amount));
  const penalty = result.data.map(row => parseFloat(row.penalty_amount));
  const actual = result.data.map(row => parseFloat(row.actual_amount));

  // Create chart with the data
  const ctx = document.getElementById('salaryChart').getContext('2d');
  new Chart(ctx, {
    type: 'line',
    data: {
      labels: labels,
      datasets: [
        {
          label: 'Actual salaries',
          data: actual,
          borderColor: '#3AA6D0',
          backgroundColor: 'rgba(58, 166, 208, 0.2)',
          pointBackgroundColor: '#0D6E9C', 
          tension: 0.3,
          fill: false,
        }
      ]
    },
    options: {
      responsive: true,
      plugins: {
        title: {
          display: true,
          text: 'Salary Trend for This Year'
        }
      },
      scales: {
        y: {
          beginAtZero: false,
          title: {
            display: true,
            text: 'Salary Amount (£)'
          }
        },
        x: {
          title: {
            display: true,
            text: 'Month'
          }
        }
      }
    }
  });
}

// Initialize chart with current teacher's ID
renderSalaryChart(<?php echo $_SESSION['id'] ?>); 
</script>
<?php
}
?>