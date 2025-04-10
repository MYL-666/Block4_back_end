<?php
// ================= INITIALIZATION =================
// Set role for htmlContent.php to know what form to generate
$phpRole='insert';
// Include the form content generator
require "../config/htmlContent.php";

// ================= ACCESS CONTROL =================
// Only admin users are allowed to insert new records
if($_SESSION['role']==='admin'){
?>
<script>
    // ================= INSERT BUTTON EVENT HANDLER =================
    // Add click event listener to the insert button
    document.getElementById("insert").addEventListener("click",function(){
            // ================= INSERT FORM DIALOG =================
            // Display Sweet Alert dialog with insert form
            Swal.fire({
                title: "<strong>Add New <?= $title ?></strong>",
                html: `<?php echo $htmlContent; ?>`, // Insert the dynamically generated form HTML
                customClass: {
                    popup: 'my-swal-popup',
                    title: 'my-swal-title',
                    input: 'my-swal-input'
                },
                showCloseButton: true,
                showCancelButton: true,
                focusConfirm: false,
                confirmButtonText: "Insert",
                cancelButtonText: 'Cancel',
                // ================= FORM SUBMISSION HANDLER =================
                // This function runs when the user clicks the Insert button
                preConfirm:async ()=>{
                    // Get the form element and create FormData object to collect inputs
                    const form=document.getElementById('swal-form')
                    let formDatas=new FormData(form)
                    
                    // Add title information to identify context
                    formDatas.append('title','<?php echo $title; ?>')
                    // Add table name for database operations
                    formDatas.append('table','<?php echo $table_name; ?>')
                    
                    // ================= API REQUEST =================
                    // Send form data to the server for processing
                    let res=await fetch('../api/insert.php',{
                        method:'POST',
                        body: formDatas
                    });

                    // Parse the response JSON
                    let data=await res.json();
                    console.log(data); // Debug output
                    
                    // Show validation errors if any
                    if(data.code!==0){
                        Swal.showValidationMessage(data.msg);
                    }
                    
                    // Return data to the next .then() handler
                    return data;
                }
            }).then((result)=>{
                // ================= SUCCESS HANDLING =================
                // If the form was submitted and confirmed (not cancelled)
                if (result.isConfirmed) {
                    // Show success message
                    Swal.fire({
                      title: "Insert Success!", // Changed from "Update Success!" to "Insert Success!"
                      icon: "success",
                      timer: 3000, // Auto-close after 3 seconds
                    }).then(()=>{
                        // Reload the page to reflect changes
                        location.reload();
                    })  
                } 
                // If cancelled, nothing happens
            })
    })

</script>

<?php
// End of admin-only section
}
?>
