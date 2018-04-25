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
                    <form action="#" class="login-form js-login-form">
                        <div class="login-form__errors js-login-form__errors" style="display:none;"></div>
                        <h1 class="heading-1">Login</h1>
                        <div class="login-form__group">
                            <label for="registerEmail" class="login-form__label">Email</label>
                            <input type="email" class="login-form__input" name="loginEmail" id="loginEmail" placeholder="sample@gmail.com" required>
                    </div>
                        <div class="login-form__group">
                            <label for="loginEmail" class="login-form__label">Password</label>
                            <input type="password" class="login-form__input" name="loginPassword" id="loginPassword" required>
                        </div>
                        <span class="login-form__text">Don't have an account yet? <a href="register.php" class="login-form__text--link">Register</a></span>
                        <button class="login-form__btn">Login</button>
                    </form>
                </div>
            </div>
        </div>


<?php include_once "inc/footer.php"; ?>
