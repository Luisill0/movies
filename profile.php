<?php
    if(session_status() == PHP_SESSION_NONE) {session_start();}
    require_once('shared/DBClient.php');

    if(isset($_SESSION['current_user'])) {
        $my_uid = $_SESSION['current_user'];
    }

    $client = new DBClient(user:'root',passwd:'');
    if(isset($_GET['uid'])) {
        $profile_uid = $_GET['uid'];
        unset($_GET['uid']);
    }
    else if($my_uid){
        $profile_uid = $my_uid;
    } else {
        header('Location: http://localhost/movies');
    }
    
    $row = $client->getUserInfo($profile_uid);
    if($row) {
        $username = $row[0];
        $email = $row[1];
        $about = $row[2];
        $photo = $row[3];
        $favorite1 = $row[4];
        $favorite2 = $row[5];
        $favorite3 = $row[6];       
    }

    if($my_uid){
        $isAdmin = $client->isAdmin($my_uid);
    }

    try {
        if($favorite1){
            $movie1 = $client->getMovieByName($favorite1);
        }
        if($favorite2){
            $movie2 = $client->getMovieByName($favorite2);
        }
        if($favorite3){
            $movie3 = $client->getMovieByName($favorite3);
        }
    }catch(Exception $ex){
        print_r($ex);
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Profile</title>
    <script src="shared/script.js" type="text/javascript"></script>
    <link rel="stylesheet" href="shared/style.css" type="text/css"/>
    <link rel="stylesheet" href="styles/profile.css" type="text/css"/>
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
            <span>Profile</span>
            <?php
                if($profile_uid == $my_uid) {
                    echo '<a href="/movies/editprofile.php"><div class="editBtn">Edit</div></a>';
                }
            ?>
            <?php
                if($isAdmin) {
                    echo '<a href="/movies/admin.php"><div id="admin-btn" class="editBtn">Admin</div></a>';
                }
            ?>
            <?php
                if($profile_uid == $my_uid) {
                    echo '<a href="/movies/logout.php"><div id="logout-btn" class="editBtn">Log Out</div></a>';
                }
            ?>
        </div>
        <div class="sections">
            <div class="left">
                <?php
                    if($photo) {
                        echo "<img class='profile-photo prevent-select' draggable='false' src='$photo' />";
                    }else {
                        echo'<div class="profile-photo prevent-select">
                                Profile Photo
                            </div>';
                    }
                ?>
                <span class="favorite-films-section prevent-select">
                    Favorite Films:
                </span>
                <div class="favorite-films">
                    <div class="movie"
                        <?php
                            if($movie1){
                                $poster = $movie1['poster'];
                                echo "style='background-image: url($poster)' ";
                                $movie_id = $movie1['movie_id'];
                                echo "onclick=\"navigate('/movies/movie.php?id=$movie_id')\"";
                            }
                        ?>
                    >
                        <div class="info">
                            <?php 
                                echo $movie1 ?
                                    $movie1['title']."(".$movie1['year'].")" :
                                    "Movie Title (Year)";
                            ?>
                        </div>
                    </div>
                    <div class="movie"
                        <?php
                            if($movie2){
                                $poster = $movie2['poster'];
                                $movie_id = $movie2['movie_id'];
                                echo "style='background-image: url($poster)' ";
                                echo "onclick=\"navigate('/movies/movie.php?id=$movie_id')\"";
                            }
                        ?>
                    >
                        <div class="info">
                            <?php 
                                echo $movie2 ?
                                    $movie2['title']."(".$movie2['year'].")" :
                                    "Movie Title (Year)";
                            ?>
                        </div>
                    </div>
                    <div class="movie"
                        <?php
                            if($movie3){
                                $poster = $movie3['poster'];
                                $movie_id = $movie3['movie_id'];
                                echo "style='background-image: url($poster)' ";
                                echo "onclick=\"navigate('/movies/movie.php?id=$movie_id')\"";
                            }
                        ?>
                    >
                        <div class="info">
                            <?php 
                                echo $movie3 ?
                                    $movie3['title']."(".$movie3['year'].")" :
                                    "Movie Title (Year)";
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="right">
                <div class="info" id="username">
                    <span class="prevent-select">Username:</span>
                    <div class="text-box">
                        <?php 
                            if($username){
                                print_r($username);
                            }else {
                                echo "Username";
                            }
                        ?>
                    </div>
                </div>
                <div class="info" id="email">
                    <span class="prevent-select">email:</span>
                    <div class="text-box">
                        <?php 
                            if($email){
                                print_r($email);
                            }else {
                                echo "email";
                            }
                        ?>
                    </div>
                </div>
                <div class="info" id="about">
                    <span class="prevent-select">About:</span>
                    <div class="text-box">
                        <span class='fs-1' style="width: 100%;">
                            <?php
                                echo $about ? 
                                    $about 
                                    :
                                    "very quiet in here...";
                            ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>    
</html>