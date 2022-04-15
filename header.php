<?php
    require_once "credentials.php";
    session_start();

    echo <<<_END

        <!DOCTYPE html>
        <html lang="en">
        <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Movie Database</title>
        //CSS
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link rel='stylesheet' id='stylesheet' href='css/stylesheet.css'"
        </head>
        
        <header>
            //Navbar
            <nav class="active">
                <li><a href="index.php">Home</a></li>
                <li><a href="#about">About</a></li>
            </nav>
            
            // add search bar in here
            
            
        </header>
    _END;
?>
