<?php
// ================= DYNAMIC FORM GENERATION =================
// Initialize form HTML structure
$htmlContent = "<form method='POST' id='swal-form'>";

// ================= PARENT-SPECIFIC FORM CONTENT =================
if($_SESSION['role']==='parent' && $title==='My Kids'){
    // Get all students for parent to potentially link with
    $stmt=$conn->prepare("SELECT CONCAT(First_Name,' ',Last_Name) AS student_name from students");
    $stmt->execute();
    $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Build student selection dropdown with datalist
    $htmlContent .="
        <div class='swal-box multiple'>
            <label for='student_name' class='swal-label'>Students</label>
            <input class='my-swal-input' list='studentOptions' id='student_name' name='student_name' placeholder='Type or select a student'>
            <datalist id='studentOptions'>
    ";
    // Add options from database results
    foreach($result as $row){
        $htmlContent .= "<option value=\"{$row['student_name']}\">{$row['student_name']}</option>";
    }
    
    // Add relationship selection radio buttons
    $htmlContent .="</datalist>
                </div>
                <div class='swal-box'>
                    <sapn class='swal-label'>Relation</sapn>
                    <div class='my-swal-input radio'>
                        <div class='swal-radio'>
                            <label for='father'>Father</label>
                            <input name='relation' id='father' type='radio' value='father'>
                        </div>
                        <div class='swal-radio'>
                            <label for='mother'>Mother</label>
                            <input name='relation' id='mother' type='radio' value='mother'>
                        </div>
                        <div class='swal-radio'>
                            <label for='guardians'>Guardians</label>
                            <input name='relation' id='guardians' type='radio' value='guardians'>
                        </div>
                    </div>
                </div>
    ";
}
// ================= CHAT FORM CONTENT =================
elseif($page_name==='chat'){
    $htmlContent .= "
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
}
// ================= GENERAL FORM CONTENT FOR OTHER ROLES =================
else{
    // Loop through table columns to create form fields
    foreach($table as $k => $v){
        // Set appropriate placeholders for different field types
        $placeholder='';
        if($k==='phone' || $k==='parents_phone'){
            $placeholder="placeholder='e.g., +447XXXXXXXXX'";
        }elseif($k==='email' || $k==='parents_email'){
            $placeholder="placeholder='e.g., example@gmail.com'";
        }elseif($k==='name'){
            $placeholder="placeholder='e.g., Johnson Wax'";
        }

        // Skip columns that shouldn't be editable
        if($v !== 'Action'  && $k!=='actual_amount' && $k!=='status' && ($k!=='cover') && $k!=='book_infor'){

            // ================= GRADE SELECTION DROPDOWN =================
            if($v=='Grade'){
                $htmlContent .="
                    <div class='swal-box'>
                        <label for='year' class='swal-label'>".htmlspecialchars($v)."</label>
                        <select name='grade' class='my-swal-input'>
                            <option value='none' selected> -select-year-</option>
                            <option value='Reception Year'>Reception Year</option>
                            <option value='Year One'>Year One</option>
                            <option value='Year Two'>Year Two</option>
                            <option value='Year Three'>Year Three</option>
                            <option value='Year Four'>Year Four</option>
                            <option value='Year Five'>Year Five</option>
                            <option value='Year Six'>Year Six</option>
                        </select>
                    </div>
                ";
            }
            // ================= BOOLEAN FIELDS WITH RADIO BUTTONS =================
            elseif($k=='backgroundCheck'|| $k==='if_paid'){
                if($k=='backgroundCheck'){
                    $yes="Checked";
                    $no="Not Checked";
                }
                if($k==='if_paid'){
                    $yes="paid";
                    $no="Not paid";
                }
                $htmlContent .="
                    <div class='swal-box'>
                        <span class='swal-label radio'>".htmlspecialchars($v)."</span>
                        <div class='my-swal-input radio'>
                            <div class='swal-radio'>
                                <label for='$no'>$no</label>
                                <input name='".htmlspecialchars($k)."' id='$no' type='radio' value='0'>
                            </div>
                            <div class='swal-radio'>
                                <label for='$yes'>$yes</label>
                                <input name='".htmlspecialchars($k)."' id='$yes' type='radio' value='1'>
                            </div>
                        </div>
                    </div>
                ";
            }
            // ================= NUMERICAL AMOUNT FIELDS =================
            elseif($k==="expected_amount" || $k==="penalty_amount" ){
                $htmlContent .="
                    <div class='swal-box'>
                        <label class='swal-label radio' for='penalty_amount'>".htmlspecialchars($k)."</label>
                        <input type='number' class='my-swal-input amount' name='".htmlspecialchars($k)."' required placeholder='Enter: 0.00' min='0'>
                    </div>
                ";
            }
            // ================= DATE INPUT FIELDS =================
            elseif($k=='borrowDate' || $k=='publishDate' || $k=='DueDate' || $k==='birthday' || $k=='returnDate'){
                $htmlContent .="
                    <div class='swal-box'>
                        <label class='swal-label'>".htmlspecialchars($k)."</label>
                        <input type='date' class='my-swal-input amount' name='".htmlspecialchars($k)."'>
                    </div>
                ";
            }
            // ================= STUDENT ID FIELD =================
            elseif($k=='student_id'){
                $htmlContent .="
                    <div class='swal-box'>
                        <label class='swal-label'>Student ID</label>
                        <input type='number' class='my-swal-input' name='".htmlspecialchars($k)."'>
                    </div>
                ";
            }
            // ================= RELATION SELECTION RADIO BUTTONS =================
            elseif($k=='relation'){
                $htmlContent .="
                    <div class='swal-box'>
                        <sapn class='swal-label'>".htmlspecialchars($v)."</sapn>
                        <div class='my-swal-input radio'>
                            <div class='swal-radio'>
                                <label class='label' for='father'>father</label>
                                <input name='".htmlspecialchars($k)."' id='father' type='radio' value='father'>
                            </div>
                            <div class='swal-radio'>
                                <label class='label' for='mother'>mother</label>
                                <input name='".htmlspecialchars($k)."' id='mother' type='radio' value='mother'>
                            </div>
                            <div class='swal-radio'>
                                <label class='label' for='guardians'>guardians</label>
                                <input name='".htmlspecialchars($k)."' id='guardians' type='radio' value='guardians'>
                            </div>
                        </div>
                    </div>
                ";
            }
            // ================= CLASS SELECTION DROPDOWN =================
            elseif($k=='class_name' && $table_name==='students'){
                $htmlContent .= "
                    <div class='swal-box'>
                        <label for='class_name' class='swal-label'>Class Name</label>
                        <select name='class_name' class='my-swal-input'>
                            <option value='none' selected> -select-class-</option>";
                // Query available classes from database
                $stmt = $conn->prepare("SELECT class_name, Grade FROM classes");
                $stmt->execute();
                $classes = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Add class options from database results
                if ($classes) {
                    foreach ($classes as $class) {
                        $grade = htmlspecialchars($class['Grade']);
                        $className = htmlspecialchars($class['class_name']);
                        $displayName = $grade . ": " . $className;
                        $htmlContent .= "<option value=\"" . $className . "\">" . $displayName . "</option>";
                    }
                }        
                $htmlContent .= "
                        </select>
                    </div>
                ";
            }
            // ================= MONTH SELECTION FIELD =================
            elseif($k=='salary_month'){
                $htmlContent .="
                    <div class='swal-box'>
                        <label class='swal-label'>Salary of Month</label>
                        <input type='month' class='my-swal-input' name='".htmlspecialchars($k)."'>
                    </div>
                ";
            }
            // ================= GENDER SELECTION RADIO BUTTONS =================
            elseif($k==='Gender'){
                $htmlContent .="
                <div class='swal-box'>
                    <sapn class='swal-label'>".htmlspecialchars($v)."</sapn>
                    <div class='my-swal-input radio'>
                        <div class='swal-radio'>
                            <label for='female'>Female</label>
                            <input name='".htmlspecialchars($k)."' id='female' type='radio' value='Female'>
                        </div>
                        <div class='swal-radio'>
                            <label for='male'>Male</label>
                            <input name='".htmlspecialchars($k)."' id='male' type='radio' value='Male'>
                        </div>
                    </div>
                </div>
            ";
            }
            // ================= NAME FIELDS WITH DATALIST =================
            elseif(($k==='Name' && $table_name==='classes') || 
            (($k=='Kids1' || $k==='Kids2') && $table_name=='parents') || 
            ($k==='Name' && $table_name==='salaries') ||
            ($k==='Name' && $table_name==='borrowed_book')
            ){
                // Determine which table to fetch names from
                $tableN='';
                if($k==='Name' && $table_name==='classes' || $k==='Name' && $table_name==='salaries'){
                    $tableN='teachers';
                }elseif((($k=='Kids1' || $k==='Kids2') && $table_name=='parents')){
                    $tableN='students';
                }elseif($k==='Name' && $table_name==='borrowed_book'){
                    $tableN='students';
                }
                // Create input with datalist for name selection
                $htmlContent .="
                <div class='swal-box multiple'>
                    <label for='Name' class='swal-label'>".htmlspecialchars($v)."</label>
                        <input class='my-swal-input' list='".htmlspecialchars($k)."option' id='".htmlspecialchars($k)."' name='".htmlspecialchars($k)."' placeholder='Type or select a ".htmlspecialchars($v)."'>
                        <datalist id='".htmlspecialchars($k)."option'>
                ";
                
                // Fetch names from appropriate table
                $stmt=$conn->prepare("SELECT CONCAT(First_Name,' ',Last_Name) AS $k FROM $tableN");
                $stmt->execute();
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                // Add options from database results
                foreach($rows as $row){
                    $htmlContent .= "<option value=\"{$row[$k]}\">{$row[$k]}</option>";
                }
                $htmlContent .="</datalist> </div>";
            }
            // ================= EMAIL FIELD FOR PERSONAL PROFILE =================
            elseif ($k === 'email') {
                if ($title === 'Me') {
                    $htmlContent .= "
                    <div class='swal-box'>
                        <label class='swal-label' for='email'>Email</label>
                        <input type='email' class='my-swal-input' name='email' placeholder='Enter your email'>
                    </div>";
                }
            }
            // ================= DEFAULT TEXT INPUT FIELD =================  
            else{
            $htmlContent .="       
                <div class='swal-box'>
                    <label class='swal-label' for='".htmlspecialchars($k)."'>".htmlspecialchars($v).'</label>
                    <input  type="text"  class="my-swal-input" name="'.htmlspecialchars($k).'"'.$placeholder.'>
                </div>';
            }
        }   
    }
}

// Close the form element
$htmlContent .= "</form>";
?>
