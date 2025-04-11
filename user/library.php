<?php 
// Start the session and initialize database connection
session_start();
require "../config/db.php";
require "../api/checkBind.php";
 $title= "Book";
 $page_name="table.form";
 $validationFile="bookV";
 // Define table columns for display
 $table=array(
    "cover"=>"Cover",
    "book_title"=>"Title",
    "Author"=>"Author",
    "publishDate"=>"PublishDate",
    "status"=>"Status",
    "student_id"=>"Student borrowed",
    "borrowDate"=>"borrowedDate",
    "DueDate"=>"DueDate",
    "Action"=>"Action"
 );
 // Alternative table structure with consolidated information
 $table2=[
    "cover"=>"Cover",
    "book_infor"=>"Book",
    "status"=>"Status",
    "Name"=>"Student borrowed",
    "borrow_infor"=>"borrowedDate->Due Date",
    "Action"=>"Action"
 ];
 $table_name="library";
 $tableID="Book_id";
 $fields=["cover","book_infor","status","Name","borrow_infor"];

 // ================= SEARCH FUNCTIONALITY =================
 // Get search parameters from URL
 $search = isset($_GET['search']) ? "%" . $_GET['search'] . "%" : "";
 $catalogue = $_GET['catalogue'] ?? 'none';
 $nav = $_GET['nav'] ?? 'none';

 // ================= BORROWED BOOKS SECTION =================
 if($nav=='borrowed'){
    $table_name="borrowed_book";
    $tableID="borrow_id";
    $fields=["borrowDate","cover","book_infor","Name","returnDate"];
    $table=[
        "borrowDate"=>"Borrow Date",
        "cover"=>"Cover",
        "book_infor"=>"Book",
        "Name"=>"Student borrowed",
        "returnDate"=>"Return Date",
        "Action"=>"Action"
    ];
    $stmt=$conn->prepare("
    SELECT
        b.borrowDate,
        l.cover,
        b.borrow_id,
        CONCAT_ws('<br>', l.title, l.Author, l.publishDate) AS book_infor,
        CONCAT(s.First_Name,' ',s.Last_Name) AS Name,
        b.DueDate,
        b.returnDate
    FROM borrowed_book as b
    JOIN library as l on b.Book_id=l.Book_id
    JOIN students as s on b.Student_id=s.student_id
    ORDER BY b.borrowDate DESC;
    ");
    $stmt->execute();
    $rows=$stmt->fetchAll(PDO::FETCH_ASSOC);
 }
 // ================= SEARCH FUNCTIONALITY =================
 else{
    // Processing search queries with specified category
 if(!empty($_GET['search']) && $_GET['catalogue']!=="none"){
    if($catalogue=='student_id'){
        $catalogue="CONCAT(s.First_Name,' ',s.Last_Name)";
    }
    if($catalogue=='book_title'){
        $catalogue='l.title';
    }
    $stmt=$conn->prepare("
        SELECT
            l.title as book_title,
            l.Book_id,
            l.Author,
            l.cover,
            l.publishDate,
            l.status,
            CONCAT(s.First_Name,' ',s.Last_Name) AS Name,
            CONCAT_ws('<br>',b.borrowDate,b.DueDate) AS borrow_infor,
            CONCAT_WS('<br>', l.title, l.Author, l.publishDate) AS book_infor,
            s.student_id,
            b.borrowDate,
            b.DueDate
            FROM library as l
        LEFT JOIN (SELECT *
        FROM borrowed_book
        WHERE borrowDate IS NOT NULL
        ORDER BY borrowDate DESC) as b on b.Book_id=l.Book_id
        LEFT JOIN students as s on b.student_id=s.student_id
        WHERE $catalogue like :search
        GROUP BY l.Book_id;
    ");
    $stmt->execute([':search'=>$search]);
    $rows=$stmt->fetchAll(PDO::FETCH_ASSOC);
 }
 // Processing search queries without specified category (search all fields)
 elseif(!empty($_GET['search']) && $_GET['catalogue']=="none"){
    $stmt=$conn->prepare("
    SELECT
        l.title AS book_title,
        l.cover,
        l.Book_id,
        l.Author,
        l.publishDate,
        l.status,
        CONCAT(s.First_Name,' ',s.Last_Name) AS Name,
        CONCAT_WS('<br>', b.borrowDate, b.DueDate) AS borrow_infor,
        CONCAT_WS('<br>', l.title, l.Author, l.publishDate) AS book_infor,
        s.student_id,
        b.borrowDate,
        b.DueDate,
        b.borrow_id
    FROM library AS l
    LEFT JOIN (
        SELECT *
        FROM borrowed_book
        WHERE borrowDate IS NOT NULL
    ) AS b ON b.Book_id = l.Book_id
    LEFT JOIN students AS s ON b.student_id = s.student_id
    WHERE
        l.title LIKE :search OR
        l.Author LIKE :search OR
        l.publishDate LIKE :search OR
        l.status LIKE :search OR
        b.borrowDate LIKE :search OR
        b.DueDate LIKE :search OR
        CONCAT(s.First_Name, ' ', s.Last_Name) LIKE :search
    GROUP BY l.Book_id
    ORDER BY b.borrowDate DESC;

    ");
    $stmt->execute([':search'=>$search]);
    $rows=$stmt->fetchAll(PDO::FETCH_ASSOC);
 }
 // Default query - show all books
 else{
    $stmt=$conn->prepare("
    SELECT 
        l.Book_id,
        l.publishDate,
        l.title,
        l.Author,
        l.cover,
        l.status,
        b.DueDate,
        b.borrowDate,
        b.returnDate,
        CONCAT(s.First_Name,' ',s.Last_Name ) AS Name, 
        CONCAT_ws('<br>',b.borrowDate,b.DueDate) AS borrow_infor,
        CONCAT_WS('<br>', l.title, l.Author, l.publishDate) AS book_infor
    FROM library l
    LEFT JOIN (
        SELECT *
        FROM borrowed_book
        WHERE borrowDate IS NOT NULL
        ORDER BY borrowDate DESC
    ) b ON l.Book_id = b.Book_id
    LEFT JOIN students s ON b.Student_id = s.student_id
    GROUP BY l.Book_id;
");
$stmt->execute();
$rows=$stmt->fetchAll(PDO::FETCH_ASSOC);
}
}

 

// ================= NEWEST BOOKS SECTION =================
// Get the latest 3 books added to the library
$stmt=$conn->prepare("SELECT * from library ORDER BY book_id DESC LIMIT 3 ");
$stmt->execute();
$newBooks=$stmt->fetchAll(PDO::FETCH_ASSOC);

// ================= MOST BORROWED BOOKS SECTION =================
// Get the top 5 most borrowed books
$stmt=$conn->prepare("
    SELECT 
        l.Book_id,
        l.title,
        l.Author,
        l.cover,
        l.publishDate,
        l.status,
        COUNT(*) AS borrow_count
    FROM borrowed_book AS b
    JOIN library AS l ON b.Book_id = l.book_id
    GROUP BY b.Book_id
    ORDER BY borrow_count DESC
    LIMIT 5
    ");
$stmt->execute();
$mostBorrowed=$stmt->fetchAll(PDO::FETCH_ASSOC);

// ================= MY BORROWED BOOKS SECTION =================
// Get books currently borrowed by the logged-in student
$stmt=$conn->prepare("SELECT * from library as l JOIN borrowed_book as b on l.Book_id=b.Book_id where b.student_id=:student AND b.returnDate is NULL");
$stmt->execute([':student'=>$_SESSION['roleID']]);
$myBook=$stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<?php 
    // Include the header with CSS and meta information
    require "./common/head.php"
?>
<body>
    <main>
        <?php
            // Include the sidebar navigation
            require "./common/slidebar.php";
        ?>
        <div class="container">
            <?php
                // Include the main table component to display books
                require "./common/table.php";
  
            ?>
             <!-- form section for insert section start -->

    </main>
    <?php   
        // Include footer section
        require "./common/footer.php";
        // Import the JavaScript for insert functionality
        require "../api/fetch.insert.php";

        // Import the JavaScript for edit functionality
        require "../api/fetch.edit.php";

        // Import the JavaScript for delete functionality   
        require "../api/fetch.delete.php";
    ?>
    <!-- ================= JAVASCRIPT FUNCTIONALITY ================= -->
    <script>
        // Event listeners for "Borrow Book" buttons
        document.querySelectorAll('.borrow-book').forEach(btn => {
            btn.addEventListener('click', function() {
                const bookId = btn.dataset.bookId;
            
                // Send AJAX request to borrow the book
                fetch('../api/borrowBook.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `book_id=${bookId}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.data==0) {
                        // Show success message if book was borrowed successfully
                        Swal.fire({
                            icon: 'success',
                            title: 'Borrowed!',
                            text: 'You have successfully borrowed this book.',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            // Reload the page to reflect changes
                            location.reload();
                        });
                    } else {
                        // Show error message if borrowing failed
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: data.msg || 'Something went wrong.',
                        });
                    }
                });
            });
        });


        // Event listeners for "Return Book" buttons
        document.querySelectorAll('.return-book').forEach(btn=>{
            btn.addEventListener("click",function(){
                const bookId = btn.dataset.bookId;
                // Send AJAX request to return the book
                fetch('../api/returnBook.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `book_id=${bookId}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.data==0) {
                        // Show success message if book was returned successfully
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'You have successfully return the book.',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            // Reload the page to reflect changes
                            location.reload();
                        });
                    } else {
                        // Show error message if returning failed
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: data.msg || 'Something went wrong.',
                        });
                    }
                });
            })
        })

        // ================= ACTIVE LINK =================
        const currentUrl=window.location.href;
        const alinks=document.querySelectorAll('.alink');
        alinks.forEach(link => {
            // check if the current url is the same as the link
            if(link.href==currentUrl){
                link.classList.add('active');
            }
        });
    </script>
</body>
</html>