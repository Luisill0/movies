<?php
    if(session_status() == PHP_SESSION_NONE) {session_start();}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Movies</title>
    <script src="shared/script.js" type="text/javascript"></script>
    <link rel="stylesheet" href="shared/style.css" type="text/css"/>
    <link rel="stylesheet" href="styles/home.css" type="text/css"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>    
<body>
    <div class="header">
        <div class="headerChild searchbar">
            <input class="search-text" type="text" placeholder="search" required/> 
            <img src="assets/search.svg" class="search-icon"/>
        </div>
        <div class="headerChild homeBtn prevent-select">
            Sign In / Sign Up
        </div>
    </div>
    <div class="content">
        <div class="popular-movies prevent-select">
            <span>Popular Movies</span>
        </div>
        <div class="movies-container">
            <div class="movie">
                <span>MOVIE POSTER 1</span>
                <div class="info">
                    Movie Title (Year)
                </div>
            </div>
            <div class="movie">
                <span>MOVIE POSTER 2</span>
                <div class="info">
                    Movie Title (Year)
                </div>
            </div>
            <div class="movie">
                <span>MOVIE POSTER 3</span>
                <div class="info">
                    Movie Title (Year)
                </div>
            </div>
            <div class="movie">
                <span>MOVIE POSTER 4</span>
                <div class="info">
                    Movie Title (Year)
                </div>
            </div>
        </div>
    </div>
</body>
</html>