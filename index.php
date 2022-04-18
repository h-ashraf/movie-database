<?php
//initialise the session
session_start();

//check if the user is logged in, if not then redirect to login page
if(!issest($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang='en'>
    <head>
        <meta charset="UTF-8">
        <title>Welcome</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        
        <style>
            body{
                font: 14px sans-serif;
                text-align: center;
            }
        </style>
    </head>
    <body>
        <h1 class="my-5"> 
            Hi, <b><?php echo htmlspecialchars($_SESSION["username"]);?>. Welcome to the Revived Blockbuster!
            </h1>
            <p>
                <a href="reset-password.php" class = "btn btn-warning"> Reset Your Password</a>
                <a href="logout.php" class = "btn btn-danger ml-3"> Sign out of your account</a>
            </p>
    </body>
    
    <footer>
        <?php
        inlcude "footer.php";
        ?>
    </footer>
</html>
