<?php
    if(session_status() == PHP_SESSION_NONE) {session_start();}
    require_once('shared/DBClient.php');

    if(isset($_SESSION['current_user'])) {
        $my_uid = $_SESSION['current_user'];
    }else {
        header('Location: http://localhost/movies');
    }

    $client = new DBClient('root', '');
        
    if(!$client->isAdmin($my_uid)) {header('Location: http://localhost/movies');}

    $movie_list = $client->getAllMovies();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Movies</title>
    <script src="shared/script.js" type="text/javascript"></script>
    <link rel="stylesheet" href="shared/style.css" type="text/css"/>
    <link rel="stylesheet" href="styles/listmovies.css" type="text/css"/>
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
    <div class="container-fluid m-0 pt-2 px-3">
        <span class='fs-1 fw-bold darkblue prevent-select' id='section-title'>
            Movie catalogue
        </span>
        <div class="movies-container">
            <?php
                if($movie_list) {
                    foreach($movie_list as $movie) {
                        $poster = $movie['poster'];
                        $movie_id = $movie['movie_id'];
                        $title = $movie['title'];
                        $year = $movie['year'];
                        $info = "$title ($year)";
                        echo
                        "
                            <div
                                class='movie'
                                style=background-image:url('$poster')
                                onclick=\"navigate('/movies/movie.php?id=$movie_id')\"
                            >
                                <div class='info'>
                                    $info
                                </div>
                            </div>
                        ";
                    }
                }
            ?>
        </div>
    </div>
</body>
</html>