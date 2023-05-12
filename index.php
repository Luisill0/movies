<?php
    if(session_status() == PHP_SESSION_NONE) {session_start();}
    require_once('shared/DBClient.php');

    if(isset($_SESSION['current_user'])) {
        $my_uid = $_SESSION['current_user'];
    }

    $client = new DBClient('root', '');
    $popular_movies = $client->getPopularMovies();
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
            <img src="assets/search.svg" draggable="false" class="search-icon prevent-select"/>
        </div>
        <div class="headerChild homeBtn prevent-select"
            <?php
                if($my_uid){
                    echo "onclick=\"navigate('/movies/profile.php')\"";
                }else {
                    echo "onclick=\"navigate('/movies/login.php')\"";
                }
            ?>
        >
            <?php
                if($my_uid){
                    echo"Profile";
                }else {
                    echo"Sign In / Sign Up";
                }
            ?>
        </div>
    </div>
    <div class="content">
        <div class="popular-movies prevent-select">
            <span>Popular Movies</span>
        </div>
        <div class="movies-container">
            <div class="movie"
                <?php
                    if($popular_movies){
                        $poster_url=$popular_movies[0]['poster'];
                        echo"style=background-image:url('$poster_url');";
                    }
                ?>
            >
                <div class="info">
                    <?php
                        if($popular_movies){
                            $title=$popular_movies[0]['title'];
                            echo $title;
                        }
                    ?>
                </div>
            </div>
            <div class="movie"
                <?php
                    if($popular_movies){
                        $poster_url=$popular_movies[1]['poster'];
                        echo"style=background-image:url('$poster_url');";
                    }
                ?>
            >
                <div class="info">
                    <?php
                        if($popular_movies){
                            $title=$popular_movies[1]['title'];
                            echo $title;
                        }
                    ?>
                </div>
            </div>
            <div class="movie"
                <?php
                    if($popular_movies){
                        $poster_url=$popular_movies[2]['poster'];
                        echo"style=background-image:url('$poster_url');";
                    }
                ?>        
            >
                <div class="info">
                    <?php
                        if($popular_movies){
                            $title=$popular_movies[2]['title'];
                            echo $title;
                        }
                    ?>
                </div>
            </div>
            <div class="movie"
                <?php
                    if($popular_movies){
                        $poster_url=$popular_movies[3]['poster'];
                        echo"style=background-image:url('$poster_url');";
                    }
                ?> 
            >
                <div class="info">
                    <?php
                        if($popular_movies){
                            $title=$popular_movies[3]['title'];
                            echo $title;
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>