<?php
    if(session_status() == PHP_SESSION_NONE) {session_start();}
    if(!isset($_SESSION['current_user'])) {header('Location: http://localhost/movies');}    
    require_once("shared/DBClient.php");

    $client = new DBClient('root', '');

    $uid = $_SESSION['current_user'];

    if(!$client->isAdmin($uid)) {header('Location: http://localhost/movies');}

    if(isset($_POST['title-year']) && isset($_POST['plot']) && isset($_POST['poster-url'])) {
        $title = $_POST['title-year'];
        $plot = $_POST['plot'];         
        $poster = $_POST['poster-url'];

        unset($_POST['title-year']);
        unset($_POST['plot']);
        unset($_POST['poster-url']);
        
        try {
            $res = $client->addMovie($title, $plot, $poster);
        }catch(Exception $ex){
            echo"<script>alert('something went wrong')</script>";
        }

        if($res){
            echo"<script>alert('success!')</script>";
        }else {
            echo"<script>alert('something went wrong')</script>";
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Movies</title>
    <script src="shared/script.js" type="text/javascript"></script>
    <link rel="stylesheet" href="shared/style.css" type="text/css"/>
    <link rel="stylesheet" href="styles/admin.css" type="text/css"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css' rel='stylesheet' integrity='sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ' crossorigin='anonymous'>
    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js' integrity='sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe' crossorigin='anonymous'></script>
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
    <div class="container-fluid m-0 pt-2 px-3" id='admin-page-content'>
        <div class='row mb-4 px-2'>
            <span class='fs-1 fw-bold darkblue prevent-select' id='section-title'>
                Admin panel
            </span>
            <div class='container-fluid px-5'>
                <span class='fs-2 fw-bold darkblue prevent-select' id='section-title'>
                    Add New Movie
                </span>
                <form action='admin.php' method='post'>
                    <div class='container-fluid my-3 p-0'>
                        <label for='title-year'
                            class='form-label prevent-select darkblue'
                        >
                            Title (Year):
                        </label>
                        <input 
                            class='form-control text-box no-focus'
                            type='text' id='title-year' name='title-year'
                            requred aria-required='true'
                        />
                    </div>
                    <div class='container-fluid mb-3 p-0'>
                        <label for='plot'
                            class='form-label prevent-select darkblue'
                        >
                            Plot:
                        </label>
                        <textarea 
                            class='form-control text-box no-focus'
                            id='plot' name='plot'
                            requred aria-required='true'
                        ></textarea>
                    </div>
                    <div class='container-fluid mb-3 p-0'>
                        <label for='poster-url'
                            class='form-label prevent-select darkblue'
                        >
                            Poster URL
                        </label>
                        <input 
                            class='form-control text-box no-focus'
                            type='url' id='poster-url' name='poster-url'
                            requred aria-required='true'
                        />
                    </div>
                    <button type="submit" class="btn btn-primary mb-2 px-3 py-2" id='btn-submit'>
                        Add Movie
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>