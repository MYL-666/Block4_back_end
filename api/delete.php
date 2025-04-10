<?php
// ================= INITIALIZATION =================
// Require database configuration
require "../config/db.php";
// Set header to indicate JSON response
header('Content-Type: application/json');

// ================= ALLOWED TABLES =================
// Define an array of table names that are allowed to be deleted from
$tableNameArray=['classes',"students","teachers",'parents','library','salaries','chat_board','borrowed_book'];

// ================= INPUT PROCESSING =================
// Get JSON data sent from the client (e.g., fetch request)
$data = json_decode(file_get_contents('php://input'), true);
// Extract ID, table ID column name, and table name from the received data
$id = $data['id'] ?? ''; // The ID of the record to delete
$tableID=$data['tableID'] ?? ""; // The name of the primary key column (e.g., 'student_id')
$tableName=$data["tableName"] ?? ""; // The name of the table to delete from

// ================= INPUT VALIDATION =================
// Check if the record ID is provided
if(empty($id)){
    echo json_encode(["code"=>1,"msg"=>'No id found!']);
    exit; // Stop script execution
}

// Check if the provided table name is in the allowed list
if(!in_array($tableName,$tableNameArray)){
    echo json_encode(["code"=>1,"msg"=>"Invalid table name specified!"]); // More specific error message
    exit; // Stop script execution
} else {
    // ================= DELETE OPERATION =================
    // Prepare the DELETE SQL statement dynamically using validated table and column names
    // Using placeholders (:table_id) prevents SQL injection vulnerabilities
    $stmt=$conn->prepare("DELETE FROM $tableName where $tableID=:table_id ");
    
    // Execute the prepared statement with the provided ID
    if($stmt->execute([":table_id"=>$id])){
        // If execution is successful, send a success response
        echo json_encode(["code"=>0,"msg"=>'Delete success!']); // 0 indicates success
        exit; // Stop script execution
    } else {
        // If execution fails, send an error response
        echo json_encode(["code"=>1,"msg"=>'Delete fail!']); // 1 indicates failure
        exit; // Stop script execution
    }
}

// Final exit (should ideally not be reached due to exits within conditions)
exit;
?>