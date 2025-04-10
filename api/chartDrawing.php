<?php
// ================= INITIALIZATION =================
// Start session and require database configuration
session_start();
require "../config/db.php";
// Set header to indicate JSON response
header('Content-Type: application/json');

// ================= ROLE CHECK =================
// This endpoint is specifically for teachers
if($_SESSION['role'] === 'teacher'){
    // Get teacher ID from session
    $teacher_id = $_SESSION['roleID'] ?? '';

    // ================= TEACHER ID VALIDATION =================
    // Check if teacher ID is available
    if (!$teacher_id) {
        echo json_encode(["code" => 1, "msg" => "No teacher ID provided"]);
        exit;
    }

    // ================= FETCH SALARY DATA =================
    // Prepare and execute query to get teacher's salary data over time
    $stmt = $conn->prepare("
        SELECT 
            DATE_FORMAT(salary_month, '%Y-%m') AS month,
            expected_amount,
            penalty_amount,
            actual_amount
        FROM salaries
        WHERE teacher_id = ?
        ORDER BY salary_month ASC
    ");
    $stmt->execute([$teacher_id]);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC); // Salary data for line chart

    // ================= FETCH STUDENT STATISTICS =================
    // Prepare and execute query to get student gender stats and class capacity
    $stmt=$conn->prepare("
        SELECT
            SUM(u.Gender = 'Male') AS male,       -- Count male students
            SUM(u.Gender = 'Female') AS female,   -- Count female students
            c.capacity,                         -- Get class capacity
            count(s.student_id) as occupid      -- Count currently occupied slots
            from user as u
            JOIN students as s on s.student_id=u.student_id
            JOIN classes as c on c.class_id=s.class_id
            JOIN teachers as t on t.teacher_id=c.teacher_id
            where t.teacher_id=:teacher_id       -- Filter by teacher ID
        ");
    $stmt->execute([':teacher_id'=>$_SESSION['roleID']]);
    $student_stats = $stmt->fetch(PDO::FETCH_ASSOC); // Gender stats for pie chart

    // Calculate remaining capacity
    $occupid=(int)$student_stats['occupid'];
    $total=(int)$student_stats['capacity'];
    $remain=$total-$occupid; // Capacity data for doughnut chart

    // ================= FETCH BIRTHDAY DATA =================
    // Prepare and execute query to get student birthday distribution by year
    $stmt=$conn->prepare("
    SELECT 
          YEAR(u.birthday) AS year, -- Group by birth year
          COUNT(*) AS count          -- Count students per year
        FROM user as u
        JOIN students as s on u.student_id=s.student_id
        JOIN classes as c on c.class_id=s.class_id
        JOIN teachers as t on t.teacher_id=c.teacher_id
        WHERE u.student_id IS NOT NULL AND t.teacher_id=:teacher_id -- Filter by teacher ID
        GROUP BY year
        ORDER BY year ASC
        ");
    $stmt->execute([":teacher_id"=>$_SESSION['roleID']]);
    $birthdayData=$stmt->fetchAll(PDO::FETCH_ASSOC); // Birthday data for bar chart

    // ================= JSON RESPONSE =================
    // Combine all fetched data into a single JSON response
    echo json_encode([
        "code" => 0, // Success code
        "data" => $data, // Salary data
        "studentStats"=>$student_stats, // Gender stats
        "capacityStats"=>[ // Capacity stats
            "total"=>$total,
            "remain"=>$remain,
            "occupid"=>$occupid
            ],
        "birthdayData"=>$birthdayData // Birthday distribution data
    ]);
    exit;
} else {
    // ================= UNAUTHORIZED ACCESS HANDLING =================
    // Handle cases where the user is not a teacher
    echo json_encode(["code"=>1, "msg"=>"Unauthorized access"]);
    exit;
}
?>