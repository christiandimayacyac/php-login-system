<?php
    // define the __CONFIG__ to allow the config.php file
    define('__CONFIG__', true);
    require_once "inc/config.php";
    require_once "inc/functions.php";

    ForceDashboard();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="stylesheet" href="assets/css/style.css">        
        <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700,900" rel="stylesheet">

        <link rel="stylesheet" href="assets/css/style.css">
        <!-- <link rel="shortcut icon" type="image/png" href="img/favicon.png"> -->
        
        <title>PHP MySQL Login System</title>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-1-of-3">
                    <form action="#" class="register-form js-register-form">
                        <div class="register-form__errors js-register-form__errors" style="display:none;"></div>
                        <h1 class="heading-1">Register Account</h1>
                        <div class="register-form__group">
                            <label for="registerEmail" class="register-form__label">Email</label>
                            <input type="email" class="register-form__input" name="registerEmail" id="registerEmail" placeholder="sample@gmail.com" required>
                    </div>
                        <div class="register-form__group">
                            <label for="registerEmail" class="register-form__label">Password</label>
                            <input type="password" class="register-form__input" name="registerPassword" id="registerPassword" required>
                        </div>
                        <span class="register-form__text">Already have an account? <a href="login.php" class="register-form__text--link">Login</a></span>
                        <button class="register-form__btn">Register</button>
                    </form>
                </div>
            </div>
        </div>


<?php include_once "inc/footer.php"; ?>
