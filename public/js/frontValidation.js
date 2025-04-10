
//regular validation function
function regeTest(v,reg,error){
    if(!reg.test(v)){
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Incorrect "+ error +"!"
        });
        return false;
    }
    return true;
}

function isEmpty(index){
    if(index.value.trim()==="" || index.value=="none"){
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "All fileds can't be empty",
        });
    }
}

function isEnough(index,lengthSmall,lengthLarge,text){
    if(index.value.trim()==""){
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "All fileds can't be empty",
        });
    }else{
        if(index.value<parseInt(lengthSmall) || index.value>parseInt(lengthLarge)){
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: `${text} should between ${lengthSmall} and ${lengthLarge}`,
            });
            return false
        }
    }


}

function emailValidation(index){
    if(!regeTest(index,/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/,"email format")){
        return  false;
    }
    return true;
}

function phoneNumberValidation(index){
    // if phone number like "+44 xxxxxxxxx/+44xxxxxxxxx/07xxxxxxxxxx"
    if(!regeTest(index,/^(\+44\s?7\d{9}|07\d{9})$/,"phone number format")){
        return  false;
    }
    return true;
}



function usernameValidation(index){
    // make sure to be at least 6 to max 30 and '-' '_' is allowed
    if(!regeTest(index,/^[a-zA-Z0-9_-]{5,30}$/,"username length")){
        return  false;
    }
    return true;
}

// function to check if role are correct
function roleValidation(index) {
    let roles = ['student', 'teacher', 'parent', 'admin'];   
    if (roles.includes(index)) {
        return true;
    }   
    Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Invalid role selection!",
    });
    return false;
}

// for the people's name should be under 50 characters
function pplName(index){
    if(!regeTest(index,/^.{1,50}$/,"name length")){
        return  false;
    }
    return true;
}

// address should under 500 characters
function addressValidation(text){
    if(!regeTest(text,/^.{0,500}$/,"address")){
        return  false;
    }
    return true;
}

//Medical_information should under 500 characters
function medicalValidation(text){
    if(!regeTest(text,/^.{0,500}$/,"Medical Information")){
        return  false;
    }
    return true;
}

// Class Name should under 20 character
function classNameValidation(name){
    if(!regeTest(name,/^.{1,20}$/,"class name")){
        return  false;
    }
    return true;
}

//Book Title should under 50 characters
function bookTitleValidation(title){
    if(!regeTest(title,/^.{0,50}$/,"book title")){
        return  false;
    }
    return true;
}

//salaries need to be have 2 decimal number and can't under 0
function salariesValidation(num){
    if(!parseFloat(num)>0){
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Incorrect salaries input!",
        });
        return false;
    }
    return true;
}