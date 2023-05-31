<?php
    if(session_status() == PHP_SESSION_NONE) {session_start();}
    if(!isset($_SESSION['current_user'])) {header('Location: http://localhost/movies');}    
    require_once("shared/DBClient.php");

    $client = new DBClient('root', '');

    $uid = $_SESSION['current_user'];

    if(!$client->isAdmin($uid)) {header('Location: http://localhost/movies');}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Movies</title>
    <script src="shared/script.js" type="text/javascript"></script>
    <script src="scripts/admin.js" type="text/javascript"></script>
    <link rel="stylesheet" href="shared/style.css" type="text/css"/>
    <link rel="stylesheet" href="styles/admin.css" type="text/css"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta charset="utf-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</head>
<body>
    <div class="header">
        <div class="headerChild searchbar">
            <form id='search-form' action='/movies/search.php' method='get'>
                <input class="search-text" id='search-bar-input' 
                    type="text" placeholder="search" name='title'
                    required
                /> 
                <img src="assets/search.svg" draggable="false" 
                    class="search-icon prevent-select"
                    onclick="search()"
                />
            </form>
        </div>
        <div class="headerChild homeBtn prevent-select"
            onclick="navigate('/movies')"
        >
            Home
        </div>
    </div>
    <div class="container-fluid m-0 pt-2 px-3" id="admin-page-content">
        <span class='fs-1 fw-bold darkblue prevent-select' id='section-title'>
            Admin panel
        </span>
        <div 
            class="container-fluid d-flex flex-column justify-content-center align-items-center"
        >
            <button 
                class="btn mt-5 w-50 fs-3 admin-button"
                onclick="navigate('/movies/addmovies.php')"
            >
                Add Movie
            </button>
            <button 
                class="btn mt-5 w-50 fs-3 admin-button"
                onclick="navigate('/movies/listmovies.php')"
            >
                List All Movies
            </button>
        </div>
    </div>
</body>    
</html>