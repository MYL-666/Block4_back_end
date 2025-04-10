<?php
/**
 * Common Table Display Component
 * Used to display data tables and student card views for different user roles
 */

// Helper function: Check if value exists
function exist($str){
  return isset($str) ? $str : "";
}

// Include page header
require "./common/header.php"; 

// Breadcrumb Navigation Section
?>
<div class="titles">
    <a href="index.php">Home</a> /
    <a href="#">Systmes</a> /
    <span> <?php echo $title; ?></span>
</div>

<?php
// ================= SALARY CHART =================
// Salary Chart Section (Visible only to teachers)
if($table_name=='salaries' && $_SESSION['role'] ==='teacher'){
?>
<canvas id="salaryChart" width="600" height="300"></canvas>
<?php
}



// ================= STUDENT CARD VIEW =================
// Student Card View Section
if(($_SESSION['role']==='teacher' && $table_name==='students') || 
   ($_SESSION['role']==='parent' && $table_name==='students') ||
   ($_SESSION['role']==='student' && $table_name==='parents')
){
?>
<div class="students-container">
    <?php
    foreach($rows as $row){
        // Add new kid button for parents with less than 2 kids
        if(isset($row['kid number']) && $row['kid_number'] <2){
        ?>
        <button id="insert" class="insert-new-kid">
            <i class="iconfont icon-add"></i>  
            Bind New Kid
        </button>
        <?php
        }
        ?>
        <!-- Student Card Container -->
        <div class="student-card" data-id="<?php echo $row[$tableID] ?>" data-tableName="<?php echo $table_name ?>" data-tableID="<?php echo $tableID ?>">
            <!-- Student Card Top Section -->
            <div class="top">
                <div class="left">
                    <img src="<?= $_SESSION['role']==='student' ? "../public/img/parent.jpeg" : '../public/img/student.jpg' ?>" alt="profile photo">
                </div>

                <div class="right">
                    <?php 
                    // Display student basic information
                    foreach($table as $k=>$v){
                    ?>
                    <div class="items">
                        <div class="label"><?php echo $v ?></div>
                        <span><?php
                        if(!empty($row[$k])){
                            // Gender icon display
                            if($row[$k]==='Male'){
                                echo "<i class='iconfont icon-male'></i>";
                            }elseif($row[$k]==='Female'){
                                echo "<i class='iconfont icon-female'></i>";
                            }else{
                                echo $row[$k];
                            }
                        }else{
                            echo "None";
                        }
                        ?></span>
                    </div>
                    <?php
                    }
                    ?>
                </div>
            </div>

            <!-- Parent/Teacher Contact Information Section -->
            <?php if(($_SESSION['role']==='teacher' && $table_name==='students') || 
                     ($_SESSION['role']==='parent' && $table_name==='students') ||
                     ($_SESSION['role']==='student' && $table_name==='parents')
            ){ ?>
            <div class="bottom">
                <?php 
                $card_title=$role_card=$guardiant="";
                if($_SESSION['role']==='teacher'){ 
                    $card_title="Parent Contact Information";
                    $role_card="parent-card";
                    $guardiant='parents';
                }elseif($_SESSION['role']==='parent'){
                    $card_title="Teacher Contact Information";
                    $role_card="teacher-card";
                    $guardiant='teachers';
                }
                ?>
                <p><?= $card_title ?></p>
                <div class="parent-shell">
                    <?php
                    // Display parent/teacher information
                    if (!empty($row['parents'])) {
                        foreach($row['parents'] as $parent) {
                            ?>
                            <div class="<?= $role_card ?>">
                                <?php
                                foreach($fields2 as $k=>$v){
                                    ?>
                                    <div class="item">
                                        <div class="parent-label"><?php echo $v ?></div>
                                        <span><?= !empty($parent[$k]) ? $parent[$k] : 'None' ?></span>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                            <?php
                        }
                    } elseif(isset($row['teacher_name'])){
                        // Display teacher information
                        foreach($fields2 as $k=>$v){
                            ?>
                            <div class="<?= $role_card ?>">
                                <div class="item">
                                    <div class="parent-label"><?php echo $v ?></div>
                                    <span><?= !empty($row[$k]) ? $row[$k] : 'None' ?></span>
                                </div>
                            </div>
                            <?php
                        }
                    }elseif($_SESSION['role']==='student' && $table_name==='parents'){
                        foreach($fields2 as $k=>$v){
                            ?>
                            <div class="teacher-card">
                                <div class="item">
                                    <div class="parent-label"><?php echo $v ?></div>
                                    <span><?= !empty($row[$k]) ? $row[$k] : 'None' ?></span>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        echo "<div class='parent-card none'>This student doesn\'t bind any $guardiant</div>";
                    }
                    ?>
                </div>
            </div>
            <?php } ?>
        </div>
    <?php
    } 
    ?>
</div>
<?php
// when the title is 'Me' show the user information

}elseif($title==='Me'){
?>
    <div class="profile-shell">
      <!-- left side of profile page section start -->
      <div class="leftside">
        <div class="top">
          <?php
          if($_SESSION['role']==='teacher'){
            echo '<img src="../public/img/teacher.jpeg" alt="teacher profiole photo">';
          }elseif($_SESSION['role']==='admin'){
            echo '<img src="../public/img/admin.jpg" alt="admin profiole photo">';
          }elseif($_SESSION['role']==='student'){
            echo '<img src="../public/img/student.jpg" alt="student profiole photo">';
          }elseif($_SESSION['role']==='parent'){
            echo '<img src="../public/img/parent.jpeg" alt="parent profiole photo">';
          }
          echo "
            <div class='user-box'>
              <div class='username'>". $_SESSION['username']."</div>
              <div class='user-role'>".$_SESSION['role']."</div> 
            </div>
            ";
          ?>
        </div>
        <div class="infor-box">
          <button class="btnEdit profile-e">
            <i class="iconfont icon-genggai"></i>
            <span>Edit</span>
          </button>

          <div class="personal-title">
            <span>Personal Information</span>
          </div>

            <div class="personal-box" data-id="<?=  !empty($rows[$tableID]) ? htmlspecialchars($rows[$tableID]) : 'N/A' ?>" data-tableName="<?php echo $table_name  ?>" data-tableID="<?php echo $tableID  ?>">
                <?php
                foreach($table as $k=>$v){
                ?>
                <div class="personal-container">
                  <div class="personal-label"><?= !empty($v) ? htmlspecialchars($v) : "N/A" ?></div>
                  <div class="personal-detail"><?= !empty($rows[$k]) ? htmlspecialchars($rows[$k]) : "N/A" ?></div>
                </div>
                <?php
                }
                ?>
            </div>

        </div>
      </div>
      <!-- left side of profile page section end -->

      <!-- right side of profile page section start -->
      <div class="rightside">
        <p>Complete your profiles</p>
        <div class="doughnut">
          <canvas id="progressChart"></canvas>
          <?php
            $precentage=0;
              foreach($fields as $k=>$v){
                if(!empty($rows[$k])){
                  $precentage += $v['weight'];
                }
              }
          ?>
          <div id="progressText"><?= $precentage."%" ?></div>
        </div>

        <div class="checklist">
          <?php foreach($fields as $k=>$v){ ?>
          <div class="checklist-item">
            <?php
              if(empty($rows[$k])){
                echo "<i class='iconfont icon-cuocha_kuai'></i>";
              }else{
                echo "<i class='iconfont icon-duigou'></i>";
              }
            ?>
            <div class="reason"><?= $v['title'] ?></div>
            <div class="percentage"><?= $v['weight']."%" ?></div>
          </div>
          <?php 
          }
          ?>
        </div>
      </div>
      <!-- right side of profile page section end -->
    </div>
  <?php
  }elseif(($_SESSION['role']==='student' && $table_name==='teachers') ){ ?>
  
    <?php
    $role=$path=$className='';
    foreach($rows as $row){
        if($table_name==='teachers'){
          $role="Teacher";
          $path="teacher.jpeg";
          $className='big';
        }elseif($table_name==='parents'){
          $role="Parents";
          $path='parent.jpeg';
          $className='small';
        }
    ?>
    <div class="profile-shell <?= $className ?>">
      <div class="leftside">
        <div class="top">
          <img src="../public/img/<?= $path ?>" alt="profile photo of teacher">

                    <div class="user-box big">
                      <?php
                        foreach($fields2 as $v){
                      ?>
                      <div class="username2"><?= isset($row[$v]) ? $row[$v] : "N/A" ?></div>
                      <?php } ?>

          </div>
        </div>

        <div class="infor-box">
          <div class="personal-title">
            <span>My <?= $role ?> Information</span>
          </div>

          <div class="personal-box"  data-tableName="<?php echo $table_name  ?>" data-tableID="<?php echo $tableID  ?>">
             <?php
             foreach($table as $k=>$v){
             ?>
             <div class="personal-container">
               <div class="personal-label"><?= $v ?></div>
               <div class="personal-detail personal2"><?= isset($row[$k]) ? $row[$k] : 'N/A' ?></div>
             </div>
             <?php
             }
             ?>
          </div>
        </div>
      </div>
    </div>

  <?php
    }
    //=============================== library layout section start ==========================
  }elseif(($table_name==='library' && $_SESSION['role']==='student')){ ?>
    <div class="library-container">
      <div class="library-top">
        <div class="book-shell-title top-title">This Month Newly</div>
        <span style="text-align: center; width:500px;">Lorem ipsum dolor sit amet consectetur adipisicing elit. Veritatis, inventore.</span>
        <button class="read-more">Read More</button>
        <div class="books-container top-container">
          <?php 
          $defaultCover = '../public/img/no-cover.png';
          if(!empty($newBooks)){
            foreach($newBooks as $book){
          ?>
              <img class="top-img" src="<?= !empty($book['cover']) || trim($book['cover'])!=='' ? $book['cover'] : $defaultCover ?>" alt="Book cover">
          <?php 
            }
        } 
        ?>
        </div>
      </div>
      <!-- My borrowed book section start -->
      <div class="best-sell">
        
          <?php 
          $defaultCover = '../public/img/no-cover.png';
          if(!empty($myBook)){
          ?>
          <div class="book-shell-title ">My Borrowed Books Records</div>
          <div class="books-container">
            <?php
            foreach($myBook as $book){
          ?>
          <div class="book-card big">
              <img src="<?= isset($book['cover']) ? $book['cover'] : $defaultCover ?>" alt="Book cover">
              <div class='book-info'>
                  <div class="date-range">
                    <div class="box">
                      <span class="label">Borrowed:</span>
                      <span class="date"><?= $book['borrowDate']  ?></span>
                    </div>
                    <div class="box">
                      <span class="label">Due:</span>
                      <span class="date due"><?= $book['DueDate'] ?></span>
                    </div>
                  </div>
                  <span class="book-title"><?= $book['title'] ?></span>
                  <p class="author"><?= $book['Author'] ?></p>
                  <p class="author"><?= $book['publishDate'] ?></p>
                  <button class="return-book" data-book-id="<?= $book['Book_id'] ?>">Return Book</button>
              </div>
          </div>
          <?php 
            }
        } 
        ?>
        </div>
      </div>
      <!-- My borrowed book section end -->

      <div class="cutline"></div>

      <div class="best-sell">
        <div class=" book-shell-title book-shell-title2">Most Popular Books</div>
        <div class="books-container">
          <?php 
            foreach($mostBorrowed as $book){
          ?>
          <div class="book-card <?= $book['status'] === 'Borrowed' ? 'borrowed' : '' ?>">
              <img src="<?= isset($book['cover']) ? $book['cover'] : $defaultCover ?>" alt="Book cover">
              <div class='book-info'>
                  <p class="borrow-time">Borrowed Times: <?= $book['borrow_count'] ?></p>
                  <span class="book-title"><?= $book['title'] ?></span>
                  <p class="author"><?= $book['Author'] ?></p>
                  <p class="author"><?= $book['publishDate'] ?></p>
                  <button class="borrow-book" data-book-id="<?= $book['Book_id'] ?>">Borrow Now</button>
              </div>
          </div>
          <?php } ?>
        </div>
      </div>

      <div class="cutline cutline2"></div>

      <div class="best-sell">
        <div class="book-shell-title">All Books in Library</div>
        <div class="books-container">
          <?php 
            foreach($rows as $book){
          ?>
          <div class="book-card <?= $book['status'] === 'Borrowed' ? 'borrowed' : '' ?>">
              <img src="<?= isset($book['cover']) ? $book['cover'] : $defaultCover ?>" alt="Book cover">
              <div class='book-info'>
                  
                  <span class="book-title"><?= $book['title'] ?></span>
                  <p class="author"><?= $book['Author'] ?></p>
                  <p class="author"><?= $book['publishDate'] ?></p>
                  <button class="borrow-book" data-book-id="<?= $book['Book_id'] ?>">Borrow Now</button>

              </div>
          </div>
          <?php } ?>
        </div>
      </div>
      
    </div>
  <?php
  }
   // For other tables and users
  else{
    ?>

  <!-- Admin users table view -->            
    <div class="table">
      <div class="head">
        <div class="table-title">
            <?php echo $title ." Table"; ?>
        </div>
        <!-- Add new record button (visible only to admin) -->
        <div class="add-new <?php if($_SESSION['role']!='admin' || $table_name==='borrowed_book') echo 'no' ?>">
          <button id="insert">
            <i class="iconfont icon-add "></i>  
            Add <?php echo $title; ?>
          </button>
        </div>
      </div>
      
        <!-- search bar section start-->
        <?php
        if($_SESSION['role']==='admin'){
        ?>
        <form action="<?= $table_name!=='borrowed_book' ? $table_name.'.php' : 'library.php' ?>" method="GET" class="search-form">
          <div class="search-box ">
            <input type="text" name="search" placeholder="Search" class="search-input" value="<?= htmlspecialchars($_GET['search'] ?? "") ?>">
            <button type="submit" class="search-btn" id="searchBtn"><i class="iconfont icon-search"></i></button>
          </div>
          <div class="catalogue">
            <select name="catalogue" id="catalogue">
            <option value="none" selected>All <?= $title ?></option>
              <!-- for students  -->
              <?php

                  foreach($table as $k=>$v){
                    $k=='Kids1' ? $v='kid\'s name' : "";
                    if($k!="Action" && $k!="kids2" && $k!="cover"){
                      $selected = ($catalogue === $k) ? 'selected' : '';
                      echo "<option value='$k' $selected>$v</option>";
                    }
                  }

              ?>
            </select>
          </div>
          <button class="reset" type="button">Reset</button>
        </form>
        
        <?php
        if($table_name==='library' || $table_name==='borrowed_book'){ ?>
          <nav class="nav-bar">
            <ul>
              <li><a class="alink" href="library.php">All Books</a></li>
              <li><a class="alink" href="library.php?nav=borrowed">Borrowed Records</a></li>
            </ul>
          </nav>
        <?php 
        }
      } ?>
        <!-- searching bar section end -->


      <table class="<?= ($table_name==='salaries' && $_SESSION['role']==='admin') ? "salary" : ""  ?>">            
          <thead>
              <tr>
              <?php 
              if($table_name==='library'){
                $tableShow=$table2;
              }else{
                $tableShow=$table;
              }
                  // Display table headers
                  foreach($tableShow as $k=>$v){
              ?>
                  <th>
                      <?php echo exist($v);  ?>
                  </th>
              <?php 
              }
            
              ?>
              </tr>
          </thead>

          <tbody>
            <?php 
            if(!empty($rows)){
            $currentTeacher='';
            $index=0;
            // Display table data
            foreach ($rows as $row){ 
              if($table_name==='salaries' && $_SESSION['role']==='admin'){
                  if ( isset($row['teacher_id']) && $currentTeacher !== $row['teacher_id']) {
                    $currentTeacher = $row['teacher_id'];
                    $index++;
                    $teacherFirstRow = true; 
                } else {
                    $teacherFirstRow = false; 
                }
        
                $rowClass = "row{$index}";
                $extraClass = $teacherFirstRow  ? '' : "hidden-row $rowClass"; 
              }
              ?>  
                  
              <tr class="rows <?= ($table_name==='salaries' && $_SESSION['role']==='admin') ? $extraClass : "" ?> <?= $table_name==='salaries' && !$teacherFirstRow && $_SESSION['role']==='admin' ? "son" : "" ?>" data-id="<?php echo $row[$tableID] ?>" data-tableName="<?php echo $table_name  ?>" data-tableID="<?php echo $tableID  ?>">
                <?php foreach ($fields as $field){ 
                  ?>
                  <td><?php 
                  // Special field display handling
                  if ($field === 'backgroundCheck'|| $field==='if_paid') {
                        // Display checkmark status
                        echo $row[$field] ? '<i class="iconfont icon-dui"></i>' : '<i class="iconfont icon-cuowutishitianchong"></i>';
                      }elseif($field==='capacity'){
                          // Display class capacity
                          $stmt=$conn->prepare("
                          SELECT 
                            count(student_id) as `number`
                            from students 
                            where class_id=:class_id
                          ");
                          $stmt->execute([":class_id"=>$row[$tableID]]);
                          $number=$stmt->fetch(PDO::FETCH_ASSOC);
                          echo $number['number'].'/'.$row[$field];
                      }elseif($field=='cover'){
                        $defaultCover="../public/img/no-cover.png";
                      ?>
                        <img src="<?= isset($row[$field]) ? $row[$field] : $defaultCover ?>" alt="Book cover">
                        <?php
                      }elseif($field=='book_infor' || $field=='borrow_infor'){
                        echo $row[$field];
                      }
                      elseif(!empty($row[$field])) {
                        echo htmlspecialchars($row[$field]);
                      }
                      else{
                        echo "N/A";
                      } 
                    
                      ?></td>
                <?php 
              }; 
                // Action buttons (edit/delete)
                if($_SESSION["role"]==="admin"){                            
                    $ID=$row[$tableID];     
                ?>
                   <td>
                      <button class='btnEdit' data-id="<?= $ID ?>">
                          <i class='iconfont icon-editor'></i>
                      </button>
                      <button class='btnDelete'>
                          <i class='iconfont icon-delete'></i>
                      </button>
                    
                    <?php
                    if($table_name==='salaries'&& $teacherFirstRow){
                      echo "
                        <button class='toggle-more' data-target='$rowClass'><i class='iconfont icon-zhankai'></i></button>
                        ";
                    }
                    ?>
                    </td>
                  <?php
                }else{
                  // Display disabled buttons for non-admin users
                  echo '<td>
                  <button class="btnEdit no">
                          <i class="iconfont icon-editor"></i>
                      </button>
                  <button class="fake no"><i class="iconfont icon-delete"></i></button></td>';
                }
                ?>
              </tr>
            <?php }
            }
            ?>
          </tbody>
      </table>
  <?php
  }
  // ================= CLASS-RELATED CHARTS =================
// Class-related Charts Section (Visible only to teachers)
if($table_name=='classes' && $_SESSION['role']==='teacher'){ 
  ?>
  <div class="chart-container" style="margin-top: 20px;">
      <!-- Gender Distribution Chart -->
      <div class="chart-card">
          <canvas id="genderChart"></canvas>
      </div>
      <!-- Class Capacity Chart -->
      <div class="chart-card">
          <canvas id="capacityChart"></canvas>
      </div>
      <!-- Birthday Distribution Chart -->
      <div class="chart-card">
          <canvas id="birthdayChart"></canvas>
      </div>
  </div>
  <?php
  }
  ?>


<?php
// ================= RESET BUTTON =================
// Reset the search input and dropdown    
if($_SESSION['role']==='admin'){
?>
<script>
  // ================= RESET BUTTON =================
  document.querySelector(".reset").addEventListener("click", function(){
    // clear the search input
    document.querySelector("input[name='search']").value = "";
    //reset the dropdown
    document.querySelector("select[name='catalogue']").value = "none";
    // submit the form to refresh the page
    document.querySelector(".search-form").submit();
  });
</script>
<?php
}
?>
