<?php
// ================= INITIALIZATION =================
// Set role for htmlContent.php to know what form to generate
$phpRole='edit';
// Include the form content generator
require "../config/htmlContent.php";
?>

<script>
    // ================= EDIT BUTTON EVENT HANDLERS =================
    // Add click event listeners to all edit buttons on the page
    document.querySelectorAll(".btnEdit").forEach(btn=>{
        btn.addEventListener("click",function(){
            // Get the ID of the record to edit from button's data attribute
            const rowid=btn.dataset.id;
            
            // ================= EDIT FORM DIALOG =================
            // Display Sweet Alert dialog with edit form
            Swal.fire({
                title: "<strong>Edition</strong>",
                html: `<?php echo $htmlContent; ?>`, // Insert the dynamically generated form HTML
                customClass: {
                    popup: 'my-swal-popup',
                    title: 'my-swal-title',
                    input: 'my-swal-input'
                },
                showCloseButton: true,
                showCancelButton: true,
                focusConfirm: false,
                confirmButtonText: "Update",
                cancelButtonText: 'Cancel',
                // ================= FORM SUBMISSION HANDLER =================
                // This function runs when the user clicks the Update button
                preConfirm:async ()=>{
                    // Get the form element and create FormData object to collect inputs
                    const form=document.getElementById('swal-form')
                    let formDatas=new FormData(form)
                    
                    // Add title information to identify context
                    formDatas.append('title','<?php echo $title; ?>')
                    // Add table name for database operations
                    formDatas.append('table','<?php echo $table_name; ?>')
                    
                    <?php
                    // For general record editing (not user profile "Me"), add the record ID
                    if($title!=='Me'){ ?>
                        formDatas.append("<?= $tableID ?>", rowid); // Add record ID using the table's primary key

                    <?php
                    }
                    // For "Me" profile editing, the ID comes from the session in the PHP code
                    ?>

                    // ================= API REQUEST =================
                    // Send form data to the server for processing
                    let res=await fetch('../api/Edit.php',{
                        method:'POST',
                        body: formDatas
                    });

                    // Parse the response JSON
                    let data=await res.json();
                    console.log(data); // Debug output
                    
                    // Show validation errors if any
                    if(data.code!==0){
                        Swal.showValidationMessage(Array.isArray(data.msg) ? data.msg.join('<br>') : data.msg);
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
                      title: "Update Success!",
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
})
</script>
