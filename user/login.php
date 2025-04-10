<?php 
 // Initialize page variables
 $title= "Login Page";
 $page_name="login";
 require "../config/db.php";
?>

<!DOCTYPE html>
<html lang="en">
    <!-- ================= HEAD SECTION ================= -->
<?php 
    require "./common/head.php"
?>

<body>
    <!-- ================= HEADER SECTION ================= -->
    <?php
        require "./common/header.php";
    ?>
    <main class="main">
        <!-- ================= SIGN UP SECTION ================= -->
        <div class="create" id="sign-page">
            <div class="left">
                <h1>Welcome Back!</h1>
                <span>If you already had an account </span>
                <span>please login there ↓</span>
                <button id="btn01">SIGN IN</button>
            </div>
            <div class="right">
                <p>Creat Account</p>
                <form action="" id="regist" method="$_POST">
                    <div class="input">
                        <input type="text" name="username" id="username_r" placeholder="User Name:" required>
                    </div>
                    <div class="input ">
                        <input type="email" name="email" placeholder="Email:" id="email_r" required>
                    </div>
                    <div class="input pdw">
                        <i class="iconfont icon-eye open"></i>
                        <i class="iconfont icon-eye-close close"></i>
                        <input type="password" class="_pdw" name="password" placeholder="Password:" required>
                    </div>
                    <div class="input pdw">
                        <i class="iconfont icon-eye open"></i>
                        <i class="iconfont icon-eye-close close"></i>
                        <input type="password" class="_pdw" name="re_password" placeholder="Confirm:" required>
                    </div>
                    <div class="radio">
                        <div class="radio-item">
                            <label for="student_r">Student</label>
                            <input class="radios" type="radio" name="role" value="student" id="student_r">
                        </div>
                        <div class="radio-item">
                            <label for="teacher_r">Teacher</label>
                            <input class="radios" type="radio" name="role" value="teacher" id="teacher_r">
                        </div>
                        <div class="radio-item">
                            <label for="parents_r">Parents</label>
                            <input class="radios" type="radio" name="role" value="parent" id="parents_r">
                        </div>                       
                    </div>
                    <button type="submit" name="submit" id="btn02">SIGN UP</button>
                    <input type="hidden" name="action" value="registration">
                </form>
            </div>
        </div>

        <!-- ================= LOGIN SECTION ================= -->
        <div class="login" id="login-page">            
            <div class="right">
                <p>Login to School</p>
                <form action="./index.php" id="login" method="$_POST">
                    <div class="input">
                        <input type="email" name="login_email" placeholder="Email:" id="login_email" required>
                    </div>
                    <div class="input pdw">
                        <i class="iconfont icon-eye open"></i>
                        <i class="iconfont icon-eye-close close"></i>
                        <input class="_pdw" type="password" name="login_password" id="login_password" placeholder="Password:" required>
                    </div>
                    <div class="forgot">
                        <a href="javascript:;">Forgot your password?</a>
                    </div>
                    <button type="submit" id="btn03">LOGIN</button>
                    <input type="hidden" name="action" value="login">
                </form>
            </div>
            <div class="left" id="sl">
                <h1>Hello, Mate!</h1>
                <span>To keep connection with us,</span>
                <span>please regist here ↓</span>
                <button id="btn04">SIGN UP</button>
            </div>
        </div>
    </main>
    <!-- ================= FOOTER SECTION ================= -->
    <?php   
        require "./common/footer.php"
    ?>
    <!-- ================= JAVASCRIPT FUNCTIONALITY ================= -->
    <script>
            // Get DOM elements
            const regist=document.getElementById("sign-page");
            const login=document.getElementById("login-page");
            const btn01=document.getElementById("btn01");
            const btn04=document.getElementById("btn04");
            const closebtn=document.querySelectorAll(".close");
            const openBtn=document.querySelectorAll(".open");
            const pdwInput=document.querySelectorAll("._pdw");
            
            // Function to toggle between register and login pages with animation
            function show(btn,a,b){
                btn.onclick=function(){
                    a.style.animation="changeToSignUp1 .5s ease-in-out forwards";
                    b.style.animation="changeToSignUp2 .5s ease-in-out forwards";
                    // Wait for animation to complete before changing display property
                    setTimeout(() => {
                        a.style.display="none";
                        b.style.display="flex";
                }, 500);
                }
            }
            // Call the toggle function for both directions
            show(btn04,login,regist);
            show(btn01,regist,login);

            // Password visibility toggle functionality
            closebtn.forEach((btn,index) => {
                
                pdwInput[index].type="password";
                btn.addEventListener("click",function(){
                    if(pdwInput[index].type=='text'){
                    btn.style.opacity='1'; // Eyes open become visible
                    openBtn[index].style.opacity='0'; // Eyes close hide
                    pdwInput[index].type="password"; // Change to password type to hide password
                    }else{
                        btn.style.opacity='0'; // Eye-open become invisible
                        openBtn[index].style.opacity='1'; // Eye close show
                        pdwInput[index].type='text'; // Change to text type so that password can be seen
                    }
                })
            });

            // ================= REGISTRATION VALIDATION ================= 
            document.getElementById("btn02").addEventListener("click",function(e){
                e.preventDefault(); // Prevent form default submission
                let form=document.getElementById("regist");
                let formDatas=new FormData(form);
                let indentities=document.querySelectorAll(".radios");
                let checkNum=0;
                let formStatus=true;

                // Async function to handle registration API request
                async function getRegist(){
                    let res = await fetch('../api/registerV.php',{
                        method:"POST",
                        body:formDatas
                    })
                    let data=await res.json();
                    console.log(data)
                    
                    // Check for errors from server
                    if(data.code !==0){
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: data.msg,
                        });
                        return;
                    }
                    
                    // Display success message
                    Swal.fire({
                      title: "Sign Up Successed!",
                      icon: "success",
                      draggable: false,
                    });

                    // Animate transition to login page
                    regist.style.animation = "changeToSignUp1 .5s ease-in-out forwards";
                    login.style.animation = "changeToSignUp2 .5s ease-in-out forwards";

                    setTimeout(() => {
                      regist.style.display = "none";
                      login.style.display = "flex";
                    }, 500);
                }


                // Front-end validation
                let register_username=document.getElementById("username_r").value;
                let register_email=document.getElementById("email_r").value;
                
                // Check if username or email is empty
                if(register_username.trim()=="" || register_email.trim()==""){
                    Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Username or Email is Empty!",
                        });
                    return false;
                }

                // Check if any identity (role) is selected
                indentities.forEach(identity => {
                    if(!identity.checked){
                        checkNum++
                    }
                });
                
                // If no identity is chosen, show error
                if(checkNum===3){
                    Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Please choose your identity",
                        });
                        return false; // Change the validation status
                }
                // If front-end validation passes, call the back-end validation
                getRegist();
            })
            // ================= END REGISTRATION VALIDATION =================


            // ================= LOGIN VALIDATION ================= 
            document.getElementById("btn03").addEventListener("click",function(e){
                e.preventDefault();
                let form_login=document.getElementById("login");
                let login_email=document.getElementById("login_email");
                let login_pdw=document.getElementById("login_password");
                let formDatas=new FormData(form_login);
                let login_status=true;

                // Front-end validation - check for empty fields
                if(login_email.value.trim()=="" || login_pdw.value.trim()==""){
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Email Or Password can't be Empty!",
                    });
                    return false;
                }

                // Async function to handle login API request
                async function Login(){
                    let res= await fetch("../api/loginV.php",{
                        method:"POST",
                        body: formDatas
                    })
                    let data= await res.json();
                    console.log(data)
                    
                    // Check for errors from server
                    if(data.code!=0){
                        Swal.fire({
                                    icon: "error",
                                    title: "Oops...",
                                    text: data.msg,
                                });
                                return;
                    }
                    
                    // If login is successful, show success message
                    if(login_status){
                    Swal.fire({
                          title: "Login Successed!",
                          icon: "success",
                          draggable: false,
                    });
                    }
                 
                    // Redirect to homepage after successful login
                    setTimeout(()=>{
                        window.location.href="./index.php"                
                    },1000)
                }

                // Call the login function
                Login();   
            })
            // ================= END LOGIN VALIDATION =================
    </script>
</body>
</html>