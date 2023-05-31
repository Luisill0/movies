<?php
    if(session_status() == PHP_SESSION_NONE) {session_start();}
    require_once('shared/DBClient.php');

    if(isset($_SESSION['current_user'])) {
        $my_uid = $_SESSION['current_user'];
    }

    if(isset($_GET['title'])) {
        $movie_title = $_GET['title'];
        unset($_GET['title']);

        $client = new DBClient(user: 'root', passwd:'');
        $movie = $client->getMovieByName($movie_title);
        if($movie) {
            $movie_id = $movie['movie_id'];
            header("Location: /movies/movie.php?id=$movie_id");
        }else {
            header("Location: /movies");
        }
    }else {
        header("Location: /movies");
    }
?>