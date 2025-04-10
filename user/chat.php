<?php
/**
 * chat.php
 * This file contains the chat interface for the website.
 * It includes announcement slideshow, chat messages display, and message posting functionality.
 * The interface features a modern design with interactive elements and role-based access control.
 */

// =========== INITIALIZATION SECTION ===========
session_start();
$title="chat";
$page_name='chat';
require "../config/db.php";
$table_name='chat_board';
require "../api/checkBind.php";
$tableID='chat_id';

?>

<!DOCTYPE html>
<html lang="en">
<?php
    require "./common/head.php";
?>
<body>
    <main>
        <?php require "./common/slidebar.php" ?>
        <div class="container">
            <?php
            require "./common/header.php";
            ?>
            <div class="chat-box">
                <!-- annoucncement section start
                        
                **** slideshow Import from https://swiperjs.com/demos#navigation *****
                        css made myself to fit my layout
                        start from swiper container label
                -->
                <div class="annoucement">
                    <div class="annoucement-title-box">
                        <div class="annoucement-title">
                            <span>Annoucement</span>
                            <i class="iconfont icon-gonggao"></i>
                        </div>
                    </div>
                    <!-- Swiper container for announcements slideshow -->
                    <swiper-container class="mySwiper" init="false">
                        <?php
                            // Fetch announcements from database
                            $annoucementstmt = $conn->prepare("
                            SELECT cb.*, u.username,u.role 
                            FROM chat_board AS cb
                            LEFT JOIN user AS u ON cb.user_id = u.id
                            WHERE cb.type = 'annoucement'
                            ORDER BY cb.chat_id DESC
                            ");                      
                            $annoucementstmt->execute();
                            while($annoucement=$annoucementstmt->fetch(PDO::FETCH_ASSOC)){ ?>
                            <swiper-slide class="chat-delete" data-tableName="chat_board" data-tableID="chat_id" data-id="<?php echo $annoucement['chat_id']?>">
                              <div class="modern-hanger">
                                <div class="bar-top"></div>
                                <div class="calendar-body" >
                                    <?php 
                                        // Show delete button for admin users
                                        if($_SESSION['role']==='admin'){
                                            echo "
                                                <button class='btnDelete'>
                                                        <i class='iconfont icon-delete'></i>
                                                </button>
                                                <button class='btnEdit' data-id='{$annoucement['chat_id']}' >
                                                        <i class='iconfont icon-genggai'></i>
                                                </button>
                                            ";
                                        }
                                    ?>
                                  <h3 class="calendar-title"><?php echo htmlspecialchars($annoucement['title']); ?></h3>
                                  <p class="calendar-date"><?php echo date('Y-M-d', strtotime($annoucement['created_date'])); ?></p>
                                  <p class="calendar-text"><?php echo htmlspecialchars($annoucement['content']); ?></p>
                                </div>                            
                                <div class="bar-bottom"></div>
                              </div>
                            </swiper-slide>
                      <?php
                            }
                        ?>
                    </swiper-container>

                    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-element-bundle.min.js"></script>
                </div>
                <!-- annoucement section end -->

                <!-- =========== CHAT MESSAGES SECTION =========== -->
                <div class="chat-shell">
                    <?php
                    // Fetch chat messages from database
                    $stmt = $conn->prepare("
                    SELECT cb.*, u.username,u.role 
                    FROM chat_board AS cb
                    LEFT JOIN user AS u ON cb.user_id = u.id
                    WHERE cb.type = 'message'
                    ORDER BY cb.chat_id DESC
                    ");                      
                    $stmt->execute();
                    while($row=$stmt->fetch(PDO::FETCH_ASSOC)){ ?>
                        <div class="chat-card chat-delete" data-tableName="chat_board" data-tableID="chat_id" data-id="<?php echo $row['chat_id']?>">
                                <?php 
                                    // Show delete button for admin users
                                    if($_SESSION['role']==='admin'){
                                        echo "
                                            <button class='btnDelete' >
                                                    <i class='iconfont icon-delete'></i>
                                            </button>
                                            <button class='btnEdit' data-id='{$row['chat_id']}' >
                                                        <i class='iconfont icon-genggai'></i>
                                            </button>
                                        ";
                                    }
                                ?>
                            <!-- Chat message header with user info -->
                            <div class="chat_header">
                                <img src="<?php 
                                    // Set profile image based on user role
                                    if($row['role']==='admin'){
                                        echo '../public/img/admin.jpg';
                                    }elseif($row['role']==='student'){
                                        echo '../public/img/student.jpg';
                                    }elseif($row['role']==='teacher'){
                                        echo '../public/img/teacher.jpeg';
                                    }elseif($row['role']==='parent'){
                                        echo '../public/img/parent.jpeg';
                                    }
                                ?>" alt="profile_photo">
                                <div class="box">
                                    <p><?php echo htmlspecialchars($row['title']);  ?></p>
                                    <span><?php echo htmlspecialchars($row['username']);  ?></span>
                                </div>
                            </div>
                             
                            <!-- Message date -->
                            <div class="date"><?php echo date('Y-M-d', strtotime($row['created_date']))  ?></div>
                            
                            <!-- Message content -->
                            <div class="chat-content"><?php echo htmlspecialchars($row['content']);  ?></div>

                            <!-- Message footer with action icons -->
                            <div class="chat-footer">
                                <div class="icons">
                                    <i class="iconfont icon-xiaoxi1"></i>
                                    <i class="iconfont icon-aixin_shixin"></i>
                                </div>
                            </div>
                        </div>
                    <?php 
                        }
                    ?>
                </div>
            </div>
        </div>
    </main>
    <?php   
        require "./common/footer.php";
        
        //import delete file
        require "../api/fetch.delete.php";
        //import edit file
        require "../api/fetch.edit.php";
    ?>
</body>

<?php
// =========== NEW MESSAGE FORM TEMPLATE ===========
$htmlContent = "
        <form method='POST' id='swal-form'>
            <div class='swal-box'>
                <label class='swal-label' for='chat-title'>Title</label>
                <input class'my-input' type='text' name='chat-title' id='chat-title' maxlength='30' required>
                <p class='char-count' id='title-count'>0 / 30</p>
            </div>
            <div class='swal-box'>
                <label class='swal-label' for='content'>Content</label>
                <textarea name='content' id='content' maxlength='500' row='5' placeholder='Write your message here...' required></textarea>
                <p class='char-count' id='content-count'>0 / 500</p>
            </div>
        ";
        // Add message type selection for admin users
        if($_SESSION['role']=='admin'){
            $htmlContent .="
                <div class='my-swal-input radio'>
                        <div class='swal-radio'>
                            <label for='bcc1'>Annoucement</label>
                            <input required name='type' id='bcc1' type='radio' value='annoucement'>
                        </div>
                        <div class='swal-radio'>
                            <label for='bcc2'>Message</label>
                            <input required name='type' id='bcc2' type='radio' value='message'>
                        </div>
                    </div>
            ";
        }
$htmlContent .='</form>';
?>

<script>
    // =========== SWIPER INITIALIZATION ===========
    const swiperEl = document.querySelector('swiper-container')
    Object.assign(swiperEl, {
      slidesPerView: 1,
      spaceBetween: 10,
      pagination: {
        clickable: true,
      },
      breakpoints: {
        640: {
          slidesPerView: 2,
          spaceBetween: 20,
        },
        768: {
          slidesPerView: 4,
          spaceBetween: 40,
        },
        1024: {
          slidesPerView: 5,
          spaceBetween: 50,
        },
      },
    });
    swiperEl.initialize();

    // =========== NEW MESSAGE HANDLING ===========
    getUser();
    document.getElementById('add-chat').addEventListener('click',function(){
        Swal.fire({
                title: "<strong>Post New Message</strong>",
                html: `<?php echo $htmlContent; ?>`, 
                didOpen: () => {
                    // Character count tracking
                    const titleInput = document.getElementById('chat-title');
                    const contentInput = document.getElementById('content');
                    const titleCount = document.getElementById('title-count');
                    const contentCount = document.getElementById('content-count');
                                
                    titleInput.addEventListener('input', e => {
                      titleCount.textContent = `${e.target.value.length} / 30`;
                    });
                
                    contentInput.addEventListener('input', e => {
                      contentCount.textContent = `${e.target.value.length} / 500`;
                    });
                },
                customClass: {
                    popup: 'my-swal-popup',
                    title: 'my-swal-title',
                    input: 'my-swal-input'
                },
                showCloseButton: true,
                showCancelButton: true,
                focusConfirm: false,
                confirmButtonText: "POST",
                cancelButtonText: 'Cancel',
                preConfirm:async ()=>{
                    // Form submission handling
                    const form=document.getElementById('swal-form')
                    let formDatas=new FormData(form)
                    formDatas.append('title','<?php echo $title; ?>')
                    formDatas.append('table','<?php echo $table_name; ?>')
                    let res=await fetch('../api/insert.php',{
                        method:'POST',
                        body: formDatas
                    });
                    
                    let data=await res.json();
                    console.log(data);
                    if(data.code!==0){
                        Swal.showValidationMessage(data.msg);
                    }
                    return data;
                }
            }).then((result)=>{
                if (result.isConfirmed) {
                    Swal.fire({
                      title: "Post Message Success!",
                      icon: "success",
                      timer: 3000,
                            }).then(()=>{
                        location.reload();
                            })  
                        } 
                    })
    })
</script>
</html>