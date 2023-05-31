<?php
    if(session_status() == PHP_SESSION_NONE) {session_start();}
    require_once('shared/DBClient.php');

    if(isset($_SESSION['current_user'])) {
        $my_uid = $_SESSION['current_user'];
    }else {
        header('Location: /movies/login.php');
    }

    $client = new DBClient(user: 'root', passwd: '');

    if(isset($_GET['id'])) {
        $movie_id = $_GET['id'];
        unset($_GET['id']);
    }else {
        header('Location: /movies/');
    }

    if($movie_id) {
        $row = $client->getMovieById($movie_id);
        if($row) {
            $title=$row['title'];
            $poster=$row['poster'];
        }else {
            header('Location: /movies/');
        }
    }

    if($movie_id && $my_uid) {
        if($client->hasReviewed($my_uid, $movie_id)) {
            header('Location: /movies/movie.php?id='.$movie_id);
        }
    }

    if(
        isset($_POST['rating']) && isset($_POST['review']) && isset($_POST['date'])
    ) {
        $rating = $_POST['rating'];
        $review = $_POST['review'];
        $date = $_POST['date'];
        try {
            $res = $client -> reviewMovie(
                user_id: $my_uid, movie_id: $movie_id,
                rating: $rating, review: $review, date: $date
            );
            if($res) {
                echo "<script>alert('success!')</script>";
                header('Location: /movies/movie.php?id='.$movie_id);
            }else {
                echo "<script>alert('something went wrong')</script>";
            }
        }catch(Exception $ex) {
            echo "<script>alert('something went wrong')</script>";
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Movies</title>
    <script src="shared/script.js" type="text/javascript"></script>
    <link rel="stylesheet" href="shared/style.css" type="text/css"/>
    <link rel="stylesheet" href="styles/review.css" type="text/css"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
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
    <div class="content">
        <div class="title prevent-select">
            Review <?php echo"$title";?>
        </div>
        <div class="centered-container">
            <div class="poster"
                <?php
                    echo"style=\"background-image: url($poster)\"";
                ?>
            >
            </div>
            <form id='review-form' <?php echo"action=\"/movies/review.php?id=$movie_id\""?> method="post">
                <div class="review-section">
                    <label class="darkblue" for="my-review">My review:</label>
                    <textarea id="my-review" name="review" class="my-review" required></textarea>
                </div>
                <div class="form">
                    <span class="darkblue">My rating: </span>
                    <div class="rating">
                        <input type="text" placeholder="_" name='rating'
                            pattern="^(?:10|[0-9](?:\.[0-9])?)$"
                            title="Please enter a number between 0 and 10"
                            required
                        />
                        <span>/10</span>
                    </div>
                    <div class="watched-on">
                        <span>Watched on:</span>
                        <input id="date" type="date" name='date'
                            min="2020-01-01"
                            required
                        />
                    </div>
                    <input class="review-btn" type="submit" value="review"/>
                </div>
            </form>
        </div>
    </div>
</body>
</html>