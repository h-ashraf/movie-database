<?php
    require_once "credentials.php";
    session_start();

    echo <<<_END
        <head>
            <title>Movie Database</title>
            <link rel='stylesheet' id='stylesheet' href='css/stylesheet.css'"
        </head>
    
        <body>

            <header>
                <h1>Movie Database</h1>
            </header>
    _END;
?>