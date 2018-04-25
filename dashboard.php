<?php
    // define the __CONFIG__ to allow the config.php file
    define('__CONFIG__', true);
    require_once "inc/config.php";

    Page::ForceLogin();

    $User = new User($_SESSION['user_id']);
    
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
        
        <title>Dashboard - PHP MySQL Login System</title>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-1-of-1">
                    <h1 class="heading-1">Today is: <?php echo date("Y/m/d"); ?></h1>
                    <h2 class="heading-2"><?php echo $User->user_email ." - registered at " . $User->user_reg_time; ?> </h2>
                    <nav class="navigation">
                        <a href="logout.php" class="navigation__link">Logout</a>
                    </nav>
                </div>
            </div>
        </div>


<?php include_once "inc/footer.php"; ?>
