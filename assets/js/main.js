// alert("main.js");


//REGISTER FORM SUBMIT HANDLER
$(document).on("submit", ".js-register-form", function(event) {
    event.preventDefault();

    //assign the form to a variable
    $form = $(this);
    //assign the error div to a variable
    $errors = $(".js-register-form__errors", $form);

    //collect data from the form
    let $dataObj = {
        email: $("input[type='email']", $form).val(),
        password: $("input[type='password']", $form).val()
    };

    alert($errors + "is submitted");

    

    //PERFORM BASIC FRONTEND DATA VALIDATION
    if ( $dataObj.email.length < 6 ) {
        $errors
            .text("Please enter a valid email address")
            .show();
        return false;
    } else if ( $dataObj.password.length < 8 ) {
        $errors
            .text("Password must be at least 8 characters long")
            .show();
        return false;
    }

    //hide the errors div
    $errors.hide();


    // SEND DATA TO SERVER USING AJAX
    $.ajax({
        type: 'POST',
        url: 'ajax/register.php',
        data: $dataObj,
        dataType: 'json',
        assync: true,
    })
    .done(function ajaxDone(data){
        //process data recieved
        if ( data.redirect !== undefined ) {
            window.location = data.redirect;
        } else if ( data.error !== "" ) {
            $errors
                .text(data.error)
                .show();
        }
    })
    .fail(function ajaxFail(e){
        //return error message
        console.log("Error: " + e);
    })
    .always(function ajaxAlwaysDoThis(data){
        //do this always
    })
    
    console.log("last");

    return false;
});

//LOGIN FORM SUBMIT HANDLER
$(document).on("submit", ".js-login-form", function(event) {
    event.preventDefault();

    //assign the form to a variable
    $form = $(this);
    //assign the error div to a variable
    $errors = $(".js-login-form__errors", $form);

    //collect data from the form
    let $dataObj = {
        email: $("input[type='email']", $form).val(),
        password: $("input[type='password']", $form).val()
    };

    alert($errors + "is submitted");

    

    //PERFORM BASIC FRONTEND DATA VALIDATION
    if ( $dataObj.email.length < 6 ) {
        $errors
            .text("Please enter a valid email address")
            .show();
        return false;
    } else if ( $dataObj.password.length < 8 ) {
        $errors
            .text("Password must be at least 8 characters long")
            .show();
        return false;
    }

    //hide the errors div
    $errors.hide();


    // SEND DATA TO SERVER USING AJAX
    $.ajax({
        type: 'POST',
        url: 'ajax/login.php',
        data: $dataObj,
        dataType: 'json',
        assync: true,
    })
    .done(function ajaxDone(data){
        //process data recieved
        if ( data.redirect !== undefined ) {
            window.location = data.redirect;
        } else if ( data.error !== "" ) {
            $errors
                .text(data.error)
                .show();
        }
    })
    .fail(function ajaxFail(e){
        //return error message
        console.log("Error: " + e);
    })
    .always(function ajaxAlwaysDoThis(data){
        //do this always
    })
    
    console.log("last");

    return false;
});