<?php 
    // ================= CONTAINER CONFIGURATION =================
    // Set the container selector based on the table type
    if($table_name==='chat_board'){
        $container=".chat-delete"; // For chat messages, use a special container class
    }else{
        $container='tr'; // For most tables, use table rows as containers
    }
?>
<script>
    // ================= RESTRICTED ACCESS BUTTONS =================
    // Handle click events for buttons without delete permission (class "no")
    const fakeBtn=document.querySelectorAll(".no");
    fakeBtn.forEach(btn => {
        btn.addEventListener("click",function(){
            // Show error message for users without access
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "You don't have access with it!",
            });
        })
    });

    // ================= DELETE FUNCTIONALITY =================
    // Handle click events for actual delete buttons
    const deleteBtn=document.querySelectorAll(".btnDelete");
    deleteBtn.forEach(btn => {
        btn.addEventListener("click",function(e){
            // Get the parent container element (table row or chat message)
            const row=e.target.closest("<?php echo $container ?>");
            // Get data attributes with record information
            const id=row.getAttribute("data-id");            // Record ID
            const tableName=row.getAttribute("data-tableName"); // Table name
            const tableID=row.getAttribute("data-tableID");  // Primary key column name
            
            // Debug logging
            console.log("get id", id);
            console.log("get tableName", tableName);
            console.log("get tableID", tableID);
            
            // ================= CONFIRMATION DIALOG =================
            // Show confirmation dialog before proceeding with deletion
            Swal.fire({
              title: 'Are you sure to delete?',
              icon: 'warning',
              showCancelButton: true,
              confirmButtonText: 'Yes',
              cancelButtonText: 'No'
            }).then((result) => {
              // Only proceed if the user confirmed
              if (result.isConfirmed) {
                // ================= API REQUEST =================
                // Send DELETE request to the server
                fetch("../api/delete.php", {
                  method: 'POST',
                  headers:{'Content-Type':'application/json'},
                  body:JSON.stringify({
                    id:id,               // Record ID
                    tableName: tableName, // Table name
                    tableID:tableID      // Primary key column name
                })
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    // ================= SUCCESS HANDLING =================
                    if(data.code === 0) { // Code 0 indicates success
                        // Show success message
                        Swal.fire({
                            title: "Delete Successed!",
                            icon: "success",
                        });
                        // Remove the deleted row from the UI
                        row.remove();
                    } 
                    // ================= ERROR HANDLING =================
                    else {
                        // Show error message if the delete operation failed
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Delete Fail!",
                        });
                    }
                })
                // ================= EXCEPTION HANDLING =================
                .catch(err => {
                  // Handle network or other errors
                  Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Something wrong!",
                  });
                });
              }
              // If user clicked "No", nothing happens
            });
  
        })
    });

</script>