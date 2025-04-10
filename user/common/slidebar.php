<!-- 
 * slidebar.php
 * This file contains the sidebar navigation section of the website.
 * It includes quick action buttons, user profile, navigation menu,
 * and logout functionality.
 -->

<!-- Quick Action Buttons Section -->
<!-- Back to Top Button -->
<a href="#" class="up">
    <i class="iconfont icon-upward"></i>
</a>

<!-- Page Refresh Button -->
<a href="#" onclick="location.reload(); return false;" class="refresh">
    <i class="iconfont icon-shuaxin"></i>
</a>

<!-- Add New Chat Button (Only visible in chat board) -->
<?php
    if($table_name=='chat_board'){
        echo "
            <a href='#' class='add-new-chat' id='add-chat'>
               <i class='iconfont icon-add'></i>
            </a>
        ";
    }
?>

<!-- Main Sidebar Container -->
<div class="slidebar">
    <!-- School Name Section -->
    <div class="school">
        <span>St Alphonsus</span>
    </div>

    <!-- User Profile Picture Section -->
    <div class="pro-pic" style="background-image: url(<?php
        if($_SESSION['role']==='admin'){
            echo '../public/img/admin.jpg';
        }elseif($_SESSION['role']==='student'){
            echo '../public/img/student.jpg';
        }elseif($_SESSION['role']==='teacher'){
            echo '../public/img/teacher.jpeg';
        }elseif($_SESSION['role']==='parent'){
            echo '../public/img/parent.jpeg';
        }
    ?>);"></div>

    <!-- User Information Section -->
    <div class="username">
        <!-- Display username and role if logged in -->
        <?php   if($_SESSION['username']){
                    echo "<span>".$_SESSION['username']."</span>
                        <p>".$_SESSION['role']."</p>
                    ";
        }else{
            echo "N/A";
        } ?>
    </div>

    <!-- Navigation Menu Section -->
    <ul class="slidebar-item">
        <!-- Home Navigation -->
        <li data-nav="home">
            <a href="./index.php">
                <i class="iconfont icon-home"></i>
                <span>Home</span>
            </a>
        </li>

        <!-- Classes Navigation -->
        <li class="list" data-nav="classes">
            <a href="./classes.php">
                <i class="iconfont icon-banjiketang"></i>
                <span>Classes</span>
            </a>
        </li>

        <!-- Students Navigation -->
        <li class="list" data-nav="students">
            <a href="./students.php">
                <i class="iconfont icon-Student"></i>
                <span>Students</span>
            </a>
        </li>

        <!-- Parents Navigation -->
        <li class="list" data-nav="parents">
            <a href="./parents.php">
                <i class="iconfont icon-parents"></i>
                <span>Parents</span>
            </a>
        </li>

        <!-- Teachers Navigation -->
        <li class="list" data-nav='teachers'>
            <a href="./teachers.php">
                <i class="iconfont icon-teacher_basic"></i>
                <span>Teachers</span>
            </a>
        </li>

        <!-- Salaries Navigation -->
        <li class="list" data-nav="salaries">
            <a href="./salaries.php">
                <i class="iconfont icon-gongzi"></i>
                <span>Salaries</span>
            </a>
        </li>

        <!-- Library Navigation -->
        <li class="list" data-nav="library">
            <a href="./library.php">
                <i class="iconfont icon-library"></i>
                <span>Library</span>
            </a>
        </li>

        <!-- Chat Board Navigation -->
        <li data-nav="chat_board">
            <a href="./chat.php">
                <i class="iconfont icon-xiaoxi1"></i>
                <span>Chat Board</span>
            </a>
        </li>
    </ul>

    <!-- Logout Section -->
    <div class="logout" id="logout">
        <i class="iconfont icon-bx-log-out"></i>
        <span>Logout</span>
    </div>
</div>

<!-- Include User Information Fetch Script -->
<?php require '../api/fetchUserInfo.php'; ?>

<!-- Initialize Sidebar Functionality -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Fetch user information
        getUser();
        
        // Add logout event listener
        document.getElementById('logout').addEventListener('click', function () {
            window.location.href = "../api/logout.php";
        });
    });
</script>