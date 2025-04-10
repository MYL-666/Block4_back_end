<script>
  // ================= NAVIGATION LINK CONFIGURATION =================
  // Define navigation links available for different user roles
  // Admin users have access to all sections
  let links1=["classes","students","parents","teachers","salaries","library"];
  // Students can access all except salaries
  let links2=["classes","students","parents","teachers","library"];
  // Parents can only access students and parents sections
  let links3=["students","parents"];
  // Teachers can access classes, students, their own profile, and salaries
  let links4=["classes","students","teachers","salaries"];


  // ================= USER SESSION RETRIEVAL =================
  // Fetch current user information from session
  async function getUser(){
    // Request user session data from server
    let res = await fetch("../api/getSessionUser.php")
    let data = await res.json();
    userRole = '';
    userName = '';
    
    // Extract user role and username if available
    if(data.code == 0){
        console.log(data.role + data.username);
        userName = data.username;
        userRole = data.role;
    }

    // ================= SIDEBAR NAVIGATION SETUP =================
    // Get all navigation list items
    const lists = document.querySelectorAll(".list");

    // Function to activate navigation items based on user role
    function activateNav(links) {
      lists.forEach((item) => {
        const nav = item.dataset.nav; 
        // If the navigation item is in the allowed links for this role, activate it
        if (links.includes(nav)) {
          item.classList.add("active");
        }
      });
    }

    // ================= ROLE-BASED NAVIGATION ACTIVATION =================
    // Apply different navigation menus based on user role
    if (userRole === "admin") {
      // Admins get access to all navigation links
      activateNav(links1);
    } else if (userRole === "student") {
      // Students get access to all except salaries
      activateNav(links2);
    } else if (userRole === "parent") {
      // Parents can only access students and parents sections
      activateNav(links3);
    } else if(userRole === "teacher") {
      // Teachers get access to classes, students, their profile, and salaries
      activateNav(links4);
    }
    
    // ================= CURRENT PAGE HIGHLIGHTING =================
    // Get current page name from PHP
    const pageName = "<?php echo $table_name ?>";
    console.log(pageName);
    
    // Highlight the current page in the sidebar
    const pages = document.querySelectorAll('.slidebar-item li');
    pages.forEach(page => {
        // If this sidebar item matches the current page, add active class
        if(pageName == page.dataset.nav){        
            page.classList.add('active2');
        }
    });
};
</script>