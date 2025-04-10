<?php
// ================= DATABASE CONNECTION =================  
// Connect to the database using the config/db.php file
require '../config/db.php';
// Start the session to access session variables
session_start();

// ================= REQUEST VALIDATION =================
// Check if the request method is POST and if the book_id and roleID are set
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_id']) && isset($_SESSION['roleID'])) {
    $bookId = intval($_POST['book_id']);
    $studentId = intval($_SESSION['roleID']);
    $returnDate = date('Y-m-d');

    // ================= UPDATE BOOK STATUS =================
    // Update the book status to 'Available'
    $stmt1 = $conn->prepare("UPDATE library SET `status` = 'Available' WHERE Book_id = :book");
    $result1 = $stmt1->execute([':book' => $bookId]);

    // ================= UPDATE BORROWED BOOK =================
    // Update the borrowed book status to 'Returned'
    $stmt2 = $conn->prepare("UPDATE borrowed_book 
        SET returned = 1, returnDate = :returnDate 
        WHERE Book_id = :book AND student_id = :student 
        ORDER BY borrowDate DESC LIMIT 1");

    $result2 = $stmt2->execute([
        ':returnDate' => $returnDate,
        ':book' => $bookId,
        ':student' => $studentId
    ]);

    // ================= RESPONSE =================
    // If the book status and borrowed book status are updated successfully, send the success message to front-end
    if ($result1 && $result2) {
        echo json_encode(['data' => 0]);
    } else {
        echo json_encode(['data' => 1, 'msg' => 'Failed to return book.']);
        exit;
    }
} else {
    // ================= RESPONSE =================
    // If the request method is not POST or the book_id and roleID are not set, send the error message to front-end 
    echo json_encode(['data' => 1, 'msg' => 'Invalid request.']);
    exit;
}
