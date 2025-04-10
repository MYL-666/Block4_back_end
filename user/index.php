<?php 
// Start the session and set page information
session_start();
 $title= "Home Page";
 $page_name="index";
 $table_name='home';
 require "../config/db.php";
 require "../api/checkBind.php";
?>

<!DOCTYPE html>
<html lang="en">
<?php 
    // Include head section
    require "./common/head.php"
?>
<body>
    <main>
    <?php require "./common/slidebar.php" ?>
        <div class="container">
            <?php
            // Include header section
            require "./common/header.php";
            ?>

            
            <div class="part">
                <div class="part-left">
                    <!-- ================= DASHBOARD SECTION ================= -->
                    <div class="dash-shell">
                        <span class="d-title">Faculty</span>
                        <div class="dashboard">
                            <?php
                            // Define tables and their primary keys for counting records
                            $table=[
                                'students'=>"student_id",
                                'teachers'=>"teacher_id",
                                'parents'=>"parents_id",
                                'library'=>"book_id"
                            ];
                            $icons=['icon-Student','icon-teacher_basic','icon-parents','icon-library'];
                            $index=0;
                            foreach($table as $k=> $v){
                                // Count records for each category
                                $stmt=$conn->prepare("SELECT count($v) as `number` from $k");
                                $stmt->execute();
                                $number=$stmt->fetch(PDO::FETCH_ASSOC);
                                if($number){
                            ?>
                            <div class="dash-box">
                                <div class="left">
                                    <span><?php echo $k?></span>
                                    <p><?php echo $number['number'] ?></p>
                                </div>
                                <div class="right">
                                    <i class="iconfont <?php echo $icons[$index]; ?>"></i>
                                </div>
                            </div>
                            <?php }
                            $index++;
                        }?>
                        </div>
                    </div>
                    <!-- dashboard section end -->

                    <!-- ================= ANNOUNCEMENT SECTION ================= -->
                    <div class="announcement">
                        <div class="more">
                            <a href="./chat.php">... More</a>                
                        </div>
                            <div class="a-title"><i class="iconfont icon-xiaoxi"></i> Annoucements</div>
                            <div class="shell">
                                <?php
                                    // Fetch the latest 4 announcements
                                    $stmt=$conn->prepare("
                                    SELECT
                                         * from chat_board as c
                                         JOIN user as u on c.user_id=u.id
                                         where `type`='annoucement' 
                                         ORDER BY c.created_date DESC
                                         limit 4
                                         ");
                                    $stmt->execute();
                                    while($row=$stmt->fetch(PDO::FETCH_ASSOC)){ ?>
                                        <div class="card">
                                            <div class="left">
                                                <div class="card-title">
                                                    <i class="iconfont icon-gonggao"></i>
                                                    <span><?php echo htmlspecialchars($row['title'])?></span>
                                                </div>
                                                <div class="content">
                                                    <span><?php echo htmlspecialchars($row['content']) ?></span>
                                                </div>
                                            </div>
                                            <div class="card-right">
                                                <span><?php echo date('M', strtotime($row['created_date'])) ?></span>
                                                <span><?php echo date('d', strtotime($row['created_date'])) ?></span>
                                            </div>
                                        </div>
                               <?php } ?>
                            </div>
                    </div>
                    <!-- Announcement section end -->
                </div>

                <!-- ================= INTRODUCTION SECTION ================= -->
                <div class="intro">
                    <div class="left">
                        <div class="bcc"></div>
                        <img src="../public/img/teachers.jpg" alt="teacher & student">
                    </div>
                    <div class="right">
                        <div class="sentence">
                            <i class="iconfont icon-dagou-4"></i>
                            <p>Quality Education</p>
                        </div>
                        <div class="sentence">
                            <i class="iconfont icon-dagou-4"></i>
                            <p>Personalized Learning</p>
                        </div>
                        <div class="sentence">
                            <i class="iconfont icon-dagou-4"></i>
                            <p>Positive Environment</p>
                        </div>
                        <button>More Infromation &nbsp;&nbsp;&nbsp;→</button>
                    </div>

                    <div class="time-card">
                      <h4> Local Time</h4>
                      <div id="clock">--:--:--</div>
                    </div>

                </div>
                <!-- intro section end -->
            </div>
            <!-- ================= SYSTEMS MANAGEMENT SECTION ================= -->
            <div class="boxes">
                <div class="title">Systems Management</div>
                <div class="shell">
                    <div class="box">
                        <i class="iconfont icon-banjiketang"></i>
                        <span id="myClass">Classes</span>
                    </div>
                    <div class="box ">
                        <i class="iconfont icon-Student"></i>
                        <span id="kids">Pupils</span>
                    </div>
                    <div class="box ">
                        <i class="iconfont icon-parents"></i>
                        <span id="myParents">Parents/Guardians</span>
                    </div>
                    <div class="box ">
                        <i class="iconfont icon-teacher_basic"></i>
                        <span id="myTeacher">Teachers</span>
                    </div>
                    <div class="box ">
                        <i class="iconfont icon-gongzi"></i>
                        <span id="mySalaries">Salaries</span>
                    </div>
                    <div class="box ">
                        <i class="iconfont icon-library"></i>
                        <span>Library Books</span>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- import Footer section -->
    <?php   
        require "./common/footer.php";
    ?>


<script>
        // ================= JAVASCRIPT FUNCTIONALITY =================
        // Function to update the clock in real-time
        function updateClock() {
          const now = new Date();
          const h = String(now.getHours()).padStart(2, '0');
          const m = String(now.getMinutes()).padStart(2, '0');
          const s = String(now.getSeconds()).padStart(2, '0');
          document.getElementById('clock').textContent = `${h}:${m}:${s}`;
        }
        setInterval(updateClock, 1000);
        updateClock();

        userRole='<?php echo $_SESSION['role'] ?>';
        // Function to show access denied alert
        function accessAlert(){
            Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "You dont have access to this block",
                        });
        }
        window.onload=function(){
            let boxes=document.querySelectorAll(".box");

        
        // For admin users: they are allowed to check and manage all blocks
        boxes.forEach((box,index) => {
            if(userRole==="admin"){
                
                box.classList.add("admin");
                box.onclick=function(){
                window.location.href=links1[index]+".php";
                }
            }       
        });

        // For students: they are only allowed to check everything except salaries
        if(userRole==="student"){
                let student_box=[boxes[0],boxes[1],boxes[2],boxes[3],boxes[5]];
                let student_box2=[boxes[4]]

                document.getElementById("kids").innerText="Me";
                document.getElementById("myClass").innerText="My Class";
                document.getElementById("myTeacher").innerText="My Teacher";
                document.getElementById("myParents").innerText="My Parents";

                student_box.forEach((box,index)=>{
                    box.classList.add("student");
                    box.onclick=function(){                        
                        window.location.href=links2[index]+".php";
                    }
                })
                student_box2.forEach((box,index)=>{
                    box.onclick=function(){                        
                        accessAlert();
                    }
                })

            }


        // For parents: they are only allowed to check "kids(students)" and themselves
        if(userRole==="parent"){
                let parents_box=[boxes[1],boxes[2]];
                let parents_box2=[boxes[0],boxes[3],boxes[4],boxes[5]]
                // Change the text in HTML to fit the identity
                document.getElementById("myParents").innerText="Me";
                document.getElementById("kids").innerText="My Kid(s)";

                parents_box.forEach((box,index)=>{
                    box.classList.add("parents");
                    box.onclick=function(){                        
                        window.location.href=links3[index]+".php";
                    }
                })
                parents_box2.forEach((box,index)=>{
                    box.onclick=function(){                        
                        accessAlert();
                    }
                })

            }

            // For teachers: they are allowed to check class, students, own, and salaries
            if(userRole==="teacher"){
                let teachers_box=[boxes[0],boxes[1],boxes[3],boxes[4]];
                let teachers_box2=[boxes[2],boxes[5]]

                document.getElementById("myClass").innerText="My Class";
                document.getElementById("myTeacher").innerText="Me";
                document.getElementById("mySalaries").innerText="My Salaries";
                document.getElementById("kids").innerText="My Students";

                teachers_box.forEach((box,index)=>{
                    box.classList.add("teachers");
                    box.onclick=function(){                        
                        window.location.href=links4[index] +".php";
                    }
                })
                teachers_box2.forEach((box,index)=>{
                    box.onclick=function(){                        
                        accessAlert();
                    }
                })

            }
        }
</script>
</body>
</html>