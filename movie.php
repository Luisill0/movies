<?php
    if(session_status() == PHP_SESSION_NONE) {session_start();}
    require_once('shared/DBClient.php');

    if(isset($_SESSION['current_user'])) {
        $my_uid = $_SESSION['current_user'];
    }

    $client = new DBClient(user:'root',passwd:'');

    if(isset($_GET['movie'])) {
        $movie_title = ucwords($_GET['movie']);
        unset($_GET['movie']);
    }

    if($movie_title){
        echo $movie_title;
        $row = $client->getMovieByName($movie_title);
        if($row){
            $title=$row['title'];
            $poster=$row['poster'];
            $plot=$row['plot'];

            echo $title;
            echo $poster;
            echo $plot;
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Movies</title>
    <script src="shared/script.js" type="text/javascript"></script>
    <link rel="stylesheet" href="shared/style.css" type="text/css"/>
    <link rel="stylesheet" href="styles/movie.css" type="text/css"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body>
    <div class="header">
        <div class="headerChild searchbar">
            <input class="search-text" type="text" placeholder="search" required/> 
            <img src="assets/search.svg" class="search-icon"/>
        </div>
        <div class="headerChild homeBtn prevent-select"
            onclick="navigate('/movies')"
        >
            Home
        </div>
    </div>
    <div class="content">
        <div class="title prevent-select">
            Film Title(Year)
        </div>
        <div class="centered-container">
            <div class="poster">
                MOVIE POSTER
            </div>
            <div class="plot">
                Plot of the film
            </div>
            <div class="stats">
                <div class="btn stat reviews-number">
                    Number of reviews
                </div>
                <div class="btn stat rating">
                    Rating/10
                </div>
                <div class="btn stat reviewBtn">
                    Review
                </div>
                <div class="btn stat shareBtn">
                    Share
                </div>
            </div>
        </div>
        <div class="reviews-title">
            Reviews:
        </div>
        <div class="reviews-section">
            <div class="review"
            onclick="navigate('/review')"
            >
                <span>Username:</span>
                <p>Review</p>
            </div>
            <div class="review">
                <span>Username:</span>
                <p>Review</p>
            </div>
        </div>
    </div>
</body>
</html>