<!-- 
 * header.php
 * This file contains the header section of the website.
 * It includes welcome message, user authentication status,
 * search functionality, and quick access icons.
 -->

<!-- Header Section Start -->
<header>
    <!-- Main Header Container -->
    <div class="container-h">
        <form action="">
            <!-- Welcome Message Section -->
            <div class="header-c">
                <p>Welcome</p>
                <!-- Dynamic Welcome Message: Shows username if logged in, otherwise shows default message -->
                <span>
                    <?php 
                        if(isset($_SESSION)){
                            echo $_SESSION['username'] ;
                        }else{
                            echo "to St Alphonsus Primary School!";
                        }
                    ?>
                </span>
            </div>

            <!-- Quick Access Section (Only visible when user is logged in) -->
            <?php if(isset($_SESSION)){ ?>
            <div class="left">
                <!-- Search Bar -->
                <div class="input">
                    <input type="text" placeholder="What do you wanna find?">
                    <button type="submit"><i class="iconfont icon-search"></i></button>
                </div>

                <!-- Message Icon -->
                <div class="icon-box">
                    <a href="">
                        <i class="iconfont icon-xiaoxi"></i>
                    </a>
                </div>

                <!-- Settings Icon -->
                <div class="icon-box">
                    <a href="">
                        <i class="iconfont icon-setting"></i>
                    </a>
                </div>
            </div>
            <?php }?>
        </form>
    </div>
</header>
<!-- Header Section End -->