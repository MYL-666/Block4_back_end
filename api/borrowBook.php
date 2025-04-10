<?php
// ================= INITIALIZATION =================
// Require database configuration and start session
require '../config/db.php'; 
session_start();

// ================= REQUEST VALIDATION =================
// Check if the request method is POST and necessary parameters are set
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_id']) && isset($_SESSION['roleID'])) {
    // Sanitize input parameters
    $bookId = intval($_POST['book_id']);
    $studentId = intval($_SESSION['roleID']);

    // ================= BOOK AVAILABILITY CHECK =================
    // Check if the book exists and its current status
    $checkStmt = $conn->prepare("SELECT `status` FROM library WHERE Book_id = :bookId");
    $checkStmt->execute([':bookId' => $bookId]);
    $book = $checkStmt->fetch(PDO::FETCH_ASSOC);

    // Handle case where book is not found
    if (!$book) {
        echo json_encode(['data' => 1, 'msg' => 'Book not found.']);
        exit;
    }

    // Handle case where book is already borrowed
    if ($book['status'] === 'Borrowed') {
        echo json_encode(['data' => 1, 'msg' => 'This book is already borrowed.']);
        exit;
    }

    // ================= BOOK BORROWING LOGIC =================
    // Book is available for borrowing
    $borrowDate = date('Y-m-d'); // Set borrow date to today
    $dueDate = date('Y-m-d', strtotime('+14 days')); // Set due date 14 days from today

    // 1. Update book status in the 'library' table to 'Borrowed'
    $stmt1 = $conn->prepare("UPDATE library SET `status` = 'Borrowed' WHERE Book_id = :book");
    $result1 = $stmt1->execute([":book" => $bookId]);

    // 2. Insert or update the borrowing record in the 'borrowed_book' table
    // Using ON DUPLICATE KEY UPDATE to handle cases where a student might re-borrow a book (or handle potential unique key conflicts)
    $stmt2 = $conn->prepare("INSERT INTO borrowed_book (student_id, Book_id, borrowDate, DueDate) 
                             VALUES (:student, :book, :borrow, :due)
                             ON DUPLICATE KEY UPDATE 
                                borrowDate = VALUES(borrowDate), 
                                DueDate = VALUES(DueDate)");
    $result2 = $stmt2->execute([
        ":borrow" => $borrowDate,
        ":due"    => $dueDate,
        ":book"   => $bookId,
        ":student"=> $studentId
    ]);

    // ================= RESPONSE HANDLING =================
    // Check if both database operations were successful
    if ($result1 && $result2) {
        // Send success response
        echo json_encode(['data' => 0]); // 0 typically indicates success
        exit;
    } else {
        // Send error response if database update failed
        echo json_encode(['data' => 1, 'msg' => 'Failed to update database.']); // 1 typically indicates an error
        exit;
    }

} else {
    // ================= INVALID REQUEST HANDLING =================
    // Send error response if the request is invalid (not POST or missing parameters)
    echo json_encode(['data' => 1, 'msg' => 'Invalid request.']);
    exit;
}
?>
