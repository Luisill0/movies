<?php
    if(session_status() == PHP_SESSION_NONE) {session_start();}
    require_once('shared/DBClient.php');

    if(isset($_SESSION['current_user'])) {
        $my_uid = $_SESSION['current_user'];
    }

    $client = new DBClient(user:'root',passwd:'');

    if(isset($_GET['id'])) {
        $movie_id = $_GET['id'];
        unset($_GET['id']);
    }else {
        header('Location: /movies/');
    }

    if($my_uid) {
        $im_admin = $client->isAdmin($my_uid);
    }

    if($movie_id){
        $row = $client->getMovieById($movie_id);
        if($row) {
            $title=$row['title'];
            $year=$row['year'];
            $poster=$row['poster'];
            $plot=$row['plot'];
        }else {
            header('Location: /movies/');
        }

        $reviews = $client->getReviews($movie_id);
    }

    if($my_uid && $movie_id) {
        $has_reviewed = $client->hasReviewed($my_uid, $movie_id);
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
            <?php
                echo "$title ($year)";
                if($im_admin) {
                    echo
                    "
                        <button 
                            class='btn' id='button-edit'
                            onclick=
                            \"
                                window.location.href='/movies/editmovie.php?id=$movie_id'
                            \"
                        >
                            Edit
                        </button>
                    ";
                    echo "
                        <button 
                            class='btn' id='button-delete'
                            onclick=
                            \"
                                if(window.confirm('Do you really want to delete this movie?')) {
                                    window.location.href='/movies/deletemovie.php?id=$movie_id'
                                }
                            \"
                        >
                            Delete
                        </button>
                    ";
                }
            ?>
        </div>
        <div class="centered-container">
            <div class="poster"
                <?php
                    echo"style=\"background-image: url($poster)\""
                ?>
            >
            </div>
            <div class="plot">
                <?php
                    echo "<span id='plot-text'>$plot</span>";
                ?>
            </div>
            <div class="stats prevent-select">
                <div class="btn stat reviews-number">
                    <?php 
                        $numReviews = $reviews ? count($reviews) : 0;
                        $s = $numReviews == 1 ? '' : 's';
                        echo "$numReviews review".$s;
                    ?>
                </div>
                <div class="btn stat rating">
                    <?php
                        $sum = 0;
                        $count = 0;
                        foreach($reviews as $review) {
                            $sum = $sum + $review['rating'];
                            $count = $count + 1;
                        }
                        $avg = $count > 0 ? $sum / $count : 10;
                        echo number_format((float)$avg, 2)."/10";
                    ?>
                </div>
                <?php
                    if(!$has_reviewed){
                        echo
                        "
                            <div class='btn stat reviewBtn'
                                onclick=\"navigate('review.php?id=$movie_id')\"
                            >
                                Review
                            </div>
                        ";
                    }else {
                        echo
                        "
                        <div class='stat reviewBtn disabled'>
                            Review
                        </div>
                        ";
                    }
                ?>
                <div class="btn stat shareBtn">
                    Share
                </div>
            </div>
        </div>
        <div class="reviews-title prevent-select">
            Reviews:
        </div>
        <div class="reviews-section">
            <?php
                if($reviews) {
                    foreach($reviews as $review) {
                        $user_id = $review['user_id'];
                        $username = $client->getUserName($user_id);
                        $review_text = $review['review'];
                        $rating = $review['rating'];
                        echo
                        "
                            <div class='review'>
                                <a href=/movies/profile.php?uid=$user_id>
                                    <span id='username'>
                                        $username $rating/10
                                    </span>
                                </a>
                                <br>
                                <span>
                                    $review_text
                                </span>
                            </div>
                        ";
                    }
                }
            ?>
        </div>
    </div>
</body>
</html>